<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Helpers\Accounting\FunctionCore;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Finance\Entities\Group;
use Modules\Finance\Entities\FinancialAccount;
use Modules\Finance\Entities\Ledger;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Finance\Entities\EntriesType;
use Modules\Finance\Entities\Entries;
use Modules\Finance\Entities\EntriesItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helpers\Accounting\LagerTree;
use Illuminate\Support\Facades\Validator;

class EntriesController extends Controller
{


    private  $account;
    private  $group;
    private  $ledger;
    private  $entriesType;
    private  $academicHelper;
    private  $entries;
    private  $entriesItem;
    var $data=array();

    public function __construct(AcademicHelper $academicHelper, EntriesItem $entriesItem, FinancialAccount $account, Group $group, Ledger $ledger, EntriesType $entriesType, Entries $entries)
    {
        $this->account=$account;
        $this->group=$group;
        $this->ledger=$ledger;
        $this->entriesType=$entriesType;
        $this->academicHelper=$academicHelper;
        $this->entries=$entries;
        $this->entriesItem=$entriesItem;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function createEntries($entryType)
    {
        return view('finance::pages.entries.add',compact('entryType'));
    }

    public function addEntries($entrytypeLabel = null) {


        // Select from entrytypes table in Db where label = $entrytypeLabel
        $data=array();

        $entrytype =DB::table('finance_entries_type')->where('label',$entrytypeLabel)->first();
        $data['entrytype'] = $entrytype; // pass entrytype array to view page
        // pass page title to view page
        $data['title'] = $entrytype->name;
        // pass tag_options array to view page
//        $data['tag_options'] = DB::table('finance_entries_type')->select('id, title')->get('tags')->result_array();

        /* Ledger selection */
        $ledgers = new LagerTree(); // initilize ledgers array - LedgerTree Lib
        $ledgers->Group = &$this->Group; // initilize selected ledger groups in ledgers array
        $ledgers->Ledger = &$this->Ledger; // initilize selected ledgers in ledgers array
        $ledgers->current_id = -1; // initilize current group id
        // set restriction_bankcash from entrytype
        $ledgers->restriction_bankcash = $entrytype->restriction_bankcash;
        $ledgers->build(0); // set ledger id to [NULL] and ledger name to [None]
        $ledgers->toList($ledgers, -1); // create a list of ledgers array
        $data['ledger_options'] = $ledgers->ledgerList; // pass ledger list to view page


        $curEntryitems = array(); // initilize current entry items array

        /* Special case if atleast one Bank or Cash on credit side (3) then 1st item is Credit */
        if ($entrytype->restriction_bankcash == 3){
            $curEntryitems[0] = array('dc' => 'C');
            $curEntryitems[1] = array('dc' => 'D');
        }else /* else 1st item is Debit */
        {
            $curEntryitems[0] = array('dc' => 'D');
            $curEntryitems[1] = array('dc' => 'C');
        }

        // created debit and credit array
        $dc_options = array(
            'D' => 'Dr',
            'C' => 'Cr',
        );

        // pass current entry items array to view page
        $data['curEntryitems'] = $curEntryitems;
        $data['dc_options'] = $dc_options;
//        var_dump($data);
//        exit();

        return view('finance::pages.entries.add',$data);
    }



    /**
     * closing balance method
     *
     * Return closing balance for the ledger
     *
     * @return void
     */
    public function cl($id = null) {

        /* Read ledger id from url get request */
        if ($id == null) {
            $id = (int)$this->input->get('id');
        }

        $ledger = DB::table('finance_ledger')->where('id',$id)->first();
        if (!$ledger) {
            $cl = array('cl' => array('dc' => '', 'amount' => ''));
        }else{
            $cl = $this->ledger->closingBalance($id);
            $status = 'ok';
            if ($ledger->type == 1) {
                if ($cl['dc'] == 'C') {
                    $status = 'neg';
                }
            }

            /* Return closing balance */
            $cl = array('cl' =>
                array(
                    'dc' => $cl['dc'],
                    'amount' => $cl['amount'],
                    'status' => $status,
                )
            );
        }
        return  json_encode($cl);
    }


    // add row entries function here

    public function addrow($restriction_bankcash) {

        // $this->layout = null;
        $data=array();
        /* Ledger selection */
        $ledgers = new LagerTree(); // initilize ledgers array - LedgerTree Lib
        $ledgers->Group = &$this->Group; // initilize selected ledger groups in ledgers array
        $ledgers->Ledger = &$this->Ledger; // initilize selected ledgers in ledgers array
        $ledgers->current_id = -1; // initilize current group id
        // set restriction_bankcash from entrytype
        $ledgers->restriction_bankcash = $restriction_bankcash;
        $ledgers->build(0); // set ledger id to [NULL] and ledger name to [None]
        $ledgers->toList($ledgers, -1); // create a list of ledgers array
        $data['ledger_options'] = $ledgers->ledgerList; // pass ledger list to view
        return view('finance::pages.entries.addrow',$data);
    }


    // entries store function

    public function entriesStore(Request $request){
//        return $request->all();

        // function core obje
        $functionCore=new FunctionCore;
//        return $request->entrytype_id;

        // Select from entrytypes table in DB1 where label = $entrytypeLabel
        $entrytype = DB::table('finance_entries_type')->where('id',$request->entrytype_id)->first();

        $dc_valid = false; 	// valid debit or credit ledger
        $dr_total = 0;		// total dr amount initially 0
        $cr_total = 0;		// total cr amount initially 0

        // loop for all $_POST['Entryitem']
        foreach ($request->Entryitem as $key => $value)
        {
            // check if $value['ledger_id'] less then or equal to 0
            if ($value['ledger_id'] <= 0)
            {
                // continue to next Entryitem
                continue;
            }

            // array of selected ledger
            $ledger = $this->ledger->where('id', $value['ledger_id'])->first();

            // check if $ledger is Empty
            if (!$ledger)
            {
                // set form validation for Entryitem to be required with error alert
                return 'ledger not found';
            }
            // check if Only Bank or Cash account is present on both Debit and Credit side
            if ($entrytype->restriction_bankcash == 4)
            {
                // check if ledger is [NOT] a Bank or Cash Account
                if ($ledger->type != 1) {
                    return 'ledger not a bank';
                }
            }
            // check if Only NON Bank or Cash account is present on both Debit and Credit side
            if ($entrytype->restriction_bankcash == 5)
            {
                // check if ledger is a Bank or Cash Account
                if ($ledger->type == 1) {
                    return 'Bank or Cash Account';
                }
            }

            // check if ledger is Debit
            if ($value['dc'] == 'D')
            {
                // check if Atleast one Bank or Cash account must be present on Debit side
                if ($entrytype->restriction_bankcash == 2)
                {
                    // check if ledger is a Bank or Cash Account
                    if ($ledger->type == 1)
                    {
                        // set dc_valid = true
                        $dc_valid = true;
                    }
                }
            } else if ($value['dc'] == 'C') // check if ledger is Credit
            {
                // check if Atleast 1 Bank or Cash account is present on Credit side
                if ($entrytype->restriction_bankcash == 3)
                {
                    // check if ledger is Bank or Cash Account
                    if ($ledger['type'] == 1)
                    {
                        // set dc_valid = true
                        $dc_valid = true;
                    }
                }
            }

            // some more form validation rules
            // var_dump($value);
            // exit();

            // if Debit selected
            if ($value['dc'] == 'D')
            {
                // if dr_amount not empty
                if (!empty($value['dr_amount']))
                {
                    // set form validation rules form dr_amount

                    // calculate total debit amount
                    $dr_total =$functionCore->calculate($dr_total, $value['dr_amount'], '+');
                }
            }else // if credit selected
            {
                // if cr_amount if not empty
                if (!empty($value['cr_amount']))
                {
                    // set form validation rules form cr_amount
                    // calculate total credit amount
                    $cr_total =$functionCore->calculate($cr_total, $value['cr_amount'], '+');
                }
            }
        }

        // check if total dr or cr amount is not equal
        if ($functionCore->calculate($dr_total, $cr_total, '!='))
        {
            // set form validation error
        }
        // check if restriction_bankcash = 2
        if ($entrytype->restriction_bankcash == 2)
        {
            // check if Atleast one Bank or Cash account is present on Debit side
            if (!$dc_valid)
            {
                // set form validation error
            }
        }

        // check if Atleast one Bank or Cash account is present on Credit side
        if ($entrytype->restriction_bankcash == 3)
        {
            // check if no Bank or Cash account is present on Credit side
            if (!$dc_valid) {
                // set form validation error
            }
        }

        /***** Check if entry type numbering is auto ******/
        if ($entrytype->numbering == 1)
        {
            /* check if $_POST['number'] is empty */
            if (empty($request->number))
            {
                // set entry number to next entry number
                $number = $this->entries->nextNumber($entrytype->id);
            }else // if not empty
            {
                // set entry number to $_POST['number']
                $number =$request->number;
            }
        }else if ($entrytype->numbering == 2) // Check if entry type numbering is manual and required
        {
            /* Manual + Required - Check if $_POST['number'] is empty */
            if (empty($number =$request->number))
            {
                //  set form validation rule

            } else // if not empty
            {
                // set entry number to $_POST['number']
                $number =$request->number;
            }
        } else // if entry type numbering is manual and not required
        {
            /* Manual + Optional - set entry number to $_POST['number'] */
            $number =$request->number;
        }



        // if for validaiton here
        /***************************************************************************/
        /*********************************** ENTRY *********************************/
        /***************************************************************************/
        $entrydata = null; // create entry data array to insert in [entries] table - DB1
        $entrydata['Entry']['number'] = $request->number; // set entry number in entry data array
        $entrydata['Entry']['entrytype_id'] = $entrytype->id; // set entrytype_id in entry data array

        // check if $_POST['tag_id'] is empty
        if (empty($request->tag_id))
        {
            // set entry tag id in entry data array to [NULL]
            $entrydata['Entry']['tag_id'] = null;

        }else // if $_POST['tag_id'] is NOT empty
        {
            // set entry tag id in entry data array to $_POST['tag_id']
            $entrydata['Entry']['tag_id'] =$request->tag_id;
        }

        /***** Check if $_POST['notes'] is empty *****/
        if (empty($request->notes))
        {
            // set entry note in entry data array to [NULL]
            $entrydata['Entry']['notes'] = '';
        }else // if NOT empty
        {
            // set entry note in entry data array to $_POST['notes']
            $entrydata['Entry']['notes'] = $request->notes;
        }

        /***** Set entry date to $_POST['date'] after converting to sql format(dateToSql function) *****/
        $entrydata['Entry']['date'] = date('Y-m-d',strtotime($request->date));

        /***************************************************************************/
        /***************************** ENTRY ITEMS *********************************/
        /***************************************************************************/
        /* Check ledger restriction */

        $entrydata['Entry']['dr_total'] = $dr_total; // set entry dr_total in entry data array as $dr_total
        $entrydata['Entry']['cr_total'] = $cr_total; // set entry cr_total in entry data array as $cr_total

        /* Add item to entry item data array if everything is ok */
        $entryitemdata = array(); // create entry items data array to insert in [entryitems] table - DB1

        // loop for all Entryitems from post data
        foreach ($request->Entryitem as $row => $entryitem)
        {
            // check if $entryitem['ledger_id'] less then or equal to 0
            if ($entryitem['ledger_id'] <= 0)
            {
                // continue to next entryitem
                continue;
            }

            // if entryitem is debit
            if ($entryitem['dc'] == 'D')
            {
                // save entry item data array with dr_amount
                $entryitemdata[] = array(
                    'Entryitem' => array(
                        'dc' => $entryitem['dc'],
                        'ledger_id' => $entryitem['ledger_id'],
                        'amount' => $entryitem['dr_amount'],
                        'narration' => $entryitem['narration']
                    )
                );
            }else // if entrytype is credit
            {
                // save entry item data array with cr_amount
                $entryitemdata[] = array(
                    'Entryitem' => array(
                        'dc' => $entryitem['dc'],
                        'ledger_id' => $entryitem['ledger_id'],
                        'amount' => $entryitem['cr_amount'],
                        'narration' => $entryitem['narration']

                    )
                );
            }
        }



        // entries object
        /* insert entry data array to entries table - DB1 */
        $entriesObj=new $this->entries;
        $entriesObj->account_id= $this->account->getActiveAccount();
        $entriesObj->tag_id=$entrydata['Entry']['tag_id'];
        $entriesObj->number=$entrydata['Entry']['number'];
        $entriesObj->entrytype_id=$entrydata['Entry']['entrytype_id'];
        $entriesObj->date=$entrydata['Entry']['date'];
        $entriesObj->dr_total=$entrydata['Entry']['dr_total'];
        $entriesObj->cr_total=$entrydata['Entry']['cr_total'];
        $entriesObj->notes=$entrydata['Entry']['notes'];
        $saveEntries=$entriesObj->save();


        // if entry data is inserted
        if ($saveEntries)
        {
            $insert_id = $entriesObj->id; // get inserted entry id

            // loop for inserting entry item data array to [entryitems] table - DB1
            foreach ($entryitemdata as $row => $itemdata)
            {
                //create and etries  item object
                $entriesItemObj=new  $this->entriesItem;
                $entriesItemObj->entries_id=$entriesObj->id;
                $entriesItemObj->ledger_id=$itemdata['Entryitem']['ledger_id'];
                $entriesItemObj->dc=$itemdata['Entryitem']['dc'];
                $entriesItemObj->amount=$itemdata['Entryitem']['amount'];
                $entriesItemObj->narration=$itemdata['Entryitem']['narration'];
                $entriesItemObj->save();
                // insert item data to entryitems table - DB1
            }

            // set entry number as per prefix, suffix and zero padding for that entry type for logging
//                $entryNumber = $functionCore->toEntryNumber($entrydata['Entry']['number'], $entrytype->id);

            // set success alert message

            // redirect to index page
        }else
        {
            // set error alert message
//                $this->session->set_flashdata('error', lang('entries_cntrler_add_entry_not_created_error'));
        }

        return 'success';


    }







    /**
     * Show the specified resource.
     * @return Response
     */
    public function entriesList()
    {
        // get active account id
        $account_id=$this->account->getActiveAccount();

        // get all entries
        $entries=$this->entries->where('account_id',$account_id)->paginate(20);
        // get all entries  type
        $entriesTypeList=$this->entriesType->where('account_id',$account_id)->get();


        return view('finance::pages.entries.index',compact('entries','entriesTypeList'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function viewEntries($id = null) {


        /* Check if valid id */
        if (empty($id))
        {
            // set error alert
            $this->session->set_flashdata('error', lang('entries_cntrler_edit_entry_not_found_error'));
            // redirect to index page
            redirect('entries/index');
        }

        // select entry where id equals $id and store to array
        $entry =DB::table('finance_entries')->where('id',$id)->get();

        /* if entry [NOT] found */
        if (!$entry)
        {
            // set error alert
            $this->session->set_flashdata('error', lang('entries_cntrler_edit_entry_not_found_error'));
            // redirect to index page
            redirect('entries/index');
        }


        /* Initial data */
        $curEntryitems = array(); // initilize current entry items array
        // store selected data to $curEntryitemsData
        $curEntryitemsData =DB::table('finance_entries_item')->where('entries_id',$id)->get();

        // loop to store selected entry items to current entry items array
        foreach ($curEntryitemsData as $row => $data)
        {
            // if debit entry
            if ($data->dc == 'D')
            {
                $curEntryitems[$row] = array
                (
                    'dc' => $data->dc,
                    'ledger_id' => $data->ledger_id,
                    'ledger_name' => $this->ledger->getName($data->ledger_id),
                    'dr_amount' => $data->amount,
                    'cr_amount' => '',
                    'narration' => $data->narration
                );
            }else // if credit entry
            {
                $curEntryitems[$row] = array
                (
                    'dc' => $data->dc,
                    'ledger_id' => $data->ledger_id,
                    'ledger_name' => $this->ledger->getName($data->ledger_id),
                    'dr_amount' => '',
                    'cr_amount' =>$data->amount,
                    'narration' => $data->narration

                );
            }
        }

        $this->data['curEntryitems'] = $curEntryitems; // pass current entry items to view
//        $this->data['allTags'] = $this->DB1->get('tags')->result_array(); // fetch all tags and pass to view
         $this->data['entry'] = $entry; // pass entry to view


//        var_dump($this->data);
//        exit();

        // render page
        return view('finance::pages.entries.view',$this->data);
    }




    public function  viewEntriesPrint($id){


        $this->data['instituteInfo']=$this->academicHelper->getInstituteProfile();

        // select entry where id equals $id and store to array
        $entry =DB::table('finance_entries')->where('id',$id)->get();

        /* if entry [NOT] found */
        if (!$entry)
        {
            // set error alert
            $this->session->set_flashdata('error', lang('entries_cntrler_edit_entry_not_found_error'));
            // redirect to index page
            redirect('entries/index');
        }


        /* Initial data */
        $curEntryitems = array(); // initilize current entry items array
        // store selected data to $curEntryitemsData
        $curEntryitemsData =DB::table('finance_entries_item')->where('entries_id',$id)->get();

        // loop to store selected entry items to current entry items array
        foreach ($curEntryitemsData as $row => $data)
        {
            // if debit entry
            if ($data->dc == 'D')
            {
                $curEntryitems[$row] = array
                (
                    'dc' => $data->dc,
                    'ledger_id' => $data->ledger_id,
                    'ledger_name' => $this->ledger->getName($data->ledger_id),
                    'dr_amount' => $data->amount,
                    'cr_amount' => '',
                    'narration' => $data->narration
                );
            }else // if credit entry
            {
                $curEntryitems[$row] = array
                (
                    'dc' => $data->dc,
                    'ledger_id' => $data->ledger_id,
                    'ledger_name' => $this->ledger->getName($data->ledger_id),
                    'dr_amount' => '',
                    'cr_amount' =>$data->amount,
                    'narration' => $data->narration

                );
            }
        }

        $this->data['curEntryitems'] = $curEntryitems; // pass current entry items to view
//        $this->data['allTags'] = $this->DB1->get('tags')->result_array(); // fetch all tags and pass to view
        $this->data['entry'] = $entry; // pass entry to view

        return view('finance::pages.entries.print-view',$this->data);
    }



    public function editntries($entrytypeLabel = null, $id = null )
    {
        // select data from entries table where id equals $id(passed id to edit function) and create array
        $entry =DB::table('finance_entries')->where('id', $id)->first();

        $entrytype = DB::table('finance_entries_type')->where('label',$entrytypeLabel)->first();
        $this->data['entrytype'] = $entrytype; // pass entryt   ype array to view page
        // pass tag_options array to view page
        $this->data['tag_options'] =null;

        /* Ledger selection */
        $ledgers = new LagerTree(); // initilize ledgers array - LedgerTree Lib
        $ledgers->Group = &$this->Group; // initilize selected ledger groups in ledgers array
        $ledgers->Ledger = &$this->Ledger; // initilize selected ledgers in ledgers array
        $ledgers->current_id = -1; // initilize current group id
        // set restriction_bankcash from entrytype
        $ledgers->restriction_bankcash = $entrytype->restriction_bankcash;
        $ledgers->build(0); // set ledger id to [NULL] and ledger name to [None]
        $ledgers->toList($ledgers, -1);	// create a list of ledgers array
        $this->data['ledger_options'] = $ledgers->ledgerList; // pass ledger list to view page

        $curEntryitems = array(); // initilize current entry items array

        // get entry items where entry_id equals $id(passed id to edit function) and store to [curEntryitemsData] array
        $curEntryitemsData = DB::table('finance_entries_item')->where('entries_id',$id)->get();
        // loop for storing current entry items in current entry items array
        foreach ($curEntryitemsData as $row => $data)
        {
            // if entry item is debit
            if ($data->dc == 'D')
            {
                $curEntryitems[$row] = array
                (
                    'dc' => $data->dc,
                    'ledger_id' => $data->ledger_id,
                    'dr_amount' => $data->amount,
                    'cr_amount' => '',
                    'narration' => $data->narration,
                );
            }else // if entry item is credit
            {
                $curEntryitems[$row] = array
                (
                    'dc' => $data->dc,
                    'ledger_id' => $data->ledger_id,
                    'dr_amount' => '',
                    'cr_amount' => $data->amount,
                    'narration' => $data->narration,
                );
            }
        }

        // pass current entry items array to view page
        $this->data['curEntryitems'] = $curEntryitems;
        // pass entry array to view page
        $this->data['entry'] = $entry;
//        return $entry->id;

        return view('finance::pages.entries.edit',$this->data);

    }


    public function  entriestUpdateStore(Request $request){
//        return $request->all();

        $dc_valid = false; 	// valid debit or credit ledger
        $dr_total = 0;		// total dr amount initially 0
        $cr_total = 0;		// total cr amount initially 0

        // function obj
        $functionCore=new FunctionCore;

        // entries type
        $entrytype=$this->entriesType->where('id',$request->entry_type_id)->first();

        if (isset($request->Entryitem) && is_array($request->Entryitem))
        {
            // loop for all $_POST['Entryitem']
            foreach ($request->Entryitem as $key => $value)
            {
                // check if $value['ledger_id'] less then or equal to 0
                if ($value['ledger_id'] <= 0)
                {
                    // continue to next Entryitem
                    continue;
                }

                // array of selected ledger
                $ledger =DB::table('finance_ledger')->where('id',$value['ledger_id'])->first();

                // check if $ledger is Empty
                if (!$ledger)
                {
                    // set form validation for Entryitem to be required with error alert
                    $this->form_validation->set_rules('Entryitem', '', 'required', array('required' => lang('entries_cntrler_invalid_ledger_form_validation_alert')));
                }
                // check if Only Bank or Cash account is present on both Debit and Credit side
                if ($entrytype->restriction_bankcash == 4)
                {
                    // check if ledger is [NOT] a Bank or Cash Account
//                    if ($ledger['type'] != 1) {
//                        $this->form_validation->set_rules('Entryitem', '', 'required', array('required' => lang('entries_cntrler_restriction_bankcash_4_form_validation_alert')));
//                    }
                }
                // check if Only NON Bank or Cash account is present on both Debit and Credit side
                if ($entrytype->restriction_bankcash == 5)
                {
                    // check if ledger is a Bank or Cash Account
//                    if ($ledger['type'] == 1) {
//                        $this->form_validation->set_rules('Entryitem', '', 'required', array('required' => lang('entries_cntrler_restriction_bankcash_5_form_validation_alert')));
//                    }
                }

                // check if ledger is Debit
                if ($value['dc'] == 'D')
                {
                    // check if Atleast one Bank or Cash account must be present on Debit side
                    if ($entrytype->restriction_bankcash == 2)
                    {
                        // check if ledger is a Bank or Cash Account
                        if ($ledger->type == 1)
                        {
                            // set dc_valid = true
                            $dc_valid = true;
                        }
                    }
                } else if ($value['dc'] == 'C') // check if ledger is Credit
                {
                    // check if Atleast 1 Bank or Cash account is present on Credit side
                    if ($entrytype->restriction_bankcash == 3)
                    {
                        // check if ledger is Bank or Cash Account
                        if ($ledger->type == 1)
                        {
                            // set dc_valid = true
                            $dc_valid = true;
                        }
                    }
                }

                // var_dump($value);
                // exit();

                // if Debit selected
                if ($value['dc'] == 'D')
                {
                    // if dr_amount not empty
                    if (!empty($value['dr_amount']))
                    {
                        // set form validation rules form dr_amount
//                        $this->form_validation->set_rules('Entryitem['.$key.'][dr_amount]', '', "greater_than[0]|amount_okay[$allowed]");

                        // calculate total debit amount
                        $dr_total =$functionCore->calculate($dr_total, $value['dr_amount'], '+');
                    }
                }else // if credit selected
                {
                    // if cr_amount if not empty
                    if (!empty($value['cr_amount']))
                    {
                        // set form validation rules form cr_amount
//                        $this->form_validation->set_rules('Entryitem['.$key.'][cr_amount]', '', "greater_than[0]|amount_okay[$allowed]");

                        // calculate total credit amount
                        $cr_total =$functionCore->calculate($cr_total, $value['cr_amount'], '+');
                    }
                }
            }

            // check if total dr or cr amount is not equal
            if ($functionCore->calculate($dr_total, $cr_total, '!='))
            {
                // set form validation error
//                $this->form_validation->set_rules('Entryitem', '', 'required', array('required' => lang('entries_cntrler_dr_cr_total_not_equal_form_validation_alert')));
            }
        }


//        return $request->all();
        // initilize current entry items array
        $curEntryitems = array();

        // loop to save post data to current entry items array
        foreach ($request->Entryitem as $row => $entryitem) {

            $curEntryitems[$row] = array
            (
                'dc' => $entryitem['dc'],
                'ledger_id' => $entryitem['ledger_id'],
                // if dr_amount isset save it else save empty string
                'dr_amount' => isset($entryitem['dr_amount']) ? $entryitem['dr_amount'] : '',
                // if cr_amount isset save it else save empty string
                'cr_amount' => isset($entryitem['cr_amount']) ? $entryitem['cr_amount'] : '',
                'narration' => $entryitem['narration']
            );
        }

        // pass current entry items array to view page
        $this->data['curEntryitems'] = $curEntryitems;

        /***************************************************************************/
        /*********************************** ENTRY *********************************/
        /***************************************************************************/

        $entrydata = null; // entry data array to insert into entries table - DB1

        /***** Entry number ******/
        $entrydata['Entry']['number'] = $request->number;

        /***** Entry id ******/
        $entrydata['Entry']['id'] = $request->entry_id;

        /****** Entrytype remains the same *****/
        $entrydata['Entry']['entrytype_id'] =1;

        /****** Check tag ******/
        if (empty($request->tag_id))
        {
            // null if empty
            $entrydata['Entry']['tag_id'] = null;
        } else
        {
            // else $_POST['tag_id']
            $entrydata['Entry']['tag_id'] = $request->tag_id;
        }

        /***** Notes *****/
        $entrydata['Entry']['notes'] = $request->notes;

        /***** Date after converting to sql format *****/
        $entrydata['Entry']['date'] = date('Y-m-d',strtotime($request->date));


        /***************************************************************************/
        /***************************** ENTRY ITEMS *********************************/
        /***************************************************************************/


        $entrydata['Entry']['dr_total'] = $dr_total; // total debit amount
        $entrydata['Entry']['cr_total'] = $cr_total; // total credit amount

        /* Add item to entryitemdata array if everything is ok */
        $entryitemdata = array();

        // loop for entry items array according to debit or credit
        foreach ($request->Entryitem as $row => $entryitem)
        {
            // check if $entryitem['ledger_id'] less then or equal to 0
            if ($entryitem['ledger_id'] <= 0)
            {
                // continue to next entryitem
                continue;
            }

            // if entry item is debit
            if ($entryitem['dc'] == 'D')
            {
                $entryitemdata[] = array
                (
                    'Entryitem' => array(
                        'dc' => $entryitem['dc'],
                        'ledger_id' => $entryitem['ledger_id'],
                        'amount' => $entryitem['dr_amount'],
                        'narration' => $entryitem['narration']

                    )
                );
            } else // if entry item is credit
            {
                $entryitemdata[] = array
                (
                    'Entryitem' => array(
                        'dc' => $entryitem['dc'],
                        'ledger_id' => $entryitem['ledger_id'],
                        'amount' => $entryitem['cr_amount'],
                        'narration' => $entryitem['narration']
                    )
                );
            }
        }

        // update entries table
        $update = DB::table('finance_entries')->where('id',$request->entry_id)->update($entrydata['Entry']);
//        $update = DB::table('finance_entries')->where('id',$request->entry_id)->delete();

        // if update successfull
        if ($update)
        {
            /* Delete all original entryitems */
            $oldItems=DB::table('finance_entries_item')->where('entries_id',$request->entry_id)->delete();
            // loop to insert entry item data to entryitems table
            foreach ($entryitemdata as $row => $itemdata)
            {
                $itemdata['Entryitem']['entries_id'] = $request->entry_id; // entry_id equals passed id
                DB::table('finance_entries_item')->insert($itemdata['Entryitem']);
            }

            // set entry number as per prefix, suffix and zero padding for that entry type for logging
//            $entryNumber = ($this->functionscore->toEntryNumber($entrydata['Entry']['number'], $entrytype['Entrytype']['id']));

            // insert log if logging is enabled
//            $this->settings_model->add_log(sprintf(lang('entries_cntrler_edit_log'),$entrytype['name'], $entryNumber), 1);

            // set success alert message
//            $this->session->set_flashdata('message', sprintf(lang('entries_cntrler_edit_entry_updated_successfully'), $entrytype['name'], $entryNumber));
            // redirect to index page
            return 'update';
        } else {
            return 'not update';

        }


    }





    // cur Ledger balance
    private function curLedgerBalance($id)
    {
        $this->data['curledger'] = DB::table('finance_ledger')->where('id',$id)->get();
        // var_dump($this->data['curledger']);
        // exit();
        $cl = $this->ledger_model->closingBalance($id);
        $status = 'ok';
        $ledger_balance = '';
        if ($this->data['curledger']['type'] == 1) {
            if ($cl['dc'] == 'C') {
                $status = 'neg';
            }
        }

        /* Return closing balance */
        $cl = array('cl' =>
            array(
                'dc' => $cl['dc'],
                'amount' => $cl['amount'],
                'status' => $status,
            )
        );

        $ledger_bal = $cl['cl']['amount'];
        $prefix = '';
        $suffix = '';
        if ($cl['cl']['status'] == 'neg') {
            $this->data['prefix'] = '<span class="error-text">';
            $this->data['suffix'] = '</span>';
        }
        if ($cl['cl']['dc'] == 'D') {
            $ledger_balance = "Dr " . $ledger_bal;
        } else if ($cl['cl']['dc'] == 'C') {
            $ledger_balance = "Cr " . $ledger_bal;
        } else {
            $ledger_balance = '-';
        }
        return $ledger_balance;
    }









    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */


    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}

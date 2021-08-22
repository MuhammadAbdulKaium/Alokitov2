<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Modules\Finance\Entities\Ledger;
use Modules\Finance\Entities\EntriesItem;
class Entries extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'finance_entries';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'account_id',
        'entries_dr_cr',
        'tran_serial',
        'voucher_type_id',
        'tran_date',
        'acc_charts_id',
        'tran_amt_dr',
        'tran_amt_cr',
        'details',
        'status',
    ];


    public function sumCreditEntries(){
        return $this->hasMany('Modules\Finance\Entities\EntriesItem', 'entries_id', 'id')->sum('cr_amount');
    }

    public function sumDebitEntries(){
        return $this->hasMany('Modules\Finance\Entities\EntriesItem', 'entries_id', 'id')->sum('dr_amount');
    }


    public function entriesItem(){
        return $this->hasMany('Modules\Finance\Entities\EntriesItem', 'entries_id', 'id')->get();
    }

    // get entriest type lebel

    public function  getEntriesTypeProfile($id){
        $entriesItemProifle= DB::table('finance_entries_type')->where('id',$id)->first();
        if(!empty($entriesItemProifle)){
            return $entriesItemProifle;
        } else {
            return "Entry Type Not FOund";
        }
    }






    /**
     * Show the entry ledger details
     */
    public function entryLedgers($id) {
//        return $id;
        /* Load the Entryitem model */
        $Entryitem = new EntriesItem;
        /* Load the Ledger model */
        $Ledger = new  Ledger;
        $rawentryitems=DB::table('finance_entries_item')->where('entries_id',$id)->get();

        /* Get dr and cr ledger id and count */
        $dr_count = 0;
        $cr_count = 0;
        $dr_ledger_id = '';
        $cr_ledger_id = '';
        foreach ($rawentryitems as $row => $entryitem) {
            if ($entryitem->dc == 'D') {
                $dr_ledger_id = $entryitem->ledger_id;
                $dr_count++;
            } else {
                $cr_ledger_id = $entryitem->ledger_id;
                $cr_count++;
            }
        }

        /* Get ledger name */
        $dr_name = $Ledger->getName($dr_ledger_id);
        $cr_name = $Ledger->getName($cr_ledger_id);

        if (strlen($dr_name) > 15) {
            $dr_name = substr($dr_name, 0, 15) . '...';
        }
        if (strlen($cr_name) > 15) {
            $cr_name = substr($cr_name, 0, 15) . '...';
        }

        /* if more than one ledger on dr / cr then add [+] sign */
        if ($dr_count > 1) {
            $dr_name = $dr_name . ' [+]';
        }
        if ($cr_count > 1) {
            $cr_name = $cr_name . ' [+]';
        }

            $ledgerstr = 'Dr ' . $dr_name . ' / ' . 'Cr ' . $cr_name;
        return $ledgerstr;
    }


    /**
     * Calculate the next number for a entry based on entry type
     */
    public function nextNumber($id)	{
        $max=DB::table('finance_entries')->first();
        if (empty($max)) {
            $maxNumber = 0;
        } else {
            $maxNumber = $max->number;
        }
        return $maxNumber + 1;
    }


    public function  getEntryTypeById($id)
    {
        return Entries::where('entrytype_id',$id)->first();
    }



//    public function viewEntries($entrytypeLabel = null, $id = null) {
//
//        /* Check for valid entry type */
//        if (empty($entrytypeLabel))
//        {
//            // set error alert
//            $this->session->set_flashdata('error', lang('entries_cntrler_entrytype_not_specified_error'));
//            // redirect to index page
//            redirect('entries/index');
//        }
//
//        // select entry type where label equals $entrytypeLabel and store to array
//        $entrytype = $this->DB1->where('label',$entrytypeLabel)->get('entrytypes')->row_array();
//
//        // if entry type [NOT] found
//        if (!$entrytype)
//        {
//            // set error alert
//            $this->session->set_flashdata('error', lang('entries_cntrler_entrytype_not_found_error'));
//            // redirect to index page
//            redirect('entries/index');
//        }
//
//        // pass entrytype to view page
//        $this->data['entrytype'] = $entrytype;
//
//        /* Check if valid id */
//        if (empty($id))
//        {
//            // set error alert
//            $this->session->set_flashdata('error', lang('entries_cntrler_edit_entry_not_found_error'));
//            // redirect to index page
//            redirect('entries/index');
//        }
//
//        // select entry where id equals $id and store to array
//        $entry = $this->DB1->where('id',$id)->get('entries')->row_array();
//
//        /* if entry [NOT] found */
//        if (!$entry)
//        {
//            // set error alert
//            $this->session->set_flashdata('error', lang('entries_cntrler_edit_entry_not_found_error'));
//            // redirect to index page
//            redirect('entries/index');
//        }
//
//
//        /* Initial data */
//        $curEntryitems = array(); // initilize current entry items array
//        $this->DB1->where('entry_id', $id); // select where entry_id equals $id
//
//        // store selected data to $curEntryitemsData
//        $curEntryitemsData = $this->DB1->get('entryitems')->result_array();
//
//        // loop to store selected entry items to current entry items array
//        foreach ($curEntryitemsData as $row => $data)
//        {
//            // if debit entry
//            if ($data['dc'] == 'D')
//            {
//                $curEntryitems[$row] = array
//                (
//                    'dc' => $data['dc'],
//                    'ledger_id' => $data['ledger_id'],
//                    'ledger_name' => $this->ledger_model->getName($data['ledger_id']),
//                    'dr_amount' => $data['amount'],
//                    'cr_amount' => '',
//                    'narration' => $data['narration']
//                );
//            }else // if credit entry
//            {
//                $curEntryitems[$row] = array
//                (
//                    'dc' => $data['dc'],
//                    'ledger_id' => $data['ledger_id'],
//                    'ledger_name' => $this->ledger_model->getName($data['ledger_id']),
//                    'dr_amount' => '',
//                    'cr_amount' => $data['amount'],
//                    'narration' => $data['narration']
//
//                );
//            }
//        }
//
//        $this->data['curEntryitems'] = $curEntryitems; // pass current entry items to view
//        $this->data['allTags'] = $this->DB1->get('tags')->result_array(); // fetch all tags and pass to view
//        $this->data['entry'] = $entry; // pass entry to view
//
//        // render page
//        $this->render('entries/view');
//    }






}

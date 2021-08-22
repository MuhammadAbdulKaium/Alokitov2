<?php

namespace Modules\Fee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\FeeAssign;
use Modules\Fee\Entities\FeeHeadFundSetting;
use Modules\Fee\Entities\FeeFund;
use Modules\Fee\Entities\FeeHead;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Fee\Entities\FeeSubhead;
use Modules\Fee\Entities\FeeWaiverType;
use Modules\Academics\Entities\Batch;
use Modules\Fee\Entities\SubHeadFine;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Fee\Entities\WaiverAssign;
use Modules\Fee\Entities\AbsetFineSetting;
use App\Http\Controllers\Helpers\Accounting\LagerTree;
use Modules\Finance\Entities\Group;
use Modules\Finance\Entities\Ledger;
use Illuminate\Support\Facades\DB;
use Modules\Finance\Entities\FinancialAccount;
class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $feehead;
    private $academicHelper;
    private $feeSubhead;
    private $feewaiverType;
    private $feefund;
    private $feeHeadFundSetting;
    private $batch;
    private $feeAssingModel;
    private $subHeadFine;
    private $academicsYear;
    private $data;
    private $waiverAssign;
    private $absetFineSetting;
    private $financialAccount;

    // for accounting
    private  $group;
    private  $ledger;

    public function  __construct(FeeHead $feeHead, WaiverAssign $waiverAssign, FinancialAccount $financialAccount, Batch $batch, AcademicHelper $academicHelper, FeeSubhead $feeSubhead, FeeWaiverType $feewaiverType, FeeFund $feefund, FeeHeadFundSetting $feeHeadFundSetting, FeeAssign $feeAssingModel, SubHeadFine $subHeadFine,AcademicsYear $academicsYear, AbsetFineSetting $absetFineSetting, Ledger $ledger, Group $group)
    {
        $this->feehead=$feeHead;
        $this->feeSubhead=$feeSubhead;
        $this->feewaiverType=$feewaiverType;
        $this->academicHelper=$academicHelper;
        $this->feefund=$feefund;
        $this->feeHeadFundSetting=$feeHeadFundSetting;
        $this->batch=$batch;
        $this->feeAssingModel=$feeAssingModel;
        $this->subHeadFine=$subHeadFine;
        $this->academicsYear=$academicsYear;
        $this->waiverAssign=$waiverAssign;
        $this->absetFineSetting=$absetFineSetting;
        $this->financialAccount=$financialAccount;
        // for accounting
        $this->group=$group;
        $this->ledger=$ledger;

    }

    public function index()
    {
        return view('fee::index');
    }


    // feee setting
    public function feeSetting($tabName)
    {
        switch ($tabName) {

            case 'feehead':
                // get all fee head
                $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
               // get active finacail account id
                $activeAccount=$this->financialAccount
                    ->where('institution_id',institution_id())
                    ->where('campus_id',campus_id())
                    ->first();

                // get fees group
                $groupProfile=$this->group->where('code','FEES')->where('account_id',$activeAccount->id)->first();
                $this->data['lagers']=$this->ledger->where('group_id',$groupProfile->id)->get();

                return view('fee::pages.setting.feehead', $this->data)->with('page', 'feehead');
                break;

            case 'fund':
               // show all ledger list
//                $entrytype =DB::table('finance_entries_type')->first();
//                $this->data['entrytype'] = $entrytype; // pass entrytype array to view page
//                // pass page title to view page
//                $this->data['title'] = $entrytype->name;
//                // pass tag_options array to view page
////        $data['tag_options'] = DB::table('finance_entries_type')->select('id, title')->get('tags')->result_array();
//
//                /* Ledger selection */
//                $ledgers = new LagerTree(); // initilize ledgers array - LedgerTree Lib
//                $ledgers->Group = &$this->Group; // initilize selected ledger groups in ledgers array
//                $ledgers->Ledger = &$this->Ledger; // initilize selected ledgers in ledgers array
//                $ledgers->current_id = -1; // initilize current group id
//                // set restriction_bankcash from entrytype
//                $ledgers->restriction_bankcash = $entrytype->restriction_bankcash;
//                $ledgers->build(0); // set ledger id to [NULL] and ledger name to [None]
//                $ledgers->toList($ledgers, -1); // create a list of ledgers array
//                $this->data['ledger_options'] = $ledgers->ledgerList; // pass ledger list to view page
//
//
//                $curEntryitems = array(); // initilize current entry items array
//
//                /* Special case if atleast one Bank or Cash on credit side (3) then 1st item is Credit */
//                if ($entrytype->restriction_bankcash == 3){
//                    $curEntryitems[0] = array('dc' => 'C');
//                    $curEntryitems[1] = array('dc' => 'D');
//                }else /* else 1st item is Debit */
//                {
//                    $curEntryitems[0] = array('dc' => 'D');
//                    $curEntryitems[1] = array('dc' => 'C');
//                }
//
//                // created debit and credit array
//                $dc_options = array(
//                    'D' => 'Dr',
//                    'C' => 'Cr',
//                );
//
//                // pass current entry items array to view page
//                $this->data['curEntryitems'] = $curEntryitems;
//                $this->data['dc_options'] = $dc_options;

                // return veiw with variables
                $this->data['feefunds']=$this->feefund->where('institution_id',institution_id())->where('campus_id',campus_id())->get();

                return view('fee::pages.setting.fund',$this->data)->with('page', 'fund');
                break;

            case 'waiver':
                // return veiw with variables
                $this->data['feewaiverTypes']=$this->feewaiverType->where('institution_id',institution_id())->where('campus_id',campus_id())->get();

                return view('fee::pages.setting.waiver', $this->data)->with('page', 'waiver');
                break;

            case 'feefundcreate':
                // return veiw with variables
                // get all fee head
                $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
                // return veiw with variables
                $this->data['feefunds']=$this->feefund->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
                return view('fee::pages.setting.feefundcreate',$this->data)->with('page', 'feefundcreate');
                break;

            case 'feefundlist':
                // return veiw with variables
                $this->data['feelist']=$this->feeHeadFundSetting->where('institution_id',institution_id())->where('campus_id',campus_id())->get();

                return view('fee::pages.setting.feefundlist',$this->data)->with('page', 'feefundlist');
                break;

            case 'absentfine':
                // return veiw with variables
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();
                // get all absent fine setting list
                $this->data['absentFineSettingList']=$this->absetFineSetting->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
                return view('fee::pages.setting.absent-fine',$this->data)->with('page', 'absentfine');
                break;


            default:
                return view('fee::pages.setting.feehead')->with('page', 'feehead');
                break;
        }
    }


    // feee setting
    public function feeCreate($tabName)
    {
        switch ($tabName) {

            case 'generalfee':
                // fee head
                $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
                // fee head fund
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();

                return view('fee::pages.feecreate.createfee',$this->data)->with('page', 'generalfee');
                break;

            case 'feelist':
                // return veiw with variables
                $this->data['feelist']=$this->feeSubhead->where('institution_id',institution_id())->where('campus_id',campus_id())->paginate(20);

                return view('fee::pages.feecreate.createdfee',$this->data)->with('page', 'feelist');
                break;


            case 'feefine':
                // return veiw with variables
                $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();
                return view('fee::pages.feecreate.feefine',$this->data)->with('page', 'feefine');
                break;

            case 'finelist':
                // return veiw with variables
                $this->data['finelist']=$this->subHeadFine->where('institution_id',institution_id())->where('campus_id',campus_id())->get();

                return view('fee::pages.feecreate.finelist',$this->data)->with('page', 'finelist');
                break;


            default:
                return view('fee::pages.feecreate.createfee')->with('page', 'generalfee');
                break;
        }
    }


    // feee setting
    public function feeCollection($tabName)
    {
        switch ($tabName) {

            case 'single':
                // institute academic year
                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                return view('fee::pages.feecollection.single',$this->data)->with('page', 'single');
                break;

            case 'multiple':
                // return veiw with variables
                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();
                $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
                return view('fee::pages.feecollection.multiple',$this->data)->with('page', 'multiple');
                break;

            case 'due-fee-collection':
                // institute academic year
                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                return view('fee::pages.feecollection.due_fee',$this->data)->with('page', 'due-fee-collection');
                break;

            default:
                return view('fee::pages.feecollection.single')->with('page', 'single');
                break;
        }
    }


    // fee fine collection here

    public function feeFineCollection($tabName)
    {
        switch ($tabName) {
            case 'late-fine':
                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();
                $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
                return view('fee::pages.finecollection.late-fine',$this->data)->with('page', 'late-fine');
                break;

            case 'absent-fine':
                // return veiw with variables
                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();
                return view('fee::pages.finecollection.absent-fine',$this->data)->with('page', 'absent-fine');
                break;

            default:
                return view('fee::pages.finecollection.late-fine')->with('page', 'late-fine');
                break;
        }
    }


    // due amount report

    public function feeReportDueAmount($tabName)
    {
        switch ($tabName) {

            case 'student-wise':

                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();

                return view('fee::pages.report-due-amount.student-wise', $this->data)->with('page', 'student-wise');
                break;

            case 'fee-wise':

                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();
                $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
                return view('fee::pages.report-due-amount.fee-wise',$this->data)->with('page', 'fee-wise');
                break;

            case 'absent-fine':
                // return veiw with variables
                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();
                return view('fee::pages.report-due-amount.absent-fine',$this->data)->with('page', 'absent-fine');
                break;

            case 'all-student':

                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();

                return view('fee::pages.report-due-amount.all-student', $this->data)->with('page', 'all-student');
                break;



            default:
                return view('fee::pages.report-due-amount.student-wise')->with('page', 'student-wise');
                break;
        }
    }




    // fee assign tab

    // feee setting
    public function feeAssign($tabName)
    {
        switch ($tabName) {

            case 'feeassign':
                // return veiw with variables
                $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
               $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();

                return view('fee::pages.feeassign.feeassign',$this->data)->with('page', 'feeassign');
                break;

            case 'feelist':

                // return veiw with variables
                $this->data['feeList']=$this->feeAssingModel->where('institution_id',institution_id())->where('campus_id',campus_id())->paginate(30);
                return view('fee::pages.feeassign.feelist',$this->data)->with('page', 'feelist');
                break;

            case 'assignwaiver':
                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();

                // return veiw with variables
                return view('fee::pages.feeassign.assignwaiver',$this->data)->with('page', 'assignwaiver');
                break;


            case 'attendancefine':
                // return veiw with variables
                return view('fee::pages.feeassign.attendancefine')->with('page', 'attendancefine');
                break;

            case 'singlefeehead':
                // return veiw with variables
                return view('fee::pages.feeassign.singlefeehead')->with('page', 'singlefeehead');
                break;

            case 'feefineamount':
                // return veiw with variables
                return view('fee::pages.feeassign.feefineamount')->with('page', 'feefineamount');
                break;

            case 'multiplefeehead':
                // return veiw with variables
                return view('fee::pages.feeassign.multiplefeehead')->with('page', 'multiplefeehead');
                break;

            case 'assignwaiverlist':
                // return veiw with variables
                $this->data['waiverAssignList']=$this->waiverAssign->where('institution_id',institution_id())->where('campus_id',campus_id())->where('year_id',academic_year())->paginate(10);

                return view('fee::pages.feeassign.assignwaiverlist',$this->data)->with('page', 'assignwaiverlist');
                break;



            default:
                // return veiw with variables
                $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();

                return view('fee::pages.feeassign.feeassign',$this->data)->with('page', 'feeassign');
                break;
        }
    }




    // feee setting
    public function feeReportCollectionAmount($tabName)
    {
        switch ($tabName) {

            case 'feedetails':
                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();
                $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
                return view('fee::pages.report-collection-amount.feedetails',$this->data)->with('page', 'feedetails');
                break;

            case 'transaction':
                return
                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();
                $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
                return view('fee::pages.report-collection-amount.transaction',$this->data)->with('page', 'transaction');
                break;


            case 'monthwise':
                // return veiw with variables
                $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
                return view('fee::pages.report-collection-amount.monthwise',$this->data)->with('page', 'monthwise');
                break;

            default:
                return view('fee::pages.report-collection-amount.feedetails')->with('page', 'feedetails');
                break;
        }
    }


    // fee money receipt collection here
    public function feeReportMoneyReceipt($tabName)
    {
        switch ($tabName) {

            case 'feereceipt':
                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();

                return view('fee::pages.report-money-receipt.fee-receipt',$this->data)->with('page', 'feereceipt');
                break;

            case 'latefinereceipt':
                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();
                return view('fee::pages.report-money-receipt.late-fine-receipt',$this->data)->with('page', 'latefinereceipt');
                break;

            case 'absentfinereceipt':
                $this->data['academicYearList']=$this->academicsYear->where('institute_id',institution_id())->where('campus_id',campus_id())->orderBy('id','desc')->get();
                $this->data['batchs']=$this->batch->where('institute',institution_id())->where('campus',campus_id())->get();

                return view('fee::pages.report-money-receipt.absent-fine-receipt',$this->data)->with('page', 'absentfinereceipt');
                break;

            default:
                return view('fee::pages.report-money-receipt.fee-receipt')->with('page', 'feereceipt');
                break;
        }
    }





    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('fee::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('fee::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('fee::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function showAllLedgerlist()

    {
        $entrytype =DB::table('finance_entries_type')->first();
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
        return $data;
    }
}

<?php

namespace Modules\Fees\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Fees\Entities\FeesInvoice;
use Modules\Fees\Entities\Fees;
use Modules\Fees\Entities\FeesItem;
use Modules\Fees\Entities\PaymentExtra;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Setting\Entities\FessSetting;

use Modules\Fees\Entities\FeeType;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Fees\Entities\InvoicePayment;
use Modules\Fees\Entities\FeesDiscount;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Modules\Student\Entities\StudentAttendanceFine;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Fees\Entities\Items;
use Modules\Accounting\Entities\AccCharts;
use Modules\Setting\Entities\AutoSmsModule;
use Modules\Fees\Entities\FeesBatchSection;
use Modules\Fees\Entities\FeesTemplateClassSection;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Setting\Entities\SmsInstitutionGetway;
use App\Http\Controllers\SmsSender;

class FeesController extends Controller
{
    private  $fees;
    private  $feesItem;
    private  $feesInvoice;
    private  $invoicePayment;
    private  $feesDiscount;
    private  $batch;
    private  $section;
    private  $paymentExtra;
    private  $academicsYear;
    private  $academicsLevel;
    private  $studentAttendanceFine;
    private  $academicHelper;
    private  $items;
    private  $accCharts;
    private  $autoSmsModule;
    private  $feesBatchSection;
    private  $feesTemplateClassSection;
    private  $studentEnrollment;
    private  $smsInstitutionGetway;
    private  $smsSender;


    private  $feeType;

    public function __construct( AutoSmsModule $autoSmsModule,SmsSender $smsSender, SmsInstitutionGetway $smsInstitutionGetway, StudentEnrollment $studentEnrollment, FeesTemplateClassSection $feesTemplateClassSection, FeesBatchSection $feesBatchSection, Fees $fees, AccCharts $accCharts, AcademicHelper $academicHelper, Items $items, StudentAttendanceFine $studentAttendanceFine, AcademicsLevel $academicsLevel, AcademicsYear $academicsYear,PaymentExtra $paymentExtra,FeesItem $feesItem,FeeType $feeType,FeesInvoice $feesInvoice,InvoicePayment $invoicePayment,FeesDiscount $feesDiscount,Batch $batch,Section $section)

    {
        $this->fees             = $fees;
        $this->feesItem         = $feesItem;
        $this->feesInvoice      = $feesInvoice;
        $this->invoicePayment   = $invoicePayment;
        $this->feesDiscount     = $feesDiscount;
        $this->batch                  = $batch;
        $this->section                = $section;
        $this->academicsYear           = $academicsYear;
        $this->paymentExtra           = $paymentExtra;
        $this->academicsLevel           = $academicsLevel;

        $this->feeType                = $feeType;
        $this->studentAttendanceFine                = $studentAttendanceFine;
        $this->academicHelper                = $academicHelper;
        $this->items                = $items;
        $this->accCharts                = $accCharts;
        $this->autoSmsModule                = $autoSmsModule;
        $this->feesBatchSection                = $feesBatchSection;
        $this->feesTemplateClassSection     = $feesTemplateClassSection;
        $this->studentEnrollment     = $studentEnrollment;
        $this->smsInstitutionGetway     = $smsInstitutionGetway;
        $this->smsSender     = $smsSender;

    }





    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('fees::dashboard.fees');
    }


    // all pages
    public function allFees($tabName)
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        switch ($tabName) {

            case 'feeslist':
                // return veiw with variables

                $allFees=$this->fees->orderBy('id','desc')->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('fee_status',0)->paginate(10);

                $searchFees="";
                return view('fees::pages.feeslist',compact('allFees','searchFees'))->with('page', 'feeslist');
                break;

            case 'feesmanage':
                // return veiw with variables
                $searchInvoice="";
                return view('fees::pages.feesmanage',compact('searchInvoice'))->with('page', 'feesmanage');
                break;


            case 'invoice':
                // return veiw with variables
                $searchInvoice="";
                $academicYear=session()->get('academic_year');
                $batchs=$this->batch->where('institute',$instituteId)->where('campus',$campus_id)->get();
                $sections=$this->section->where('institute',$instituteId)->where('campus',$campus_id)->get();

//                $feesinvoices=$this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereNotNull('fees_id')->orderBy('id','desc')->paginate(10);
                $feesinvoices=$this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->orderBy('id','desc')->paginate(10);
                return view('fees::pages.invoice',compact('searchInvoice','feesinvoices','batchs','sections'))->with('page', 'invoice');
                break;

            case 'paymenttransaction':
                // return veiw with variable
                //get all transactions
                $allPaymentTransaction=$this->invoicePayment->where('institution_id',$instituteId)->where('campus_id',$campus_id)->orderBy('id','desc')->whereNotNull('fees_id')->paginate(10);
                $searchPaymentTransaction="";
                return view('fees::pages.paymenttransaction',compact('allPaymentTransaction','searchPaymentTransaction'))->with('page', 'paymenttransaction');
                break;


            case 'addfees':
                // return veiw with variables
                $feesModules=$this->autoSmsModule->where('ins_id',$instituteId)->where('campus_id',$campus_id)->where('status_code','FEES')->where('status',1)->first();
                // items
                $allItems=$this->items->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();
                $feeTypes=$this->feeType->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();
                return view('fees::pages.addfees',compact('feeTypes','feesModules','allItems'))->with('page', 'addfees');
                break;

            case 'feestemplate':
                // return veiw with variables
                $allFees=$this->fees->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('fee_status',1)->orderBy('id','desc')->paginate(10);
                $searchFees="";
                $feesBatchSections=0;
                return view('fees::pages.feestemplate',compact('allFees','searchFees','feesBatchSections'))->with('page', 'feestemplate');
                break;

            case 'feetype':
                // return veiw with variables
                $feetypes=$this->feeType->where('institution_id',$instituteId)->where('campus_id',$campus_id)->paginate(10);
                return view('fees::pages.feetype',compact('feetypes'))->with('page', 'feetype');
                break;


            case 'advance_payment':
//                return "toams";
                // return veiw with variables
               $extraPaymentList=$this->paymentExtra->paginate(2);
                $allAcademicsLevel=$this->academicHelper->getAllAcademicLevel();
                $advancePaymentView=view('fees::pages.modal.extrapayment_amount', compact('extraPaymentList'));

                return view('fees::pages.payment_extralist',compact('extraPaymentList','allAcademicsLevel','advancePaymentView'))->with('page', 'advance_payment');
                break;


            case 'attendance_fine':
//                return "toams";
                // return veiw with variables
                $extraPaymentList=$this->paymentExtra->paginate(10);
                $allAcademicsLevel=$this->academicHelper->getAllAcademicLevel();
                return view('fees::pages.attendance_fine',compact('extraPaymentList','allAcademicsLevel'))->with('page', 'attendance_fine');
                break;
                // test


            case 'attendance_fine_generate':
//                return "toams";
                // return veiw with variables
                return view('fees::pages.attendance_fine_generate')->with('page', 'attendance_fine_generate');
                break;

            case 'items':
                $itemList=$this->items->where('institution_id',$instituteId)->where('campus_id',$campus_id)->orderBy('id','desc')->get();
                // return veiw with variables

                // get fees group id
               $feesAccChartProfile=$this->accCharts->where('company_id',$instituteId)->where('brunch_id',$campus_id)->where('chart_name','Fees')->first();

                if(!empty($feesAccChartProfile)) {
                    // select all accounting leger by fee group
                    $accChartList=$this->accCharts->where('company_id',$instituteId)->where('brunch_id',$campus_id)->where('chart_parent',$feesAccChartProfile->id)->where('status',1)->get();
                } else {
                    $accChartList=[];
                }

                return view('fees::pages.items', compact('itemList','accChartList'))->with('page', 'items');
                break;


            default:
                return view('fees::dashboard.fees')->with('page', 'fees');
                break;
        }
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function manageFees(Request $request)
    {

//        return $request->all();

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        // DB beginTransaction
        DB::beginTransaction();

        // fees creation
        try {

            $fees=new $this->fees();
            $fees->fee_name=$request->input('fee_name');
            $fees->institution_id=$instituteId;
            $fees->campus_id=$campus_id;
            $fees->due_date=date('Y-m-d', strtotime($request->input('due_date')));
            $fees->fee_type=$request->input('fees_type_name');
            $fees->description=$request->input('description');
            $fees->fee_status=$request->input('fee_status');
            $fees->month=$request->input('month');
            $fees->year=$request->input('year');
            $fees->partial_allowed='0';
            $feesSumbited=$fees->save();

        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            // Redirecting with error message

            DB::rollback();

            return redirect()->back()
                ->withErrors($e->getErrors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }


        // crate fees items
        try {

            // delete action
            $fees_delete_counter = $request->input('fees_delete_counter');
            $deleteList = $request->input('deleteList');
            // checking
            if($fees_delete_counter>0){
                for($i=0; $i<$fees_delete_counter; $i++){
                    $this->feesItem->find($deleteList[$i])->delete();
                }
            }

            // item creation action
            $itemCount=$request->input('fees_item_counter');
            //
            for($i=1;$i<=$itemCount;$i++){
                $item= $request->$i;
                $feesItem=new $this->feesItem();
                $feesItem->item_name=$item['item_name'];
                $feesItem->item_id=$item['item_tbl_id'];
                $feesItem->rate=$item['rate'];
                $feesItem->qty=$item['qty'];
                $feesItem->status='';
                $feesItem->fees_id=$fees->id;
                $feesItem->save();
            }

        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return redirect()->back()
                ->withErrors($e->getErrors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        // discount
        $discount_name=$request->input('discount_name');
        $discount_percent=$request->input('discount_percent');

        if(!empty($discount_percent)) {

            $feesDiscount = new $this->feesDiscount();
            $feesDiscount->institution_id=$instituteId;
            $feesDiscount->campus_id=$campus_id;
            $feesDiscount->fees_id = $fees->id;
            $feesDiscount->discount_name = $discount_name;
            $feesDiscount->discount_percent = $discount_percent;
            $feesDiscount->status = '';
            $feesDiscount->save();
        }
        // If we reach here, then
        // data is valid and working.
        // Commit the queries!
        DB::commit();
        // return
        return json_encode($fees->id);
    }



    public function feesTemplateUpdatebyId(Request $request,$id)
    {
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

            // get fees sms modules
        $feesModules=$this->autoSmsModule->where('ins_id',$instituteId)->where('campus_id',$campus_id)->where('status_code','FEES')->where('status',1)->first();
        $fee=$fees=$this->fees->find($id);
        $feeTypes=$this->feeType->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();


        $feesTemplateClassSection=$this->feesTemplateClassSection->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('fees_id',$id)->get();

        // response data array
        $data = array();
        foreach($feesTemplateClassSection as $feesTemplate) {
            $data[]= array(
                'id' =>$feesTemplate->id,
                'batch_id' => $feesTemplate->batch_id,
                'section_id' => $feesTemplate->section_id,
                'name' => $feesTemplate->batch()->batch_name . '- Section: ' . $feesTemplate->section()->section_name,
//                        'std_count' => getNumberOfStudentByBatchSection($feesTemplate->batch_id, $feesTemplate->section_id),
            );
        }
        $feesClassSectionList=$data;
        $data=json_encode($data);

        // all items
       $allItems= $this->items->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();
        return view('fees::pages.fees_template_copy',compact('fee','feeTypes','feesModules','data','feesClassSectionList','allItems'))->with('page', 'addfees');
    }

    /// get all fees
    public  function getAllFees(Request $request)
    {
        $searchTerm = $request->input('term');

        $instituteId = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

        $allFees = $this->fees->where('institution_id', $instituteId)->where('campus_id', $campus_id)->where('fee_name', 'like', "%" . $searchTerm . "%")->where('fee_status', 0)->get();

        // checking
        if ($allFees) {
            $data = array();
            foreach ($allFees as $fees) {
                // store into data set
                $data[] = array(
                    'id' => $fees->id,
                    'fee_name' => $fees->fee_name,
                );
            }

            return json_encode($data);
        }

    }



    // get all fees template




    public  function getAllFeesTemplate(Request $request)
    {
        $searchTerm = $request->input('term');

        $instituteId = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

        $allFees = $this->fees->where('institution_id', $instituteId)->where('campus_id', $campus_id)->where('fee_name', 'like', "%" . $searchTerm . "%")->where('fee_status', 1)->get();

        // checking
        if ($allFees) {
            $data = array();
            foreach ($allFees as $fees) {
                // store into data set
                $data[] = array(
                    'id' => $fees->id,
                    'fee_name' => $fees->fee_name,
                );
            }

            return json_encode($data);
        }

    }




    //fees search

    public  function  feesSearch(Request $request){

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

//        $request->all();

        $fees_id=$request->input('fees_id');
        $fees_name=$request->input('fees_name');
        $partial_allowed=$request->input('partial_allow');
        $from_date= $request->input('search_start_date');
        $to_date= $request->input('search_end_date');
        $due_date=$request->input('search_due_date');
        $fees_status=$request->input('fees_status');
        if(!empty($from_date)) {
            $from_date=date('Y-m-d H:i:s', strtotime($from_date));
        }
        if(!empty($to_date)) {
            $to_date=date('Y-m-d', strtotime($to_date));
            $to_date = new Carbon($to_date);
            $to_date = $to_date->endOfDay();
        }


        $allSearchInputs=array();
        // check fees_id
        if ($fees_id) {
            $allSearchInputs['id'] = $fees_id;
        }
        // check payer_id
        if ($fees_name) {

            $allSearchInputs['fee_name'] = $fees_name;
        }
        // // check payment_status
        if ($fees_status) {
            $allSearchInputs['fee_status'] = $fees_status;
        }
        if (($partial_allowed=="0") || ($partial_allowed=="1") ) {
            $allSearchInputs['partial_allowed'] = $partial_allowed;
        }

        if ($due_date) {
            $due_date=date('Y-m-d H:i:s', strtotime($due_date));
            $allSearchInputs['due_date'] = $due_date;
        }

//        return $allSearchInputs;

        if(!empty($from_date) && !empty($to_date)) {
            $allFees = $this->fees->where($allSearchInputs)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereBetween('created_at', [$from_date, $to_date])->paginate(10);
        } else {
            $allFees = $this->fees->where($allSearchInputs)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->paginate(10);
        }


        if ($allFees) {
            // all inputs
            $allInputs =[
                'fees_id' => $fees_id,
                'fees_name'=>$fees_name,
                'partial_allowed'=>$partial_allowed,
                'fees_status' => $fees_status,
                'search_start_date' => $from_date,
                'search_end_date' => $to_date,
                'search_due_date'=>$due_date
            ];
            // return view
            $allInputs=(Object)$allInputs;
            $searchFees=1;
            return view('fees::pages.feeslist', compact('searchFees','allFees','allInputs'))->with('page', 'feeslist');
            // return redirect()->back()->with(compact('state'))->withInput();

        }

    }




//    public function feesUpdate(Request $request,$id) {
//        return $request->all();
//        $fees=$this->fees->findOrFail($id);
//        $fees->fee_name=$request->input('fee_name');
//        $fees->due_date=date('Y-m-d', strtotime($request->input('due_date')));
//        $fees->fee_type=$request->input('fee_type');
//        $fees->description=$request->input('description');
//        $data=$fees->save();
//
//        if($data){
//
//            $itemCount=$request->input('fees_item_counter');
//            //
//            for($i=1;$i<=$itemCount;$i++){
//                $item= $request->$i;
//                $feesItem=new $this->feesItem();
//                $feesItem->item_name=$item['item_name'];
//                $feesItem->rate=$item['rate'];
//                $feesItem->qty=$item['qty'];
//                $feesItem->status='';
//                $feesItem->fees_id=$fees->id;
//                $feesItem->save();
//            }
//
//        }
//        return json_encode($fees->id);
//    }


// fees delete
    public function deleteFees($feesId){
        $InvoiceProfile=$this->feesInvoice->where('fees_id',$feesId)->first();
        if(!empty($InvoiceProfile)) {
            return 'warning';
        } else {
            $feesProfile= $this->fees->find($feesId);
            $feesProfile->delete();
            return 'success';
        }
        // checking

    }


    public function updatePartialPaymentById( Request $request) {

        $fees_id=$request->input('fees_id');
        $fees=$this->fees->find($fees_id);
        $fees->partial_allowed=$request->input('partial_allowed');
        $fees->save();
        return "Succcess";

    }



    // fees setting

    public function  feesSettingView($tabName)
    {

        switch ($tabName) {

            case 'fine':
                //fine Setting
                $fineSettings = FessSetting::where('setting_type', 1)->get();
                return view('fees::pages.fees-setting-view-fine', compact('fineSettings'))->with('page', 'fine');
                break;

            default:
                //tuition fees Setting
                $tuitionSettings = FessSetting::where('setting_type', 2)->get();
                return view('fees::pages.fees-setting-view-tuition',compact('tuitionSettings'))->with('page', 'tuitionfee');
                break;
        }
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
        return view('fees::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('fees::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */

    // fees due date update View Modal

    public function feesDueDateUpdateModal($feesId)
    {
        $feesProfile=$this->fees->find($feesId);
        return view('fees::pages.modal.fees_date_update_view',compact('feesProfile'));
    }

    public function  feesDueDateUpdate(Request $request) {
        $fees_id=$request->input('fees_id');
        $due_date=date('Y-m-d',strtotime($request->input('due_date')));
        $feesProifle= $this->fees->find($fees_id);
        if(!empty($feesProifle)){
            $feesProifle->due_date=$due_date;
            $feesProifle->save();
        }
        Session::flash('message','Fees Due Date Update Successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */


    public function  feesTemplateSearch(Request $request){

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        $fees_id_single = $request->input('fees_id_single');
        $fees_id = $request->input('fees_id');
        $fees_name = $request->input('fees_name');

       return $templateFeesProfile=$this->fees->Orwhere('id',$fees_id_single)->Orwhere('id',$fees_id)->orWhere('fee_name',$fees_name)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('fee_status',1)->first();



        // all inputs
        $allInputs = [
            'fees_id' => $fees_id,
            'fees_id_single' => $fees_id_single,
            'fees_name' => $fees_name
        ];
        // return view
        $allInputs = (Object)$allInputs;

        $allFees=$this->fees->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('fee_status',1)->paginate(10);

         $feesBatchSections=$this->feesTemplateClassSection->where('fees_id',$templateFeesProfile->id)->get();

        return view('fees::pages.feestemplate', compact('templateFeesProfile','allInputs','allFees','feesBatchSections'))->with('page', 'feestemplate');

    }



    public function feesTemplateAddClassSectionModal($feesTemplateId){

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        $academicYears=$this->academicsYear->where('institute_id',$instituteId)->where('campus_id',$campus_id)->orderBy('id','desc')->get();
        return view('fees::pages.modal.fees_template_add_class_section',compact('feesTemplateId','academicYears'));

    }


    public function feesTemplateClassSectionStore (Request $request)
    {

//        return $request->all();

        $instituteId = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();
        $row = 0; // assign for make a new table row

        $fees_id = $request->input('fees_id');
        $batch_id = $request->input('batch');
        $section_id = $request->input('section');
        $fees_type = 1; // 1 = copy template

        $feesTemplateBatchSectionProfileOld = $this->feesTemplateClassSection->where('fees_id',$fees_id)->where('batch_id',$batch_id)->where('section_id',$section_id)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->first();

        if(empty($feesTemplateBatchSectionProfileOld)) {
        $feesBatchSectionObj = new $this->feesTemplateClassSection;
        $feesBatchSectionObj->institution_id = $instituteId;
        $feesBatchSectionObj->campus_id = $campus_id;
        $feesBatchSectionObj->fees_id = $fees_id;
        $feesBatchSectionObj->batch_id = $batch_id;
        $feesBatchSectionObj->section_id = $section_id;
        $result = $feesBatchSectionObj->save();

        if ($result) {


            $feesTemplateBatchSectionProfile = $this->feesTemplateClassSection->where('id', $feesBatchSectionObj->id)->first();
            $deleteButton='<a id="'.$feesTemplateBatchSectionProfile->id.'" class="fees_template_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>';

            $row .= '<tr id="' . $feesTemplateBatchSectionProfile->id . '">';
            $row .= '<td>' . $feesTemplateBatchSectionProfile->id . '</td>';
            $row .= '<td>' . $feesTemplateBatchSectionProfile->batch()->batch_name . '</td>';
            $row .= '<td>' . $feesTemplateBatchSectionProfile->section()->section_name . '</td>';
            if(!empty($feesTemplateBatchSectionProfile->batch()->division())) {

            $row .= '<td>' . $feesTemplateBatchSectionProfile->batch()->division()->name . '</td>';
            } else {
                $row .= '<td></td>';
            }
            $row .= '<td>' . getNumberOfStudentByBatchSection($feesTemplateBatchSectionProfile->batch_id, $feesTemplateBatchSectionProfile->section_id)->count() . '</td>';
            $row .= '<td>'.$deleteButton.'</td>';
            $row .= '</tr>';

            return $row;

        } else {
            return "error";
        }

     } else {
            return 'error';
        }

    }


    // Fees template class section delete

    public function  feesTemplateClassSectionDelete($feesTemplateId){

         $feesTemplateProfile=$this->feesTemplateClassSection->find($feesTemplateId);
         if(!empty($feesTemplateProfile)){
             $feesTemplateProfile->delete();
             return "success";
         }
         else {
             return "wrong";
         }

    }


    public function  feesTemplateBatchSection(){

        $instituteId = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

        $feesTemplateClassSection=$this->feesTemplateClassSection->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();

        // response data array
        $data = array();
        foreach($feesTemplateClassSection as $feesTemplate) {
            $data[] = array(
                'id' =>$feesTemplate->id,
                'batch_id' => $feesTemplate->batch_id,
                'section_id' => $feesTemplate->section_id,
                'name' => $feesTemplate->batch()->batch_name . '- Section: ' . $feesTemplate->section()->section_name,
//                        'std_count' => getNumberOfStudentByBatchSection($feesTemplate->batch_id, $feesTemplate->section_id),
            );
        }

        $data=json_encode($data);

        return $data;
    }


    public function feesTemplateManage(Request $request){

//        return $request->all();


//        return $request->all();

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        // DB beginTransaction
        DB::beginTransaction();

        // fees creation
        try {

            $fees=new $this->fees();
            $fees->fee_name=$request->input('fee_name');
            $fees->institution_id=$instituteId;
            $fees->campus_id=$campus_id;
            $fees->due_date=date('Y-m-d', strtotime($request->input('due_date')));
            $fees->fee_type=$request->input('fees_type_name');
            $fees->description=$request->input('description');
            $fees->fee_status=$request->input('fee_status');
            $fees->month=$request->input('month');
            $fees->year=$request->input('year');
            $fees->partial_allowed='0';
            $feesSumbited=$fees->save();


            if($feesSumbited){

                // delete action
                $fees_delete_counter = $request->input('fees_delete_counter');
                $deleteList = $request->input('deleteList');
                // checking
                if($fees_delete_counter>0){
                    for($i=0; $i<$fees_delete_counter; $i++){
                        $this->feesItem->find($deleteList[$i])->delete();
                    }
                }

                // item creation action
                $itemCount=$request->input('fees_item_counter');
                //
                for($i=1;$i<=$itemCount;$i++){
                    $item= $request->$i;
                    $feesItem=new $this->feesItem();
                    $feesItem->item_name=$item['item_name'];
                    $feesItem->item_id=$item['item_tbl_id'];
                    $feesItem->rate=$item['rate'];
                    $feesItem->qty=$item['qty'];
                    $feesItem->status='';
                    $feesItem->fees_id=$fees->id;
                    $feesItem->save();
                }


                // discount
                $discount_name=$request->input('discount_name');
                $discount_percent=$request->input('discount_percent');

                if(!empty($discount_percent)) {

                    $feesDiscount = new $this->feesDiscount();
                    $feesDiscount->institution_id=$instituteId;
                    $feesDiscount->campus_id=$campus_id;
                    $feesDiscount->fees_id = $fees->id;
                    $feesDiscount->discount_name = $discount_name;
                    $feesDiscount->discount_percent = $discount_percent;
                    $feesDiscount->status = '';
                    $feesDiscount->save();
                }


                // fees class section and class section student add
                $auto_sms=$request->input('auto_sms');
                $csCount = $request->input('cs_count');
                $fees_id = $fees->id;
                $batchList =$request->input('batch');
                $sectionList =$request->input('section');



                //      return  $batchList=[];
                if($csCount>0) {
                    $studentIdListArray = array();

                    for ($i = 0; $i < $csCount; $i++) {
                        $batch = $batchList[$i];
                        $section = $sectionList[$i];
                        $stdList = $this->studentEnrollment->where(['batch' => $batch, 'section' => $section])->get(['std_id']);

                        if ($stdList->count() > 0) {
                            foreach ($stdList as $std) {
                                $studentFeesInvoiceProfile = $this->feesInvoice->where('fees_id', $fees_id)->where('payer_id', $std->std_id)->first();
                                // check student information profile
                                if (empty($studentFeesInvoiceProfile)) {
                                    $feesPayers = new $this->feesInvoice();
                                    $feesPayers->institution_id = $instituteId;
                                    $feesPayers->campus_id = $campus_id;
                                    $feesPayers->fees_id = $fees_id;
                                    $feesPayers->payer_id = $std->std_id;
                                    $feesPayers->payer_type = 1;
                                    $feesPayers->invoice_type = 1;
                                    $feesPayers->invoice_status = "2";
                                    $feesPayers->wf_status = "1";
                                    $result = $feesPayers->save();
                                    if ($result) {
                                        $studentIdListArray[] = $std->std_id;
                                    }
                                }
                            }

                            // fees batch section profile check
                            $feesBatchSection = new $this->feesBatchSection;
                            $feesBatchSection->institution_id = $instituteId;
                            $feesBatchSection->campus_id = $campus_id;
                            $feesBatchSection->fees_id = $fees_id;
                            $feesBatchSection->batch_id = $batchList[$i];
                            $feesBatchSection->section_id = $sectionList[$i];
                            $feesBatchSection->save();
                        }
                    }

                    $smsGetawayProfile = $this->smsInstitutionGetway->where('institution_id', $instituteId)->where('status', 1)->first();
                    if (!empty($smsGetawayProfile) && !empty($studentIdListArray) && ($auto_sms == 1)) {
                        $this->smsSender->create_fees_multisms_generate_job($fees_id, $studentIdListArray);
                    }
                }



            }




        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            // Redirecting with error message

            DB::rollback();

            return "error";
        }





        // If we reach here, then
        // data is valid and working.
        // Commit the queries!
        DB::commit();
        // return
        return 'success';

    }




}

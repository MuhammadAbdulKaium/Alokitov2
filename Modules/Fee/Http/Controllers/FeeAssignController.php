<?php

namespace Modules\Fee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Fee\Entities\FeeAssign;
use Modules\Fee\Entities\FeeInvoice;
use Modules\Student\Entities\StudentProfileView;
use Modules\Fee\Entities\FeeSubhead;
use App\Http\Controllers\Helpers\AcademicHelper;
use App;
use Illuminate\Support\Facades\DB;
class FeeAssignController extends Controller
{

    private  $feeAssign;
    private  $feeInvoice;
    private  $studentProfileView;
    private  $feeSubhead;
    private $data;
    private  $academicHelper;

    public function  __construct(FeeAssign $feeAssign, AcademicHelper $academicHelper,FeeInvoice $feeInvoice,StudentProfileView $studentProfileView,FeeSubhead $feeSubhead)
    {
        $this->feeAssign=$feeAssign;
        $this->feeInvoice=$feeInvoice;
        $this->studentProfileView=$studentProfileView;
        $this->feeSubhead=$feeSubhead;
        $this->academicHelper=$academicHelper;
    }

    public function feeAssignStore(Request $request)
    {
//        return str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
//        return $request->all();
//        return $request->student;



//        DB::beginTransaction();
//        try {

        if($request->fee_allocaitonfor==1) {
            return $this->feeAssignClassSection($request, $request->class_id, NULL, NULL);
        } elseif($request->fee_allocaitonfor==2) {
//            return '2';
            return $this->feeAssignClassSection($request, $request->class_id, $request->section, NULL);

        }elseif($request->fee_allocaitonfor==3) {
//            return '3';
             return  $this->feeAssignClassSection($request, $request->class_id, NULL, $request->student);
        }


    }


    public function feeAssignClassSection(Request $request,$class_id,$section_id,$student_id){

        DB::beginTransaction();
        try {
        $feeAssignObj= new $this->feeAssign;
        $feeAssignObj->institution_id=institution_id();
        $feeAssignObj->campus_id=campus_id();
        $feeAssignObj->head_id=$request->fee_head;
        $feeAssignObj->sub_head_id=$request->sub_head;
        $feeAssignObj->class_id=$class_id;
        $feeAssignObj->section_id=$section_id;
        $feeAssignObj->amount=$request->amount;
        $feeAssignObj->student_id=json_encode($student_id);
        $result= $feeAssignObj->save();



        // get due date and start date
         $subHeadProfile=$this->feeSubhead->where('id',$request->sub_head)->where('institution_id',institution_id())->where('campus_id',campus_id())->first();
        if($result){

            // make where cause
               $studentList=  $this->getStudentListByClassSectionStdId($class_id,$section_id,$student_id);
             foreach ($studentList as $student){
                // if invoice already exit not working
               $studntInvoiceProfile= FeeInvoice::where('student_id',$student->std_id)->where('head_id',$feeAssignObj->head_id)->where('sub_head_id',$feeAssignObj->sub_head_id)->where('academic_year',academic_year())->first();
               if(empty($studntInvoiceProfile)) {
                   // invoice Object $this
                   $invoiceObj = new $this->feeInvoice;
                   $invoiceObj->institution_id = institution_id();
                   $invoiceObj->campus_id = campus_id();
                   $invoiceObj->academic_year = academic_year();
                   $invoiceObj->fee_assign_id = $feeAssignObj->id;
                   $invoiceObj->invoice_num = mt_rand();
                   $invoiceObj->head_id = $request->fee_head;
                   $invoiceObj->class_id = $student->batch;
                   $invoiceObj->section_id = $student->section;
                   $invoiceObj->sub_head_id = $request->sub_head;
                   $invoiceObj->amount = $request->amount;
                   $invoiceObj->student_id = $student->std_id;
                   $invoiceObj->due_date = $subHeadProfile->due_date;
                   $invoiceObj->start_date = $subHeadProfile->start_date;
                   $invoiceObj->due_amount = 0;
                   $invoiceObj->year = academic_year();
                   $invoiceObj->status = 'unpaid';
                   $invoiceObj->save();
               }
            }
            DB::commit();
            Session::flash('message', 'Fee Successfully Created');
            return redirect()->back();
        }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('message', 'Something Wrongs Please Try Again');
            return redirect()->back();
        }
    }


    // fee assing delete with all invoice id

    public function feeAssignDeleteWithAllInvoices($feesId){
        $feesProfile=$this->feeAssign->find($feesId);
        if($feesProfile->delete()){
            $this->feeInvoice->where('fee_assign_id', $feesId)->delete();
            return 'success';
        } else {
            return 'error';
        }
    }

    public  function classSectionWiseStudent(Request $request){

        return  $this->studentProfileView
            ->select('std_id','first_name','middle_name','last_name')
            ->where('batch',$request->class_id)
            ->where('institute',institution_id())
            ->where('campus',campus_id())
            ->where('academic_year',academic_year())
            ->get();
    }

    public function  getStudentListByClassSectionStdId($class,$section,$studentArray){
        if(!empty($studentArray)) {
            return $studentList=$this->studentProfileView->where('institute',institution_id())->where('campus',campus_id())->where('batch',$class)->whereIn('std_id',$studentArray)->get();
        } elseif(!empty($section)) {
           return  $studentList=$this->studentProfileView->where('institute',institution_id())->where('campus',campus_id())->where('batch',$class)->where('section',$section)->get();
        } else {
        return    $studentList=$this->studentProfileView->where('institute',institution_id())->where('campus',campus_id())->where('batch',$class)->get();
        }

    }





}

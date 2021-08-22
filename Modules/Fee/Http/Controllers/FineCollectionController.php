<?php

namespace Modules\Fee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Academics\Entities\AttendanceUploadAbsent;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Fee\Entities\FeeInvoice;
use Modules\Fee\Entities\AbsetFineSetting;
use Modules\Fee\Entities\Transaction;
use Modules\Fee\Entities\SubHeadFine;
class FineCollectionController extends Controller
{

    private $data;
    private $batch;
    private $attendanceUploadAbsent;
    private $academicsYear;
    private $feeInvoice;
    private $absentFineSetting;
    private $academicHelper;
    private $transaction;

    public function  __construct(AbsetFineSetting $absetFineSetting,Transaction $transaction, AcademicHelper $academicHelper, AttendanceUploadAbsent $attendanceUploadAbsent, Batch $batch, AcademicsYear $academicsYear, FeeInvoice $feeInvoice)
    {
        $this->attendanceUploadAbsent=$attendanceUploadAbsent;
        $this->batch=$batch;
        $this->academicsYear=$academicsYear;
        $this->feeInvoice=$feeInvoice;
        $this->absentFineSetting=$absetFineSetting;
        $this->academicHelper=$academicHelper;
        $this->transaction=$transaction;

    }

    public function searchAbsentStudentList(Request $request){
//        return  $request->all();
        $this->data['academic_year']=$request->year_id;
        $this->data['class_id']=$request->class_id;
        $this->data['section_id']=$request->section;
        // query for all abasent student list
        $this->data['absentStudentList']=  $this->attendanceUploadAbsent
           ->select('std_id', DB::raw('count(*) as total_absent'))
            ->where('academic_year',$request->year_id)
            ->where('batch',$request->class_id)
            ->where('section',$request->section)
            ->where('institute',institution_id())
            ->where('campus',campus_id())
             ->groupBy('std_id')
            ->get();

        // fine rate
         $this->data['fineRate']=$this->absentFineSetting
            ->where('class',$request->class_id)
            ->where('institution_id',institution_id())
            ->where('campus_id',campus_id())->first();


        return view('fee::modal.absent-fine.studentlist',$this->data);


    }


    public function searchLateFine(Request $request){
//        return $request->all();
//        $this->data['academic_year']=$request->year_id;
//        $this->data['class_id']=$request->class_id;
//        $this->data['section_id']=$request->section;
//        $this->data['fee_head']=$request->fee_head;
//        $this->data['sub_head']=$request->sub_head;
        // query for all abasent student list
        $this->data['lateFineList']=  $this->feeInvoice
            ->where('class_id',$request->class_id)
            ->where('section_id',$request->section)
            ->where('head_id',$request->fee_head)
            ->where('sub_head_id',$request->sub_head)
            ->where('institution_id',institution_id())
            ->where('campus_id',campus_id())
            ->whereIn('status',['unpaid','partial'])
            ->where('due_date', '<=', date('Y-m-d'))
            ->get();

        return view('fee::modal.late-fine.studentlist',$this->data);
    }


    public function absentFineModalSingle($studentId,$fineRate){
        $academicYear=$this->academicHelper->getAcademicYear();
        $this->data['studentTotalAbsent']= $this->getTotalAbsent($studentId);
        $this->data['absentFineRate']=$fineRate;
        $this->data['std_id']=$studentId;
        // get preview paid amount
       $this->data['previous_paid']=$this->transaction
            ->where('academic_year',$academicYear)
            ->where('institution_id',institution_id())
            ->where('campus_id',campus_id())
            ->where('std_id',$studentId)
            ->where('payment_type',2)->sum('amount');

        return view('fee::modal.absent-fine.single-student-modal',$this->data);

    }


    // student total absent calculate
    public function getTotalAbsent($studentID){
        $academicYear=$this->academicHelper->getAcademicYear();
       return  $absentProfile=$this->attendanceUploadAbsent
            ->where('academic_year',$academicYear)
            ->where('institute',institution_id())
            ->where('campus',campus_id())
            ->where('std_id',$studentID)
            ->count();
    }

    // absent fine colleciton store
    public function  absentFineCollectionStore(Request $request){
        $academicYear=$this->academicHelper->getAcademicYear();

        $transactionObj=new $this->transaction;
        $transactionObj->institution_id=institution_id();
        $transactionObj->campus_id=campus_id();
        $transactionObj->academic_year=$academicYear;
        $transactionObj->amount=$request->paid_amount;
        $transactionObj->std_id=$request->std_id;
        $transactionObj->payment_date=date('Y-m-d');
        $transactionObj->payment_type=2; // 2 for absent fine collection
        $transactionObj->payment_status=1;
        $transactionObj->paid_by='1';
        $transactionObj->recipt_no=001;
        $transactionObj->save();
        return redirect()->back();
    }

    public function latefeeFineModalSingle($invoice_id,$totalfeefinePaid){
        // invoice profile
          $this->data['invoiceProfile']= $this->feeInvoice->find($invoice_id);
          $this->data['totalfeefinePaid']= $totalfeefinePaid;
         // get sub head fine amount
        $this->data['lateFineProfile']=SubHeadFine::where('head_id',$this->data['invoiceProfile']->head_id)
                    ->where('sub_head_id',$this->data['invoiceProfile']->sub_head_id)
                    ->first();

        return view('fee::modal.late-fine.single-student-modal',$this->data);
    }


    // late fine collection store
    public function lateFineCollectionStore(Request $request){
//        return $request->all();
        $academicYear=$this->academicHelper->getAcademicYear();
        $transactionObj=new $this->transaction;
        $transactionObj->institution_id=institution_id();
        $transactionObj->campus_id=campus_id();
        $transactionObj->invoice_id=$request->invoice_id;
        $transactionObj->academic_year=$academicYear;
        $transactionObj->amount=$request->paid_amount;
        $transactionObj->std_id=$request->std_id;
        $transactionObj->payment_date=date('Y-m-d');
        $transactionObj->payment_type=3; // 3for late fine collection
        $transactionObj->payment_status=1;
        $transactionObj->paid_by='1';
        $transactionObj->recipt_no=001;
        $transactionObj->save();

        return redirect()->back();
    }



}

<?php

namespace Modules\Fee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\Section;
use Modules\Fee\Entities\AbsetFineSetting;
use Modules\Academics\Entities\AttendanceUploadAbsent;
use Illuminate\Support\Facades\DB;
use Modules\Fee\Entities\FeeInvoice;
use Modules\Student\Entities\StudentProfileView;
use Modules\Academics\Entities\Batch;
use App;
class DueReportController extends Controller
{
    private $academicHelper;
    private $data;
    private $absentFineSetting;
    private $attendanceUploadAbsent;
    private $feeInvoice;
    private $studentProfileView;
    private $batch;
    public function  __construct( AcademicHelper $academicHelper, Batch $batch, StudentProfileView $studentProfileView, FeeInvoice $feeInvoice, AttendanceUploadAbsent $attendanceUploadAbsent, AbsetFineSetting $absentFineSetting)
    {
        $this->academicHelper=$academicHelper;
        $this->absentFineSetting=$absentFineSetting;
        $this->attendanceUploadAbsent=$attendanceUploadAbsent;
        $this->feeInvoice=$feeInvoice;
        $this->studentProfileView=$studentProfileView;
        $this->batch=$batch;

    }

    public function searchDueAbsentStudentList(Request $request){
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

        return view('fee::modal.duereport.absent-fine.studentlist',$this->data);
    }


    public function studentDueAbsentReportDownload(Request $request) {
        $this->data['instituteInfo']=$this->academicHelper->getInstituteProfile();

        $this->data['academic_year']=$request->year_id;
        $this->data['class_id']=$request->class_id;
        $this->data['section_id']=$request->section;

        // class id by class name and section id by section name
        $this->data['class_name']=Batch::getBatchNameById($request->class_id);
        $this->data['section_name']=Section::getSectionNameById($request->section);

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

        view()->share($this->data);
        //generate PDf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('fee::modal.duereport.absent-fine.download.studentlist')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }






    // search studenet fee wise due report or invoice list

    public function studentInvoiceSearch(Request $request){
         $this->data['feeInvoiceListArray']=$this->studentInvoiceSearchArray($request);
        return view('fee::modal.duereport.feewise.studentlist',$this->data);

    }


    public function studentDueReportDownload(Request $request) {
        $this->data['instituteInfo']=$this->academicHelper->getInstituteProfile();
         $this->data['feeInvoiceListArray']=$this->studentInvoiceSearchArray($request);
        view()->share($this->data);
        //generate PDf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('fee::modal.duereport.feewise.downlaod.studentlist')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function studentInvoiceSearchArray(Request $request){
        $studentInvoiceList=$this->feeInvoice
            ->where('class_id',$request->class_id)
            ->where('head_id',$request->fee_head)
            ->where('academic_year',$request->year_id)
            ->where('sub_head_id',$request->sub_head)->get();
        $invoiceArrayList=array();
        $totalPaidAmount=0; $totalPayableAmount=0; $totalAmount=0; $class='';$section='';
        foreach ($studentInvoiceList as $invoice){
            $studentProfile=$invoice->studentProfile();
            $invoiceArrayList[$invoice->id]['std_id']=$studentProfile->username;
            $invoiceArrayList[$invoice->id]['std_name']=$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name;
            $invoiceArrayList[$invoice->id]['std_roll']=$studentProfile->gr_no;
            $invoiceArrayList[$invoice->id]['std_fee_head']=$invoice->feehead()->name;
            $invoiceArrayList[$invoice->id]['std_sub_head']=$invoice->subhead()->name;
            $invoiceArrayList[$invoice->id]['std_paid_amount']=$invoice->paid_amount;
            $invoiceArrayList[$invoice->id]['std_amount']=$invoice->amount;
            $invoiceArrayList[$invoice->id]['std_due_amount']=$invoice->amount-$invoice->paid_amount;
            // calcualte total paid amount
            $totalAmount+=$invoice->amount;
            $totalPaidAmount+=$invoice->paid_amount;
            $totalPayableAmount+=$invoice->amount-$invoice->paid_amount;
            $class=$studentProfile->batch()->batch_name;;
            $section=$studentProfile->section()->section_name;;
        }
//        return $invoiceArrayList;
        return array('invoiceList'=>$invoiceArrayList,
                      'totalPaidamount'=>$totalPaidAmount,
                        'class'=>$class,
                        'section'=>$section,
                        'totalPayableAmount'=>$totalPayableAmount,
                        'totalAmount'=>$totalAmount,
        );

    }

    // search class seciton student for student wise due report
    public function  searchClassSectionStudent(Request $request){
//        return $request->all();
        $this->data['studentList']=$this->studentProfileView
            ->where('institute',institution_id())
            ->where('campus',campus_id())
            ->where('batch',$request->class_id)
            ->where('section',$request->section)
            ->where('academic_year',$request->year_id)
            ->get();
        return view('fee::modal.duereport.studentwise.studentlist',$this->data);
    }


    // due amont report student wise
    public function dueAmountReportByStudentID($stdID){
        $this->data['instituteInfo']=$this->academicHelper->getInstituteProfile();
        $this->data['studentProfile']=$this->studentProfileView
            ->where('std_id',$stdID)->first();
        $this->data['dueInvoiceList']=  $this->feeInvoice
            ->where('academic_year',academic_year())
            ->where('institution_id',institution_id())
            ->where('campus_id',campus_id())
            ->where('student_id',$stdID)
            ->whereIn('status',['unpaid','partial'])
            ->get();
        return view('fee::modal.duereport.pdf.studentwise',$this->data);

    }


    public function searchClassSectionDueReport(Request $request){

        $class=Batch::getBatchNameById($request->class_id);
        $section=Section::getSectionNameById($request->section);


        $studentList=$this->studentProfileView
            ->where('institute',institution_id())
            ->where('campus',campus_id())
            ->where('batch',$request->class_id)
            ->where('section',$request->section)
            ->where('academic_year',$request->year_id)
            ->get();

    $studentarray=[];
    foreach ($studentList as $student){
       $totalPayableAmount= $this->feeInvoice->totalPayableAmount($student->std_id);
       $totalPaidAmount= $this->feeInvoice->totalPaidAmount($student->std_id);
       $totalWaiverAmount= $this->feeInvoice->totalWaiver($student->std_id);
        $studentarray[$student->std_id]['std_id']=$student->username;
        $studentarray[$student->std_id]['std_name']=$student->first_name.' '.$student->middle_name.' '.$student->last_name;
        $studentarray[$student->std_id]['std_roll']=$student->gr_no;
        $studentarray[$student->std_id]['class']=$class;
        $studentarray[$student->std_id]['section']=$section;
        $studentarray[$student->std_id]['payable_amount']= $totalPayableAmount;
        $studentarray[$student->std_id]['paid_amount']=$totalPaidAmount;
        $studentarray[$student->std_id]['waiver_amount']=$totalWaiverAmount;
        $studentarray[$student->std_id]['due_amount']=($totalPayableAmount-$totalWaiverAmount)-$totalPaidAmount;
    }
        $this->data['instituteInfo']=$this->academicHelper->getInstituteProfile();
     $this->data['studentarray']=$studentarray;


        return view('fee::modal.duereport.pdf.due-all-student',$this->data);

    }


}

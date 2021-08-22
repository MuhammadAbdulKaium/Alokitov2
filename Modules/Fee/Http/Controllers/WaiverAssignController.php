<?php

namespace Modules\Fee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\WaiverAssign;
use Modules\Student\Entities\StudentProfileView;
use Modules\Fee\Entities\FeeWaiverType;
use Modules\Fee\Entities\FeeHead;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\AcademicHelper;
use App;
use Excel;
class WaiverAssignController extends Controller
{
    private  $waiverAssign;
    private  $studentProfileView;
    private  $feeWaiverType;
    private  $feehead;
    private $data;
    private $academicHelper;


    public function  __construct(WaiverAssign $waiverAssign, AcademicHelper $academicHelper,StudentProfileView $studentProfileView,FeeHead $feeHead, FeeWaiverType $feeWaiverType)
    {
        $this->waiverAssign=$waiverAssign;
        $this->studentProfileView=$studentProfileView;
        $this->feewaiverType=$feeWaiverType;
        $this->feehead=$feeHead;
        $this->academicHelper=$academicHelper;
    }


    public function searchClassSectionStudent(Request $request){
//        return $request->all();
            $this->data['studentList']=$this->studentProfileView->where('institute',institution_id())->where('campus',campus_id())->where('academic_year',$request->year_id)->where('section',$request->section)->where('batch',$request->class_id)->orderBy('gr_no','asc')->get();
            $this->data['feeheads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
            $this->data['feewaiverTypes']=$this->feewaiverType->where('institution_id',institution_id())->where('campus_id',campus_id())->get();
        return view('fee::modal.student-search-for-waiver',$this->data);
    }


    public function waiverAssignStore(Request $request){
//        return $request->all();
         $studentList=$request->student_ids;
        $studentListArray=explode(',', $studentList);

        // student list for loop and insert data
        foreach ($studentListArray as $key=>$value){

            // student class and section student profile
            $studentProfile=StudentProfileView::where('std_id',$value)->first();
            $waiverProfile=$this->waiverAssign
                ->where('head_id',$request->feehead)
                ->where('year_id',academic_year())
                ->where('student_id',$value)
                ->first();
            // new assign waiver
            if(empty($waiverProfile)) {
                $waiverAssignObj = new $this->waiverAssign;
                $waiverAssignObj->institution_id = institution_id();
                $waiverAssignObj->campus_id = campus_id();
                $waiverAssignObj->year_id = academic_year();
                $waiverAssignObj->head_id = $request->feehead;
                $waiverAssignObj->waiver_type = $request->waiver_type;
                $waiverAssignObj->class = $studentProfile->batch;
                $waiverAssignObj->section = $studentProfile->section;
                $waiverAssignObj->student_id = $value;
                $waiverAssignObj->amount_percentage = $request->percentage_amount;
                $waiverAssignObj->amount = $request->amount;
                $waiverAssignObj->date = date('Y-m-d');
                $waiverAssignObj->save();
            }

        }
        Session::flash('message', 'Waiver Assign Successfully');
        return redirect()->back();
    }


    // waiver assign delete
    public function waiverAssignDelete($waiverID){

        $feesProfile=$this->waiverAssign->find($waiverID);
        if($feesProfile->delete()){
            return 'success';
        } else {
            return 'error';
        }
    }

    public function downlaodWaiverAssignPdf(){

        // institiue information
        $this->data['instituteInfo']=$this->academicHelper->getInstituteProfile();

        $this->data['waiverAssignList']=$this->waiverAssign->
        where('institution_id',institution_id())
            ->where('campus_id',campus_id())
            ->where('year_id',academic_year())
            ->get();

        view()->share($this->data);
        //generate PDf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('fee::modal.waiver-report.pdf.waiver-assign')->setPaper('a4', 'portrait');
        return $pdf->stream();

    }


    public function downlaodWaiverAssignExcel(){
        $this->data['instituteInfo']=$this->academicHelper->getInstituteProfile();

        $this->data['waiverAssignList']=$this->waiverAssign->
        where('institution_id',institution_id())
            ->where('campus_id',campus_id())
            ->where('year_id',academic_year())
            ->get();

//        Excel::loadView('fee::modal.waiver-report.excel.waiver-assign', $this->data)
//            ->setTitle('Assign_Waiver_Report')
//            ->sheet('SheetName')
//            ->export('xls');

        Excel::create('Waiver-Assign-Report', function($excel) {

            $excel->sheet('New sheet', function($sheet) {

                $sheet->loadView('fee::modal.waiver-report.excel.waiver-assign',$this->data);

            });

        })->download();

    }



}

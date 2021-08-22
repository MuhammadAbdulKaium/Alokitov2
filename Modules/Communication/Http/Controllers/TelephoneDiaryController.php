<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Communication\Entities\Notice;
use Illuminate\Support\Facades\DB;
use App\Content;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Student\Entities\StudentProfileView;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Excel;

class TelephoneDiaryController extends Controller
{


    private  $academicHelper;
    private  $academicsYear;
    private $studentProfileView;
    private $department;
    private $designation;
    private $employeeInformation;

    public function __construct(AcademicHelper $academicHelper, AcademicsYear $academicsYear, StudentProfileView $studentProfileView, EmployeeDesignation $designation, EmployeeDepartment $department,EmployeeInformation $employeeInformation)
    {
        $this->academicHelper                 = $academicHelper;
        $this->academicsYear                 = $academicsYear;
        $this->studentProfileView                 = $studentProfileView;
        $this->department = $department;
        $this->designation = $designation;
        $this->employeeInformation = $employeeInformation;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */

    // student contact view page
    public function  studentContact() {


        // Academic year
        $academicYears = $this->academicsYear->where([
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus()
        ])->get();
        // all inputs
        $allInputs = array('year' => null, 'level' => null, 'batch' => null, 'section' => null, 'gr_no' => null, 'email' => null);
        // return view with vaiables
        return View('communication::pages.student-contact', compact('academicYears', 'allInputs'))->with('allEnrollments', null);

    }



    // student contact search Result
    public function studentContactSearch(Request $request) {
        $campusId  = $request->input('institute');
        $instituteId  = $request->input('campus');
        $academicYear  = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch         = $request->input('batch');
        $section       = $request->input('section');
        $grNo          = $request->input('gr_no');
        $email         = $request->input('email');
        $username         = $request->input('username');
        $returnType    = $request->input('return_type', 'view');

        // qry
        $allSearchInputs = array();

        // checking return type
        if($returnType=="json"){
            // input institute and campus id
            $allSearchInputs['campus'] = $campusId;
            $allSearchInputs['institute'] = $instituteId;
        }else{
            // input institute and campus id
            $allSearchInputs['campus'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute'] = $this->academicHelper->getInstitute();
        }

        // check academicYear
        if ($academicYear) $allSearchInputs['academic_year'] = $academicYear;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;
        // check grNo
        if ($grNo) $allSearchInputs['gr_no'] = $grNo;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        if ($username) $allSearchInputs['username'] = $username;

        // checking
        if($returnType=="json"){
            // return with variables
            return $this->studentProfileView->where($allSearchInputs)->get();
        }else{
            // search result
            $allEnrollments = $this->studentProfileView->where($allSearchInputs)->paginate(20);
            // checking
            if ($allEnrollments) {
//                return $allSearchInputs;
                // std list view maker
                $stdListView = view('communication::pages.includes.student-contact-list', compact('allEnrollments','allSearchInputs'))->render();
                // return with variables
                return ['status'=>'success', 'msg'=>'Student Contact found', 'html'=>$stdListView];
            } else {
                return ['status'=>'failed', 'msg'=>'No Records found'];
            }
        }
    }


    public function  studentContactSearchDownload(Request $request) {
//        return $request->all();

        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $grNo = $request->input('gr_no');
        $email = $request->input('email');
        $username = $request->input('username');
        // input institute and campus id
        $allSearchInputs['campus'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute'] = $this->academicHelper->getInstitute();

        // check academicYear
        if ($academicYear) $allSearchInputs['academic_year'] = $academicYear;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;
        // check grNo
        if ($grNo) $allSearchInputs['gr_no'] = $grNo;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        if ($username) $allSearchInputs['username'] = $username;

        // search result
         $allEnrollments = $this->studentProfileView->where($allSearchInputs)->get();


        view()->share(compact('allEnrollments','allSearchInputs'));
        //generate excel
        Excel::create('Student Contact List', function ($excel) {
            $excel->sheet('Student Contact List', function ($sheet) {
                // Font family
                $sheet->setFontFamily('Comic Sans MS');
                // Set font with ->setStyle()
                $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                // cell formatting
                $sheet->setAutoSize(true);
                // Set all margins
                $sheet->setPageMargin(0.25);
                // mergeCell
                // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                $sheet->loadView('communication::pages.reports.student-contact-list');
            });
        })->download('xlsx');

    }





    // employee contact view page
    public function  employeeContact() {

        // campus and institute id
        $campusId= $this->academicHelper->getCampus();
        $instituteId= $this->academicHelper->getInstitute();

        // employee designations
        $allDesignaitons = $this->designation->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('name', 'ASC')->get();
        // employee departments
        $allDepartments = $this->department->where(['institute_id'=>$instituteId, 'dept_type'=>0])->orderBy('name', 'ASC')->get();
        // // all inputs as objects
        $allInputs = ['designation' => null, 'department' => null, 'category' => null, 'email' => null, 'id' => null];
        // return view
        return view('communication::pages.employee-contact', compact('allDesignaitons', 'allDepartments', 'allInputs'))->with('allEmployee', null);
    }


    // Employee contact search Result
    public function employeeContactSearch(Request $request) {


        $campusId= $this->academicHelper->getCampus();
        $instituteId= $this->academicHelper->getInstitute();
        $designation = $request->input('designation');
        $department  = $request->input('department');
        $category    = $request->input('category');
        $email       = $request->input('email');
        $empId       = $request->input('emp_id');
        // return type
        $returnType = $request->input('return_type', 'view');

        // qry
        $allSearchInputs = array();

        // checking return type
        if($returnType=="json"){
            // input institute and campus id
            $allSearchInputs['campus_id'] = $campusId;
            $allSearchInputs['institute_id'] = $instituteId;
        }else{
            // input institute and campus id
            $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();
        }

        // check department
        if ($department) $allSearchInputs['department'] = $department;
        // check designation
        if ($designation) $allSearchInputs['designation'] = $designation;
        // check category
        if ($category) $allSearchInputs['category'] = $category;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        // check empId
        if ($empId) $allSearchInputs['id'] = $empId;

        // search result
        $allEmployee = EmployeeInformation::where($allSearchInputs)->get();
        // checking
        if($returnType=="json"){
            // return with variables
            return $allEmployee;
        }else{
            // checking
            if ($allEmployee) {
                // employee designations
                 $allDesignaitons = EmployeeDesignation::where('institute_id',$instituteId)->orderBy('name', 'ASC')->get();
                // employee departments
                 $allDepartments = EmployeeDepartment::where('institute_id',$instituteId)->orderBy('name', 'ASC')->get();
                // all inputs
                $allInputs = ['designation' => $designation, 'department' => $department, 'category' => $category, 'email' => $email, 'id' => $empId];
                // return view
                return view('communication::pages.employee-contact', compact('allDesignaitons', 'allDepartments', 'allEmployee', 'allInputs'));

            } else {
                Session::flash('warning', 'ubable to perform the action');
                // return redirect
                return redirect()->back();
            }
        }

    }


    public function  employeeContactSearchDownload(Request $request)
    {
//        return $request->all();

        $designation = $request->input('designation');
        $department = $request->input('department');
        $category = $request->input('category');
        $email = $request->input('email');
        $empId = $request->input('emp_id');

        // qry
        $allSearchInputs = array();

        // input institute and campus id
        $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();

        // check department
        if ($department) $allSearchInputs['department'] = $department;
        // check designation
        if ($designation) $allSearchInputs['designation'] = $designation;
        // check category
        if ($category) $allSearchInputs['category'] = $category;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        // check empId
        if ($empId) $allSearchInputs['id'] = $empId;

        // search result
        $allEmployee = EmployeeInformation::where($allSearchInputs)->get();
        // checking
        // checking
        if ($allEmployee) {

            view()->share(compact('allEmployee'));
            //generate excel
            Excel::create('Employee List', function ($excel) {
                $excel->sheet('Employee List', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                    $sheet->loadView('communication::pages.reports.employee-contact-list');
                });
            })->download('xlsx');


        } else {
            Session::flash('warning', 'ubable to perform the action');
            // return redirect
            return redirect()->back();
        }

    }



}

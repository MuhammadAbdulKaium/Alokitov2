<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\StudentDepartment;
use App\Http\Controllers\Helpers\AcademicHelper;
use Redirect;
use Session;
use Validator;

class EmployeeDepartmentController extends Controller
{

    private $batch;
    private $academicsLevel;
    private $academicHelper;
    private $department;
    private $studentDepartment;

    // constructor
    public function __construct(AcademicHelper $academicHelper, Batch $batch, AcademicsLevel $academicsLevel, StudentDepartment $studentDepartment, EmployeeDepartment $department)
    {
        $this->batch = $batch;
        $this->academicsLevel = $academicsLevel;
        $this->academicHelper = $academicHelper;
        $this->department = $department;
        $this->studentDepartment = $studentDepartment;
    }


    // Display a listing of the resource.
    public function index()
    {
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // all department list
        $allDepartments = $this->department->where([ 'institute_id'=>$instituteId])->orderBy('name', 'ASC')->get();
        // return view with allDepartments variable
        return view('employee::pages.department', compact('allDepartments'))->with('page', 'create');
    }

    // Show the form for creating a new resource.
    public function create()
    {
        // academic details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYearId = $this->academicHelper->getAcademicYear();

        // academic level list
        $allAcademicsLevel = $this->academicsLevel->where([
            'campus_id'=>$campusId,'institute_id'=>$instituteId])->get();
        // find department
        $departmentProfile = null;
        // return view with variables
        return view('employee::pages.modals.department-create', compact('allAcademicsLevel', 'departmentProfile'));
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'alias' => 'required',
            'dept_id' => 'required',
            'std_dept_id' => 'required',
            'dept_type' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {


            // Start transaction!
            DB::beginTransaction();
            // start creating week off day
            try {
                // dept type
                $name = $request->input('name');
                $alias = $request->input('alias');
                $deptId = $request->input('dept_id');
                $deptType = $request->input('dept_type');
                // academic details
                $campusId = $this->academicHelper->getCampus();
                $instituteId = $this->academicHelper->getInstitute();
                $academicYearId = $this->academicHelper->getAcademicYear();

                // checking deptId
                if($deptId>0){
                    // now update department
                    $departmentProfile = $this->department->find($deptId);
                }else{
                    // now create department
                    $departmentProfile = new $this->department();
                }
                // input stdDeptProfile
                $departmentProfile->name = $name;
                $departmentProfile->alias = $alias;
                $departmentProfile->dept_type = $deptType;
                $departmentProfile->campus_id = $campusId;
                $departmentProfile->institute_id = $instituteId;
                // now save $stdDeptProfile
                $departmentProfileSaved = $departmentProfile->save();

                // checking
                if ($departmentProfileSaved) {
                    // checking dept_type
                    if($deptType==0){
                        // Commit the queries!
                        DB::commit();
                        // session msg
                        Session::flash('success', 'Department Submitted');
                        // return redirect
                        return redirect()->back();
                    }else{
                        // Student Department Details
                        $stdDeptId = $request->input('std_dept_id');
                        $academicLevel = $request->input('academic_level');
                        $academicBatch = $request->input('academic_batch');

                        // checking deptId
                        if($stdDeptId>0){
                            // now update department
                            $stdDeptProfile = $this->studentDepartment->find($stdDeptId);
                        }else{
                            // checking student department
                            if($this->checkStudentDepartment($academicLevel, $academicBatch, $academicYearId, $campusId, $instituteId)){
                                // Rollback and then redirect back to form with errors Redirecting with error message
                                DB::rollback();
                                //session data
                                Session::flash('warning', ' Student Department Already exists');
                                // return redirect
                                return redirect()->back();
                            }
                            // now create department
                            $stdDeptProfile = new $this->studentDepartment();
                        }
                        // input stdDeptProfile
                        $stdDeptProfile->dept_id = $departmentProfile->id;
                        $stdDeptProfile->academic_level = $academicLevel;
                        $stdDeptProfile->academic_batch = $academicBatch;
                        $stdDeptProfile->academic_year = $academicYearId;
                        $stdDeptProfile->campus_id = $campusId;
                        $stdDeptProfile->institute_id = $instituteId;
                        // now save $stdDeptProfile
                        $stdDeptProfileSaved = $stdDeptProfile->save();
                        // checking
                        if($stdDeptProfileSaved){
                            // If we reach here, then data is valid and working.
                            // Commit the queries!
                            DB::commit();
                            // session data
                            Session::flash('success', 'Department Submitted');
                            // receiving page action
                            return redirect()->back();
                        }else{
                            // Rollback and then redirect back to form with errors
                            // Redirecting with error message
                            DB::rollback();
                            //session data
                            Session::flash('warning', 'Unable to Submit Student Department');
                            // return redirect
                            return redirect()->back();
                        }
                    }
                } else {
                    // Rollback and then redirect back to form with errors
                    // Redirecting with error message
                    DB::rollback();
                    //session data
                    Session::flash('warning', 'Unable to perform the actions');
                    // return redirect
                    return redirect()->back();
                }

            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                // Redirecting with error message
                DB::rollback();
                // session data
                Session::flash('warning', 'Fatal Error! Try catch exception');
                // receiving page action
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } else {
            Session::flash('warning', 'invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // Show the specified resource.
    public function show($id)
    {
        // find department
        $departmentProfile = $this->department->FindOrFail($id);
        // return view with allDepartments variable
        return view('employee::pages.modals.department-view', compact('departmentProfile'));
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        // academic details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYearId = $this->academicHelper->getAcademicYear();

        // academic level list
        $allAcademicsLevel = $this->academicsLevel->where([
            'campus_id'=>$campusId,'institute_id'=>$instituteId, 'academics_year_id'=>$academicYearId
        ])->get();
        // find department
        $departmentProfile = $this->department->FindOrFail($id);
        // return view with variables
        return view('employee::pages.modals.department-create', compact('allAcademicsLevel', 'departmentProfile'));
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        // department profile
        $departmentProfile = $this->department->FindOrFail($id);

        // checking
        if ($departmentProfile) {
            // checking student department
            if($studentDepartment = $departmentProfile->studentDepartment()){
                $studentDepartment->delete();
            }
            // now delete employee department
            $departmentProfileDeleted = $departmentProfile->delete();
            // checking
            if ($departmentProfileDeleted) {
                Session::flash('success', 'Department Deleted');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Uabale to delete department');
                // return redirect
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Uabale to perform the actions');
            // return redirect
            return redirect()->back();
        }
    }

    /**
     * @param $academicLevel
     * @param $academicBatch
     * @param $academicYearId
     * @param $campusId
     * @param $instituteId
     * @return  boolean
     */
    public function checkStudentDepartment($academicLevel, $academicBatch, $academicYearId, $campusId, $instituteId)
    {
        $stdDeptProfileList = $this->studentDepartment->where([
            'academic_level' => $academicLevel,
            'academic_batch' => $academicBatch,
            'academic_year' => $academicYearId,
            'campus_id' => $campusId,
            'institute_id' => $instituteId
        ])->get();
        // checking
        if($stdDeptProfileList->count()>0){
            return true;
        }else{
            return false;
        }
    }

    // //////////////////  ajax request //////////////////
    // public function findAllDepartment()
    // {
    //     // all departments
    //     $allDepartments = array();
    //     // find all departments
    //     $allDepartmentsProfile = EmployeeDepartment::orderBy('name', 'ASC')->get(['id', 'name']);
    //     // looping
    //     foreach ($allDepartmentsProfile as $department) {
    //         $allDepartments[] = array('id' => $department->id, 'name' => $department->name);
    //     }

    //     // return all employee
    //     return $allDepartments;
    // }
}

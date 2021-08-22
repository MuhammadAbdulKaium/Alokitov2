<?php

namespace Modules\Admin\Http\Controllers;


use App\User;
use App\Models\Role;
use App\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Helpers\SessionHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\division;
use Modules\Academics\Entities\Section;
use Modules\Academics\Http\Controllers\DivisionController;
use Modules\Setting\Entities\CadetPerformanceActivity;
use Modules\Setting\Entities\CadetPerformanceCategory;
use Modules\Setting\Entities\CadetPerformanceType;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\State;
use Modules\Setting\Entities\InstituteAddress;
use App\UserInfo;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Academics\Http\Controllers\AttendanceUploadController;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Employee\Http\Controllers\NationalHolidayController;
use Modules\Employee\Http\Controllers\WeekOffDayController;
use Modules\Setting\Http\Controllers\CampusController;
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\StudentProfileView;
use Modules\Student\Http\Controllers\reports\StudentAttendanceReportController;
use Modules\Student\Http\Controllers\StudentController;

class HighAdminController extends Controller
{

    private  $user;
    private  $role;
    private $studentProfileView;
    private  $roleUser;
    private  $sessionHelper;
    private  $academicHelper;
    private  $validator;
    private  $state;
    private  $instituteAddress;
    private  $attendanceUploadController;
    private  $attendanceUpload;
    private  $holidayController;
    private  $weekOffDayController;
    private  $attendanceReportController;
    private  $userInfo;


    public function __construct(User $user, Role $role, RoleUser $roleUser, SessionHelper $sessionHelper, AcademicHelper $academicHelper, Validator $validator, State $state, InstituteAddress $instituteAddress, AttendanceUploadController $attendanceUploadController, AttendanceUpload $attendanceUpload, NationalHolidayController $holidayController, WeekOffDayController $weekOffDayController, StudentAttendanceReportController $attendanceReportController, UserInfo $userInfo,StudentProfileView $studentProfileView)
    {
        $this->user = $user;
        $this->role = $role;
        $this->roleUser = $roleUser;
        $this->sessionHelper  = $sessionHelper;
        $this->academicHelper = $academicHelper;
        $this->validator = $validator;
        $this->state = $state;
        $this->instituteAddress = $instituteAddress;
        $this->attendanceUploadController = $attendanceUploadController;
        $this->attendanceUpload = $attendanceUpload;
        $this->holidayController = $holidayController;
        $this->weekOffDayController = $weekOffDayController;
        $this->attendanceReportController = $attendanceReportController;
        $this->userInfo = $userInfo;
        $this->studentProfileView = $studentProfileView;
    }


    public function highDashboardStatics(){
        $allInst=Institute::all();
        $type=CadetPerformanceType::whereIn('id',['6', '1'])->get();
        return view('admin::pages.manage-uno.pieChart',compact('allInst','type'))->with('page','institute');
    }
    public function getHighDashboardCadetRegister(){
        $allInst=Institute::all();
        $type=CadetPerformanceType::whereIn('id',['6', '1'])->get();
        return view('admin::pages.manage-uno.cadetRegister',compact('allInst','type'))->with('page','cadet');
    }
    public function getAjaxInstituteCampus($id){
        $academicYear=Campus::where('institute_id',$id)->get();
        $data = [];
        if($academicYear->count() > 0){
            array_push($data, '<option value="">-- Select --</option>');
            foreach($academicYear as $item)
            {
                array_push($data, '<option value="'. $item->id .'" data-point="'. $item->id .'">'. $item->name .'</option>');
            }
        }

        return json_encode($data) ;
    }
    public function getAjaxAcademicYear($id){
        $academicYear=AcademicsYear::where('campus_id',$id)->get();
        $data = [];
        if($academicYear->count() > 0){
            array_push($data, '<option value="">-- Select --</option>');
            foreach($academicYear as $item)
            {
                array_push($data, '<option value="'. $item->id .'" data-point="'. $item->id .'">'. $item->year_name .'</option>');
            }
        }

        return json_encode($data) ;
    }

    public function getAjaxAcademicDivision($id){
        $academicYear=division::where('campus',$id)->get();
        $data = [];
        if($academicYear->count() > 0){
            array_push($data, '<option value="">-- Select --</option>');
            foreach($academicYear as $item)
            {
                array_push($data, '<option value="'. $item->id .'" data-point="'. $item->id .'">'. $item->name .'</option>');
            }
        }

        return json_encode($data) ;
    }

    public function getAjaxAcademicBatch($id){
        $academicYear=Batch::where('academics_level_id',$id)->get();
        $data = [];
        if($academicYear->count() > 0){
            array_push($data, '<option value="">-- Select --</option>');
            foreach($academicYear as $item)
            {
                array_push($data, '<option value="'. $item->id .'" data-point="'. $item->id .'">'. $item->batch_name .'</option>');
            }
        }

        return json_encode($data) ;
    }

    public function getAjaxAcademicSection($id){
        $academicYear=Section::where('batch_id',$id)->get();
        $data = [];
        if($academicYear->count() > 0){
            array_push($data, '<option value="">-- Select --</option>');
            foreach($academicYear as $item)
            {
                array_push($data, '<option value="'. $item->id .'" data-point="'. $item->id .'">'. $item->section_name .'</option>');
            }
        }

        return json_encode($data) ;
    }

    public function getAjaxAcademicLevel($id){
        $academicYear=AcademicsLevel::where('campus_id',$id)->get();
        $data = [];
        if($academicYear->count() > 0){
            array_push($data, '<option value="">-- Select --</option>');
            foreach($academicYear as $item)
            {
                array_push($data, '<option value="'. $item->id .'" data-point="'. $item->id .'">'. $item->level_name .'</option>');
            }
        }

        return json_encode($data) ;
    }
    public function searchcadetData(Request $request){
        $allInst=Institute::all();
        $type=CadetPerformanceType::whereIn('id',['6', '1'])->get();

//        dd(session()->get('institute'));
        $institute      = $request->inst;
        $campus         = $request->campusId;
        $academicYear   = $request->year;
        $month          = $request->month;
        $academicLevel  = $request->levelID;
        $divisionID     = $request->divisionId;
        $batch          = $request->classID;
        $section        = $request->sectionID;
        $catType        = $request->cattype;
        $categoryID     = $request->categoryID;
        $categoryActivity= $request->categoryActivity;
        $username       = $request->std_username;

        $searchData = [];
        $allSearchInputs = array();

        $allSearchInputs['campus'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute'] = $this->academicHelper->getInstitute();
        if($institute) $allSearchInputs['institute'] = $institute;

        if($campus) $allSearchInputs['campus'] = $campus;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;

        if($catType != null && $categoryID == null && $categoryActivity == null)
        {
            $val = 0;
            $searchData=$this->studentProfileView->where($allSearchInputs)->get();
            foreach ($searchData as $cated)
            {
                $record = CadetAssesment::where('type', $catType)
                    ->where('institute_id', $institute)
                    ->where('campus_id', $campus)
                    ->where('student_id', $cated->std_id)->first();

                if($record == null)
                {
                    unset($searchData[$val]);
                }
                $val++;
            }
        }
        elseif ($catType != null && $categoryID != null && $categoryActivity == null)
        {
            $val = 0;
            $searchData=$this->studentProfileView->where($allSearchInputs)->get();
            foreach ($searchData as $cated)
            {
                $record = CadetAssesment::where('type', $catType)
                    ->where('institute_id', $institute)
                    ->where('campus_id', $campus)
                    ->where('performance_category_id', $categoryID)
                    ->where('student_id', $cated->std_id)->first();

                if($record == null)
                {
                    unset($searchData[$val]);
                }
                $val++;
            }
        }
        elseif ($catType != null && $categoryID != null && $categoryActivity != null)
        {
            $val = 0;
            $searchData=$this->studentProfileView->where($allSearchInputs)->get();
            foreach ($searchData as $cated)
            {
                $record = CadetAssesment::where('type', $catType)
                    ->where('institute_id', $institute)
                    ->where('campus_id', $campus)
                    ->where('performance_category_id', $categoryID)
                    ->where('cadet_performance_activity_id', $categoryActivity)
                    ->where('student_id', $cated->std_id)->first();

                if($record == null)
                {
                    unset($searchData[$val]);
                }
                $val++;
            }
        }else
        {
            $searchData=$this->studentProfileView->where($allSearchInputs)->get();
        }
//        return json_encode($searchData) ;
        $stdListView = view('admin::pages.manage-uno.partial.cadet-list', compact('searchData'))->render();
        return ['status'=>'success', 'msg'=>'Student List found', 'html'=>$stdListView];

//        return view('admin::pages.manage-uno.cadetRegister',compact('searchData','allInst','type'))->with('page','cadet');

    }

    public function getAjaxTypeCategory($id){
        $data = [];
        array_push($data, '<option value="">-- Select --</option>');
        if($id == 6){
            $category=CadetPerformanceCategory::where('id',19)->get();
        }
        else{
            $category=CadetPerformanceCategory::where('category_type_id',$id)->get();
        }
//       $category=CadetPerformanceCategory::where('category_type_id',$id)->get();

        if($category->count() > 0){

            foreach($category as $item)
            {
                array_push($data, '<option value="'. $item->id .'" data-point="'. $item->id .'">'. $item->category_name .'</option>');
            }
        }
        return json_encode($data) ;
    }

    public function getAjaxCategoryActivity($id)
    {
        $data = [];

        $activity=CadetPerformanceActivity::where('cadet_category_id',$id)->get();

        if($activity->count() > 0){
            array_push($data, '<option value="">-- Select --</option>');
            foreach($activity as $item)
            {
                array_push($data, '<option value="'. $item->id .'" data-point="'. $item->id .'">'. $item->activity_name .'</option>');
            }
        }
        return json_encode($data);
    }

}

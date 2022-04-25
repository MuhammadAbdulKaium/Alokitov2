<?php

namespace Modules\Student\Http\Controllers;

use App\Address;
use App\Http\Controllers\Helpers\AcademicHelper;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\AcademicsAdmissionYear;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Setting\Entities\Country;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\StdEnrollHistory;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentParent;
use Modules\Student\Entities\StudentProfileView;
use App;
class CadetBulkEditController extends Controller
{
    private $academicHelper;
    public function __construct(AcademicHelper $academicHelper)
    {

        $this->academicHelper = $academicHelper;
    }
    public function index()
    {
        $studentInfos = StudentInformation::with('hobbyDreamIdolAim', 'singleEnrollment', 'singleEnrollment.singleBatch', 'singleEnrollment.singleSection', 'nationalitys')->where([['campus', $this->academicHelper->getCampus()], ['institute', $this->academicHelper->getInstitute()]])->get();
        $countries = Country::all();
        $academicYears = $this->academicHelper->getAllAcademicYears();
        $batches = Batch::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $section = Section::all();
        return view("student::pages.cadet-bulk-edit.cadet-bulk-edit", compact('studentInfos', 'countries', 'academicYears', 'batches'));
    }

    public function searchSection(Request $request)
    {
        return Section::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            "batch_id" => $request->batch
        ])->get();
    }

    public function searchGenarateForm(Request $request)
    {
       
        $authRole = Auth::user()->role()->name;
        $selectForms = $request->selectForm;
        $countries = Country::all();
        $academicAdmissionYear = AcademicsAdmissionYear::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $academicYears = AcademicsYear::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $batches = Batch::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $sections = Section::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $levels = AcademicsLevel::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $classId = $request->classId;
        $sectionId = $request->sectionId;
        $searchBatchSection = [];
        if ($classId) {
            $searchBatchSection['batch'] = $classId;
        }
        if ($sectionId) {
            $searchBatchSection['section'] = $sectionId;
        }

        $searchAllStudent = [];
        $parent = StudentParent::pluck('std_id');
        $studentassesment = StudentProfileView::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute()
        ])->pluck('std_id')->toArray();
        if($request->showNull){
            if(isset($request->selectForm)){
    
                foreach ($request->selectForm as $form) {
        
                    if ($form == "section") {
                        $sectionNull = StudentProfileView::where([
                            'section' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('std_id')->toArray();
                        $sectionEmpty = StudentProfileView::where([
                            'section' => 0,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $sectionNull);
                        $searchAllStudent = array_merge($searchAllStudent, $sectionEmpty);
                    }
                    if ($form == "batch") {
        
                        $batchNull = StudentProfileView::where([
                            'batch' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('std_id')->toArray();
                        $batchEmpty = StudentProfileView::where([
                            'batch' => 0,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $batchNull);
                        $searchAllStudent = array_merge($searchAllStudent, $batchEmpty);
                    }
        
                    if ($form == "academicLevel") {
                        $levelNull = StudentProfileView::where([
                            'academic_level' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('std_id')->toArray();
                        $levelEmptye =  StudentProfileView::where([
                            'academic_level' => 0,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $levelNull);
                        $searchAllStudent = array_merge($searchAllStudent, $levelEmptye);
                    }
                    if ($form == "academicYear") {
                        $academicYearNull = StudentProfileView::where([
                            'academic_year' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('std_id')->toArray();
                        $academicYearEmpty =  StudentProfileView::where([
                            'academic_year' => 0,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $academicYearNull);
                        $searchAllStudent = array_merge($searchAllStudent, $academicYearEmpty);
                    }
                    if ($form == "admissionYear") {
                        $admissionYearEmpty = StudentEnrollment::where([
                            'admission_year' => 0,
                        ])->pluck('std_id')->toArray();
                        $admissionYearNull = StudentEnrollment::where([
                            'admission_year' => null,
                        ])->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $admissionYearEmpty);
                        $searchAllStudent = array_merge($searchAllStudent, $admissionYearNull);
                    }
                    if ($form == "MotherEmail") {
                        $MotherEmail = StudentGuardian::where([
                            'type' => 0,
                            'email' => null
                        ])->pluck('id')->toArray();
                        $parent = StudentParent::whereIn('gud_id',$MotherEmail)->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $parent);
                    }
                    if ($form == "MotherContact") {
                        $MotherContact = StudentGuardian::where([
                            'type' => 0,
                            'mobile' => null
                        ])->pluck('id')->toArray();
                        $parent = StudentParent::whereIn('gud_id',$MotherContact)->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $parent);
                    }
                    if ($form == "MotherOccupation") {
                        $MotherOccupation = StudentGuardian::where([
                            'type' => 0,
                            'occupation' => null
                        ])->pluck('id')->toArray();
                        $parent = StudentParent::whereIn('gud_id',$MotherOccupation)->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $parent);
                    }
                    if ($form == "MotherName") {
                        $MotherName = StudentGuardian::where([
                            'type' => 0,
                            'first_name' => null
                        ])->pluck('id')->toArray();
                        $parent = StudentParent::whereIn('gud_id',$MotherName)->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $parent);
                    }
                    if ($form == "FatherEmail") {
                        $FatherEmail = StudentGuardian::where([
                            'type' => 1,
                            'email' => null
                        ])->pluck('id')->toArray();
                        $parent = StudentParent::whereIn('gud_id',$FatherEmail)->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $parent);
                    }
                    if ($form == "FatherContact") {
                        $FatherContact = StudentGuardian::where([
                            'type' => 1,
                            'mobile' => null
                        ])->pluck('id')->toArray();
                        $parent = StudentParent::whereIn('gud_id',$FatherContact)->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $parent);
                    }
                    if ($form == "FatherOccupation") {
                        $FatherOccupation = StudentGuardian::where([
                            'type' => 1,
                            'occupation' => null
                        ])->pluck('id')->toArray();
                        $parent = StudentParent::whereIn('gud_id',$FatherOccupation)->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $parent);
                    }
                    if ($form == "FatherName") {
                          $FatherNameEmpty = StudentGuardian::where([
                            'type' => 1,
                            'first_name' => null,
                        ])->pluck('id')->toArray();
                        $parent = StudentParent::whereIn('gud_id',$FatherNameEmpty)->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $parent);
                    }
        
                    if ($form == "Idol") {
                        $Idol = CadetAssesment::where(['type' => 6])->pluck('student_id')->toArray();
                        $idolStdId = array_diff($studentassesment,$Idol);
                        $searchAllStudent = array_merge($searchAllStudent, $idolStdId);
                    }
                    if ($form == "Dream") {
                        $Dream = CadetAssesment::where(['type' => 5])->pluck('student_id')->toArray();
                        $dreamStdId = array_diff($studentassesment,$Dream);
                        $searchAllStudent = array_merge($searchAllStudent, $dreamStdId);
                    }
                    if ($form == "Aim") {
                        $Aim = CadetAssesment::where(['type' => 4])->pluck('student_id')->toArray();
                        $AimStdId = array_diff($studentassesment,$Aim);
                        $searchAllStudent = array_merge($searchAllStudent, $AimStdId);
                    }
                    if ($form == "Hobby") {
                        $Hobby = CadetAssesment::where(['type' => 3])->pluck('student_id')->toArray();
                        $HobbyStdId = array_diff($studentassesment,$Hobby);
                        
                        $searchAllStudent = array_merge($searchAllStudent, $HobbyStdId);
                    }
        
                    if ($form == "IdentificationMarks") {
                        $identification = StudentInformation::where([
                            'identification_mark' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $identification);
                    }
                    if ($form == "Language") {
                        $Language = StudentInformation::where([
                            'language' => ' ',
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $Language);
                    }
                    if ($form == "Nationality") {
                        $Nationality = StudentInformation::where([
                            'nationality' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $Nationality);
                    }
                    if ($form == "PermanentAddress") {
                     
                        $AddressNull = Address::where(['address' => null, 'type' => 'STUDENT_PERMANENT_ADDRESS'])->pluck('user_id')->toArray();
                        $AddressEmpty = Address::where(['address' =>" ", 'type' => 'STUDENT_PERMANENT_ADDRESS'])->pluck('user_id')->toArray();
                        $PermanentAddressNull = StudentProfileView::where([
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->whereIn('user_id',$AddressNull)->pluck('std_id')->toArray();

                        $PermanentAddressEmpty = StudentProfileView::where([
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->whereIn('user_id',$AddressEmpty)->pluck('std_id')->toArray();

                        $searchAllStudent = array_merge($searchAllStudent, $PermanentAddressEmpty);
                        $searchAllStudent = array_merge($searchAllStudent, $PermanentAddressNull);
                    }
                    if ($form == "PresentAddress") {
                      

                        $EmptyeAddress = Address::where(['address' =>" ", 'type' => 'STUDENT_PRESENT_ADDRESS'])->pluck('user_id')->toArray();
                        $nullAddress = Address::where(['address' => null, 'type' => 'STUDENT_PRESENT_ADDRESS'])->pluck('user_id')->toArray();
                        $PresentAddressNull = StudentProfileView::where([
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->whereIn('user_id',$nullAddress)->pluck('std_id')->toArray();
                        $PresentAddressEmptye = StudentProfileView::where([
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->whereIn('user_id',$EmptyeAddress)->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $PresentAddressNull);
                        $searchAllStudent = array_merge($searchAllStudent, $PresentAddressEmptye);
                    }
                    if ($form == "MeritPosition") {
                        $meridPosition = StudentProfileView::where([
                            'gr_no' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $meridPosition);
                    }
                    if ($form == "BloodGroup") {
                        $BloodGroup =  StudentInformation::where([
                            'blood_group' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $BloodGroup);
                    }
                    if ($form == "Religion") {
                        // religion
                        $religionNull = StudentProfileView::where([
                            'religion' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('std_id')->toArray();
                        $religionEmpty = StudentProfileView::where([
                            'religion' => 0,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('std_id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $religionNull);
                        $searchAllStudent = array_merge($searchAllStudent, $religionEmpty);
                    }
                    if ($form == "BirthPlace") {
                        // birth_place
                        $birth_place =  StudentInformation::where([
                            'birth_place' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $birth_place);
                    }
                    if ($form == "DateofBirth") {
                        // dob
                        $dob =  StudentInformation::where([
                            'dob' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $dob);
                    }
                    if ($form == "Gender") {
                        // gender
                        $gender =  StudentInformation::where([
                            'gender' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $gender);
                    }
                    if ($form == "BengaliName") {
                        // bn_fullname
                        $bn_fullname =  StudentInformation::where([
                            'bn_fullname' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $bn_fullname);
                    }
                    if ($form == "NickName") {
                        // middle_name
                        $middle_name =  StudentInformation::where([
                            'middle_name' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $middle_name);
                    }
                    if ($form == "LastName") {
                        $last_name =  StudentInformation::where([
                            'last_name' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $last_name);
                    }
                    if ($form == "FirstName") {
                        $first_name =  StudentInformation::where([
                            'first_name' => null,
                            'campus' => $this->academicHelper->getCampus(),
                            'institute' => $this->academicHelper->getInstitute()
                        ])->pluck('id')->toArray();
                        $searchAllStudent = array_merge($searchAllStudent, $first_name);
                    }
                }
            }
            $searchAllUniqueStudent = array_unique($searchAllStudent);
        }
        if ($request->showNull  && $request->classId && $request->sectionId) {
            // $studentInfos 
            if (isset($searchAllUniqueStudent)) {
                $studentInfos = StudentProfileView::with('singleBatch', 'singleSection', 'academicYear', 'academicLevel', 'getStudentAddress', 'singleUser', 'singleStudent.singleEnrollment', 'singleStudent.singleEnrollment.admissionYear', 'singleStudent.singleUser', 'singleStudent','singleStudent.singleParent.singleGuardian', 'singleStudent.nationalitys', 'singleStudent.hobbyDreamIdolAim')
                    ->where([
                        'batch' => $request->classId,
                        'section' => $request->sectionId,
                        'campus' => $this->academicHelper->getCampus(),
                        'institute' => $this->academicHelper->getInstitute()
                    ])->whereIn('std_id', array_values($searchAllUniqueStudent))->get();
            } else {
                $studentInfos = [];
            }
        } else if ($request->showNull  && $request->classId) {
            if (isset($searchAllUniqueStudent)) {
                $studentInfos = StudentProfileView::with('singleBatch','singleStudent.singleParent.singleGuardian', 'singleSection', 'academicYear', 'academicLevel', 'getStudentAddress', 'singleUser', 'singleStudent.singleEnrollment', 'singleStudent.singleEnrollment.admissionYear', 'singleStudent.singleUser', 'singleStudent', 'singleStudent.nationalitys', 'singleStudent.hobbyDreamIdolAim')
                    ->where([
                        'batch' => $request->classId,
                        'campus' => $this->academicHelper->getCampus(),
                        'institute' => $this->academicHelper->getInstitute()
                    ])->whereIn('std_id', array_values($searchAllUniqueStudent))->get();
            } else {
                $studentInfos = [];
            }
        } else if ($request->showNull) {
            // return $searchAllUniqueStudent;
            if (isset($searchAllUniqueStudent)) {
                $studentInfos = StudentProfileView::with('singleBatch', 'singleSection','singleStudent.singleParent.singleGuardian', 'academicYear', 'academicLevel', 'getStudentAddress', 'singleUser', 'singleStudent.singleEnrollment', 'singleStudent.singleEnrollment.admissionYear', 'singleStudent.singleUser', 'singleStudent', 'singleStudent.nationalitys', 'singleStudent.hobbyDreamIdolAim')
                    ->where([
                        'campus' => $this->academicHelper->getCampus(),
                        'institute' => $this->academicHelper->getInstitute()
                    ])->whereIn('std_id', array_values($searchAllUniqueStudent))->get();
            } else {
                $studentInfos = [];
            }
        } else {
            $studentInfos = StudentProfileView::with('singleBatch', 'singleSection','singleStudent.singleParent.singleGuardian', 'academicYear', 'academicLevel', 'getStudentAddress', 'singleUser', 'singleStudent.singleEnrollment', 'singleStudent.singleEnrollment.admissionYear', 'singleStudent.singleUser', 'singleStudent', 'singleStudent.nationalitys', 'singleStudent.hobbyDreamIdolAim')
                ->where($searchBatchSection)
                ->where(['campus' => $this->academicHelper->getCampus(), 'institute' => $this->academicHelper->getInstitute()])->get();
        }
        if($request->search_type == "Print"){
            $user = Auth::user();
             $institute = Institute::findOrFail($this->academicHelper->getInstitute());
             $pdf = App::make('dompdf.wrapper');
             $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->loadView('student::pages.cadet-bulk-edit.cadet-bulk-edit-print', compact('user','institute','authRole', 'batches', 'academicAdmissionYear', 'academicYears', 'sections', 'levels', 'studentInfos', 'selectForms', 'countries'))
            ->setPaper('a1', 'landscape');
            return $pdf->stream();
        }else{
            return view("student::pages.cadet-bulk-edit.cadet-bulk-edit-form", compact('authRole', 'batches', 'academicAdmissionYear', 'academicYears', 'sections', 'levels', 'studentInfos', 'selectForms', 'countries'));

        }
    }
    public function studentGuardianUpdate($guardians,$userName){
        $user = User::all();
        foreach($guardians as $guardian){
            // Mother's
            if($guardian->type ==0){
                $oldUserName =  $user->where('id',$guardian->user_id)->first();
                $userExplode=  explode('-',$oldUserName->username);
                if(sizeof($userExplode)>2){
                    $newUserName = $userExplode[0].'-'.$userExplode[1].'-'.$userName;
                    $oldUserName->update([
                        'username'=>$newUserName
                    ]);
                }
            }
            // Father's
            if($guardian->type == 1){
                $oldUserName =  $user->where('id',$guardian->user_id)->first();
                $userExplode=  explode('-',$oldUserName->username);
                if(sizeof($userExplode)>2){
                    $newUserName = $userExplode[0].'-'.$userExplode[1].'-'.$userName;
                    $oldUserName->update([
                        'username'=>$newUserName
                    ]);
                }
            }
            // Sister
            if($guardian->type ==2){
                $oldUserName =  $user->where('id',$guardian->user_id)->first();
                $userExplode=  explode('-',$oldUserName->username);
                if(sizeof($userExplode)>2){
                    $newUserName = $userExplode[0].'-'.$userExplode[1].'-'.$userName;
                    $oldUserName->update([
                        'username'=>$newUserName
                    ]);
                }
            }
            // Brother
            if($guardian->type ==3){
                $oldUserName =  $user->where('id',$guardian->user_id)->first();
                $userExplode=  explode('-',$oldUserName->username);
                if(sizeof($userExplode)>2){
                    $newUserName = $userExplode[0].'-'.$userExplode[1].'-'.$userName;
                    $oldUserName->update([
                        'username'=>$newUserName
                    ]);
                }
            }
            // Relative
            if($guardian->type ==4){
                $oldUserName =  $user->where('id',$guardian->user_id)->first();
                $userExplode=  explode('-',$oldUserName->username);
                if(sizeof($userExplode)>2){
                    $newUserName = $userExplode[0].'-'.$userExplode[1].'-'.$userName;
                    $oldUserName->update([
                        'username'=>$newUserName
                    ]);
                }
            }
            // Other
            if($guardian->type ==5){
                $oldUserName =  $user->where('id',$guardian->user_id)->first();
                $userExplode=  explode('-',$oldUserName->username);
                if(sizeof($userExplode)>2){
                    $newUserName = $userExplode[0].'-'.$userExplode[1].'-'.$userName;
                    $oldUserName->update([
                        'username'=>$newUserName
                    ]);
                }
            }
            // Spouse
            if($guardian->type ==6){
                $oldUserName =  $user->where('id',$guardian->user_id)->first();
                $userExplode=  explode('-',$oldUserName->username);
                if(sizeof($userExplode)>2){
                    $newUserName = $userExplode[0].'-'.$userExplode[1].'-'.$userName;
                    $oldUserName->update([
                        'username'=>$newUserName
                    ]);
                }
            }
            // Son
            if($guardian->type ==7){
                $oldUserName =  $user->where('id',$guardian->user_id)->first();
                $userExplode=  explode('-',$oldUserName->username);
                if(sizeof($userExplode)>2){
                    $newUserName = $userExplode[0].'-'.$userExplode[1].'-'.$userName;
                    $oldUserName->update([
                        'username'=>$newUserName
                    ]);
                }
            }
            // Daughter
            if($guardian->type ==8){
                $oldUserName =  $user->where('id',$guardian->user_id)->first();
                $userExplode=  explode('-',$oldUserName->username);
                if(sizeof($userExplode)>2){
                    $newUserName = $userExplode[0].'-'.$userExplode[1].'-'.$userName;
                    $oldUserName->update([
                        'username'=>$newUserName
                    ]);
                }
            }
        }
    }
    public function bulkEditSaveData(Request $request)
    {

        
        DB::beginTransaction();
        try {
            if (isset($request->upload)) {
                foreach ($request->upload as $key => $value) {
        
        
                    $studentEnroment = StudentEnrollment::where('std_id', $key)->first();
                    $admissionYear = isset($request->admissionYear) ? (int) $request->admissionYear[$key] : $studentEnroment->admission_year;
                    $academicYear = isset($request->academicYear) ? (int) $request->academicYear[$key] : $studentEnroment->academic_year;
                    $academicLevel = isset($request->academicLevel) ? (int) $request->academicLevel[$key] : $studentEnroment->academic_level;
                    $batch =  isset($request->batch) ? (int) $request->batch[$key] : $studentEnroment->batch;
                    $section = isset($request->section) ? (int) $request->section[$key] : $studentEnroment->section;
        
                    // return $batch ;
                    // return gettype($batch) ;
                    if ($studentEnroment) {
                        $studentEnroment->update([
                            'admission_year' => $admissionYear,
                            'academic_year' => $academicYear,
                            'academic_level' => $academicLevel,
                            'batch' => $batch,
                            'section' => $section,
                        ]);
                    }
                    // return $studentEnroment;
        
                    //student  information for StudentInformation Table chack by campus id , institute id, id
                    $studentUserId =  StudentProfileView::where('std_id', $key)->first();
                    $studentInformation = StudentInformation::where([['campus', $this->academicHelper->getCampus()], ['institute', $this->academicHelper->getInstitute()], ['user_id', $studentUserId->user_id]])->first();
                    //student information for User Table chack by id
                    $user = User::where('id', $studentInformation->user_id)->first();
        
                    if (isset($request->user_name[$key])) {
                        $checkUser =  User::where('username', $request->user_name[$key])->first();
                    } else {
                        $checkUser = null;
                    }
                    $parent = StudentParent::where('std_id', $key)->pluck('gud_id');
                    $studentGuardian = StudentGuardian::whereIn('id', array_values($parent->toArray()))->get();
                    //student Mother's information for StudentGuardian Table chack by usre_id, type
                    $mother = StudentGuardian::where('type', 0)->whereIn('id', array_values($parent->toArray()))->first();
                    //student Father's informationor for StudentGuardian Table chack by user_id, type
                    $father = StudentGuardian::where('type', 1)->whereIn('id', array_values($parent->toArray()))->first();
        
                    // Student meridPosition & tution_fees for StudentEnrolement Table chack by std_id
                    $meridPosition = StudentEnrollment::where('std_id', $key)->first();
        
                    // Student presentAddress for Address Table chack by 
                    $presentAddress = Address::where(['user_id' => $user->id, 'type' => 'STUDENT_PRESENT_ADDRESS'])->first();
                    $permanentAddress = Address::where(['user_id' => $user->id, 'type' => 'STUDENT_PERMANENT_ADDRESS'])->first();
        
                    // get Student Information Data
                    $username =  isset($request->user_name) ? $request->user_name[$key] : $user->username;
                    $first_name =  isset($request->first_name) ? $request->first_name[$key] : $studentInformation->first_name;
                    $last_name =  isset($request->last_name) ? $request->last_name[$key] : $studentInformation->last_name;
                    $nickname =  isset($request->nickname) ? $request->nickname[$key] : $studentInformation->middle_name;
                    $bn_fullname =  isset($request->bn_fullname) ? $request->bn_fullname[$key] : $studentInformation->bn_fullname;
                    $gender = isset($request->gender) ? $request->gender[$key] : $studentInformation->gender;
                    $dob = isset($request->dob) ? $request->dob[$key] : $studentInformation->dob;
                    $birth_place =  isset($request->birth_place) ? $request->birth_place[$key] : $studentInformation->birth_place;
                    $religion =  isset($request->religion) ? $request->religion[$key] : $studentInformation->religion;
                    $blood_group = isset($request->blood_group) ? $request->blood_group[$key] : $studentInformation->blood_group;
                    $nationality =  isset($request->nationality) ? $request->nationality[$key] : $studentInformation->nationality;
                    $language =  isset($request->language) ? $request->language[$key] : $studentInformation->language;
                    $identification_mark =  isset($request->identification_mark) ? $request->identification_mark[$key] : $studentInformation->identification_mark;
        
                    // get Student Father's Data
                    $fathername = isset($request->fathername) ? $request->fathername[$key] : $father->first_name;
                    $fatheremail =  isset($request->fatheremail) ? $request->fatheremail[$key] : $father->email;
                    $fathercontact =  isset($request->fathercontact) ? $request->fathercontact[$key] : $father->mobile;
                    $fatheroccupation =  isset($request->fatheroccupation) ? $request->fatheroccupation[$key] : $father->occupation;
                    // get Student Mother's Data
                    $mothername =  isset($request->mothername) ? $request->mothername[$key] : $mother->first_name;
                    $motheremail =  isset($request->motheremail) ? $request->motheremail[$key] : $mother->email;
                    $mothercontact =  isset($request->mothercontact) ? $request->mothercontact[$key] : $mother->mobile;
                    $motheroccupation =  isset($request->motheroccupation) ? $request->motheroccupation[$key] : $mother->occupation;
                    // get Student address Data
                    $presentaddress = null;
                    if (isset($request->presentaddress)) {
                        $presentaddress = $request->presentaddress[$key];
                    } else if ($presentAddress) {
                        $presentaddress = $presentAddress->address;
                    } else {
                        $presentaddress = null;
                    }
                    $permanentaddress = null;
                    if (isset($request->permanentaddress)) {
                        $permanentaddress = $request->permanentaddress[$key];
                    } else if ($permanentAddress) {
                        $permanentaddress = $permanentAddress->address;
                    } else {
                        $permanentaddress = null;
                    }
        
                    // get Student StudentEnrollment Data
                    $gr_no =  isset($request->gr_no) ? $request->gr_no[$key] : $meridPosition->gr_no;
        
        
                    if ($studentInformation) {
                        // return "ache";
                        if ($request->user_name || $request->first_name || $request->last_name || $request->middle_name || $request->bn_fullname || $request->gender || $request->dob || $request->birth_place || $request->birth_place || $request->religion || $request->blood_group || $request->nationality || $request->language || $request->identification_mark) {
                            if ($checkUser &&  $request->user_name[$key] != $user->username) {
                                return response()->json([
                                    'errors' => "Cadet Number Duplicate Enty!"
                                ]);
                            } else {
                                $studentInformation->update([
                                    'first_name' =>  $first_name,
                                    'last_name' => $last_name,
                                    'middle_name' => $nickname,
                                    'bn_fullname' => $bn_fullname,
                                    'gender' => $gender,
                                    'dob' => $dob,
                                    'birth_place' => $birth_place,
                                    'religion' => $religion,
                                    'blood_group' => $blood_group,
                                    'nationality' => $nationality,
                                    'language' => $language,
                                    'identification_mark' => $identification_mark,
                                    'updated_at' => Carbon::now(),
                                    'updated_by' => Auth::id()
                                ]);
                                $user->update([
                                    'username' =>  $username
                                ]);
                                self::studentGuardianUpdate($studentGuardian, $username);
                            }
                        }
                    }
                    //  Father's Information Update
        
                    if ($father) {
                        if ($request->fathername || $request->fatheremail || $request->fathercontact || $request->fatheroccupation) {
                            $father->update([
                                'first_name' => $fathername,
                                'email' => $fatheremail,
                                'mobile' => $fathercontact,
                                'occupation' => $fatheroccupation
                            ]);
                        }
                    }
                    // //  Mother's Information Upadate
                    if ($mother) {
                        if ($request->mothername || $request->motheremail || $request->mothercontact || $request->motheroccupation) {
                            $mother->update([
                                'first_name' => $mothername,
                                'email' => $motheremail,
                                'mobile' => $mothercontact,
                                'occupation' => $motheroccupation
                            ]);
                        }
                    }
                    // //PresentAddress Update
                    if (!empty($presentAddress)) {
                        // return "update"
                        $presentAddress->update([
                            'address' => $presentaddress
                        ]);
                    } else {
                        // return "Create";
                        if (!empty($request->presentaddress[$key])) {
                            Address::create([
                                'user_id' => $user->id,
                                'type' => 'STUDENT_PRESENT_ADDRESS',
                                'address' => isset($request->presentaddress) ? $request->presentaddress[$key] : null
                            ]);
                        }
                    }
        
                    //  permanentAddress Update
                    if (!empty($permanentAddress)) {
                        // return "update"
                        $permanentAddress->update([
                            'address' => $permanentaddress
                        ]);
                    } else {
                        if (!empty($request->permanentaddress[$key])) {
                            Address::create([
                                'user_id' => $user->id,
                                'type' => 'STUDENT_PERMANENT_ADDRESS',
                                'address' => isset($request->permanentaddress) ? $request->permanentaddress[$key] : null
                            ]);
                        }
                    }
        
        
                    // // meridPosition update
                    if ($meridPosition) {
                        if ($request->gr_no) {
                            $meridPosition->update([
                                'gr_no' => $gr_no,
                            ]);
                        }
                        StdEnrollHistory::create([
                            'enroll_id' => $meridPosition->id,
                            'gr_no' => $gr_no,
                            'section' => $section,
                            'batch' => $batch,
                            'academic_level' => $academicLevel,
                            'academic_year' => $academicYear,
                            'enrolled_at' => Carbon::now(),
                            'batch_status' => $meridPosition->batch_status,
                            'remark' => $meridPosition->remark,
                            'admission_year' => $admissionYear,
                            'enroll_status' => $meridPosition->enroll_status,
                        ]);
                    }
        
                    // delet && create Student Assesment Data
                    // CadetAssesment 
                    $hobby = CadetAssesment::where(['student_id' => $key, 'type' => 3])->first();
                    $aim = CadetAssesment::where(['student_id' => $key, 'type' => 4])->first();
                    $dream = CadetAssesment::where(['student_id' => $key, 'type' => 5])->first();
                    $idol = CadetAssesment::where(['student_id' => $key, 'type' => 6])->first();
                    // get Student assesment Data
                    if ($hobby) {
        
                        $input_hobby =  isset($request->hobby) ? $request->hobby[$key] : $hobby->remarks;
                    }
                    if ($aim) {
        
                        $input_aim =  isset($request->aim) ? $request->aim[$key] : $aim->remarks;
                    }
                    if ($dream) {
        
                        $input_dream =  isset($request->dream) ? $request->dream[$key] : $dream->remarks;
                    }
                    if ($idol) {
        
                        $input_idol =  isset($request->idol) ? $request->idol[$key] : $idol->remarks;
                    }
                    $academics_year_id = isset($request->academicYear) ? $request->academicYear[$key] : 0;
                    $academics_level_id = isset($request->academicLevel) ? $request->academicLevel[$key] : 0;
                    $section_id = isset($request->section) ? $request->section[$key] : 0;
                    $batch_id = isset($request->batch) ? $request->batch[$key] : 0;
        
                    $assHobby = isset($request->hobby) ? $request->hobby[$key] : " ";
                    $assAim =  isset($request->aim) ? $request->aim[$key] : " ";
                    $assDream = isset($request->dream) ? $request->dream[$key] : " ";
                    $assIdol = isset($request->idol) ? $request->idol[$key] : " ";
                    if (!empty($hobby) && empty($request->hobby[$key])) {
                        $hobby->forceDelete();
                    } else if (!empty($hobby) && !empty($request->hobby[$key])) {
                        // return "update"
                        $hobby->update(['remarks' => $input_hobby]);
                    } else {
                        if (!empty($request->hobby[$key])) {
                            CadetAssesment::create([
                                'student_id' => $key,
                                'campus_id' => $this->academicHelper->getCampus(),
                                'institute_id' => $this->academicHelper->getInstitute(),
                                'academics_year_id' => $academics_year_id,
                                'academics_level_id' => $academics_level_id,
                                'section_id' => $section_id,
                                'batch_id' => $batch_id,
                                'date' => Carbon::now(),
                                'type' => 3,
                                'remarks' => $assHobby
        
                            ]);
                        }
                    }
                    if (!empty($aim) && empty($request->aim[$key])) {
                        $aim->forceDelete();
                    } else if (!empty($aim) && !empty($request->aim[$key])) {
                        // return "update"
                        $aim->update(['remarks' => $input_aim]);
                    } else {
                        if (!empty($request->aim[$key])) {
                            CadetAssesment::create([
                                'student_id' => $key,
                                'campus_id' => $this->academicHelper->getCampus(),
                                'institute_id' => $this->academicHelper->getInstitute(),
                                'academics_year_id' => $academics_year_id,
                                'academics_level_id' => $academics_level_id,
                                'section_id' => $section_id,
                                'batch_id' => $batch_id,
                                'date' => Carbon::now(),
                                'type' => 4,
                                'remarks' => $assAim
        
                            ]);
                        }
                    }
                    if (!empty($dream) && empty($request->dream[$key])) {
                        $dream->forceDelete();
                    } else if (!empty($dream) && !empty($request->dream[$key])) {
                        // return "update"
                        $dream->update(['remarks' => $input_dream]);
                    } else {
                        if (!empty($request->dream[$key])) {
                            CadetAssesment::create([
                                'student_id' => $key,
                                'campus_id' => $this->academicHelper->getCampus(),
                                'institute_id' => $this->academicHelper->getInstitute(),
                                'academics_year_id' => $academics_year_id,
                                'academics_level_id' => $academics_level_id,
                                'section_id' => $section_id,
                                'batch_id' => $batch_id,
                                'date' => Carbon::now(),
                                'type' => 5,
                                'remarks' => $assDream
        
                            ]);
                        }
                    }
                    if (!empty($idol) && empty($request->idol[$key])) {
                        $idol->forceDelete();
                    } else if (!empty($idol) && !empty($request->idol[$key])) {
                        // return "update"
                        $idol->update(['remarks' => $input_idol]);
                    } else {
                        if (!empty($request->idol[$key])) {
                            CadetAssesment::create([
                                'student_id' => $key,
                                'campus_id' => $this->academicHelper->getCampus(),
                                'institute_id' => $this->academicHelper->getInstitute(),
                                'academics_year_id' => $academics_year_id,
                                'academics_level_id' => $academics_level_id,
                                'section_id' => $section_id,
                                'batch_id' => $batch_id,
                                'date' => Carbon::now(),
                                'type' => 6,
                                'remarks' => $assIdol
        
                            ]);
                        }
                    }
                }
            } else {
                return response()->json([
                    'errors' => "Please Min 1 row Select !"
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return back();
    }
}

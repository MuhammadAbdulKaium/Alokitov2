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
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\StdEnrollHistory;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentParent;
use Modules\Student\Entities\StudentProfileView;

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
        // return $request->all();

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


        if ($request->classId || $request->sectionId) {

            $studentInfos = StudentProfileView::with('singleBatch', 'singleSection', 'academicYear', 'academicLevel', 'getStudentAddress', 'singleUser', 'singleStudent.singleEnrollment', 'singleStudent.singleEnrollment.admissionYear', 'singleStudent.singleUser', 'singleStudent', 'singleStudent.nationalitys', 'singleStudent.hobbyDreamIdolAim')
                ->where($searchBatchSection)
                ->where(['campus' => $this->academicHelper->getCampus(), 'institute' => $this->academicHelper->getInstitute()])->get();
        }
        if ($request->showNull) {

            $searchNullValue = [];
            $searchEmptyValue = [];
            $selectForm = array();
            foreach ($request->selectForm as $form) {
                if ($form == 'admissionYear') {
                    array_push($selectForm, 'admissionYear');
                } else if ($form == 'academicYear') {
                    array_push($selectForm, 'academicYear');
                } else if ($form == 'academicLevel') {
                    array_push($selectForm, 'academicLevel');
                } else if ($form == 'batch') {
                    array_push($selectForm, 'batch');
                } else if ($form == 'section') {
                    array_push($selectForm, 'section');
                }
            }
            foreach ($selectForm as $form) {
                if ($form == 'academicYear') {
                    $searchNullValue['academic_year'] = null;
                    $searchEmptyValue['academic_year'] = 0;
                }
                if ($form == 'academicLevel') {
                    $searchNullValue['academic_level'] = null;
                    $searchEmptyValue['academic_level'] = 0;
                }
                if ($form == 'batch') {
                    $searchNullValue['batch'] = null;
                    $searchEmptyValue['batch'] = 0;
                }

                if ($form == 'section') {
                    $searchNullValue['section'] = null;
                    $searchEmptyValue['section'] = 0;
                }
                if ($form == 'admissionYear') {
                    // $serchEnrolment[admission_year]
                    $studentEnrollments = StudentEnrollment::where([
                        'admission_year' => 0,
                    ])->orWhere('admission_year', null)->pluck('std_id');
                }
            }
            //  return $searchNullValue;

            $studentNullValue = StudentProfileView::where($searchNullValue)->where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute()
            ])->pluck('std_id')->toArray();
            $studentEmptyValue = StudentProfileView::where($searchEmptyValue)->where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute()
            ])->pluck('std_id')->toArray();
            if (sizeof($studentNullValue) > 0 && sizeof($studentEmptyValue) > 0) {
                $studentEmptyNullValue = array_merge($studentNullValue, $studentEmptyValue);
            } else if (sizeof($studentNullValue) > 0) {
                $studentEmptyNullValue = $studentNullValue;
            } else if (sizeof($studentEmptyValue) > 0) {
                $studentEmptyNullValue = $studentEmptyValue;
            } else {
                $studentEmptyNullValue = [];
            }

            if (isset($studentEnrollments) && isset($studentEmptyNullValue)) {
                $studentInfos = StudentProfileView::with('singleBatch', 'singleSection', 'academicYear', 'academicLevel', 'getStudentAddress', 'singleUser', 'singleStudent.singleEnrollment', 'singleStudent.singleEnrollment.admissionYear', 'singleStudent.singleUser', 'singleStudent', 'singleStudent.nationalitys', 'singleStudent.hobbyDreamIdolAim')
                    ->where([
                        'campus' => $this->academicHelper->getCampus(),
                        'institute' => $this->academicHelper->getInstitute()
                    ])->whereIn('std_id', array_values($studentEnrollments->toArray()))
                    ->whereIn('std_id', array_values($studentEmptyNullValue))->get();
            } else if (isset($studentEmptyNullValue)) {
                $studentInfos = StudentProfileView::with('singleBatch', 'singleSection', 'academicYear', 'academicLevel', 'getStudentAddress', 'singleUser', 'singleStudent.singleEnrollment', 'singleStudent.singleEnrollment.admissionYear', 'singleStudent.singleUser', 'singleStudent', 'singleStudent.nationalitys', 'singleStudent.hobbyDreamIdolAim')
                    ->where([
                        'campus' => $this->academicHelper->getCampus(),
                        'institute' => $this->academicHelper->getInstitute()
                    ])->whereIn('std_id', array_values($studentEmptyNullValue))->get();
            } else if (isset($studentEnrollments)) {
                $studentInfos = StudentProfileView::with('singleBatch', 'singleSection', 'academicYear', 'academicLevel', 'getStudentAddress', 'singleUser', 'singleStudent.singleEnrollment', 'singleStudent.singleEnrollment.admissionYear', 'singleStudent.singleUser', 'singleStudent', 'singleStudent.nationalitys', 'singleStudent.hobbyDreamIdolAim')
                    ->whereIn('std_id', array_values($studentEnrollments))
                    ->where([
                        'campus' => $this->academicHelper->getCampus(),
                        'institute' => $this->academicHelper->getInstitute()
                    ])->get();
            } else {
                $studentInfos = [];
            }
        }
        // return $studentInfos;
        return view("student::pages.cadet-bulk-edit.cadet-bulk-edit-form", compact('batches', 'academicAdmissionYear', 'academicYears', 'sections', 'levels', 'studentInfos', 'selectForms', 'countries'));
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
                    $studentInformation = StudentInformation::where([['campus', $this->academicHelper->getCampus()], ['institute', $this->academicHelper->getInstitute()], ['id', $key]])->first();
                    //student information for User Table chack by id
                    $user = User::where('id', $studentInformation->user_id)->first();
                    if (isset($request->user_name[$key])) {
                        $checkUser =  User::where('username', $request->user_name[$key])->first();
                    } else {
                        $checkUser = null;
                    }
                    $parent = StudentParent::where('std_id', $key)->pluck('gud_id');
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
                    $presentaddress =  isset($request->presentaddress) ? $request->presentaddress[$key] : $presentAddress->address;
                    $permanentaddress =  isset($request->permanentaddress) ? $request->permanentaddress[$key] : $permanentAddress->address;
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
                    if ($presentAddress) {
                        if ($request->presentaddress) {
                            $presentAddress->update([
                                'address' => $presentaddress
                            ]);
                        }
                    }
                    // //  permanentAddress Update
                    if ($permanentAddress) {
                        if ($request->permanentaddress) {
                            $permanentAddress->update([
                                'address' => $permanentaddress
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
                            'enroll_id'=>$meridPosition->id,
                            'gr_no'=>$gr_no,
                            'section'=>$section,
                            'batch'=>$batch,
                            'academic_level'=>$academicLevel,
                            'academic_year'=>$academicYear,
                            'enrolled_at'=> Carbon::now(),
                            'batch_status'=>$meridPosition->batch_status,
                            'remark'=>$meridPosition->remark,
                            'admission_year'=> $admissionYear,
                            'enroll_status'=>$meridPosition->enroll_status,
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

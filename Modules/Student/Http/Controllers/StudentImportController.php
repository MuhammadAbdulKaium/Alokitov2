<?php

namespace Modules\Student\Http\Controllers;

use App\Address;
use App\Content;
use App\RoleUser;
use File;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Academics\Entities\AcademicsAdmissionYear;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\CadetFees\Entities\CadetFeesAssign;
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\CadetPersonalPhoto;
use Modules\Student\Entities\Imports\StudentImport;
use Modules\Student\Entities\StdEnrollHistory;
use Modules\Student\Entities\StudentAttachment;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentParent;
use Modules\Student\Http\Controllers\StudentInfoController;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Student\Entities\AdminStdUpload;
use App\UserInfo;
use phpDocumentor\Reflection\Types\Null_;
use Redirect;
use Session;
use Validator;
use Carbon\Carbon;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;


class StudentImportController extends Controller
{
    private $studentInfoController;
    private $section;
    private $userInfo;
    private $academicHelper;
    private $role;
    private $user;
    private $adminStdUpload;
    private $studentParent;

    // constructor
    public function __construct(StudentGuardian $studentGuardian, StudentParent $studentParent, StudentInfoController $studentInfoController, Section $section, UserInfo $userInfo, AcademicHelper $academicHelper, Role $role, User $user, AdminStdUpload $adminStdUpload)
    {
        $this->studentInfoController = $studentInfoController;
        $this->section = $section;
        $this->userInfo = $userInfo;
        $this->academicHelper = $academicHelper;
        $this->role = $role;
        $this->user = $user;
        $this->adminStdUpload = $adminStdUpload;
        $this->studentGuardian = $studentGuardian;
        $this->studentParent = $studentParent;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('student::pages.student-import.student-import-info');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function showDataset(Request $request)
    {
        $data = $request->input('data');
        $data = json_decode($data, TRUE);
        return view('student::pages.student-import.student-import-list', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function upload(Request $request)
    {
        $data = Excel::toArray(new StudentImport(), $request->file('student_import'));
        //        return $data;
        return view('student::pages.student-import.student-import-list', compact('data'));
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public function imageUpload(Request $request)
    {
        $imageName = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $missingStdIds = [];
            //            $file_name_timestamp = "ems";
            foreach ($image as $files) {
                $user_id = trim($files->getClientOriginalName(), '.' . $files->getClientOriginalExtension());
                $file_name_timestamp = "ems" . $user_id . '-' . Carbon::now()->timestamp;

                //User Info
                $userID = User::where('username', $user_id)->first();
                if ($userID) {
                    $user = $userID['id'];
                    //                echo $user;
                    //                echo '<br>';
                    $destinationPath = 'assets/users/images/';
                    $imageName = $file_name_timestamp . "." . $files->getClientOriginalExtension();
                    $ext = $files->getClientOriginalExtension();
                    $files->move($destinationPath, $imageName);
                    $data[] = $imageName;

                    //End Image Info

                    //Personal Info
                    $personalInfo = StudentInformation::where('user_id', $user)->first();
                    //                echo $personalInfo;
                    //                //Enrollment
                    $enrollment = $personalInfo->enroll();

                    $campus = $this->academicHelper->getCampus();
                    $institute = $this->academicHelper->getInstitute();
                    //Check Image Exist or not
                    $imageAttachmentUpdate = StudentAttachment::where('std_id', $enrollment->std_id)->where('doc_type', 'PROFILE_PHOTO')->first();
                    DB::beginTransaction();
                    if ($imageAttachmentUpdate) {
                        try {
                            $contentFind = Content::where('id', $imageAttachmentUpdate->doc_id)->first();
                            $contentFind->name = $imageName;
                            $contentFind->file_name = $imageName;
                            $contentFind->path = $destinationPath;
                            $contentFind->mime = $ext;
                            $content_update = $contentFind->save();

                            if ($content_update) {
                                $photoStore = new CadetPersonalPhoto;
                                $photoStore->image = $imageName;
                                $photoStore->date = date('Y-m-d');
                                $photoStore->cadet_no = $user;
                                $photoStore->student_id = $enrollment->std_id;
                                $photoStore->campus_id = $campus;
                                $photoStore->institute_id = $institute;
                                $photoStore->academics_year_id = $enrollment->academic_year;
                                $photoStore->section_id = $enrollment->section;
                                $photoStore->batch_id = $enrollment->batch;
                                $photoStorage = $photoStore->save();
                            }
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            return redirect()->back($e->getMessage());
                        }
                    } else {
                        try {
                            $userDocument = new Content();
                            // storing user document
                            $userDocument->name = $imageName;
                            $userDocument->file_name = $imageName;
                            $userDocument->path = $destinationPath;
                            $userDocument->mime = $ext;
                            $insertDocument = $userDocument->save();


                            if ($insertDocument) {
                                $studentAttachment = new StudentAttachment();
                                // storing student attachment
                                $studentAttachment->std_id     = $enrollment->std_id;
                                $studentAttachment->doc_id     = $userDocument->id;
                                $studentAttachment->doc_type   = "PROFILE_PHOTO";
                                $studentAttachment->doc_status = 0;
                                // save student attachment profile
                                $attachmentUploaded = $studentAttachment->save();
                            }
                            if ($insertDocument) {
                                $photoStore = new CadetPersonalPhoto;
                                $photoStore->image = $imageName;
                                $photoStore->date = date('Y-m-d');
                                $photoStore->cadet_no = $user;
                                $photoStore->student_id = $enrollment->std_id;
                                $photoStore->campus_id = $campus;
                                $photoStore->institute_id = $institute;
                                $photoStore->academics_year_id = $enrollment->academic_year;
                                $photoStore->section_id = $enrollment->section;
                                $photoStore->batch_id = $enrollment->batch;
                                $photoStorage = $photoStore->save();
                            }
                            if ($insertDocument) {
                                // If we reach here, then data is valid and working. Commit the queries!
                                DB::commit();
                            }
                        } catch (ValidationException $e) {
                            // Rollback and then redirect
                            // back to form with errors
                            DB::rollback();
                            return redirect()->back($e->getMessage());
                        }
                    }
                } else {
                    array_push($missingStdIds, $user_id);
                }
                $imageName = "";
            }
        }

        if (sizeof($missingStdIds) > 0) {
            $str = '';
            foreach ($missingStdIds as $missingId) {
                $str .= $missingId . ", ";
            }

            Session::flash('warning', 'No Students found with these ids- ' . $str . '! All other photos are uploaded.');
            // receiving page action
            return redirect()->back();
        }
        // return
        Session::flash('success', 'Student Image Uploaded !!!');
        // receiving page action
        return redirect()->back();
    }

    function createParentUser($guardianProfile, $studentUser, $letter)
    {
        $newUserProfile = new $this->user();
        // store user details
        $newUserProfile->name = $guardianProfile->first_name;
        $guardianTypeLetter = $letter;
        for ($j = 1;; $j++) {
            $guardianUsername = $guardianTypeLetter . "-" . $j . "-" . $studentUser->username;
            $sameUser = User::where('username', $guardianUsername)->first();
            if (!$sameUser) {
                break;
            }
        }
        $newUserProfile->username = $guardianUsername;
        $newUserProfile->email = $guardianUsername;
        $newUserProfile->password = bcrypt(123456);
        // saving parent user profile
        $parentUserCreated = $newUserProfile->save();
        if ($parentUserCreated) {
            return $newUserProfile;
        } else {
            return null;
        }
    }

    function createUserInfoProfile($newUserProfile)
    {
        $userInfoProfile = new $this->userInfo();
        // add user details
        $userInfoProfile->user_id = $newUserProfile->id;
        $userInfoProfile->institute_id = $this->academicHelper->getInstitute();
        $userInfoProfile->campus_id = $this->academicHelper->getCampus();
        // save user Info profile
        return $userInfoProfile->save();
    }

    function createStudentParentProfile($newUserProfile, $guardianProfile, $StudentId)
    {
        // studentRoleProfile
        $studentRoleProfile = $this->role->where('name', 'parent')->first();
        // assigning student role to this user
        $newUserProfile->attachRole($studentRoleProfile);
        // add this guardian as student parent
        return $this->studentParent->create([
            'gud_id' => $guardianProfile->id,
            'std_id' => $StudentId,
        ]);
    }

    public function store(Request $request)
    {
        $array = array();
        $array2 = array();
        $array3 = array();
        $totalRow = array();
        for ($i = 0; $i < count($request->username); $i++) {
            if (isset($array2[$request['username'][$i]])) {
                $array3[$array2[$request['username'][$i]] + 1] = $request['username'][$i];
                $array3[$i + 1] = $request['username'][$i];
            } else {
                $array2[$request['username'][$i]] = $i;
            }
        }

        if (sizeof($array3)) {
            return ['status' => 'inlineDuplicate', 'msg' => 'Inline Duplicated Data', 'inlineUser' => $array3];
        } else {
            for ($i = 0; $i < count($request->username); $i++) {
                $currentUser = User::where('username', $request['username'][$i])->first();
                if ($currentUser) {
                    array_push($array, $currentUser);
                }
            }

            if (sizeof($array)) {
                return ['status' => 'duplicate', 'msg' => 'Duplicated Data', 'currentUser' => $array];
            } else {
                DB::beginTransaction();
                try {

                    for ($i = 0; $i < count($request->first_name); $i++) {
                        $religion_input = $request['religion'][$i];
                        if ($religion_input) {
                            if ($religion_input == 'Islam') {
                                $religion = 1;
                            } else if ($religion_input == 'Hinduism') {
                                $religion = 2;
                            } else if ($religion_input == 'Christianity') {
                                $religion = 3;
                            } else if ($religion_input == 'Buddhism') {
                                $religion = 4;
                            } else {
                                $religion = 5;
                            }
                        }
                        //Data design for Language
                        $language = $request['language'][$i];

                        //Data Design for Nationality

                        $nationalityInput = $request['nationality'][$i];
                        if ($nationalityInput) {
                            if ($nationalityInput == 'Bangladeshi') {
                                $nationality = '1';
                            } else {
                                $nationality = '0';
                            }
                        }

                        //Data Design for Gender

                        $gender = $request['gender'][$i];

                        //Data Design for Academic Year

                        $academicYearInput = $request['academic_year'][$i];
                        if ($academicYearInput) {
                            $getAcademicYear = AcademicsYear::where([
                                'institute_id' => $this->academicHelper->getInstitute(),
                                'campus_id' => $this->academicHelper->getCampus(),
                                'year_name' => $academicYearInput
                            ])->first();
                            if ($getAcademicYear) {
                                $academicYear = $getAcademicYear->id;
                            } else {
                                $academicYear = 0;
                            }
                        }

                        //Data Design for Academic Level
                        $academicLevelInput = $request['academic_level'][$i];
                        if ($academicLevelInput) {
                            $getAcademicLevel = AcademicsLevel::where([
                                'institute_id' => $this->academicHelper->getInstitute(),
                                'campus_id' => $this->academicHelper->getCampus(),
                                'level_name' => $academicLevelInput
                            ])->first();
                            if ($getAcademicLevel) {
                                $level = $getAcademicLevel->id;
                            } else {
                                $level = 0;
                            }
                        }

                        //Data Design for Admission Year
                        $admissionYearInput = $request['admission_year'][$i];
                        if ($admissionYearInput) {
                            $getAdmissionYear = AcademicsAdmissionYear::where([
                                'institute_id' => $this->academicHelper->getInstitute(),
                                'campus_id' => $this->academicHelper->getCampus(),
                                'year_name' => $admissionYearInput
                            ])->first();
                            if ($getAdmissionYear) {
                                $admissionYear = $getAdmissionYear->id;
                            } else {
                                $admissionYear = 0;
                            }
                        }

                        //Data Design for Academic Batch
                        $batchInput = $request['class'][$i];
                        if ($batchInput) {
                            $getBatch = Batch::where([
                                'institute' => $this->academicHelper->getInstitute(),
                                'campus' => $this->academicHelper->getCampus(),
                                'batch_name' => $batchInput
                            ])->first();
                            if ($getBatch) {
                                $class = $getBatch->id;
                            } else {
                                $class = 0;
                            }
                        }
                        //Data Design for Section
                        $sectionInput = $request['section'][$i];
                        if ($sectionInput) {
                            $getSection = Section::where([
                                'institute' => $this->academicHelper->getInstitute(),
                                'campus' => $this->academicHelper->getCampus(),
                                'section_name' => $sectionInput,
                                'batch_id' => $class
                            ])->first();
                            if ($getSection) {
                                $section = $getSection->id;
                            } else {
                                $section = 0;
                            }
                        }

                        // Data Design for Student type
                        $studentTypeInput = $request['student_type'][$i];
                        if ($studentTypeInput == 'Pre Admission') {
                            $studentType = 1;
                        } elseif ($studentTypeInput == 'Regular') {
                            $studentType = 2;
                        } else {
                            $studentType = null;
                        }

                        // Student Roll Number
                        if ($request['roll'][$i]) {
                            $rollNo = $request['roll'][$i];
                        } else {
                            $rollNo = 0;
                        }


                        $userProfile = new User();
                        $userProfile->name = $request['first_name'][$i];
                        $userProfile->username = $request['username'][$i];
                        $userProfile->email = $request['username'][$i];
                        $userProfile->password = bcrypt(123456);
                        $userStore = $userProfile->save();

                        $insertedId = $userProfile->id;

                        if ($userStore) {
                            $role_user = new RoleUser();
                            $role_user->user_id = $insertedId;
                            $role_user->role_id = 3;
                            $role_user->save();
                        }
                        if ($userStore) {
                            $user_campus_inst = new UserInfo();
                            $user_campus_inst->user_id = $insertedId;
                            $user_campus_inst->campus_id = $this->academicHelper->getCampus();
                            $user_campus_inst->institute_id = $this->academicHelper->getInstitute();
                            $user_campus_inst->save();
                        }

                        $dob = ($this->validateDate($request['dob'][$i])) ? $request['dob'][$i] : null;

                        if ($userStore) {
                            $information = new StudentInformation();
                            $information->user_id = $insertedId;
                            $information->type = $studentType;
                            $information->title = '';
                            $information->first_name = $request['first_name'][$i];
                            $information->last_name = $request['last_name'][$i];
                            $information->gender = $gender;
                            $information->dob = $dob;
                            $information->blood_group = $request['blood_group'][$i];
                            $information->religion = $religion;
                            $information->birth_place = $request['birth_place'][$i];
                            $information->email = $request['username'][$i];
                            $information->language = $language;
                            $information->nationality = $nationality;
                            $information->identification_mark = $request['identification_mark'][$i];
                            $information->institute = $this->academicHelper->getInstitute();
                            $information->campus = $this->academicHelper->getCampus();
                            $information->bn_fullname = $request['bn_fullname'][$i];
                            $information->batch_no = $request['batch'][$i];
                            $information->status = 1;
                            $storeStudentInformation = $information->save();
                            $StudentId = $insertedId;
                        }
                        if ($storeStudentInformation) {
                            $presentAddress = new Address();
                            $presentAddress->user_id = $StudentId;
                            $presentAddress->type = 'STUDENT_PRESENT_ADDRESS';
                            $presentAddress->address = $request['present_address'][$i];
                            $presentAddress->save();

                            $permanentAddress = new Address();
                            $permanentAddress->user_id = $StudentId;
                            $permanentAddress->type = 'STUDENT_PERMANENT_ADDRESS';
                            $permanentAddress->address = $request['permanent_address'][$i];
                            $permanentAddress->save();
                        }
                        // Tuition Fees creation
                        if ($request['tution_fees'][$i]) {
                            CadetFeesAssign::create([
                                'std_id' => $information->id,
                                'fees' => $request->tution_fees[$i],
                                'academic_level' => $level,
                                'batch'          => $class,
                                'section'        => $section,
                                'academic_year'  => $academicYear,
                                'campus_id'      => $this->academicHelper->getCampus(),
                                'instittute_id'      => $this->academicHelper->getInstitute(),
                                'created_by' => Auth::id()
                            ]);
                        }

                        if ($storeStudentInformation) {
                            if ($request['hobby'][$i]) {
                                $hobby = new CadetAssesment();
                                $hobby->student_id = $information->id;
                                $hobby->campus_id = $this->academicHelper->getCampus();
                                $hobby->institute_id = $this->academicHelper->getInstitute();
                                $hobby->academics_year_id = $academicYear;
                                $hobby->academics_level_id = $level;
                                $hobby->section_id = $section;
                                $hobby->batch_id = $class;
                                $hobby->date = date('Y-m-d');
                                $hobby->type = 3;
                                $hobby->remarks = $request['hobby'][$i];
                                $hobby->save();
                            }

                            if ($request['aim'][$i]) {
                                $aim = new CadetAssesment();
                                $aim->student_id = $information->id;
                                $aim->campus_id = $this->academicHelper->getCampus();
                                $aim->institute_id = $this->academicHelper->getInstitute();
                                $aim->academics_year_id = $academicYear;
                                $aim->academics_level_id = $level;
                                $aim->section_id = $section;
                                $aim->batch_id = $class;
                                $aim->date = date('Y-m-d');
                                $aim->type = 4;
                                $aim->remarks = $request['aim'][$i];
                                $aim->save();
                            }

                            if ($request['idol'][$i]) {
                                $idol = new CadetAssesment();
                                $idol->student_id = $information->id;
                                $idol->campus_id = $this->academicHelper->getCampus();
                                $idol->institute_id = $this->academicHelper->getInstitute();
                                $idol->academics_year_id = $academicYear;
                                $idol->academics_level_id = $level;
                                $idol->section_id = $section;
                                $idol->batch_id = $class;
                                $idol->date = date('Y-m-d');
                                $idol->type = 6;
                                $idol->remarks = $request['idol'][$i];
                                $idol->save();
                            }

                            if ($request['dream'][$i]) {
                                $dream = new CadetAssesment();
                                $dream->student_id = $information->id;
                                $dream->campus_id = $this->academicHelper->getCampus();
                                $dream->institute_id = $this->academicHelper->getInstitute();
                                $dream->academics_year_id = $academicYear;
                                $dream->academics_level_id = $level;
                                $dream->section_id = $section;
                                $dream->batch_id = $class;
                                $dream->date = date('Y-m-d');
                                $dream->type = 5;
                                $dream->remarks = $request['dream'][$i];
                                $dream->save();
                            }
                        }
                        if ($storeStudentInformation) {
                            $enrolement = new StudentEnrollment();
                            $enrolement->std_id = $information->id;
                            $enrolement->gr_no = $rollNo;
                            $enrolement->academic_level = $level;
                            $enrolement->batch = $class;
                            $enrolement->section = $section;
                            $enrolement->academic_year = $academicYear;
                            $enrolement->admission_year = $admissionYear;
                            $enrolement->enrolled_at = date('Y-m-d');
                            $enrolement->enroll_status = 'IN_PROGRESS';
                            $enrolement->batch_status = 'IN_PROGRESS';
                            $enroleStore = $enrolement->save();

                            $enrolID = $enrolement->id;
                            if ($enroleStore) {
                                $enroleHistory = new StdEnrollHistory();
                                $enroleHistory->enroll_id = $enrolID;
                                $enroleHistory->gr_no = $rollNo;
                                $enroleHistory->academic_level = $level;
                                $enroleHistory->batch = $class;
                                $enroleHistory->section = $section;
                                $enroleHistory->academic_year = $academicYear;
                                $enroleHistory->admission_year = $admissionYear;
                                $enroleHistory->enrolled_at = date('Y-m-d');
                                $enroleHistory->enroll_status = 'IN_PROGRESS';
                                $enroleHistory->batch_status = 'IN_PROGRESS';
                                $enrolementHistory = $enroleHistory->save();
                            }
                        }



                        // Guardian Insert
                        if ($storeStudentInformation) {
                            $studentUser = $userProfile;

                            // Father Insert;
                            $guardianProfile = $this->studentGuardian->create([
                                'type' => 1,
                                'first_name' => $request['father_name'][$i],
                                'occupation' => $request['father_occupation'][$i],
                                'mobile' => $request['father_mobile'][$i],
                                'gender' => 1
                            ]);
                            // checking
                            if ($guardianProfile) {
                                // new user profile
                                $newUserProfile = $this->createParentUser($guardianProfile, $studentUser, "F");
                                // checking
                                if ($newUserProfile) {
                                    // create guardian user info
                                    $userInfoProfileSaved = $this->createUserInfoProfile($newUserProfile);
                                    // checking info profile
                                    if ($userInfoProfileSaved) {
                                        $studentParentProfile = $this->createStudentParentProfile($newUserProfile, $guardianProfile, $information->id);
                                        // checking
                                        if ($studentParentProfile) {
                                            // update guardian profile
                                            $guardianProfile->user_id = $newUserProfile->id;
                                            $guardianProfileUpdate = $guardianProfile->save();
                                        }
                                    }
                                }
                            }

                            // Mother Insert;
                            $guardianProfile = $this->studentGuardian->create([
                                'type' => 0,
                                'first_name' => $request['mother_name'][$i],
                                'occupation' => $request['mother_occupation'][$i],
                                'mobile' => $request['mother_mobile'][$i],
                                'gender' => 2
                            ]);
                            // checking
                            if ($guardianProfile) {
                                // new user profile
                                $newUserProfile = $this->createParentUser($guardianProfile, $studentUser, "M");
                                // checking
                                if ($newUserProfile) {
                                    // create guardian user info
                                    $userInfoProfileSaved = $this->createUserInfoProfile($newUserProfile);
                                    // checking info profile
                                    if ($userInfoProfileSaved) {
                                        $studentParentProfile = $this->createStudentParentProfile($newUserProfile, $guardianProfile, $information->id);
                                        // checking
                                        if ($studentParentProfile) {
                                            // update guardian profile
                                            $guardianProfile->user_id = $newUserProfile->id;
                                            $guardianProfileUpdate = $guardianProfile->save();
                                        }
                                    }
                                }
                            }


                            if ($request['guardian_relation'][$i] != "Father" && $request['guardian_relation'][$i] != "Mother") {
                                $guardianType = null;
                                switch ($request['guardian_relation'][$i]) {
                                    case 'Sister':
                                        $guardianType = "2";
                                        break;
                                    case 'Brother':
                                        $guardianType = "3";
                                        break;
                                    case 'Relative':
                                        $guardianType = "4";
                                        break;
                                    case 'Other':
                                        $guardianType = "5";
                                        break;
                                }

                                if ($request['guardian_name'][$i]) {
                                    // Guardian Insert;
                                    $guardianProfile = $this->studentGuardian->create([
                                        'type' => $guardianType,
                                        'first_name' => $request['guardian_name'][$i],
                                        'mobile' => $request['mother_mobile'][$i],
                                        'is_guardian' => 1,
                                    ]);
                                    // checking
                                    if ($guardianProfile) {
                                        // new user profile
                                        $newUserProfile = $this->createParentUser($guardianProfile, $studentUser, substr($request['guardian_relation'][$i], 0, 1));
                                        // checking
                                        if ($newUserProfile) {
                                            // create guardian user info
                                            $userInfoProfileSaved = $this->createUserInfoProfile($newUserProfile);
                                            // checking info profile
                                            if ($userInfoProfileSaved) {
                                                $studentParentProfile = $this->createStudentParentProfile($newUserProfile, $guardianProfile, $information->id);
                                                // checking
                                                if ($studentParentProfile) {
                                                    // update guardian profile
                                                    $guardianProfile->user_id = $newUserProfile->id;
                                                    $guardianProfileUpdate = $guardianProfile->save();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if ($guardianProfileUpdate) {
                            array_push($totalRow, $i);
                        }
                    }


                    if (sizeof($totalRow)) {
                        DB::commit();
                        return ['status' => 'recordSuccessfull', 'msg' => 'Data record Successfully', 'recordData' => $totalRow];
                    }
                } catch (\Exception $e) {
                    // Rollback
                    DB::rollback();
                    // throw exceptions
                    throw $e;
                    return 420;
                }
            }
        }
    }




    // Rearrange Student list
    public function stdImportListRearrange($stdCount, $stdList)
    {
        $responseArray = array();
        // std list
        $stdList = json_decode($stdList);
        // checking
        if ($stdCount > 0) {
            // looping
            for ($i = 1; $i <= $stdCount; $i++) {
                // std details
                $firstName = $i . '_first_name';
                $middleName = $i . '_middle_name';
                $lastName = $i . '_last_name';
                $gender = $i . '_gender';
                $email = $i . '_email';
                // $admissionYear = $i.'_admission_year';
                $dob = $i . '_dob';
                $campus = $i . '_campus';
                $grNO = $i . '_gr_no';
                $batch = $i . '_batch';
                $section = $i . '_section';
                // find std details
                $responseArray[$i]['first_name'] = $stdList->$firstName;
                $responseArray[$i]['middle_name'] =  $stdList->$middleName;
                $responseArray[$i]['last_name'] =  $stdList->$lastName;
                $responseArray[$i]['gender'] =  $stdList->$gender;
                $responseArray[$i]['email'] =  $stdList->$email;
                // $responseArray[$i]['admission_year'] =  $stdList->$admissionYear;
                $responseArray[$i]['dob'] =  $stdList->$dob;
                //$responseArray[$i]['campus'] =  $stdList->$campus;
                $responseArray[$i]['gr_no'] = $stdList->$grNO;
                $responseArray[$i]['batch'] =  $stdList->$batch;
                $responseArray[$i]['section'] =  $stdList->$section;
            }
            // return response
            return $responseArray;
        } else {
            // return response
            return null;
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function getDownload()
    {
        //        //PDF file is stored under project/public/download/info.pdf
        //        $file= public_path(). "/download/student_import.xlsx";
        //
        //        $headers = array(
        //            'Content-Type: application/xlsx',
        //        );
        //
        //        return response()->download($file, 'student_import.xlsx', $headers);
    }

    public function imagePage()
    {
        return view('student::pages.student-import.student-import-image');
    }

    //////////////////////////////////////// Student Import By Admin ////////////////////////////////////////

    // student list create
    public function adminStudentListCreate()
    {
        // institute and campus details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // find institute uploads by admin
        $uploadList = $this->adminStdUpload->where(['campus' => $campus, 'institute' => $institute])->orderBy('created_at', 'DESC')->get();
        // return view with variable
        return view('student::pages.student-import.admin-student-upload', compact('uploadList'));
    }

    // student list create
    public function adminStudentListStore(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), ['student_list'  => 'required']);
        // storing requesting input data
        if ($validator->passes()) {
            // institute and campus details
            $campus = $this->academicHelper->getCampus();
            $institute = $this->academicHelper->getInstitute();

            // file storing
            $file  = $request->file('student_list');
            $name = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $contentName = "ems" . $campus . $institute . date("Ymdhis") . mt_rand(100000, 999999);
            $fileName = $contentName . "." . $extension;
            $destinationPath = 'assets/documents/student-list/';

            // Start transaction!
            DB::beginTransaction();

            // start to try
            try {
                // move file to the destination path
                if ($file->move($destinationPath, $fileName)) {
                    // now upload the file details
                    $fileProfile = new $this->adminStdUpload;
                    // now store file details
                    $fileProfile->name = $name;
                    $fileProfile->file_name = $contentName;
                    $fileProfile->mime = $extension;
                    $fileProfile->campus = $campus;
                    $fileProfile->institute = $institute;
                    // now save and checking
                    if ($fileProfile->save()) {
                        // If we reach here, then data is valid and working. Commit the queries!
                        DB::commit();
                        // return
                        Session::flash('success', 'Student List Uploaded !!!');
                        // receiving page action
                        return redirect()->back();
                    } else {
                        // path to remove uploaded file
                        $filePath = $destinationPath . '/' . $fileName;
                        // now deleting the uploaded file
                        File::delete($filePath);

                        Session::flash('warning', 'Unable to Save Student List');
                        // receiving page action
                        return redirect()->back();
                    }
                } else {
                    Session::flash('warning', 'Unable to Upload Student List');
                    // receiving page action
                    return redirect()->back();
                }
            } catch (ValidationException $e) {
                // Rollback
                DB::rollback();
                // Redirect back to form with errors Redirecting with error message
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                // Rollback
                DB::rollback();
                // throw exceptions
                throw $e;
            }
        } else {
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back();
        }
    }


    // upload student list
    public function adminStudentUpload(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), ['file_id'  => 'required']);
        // storing requesting input data
        if ($validator->passes()) {
            // request details
            $fileId = $request->input('file_id');
            // find uploaded file
            $fileProfile = $this->adminStdUpload->find($fileId);
            // institute details
            $institute = $this->academicHelper->getInstituteProfile();

            // array for json body request
            $json = [
                'file_path' => public_path() . '/assets/documents/student-list/' . $fileProfile->file_name . '.' . $fileProfile->mime,
                'file_original_name' => $fileProfile->file_name,
                'institute_short_code' => $institute->institute_alias,
            ];

            // call guzzle http auto request
            $client = new Client();
            // result
            $result = json_decode($client->request('POST', 'http://localhost:5000/upload', ['json' => $json])->getBody()->getContents());
            // checking
            if ($result->success) {
                // uploaded file name
                $uploadedFileName = $fileProfile->file_name . '_uploaded' . $fileProfile->mime;
                // update file profile
                $fileProfile->u_file_name = $uploadedFileName;
                $fileProfile->status = 1;
                // save file profile
                $fileProfile->save();
                // return
                return ['status' => 'success', 'file_id' => $fileProfile->id, 'msg' => $result->message];
            } else {
                // return
                return ['status' => 'failed', 'msg' => $result->message];
            }
        } else {
            // return
            return ['status' => 'failed', 'msg' => 'Invalid Information provided !!!'];
        }
    }

    // adminStudentDelete
    public function adminStudentDelete($id)
    {
        // find student profile
        if ($fileProfile = $this->adminStdUpload->find($id)) {
            // file path
            $filePath = 'assets/documents/student-list/' . $fileProfile->file_name . '.' . $fileProfile->mime;
            // now delete file profile
            if ($fileProfile->delete()) {
                // now deleting the post image
                File::delete($filePath);
                // return
                Session::flash('success', 'Student List File Deleted !!!');
            } else {
                // return
                Session::flash('warning', 'Unable to Delete File !!!');
            }
            // receiving page action
            return redirect()->back();
        } else {
            // return to 404 page
            abort(404);
        }
    }



    //////////////////////////////////////// Student Import By Admin ////////////////////////////////////////


    /**
     * Check emails duplicacy for students
     */
    public function checkEmails(Request $request)
    {
        $emails = $request->input('form_data');
        $emails = json_decode($emails);
        $result = array();

        for ($i = 1; $i < count($emails); $i++) {

            $user = User::where('email', '=', $emails[$i])->get();
            if ($user->count() > 0) {
                $result[$i] = 0;
            } else {
                $result[$i] = 1;
            }
        }
        return $result;
    }
}

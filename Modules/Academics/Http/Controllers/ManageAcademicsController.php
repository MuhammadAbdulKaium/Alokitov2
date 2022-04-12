<?php

namespace Modules\Academics\Http\Controllers;

use App\Helpers\UserAccessHelper;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\Division;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\SubjectTeacher;
use Modules\Employee\Entities\EmployeeInformation;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\BatchSemester;
use Modules\Academics\Http\Controllers\AttendanceUploadController;
use Modules\Academics\Entities\AdditionalSubject;
use Modules\Academics\Entities\SubjectGroup;
use Redirect;
use Session;
use Validator;

class ManageAcademicsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $academicYear;
    private $batch;
    private $section;
    private $division;
    private $subjcet;
    private $classSubject;
    private $subjectTeacher;
    private $employee;
    private $academicHelper;
    private $batchSemester;
    private $sectionController;
    private $attendanceUploadController;
    private $additionalSubject;
    private $subjectGroup;
    use UserAccessHelper;

    public function __construct(AcademicsYear $academicYear, Batch $batch, Section $section, Division $division, Subject $subject, ClassSubject $classSubject, SubjectTeacher $subjectTeacher, EmployeeInformation $employee, AcademicHelper $academicHelper, BatchSemester $batchSemester, SectionController $sectionController, AttendanceUploadController $attendanceUploadController, AdditionalSubject $additionalSubject, SubjectGroup $subjectGroup)
    {
        $this->academicYear  = $academicYear;
        $this->batch          = $batch;
        $this->section        = $section;
        $this->division       = $division;
        $this->subjcet        = $subject;
        $this->classSubject   = $classSubject;
        $this->subjectTeacher = $subjectTeacher;
        $this->employee       = $employee;
        $this->academicHelper = $academicHelper;
        $this->batchSemester  = $batchSemester;
        $this->sectionController = $sectionController;
        $this->attendanceUploadController = $attendanceUploadController;
        $this->additionalSubject = $additionalSubject;
        $this->subjectGroup = $subjectGroup;
    }

    public function index()
    {
        return abort('404');
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // division list
        $divisions = $this->division->get();
        // batches with division
        $batchesWithoutDivision = $this->batch->whereNull('division_id')->orderBy('division_id')->get();
        // batches without division
        $batchesWithDivision = $this->batch->whereNotNull('division_id')->orderBy('division_id')->get();
        // return all variables with view
        return view('academics::manage-academics.index', compact('divisions', 'batchesWithoutDivision', 'batchesWithDivision'));
    }

    // add section
    public function showSection($batchId)
    {
        // batch profile
        $batchProfile = $this->batch->where('id', $batchId)->first();
        // return view with batch profile variable
        return view('academics::manage-academics.modals.section-list', compact('batchProfile'));
    }

    // add section
    public function addSection($batchId)
    {
        // all academic years
        // $allAcademicsYears = $this->academicYear::all();
        $allAcademicsYears = $this->academicHelper->getAllAcademicYears();
        // batch profile
        $batchProfile = $this->batch->find($batchId);
        // return view with batch profile variable
        return view('academics::manage-academics.modals.section-add', compact('allAcademicsYears', 'batchProfile'));
    }

    // store section
    public function storeSection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academics_year' => 'required|max:100',
            'batch'          => 'required|max:100',
            'section'        => 'required',
            'intake'         => 'required',
        ]);

        if ($validator->passes()) {
            // section new instance
            $sectionProfile = new $this->section();
            // adding section into the profile
            $sectionProfile->academics_year_id = $request->input('academics_year');
            $sectionProfile->batch_id          = $request->input('batch');
            $sectionProfile->section_name      = $request->input('section');
            $sectionProfile->intake            = $request->input('intake');
            $sectionProfile->campus = $this->academicHelper->getCampus();
            $sectionProfile->institute = $this->academicHelper->getInstitute();
            // saving the profile
            $sectionProfileCreated = $sectionProfile->save();
            // checking
            if ($sectionProfileCreated) {
                // success message
                Session::flash('success', 'Subject Added Successfully');
                return redirect()->back();
            }
        } else {
            // warning message
            Session::flash('warning', 'Invalid information');
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // add subject
    public function addSubject(Request $request)
    {
        $pageAccessData = self::linkAccess($request);

        // return $pageAccessData;

        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // section id
        $sectionId = null;
        //All Academic Level list
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        //All batch section and division list
        $allBatchSectionDivision = $this->sectionController->findBatchSection();
        // all Subjects;
        $allSubjects = $this->subjcet->with('checkSubGroupAssignSingle.subjectGroupSingle')->get()->toArray();

        $user = Auth::user();
        $employeeId = ($user->employee()) ? $user->employee()->id : null;
        $batches = $this->batch->get();

        if (!($user->role()->id == 1 || $user->role()->id == 6)) {
            if ($employeeId) {
                $batchIds = DB::table('class_teacher_assign')->where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'teacher_id' => $employeeId,
                    'status' => 1
                ])->get()->groupBy('batch_id')->toArray();
                $batches = Batch::whereIn('id', array_keys($batchIds))->get();
            }
        }

        // teacher lists
        $teacherList = $this->employee->with('singleUser')->where(['category' => 1, 'campus_id' => $campusId, 'institute_id' => $instituteId])->get();
        // return view with variable
        return view('academics::manage-academics.class-subject-add', compact('pageAccessData', 'batches', 'sectionId', 'allSubjects', 'allBatchSectionDivision', 'allAcademicsLevel', 'teacherList'));
    }

    // storing class subject
    public function storeSubject(Request $request)
    {
        // return $request->all();
        $batchId   = $request->input('batch_id');
        $sectionId = $request->input('section_id');
        // row counter
        $rowCount    = $request->input('rows_no');
        $deleteCount = $request->input('delete_no');
        // now delete
        $allDeletedSubjects = $request->deleteList;
        if ($allDeletedSubjects > 0) {
            // single subject
            for ($a = 1; $a <= $deleteCount; $a++) {
                $deletedSingleSubjectId = $allDeletedSubjects['id_' . $a];
                // deletd class subject profile
                $deletedClassSubjectProfile = $this->classSubject->where('id', $deletedSingleSubjectId)->first();
                $deletedClassSubjectProfile->delete();
            }
        }
        // loop counter
        $x = 0;
        // looping
        for ($i = 1; $i <= $rowCount; $i++) {
            // //all subjects
            $allSubjects = $request->subjects;
            // single subject
            $singleSubjectId = $allSubjects['sub_' . $i];
            // receive the current subject form inputs
            $subjectProfile = $request->input('subject' . $singleSubjectId);
            // get the cs id first
            $csId = $subjectProfile['cs_id'];

            // checking
            if ($csId > 0) {
                // update the existing classSubject
                $classSubjectProfile = $this->classSubject->where('id', $csId)->first();
            } else {
                // create new classSubject instance
                $classSubjectProfile = new $this->classSubject();
            }
            // creating new class subject
            $classSubjectProfile->class_id       = $batchId;
            $classSubjectProfile->section_id     = $sectionId;
            $classSubjectProfile->subject_id     = $subjectProfile['id'];
            $classSubjectProfile->subject_code   = $subjectProfile['code'];
            $classSubjectProfile->subject_type   = $subjectProfile['type'];
            $classSubjectProfile->subject_group   = $subjectProfile['group'];
            $classSubjectProfile->subject_list  = $subjectProfile['list'];
            //$classSubjectProfile->subject_credit = $subjectProfile['credit'];
            $classSubjectProfile->is_countable = $subjectProfile['is_countable'];
            $classSubjectProfile->sorting_order  = $subjectProfile['sort_order'];
            $classSubjectProfile->campus_id  = $this->academicHelper->getCampus();
            $classSubjectProfile->institute_id  = $this->academicHelper->getInstitute();

            // saving classSubjectProfile
            $classSubjectProfileSubmitted = $classSubjectProfile->save();
            // checking
            if ($classSubjectProfileSubmitted) {
                // count classSubjectProfile creation
                $x = $x + 1;

                if (isset($subjectProfile['teachers'])) {
                    SubjectTeacher::where('class_subject_id', $classSubjectProfile->id)->whereNotIn('employee_id', $subjectProfile['teachers'])->delete();
                    $subTeachers = SubjectTeacher::where('class_subject_id', $classSubjectProfile->id)->get();

                    foreach ($subjectProfile['teachers'] as $key => $teacherId) {
                        $prevSubTeacher = $subTeachers->firstWhere('employee_id', $teacherId);

                        if (!$prevSubTeacher) {
                            SubjectTeacher::create([
                                'class_subject_id' => $classSubjectProfile->id,
                                'employee_id' => $teacherId,
                                'status' => 'PERMANENT',
                                'is_active' => 1,
                            ]);
                        }
                    }
                } else {
                    SubjectTeacher::where('class_subject_id', $classSubjectProfile->id)->delete();
                }
            }
        }

        //redirecting
        if ($rowCount == $x) {
            Session::flash('batchId', $batchId);
            Session::flash('sectionId', $sectionId);
            Session::flash('success', 'Subjects Submitted Successfully');
            return redirect()->back();
        }
    }

    // show all class subjects
    public function allClassSubject(Request $request)
    {
        // response array
        $responseArrayList['class_sub_list'] = [];
        $responseArrayList['sub_group_list'] = [];
        // request details
        $batch = $request->input('batch');
        $section = $request->input('section');
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // class subject profile
        $classSubjectList = $this->classSubject->with('teachers')->where([
            'class_id' => $batch,
            'section_id' => $section,
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->orderBy('sorting_order', 'ASC')->get();
        $subjectGroupList = $this->subjectGroup->get();

        // checking subject group list
        if ($subjectGroupList and $subjectGroupList->count() > 0) {
            // class subject group list
            foreach ($subjectGroupList as $group) {
                // input subject group details
                $responseArrayList['sub_group_list'][] = ['id' => $group->id, 'name' => $group->name, 'type' => $group->type];
            }
        }

        // checking subject group list
        if ($classSubjectList and $classSubjectList->count() > 0) {
            // class subject list looping
            foreach ($classSubjectList as $subject) {
                $responseArrayList['class_sub_list'][] = [
                    'id'            => $subject->id,
                    'sortOrder'     => $subject->sorting_order,
                    'classId'       => $subject->class_id,
                    'sectionId'     => $subject->section_id,
                    'subjectId'     => $subject->subject_id,
                    'subjectName'   => $subject->subject()->subject_name,
                    'subjectCode'   => $subject->subject_code,
                    'subjectCredit' => $subject->subject_credit,
                    'is_countable' => $subject->is_countable,
                    'subjectType'   => $subject->subject_type,
                    'subjectGroup'   => $subject->subject_group,
                    'subjectList'   => $subject->subject_list,
                    'teacherIds'       => $subject->teachers->pluck('employee_id'),
                ];
            }
        }

        // return response
        return $responseArrayList;
    }

    //////////////////////  find class subjects for ajax request //////////////////////
    public function findsubjcet(Request $request)
    {
        // input details
        $class = $request->input('class_id');
        $section = $request->input('section_id');
        // response array
        $data = array();
        // all class subject
        $allClassSubject = $this->classSubject->where([
            'class_id' => $class,
            'section_id' => $section,
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->orderBy('sorting_order', 'ASC')->get();
        // active user information
        $userInfo = Auth::user();
        // checking user role
        if ($userInfo->hasRole('teacher')) {
            // find user employee profile
            $teacherInfoProfile = $userInfo->employee();
            // find class teacher subject list
            $classTeacherSubjects = $this->subjectTeacher->where(['employee_id' => $teacherInfoProfile->id, 'is_active' => 1])->get();
            // Teacher subject looping for finding subjects of this class-section
            foreach ($classTeacherSubjects as $teacherSubject) {
                // find class subject profile anc checking
                if ($classSubject = $teacherSubject->classSubject()) {
                    // checking class subject details
                    if (($classSubject->class_id == $class) and ($classSubject->section_id == $section)) {
                        $data[] = $this->ClassSubjectReturnPack($classSubject);
                    }
                }
            }
        } else {
            // looping for adding division into the batch name
            foreach ($allClassSubject as $classSubject) {
                $data[] = $this->ClassSubjectReturnPack($classSubject);
            }
        }
        //then sent this data to ajax success
        return $data;
    }

    public function findGroup(Request $request)
    {
        $divisions = Section::findOrFail($request->section_id)->divisions;

        return $divisions;
    }

    public function findGroupSubject(Request $request)
    {
        $subjects = Division::findOrFail($request->division_id)->subjects;

        return $subjects;
    }

    public function findSemester(Request $request)
    {
        // response array
        $data = array();
        // input details
        $academicBatch = $request->input('batch');
        $academicLevel = $request->input('academic_level');
        $academicYear = $this->academicHelper->getAcademicYear();
        // semester list
        $batchSemesterList =  $this->batchSemester->where([
            'academic_year' => $academicYear,
            'academic_level' => $academicLevel,
            'batch' => $academicBatch
        ])->orderBy('semester_id', 'ASC')->get();

        // looping for adding division into the batch name
        foreach ($batchSemesterList as $myBatchSemester) {
            $semester = $myBatchSemester->semester();
            //  checking semester profile
            if ($semester) {
                $data[] = array(
                    'id' => $semester->id,
                    'name' => $semester->name
                );
            }
        }
        //then sent this data to ajax success
        return $data;
    }

    public function assingTeacher($id)
    {
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // teacher lists
        $teacherList = $this->employee->where(['category' => 1, 'campus_id' => $campusId, 'institute_id' => $instituteId])->get();
        // class subject profile
        $classSubjectProfile = $this->classSubject->where('id', $id)->first();
        // return view with batch profile variable
        return view('academics::manage-academics.modals.assign-teacher', compact('classSubjectProfile', 'teacherList'));
    }

    public function viewAssingedTeacher($id)
    {
        // class subject profile
        $classSubjectProfile = $this->classSubject->where('id', $id)->first();
        // class subject teacher list
        $subjectTeacherList = $this->subjectTeacher->where('class_subject_id', $id)->get();

        // return view with batch profile variable
        return view('academics::manage-academics.modals.assign-teacher-view', compact('classSubjectProfile', 'subjectTeacherList'));
    }

    public function deleteAssingedTeacher($id)
    {
        // class subject teacher list
        $csTeacherProfile = $this->subjectTeacher->where('id', $id)->first();
        // class suject id
        $csId = $csTeacherProfile->class_subject_id;
        // delete csTeacherProfile
        $csTeacherProfileDeleted = $csTeacherProfile->delete();
        // response data
        $data = array();
        //checking
        if ($csTeacherProfileDeleted) {
            // success informaiton
            $data['status'] = 'success';
            // class subject teacher list
            $subjectTeacherList = $this->subjectTeacher->where('class_subject_id', $csId)->get();
            if ($subjectTeacherList->count() > 0) {
                // list count
                $data['list'] = $subjectTeacherList->count();
            } else {
                // null list items
                $data['list'] = null;
            }
            // return
            return $data;
        } else {
            $data['status'] = 'failed';
            return $data;
        }
    }

    public function storeAssingTeacher(Request $request)
    {
        // return $request->all();
        // create teacher instance
        $subjectTeacherProfile = new $this->subjectTeacher();
        // storing teacher details
        $subjectTeacherProfile->class_subject_id = $request->input('cs_id');
        $subjectTeacherProfile->employee_id      = $request->input('teacher_id');
        $subjectTeacherProfile->status           = $request->input('teacher_status');
        $subjectTeacherProfile->is_active        = $request->input('is_active');
        // saving teacher
        $subjectTeacherProfileCreated = $subjectTeacherProfile->save();

        // checking
        if ($subjectTeacherProfileCreated) {
            Session::flash('success', 'Teacher Added Successfully');
            return redirect()->back();
        }
    }



    //get class section fourth subject list
    public function getClassSectionAdditionalSubjectList(Request $request)
    {
        // subject group array list
        $subGroupArrayList = [];
        // input details
        $academicLevel = $request->input('academic_level');
        $academicBatch = $request->input('batch');
        $academicSection = $request->input('section');
        // find batch profile
        $batchProfile = $this->academicHelper->findBatch($academicBatch);
        // institute details
        $academicYear = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // find class section student list
        $studentList = $this->attendanceUploadController->stdList($academicLevel, $academicBatch, $academicSection);
        // subject group list
        $subjectGroupList = $this->subjectGroup->get();
        // checking subject group list
        if ($subjectGroupList and $subjectGroupList->count() > 0) {
            // class subject group list
            foreach ($subjectGroupList as $group) {
                $subGroupArrayList[$group->id] = ['name' => $group->name, 'type' => $group->type];
            }
        }

        // qry marker
        $qry = [
            'class_id' => $academicBatch,
            'section_id' => $academicSection,
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ];
        // class subject list

        // compulsory subject list
        $compulsorySubList = $this->classSubject->where($qry)->where(['subject_type' => 1, 'subject_list' => 0])->get(['id', 'subject_group', 'subject_id']);
        // elective subject list
        $electiveSubListOne = $this->classSubject->where($qry)->where(['subject_type' => 2])->whereIn('subject_list', [0, 1])->get(['id', 'subject_group', 'subject_id']);
        $electiveSubListTwo = $this->classSubject->where($qry)->where(['subject_type' => 2])->whereIn('subject_list', [0, 2])->get(['id', 'subject_group', 'subject_id']);
        $electiveSubListThree = $this->classSubject->where($qry)->where(['subject_type' => 2])->whereIn('subject_list', [0, 3])->get(['id', 'subject_group', 'subject_id']);
        // elective subject list
        $optionalSubList = $this->classSubject->where($qry)->whereIn('subject_type', [2, 3])->where('subject_list', 0)->get(['id', 'subject_group', 'subject_id']);

        // subject re-arranging
        $compulsorySubList = $this->reArrangeClassGroupSubjectList($compulsorySubList);
        $electiveSubListOne = $this->reArrangeClassGroupSubjectList($electiveSubListOne);
        $electiveSubListTwo = $this->reArrangeClassGroupSubjectList($electiveSubListTwo);
        $electiveSubListThree = $this->reArrangeClassGroupSubjectList($electiveSubListThree);
        $optionalSubList = $this->reArrangeClassGroupSubjectList($optionalSubList);

        // // find class section additional subjects
        $studentAdditionalSubjectList = $this->additionalSubject->where([
            'batch' => $academicBatch, 'section' => $academicSection
        ])->get();
        // // re-arrange student additional subject list
        $stdAddSubArrayList = $this->reArrangeStudentAdditionalSubjectList($studentAdditionalSubjectList);

        $canSave = $request->can_save;

        // return view with batch profile variable
        return view('academics::manage-academics.modals.class-section-fourth-subject-list', compact('studentList', 'compulsorySubList', 'electiveSubListOne', 'electiveSubListTwo', 'electiveSubListThree', 'optionalSubList', 'stdAddSubArrayList', 'subGroupArrayList', 'batchProfile', 'canSave'));
    }

    // re-arrange class group subject
    public function reArrangeClassGroupSubjectList($classSubjectList)
    {
        // response array list
        $groupSubArrayList = [];
        // checking
        if ($classSubjectList and !empty($classSubjectList) and count($classSubjectList) > 0) {
            // class subject looping
            foreach ($classSubjectList as $subject) {
                // checking subject group
                if ($subject->subject_group == null || $subject->subject_group == 0) continue;
                $groupSubArrayList[$subject->subject_group][] = $subject->subject_id;
            }
        }
        // return
        return $groupSubArrayList;
    }


    //store class section Additional subject list
    public function storeClassSectionAdditionalSubjectList(Request $request)
    {
        // input details
        $academicLevel = $request->input('academic_level');
        $academicBatch = $request->input('batch');
        $academicSection = $request->input('section');
        // institute details
        $academicYear = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // subject details
        $studentList = $request->input('std_list');
        // checking
        if (count($studentList) > 0) {
            // Start transaction!
            DB::beginTransaction();

            // grade creation
            try {
                // student loop counter
                $stdCount = 0;
                // subject list looping
                foreach ($studentList as $stdId => $subjectList) {

                    // group subject list
                    $addSubId = array_key_exists('add_id', $subjectList) ? $subjectList['add_id'] : 0;
                    $subList = array_key_exists('sub_list', $subjectList) ? $subjectList['sub_list'] : [];
                    $groupList = array_key_exists('group_list', $subjectList) ? $subjectList['group_list'] : [];
                    $elective = array_key_exists('elective', $subjectList) ? $subjectList['elective'] : null;
                    $optional = array_key_exists('optional', $subjectList) ? $subjectList['optional'] : null;

                    // checking
                    if ($addSubId > 0) {
                        // find student additional subjects
                        $additionalSubjectProfile = $this->additionalSubject->find($addSubId);
                    } else {
                        // create student additional subjects
                        $additionalSubjectProfile = new $this->additionalSubject();
                    }
                    // now store student subject assignment list
                    $additionalSubjectProfile->std_id = $stdId;
                    $additionalSubjectProfile->sub_list = json_encode($subList);
                    $additionalSubjectProfile->group_list = json_encode($groupList);
                    //$additionalSubjectProfile->elective = $elective;
                    //$additionalSubjectProfile->optional = $optional;
                    $additionalSubjectProfile->batch = $academicBatch;
                    $additionalSubjectProfile->section = $academicSection;
                    $additionalSubjectProfile->a_year = $academicYear;
                    // $additionalSubjectProfile->campus = $campus;
                    // $additionalSubjectProfile->institute = $institute;
                    // save and checking
                    if ($additionalSubjectProfile->save()) {
                        $stdCount++;
                    }
                }

                // checking student subject assignment
                if (count($studentList) == $stdCount) {
                    // If we reach here, then data is valid and working. Commit the queries!
                    DB::commit();
                    // return
                    return ['status' => 'success', 'msg' => 'Student Additional Subject Assigned Successfully'];
                } else {
                    // Rollback and then redirect back to form with errors
                    DB::rollback();
                    // return
                    return ['status' => 'failed', 'msg' => 'Unable to Perform the Action'];
                }
            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                DB::rollback();
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } else {
            // return
            return ['status' => 'failed', 'msg' => 'Invalid Information'];
        }
    }


    // re-arrange class subject list
    /**
     * @param $classSubjectList
     * @return array
     */
    public function reArrangeClassSubjectList($classSubjectList)
    {
        // response array list
        $classSubjectArrayList = array();

        // checking
        if ($classSubjectList and !empty($classSubjectList) and count($classSubjectList) > 0) {
            // class subject looping
            foreach ($classSubjectList as $singleClassSubject) {
                $classSubjectArrayList[] = (object)$this->ClassSubjectReturnPack($singleClassSubject);
            }
        }
        // return
        return $classSubjectArrayList;
    }


    /**
     * @param $classSubject
     * @return array
     */
    public function ClassSubjectReturnPack($classSubject)
    {
        return [
            'id' => $classSubject->id,
            'sub_class' => $classSubject->class_id,
            'sub_section' => $classSubject->section_id,
            'sub_id' => $classSubject->subject_id,
            'sub_name' => $classSubject->subject()->subject_name,
            'sub_code' => $classSubject->subject_code,
            'sub_type' => $classSubject->subject_type,
            'sub_group' => $classSubject->subject_group,
            'sub_credit' => $classSubject->subject_credit,
            'teacher' => $classSubject->subjectTeacher()
        ];
    }

    /**
     * @param $studentAdditionalSubjectList
     * @return array
     */
    public function reArrangeStudentAdditionalSubjectList($studentAdditionalSubjectList)
    {
        // response data
        $responseData = [];
        // checking
        if ($studentAdditionalSubjectList and count($studentAdditionalSubjectList) > 0) {
            // studentAdditionalSubjectList looping
            foreach ($studentAdditionalSubjectList as $additionalSubject) {
                $responseData[$additionalSubject->std_id] = ['add_id' => $additionalSubject->id, 'group_list' => json_decode($additionalSubject->group_list)];
            }
        }
        // return response
        return $responseData;
    }

    public function getAjaxSection($id)
    {
        $data = [];

        $activity = Section::where('batch_id', $id)->get();

        if ($activity->count() > 0) {
            array_push($data, '<option value="">-- Select --</option>');
            foreach ($activity as $item) {
                array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->section_name . '</option>');
            }
        }
        return json_encode($data);
    }

    public function getFormFromAcademicsBatch(Request $request)
    {
        if ($request->id) {
            $batch = Batch::findOrFail($request->id);
            $sections = $batch->section();
            $user = Auth::user();
            $employeeId = ($user->employee()) ? $user->employee()->id : null;

            if (!($user->role()->id == 1 || $user->role()->id == 6)) {
                if ($employeeId) {
                    $sectionIds = DB::table('class_teacher_assign')->where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'teacher_id' => $employeeId,
                        'batch_id' => $request->id,
                        'status' => 1
                    ])->get()->groupBy('section_id')->toArray();
                    $sections = Section::whereIn('id', array_keys($sectionIds))->get();
                }
            }
        }else{
            $sections = [];
        }

        return $sections;
    }
}

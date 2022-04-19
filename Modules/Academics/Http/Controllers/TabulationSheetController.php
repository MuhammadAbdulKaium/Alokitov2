<?php

namespace Modules\Academics\Http\Controllers;

use App\Helpers\ExamHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use App\Subject;
use CreateStudentEnrollmentHistoryTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\AcademicsApprovalLog;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\ExamCategory;
use Modules\Academics\Entities\ExamList;
use Modules\Academics\Entities\ExamMark;
use Modules\Academics\Entities\ExamMarkParameter;
use Modules\Academics\Entities\ExamName;
use Modules\Academics\Entities\Grade;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\Semester;
use Modules\Academics\Entities\SubjectMark;
use Modules\House\Entities\House;
use Modules\LevelOfApproval\Entities\ApprovalNotification;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\StdEnrollHistory;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentProfileView;

class TabulationSheetController extends Controller
{
    private $academicHelper;
    use ExamHelper;

    // constructor
    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index($type, $listId=null)
    {
        $academicYears = $this->academicHelper->getAllAcademicYears();
        $terms = Semester::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'status' => 1
        ])->get();
        $exams = ExamName::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $batches = Batch::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $termExamCategory = ExamCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'alias' => 'term-end'
        ])->first();
        $termExams = [];
        if ($termExamCategory) {
            $termExams = $termExamCategory->examNames;
        }
        
        if ($type == "exam") {
            $examList = ($listId)?ExamList::findOrFail($listId):null;
            return view('academics::exam.tabulation-sheet.tabulation-sheet-exam', compact('academicYears', 'terms', 'exams', 'examList'));
        } else if ($type == "term") {
            return view('academics::exam.tabulation-sheet.tabulation-sheet-term', compact('academicYears', 'terms', 'termExams', 'batches'));
        } else if ($type == "term-summary") {
            return view('academics::exam.tabulation-sheet.tabulation-sheet-term-summary', compact('academicYears', 'terms', 'batches'));
        } else if ($type == "year") {
            return view('academics::exam.tabulation-sheet.tabulation-sheet-year', compact('academicYears', 'batches'));
        } else {
            return abort("404");
        }
    }


    public function create()
    {
        return view('academics::create');
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        return view('academics::show');
    }


    public function edit($id)
    {
        return view('academics::edit');
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function examFromYear(Request $request)
    {
        $semesterIds = Semester::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->yearId
        ])->pluck('id');
        return ExamName::whereIn('term_id', $semesterIds)->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
    }

    public function examSheetSearchMarks(Request $request)
    {
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $semesterId = $request->termId;
        $sectionId = $request->sectionId;
        $academicsYear = AcademicsYear::findOrFail($request->yearId);

        $examMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->yearId,
            'semester_id' => $request->termId,
            'exam_id' => $request->examId,
        ])->whereIn('batch_id', $request->batchId)->get();

        $allClassSubjects = ClassSubject::where([
            ['campus_id', $this->academicHelper->getCampus()],
            ['institute_id', $this->academicHelper->getInstitute()],
        ])->whereIn('class_id', $request->batchId)->get();

        $studentEnrollments = StudentEnrollment::with('singleSection', 'singleBatch')
            ->join('student_enrollment_history', 'student_enrollment_history.enroll_id', 'student_enrollments.id')
            ->where([
                'student_enrollment_history.academic_year' => $request->yearId,
            ])->whereIn('student_enrollment_history.batch', $request->batchId)
            ->select('student_enrollment_history.*', 'student_enrollments.std_id')->get();

        $currentStudents = StudentProfileView::with('singleUser', 'singleBatch', 'singleSection')->where([
            'academic_year' => $request->yearId,
            'batch' => $request->batchId,
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        if ($sectionId) {
            $allClassSubjects = $allClassSubjects->where('section_id', $sectionId);
            $section = Section::findOrFail($sectionId);
            $examMarks = $examMarks->where('section_id', $sectionId);
            $studentEnrollments = $studentEnrollments->where('section', $sectionId);
            $currentStudents = $currentStudents->where('section', $sectionId);
        } else {
            $section = null;
        }

        $studentEnrollments = $studentEnrollments->keyBy('std_id');

        $subjectMarks = SubjectMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'exam_id' => $request->examId,
        ])->whereIn('batch_id', $request->batchId)->get()->keyBy('subject_id');

        $criterias = ExamMarkParameter::all()->keyBy('id');
        $sem = Semester::findOrFail($semesterId);

        $exam = ExamName::findOrFail($request->examId);
        $batch = Batch::with('grade')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('id', $request->batchId)->get();
        $grades = [];
        foreach ($batch as $ba) {
            $grades[$ba->id] = (sizeof($ba->grade) > 0) ? $ba->grade[0]->allDetails() : null;
        }

        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');


        $sheetData = $this->getExamWiseMarkSheet($request->yearId, $semesterId, $request->examId, $request->batchId, $sectionId);
        $examList = null; $approvalStatus = false; $approvalLogs = [];

        if (sizeof($request->batchId)<2 && $sectionId) {
            $examList = ExamList::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'academic_year_id' => $request->yearId,
                'term_id' => $request->termId,
                'exam_id' => $request->examId,
                'batch_id' => $request->batchId,
                'section_id' => $sectionId,
            ])->first();
            // if ($examList) {
            //     $approvalAccess = $this->academicHelper->getApprovalInfo('exam_result', $examList);
            //     $approvalStatus = $approvalAccess['approval_access'];
            //     $approvalLogs = AcademicsApprovalLog::with('user')->where([
            //         'campus_id' => $this->academicHelper->getCampus(),
            //         'institute_id' => $this->academicHelper->getInstitute(),
            //         'menu_id' => $examList->id,
            //         'menu_type' => 'exam_result',
            //     ])->get();
            // }
        }

        // Getting Students start
        $stdIds = $examMarks->pluck('student_id')->toArray();
        $students = StudentProfileView::with('singleUser', 'singleStudent', 'singleBatch', 'singleSection', 'singleSection', 'singleYear', 'singleEnroll.admissionYear', 'roomStudent')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('std_id', $stdIds)->get();        
        // Getting Students end

        $examMarks = $examMarks->groupBy('subject_id');
        $subjects = Subject::whereIn('id', array_keys($examMarks->toArray()))->get()->keyBy('id');

        $compact = $request->compact;
        if ($request->type == "print") {
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $user = Auth::user();
            $pdf->loadView('academics::exam.tabulation-sheet.snippets.tabulation-sheet-exam-pdf', compact('grades', 'user', 'compact', 'sheetData', 'institute', 'students', 'studentEnrollments', 'subjects', 'criterias', 'subjectMarks', 'exam', 'sem', 'houses', 'academicsYear', 'batch', 'section'))->setPaper('a2', 'landscape');
            return $pdf->stream();
        } else {
            return view('academics::exam.tabulation-sheet.snippets.tabulation-sheet-exam-table', compact('sheetData', 'compact', 'students', 'subjects', 'studentEnrollments', 'batch', 'criterias', 'subjectMarks', 'exam', 'sem', 'grades', 'houses', 'examList', 'approvalStatus', 'approvalLogs'))->render();
        }
    }

    public function termSheetSearchMarks(Request $request)
    {
        $compact = $request->compact;
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $semesterId = $request->termId;
        $sectionId = $request->sectionId;
        $academicsYear = AcademicsYear::findOrFail($request->yearId);

        $examMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->yearId,
            'semester_id' => $request->termId,
        ])->whereIn('batch_id', $request->batchId)->get();

        $studentEnrollments = StudentEnrollment::with('singleSection', 'singleBatch')
            ->join('student_enrollment_history', 'student_enrollment_history.enroll_id', 'student_enrollments.id')
            ->where([
                'student_enrollment_history.academic_year' => $request->yearId,
            ])->whereIn('student_enrollment_history.batch', $request->batchId)
            ->select('student_enrollment_history.*', 'student_enrollments.std_id')->get();

        $currentStudents = StudentProfileView::with('singleUser', 'singleBatch', 'singleSection')->where([
            'academic_year' => $request->yearId,
            'batch' => $request->batchId,
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        if ($sectionId) {
            $section = Section::findOrFail($sectionId);
            $examMarks = $examMarks->where('section_id', $sectionId);
            $studentEnrollments = $studentEnrollments->where('section', $sectionId);
            $currentStudents = $currentStudents->where('section', $sectionId);
        } else {
            $section = null;
        }

        $studentEnrollments = $studentEnrollments->keyBy('std_id');

        // Taking unique exam ids from Exam Marks
        $examIds = array_keys($examMarks->groupBy('exam_id')->toArray());

        if (sizeof($examIds) > 0) {
            $effectiveExamIds = ExamName::where('effective_on', 1)->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->whereIn('id', $examIds)->get()->groupBy('exam_category_id')->toArray();
            $examCategories = (sizeof($effectiveExamIds) > 0) ? ExamCategory::with('examNames')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->whereIn('id', array_keys($effectiveExamIds))->get() : [];
        } else {
            $effectiveExamIds = [];
            $examCategories = [];
        }

        // Getting Students start
        $stdIds = $examMarks->pluck('student_id')->toArray();
        $students = StudentProfileView::with('singleUser', 'singleStudent', 'singleBatch', 'singleSection', 'singleSection', 'singleYear', 'singleEnroll.admissionYear', 'roomStudent')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('std_id', $stdIds)->get();        
        // Getting Students end

        $examMarksExamWise = $examMarks->where('exam_id', $request->examId)->groupBy('subject_id');
        $examMarks = $examMarks->groupBy('subject_id');

        $subjectMarks = SubjectMark::with('exam.ExamCategory')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereIn('batch_id', $request->batchId)->get();
        $subjectMarksExamWise = $subjectMarks->where('exam_id', $request->examId)->keyBy('subject_id');
        $subjectMarks = $subjectMarks->groupBy('subject_id');

        $criterias = ExamMarkParameter::all()->keyBy('id');

        $subjects = Subject::leftJoin('subject_group_assign', 'subject_group_assign.sub_id', '=', 'subject.id')
            ->select('subject.id', 'subject.subject_name', 'subject.subject_code', 'subject_group_assign.sub_id', 'subject_group_assign.sub_group_id')
            ->whereIn('subject.id', array_keys($examMarks->toArray()))->get()->groupBy('sub_group_id')->toArray();

        $sem = Semester::findOrFail($semesterId);
        $batch = Batch::with('grade')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('id', $request->batchId)->get();
        $grades = [];
        foreach ($batch as $ba) {
            $grades[$ba->id] = (sizeof($ba->grade) > 0) ? $ba->grade[0]->allDetails() : null;
        }
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');

        $termFinalExamCategory = ExamCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'alias' => 'term-end'
        ])->first();


        $sheetData = $this->getTermWiseMarkSheet(
            $request->yearId,
            $semesterId,
            $request->examId,
            $request->batchId,
            $sectionId,
        );

        // return $sheetData;

        if ($request->type == "print") {
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $user = Auth::user();
            $pdf->loadView('academics::exam.tabulation-sheet.snippets.tabulation-sheet-term-pdf', compact(
                'sheetData',
                'user',
                'institute',
                'academicsYear',
                'sem',
                'batch',
                'section',
                'compact',
                'students',
                'studentEnrollments',
                'subjectMarks',
                'subjectMarksExamWise',
                'subjects',
                'criterias',
                'grades',
                'houses',
                'examCategories',
                'termFinalExamCategory'
            ))->setPaper('a2', 'landscape');
            return $pdf->stream();
        } else {
            return view('academics::exam.tabulation-sheet.snippets.tabulation-sheet-term-table', compact(
                'sheetData',
                'batch',
                'compact',
                'students',
                'studentEnrollments',
                'subjectMarks',
                'subjectMarksExamWise',
                'subjects',
                'criterias',
                'grades',
                'houses',
                'examCategories',
                'termFinalExamCategory'
            ))->render();
        }
    }

    public function termSummarySheetSearchMarks(Request $request)
    {
        $compact = $request->compact;
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $semesterId = $request->termId;
        $sectionId = $request->sectionId;
        $academicsYear = AcademicsYear::findOrFail($request->yearId);

        $examMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->yearId,
            'semester_id' => $request->termId,
        ])->whereIn('batch_id', $request->batchId)->get();

        $studentEnrollments = StudentEnrollment::with('singleSection', 'singleBatch')
            ->join('student_enrollment_history', 'student_enrollment_history.enroll_id', 'student_enrollments.id')
            ->where([
                'student_enrollment_history.academic_year' => $request->yearId,
            ])->whereIn('student_enrollment_history.batch', $request->batchId)
            ->select('student_enrollment_history.*', 'student_enrollments.std_id')->get();

        if ($sectionId) {
            $section = Section::findOrFail($sectionId);
            $examMarks = $examMarks->where('section_id', $sectionId);
            $studentEnrollments = $studentEnrollments->where('section', $sectionId);
        } else {
            $section = null;
        }

        $studentEnrollments = $studentEnrollments->keyBy('std_id');

        // Taking unique exam ids from Exam Marks
        $examIds = array_keys($examMarks->groupBy('exam_id')->toArray());

        if (sizeof($examIds) > 0) {
            $effectiveExamIds = ExamName::where('effective_on', 1)->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->whereIn('id', $examIds)->get()->groupBy('exam_category_id')->toArray();
            $examCategories = (sizeof($effectiveExamIds) > 0) ? ExamCategory::with('examNames')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->whereIn('id', array_keys($effectiveExamIds))->get() : [];
        } else {
            $effectiveExamIds = [];
            $examCategories = [];
        }

        // Getting Students start
        $stdIds = $examMarks->pluck('student_id')->toArray();
        $students = StudentProfileView::with('singleUser', 'singleStudent', 'singleBatch', 'singleSection', 'singleSection', 'singleYear', 'singleEnroll.admissionYear', 'roomStudent')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('std_id', $stdIds)->get();        
        // Getting Students end

        $examMarks = $examMarks->groupBy('subject_id');

        $subjectMarks = SubjectMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereIn('batch_id', $request->batchId)->get()->groupBy('subject_id');

        $criterias = ExamMarkParameter::all()->keyBy('id');

        $subjects = Subject::leftJoin('subject_group_assign', 'subject_group_assign.sub_id', '=', 'subject.id')
            ->select('subject.id', 'subject.subject_name', 'subject.subject_code', 'subject_group_assign.sub_id', 'subject_group_assign.sub_group_id')
            ->whereIn('subject.id', array_keys($examMarks->toArray()))->get()->groupBy('sub_group_id')->toArray();

        $sem = Semester::findOrFail($semesterId);
        $batch = Batch::with('grade')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('id', $request->batchId)->get();
        $grades = [];
        foreach ($batch as $ba) {
            $grades[$ba->id] = (sizeof($ba->grade) > 0) ? $ba->grade[0]->allDetails() : null;
        }
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');


        //Calculating marksheet data starts
        $sheetData = $this->getTermWiseSummaryMarkSheet($request->yearId, $semesterId, $request->batchId, $sectionId);
        //Calculating marksheet data ends

        // return $sheetData;

        if ($request->type == "print") {

            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $user = Auth::user();
            $pdf->loadView('academics::exam.tabulation-sheet.snippets.tabulation-sheet-term-summary-pdf', compact('sheetData', 'user', 'institute', 'academicsYear', 'sem', 'batch', 'section', 'compact', 'students', 'studentEnrollments', 'subjectMarks', 'examMarks', 'subjects', 'criterias', 'grades', 'houses', 'examCategories'))->setPaper('a2', 'landscape');
            return $pdf->stream();
        } else {
            return view('academics::exam.tabulation-sheet.snippets.tabulation-sheet-term-summary-table', compact('sheetData', 'batch', 'compact', 'students', 'studentEnrollments', 'subjectMarks', 'examMarks', 'subjects', 'criterias', 'grades', 'houses', 'examCategories'))->render();
        }
    }


    public function yearSheetSearchMarks(Request $request)
    {
        $compact = $request->compact;
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $sectionId = $request->sectionId;
        $academicsYear = AcademicsYear::findOrFail($request->yearId);

        $allExamMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->yearId,
        ])->whereIn('batch_id', $request->batchId)->get();

        $studentEnrollments = StudentEnrollment::with('singleSection', 'singleBatch')
            ->join('student_enrollment_history', 'student_enrollment_history.enroll_id', 'student_enrollments.id')
            ->where([
                'student_enrollment_history.academic_year' => $request->yearId,
            ])->whereIn('student_enrollment_history.batch', $request->batchId)->select('student_enrollment_history.*', 'student_enrollments.std_id')->get();

        if ($sectionId) {
            $section = Section::findOrFail($sectionId);
            $allExamMarks = $allExamMarks->where('section_id', $sectionId);
            $studentEnrollments = $studentEnrollments->where('section', $sectionId);
        } else {
            $section = null;
        }

        $studentEnrollments = $studentEnrollments->keyBy('std_id');

        // Taking unique exam ids from Exam Marks
        $examIds = array_keys($allExamMarks->groupBy('exam_id')->toArray());

        if (sizeof($examIds) > 0) {
            $effectiveExamIds = ExamName::where('effective_on', 1)->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->whereIn('id', $examIds)->get()->groupBy('exam_category_id')->toArray();
            $examCategories = (sizeof($effectiveExamIds) > 0) ? ExamCategory::with('examNames')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->whereIn('id', array_keys($effectiveExamIds))->get() : [];
        } else {
            $effectiveExamIds = [];
            $examCategories = [];
        }

        // Getting Students start
        $stdIds = $allExamMarks->pluck('student_id')->toArray();
        $students = StudentProfileView::with('singleUser', 'singleStudent', 'singleBatch', 'singleSection', 'singleSection', 'singleYear', 'singleEnroll.admissionYear', 'roomStudent')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('std_id', $stdIds)->get();        
        // Getting Students end

        $examMarks = $allExamMarks->groupBy('subject_id');

        $subjectMarks = SubjectMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereIn('batch_id', $request->batchId)->get()->keyBy('subject_id');

        $criterias = ExamMarkParameter::all()->keyBy('id');

        $subjects = Subject::leftJoin('subject_group_assign', 'subject_group_assign.sub_id', '=', 'subject.id')
            ->select('subject.id', 'subject.subject_name', 'subject.subject_code', 'subject_group_assign.sub_id', 'subject_group_assign.sub_group_id')
            ->whereIn('subject.id', array_keys($examMarks->toArray()))->get()->groupBy('sub_group_id')->toArray();

        $batch = Batch::with('grade')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('id', $request->batchId)->get();
        $grades = [];
        foreach ($batch as $ba) {
            $grades[$ba->id] = (sizeof($ba->grade) > 0) ? $ba->grade[0]->allDetails() : null;
        }
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');

        $allSemesters = Semester::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'status' => 1
        ])->get();

        //Calculating marksheet data starts
        $sheetData = [];
        foreach ($students as $student) {
            $grandTotal = null;
            $totalSubjects = 0;
            $sheetData[$student->std_id] = [];
            $grandTotalFullMark = 0;
            $gradesBatchWise = $grades[$studentEnrollments[$student->std_id]->batch];

            foreach ($subjects as $key => $subjectGroup) {
                $sheetData[$student->std_id][$key] = [];
                $totalMark = null;
                $totalFullMark = 0;
                $j = 0;
                foreach ($subjectGroup as $subject) {
                    $sheetData[$student->std_id][$key][$subject['id']] = [];
                }
                foreach ($allSemesters as $semester) {
                    $filteredExamMarks = $allExamMarks->where('semester_id', $semester->id)->groupBy('subject_id');
                    $data = $this->getTermWiseMarkSheetData($student->std_id, $key, $subjectGroup, $subjectMarks, $examCategories, $filteredExamMarks, $gradesBatchWise);

                    foreach ($subjectGroup as $subject) {
                        // $sheetData[$student->std_id][$key][$subject['id']][$semester->id] = ($data[$subject['id']]['totalMark']) ? round($data[$subject['id']]['totalMark'], 2) : "";
                        $sheetData[$student->std_id][$key][$subject['id']][$semester->id] = [
                            'totalMark' => ($data[$subject['id']]['totalMark']) ? round($data[$subject['id']]['totalMark'], 2) : null,
                            'totalFullMark' => ($data[$subject['id']]['totalMark']) ? round($data[$subject['id']]['totalFullMark'], 2) : null,
                        ];
                    }
                }
                foreach ($subjectGroup as $subject) {
                    $subjectTotal = null;
                    $subjectTotalFullMark = 0;
                    $i = 0;
                    foreach ($allSemesters as $semester) {
                        if ($sheetData[$student->std_id][$key][$subject['id']][$semester->id]) {
                            if ($sheetData[$student->std_id][$key][$subject['id']][$semester->id]['totalMark'] !== null) {
                                $subjectTotal += $sheetData[$student->std_id][$key][$subject['id']][$semester->id]['totalMark'];
                            }
                            $subjectTotalFullMark += $sheetData[$student->std_id][$key][$subject['id']][$semester->id]['totalFullMark'];
                            if ($sheetData[$student->std_id][$key][$subject['id']][$semester->id]['totalMark']) {
                                $i++;
                            }
                        }
                    }
                    if (!$key) {
                        if ($i != 0) {
                            $totalSubjects++;
                        }
                        if ($subjectTotal !== null) {
                            $sheetData[$student->std_id][$key][$subject['id']]['totalAvg'] = ($i && $subjectTotal) ? round($subjectTotal / $i, 2) : null;
                            $grandTotal += ($i) ? $subjectTotal / $i : 0;
                        } else{
                            $sheetData[$student->std_id][$key][$subject['id']]['totalAvg'] = null;
                        }
                        $grandTotalFullMark += ($i) ? $subjectTotalFullMark / $i : 0;
                    } else {
                        if ($subjectTotal !== null) {
                            $totalMark += $subjectTotal;
                        }
                        $totalFullMark += $subjectTotalFullMark;
                        $j += $i;
                    }
                }
                if ($key) {
                    if ($j != 0) {
                        $totalSubjects++;
                    }
                    if ($totalMark !== null) {
                        $sheetData[$student->std_id][$key]['totalAvg'] = ($j && $totalMark) ? round($totalMark / $j, 2) : null;
                        $grandTotal += ($j) ? $totalMark / $j : 0;
                    } else{
                        $sheetData[$student->std_id][$key]['totalAvg'] = null;
                    }
                    $grandTotalFullMark += ($j) ? $totalFullMark / $j : 0;
                }
            }

            $grandTotalConversion = ($totalSubjects) ? 100 / ($grandTotalFullMark / $totalSubjects) : 0;
            if ($grandTotal !== null) {
                $sheetData[$student->std_id]['grandTotal'] = round($grandTotal, 2);
                $sheetData[$student->std_id]['avg'] = ($totalSubjects) ? round(($grandTotal / $totalSubjects) * $grandTotalConversion, 2) : 0;
                $sheetData[$student->std_id]['grade'] = ($gradesBatchWise && $totalSubjects) ? grade($gradesBatchWise, ($grandTotal / $totalSubjects) * $grandTotalConversion) : "";
            } else {
                $sheetData[$student->std_id]['grandTotal'] = null;
                $sheetData[$student->std_id]['avg'] = null;
                $sheetData[$student->std_id]['grade'] = null;
            }
            $sheetData[$student->std_id]['hasMark'] = ($totalSubjects) ? true : false;
        }
        //Calculating marksheet data ends

        //Calculating students position according to marks starts
        $stdMarks = [];
        foreach ($sheetData as $key => $data) {
            $stdMarks[$key] = $data['avg'];
        }
        arsort($stdMarks);
        $i = 1;
        foreach ($stdMarks as $key => $stdMark) {
            if ($sheetData[$key]['hasMark']) {
                $sheetData[$key]['position'] = $i++;
            } else{
                $sheetData[$key]['position'] = null;
            }
        }
        //Calculating students position according to marks ends

        // return $sheetData;

        if ($request->type == "print") {
            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $user = Auth::user();
            $pdf->loadView('academics::exam.tabulation-sheet.snippets.tabulation-sheet-year-pdf', compact('user', 'compact', 'sheetData', 'institute', 'allSemesters', 'students', 'studentEnrollments', 'examMarks', 'subjects', 'criterias', 'grades', 'houses', 'academicsYear', 'batch', 'section', 'examCategories'))->setPaper('a2', 'landscape');
            return $pdf->stream();
        } else {
            return view('academics::exam.tabulation-sheet.snippets.tabulation-sheet-year-table', compact('sheetData', 'compact', 'allSemesters', 'students', 'studentEnrollments', 'examMarks', 'subjects', 'criterias', 'grades', 'houses', 'batch', 'examCategories'))->render();
        }
    }


    public function tabulationSheetPdf()
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('academics::exam.tabulation-sheet-pdf')->setPaper('a2', 'landscape');
        return $pdf->stream();
    }


    public function  getStudentProfileSingleExamInfo($std_id, $batch, $section, $academicYear, $termId, $examId)
    {
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $semesterId = $termId;
        $sectionId = $section;
        $academicsYear = $academicYear;

        $examMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $academicsYear,
            'semester_id' => $termId,
            'exam_id' => $examId,
            'batch_id' => $batch,
            'student_id' => $std_id
        ])->get();

        $allClassSubjects = ClassSubject::where([
            ['class_id', $batch],
            ['campus_id', $this->academicHelper->getCampus()],
            ['institute_id', $this->academicHelper->getInstitute()],
        ])->get();

        if ($sectionId) {
            $allClassSubjects = $allClassSubjects->where('section_id', $sectionId);
            $section = Section::findOrFail($sectionId);
            $examMarks = $examMarks->where('section_id', $sectionId);
        } else {
            $section = null;
        }

        $subjectMarks = SubjectMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'exam_id' => $examId,
            'batch_id' => $batch
        ])->get()->keyBy('subject_id');

        $criterias = ExamMarkParameter::all()->keyBy('id');
        $sem = Semester::findOrFail($semesterId);

        // Pulling subject marks
        $examParameters = SubjectMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'exam_id' => $examId,
            'batch_id' => $batch
        ])->get();

        $examMarks = $examMarks->groupBy('subject_id');

        $subjects = Subject::whereIn('id', array_keys($examMarks->toArray()))->get()->keyBy('id');

        //Elective Optional Subject student ids start
        $subjectStdIds = [];
        foreach ($subjects as $key => $subject) {
            $classSubject = $allClassSubjects->where('subject_id', $subject->id)->firstWhere('subject_type', '!=', 1);

            if ($classSubject) {
                $stdIds = $this->academicHelper->stdIdsHasTheSub($batch, $sectionId, $subject->id);
                $subjectStdIds[$subject->id] = $stdIds;
            }
        }
        //Elective Optional Subject student ids end

        $exam = ExamName::findOrFail($examId);
        $batch = Batch::findOrFail($batch);
        $grades = (sizeof($batch->grade) > 0) ? $batch->grade[0]->allDetails() : null;

        $i = 0;
        $totalMark = 0;
        $totalAvgMark = 0;
        $isFail = false;
        $sheetData[$std_id] = [];

        foreach ($subjects as $subject) {
            $data = $this->getExamWiseMarkSheetData($std_id, $subject->id, $subjectMarks, $examMarks);
            $sheetData[$std_id][$subject->id] = $data;
            $totalMark += $data['totalMark'];
            $totalAvgMark += $data['totalAvgMark'];
            if ($data['marksFound']) {
                $i++;
            }
            if ($data['isFail']) {
                $isFail = true;
            }
        }
        if ($grades) {
            if ($isFail) {
                $grade = grade($grades, 0);
            } else {
                $grade = grade($grades, ($i) ? $totalAvgMark / $i : 0);
            }
        } else {
            $grade = "";
        }

        $sheetData[$std_id]['grandTotalMark'] =  $totalMark;
        $sheetData[$std_id]['totalAvgMark'] =  ($i) ? round($totalMark / $i, 2) : 0;
        $sheetData[$std_id]['totalAvgMarkPercentage'] =  ($i) ? round($totalAvgMark / $i, 2) : 0;
        $sheetData[$std_id]['isFail'] =  $isFail;
        $sheetData[$std_id]['gpa'] =  $grade;

        $criteriaUniqueIds = [];

        foreach ($subjects as $subject) {
            if (isset($subjectMarks[$subject->id])) {
                $marks = json_decode($subjectMarks[$subject->id]->marks, 1);
                $conversionPoint = ($subjectMarks[$subject->id]->full_marks != 0) ? $subjectMarks[$subject->id]->full_mark_conversion / $subjectMarks[$subject->id]->full_marks : 0;
                foreach ($marks['fullMarks'] as $key => $mark) {
                    array_push($criteriaUniqueIds, $key);
                }
            }
        }
        $criteriaUniqueIds = array_unique($criteriaUniqueIds);
        $criteriasAll = ExamMarkParameter::get()->keyBy('id');


        return view('student::pages.student-profile.modals.profile-single-exam-info', compact('criteriasAll', 'criteriaUniqueIds', 'std_id', 'sheetData', 'subjects', 'criterias', 'subjectMarks', 'exam', 'sem'));
    }

    public function examResultApprove(Request $request)
    {
        $auth_user_id = Auth::user()->id;
        $approvalData = ExamList::findOrFail($request->exam_list_id);
        
        DB::beginTransaction();
        try {
            // if ($approvalData->publish_status == 1) {
            if ($approvalData->publish_status == 0 || $approvalData->publish_status == 1) {
                // $approval_info = $this->academicHelper->getApprovalInfo('exam_result', $approvalData);
                $step = $approvalData->step;
                // $approval_access = $approval_info['approval_access'];
                // $last_step = $approval_info['last_step'];
                // if($approval_access){
                //     $approvalLayerPassed = $this->academicHelper->approvalLayerPassed('exam_result', $approvalData, true);

                //     if ($approvalLayerPassed) {
                //         // Notification level update for level of approval start
                //         ApprovalNotification::where([
                //             'unique_name' => 'exam_result',
                //             'menu_id' => $request->exam_list_id,
                //             'action_status' => 0,
                //             'campus_id' => $this->academicHelper->getCampus(),
                //             'institute_id' => $this->academicHelper->getInstitute(),
                //         ])->update(['approval_level' => $step+1]);
                //         // Notification level update for level of approval end
                //         if($step==$last_step){
                            $updateData = [
                                'publish_status'=>2,
                                'step'=>$step+1
                            ];
                            // Notification status update for level of approval start
                        //     $approvalHistoryInfo = $this->academicHelper->generateApprovalHistoryInfo('exam_result', $approvalData);
                        //     ApprovalNotification::where([
                        //         'unique_name' => 'exam_result',
                        //         'menu_id' => $request->exam_list_id,
                        //         'action_status' => 0,
                        //         'campus_id' => $this->academicHelper->getCampus(),
                        //         'institute_id' => $this->academicHelper->getInstitute(),
                        //     ])->update([
                        //         'action_status' => 1,
                        //         'approval_info' => json_encode($approvalHistoryInfo)
                        //     ]);
                        //     // Notification status update for level of approval end
                        // }else{ // end if($step==$last_step){
                        //     $updateData = [
                        //         'step'=>$step+1
                        //     ];
                        // }
                        $approvalData->update($updateData); 
                    // }
                    
                    // AcademicsApprovalLog::create([
                    //     'menu_id' => $approvalData->id,
                    //     'menu_type' => 'exam_result',
                    //     'user_id' => $auth_user_id,
                    //     'approval_layer' => $step,
                    //     'action_status' => 1,
                    //     'created_by' => $auth_user_id,
                    //     'campus_id' => $this->academicHelper->getCampus(),
                    //     'institute_id' => $this->academicHelper->getInstitute(),
                    // ]);
                    DB::commit();
                    $output = ['status' => 1, 'message' => 'Exam Result successfully approved'];
                // } else { // end if($approval_access && $approvalData->approval_level==$step){
                //     $output = ['status' => 0, 'message' => 'Sorry you have no approval'];
                // }
            } else { // end if($approvalData->status==0)
                if ($approvalData->status == 3) {
                    $output = ['status' => 0, 'message' => 'Exam Result already rejected'];
                } else {
                    $output = ['status' => 0, 'message' => 'Exam Result already approved'];
                }
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $output = ['status' => 0, 'message' => $th];
        }

        return $output;
    }
}

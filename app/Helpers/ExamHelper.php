<?php

namespace App\Helpers;

use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\ExamCategory;
use Modules\Academics\Entities\ExamMark;
use Modules\Academics\Entities\ExamMarkParameter;
use Modules\Academics\Entities\ExamName;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\Semester;
use Modules\Academics\Entities\Subject;
use Modules\Academics\Entities\SubjectMark;
use Modules\House\Entities\House;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentProfileView;

trait ExamHelper
{
    protected function getExamWiseMarkSheetData($stdId, $subjectId, $subjectMarks, $examMarks)
    {
        $data = [];
        $isFail = false;
        $examMark = null;

        if (isset($examMarks[$subjectId])) {
            $examMark = $examMarks[$subjectId]->firstWhere('student_id', $stdId);
            $breakdownMark = ($examMark) ? json_decode($examMark->breakdown_mark, 1) : null;
            $convertPoint = 1;

            if ($examMark) {
                if ($examMark->total_mark) {
                    $convertPoint = $examMark->total_conversion_mark / $examMark->total_mark;
                }
            }
            $marks = json_decode($subjectMarks[$subjectId]->marks, 1);
            $parameterPassMarks = $marks['passMarks'];

            foreach ($marks['fullMarks'] as $key => $mark) {
                $thisIsFail = false;
                if (isset($breakdownMark[$key]) && isset($parameterPassMarks[$key])) {
                    if ($breakdownMark[$key] !== null) {
                        if ($parameterPassMarks[$key] > $breakdownMark[$key]) {
                            $isFail = true;
                            $thisIsFail = true;
                        }
                    }
                }

                $data[$key] = [
                    'color' => ($thisIsFail) ? 'red' : 'black',
                    'mark' => (isset($breakdownMark[$key])) ? round($breakdownMark[$key] * $convertPoint, 2) : null
                ];
            }
        }

        if ($examMark) {
            $data['totalMark'] = ($examMark->total_conversion_mark !== null) ? round($examMark->total_conversion_mark, 2) : null;
            $data['totalAvgMark'] = ($examMark->on_100 !== null) ? round($examMark->on_100, 2) : null;
            $data['marksFound'] = ($examMark->total_mark !== null) ? true : false;
        }else{
            $data['totalMark'] = null;
            $data['totalAvgMark'] = null;
            $data['marksFound'] = false;
        }
        $data['isFail'] = $isFail;

        return $data;
    }

    protected function isFailedInCriterias($examIds, $examMarks, $subjectMarks)
    {
        $subjectPassMarks = [];
        $subjectMarks = $subjectMarks->whereIn('exam_id', $examIds);
        $i = 0;
        foreach ($subjectMarks as $subjectMark) {
            $passMarks = json_decode($subjectMark->marks, true)['passMarks'];
            foreach ($passMarks as $key => $passMark) {
                if (!isset($subjectPassMarks[$key])) {
                    $subjectPassMarks[$key] = 0;
                }
                $subjectPassMarks[$key] += $passMark;
            }
            $i++;
        }
        foreach ($subjectPassMarks as $key => $subjectPassMark) {
            $subjectPassMarks[$key] = $subjectPassMark / $i;
        }

        $examObtainedMarks = [];
        $examMarks = $examMarks->whereIn('exam_id', $examIds);
        $i = 0;
        foreach ($examMarks as $examMark) {
            $marks = json_decode($examMark->breakdown_mark, true);
            foreach ($marks as $key => $mark) {
                if (!isset($examObtainedMarks[$key]) && $mark) {
                    $examObtainedMarks[$key] = 0;
                }
                if ($mark) {
                    $examObtainedMarks[$key] += $mark;
                }
            }
            $i++;
        }
        foreach ($examObtainedMarks as $key => $examObtainedMark) {
            $examObtainedMarks[$key] = $examObtainedMark / $i;
        }

        foreach ($examObtainedMarks as $key => $examObtainedMark) {
            if (isset($subjectPassMarks[$key])) {
                if ($subjectPassMarks[$key] > $examObtainedMark) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function getTermWiseMarkSheetData($stdId, $subjectGroupId, $subjectGroup, $subjectMarks, $examCategories, $examMarks, $grades)
    {
        $groupTotal = null;
        $sheetData = [];
        $hasMark = false;
        $totalSubjects = 0;
        $grandTotal = null;
        $grandTotalFullMark = null;
        $grandTotalAvgMark = null;
        $totalGroupFullMark = null;
        $failedInGroup = false;
        $isFail = false;
        $totalExamCatHasMarks = 0;

        foreach ($subjectGroup as $subject) {
            $totalMark = null;
            $sheetData[$subject['id']] = [];
            $failedInOneExam = false;
            $totalFullMark = 0;
            $isFail = false;

            foreach ($examCategories as $examCategory) {
                $examIds = $examCategory->examNames->where('effective_on', 1)->pluck('id');
                $mark = null;
                $passMark = null;
                $fullMark = null;
                if (isset($examMarks[$subject['id']])) {
                    if ($examCategory->best_count>0) {
                        $examIds = $examMarks[$subject['id']]->where('student_id', $stdId)->whereIn('exam_id', $examIds)->sortByDesc('total_conversion_mark')->take($examCategory->best_count)->pluck('exam_id');
                    }
                    $mark = $examMarks[$subject['id']]->where('student_id', $stdId)->whereIn('exam_id', $examIds)->avg('total_conversion_mark');
                }
                $sheetData[$subject['id']][$examCategory->id] = [
                    'mark' => null,
                    'isFail' => false
                ];
                if ($mark !== null) {
                    $totalMark += $mark;
                    $fullMark = $subjectMarks[$subject['id']]->whereIn('exam_id', $examIds)->avg('full_mark_conversion');
                    $passMark = $subjectMarks[$subject['id']]->whereIn('exam_id', $examIds)->avg('pass_mark_conversion');
                    $totalFullMark += $fullMark;
                    $totalGroupFullMark += $fullMark;
                    $hasMark = true;
                    $totalExamCatHasMarks++;
                    $sheetData[$subject['id']][$examCategory->id]['mark'] = round($mark, 2);
                    if (($passMark > $mark) || $this->isFailedInCriterias($examIds, $examMarks[$subject['id']]->where('student_id', $stdId), $subjectMarks[$subject['id']])) {
                        $failedInOneExam = true;
                        $failedInGroup = true;
                        $sheetData[$subject['id']][$examCategory->id]['isFail'] = true;
                    }
                }
            }

            if ($totalMark !== null) {
                $groupTotal += $totalMark;
            }
            if ($hasMark) {
                if (!$subjectGroupId) {
                    $totalSubjects++;
                    if ($totalMark !== null) {
                        $grandTotal += $totalMark;
                        $grandTotalAvgMark += ($totalFullMark) ? ($totalMark / $totalFullMark) * 100 : 0;
                    }
                    $grandTotalFullMark += $totalFullMark;
                    $totalMarkConversion = ($totalFullMark) ? 100 / $totalFullMark : 0;
                    if ($grades) {
                        $sheetData[$subject['id']]['grade'] = grade($grades, ($totalMark * $totalMarkConversion));
                        $sheetData[$subject['id']]['gradePoint'] = gradePoint($grades, ($totalMark * $totalMarkConversion));
                        $isFail = (gradePoint($grades, ($totalMark * $totalMarkConversion)) == 0) ? true : false;
                    } else {
                        $sheetData[$subject['id']]['grade'] = "";
                        $sheetData[$subject['id']]['gradePoint'] = "";
                        $isFail = "";
                    }
                    if ($failedInOneExam) {
                        if (($totalMark < ($totalFullMark * 0.6)) || ($totalExamCatHasMarks < 2)) {
                            $isFail = true;
                            $sheetData[$subject['id']]['grade'] = ($grades) ? grade($grades, 0) : "";
                            $sheetData[$subject['id']]['gradePoint'] = ($grades) ? gradePoint($grades, 0) : "";
                        }
                    }
                    $sheetData[$subject['id']]['isFail'] = $isFail;
                }
                $sheetData[$subject['id']]['totalMark'] = round($totalMark, 2);
                $sheetData[$subject['id']]['totalFullMark'] = $totalFullMark;
                $sheetData[$subject['id']]['avgMark'] = ($totalFullMark) ? round(($totalMark / $totalFullMark) * 100, 2) : 0;
            } else {
                $sheetData[$subject['id']]['totalMark'] = null;
                $sheetData[$subject['id']]['totalFullMark'] = null;
                $sheetData[$subject['id']]['avgMark'] = null;
                $sheetData[$subject['id']]['grade'] = null;
                $sheetData[$subject['id']]['gradePoint'] = null;
                $sheetData[$subject['id']]['isFail'] = null;
            }
        }

        if ($subjectGroupId) {
            if ($hasMark) {
                $totalSubjects++;
                if ($groupTotal !== null) {
                    $grandTotal += $groupTotal / sizeof($subjectGroup);
                    $grandTotalAvgMark += (($groupTotal / sizeof($subjectGroup)) / ($totalGroupFullMark / sizeof($subjectGroup))) * 100;
                    $sheetData['totalMark'] = round($groupTotal / sizeof($subjectGroup), 2);
                    $sheetData['avgMark'] = round((($groupTotal / sizeof($subjectGroup)) / ($totalGroupFullMark / sizeof($subjectGroup))) * 100, 2);
                } else{
                    $sheetData['totalMark'] = null;
                    $sheetData['avgMark'] = null;
                }
                $grandTotalFullMark += ($totalGroupFullMark / sizeof($subjectGroup));
                $sheetData['totalFullMark'] = $totalGroupFullMark / sizeof($subjectGroup);
                $totalGroupMarkConversion = 100 / ($totalGroupFullMark / sizeof($subjectGroup));
                if ($grades && ($groupTotal !== null)) {
                    $sheetData['grade'] = grade($grades, ($groupTotal / sizeof($subjectGroup)) * $totalGroupMarkConversion);
                    $sheetData['gradePoint'] = gradePoint($grades, ($groupTotal / sizeof($subjectGroup)) * $totalGroupMarkConversion);
                    $isFail = (gradePoint($grades, ($groupTotal / sizeof($subjectGroup)) * $totalGroupMarkConversion) == 0) ? true : false;
                } else {
                    $sheetData['grade'] = "";
                    $sheetData['gradePoint'] = "";
                    $isFail = "";
                }
                if ($failedInGroup && ($groupTotal !== null)) {
                    if (($groupTotal / sizeof($subjectGroup) < (($totalGroupFullMark / sizeof($subjectGroup)) * 0.6)) || ($totalExamCatHasMarks < 2)) {
                        $isFail = true;
                        $sheetData['grade'] = ($grades) ? grade($grades, 0) : "";
                        $sheetData['gradePoint'] = ($grades) ? gradePoint($grades, 0) : "";
                    }
                }
            } else {
                $sheetData['totalMark'] = null;
                $sheetData['totalFullMark'] = null;
                $sheetData['avgMark'] = null;
                $sheetData['grade'] = null;
                $sheetData['gradePoint'] = null;
            }
        }

        $sheetData['totalSubjects'] = $totalSubjects;
        $sheetData['grandTotal'] = ($grandTotal!==null)?round($grandTotal, 2):null;
        $sheetData['grandTotalFullMark'] = round($grandTotalFullMark, 2);
        $sheetData['grandTotalAvgMark'] = ($grandTotalAvgMark!==null)?round($grandTotalAvgMark, 2):null;
        $sheetData['isFail'] = $isFail;

        return $sheetData;
    }

    public function getExamWiseMarkSheet($yearId, $semesterId, $examId, $batchId, $sectionId)
    {
        $academicsYear = AcademicsYear::findOrFail($yearId);

        $examMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $yearId,
            'semester_id' => $semesterId,
            'exam_id' => $examId,
        ])->whereIn('batch_id', $batchId)->get();

        $allClassSubjects = ClassSubject::where([
            ['campus_id', $this->academicHelper->getCampus()],
            ['institute_id', $this->academicHelper->getInstitute()],
        ])->whereIn('class_id', $batchId)->get();

        $studentEnrollments = StudentEnrollment::with('singleSection', 'singleBatch')
            ->join('student_enrollment_history', 'student_enrollment_history.enroll_id', 'student_enrollments.id')
            ->where([
                'student_enrollment_history.academic_year' => $yearId,
            ])->whereIn('student_enrollment_history.batch', $batchId)
            ->select('student_enrollment_history.*', 'student_enrollments.std_id')->get();

        if ($sectionId) {
            $allClassSubjects = $allClassSubjects->where('section_id', $sectionId);
            $section = Section::findOrFail($sectionId);
            $examMarks = $examMarks->where('section_id', $sectionId);
            $studentEnrollments = $studentEnrollments->where('section', $sectionId);
        } else {
            $section = null;
        }

        $studentEnrollments = $studentEnrollments->keyBy('std_id');

        $students = StudentProfileView::with('singleUser', 'singleStudent', 'singleBatch', 'singleSection', 'singleSection', 'singleYear', 'singleEnroll.admissionYear', 'roomStudent')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('std_id', array_keys($studentEnrollments->toArray()))->get();

        $subjectMarks = SubjectMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'exam_id' => $examId,
        ])->whereIn('batch_id', $batchId)->get()->keyBy('subject_id');

        $criterias = ExamMarkParameter::all()->keyBy('id');
        $sem = Semester::findOrFail($semesterId);

        $examMarks = $examMarks->groupBy('subject_id');

        $subjects = Subject::whereIn('id', array_keys($examMarks->toArray()))->get()->keyBy('id');

        //Elective Optional Subject student ids start
        $subjectStdIds = [];

        foreach ($subjects as $key => $subject) {
            $classSubject = $allClassSubjects->where('subject_id', $subject->id)->firstWhere('subject_type', '!=', 1);

            if ($classSubject) {
                $stdIds = $this->academicHelper->stdIdsHasTheSub($batchId, $sectionId, $subject->id);
                $subjectStdIds[$subject->id] = $stdIds;
            }
        }
        //Elective Optional Subject student ids end

        $exam = ExamName::findOrFail($examId);
        $batch = Batch::with('grade')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('id', $batchId)->get();
        $grades = [];
        foreach ($batch as $ba) {
            $grades[$ba->id] = (sizeof($ba->grade) > 0) ? $ba->grade[0]->allDetails() : null;
        }

        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');


        $sheetData = [];
        //Calculating marksheet data starts
        foreach ($students as $student) {
            $i = 0;
            $totalMark = null;
            $totalAvgMark = null;
            $isFail = false;
            $sheetData[$student->std_id] = [];

            foreach ($subjects as $subject) {
                $data = $this->getExamWiseMarkSheetData($student->std_id, $subject->id, $subjectMarks, $examMarks);
                $sheetData[$student->std_id][$subject->id] = $data;
                if ($data['totalMark'] !== null) {
                    $totalMark += $data['totalMark'];
                }
                if ($data['totalAvgMark'] !== null) {
                    $totalAvgMark += $data['totalAvgMark'];
                }
                if ($data['marksFound']) {
                    $i++;
                }
                if ($data['isFail']) {
                    $isFail = true;
                }
            }

            $gradesBatchWise = $grades[$studentEnrollments[$student->std_id]->batch];
            if ($gradesBatchWise && $i > 0) {
                if ($isFail) {
                    $grade = grade($gradesBatchWise, 0);
                } else {
                    if ($totalAvgMark !== null) {
                        $grade = grade($gradesBatchWise, ($i) ? $totalAvgMark / $i : 0);
                    }else{
                        $grade = "";
                    }
                }
            } else {
                $grade = "";
            }

            $sheetData[$student->std_id]['grandTotalMark'] =  $totalMark;
            if ($i) {
                $sheetData[$student->std_id]['totalAvgMark'] =  ($totalMark !== null) ? round($totalMark / $i, 2) : "";
                $sheetData[$student->std_id]['totalAvgMarkPercentage'] =  ($totalAvgMark !== null) ? round($totalAvgMark / $i, 2) : "";
            }else{
                $sheetData[$student->std_id]['totalAvgMark'] =  "";
                $sheetData[$student->std_id]['totalAvgMarkPercentage'] =  "";
            }
            $sheetData[$student->std_id]['hasMark'] =  ($i) ? true : false;
            $sheetData[$student->std_id]['isFail'] =  $isFail;
            $sheetData[$student->std_id]['gpa'] =  $grade;
        }
        //Calculating marksheet data ends

        //Calculating students position according to marks starts
        $stdMarks = [];
        foreach ($sheetData as $key => $data) {
            $stdMarks[$key] = $data['grandTotalMark'];
        }
        arsort($stdMarks);
        $i = 1;
        foreach ($stdMarks as $key => $stdMark) {
            if ($sheetData[$key]['hasMark']) {
                $sheetData[$key]['position'] = $i++;
            }else{
                $sheetData[$key]['position'] = "";
            }
        }
        //Calculating students position according to marks ends

        return $sheetData;
    }

    // Get Full Term Wise Details Sheet
    public function getTermWiseMarkSheet($yearId, $termId, $examId, $batchId, $sectionId)
    {
        $examMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $yearId,
            'semester_id' => $termId,
        ])->whereIn('batch_id', $batchId)->get();

        $studentEnrollments = StudentEnrollment::with('singleSection', 'singleBatch')
            ->join('student_enrollment_history', 'student_enrollment_history.enroll_id', 'student_enrollments.id')
            ->where([
                'student_enrollment_history.academic_year' => $yearId,
            ])->whereIn('student_enrollment_history.batch', $batchId)
            ->select('student_enrollment_history.*', 'student_enrollments.std_id')->get();

        if ($sectionId) {
            $examMarks = $examMarks->where('section_id', $sectionId);
            $studentEnrollments = $studentEnrollments->where('section', $sectionId);
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

        $students = StudentProfileView::with('singleUser', 'singleStudent', 'singleBatch', 'singleSection', 'singleSection', 'singleYear', 'singleEnroll.admissionYear', 'roomStudent')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('std_id', array_keys($studentEnrollments->toArray()))->get();

        $examMarksExamWise = $examMarks->where('exam_id', $examId)->groupBy('subject_id');
        $examMarks = $examMarks->groupBy('subject_id');

        $subjectMarks = SubjectMark::with('exam.ExamCategory')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereIn('batch_id', $batchId)->get();
        $subjectMarksExamWise = $subjectMarks->where('exam_id', $examId)->keyBy('subject_id');
        $subjectMarks = $subjectMarks->groupBy('subject_id');

        $subjects = Subject::leftJoin('subject_group_assign', 'subject_group_assign.sub_id', '=', 'subject.id')
            ->select('subject.id', 'subject.subject_name', 'subject.subject_code', 'subject_group_assign.sub_id', 'subject_group_assign.sub_group_id')
            ->whereIn('subject.id', array_keys($examMarks->toArray()))->get()->groupBy('sub_group_id')->toArray();

        $batch = Batch::with('grade')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('id', $batchId)->get();
        $grades = [];
        foreach ($batch as $ba) {
            $grades[$ba->id] = (sizeof($ba->grade) > 0) ? $ba->grade[0]->allDetails() : null;
        }

        $termFinalExamCategory = ExamCategory::where('alias', 'term-end')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->first();

        //Calculating marksheet data starts
        $sheetData = [];
        foreach ($students as $student) {
            $grandTotal = null;
            $grandTotalFullMark = 0;
            $grandTotalAvgMark = null;
            $totalSubjects = 0;
            $sheetData[$student->std_id] = [];
            $isFail = false;
            $gradesBatchWise = $grades[$studentEnrollments[$student->std_id]->batch];

            foreach ($subjects as $key => $subjectGroup) {
                $data = $this->getTermWiseMarkSheetData($student->std_id, $key, $subjectGroup, $subjectMarks, $examCategories, $examMarks, $gradesBatchWise);
                foreach ($subjectGroup as $subject) {
                    $examWiseData = $this->getExamWiseMarkSheetData($student->std_id, $subject['id'], $subjectMarksExamWise, $examMarksExamWise);
                    $data[$subject['id']][$termFinalExamCategory->id]['details'] = $examWiseData;
                }

                $sheetData[$student->std_id][$key] = $data;
                $totalSubjects += $data['totalSubjects'];
                if ($data['grandTotal'] !== null) {
                    $grandTotal += $data['grandTotal'];
                }
                if ($data['grandTotalAvgMark']) {
                    $grandTotalAvgMark += $data['grandTotalAvgMark'];
                }
                $grandTotalFullMark += $data['grandTotalFullMark'];
                if ($data['isFail']) {
                    $isFail = true;
                }
            }

            $grandTotalConversion = ($totalSubjects) ? 100 / ($grandTotalFullMark / $totalSubjects) : 0;
            if ($grandTotal!==null) {
                $sheetData[$student->std_id]['grandTotal'] = round($grandTotal, 2);
                $sheetData[$student->std_id]['gradePoint'] = ($gradesBatchWise && $totalSubjects) ? gradePoint($gradesBatchWise, ($grandTotal / $totalSubjects) * $grandTotalConversion) : null;
                $sheetData[$student->std_id]['grade'] = ($gradesBatchWise && $totalSubjects) ? grade($gradesBatchWise, ($grandTotal / $totalSubjects) * $grandTotalConversion) : null;
            } else{
                $sheetData[$student->std_id]['grandTotal'] = null;
                $sheetData[$student->std_id]['gradePoint'] = null;
                $sheetData[$student->std_id]['grade'] = null;
            }
            if ($grandTotalAvgMark) {
                $sheetData[$student->std_id]['avg'] = ($totalSubjects) ? round(($grandTotalAvgMark / $totalSubjects), 2) : null;
            }else{
                $sheetData[$student->std_id]['avg'] = null;
            }
            if ($isFail) {
                $sheetData[$student->std_id]['gradePoint'] = ($gradesBatchWise) ? gradePoint($gradesBatchWise, 0) : null;
                $sheetData[$student->std_id]['grade'] = ($gradesBatchWise) ? grade($gradesBatchWise, 0) : null;
            }
            $sheetData[$student->std_id]['hasMark'] = ($totalSubjects) ? true : false;
            $sheetData[$student->std_id]['isFail'] = $isFail;
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
            } else {
                $sheetData[$key]['position'] = null;
            }
        }
        //Calculating students position according to marks ends

        return $sheetData;
    }

    public function getTermWiseSummaryMarkSheet($yearId, $semesterId, $batchId, $sectionId)
    {
        $academicsYear = AcademicsYear::findOrFail($yearId);

        $examMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $yearId,
            'semester_id' => $semesterId,
        ])->whereIn('batch_id', $batchId)->get();

        $studentEnrollments = StudentEnrollment::with('singleSection', 'singleBatch')
            ->join('student_enrollment_history', 'student_enrollment_history.enroll_id', 'student_enrollments.id')
            ->where([
                'student_enrollment_history.academic_year' => $yearId,
            ])->whereIn('student_enrollment_history.batch', $batchId)
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

        $students = StudentProfileView::with('singleUser', 'singleStudent', 'singleBatch', 'singleSection', 'singleSection', 'singleYear', 'singleEnroll.admissionYear', 'roomStudent')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('std_id', array_keys($studentEnrollments->toArray()))->get();

        $examMarks = $examMarks->groupBy('subject_id');

        $subjectMarks = SubjectMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereIn('batch_id', $batchId)->get()->groupBy('subject_id');

        $criterias = ExamMarkParameter::all()->keyBy('id');

        $subjects = Subject::leftJoin('subject_group_assign', 'subject_group_assign.sub_id', '=', 'subject.id')
            ->select('subject.id', 'subject.subject_name', 'subject.subject_code', 'subject_group_assign.sub_id', 'subject_group_assign.sub_group_id')
            ->whereIn('subject.id', array_keys($examMarks->toArray()))->get()->groupBy('sub_group_id')->toArray();

        $sem = Semester::findOrFail($semesterId);
        $batch = Batch::with('grade')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('id', $batchId)->get();
        $grades = [];
        foreach ($batch as $ba) {
            $grades[$ba->id] = (sizeof($ba->grade) > 0) ? $ba->grade[0]->allDetails() : null;
        }
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');


        //Calculating marksheet data starts
        $sheetData = [];
        foreach ($students as $student) {
            $grandTotal = null;
            $grandTotalFullMark = 0;
            $totalSubjects = 0;
            $sheetData[$student->std_id] = [];
            $isFail = false;
            $gradesBatchWise = $grades[$studentEnrollments[$student->std_id]->batch];

            foreach ($subjects as $key => $subjectGroup) {
                $data = $this->getTermWiseMarkSheetData($student->std_id, $key, $subjectGroup, $subjectMarks, $examCategories, $examMarks, $gradesBatchWise);
                $sheetData[$student->std_id][$key] = $data;
                $totalSubjects += $data['totalSubjects'];
                if ($data['grandTotal'] !== null) {
                    $grandTotal += $data['grandTotal'];
                }
                $grandTotalFullMark += $data['grandTotalFullMark'];
                if ($data['isFail']) {
                    $isFail = true;
                }
            }

            $grandTotalConversion = ($totalSubjects) ? 100 / ($grandTotalFullMark / $totalSubjects) : 0;
            if ($grandTotal !== null) {
                $sheetData[$student->std_id]['grandTotal'] = round($grandTotal, 2);
                $sheetData[$student->std_id]['avg'] = ($totalSubjects) ? round(($grandTotal / $totalSubjects) * $grandTotalConversion, 2) : "";
                $sheetData[$student->std_id]['grade'] = ($gradesBatchWise && $totalSubjects) ? grade($gradesBatchWise, ($grandTotal / $totalSubjects) * $grandTotalConversion) : "";
            }else{
                $sheetData[$student->std_id]['grandTotal'] = null;
                $sheetData[$student->std_id]['avg'] = null;
                $sheetData[$student->std_id]['grade'] = null;
            }
            if ($isFail) {
                $sheetData[$student->std_id]['grade'] = ($gradesBatchWise) ? grade($gradesBatchWise, 0) : "";
            }
            $sheetData[$student->std_id]['hasMark'] = ($totalSubjects) ? true : false;
            $sheetData[$student->std_id]['isFail'] = $isFail;
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
            if($sheetData[$key]['hasMark']){
                $sheetData[$key]['position'] = $i++;
            }else{
                $sheetData[$key]['position'] = null;
            }
        }
        //Calculating students position according to marks ends

        return $sheetData;
    }

    public function getMaxMinSubjectMarks($subjects, $sheetData)
    {
        //Calculating subject wise highest and lowest marks starts
        $subjectGroupMarks = [];
        $subjectMarks = [];
        foreach ($subjects as $key => $subjectGroup) {
            if ($key) {
                $subjectGroupMarks[$key] = [
                    'highestMark' => 0,
                    'lowestMark' => 100
                ];
                foreach ($sheetData as $stdId => $data) {
                    if ($data[$key]['grandTotal'] && $data[$key]['grandTotal'] != 0) {
                        if ($subjectGroupMarks[$key]['highestMark'] < $data[$key]['grandTotal']) {
                            $subjectGroupMarks[$key]['highestMark'] = $data[$key]['grandTotal'];
                        }
                        if ($subjectGroupMarks[$key]['lowestMark'] > $data[$key]['grandTotal']) {
                            $subjectGroupMarks[$key]['lowestMark'] = $data[$key]['grandTotal'];
                        }
                    }
                }
            } else {
                foreach ($subjectGroup as $subject) {
                    $subjectMarks[$subject['id']] = [
                        'highestMark' => 0,
                        'lowestMark' => 100
                    ];
                    foreach ($sheetData as $stdId => $data) {
                        if ($data[$key][$subject['id']]['totalMark'] && $data[$key][$subject['id']]['totalMark'] != 0) {
                            if ($subjectMarks[$subject['id']]['highestMark'] < $data[$key][$subject['id']]['totalMark']) {
                                $subjectMarks[$subject['id']]['highestMark'] = $data[$key][$subject['id']]['totalMark'];
                            }
                            if ($subjectMarks[$subject['id']]['lowestMark'] > $data[$key][$subject['id']]['totalMark']) {
                                $subjectMarks[$subject['id']]['lowestMark'] = $data[$key][$subject['id']]['totalMark'];
                            }
                        }
                    }
                }
            }
        }
        //Calculating subject wise highest and lowest marks ends

        return [
            'subjectGroupMarks' => $subjectGroupMarks,
            'subjectMarks' => $subjectMarks,
        ];
    }

    public function getFirstLastStudents($sheetData)
    {
        $stdMarks = [];
        foreach ($sheetData as $key => $data) {
            $stdMarks[$key] = $data['avg'];
        }
        arsort($stdMarks);

        return [
            'first' => array_key_first($stdMarks),
            'last' => array_key_last($stdMarks)
        ];
    }
}

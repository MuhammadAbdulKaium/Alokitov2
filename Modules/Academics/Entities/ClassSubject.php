<?php

namespace Modules\Academics\Entities;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\SubjectGroup;
use Modules\Academics\Entities\ClassSubStudent;
use Modules\Academics\Entities\Batch;

class ClassSubject extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'class_subjects';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = ['*'];


    public function batch()
    {
        return $batch = $this->belongsTo('Modules\Academics\Entities\Batch', 'class_id', 'id')->first();
    }

    public function section()
    {
        return $section = $this->belongsTo('Modules\Academics\Entities\Section', 'section_id', 'id')->first();
    }

    public function subject()
    {
        return $subject = $this->belongsTo('App\Subject', 'subject_id', 'id')->first();
    }

    public function teacher()
    {
        return $this->hasMany('Modules\Academics\Entities\SubjectTeacher', 'class_subject_id', 'id')->get();
    }

    public function subjectTeacher()
    {
        return $count = $this->hasMany('Modules\Academics\Entities\SubjectTeacher', 'class_subject_id', 'id')->get()->count();
    }

    public function teachers()
    {
        return $this->hasMany('Modules\Academics\Entities\SubjectTeacher', 'class_subject_id', 'id');
    }

    public function subjectGroup()
    {
        return $this->belongsTo('Modules\Academics\Entities\SubjectGroup', 'subject_group', 'id')->first();
    }



    // find class subject list for specific class
    public function getClassSubjectList($class)
    {
        $studentAllSubjects = $this->where(['class_id' => $class])->orderBy('sorting_order', 'ASC')->get();
        // subject array list
        $subArrayList = array();
        // subject duplicate checker array
        $mySubjectListArray = [];
        // looping
        foreach ($studentAllSubjects as $singleSubject) {
            // checking subject duplicate entry
            if (array_key_exists($singleSubject->subject_id, $mySubjectListArray) == false) {
                // record subject entry
                $mySubjectListArray[$singleSubject->subject_id] = $singleSubject->subject_id;

                // get subject details
                $subjectDetails = $singleSubject->subject();
                $subjectGroup = $singleSubject->subjectGroup();
                // adding to the array list
                $subArrayList[] = [
                    'cs_id' => $singleSubject->id,
                    'id' => $subjectDetails->id,
                    'name' => $subjectDetails->subject_name,
                    'code' => $subjectDetails->subject_code,
                    'pass_mark' => $singleSubject->pass_mark,
                    'exam_mark' => $singleSubject->exam_mark,
                    'is_countable' => $singleSubject->is_countable,
                    'has_group' => $subjectGroup ? 1 : 0,
                    'group_id' => $singleSubject->subject_group,
                    'group_name' => $subjectGroup ? $subjectGroup->name : 'No Group',
                    'type' => $singleSubject->subject_type,
                    'list' => $singleSubject->subject_list,
                ];
            }
        }
        // return academic class subject array list
        return $subArrayList;
    }


    //////////////////////////////// Class Subject Group Section ////////////////////////////////

    public function findClassSubjectGroupList($class, $year = 0)
    {
        // find batch and sections
        $batchProfile = Batch::find($class);
        // campus and institute details
        $campus = $batchProfile->campus;
        $institute = $batchProfile->institute;

        $classGroupList = ClassSubStudent::where(['class_id' => $class, 'year_id' => $year, 'campus_id' => $campus, 'institute_id' => $institute])->get();
        // group array list
        $classGroupListArrayList = [];
        // checking
        if ($classGroupList->count() > 0) {
            // rearrange group subject
            foreach ($classGroupList as $classGroup) {
                $classGroupListArrayList[$classGroup->subject_group] = ['limit' => $classGroup->std_limit, 'admit' => $classGroup->std_admit];
            }
        }

        $subjectGroupList = SubjectGroup::where(['campus' => $campus, 'institute' => $institute])->get(['id', 'name']);
        // group subject array list
        $subjectGroupArrayList = [];
        // rearrange group subject
        foreach ($subjectGroupList as $groupSubject) {
            $subjectGroupArrayList[$groupSubject->id] = $groupSubject->name;
        }


        // qry marker
        $qry = ['class_id' => $class];
        // class subject list
        $classSubList = $this->where($qry)->orderBy('sorting_order', 'ASC')->get(['id', 'subject_group', 'subject_id', 'subject_type', 'subject_list', 'class_id']);
        // group subject array list making
        $groupSubjectArrayList = $this->groupSubjectArrayList($classSubList, $subjectGroupArrayList, $classGroupListArrayList);
        // return class section subject list
        return $groupSubjectArrayList;
    }

    // class subject array list maker
    public function groupSubjectArrayList($classSubList, $subjectGroupArrayList, $classGroupListArrayList)
    {
        $classSubArrayList = [];
        // class subject array listing
        foreach ($classSubList as $subject) {
            // checking subject group
            if ($subject->subject_group == null || $subject->subject_group == 0) continue;
            // subject group name
            $groupName = array_key_exists($subject->subject_group, $subjectGroupArrayList) ? $subjectGroupArrayList[$subject->subject_group] : 'No Name';
            // group std info

            $subGroupStdInfo =  array_key_exists($subject->subject_group, $classGroupListArrayList) ? $classGroupListArrayList[$subject->subject_group] : [];
            // subject group name
            $groupSubjectName = 'sub_type';


            // compulsory subject
            if ($subject->subject_type == 1 and $subject->subject_list == 0) {
                // subject group name
                $groupSubjectName = 'compulsory';
            }
            // elective one subject
            elseif ($subject->subject_type == 2 and ($subject->subject_list == 0 || $subject->subject_list == 1)) {
                // subject group name
                $groupSubjectName = 'elective_one';
            }
            // elective two subject
            elseif ($subject->subject_type == 2 and $subject->subject_list == 2) {
                // subject group name
                $groupSubjectName = 'elective_two';
            }
            // elective three subject
            elseif ($subject->subject_type == 2 and $subject->subject_list == 3) {
                // subject group name
                $groupSubjectName = 'elective_three';
            }

            if (!empty($subGroupStdInfo)) {
                if (($subGroupStdInfo['admit'] < $subGroupStdInfo['limit'])) {
                    $classSubArrayList[$groupSubjectName][$subject->subject_group]['name'] = $groupName;
                    $classSubArrayList[$groupSubjectName][$subject->subject_group]['sub_list'][] = $subject->id;
                }
            } else {
                $classSubArrayList[$groupSubjectName][$subject->subject_group]['name'] = $groupName;
                $classSubArrayList[$groupSubjectName][$subject->subject_group]['sub_list'][] = $subject->id;
            }


            // optional subject
            if (($subject->subject_type == 2 || $subject->subject_type == 3) and $subject->subject_list == 0) {
                if (!empty($subGroupStdInfo)) {
                    if (($subGroupStdInfo['admit'] < $subGroupStdInfo['limit'])) {
                        $classSubArrayList['optional'][$subject->subject_group]['name'] = $groupName;
                        $classSubArrayList['optional'][$subject->subject_group]['sub_list'][] = $subject->id;
                    }
                } else {
                    $classSubArrayList['optional'][$subject->subject_group]['name'] = $groupName;
                    $classSubArrayList['optional'][$subject->subject_group]['sub_list'][] = $subject->id;
                }
            }
        }

        // return
        return $classSubArrayList;
    }
    //////////////////////////////// Class Subject Group Section ////////////////////////////////

}

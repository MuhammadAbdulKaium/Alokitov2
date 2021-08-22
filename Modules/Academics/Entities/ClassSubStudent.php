<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\AdditionalSubject;

class ClassSubStudent extends Model
{

    // Table name
    protected $table = 'class_subject_students';

    // Timestamps
    public $timestamps = false;

    // The attribute that should be used for SoftDeletes.
    // protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    // The attributes that are mass assignable.
    // protected $fillable = ['*'];



    // class section student group subject list
    public function getClassSectionStudentSubjectList($section, $class, $campus, $institute)
    {
        // academic year
        $academicYear = session()->get('academic_year');
        // find class subject list
        $classSubList = $this->getClassSubjectList($class, $section);
        // find class section student list
        $stdAddSubList = AdditionalSubject::where(['batch'=>$class, 'section'=>$section, 'a_year'=>$academicYear, 'campus'=>$campus, 'institute'=>$institute])
            ->get(['std_id', 'sub_list', 'group_list']);
        // student subject array list
        $stdSubArrayList = [];
        // student list looping
        foreach ($stdAddSubList as $singleStdSubjects){
            // student id
            $myStdId = $singleStdSubjects->std_id;
            // find student additional subject list
            $myAddSubList =  json_decode($singleStdSubjects->sub_list, true);
            // class subject looping to check student assignment
            foreach ($classSubList as $classSubject){
                // class subject details
                $csId = (int) $classSubject['cs_id'];
                $csType = (int) $classSubject['type'];
                $csCode = $classSubject['code'];
                $csName = $classSubject['name'];
                $csGroupId = $classSubject['group_id'];
                $csGroupName = $classSubject['group_name'];
                // checking subject type
                if($csType==0 || $csType==1){
                    $stdSubArrayList[$myStdId][$csGroupId]['sub_list'][] = $csCode;
                    $stdSubArrayList[$myStdId][$csGroupId]['name'] = $csGroupName;
                    $stdSubArrayList[$myStdId][$csGroupId]['type'] = 1;
                }else{
                    // checking student additional subject list
                    if(count($myAddSubList)>0){
                        // subject list looping
                        foreach($myAddSubList as $subType=>$myAddSubjects){
                            // student subject list
                            $myAddSubArray = json_decode($myAddSubjects);
                            // checking student subject list
                            if(!empty($myAddSubArray) AND in_array($csId,$myAddSubArray)){
                                $stdSubArrayList[$myStdId][$csGroupId]['sub_list'][] = $csCode;
                                $stdSubArrayList[$myStdId][$csGroupId]['name'] = $csGroupName;
                                $stdSubArrayList[$myStdId][$csGroupId]['type'] = ($subType=="opt"?0:1);
                            }
                        }
                    }
                }
            }
        }

        // return
        return $stdSubArrayList;
    }


    // find class subject list for specific class
    public function getClassSubjectList($class, $section)
    {
        $studentAllSubjects = ClassSubject::where(['class_id' => $class, 'section_id'=>$section])->orderBy('sorting_order', 'ASC')->get();
        // subject array list
        $subArrayList = array();
        // subject duplicate checker array
        $mySubjectListArray = [];
        // looping
        foreach ($studentAllSubjects as $singleSubject) {
            // checking subject duplicate entry
            if(array_key_exists($singleSubject->subject_id, $mySubjectListArray)==false){
                // record subject entry
                $mySubjectListArray[$singleSubject->subject_id] = $singleSubject->subject_id;

                // get subject details
                $subjectDetails = $singleSubject->subject();
                $subjectGroup = $singleSubject->subjectGroup();
                // adding to the array list
                $subArrayList[] = [
                    'cs_id'=>$singleSubject->id,
                    'id' => $subjectDetails->id,
                    'name' => $subjectDetails->subject_name,
                    'code' => $subjectDetails->subject_code,
                    'pass_mark' => $singleSubject->pass_mark,
                    'exam_mark' => $singleSubject->exam_mark,
                    'is_countable' => $singleSubject->is_countable,
                    'has_group' => $subjectGroup?1:0,
                    'group_id' => $singleSubject->subject_group,
                    'group_name' => $subjectGroup?$subjectGroup->name:'No Group',
                    'type' => $singleSubject->subject_type,
                    'list' => $singleSubject->subject_list,
                ];
            }
        }
        // return academic class subject array list
        return $subArrayList;
    }



}

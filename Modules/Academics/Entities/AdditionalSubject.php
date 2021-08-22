<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Helpers\AcademicHelper;

class AdditionalSubject extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'academic_additional_subjects';

    // The attribute that should be used for softDelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'std_id',
        'main_class_sub_id',
        'fourth_class_sub_id',
        'sub_list',
        'group_list',
        'batch',
        'section',
        'a_year',
        'campus',
        'institute',
    ];


    // find student/class-section  additional subject list
    public function getStudentAdditionalSubjectList($stdId, $sectionId, $classId, $academicYear, $campusId, $instituteId)
    {
        // response array list
        $responseArrayList = array();
        // qry
        $qry = ['campus'=>$campusId,'institute'=>$instituteId];
        // $qry = ['campus'=>$campusId,'institute'=>$instituteId, 'a_year'=>$academicYear];
        // checking student id
        $qry = $stdId?['std_id'=>$stdId]:['batch'=>$classId,'section'=>$sectionId];
        // find student additional subject list
        $stdSubjectList = $this->where($qry)->get();
        // checking student additional subject list
        if($stdSubjectList->count()>0){
            // additional subject list looping
            foreach ($stdSubjectList as $additionalSubject){
                // store student additional subject information
                $responseArrayList[$additionalSubject->std_id] = [
                    $additionalSubject->main_class_sub_id => 1, $additionalSubject->fourth_class_sub_id => 0
                ];
            }
        }
        // return response data
        return $responseArrayList;
    }

}

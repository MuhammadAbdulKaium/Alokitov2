<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Employee\Entities\EmployeeLeaveStructureType;
use App\Http\Controllers\Helpers\AcademicHelper;

class EmployeeLeaveStructure extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'employee_leave_structures';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
        'parent',
        'campus_id',
        'institute_id'
    ];


    public function childCount()
    {
        return self::where(['campus_id'=>session()->get('campus'),'institute_id'=>session()->get('institute')])->where('parent', '!=', 0)->get()->count();
    }

//    // return all children of this parent
//    public function myChildren()
//    {
//        return self::where(['parent' => $this->id, 'campus_id'=>session()->get('campus'), 'institute_id'=>session()->get('institute')])->get();
//    }

//    // returns employee profile
//    public function myChildren()
//    {
//        return $this->belongsTo('Modules\Employee\Entities\EmployeeLeaveStructure', 'parent', 'id' )->get();
//    }

    // return all children of this parent
    public function myParent()
    {
        return self::where(['id' => $this->parent])->first();
    }


    // return all children of this parent
    public function structureLeaveTypes()
    {
        return $this->hasMany('Modules\Employee\Entities\EmployeeLeaveStructureType', 'structure_id', 'id' )->get();
    }

}

<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectGroupAssign extends Model
{

    // use SoftDeletes;

    // Table name
    protected $table = 'subject_group_assign';

    // The attribute that should be used for softDelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = ['sub_id', 'sub_group_id'];

    // group subject
    public function subjectGroup()
    {
        return $this->belongsTo('Modules\Academics\Entities\SubjectGroup', 'sub_group_id', 'id')->first();
    }

    public function subjectGroupSingle()
    {
        return $this->belongsTo('Modules\Academics\Entities\SubjectGroup', 'sub_group_id', 'id');
    }

    // group subject
    public function subject()
    {
        return $this->belongsTo('App\Subject', 'sub_id', 'id')->first();
    }
}

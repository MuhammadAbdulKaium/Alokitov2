<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeScale extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'academics_grade_scales';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'loader_route',
        'is_active',
    ];

    // all grade details
    public function allGarde()
    {
        return $this->hasMany('Modules\Academics\Entities\Grade', 'grade_scale_id', 'id')->where([
            'institute'=> session()->get('institute'),
            'campus'=> session()->get('campus')
        ])->get();
    }



    


    
}

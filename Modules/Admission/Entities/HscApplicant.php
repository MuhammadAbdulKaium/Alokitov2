<?php

namespace Modules\Admission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HscApplicant extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'hsc_applicants';

    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    protected $hidden = ['password'];

    //The attributes that are mass assignable.
    protected $fillable = ['*'];


    // find batch
    public function year()
    {
        return $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'year', 'id')->first();
    }

    // find batch
    public function batch()
    {
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'batch', 'id')->first();
    }

    // find thana / upazilla
    public function thana()
    {
        return $this->belongsTo('Modules\Setting\Entities\City', 'a_thana', 'id')->first();
    }
    // find zilla
    public function zilla()
    {
        return $this->belongsTo('Modules\Setting\Entities\State', 'a_zilla', 'id')->first();
    }
    // find nationality
    public function nationality()
    {
        return $this->belongsTo('Modules\Setting\Entities\Country', 'nationality', 'id')->first();
    }



}

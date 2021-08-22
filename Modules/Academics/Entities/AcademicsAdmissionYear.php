<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicsAdmissionYear extends Model
{
    
    use SoftDeletes;

    protected $table = 'academics_admissionyear';

    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'year_name', 'start_date', 'end_date', 'status',
    ];

}

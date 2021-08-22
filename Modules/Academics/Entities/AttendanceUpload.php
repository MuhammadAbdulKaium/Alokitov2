<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceUpload extends Model
{

    use SoftDeletes;

    protected $table = 'attendance_upload';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'h_id',
        'std_id',
        'std_gr_no',
        'card_no',
        'entry_date_time',
        'academic_year',
        'level',
        'batch',
        'section',
        'campus',
        'institute',
        'is_device',
    ];


    public function student(){
        return $this->belongsTo('Modules\Student\Entities\StudentInformation', 'std_id', 'id')->first();
    }

    public  function section(){
        return  $this->belongsTo('Modules\Academics\Entities\Section', 'section', 'id')->first();

    }

    public  function batch(){
        return  $this->belongsTo('Modules\Academics\Entities\Batch', 'batch', 'id')->first();

    }

}

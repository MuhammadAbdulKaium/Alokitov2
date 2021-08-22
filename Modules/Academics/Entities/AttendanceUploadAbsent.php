<?php

namespace Modules\Academics\Entities;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Fee\Entities\Transaction;

class AttendanceUploadAbsent extends Model
{

    use SoftDeletes;

    protected $table = 'attendance_upload_absents';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'h_id',
        'std_id',
        'std_gr_no',
        'date',
        'academic_year',
        'level',
        'batch',
        'section',
        'campus',
        'institute',
        'entry_date_time',
    ];

    // return student profile
    public function student(){
        return $this->belongsTo('Modules\Student\Entities\StudentInformation', 'std_id', 'id')->first();
    }


    public  function section(){
        return  $this->belongsTo('Modules\Academics\Entities\Section', 'section', 'id')->first();

    }

    public  function batch(){
        return  $this->belongsTo('Modules\Academics\Entities\Batch', 'batch', 'id')->first();

    }

    public function studentProfile(){
        return $this->belongsTo('Modules\Student\Entities\StudentProfileView', 'std_id', 'std_id')->first();
    }

    // total absent paid amount calcualte
    public  function totalAbsentAmountPaid($studentId) {
        return Transaction::where('academic_year',academic_year())
            ->where('institution_id',institution_id())
            ->where('campus_id',campus_id())
            ->where('std_id',$studentId)
            ->where('payment_type',2)->sum('amount');
    }

}

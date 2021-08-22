<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceFine extends Model
{
    use SoftDeletes;
    protected $table    = 'attendance_fine_setting';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'ins_id',
        'campus_id',
        'setting_type',
        'amount',
        'form_entry_time',
        'to_entry_time',
        'sorting_order'
    ];


    public function institute()
    {
        return $this->belongsTo('Modules\Setting\Entities\Institute', 'ins_id', 'id')->first();
    }

    public function campus()
    {
        return $this->belongsTo('Modules\Setting\Entities\Campus', 'campus_id', 'id')->first();
    }
}

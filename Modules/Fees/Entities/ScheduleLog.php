<?php

namespace Modules\Fees\Entities;

use Illuminate\Database\Eloquent\Model;

class ScheduleLog extends Model
{
    protected $table = 'schedule_log';

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'ip',
        'request',
        'response',
    ];
}

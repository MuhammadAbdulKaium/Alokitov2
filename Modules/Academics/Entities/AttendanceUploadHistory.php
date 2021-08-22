<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceUploadHistory extends Model
{

    use SoftDeletes;

    protected $table = 'attendance_upload_history';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'status','name', 'file_name', 'path', 'mime', 'uploaded_at', 'campus', 'institute',
    ];
}
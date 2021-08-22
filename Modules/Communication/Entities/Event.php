<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'events';

    // The attribute that should be used for softDelete.
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_type',
        'title',
        'detail',
        'start_date_time',
        'end_date_time',
        'status',
        'campus',
        'institute',
        'created_by',
        'updated_by',
    ];
}
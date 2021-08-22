<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'notice';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'institute_id',
        'campus_id',
        'title',
        'notice_date',
        'desc',
        'user_type',
        'notice_file',
        'status',
    ];


    public function content(){
        return $this->belongsTo('App\Content', 'notice_file', 'id')->first();
    }

}

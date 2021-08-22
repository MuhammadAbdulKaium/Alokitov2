<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAttachment extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'student_attachments';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'std_id',
        'doc_id',
        'doc_type',
        'doc_details',
        'doc_submited_at',
        'doc_status',
    ];

    //returs the single content from the content db table
    public function singleContent()
    {   
        // getting single content
        $singleContent = $this->belongsTo('App\Content', 'doc_id', 'id')->first();
        // checking
        if ($singleContent) {
            // return single content
            return $singleContent;
        } else {
            // return false
            return false;
        }
    }

    //returs the all content from the content db table
    public function allContent()
    {
        // getting all content
        $allContent = $this->belongsToMany('App\Content', 'doc_id', 'id')->get();
        // checking
        if ($allContent) {
            // return all content
            return $allContent;
        } else {
            // return false
            return false;
        }
    }

}

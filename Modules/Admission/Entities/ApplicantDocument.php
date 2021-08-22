<?php

namespace Modules\Admission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicantDocument extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'applicant_document';

    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'applicant_id',
        'doc_name',
        'doc_type',
        'doc_path',
        'doc_mime',
        'doc_details'
    ];

    // return document applicant
    public function applicant()
    {
        return $this->belongsTo('Modules\Admission\Entities\ApplicantUser', 'applicant_id', 'id')->first();
    }
}

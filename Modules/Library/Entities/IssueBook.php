<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IssueBook extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'issue_book';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'asn_no',
        'book_id',
        'isbn_no',
        'holder_id',
        'holder_type',
        'issue_date',
        'due_date',
        'status',
    ];

    public function book(){
        return $this->belongsTo('Modules\Library\Entities\Book','book_id','id')->first();
    }

    public function student()
    {
        // getting student attatchment from student attachment db table
        return $student = $this->belongsTo('Modules\Student\Entities\StudentInformation', 'holder_id', 'id')->first();
    }



    public function employee()
    {
        // getting student attatchment from student attachment db table
        return $employee = $this->belongsTo('Modules\Employee\Entities\EmployeeInformation', 'holder_id', 'id')->first();
    }
}

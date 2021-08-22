<?php

namespace Modules\Fees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceFine extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'invoice_fine';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'fees_id',
        'invoice_id',
        'payer_id',
        'fine_amount',
        'late_day'
    ];

    public function  students(){
        return $this->belongsTo('Modules\Student\Entities\StudentEnrollment','payer_id','id')->first();
    }

    public function  payer(){
        return $this->belongsTo('Modules\Student\Entities\StudentInformation','payer_id','id')->first();
    }

}

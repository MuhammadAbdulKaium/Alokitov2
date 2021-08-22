<?php

namespace Modules\Fees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PaymentExtra extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'payment_advance';

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
        'invoice_id',
        'student_id',
        'extra_amount',
    ];


    // get payer id by name
    public function  payer(){
        return $this->belongsTo('Modules\Student\Entities\StudentInformation','student_id','id')->first();
    }

}

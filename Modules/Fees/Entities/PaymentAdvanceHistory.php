<?php

namespace Modules\Fees\Entities;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class PaymentAdvanceHistory extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'payment_advance_history';

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
        'student_id',
        'amount',
        'status',
        'is_read',
        'invoice_id'
    ];

}

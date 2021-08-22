<?php

namespace Modules\Fees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGatewayResponse extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'paymentgateway_response';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'request_id',
        'track_code',
        'tran_id',
        'date',
        'time',
        'response_data',
        'status',
        'institution_id',
        'campus_id',
    ];
}


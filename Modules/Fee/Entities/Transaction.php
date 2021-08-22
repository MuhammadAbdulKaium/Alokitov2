<?php

namespace Modules\Fee\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
class Transaction extends Model
{

    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'fee_transaction';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    protected $fillable = [
        /*'id',
        'institution_id',
        'campus_id',
        'invoice_id',
        'std_id',
        'amount',
        'payment_date',
        'payment_status',
        'paid_by',
        'recipt_no',
        'status',*/

                'id',
                'invoice_id',
                'institution_id',
                'campus_id',
                'std_id',
                'amount',
                'academic_year',
                'payment_date',
                'payment_status',
                'payment_type',
                'paid_by',
                'recipt_no',
                'head_id',
                'status'

    ];

    public function invoiceProfile(){
        return $this->belongsTo('Modules\Fee\Entities\FeeInvoice', 'invoice_id', 'id')->first();
    }

    public function studentProfile(){
        return $this->belongsTo('Modules\Student\Entities\StudentProfileView', 'std_id', 'std_id')->first();
    }


}
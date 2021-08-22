<?php

namespace Modules\Fees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class InvoicePayment extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'invoice_payment';

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
        'payment_amount',
        'extra_payment_amount',
        'extra_taken',
        'payment_method_id',
        'transaction_id',
        'payment_date',
        'payment_status',
        'paid_by'
    ];

    public function payment_method(){
        return $this->belongsTo('Modules\Fees\Entities\PaymentMethod','payment_method_id','id')->first();
    }

    public function invoice(){
        return $this->belongsTo('Modules\Fees\Entities\FeesInvoice','invoice_id','id')->first();
    }

    public function fees(){
        return $this->belongsTo('Modules\Fees\Entities\Fees','fees_id','id')->first();
    }

    public function due_fine_amount(){
        return $this->belongsTo('Modules\Fees\Entities\InvoiceFine','invoice_id','invoice_id')->where('status','DUE_FINE')->first();
    }

    public function attendance_fine_amount(){
        return $this->belongsTo('Modules\Fees\Entities\InvoiceFine','invoice_id','invoice_id')->where('status','ATTENDANCE_FINE')->first();
    }

    public function getInvoiceIdByPaymentId($id){
        $paymentPrifle= DB::table('invoice_payment')->where('parent_id',$id)->first();
//        return $paymentPrifle->invoice_id;
        $invoiceProfile=DB::table('fees_invoices')->where('id',$paymentPrifle->invoice_id)->first();
        $studentProfile=DB::table('student_informations')->where('id',$invoiceProfile->payer_id)->first();
        return  $studentProfile;
    }

}

<?php

namespace Modules\Fees\Entities;

use Modules\Fees\Entities\InvoicePayment;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;


class FeesInvoice extends Model
{
    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'fees_invoices';

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
        'payer_id',
        'payer_type',
        'payer_status',
        'inform_status',
        'invoice_status',
        'waiver_fees',
        'waiver_type',
        'wf_status'
    ];


    public function fees(){
        return $this->belongsTo('Modules\Fees\Entities\Fees','fees_id','id')->first();
    }

    public function  payer(){
        return $this->belongsTo('Modules\Student\Entities\StudentInformation','payer_id','id')->first();
    }
    public function  students(){
        return $this->belongsTo('Modules\Student\Entities\StudentEnrollment','payer_id','id')->first();
    }

    //
    public function  findReduction(){
        return    $dueFineReduction=$this->hasOne('Modules\Fees\Entities\FineReduction','invoice_id','id')->first();

    }



    public  function totalPayment(){
        return InvoicePayment::where('invoice_id',$this->id)->get()->sum('payment_amount');
    }

    public function paymentTransaction(){
        return $this->hasMany('Modules\Fees\Entities\InvoicePayment','invoice_id','id')->get();
    }

    public function invoice_payment_summary(){
        return $this->hasOne('Modules\Fees\Entities\InvoicePaymentSummary','invoice_id','id')->first();

    }

    public function due_fine_amount(){
        return $this->hasOne('Modules\Fees\Entities\InvoiceFine','invoice_id','id')->where('status','DUE_FINE')->first();
    }

    public function attendance_fine_amount(){
        return $this->hasOne('Modules\Fees\Entities\InvoiceFine','invoice_id','id')->where('status','ATTENDANCE_FINE')->first();
    }




    // get fees amount by fees id and student id

    public static function  getStudentFeesAmountById($feesId,$std_id){

        $studentFeesInvoiceProfile=FeesInvoice::where('fees_id',$feesId)->where('payer_id',$std_id)->first();
// check student information profile
        if(!empty($studentFeesInvoiceProfile)) {
            // invoice profile
            $invoice = FeesInvoice::find($studentFeesInvoiceProfile->id);

            $fees = $invoice->fees();
            $std = $invoice->payer();

            $subtotal = 0;
            $totalAmount = 0;
            $totalDiscount = 0;
            foreach ($fees->feesItems() as $amount) {
                $subtotal += $amount->rate * $amount->qty;
            }
            if ($discount = $invoice->fees()->discount()) {
                $discountPercent = $discount->discount_percent;
                $totalDiscount = (($subtotal * $discountPercent) / 100);
                $totalAmount = $subtotal - $totalDiscount;
            } else {
                $totalAmount = $subtotal;
            }

            $amount = $totalAmount;
            return $amount;

        }

    }




}

<?php

namespace Modules\Fee\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Fee\Entities\Transaction;
use Modules\Fee\Entities\WaiverAssign;
use Modules\Fee\Entities\FeeWaiverType;

class FeeInvoice extends Model
{

    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'fee_invoice';

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
        'invoice_num',
        'head_id',
        'sub_head_id',
        'waiver_amount',
        'waiver_type',
        'amount',
        'student_id',
        'class_id',
        'section_id',
        'due_date',
        'start_date',
        'due_amount',
        'status',
    ];



    public function feehead(){
        return $this->belongsTo('Modules\Fee\Entities\FeeHead','head_id','id')->first();
    }

    public function subhead(){
        return $this->belongsTo('Modules\Fee\Entities\FeeSubhead','sub_head_id','id')->first();
    }

    public function studentProfile(){
        return $this->belongsTo('Modules\Student\Entities\StudentProfileView', 'student_id', 'std_id')->first();
    }

    public function section()
    {
        return $this->belongsTo('Modules\Academics\Entities\Section', 'section_id', 'id')->first();
    }

    public function isWaiver($student_id,$head_id,$invoiceAmount){
//        return 0;
        $waiverProfile= WaiverAssign::where('student_id',$student_id)
            ->where('head_id',$head_id)
            ->first();

        if(!empty($waiverProfile)){
           if($waiverProfile->amount_percentage==1) {
               return ['waiver'=>($waiverProfile->amount / 100) * $invoiceAmount,'waiver_type'=>$waiverProfile->waiver_type];
           }
           else {
               return ['waiver'=>$waiverProfile->amount,'waiver_type'=>$waiverProfile->waiver_type];
              }
        } else {
            return ['waiver'=>0,'waiver_type'=>null];
        }

    }

    public function waiverTypeName($waiverTypeId){
        $waiverTypeProfile= FeeWaiverType::find($waiverTypeId);
            if(!empty($waiverTypeProfile)) {
             return $waiverTypeProfile->name;
            } else {
             return null;
            }
    }



    // get late fine

    public function lateFine(){
        return $this->belongsTo('Modules\Fee\Entities\SubHeadFine', 'class_id', 'class_id')->first();
    }

    // get fine paid amount
    public function lateFinePaid($invoiceId){
        return Transaction::where('invoice_id',$invoiceId)
            ->where('payment_type',3)->sum('amount');
    }

    public function waiverTypeProfile(){
        return $this->belongsTo('Modules\Fee\Entities\FeeWaiverType','waiver_type','id')->first();
    }


    public function totalPayableAmount($studnetId){
        $payableTotal=FeeInvoice::where('student_id',$studnetId)->sum('amount');
        if(!empty($payableTotal)){
            return $payableTotal;
        } else {
            return 0;
        }
    }


    public function totalPaidAmount($studnetId){
        $paidTotal=FeeInvoice::where('student_id',$studnetId)->sum('paid_amount');
        if(!empty($paidTotal)){
            return $paidTotal;
        } else {
            return 0;
        }
    }

    public function totalWaiver($studentId){

        $feeInvoiceList=FeeInvoice::where('student_id',$studentId)->get();
        if(!empty($feeInvoiceList)) {
            $totalWaiver=0;
            foreach($feeInvoiceList as $invoice) {
             $waiverProfile=$this->isWaiver($invoice->student_id,$invoice->head_id,$invoice->amount);
            $totalWaiver+=$waiverProfile['waiver'];
            }
            return $totalWaiver;

        } else {
            return 0;
        }
    }


}

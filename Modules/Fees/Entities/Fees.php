<?php

namespace Modules\Fees\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
class Fees extends Model
{

    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'fees';

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
        'fee_name',
        'due_date',
        'month',
        'year',
        'fee_type',
        'description',
        'fee_status',
        'partial_allowed',
    ];

    public function feesItems(){
        return $this->hasMany('Modules\Fees\Entities\FeesItem', 'fees_id', 'id')->get();
    }

    public function payers(){
        return $this->hasMany('Modules\Fees\Entities\FeesInvoice', 'fees_id', 'id')->get();
    }

    public function invoice(){
        return $this->hasMany('Modules\Fees\Entities\FeesInvoice','fees_id','id')->get();
    }

    public function invoicePaid(){
        return $this->hasMany('Modules\Fees\Entities\FeesInvoice','fees_id','id')->where('invoice_status','1')->get();
    }

    public function invoiceUnPaid(){
        return $this->hasMany('Modules\Fees\Entities\FeesInvoice','fees_id','id')->where('invoice_status','2')->get();
    }

    public function invoiceCancel(){
        return $this->hasMany('Modules\Fees\Entities\FeesInvoice','fees_id','id')->where('invoice_status','4')->get();
    }

    public function invoicePartialAmount(){
        return $this->hasMany('Modules\Fees\Entities\FeesInvoice','fees_id','id')->where('invoice_status','3')->get();
    }


    // get only discount in

    public function removeCancelDiscount(){
        return $this->hasMany('Modules\Fees\Entities\FeesInvoice','fees_id','id')->whereIn('invoice_status',[1,2,3])->get();
    }


    public  function discount(){
        return $this->hasOne('Modules\Fees\Entities\FeesDiscount','fees_id','id')->first();
    }

    public function fees_type(){
        return $this->belongsTo('Modules\Fees\Entities\FeeType','fee_type','id')->first();
    }



}

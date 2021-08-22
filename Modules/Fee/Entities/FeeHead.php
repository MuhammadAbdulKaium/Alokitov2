<?php

namespace Modules\Fee\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class FeeHead extends Model
{

    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'fee_head';

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
        'name',
        'status',
        'ledger_id',

    ];


    public function feeHeadMonthly($head_id){
        $feeMonltyAmount= array();
        $totalAmount=0;
        for ($i=1; $i<=12; $i++){
            $feeMonltyAmount[$i] = DB::table('fee_transaction')
                ->where('institution_id', institution_id())
                ->where('campus_id', campus_id())
                ->where('academic_year', academic_year())
                ->where('head_id', $head_id)
                ->whereMonth('payment_date', date('m',strtotime("$i/12/10")))
                ->sum('amount');
            $totalAmount+=$feeMonltyAmount[$i];
        }
        return ['monthlyTotal'=>$totalAmount,'monthly'=>$feeMonltyAmount];
    }

    public function ledger(){
        return $this->belongsTo('Modules\Finance\Entities\Ledger', 'ledger_id', 'id')->first();
    }



}

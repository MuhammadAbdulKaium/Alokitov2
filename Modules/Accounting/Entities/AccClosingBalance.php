<?php

namespace Modules\Accounting\Entities;
use Illuminate\Database\Eloquent\Model;
class AccClosingBalance extends Model
{
    protected $table = "acc_closing_balance";
    protected $fillable = [];

    /*public static function getClosingBalance($headId){
        $accFYears = AccFYear::where('status', '=', 1)->get();
        $FYears = $accFYears[0]->id;
        return self::where('acc_charts_id',$headId)->where('acc_f_year_id', $FYears)->get(['balance']);
    }*/
}
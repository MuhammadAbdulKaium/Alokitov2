<?php

namespace Modules\Accounting\Entities;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class AccCharts extends Model
{
    private $companyId = "";
    private $branchId = "";
    protected $table = "acc_charts";
    protected $fillable = [];
    protected $data = array();
    protected $academicHelper;
    /**
     * child to parent
     * @return mixed
     */
//    public function __construct(AcademicHelper $academicHelper){
//        $this->academicHelper = $academicHelper;
//
//    }


    public function parent() {
        return $this->belongsTo(self::class, 'chart_parent','id')->first();
    }

    /**
     * parent to childs
    */
    public function childs(){
        return $this->hasMany(self::class, 'chart_parent','id');
    }

    /**
     * @param $headId
     * @return array
     */
    public function  chartLedgre($headId) {
        $this->companyId = session()->get('institute');
        $this->branchId = session()->get('campus');
        $mainHead = self::where('id',$headId)->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])->get();
        if(count($mainHead[0]->childs) > 0){
            foreach ($mainHead[0]->childs as $child){
                if($child->chart_type == 'L'){
                    $this->data[] = $child->id;
                }else{
                    $this->chartLedgre($child->id);
                }
            }
        }else{
            if($mainHead[0]->chart_type == 'L'){
                $this->data[] = $mainHead[0]->id;
            }
        }
        return $this->data;
    }

    /**
     * @param $headId
     * @return int
     */
    public function sumCalc($headId){
        //$headId = 1;
        return $this->sumDrCalc($headId) - $this->sumCrCalc($headId);
    }


    public function groupCloseingBlance($headId){
        return $this->subTotalOpeningBlc($headId)+$this->sumDrCalc($headId) - $this->sumCrCalc($headId);
    }

    public function headCloseingBlance($headId){
        return $this->singleOpeningBlc($headId)+$this->sumDrCalc($headId) - $this->sumCrCalc($headId);
    }



    /**
     * @param $headId
     * @return int
     */
    public function sumDrCalc($headId){
        $this->companyId = session()->get('institute');
        $this->branchId = session()->get('campus');
        $accFYears = AccFYear::where('status', '=', 1)->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])->get();
        $FYears = $accFYears[0]->id;
        $closingBalance= AccClosingBalance::where('acc_charts_id',$headId)
            ->where('acc_f_year_id', $FYears)
            ->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])
            ->get(['balance']);

        $this->data = array();
//        $result = (!empty($closingBalance[0]->balance)) ? $closingBalance[0]->balance : 0 ;
        $result=0;
        $chartLedger = $this->chartLedgre($headId);
        for($i=0;$i<count($chartLedger);$i++){
            $dr = AccVoucherEntry::select(DB::raw(' SUM(tran_amt_dr) tran_amt_dr '))->where('status','1')
                ->where('acc_charts_id',$chartLedger[$i])
                ->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])->get();
            $dr->toArray();
            $result += $dr[0]->tran_amt_dr;
        }
        /*if(count($chartLedger)>2){
           echo $chartLedger[0];
            foreach($chartLedger as $ledger){
                $dr = AccVoucherEntry::select(DB::raw(' SUM(tran_amt_dr) tran_amt_dr ')) ->where('acc_charts_id',$ledger) ->get();
                $dr->toArray();
                $result = $result + $dr[0]->tran_amt_dr;
            }
        }*/
        return $result;
    }

    /**
     * @param $headId
     * @return int
     */
    public function sumCrCalc($headId){
        $this->companyId = session()->get('institute');
        $this->branchId = session()->get('campus');
        $accFYears = AccFYear::where('status', '=', 1)
            ->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])
            ->get();
        $FYears = $accFYears[0]->id;
        $closingBalance= AccClosingBalance::where('acc_charts_id',$headId)
            ->where('acc_f_year_id', $FYears)
            ->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])
            ->get(['balance']);

        $this->data = array();
//        $result = (!empty($closingBalance[0]->balance)) ? $closingBalance[0]->balance : 0 ;
        $result=0;
        $chartLedger = $this->chartLedgre($headId);
        for($i=0;$i<count($chartLedger);$i++){
            $cr = AccVoucherEntry::select(DB::raw(' SUM(tran_amt_cr) tran_amt_cr '))
                ->where('status','1')->where('acc_charts_id',$chartLedger[$i])
                ->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])
                ->get();
            $cr->toArray();
            $result += $cr[0]->tran_amt_cr;
        }
        /*foreach($this->chartLedgre($headId) as $ledger){
            $cr = AccVoucherEntry::select(DB::raw(' SUM(tran_amt_cr) tran_amt_cr ')) ->where('acc_charts_id',$ledger) ->get();
            $cr->toArray();
            $result = $result + $cr[0]->tran_amt_cr;
        }*/
        return $result;
    }

    public function  subTotalOpeningBlc($headId){
        $this->companyId = session()->get('institute');
        $this->branchId = session()->get('campus');
        $accFYears = AccFYear::where('status', '=', 1)
            ->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])
            ->get();
         $FYears = $accFYears[0]->id;

        $closingBalance= AccClosingBalance::where('acc_charts_id',$headId)->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])
            ->get(['balance']);
        $this->data = array();
        $result = (!empty($closingBalance[0]->balance)) ? $closingBalance[0]->balance : 0 ;
        $chartLedger = $this->chartLedgre($headId);
        for($i=0;$i<count($chartLedger);$i++) {
            $dr =  AccClosingBalance::where('acc_charts_id',$chartLedger[$i])->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])
                ->get(['balance']);
            $dr->toArray();
            $result = $result + $dr[0]->balance;
        }
        return $result;
    }


    public function singleOpeningBlc($headId){
        $this->companyId = session()->get('institute');
        $this->branchId = session()->get('campus');
        $accFYears = AccFYear::where('status', '=', 1)
            ->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])
            ->get();
        $FYears = $accFYears[0]->id;

        $closingBalance= AccClosingBalance::where('acc_charts_id',$headId)->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])
            ->get(['balance']);

        $result = (!empty($closingBalance[0]->balance)) ? $closingBalance[0]->balance : 0 ;
        return $result;
    }





    public function headInfo($headId){
        return self::where('id',$headId)->get();
    }
}

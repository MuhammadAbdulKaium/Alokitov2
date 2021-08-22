<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Wildside\Userstamps\Userstamps;
use Modules\Finance\Entities\EntriesItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helpers\Accounting\FunctionCore;
use Modules\Finance\Entities\FinancialAccount;
class Ledger extends Model
{
    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'finance_ledger';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'group_id',
        'account_id',
        'dr_cr_status',
        'balance',
        'cash_acc',
        'reconciliation',
        'notes',
        'status',
    ];


    public  function  sumLedgerDrAmountById($ledger_id){
        return EntriesItem::where('ledger_id',$ledger_id)->sum('dr_amount');
    }

    public  function  sumLedgerCrAmountById($ledger_id){
        return EntriesItem::where('ledger_id',$ledger_id)->sum('cr_amount');
    }


    public  function  sumLedgerDrAmountByDate($ledger_id){
        return EntriesItem::where('ledger_id',$ledger_id)->where('created_at',date('Y-m-d'))->sum('dr_amount');
    }

    /**
     * Calculate opening balance of specified ledger account for the given
     * date range
     *
     * @param1 int ledger id
     * @param2 date start date
     * @return array D/C, Amount
     */

    function openingBalance($id, $start_date = null) {

        // function core object
        $functionCore= new FunctionCore;

        /* Opening balance */
        $op=DB::table('finance_ledger')->where('id',$id)->first();

        if (!$op) {
            $this->session->set_flashdata('error', lang('ledger_not_found_failed_op_balance'));

        }

        $op_total = 0;
        if (empty($op->op_balance)) {
            $op_total = 0;
        } else {
            $op_total = $op->op_balance;
        }
        $op_total_dc = $op->op_balance_dc;

        /* If start date is not specified then return here */
        if (is_null($start_date)) {
            return array('dc' => $op_total_dc, 'amount' => $op_total);
        }

        /* Debit total */

        $query = DB::table('finance_entries_item')->select(DB::raw(' SUM(finance_entries_item.amount) as total'))
            ->where('ledger_id',$id)
            ->where('dc', 'D');
            if (!empty($start_date)) {
                $query = $query->where('date', '<', $start_date);
            }

        $total = $query->leftJoin('finance_entries', 'finance_entries_item.entries_id', '=', 'finance_entries.id')->first();

        if (empty($total->total)) {
            $dr_total = 0;
        } else {
            $dr_total = $total->total;
        }

        /* Credit total */

        $query= DB::table('finance_entries_item')->select(DB::raw(' SUM(finance_entries_item.amount) as total'))
             ->where('ledger_id',$id)
            ->where('dc', 'C');
             if (!empty($start_date)) {
                 $query = $query->where('date', '<', $start_date);
             }

            $total=$query->leftJoin('finance_entries', 'finance_entries_item.entries_id', '=', 'finance_entries.id')->first();

        if (empty($total->total)) {
            $cr_total = 0;
        } else {
            $cr_total = $total->total;
        }

        /* Add opening balance */
        if ($op_total_dc == 'D') {
            $dr_total_final = $functionCore->calculate($op_total, $dr_total, '+');
            $cr_total_final = $cr_total;
        } else {
            $dr_total_final = $dr_total;
            $cr_total_final = $functionCore->calculate($op_total, $cr_total, '+');
        }

        /* Calculate final opening balance */
        if ($functionCore->calculate($dr_total_final, $cr_total_final, '>')) {
            $op_total = $functionCore->calculate($dr_total_final, $cr_total_final, '-');
            $op_total_dc = 'D';
        } else if ($functionCore->calculate($dr_total_final, $cr_total_final, '==')) {
            $op_total = 0;
            $op_total_dc = $op_total_dc;
        } else {
            $op_total = $functionCore->calculate($cr_total_final, $dr_total_final, '-');
            $op_total_dc = 'C';
        }

        return array('dc' => $op_total_dc, 'amount' => $op_total);
    }





    /* Return ledger name from id */
    public function getName($id) {
        // function core object
        $functionCore= new FunctionCore;

        $ledger=DB::table('finance_ledger')->where('id',$id)->first();
        if ($ledger) {
            return $functionCore->toCodeWithName($ledger->code,$ledger->name);
        } else {
            return('ERROR');
        }
    }




    // closing balance for ladeger

    function closingBalance($id, $start_date = null, $end_date = null) {

        // object for functioncode
        $functionCore=new FunctionCore;

        if (empty($id)) {
            show_404();
        }

        /* Opening balance */
        $op =  DB::table('finance_ledger')->where('id', $id)->first();

        if (!$op) {
            return "Ledger Not found!";
        }

        $op_total = 0;
        $op_total_dc = $op->op_balance_dc;
        if (is_null($start_date)) {
            if (empty( $op->op_balance)) {
                $op_total = 0;
            } else {
                $op_total = $op->op_balance;
            }
        }

        $dr_total = 0;
        $cr_total = 0;
        $dr_total_dc = 0;
        $cr_total_dc = 0;

        /* Debit total */
        $dr_conditions = array(
            'ledger_id' => $id,
            'dc' => 'D'
        );


        $query = DB::table('finance_entries_item')->select(DB::raw(' SUM(finance_entries_item.amount) as total'))
            ->where('ledger_id',$id)
            ->where('dc','D');
                    if (!empty($start_date)) {
                        $query = $query->where('date', '<=', $start_date);
                    }
                    if (!empty($end_date)) {
                        $query = $query->where('date', '>=', $end_date);
                    }
            $total=$query->leftJoin('finance_entries', 'finance_entries_item.entries_id', '=', 'finance_entries.id')->first();

        if (empty($total->total)) {
            $dr_total = 0;
        } else {
            $dr_total = $total->total;
        }

        /* Credit total */
        $cr_conditions = array(
            'entryitems.ledger_id' => $id,
            'entryitems.dc' => 'C'
        );

        if (!is_null($start_date)) {
            $cr_conditions['entries.date >='] = $start_date;
        }
        if (!is_null($end_date)) {
            $cr_conditions['entries.date <='] = $end_date;
        }


        $query = DB::table('finance_entries_item')->select(DB::raw(' SUM(finance_entries_item.amount) as total'))
            ->where('ledger_id',$id)
            ->where('dc','C');
             if (!empty($start_date)) {
                 $query = $query->where('date', '<=', $start_date);
             }
              if (!empty($end_date)) {
                  $query = $query->where('date', '>=', $end_date);
              }
        $total=$query->leftJoin('finance_entries', 'finance_entries_item.entries_id', '=', 'finance_entries.id')->first();

//        print_r($total);
//        die();
        if (empty($total->total)) {
            $cr_total = 0;
        } else {
            $cr_total = $total->total;
        }
        /* Add opening balance */
        if ($op_total_dc == 'D') {
            $dr_total_dc = $functionCore->calculate($op_total, $dr_total, '+');
            $cr_total_dc = $cr_total;
        } else {
            $dr_total_dc = $dr_total;
            $cr_total_dc = $functionCore->calculate($op_total, $cr_total, '+');
        }

        /* $this->calculate and update closing balance */
        $cl = 0;
        $cl_dc = '';
        if ($functionCore->calculate($dr_total_dc, $cr_total_dc, '>')) {
            $cl = $functionCore->calculate($dr_total_dc, $cr_total_dc, '-');
            $cl_dc = 'D';
        } else if ($functionCore->calculate($cr_total_dc, $dr_total_dc, '==')) {
            $cl = 0;
            $cl_dc = $op_total_dc;
        } else {
            $cl = $functionCore->calculate($cr_total_dc, $dr_total_dc, '-');
            $cl_dc = 'C';
        }

        return array('dc' => $cl_dc, 'amount' => $cl, 'dr_total' => $dr_total, 'cr_total' => $cr_total);
    }



    /* Calculate difference in opening balance */
    public function getOpeningDiff() {

        // object for functioncode
        $functionCore=new FunctionCore;

        $instituteId= session()->get('institute');
        $campus= session()->get('campus');
        $finincaialAccountProfile=FinancialAccount::where('institution_id',$instituteId)->where('campus_id',$campus)->first();

        $groupListArray=DB::table('finance_group')->where('account_id',$finincaialAccountProfile->id)->pluck('id')->toArray();
        $total_op = 0;
        $ledgers =  DB::table('finance_ledger')->whereIn('group_id',$groupListArray)->get();
        foreach ($ledgers as $row => $ledger)
        {
            if ($ledger->op_balance_dc == 'D')
            {
                $total_op = $functionCore->calculate($total_op, $ledger->op_balance, '+');
            } else {
                $total_op =$functionCore->calculate($total_op, $ledger->op_balance, '-');
            }
        }

        /* Dr is more ==> $total_op >= 0 ==> balancing figure is Cr */
        if ($functionCore->calculate($total_op, 0, '>=')) {
            return array('opdiff_balance_dc' => 'C', 'opdiff_balance' => $total_op);
        } else {
            return array('opdiff_balance_dc' => 'D', 'opdiff_balance' => $functionCore->calculate($total_op, 0, 'n'));
        }
    }





}

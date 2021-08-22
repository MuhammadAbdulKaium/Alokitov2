<?php
namespace App\Http\Controllers\Helpers\Accounting;
/**
 * Class to store the entire account tree with the details
 */
use Modules\Finance\Entities\Ledger;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helpers\Accounting\FunctionCore;
use Illuminate\Support\Facades\Log;
use Modules\Finance\Entities\FinancialAccount;
class AccountList
{

    public function __construct()
    {

    }
    var $id = 0;
    var $name = '';
    var $code = '';

    var $g_parent_id = 0;   /* Group specific */
    var $g_affects_gross = 0; /* Group specific */
    var $l_group_id = 0;    /* Ledger specific */
    var $l_type = 0;    /* Ledger specific */
    var $l_reconciliation = 0;  /* Ledger specific */
    var $l_notes = '';    /* Ledger specific */

    var $op_total = 0;
    var $op_total_dc = 'D';
    var $dr_total = 0;
    var $cr_total = 0;
    var $cl_total = 0;
    var $cl_total_dc = 'D';

    var $children_groups = array();
    var $children_ledgers = array();

    var $counter = 0;

    var $only_opening = false;
    var $start_date = null;
    var $end_date = null;
    var $affects_gross = -1;

    var $Group = null;
    var $Ledger = null;

    /**
     * Initializer
     */
    function AccountList()
    {
        return;
    }

    /**
     * Setup which group id to start from
     */
    function start($id)
    {
        // institue id and campus id
        $instituteId= session()->get('institute');
        $campus= session()->get('campus');
        $finincaialAccountProfile=FinancialAccount::where('institution_id',$instituteId)->where('campus_id',$campus)->first();
        if ($id == 0)
        {
            $this->id = NULL;
            $this->name = "None";
        } else {
            $group = DB::table('finance_group')->where('id',$id)->where('account_id',$finincaialAccountProfile->id)->first();
            if($group){
                $this->id = $group->id;
                $this->name = $group->name;
                $this->code = $group->code;
                $this->g_parent_id = $group->parent_id;
                $this->g_affects_gross = $group->affects_gross;
            }
        }

        $this->op_total = 0;
        $this->op_total_dc = 'D';
        $this->dr_total = 0;
        $this->cr_total = 0;
        $this->cl_total = 0;
        $this->cl_total_dc = 'D';

        /* If affects_gross set, add sub-ledgers to only affects_gross == 0 */
        if ($this->affects_gross == 1) {
            /* Skip adding sub-ledgers if affects_gross is set and value == 1 */
        } else {
            $this->add_sub_ledgers();
        }
        $this->add_sub_groups();
        unset($this->_ci);

    }

    /**
     * Find and add subgroups as objects
     */
    function add_sub_groups()
    {
        // function core object
        $functionCore=new FunctionCore;

        $conditions = array('parent_id' => $this->id);

        /* Check if net or gross restriction is set */
        if ($this->affects_gross == 0) {
            $conditions['affects_gross'] = 0;
        }
        if ($this->affects_gross == 1) {
            $conditions['affects_gross'] = 1;
        }
        /* Reset is since its no longer needed below 1st level of sub-groups */
        $this->affects_gross = -1;

        /* If primary group sort by id else sort by name */
        if ($this->id == NULL) {
            $child_group_q=DB::table('finance_group')->where($conditions)->get();
        } else {
            $child_group_q=DB::table('finance_group')->where($conditions)->get();
        }

        $counter = 0;
        foreach ($child_group_q as $row)
        {
            /* Create new AccountList object */
            $this->children_groups[$counter] = new AccountList();

            /* Initial setup */
            $this->children_groups[$counter]->Group = &$this->Group;
            $this->children_groups[$counter]->Ledger = &$this->Ledger;
            $this->children_groups[$counter]->only_opening = $this->only_opening;
            $this->children_groups[$counter]->start_date = $this->start_date;
            $this->children_groups[$counter]->end_date = $this->end_date;
            $this->children_groups[$counter]->affects_gross = -1; /* No longer needed in sub groups */

            $this->children_groups[$counter]->start($row->id);

            /* Calculating opening balance total for all the child groups */
            $temp1 = $functionCore->calculate_withdc(
                $this->op_total,
                $this->op_total_dc,
                $this->children_groups[$counter]->op_total,
                $this->children_groups[$counter]->op_total_dc
            );
            $this->op_total = $temp1['amount'];
            $this->op_total_dc = $temp1['dc'];

            /* Calculating closing balance total for all the child groups */
            $temp2 = $functionCore->calculate_withdc(
                $this->cl_total,
                $this->cl_total_dc,
                $this->children_groups[$counter]->cl_total,
                $this->children_groups[$counter]->cl_total_dc
            );
            $this->cl_total = $temp2['amount'];
            $this->cl_total_dc = $temp2['dc'];

            /* Calculate Dr and Cr total */
            $this->dr_total = $functionCore->calculate($this->dr_total, $this->children_groups[$counter]->dr_total, '+');
            $this->cr_total = $functionCore->calculate($this->cr_total, $this->children_groups[$counter]->cr_total, '+');

            $counter++;
        }
    }

    /**
     * Find and add subledgers as array items
     */
    function add_sub_ledgers()
    {
        // function core object
        $functionCore=new FunctionCore;
        $ledgerModel=new Ledger;


        $child_ledger_q=DB::table('finance_ledger')->where('group_id', $this->id)->get();
        $counter = 0;
        foreach ($child_ledger_q as $row)
        {
            $this->children_ledgers[$counter]['id']               = $row->id;
            $this->children_ledgers[$counter]['name']             = $row->name;
            $this->children_ledgers[$counter]['code']             = $row->code;
            $this->children_ledgers[$counter]['l_group_id']       = $row->group_id;
            $this->children_ledgers[$counter]['l_type']           = $row->type;
            $this->children_ledgers[$counter]['l_reconciliation'] = $row->reconciliation;
            $this->children_ledgers[$counter]['l_notes']          = $row->notes;

            /* If start date is specified dont use the opening balance since its not applicable */
            if (is_null($this->start_date)) {
                $this->children_ledgers[$counter]['op_total'] = $row->op_balance;
                $this->children_ledgers[$counter]['op_total_dc'] =$row->op_balance_dc;
            } else {
                $this->children_ledgers[$counter]['op_total'] = 0.00;
                $this->children_ledgers[$counter]['op_total_dc'] = $row->op_balance_dc;
            }

            /* Calculating current group opening balance total */
            $temp3 = $functionCore->calculate_withdc(
                $this->op_total,
                $this->op_total_dc,
                $this->children_ledgers[$counter]['op_total'],
                $this->children_ledgers[$counter]['op_total_dc']
            );

            $this->op_total = $temp3['amount'];
            $this->op_total_dc = $temp3['dc'];

            if ($this->only_opening == true) {
                /* If calculating only opening balance */
                $this->children_ledgers[$counter]['dr_total'] = 0;
                $this->children_ledgers[$counter]['cr_total'] = 0;
                $this->children_ledgers[$counter]['cl_total'] = $this->children_ledgers[$counter]['op_total'];
                $this->children_ledgers[$counter]['cl_total_dc'] = $this->children_ledgers[$counter]['op_total_dc'];
            } else {
                $cl = $ledgerModel->closingBalance( $row->id, $this->start_date, $this->end_date );
                $this->children_ledgers[$counter]['dr_total'] = $cl['dr_total'];
                $this->children_ledgers[$counter]['cr_total'] = $cl['cr_total'];
                $this->children_ledgers[$counter]['cl_total'] = $cl['amount'];
                $this->children_ledgers[$counter]['cl_total_dc'] = $cl['dc'];
            }

            /* Calculating current group closing balance total */
            $temp4 = $functionCore->calculate_withdc(
                $this->cl_total,
                $this->cl_total_dc,
                $this->children_ledgers[$counter]['cl_total'],
                $this->children_ledgers[$counter]['cl_total_dc']
            );

            $this->cl_total = $temp4['amount'];
            $this->cl_total_dc = $temp4['dc'];

            /* Calculate Dr and Cr total */
            $this->dr_total = $functionCore->calculate($this->dr_total, $this->children_ledgers[$counter]['dr_total'], '+');
            $this->cr_total = $functionCore->calculate($this->cr_total, $this->children_ledgers[$counter]['cr_total'], '+');

            $counter++;
        }
    }

}
<?php
namespace App\Http\Controllers\Helpers\Accounting;
/**
 * Class to store the entire group tree
 */
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helpers\Accounting\FunctionCore;
use Illuminate\Support\Facades\Log;
use Modules\Finance\Entities\FinancialAccount;
class LagerTree
{

    var $id = 0;
    var $name = '';
    var $code = '';

    var $children_groups = array();
    var $children_ledgers = array();

    var $counter = 0;

    var $current_id = -1;

    var $restriction_bankcash = 1;

    var $default_text = 'Please Select Ledger';

    var $Group = null;
    var $Ledger = null;
    public function __construct()
    {

    }
    /**
     * Initializer
     */
    function LedgerTree()
    {
        return;
    }

    /**
     * Setup which group id to start from
     */
    function build($id)
    {
        // institue id and campus id
        $instituteId= session()->get('institute');
        $campus= session()->get('campus');
        $finincaialAccountProfile=FinancialAccount::where('institution_id',$instituteId)->where('campus_id',$campus)->first();



        if ($id == 0)
        {
            $this->id = NULL;
            $this->name = 'none';
        } else {
            $group = DB::table('finance_group')->where('id',$id)->where('account_id',$finincaialAccountProfile->id)->first();
            if($group){
                $this->id = $group->id;
                $this->name = $group->name;
                $this->code = $group->code;
            }

        }

        $this->add_sub_ledgers();
        $this->add_sub_groups();
        // unset($this->_ci);

    }

    /**
     * Find and add subgroups as objects
     */
    function add_sub_groups()
    {

        /* If primary group sort by id else sort by name */

        if ($this->id == NULL) {
            $child_group_q = DB::table('finance_group')->where('parent_id',$this->id)->get();
        } else {
            $child_group_q = DB::table('finance_group')->where('parent_id',$this->id)->get();
        }

        $counter = 0;
        foreach ($child_group_q as $row)
        {
            /* Create new AccountList object */
            $this->children_groups[$counter] = new LagerTree();
            /* Initial setup */
            $this->children_groups[$counter]->Group = &$this->Group;
            $this->children_groups[$counter]->Ledger = &$this->Ledger;
            $this->children_groups[$counter]->current_id = $this->current_id;
            $this->children_groups[$counter]->build($row->id);
            $counter++;
        }
    }

    /**
     * Find and add subledgers as array items
     */
    function add_sub_ledgers()
    {
        $child_ledger_q=DB::table('finance_ledger')->where('group_id',$this->id)->get();
//        $child_ledger_q=DB::table('finance_ledger')->where('group_id',3)->get();
//        Log::info(print_r($child_ledger_q));

        $counter = 0;
        foreach ($child_ledger_q as $row)
        {
            $this->children_ledgers[$counter]['id'] = $row->id;
            $this->children_ledgers[$counter]['name'] = $row->name;
            $this->children_ledgers[$counter]['code'] = $row->code;
            $this->children_ledgers[$counter]['type'] = $row->type;
            $counter++;
        }
    }

    var $ledgerList = array();

    /* Convert ledger tree to a list */
    public function toList($tree, $c = 0)
    {
        $functionsCore= new FunctionCore;

        /* Add group name to list */
        if ($tree->id != 0) {
            /* Set the group id to negative value since we want to disable it */
            $this->ledgerList[-$tree->id] = $this->space($c).($functionsCore->toCodeWithName($tree->code, $tree->name));
        } else {
            $this->ledgerList[0] = $this->default_text;
        }

        /* Add child ledgers */
        if (count($tree->children_ledgers) > 0) {
            $c++;
            foreach ($tree->children_ledgers as $id => $data) {
                $ledger_name = ($functionsCore->toCodeWithName($data['code'], $data['name']));
                $this->ledgerList[$data['id']] = $this->space($c) . $ledger_name;
//                /* Add ledgers as per restrictions */
//                if ($this->restriction_bankcash == 1 ||
//                    $this->restriction_bankcash == 2 ||
//                    $this->restriction_bankcash == 3) {
//                    /* All ledgers */
//                    $this->ledgerList[$data['id']] = $this->space($c) . $ledger_name;
//                    Log::info($this->ledgerList[$data['id']]);
//                } else if ($this->restriction_bankcash == 4) {
//                    /* Only bank or cash ledgers */
//                    if ($data['type'] == 1) {
//                        $this->ledgerList[$data['id']] = $this->space($c) . $ledger_name;
//                    }
//
//                } else if ($this->restriction_bankcash == 5) {
//                    /* Only NON bank or cash ledgers */
//                    if ($data['type'] == 0) {
//                        $this->ledgerList[$data['id']] = $this->space($c) . $ledger_name;
//                    }
//                }
            }
            $c--;
//            Log::info('print_r'.print_r($this->ledgerList));
        }

        /* Process child groups recursively */
        foreach ($tree->children_groups as $id => $data) {
            $c++;
            $this->toList($data, $c);
            $c--;
        }
    }

    function space($count)
    {
        $str = '';
        for ($i = 1; $i <= $count; $i++) {
            $str .= '&nbsp;&nbsp;&nbsp;';
        }
        return $str;
    }

}

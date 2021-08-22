<?php
namespace App\Http\Controllers\Helpers\Accounting;
/**
 * Class to store the entire group tree
 */
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helpers\Accounting\FunctionCore;
use Modules\Finance\Entities\FinancialAccount;
class GroupTree
{
    var $id = 0;
    var $name = '';
    var $code = '';
    var $children_groups = array();
    var $counter = 0;
    var $current_id = -1;
    var $Group = null;

    public function __construct()
    {

    }
    /**
     * Initializer
     */
    function GroupTree()
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

        if ($this->current_id == $id) {
            return;
        }

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
            }
        }

        $this->add_sub_groups();
        // unset($this->_ci);

    }

    /**
     * Find and add subgroups as objects
     */
    function add_sub_groups()
    {
//        $conditions = array('groups.parent_id' => $this->id);

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
            $this->children_groups[$counter] = new GroupTree();

            /* Initial setup */
            $this->children_groups[$counter]->Group = &$this->Group;
            $this->children_groups[$counter]->current_id = $this->current_id;

            $this->children_groups[$counter]->build($row->id);

            $counter++;
        }
    }

    var $groupList = array();

    /* Convert group tree to a list */
    public function toList($tree, $c = 0)
    {
        $functionsCore= new FunctionCore;
        $counter = $c;

        if ($tree->id != 0) {
            $this->groupList[$tree->id] = $this->space($counter) .
                ($functionsCore->toCodeWithName($tree->code, $tree->name));
        }

        /* Process child groups recursively */
        foreach ($tree->children_groups as $id => $data) {
            $counter++;
            $this->toList($data, $counter);
            $counter--;
        }
    }

    function space($count)
    {
        $str = '';
        for ($i = 1; $i <= $count; $i++) {
            $str .= '		&nbsp;		&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        return $str;
    }
}

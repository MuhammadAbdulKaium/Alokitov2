<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Finance\Entities\Group;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Finance\Entities\FinancialAccount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helpers\Accounting\GroupTree;
class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private  $account;
    private  $group;
    private  $academicHelper;
    private  $groupTree;

    public function __construct(AcademicHelper $academicHelper, FinancialAccount $account, Group $group, GroupTree $groupTree)
    {
        $this->account=$account;
        $this->group=$group;
        $this->academicHelper=$academicHelper;
        $this->groupTree=$groupTree;

    }


    public function createGroup()
    {
        $parentGroups = new GroupTree();
        $parentGroups->Group = &$this->Group;
        $parentGroups->current_id = -1;
        $parentGroups->build(0);
        $parentGroups->toList($parentGroups, -1);
        $parentsGroups=$parentGroups->groupList;
        return view('finance::pages.group.add',compact('parentsGroups'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('finance::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function storeGroup(Request $request)
    {
//     return  $request->all();

        if(!empty($request->group_id)) {
            $groupProfile = $this->group->find($request->group_id);
            $groupProfile->name = $request->name;
            $groupProfile->account_id = $this->account->getActiveAccount();
            $groupProfile->code = $request->code;
            $groupProfile->parent_id = $request->parent_id;
            $groupProfile->affects_gross = $request->affects_gross;
            $result = $groupProfile->save();
            Session::flash('message', 'Group Updated Successfully');

        } else {
            $groupObj = new $this->group;
            $groupObj->account_id = $this->account->getActiveAccount();
            $groupObj->name = $request->name;
            $groupObj->code = $request->code;
            $groupObj->parent_id = $request->parent_id;
            $groupObj->affects_gross = $request->affects_gross;
            $result = $groupObj->save();
            Session::flash('message', 'Group Created Successfully');
        }


        return redirect()->back();

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('finance::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function editGorup($groupId)
    {


        $institutionId=$this->academicHelper->getInstitute();
        $campusId=$this->academicHelper->getCampus();
        // get active account
        $activeAccount=$this->account->where('institution_id',$institutionId)->where('campus_id',$campusId)->where('status',1)->first();

        // get parents grouup
        $parentGorup=$this->group->where('parent_id',0)->where('account_id',$activeAccount->id)->get();

        $groupProfile=$this->group->find($groupId);
        return view('finance::pages.groups-add', compact('groupProfile','parentGorup'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */

}

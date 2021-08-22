<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Finance\Entities\Ledger;
use Modules\Finance\Entities\Group;
use Modules\Finance\Entities\FinancialAccount;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\Accounting\GroupTree;
class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private  $ledger;
    private  $group;
    private  $account;

    public function  __construct(Ledger $ledger, Group $group, FinancialAccount $account)
    {
        $this->ledger=$ledger;
        $this->group=$group;
        $this->account=$account;
    }

    public function index()
    {
        return view('finance::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function ledgerCreate()
    {

        $parentGroups = new GroupTree();
        $parentGroups->Group = &$this->Group;
        $parentGroups->current_id = -1;
        $parentGroups->build(0);
        $parentGroups->toList($parentGroups, -1);
        $groupList=$parentGroups->groupList;
        return view('finance::pages.ledger.add', compact('groupList'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function ledgerStore(Request $request)
    {

//        return $request->all();
//        $activeAccount=$this->account->where('status',1)->first();

        // check leader id
        if(!empty($request->ledger_id)) {

            $ledgerProfile= $this->ledger->find($request->ledger_id);
//            $ledgerProfile->account_id=$activeAccount->id;
            $ledgerProfile->group_id=$request->group_id;
            $ledgerProfile->name=$request->name;
            $ledgerProfile->ledger_code=$request->ledger_code;
            $ledgerProfile->dr_cr_status=$request->dr_cr_status;
            $ledgerProfile->balance=$request->balance;
            $ledgerProfile->cash_acc=$request->cash_acc;
            $ledgerProfile->reconciliation=$request->reconciliation;
            $ledgerProfile->notes=$request->notes;
            $ledgerProfile->save();
            Session::flash('message','Ledger Successfully Updated ');

        } else {

            // ledger obje
            $ledgerObj= new $this->ledger;
            $ledgerObj->group_id=$request->group_id;
            $ledgerObj->name=$request->name;
            $ledgerObj->account_id=$this->account->getActiveAccount();
            $ledgerObj->code=$request->code;
            $ledgerObj->op_balance_dc=$request->op_balance_dc;
            $ledgerObj->op_balance=$request->op_balance;
            $ledgerObj->type=$request->type;
            $ledgerObj->reconciliation=$request->reconciliation;
            $ledgerObj->notes=$request->notes;
            $ledgerObj->save();
            Session::flash('message','Ledger Successfully Created ');

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
    public function ledgerEdit($ledgerId)
    {
        $activeAccount=$this->account->where('status',1)->first();
        $parentGroupList= $this->group->where('account_id',$activeAccount->id)->where('parent_id',0)->get();
        $ledgerProfile= $this->ledger->find($ledgerId);

        return view('finance::pages.ledgers-add',compact('ledgerProfile','parentGroupList'));
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
    public function destroy()
    {
    }
}

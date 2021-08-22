<?php

namespace Modules\Fee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\FeeHead;
use Illuminate\Support\Facades\Session;
use Modules\Finance\Entities\FinancialAccount;
use Modules\Finance\Entities\Group;
use Modules\Finance\Entities\Ledger;

class FeeHeadController extends Controller
{
    private $feehead;
    private $academicHelper;
    private $financialAccount;
    private $group;
    private $ledger;
    private $data;

    public function  __construct(FeeHead $feeHead, AcademicHelper $academicHelper, FinancialAccount $financialAccount, Ledger $ledger, Group $group)
    {
        $this->feehead=$feeHead;
        $this->academicHelper=$academicHelper;
        $this->financialAccount=$financialAccount;
        $this->group=$group;
        $this->ledger=$ledger;

    }


    public function store(Request $request)
    {
        $feehead_id=$request->feehead_id;
        if(!empty($feehead_id)){

            // create fee head
            $feeheadProfile= $this->feehead->find($feehead_id);
            $feeheadProfile->name=$request->name;
            $feeheadProfile->ledger_id=$request->ledger_id;
            $feeheadProfile->save();
            Session::flash('message', 'Fee Head Successfully Updated');
        }else {
            // create fee head
            $feeheadObj= new $this->feehead;
            $feeheadObj->institution_id=institution_id();
            $feeheadObj->campus_id=campus_id();
            $feeheadObj->name=$request->name;
            $feeheadObj->ledger_id=$request->ledger_id;
            $feeheadObj->save();
            Session::flash('message', 'Fee Head Successfully Created');
        }

        return redirect()->back();

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function feeHeadEdit($feeheadid)
    {
        // get feehead profile
         $this->data['feeheadProfile']=$this->feehead->find($feeheadid);
        // fee head list
        $this->data['feeHeads']=$this->feehead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();

        // get active finacail account id
        $activeAccount=$this->financialAccount
            ->where('institution_id',institution_id())
            ->where('campus_id',campus_id())
            ->first();

        // get fees group
        $groupProfile=$this->group->where('code','FEES')->where('account_id',$activeAccount->id)->first();
        $this->data['lagers']=$this->ledger->where('group_id',$groupProfile->id)->get();

        return view('fee::pages.setting.feehead',$this->data)->with('page', 'feehead');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('fee::edit');
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

<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Finance\Entities\FinancialAccount;
use Modules\Finance\Entities\Group;
use Modules\Finance\Entities\Ledger;
Use Modules\Finance\Entities\EntriesType;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\Accounting\AccountList;
use Modules\Finance\Entities\Tags;
use Illuminate\Support\Facades\DB;
class FinancialAccountController extends Controller
{
    private  $account;
    private  $group;
    private  $ledger;
    private  $entriesType;
    private  $academicHelper;
    protected $data=array();
    private $tags;


    public function __construct(AcademicHelper $academicHelper,FinancialAccount $account, Group $group, Ledger $ledger, EntriesType $entriesType, Tags $tags)
    {
        $this->account=$account;
        $this->group=$group;
        $this->ledger=$ledger;
        $this->entriesType=$entriesType;
        $this->academicHelper=$academicHelper;
        $this->tags=$tags;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function createAccount()
    {
//        $accountList=$this->account->all();
//        $activeAccount=$this->account->where('status',1)->first();
        return view('finance::pages.setting.create_account');
    }


    public function chartOfAccount()
    {


        $accountlist = new AccountList();
        $accountlist->Group = &$this->Group;
        $accountlist->Ledger = &$this->Ledger;
        $accountlist->only_opening = false;
        $accountlist->start_date = null;
        $accountlist->end_date = null;
        $accountlist->affects_gross = -1;
        $accountlist->start(0);
        $accountList = $accountlist;
        $this->data['accountList'] = $accountlist;
        $opdiff = $this->ledger->getOpeningDiff();
        $this->data['opdiff'] = $opdiff;


        return view('finance::pages.accounts.chart-of-account',$this->data);
    }



    public function accountActive(Request $request){

        $institution_id=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        // all account are deactive
        $this->account
            ->where('institution_id', $institution_id)
            ->where('campus_id', $campus_id)
            ->update(['status' => 0]);

        // only selected account are active here
        $this->account->where('account_id', $request->account_id)
            ->update(['status' => 1]);

        Session::flash('message','Account  Created ');

        return view('finance::pages.dashboard');

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
    public function store(Request $request)
    {
        $institution=$this->academicHelper->getInstitute();
        $campus=$this->academicHelper->getCampus();

//        return $request->all();
        // finacila accoutn object creat e
        $financialObj= new $this->account;
        $financialObj->institution_id=$institution;
        $financialObj->campus_id=$campus;
        $financialObj->account_name=$request->name;
        $financialObj->account_id=$request->code;
        $financialObj->f_year_start=date('Y-m-d',strtotime($request->start_date));
        $financialObj->f_year_end=date('Y-m-d',strtotime($request->end_date));
        $financialObj->address=$request->address;
        $financialObj->email=$request->email;
        $save=$financialObj->save();
        if($save){
            // insert parent group auto
            $this->createGroup($financialObj->id,'Asset','01');
            $this->createGroup($financialObj->id,'Liabilities and Owners Equity','02');
            $this->createGroup($financialObj->id,'Incomes','03');
            $this->createGroup($financialObj->id,'Expenses','04');

            // insert entry type auto
            $this->createEntryType($financialObj->id,'Receipt','receipt');
            $this->createEntryType($financialObj->id,'Payment','Payment');
            $this->createEntryType($financialObj->id,'Contra','contra');
            $this->createEntryType($financialObj->id,'Journal','journal');

            // insert tags auto
            $this->createTags($financialObj->id,'Cash','#00000','#00000');
            $this->createTags($financialObj->id,'Cheque','#4dd684','#d65858');
            $this->createTags($financialObj->id,'Transfer','#1e68d6','#f2f2f2');


        }



        Session::flash('message','Account Successfully Created ');

        return redirect()->back();

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function accountList()
    {
         $accountList=$this->account->get();
        return view('finance::pages.manage-account', compact('accountList'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function accountShow()
    {
        // active account
        $activeAccount=$this->account->where('status',1)->first();


        $parentGroup=$this->group->where('parent_id',0)->where('account_id',$activeAccount->id)->get();

        return view('finance::pages.accounts-show',compact('parentGroup'));

    }


    public function  createGroup($accountId,$name,$code){

        // object group
        $groupObj= new $this->group;
        $groupObj->account_id=$accountId;
        $groupObj->name=$name;
        $groupObj->code=$code;
        $save=$groupObj->save();
        if($save){
            return true;
        } else {
            return false;
        }
    }


    public function  createEntryType($accountId,$name,$label){

        // create object entryType
        $entryTypeObj=new $this->entriesType;
        $entryTypeObj->account_id=$accountId;
        $entryTypeObj->name=$name;
        $entryTypeObj->label=$label;
        $save=$entryTypeObj->save();
            if($save){
                return true;
            } else {
                return false;
            }
    }

    public function createTags($accountId,$name,$color,$background){

        // create object tags
        $tagsObj=new $this->tags;
        $tagsObj->account_id=$accountId;
        $tagsObj->title=$name;
        $tagsObj->color=$color;
        $tagsObj->background=$background;
        $save=$tagsObj->save();
        if($save){
            return true;
        } else {
            return false;
        }

    }


    // dashbaord here
    public function dashboard()
    {

        $institution_id=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        $finincaialAccountProfile=$this->account->where('institution_id',$institution_id)->where('campus_id',$campus_id)->first();
        $groupListArray=DB::table('finance_group')->where('account_id',$finincaialAccountProfile->id)->pluck('id')->toArray();

        /* Cash and bank sumary */
        $ledgers =  DB::table('finance_ledger')->whereIn('group_id',$groupListArray)->where('type',1)->get();
        $ledgersCB = array();
        foreach ($ledgers as $ledger) {
            $ledgersCB[] = array(
                // 'name' => ($this->functionscore->toCodeWithName($ledger['code'], $ledger['name'])),
                'name' => $ledger->name,
                'code' => $ledger->code,
                'balance' => $this->ledger->closingBalance($ledger->id),
            );
        }
        $this->data['ledgers'] = $ledgersCB;

        $assetId=$this->group->getGroupId('01');
        /* Account summary */
        $assets = new AccountList();
        $assets->Group = &$this->Group;
        $assets->Ledger = &$this->Ledger;
        $assets->only_opening = false;
        $assets->start_date = null;
        $assets->end_date = null;
        $assets->affects_gross = -1;
        $assets->start($assetId);


        $liabilitiesId=$this->group->getGroupId('02');
        $liabilities = new AccountList();
        $liabilities->Group = &$this->Group;
        $liabilities->Ledger = &$this->Ledger;
        $liabilities->only_opening = false;
        $liabilities->start_date = null;
        $liabilities->end_date = null;
        $liabilities->affects_gross = -1;
        $liabilities->start($liabilitiesId);

        $incomeId=$this->group->getGroupId('03');
        $income = new AccountList();
        $income->Group = &$this->Group;
        $income->Ledger = &$this->Ledger;
        $income->only_opening = false;
        $income->start_date = null;
        $income->end_date = null;
        $income->affects_gross = -1;
        $income->start($incomeId);

        $expenseId=$this->group->getGroupId('04');
        $expense = new AccountList();
        $expense->Group = &$this->Group;
        $expense->Ledger = &$this->Ledger;
        $expense->only_opening = false;
        $expense->start_date = null;
        $expense->end_date = null;
        $expense->affects_gross = -1;
        $expense->start($expenseId);



         $accsummary = array(
            'assets_total_dc' => $assets->cl_total_dc,
            'assets_total' => $assets->cl_total,
            'liabilities_total_dc' => $liabilities->cl_total_dc,
            'liabilities_total' => $liabilities->cl_total,
            'income_total_dc' => $income->cl_total_dc,
            'income_total' => $income->cl_total,
            'expense_total_dc' => $expense->cl_total_dc,
            'expense_total' => $expense->cl_total,
        );
        $this->data['accsummary'] = $accsummary;
        $this->data['account'] = $finincaialAccountProfile;

        // today income
         $this->data['today_total_income']= $this->totalOfToday(03,$finincaialAccountProfile->id);
         $this->data['today_total_expense']= $this->totalOfToday(04,$finincaialAccountProfile->id);
        $this->data['monthly_total_income']= $this->totalOfMonth(03,$finincaialAccountProfile->id);
        $this->data['monthly_total_expense']= $this->totalOfMonth(04,$finincaialAccountProfile->id);




        return view('finance::pages.accounts.dashboard',$this->data);
    }



    public function  totalOfToday($mainGroupCode, $account_id){
          $groupProfile=$this->group
            ->where('account_id',$account_id)
            ->where('code',$mainGroupCode)->first();
           $groupArray=$this->group
             ->where('account_id',$account_id)
//             ->where('code',$mainGroupCode)
             ->where('parent_id',$groupProfile->id)
             ->pluck('id')->toArray();

          $entriesArray = DB::table('finance_entries_item')
            ->select(DB::raw('SUM(amount) as total, date'))
            ->leftJoin('finance_ledger', 'finance_entries_item.ledger_id','=','finance_ledger.id')
            ->leftJoin('finance_entries', 'finance_entries_item.entries_id','=','finance_entries.id')
            ->whereIn('finance_ledger.group_id', $groupArray)
            ->groupBy('finance_entries.date')
            ->where('finance_entries.date',date('Y-m-d'))
            ->first();
//         return print_r($entriesArray);
        if (!empty($entriesArray)) {
            return $entriesArray->total;
        } else {
            return false;
        }
    }


    public function  totalOfMonth($mainGroupCode, $account_id){
        $groupProfile=$this->group
            ->where('account_id',$account_id)
            ->where('code',$mainGroupCode)->first();
        $groupArray=$this->group
            ->where('account_id',$account_id)
//             ->where('code',$mainGroupCode)
            ->where('parent_id',$groupProfile->id)
            ->pluck('id')->toArray();

         $entriesArray = DB::table('finance_entries_item')
            ->select(DB::raw('SUM(amount) as total, date'))
            ->leftJoin('finance_ledger', 'finance_entries_item.ledger_id','=','finance_ledger.id')
            ->leftJoin('finance_entries', 'finance_entries_item.entries_id','=','finance_entries.id')
            ->whereIn('finance_ledger.group_id', $groupArray)
            ->groupBy('finance_entries.date')
            ->whereMonth('finance_entries_item.created_at', 9)
            ->get();
        if ($entriesArray->count() > 0) {
            $sum=0;
            foreach ($entriesArray as $key=>$value){
                $sum+=$value->total;
            }
            return $sum;
        } else {
            return false;
        }

    }


}

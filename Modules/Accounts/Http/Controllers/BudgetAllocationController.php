<?php

namespace Modules\Accounts\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Accounts\Entities\Budget;
use Modules\Accounts\Entities\BudgetDetails;
use Modules\Accounts\Entities\ChartOfAccount;

class BudgetAllocationController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index()
    {
        $budgets = Budget::with('account')->module()->get();

        return view('accounts::budget-allocation.index', compact('budgets'));
    }


    public function create()
    {
        $ledgerParentIds = ChartOfAccount::module()->where('account_type', 'ledger')->pluck('parent_id')->toArray();
        $ledgerParents = ChartOfAccount::module()->whereIn('id', array_unique($ledgerParentIds))->get();
        $ledgers = ChartOfAccount::module()->where('account_type', 'ledger')->get();

        return view('accounts::budget-allocation.modal.add-budget', compact('ledgers', 'ledgerParents'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'ledgerId' => 'required',
            'amount' => 'required',
        ]);

        $account = ChartOfAccount::findOrFail($request->ledgerId);
        $previousBudget = Budget::module()->where([
            'fiscal_year' => $request->year,
            'account_id' => $account->id,
        ])->first();

        if ($previousBudget){
            Session::flash('errorMessage', 'Budget already created for this fiscal year.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $budgetId = Budget::insertGetId([
                'fiscal_year' => $request->year,
                'account_id' => $account->id,
                'account_type' => $account->account_type,
                'increase_by' => $account->increase_by,
                'amount' => $request->amount,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            foreach ($request->months as $month => $amount){
                BudgetDetails::create([
                    'fiscal_year' => $request->year,
                    'budget_summary_id' => $budgetId,
                    'month_no' => $month,
                    'account_id' => $account->id,
                    'account_type' => $account->account_type,
                    'increase_by' => $account->increase_by,
                    'amount' => $amount
                ]);
            }

            DB::commit();
            Session::flash('message', 'New budget added successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('errorMessage', 'Error creating new budget.');
            return redirect()->back();
        }
    }


    public function show($id)
    {
        return view('accounts::show');
    }


    public function edit($id)
    {
        $budget = Budget::findOrFail($id);
        $budgetMonths = $budget->details->keyBy('month_no');
        $ledgerParentIds = ChartOfAccount::module()->where('account_type', 'ledger')->pluck('parent_id')->toArray();
        $ledgerParents = ChartOfAccount::module()->whereIn('id', array_unique($ledgerParentIds))->get();
        $ledgers = ChartOfAccount::module()->where('account_type', 'ledger')->get();

        return view('accounts::budget-allocation.modal.edit-budget', compact('budget', 'budgetMonths', 'ledgers', 'ledgerParents'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'year' => 'required',
            'ledgerId' => 'required',
            'amount' => 'required',
        ]);

        $budget = Budget::findOrFail($id);
        $account = ChartOfAccount::findOrFail($request->ledgerId);

        if ($budget->account_id != $account->id || $budget->fiscal_year != $request->year){
            $previousBudget = Budget::module()->where([
                'fiscal_year' => $request->year,
                'account_id' => $account->id,
            ])->first();
            if ($previousBudget){
                Session::flash('errorMessage', 'Budget already created for this fiscal year.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $budget->update([
                'fiscal_year' => $request->year,
                'account_id' => $account->id,
                'account_type' => $account->account_type,
                'increase_by' => $account->increase_by,
                'amount' => $request->amount
            ]);

            foreach ($request->months as $month => $amount){
                $budgetDetails = BudgetDetails::where([
                    'budget_summary_id' => $budget->id,
                    'month_no' => $month
                ])->first();
                if ($budgetDetails){
                    $budgetDetails->update([
                        'fiscal_year' => $request->year,
                        'budget_summary_id' => $budget->id,
                        'account_id' => $account->id,
                        'account_type' => $account->account_type,
                        'increase_by' => $account->increase_by,
                        'amount' => $amount
                    ]);
                }
            }

            DB::commit();
            Session::flash('message', 'Budget updated successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('errorMessage', 'Error updating budget.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $budget = Budget::findOrFail($id);

        foreach ($budget->details as $budgetDetails){
            $budgetDetails->delete();
        }

        $budget->delete();

        Session::flash('message', 'Budget deleted successfully.');
        return redirect()->back();
    }
}

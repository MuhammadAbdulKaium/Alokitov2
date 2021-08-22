<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Helpers\Accounting\LagerTree;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Finance\Entities\FinancialAccount;
use Modules\Finance\Entities\Group;
use Modules\Finance\Entities\Ledger;
Use Modules\Finance\Entities\EntriesType;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\Session;
use Modules\Finance\Entities\EntriesItem;
use Carbon\Carbon;
use App\Http\Controllers\Helpers\Accounting\FunctionCore;
use App\Http\Controllers\Helpers\Accounting\AccountList;
use Illuminate\Support\Facades\DB;
use App;
use Excel;
class ReportController extends Controller
{

    private  $account;
    private  $group;
    private  $ledger;
    private  $entriesType;
    private  $academicHelper;
    private  $entriesItem;
    var $data=array();

    public function __construct(EntriesItem $entriesItem,AcademicHelper $academicHelper,FinancialAccount $account, Group $group, Ledger $ledger, EntriesType $entriesType)
    {
        $this->account=$account;
        $this->group=$group;
        $this->ledger=$ledger;
        $this->entriesType=$entriesType;
        $this->academicHelper=$academicHelper;
        $this->entriesItem=$entriesItem;

    }

    public function balancesheet(Request $request,$download = NULL, $format = NULL){

        // function core object
        $functionCore= new FunctionCore;

        $only_opening = false;
        $startdate = null;
        $enddate = null;


        if ( $request->isMethod('post')) {
            $this->data['options'] = true;
            if (!empty($this->input->post('opening'))) {
                $only_opening = true;
                /* Sub-title*/
                $this->data['subtitle'] = sprintf(lang('opening_balance_sheet_as_on'), $this->functionscore->dateFromSql($this->mAccountSettings->fy_start));
            } else {
                if ($this->input->post('startdate')) {
                    $startdate = $this->functionscore->dateToSQL($this->input->post('startdate'));
                }
                if ($this->input->post('enddate')) {
                    $enddate = $this->functionscore->dateToSQL($this->input->post('enddate'));
                }

                if ( $this->input->post('startdate') && $this->input->post('enddate')) {
                    $this->data['subtitle'] = sprintf(lang('balance_sheet_from_to'), $this->functionscore->dateFromSql($this->functionscore->dateToSQL($this->input->post('startdate'))), $this->functionscore->dateFromSql($this->functionscore->dateToSQL($this->input->post('enddate'))));
                }
                if ( $this->input->post('startdate')) {
                    $this->data['subtitle'] = sprintf(lang('balance_sheet_from'), $this->functionscore->dateFromSql($this->functionscore->dateToSQL($this->input->post('startdate'))));
                }
                if ($this->input->post('enddate')) {
                    $this->data['subtitle'] = sprintf(lang('balance_sheet_from_to'), $this->functionscore->dateFromSql($this->mAccountSettings->fy_start), $this->functionscore->dateFromSql($this->functionscore->dateToSQL($this->input->post('enddate'))));
                }
            }
        }else{
//            $this->data['options'] = false;
            /* Sub-title*/
//            $this->data['subtitle'] = sprintf(lang('closing_balance_sheet_as_on'), $this->functionscore->dateFromSql($this->mAccountSettings->fy_end));
        }

        /**********************************************************************/
        /*********************** BALANCESHEET CALCULATIONS ********************/
        /**********************************************************************/
        /* Liabilities */
        // get liablititye gorup id
        $liabilitiesId=$this->group->getGroupId('02');

        $liabilities = new AccountList();
        $liabilities->Group = &$this->Group;
        $liabilities->Ledger = &$this->Ledger;
        $liabilities->only_opening = $only_opening;
        $liabilities->start_date = $startdate;
        $liabilities->end_date = $enddate;
        $liabilities->affects_gross = -1;
        $liabilities->start($liabilitiesId);

        $bsheet['liabilities'] = $liabilities;

        $bsheet['liabilities_total'] = 0;
        if ($liabilities->cl_total_dc == 'C') {
            $bsheet['liabilities_total'] = $liabilities->cl_total;
        } else {
            $bsheet['liabilities_total'] =$functionCore->calculate($liabilities->cl_total, 0, 'n');
        }

        /* Assets */
        // get asset gorup id
        $assetId=$this->group->getGroupId('01');
        $assets = new AccountList();
        $assets->Group = &$this->Group;
        $assets->Ledger = &$this->Ledger;
        $assets->only_opening = $only_opening;
        $assets->start_date = $startdate;
        $assets->end_date = $enddate;
        $assets->affects_gross = -1;
        $assets->start($assetId);

        $bsheet['assets'] = $assets;
        $bsheet['assets_total'] = 0;
        if ($assets->cl_total_dc == 'D') {
            $bsheet['assets_total'] = $assets->cl_total;
        } else {
            $bsheet['assets_total'] = $functionCore->calculate($assets->cl_total, 0, 'n');
        }

        /* Profit and loss calculations */
        // Income  gorup id
        $incomeId=$this->group->getGroupId('03');

        $income = new AccountList();
        $income->Group = &$this->Group;
        $income->Ledger = &$this->Ledger;
        $income->only_opening = $only_opening;
        $income->start_date = $startdate;
        $income->end_date = $enddate;
        $income->affects_gross = -1;
        $income->start($incomeId);

        // Expense  gorup id
        $expenseId=$this->group->getGroupId('04');

        $expense = new AccountList();
        $expense->Group = &$this->Group;
        $expense->Ledger = &$this->Ledger;
        $expense->only_opening = $only_opening;
        $expense->start_date = $startdate;
        $expense->end_date = $enddate;
        $expense->affects_gross = -1;
        $expense->start($expenseId);

        if ($income->cl_total_dc == 'C') {
            $income_total = $income->cl_total;
        } else {
            $income_total = $functionCore->calculate($income->cl_total, 0, 'n');
        }
        if ($expense->cl_total_dc == 'D') {
            $expense_total = $expense->cl_total;
        } else {
            $expense_total = $functionCore->calculate($expense->cl_total, 0, 'n');
        }

        $bsheet['pandl'] = $functionCore->calculate($income_total, $expense_total, '-');

        /* Difference in opening balance */
        $bsheet['opdiff'] = $this->ledger->getOpeningDiff();
        if ($functionCore->calculate($bsheet['opdiff']['opdiff_balance'], 0, '==')) {
            $bsheet['is_opdiff'] = false;
        } else {
            $bsheet['is_opdiff'] = true;
        }

        /**** Final balancesheet total ****/
        $bsheet['final_liabilities_total'] = $bsheet['liabilities_total'];
        $bsheet['final_assets_total'] = $bsheet['assets_total'];

        /* If net profit add to liabilities, if net loss add to assets */
        if ($functionCore->calculate($bsheet['pandl'], 0, '>=')) {
            $bsheet['final_liabilities_total'] = $functionCore->calculate(
                $bsheet['final_liabilities_total'],
                $bsheet['pandl'], '+');
        } else {
            $positive_pandl = $functionCore->calculate($bsheet['pandl'], 0, 'n');
            $bsheet['final_assets_total'] = $functionCore->calculate(
                $bsheet['final_assets_total'],
                $positive_pandl, '+');
        }

        /**
         * If difference in opening balance is Dr then subtract from
         * assets else subtract from liabilities
         */
        if ($bsheet['is_opdiff']) {
            if ($bsheet['opdiff']['opdiff_balance_dc'] == 'D') {
                $bsheet['final_assets_total'] = $functionCore->calculate(
                    $bsheet['final_assets_total'],
                    $bsheet['opdiff']['opdiff_balance'], '+');
            } else {
                $bsheet['final_liabilities_total'] = $functionCore->calculate(
                    $bsheet['final_liabilities_total'],
                    $bsheet['opdiff']['opdiff_balance'], '+');
            }
        }
        $this->data['bsheet'] = $bsheet;

        if (!$download) {
            // render page
            return view('finance::pages.report.balancesheet',$this->data);
        }

        if ($download === 'download') {

            if ($format === 'pdf') {
                view()->share($this->data);
                //generate PDf
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('finance::pages.report.pdf.balancesheet')->setPaper('a4', 'portrait');
                return $pdf->stream();
            }
            if ($format === 'excel') {

                Excel::loadView('finance::pages.report.excel.balancesheet', $this->data)
                    ->setTitle('FileName')
                    ->sheet('SheetName')
                    ->export('xls');


                return view('finance::pages.report.pdf.balancesheet', $this->data);
            }
        }


    }


    /// profit and loss here
    public function profitloss(Request $request,$download = NULL, $format = NULL){


        // function core object
        $functionCore= new FunctionCore;

        $only_opening = false;
        $startdate = null;
        $enddate = null;


        if ($request->isMethod('post')) {
            $this->data['options'] = true;
            if (!empty($this->input->post('opening'))) {
                $only_opening = true;
                /* Sub-title*/
                $this->data['subtitle'] = sprintf(lang('opening_profit_loss_as_on'), $this->functionscore->dateFromSql($this->mAccountSettings->fy_start));
            } else {
                if ($this->input->post('startdate')) {
                    $startdate =$functionCore->dateToSQL($this->input->post('startdate'));
                }
                if ($this->input->post('enddate')) {
                    $enddate = $functionCore->dateToSQL($this->input->post('enddate'));
                }
                if ( $this->input->post('startdate') && $this->input->post('enddate')) {
                    $this->data['subtitle'] = sprintf(lang('profit_loss_from_to'),  $functionCore->dateFromSql($this->functionscore->dateToSQL($this->input->post('startdate'))), $this->functionscore->dateFromSql($this->functionscore->dateToSQL($this->input->post('enddate'))));
                }
                if ( $this->input->post('startdate')) {
                    $this->data['subtitle'] = sprintf(lang('profit_loss_from'), $functionCore->dateFromSql($this->functionscore->dateToSQL($this->input->post('startdate'))));

                }
                if ($this->input->post('enddate')) {
                    $this->data['subtitle'] = sprintf(lang('profit_loss_from_to'), $functionCore->dateFromSql($this->mAccountSettings->fy_start), $this->functionscore->dateFromSql($this->functionscore->dateToSQL($this->input->post('enddate'))));
                }
            }
        }else{
//            $this->data['options'] = false;
//            $this->data['subtitle'] = sprintf(lang('closing_profit_loss_as_on'), $functionCore->dateFromSql($this->mAccountSettings->fy_end));
        }


        /**********************************************************************/
        /*********************** GROSS CALCULATIONS ***************************/
        /**********************************************************************/
        /* Gross P/L : Expenses */
        $expenseId=$this->group->getGroupId('04');

        $gross_expenses = new AccountList();
        $gross_expenses->Group = &$this->Group;
        $gross_expenses->Ledger = &$this->Ledger;
        $gross_expenses->only_opening = $only_opening;
        $gross_expenses->start_date = $startdate;
        $gross_expenses->end_date = $enddate;
        $gross_expenses->affects_gross = 1;
        $gross_expenses->start($expenseId);

        $pandl['gross_expenses'] = $gross_expenses;

        $pandl['gross_expense_total'] = 0;
        if ($gross_expenses->cl_total_dc == 'D') {
            $pandl['gross_expense_total'] = $gross_expenses->cl_total;
        } else {
            $pandl['gross_expense_total'] =$functionCore->calculate($gross_expenses->cl_total, 0, 'n');
        }

        /* Gross P/L : Incomes */
        $incomeId=$this->group->getGroupId('03');
        $gross_incomes = new AccountList();
        $gross_incomes->Group = &$this->Group;
        $gross_incomes->Ledger = &$this->Ledger;
        $gross_incomes->only_opening = $only_opening;
        $gross_incomes->start_date = $startdate;
        $gross_incomes->end_date = $enddate;
        $gross_incomes->affects_gross = 1;
        $gross_incomes->start($incomeId);

        $pandl['gross_incomes'] = $gross_incomes;

        $pandl['gross_income_total'] = 0;
        if ($gross_incomes->cl_total_dc == 'C') {
            $pandl['gross_income_total'] = $gross_incomes->cl_total;
        } else {
            $pandl['gross_income_total'] = $functionCore->calculate($gross_incomes->cl_total, 0, 'n');
        }

        /* Calculating Gross P/L */
        $pandl['gross_pl'] = $functionCore->calculate($pandl['gross_income_total'], $pandl['gross_expense_total'], '-');

        /**********************************************************************/
        /************************* NET CALCULATIONS ***************************/
        /**********************************************************************/

        /* Net P/L : Expenses */
        $expenseId=$this->group->getGroupId('04');
        $net_expenses = new AccountList();
        $net_expenses->Group = &$this->Group;
        $net_expenses->Ledger = &$this->Ledger;
        $net_expenses->only_opening = $only_opening;
        $net_expenses->start_date = $startdate;
        $net_expenses->end_date = $enddate;
        $net_expenses->affects_gross = 0;
        $net_expenses->start($expenseId);

        $pandl['net_expenses'] = $net_expenses;

        $pandl['net_expense_total'] = 0;
        if ($net_expenses->cl_total_dc == 'D') {
            $pandl['net_expense_total'] = $net_expenses->cl_total;
        } else {
            $pandl['net_expense_total'] =$functionCore->calculate($net_expenses->cl_total, 0, 'n');
        }

        /* Net P/L : Incomes */
        $incomeId=$this->group->getGroupId('03');
        $net_incomes = new AccountList();
        $net_incomes->Group = &$this->Group;
        $net_incomes->Ledger = &$this->Ledger;
        $net_incomes->only_opening = $only_opening;
        $net_incomes->start_date = $startdate;
        $net_incomes->end_date = $enddate;
        $net_incomes->affects_gross = 0;
        $net_incomes->start($incomeId);

        $pandl['net_incomes'] = $net_incomes;

        $pandl['net_income_total'] = 0;
        if ($net_incomes->cl_total_dc == 'C') {
            $pandl['net_income_total'] = $net_incomes->cl_total;
        } else {
            $pandl['net_income_total'] =$functionCore->calculate($net_incomes->cl_total, 0, 'n');
        }

        /* Calculating Net P/L */
        $pandl['net_pl'] = $functionCore->calculate($pandl['net_income_total'], $pandl['net_expense_total'], '-');
        $pandl['net_pl'] = $functionCore->calculate($pandl['net_pl'], $pandl['gross_pl'], '+');

        $this->data['pandl'] = $pandl;

        if (!$download) {
            // render page
            return view('finance::pages.report.profitloss',$this->data);
        }

        if ($download === 'download') {
            if ($format === 'pdf') {
                view()->share($this->data);
                //generate PDf
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('finance::pages.report.pdf.profitloss')->setPaper('a4', 'portrait');
                return $pdf->stream();


            }
            if ($format === 'excel') {

                Excel::loadView('finance::pages.report.excel.profitloss', $this->data)
                    ->setTitle('profitloss')
                    ->sheet('profitloss')
                    ->export('xls');
            }
        }

    }




    // trail balance here
    public function trialbalance(Request $request,$download = NULL, $format = NULL)
    {

        $accountlist = new AccountList();
        $accountlist->Group = &$this->Group;
        $accountlist->Ledger = &$this->Ledger;
        $accountlist->only_opening = false;
        $accountlist->start_date = null;
        $accountlist->end_date = null;
        $accountlist->affects_gross = -1;

        $accountlist->start(0);
        $this->data['accountlist'] = $accountlist;
        if (!$download) {
            // render page
            return view('finance::pages.report.trailbalance',$this->data);
        }

        if ($download === 'download') {
            if ($format === 'pdf') {
                view()->share($this->data);
                //generate PDf
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('finance::pages.report.pdf.trailbalance')->setPaper('a4', 'landscape');
                return $pdf->stream();


            }
            if ($format === 'excel') {

               return  Excel::loadView('finance::pages.report.excel.trailbalance', $this->data)
                    ->setTitle('trailbalance')
                    ->sheet('trailbalance')
                    ->export('xls');
            }
        }

    }



    // get ledger statement here
    public function ledgerStatement(Request $request, $show=true, $ledgerId = NULL) {


//         $query = DB::table('finance_entries_item')
//             ->select(DB::raw(' SUM(finance_entries_item.amount) as total'))
//            ->where('finance_entries_item.ledger_id',11)
//            ->where('finance_entries_item.dc', 'D')
//            ->where('finance_entries.date','<=', '2019-09-23')
//            ->leftJoin('finance_entries', 'finance_entries_item.entries_id', '=', 'finance_entries.id')
//            ->first();
//         print_r($query);
//         die();

        //        return $request->all();
        // function core object
        $functionCore= new FunctionCore;

        /* Create list of ledgers to pass to view */
        $ledgers = new LagerTree();
        $ledgers->Group = &$this->Group;
        $ledgers->Ledger = &$this->Ledger;
        $ledgers->current_id = -1;
        $ledgers->restriction_bankcash = 1;
        $ledgers->build(0);
        $ledgers->toList($ledgers, -1);

        $this->data['ledgers'] = $ledgers->ledgerList;
        $this->data['showEntries'] = false;
        $this->data['options'] = false;
        $this->data['input_ledger'] = $ledgerId;

        if ($request->isMethod('post')) {
            if (empty($request->ledger_id)) {
                $this->session->set_flashdata('error', lang('invalid_ledger'));
                redirect('reports/ledgerstatement');
            }
            $ledgerId = $request->ledger_id;
        }

        if ($ledgerId) {
            /* Check if ledger exists */
            $ledger=DB::table('finance_ledger')->where('id',$ledgerId)->first();

            if (!$ledger) {
                $this->session->set_flashdata('error', lang('ledger_not_found'));
                redirect('reports/ledgerstatement');
            }


            $this->data['ledger_data'] = (array)$ledger;

            /* Set the approprite search conditions */
            $conditions = array();
            $conditions['ledger_id'] = $ledgerId;

            /* Set the approprite search conditions if custom date is selected */
            $startdate = null;
            $enddate = null;
        }



        if ($ledgerId) {
            $this->data['options'] = true;
            if (!empty($request->startdate)) {
                $startdate = date('Y-m-d',strtotime($request->startdate));
                $cr_conditions['date'] ='>=,'.$startdate;

            }

            if (!empty($request->enddate)) {
                $enddate = date('Y-m-d',strtotime($request->enddate));
                $cr_conditions['date'] ='<=,'.$enddate;
            }


            /* Sub-title*/
            if (!empty($request->startdate) && !empty($request->enddate)) {
                $this->data['subtitle'] = 'This is Sub Tittle'.($ledger->name).$request->startdate.$request->enddate;
            } else if (!empty($request->startdate)) {
                $this->data['subtitle'] =$ledger->name. $request->startdate.'Setting End Date 31 Dec 2019';
            } else if (!empty($request->enddate)) {
                $this->data['subtitle'] =$ledger->name.'Setting Start Date'.$request->enddate;
            }else{
                $this->data['subtitle'] =$ledger->name.'Setting Start Date and Setting End Date';

            }
            /* Opening and closing titles */
            if (is_null($startdate)) {
                $this->data['opening_title'] = 'Setting Start Date';
            } else {
                $this->data['opening_title'] = $request->startdate;
            }
            if (is_null($enddate)) {
                $this->data['closing_title'] = 'Setting End Date';
            } else {
                $this->data['closing_title'] = $request->enddate;
            }
            /* Calculating opening balance */
             $op = $this->ledger->openingBalance($ledgerId, $startdate);
             $this->data['op'] = $op;
//            var_dump($this->data['op']);
//            exit();

            /* Calculating closing balance */
            $cl = $this->ledger->closingBalance($ledgerId, $startdate, $enddate);
            $this->data['cl'] = $cl;

            /* Calculate current page opening balance */
              $current_op = $op;
//             $entriesList = DB::table('finance_entries')->where($conditions)->whereBetween('date', [$startdate, $enddate])
//                ->leftJoin('finance_entries_item', 'finance_entries.id', '=', 'finance_entries_item.entries_id')->get(['finance_entries.id as id','tag_id','entrytype_id','number','date','dr_total','cr_total','narration','entries_id','ledger_id','amount','dc','reconciliation_date']);
//
              $entriesList = DB::table('finance_entries')
                  ->whereBetween('date', [$startdate, $enddate])
                  ->where('ledger_id', $ledgerId)
                  ->orderBy('id','asc')
                ->leftJoin('finance_entries_item', 'finance_entries.id', '=', 'finance_entries_item.entries_id')->get(['finance_entries.id as id','tag_id','entrytype_id','number','date','dr_total','cr_total','narration','entries_id','ledger_id','amount','dc','reconciliation_date']);

            $this->data['entries']=$entriesList;
            /* Set the current page opening balance */
            $this->data['current_op'] = $current_op;

            /* Pass varaibles to view which are used in Helpers */
            $this->data['allTags'] = DB::table('finance_tags')->where('account_id',$this->account->getActiveAccount())->get();
            $this->data['showEntries'] = true;
            $this->data['input_ledger'] = $ledgerId;
            if(!empty($request->startdate) && !empty($request->enddate)) {
                $this->data['input_start_date'] = date('Y-m-d', strtotime($request->startdate));
                $this->data['input_end_date'] = date('Y-m-d',strtotime($request->enddate));
            } else {
                $this->data['input_start_date'] = '';
                $this->data['input_end_date'] = '';
            }

        }

        if($show){
            return view('finance::pages.report.ledgerstatement',$this->data);
        } else {
            return array(
                'ledgers' 	=> $this->data['ledgers'],
                'showEntries' => $this->data['showEntries'],
                'ledger_data' => $this->data['ledger_data'],
                'subtitle' 	=> $this->data['subtitle'],
                'opening_title' => $this->data['opening_title'],
                'closing_title' => $this->data['closing_title'],
                'op' 			=> $this->data['op'],
                'cl' 			=> $this->data['cl'],
                'entries'		=> $this->data['entries'],
                'current_op' 	=> $this->data['current_op'],
                'allTags' 	=> $this->data['allTags'],
            );
        }

    }



    // get ledger statement here
    public function ledgerEntries(Request $request, $show=true, $ledgerId = NULL) {
        // function core object
        $functionCore= new FunctionCore;


        /* Create list of ledgers to pass to view */
        $ledgers = new LagerTree();
        $ledgers->Group = &$this->Group;
        $ledgers->Ledger = &$this->Ledger;
        $ledgers->current_id = -1;
        $ledgers->restriction_bankcash = 1;
        $ledgers->build(0);
        $ledgers->toList($ledgers, -1);

        $this->data['ledgers'] = $ledgers->ledgerList;
        $this->data['showEntries'] = false;
        $this->data['options'] = false;
        $this->data['input_ledger'] = $ledgerId;

        if ($request->isMethod('post')) {
            if (empty($request->ledger_id)) {
                $this->session->set_flashdata('error', lang('invalid_ledger'));
                redirect('reports/ledgerentries');
            }
            $ledgerId = $request->ledger_id;
        }

        if ($ledgerId) {
            /* Check if ledger exists */
            $ledger=DB::table('finance_ledger')->where('id',$ledgerId)->first();

            if (!$ledger) {
                $this->session->set_flashdata('error', lang('ledger_not_found'));
                redirect('reports/ledgerentries');
            }


            $this->data['ledger_data'] = (array)$ledger;

            /* Set the approprite search conditions */
            $conditions = array();
            $conditions['ledger_id'] = $ledgerId;

            /* Set the approprite search conditions if custom date is selected */
            $startdate = null;
            $enddate = null;
        }



        if ($ledgerId) {
            $this->data['options'] = true;
            if (!empty($request->startdate)) {
                $startdate = date('Y-m-d',strtotime($request->startdate));
                $cr_conditions['date'] ='>=,'.$startdate;
            }

            if (!empty($request->enddate)) {
                $enddate = date('Y-m-d',strtotime($request->enddate));
                $cr_conditions['date'] ='<=,'.$enddate;
            }


            /* Sub-title*/
            if (!empty($request->startdate) && !empty($request->enddate)) {
                $this->data['subtitle'] = 'This is Sub Tittle'.($ledger->name).$request->startdate.$request->enddate;
            } else if (!empty($request->startdate)) {
                $this->data['subtitle'] =$ledger->name. $request->startdate.'Setting End Date 31 Dec 2019';
            } else if (!empty($request->enddate)) {
                $this->data['subtitle'] =$ledger->name.'Setting Start Date'.$request->enddate;
            }else{
                $this->data['subtitle'] =$ledger->name.'Setting Start Date and Setting End Date';

            }
            /* Opening and closing titles */
            if (is_null($startdate)) {
                $this->data['opening_title'] = 'Setting Start Date';
            } else {
                $this->data['opening_title'] = $request->startdate;
            }
            if (is_null($enddate)) {
                $this->data['closing_title'] = 'Setting End Date';
            } else {
                $this->data['closing_title'] = $request->enddate;
            }
            /* Calculating opening balance */
            $op = $this->ledger->openingBalance($ledgerId, $startdate);
            $this->data['op'] = $op;

            /* Calculating closing balance */
            $cl = $this->ledger->closingBalance($ledgerId, $startdate, $enddate);
            $this->data['cl'] = $cl;

            /* Calculate current page opening balance */
            $current_op = $op;
            $entriesList = DB::table('finance_entries')
                ->whereBetween('date', [$startdate, $enddate])
                ->where('ledger_id', $ledgerId)
                ->orderBy('id','asc')
                ->leftJoin('finance_entries_item', 'finance_entries.id', '=', 'finance_entries_item.entries_id')->get(['finance_entries.id as id','tag_id','entrytype_id','number','date','dr_total','cr_total','narration','entries_id','ledger_id','amount','dc','reconciliation_date']);

            $this->data['entries']=$entriesList;
            /* Set the current page opening balance */
            $this->data['current_op'] = $current_op;

            /* Pass varaibles to view which are used in Helpers */
//            $this->data['allTags'] = $this->DB1->get('tags')->result_array();
            $this->data['showEntries'] = true;
            $this->data['input_ledger'] = $ledgerId;
        }

        if(!empty($request->startdate) && !empty($request->enddate)) {
            $this->data['input_start_date'] = date('Y-m-d', strtotime($request->startdate));
            $this->data['input_end_date'] = date('Y-m-d',strtotime($request->enddate));
        } else {
            $this->data['input_start_date'] = '';
            $this->data['input_end_date'] = '';
        }

//        var_dump($this->data['entries']);
//        exit();
        if($show){
            return view('finance::pages.report.ledgerentries',$this->data);

        } else {
            return array(
                'ledgers' 	=> $this->data['ledgers'],
                'showEntries' => $this->data['showEntries'],
                'ledger_data' => $this->data['ledger_data'],
                'subtitle' 	=> $this->data['subtitle'],
                'opening_title' => $this->data['opening_title'],
                'closing_title' => $this->data['closing_title'],
                'op' 			=> $this->data['op'],
                'cl' 			=> $this->data['cl'],
                'entries'		=> $this->data['entries'],
                'current_op' 	=> $this->data['current_op'],
            );
        }


    }




    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function export_ledgerstatement(Request $request)
    {
//        return $request->all();
//      $format=$request->
        $this->data['entriesStatement']=true;
        $this->data = $this->ledgerstatement($request,false, $request->id);
//        var_dump( $this->data);
        if($request->type=='pdf') {
            view()->share($this->data);
            //generate PDf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('finance::pages.report.pdf.ledgerstatement')->setPaper('a4', 'landscape');
            return $pdf->stream();

        }
        elseif($request->type=='excel') {
            return Excel::loadView('finance::pages.report.excel.ledgerstatement', $this->data)
                ->setTitle('trailbalance')
                ->setFilename('ledgerStatement')
                ->sheet('trailbalance')
                ->export('xls');

        }
    }


    public function export_ledgerEntries(Request $request)
    {
//        return $request->all();
//      $format=$request->
        $this->data = $this->ledgerEntries($request,false, $request->id);
        $this->data['entriesStatement']=false;
        if($request->type=='pdf') {
            view()->share($this->data);
            //generate PDf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('finance::pages.report.pdf.ledgerentries')->setPaper('a4', 'landscape');
            return $pdf->stream();

        }
        elseif($request->type=='excel') {
            return Excel::loadView('finance::pages.report.excel.ledgerentries', $this->data)
                ->setTitle('trailbalance')
                ->setFilename('ledgerStatement')
                ->sheet('trailbalance')
                ->export('xls');

        }
    }



}

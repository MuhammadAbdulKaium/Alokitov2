<?php

namespace Modules\Finance\Entities;

use App\Http\Controllers\Helpers\Accounting\FunctionCore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
class FinancialAccount extends Model
{
    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'finance_account';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'account_name',
        'account_id',
        'f_year_start',
        'f_year_end',
        'company_name',
        'address',
        'email',
        'is_active',
    ];

    // get current finincila account here

    public function getActiveAccount()
    {
        // institue id and campus id
        $instituteId= session()->get('institute');
        $campus= session()->get('campus');
        $finincaialAccountProfile=FinancialAccount::where('institution_id',$instituteId)->where('campus_id',$campus)->first();
        return $finincaialAccountProfile->id;
    }





// Print all chart of account

    public function print_account_chart($account, $c = 0, $THIS)
    {
        // fucntion core object
        $functionCore= new \App\Http\Controllers\Helpers\Accounting\FunctionCore;

        $counter = $c;
        /* Print groups */
        if ($account->id != 0) {
            if ($account->id <= 4) {
                echo '<tr class="tr-group tr-root-group">';
            } else {
                echo '<tr class="tr-group">';
            }
            echo '<td>';
            echo $this->print_space($counter);
            echo $account->code;
            echo '</td>';
            echo '<td class="td-group">';
            echo $this->print_space($counter);
            echo $account->name;
            echo '</td>';

            echo '<td> Group </td>';

            echo '<td style="text-align:center;">-</td>';
            echo '<td style="text-align:center;">-</td>';

            /* If group id less than 4 dont show edit and delete links */
            if ($account->id <= 4) {
                echo '<td class="td-actions"></td>';
            } else {
//                echo '<td class="td-actions">';
//                echo '<i class="glyphicon glyphicon-edit"></i>Edit', array('class' => 'no-hover font-normal', 'escape' => false);
//                echo "<span class='link-pad'></span>";
//                echo '<i class="glyphicon-trash"></i>Delete', array('class' => 'no-hover font-normal', 'escape' => false);

//                echo '</td>';
            }
            echo '</tr>';
        }

        /* Print child ledgers */
        if (count($account->children_ledgers) >= 1) {
            $counter++;
            foreach ($account->children_ledgers as $id => $data) {
                echo '<tr class="tr-ledger">';
                echo '<td class="td-ledger">';
                echo $this->print_space($counter);
                echo $data['code'];
                echo '</td>';
                echo '<td class="td-ledger">';
                echo $this->print_space($counter);
                //to change later
                echo '<a href="'.url('/finance/accounts/reports/ledgerstatement/ledgerid',$data['id']).'">'.$data['name'].'</a>';
                echo '</td>';
                echo '<td>Ledger</td>';

                echo '<td style="text-align:right">';
                echo $functionCore->toCurrency($data['op_total_dc'], $data['op_total']);
                echo '</td>';

                echo '<td style="text-align:right">';
                echo $functionCore->toCurrency($data['cl_total_dc'], $data['cl_total']);
                echo '</td>';

                echo '</tr>';
            }
            $counter--;
        }

        /* Print child groups recursively */
        foreach ($account->children_groups as $id => $data) {
            $counter++;
            $this->print_account_chart($data, $counter, $THIS);
            $counter--;
        }
    }

    public function print_space($count)
    {
        $html = '';
        for ($i = 1; $i <= $count; $i++) {
            $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        return $html;
    }

    // print balance sheet and profileloss account st short

    public function account_st_short($account, $c = 0, $THIS, $dc_type)
    {
        // funciton core object
        $functionCore=new FunctionCore;

        $counter = $c;
        if ($account->id > 4)
        {
            if ($dc_type == 'D' && $account->cl_total_dc == 'C' && $functionCore->calculate($account->cl_total, 0, '!=')) {
                echo '<tr class="tr-group dc-error">';
            } else if ($dc_type == 'C' && $account->cl_total_dc == 'D' && $functionCore->calculate($account->cl_total, 0, '!=')) {
                echo '<tr class="tr-group dc-error">';
            } else {
                echo '<tr class="tr-group">';
            }

            echo '<td class="td-group">';
            echo $this->print_space($counter);
            echo ($functionCore->toCodeWithName($account->code, $account->name));
            echo '</td>';

            echo '<td class="text-right">';
            echo $functionCore->toCurrency($account->cl_total_dc, $account->cl_total);
            echo $this->print_space($counter);
            echo '</td>';

            echo '</tr>';
        }
        foreach ($account->children_groups as $id => $data)
        {
            $counter++;
            $this->account_st_short($data, $counter, $THIS, $dc_type);
            $counter--;
        }
        if (count($account->children_ledgers) > 0)
        {
            $counter++;
            foreach ($account->children_ledgers as $id => $data)
            {
                if ($dc_type == 'D' && $data['cl_total_dc'] == 'C' && $functionCore->calculate($data['cl_total'], 0, '!=')) {
                    echo '<tr class="tr-ledger dc-error">';
                } else if ($dc_type == 'C' && $data['cl_total_dc'] == 'D' && $functionCore->calculate($data['cl_total'], 0, '!=')) {
                    echo '<tr class="tr-ledger dc-error">';
                } else {
                    echo '<tr class="tr-ledger">';
                }

                echo '<td class="td-ledger">';
                echo $this->print_space($counter);

                echo $functionCore->toCodeWithName($data['code'], $data['name']);
                echo '</td>';

                echo '<td class="text-right">';
                echo $functionCore->toCurrency($data['cl_total_dc'], $data['cl_total']);
                echo $this->print_space($counter);
                echo '</td>';

                echo '</tr>';
            }
            $counter--;
        }
    }

    // print for trailblance account here

    function print_trail_balance_account_chart($account, $c = 0, $THIS)
    {
        // funciton core object
        $functionCore=new FunctionCore;
        $counter = $c;

        /* Print groups */
        if ($account->id != 0) {
            if ($account->id <= 4) {
                echo '<tr class="tr-group tr-root-group">';
            } else {
                echo '<tr class="tr-group">';
            }
            echo '<td class="td-group">';
            echo $this->print_space($counter);
            echo ($functionCore->toCodeWithName($account->code, $account->name));
            echo '</td>';

            echo '<td>Group</td>';

            echo '<td>';
            echo $functionCore->toCurrency($account->op_total_dc, $account->op_total);
            echo '</td>';

            echo '<td>' . $functionCore->toCurrency('D', $account->dr_total) . '</td>';

            echo '<td>' . $functionCore->toCurrency('C', $account->cr_total) . '</td>';

            if ($account->cl_total_dc == 'D') {
                echo '<td>' . $functionCore->toCurrency('D', $account->cl_total) . '</td>';
            } else {
                echo '<td>' . $functionCore->toCurrency('C', $account->cl_total) . '</td>';
            }

            echo '</tr>';
        }

        /* Print child ledgers */
        if (count($account->children_ledgers) > 0) {
            $counter++;
            foreach ($account->children_ledgers as $id => $data) {
                echo '<tr class="tr-ledger">';
                echo '<td class="td-ledger">';
                echo $this->print_space($counter);
                echo $functionCore->toCodeWithName($data['code'], $data['name']);
                echo '</td>';
                echo '<td>Ledger</td>';

                echo '<td>';
                echo $functionCore->toCurrency($data['op_total_dc'], $data['op_total']);
                echo '</td>';

                echo '<td>' .$functionCore->toCurrency('D', $data['dr_total']) . '</td>';

                echo '<td>' . $functionCore->toCurrency('C', $data['cr_total']) . '</td>';

                if ($data['cl_total_dc'] == 'D') {
                    echo '<td>' . $functionCore->toCurrency('D', $data['cl_total']) . '</td>';
                } else {
                    echo '<td>' . $functionCore->toCurrency('C', $data['cl_total']) . '</td>';
                }

                echo '</tr>';
            }
            $counter--;
        }

        /* Print child groups recursively */
        foreach ($account->children_groups as $id => $data) {
            $counter++;
            $this->print_trail_balance_account_chart($data, $counter, $THIS);
            $counter--;
        }
    }





}

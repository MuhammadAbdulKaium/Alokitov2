@php
    $financialAccountObj= new \Modules\Finance\Entities\FinancialAccount;
    $functionCore= new \App\Http\Controllers\Helpers\Accounting\FunctionCore;
@endphp
<table class="stripped" style="text-align: left">
    <thead>
    <tr>
        <th>Account Name</th>
        <th>Type</th>
        <th>O/P Balance (৳)</th>
        <th>Debit Total (৳)</th>
        <th>Credit Total (৳)</th>
        <th>C/L Balance (৳)</th>
    </tr>
    </thead>
    <?php
    $financialAccountObj->print_trail_balance_account_chart($accountlist, -1, $this);

    if ($functionCore->calculate($accountlist->dr_total, $accountlist->cr_total, '==')) {
        echo '<tr class="bold-text ok-text">';
    } else {
        echo '<tr class="bold-text error-text">';
    }
    echo '<td>Total</td>';
    echo '<td></td><td></td>';
    echo '<td>' . $functionCore->toCurrency('D', $accountlist->dr_total) . '</td>';
    echo '<td>' . $functionCore->toCurrency('C', $accountlist->cr_total) . '</td>';
    if ($functionCore->calculate($accountlist->dr_total, $accountlist->cr_total, '==')) {
        echo '<td><span class="glyphicon glyphicon-ok-sign"></span></td>';
    } else {
        echo '<td><span class="glyphicon glyphicon-remove-sign"></span></td>';
    }
    echo '<td></td>';
    echo '</tr>';
    ?>
</table>
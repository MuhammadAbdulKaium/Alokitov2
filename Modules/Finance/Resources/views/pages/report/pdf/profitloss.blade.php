<!DOCTYPE html>
<html lang="en">
<head>
    <title>Balance Sheet</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #CCC;
        }
        h2, h3 {
            text-align: center;
        }

    </style>
</head>
<body>

@php
    $gross_total = 0;
    $positive_gross_pl = 0;
    $net_expense_total = 0;
    $net_income_total = 0;
    $positive_net_pl = 0;
    $financialAccountObj= new \Modules\Finance\Entities\FinancialAccount;
    $functionCore= new \App\Http\Controllers\Helpers\Accounting\FunctionCore;
@endphp

<h3>Alokito School and College</h3>
<h4>Closing Balance Sheet as on 31-Dec-2019</h4>

<table class="table-bordered">
    <tr>
        <th width="70%">Gross Expenses (Dr)</th>
        <th class="text-right">Amount TK</th>
    </tr>
    {{$financialAccountObj->account_st_short($pandl['gross_expenses'], $c = -1, $this, 'D')}}
    <?php
    /* Gross Expense Total */
    $gross_total = $pandl['gross_expense_total'];
    if ($functionCore->calculate($pandl['gross_expense_total'], 0, '>=')) {
        echo '<tr style="font-weight: bold">';
        echo '<td>Total Gross Expense</td>';
        echo '<td class="text-right">' . $functionCore->toCurrency('D', $pandl['gross_expense_total']) . '</td>';
        echo '</tr>';
    } else {
        echo '<tr style="font-weight: bold">';
        echo '<td>Total Gross Expense</td>';
        echo '<td class="text-right show-tooltip" data-toggle="tooltip" data-original-title="Expecting Dr Balance">' . $this->functionscore->toCurrency('D', $pandl['gross_expense_total']) . '</td>';
        echo '</tr>';
    }
    ?>
    <tr style="font-weight: bold; color: #006400">
        <?php
        /* Gross Profit C/D */
        if ($functionCore->calculate($pandl['gross_pl'], 0, '>=')) {
            echo '<td>Gross Profit C/D</td>';
            echo '<td class="text-right">' . $functionCore->toCurrency('', $pandl['gross_pl']) . '</td>';
            $gross_total = $functionCore->calculate($gross_total, $pandl['gross_pl'], '+');
        } else {
            echo '<td></td>';
            echo '<td></td>';
        }
        ?>
    </tr>
    <tr style="font-weight: bold; background-color: #8c8c8c">
        <td>Total</td>
        <td class="text-right"><?php echo $functionCore->toCurrency('D', $gross_total); ?></td>
    </tr>
</table>
<table class="table-bordered">
    <tr>
        <th width="70%">Gross Income (Cr)</th>
        <th class="text-right">Amount TK</th>
    </tr>
    {{$financialAccountObj->account_st_short($pandl['gross_incomes'], $c = -1, $this, 'C')}}
    <?php
    /* Gross Income Total */
    $gross_total = $pandl['gross_income_total'];
    if ($functionCore->calculate($pandl['gross_income_total'], 0, '>=')) {
        echo '<tr style="font-weight: bold">';
        echo '<td>Gross Incomes </td>';
        echo '<td class="text-right">' . $functionCore->toCurrency('C', $pandl['gross_income_total']) . '</td>';
        echo '</tr>';
    } else {
        echo '<tr style="font-weight: bold">';
        echo '<td>Gross Incomes</td>';
        echo '<td class="text-right show-tooltip" data-toggle="tooltip" data-original-title="Expecting Cr Balance">' . $this->functionscore->toCurrency('C', $pandl['gross_income_total']) . '</td>';
        echo '</tr>';
    }
    ?>
    <tr  style="font-weight: bold; color: #006400">
        <?php
        /* Gross Loss C/D */
        if ($functionCore->calculate($pandl['gross_pl'], 0, '>=')) {
            echo '<td></td>';
            echo '<td></td>';
        } else {
            echo '<td>Gross Loss C/D</td>';
            $positive_gross_pl = $functionCore->calculate($pandl['gross_pl'], 0, 'n');
            echo '<td class="text-right">' . $functionCore->toCurrency('', $positive_gross_pl) . '</td>';
            $gross_total = $functionCore->calculate($gross_total, $positive_gross_pl, '+');
        }
        ?>
    </tr>
    <tr style="font-weight: bold; background-color:#8c8c8c">
        <td>Total</td>
        <td class="text-right"><?php echo $functionCore->toCurrency('C', $gross_total); ?></td>
    </tr>
</table>
<table class="table-bordered">
    <tr>
        <th width="70%">Net Expenses(Dr)</th>
        <th class="text-right">Amount TK</th>
    </tr>
    {{$financialAccountObj->account_st_short($pandl['net_expenses'], $c = -1, $this, 'D')}}
    <?php
    /* Net Expense Total */
    $net_expense_total = $pandl['net_expense_total'];
    if ($functionCore->calculate($pandl['net_expense_total'], 0, '>=')) {
        echo '<tr style="font-weight: bold">';
        echo '<td>Total Expenses</td>';
        echo '<td class="text-right">' . $functionCore->toCurrency('D', $pandl['net_expense_total']) . '</td>';
        echo '</tr>';
    } else {
        echo '<tr style="font-weight: bold">';
        echo '<td>Total Expenses</td>';
        echo '<td class="text-right show-tooltip" data-toggle="tooltip" data-original-title="Expecting Dr Balance">' . $this->functionscore->toCurrency('D', $pandl['net_expense_total']) . '</td>';
        echo '</tr>';
    }
    ?>
    <tr  style="font-weight: bold; color: #006400">
        <?php
        /* Gross Loss B/D */
        if ($functionCore->calculate($pandl['gross_pl'], 0, '>=')) {
            echo '<td></td>';
            echo '<td></td>';
        } else {
            echo '<td>Gross Loss B/D</td>';
            $positive_gross_pl = $functionCore->calculate($pandl['gross_pl'], 0, 'n');
            echo '<td class="text-right">' . $functionCore->toCurrency('', $positive_gross_pl) . '</td>';
            $net_expense_total =$functionCore->calculate($net_expense_total, $positive_gross_pl, '+');
        }
        ?>
    </tr>
    <tr  style="font-weight: bold; color: #006400">
        <?php
        /* Net Profit */
        if ($functionCore->calculate($pandl['net_pl'], 0, '>=')) {
            echo '<td>Net Profit</td>';
            echo '<td class="text-right">' .$functionCore->toCurrency('', $pandl['net_pl']) . '</td>';
            $net_expense_total =$functionCore->calculate($net_expense_total, $pandl['net_pl'], '+');
        } else {
            echo '<td></td>';
            echo '<td></td>';
        }
        ?>
    </tr>
    <tr style="font-weight: bold; background-color: #8c8c8c">
        <td>Total</td>
        <td class="text-right"><?php echo $functionCore->toCurrency('D', $net_expense_total); ?></td>
    </tr>
</table>
<table class="table-bordered">
    <tr>
        <th width="70%">Net Incomes(Cr)</th>
        <th class="text-right">Amount TK</th>
    </tr>
    {{$financialAccountObj->account_st_short($pandl['net_incomes'], $c = -1, $this, 'C')}}
    <?php
    /* Net Income Total */
    $net_income_total = $pandl['net_income_total'];
    if ($functionCore->calculate($pandl['net_income_total'], 0, '>=')) {
        echo '<tr style="font-weight: bold">';
        echo '<td>Net Income Total</td>';
        echo '<td class="text-right">' . $functionCore->toCurrency('C', $pandl['net_income_total']) . '</td>';
        echo '</tr>';
    } else {
        echo '<tr class="dc-error bold-text">';
        echo '<td>Net Income Total</td>';
        echo '<td class="text-right show-tooltip" data-toggle="tooltip" data-original-title="Expecting Cr Balance">' .$functionCore->toCurrency('C', $pandl['net_income_total']) . '</td>';
        echo '</tr>';
    }
    ?>
    <tr  style="font-weight: bold; color: #006400">
        <?php
        /* Gross Profit B/D */
        if ($functionCore->calculate($pandl['gross_pl'], 0, '>=')) {
            $net_income_total = $functionCore->calculate($net_income_total, $pandl['gross_pl'], '+');
            echo '<td>Gross Profit B/D </td>';
            echo '<td class="text-right">' .  $functionCore->toCurrency('', $pandl['gross_pl']) . '</td>';
        } else {
            echo '<td></td>';
            echo '<td></td>';
        }
        ?>
    </tr>
    <tr  style="font-weight: bold; color: #006400">
        <?php
        /* Net Loss */
        if ($functionCore->calculate($pandl['net_pl'], 0, '>=')) {
            echo '<td></td>';
            echo '<td></td>';
        } else {
            echo '<td>Net Loss</td>';
            $positive_net_pl = $functionCore->calculate($pandl['net_pl'], 0, 'n');
            echo '<td class="text-right">' . $functionCore->toCurrency('', $positive_net_pl) . '</td>';
            $net_income_total = $functionCore->calculate($net_income_total, $positive_net_pl, '+');
        }
        ?>
    </tr>
    <tr style="font-weight: bold; background-color: #8c8c8c">
        <td>Total</td>
        <td class="text-right"><?php echo$functionCore->toCurrency('C', $net_income_total); ?></td>
    </tr>
</table>
</body>
</html>
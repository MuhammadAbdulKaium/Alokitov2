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
        }
        h2, h3 {
            text-align: center;
        }
    </style>
</head>
<body>
@php
    $financialAccountObj= new \Modules\Finance\Entities\FinancialAccount;
    $functionCore= new \App\Http\Controllers\Helpers\Accounting\FunctionCore;
@endphp

<h3>Alokito School and College</h3>
<h4>Closing Balance Sheet as on 31-Dec-2019</h4>

<table class="table-bordered">
    <!-- Assets -->
    <tr>
        <th  width="70%">Assets</th>
        <th class="text-right">Amount(TK)</th>
    </tr>
    {{$financialAccountObj->account_st_short($bsheet['assets'], $c = -1, $this, 'D')}}

    </tr>
    <?php
    /* Assets Total */
    if ($functionCore->calculate($bsheet['assets_total'], 0, '>=')) {
        echo '<tr style="font-weight: bold;">';
        echo '<td>Total Asset</td>';
        echo '<td class="text-right">' . $functionCore->toCurrency('D', $bsheet['assets_total']) . '</td>';
        echo '</tr>';
    } else {
        echo '<tr style="font-weight: bold;">';
        echo '<td>Total Asset</td>';
        echo '<td class="text-right show-tooltip" data-toggle="tooltip" data-original-title="Expecting positive Dr Balance">' . $this->functionscore->toCurrency('D', $bsheet['assets_total']) . '</td>';
        echo '</tr>';
    }
    ?>
    <tr style="font-weight: bold;">
        <?php
        /* Net loss */
        if ($functionCore->calculate($bsheet['pandl'], 0, '>=')) {
            echo '<td></td>';
            echo '<td></td>';
        } else {
            echo '<td>Net Loss</td>';
            $positive_pandl = $functionCore->calculate($bsheet['pandl'], 0, 'n');
            echo '<td class="text-right">' . $functionCore->toCurrency('D', $positive_pandl) . '</td>';
        }
        ?>
    </tr>
    <?php
    /* Difference in opening balance */
    if ($bsheet['is_opdiff']) {
        echo '<tr style="font-weight: bold; background-color: #FF0000">';
        /* If diff in opening balance is Dr */
        if ($bsheet['opdiff']['opdiff_balance_dc'] == 'D') {
            echo '<td>Diffrent Opening Balance</td>';
            echo '<td class="text-right">' .$functionCore->toCurrency('D', $bsheet['opdiff']['opdiff_balance']) . '</td>';
        } else {
            echo '<td></td>';
            echo '<td></td>';
        }
        echo '</tr>';
    }
    ?>

    <?php
    /* Total */
    if ($functionCore->calculate($bsheet['final_liabilities_total'],
        $bsheet['final_assets_total'], '==')) {
        echo '<tr style="font-weight: bold; background-color: #efefef">';
    } else {
        echo '<tr style="font-weight: bold; background-color: #efefef">';
    }
    echo '<td>Total</td>';
    echo '<td class="text-right">' .
        $functionCore->toCurrency('D', $bsheet['final_assets_total']) .
        '</td>';
    echo '</tr>';
    ?>

</table>


<table class="table-bordered" style="margin-top: 20px">
    <!-- Liabilities Calculate -->
    <tr>
        <th width="70%">Liabilities and Owners Equity</th>
        <th class="text-right">Amount(TK)</th>
    </tr>
    {{$financialAccountObj->account_st_short($bsheet['liabilities'], $c = -1, $this, 'C')}}

    </tr>

    <?php
    /* Liabilities Total */
    if ($functionCore->calculate($bsheet['liabilities_total'], 0, '>=')) {
        echo '<tr style="font-weight: bold">';
        echo '<td>Total Liabilities and Owners Equity</td>';
        echo '<td class="text-right">' . $functionCore->toCurrency('C', $bsheet['liabilities_total']) . '</td>';
        echo '</tr>';
    } else {
        echo '<tr style="font-weight: bold">';
        echo '<td>Total Liabilities and Owners Equity</td>';
        echo '<td class="text-right show-tooltip" data-toggle="tooltip" data-original-title="Expecting positive Cr balance">' . $this->functionscore->toCurrency('C', $bsheet['liabilities_total']) . '</td>';
        echo '</tr>';
    }
    ?>
    <tr style="font-weight: bold">
        <?php
        /* Net profit */
        if ($functionCore->calculate($bsheet['pandl'], 0, '>=')) {
            echo '<td>Net Profit</td>';
            echo '<td class="text-right">' .$functionCore->toCurrency('C', $bsheet['pandl']) . '</td>';
        } else {
            echo '<td></td>';
            echo '<td></td>';
        }
        ?>
    </tr>
    <?php
    /* Difference in opening balance */
    if ($bsheet['is_opdiff']) {
        echo '<tr style="font-weight: bold; background-color: #FF0000">';
        /* If diff in opening balance is Cr */
        if ($bsheet['opdiff']['opdiff_balance_dc'] == 'C') {
            echo '<td>Different Opening Balance</td>';
            echo '<td class="text-right">' . $functionCore->toCurrency('C', $bsheet['opdiff']['opdiff_balance']) . '</td>';
        } else {
            echo '<td></td>';
            echo '<td></td>';
        }
        echo '</tr>';
    }
    ?>

    <?php
    /* Total */
    if ($functionCore->calculate($bsheet['final_liabilities_total'],
        $bsheet['final_assets_total'], '==')) {
        echo '<tr style="font-weight: bold; background-color: #efefef">';
    } else {
        echo '<tr style="font-weight: bold; background-color: #efefef">';
    }
    echo '<td>Total</td>';
    echo '<td class="text-right">' .
        $functionCore->toCurrency('C', $bsheet['final_liabilities_total']) .
        '</td>';
    echo '</tr>';
    ?>

</table>
</body>
</html>




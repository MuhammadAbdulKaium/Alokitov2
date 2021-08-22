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

        table.table-bordered .tr-group {
            font-weight: bold;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
    </style>
</head>
<body>

<h3>Alokito School and College</h3>
<h4>Closing Balance Sheet as on 31-Dec-2019</h4>


@php
    $financialAccountObj= new \Modules\Finance\Entities\FinancialAccount;
    $functionCore= new \App\Http\Controllers\Helpers\Accounting\FunctionCore;
@endphp
<table class="table-bordered">
    <thead>
    <tr style="background-color: #4B8B3B; color: #fff">
        <th>Account Name</th>
        <th>Type</th>
        <th>O/P Balance</th>
        <th>Debit Total</th>
        <th>Credit Total</th>
        <th>C/L Balance</th>
    </tr>
    </thead>
    <tfoot>
    <?php
    $financialAccountObj->print_trail_balance_account_chart($accountlist, -1, $this);

    if ($functionCore->calculate($accountlist->dr_total, $accountlist->cr_total, '==')) {
        echo '<tr style="font-weight: bold; background-color: #4B8B3B; color: #fff">';
    } else {
        echo '<tr style="font-weight: bold; background-color: #FF0000; color: #fff"">';
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
    </tfoot>
</table>
</body>
</html>
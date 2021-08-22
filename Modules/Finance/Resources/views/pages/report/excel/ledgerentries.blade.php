@php
    $functionCore= new \App\Http\Controllers\Helpers\Accounting\FunctionCore;
@endphp

<table>
    <tr>
        <th colspan="8" style="text-align: center">{{$subtitle}}</th>
        <td></td>
        </tr>
</table>

<table class="table table-bordered">
    <tbody>
    <tr>
        <th>Bank or cash account</th>
        <td> <?php
            echo ($ledger_data['type'] == 1) ? 'Yes' : 'No';
            ?></td>
        <th>Opening balance as on 01-Jan-2019</th>
        <td><?php echo $functionCore->toCurrency($op['dc'], $op['amount']); ?></td>
    </tr>
    <tr>
        <th>Notes</th>
        <td><?php echo ($ledger_data['notes']); ?></td>
        <th>Closing balance as on 31-Dec-2019</th>
        <td><?php echo $functionCore->toCurrency($cl['dc'], $cl['amount']); ?></td>
    </tr>
    </tbody>
</table>

<table class="stripped">
    <tr>
        <th>Date</th>
        <th>Number</th>
        <th>Ledger</th>
        <th>Type</th>
        <th>Tag</th>
        <th>Dr Amount</th>
        <th>Cr Amount</th>
   </tr>
    <?php
    /* Show the entries table */
    foreach ($entries as $entry) {
    $entryTypeName = 'Payment';
    $entryTypeLabel = 'Recipt';

    echo '<tr>';
    echo '<td>' . date($entry->date). '</td>';
    echo '<td>' . $entry->number. '</td>';
    echo '<td>' . ($functionCore->entryLedgers($entry->id)) . '</td>';
    echo '<td>' . ($entryTypeName) . '</td>';
    echo '<td>Tag Name</td>';
    if ($entry->dc == 'D') {
        echo '<td>' . $functionCore->toCurrency('D', $entry->amount) . '</td>';
        echo '<td>' . '</td>';
    } else if ($entry->dc == 'C') {
        echo '<td>' . '</td>';
        echo '<td>' . $functionCore->toCurrency('C', $entry->amount) . '</td>';
    } else {
        echo '<td>Search Amount Error</td>';
        echo '<td>Search Amount Error</td>';
    }

    echo '</tr>';
    }
    ?>
</table>
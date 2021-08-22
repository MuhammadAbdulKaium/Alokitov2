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
        <th>Balance </th>
   </tr>
    <?php
        if($entriesStatement) {
    /* Current opening balance */
    $entry_balance['amount'] = $current_op['amount'];
    $entry_balance['dc'] = $current_op['dc'];
    echo '<tr style="background-color: #51cfd2">';
    echo '<td colspan="7">';
    echo 'Current opening balance';
    echo '</td>';
    echo '<td>' . $functionCore->toCurrency($current_op['dc'], $current_op['amount']) . '</td>';
    echo '<td></td>';
    echo '</tr>';
    }

    ?>
    <?php
    /* Show the entries table */
    foreach ($entries as $entry) {
    /* Calculate current entry balance */
    $entry_balance = $functionCore->calculate_withdc(
        $entry_balance['amount'], $entry_balance['dc'],
        $entry->amount, $entry->dc
    );
    // get entry Type name and label
    //                            $et =$entry->getEntryTypeById($entry->id);
    $entryTypeName = 'Payment';
    $entryTypeLabel = 'Recipt';

    /* Negative balance if its a cash or bank account and balance is Cr */
    if ($ledger_data['type'] == 1) {
        if ($entry_balance['dc'] == 'C' && $entry_balance['amount'] != '0.00') {
            echo '<tr class="error-text">';
        } else {
            echo '<tr>';
        }
    } else {
        echo '<tr>';
    }

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

    echo '<td>' . $functionCore->toCurrency($entry_balance['dc'], $entry_balance['amount']) . '</td>';

    echo '</tr>';
    }
    ?>
    <?php
    if($entriesStatement) {
    /* Current closing balance */
    echo '<tr style="background-color: #51cfd2">';
    echo '<td colspan="7">';
    echo 'Current closing balance';
    echo '</td>';
    echo '<td>' . $functionCore->toCurrency($entry_balance['dc'], $entry_balance['amount']) . '</td>';
    echo '<td></td>';
    echo '</tr>';
    }
    ?>
</table>
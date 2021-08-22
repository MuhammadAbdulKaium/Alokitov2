<table id="feeDetails" class="table table-striped table-bordered" style="margin-top: 20px">
    <thead>
    <tr>
        <th>Student ID</th>
        <th>Fee Head</th>
        <th>Sub Head</th>
        <th>Amount</th>
        <th>Payment Date</th>

    </tr>
    </thead>
    <tbody>
    @foreach($feeTransactionList as $transaction)
    <tr class="gradeX">
        <td>{{$transaction->std_id}}</td>
        <td>{{$transaction->invoiceProfile()->feehead()->name}}</td>
        <td>{{$transaction->invoiceProfile()->subhead()->name}}</td>
        <td>{{$transaction->amount}}</td>
        <td>{{$transaction->payment_date}}</td>
    </tr>

  @endforeach
    </tbody>
</table>
<script>

    $('#feeDetails').dataTable();

  </script>
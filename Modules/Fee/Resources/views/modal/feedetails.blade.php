<table id="feeDetails" class="table table-striped table-bordered" style="margin-top: 20px">
    <thead>
    <tr>
        <th>Student ID</th>
        <th>Fee Head</th>
        <th>Sub Head</th>
        <th>Amount</th>
        <th>Paid</th>
        <th>Due</th>
    </tr>
    </thead>
    <tbody>
    @foreach($feeInvoiceDetails as $invoice)
    <tr class="gradeX">
        <td>{{$invoice->studentProfile()->username}}</td>
        <td>{{$invoice->feehead()->name}}</td>
        <td>{{$invoice->subhead()->name}}</td>
        <td>{{$invoice->amount}}</td>
        <td>{{$invoice->paid_amount}}</td>
        <td>{{$invoice->amount-$invoice->paid_amount}}</td>
    </tr>

  @endforeach
    </tbody>
</table>
<script>

    $('#feeDetails').dataTable();

  </script>
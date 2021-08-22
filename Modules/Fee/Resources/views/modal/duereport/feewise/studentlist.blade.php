<link rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/css/dataTables.checkboxes.css">

<table id="invoiceListTable" class="table table-striped table-bordered" style="margin-top: 20px">
    <thead>
    <tr>
        <th>STD ID</th>
        <th>Name</th>
        <th>Roll</th>
        <th>Amount</th>
        {{--<th>Waiver</th>--}}
        <th>Paid Amount</th>
        <th>Due Amount</th>
    </tr>

    </thead>
    <tbody>

    @php $feeInvoicetList=$feeInvoiceListArray['invoiceList'] @endphp
    @foreach($feeInvoicetList as $key=>$invoice)
        <tr class="gradeX">
            <td width="15%">{{$feeInvoicetList[$key]['std_id']}}</td>
            <td width="20%">{{$feeInvoicetList[$key]['std_name']}}</td>
            <td width="3%">{{$feeInvoicetList[$key]['std_roll']}}</td>
            <td width="3%">{{$feeInvoicetList[$key]['std_amount']}}</td>
            <td width="5%">{{$feeInvoicetList[$key]['std_paid_amount']}}</td>
            <td width="5%">{{$feeInvoicetList[$key]['std_due_amount']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>

    var oTable = $('#invoiceListTable').dataTable({
        stateSave: true
    });

</script>
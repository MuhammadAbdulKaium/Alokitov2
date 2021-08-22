<link rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/css/dataTables.checkboxes.css">

<table id="studentListTable" class="table table-striped table-bordered" style="margin-top: 20px; font-size: 12px">
    <thead>
    <tr>
        <th>STD ID</th>
        <th>Name</th>
        <th>Roll</th>
        <th>Paid</th>
        <th>Payment Date</th>
    </tr>

    </thead>
    <tbody>

    @php $absentfineReceiptList=$absentfineReceiptArray['moneyreceipt'] @endphp
    @foreach($absentfineReceiptList as $key=>$receipt)
        <tr class="gradeX">
            <td width="15%">{{$absentfineReceiptList[$key]['std_id']}}</td>
            <td width="20%">{{$absentfineReceiptList[$key]['std_name']}}</td>
            <td width="3%">{{$absentfineReceiptList[$key]['std_roll']}}</td>
            <td width="5%">{{$absentfineReceiptList[$key]['std_paid_amount']}}</td>
            <td width="10%">{{$absentfineReceiptList[$key]['std_date']}}</td>
        </tr>
    @endforeach

    </tbody>
</table>

<script>

    var oTable = $('#studentListTable').dataTable({
        stateSave: true
    });



</script>
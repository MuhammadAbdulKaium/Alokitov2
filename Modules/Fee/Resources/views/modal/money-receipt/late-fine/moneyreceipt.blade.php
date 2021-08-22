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
    @php $latefineReceiptList=$latefineReceiptArray['moneyreceipt'] @endphp
    @foreach($latefineReceiptList as $key=>$receipt)
        <tr class="gradeX">
            <td width="15%">{{$latefineReceiptList[$key]['std_id']}}</td>
            <td width="20%">{{$latefineReceiptList[$key]['std_name']}}</td>
            <td width="3%">{{$latefineReceiptList[$key]['std_roll']}}</td>
            <td width="5%">{{$latefineReceiptList[$key]['std_paid_amount']}}</td>
            <td width="10%">{{$latefineReceiptList[$key]['std_date']}}</td>
        </tr>
    @endforeach

    </tbody>
</table>

<script>

    var oTable = $('#studentListTable').dataTable({
        stateSave: true
    });



</script>
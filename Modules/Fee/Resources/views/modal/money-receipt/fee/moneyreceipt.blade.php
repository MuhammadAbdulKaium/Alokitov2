<table id="studentListTable" class="table table-striped table-bordered" style="margin-top: 20px; font-size: 12px">
    <thead>
    <tr>
        <th>STD ID</th>
        <th>Name</th>
        <th>Roll</th>
        <th>ckass</th>
        <th>section</th>
        <th>Fee Head</th>
        <th>Sub Head</th>
        <th>Paid</th>
        <th>Date</th>
        <th>View</th>
    </tr>

    </thead>
    <tbody>
    @php $feeReceiptList=$feeReceiptArray['moneyreceipt'] @endphp
    @foreach($feeReceiptList as $key=>$receipt)
        <tr class="gradeX">
            <td width="15%">{{$feeReceiptList[$key]['std_id']}}</td>
            <td width="20%">{{$feeReceiptList[$key]['std_name']}}</td>
            <td width="3%">{{$feeReceiptList[$key]['std_roll']}}</td>
            <td width="10%">{{$feeReceiptList[$key]['std_class']}}</td>
            <td width="5%">{{$feeReceiptList[$key]['std_section']}}</td>
            <td width="10%">{{$feeReceiptList[$key]['std_fee_head']}}</td>
            <td width="10%">{{$feeReceiptList[$key]['std_sub_head']}}</td>
            <td width="5%">{{$feeReceiptList[$key]['std_paid_amount']}}</td>
            <td width="10%">{{$feeReceiptList[$key]['std_date']}}</td>
            <td width="5%"><a href="">View</a> </td>
        </tr>
    @endforeach

    </tbody>
</table>

<script>

    var oTable = $('#studentListTable').dataTable({
        stateSave: true
    });



</script>
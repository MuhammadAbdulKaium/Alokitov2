<style>
    .heading {
        text-align: center;
        margin: 0px;
        padding: 0px;
    }
    p {
        font-size: 12px;
        padding-top: 5px;
    }
    h2, h5 {
        margin: 0px;
        padding: 0px;
        line-height: 0px;
    }
    #studentListTable {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #studentListTable td, #studentListTable th {
        border: 1px solid #ddd;
        padding: 2px;
        font-size: 10px;
    }

    /*#studentListTable tr:nth-child(even){background-color: #f2f2f2;}*/


    #studentListTable th {
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
</style>

<div class="heading">
    <h2>{{$instituteInfo->institute_name}}</h2>
    <p>{{$instituteInfo->address1}}</p>
    <h5>Due Fee Report</h5>
    <p>Class: <strong>{{$feeInvoiceListArray['class']}}</strong>   Section : <strong>{{$feeInvoiceListArray['section']}}</strong></p>

</div>
<table id="studentListTable" class="table table-striped table-bordered" style="margin-top: 20px">
    <thead>
    <tr>
        <th>STD ID</th>
        <th>Name</th>
        <th>Roll</th>
        <th>Fee Head</th>
        <th>Sub Head</th>
        <th>Amount</th>
        {{--<th>Waiver</th>--}}
        <th>Paid</th>
        <th>Due </th>
    </tr>

    </thead>
    <tbody>

    @php $feeInvoicetList=$feeInvoiceListArray['invoiceList'] @endphp
    @foreach($feeInvoicetList as $key=>$invoice)
        <tr class="gradeX">
            <td width="15%">{{$feeInvoicetList[$key]['std_id']}}</td>
            <td width="20%">{{$feeInvoicetList[$key]['std_name']}}</td>
            <td width="3%">{{$feeInvoicetList[$key]['std_roll']}}</td>
            <td width="8%">{{$feeInvoicetList[$key]['std_fee_head']}}</td>
            <td width="10%">{{$feeInvoicetList[$key]['std_sub_head']}}</td>
            <td width="3%">{{$feeInvoicetList[$key]['std_amount']}}</td>
            <td width="5%">{{$feeInvoicetList[$key]['std_paid_amount']}}</td>
            <td width="5%">{{$feeInvoicetList[$key]['std_due_amount']}}</td>
        </tr>
    @endforeach
    <tr>
        <th colspan="5" align="right">Total Paid Amount</th>
        <th>{{$feeInvoiceListArray['totalAmount']}}</th>
        <th>{{$feeInvoiceListArray['totalPayableAmount']}}</th>
        <th>{{$feeInvoiceListArray['totalPaidamount']}}</th>

    </tr>
    </tbody>
</table>

<script>

    var oTable = $('#invoiceListTable').dataTable({
        stateSave: true
    });

</script>
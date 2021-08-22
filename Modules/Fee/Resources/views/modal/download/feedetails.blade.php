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
    #feeDetails {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #feeDetails td, #feeDetails th {
        border: 1px solid #ddd;
        padding: 2px;
        font-size: 10px;
    }

    /*#studentListTable tr:nth-child(even){background-color: #f2f2f2;}*/


    #feeDetails th {
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
</style>

<div class="heading">
    <h2>{{$instituteInfo->institute_name}}</h2>
    <p>{{$instituteInfo->address1}}</p>
    <h5>Report Collection Amount</h5>
    <p>Class: <strong>{{$class}}</strong>   Section : <strong>{{$section}}</strong></p>

</div>

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
    @php $totalAmount=0; $totalPaidAmount=0; $totalDueAmount=0 @endphp
    @foreach($feeInvoiceDetails as $invoice)
        <tr class="gradeX">
            <td>{{$invoice->studentProfile()->username}}</td>
            <td>{{$invoice->feehead()->name}}</td>
            <td>{{$invoice->subhead()->name}}</td>
            <td>{{$invoice->amount}}</td>
            <td>{{$invoice->paid_amount}}</td>
            <td>{{$invoice->amount-$invoice->paid_amount}}</td>
        </tr>
        @php
            $totalAmount+=$invoice->amount;
            $totalPaidAmount+=$invoice->paid_amount;
            $totalDueAmount+=$invoice->amount-$invoice->paid_amount
        @endphp

    @endforeach
    <tr>
        <th colspan="3" align="right">Total Paid Amount</th>
        <th>{{$totalAmount}}</th>
        <th>{{$totalPaidAmount}}</th>
        <th>{{$totalDueAmount}}</th>

    </tr>
    </tbody>
</table>
<script>

    $('#feeDetails').dataTable();

</script>
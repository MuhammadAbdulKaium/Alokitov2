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
<h5>Money Receipt</h5>
    <p>Form Date {{date('d/m/Y',strtotime($start_date))}} to {{date('d/m/Y',strtotime($end_date))}}</p>
</div>

<table id="studentListTable" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>STD ID</th>
        <th>Name</th>
        <th>Roll</th>
        <th>Class</th>
        <th>Section</th>
        <th>Fee Head</th>
        <th>Sub Head</th>
        <th>Paid</th>
        <th>Date</th>
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
        </tr>
    @endforeach
    <tr>
        <th colspan="7" align="right">Total Paid Amount</th>
        <th colspan="2">{{$feeReceiptArray['totalPaidamount']}}</th>

    </tr>

    </tbody>
</table>

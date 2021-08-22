<!DOCTYPE html>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fees Invoice Report</title>
    {{--<style>body { font-family:  'Siyamrupali'; } </style>--}}
    <style>
        /*@font-face {*/
            /*font-family: 'Siyamrupali';*/
            /*!*font-style: normal;*!*/
            /*!*font-weight: normal;*!*/
            /*!*src: url(http://venusitltd.com/fonts/Siyamrupali.ttf) format('truetype');*!*/
        /*}*/




        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }
        #inst-info{
            float:left;
            width: 85%;
            margin: 30px;
            text-align: center;
        }


        #inst-photo {
            float: left;
            margin-top: 20px;
            margin-left: 10px;
        }


        * { margin: 0; padding: 0; }
        .header-info {
            clear: both;

        }



        #details {
            clear: both;
            margin-top: 40px;
            margin-left: 20px;
        }

        #client {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
            float: left;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0  0 10px 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }


        body { font: 11px/1.4 Georgia, serif; }
        textarea { border: 0; font: 11px Georgia, Serif; overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        table td, table th { border: 1px solid black; padding: 5px; }
        #tableDesign {
            padding: 20px;
        }
        #tableDesign { clear: both; width: 100%; margin: 0px 0 0 0; border: 1px solid black; }
        #tableDesign th { background: #eee; }
        #tableDesign textarea { width: 80px; height: 50px; }
        #tableDesign tr.item-row td { border: 0; vertical-align: top; }
        #tableDesign td.description { width: 300px; }
        #tableDesign td.item-name { width: 175px; }
        #tableDesign td.description textarea, #tableDesign td.item-name textarea { width: 100%; }
        #tableDesign td.total-line { border-right: 0; text-align: right; }
        #tableDesign td.total-value { border-left: 0; padding: 10px; }
        #tableDesign td.total-value textarea { height: 20px; background: none; }
        #tableDesign td.balance { background: #eee; }
        #tableDesign td.blank { border: 0; }


        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 4px 0;
            text-align: center;
        }
        span.label {
            background: red;
            color:#FFffff;
            padding: 3px;
            height: 60px;
        }

        .transactions_table {
            font-size: 10px;
        }
        .payment {
            font-size: 12px;
            padding: 10px;
        }
    .paid-icon {
        padding-bottom: 10px;
    }


    </style>
</head>
<body>

@php
    $std=$invoice->payer();
    $enroll=$std->singleEnroll();
    $fees=$invoice->fees();
@endphp

<div class="header-info">
    <div id="inst-photo">
        <img src="{{public_path().'/assets/users/images/'.$institute->logo}}"  style="width:100px;height:100px">
    </div>
    <div id="inst-info">
        <b style="font-size: 30px">{{$institute->institute_name}}</b><br/>{{'Address: '.$institute->address1}}<br/>{{'Phone: '.$institute->phone. ', E-mail: '.$institute->email.', Website: '.$institute->website}}
    </div>
</div>


    <div id="details" class="clearfix" style="width: 550px">
        <div id="client">
            <div class="to">INVOICE TO:</div>
            <h2 class="name">{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}                                               <br>
            </h2>
            E-mail : <a href="{{$std->email}}">{{$std->email}}</a>
            <br>
            Contact No. : {{$std->phone}}

        </div>
        <div id="invoice" style="width: 720px">
            <h3>INVOICE #{{$invoice->id}}</h3>

            @if ($invoice->invoice_status=="2")
                <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-danger">Un-Paid</span>
            @elseif ($invoice->invoice_status=="1")
                <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-primary">Paid</span>
            @else
                <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-success">Partail Payment</span>
            @endif

            <div class="date">Due Date: @php $due_date=date('Y-m-d',strtotime($fees->due_date)); @endphp
                {{$due_date}}</div>
        </div>
    </div>

    @if($invoice->invoice_status=="1")
        <div class="paid-icon">
        <img src="{{public_path('assets/fees/icon-paid.gif')}}" alt="Paid Image" class="img-responsive" style="width:65px;margin-top: 5px;">  </div>
    </div>
     @endif

    <table id="tableDesign"  border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th class="no">#</th>
            <th class="desc">DESCRIPTION</th>
            <th class="unit">UNIT PRICE</th>
            <th class="qty">QUANTITY</th>
            <th class="total">TOTAL</th>
        </tr>
        </thead>
        <tbody>
        @foreach($fees->feesItems() as $item)
        <tr>
            <td class="no">01</td>
            <td  style="font-family:Siyamrupali;" class="desc">{{$item->item_name}} </td>
            <td class="unit">{{$item->rate}} </td>
            <td class="qty">{{$item->qty}} </td>
            <td class="total">{{$item->rate*$item->qty}} </td>
        </tr>
        @endforeach
        </tbody>

<?php //var_dump($fees->feesItems());?>

        <tfoot>
        <tr>
            <td colspan="3" rowspan="7"></td>
            <td colspan="1" style="background: #eee">Sub Total</td>
            <td> @php $subtotal=0; $totalAmount=0; $totalDiscount=0; $getAttendFine=0; $getDueFine=0;  @endphp
                @foreach($fees->feesItems() as $amount)
                @php $subtotal += $amount->rate*$amount->qty;@endphp

                @endforeach
                {{$subtotal}} </td>
        </tr>
        @if($discount = $invoice->fees()->discount())
        @php $discountPercent=$discount->discount_percent;
        $totalDiscount=(($subtotal*$discountPercent)/100);
        $totalAmount=$subtotal-$totalDiscount
        @endphp
        @else
        @php
        $totalAmount=$subtotal;
        @endphp

        @endif



        @if($invoice->waiver_type=="1")
        @php $totalWaiver=(($totalAmount*$invoice->waiver_fees)/100);
        $totalAmount=$totalAmount-$totalWaiver
        @endphp
        @elseif($invoice->waiver_type=="2")
        @php $totalWaiver=$invoice->waiver_fees;
        $totalAmount=$totalAmount-$totalWaiver
        @endphp

        @endif



        @if($discount = $invoice->fees()->discount())
        @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
        @endif


        @if(!empty($invoice->waiver_fees))
        @php $totalDiscount=$totalDiscount+$totalWaiver @endphp
        @endif





        @php
        $dueFinePaid=$invoice->invoice_payment_summary();
        $var_dueFine=0;
        if($dueFinePaid){
        $var_dueFine = json_decode($dueFinePaid->summary);
        }
        @endphp

        @if(!empty($var_dueFine))
        @php $getDueFine=$var_dueFine->due_fine->amount; @endphp
        @else
        @php $getDueFine=get_fees_day_amount($invoice->fees()->due_date) @endphp
        @endif




        @php
        $attendanceFinePaid=$invoice->invoice_payment_summary();
        $var_AttnFine=0;
        if($attendanceFinePaid){
        $var_AttnFine = json_decode($attendanceFinePaid->summary);
        }
        @endphp
        @if(!empty($var_AttnFine))

        @php $getAttendFine= $var_AttnFine->attendance_fine->amount; @endphp
        @else

        @php $getAttendFine=getAttendanceFinePreviousMonth($invoice->id); @endphp
        @endif

        @php $attendance_fine=getAttendanceFinePreviousMonth($invoice->id);@endphp

        <tr>
            <td colspan="1"  style="background: #eee">Attendance Fine</td>
            <td class="right" style="border: none;float: right" >   @if($invoice->invoice_status=="2")
                {{$attendance_fine}}
                @elseif($invoice->invoice_status=="1")
                {{$getAttendFine}}
                @endif
            </td>
        </tr>


        <tr>
            <td colspan="1"  style="background: #eee">Due Fine</td>
            <td class="right" >@if($invoice->invoice_status=="2")
                + {{$day_fine_amount}}
                @elseif($invoice->invoice_status=="1")
                {{$getDueFine}}
                @endif
            </td>
        </tr>

        <tr>

            <td colspan="1"  style="background: #eee">Discount</td>
            <td>{{$totalDiscount }}</td>
        </tr>

        <tr>

            <td colspan="1"  style="background: #eee">Grand Total</td>
            <td>
                @if($invoice->invoice_status=="2")
                {{$totalAmount+$day_fine_amount+$attendance_fine}}
                @elseif($invoice->invoice_status=="1")
                {{$totalAmount+$getAttendFine+$getDueFine}}
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="1"  style="background: #eee">Total Amount Paid</td>
            <td class="right" style="border: none; float: right">
             @php  $totalAmountPaid=0;  @endphp
                @if(!empty($paymentList))
                @foreach($paymentList as $payment)
                @php $totalAmountPaid=$totalAmountPaid+$payment->payment_amount @endphp
                @endforeach


                @if($invoice->invoice_status=="2")
                {{ $totalAmountPaid}}
                @elseif($invoice->invoice_status=="1")
                {{$totalAmount+$getAttendFine+$getDueFine}}
                @endif
            @endif
            </td>
        </tr>

        <tr>
            <td colspan="1"  style="background: #eee">Total Amount Due</td>

            @if($invoice->invoice_status=="1")
            <td class="right strong" style="float: right">0</td>

            @elseif($invoice->invoice_status=="2")
            <td class="right strong" style="float: right">{{$totalAmount+$day_fine_amount+$attendance_fine}}</td>

            @else
            <td class="right strong" style="float: right">{{$totalAmount-$totalAmountPaid}}</td>

            @endif
        </tr>

        </tfoot>
    </table>
@if($paymentList->count())
    <div class="payment">Payment Transactions:</div>
        <table id="tableDesign">
            <tr>
                <td># No</td>
                <td>Payment Method</th>
                <td>Transaction Id/Cheque No</td>
                <td>Payment Date</td>
                <td>Payment Status</td>
                <td>Payment Amount</td>
            </tr>

            @php $i=1; @endphp
            @foreach($paymentList as $payment)
            <tr>
                <td >{{$i++}}</td>
                <td >{{$payment->payment_method()->method_name}} </td>
                <td >{{$payment->transaction_id}} </td>
                <td >{{$payment->payment_date}} </td>
                <td >{{$payment->payment_status}} </td>
                <td >{{$payment->payment_amount}} </td>
            </tr>
            @endforeach
        </table>
@endif
</main>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fees Invoice Report</title>
    {{--<style>body { font-family:  'Siyamrupali'; } </style>--}}
    <style>
        @font-face {
            font-family: 'Siyamrupali';
            /*font-style: normal;*/
            /*font-weight: normal;*/
            /*src: url(http://venusitltd.com/fonts/Siyamrupali.ttf) format('truetype');*/
        }

        body {  font-family: 'Siyamrupali';  }


        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }


        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        #logo {
            float: left;
            margin-top: 8px;
        }

        #logo img {
            height: 70px;
        }

        #company {
            float: right;
            text-align: right;
        }


        #details {
            margin-bottom: 50px;
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

        table {
            width: 90%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 5px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: right;
        }

        table td h3{
            color: #57B223;
            font-size: 12px;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 12px;
            background: #57B223;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }

        table .qty {
        }

        table .total {
            background: #57B223;
            color: #FFFFFF;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 12px;
        }

        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            padding: 5px 10px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 12px;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr:last-child td {
            color: #57B223;
            font-size: 12px;
            border-top: 1px solid #57B223;

        }

        table tfoot tr td:first-child {
            border: none;
        }

        #thanks{
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices{
            padding-left: 6px;
            border-left: 6px solid #0087C3;
        }

        #notices .notice {
            font-size: 1.2em;
        }

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
            font-size: 16px;
            padding: 10px;
        }
        .paid-icon {
            padding-bottom: 10px;
        }


    </style>
</head>
<body>

<div class="invoice-body" style="width: 30%">
@php
    $std=$invoice->payer();
    $enroll=$std->singleEnroll();
    $fees=$invoice->fees();
@endphp
<header class="clearfix" style="100%;">

    {{trans('fees_modules/invoice_fees_report.invoice_title')}}
    <div id="logo">
        <img src="{{public_path('assets/users/images/'.$institute->logo)}}">
    </div>

    <div id="company" style=" width: 200px; float:right; margin-top: -70px;  ">
        <h2 class="name">{{trans('fees_modules/invoice_fees_report.school')}}</h2>

            {{trans('fees_modules/invoice_fees_report.phone')}}: {{numberFormatter("1768368565")}}
            <a href="#">{{trans('fees_modules/invoice_fees_report.address')}} </a>

        </div>
    </div>
</header>
<div>
    <div id="details" class="clearfix" style="width:100%">
        <div id="client">
            <div class="to">{{trans('fees_modules/invoice_fees_report.invoice')}}:</div>
            <h2 class="name">{{trans('fees_modules/invoice_fees_report.std_name')}} <br>
            </h2>
            {{trans('fees_modules/invoice_fees_report.phone')}}: romeshshil99@gmail.com
            <br>
            {{trans('fees_modules/invoice_fees_report.email')}} : {{trans('fees_modules/invoice_fees_report.phone_number')}}

        </div>
        <div id="invoice" style="width: 200px; float:right; margin-top: -100px;">
            <div class="to">{{trans('fees_modules/invoice_fees_report.invoice')}}</div>
            #{{numberFormatter("98375")}} <br>
            @if ($invoice->invoice_status=="2")
                <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-danger">{{trans('fees_modules/invoice_fees_report.unpaid')}}</span>
            @elseif ($invoice->invoice_status=="1")
                <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-primary">{{trans('fees_modules/invoice_fees_report.paid')}}</span>
            @else
                <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-success">{{trans('fees_modules/invoice_fees_report.partial_paid')}}</span>
            @endif

            <div class="date">{{trans('fees_modules/invoice_fees_report.due_date')}}: {{trans('fees_modules/invoice_fees_report.date')}}</div>
        </div>
    </div>
    @if($invoice->invoice_status=="1")
        <div class="paid-icon">
            <img src="{{public_path('assets/fees/icon-paid.gif')}}" alt="Paid Image" class="img-responsive" style="width:65px;margin-top: 5px;">  </div>
</div>
@endif


<table  border="0" cellspacing="0" cellpadding="0" style="width:100%">
    <thead>
    <tr>
        <th class="no">#{{trans('fees_modules/invoice_fees_report.number')}}</th>
        <th class="desc">{{trans('fees_modules/invoice_fees_report.description')}}</th>
        <th class="unit">{{trans('fees_modules/invoice_fees_report.unit_price')}}</th>
        <th class="qty">{{trans('fees_modules/invoice_fees_report.quantity')}}</th>
        <th class="total">{{trans('fees_modules/invoice_fees_report.total')}}</th>
    </tr>
    </thead>
    <tbody>

    @php $i=1; @endphp
    @foreach($fees->feesItems() as $item)
        <tr>
            <td class="no">{{numberFormatter($i++) }}</td>
            <td  style="font-family:Siyamrupali;" class="desc">{{$item->item_name}} </td>
            <td class="unit">{{numberFormatter($item->rate) }} </td>
            <td class="unit">{{numberFormatter($item->qty) }} </td>
            <td class="unit">{{numberFormatter($item->rate*$item->qty)}} </td>
        </tr>
    @endforeach

    <?php //var_dump($fees->feesItems());?>

    </tbody>
    <tfoot>
    <tr>
        <td colspan="2"></td>
        <td colspan="2">{{trans('fees_modules/invoice_fees_report.subtotal')}}</td>
        <td> @php $subtotal=0; @endphp
            @foreach($fees->feesItems() as $amount)
            @php $subtotal += $amount->rate*$amount->qty;@endphp

            @endforeach
            {{numberFormatter($subtotal)}}  </td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="2">{{trans('fees_modules/invoice_fees_report.discount')}}</td>
        <td>@if($discount = $invoice->fees()->discount())
            @php $discountPercent=$discount->discount_percent; $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
            {{numberFormatter($totalDiscount) }}

            @endif</td>
    </tr>

    <tr>
        <td colspan="2"></td>
        <td colspan="2">{{trans('fees_modules/invoice_fees_report.grand_total')}} </td>
        <td>@if($discount = $invoice->fees()->discount())
            @php $discountPercent=$discount->discount_percent;
            $totalDiscount=(($subtotal*$discountPercent)/100);
            $totalAmount=$subtotal-$totalDiscount
            @endphp
            {{numberFormatter($totalAmount) }}
            @else
            {{numberFormatter($subtotal) }}

            @endif</td>
    </tr>

    <tr>
        <td colspan="2"></td>
        <td colspan="2">{{trans('fees_modules/invoice_fees_report.total_amount_paid')}}</td>
        <td>@php  $totalAmountPaid=0;  @endphp
            @if(!empty($paymentList))
            @foreach($paymentList as $payment)
            @php $totalAmountPaid=$totalAmountPaid+$payment->payment_amount @endphp
            @endforeach
            {{numberFormatter($totalAmountPaid) }}
            @endif</td>
    </tr>

    <tr>
        <td colspan="2"></td>
        <td colspan="2">{{trans('fees_modules/invoice_fees_report.total_amount_due')}}</td>
        @if($discount = $invoice->fees()->discount())
        @php $discountPercent=$discount->discount_percent;
        $totalDiscount=(($subtotal*$discountPercent)/100);
        $totalAmount=$subtotal-$totalDiscount
        @endphp
        <td class="right strong"> {{numberFormatter($totalAmount-$totalAmountPaid) }}</td>
        @else
        <td class="right strong"> {{numberFormatter($subtotal -$totalAmountPaid) }}</td>

        @endif
    </tr>

    </tfoot>
</table>
<div class="payment">{{trans('fees_modules/invoice_fees_report.payment_transaction')}}:</div>
<table class="transactions_table" style="width:100%">
    <tr>
        <td># {{trans('fees_modules/invoice_fees_report.number')}}</td>
        <td>{{trans('fees_modules/invoice_fees_report.payment_method')}}</th>
        <td>{{trans('fees_modules/invoice_fees_report.transaction_id')}}</td>
        <td>{{trans('fees_modules/invoice_fees_report.payment_date')}}</td>
        <td>{{trans('fees_modules/invoice_fees_report.payment_status')}}</td>
        <td>{{trans('fees_modules/invoice_fees_report.payment_amount')}}</td>
    </tr>

    @php $i=1; @endphp
    @foreach($paymentList as $payment)
    <tr>
        <td >{{numberFormatter($i++) }}</td>
        <td >{{$payment->payment_method()->method_name}} </td>
        <td >{{$payment->transaction_id}} </td>
        <td >{{numberFormatter($payment->payment_date) }} </td>
        <td >{{$payment->payment_status}} </td>
        <td >{{numberFormatter($payment->payment_amount) }}</td>

    </tr>
    @endforeach
</table>
</div>
</main>

</div>
</body>
</html>
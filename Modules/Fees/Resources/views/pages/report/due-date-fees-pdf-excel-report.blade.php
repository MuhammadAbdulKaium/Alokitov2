<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Fees Invoice Report</title>

    @if($report_type=="pdf")
    <style>
        #inst-info{
            float:left;
            width: 85%;
            margin: 30px;
            text-align: center;
        }

        #inst-photo {
            float: left;
            margin-top: 20px;
        }

        * { margin: 0; padding: 0; }
        .header-info {
            clear: both;

        }

        .small-section {
            border-top: 2px solid #00a65a;
            padding: 10px;
        }

        .payment{
            padding-top: 10px;
            font-size: 16px;
        }


        body { font: 12px/1.4 Georgia, serif; }
        textarea { border: 0; font: 14px Georgia, Serif; overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        table td, table th { border: 1px solid black; padding: 5px; }
        #feesDailyCollection {
            padding: 20px;
        }
        #feesDailyCollection { clear: both; width: 100%; margin: 30px 0 0 0; border: 1px solid black; }
        #feesDailyCollection th { background: #eee; }
        #feesDailyCollection textarea { width: 80px; height: 50px; }
        #feesDailyCollection tr.item-row td { border: 0; vertical-align: top; }
        #feesDailyCollection td.description { width: 300px; }
        #feesDailyCollection td.item-name { width: 175px; }
        #feesDailyCollection td.description textarea, #feesDailyCollection td.item-name textarea { width: 100%; }
        #feesDailyCollection td.total-line { border-right: 0; text-align: right; }
        #feesDailyCollection td.total-value { border-left: 0; padding: 10px; }
        #feesDailyCollection td.total-value textarea { height: 20px; background: none; }
        #feesDailyCollection td.balance { background: #eee; }
        #feesDailyCollection td.blank { border: 0; }
</style>

        </head>
<body>

<div class="header-info">
    <div id="inst-photo">
        @if($instituteInfo->logo)
            <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:100px;height:100px">
        @endif
    </div>
    <div id="inst-info">
        <b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
    </div>
</div>
@endif
<div class="small-section" style="clear: both" >
    <div class="payment">Due Date Invoice List:</div>
</div>
        <table id="feesDailyCollection" class="transactions_table">

                <thead>
                <tr>
                    <th>Invoice Id</th>
                    <th>Fee Name</th>
                    <th>Payer Name</th>
                    <th>Fees Amount</th>
                    <th>Discount</th>
                    <th>Paid Amount</th>
                    <th>Status</th>
                </tr>
                </thead>

            </tr>
            <tbody>
            @php  $i = 1; @endphp
                @foreach($invoiceList as $invoice)

                    <tr>
                        <td>{{$i++}}</td>
                        @php
                            $fees=$invoice->fees();
                            $std=$invoice->payer();
                        @endphp
                        <td>{{$fees->fee_name}}</td>
                        <td>{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}</td>
                        <td>
                            @php $subtotal=0; @endphp
                            @foreach($fees->feesItems() as $amount)
                                @php $subtotal += $amount->rate*$amount->qty;@endphp

                            @endforeach
                            @if($discount = $invoice->fees()->discount())
                                @php $discountPercent=$discount->discount_percent;
                                                    $totalDiscount=(($subtotal*$discountPercent)/100);
                                                    $totalAmount=$subtotal-$totalDiscount
                                @endphp
                                {{$totalAmount}}

                            @else
                                {{$subtotal}}

                            @endif
                        </td>
                        <td>
                            {{--@php @endphp--}}
                            @if($discount = $invoice->fees()->discount())
                                @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
                                {{$totalDiscount }}
                            @endif

                        </td>
                        <td>
                            {{$invoice->totalPayment()}}


                        </td>
                        <td>

                            @if ($invoice->invoice_status=="2")
                                <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-danger">Un-Paid</span>
                            @elseif ($invoice->invoice_status=="1")
                                <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-primary">Paid</span>
                            @elseif ($invoice->invoice_status=="4")
                                <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-danger">Cancel</span>
                            @else
                                <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-success">Partial Payment</span>
                            @endif

                            <span id="cancelInvoiceStatus{{$invoice->id}}" class="label label-danger" style="display: none">Cancel</span>
                        </td>
                        {{--<td> @if ($fees->partial_allowed==1) <span class="btn-orange">Yes<span> @else <span>No</span> @endif</td>--}}
                        {{--<td>{{date('m-d-Y',strtotime($fees->due_date))}}</td>--}}
                        {{--<td>{{$fees->fee_status}}</td>--}}

                    </tr>
                @endforeach
            <tbody>
        </table>
</main>
<footer>
</footer>
</body>
</html>
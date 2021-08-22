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
    <div class="payment">Payment List:</div>
</div>
        <table id="feesDailyCollection" class="transactions_table">

                <thead>
                <tr>
                    <th># No</th>
                    <th>Payment Date</th>
                    <th>Invoice ID</th>
                    <th></th>
                    <th>Student Name</th>
                    <th></th>
                    <th>Payment Method</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Payment Amount</th>
                </tr>
                </thead>

            </tr>
            <tbody>
            @php $totalAmount=0; $i = 1; @endphp
                @foreach($fpt_list as $payment)

                    @php
                        $totalAmount+=$payment->payment_amount;
                    @endphp

                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$payment->payment_date}}</td>
                        <td>{{$payment->invoice()->payer_id}}</td>
                        <td></td>
                        @php $std=$payment->invoice()->payer() @endphp
                        <td>{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}</td>
                        <td></td>
                        <td>{{$payment->payment_method()->method_name}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td >{{$payment->payment_amount}}</td>
                    </tr>
                @endforeach
            <tbody>
            <tfoot>
            <tr>
                <th id="total" style="text-align: right" colspan="11">Total :</th>
                <td>{{$totalAmount}}</td>
            </tr>
            </tfoot>

            {{--<tr>--}}
                {{--<td># 1</td>--}}
                {{--<td>Paypal</th>--}}
                {{--<td>9893457358FHG</td>--}}
                {{--<td>20/20/2017</td>--}}
                {{--<td>TOtal</td>--}}
                {{--<td>TOtal</td>--}}
            {{--</tr>--}}
        </table>
</main>
<footer>
</footer>
</body>
</html>
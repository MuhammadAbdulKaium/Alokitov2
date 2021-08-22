<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 7/6/17
 * Time: 12:50 PM
 */
use Modules\Accounting\Entities\AccCharts;
?>

<table>
    <thead>
    <tr>
        <th colspan="2" style="text-align: center;">Receipts</th>
    </tr>
    </thead>
    @php
        $receive = 0;
        $payment = 0;
        $receiveCount = count($receiveTrns);
        $paymentCount = count($paymentTrns) + 1;
        $count = $paymentCount - $receiveCount;

    @endphp
    @foreach($receiveTrns as $receiveTrn)
        <tr>
            <td style="text-align: left;">
                @php
                    $a = AccCharts::where('id',$receiveTrn->acc_charts_id)->get(['chart_name']);
                    $receive = $receive + $receiveTrn->amount;
                @endphp
                {{$a[0]->chart_name}}</td>
            <td style="text-align: right;">{{$receiveTrn->amount}}</td>
        </tr>
    @endforeach
    @if($count >0)
        @for($i=1;$i<=abs($count);$i++)
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
        @endfor
    @endif
    <tr>
        <td style="text-align: left;">Total</td>
        <td style="text-align: right;">{{$receive}}</td>
    </tr>
</table>


<table>
    <thead>
    <tr>
        <th colspan="2" style="text-align: center;">Payment</th>
    </tr>
    </thead>
    @foreach($paymentTrns as $paymentTrn)
        <tr>
            <td style="text-align: left;">
                @php
                    $a = AccCharts::where('id',$paymentTrn->acc_charts_id)->get(['chart_name']);
                    $payment = $payment + $paymentTrn->amount;
                @endphp
                {{$a[0]->chart_name}}</td>
            <td style="text-align: right;">{{$paymentTrn->amount}}</td>
        </tr>
    @endforeach
    @if($count < 0)
        @for($i=1;$i<=abs($count);$i++)
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
        @endfor
    @endif
    <tr>
        <td>Closing Balance</td>
        <td  style="text-align: right;">{{$receive - $payment}} </td>
    </tr>
    <tr>
        <td style="text-align: left;">Total</td>
        <td style="text-align: right;">{{$receive}}</td>
    </tr>
</table>
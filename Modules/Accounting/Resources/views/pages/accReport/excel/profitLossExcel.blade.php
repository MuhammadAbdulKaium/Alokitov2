<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 7/6/17
 * Time: 4:18 PM
 */
?>
<table>
    <tbody>
    @foreach($accHeads as $accHead)
        <tr style="font-weight: 600" >
            <td>{{$accHead->chart_name}}</td>
            <td style="text-align: right">{{ abs($accHead->sumCalc($accHead->id)) }}</td>
        </tr>
        @if(count($accHead->childs))
            @include('accounting::pages.accReport.manageChild',['childs' => $accHead->childs])
        @endif
        <tr><td colspan="2"></td></tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr style="font-weight: 600">
        @php
            $profitLoss = abs($accHead->sumCalc(3)) - abs($accHead->sumCalc(4));
        @endphp
        <td style="text-align: right">
            @if($profitLoss >= 0){{'Net Profit'}}
            @else {{'Net Loss'}}
            @endif
        </td>
        <td style="text-align: right">{{abs($profitLoss)}}</td>
    </tr>
    </tfoot>
</table>
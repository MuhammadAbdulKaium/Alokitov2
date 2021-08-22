<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 7/6/17
 * Time: 1:57 PM
 */
?>
<table id="myTable" class="table table-striped table-bordered">
    <tbody>
    @foreach($accHeads as $accHead)
        <tr style="font-weight: 600" >
            <td>{{$accHead->chart_name}}</td>
            <td style="text-align: right">{{ abs($accHead->sumCalc($accHead->id)) }}</td>
        </tr>
        @if(count($accHead->childs))
            @include('accounting::pages.accReport.manageChild',['childs' => $accHead->childs])
        @endif
        <tr style="font-weight: 600">
            <td  style="text-align: right"> Total</td>
            <td>{{ abs($accHead->sumCalc($accHead->id)) }}</td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr style="font-weight: 600">
        @php
            $profitLoss = abs($accHead->sumCalc(3)) - abs($accHead->sumCalc(4));
        @endphp
        <td>
            @if($profitLoss >= 0){{'Net Profit'}}
            @else {{'Net Loss'}}
            @endif
        </td>
        <td>{{abs($profitLoss)}}</td>
    </tr>
    <tr style="font-weight: 600">
        <td  style="text-align: right"> Total</td>
        <td>{{ abs($accHead->sumCalc($accHead->id)) + $profitLoss }}</td>
    </tr>
    </tfoot>
</table>

<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 7/6/17
 * Time: 1:39 PM
 */
?>
<table>
    <thead>
    <tr>
        <th style="text-align: center">Account Head</th>
        <th style="text-align: center">Dr Total</th>
        <th style="text-align: center">Cr Total</th>
        <th style="text-align: center">Closing Balance</th>
    </tr>
    </thead>
    <tbody>
    {{--@php $i=1 @endphp--}}
    @foreach($accHeads as $accHead)
        <tr style="font-weight: 600" >
            {{--<td>{{ $i++ }}</td>--}}
            <td>{{$accHead->chart_name}}</td>
            <td style="text-align: right">{{ abs($accHead->sumDrCalc($accHead->id)) }}</td>
            <td style="text-align: right">{{ abs($accHead->sumCrCalc($accHead->id)) }}</td>
            <td style="text-align: right">{{ abs($accHead->sumCalc($accHead->id)) }}</td>
        </tr>
        @if(count($accHead->childs))
            @include('accounting::pages.accReport.manageChildTB',['childs' => $accHead->childs])
        @endif
        <tr>
            <td colspan="4"></td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr style="font-weight: 600">
        <td  style="text-align: right"> Total</td>
        <td  style="text-align: right">
            {{
            abs($accHead->sumDrCalc(1)) +
            abs($accHead->sumDrCalc(2)) +
            abs($accHead->sumDrCalc(3)) +
            abs($accHead->sumDrCalc(4))
            }}
        </td>
        <td  style="text-align: right">
            {{
            abs($accHead->sumCrCalc(1)) +
            abs($accHead->sumCrCalc(2)) +
            abs($accHead->sumCrCalc(3)) +
            abs($accHead->sumCrCalc(4))
            }}</td>
        <td></td>
        <td></td>
    </tr>
    </tfoot>
</table>

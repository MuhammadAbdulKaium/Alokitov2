<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">

<table class="table table-bordered">
    <thead>
    <tr>
        <th>Fee Head</th>
        <th>Jan</th>
        <th>Feb</th>
        <th>Mar</th>
        <th>Apr</th>
        <th>May</th>
        <th>Jun</th>
        <th>Jul</th>
        <th>Aug</th>
        <th>Sep</th>
        <th>Oct</th>
        <th>Nov</th>
        <th>Dec</th>
        <th>Totoal</th>
    </tr>
    </thead>
    <tbody>
    @php $monthlyTotal=0; $yearlyJanuary=0; $yearlyFeb=0; $yearlyMar=0;;$yearlyApril=0; $yearlyMay=0; @endphp
    @foreach($feeHeadList as $feeHead)
    <tr>
        <td>{{$feeHead->name}} </td>
        @php $feeHeadMonthly=$feeHead->monthlyReportByfeeHead($feeHead->id); @endphp
{{--        @php $feeHeadMonthlyArray=($feeHeadMonthly[$feeHead->id]) @endphp--}}
{{--            {{dd($feeHeadMonthly)}}--}}
            {{--{{count($feeHeadMonthly)}}--}}
        @if(!empty($feeHeadMonthly[$feeHead->id]))
                @php $total=0; $januaryColumn=0; $febColumn=0; $marColumn=0; $aprilColumn=0; $mayColumn=0;@endphp
                    @for($i=1;$i<=count($feeHeadMonthly[$feeHead->id]); $i++)
                       <td>{{$feeHeadMonthly[$feeHead->id][$i]}}</td>



                        @if($i==1)
                            @php $januaryColumn+=$feeHeadMonthly[$feeHead->id][1]; @endphp
                        @elseif($i==2)
                            @php $febColumn+=$feeHeadMonthly[$feeHead->id][2]; @endphp
                        @elseif($i==3)
                            @php $marColumn+=$feeHeadMonthly[$feeHead->id][3]; @endphp
                        @elseif($i==4)
                            @php $aprilColumn+=$feeHeadMonthly[$feeHead->id][4]; @endphp
                        @elseif($i==5)
                            @php $mayColumn+=$feeHeadMonthly[$feeHead->id][5]; @endphp
                        {{--@elseif($i==6)--}}
                            {{--@php $junColumn+=$feeHeadMonthly[$feeHead->id][6]; @endphp--}}
                        {{--@elseif($i==7)--}}
                            {{--@php $julColumn+=$feeHeadMonthly[$feeHead->id][7]; @endphp--}}
                        {{--@elseif($i==8)--}}
                            {{--@php $augColumn+=$feeHeadMonthly[$feeHead->id][8]; @endphp--}}
                        {{--@elseif($i==9)--}}
                            {{--@php $sepColumn+=$feeHeadMonthly[$feeHead->id][9]; @endphp--}}
                        {{--@elseif($i==10)--}}
                            {{--@php $octColumn+=$feeHeadMonthly[$feeHead->id][10]; @endphp--}}
                        {{--@elseif($i==11)--}}
                            {{--@php $novColumn+=$feeHeadMonthly[$feeHead->id][11]; @endphp--}}
                        {{--@elseif($i==12)--}}
                            {{--@php $decColumn+=$feeHeadMonthly[$feeHead->id][12]; @endphp--}}
                        @endif







                        @php $total+=$feeHeadMonthly[$feeHead->id][$i] @endphp
                    @endfor
                <td>{{$total}}</td>
            @else
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>

        @endif
    </tr>
        @php
            $yearlyJanuary+=$januaryColumn;
            $yearlyFeb+=$febColumn;
            $yearlyMar+=$marColumn;
            $yearlyApril+=$aprilColumn;
            $yearlyMay+=$mayColumn;

        @endphp

  @endforeach
    <tr style="background: #8c8c8c">
        <th>Total </th>
        <th>{{$yearlyJanuary}}</th>
        <th>{{$yearlyFeb}}</th>
        <th>{{$yearlyMar}}</th>
        <th>{{$yearlyApril}}</th>
        <th>{{$yearlyMay}}</th>
        <th>500</th>
        <th>6000</th>
        <th>3000</th>
        <th>200</th>
        <th>6000</th>
        <th>222</th>
        <th>150</th>
        <th>10000</th>
    </tr>

    </tbody>
</table>
</div>
</body>
</html>

<script></script>
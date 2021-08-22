@extends('fee::layouts.feereport-colleciton-amount')
<!-- page content -->
@section('page-content')
    <style>
        .userprofile{
            padding: 15px;
            border: 2px solid #efefef;
            border-radius: 10px;
        }
    </style>

    <div class="box-body">
    </div>

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
        @php $totalMonthlyFee=0; @endphp
        @foreach($feeHeads as $feehead)
        <tr>
            <td>{{$feehead->name}} </td>
            @if(!empty($feehead->feeHeadMonthly($feehead->id)))
                @php $feeHeadProfile=$feehead->feeHeadMonthly($feehead->id) @endphp
            @foreach($feeHeadProfile['monthly'] as $key=>$month)
                <td>{{$month}}</td>
             @endforeach
             <td>{{$feeHeadProfile['monthlyTotal']}}</td>
                @php $totalMonthlyFee+=$feeHeadProfile['monthlyTotal']; @endphp
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
            @endif
        </tr>

            @endforeach
            <tr style="background: #ccc;font-weight: bold;">
                <td colspan="13">Total </td>
                <td>{{$totalMonthlyFee}} </td>
            </tr>


        </tbody>
    </table>

@endsection
@extends('finance::layouts.master')
@section('section-title')
    <h1>
        <i class="fa fa-search"></i>Finance
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/library/default/index">Finacne</a></li>
        <li class="active">Manage Account</li>
    </ul>
@endsection
<!-- page content -->
@section('page-content')
    <div class="box box-body">
        <div style="padding:25px;">
            <table class="table stripped">
                <tbody>
                <tr>
                    <th>Account Name</th>
                    <th>Type</th>
                    <th>O/P Balance (OMR)</th>
                    <th>Debit Total (OMR)</th>
                    <th>Credit Total (OMR)</th>
                    <th>C/L Balance (OMR)</th>
                </tr>
                <tr class="tr-group tr-root-group">
                    <td class="td-group">Assets</td>
                    <td>Group</td>
                    <td>Dr 0.000</td>
                    <td>Dr 0.000</td>
                    <td>Cr 0.000</td>
                    <td>Dr 0.000</td>
                </tr>
                @foreach($assetgroupList as $agroup)
                    <tr class="tr-group">
                        <td class="td-group">&nbsp;{{$agroup->name}}</td>
                        <td>Group</td>
                        <td>Dr 00</td>
                        <td>0.00</td>
                        <td>Cr 0</td>
                        <td>Dr 0</td>
                    </tr>

                    @if($agroup->getLedger($agroup->id))
                        @php $ledgerList=$agroup->getLedger($agroup->id); @endphp
                        @foreach($ledgerList as $ledger)
                            <tr class="tr-ledger">

                                <td class="td-ledger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">{{$ledger->name}}</a></td>
                                <td>Ledger</td>
                                <td>Dr 0</td>
                                <td>Dr {{$ledger->sumLedgerDrAmountById($ledger->id)}}</td>
                                <td>Cr {{$ledger->sumLedgerCrAmountById($ledger->id)}}</td>
                                <td>Dr0.000</td>

                            </tr>
                        @endforeach
                    @endif
                @endforeach



                {{--Liablitiye --}}
                {{--<tr class="tr-group tr-root-group">--}}
                    {{--<td class="td-group">Liabilities and Owners Equity</td>--}}
                    {{--<td>Group</td>--}}
                    {{--<td>0.000</td>--}}
                    {{--<td>0.000</td>--}}
                    {{--<td>0.000</td>--}}
                    {{--<td>0.000</td>--}}
                {{--</tr>--}}

                {{--@foreach($libgroupList as $lgroup)--}}
                    {{--<tr class="tr-group">--}}
                        {{--<td class="td-group">&nbsp;{{$lgroup->name}}</td>--}}
                        {{--<td>Group</td>--}}
                        {{--<td>Dr 00</td>--}}
                        {{--<td>Dr 0</td>--}}
                        {{--<td>Cr 0</td>--}}
                        {{--<td>Dr 0</td>--}}
                    {{--</tr>--}}

                    {{--@if($lgroup->getLedger($lgroup->id))--}}
                        {{--@php $ledgerList=$lgroup->getLedger($lgroup->id); @endphp--}}
                        {{--@foreach($ledgerList as $ledger)--}}
                            {{--<tr class="tr-ledger">--}}

                                {{--<td class="td-ledger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">{{$ledger->name}}</a></td>--}}
                                {{--<td>Ledger</td>--}}
                                {{--<td>Dr 0</td>--}}
                                {{--<td>Dr {{$ledger->sumLedgerDrAmountById($ledger->id)}}</td>--}}
                                {{--<td>Cr {{$ledger->sumLedgerCrAmountById($ledger->id)}}</td>--}}
                                {{--<td>Dr 0.000</td>--}}

                            {{--</tr>--}}
                        {{--@endforeach--}}
                    {{--@endif--}}
                {{--@endforeach--}}


                {{--income group--}}

                {{--<tr class="tr-group tr-root-group">--}}
                    {{--<td class="td-group">Incomes</td>--}}
                    {{--<td>Group</td>--}}
                    {{--<td>0.000</td>--}}
                    {{--<td>0.000</td>--}}
                    {{--<td>0.000</td>--}}
                    {{--<td>0.000</td>--}}
                {{--</tr>--}}

                {{--@foreach($incomegroupList as $igroup)--}}
                    {{--<tr class="tr-group">--}}
                        {{--<td class="td-group">&nbsp;{{$igroup->name}}</td>--}}
                        {{--<td>Group</td>--}}
                        {{--<td>Dr 00</td>--}}
                        {{--<td>Dr 0</td>--}}
                        {{--<td>Cr 0</td>--}}
                        {{--<td>Dr 0</td>--}}
                    {{--</tr>--}}

                    {{--@if($igroup->getLedger($igroup->id))--}}
                        {{--@php $ledgerList=$igroup->getLedger($igroup->id); @endphp--}}
                        {{--@foreach($ledgerList as $ledger)--}}
                            {{--<tr class="tr-ledger">--}}

                                {{--<td class="td-ledger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">{{$ledger->name}}</a></td>--}}
                                {{--<td>Ledger</td>--}}
                                {{--<td>Dr 0</td>--}}
                                {{--<td>Dr {{$ledger->sumLedgerDrAmountById($ledger->id)}}</td>--}}
                                {{--<td>Cr {{$ledger->sumLedgerCrAmountById($ledger->id)}}</td>--}}
                                {{--<td>Dr 0.000</td>--}}

                            {{--</tr>--}}
                        {{--@endforeach--}}
                    {{--@endif--}}
                {{--@endforeach--}}


                {{--Expense  group--}}

                {{--<tr class="tr-group tr-root-group">--}}
                    {{--<td class="td-group">Expenses</td>--}}
                    {{--<td>Group</td>--}}
                    {{--<td>0.000</td>--}}
                    {{--<td>0.000</td>--}}
                    {{--<td>0.000</td>--}}
                    {{--<td>0.000</td>--}}
                {{--</tr>--}}

                {{--@foreach($expensegroupList as $egroup)--}}
                    {{--<tr class="tr-group">--}}
                        {{--<td class="td-group">&nbsp;{{$egroup->name}}</td>--}}
                        {{--<td>Group</td>--}}
                        {{--<td>Dr 00</td>--}}
                        {{--<td>Dr 0</td>--}}
                        {{--<td>Cr 0</td>--}}
                        {{--<td>Dr 0</td>--}}
                    {{--</tr>--}}

                    {{--@if($egroup->getLedger($egroup->id))--}}
                        {{--@php $ledgerList=$egroup->getLedger($egroup->id); @endphp--}}
                        {{--@foreach($ledgerList as $ledger)--}}
                            {{--<tr class="tr-ledger">--}}

                                {{--<td class="td-ledger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">{{$ledger->name}}</a></td>--}}
                                {{--<td>Ledger</td>--}}
                                {{--<td>Dr 0</td>--}}
                                {{--<td>Dr {{$ledger->sumLedgerDrAmountById($ledger->id)}}</td>--}}
                                {{--<td>Cr {{$ledger->sumLedgerCrAmountById($ledger->id)}}</td>--}}

                                {{--<td>Dr 0.000</td>--}}

                            {{--</tr>--}}
                        {{--@endforeach--}}
                    {{--@endif--}}
                {{--@endforeach--}}









                {{--<tr class="bold-text ok-text">--}}
                    {{--<td>TOTAL</td>--}}
                    {{--<td></td>--}}
                    {{--<td></td>--}}
                    {{--<td>Dr {{$totalDebitAmount}}</td>--}}
                    {{--<td>Cr {{$totalCreditAmount}}</td>--}}
                    {{--<td><span class="glyphicon glyphicon-ok-sign"></span></td>--}}
                    {{--<td></td>--}}
                {{--</tr>--}}
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('page-script')
@endsection
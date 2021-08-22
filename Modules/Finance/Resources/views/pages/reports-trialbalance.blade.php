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

    <style>
        <style type="text/css">


        ul.nav li.dropdown:hover > ul.dropdown-menu {
            display: block;
        }





        /************** TABLES ***************/
        table {
            width: 100%;
        }

        th {
            border-bottom: 2px solid #555;
            text-align: left;
            font-size: 14px;
        }

        th a {
            display: block;
            padding: 2px 4px;
            text-decoration: none;
        }

        th a.asc:after {
            content: ' ⇣';
        }

        th a.desc:after {
            content: ' ⇡';
        }

        table tr td {
            padding: 4px;
            text-align: left;
        }

        table.stripped tr td {
            border-bottom:1px solid #DDDDDD;
            border-top:1px solid #DDDDDD;
        }

        table.stripped tr:hover {
            background-color: #FFFF99;
        }

        table.stripped .tr-ledger {

        }

        table.stripped .tr-group {
            font-weight: bold;
        }

        table.extra tr td {
            padding: 6px;
        }

        table.stripped .tr-root-group {
            background-color: #F3F3F3;
            color: #754719;
        }

        .ajax-add {
            background-color: #F3F3F3;
        }

        /* Forms */

        fieldset {
            border: none;
            margin-left: 10px;
        }
        fieldset legend {
            font-size: 17px;
            margin-left: -10px;
            border-bottom: 1px solid #DDDDDD;
        }

        /* Form errors */

        form .error {
            -moz-border-radius: 4px;
            -webkit-border-radius: 4px;
            border-radius: 4px;
            font-weight: normal;
        }
        form .error-message {
            -moz-border-radius: none;
            -webkit-border-radius: none;
            border-radius: none;
            border: none;
            background: none;
            margin: 0;
            padding-left: 4px;
            padding-right: 0;
        }
        form .error,
        form .error-message {
            color: #9E2424;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            -ms-box-shadow: none;
            -o-box-shadow: none;
            box-shadow: none;
            text-shadow: none;
        }

        .paginate {
            margin-top:20px;
        }

        #footer {
            margin-top: 60px;
            border-top: 1px solid #BBBBBB;
            background-color: #f8f8f8;
            border-color: #e7e7e7;
            z-index: 999999;
            bottom: 0;
        }

        .table-top {
            vertical-align: top;
        }

        .width-50 {
            width: 40%;
        }

        .width-drcr {
            width: 80px;
        }


        .report-tb-pad {
            padding-top: 30px;
        }

        .bold-text {
            font-weight: bold;
        }

        .error-text {
            font-weight: bold;
            color: #FF0000;
        }

        .ok-text {
            color: #006400;
        }

        .bg-filled {
            background-color: #F3F3F3;
        }

        .alert {
            font-size: 17px;
        }

        .help-block {
            font-size: 12px;
        }

        .subtitle {
            color: #333333;
            font-size: 19px;
            margin-bottom: 10px;
        }

        .summary {
            background-color: #FFFFCC;
            border: 1px solid #BBBBBB;
            border-collapse: collapse;
            text-align: left;
            width: 600px;
        }

        .summary td {
            border: 1px solid #BBBBBB;
        }

        table.stripped .tr-highlight {
            background-color: #F3F3F3;
        }

        .font-normal {
            font-weight: normal;
        }

        .td-fixwidth-summary {
            width: 300px;
            vertical-align: text-top;
        }

        .box-container {
            border: 1px solid #BBBBBB;
            padding: 20px;
        }


        .print-button {
            padding: 5px;
        }

        a.footer-power {
            text-decoration: none;
        }

        /* Select2 */
        .select2 {
            min-width: 200px;
        }

        .select2-container--default .select2-results__option[aria-disabled="true"] {
            font-weight: bold;
            color: #000;
        }

        div.kb-shorcuts:hover {
            opacity:.999999;
        }

        .kb-shorcuts {
            border-color: #e7e7e7;
            opacity: .2;
            float: left;
        }

        .kb-shorcuts ul li:first-letter {
            text-transform:uppercase;
        }

        .kb-shorcuts ul li {
            display: inline-block;
            margin: 0 3px;
            border: 1px solid #ccc;
            font-weight: bold;
            padding: 2px;
            background: #fff;
        }

        .kb-shorcuts ul li span {
            color: #428bca;
            margin-left: 5px;
            margin-right: 5px;
        }

        .text-small {
            font-size:12px;
            font-style: italic;
            color: #666;
        }
    </style>


    <div class="box box-body">
        <div style="padding:25px;">
            <table class="table stripped">
                <tbody>
                <tr>
                    <th>Account Name</th>
                    <th>Type</th>
                    <th>O/P Balance</th>
                    <th>Debit Total</th>
                    <th>Credit Total</th>
                    <th>C/L Balance</th>
                </tr>

                @php $parentGroupList=$trailBalanceArray['parent']; @endphp

                @foreach($parentGroupList as $key=>$parentGroup)

                    {{--Parent Details--}}
                    <tr class="tr-group tr-root-group">
                        <td class="td-group">{{$parentGroup['name']}}</td>
                        <td>Group</td>
                        <td>Dr {{$parentGroup['op_balance']}} </td>
                        <td>Dr {{$parentGroup['dr_amount']}}</td>
                        <td>Cr {{$parentGroup['cr_amount']}}</td>
                        <td>Cr 0.000</td>
                    </tr>
                    {{--child gorup--}}
                    @php $childGroupList=array_key_exists('child',$parentGroup)?$parentGroup['child']:[]; @endphp
                    {{--List Loop--}}
                    @foreach($childGroupList as $chiledKey=>$childGroup)
                        {{--{{dd($childGroup['ledger'])}}--}}

                        {{--Child Gorup Details--}}
                        <tr class="tr-group">
                            <td class="td-group">&nbsp;&nbsp; {{$childGroup['name']}}</td>
                            <td>Group</td>
                            <td>Dr {{$childGroup['op_balance']}} </td>
                            <td>Dr {{$childGroup['dr_amount']}}</td>
                            <td>Cr {{$childGroup['cr_amount']}}</td>
                            <td>Cr 0.00</td>
                        </tr>
                        {{--Ledger List--}}
                        @php $ledgerList = array_key_exists('ledger', $childGroup)?$childGroup['ledger']:[]; @endphp
                        @foreach($ledgerList as  $key=>$ledger)
                            <tr class="tr-ledger">

                                <td class="td-ledger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;<a href="#"> {{$ledger['name']}}</a>
                                </td>
                                <td>Ledger</td>
                                <td>Dr {{$ledger['op_balance']}} </td>
                                <td>Dr {{$ledger['dr_amount']}}</td>
                                <td>Cr {{$ledger['cr_amount']}}</td>
                                <td>Cr 0.00</td>
                            </tr>
                        @endforeach
                        {{--@endif--}}
                    @endforeach
                    {{--@endif--}}
                @endforeach


                <tr class="bold-text ok-text">
                <td>TOTAL</td>
                <td></td>
                <td></td>
                <td>Dr {{ $totalDebitCredit['total_debit']}}</td>
                <td>Cr {{ $totalDebitCredit['total_credit']}}</td>
                <td><span class="glyphicon glyphicon-ok-sign"></span></td>
                <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('page-script')
@endsection
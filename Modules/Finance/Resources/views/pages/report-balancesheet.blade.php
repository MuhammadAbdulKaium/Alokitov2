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



        <table class="table">

            <!-- Liabilities and Assets -->
            <tbody><tr>
                <td class="table-top width-50" style="border-top: 0px;">
                    <table class="table table-white">
                        <tbody>

                        @php $liblitiesGroup=$balanceSheetArray['parent']['LIABILITES']; @endphp
                        <tr>
                            <th>{{$liblitiesGroup['name']}}</th>
                            <th class="text-right">(Cr) Amount)</th>
                        </tr>

                        @php $childGroupList=array_key_exists('child',$liblitiesGroup)?$liblitiesGroup['child']:[]; @endphp
                        {{--List Loop--}}
                        @foreach($childGroupList as $chiledKey=>$childGroup)

                            <tr class="tr-group">
                                <td class="td-group">&nbsp;{{$childGroup['name']}}</td>
                                <td class="text-right">Cr {{$childGroup['cr_amount']}}</td>
                            </tr>

                            @php $ledgerList = array_key_exists('ledger', $childGroup)?$childGroup['ledger']:[]; @endphp
                            @foreach($ledgerList as  $key=>$ledger)

                                <tr class="tr-ledger">
                                    <td class="td-ledger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/finance/reports/ledgerstatement/ledgerid:2">{{$ledger['name']}}</a></td>
                                    <td class="text-right">Cr {{$ledger['cr_amount']}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                </tr>
                            @endforeach
                        @endforeach

                        </tbody></table>
                </td>

                <td class="table-top width-50" style="border-top:0px;">
                    <table class="table table-white">
                        <tbody>

                        @php $assetGroup=$balanceSheetArray['parent']['ASSET']; @endphp
                        <tr>
                            <th>{{$assetGroup['name']}}</th>
                            <th class="text-right">(Dr) Amount)</th>
                        </tr>

                        @php $childGroupList=array_key_exists('child',$assetGroup)?$assetGroup['child']:[]; @endphp
                        {{--List Loop--}}
                        @foreach($childGroupList as $chiledKey=>$childGroup)

                            <tr class="tr-group">
                                <td class="td-group">&nbsp;{{$childGroup['name']}}</td>
                                <td class="text-right">Dr {{$childGroup['dr_amount']}}</td>
                            </tr>

                            @php $ledgerList = array_key_exists('ledger', $childGroup)?$childGroup['ledger']:[]; @endphp
                            @foreach($ledgerList as  $key=>$ledger)

                                <tr class="tr-ledger">
                                    <td class="td-ledger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/finance/reports/ledgerstatement/ledgerid:2">{{$ledger['name']}}</a></td>
                                    <td class="text-right">Dr {{$ledger['dr_amount']}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                </tr>
                            @endforeach
                        @endforeach

                        </tbody></table>
                </td>
            </tr>

            <tr>
                <td class="table-top width-50">
                    <div class="report-tb-pad"></div>
                    <table class="table  stripped">
                        <tbody><tr><td>Total Liability and Owners Equity</td><td class="text-right">{{$liblitiesGroup['cr_amount']}}</td></tr>				<tr>
                            <td>Profit &amp; Loss Account (Net Profit)</td><td class="text-right">{{$assetGroup['dr_amount']-$liblitiesGroup['cr_amount']}}</td>				</tr>
                        <tr class="bold-text">
                            <td>Total</td>
                            <td class="text-right">{{$assetGroup['dr_amount']}}</td>
                        </tr>
                        </tbody></table>
                </td>

                <td class="table-top width-50">
                    <div class="report-tb-pad"></div>
                    <table class="table  stripped">
                        <tbody><tr><td>Total Assets</td><td class="text-right">Dr {{$assetGroup['dr_amount']}}</td></tr>				<tr>
                            <td>&nbsp;</td><td>&nbsp;</td>				</tr>
                        <tr class="bold-text">
                            <td>Total</td>
                            <td class="text-right">{{$assetGroup['dr_amount']}}</td>
                        </tr>
                        </tbody></table>
                </td>
            </tr>
            <!-- END Liabilities and Assets -->

            </tbody></table>


    </>
    </div>
@endsection
@section('page-script')
@endsection
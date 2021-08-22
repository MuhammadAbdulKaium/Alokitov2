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

                <!-- Gross Profit and Loss -->
                <tbody><tr>
                    <td class="table-top width-50">

                        <table class="colVis table table-white dataTable">
                            <tbody><tr>
                                <th>Gross Expenses</th>
                                <th class="text-right">(Dr) Amount</th>
                            </tr>

                            @php $grossExpenseList=$profitandLossArray['GROSS']['EXPENSE']; @endphp
                            @foreach($grossExpenseList as $key=>$grossExpense)
                                <tr class="tr-group">
                                    <td class="td-group">&nbsp;{{$grossExpense['name']}}</td>
                                    <td class="text-right">Dr {{$grossExpense['dr_amount']}}</td>
                                </tr>
                                @php $ledgerList = array_key_exists('ledger', $grossExpense)?$grossExpense['ledger']:[]; @endphp
                                @foreach($ledgerList as  $key=>$ledger)

                                    <tr class="tr-ledger">
                                        <td class="td-ledger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/finance/reports/ledgerstatement/ledgerid:2">{{$ledger['name']}}</a></td>
                                        <td class="text-right">Dr {{$ledger['dr_amount']}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    </tr>
                                @endforeach
                            @endforeach

                            </tbody></table>
                    </td>

                    <td class="table-top width-50">
                        <table class="colVis table table-white dataTable">
                            <tbody><tr>
                                <th>Gross Incomes</th>
                                <th class="text-right">(Cr) Amount</th>
                            </tr>
                            @php $grossIncomeList=$profitandLossArray['GROSS']['INCOME']; @endphp
                            @foreach($grossIncomeList as $key=>$grossIncome)
                                <tr class="tr-group">
                                    <td class="td-group">&nbsp;{{$grossIncome['name']}}</td>
                                    <td class="text-right">Dr {{$grossIncome['cr_amount']}}</td>
                                </tr>
                                @php $ledgerList = array_key_exists('ledger', $grossIncome)?$grossIncome['ledger']:[]; @endphp
                                @foreach($ledgerList as  $key=>$ledger)

                                    <tr class="tr-ledger">
                                        <td class="td-ledger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/finance/reports/ledgerstatement/ledgerid:2">{{$ledger['name']}}</a></td>
                                        <td class="text-right">Dr {{$ledger['cr_amount']}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    </tr>
                                @endforeach
                            @endforeach

                            </tbody></table>
                    </td>
                </tr>

                <tr>
                    <td class="table-top width-50">
                        <div class="report-tb-pad"></div>
                        <table class="colVis table table-white dataTable">
                            <tbody><tr><td>Total Gross Expenses</td><td class="text-right">0.00</td></tr>
                            <tr>
                                <td>Gross Profit C/O</td><td class="text-right">700.00</td>				</tr>
                            <tr class="bold-text">
                                <td>Total</td>
                                <td class="text-right">700.00</td>
                            </tr>
                            </tbody></table>
                    </td>

                    <td class="table-top width-50">
                        <div class="report-tb-pad"></div>
                        <table class="colVis table table-white dataTable">
                            <tbody><tr><td>Total Gross Incomes</td><td class="text-right">Cr 700.00</td></tr>				<tr>
                                <td>&nbsp;</td><td>&nbsp;</td>				</tr>
                            <tr class="bold-text">
                                <td>Total</td>
                                <td class="text-right">700.00</td>
                            </tr>
                            </tbody></table>
                    </td>
                </tr>

                <!-- Net Profit and Loss -->
                <tr>
                    <td class="table-top width-50">
                        <div class="report-tb-pad"></div>
                        <table class="table stripped">
                            <tbody><tr>
                                <th>Net Expenses</th>
                                <th class="text-right">(Dr) Amount</th>
                            </tr>

                            @php $netExpenseList=$profitandLossArray['NET']['EXPENSE']; @endphp
                            {{--                            {{dd($grossExpenseList)}}--}}
                            @foreach($netExpenseList as $key=>$netExpense)
                                <tr class="tr-group">
                                    <td class="td-group">&nbsp;{{$netExpense['name']}}</td>
                                    <td class="text-right">Dr {{$netExpense['dr_amount']}}</td>
                                </tr>
                                @php $ledgerList = array_key_exists('ledger', $netExpense)?$netExpense['ledger']:[]; @endphp
                                @foreach($ledgerList as  $key=>$ledger)

                                    <tr class="tr-ledger">
                                        <td class="td-ledger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/finance/reports/ledgerstatement/ledgerid:2">{{$ledger['name']}}</a></td>
                                        <td class="text-right">Dr {{$ledger['dr_amount']}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    </tr>
                                @endforeach
                            @endforeach


                            </tbody></table>
                    </td>

                    <td class="table-top width-50">
                        <div class="report-tb-pad"></div>
                        <table class="table stripped">
                            <tbody><tr>
                                <th>Net Incomes</th>
                                <th class="text-right">(Cr) Amount</th>
                            </tr>
                            <tr class="tr-group"><td class="td-group">&nbsp;Indirect Incomes</td><td class="text-right">0.00</td></tr>			</tbody></table>
                    </td>
                </tr>

                <tr>
                    <td class="table-top width-50">
                        <div class="report-tb-pad"></div>
                        <table class="table stripped">
                            <tbody><tr><td>Total Expenses</td><td class="text-right">0.00</td></tr>				<tr>
                                <td>&nbsp;</td><td>&nbsp;</td>				</tr>
                            <tr>
                                <td>Net Profit</td><td class="text-right">700.00</td>				</tr>
                            <tr class="bold-text">
                                <td>Total</td>
                                <td class="text-right">700.00</td>
                            </tr>
                            </tbody></table>
                    </td>

                    <td class="table-top width-50">
                        <div class="report-tb-pad"></div>
                        <table class=" table stripped">
                            <tbody><tr><td>Total Incomes</td><td class="text-right">0.00</td></tr>				<tr>
                                <td>Gross Profit B/F</td><td class="text-right">700.00</td>				</tr>
                            <tr>
                                <td>&nbsp;</td><td>&nbsp;</td>				</tr>
                            <tr class="bold-text">
                                <td>Total</td>
                                <td class="text-right">700.00</td>
                            </tr>
                            </tbody></table>
                    </td>
                </tr>
                </tbody></table>


        </div>
    </div>
@endsection
@section('page-script')
@endsection
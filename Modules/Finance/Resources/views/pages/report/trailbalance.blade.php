@extends('finance::layouts.master')
@section('section-title')

    <h1>
        <i class="fa fa-search"></i>Finance
    </h1>
    <ul class="breadcrumb">
        <li>
            <a href="/">
                <i class="fa fa-home"></i>Home
            </a>
        </li>
        <li>
            <a href="/library/default/index">Finacne</a>
        </li>
        <li class="active">Manage Account</li>
    </ul>

@endsection

<!-- page content -->
@section('page-content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Report - Trial Balance</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="btn-group" role="group">
                            <a href="{{URL::to('finance/accounts/reports/trailbalance/download/pdf')}}" class="btn btn-default btn-sm">Export to .PDF</a>
                            <a href="{{URL::to('finance/accounts/reports/trailbalance/download/excel')}}" class="btn btn-default btn-sm">Export to .Excel</a>
                            {{--<a class="btn btn-default btn-sm" onclick="window.print()">Print</a>--}}
                        </div>

                        <div id="section-to-print">
                            <div class="subtitle text-center">
                                Trial Balance from 01-Jan-2019 to 31-Dec-2019 </div>

                            @php
                                $financialAccountObj= new \Modules\Finance\Entities\FinancialAccount;
                                $functionCore= new \App\Http\Controllers\Helpers\Accounting\FunctionCore;
                            @endphp


                            <?php
                            echo '<table class="stripped">'; ?>
                            <thead>
                            <tr>
                                <th>Account Name</th>
                                <th>Type</th>
                                <th>O/P Balance (৳)</th>
                                <th>Debit Total (৳)</th>
                                <th>Credit Total (৳)</th>
                                <th>C/L Balance (৳)</th>
                            </tr>
                            </thead>
                            <?php
                            $financialAccountObj->print_trail_balance_account_chart($accountlist, -1, $this);

                                if ($functionCore->calculate($accountlist->dr_total, $accountlist->cr_total, '==')) {
                                    echo '<tr class="bold-text ok-text">';
                                } else {
                                    echo '<tr class="bold-text error-text">';
                                }
                                echo '<td>Total</td>';
                                echo '<td></td><td></td>';
                                echo '<td>' . $functionCore->toCurrency('D', $accountlist->dr_total) . '</td>';
                                echo '<td>' . $functionCore->toCurrency('C', $accountlist->cr_total) . '</td>';
                                if ($functionCore->calculate($accountlist->dr_total, $accountlist->cr_total, '==')) {
                                    echo '<td><span class="glyphicon glyphicon-ok-sign"></span></td>';
                                } else {
                                    echo '<td><span class="glyphicon glyphicon-remove-sign"></span></td>';
                                }
                                echo '<td></td>';
                                echo '</tr>';

                                echo '</table>';
                            ?>


                        </div>

                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('page-script')


@endsection



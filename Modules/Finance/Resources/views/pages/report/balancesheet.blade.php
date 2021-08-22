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

    @php
        $financialAccountObj= new \Modules\Finance\Entities\FinancialAccount;
        $functionCore= new \App\Http\Controllers\Helpers\Accounting\FunctionCore;
    @endphp

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <?php if($bsheet['is_opdiff']) {
            echo '<div><div role="alert" class="alert alert-danger">There is a difference in opening balance of'.$functionCore->toCurrency($bsheet['opdiff']['opdiff_balance_dc'], $bsheet['opdiff']['opdiff_balance']).'</div></div>';
        }

        /* Show difference in liabilities and assets total */
        if ($functionCore->calculate($bsheet['final_liabilities_total'], $bsheet['final_assets_total'], '!=')) {
            $final_total_diff = $functionCore->calculate($bsheet['final_liabilities_total'], $bsheet['final_assets_total'], '-');
            echo '<div><div role="alert" class="alert alert-danger">Difference in liabilities and assets '.$functionCore->toCurrency('X', $final_total_diff).'</div></div>';
        }
        ?>

        <div class="row">

            <!-- ./col -->
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Report - Balance Sheet</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="accordion" class="ui-accordion ui-widget ui-helper-reset" role="tablist">
                            <h3 class="ui-accordion-header ui-corner-top ui-state-default ui-accordion-icons ui-accordion-header-collapsed ui-corner-all" role="tab" id="ui-id-1" aria-controls="ui-id-2" aria-selected="false" aria-expanded="false" tabindex="0"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Options</h3>

                            <div class="balancesheet form ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content" id="ui-id-2" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="true" style="height: 135px; display: none;">
                                <form action="http://localhost/accountant/reports/balancesheet" method="post" accept-charset="utf-8">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div style="margin-top: 30px;">
                                                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                                    <input type="checkbox" id="BalancesheetOpening" name="opening" class="checkbox skip" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                                                <label for="BalancesheetOpening">Show Opening Balance Sheet</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Start Date</label>

                                                <div class="input-group">
                                                    <input id="BalancesheetStartdate" type="text" name="startdate" class="form-control hasDatepicker">
                                                    <div class="input-group-addon">
                                                        <i>
                                                            <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave start date as empty if you want statement from the start of the financial year.">
                                                            </div>
                                                        </i>
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>End Date</label>

                                                <div class="input-group">
                                                    <input id="BalancesheetEnddate" type="text" name="enddate" class="form-control hasDatepicker">
                                                    <div class="input-group-addon">
                                                        <i>
                                                            <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave end date as empty if you want statement till the end of the financial year.">
                                                            </div>
                                                        </i>
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="reset" name="reset" class="btn btn-primary  pull-right" value="Clear" style="margin-left: 5px;">
                                        <input type="submit" name="submit" class="btn btn-primary pull-right" value="Submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <br>

                        <div class="btn-group" role="group">
                            <a href="{{URL::to('finance/accounts/reports/balancesheet/download/pdf')}}" class="btn btn-default btn-sm">Export to .PDF</a>
                            <a href="{{URL::to('finance/accounts/reports/balancesheet/download/excel')}}" class="btn btn-default btn-sm">Export to .Excel</a>
                            {{--<a class="btn btn-default btn-sm" onclick="window.print()">Print</a>--}}
                        </div>

                        <div id="section-to-print">
                            <div class="subtitle text-center">
                                Closing Balance Sheet as on 31-Dec-2019 </div>
                            <table>
                                <!-- Liabilities and Assets -->
                                <tr>
                                    <!-- Assets -->
                                    <td class="table-top width-50">
                                        <table class="stripped">
                                            <tr>
                                                <th>Assets</th>
                                                <th class="text-right">Amount(TK)</th>
                                            </tr>
                                            {{$financialAccountObj->account_st_short($bsheet['assets'], $c = -1, $this, 'D')}}
                                        </table>
                                    </td>

                                    <!-- Liabilities -->
                                    <td class="table-top width-50">
                                        <table class="stripped">
                                            <tr>
                                                <th>Liabilities and Owners Equity</th>
                                                <th class="text-right">Amount(TK)</th>
                                            </tr>
                                            {{$financialAccountObj->account_st_short($bsheet['liabilities'], $c = -1, $this, 'C')}}
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <!-- Assets Calculations -->
                                    <td class="table-top width-50">
                                        <div class="report-tb-pad"></div>
                                        <table class="stripped">
                                            <?php
                                            /* Assets Total */
                                            if ($functionCore->calculate($bsheet['assets_total'], 0, '>=')) {
                                                echo '<tr class="bold-text">';
                                                echo '<td>Total Asset</td>';
                                                echo '<td class="text-right">' . $functionCore->toCurrency('D', $bsheet['assets_total']) . '</td>';
                                                echo '</tr>';
                                            } else {
                                                echo '<tr class="dc-error bold-text">';
                                                echo '<td>Total Asset</td>';
                                                echo '<td class="text-right show-tooltip" data-toggle="tooltip" data-original-title="Expecting positive Dr Balance">' . $this->functionscore->toCurrency('D', $bsheet['assets_total']) . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                            <tr class="bold-text">
                                                <?php
                                                /* Net loss */
                                                if ($functionCore->calculate($bsheet['pandl'], 0, '>=')) {
                                                    echo '<td>&nbsp</td>';
                                                    echo '<td>&nbsp</td>';
                                                } else {
                                                    echo '<td>Net Loss</td>';
                                                    $positive_pandl = $functionCore->calculate($bsheet['pandl'], 0, 'n');
                                                    echo '<td class="text-right">' . $functionCore->toCurrency('D', $positive_pandl) . '</td>';
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                            /* Difference in opening balance */
                                            if ($bsheet['is_opdiff']) {
                                                echo '<tr class="bold-text error-text">';
                                                /* If diff in opening balance is Dr */
                                                if ($bsheet['opdiff']['opdiff_balance_dc'] == 'D') {
                                                    echo '<td>Diffrent Opening Balance</td>';
                                                    echo '<td class="text-right">' .$functionCore->toCurrency('D', $bsheet['opdiff']['opdiff_balance']) . '</td>';
                                                } else {
                                                    echo '<td>&nbsp</td>';
                                                    echo '<td>&nbsp</td>';
                                                }
                                                echo '</tr>';
                                            }
                                            ?>

                                            <?php
                                            /* Total */
                                            if ($functionCore->calculate($bsheet['final_liabilities_total'],
                                                $bsheet['final_assets_total'], '==')) {
                                                echo '<tr class="bold-text bg-filled">';
                                            } else {
                                                echo '<tr class="bold-text error-text bg-filled">';
                                            }
                                            echo '<td>Total</td>';
                                            echo '<td class="text-right">' .
                                                $functionCore->toCurrency('D', $bsheet['final_assets_total']) .
                                                '</td>';
                                            echo '</tr>';
                                            ?>
                                        </table>
                                    </td>

                                    <!-- Liabilities Calculations -->
                                    <td class="table-top width-50">
                                        <div class="report-tb-pad"></div>
                                        <table class="stripped">
                                            <?php
                                            /* Liabilities Total */
                                            if ($functionCore->calculate($bsheet['liabilities_total'], 0, '>=')) {
                                                echo '<tr class="bold-text">';
                                                echo '<td>Total Liblity</td>';
                                                echo '<td class="text-right">' . $functionCore->toCurrency('C', $bsheet['liabilities_total']) . '</td>';
                                                echo '</tr>';
                                            } else {
                                                echo '<tr class="dc-error bold-text">';
                                                echo '<td>TOtal Libility</td>';
                                                echo '<td class="text-right show-tooltip" data-toggle="tooltip" data-original-title="Expecting positive Cr balance">' . $this->functionscore->toCurrency('C', $bsheet['liabilities_total']) . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                            <tr class="bold-text">
                                                <?php
                                                /* Net profit */
                                                if ($functionCore->calculate($bsheet['pandl'], 0, '>=')) {
                                                    echo '<td>Net Profit</td>';
                                                    echo '<td class="text-right">' .$functionCore->toCurrency('C', $bsheet['pandl']) . '</td>';
                                                } else {
                                                    echo '<td>&nbsp</td>';
                                                    echo '<td>&nbsp</td>';
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                            /* Difference in opening balance */
                                            if ($bsheet['is_opdiff']) {
                                                echo '<tr class="bold-text error-text">';
                                                /* If diff in opening balance is Cr */
                                                if ($bsheet['opdiff']['opdiff_balance_dc'] == 'C') {
                                                    echo '<td>Different Opening Blance</td>';
                                                    echo '<td class="text-right">' . $functionCore->toCurrency('C', $bsheet['opdiff']['opdiff_balance']) . '</td>';
                                                } else {
                                                    echo '<td>&nbsp</td>';
                                                    echo '<td>&nbsp</td>';
                                                }
                                                echo '</tr>';
                                            }
                                            ?>

                                            <?php
                                            /* Total */
                                            if ($functionCore->calculate($bsheet['final_liabilities_total'],
                                                $bsheet['final_assets_total'], '==')) {
                                                echo '<tr class="bold-text bg-filled">';
                                            } else {
                                                echo '<tr class="bold-text error-text bg-filled">';
                                            }
                                            echo '<td>Total</td>';
                                            echo '<td class="text-right">' .
                                                $functionCore->toCurrency('C', $bsheet['final_liabilities_total']) .
                                                '</td>';
                                            echo '</tr>';
                                            ?>
                                        </table>
                                    </td>
                                </tr>
                            </table>


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



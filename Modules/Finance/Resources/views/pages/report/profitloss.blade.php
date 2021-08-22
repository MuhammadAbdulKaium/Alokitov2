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
     $gross_total = 0;
    $positive_gross_pl = 0;
    $net_expense_total = 0;
    $net_income_total = 0;
    $positive_net_pl = 0;

        $financialAccountObj= new \Modules\Finance\Entities\FinancialAccount;
        $functionCore= new \App\Http\Controllers\Helpers\Accounting\FunctionCore;
    @endphp
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Trading and Profit &amp; Loss Statement</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="accordion" class="ui-accordion ui-widget ui-helper-reset" role="tablist">
                            <h3 class="ui-accordion-header ui-corner-top ui-accordion-header-collapsed ui-corner-all ui-state-default ui-accordion-icons ui-state-hover" role="tab" id="ui-id-1" aria-controls="ui-id-2" aria-selected="false" aria-expanded="false" tabindex="0"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Options</h3>
                            <div class="profitandloss form ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content" id="ui-id-2" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="true" style="display: none; height: 135px;">
                                <form action="http://localhost/accountant/reports/profitloss" method="post" accept-charset="utf-8">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div style="margin-top: 30px;">
                                                <label>
                                                    <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                                        <input type="checkbox" id="ProfitlossOpening" name="opening" class="checkbox skip" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> Show Opening Balance Sheet</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Start Date</label>

                                                <div class="input-group">
                                                    <input id="ProfitlossStartdate" type="text" name="startdate" class="form-control hasDatepicker">
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
                                                    <input id="ProfitlossEnddate" type="text" name="enddate" class="form-control hasDatepicker">
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
                            <a href="{{URL::to('finance/accounts/reports/profitloss/download/pdf')}}" class="btn btn-default btn-sm">Export to .PDF</a>
                            <a href="{{URL::to('finance/accounts/reports/profitloss/download/excel')}}" class="btn btn-default btn-sm">Export to .CSV</a>
                            {{--<a class="btn btn-default btn-sm" onclick="window.print()">Print</a>--}}
                        </div>

                        <div id="section-to-print">
                            <div class="subtitle text-center">
                                Closing Trading and Profit &amp; Loss as on 31-Dec-2019 </div>
                            <table>

                                <tr>
                                    <!-- Gross Expenses -->
                                    <td class="table-top width-50">
                                        <table class="stripped">
                                            <tr>
                                                <th>Gross Expenses</th>
                                                <th class="text-right">Amount TK</th>
                                            </tr>
                                            {{$financialAccountObj->account_st_short($pandl['gross_expenses'], $c = -1, $this, 'D')}}
                                        </table>
                                    </td>

                                    <!-- Gross Incomes -->
                                    <td class="table-top width-50">
                                        <table class="stripped">
                                            <tr>
                                                <th>Gross Income</th>
                                                <th class="text-right">Amount TK</th>
                                            </tr>
                                            {{$financialAccountObj->account_st_short($pandl['gross_incomes'], $c = -1, $this, 'C')}}
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="table-top width-50">
                                        <div class="report-tb-pad"></div>
                                        <table class="stripped">
                                            <?php
                                            /* Gross Expense Total */
                                            $gross_total = $pandl['gross_expense_total'];
                                            if ($functionCore->calculate($pandl['gross_expense_total'], 0, '>=')) {
                                                echo '<tr class="bold-text">';
                                                echo '<td>Total Gross Expense</td>';
                                                echo '<td class="text-right">' . $functionCore->toCurrency('D', $pandl['gross_expense_total']) . '</td>';
                                                echo '</tr>';
                                            } else {
                                                echo '<tr class="dc-error bold-text">';
                                                echo '<td>Total Gross Expense</td>';
                                                echo '<td class="text-right show-tooltip" data-toggle="tooltip" data-original-title="Expecting Dr Balance">' . $this->functionscore->toCurrency('D', $pandl['gross_expense_total']) . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                            <tr class="bold-text">
                                                <?php
                                                /* Gross Profit C/D */
                                                if ($functionCore->calculate($pandl['gross_pl'], 0, '>=')) {
                                                    echo '<td>Gross Profit C/D</td>';
                                                    echo '<td class="text-right">' . $functionCore->toCurrency('', $pandl['gross_pl']) . '</td>';
                                                    $gross_total = $functionCore->calculate($gross_total, $pandl['gross_pl'], '+');
                                                } else {
                                                    echo '<td>&nbsp</td>';
                                                    echo '<td>&nbsp</td>';
                                                }
                                                ?>
                                            </tr>
                                            <tr class="bold-text bg-filled">
                                                <td>Total</td>
                                                <td class="text-right"><?php echo $functionCore->toCurrency('D', $gross_total); ?></td>
                                            </tr>
                                        </table>
                                    </td>

                                    <td class="table-top width-50">
                                        <div class="report-tb-pad"></div>
                                        <table class="stripped">
                                            <?php
                                            /* Gross Income Total */
                                            $gross_total = $pandl['gross_income_total'];
                                            if ($functionCore->calculate($pandl['gross_income_total'], 0, '>=')) {
                                                echo '<tr class="bold-text">';
                                                echo '<td>Gross Incomes </td>';
                                                echo '<td class="text-right">' . $functionCore->toCurrency('C', $pandl['gross_income_total']) . '</td>';
                                                echo '</tr>';
                                            } else {
                                                echo '<tr class="dc-error bold-text">';
                                                echo '<td>Gross Incomes</td>';
                                                echo '<td class="text-right show-tooltip" data-toggle="tooltip" data-original-title="Expecting Cr Balance">' . $this->functionscore->toCurrency('C', $pandl['gross_income_total']) . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                            <tr class="bold-text">
                                                <?php
                                                /* Gross Loss C/D */
                                                if ($functionCore->calculate($pandl['gross_pl'], 0, '>=')) {
                                                    echo '<td>&nbsp</td>';
                                                    echo '<td>&nbsp</td>';
                                                } else {
                                                    echo '<td>Gross Loss C/D</td>';
                                                    $positive_gross_pl = $functionCore->calculate($pandl['gross_pl'], 0, 'n');
                                                    echo '<td class="text-right">' . $functionCore->toCurrency('', $positive_gross_pl) . '</td>';
                                                    $gross_total = $functionCore->calculate($gross_total, $positive_gross_pl, '+');
                                                }
                                                ?>
                                            </tr>
                                            <tr class="bold-text bg-filled">
                                                <td>Total</td>
                                                <td class="text-right"><?php echo $functionCore->toCurrency('C', $gross_total); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Net Profit and Loss -->
                                <tr>
                                    <td class="table-top width-50">
                                        <div class="report-tb-pad"></div>
                                        <table class="stripped">
                                            <tr>
                                                <th>Net Expenses</th>
                                                <th class="text-right">Amount TK</th>
                                            </tr>
                                            {{$financialAccountObj->account_st_short($pandl['net_expenses'], $c = -1, $this, 'D')}}
                                        </table>
                                    </td>

                                    <td class="table-top width-50">
                                        <div class="report-tb-pad"></div>
                                        <table class="stripped">
                                            <tr>
                                                <th>Net Incomes</th>
                                                <th class="text-right">Amount TK</th>
                                            </tr>
                                            {{$financialAccountObj->account_st_short($pandl['net_incomes'], $c = -1, $this, 'C')}}
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="table-top width-50">
                                        <div class="report-tb-pad"></div>
                                        <table class="stripped">
                                            <?php
                                            /* Net Expense Total */
                                            $net_expense_total = $pandl['net_expense_total'];
                                            if ($functionCore->calculate($pandl['net_expense_total'], 0, '>=')) {
                                                echo '<tr class="bold-text">';
                                                echo '<td>Total Expenses</td>';
                                                echo '<td class="text-right">' . $functionCore->toCurrency('D', $pandl['net_expense_total']) . '</td>';
                                                echo '</tr>';
                                            } else {
                                                echo '<tr class="dc-error bold-text">';
                                                echo '<td>Total Expenses</td>';
                                                echo '<td class="text-right show-tooltip" data-toggle="tooltip" data-original-title="Expecting Dr Balance">' . $this->functionscore->toCurrency('D', $pandl['net_expense_total']) . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                            <tr class="bold-text">
                                                <?php
                                                /* Gross Loss B/D */
                                                if ($functionCore->calculate($pandl['gross_pl'], 0, '>=')) {
                                                    echo '<td>&nbsp</td>';
                                                    echo '<td>&nbsp</td>';
                                                } else {
                                                    echo '<td>Gross Loss B/D</td>';
                                                    $positive_gross_pl = $functionCore->calculate($pandl['gross_pl'], 0, 'n');
                                                    echo '<td class="text-right">' . $functionCore->toCurrency('', $positive_gross_pl) . '</td>';
                                                    $net_expense_total =$functionCore->calculate($net_expense_total, $positive_gross_pl, '+');
                                                }
                                                ?>
                                            </tr>
                                            <tr class="bold-text ok-text">
                                                <?php
                                                /* Net Profit */
                                                if ($functionCore->calculate($pandl['net_pl'], 0, '>=')) {
                                                    echo '<td>Net Profit</td>';
                                                    echo '<td class="text-right">' .$functionCore->toCurrency('', $pandl['net_pl']) . '</td>';
                                                    $net_expense_total =$functionCore->calculate($net_expense_total, $pandl['net_pl'], '+');
                                                } else {
                                                    echo '<td>&nbsp</td>';
                                                    echo '<td>&nbsp</td>';
                                                }
                                                ?>
                                            </tr>
                                            <tr class="bold-text bg-filled">
                                                <td>Total</td>
                                                <td class="text-right"><?php echo $functionCore->toCurrency('D', $net_expense_total); ?></td>
                                            </tr>
                                        </table>
                                    </td>

                                    <td class="table-top width-50">
                                        <div class="report-tb-pad"></div>
                                        <table class="stripped">
                                            <?php
                                            /* Net Income Total */
                                            $net_income_total = $pandl['net_income_total'];
                                            if ($functionCore->calculate($pandl['net_income_total'], 0, '>=')) {
                                                echo '<tr class="bold-text">';
                                                echo '<td>Net Income Total</td>';
                                                echo '<td class="text-right">' . $functionCore->toCurrency('C', $pandl['net_income_total']) . '</td>';
                                                echo '</tr>';
                                            } else {
                                                echo '<tr class="dc-error bold-text">';
                                                echo '<td>Net Income Total</td>';
                                                echo '<td class="text-right show-tooltip" data-toggle="tooltip" data-original-title="Expecting Cr Balance">' .$functionCore->toCurrency('C', $pandl['net_income_total']) . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                            <tr class="bold-text">
                                                <?php
                                                /* Gross Profit B/D */
                                                if ($functionCore->calculate($pandl['gross_pl'], 0, '>=')) {
                                                    $net_income_total = $functionCore->calculate($net_income_total, $pandl['gross_pl'], '+');
                                                    echo '<td>Gross Profit B/D </td>';
                                                    echo '<td class="text-right">' .  $functionCore->toCurrency('', $pandl['gross_pl']) . '</td>';
                                                } else {
                                                    echo '<td>&nbsp</td>';
                                                    echo '<td>&nbsp</td>';
                                                }
                                                ?>
                                            </tr>
                                            <tr class="bold-text ok-text">
                                                <?php
                                                /* Net Loss */
                                                if ($functionCore->calculate($pandl['net_pl'], 0, '>=')) {
                                                    echo '<td>&nbsp</td>';
                                                    echo '<td>&nbsp</td>';
                                                } else {
                                                    echo '<td>Net Loss</td>';
                                                    $positive_net_pl = $functionCore->calculate($pandl['net_pl'], 0, 'n');
                                                    echo '<td class="text-right">' . $functionCore->toCurrency('', $positive_net_pl) . '</td>';
                                                    $net_income_total = $functionCore->calculate($net_income_total, $positive_net_pl, '+');
                                                }
                                                ?>
                                            </tr>
                                            <tr class="bold-text bg-filled">
                                                <td>Total</td>
                                                <td class="text-right"><?php echo$functionCore->toCurrency('C', $net_income_total); ?></td>
                                            </tr>
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



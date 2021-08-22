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
        $functionCore= new \App\Http\Controllers\Helpers\Accounting\FunctionCore;
    @endphp

    <!-- Main content -->

    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Report - Ledger Statement</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- <div id="accordion"> -->
                        <!-- <h3>Options</h3> -->
                        <div class="balancesheet form">
                            <form action="{{URL::to('finance/accounts/reports/ledgerstatement')}}" method="post" accept-charset="utf-8">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ledger Account</label>
                                        <select class="form-control" id="ReportLedgerId" name="ledger_id">
                                            <?php foreach ($ledgers as $id => $ledger): ?>
                                                <option value="<?= $id; ?>" <?= ($id < 0) ? 'disabled' : "" ?> <?= (($input_ledger== $id)) ?'selected':''?>><?= $ledger; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <div class="input-group">
                                            <input id="ReportStartdate" type="text" @if(!empty($input_start_date)) value="{{date('d-m-Y',strtotime($input_start_date))}}" @endif name="startdate" class="form-control">
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>End Date</label>

                                        <div class="input-group">
                                            <input id="ReportEnddate" type="text" name="enddate" @if(!empty($input_end_date)) value="{{date('d-m-Y',strtotime($input_end_date))}}" @endif  class="form-control">
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
                                <input type="reset" name="reset" class="btn btn-primary pull-right" style="margin-left: 5px;" value="Clear">
                                <input type="submit" name="submit" class="btn btn-primary pull-right" value="Submit">

                                <?php
                                if (!empty($input_ledger)){
                                $get = '?id='. $input_ledger;

                                if (!empty($input_start_date)) {
                                    $get .= '&startdate='. $input_start_date;

                                }
                                if (!empty($input_end_date)) {
                                    $get .= "&enddate=". $input_end_date;
                                }
                                ?>
                                <a href="{{URL::to('finance/accounts/reports/export_ledgerstatement')}}{{$get}}&type=excel" class="btn btn-primary pull-right">Report Excel</a>
                                <a href="{{URL::to('finance/accounts/reports/export_ledgerstatement')}}{{$get}}&type=pdf" class="btn btn-primary pull-right">Report PDF</a>
                                <?php
                                }
                                ?>
                            </div>



                            </form>
                        </div>
                        <!-- </div> -->
                        <?php if ($showEntries) :  ?>
                        <div class="subtitle">
                            <?php //echo $subtitle; ?>
                        </div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-6">
                                <table class="summary stripped table-condensed">
                                    <tr>
                                        <td class="td-fixwidth-summary"><?php echo ('Bank or cash account'); ?></td>
                                        <td>

                                            <?php
                                            echo ($ledger_data['type'] == 1) ? 'Yes' : 'No';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-fixwidth-summary"><?php echo ('Notes'); ?></td>
                                        <td><?php echo ($ledger_data['notes']); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="summary stripped table-condensed">
                                    <tr>
                                        <td class="td-fixwidth-summary">Opening Balance </td>
                                        <td><?php echo $functionCore->toCurrency($op['dc'], $op['amount']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-fixwidth-summary">Closing Balance</td>
                                        <td><?php echo $functionCore->toCurrency($cl['dc'], $cl['amount']+$op['amount']); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <table class="stripped">

                            <tr>
                                <th>Date</th>
                                <th>Number</th>
                                <th>Ledger</th>
                                <th>Type</th>
                                <th>Tag</th>
                                <th>Dr Amount (৳)</th>
                                <th>Cr Amount (৳)</th>
                                <th>Balance (৳)</th>
                                <th>Actions</th>
                            </tr>

                            <?php
                            /* Current opening balance */
                            $entry_balance['amount'] = $current_op['amount'];
                            $entry_balance['dc'] = $current_op['dc'];
                            echo '<tr class="tr-highlight">';
                            echo '<td colspan="7">';
                            echo 'Current opening balance';
                            echo '</td>';
                            echo '<td>' . $functionCore->toCurrency($current_op['dc'], $current_op['amount']) . '</td>';
                            echo '<td></td>';
                            echo '</tr>';
                            ?>

                            <?php
                            /* Show the entries table */
                            foreach ($entries as $entry) {
                            /* Calculate current entry balance */
                            $entry_balance = $functionCore->calculate_withdc(
                                $entry_balance['amount'], $entry_balance['dc'],
                                $entry->amount, $entry->dc
                            );
                            // get entry Type name and label
//                            $et =$entry->getEntryTypeById($entry->id);
                            $entryTypeName = 'Payment';
                            $entryTypeLabel = 'Recipt';

                            /* Negative balance if its a cash or bank account and balance is Cr */
                            if ($ledger_data['type'] == 1) {
                                if ($entry_balance['dc'] == 'C' && $entry_balance['amount'] != '0.00') {
                                    echo '<tr class="error-text">';
                                } else {
                                    echo '<tr>';
                                }
                            } else {
                                echo '<tr>';
                            }

                            echo '<td>' . date($entry->date). '</td>';
                            echo '<td>' . $entry->number. '</td>';
                            echo '<td>' . ($functionCore->entryLedgers($entry->id)) . '</td>';
                            echo '<td>' . ($entryTypeName) . '</td>';
                            echo '<td>Tag Name</td>';
                            if ($entry->dc == 'D') {
                                echo '<td>' . $functionCore->toCurrency('D', $entry->amount) . '</td>';
                                echo '<td>' . '</td>';
                            } else if ($entry->dc == 'C') {
                                echo '<td>' . '</td>';
                                echo '<td>' . $functionCore->toCurrency('C', $entry->amount) . '</td>';
                            } else {
                                echo '<td>Search Amount Error</td>';
                                echo '<td>Search Amount Error</td>';
                            }

                            echo '<td>' . $functionCore->toCurrency($entry_balance['dc'], $entry_balance['amount']) . '</td>';

                            echo '<td>';
                            ?>
                            <a href="entries/view/<?= ($entryTypeLabel); ?>/<?= $entry->id; ?>" class="no-hover" escape="false"><i class="glyphicon glyphicon-log-in"></i>View</a>
                            <span class="link-pad"></span>
                            <a href="entries/edit/<?= ($entryTypeLabel); ?>/<?= $entry->id; ?>" class="no-hover" escape="false"><i class="glyphicon glyphicon-edit"></i>Edit</a>
                            <span class="link-pad"></span>
                            <a href="entries/delete/<?= ($entryTypeLabel); ?>/<?= $entry->id; ?>" class="no-hover" escape="false"><i class="glyphicon glyphicon-trash"></i>Delete</a>

                            <?php
                            echo '</td>';
                            echo '</tr>';
                            }
                            ?>

                            <?php
                            /* Current closing balance */
                            echo '<tr class="tr-highlight">';
                            echo '<td colspan="7">';
                            echo 'Current closing balance';
                            echo '</td>';
                            echo '<td>' . $functionCore->toCurrency($entry_balance['dc'], $entry_balance['amount']) . '</td>';
                            echo '<td></td>';
                            echo '</tr>';
                            ?>

                        </table>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- /.row -->
    </section>

    <!-- /.content -->
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function() {

            /* Calculate date range in javascript */
            $('#ReportStartdate').datepicker({format: 'dd-mm-yyyy',todayHighlight: true});
            $('#ReportEnddate').datepicker({format: 'dd-mm-yyyy',todayHighlight: true});
            $("#ReportLedgerId").select2({width:'100%'});
        });
    </script>

@endsection



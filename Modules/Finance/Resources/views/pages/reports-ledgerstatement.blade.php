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
            <style type="text/css">
                .datepicker{z-index:100002 !important;}
            </style>
            <script type="text/javascript">
                $(document).ready(function() {

                    /* Calculate date range in javascript */
                    startDate = new Date(1546174800000  + (new Date().getTimezoneOffset() * 60 * 1000));
                    endDate = new Date(1577797199000  + (new Date().getTimezoneOffset() * 60 * 1000));

                    /* On selecting custom period show the start and end date form fields */
                    $('#ReportCustomPeriod').change(function() {
                        if ($(this).prop('checked')) {
                            $('#ledgerst-period').show();
                        } else {
                            $('#ledgerst-period').hide();
                        }
                    });
                    $('#ReportCustomPeriod').trigger('change');

                    /* Setup jQuery datepicker ui */
                    $('#ReportStartdate').datepicker({
                        minDate: startDate,
                        maxDate: endDate,
                        dateFormat:'dd-mm-yy',
                        numberOfMonths: 1,
                        onClose: function(selectedDate) {
                            if (selectedDate) {
                                $("#ReportEnddate").datepicker("option", "minDate", selectedDate);
                            } else {
                                $("#ReportEnddate").datepicker("option", "minDate", startDate);
                            }
                        }
                    });
                    $('#ReportEnddate').datepicker({
                        minDate: startDate,
                        maxDate: endDate,
                        dateFormat: 'dd-mm-yy',
                        numberOfMonths: 1,
                        onClose: function(selectedDate) {
                            if (selectedDate) {
                                $("#ReportStartdate").datepicker("option", "maxDate", selectedDate);
                            } else {
                                $("#ReportStartdate").datepicker("option", "maxDate", endDate);
                            }
                        }
                    });


                    $('#ledger_start_date').datepicker({
                        dateFormat:'dd-mm-yy',
                        numberOfMonths: 1,
                        onClose: function(selectedDate) {
                            if (selectedDate) {
                                $("#ledger_end_date").datepicker("option", "minDate", selectedDate);
                            } else {
                                $("#ledger_end_date").datepicker("option", "minDate", startDate);
                            }
                        }
                    });
                    $('#ledger_end_date').datepicker({
                        dateFormat: 'dd-mm-yy',
                        numberOfMonths: 1,
                        onClose: function(selectedDate) {
                            if (selectedDate) {
                                $("#ledger_start_date").datepicker("option", "maxDate", selectedDate);
                            } else {
                                $("#ledger_start_date").datepicker("option", "maxDate", endDate);
                            }
                        }
                    });
                    $('.export-ledger-summary-data').unbind();
                    $('.export-ledger-summary-data').on('click',function(){
                        $('#ledger_end_date').datepicker('setDate', null);
                        $("#ledger_start_date").datepicker("setDate", null);
                        $("#ledger_end_date").datepicker("option", "minDate", null);
                        $("#ledger_start_date").datepicker("option", "maxDate", null);
                    });
                    $('.ledgerExportReport').unbind();
                    $('.ledgerExportReport').on('click',function(){
                        var startDate = $('#ledger_start_date').val().trim();
                        var endDate = $('#ledger_end_date').val().trim();
                        if(startDate != '' && endDate !=''){
                            window.location.href = $('#ledgerSummaryExportModal').data('exportUrl')+"?startDate="+startDate+'&endDate='+endDate;
                        }
                        else{
                            alertMessage.display( 'danger', 'Please select start date and end date.' , false);
                        }
                    });
                });
            </script>

            <div class="ledgerstatement form col-md-5 col-sm-7 col-xs-8">
                <form action="/finance/reports/ledgerstatement" id="ReportLedgerstatementForm" method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"><input type="hidden" name="data[_Token][key]" value="2454794d945471b5a79e6e844094e19ab3b88327" id="Token749650567"></div><div class="form-group"><label for="ReportLedgerId">Ledger account</label><select name="data[Report][ledger_id]" class="form-control" id="ReportLedgerId">
                            <option value="2">Bank Asia</option>
                            <option value="1">Fees</option>
                        </select></div><div class="form-group"><div class="checkbox"><input type="hidden" name="data[Report][custom_period]" id="ReportCustomPeriod_" value="0"><label for="ReportCustomPeriod"><input type="checkbox" name="data[Report][custom_period]" class="checkbox" value="1" id="ReportCustomPeriod"> Change default period</label></div></div><fieldset id="ledgerst-period" style="display: none;"><div class="form-group"><label for="ReportStartdate">Start date</label><input name="data[Report][startdate]" class="form-control hasDatepicker" type="text" id="ReportStartdate"></div><div class="form-group"><label for="ReportEnddate">End date</label><input name="data[Report][enddate]" class="form-control hasDatepicker" type="text" id="ReportEnddate"></div></fieldset><div class="form-group"><input class="btn btn-primary" type="submit" value="Submit"><span class="link-pad"></span><a href="/finance/reports/ledgerstatement" class="btn btn-default">Clear</a></div><div style="display:none;"><input type="hidden" name="data[_Token][fields]" value="183161bcc559b2cbc31c44148ef1aad06af29eae%3A" id="TokenFields289013985"><input type="hidden" name="data[_Token][unlocked]" value="" id="TokenUnlocked912437004"></div></form></div>
            <div class="col-md-2 col-sm-3 col-xs-4 pull-right text-right">
                <a href="javascript:void(0);" class="text-info export-ledger-summary-data" style="padding: 20px 0px;" data-toggle="modal" data-target="#ledgerSummaryExportModal">Export</a>
            </div>
            <div class="clearfix"></div>
            <!-- Model for export ledger data -->
            <div class="modal fade" id="ledgerSummaryExportModal" role="dialog" aria-labelledby="myModalLabel" data-export-url="/finance/reports/ledgerCustReport" aria-hidden="true" style="display: none;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="javascript:void(0);" onsubmit="return false;" id="ledgersExportRpt" method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"><input type="hidden" name="data[_Token][key]" value="2454794d945471b5a79e6e844094e19ab3b88327" id="Token314476678"></div>	    <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="myModalLabel">Ledger Summary Report </h4>
                            </div>
                            <div class="modal-body" style="padding: 35px 30px;">
                                <div class="form-group">
                                    <div class="col-md-3" style="text-align: right; vertical-align: middle">
                                        <label class="control-label">Start Date</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="input text"><input name="start_date" class="form-control hasDatepicker" id="ledger_start_date" placeholder="Start date" autocomplete="off" type="text"></div>                        <p id="sche_start_date_error" class="has-error help-block" style="display: none;">Please select end date</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3" style="text-align: right; vertical-align: middle">
                                        <label class="control-label">End Date</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="input text"><input name="end_date" class="form-control hasDatepicker" id="ledger_end_date" placeholder="End date" autocomplete="off" type="text"></div>                        <p id="sche_end_date_error" class="has-error help-block" style="display: none;">Please select end date</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" class="btn btn-primary ledgerExportReport" value="Submit">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                            <div style="display:none;"><input type="hidden" name="data[_Token][fields]" value="b0128389bd069adabea061410e03cf8255594a86%3A" id="TokenFields1526508547"><input type="hidden" name="data[_Token][unlocked]" value="" id="TokenUnlocked1439262449"></div></form>        </div>
                </div>
            </div>
            <!-- Model for export ledger data -->

        </div>
    </div>
@endsection
@section('page-script')
@endsection
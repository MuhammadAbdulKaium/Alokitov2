@extends('fees::layouts.fees_report_master')
@section('section-title')
<h1><i class="fa fa-plus-square"></i>Daily Fees Collection</h1>

@endsection
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="box box-solid">
        <div class="box-header with-border">
            <h4 class="box-title"><i class="fa fa-filter"></i> Search</h4>
        </div><!--./box-header-->
        <form id="feesPaymentTransaction" action="/fees/report/date-wise-fees/" method="get">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            @if(!empty($allInputs))
            <input type="hidden" name="request_type" value="pdf">
            @endif
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">

                        <div class="form-group field-feespaymenttransactionsearch-fp_sdate required">
                            <label class="control-label" for="feespaymenttransactionsearch-fp_sdate">Start Date</label>
                            <input type="text" id="fpt_search_start_date" value="@if(!empty($allInputs->search_start_date)) {{date('d-m-Y',strtotime($allInputs->search_start_date))}}  @endif" class="form-control" name="fpt_search_start_date" placeholder="Start Date">


                            <div class="help-block"></div>
                        </div>			</div>
                    <div class="col-sm-3">
                        <div class="form-group field-feespaymenttransactionsearch-fp_edate required">
                            <label class="control-label" for="feespaymenttransactionsearch-fp_edate">End Date</label>
                            <input type="text" id="fpt_search_end_date" class="form-control" name="fpt_search_end_date" value="@if(!empty($allInputs->search_end_date)) {{date('d-m-Y',strtotime($allInputs->search_end_date))}}  @endif" placeholder="Start Date">


                            <div class="help-block"></div>
                        </div>			</div>
                    <div class="col-sm-3">
                        <div class="form-group field-feespaymenttransactionsearch-fees_pay_tran_mode">
                            <label class="control-label" for="feespaymenttransactionsearch-fees_pay_tran_mode">Payment Method</label>
                            <select id="payment_method" class="form-control" name="payment_method">
                                <option value="">---- Select Payment Method ----</option>
                                    @if($paymentMethods)
                                            @foreach($paymentMethods as $paymentmethod)
                                        @if(!empty($allInputs))
                                            @if ($allInputs->payment_method_id == $paymentmethod->id)
                                                <option value="{{$paymentmethod->id}}" selected>{{$paymentmethod->method_name}}</option>
                                            @endif
                                        @endif
                                        <option value="{{$paymentmethod->id}}">{{$paymentmethod->method_name}}</option>

                                                @endforeach
                                        @endif

                            </select>

                            <div class="help-block"></div>
                        </div>			</div>
                    {{--<div class="col-sm-3">--}}
                        {{--<div class="form-group field-feespaymenttransactionsearch-fees_pay_tran_type">--}}
                            {{--<label class="control-label" for="feespaymenttransactionsearch-fees_pay_tran_type">Payment Type</label>--}}
                            {{--<select id="feespaymenttransactionsearch-fees_pay_tran_type" class="form-control" name="FeesPaymentTransactionSearch[fees_pay_tran_type]">--}}
                                {{--<option value="">---- Select Payment Type ----</option>--}}
                                {{--<option value="0">General</option>--}}
                                {{--<option value="1">Down Payment</option>--}}
                                {{--<option value="2">Other Fees</option>--}}
                            {{--</select>--}}

                            {{--<div class="help-block"></div>--}}
                        {{--</div>			--}}
                    {{--</div>--}}
                </div>
                <div class="row">
                    {{--<div class="col-sm-3">--}}
                        {{--<div class="form-group field-feespaymenttransactionsearch-fees_pay_tran_other_id">--}}
                            {{--<label class="control-label" for="feespaymenttransactionsearch-fees_pay_tran_other_id">Other Fees</label>--}}
                            {{--<select id="feespaymenttransactionsearch-fees_pay_tran_other_id" class="form-control" name="FeesPaymentTransactionSearch[fees_pay_tran_other_id]" disabled="">--}}
                                {{--<option value="">---- Select Fees Type ----</option>--}}
                            {{--</select>--}}

                            {{--<div class="help-block"></div>--}}
                        {{--</div>			</div>--}}
                </div>


            </div>
            <div class="box-footer">
                <button type="submit"  class="btn btn-primary btn-create">Search</button>		<a class="btn btn-default btn-create" href="/report/fees/date-wise-fees">Cancel</a>	</div>
        </form>
    </div>

        @if(!empty($fpt_list))
    <div id="transaction_list_row" class="row">
        <div class="box box-solid">
            <div class="et">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> View Student Payment List</h3>
                    <div class="box-tools">
                        {{--<a id="fees_payment_transaction_excel" class="btn btn-info btn-sm"><i class="fa fa-file-excel-o"></i> Excel</a>--}}
                        @if(!empty($allInputs))
                            @php
                                $start_date=date('Y-m-d',strtotime($allInputs->search_start_date));
                                $end_date=date('Y-m-d',strtotime($allInputs->search_end_date));
                            @endphp
                                <button type="button" id="get_fees_collection_pdf"  data-key="pdf" class="btn btn-info btn-sm download-report"><i class="fa fa-file-excel-o"></i> PDF</button>
                                <button type="button" id="get_fees_collection_excel" data-key="xlxs"  class="btn btn-info btn-sm download-report"><i class="fa fa-file-excel-o"></i> Excel</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="box-header">
                </div>
                <div class="box-body">
                    <table id="fptList" class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Date.</th>
                            <th>Student ID</th>
                            <th>GR No.</th>
                            <th width="40%">Name</th>
                            <th>Payment Type</th>
                            <th>Payment Method</th>
                            <th>Receipt No</th>
                            <th>Bank</th>
                            <th>Check Date</th>
                            <th>Check NO</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $totalAmount=0; $i = 1; @endphp

                        @foreach($feesListWithoutPaginate as $payment)
                            @php
                                $totalAmount+=$payment->payment_amount;
                            @endphp

                            @endforeach
                        @foreach($fpt_list as $payment)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$payment->payment_date}}</td>
                                <td>{{$payment->invoice()->payer_id}}</td>
                                <td></td>
                                @php $std=$payment->invoice()->payer() @endphp
                                <td>{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}</td>
                                <td></td>
                                <td>{{$payment->payment_method()->method_name}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td >{{$payment->payment_amount}}</td>
                            </tr>
                        @endforeach
                        <tfoot>
                        <tr>
                            <th id="total" style="text-align: right" colspan="11">Total :</th>
                            <td>{{$totalAmount}}</td>
                        </tr>
                        </tfoot>
                    </table>
                    <div class="feesPayment-pagination" style="float: right">
                        {!! $fpt_list->appends(Request::only([
                        'search'=>'search',
                        'filter'=>'filter',
                        'payment_method'=>'payment_method',
                        'fpt_search_start_date'=>'fpt_search_start_date',
                        'fpt_search_end_date'=>'fpt_search_end_date',
                        ]))->render() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif




@endsection

@section('page-script')

    {{--<script>--}}

    $(".download-report").click(function(){
    var report_type = $(this).attr('data-key');
    // dynamic html form
    $('<form id="get_fees_collection_form" action="/fees/report/daily-fees-report" method="get" style="display:none;"></form>')
    .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
    .append('<input type="hidden" name="fpt_search_start_date" value="'+$('#fpt_search_start_date').val()+'"/>')
    .append('<input type="hidden" name="fpt_search_end_date" value="'+$('#fpt_search_end_date').val()+'"/>')
    .append('<input type="hidden" name="payment_method" value="'+$('#payment_method').val()+'"/>')
    .append('<input type="hidden" name="report_type" value="'+report_type+'"/>')
    // append to body and submit the form
    .appendTo('body').submit();
    // remove form from the body
    $('#get_fees_collection_form').remove();
    });




    $('#fpt_search_start_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
    $('#fpt_search_end_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});




@endsection


@extends('fees::layouts.fees_report_master')
@section('section-title')
    <h1><i class="fa fa-plus-square"></i>Due Date Fees Report</h1>

@endsection
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="box box-solid">
        <div class="box-header with-border">
            <h4 class="box-title"><i class="fa fa-filter"></i> Search</h4>
        </div><!--./box-header-->
        <form id="due-date-fees-report" action="/fees/report/due-date-fees-report/" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            @if(!empty($allInputs))
                <input type="hidden" name="request_type" value="pdf">
            @endif
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">

                        <div class="form-group field-due-date-fees-reportsearch-fp_sdate required">
                            <label class="control-label" for="due-date-fees-reportsearch-fp_sdate">Start Date</label>
                            <input type="text" id="search_start_date" value="@if(!empty($allInputs->search_start_date)) {{date('d-m-Y',strtotime($allInputs->search_start_date))}}  @endif" class="form-control" name="search_start_date" placeholder="Start Date">

                            <div class="help-block"></div>
                        </div>			</div>
                    <div class="col-sm-3">
                        <div class="form-group field-due-date-fees-reportsearch-fp_edate required">
                            <label class="control-label" for="due-date-fees-reportsearch-fp_edate">End Date</label>
                            <input type="text" id="search_end_date" class="form-control" name="search_end_date" value="@if(!empty($allInputs->search_end_date)) {{date('d-m-Y',strtotime($allInputs->search_end_date))}}  @endif" placeholder="Start Date">


                            <div class="help-block"></div>
                        </div>			</div>
                    <div class="col-sm-3">
                        <div class="form-group field-due-date-fees-reportsearch-fees_pay_tran_mode">
                            <label class="control-label" for="due-date-fees-reportsearch-fees_pay_tran_mode">Payment Method</label>
                            <select id="invoice_status" class="form-control" name="invoice_status">
                                <option value="">Select Payment</option>
                                <option value="1" @if (!empty($allInputs) && ($allInputs->invoice_status =="1")) selected="selected" @endif>Paid</option>
                                <option value="2" @if (!empty($allInputs) && ($allInputs->invoice_status =="2")) selected="selected" @endif >Un Paid</option>
                                <option value="3" @if (!empty($allInputs) && ($allInputs->invoice_status =="3")) selected="selected" @endif >Parital Paid</option>
                            </select>

                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group field-due-date-fees-reportsearch-fees_pay_tran_type">
                            <label class="control-label" for="due-date-fees-reportsearch-fees_pay_tran_type">Payment Type</label>
                            {{--<select id="due-date-fees-reportsearch-fees_pay_tran_type" class="form-control" name="due-date-fees-reportSearch[fees_pay_tran_type]">--}}
                            {{--<option value="">---- Select Payment Type ----</option>--}}
                            {{--<option value="0">General</option>--}}
                            {{--<option value="1">Down Payment</option>--}}
                            {{--<option value="2">Other Fees</option>--}}
                            {{--</select>--}}

                            <div class="help-block"></div>
                        </div>			</div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group field-due-date-fees-reportsearch-fees_pay_tran_other_id">
                            <label class="control-label" for="due-date-fees-reportsearch-fees_pay_tran_other_id">Other Fees</label>
                            {{--<select id="due-date-fees-reportsearch-fees_pay_tran_other_id" class="form-control" name="due-date-fees-reportSearch[fees_pay_tran_other_id]" disabled="">--}}
                            {{--<option value="">---- Select Fees Type ----</option>--}}
                            {{--</select>--}}

                            <div class="help-block"></div>
                        </div>			</div>
                </div>


            </div>
            <div class="box-footer">
                <button type="submit"  class="btn btn-primary btn-create">Search</button>		<a class="btn btn-default btn-create" href="/report/fees/date-wise-fees">Cancel</a>	</div>
        </form>
    </div>

    @if(!empty($searchInvoice))
        @php $feesinvoices=$invoiceList; @endphp

    @if($feesinvoices->count()>0)

        <div class="box box-solid">
            <div class="et">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> View Student Payment Status</h3>
                    <div class="box-tools">
                        <button type="button" id="get_fees_collection_pdf" data-key="pdf" class="btn btn-info btn-sm download-report"><i class="fa fa-file-excel-o"></i> PDF</button>
                        <button type="button" id="get_fees_collection_excel" data-key="xlxs" class="btn btn-info btn-sm download-report"><i class="fa fa-file-excel-o"></i> Excel</button>
                    </div>
                </div>
            </div>

        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="feesListTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Invoice Id</th>
                            <th><a  data-sort="sub_master_code">Fee Name</a></th>
                            <th><a  data-sort="sub_master_alias">Payer Name</a></th>
                            <th><a  data-sort="sub_master_alias">Fees Amount</a></th>
                            <th><a  data-sort="sub_master_alias">Discount</a></th>
                            <th><a  data-sort="sub_master_alias">Paid Amount</a></th>
                            <th><a  data-sort="sub_master_alias">Status</a></th>
                            <th><a  data-sort="sub_master_alias">Informed?</a></th>
                            <th><a>Action</a></th>
                        </tr>

                        </thead>
                        <tbody>

                        @php

                            $i = 1
                        @endphp
                        @foreach($feesinvoices as $invoice)

                            <tr class="gradeX">
                                <td>{{$i++}}</td>
                                @php
                                    $fees=$invoice->fees();
                                    $std=$invoice->payer();
                                @endphp
                                <td>{{$fees->fee_name}}</td>
                                <td>{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}</td>
                                <td>
                                    @php $subtotal=0; @endphp
                                    @foreach($fees->feesItems() as $amount)
                                        @php $subtotal += $amount->rate*$amount->qty;@endphp

                                    @endforeach
                                    @if($discount = $invoice->fees()->discount())
                                        @php $discountPercent=$discount->discount_percent;
                                                    $totalDiscount=(($subtotal*$discountPercent)/100);
                                                    $totalAmount=$subtotal-$totalDiscount
                                        @endphp
                                        {{$totalAmount}}

                                    @else
                                        {{$subtotal}}

                                    @endif
                                </td>
                                <td>
                                    {{--@php @endphp--}}
                                    @if($discount = $invoice->fees()->discount())
                                        @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
                                        {{$totalDiscount }}
                                    @endif

                                </td>
                                <td>
                                    {{$invoice->totalPayment()}}


                                </td>
                                <td>

                                    @if ($invoice->invoice_status=="2")
                                        <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-danger">Un-Paid</span>
                                    @elseif ($invoice->invoice_status=="1")
                                        <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-primary">Paid</span>
                                    @elseif ($invoice->invoice_status=="4")
                                        <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-danger">Cancel</span>
                                    @else
                                        <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-success">Partial Payment</span>
                                    @endif

                                    <span id="cancelInvoiceStatus{{$invoice->id}}" class="label label-danger" style="display: none">Cancel</span>
                                </td>
                                <td>0</td>
                                {{--<td> @if ($fees->partial_allowed==1) <span class="btn-orange">Yes<span> @else <span>No</span> @endif</td>--}}
                                {{--<td>{{date('m-d-Y',strtotime($fees->due_date))}}</td>--}}
                                {{--<td>{{$fees->fee_status}}</td>--}}

                                <td>
                                    <a href="{{ URL::to('fees/invoice/show', $invoice->id) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                    {{--<a href="" id="batch_edit_{{$invoice->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>--}}
                                    @if($invoice->invoice_status=="2")
                                        <a  id="{{$invoice->id}}" class="btn btn-danger cancelInvoice btn-xs"  title="" style="font-size:15px;" data-toggle="tooltip" data-placement="bottom" onclick="return confirm('Are you sure, you want to cancel this invoice?');" data-original-title="Cancel Invoice"><i class="fa fa-times-circle-o"></i></a>
                                    @endif
                                    <a  id="{{$invoice->id}}" class="btn btn-danger btn-xs delete_class" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        {{--{{ $invoice->render() }}--}}

                        </tbody>
                    </table>
                </div>
                {{--<div class="link" style="float: right"> {{ $feesinvoices->links() }}</div>--}}
                {{--<div class="link" style="float: right">--}}


                    {{--                    {!! $feesinvoices->appends(Request::all(['']))->render() !!}--}}
                    {{--{!! $feesinvoices->appends(Request::only([--}}
                        {{--'search'=>'search',--}}
                        {{--'filter'=>'filter',--}}
                        {{--'payer_id'=>'std_id',--}}
                        {{--'fees_id'=>'fees_id',--}}
                        {{--'id'=>'invoice_id',--}}
                        {{--'payment_type'=>'payment_type',--}}
                        {{--'batch'=>'batch',--}}
                        {{--'section'=>'section',--}}
                        {{--'start_date'=>'search_start_date',--}}
                        {{--'end_date'=>'search_end_date',--}}
                        {{--]))->render() !!}--}}

                {{--</div>--}}

                @else
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                    </div>
                @endif

            </div><!-- /.box-body -->

            @endif




@endsection

@section('page-script')

    {{--<script>--}}

    $(".download-report").click(function(){
    var report_type = $(this).attr('data-key');
    // dynamic html form
    $('<form id="get_fees_invoice_form" action="/fees/report/due-date-fees-report-pdf-excel" method="get" style="display:none;"></form>')
    .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
    .append('<input type="hidden" name="search_start_date" value="'+$('#search_start_date').val()+'"/>')
    .append('<input type="hidden" name="search_end_date" value="'+$('#search_end_date').val()+'"/>')
    .append('<input type="hidden" name="invoice_status" value="'+$('#invoice_status').val()+'"/>')
    .append('<input type="hidden" name="report_type" value="'+report_type+'"/>')
    // append to body and submit the form
    .appendTo('body').submit();
    // remove form from the body
    $('#get_fees_invoice_form').remove();
    });




    $('#search_start_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
    $('#search_end_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});




@endsection


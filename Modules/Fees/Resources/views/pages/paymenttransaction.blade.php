@extends('fees::layouts.master')
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="col-md-12">
        <h4><strong>Payment Transaction Search</strong></h4>
        <div class="box box-solid">
            <form id="InvoiceSearch" action="/fees/paymenttransaction/search" method="get">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <div class="row">

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Fees Name</label>
                                <input class="form-control" id="search_fees_name" name="fees_name" type="text" value="@if(!empty($allInputs)) {{$allInputs->fees_name}} @endif" placeholder="Type Fees Name">
                                <input id="fees_id" name="fees_id" type="hidden" value="@if(!empty($allInputs)) {{$allInputs->fees_id}} @endif" />
                                <div class="help-block"></div>
                            </div>
                        </div>


                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Invoice ID</label>
                                <input class="form-control" name="search_invoice_id" id="search_invoice_id" type="text" value="@if(!empty($allInputs)) {{$allInputs->invoice_id}} @endif" placeholder="Invoice Id">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Payer Name </label>
                                <input class="form-control" id="std_name" name="payer_name" value="@if(!empty($allInputs)) {{$allInputs->payer_name}}  @endif" type="text" placeholder="Type Student Name">
                                <input id="std_id" name="std_id" type="hidden" value="@if(!empty($allInputs)) {{$allInputs->payer_id}}  @endif"/>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Transaction ID</label>
                                <input class="form-control" name="search_transaction_id" id="search_transaction_id" type="text" value="@if(!empty($allInputs)) {{$allInputs->transaction_id}} @endif" placeholder="Type Transaction Id">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group field-feespaymenttransactionsearch-fp_sdate ">
                                <label class="control-label" for="feespaymenttransactionsearch-fp_sdate">Start Date</label>
                                <input type="text"  id="search_start_date" class="form-control"  value="@if(!empty($allInputs->search_start_date)) {{date('d-m-Y',strtotime($allInputs->search_start_date))}}  @endif" name="search_start_date" placeholder="Start Date">
                                <div class="help-block">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group field-feespaymenttransactionsearch-fp_edate ">
                                <label class="control-label" for="feespaymenttransactionsearch-fp_edate">End Date</label>
                                <input type="text" id="search_end_date" class="form-control" name="search_end_date"   value="@if(!empty($allInputs->search_end_date)) {{date('d-m-Y',strtotime($allInputs->search_end_date))}}  @endif" placeholder="Start Date">


                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group field-feespaymenttransactionsearch-fp_sdate ">
                                <label class="control-label" for="feespaymenttransactionsearch-fp_sdate">Payment Date</label>
                                <input type="text"  id="search_payment_date" class="form-control"  value="@if(!empty($allInputs->search_payment_date)) {{date('d-m-Y',strtotime($allInputs->search_payment_date))}}  @endif" name="search_payment_date" placeholder="Start Date">
                                <div class="help-block">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        <button type="submit"  class="btn btn-primary btn-create">Search</button>
                    </div>
            </form>


        </div>

        @if(!empty($searchPaymentTransaction))
            @php  $allPaymentTransaction=$allPaymentTransaction; @endphp
        @endif

        @if($allPaymentTransaction->count()>0)

            <div class="box-body table-responsive">
                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                    <div id="w1" class="grid-view">

                        <table id="feesListTable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><a  data-sort="sub_master_code">#ID</a></th>
                                <th><a  data-sort="sub_master_code">Fee Name</a></th>
                                <th><a  data-sort="sub_master_code">Type</a></th>
                                <th><a  data-sort="sub_master_alias">Payer</a></th>
                                <th><a  data-sort="sub_master_alias">Transaction Id/Cheque No.</a></th>
                                <th><a  data-sort="sub_master_alias">Payment Method</a></th>
                                <th><a  data-sort="sub_master_alias">Payment Date</a></th>
                                <th><a  data-sort="sub_master_alias">Payment Status</a></th>
                                <th><a  data-sort="sub_master_alias">Payment</a></th>
                                <th><a  data-sort="sub_master_alias">Due Fine</a></th>
                                <th><a  data-sort="sub_master_alias">Total</a></th>
                                <th><a>Actions</a></th>
                            </tr>

                            </thead>
                            <tbody>
                            @php $due_fine_amount=0; $attendance_fine_amount=0; @endphp

                            @foreach($allPaymentTransaction as $payment)

                                @if($payment->due_fine_amount())
                                    @php $due_fine_amount=$payment->due_fine_amount()->fine_amount; @endphp
                                @else
                                   @php  $due_fine_amount=0;@endphp
                                @endif


                                <tr class="gradeX">
                                    <td>{{$payment->id}}</td>
                                    <td>
                                        @if(!empty($payment->invoice_id))
                                            @if(!empty($payment->invoice()->fees_id))
                                                {{$payment->invoice()->fees()->fee_name}}
                                            @else
                                                Attendance Fine
                                            @endif
                                        @else
                                            N/A
                                        @endif

                                    </td>

                                    <td>
                                        @if(!empty($payment->invoice_id))
                                            @if(!empty($payment->invoice()->fees_id))
                                                <span  class="label label-success">F</span>
                                            @else
                                                <span  class="label label-primary">A</span>
                                            @endif
                                        @else
                                            N/A
                                        @endif

                                    </td>

                                    <td>
                                        @if(!empty($payment->invoice_id))
                                            @php $std=$payment->invoice()->payer(); @endphp
                                            {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}
                                        @else
                                            @php $std=$payment->getInvoiceIdByPaymentId($payment->id); @endphp
                                            {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}

                                        @endif


                                    </td>



                                    <td>{{$payment->transaction_id}}</td>
                                    <td>{{$payment->payment_method()->method_name}}</td>
                                    <td>{{date('d-m-Y',strtotime($payment->payment_date))}}</td>
                                    <td><span  class="label label-primary">{{$payment->payment_status}}</span></td>
                                    <td>{{$payment->payment_amount}}</td>
                                    <td>{{$due_fine_amount}}</td>
                                    <td>{{$payment->payment_amount+$due_fine_amount+$attendance_fine_amount}}</td>
                                    <td>
                                        {{--<a class="btn btn-info btn-xs" href="/fees/invoice/payment/update/{{$payment->id}}"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-edit"></i></a>--}}
                                        <a class="btn btn-info btn-xs"  href="/fees/invoice/payment/view/{{$payment->id}}"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-eye"></i></a>
                                    </td>
                                    {{--<td> @if ($fees->partial_allowed==1) <span class="btn-orange">Yes<span> @else <span>No</span> @endif</td>--}}
                                    {{--<td>{{date('m-d-Y',strtotime($fees->due_date))}}</td>--}}
                                    {{--<td>{{$fees->fee_status}}</td>--}}


                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="link" style="float: right">
                        {!! $allPaymentTransaction->appends(Request::only([
                              'search'=>'search',
                              'filter'=>'filter',
                              'fees_id'=>'fees_id',
                              'std_id'=>'std_id',
                              'search_invoice_id'=>'search_invoice_id',
                              'search_transaction_id'=>'search_transaction_id',
                              'search_start_date'=>'search_start_date',
                              'search_end_date'=>'search_end_date',
                              'search_payment_date'=>'search_payment_date',

                              ]))->render() !!}</div>



                </div>
                @else
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                    </div>
                @endif

            </div><!-- /.box-body -->
            @endsection

        @section('page-script')

            $('#search_start_date').datepicker({format: 'dd-mm-yyyy'});
            $('#search_end_date').datepicker({format: 'dd-mm-yyyy'});
            $('#search_payment_date').datepicker({format: 'dd-mm-yyyy'});


            // get student name and select auto complete

            $('#std_name').keypress(function() {
            $(this).autocomplete({
            source: loadFromAjax,
            minLength: 1,

            select: function(event, ui) {
            // Prevent value from being put in the input:
            this.value = ui.item.label;
            // Set the next input's value to the "value" of the item.
            $(this).next("input").val(ui.item.id);
            event.preventDefault();
            }
            });

            /// load student name form
            function loadFromAjax(request, response) {
            var term = $("#std_name").val();
            $.ajax({
            url: '/student/find/student',
            dataType: 'json',
            data: {
            'term': term
            },
            success: function(data) {
            // you can format data here if necessary
            response($.map(data, function(el) {
            return {
            label: el.name,
            value: el.name,
            id: el.id
            };
            }));
            }
            });
            }
            });



            // get Fees autocomplete

            $('#search_fees_name').keypress(function() {
            $(this).autocomplete({
            source: loadFromAjax,
            minLength: 1,

            select: function(event, ui) {
            // Prevent value from being put in the input:
            this.value = ui.item.label;
            // Set the next input's value to the "value" of the item.
            $(this).next("input").val(ui.item.id);
            event.preventDefault();
            }
            });

            /// load student name form
            function loadFromAjax(request, response) {
            var term = $("#search_fees_name").val();
            $.ajax({
            url: '/fees/find/all_fees',
            dataType: 'json',
            data: {
            'term': term
            },
            success: function(data) {
            // you can format data here if necessary
            response($.map(data, function(el) {
            return {
            label: el.fee_name,
            value: el.fee_name,
            id: el.id
            };
            }));
            }
            });
            }
            });




@endsection


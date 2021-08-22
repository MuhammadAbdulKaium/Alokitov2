
@extends('fees::layouts.master')
<style>
    .InvoiceAdvanceSearch {
        display: none;
    }
    #advance_search {
        float: right;
        margin-top: -5px;
    }
</style>
@php  $batchString="Class" @endphp
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="col-md-12">
        <h4 class="box-title"><i class="fa fa-filter"></i> Search <a id="advance_search" class="btn btn-primary" href="#">Advance Search</a></h4>
        <div class="box box-solid">
            <form id="InvoiceSearch" action="/fees/invoice/search" method="get">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Invoice ID</label>
                                <input class="form-control" name="invoice_id" id="search_invoice_id" type="text" value="@if(!empty($allInputs)) {{$allInputs->invoice_id}} @endif" placeholder="Type Invoice ID">
                                <div class="help-block"></div>
                            </div>
                        </div>

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
                                <label class="control-label">Payer Name </label>

                                <input class="form-control" id="std_name" name="payer_name" type="text" value="@if(!empty($allInputs)) {{$allInputs->payer_name}} @endif " placeholder="Type Student Name">
                                <input id="std_id" name="std_id" type="hidden" value="@if(!empty($allInputs)) {{$allInputs->payer_id}}  @endif"/>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group field-feespaymenttransactionsearch-fp_sdate required ">
                                <label class="control-label" for="feespaymenttransactionsearch-fp_sdate">Start Date</label>
                                <input type="text"  id="search_start_date" class="form-control"   value="@if(!empty($allInputs->search_start_date)) {{date('d-m-Y',strtotime($allInputs->search_start_date))}}  @endif" name="search_start_date" placeholder="Start Date">
                                <div class="help-block"></div>
                            </div>			</div>

                        <div class="col-sm-2">
                            <div class="form-group field-feespaymenttransactionsearch-fp_edate required">
                                <label class="control-label" for="feespaymenttransactionsearch-fp_edate">End Date</label>
                                <input type="text" id="search_end_date" class="form-control"  name="search_end_date"   value="@if(!empty($allInputs->search_end_date)) {{date('d-m-Y',strtotime($allInputs->search_end_date))}}  @endif" placeholder="Start Date">


                                <div class="help-block"></div>
                            </div>			</div>
                        <div class="col-sm-2">
                            <div class="form-group field-feespaymenttransactionsearch-fees_pay_tran_mode">
                                <label class="control-label" for="feespaymenttransactionsearch-fees_pay_tran_mode">Payment Status</label>
                                <select id="invoice_status" class="form-control" name="invoice_status">
                                    <option value="">Select Payment</option>
                                    <option value="" @if (!empty($allInputs) && ($allInputs->invoice_status =="NULL")) selected="selected" @endif>All</option>
                                    <option value="1" @if (!empty($allInputs) && ($allInputs->invoice_status =="1")) selected="selected" @endif>Paid</option>
                                    <option value="2" @if (!empty($allInputs) && ($allInputs->invoice_status =="2")) selected="selected" @endif >Un Paid</option>
                                    <option value="3" @if (!empty($allInputs) && ($allInputs->invoice_status =="3")) selected="selected" @endif >Parital Paid</option>
                                </select>

                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="InvoiceAdvanceSearch">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="control-label">{{$batchString}} </label>
                                            <select name="batch" id="batch_select"  class="form-control academicBatch">
                                                @if($batchs->count()>0)
                                                    <option value="">Select {{$batchString}}</option>
                                                    @foreach($batchs as $batch )
                                                        @if(!empty($allInputs->batch))
                                                                    @if($batch->id==$allInputs->batch)
                                                                <option selected value="{{$batch->id}}">{{$batch->batch_name}} @if($batch->get_division()) {{$batch->get_division()->name}} @endif</option>
                                                                 @endif
                                                            @endif
                                                        <option value="{{$batch->id}}">{{$batch->batch_name}} @if($batch->get_division()) {{$batch->get_division()->name}} @endif</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>


                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="control-label" for="section">Section</label>
                                            <select id="section" class="form-control academicSection" name="section">
                                                <option value="" selected disabled>--- Select Section ---</option>
                                            </select>
                                            <div class="help-block"></div>
                                        </div>
                                        </div>

                                    <div class="col-sm-2">
                                        <div class="form-group field-created_start_date">
                                            <label class="control-label" for="created_start_date">Start Date (created date)</label>
                                            <input type="text"  id="created_start_date" class="form-control"   value="@if(!empty($allInputs->created_start_date)) {{date('d-m-Y',strtotime($allInputs->created_start_date))}}  @endif" name="created_start_date">
                                            <div class="help-block"></div>
                                        </div>			</div>

                                    <div class="col-sm-2">
                                        <div class="form-group field-created_end_date">
                                            <label class="control-label" for="created_end_date">End Date (created date)</label>
                                            <input type="text" id="created_end_date" class="form-control" name="created_end_date"   value="@if(!empty($allInputs->created_end_date)) {{date('d-m-Y',strtotime($allInputs->created_end_date))}}  @endif" >

                                            <div class="help-block"></div>
                                        </div>
                                    </div>


                                    <div class="col-sm-2">
                                        <div class="form-group field-feespaymenttransactionsearch-fees_pay_tran_mode">
                                            <label class="control-label" for="feespaymenttransactionsearch-fees_pay_tran_mode">Waiver Status</label>
                                            <select id="invoice_status" class="form-control" name="waiver_status">
                                                <option value="">Select Waiver Status</option>
                                                <option value="1" @if (!empty($allInputs) && ($allInputs->waiver_status =="1")) selected="selected" @endif>Avaliable</option>
                                                <option value="2" @if (!empty($allInputs) && ($allInputs->waiver_status =="2")) selected="selected" @endif >Appllied</option>
                                            </select>

                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group field-fee_or_attendance">
                                            <label class="control-label" for="fee_or_attendance">Invoice Type</label>
                                            <select id="invoice_status" class="form-control" name="invoice_type">
                                                <option value="">Select Invoice Type</option>
                                                <option value="1" @if (!empty($allInputs) && ($allInputs->invoice_type =="1")) selected="selected" @endif>F</option>
                                                <option value="2" @if (!empty($allInputs) && ($allInputs->invoice_type =="2")) selected="selected" @endif >A</option>
                                            </select>

                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                </div>


                            </div>


                            </div>

                    </div>
                </div>
                    <div class="box-footer">
                        <button type="submit"  class="btn btn-primary btn-create">Search</button>
                    </div>


            </form>

            {{--//  Start advance search here from--}}

            {{--//  End advance search here from--}}

        </div>


        @if(!empty($searchInvoice))
            @php $feesinvoices=$allFeesInvoices; $getAttendFine=0; $getDueFine=0;   @endphp
        @endif

        @if(!empty($searchInvoice))
            @php $feesinvoices=$allFeesInvoices; $getAttendFine=0; $getDueFine=0;   @endphp
        @endif

        @if($feesinvoices->count()>0)

            <div class="box-body table-responsive">
                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                    <div id="w1" class="grid-view">

                        <table id="feesListTable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Invoice Id</th>
                                <th><a  data-sort="sub_master_code">Fee Name</a></th>
                                <th><a  data-sort="sub_master_code">Fees Type</a></th>
                                <th><a  data-sort="sub_master_alias">Payer Name</a></th>
                                <th><a  data-sort="sub_master_alias">Fees</a></th>
                                <th><a  data-sort="sub_master_alias">Discount</a></th>
                                <th><a  data-sort="sub_master_alias">Due Fine</a></th>
                                <th><a  data-sort="sub_master_alias">Total Amount</a></th>
                                <th><a  data-sort="sub_master_alias">Paid Amount</a></th>
                                <th><a  data-sort="sub_master_alias">Status</a></th>
                                <th><a  data-sort="sub_master_alias">Waiver</a></th>
                                <th><a>Action</a></th>
                            </tr>

                            </thead>
                            <tbody>

                            @php

                                $i = 1
                            @endphp
                            @foreach($feesinvoices as $invoice)

                                @php $std=$invoice->payer(); @endphp
                                {{-- attendance and due fine amount--}}

                                @if($invoice->due_fine_amount())
                                    @php $due_fine_amount=$invoice->due_fine_amount()->fine_amount; @endphp
                                @else
                                    @php $due_fine_amount=0;
                                    @endphp
                                @endif

                                <tr class="gradeX">
                                    @if(!empty($invoice->fees()))
                                    <td>{{$invoice->id}}</td>
                                    @php
                                        $fees=$invoice->fees();

                                    @endphp
                                    <td>{{$fees->fee_name}}</td>
                                    <td>
                                        <span  class="label label-success">F</span>
                                    </td>


                                    <td><a href="/student/profile/personal/{{$invoice->payer_id}}">@if(!empty($std)) {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}} @endif</a></td>


                                    @php $subtotal=0; $totalAmount=0; $totalDiscount=0; @endphp
                                    @foreach($fees->feesItems() as $amount)
                                        @php $subtotal += $amount->rate*$amount->qty;@endphp

                                    @endforeach



                                    {{--Due Fine Amount--}}
                                    @php
                                        $dueFinePaid=$invoice->invoice_payment_summary();
                                        $var_dueFine=0;
                                        if($dueFinePaid){
                                            $var_dueFine = json_decode($dueFinePaid->summary);
                                        }
                                    @endphp

                                    @if($invoice->invoice_status=="1")
                                        @if(!empty($var_dueFine))
                                            @php $getDueFine=$var_dueFine->due_fine->amount; @endphp
                                        @endif
                                    @else
                                        @if(!empty($invoice->findReduction()))
                                            @php $getDueFine=$invoice->findReduction()->due_fine; @endphp
                                        @else
                                            @php $getDueFine=get_fees_day_amount($invoice->fees()->due_date) @endphp
                                        @endif
                                    @endif

                                        @if($discount = $invoice->fees()->discount())
                                            @php $discountPercent=$discount->discount_percent;
                                                    $totalDiscount=(($subtotal*$discountPercent)/100);
                                                    $totalAmount=$subtotal-$totalDiscount
                                            @endphp
                                        @else
                                            @php
                                                $totalAmount=$subtotal;
                                            @endphp

                                        @endif


                                        {{--waiver Check --}}
                                        @if($invoice->waiver_type=="1")
                                            @php $totalWaiver=(($totalAmount*$invoice->waiver_fees)/100);
                                                 $totalAmount=$totalAmount-$totalWaiver
                                            @endphp
                                        @elseif($invoice->waiver_type=="2")
                                            @php $totalWaiver=$invoice->waiver_fees;
                                                 $totalAmount=$totalAmount-$totalWaiver
                                            @endphp

                                        @endif


                                        @if($discount = $invoice->fees()->discount())
                                            @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
                                        @endif


                                        @if(!empty($invoice->waiver_fees))
                                            @php $totalDiscount=$totalDiscount+$totalWaiver @endphp
                                        @endif




                                    {{--<td>{{$subtotal+$getAttendFine+$getDueFine-$totalDiscount}}</td>--}}

                                    <td>{{$subtotal}}</td>
                                    <td>{{$totalDiscount}} </td>
                                    <td>{{$getDueFine}}</td>
                                    <td>{{$subtotal+$getDueFine-$totalDiscount}}</td>

                                    <td>
                                        @if ($invoice->invoice_status=="2")
                                        {{$invoice->totalPayment()}}
                                        @elseif ($invoice->invoice_status=="1")
                                            {{$invoice->totalPayment()+$getDueFine}}
                                          @endif

                                    </td>

                                    <td>

                                        @if ($invoice->invoice_status=="2")
                                            <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-danger">Un-Paid</span>
                                        @elseif ($invoice->invoice_status=="1")
                                            <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-primary">Paid</span>
                                        @elseif ($invoice->invoice_status=="4")
                                            <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-danger">Cancel</span>
                                        @elseif ($invoice->invoice_status=="3")
                                            <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-success">Partial Payment</span>
                                        @endif

                                        <span id="cancelInvoiceStatus{{$invoice->id}}" class="label label-danger" style="display: none">Cancel</span>
                                    </td>
                                    <td>
                                        @if(!empty($invoice->payer()->student_waiver()) && ($invoice->payer()->student_waiver()->end_date>date('Y-m-d')) && ($invoice->wf_status=='1'))
                                            @if($invoice->invoice_status=="2")
                                            <a  class="label label-primary"   href="/fees/invoice/add-waiver-modal/{{$invoice->id}}/" title="Add Waiver" data-pjax="0" data-target="#globalModal" data-toggle="modal"  class="btn btn-success btn-xs wf_status" >Available</a>
                                            @endif
                                            @elseif(!empty($invoice->payer()->student_waiver()) && ($invoice->wf_status=='2'))
                                            <span class="label  label-default ">Applied</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $getUrl=Request::fullUrl();
                                            $currentUrl=str_replace('/','+',$getUrl);
                                            $currentUrl=str_replace('?','>>',$currentUrl);
                                            $currentUrl=str_replace('%','-',$currentUrl);
                                        @endphp

                                        {{--                            {{$currentUrl}}--}}
                                        {{-- {{urlencode(strtolower(url()->current()))}}
 --}}


                                        <a href="/fees/invoice/show/{{$invoice->id}}/{{$currentUrl}}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        {{--<a href="" id="batch_edit_{{$invoice->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>--}}
                                        @if($invoice->invoice_status=="2")
                                            <a  id="{{$invoice->id}}" class="btn btn-danger cancelInvoice btn-xs"  title="" style="font-size:15px;" data-toggle="tooltip" data-placement="bottom"  data-original-title="Cancel Invoice"><i class="fa fa-times-circle-o"></i></a>
                                            <a  id="{{$invoice->id}}" class="btn btn-danger btn-xs delete_class"  data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        @endif
                                    </td>

                                        @else

                                        <td>{{$invoice->id}}</td>
                                        <td>Attendance Fine</td>
                                        <td>
                                                <span  class="label label-primary">A</span>
                                        </td>
                                        <td><a href="/student/profile/personal/{{$invoice->payer_id}}">@if(!empty($std)) {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}} @endif</a></td>
                                        <td>{{$invoice->invoice_amount}}</td>
                                        <td></td>
                                        <td></td>
                                        <td>       @if ($invoice->invoice_status=="1")
                                                {{$invoice->invoice_amount}}

                                            @endif</td>
                                        <td>
                                            @if ($invoice->invoice_status=="1")
                                                {{$invoice->invoice_amount}}

                                                @endif

                                        </td>
                                        <td>

                                            @if ($invoice->invoice_status=="2")
                                                <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-danger">Un-Paid</span>
                                            @elseif ($invoice->invoice_status=="1")
                                                <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-primary">Paid</span>
                                            @elseif ($invoice->invoice_status=="4")
                                                <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-danger">Cancel</span>
                                            @elseif ($invoice->invoice_status=="3")
                                                <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-success">Partial Payment</span>
                                            @endif

                                            <span id="cancelInvoiceStatus{{$invoice->id}}" class="label label-danger" style="display: none">Cancel</span>
                                        </td>
                                        <td>
                                        <td>
                                            @php
                                                $getUrl=Request::fullUrl();
                                                $currentUrl=str_replace('/','+',$getUrl);
                                                $currentUrl=str_replace('?','>>',$currentUrl);
                                                $currentUrl=str_replace('%','-',$currentUrl);
                                            @endphp

                                            {{--                            {{$currentUrl}}--}}
                                            {{-- {{urlencode(strtolower(url()->current()))}}
     --}}


                                            <a href="/fees/invoice/show/{{$invoice->id}}/{{$currentUrl}}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                            {{--<a href="" id="batch_edit_{{$invoice->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>--}}
                                            @if($invoice->invoice_status=="2")
                                                <a  id="{{$invoice->id}}" class="btn btn-danger cancelInvoice btn-xs"  title="" style="font-size:15px;" data-toggle="tooltip" data-placement="bottom"  data-original-title="Cancel Invoice"><i class="fa fa-times-circle-o"></i></a>
                                                <a  id="{{$invoice->id}}" class="btn btn-danger btn-xs delete_class"  data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                            @endif
                                        </td>

                                        @endif

                                </tr>
                            @endforeach
                            {{--{{ $invoice->render() }}--}}

                            </tbody>
                        </table>
                    </div>
                    {{--<div class="link" style="float: right"> {{ $feesinvoices->links() }}</div>--}}
                    <div class="link" style="float: right">


                        {{--                    {!! $feesinvoices->appends(Request::all(['']))->render() !!}--}}
                        {!! $feesinvoices->appends(Request::only([
                            'search'=>'search',
                            'filter'=>'filter',
                            'payer_id'=>'std_id',
                            'fees_id'=>'fees_id',
                            'fees_name'=>'fees_name',
                            'invoice_status'=>'invoice_status',
                            'id'=>'invoice_id',
                            'batch'=>'batch',
                            'section'=>'section',
                            'start_date'=>'search_start_date',
                            'end_date'=>'search_end_date',
                            'created_start_date'=>'created_start_date',
                            'created_end_date'=>'created_end_date',
                            'waiver_status'=>'waiver_status',
                            'invoice_type'=>'invoice_type',
                            ]))->render() !!}

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
            {{--<script>--}}

        // Invoice status Cancel Ajax Request
                    $('.cancelInvoice').click(function(e) {

            var invoiceId= $(this).attr('id');

                swal({
                        title: "Are you sure?",
                        text: "You want to cancel invoice",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Yes!',
                        cancelButtonText: "No",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {

                        if (isConfirm) {

                            // ajax request
                            $.ajax({

                                url: '/fees/invoice/update_status/' + invoiceId,
                                type: 'GET',
                                cache: false,
                                beforeSend: function () {
                                    {{--alert($('form#Partial_allowForm').serialize());--}}
                                },

                                success: function (data) {
                                    $('#' + invoiceId).hide();
                                    $('#unPainInvoiceStatus' + invoiceId).hide();
                                    $('#cancelInvoiceStatus' + invoiceId).show();
                                    swal("Success!", "Invoice successfully cancelled", "success");

                                },

                                error: function (data) {
                                    alert(JSON.stringify(data));
                                }
                            });
                        } else {
                            swal("No", "Your Invoice is safe :)", "error");
                            e.preventDefault();
                        }
                    });

            });


    // invoice delete ajax request
            $('.delete_class').click(function(e){
            var tr = $(this).closest('tr'),
            del_id = $(this).attr('id');

                swal({
                        title: "Are you sure?",
                        text: "You want to delete invoice",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Yes!',
                        cancelButtonText: "No",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {

                        if (isConfirm) {

                            $.ajax({
                                url: "invoice/delete/" + del_id,
                                type: 'GET',
                                cache: false,
                                success: function (result) {
                                    tr.fadeOut(1000, function () {
                                        $(this).remove();
                                    });
                                swal("Success!", "Invoice successfully deleted", "success");

                                }
                            });
                        } else {
                            swal("NO", "Your Invoice is safe :)", "error");
                            e.preventDefault();
                        }
                });
            });


            $('#search_start_date').datepicker({format: 'dd-mm-yyyy'});
            $('#search_end_date').datepicker({format: 'dd-mm-yyyy'});
            $('#created_start_date').datepicker({format: 'dd-mm-yyyy'});
            $('#created_end_date').datepicker({format: 'dd-mm-yyyy'});


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


    // advance search invoice

    $("#advance_search").click(function () {
        $(".InvoiceAdvanceSearch").fadeToggle("slow", "linear");
    })


        // request for section list using batch id
        jQuery(document).on('change','.academicBatch',function(){
            // get academic level id
            var batch_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/section') }}",
                type: 'GET',
                cache: false,
                data: {'id': batch_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // statements
                },

                success:function(data){
                    op+='<option value="" selected disabled>--- Select Section ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }
                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);

                    $('.academicSubject').html("");
                    $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                    $('#assessment_table_row').html('');
                    // semester reset
                    $('.academicSemester option:first').prop('selected', true);
                },
                error:function(){
                    // statements
                },
            });
        });


    @if(Session::has('message'))
          toastr.info("{{ Session::get('message') }}");

    @endif




@endsection


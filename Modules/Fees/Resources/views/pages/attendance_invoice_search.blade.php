
@extends('fees::layouts.fees_report_master')
<style>
    .InvoiceAdvanceSearch {
        display: none;
    }
    #advance_search {
        float: right;
        margin-top: -5px;
    }
</style>

@section('section-title')
    <h1><i class="fa fa-plus-square"></i>Student Invoice  Search</h1>

@endsection

@php  $batchString="Class" @endphp
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="col-md-12">
        <h4 class="box-title"><i class="fa fa-filter"></i> Search</h4>
        <div class="box box-solid">
            <form id="InvoiceSearch" action="/fees/student/invoice/search/result" method="get">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group required">
                                <label class="control-label">Student Name / Username </label>

                                <input class="form-control" required id="std_name" name="payer_name" type="text" value="@if(!empty($allInputs)) {{$allInputs->payer_name}} @endif " placeholder="Type Student Name">
                                <input id="std_id" required name="std_id" type="hidden" value="@if(!empty($allInputs)) {{$allInputs->payer_id}}  @endif"/>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group field-feespaymenttransactionsearch-fp_sdate required ">
                                <label class="control-label" for="feespaymenttransactionsearch-fp_sdate">Start Date</label>
                                <input type="text"  id="search_start_date" class="form-control" required  value="@if(!empty($allInputs->search_start_date)) {{date('m/d/Y',strtotime($allInputs->search_start_date))}}  @endif" name="search_start_date" placeholder="Start Date">
                                <div class="help-block"></div>
                            </div>			</div>

                        <div class="col-sm-2">
                            <div class="form-group field-feespaymenttransactionsearch-fp_edate required">
                                <label class="control-label" for="feespaymenttransactionsearch-fp_edate">End Date</label>
                                <input type="text" id="search_end_date" class="form-control" required name="search_end_date" value="@if(!empty($allInputs->search_end_date)) {{date('m/d/Y',strtotime($allInputs->search_end_date))}}  @endif"    placeholder="Start Date">


                                <div class="help-block"></div>
                            </div>			</div>


                        <div class="col-sm-2">
                            <div class="form-group field-feespaymenttransactionsearch-type">
                                <label class="control-label" for="feespaymenttransactionsearch-fees_pay_tran_mode">Invoice Type</label>
                                <select id="invoice_type" class="form-control" name="invoice_type">
                                    <option value="">Select Type</option>
                                    <option value="1" @if (!empty($allInputs) && ($allInputs->invoice_type =="1")) selected="selected" @endif>Fees Invoice </option>
                                    <option value="2" @if (!empty($allInputs) && ($allInputs->invoice_type =="2")) selected="selected" @endif >Attendance Invoice</option>
                                </select>

                                <div class="help-block"></div>
                            </div>
                        </div>


                        <div class="col-sm-2">
                            <div class="form-group field-feespaymenttransactionsearch-fees_pay_tran_mode">
                                <label class="control-label" for="feespaymenttransactionsearch-fees_pay_tran_mode">Payment Status</label>
                                <select id="invoice_status" class="form-control" name="invoice_status">
                                    <option value="">Select Payment</option>
                                    <option value="1" @if (!empty($allInputs) && ($allInputs->invoice_status =="1")) selected="selected" @endif>Paid</option>
                                    <option value="2" @if (!empty($allInputs) && ($allInputs->invoice_status =="2")) selected="selected" @endif >Un Paid</option>
                                </select>

                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3" style="margin-top: 20px">
                            <button type="submit"  class="btn btn-primary btn-create">Search</button>
                            {{--<button type="reset" class="btn btn-default">Reset</button>--}}
                        </div>
                    </div>

                </div>



            </form>

            {{--//  Start advance search here from--}}

            {{--//  End advance search here from--}}

        </div>

        @if(!empty($searchInvoice))
            @php $feesinvoices=$allFeesInvoices; $getAttendFine=0; $getDueFine=0; $totalDiscount=0;   @endphp
            @if(!empty($feesinvoices) && ($feesinvoices->count()>0))

                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <div id="w1" class="grid-view">
                            <form action="/fees/student/invoice/process"  method="get">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="std_id"  value="{{$allInputs->payer_id}}">
                                <table id="feesListTable" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" class="chk_boxes" label="check all"  />Check all</th>
                                        <th>Invoice Id</th>
                                        <th><a  data-sort="sub_master_code">Fees Type</a></th>
                                        <th><a  data-sort="sub_master_code">Fees Name</a></th>
                                        <th><a  data-sort="sub_master_alias">Fees Amount</a></th>
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

                                        $i = 1; $fees="";
                                    @endphp
                                    @foreach($feesinvoices as $invoice)

                                        {{-- attendance and due fine amount--}}

                                        @if($invoice->due_fine_amount())
                                            @php $due_fine_amount=$invoice->due_fine_amount()->fine_amount; @endphp
                                        @else
                                            @php $due_fine_amount=0; @endphp
                                        @endif

                                        @if($invoice->attendance_fine_amount())
                                            @php $attendance_fine_amount=$invoice->attendance_fine_amount()->fine_amount;@endphp
                                        @else
                                            @php $attendance_fine_amount=0; @endphp
                                        @endif

                                        <tr class="gradeX">
                                            <td><input type="checkbox" class="chk_boxes1" name="invoice_id[{{$invoice->id}}]" value="{{$invoice->invoice_type}}"  /></td>

                                            <td>{{$invoice->id}}</td>
                                            @php
                                                if(!empty($invoice->fees_id)) {
                                                    $fees=$invoice->fees();
                                                    }
                                                    $std=$invoice->payer()

                                            @endphp

                                            <td>
                                                @if(!empty($invoice->fees_id))
                                                    <span  class="label label-success">F</span>
                                                @else
                                                    <span  class="label label-primary">A</span>
                                                @endif

                                            </td>
                                            <td>
                                                @if(!empty($invoice->fees_id))
                                                    {{$fees->fee_name}}
                                                @else
                                                    N/A
                                                @endif

                                            </td>
                                            @if(!empty($invoice->fees_id))


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

                                    @endif

                                            <td>
                                                @if(!empty($invoice->invoice_amount))
                                                    {{$invoice->invoice_amount}}
                                                @else
                                                    {{$subtotal}}

                                                @endif

                                            </td>
                                            <td>{{$totalDiscount}} </td>
                                            <td>{{$getDueFine}}</td>

                                            <td>

                                                @if(!empty($invoice->invoice_amount))
                                                    {{$invoice->invoice_amount}}
                                                @else
                                                    {{$subtotal+$getDueFine-$totalDiscount}}

                                                @endif


                                            </td>

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


                                                @if(!empty($invoice->payer()->student_waiver()) && ($invoice->payer()->student_waiver()->end_date>date('Y-m-d')) && ($invoice->wf_status=='1') && (!empty($invoice->fees_id)))
                                                    <a  class="label label-primary"   href="/fees/invoice/add-waiver-modal/{{$invoice->id}}/" title="Add Waiver" data-pjax="0" data-target="#globalModal" data-toggle="modal"  class="btn btn-success btn-xs wf_status" >Available</a>
                                                @elseif(!empty($invoice->payer()->student_waiver()) && ($invoice->wf_status=='2'))
                                                    <span class="label  label-default ">Applied</span>
                                                @endif</td>
                                            {{--                                    <td> @if ($fees->partial_allowed==1) <span class="btn-orange">Yes<span> @else <span>No</span> @endif</td>--}}

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
                                                    <a  id="{{$invoice->id}}" class="btn btn-danger cancelInvoice btn-xs"  title="" style="font-size:15px;" data-toggle="tooltip" data-placement="bottom" onclick="return confirm('Are you sure, you want to cancel this invoice?');" data-original-title="Cancel Invoice"><i class="fa fa-times-circle-o"></i></a>
                                                    <a  id="{{$invoice->id}}" class="btn btn-danger btn-xs delete_class" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                                @endif
                                            </td>
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
                                'id'=>'invoice_id',
                                'payment_type'=>'payment_type',
                                'batch'=>'batch',
                                'section'=>'section',
                                'start_date'=>'search_start_date',
                                'end_date'=>'search_end_date',
                                'created_start_date'=>'created_start_date',
                                'created_end_date'=>'created_end_date',
                                'waiver_status'=>'waiver_status',
                                ]))->render() !!}

                        </div>
                            <button type="submit"  class="btn btn-primary btn-create">Process</button>
                        @else
                            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                            </div>
                        @endif
                        @endif

                        </form>

                    </div><!-- /.box-body -->
                    @endsection

                    @section('page-script')


                        // check box all select here
                        $('.chk_boxes').click(function(){
                        $('.chk_boxes1').prop('checked', this.checked);
                        });


                        // Invoice status Cancel Ajax Request
                        $('.cancelInvoice').click(function() {
                        var invoiceId= $(this).attr('id')

                        // ajax request
                        $.ajax({

                        url: '/fees/invoice/update_status/'+invoiceId,
                        type: 'GET',
                        cache: false,
                        beforeSend: function() {
                        {{--alert($('form#Partial_allowForm').serialize());--}}
                        },

                        success:function(data){
                        $('#'+invoiceId).hide();
                        $('#unPainInvoiceStatus'+invoiceId).hide();
                        $('#cancelInvoiceStatus'+invoiceId).show();
                        },

                        error:function(data){
                        alert(JSON.stringify(data));
                        }
                        });

                        });


                        // invoice delete ajax request
                        $('.delete_class').click(function(){
                        var tr = $(this).closest('tr'),
                        del_id = $(this).attr('id');

                        $.ajax({
                        url: "invoice/delete/"+ del_id,
                        type: 'GET',
                        cache: false,
                        success:function(result){
                        tr.fadeOut(1000, function(){
                        $(this).remove();
                        });
                        }
                        });
                        });


                        $('#search_start_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
                        $('#search_end_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
                        $('#created_start_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
                        $('#created_end_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});


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


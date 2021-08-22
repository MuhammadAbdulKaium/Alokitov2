
@extends('fees::layouts.master')

@php  $batchString="Class" @endphp
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="col-md-12">
        <div class="box box-solid">
            <form id="InvoiceSearch" action="/fees/feesmanage/search" method="get">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Fees ID</label>
                                <input class="form-control" name="fees_id_single" id="search_fees_id" value="@if(!empty($allInputs)) {{$allInputs->fees_id_single}} @endif"  type="text" placeholder="Fees Id">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Fees Name</label>
                                <input class="form-control" id="search_fees_name" name="fees_name" value="@if(!empty($allInputs)) {{$allInputs->fees_name}} @endif" type="text"  placeholder="Fees Name">
                                <input id="fees_id" name="fees_id" value="@if(!empty($allInputs)) {{$allInputs->fees_id}} @endif"  type="hidden" />
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-2" style="margin-top: 23px">
                         <button type="submit"  class="btn btn-primary btn-create">Search</button>
                        </div>
                        @if(!empty($feesProfile) && ($feesProfile->count()>0))
                        <div class="col-md-4 pull-right col-md-offset-2" style="border: 2px solid #efefef">
                            <div class="col-md-6 col-md-offset-1">
                                <div class="radio">
                                    <label><input type="radio" name="payer_select"   value="1">Student By Name</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="payer_select"  value="2">Student By Class-Section</label>
                                </div>
                            </div>
                            <div class="col-sm-4" style="margin-top: 23px">
                                <a href="" id="addPayer"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"   class="btn btn-primary btn-create">Add Student</a>
                            </div>
                        </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

    </div>

        @if(!empty($feesProfile))

            <table id="feesListTable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th><a  data-sort="sub_master_name">Fees Name</a></th>
                    <th><a  data-sort="sub_master_code">Description</a></th>
                    <th><a  data-sort="sub_master_alias">Type</a></th>
                    <th><a  data-sort="sub_master_alias">Amount</a></th>
                    <th><a  data-sort="sub_master_alias">Discount</a></th>
                    {{--<th><a  data-sort="sub_master_alias">Partial Payment</a></th>--}}
                    <th><a  data-sort="sub_master_alias">Due Date</a></th>
                    {{--<th><a  data-sort="sub_master_alias">Status</a></th>--}}
                </tr>

                </thead>
                <tbody>
                    <tr class="gradeX">
                        <td>{{$feesProfile->id}}</td>
                        <td>{{$feesProfile->fee_name}}</td>
                        <td>{{$feesProfile->description}}</td>
                        <td>{{$feesProfile->fees_type()->fee_type_name}}</td>

                        @php $subtotal=0; @endphp
                        @foreach($feesProfile->feesItems() as $amount)
                            @php $subtotal += $amount->rate*$amount->qty;@endphp

                        @endforeach
                        {{--dsicount --}}
                        @if($discount =$feesProfile->discount())
                            @php $discountPercent=$discount->discount_percent;
                                                    $totalDiscount=(($subtotal*$discountPercent)/100);
                                                    $totalAmount=$subtotal-$totalDiscount
                            @endphp
                        @endif

                        <td>{{$subtotal}}</td>
                        <td>
                            {{--@php @endphp--}}
                            @if($discount = $feesProfile->discount())
                                @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
                                {{$totalDiscount }}
                            @endif

                        </td>

                        {{--discount--}}
                        {{--                  <td> @if ($fees->partial_allowed=="0") <span class="btn-orange">NO<span> @else <span>Yes</span> @endif</td>--}}
                        <td>{{date('d-m-Y',strtotime($feesProfile->due_date))}}</td>
                        {{--<td>{{$fees->fee_status}}</td>--}}

                    </tr>


                </tbody>
            </table>


        @endif


        @if(!empty($feesProfile))
        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="feesListTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Invoice Id</th>
                            <th><a  data-sort="sub_master_code">Fee Name</a></th>
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
                        <tbody class="add-payer-section">

                        {{--<tr >--}}

                        {{--</tr>--}}

                        @if(!empty($searchInvoice) && (count($allFeesInvoices)>0))
                            @php $feesinvoices=$allFeesInvoices;  $getAttendFine=0; $getDueFine=0;   @endphp



             @if($feesinvoices->count()>0)
                            @php

                                $i = 1
                            @endphp
                            @foreach($feesinvoices as $invoice)


                            {{-- attendance and due fine amount--}}

                            @if($invoice->due_fine_amount())
                                @php $due_fine_amount=$invoice->due_fine_amount()->fine_amount; @endphp
                                @else
                               @php $due_fine_amount=0; @endphp
                            @endif


                                <tr class="gradeX">
                                    <td>{{$invoice->id}}</td>
                                    @php
                                        $fees=$invoice->fees();
                                        $std=$invoice->payer()
                                    @endphp
                                    <td>{{$fees->fee_name}}</td>
                                    <td><a href="/student/profile/personal/{{$invoice->payer_id}}">@if(!empty($std)) {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}} @endif</a></td>


                                    @php $subtotal=0; $totalAmount=0; $totalDiscount=0; @endphp
                                    @foreach($fees->feesItems() as $amount)
                                        @php $subtotal += $amount->rate*$amount->qty;@endphp

                                    @endforeach
                                    @if($discount = $invoice->fees()->discount())
                                        @php $discountPercent=$discount->discount_percent;
                                                    $totalDiscount=(($subtotal*$discountPercent)/100);
                                                    $totalAmount=$subtotal-$totalDiscount
                                        @endphp

                                    @else
                                        @php $totalAmount=$subtotal;  @endphp

                                    @endif

                                @if($invoice->waiver_type=="1")
                                        @php $totalWaiver=(($subtotal*$invoice->waiver_fees)/100);
                                    $totalAmount=$totalAmount-$totalWaiver
                                    @endphp
                                    @elseif($invoice->waiver_type=="2")
                                        @php $totalWaiver=$invoice->waiver_fees;
                                            $totalAmount=$subtotal-$totalWaiver
                                        @endphp
                                    @endif

                                    {{--Due Fine Amount--}}
                                    @php
                                        $dueFinePaid=$invoice->invoice_payment_summary();
                                        if($dueFinePaid){
                                            $var_a = json_decode($dueFinePaid->summary);
                                        }
                                    @endphp
                                    @if(!empty($var_a))

                                        @php $getDueFine=$var_a->due_fine->amount; @endphp
                                    @else

                                        @php $getDueFine=get_fees_day_amount($invoice->fees()->due_date) @endphp
                                    @endif
                                    {{--end due fine amount--}}


                                    {{--end attend fine amount--}}

                                    {{--/// discount calculate--}}
                                    @if($discount = $invoice->fees()->discount())
                                        @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
                                    @endif

                                    {{--caclulate Discount waiver--}}
                                    @if(!empty($invoice->waiver_fees))
                                        @php $totalDiscount=$totalDiscount+$totalWaiver @endphp
                                    @endif


                                    <td>{{$subtotal}}</td>
                                    <td>{{$totalDiscount}} </td>
                                    <td>{{$getDueFine}}</td>
                                    <td>{{$subtotal+$getDueFine-$totalDiscount}}</td>

                                    <td>
                                        {{$invoice->totalPayment()+$due_fine_amount}}

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
                                            <a  class="label label-primary"   href="/fees/invoice/add-waiver-modal/{{$invoice->id}}/" title="Add Waiver" data-pjax="0" data-target="#globalModal" data-toggle="modal"  class="btn btn-success btn-xs wf_status" >Available</a>
                                            @elseif(!empty($invoice->payer()->student_waiver()) && ($invoice->wf_status=='2'))
                                            <span class="label  label-default ">Applied</span>
                                        @endif</td>
                                    <td>
                                        {{--<a href="" id="batch_edit_{{$invoice->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>--}}
                                        @if($invoice->invoice_status=="2")
                                        <a  id="{{$invoice->id}}" class="btn btn-danger btn-xs delete_class" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            {{--{{ $invoice->render() }}--}}

                         @endif

                        @endif

                        </tbody>
                    </table>
                </div>
                {{--<div class="link" style="float: right"> {{ $feesinvoices->links() }}</div>--}}
                <div class="link" style="float: right">

                    @if(!empty($feesinvoices))
{{--                    {!! $feesinvoices->appends(Request::all(['']))->render() !!}--}}
                    {!! $feesinvoices->appends(Request::only([
                        'search'=>'search',
                        'filter'=>'filter',
                        'payer_id'=>'std_id',
                        'fees_id'=>'fees_id',
                        'fees_id_single'=>'fees_id_single',
                        ]))->render() !!}
                    @endif

            </div>



        </div><!-- /.box-body -->

            @else
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642; margin-top: 130px">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                </div>
            @endif


        <!-- Modal -->
            <div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                        {{--<div class="modal-header">--}}
                            {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                            {{--<h4 class="modal-title" id="myModalLabel"> API CODE </h4>--}}
                        {{--</div>--}}
                        <div id="getCode">
                            {{--//ajax success content here.--}}
                </div>
            </div>


        @endsection

            <script>


                function  waiver_avaliable(invoice) {
                    $.ajax({
                        url: "/fees/invoice/add-waiver-modal/"+invoice,
                        type: 'GET',
                        cache: false,
                        success:function(msg){
                            $("#getCode").html(msg);
                            $("#getCodeModal").modal('show');
                        }
                    });
                }

                function delete_recent_invoice(invoice) {
                    alert("/fees/invoice/delete/"+invoice);
                    $.ajax({
                        url: "/fees/invoice/delete/"+ invoice,
                        type: 'GET',
                        cache: false,
                        success:function(result){
                            $('#'+invoice).fadeOut(1000, function(){
                                $(invoice).remove();
                            });
                        }
                    });
                }

            </script>


        @section('page-script')
            {{--<script>--}}
                {{--{{$invoice->fees_id}}--}}

      //add student button link change

            $('input[type="radio"]').on('change', function(e){
                   var value= $(this).val();
            if(value=="1") {
                @if(!empty($feesProfile))
                $("#addPayer").attr("href","/fees/feesmanage/add/payer/{{$feesProfile->id}}");
                @endif
                }
           else if(value=="2") {
                @if(!empty($feesProfile))
                $("#addPayer").attr("href","/fees/feesmanage/add/payer/class/section/{{$feesProfile->id}}");
                @endif
                }
              });






    // invoice delete ajax request
            $('.delete_class_recent, .delete_class').click(function(){

                var x = confirm("Are you sure you want to delete?");
            if(x) {

            var tr = $(this).closest('tr');
            del_id = $(this).attr('id');
            $.ajax({
                    url: "/fees/invoice/delete/"+ del_id,
                    type: 'GET',
                    cache: false,
                    success:function(result){
                    tr.fadeOut(1000, function(){
                    $(this).remove();
                      });
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




    @if(Session::has('message'))
          toastr.info("{{ Session::get('message') }}");

    @endif




@endsection







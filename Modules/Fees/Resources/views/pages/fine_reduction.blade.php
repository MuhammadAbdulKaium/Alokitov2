@extends('fees::layouts.fees_report_master')
@section('section-title')
    <h1><i class="fa fa-plus-square"></i>Fine Reduction</h1>

@endsection
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="box box-solid">
        <div class="box-header with-border">
            <h4 class="box-title"><i class="fa fa-filter"></i> Search</h4>
        </div><!--./box-header-->
        <form id="InvoiceSearchForm" action="/fees/student/fine_reduction/search" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            @if(!empty($allInputs))
                <input type="hidden" name="request_type" value="pdf">
            @endif
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">

                        <div class="form-group field-due-date-fees-reportsearch-fp_sdate required">
                            <label class="control-label" for="due-date-fees-reportsearch-fp_sdate">Invoice Number</label>
                            <input type="text" @if(!empty($invoice_id)) value="{{$invoice_id}}" @endif id="invoice_id" class="form-control" name="invoice_id" placeholder="Search Invoice Number">

                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>

            <div class="box-footer">
                <button type="submit"  class="btn btn-primary btn-create">Search</button></div>
                </div>
            </div>
        </form>



    @if(!empty($invoice))
        @php  $getAttendFine=0; $getDueFine=0;   @endphp
    @endif

    <div class="box box-solid">

    @if(!empty($invoice))

        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="feesListTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Invoice Id</th>
                            <th><a  data-sort="sub_master_code">Fee Name</a></th>
                            <th><a  data-sort="sub_master_alias">Payer Name</a></th>
                            <th><a  data-sort="sub_master_alias">Amount</a></th>
                            <th><a  data-sort="sub_master_alias">Fees Amount</a></th>
                            <th><a  data-sort="sub_master_alias">Discount</a></th>
                            <th><a  data-sort="sub_master_alias">Due Fine</a></th>
                            <th><a  data-sort="sub_master_alias">Attendance Fine</a></th>
                            <th><a  data-sort="sub_master_alias">Paid Amount</a></th>
                            <th><a  data-sort="sub_master_alias">Status</a></th>
                            <th><a  data-sort="sub_master_alias">Waiver</a></th>
                            <th><a  data-sort="sub_master_alias">Partial Allowed</a></th>
                            <th><a>Action</a></th>
                        </tr>

                        </thead>
                        <tbody>

                        @php

                            $i = 1
                        @endphp
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

                                {{--end due fine amount--}}

                                {{--attendace Fine Amount--}}
                                @php
                                    $attendanceFinePaid=$invoice->invoice_payment_summary();
                                    $var_AttnFine=0;
                                    if($attendanceFinePaid){
                                        $var_AttnFine = json_decode($attendanceFinePaid->summary);
                                    }
                                @endphp
                                @if($invoice->invoice_status=="1")
                                    @if(!empty($var_AttnFine))
                                        @php $getAttendFine= $var_AttnFine->attendance_fine->amount; @endphp
                                     @endif
                                @else
                                    @if(!empty($invoice->findReduction()))
                                        @php $getAttendFine=$invoice->findReduction()->attendance_fine; @endphp
                                    @else
                                        @php $getAttendFine=getAttendanceFinePreviousMonth($invoice->id); @endphp
                                    @endif
                                @endif

                                {{--end attend fine amount--}}

                                {{--/// discount calculate--}}
                                @if($discount = $invoice->fees()->discount())
                                    @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
                                @endif

                                {{--caclulate Discount waiver--}}
                                @if(!empty($invoice->waiver_fees))
                                    @php $totalDiscount=$totalDiscount+$totalWaiver @endphp
                                @endif


                                <td id="totalAmount" >{{$subtotal+$getAttendFine+$getDueFine-$totalDiscount}}</td>

                                <td id="getSubtotal">{{$subtotal}}</td>
                                <td id="getDiscount">{{$totalDiscount}} </td>
                                <td id="getDueFine">{{$getDueFine}}</td>
                                <td id="getAttendanceFine">{{$getAttendFine}}</td>

                                <td>
                                    {{$invoice->totalPayment()+$due_fine_amount+$attendance_fine_amount}}

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
                                <td> @if ($fees->partial_allowed==1) <span class="label label-success">Yes<span> @else <span class="label ">No</span> @endif</td>

                                <td>
                                <a href="/fees/student/fine-reduction/modal/{{$invoice->id}}/{{$getDueFine}}/{{$getAttendFine}}" class="btn btn-success btn-xs"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-check"></i></a>
                                    {{--<a href="" id="batch_edit_{{$invoice->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>--}}
                                </td>
                            </tr>


                        </tbody>
                    </table>
                </div>


                @else
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                    </div>
                @endif
            </div>
            </div><!-- /.box-body -->
        </div>
@endsection

@section('page-script')






@endsection


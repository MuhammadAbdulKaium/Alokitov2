@extends('fees::layouts.master')

<style>
    .fine-enable {
        margin-top: 20px !important;
    }
    .control-label {
        font-size: 18px;
        font-weight: normal;
    }
</style>
<!-- page content -->
@section('page-content')

    @php
        $std=$invoice->payer();
        $enroll=$std->singleEnroll();
        $fees=$invoice->fees();
    @endphp
    <!-- grading scale -->
    <div class="col-md-12">
        <div class="innerAll shop-client-products cart invoice col-md-9">
            <h3 class="">Invoice</h3>
            <hr>

            @if(Session::has('message'))
                <div class="alert-success alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">
                    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i>{{ Session::get('message') }}</h4>
                </div>

            @endif

            <div class="pull-left">
                <a href="{{$currentUrl}}" class="btn btn-primary"><i></i>Back</a>
            </div>

            <div class="pull-right">
                <div class="hidden-print" style="display: inline-block;">
                    @if(Auth::user()->hasRole(['admin','super-admin','guest']))
                    @if ($invoice->invoice_status!="1")
                    <a  href="/fees/invoice/payment/{{$invoice->id}}" class="btn btn-block btn-default btn-icon glyphicons" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add Payment</a>
                    @endif
                   @endif
                </div>
                <div class="hidden-print" style="display: inline-block; margin-left: 5px;">
             <a href="/fees/invoice/pdf/report/{{$invoice->id}}" class="btn btn-block btn-default btn-icon glyphicons edit edit-invoice-btn"><i></i>Download PDF</a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix separator bottom"></div>
            <table class="table table-invoice" style="display: none;">
                <tbody>
                <tr>
                    <td style="width: 58%;"><div class="media"></div></td>
                    <td class="right">
                        <div class="innerL">
                            <button type="button" data-toggle="print" class="btn btn-default btn-icon glyphicons print hidden-print"><i></i> Print invoice</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="invoice-block">
                <table class="table table-invoice">
                    <tbody>
                    <tr>
                        <td style="width: 60%; border: 0px ; ">
                            <img src="{{URL::asset('assets/users/images/'.$institute->logo)}}" class="media-object pull-left" style="width:70px;height:auto; margin-right: 7px;">
                            <p class="lead" style="display: inline-block; margin-top: 8px;">{{$institute->institute_name}}</p>
                            <address class="margin-none">
                                <br>
                            </address>
                        </td>
                        <td class="right" style="border: 0px ;">
                            {{$institute->address1}}<br>
                            {{$institute->phone}}                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-top: none;">
                            <div class="row fees-info">
                                <div class="col-md-4 col-sm-4 col-xs-4 ">
                                    <label>Billed To</label>
                                    <div style="font-size: 12px; line-height: 20px;">
                                        {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}                                               <br>
                                        <address class="margin-none" style="line-height: 20px;">
                                            Admission No : 222                                                    <br>
                                            E-mail : <a href="#">{{$std->email}}</a>
                                            <br>
                                            Contact No. : {{$std->phone}}                                                    <br>
                                            <!-- Address : -->
                                        </address>
                                    </div>

                                    <div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <div class="">
                                        <label class="control-label" for="firstname">Due Date:</label>
                                            @php $due_date=date('Y-m-d',strtotime($fees->due_date)); @endphp
                                        {{$due_date}}
                                        <div>
                                        </div>
                                    </div>
                                    <div class="" style="margin-top: 10px;">
                                        <label class="control-label" for="firstname">Fees Type</label>
                                        <div>
                                            {{$fees->fees_type()->fee_type_name}}                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-sm-5 col-xs-5 text-right">
                                    <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px;">
                                        admision Fee                                                                            </div>
                                    <div class="">
                                        <label class="control-label" for="firstname">Invoice #{{$invoice->id}}</label><br>

                                        @if ($invoice->invoice_status=="2")
                                            <span  class="label label-danger">Un-Paid</span>
                                        @elseif ($invoice->invoice_status=="1")
                                            <span   class="label label-primary">Paid</span>
                                        @else
                                            <span  class="label label-success">Partail Payment</span>
                                        @endif

                                    </div>
                                    @if($day_fine_amount>0)

                                    <div class="fine-enable">
                                    <span  class="label label-success">Fine Enabled</span>
                                    </div>
                                    @endif
                                    <div class="" style="margin-top:10px;">
                                        <label class="control-label" for="firstname">Amount&nbsp;<span style="font-weight: 400;">(TK)</span> </label>
                                        <div>

                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div>
                                    @if($invoice->invoice_status=="1")
                                    <img src="{{URL::asset('assets/fees/icon-paid.gif')}}" alt="Paid Image" class="img-responsive" style="width:65px;margin-top: 5px;">  </div>
                                @endif
                                <div class="" style="margin-top: 20px; font-size: 12px; color:#a2a2a2;">
                                                                    </div>
                                <div class="clearfix"></div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div>
                    <label class="control-label">Line Items</label>
                </div>
                <table class="table table-invoice line-item-tbl table-striped table-bordered" style="font-size: 16px;">
                    <thead>
                    <tr>
                        <th style="" align="left">Line Item</th>
                        <th style="text-align: right;width : 100px;width: 20%;" align="right">Rate</th>
                        <th style="text-align: center;width : 100px;" align="right">Qty</th>
                        <th style="text-align: right;width : 100px;" align="right">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fees->feesItems() as $item)
                    <tr>
                        <td>
                            <div class="line-item-name">{{$item->item_name}}</div>
                            <div class="line-item-desc"></div>
                        </td>
                        <td style="text-align: right;">
                            <div class="line-item-name">{{$item->rate}}</div>
                        </td>
                        <td style="text-align: center;">
                            <div class="line-item-name">{{$item->qty}}</div>
                        </td>
                        <td style="text-align: right;">
                            <div class="line-item-name">{{$item->rate*$item->qty}}</div>
                        </td>
                    </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="separator bottom hidden-print"></div>
                <div class="row" style="font-size: 12px;">
                    <div class="col-md-4 col-sm-6 col-md-offset-1 pull-right">
                        <table class="table table-borderless table-condensed cart_total">
                            <tbody>
                            <tr>
                                <td class="right">Sub Total:</td>
                                <td class="right" style="float: right">
                                    @php $subtotal=0; $totalAmount=0; $totalDiscount=0; $getDueFine=0;  @endphp
                                    @foreach($fees->feesItems() as $amount)
                                        @php $subtotal += $amount->rate*$amount->qty;@endphp

                                    @endforeach
                                    {{$subtotal}}</td>
                            </tr>

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


                            {{--                            {{$totalAmount}}--}}

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


                            <tr>
                                <td class="right" style="border: none;">
                                    Due Fine
                                </td>
                                <td class="right" style="border: none;float: right" >
                                    {{$getDueFine}}
                                </td>
                            </tr>


                            <tr>
                                <td class="right" style="border: none;">
                                    Discount
                                </td>
                                <td class="right" style="border: none;float: right" > - {{$totalDiscount}}
                                </td>
                            </tr>
                            {{--<tr>--}}
                            {{--<td class="right" style="border: none;">Tax:</td>--}}
                            {{--<td class="right" style="border: none;">0.00 BDT.</td>--}}
                            {{--</tr>--}}
                            <tr>
                                <td class="right">Total:</td>
                                <td class="right" style="float: right">
                                        {{$totalAmount+$getDueFine}}
                                </td>
                            </tr>
                            <tr>
                                <td class="right" style="border: none;">Total Amount Paid:</td>
                                <td class="right" style="border: none; float: right">
                                    @php  $totalAmountPaid=0;  @endphp
                                    @if(!empty($paymentList))
                                        @foreach($paymentList as $payment)
                                            @php $totalAmountPaid=$totalAmountPaid+$payment->payment_amount @endphp
                                        @endforeach
                                        @if($invoice->invoice_status=="2")
                                            {{ $totalAmountPaid}}
                                        @elseif($invoice->invoice_status=="1")
                                            {{$totalAmount+$getDueFine}}
                                        @endif
                                        {{--                                        {{$totalAmountPaid}}--}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="right">Due Amount:</td>
                                @if($invoice->invoice_status=="1")
                                    <td class="right strong" style="float: right">0</td>

                                @elseif($invoice->invoice_status=="2")
                                    <td class="right strong" style="float: right">{{$totalAmount+$getDueFine}}</td>

                                @else
                                    <td class="right strong" style="float: right">{{$totalAmount-$totalAmountPaid}}</td>


                                @endif

                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-7 col-sm-6 pull-left">

                        <div class="box-generic" style="min-height: 142px; text-align: justify;">
                            <p class="margin-none"><strong>Comments:</strong><br>Pay Fast </p>
                        </div>
                    </div>
                </div>

                    @if($paymentList->count()>0)
                    <div>
                    <label class="control-label">Payment Transactions</label>
                </div>
                <table class="table table-bordered table-striped" style="font-size: 16px;">
                    <thead>
                    <tr>
                        <th style="width: 1%;" class="center">No.</th>
                        <th>Payment Method</th>
                        <th>Transaction Id/Cheque No.</th>
                        <th>Payment Date</th>
                        <th>Payment Status</th>
                        <th align="right">Payment Amount</th>
                        {{--<th class="hidden-print">Action</th>--}}

                    </tr>

                    </thead>
                    <tbody>
                        @php $i=1; $payment_amount=0; @endphp
                        @foreach($paymentList as $payment)
                        <tr>
                            <td >{{$i++}}</td>
                            <td >{{$payment->payment_method()->method_name}}</td>
                            <td >{{$payment->transaction_id}}</td>
                            <td >{{$payment->payment_date}}</td>
                            <td >{{$payment->payment_status}}</td>
                            <td >{{$payment->payment_amount}}</td>
                             @php $extra_payment=$payment->extra_payment_amount @endphp
                            {{--<td > <a href="/fees/invoice/payment/update/{{$payment->id}}"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-edit"></i></a> </td>--}}
                        </tr>
                        @endforeach
                          </tbody>
                </table>
                @endif
            </div>

            @if($invoiceFine->count()>0)
                <div>
                    <label class="control-label">All Fine List</label>
                </div>
                <table class="table table-bordered table-striped" style="font-size: 16px;">
                    <thead>
                    <tr>
                        <th style="width: 1%;" class="center">No.</th>
                        <th>Fees Fine</th>
                        <th>Fine Type</th>
                        <th>Payment Date</th>
                   </tr>

                    </thead>
                    <tbody>
                    @foreach($invoiceFine as $fine)
                        <tr>
                            <td >1</td>
                            <td >{{$fine->fine_amount}}</td>
                            <td >{{$fine->status}}</td>
                            <td >{{$fine->late_day}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif


            <hr>
            @php $i=1;  @endphp
            @if(!empty($payment_extra->student_id))
            <div>
                <label class="control-label">Advance Payments</label>
            </div>
            <table class="table table-bordered table-striped" style="font-size: 16px;">
                <thead>
                <tr>
                    <th style="width: 1%;" class="center">Id</th>
                    <th>Current Advance Payment</th>
                    <th>Total Advance Payment</th>
                </tr>
                </thead>
                <tbody>
                        <tr>
                            <td >{{$i++}}</td>
                            <td >@if(!empty($extra_payment)) {{$extra_payment}} @else 0 @endif</td>
                            <td >{{$payment_extra->extra_amount}}</td>

                        </tr>
                </tbody>
            </table>
            @endif

            <div class="clearfix"></div>
        </div>
    </div>
        @endsection

        @section('page-script')

            $(document).ready(function(){
                $('#feesListTable').DataTable();
            });


@endsection


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
    @php $multipleFeesAmount=0;  $attendanceFine=0; $invoiceArrayList=array(); $totalDiscount=0; $totalWaiver=0;  $day_fine_amount=0;  @endphp
    @foreach($invoiceList as $invoice)
        @if($invoice->invoice_type=="1")
            @php $fees=$invoice->fees(); $singleFeesAmount=0; @endphp
            @foreach($fees->feesItems() as $item)
                @php $singleFeesAmount +=$item->rate*$item->qty; @endphp
            @endforeach


            @php
                $dueFinePaid=$invoice->invoice_payment_summary();
                $var_dueFine=0;
                if($dueFinePaid){
                    $var_dueFine = json_decode($dueFinePaid->summary);
                }
            @endphp


            @if(!empty($var_dueFine))
                @php $getDueFine=$var_dueFine->due_fine->amount; @endphp
            @endif
                    @if(!empty($invoice->findReduction()))
                        @php $getDueFine=$invoice->findReduction()->due_fine; @endphp
                    @else
                        @php $getDueFine=get_fees_day_amount($invoice->fees()->due_date) @endphp
                    @endif



            {{--Discount Calculate Code--}}
            @if($discount = $invoice->fees()->discount())
                @php $discountPercent=$discount->discount_percent;
                          $totalDiscount+=(($singleFeesAmount*$discountPercent)/100);
                @endphp
            @endif

            {{--End Discount Calculate Code--}}

            {{--Waiver Calculate Code --}}
            @if($invoice->waiver_type=="1")
                @php $totalWaiver+=(($singleFeesAmount*$invoice->waiver_fees)/100);
                @endphp
            @elseif($invoice->waiver_type=="2")
                @php $totalWaiver+=$invoice->waiver_fees;
                @endphp

            @endif
             {{--end Waiver Calculate --}}

            {{--Due Fine Aamount Code--}}
           @php
                $day_fine_amount+=$getDueFine;
            @endphp
            {{--End Due Fine Amount--}}


            @php $multipleFeesAmount+=$singleFeesAmount;
                $invoiceArrayList[$invoice->id]= $singleFeesAmount;
            @endphp
        @endif
        @if($invoice->invoice_type=="2")
            @php $attendanceFine+=$invoice->invoice_amount;
                $invoiceArrayList[$invoice->id]= $attendanceFine;
            @endphp
        @endif
    @endforeach
    {{--<pre>--}}
    {{--{{$totalDiscount}};--}}
    {{--wa={{$totalWaiver}};--}}
    {{--wa={{$day_fine_amount}};--}}
        {{--</pre>--}}

    @php $totalAmount=$multipleFeesAmount+$attendanceFine+$day_fine_amount-$totalDiscount-$totalWaiver; @endphp

    <div class="col-md-12">
        <div class="innerAll shop-client-products cart invoice col-md-9">
            <h3 class="">Invoice</h3>
            <hr>
            <div class="pull-right">
                @if($invoiceStatusCheck->count()>0)

                    @else
                <div class="hidden-print" style="display: inline-block;">
                    <a  href="/fees/invoice/student/payment/process/{{implode(',',$invoiceArray)}}/{{$totalAmount}}/" class="btn btn-block btn-default btn-icon glyphicons" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add Payment</a>
                </div>
                @endif
                <div class="hidden-print" style="display: inline-block; margin-left: 5px;">
             <a href="/fees/invoice-all/pdf/{{implode(',',$invoiceArray)}}" class="btn btn-block btn-default btn-icon glyphicons edit edit-invoice-btn"><i></i>Download PDF</a>
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
                                        {{$studentInfo->first_name.' '.$studentInfo->middle_name.' '.$studentInfo->last_name}}                                               <br>
                                        <address class="margin-none" style="line-height: 20px;">
                                            Admission No : 222                                                    <br>
                                            E-mail : <a href="#">{{$studentInfo->email}}</a>
                                            <br>
                                            Contact No. : {{$studentInfo->phone}}                                                    <br>
                                            <!-- Address : -->
                                        </address>
                                    </div>

                                    <div>
                                    </div>
                                </div>

                                <div class="col-md-5 col-sm-5 col-xs-5 text-right pull-right">
                                    {{--<div style="font-weight: bold; font-size: 15px; margin-bottom: 15px;">--}}
                                        {{--admision Fee                                                                            </div>--}}
                                    {{--<div class="">--}}
                                        {{--<label class="control-label" for="firstname">Invoice #{{$invoice->id}}</label><br>--}}


                                            @if($invoiceStatusCheck->count()>0)
                                                <span  class="label label-success">Paid</span>
                                            @else
                                                <span  class="label label-danger">Un-Paid</span>
                                            @endif


                                    {{--@if($day_fine_amount>0)--}}

                                    {{--<div class="fine-enable">--}}
                                    {{--<span  class="label label-success">Fine Enabled</span>--}}
                                    {{--</div>--}}
                                    {{--@endif--}}
                                    <div class="" style="margin-top:10px;">
                                        <label class="control-label" for="firstname">Amount: &nbsp;<span style="font-weight: 700;">{{$totalAmount}}</span> </label>
                                        <div>

                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div>
                                    {{--@if($invoice->invoice_status=="1")--}}
                                    {{--<img src="{{URL::asset('assets/fees/icon-paid.gif')}}" alt="Paid Image" class="img-responsive" style="width:65px;margin-top: 5px;">  </div>--}}
                                {{--@endif--}}
                                <div class="" style="margin-top: 20px; font-size: 12px; color:#a2a2a2;">
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
                                        @php $multipleFeesAmount=0;  $attendanceFine=0;  @endphp
                                        @foreach($invoiceList as $invoice)
                                            @if($invoice->invoice_type=="1")
                                               @php $fees=$invoice->fees(); $singleFeesAmount=0; @endphp

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
                                            @php $singleFeesAmount +=$item->rate*$item->qty; @endphp
                                        @endforeach
                                               @php $multipleFeesAmount+=$singleFeesAmount; @endphp
                                            @endif
                                            @if($invoice->invoice_type=="2")
                                                @php $attendanceFine=$invoice->invoice_amount @endphp
                                               @endif

                                        @endforeach
                                        </tbody>
                                    </table>

                                <div class="clearfix"></div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="separator bottom hidden-print"></div>
                <div class="row" style="font-size: 12px;">
                    <div class="col-md-4 col-sm-6 col-md-offset-1 pull-right">
                        <table class="table table-borderless table-condensed cart_total">
                            <tbody>
                            <tr>
                                <td class="right">Sub Total:</td>
                                <td class="right" style="float: right">{{$multipleFeesAmount}}
                                </td>
                            </tr>

                            <tr>
                                <td class="right" style="border: none;">
                                    Discount
                                </td>
                                <td class="right" style="border: none;float: right" >- {{$totalDiscount}}
                                </td>
                            </tr>

                            <tr>
                                <td class="right" style="border: none;">
                                    Waiver
                                </td>
                                <td class="right" style="border: none;float: right" >- {{$totalWaiver}}
                                </td>
                            </tr>

                            <tr>
                                <td class="right" style="border: none;">
                                    Due Fine
                                </td>
                                <td class="right" style="border: none;float: right" > {{$day_fine_amount}}
                                </td>
                            </tr>


                            <tr>
                                <td class="right" style="border: none;">
                                    Attendance Fine
                                </td>
                                <td class="right" style="border: none;float: right" > {{$attendanceFine}}
                                </td>
                            </tr>
                            <tr>
                                <td class="right">Total Amount:</td>
                                <td class="right" style="float: right"> {{$totalAmount}}
                                </td>
                            </tr>

                            <tr>
                                <td class="right">Total Paid Amount:</td>
                                <td class="right" style="float: right">
                                    @if($invoiceStatusCheck->count()>0)
                                             {{$totalAmount}}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="right">Total Paid Due:</td>
                                <td class="right" style="float: right">
                                    @if($invoiceStatusCheck->count()>0)

                                       @else
                                        {{$totalAmount}}
                                    @endif
                                </td>
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

            <div class="clearfix"></div>
        </div>
    </div>
        @endsection

        @section('page-script')

            $(document).ready(function(){
                $('#feesListTable').DataTable();
            });


@endsection


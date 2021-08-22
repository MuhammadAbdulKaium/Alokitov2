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
                                </div>

                                <div class="col-md-5 col-sm-5 col-xs-5 text-right pull-right">
                                    <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px;">    </div>
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

                                    <div class="" style="margin-top:10px;">
                                        <label class="control-label" for="firstname">Amount&nbsp;<span style="font-weight: 400;"> {{$invoice->invoice_amount}} (TK)</span> </label>
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
                    <label class="control-label">Attendance Fine</label>
                </div>
                <table class="table table-invoice line-item-tbl table-striped table-bordered" style="font-size: 16px;">
                    <thead>
                    <tr>
                        <th style="" align="left">Fine Name</th>
                        <th style="text-align: right;width : 100px;width: 20%;" align="right">Rate</th>
                        <th style="text-align: center;width : 100px;" align="right">Qty</th>
                        <th style="text-align: right;width : 100px;" align="right">Total</th>
                    </tr>

                    <tr>
                        <td style="" align="left">Attendance Fine Name</td>
                        <td style="text-align: right;width : 100px;width: 20%;" align="right">{{$invoice->invoice_amount}}</td>
                        <td style="text-align: center;width : 100px;" align="right">1</td>
                        <td style="text-align: right;width : 100px;" align="right">{{$invoice->invoice_amount}}</td>
                    </tr>

                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class="separator bottom hidden-print"></div>

    </div>
        @endsection

        @section('page-script')

            $(document).ready(function(){
                $('#feesListTable').DataTable();
            });


@endsection


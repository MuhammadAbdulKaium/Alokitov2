
{{--<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />--}}

<link href="{{public_path('template-2/css/bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<style>
    @page { margin: 0px; }
    body { margin: 0px; }
</style>
<div class="container card-box" style="margin-bottom: 15px;">
    <div id="piInvoice_pdf">
        <div class="row">
            <div class="col-md-4">
                <div class="pull-left">
                <img alt="Invoice" width="20%" height="20%" src="http://alokitolive.com/asset/logos/tYxmnsmQOOqif8PSgKPs.jpg" style="vertical-align: middle; padding-top: 2%;"></div>
            </div>

            <div class="col-md-8">
                <div class="pull-right">
                    <div class="text-center">
                <h1>Alokito School Management Software</h1>
                    <address style="margin-left: 100px; margin-right: 100px">
                        <strong>Phone:</strong> +880 1974 328526
                        <strong>Email:</strong> info@alokitosoftware.com <br>
                      <p>Venus Complex (Ground Floor), Kha-199/3 & 199/4,, Bir Uttam Rafiqul Islam Ave,Middle Badda, Dhaka 1212</p>

                    </address>
                </div>
                </div>
            </div>
        </div>
        <hr style="margin-top: 0px; margin-bottom: 0px;">
        <div class="row">
            <h4 align="center">Invoice # {{$smsCreditProfile->id}} <strong>
                    <small></small></strong>
            </h4>
            {{--<div class="col-md-5">--}}
                {{--<address><strong>Bill To:<br></strong> <strong>Romesh Shil<br></strong> <span style="">--}}
               {{--Naogaon, Bangladesh <br></span> <span style="display: none;">--}}
               {{--<br></span> <span style="">--}}
               {{--Rajshahshi<br></span> <span style="">--}}
               {{--1200 <br></span> <span style="display: none;">--}}
               {{--<br></span> <span style="display: none;">--}}
               {{--<br></span>--}}
                {{--</address>--}}
            {{--</div>--}}

            <div class="col-md-6">
                <div class="pull-left">
                    <address><strong>Bill To:<br></strong> <strong>{{$instituteProfile->institute_name}}<br></strong>
                        <strong>Phone:</strong><span>{{$instituteProfile->phone}}</span>
                        <br><strong>Email:</strong><span> {{$instituteProfile->email}}</span>
                        <br>
                        <span style="">
              {{$instituteProfile->address1}} <br></span> <span style="display: none;">
               <br></span>
                    </address>
                </div>
            </div>

            <div class="col-md-6">
                <div class="pull-right">
                    <p><strong>Invoice Number: </strong>{{$smsCreditProfile->id}}</p>
                    <p><strong>Invoice Date: </strong>{{date('D F j, Y', strtotime($smsCreditProfile->accepted_date))}}</p>
                    {{--<p><strong>Due Date: </strong>{{date('D F j, Y', strtotime("+1 month",$smsCreditProfile->accepted_date))}}</p>--}}
                    <p><strong>Amount: </strong> {{$smsCreditProfile->sms_amount*$smsPrice}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive" style="margin-top: 20px;">
                    <table class="table ">
                        <thead style="background-color: rgb(51, 51, 51);">
                        <tr>
                            <th style="color: rgb(255, 255, 255); text-align: left;">Items Name</th>
                            <th style="color: rgb(255, 255, 255); text-align: right;">Quantity</th>
                            <th style="color: rgb(255, 255, 255); text-align: right;">Price</th>
                            <th style="color: rgb(255, 255, 255); text-align: right;">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align: left;"><span style="color: rgb(119, 119, 119);">New SMS Credit</span></td>
                            <td style="text-align: right;">{{$smsCreditProfile->sms_amount}}</td>
                            <td style="text-align: right;">{{$smsPrice}}</td>
                            <td style="text-align: right;">{{$smsCreditProfile->sms_amount*$smsPrice}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
            </div>
        </div>
        <div class="row" style="margin-right:0px>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6 col-md-offset-6 col-lg-offset-6 col-sm-offset-6 col-xs-offset-6">
                <p class="text-right"><b>Sub-total: </b>{{$smsCreditProfile->sms_amount*$smsPrice}}</p>
                <hr>
                <h3 class="text-right">Invoice Amount:  {{$smsCreditProfile->sms_amount*$smsPrice}}</h3>
                <hr>
                    @if($smsCreditProfile->payment_status==1)
                <p class="text-right">Amount Paid: {{$smsCreditProfile->sms_amount*$smsPrice}}</p>
                @else
                         <p class="text-right"><b>Amount Due:</b> {{$smsCreditProfile->sms_amount*$smsPrice}}</p>
                @endif
    </div>
        </div>
        <div style="display: none;">
            Notes:
        </div>
        <div style="text-align: center; display: none;">
            <hr>
        </div>
@if($smsCreditProfile->payment_status==1)
        <div class="row">
            <div class="col-md-12" style="margin-left: 200px">
                 <img width="30%" src="https://toppng.com/public/uploads/preview/paid-stamp-115234374820uwv6olpxz.png">
                    </div>
            </div>
        @endif

    </div>
</div>
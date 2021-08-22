<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {{--<style>body { font-family:  'Siyamrupali'; } </style>--}}
    <style>
        @font-face {
            font-family: 'Siyamrupali';
            /*font-style: normal;*/
            /*font-weight: normal;*/
            /*src: url(http://venusitltd.com/fonts/Siyamrupali.ttf) format('truetype');*/
        }

        body {  font-family: 'Siyamrupali';  }


        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #000;
            text-decoration: none;
        }


        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        #logo {
            float: left;
        }

        #logo img {
            height: 70px;
        }

        #company {
            float: left;
            text-align: center;
            margin-left: 80px;
        }


        #details {
            margin-top: -10px;
            margin-bottom: 10px;
        }

        #client {
            padding-left: 0px;
            float: left;
        }
        #client .name {
            font-size: 10px;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #0087C3;
            font-size: 10px;
            line-height: 1em;
            font-weight: normal;
            margin: 0  0 10px 0;
        }

        #invoice .date {
            font-size: 10px;
            color: #777777;
        }


        body { font: 9px Georgia, serif; }
        table { border-collapse: collapse; font-size: 10px; }
        table td, table th { border: 1px solid #6f6f6f; padding: 2px; }
        #tableDesign {
            padding: 10px;
            min-height: 1000px;
        }
        #tableDesign { clear: both; width: 100%; margin: 0px 0 0 0; }
        #tableDesign th { background: #eee; font-size: 10px; font-weight: normal }
        #tableDesign textarea { width: 80px; height: 50px; }
        #tableDesign tr.item-row td { border: 0; vertical-align: top; }

        .calculate{
            width: 100%;
            text-align: right;
        }



        .teacher {
            float: left;
            font-size: 10px;
            border-top:1px solid #6f6f6f;
            width: 25%;
            text-align: center;
            padding-top: 5px;
        }

        .student {
            float: right;
            font-size: 10px;
            border-top:1px solid #6f6f6f;
            width: 30%;
            text-align: center;
            padding-top: 5px;
        }

        .invoice-date {
            float: left;
            font-size: 10px;
            width: 40%;
            text-align: center;
            padding-top: 5px;
        }
        .invoice-body{
            float: left;
            width: 30%;
            margin-left: 30px;
        }
        .copy-text {
            font-size: 11px;
            background:#48b04f;
            color: #fff;
            text-align: center;
            width: 29%;
            padding: 3px;
        }
        .item-list{
            height: 526px;
            border: 1px solid #6f6f6f;
        }
        .footer-section {
            margin-top: 20px;
        }


    </style>
</head>
<body>
<div class="invoice-section" style="width: 100%;">
    <div class="invoice-body">
        @php
            $std=$invoice->payer();
            $enroll=$std->singleEnroll();
            $fees=$invoice->fees();
        @endphp
        <header class="clearfix" style="100%;">

{{--                {{trans('fees_modules/invoice_fees_report.invoice_title')}}--}}
            <div id="logo" style="margin-top:10px">
                <img src="{{public_path('assets/users/images/'.$institute->logo)}}" style="height: 40x; width: 40px">
            </div>

            <div id="company" style=" width: 300px; margin-top: -30px;  ">
                <h2 class="name">{{$institute->institute_name}}</h2>
                Phone: {{$institute->phone}}
                <a href="#">{{$institute->address1}} </a>
                <a href="#">Email:{{$institute->email}} ,Web:{{$institute->website}}</a>

            </div>
            <div class="copy-text" style="margin-top: 5px">School Copy</div>
        </header>
        <div id="details" class="clearfix" style="width:100%; font-size: 10px;">
            <div id="client" style="margin-top: -10px;">
                <h2 class="name">{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}} <br>
                </h2>
                Email: {{$std->email}}
                <br>
                @if(!empty($std->phone))
                    Phone : {{$std->phone}}
                @endif
            </div>
            <div id="invoice" style="width: 300px; float:right; margin-top: -30px;">
                <div class="to">Invoice: #{{$invoice->id}}</div>
            </div>
        </div>
        <div class="item-list">
            <div class="first_table" style="height: 407px !important;">
                <table  border="0" cellspacing="0" cellpadding="0" id="tableDesign">
                    <thead>
                    <tr>
                        <th class="no">#No</th>
                        <th class="desc">Description</th>
                        <th class="unit">Amount</th>
                        <th class="qty">Quantity</th>
                        <th class="total">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="no">1</td>
                            <td  style="font-family:Siyamrupali;" class="desc">Attendance Fine</td>
                            <td class="unit">{{$invoice->invoice_amount}}</td>
                            <td class="unit">1 </td>
                            <td class="unit">{{$invoice->invoice_amount}} </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="footer-section" style="width: 100%">
            <div class="teacher">Teacher </div>
            <div class="invoice-date">Date: {{date("d-m-Y")}}</div>
            <div class="student">Student</div>
        </div>
    </div>
    {{--..............--}}
    <div class="invoice-body">
        @php
            $std=$invoice->payer();
            $enroll=$std->singleEnroll();
            $fees=$invoice->fees();
        @endphp
        <header class="clearfix" style="100%;">

            {{--    {{trans('fees_modules/invoice_fees_report.invoice_title')}}--}}
            <div id="logo" style="margin-top:10px">
                <img src="{{public_path('assets/users/images/'.$institute->logo)}}" style="height: 40x; width: 40px">
            </div>


            <div id="company" style=" width: 300px; margin-top: -30px;  ">
                <h2 class="name">{{$institute->institute_name}}</h2>
                Phone: {{$institute->phone}}
                <a href="#">{{$institute->address1}} </a>
                <a href="#">Email:{{$institute->email}} ,Web:{{$institute->website}}</a>

            </div>
            <div class="copy-text" style="margin-top: 5px">Student Copy</div>
        </header>
        <div id="details" class="clearfix" style="width:100%; font-size: 10px;">
            <div id="client" style="margin-top: -10px;">
                <h2 class="name">{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}} <br>
                </h2>
                Email: {{$std->email}}
                <br>
                @if(!empty($std->phone))
                         Phone : {{$std->phone}}
                    @endif
            </div>
            <div id="invoice" style="width: 300px; float:right; margin-top: -30px;">
                <div class="to">Invoice: #{{$invoice->id}}</div>
            </div>
        </div>
        <div class="item-list">
            <div class="first_table" style="height: 407px !important;">
                <table  border="0" cellspacing="0" cellpadding="0" id="tableDesign">
                    <thead>
                    <tr>
                        <th class="no">#No</th>
                        <th class="desc">Description</th>
                        <th class="unit">Amount</th>
                        <th class="qty">Quantity</th>
                        <th class="total">Total</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td class="no">1</td>
                        <td  style="font-family:Siyamrupali;" class="desc">Attendance Fine</td>
                        <td class="unit">{{$invoice->invoice_amount}}</td>
                        <td class="unit">1 </td>
                        <td class="unit">{{$invoice->invoice_amount}} </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer-section" style="width: 100%">
            <div class="teacher">Teacher </div>
            <div class="invoice-date">Date: {{date("d-m-Y")}}</div>
            <div class="student">Student</div>
        </div>
    </div>

    {{--.....................--}}
    <div class="invoice-body">
        @php
            $std=$invoice->payer();
            $enroll=$std->singleEnroll();
            $fees=$invoice->fees();
        @endphp
        <header class="clearfix" style="100%;">

            {{--    {{trans('fees_modules/invoice_fees_report.invoice_title')}}--}}
            <div id="logo" style="margin-top:10px">
                <img src="{{public_path('assets/users/images/'.$institute->logo)}}" style="height: 40x; width: 40px">
            </div>


            <div id="company" style=" width: 300px; margin-top: -30px;  ">
                <h2 class="name">{{$institute->institute_name}}</h2>
                Phone: {{$institute->phone}}
                <a href="#">{{$institute->address1}} </a>
                <a href="#">Email:{{$institute->email}} ,Web:{{$institute->website}}</a>

            </div>
            <div class="copy-text" style="margin-top: 5px">Bank Copy</div>
        </header>
        <div id="details" class="clearfix" style="width:100%; font-size: 10px;">
            <div id="client" style="margin-top: -10px;">
                <h2 class="name">{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}} <br>
                </h2>
                Email: {{$std->email}}
                <br>
                @if(!empty($std->phone))
                    Phone : {{$std->phone}}
                @endif
            </div>
            <div id="invoice" style="width: 300px; float:right; margin-top: -30px;">
                <div class="to">Invoice: #{{$invoice->id}}</div>
            </div>
        </div>
        <div class="item-list">
            <div class="first_table" style="height: 407px !important;">
                <table  border="0" cellspacing="0" cellpadding="0" id="tableDesign">
                    <thead>
                    <tr>
                        <th class="no">#No</th>
                        <th class="desc">Description</th>
                        <th class="unit">Amount</th>
                        <th class="qty">Quantity</th>
                        <th class="total">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="no">1</td>
                            <td  style="font-family:Siyamrupali;" class="desc">Attendance Fine</td>
                            <td class="unit">{{$invoice->invoice_amount}}</td>
                            <td class="unit">1 </td>
                            <td class="unit">{{$invoice->invoice_amount}} </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer-section" style="width: 100%">
            <div class="teacher">Teacher </div>
            <div class="invoice-date">Date: {{date("d-m-Y")}}</div>
            <div class="student">Student</div>
        </div>
    </div>


</div>
</body>
</html>
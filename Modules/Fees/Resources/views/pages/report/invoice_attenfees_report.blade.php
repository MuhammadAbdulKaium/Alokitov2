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


@php $multipleFeesAmount=0;  $attendanceFine=0; $invoiceArrayList=array(); $totalDiscount=0; $totalWaiver=0;  $day_fine_amount=0;  @endphp
@foreach($invoiceList as $invoice)
    @if($invoice->invoice_type=="1")
        @php $fees=$invoice->fees(); $singleFeesAmount=0; @endphp
        @foreach($fees->feesItems() as $item)
            @php $singleFeesAmount +=$item->rate*$item->qty; @endphp
        @endforeach

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
            $day_fine_amount+=get_fees_day_amount($fees->due_date);
        @endphp
        {{--End Due Fine Amount--}}





        @php $multipleFeesAmount+=$singleFeesAmount;
                $invoiceArrayList[$invoice->id]= $singleFeesAmount;
        @endphp
    @endif
    @if($invoice->invoice_type=="2")
        @php $attendanceFine=$invoice->invoice_amount;
                $invoiceArrayList[$invoice->id]= $attendanceFine;
        @endphp
    @endif
@endforeach

@php $totalAmount=$multipleFeesAmount+$attendanceFine+$day_fine_amount-$totalDiscount-$totalWaiver; @endphp


<div class="invoice-section" style="width: 100%;">
    <div class="invoice-body">
        <header class="clearfix" style="100%;">
            <div id="logo" style="margin-top:10px">
                                <img src="{{public_path('assets/users/images/'.$institute->logo)}}" style="height: 40x; width: 60px">
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
                <h2 class="name">{{$studentInfo->first_name.' '.$studentInfo->middle_name.' '.$studentInfo->last_name}} <br>
                </h2>
                Email: {{$studentInfo->email}}
                <br>
                @if(!empty($studentInfo->phone))
                    Phone : {{$studentInfo->phone}}
                @endif
            </div>
            <div id="invoice" style="width: 300px; float:right; margin-top: -30px;">
                <div class="to">Invoice: #</div>

                <div class="date">Due Date:</div>
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
                    @php $multipleFeesAmount=0;  $attendanceFine=0; $i=1;  @endphp
                    @foreach($invoiceList as $invoice)
                        @if($invoice->invoice_type=="1")
                            @php $fees=$invoice->fees(); $singleFeesAmount=0; @endphp

                            @foreach($fees->feesItems() as $item)

                                <tr>
                                    <td class="no">{{$i++ }}</td>
                                    <td  style="font-family:Siyamrupali;" class="desc">{{$item->item_name}} </td>
                                    <td class="unit">{{$item->rate }} </td>
                                    <td class="unit">{{$item->qty }} </td>
                                    <td class="unit">{{$item->rate*$item->qty}} </td>
                                </tr>
                    @php $singleFeesAmount +=$item->rate*$item->qty; @endphp
                    @endforeach
                    @php $multipleFeesAmount+=$singleFeesAmount; @endphp
                    @endif
                    @if($invoice->invoice_type=="2")
                        @php $attendanceFine=$invoice->invoice_amount @endphp
                    @endif

                    @endforeach
                </table>
            </div>
            <table class="calculate">
                <tr>
                    <td>Sub Total</td>
                    <td width="15%">{{$multipleFeesAmount}}</td>
                </tr>

                <tr>
                    <td>Due Fine</td>

                    <td>
                        {{$day_fine_amount}}
                    </td>
                </tr>


                <tr>
                    <td class="table_float" >Attendance Fine</td>
                    <td>{{$attendanceFine}}</td>
                </tr>



                <tr>
                    <td>Discount</td>
                    <td>{{$totalDiscount}}</td>
                </tr>

                <tr>
                    <td>Grand Total</td>
                    <td>
                        {{$totalAmount}}
                    </td>
                </tr>

                <tr>
                    <td>Total Amount Paid</td>
                    <td>
                        @if($invoiceStatusCheck->count()>0)
                            {{$totalAmount}}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>Total Due Amount</td>
                    <td>@if($invoiceStatusCheck->count()>0)

                        @else
                            {{$totalAmount}}
                        @endif</td>

                </tr>
                </tfoot>
            </table>
        </div>
        <div class="footer-section" style="width: 100%">
            <div class="teacher">Teacher </div>
            <div class="invoice-date">Date: {{date("d-m-Y")}}</div>
            <div class="student">Student</div>
        </div>
    </div>

    <div class="invoice-body">
        <header class="clearfix" style="100%;">

            {{--    {{trans('fees_modules/invoice_fees_report.invoice_title')}}--}}
            <div id="logo" style="margin-top:10px">
                <img src="{{public_path('assets/users/images/'.$institute->logo)}}" style="height: 40x; width: 60px">
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
                <h2 class="name">{{$studentInfo->first_name.' '.$studentInfo->middle_name.' '.$studentInfo->last_name}} <br>
                </h2>
                Email: {{$studentInfo->email}}
                <br>
                @if(!empty($studentInfo->phone))
                    Phone : {{$studentInfo->phone}}
                @endif
            </div>
            <div id="invoice" style="width: 300px; float:right; margin-top: -30px;">
                <div class="to">Invoice: #</div>

                <div class="date">Due Date:</div>
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
                    @php $multipleFeesAmount=0;  $attendanceFine=0; $i=1;  @endphp
                    @foreach($invoiceList as $invoice)
                        @if($invoice->invoice_type=="1")
                            @php $fees=$invoice->fees(); $singleFeesAmount=0; @endphp

                            @foreach($fees->feesItems() as $item)

                                <tr>
                                    <td class="no">{{$i++ }}</td>
                                    <td  style="font-family:Siyamrupali;" class="desc">{{$item->item_name}} </td>
                                    <td class="unit">{{$item->rate }} </td>
                                    <td class="unit">{{$item->qty }} </td>
                                    <td class="unit">{{$item->rate*$item->qty}} </td>
                                </tr>
                    @php $singleFeesAmount +=$item->rate*$item->qty; @endphp
                    @endforeach
                    @php $multipleFeesAmount+=$singleFeesAmount; @endphp
                    @endif
                    @if($invoice->invoice_type=="2")
                        @php $attendanceFine=$invoice->invoice_amount @endphp
                    @endif

                    @endforeach
                </table>
            </div>
            <table class="calculate">
                <tr>
                    <td>Sub Total</td>
                    <td width="15%">{{$multipleFeesAmount}}</td>
                </tr>

                <tr>
                    <td>Due Fine</td>

                    <td>
                        {{$day_fine_amount}}
                    </td>
                </tr>


                <tr>
                    <td class="table_float" >Attendance Fine</td>
                    <td>{{$attendanceFine}}</td>
                </tr>



                <tr>
                    <td>Discount</td>
                    <td>{{$totalDiscount}}</td>
                </tr>

                <tr>
                    <td>Grand Total</td>
                    <td>
                        {{$totalAmount}}
                    </td>
                </tr>

                <tr>
                    <td>Total Amount Paid</td>
                    <td>
                        @if($invoiceStatusCheck->count()>0)
                            {{$totalAmount}}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>Total Due Amount</td>
                    <td>@if($invoiceStatusCheck->count()>0)

                        @else
                            {{$totalAmount}}
                        @endif</td>

                </tr>
                </tfoot>
            </table>
        </div>
        <div class="footer-section" style="width: 100%">
            <div class="teacher">Teacher </div>
            <div class="invoice-date">Date: {{date("d-m-Y")}}</div>
            <div class="student">Student</div>
        </div>
    </div>

    <div class="invoice-body">
        <header class="clearfix" style="100%;">

            {{--    {{trans('fees_modules/invoice_fees_report.invoice_title')}}--}}
            <div id="logo" style="margin-top:10px">
                <img src="{{public_path('assets/users/images/'.$institute->logo)}}" style="height: 40x; width: 60px">
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
                <h2 class="name">{{$studentInfo->first_name.' '.$studentInfo->middle_name.' '.$studentInfo->last_name}} <br>
                </h2>
                Email: {{$studentInfo->email}}
                <br>
                @if(!empty($studentInfo->phone))
                    Phone : {{$studentInfo->phone}}
                @endif
            </div>
            <div id="invoice" style="width: 300px; float:right; margin-top: -30px;">
                <div class="to">Invoice: #</div>

                <div class="date">Due Date:</div>
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
                    @php $multipleFeesAmount=0;  $attendanceFine=0; $i=1;  @endphp
                    @foreach($invoiceList as $invoice)
                        @if($invoice->invoice_type=="1")
                            @php $fees=$invoice->fees(); $singleFeesAmount=0; @endphp

                            @foreach($fees->feesItems() as $item)

                                <tr>
                                    <td class="no">{{$i++ }}</td>
                                    <td  style="font-family:Siyamrupali;" class="desc">{{$item->item_name}} </td>
                                    <td class="unit">{{$item->rate }} </td>
                                    <td class="unit">{{$item->qty }} </td>
                                    <td class="unit">{{$item->rate*$item->qty}} </td>
                                </tr>
                    @php $singleFeesAmount +=$item->rate*$item->qty; @endphp
                    @endforeach
                    @php $multipleFeesAmount+=$singleFeesAmount; @endphp
                    @endif
                    @if($invoice->invoice_type=="2")
                        @php $attendanceFine=$invoice->invoice_amount @endphp
                    @endif

                    @endforeach
                </table>
            </div>
            <table class="calculate">
                <tr>
                    <td>Sub Total</td>
                    <td width="15%">{{$multipleFeesAmount}}</td>
                </tr>

                <tr>
                    <td>Due Fine</td>

                    <td>
                        {{$day_fine_amount}}
                    </td>
                </tr>


                <tr>
                    <td class="table_float" >Attendance Fine</td>
                    <td>{{$attendanceFine}}</td>
                </tr>



                <tr>
                    <td>Discount</td>
                    <td>{{$totalDiscount}}</td>
                </tr>

                <tr>
                    <td>Grand Total</td>
                    <td>
                        {{$totalAmount}}
                    </td>
                </tr>

                <tr>
                    <td>Total Amount Paid</td>
                    <td>
                        @if($invoiceStatusCheck->count()>0)
                            {{$totalAmount}}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>Total Due Amount</td>
                    <td>@if($invoiceStatusCheck->count()>0)

                        @else
                            {{$totalAmount}}
                        @endif</td>

                </tr>
                </tfoot>
            </table>
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice Report</title>
    @php  $batchString="Class" @endphp
    @if($report_type=="pdf")
        <style>


            * { margin: 0; padding: 0; }
            .header-info {
                clear: both;

            }

            .small-section {
                border-top: 2px solid #00a65a;
                padding: 10px;
            }

            .payment{
                padding-top: 10px;
                font-size: 16px;
            }


            body { font: 12px/1.4 Georgia, serif; }
            textarea { border: 0; font: 14px Georgia, Serif; overflow: hidden; resize: none; }
            table { border-collapse: collapse; }
            table td, table th { border: 1px solid black; padding: 5px; }
            #tableDesign {
                padding: 20px;
            }
            #tableDesign { clear: both; width: 100%; margin: 30px 0 0 0; border: 1px solid black; }
            #tableDesign th { background: #eee; }
            #tableDesign textarea { width: 80px; height: 50px; }
            #tableDesign tr.item-row td { border: 0; vertical-align: top; }
            #tableDesign td.description { width: 300px; }
            #tableDesign td.item-name { width: 175px; }
            #tableDesign td.description textarea, #tableDesign td.item-name textarea { width: 100%; }
            #tableDesign td.total-line { border-right: 0; text-align: right; }
            #tableDesign td.total-value { border-left: 0; padding: 10px; }
            #tableDesign td.total-value textarea { height: 20px; background: none; }
            #tableDesign td.balance { background: #eee; }
            #tableDesign td.blank { border: 0; }

            table#mytable,
            table#mytable td
            {
                border: none !important;
                text-align: center;
            }

            .header-section {
                width: 100%;
                position: relative;
                border-bottom: 2px solid #eee;
            }
            .header-section .logo {
                width: 30%;
                float: left;
            }

            .header-section .logo img {
                float: right;
            }

            .header-section .text-section {
                width: 70%;
                float: left;
                text-align: center;
                margin-top: 10px;
            }
            .header-section .text-section p {
                margin-right: 200px;
            }
            p.title {
                font-size: 25px;
                font-weight: bold;
                margin-top: 0px;
            }
            p.address-section {
                font-size: 12px;
                margin-top: -5px;
            }
            .report-title {
                width: 100%;
                margin: 0px;
                padding: 0px;
                text-align: center;
            }
            .report-title p {
                font-size: 13px;
                font-weight: 600;
                padding-top: 5px;

            }


        </style>

</head>
<body>

<div class="header">
    <div class="header-section">
        <div class="logo">
            <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
        </div>
        <div class="text-section">
            <p class="title">{{$instituteInfo->institute_name}}</p><br/><p class="address-section">{{'Address: '.$instituteInfo->address1.',Phone: '.$instituteInfo->phone}}<br/>{{'E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}} </p>
        </div>
        <div class="report-title">
            <p>{{$reportTitle}}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>

@endif
<div class="small-section" style="clear: both" >
    <div class="payment">Invoice Report:</div>
</div>

<table id="tableDesign" class="transactions_table">
    <thead>
    <tr>
        <th>Year</th>
        <th>Level</th>
        <th>{{$batchString}}</th>
        <th>Section</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Status</th>
    </tr>
    <thead>
    <tbody>
    <tr>
        <td ><b>{{$allInputs->academics_years}}</b></td>
        @if($allInputs->academics_level_value!="null")
            <td >{{$allInputs->academics_level}}</td>
        @else
            <td>---</td>
        @endif
        @if(($allInputs->batch_value!="null"))
            <td >{{$allInputs->batch}}</td>
        @else
            <td>---</td>
        @endif
        @if(($allInputs->section_value!="null"))
            <td >{{$allInputs->section}}</td>
        @else
            <td>---</td>
        @endif
        <td > <b>{{date("d-m-Y",strtotime($allInputs->search_start_date))}}</b></td>
        <td ><b>{{date("d-m-Y",strtotime($allInputs->search_end_date))}}</b></td>
        <td > <b>
                @if($allInputs->invoice_status==1)
                    Paid
                @elseif($allInputs->invoice_status==2)
                    Un-paid
                @elseif($allInputs->invoice_status==3)
                    Partial Paid
                @elseif($allInputs->invoice_status==4)
                    Cancel
                @endif

            </b></td>
    </tr>
    </tbody>
</table>

<table id="tableDesign" class="transactions_table" style="margin-top: -20px;">

    <thead>
    <tr>
        <th>Invoice Id</th>
        <th>Fee Name<</th>
        <th>Payer Name</th>
        <th>Amount</th>
        <th>Fees Amount</th>
        <th>Discount</th>
        <th>Due Fine</th>
        <th>Attendance Fine</th>
        <th>Paid Amount</th>
        <th>Status</th>
    </tr>
    </thead>

    </tr>
    <tbody>
    @php

        $i = 1; $totalDiscount=0; $getAttendFine=0; $getDueFine=0;  $due_fine_amount=0; $subTotalSum=0; $totalFeesAmount=0; $totalFeesAmountSum=0; $totalSumAmount=0; $totalDueAmountSum=0; $totalAttendanceAmountSum=0; $totalDiscountAmountSum=0; $totalPaidAmountSum=0;
    @endphp
    @foreach($allFeesInvoices as $invoice)

        <tr class="gradeX">
            <td>{{$invoice->id}}</td>
            @php
                $fees=$invoice->fees();
                $std=$invoice->payer();
              $day_fine_amount=get_fees_day_amount($fees->due_date);
            @endphp
            <td>{{$fees->fee_name}}</td>
            <td>{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}</td>

            {{-- attendance and due fine amount--}}

            @if($invoice->due_fine_amount())
                @php $due_fine_amount=$invoice->due_fine_amount()->fine_amount;
                @endphp
            @else
                @php $due_fine_amount=0;  @endphp
            @endif

            @if($invoice->attendance_fine_amount())
                @php $attendance_fine_amount=$invoice->attendance_fine_amount()->fine_amount;@endphp
            @else
                @php $attendance_fine_amount=0; @endphp
            @endif



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

            {{--/// discount calculate--}}
            @if($discount = $invoice->fees()->discount())
                @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
            @endif

            {{--caclulate Discount waiver--}}
            @if(!empty($invoice->waiver_fees))
                @php $totalDiscount=$totalDiscount+$totalWaiver @endphp
            @endif


            <td>{{$subtotal+$getAttendFine+$getDueFine-$totalDiscount}}</td>

            <td>{{$subtotal}}</td>
            <td>{{$totalDiscount}} </td>
            <td>{{$getDueFine}}</td>
            <td>{{$getAttendFine}}</td>

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
        </tr>

        @php
            $totalFeesAmountSum+=$subtotal+$getAttendFine+$getDueFine-$totalDiscount;
            $subTotalSum+=$subtotal;
            $totalDiscountAmountSum+=$totalDiscount;
            $totalDueAmountSum+=$getDueFine;
            $totalAttendanceAmountSum+=$getAttendFine;
            $totalSumAmount+=$invoice->totalPayment()+$due_fine_amount+$attendance_fine_amount;

        @endphp


    @endforeach

    <tr>
        <th colspan="3">Total</th>
        <th>{{$totalFeesAmountSum}}</th>
        <th >{{$subTotalSum}}</th>
        <th >{{$totalDiscountAmountSum}}</th>
        <th >{{$totalDueAmountSum}}</th>
        <th >{{$totalAttendanceAmountSum}}</th>
        <th >{{$totalSumAmount}}</th>
        <th>Status</th>
    </tr>
    <tbody>

    {{--<tr>--}}
    {{--<td># 1</td>--}}
    {{--<td>Paypal</th>--}}
    {{--<td>9893457358FHG</td>--}}
    {{--<td>20/20/2017</td>--}}
    {{--<td>TOtal</td>--}}
    {{--<td>TOtal</td>--}}
    {{--</tr>--}}
</table>
</main>
</body>
</html>
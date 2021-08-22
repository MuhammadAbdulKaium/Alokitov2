<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Fees Invoice List</title>
    <style>
        @page { margin: 100px 0px; }
        .header { position: fixed; left: 0px; top: -100px; right: 0px; height: 100px; text-align: center; }
        .footer { position: fixed; left: 0px; bottom: -50px; right: 0px; height: 50px;text-align: center;}
        .footer .pagenum:before { content: counter(page); }

    /* Table */
    .table-design {
        border-collapse: collapse;
        font-size: 12px;
        min-width: 100%;
        margin-left: 10px;
        margin-right: 15px;
        margin-top: 15px;
    }

    .table-design th,
    .table-design td {
        padding: 4px 12px;
        border: 1px solid #efefef;
    }
    .table-design .title {
        margin: 4px;
    }
        .table-design thead{
            display: table-row-group;
        }

    /* Table Header */
    .table-design thead th {
        text-transform: capitalize;
        color: #FFFFFF;
        font-weight: normal;
        background-color: #00b050;
    }
    .table-design thead th:first-child {
        text-align: left;
    }

    /* Table Body */
    .table-design tbody td {
        color: #353535;
        background-color: #d6ffda;
        text-align: center;
    }
    .table-design tbody th {
        text-align: left;
        font-weight: normal;
        background-color: #00b954;
        /*padding-right: 45px;*/
        color: #FFFFFF;
    }
    .table-design tbody tr:nth-child(odd) td {
        background-color: #ecffee;
    }
    .table-design tbody tr:nth-child(odd) th {
        background-color: #00b050;
    }
    .table-design tbody tr:last-child td {
        border: 0;
    }
    .table-design tbody tr:hover td {
        background-color: #ffffa2;
        border-color: #ffff0f;
    }

    /* Table Footer */
    .table-design tfoot th {
        border-top: 1px dashed #ff8100;
    }
    .table-design tfoot th:first-child {
        text-align: right;
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
            margin-top: -30px;
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
        <div style="clear: both;"></div>
    </div>
</div>
<div class="footer">
    Page <span class="pagenum"></span>
</div>

<div class="table-wrapper">
    @if(!empty($inviceByYearMonth))
    <table class="table-design" >
        <thead>
        <tr>
            <th width="5%">Month</th>
            <th>Invoice</th>
            <th>Fee Name</th>
            <th>Fees</th>
            <th>Amount</th>
            <th>Discount</th>
            <th>Due</th>
            <th>Attendance</th>
            <th>Paid</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($inviceByYearMonth as $yearMonth => $invoiceListArray)
            {{--        Found {{ sizeof($invoiceListArray) }} classes for {{ $yearMonth }}--}}

            <tr>
                @php $getAttendFine=0; $getDueFine=0; $i=1;  $rowSpan=sizeof($invoiceListArray);@endphp
                <th  rowspan="{{sizeof($invoiceListArray)}}">{{date('F',strtotime($yearMonth))}}</th>
                @foreach ($invoiceListArray as $invoice)
                    @php $i++ @endphp
                     @if($i==2)
                     @else
                          <tr>
                     @endif

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
                    <td>{{$invoice->id}}</td>
                    @php
                        $fees=$invoice->fees();
                        $std=$invoice->payer()
                    @endphp
                    <td>{{$fees->fee_name}}</td>

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

                    {{--<td> @if ($fees->partial_allowed==1) <span class="btn-orange">Yes<span> @else <span>No</span> @endif</td>--}}
                    {{--<td>{{date('m-d-Y',strtotime($fees->due_date))}}</td>--}}
                    {{--<td>{{$fees->fee_status}}</td>--}}

            </tr>



        @endforeach

        @endforeach
        </tbody>
    </table>
    @else
        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in text-center">
            <h5 class="text-bold"><i class="fa fa-warning"></i> No Fees Invoice found </h5>
        </div>
    @endif
</div>

</body>
</html>

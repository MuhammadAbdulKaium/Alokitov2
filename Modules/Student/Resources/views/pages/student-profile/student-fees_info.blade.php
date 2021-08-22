@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
    <style>
        .highlight {
            background: #48B04F !important;
            font-weight: 700;
            text-align: center;
            color: #fff !important;
        }

    </style>

    {{--<div class="box-body table-responsive">--}}
        {{--<div class="panel-body" style="background: #efefef; font-weight: 700">Fees Invoice List @if(!empty($personalInfo)) <a href="/student/profile/fees_info/download/{{$personalInfo->id}}" class="btn btn-primary pull-right">Download</a>  @endif</div>--}}
        {{--<div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">--}}
            {{--<div id="w1" class="grid-view">--}}
                {{--@if(!empty($inviceByYearMonth))--}}
                    {{--<table class="table table-bordered">--}}
                        {{--<tr class="highlight">--}}
                            {{--<thead>--}}
                            {{--<th class="highlight" width="20%">Month</th>--}}
                            {{--<th  class="highlight">Invoice ID</th>--}}
                            {{--<th class="highlight" >Fee Name</th>--}}
                            {{--<th class="highlight" >Fees</th>--}}
                            {{--<th class="highlight" >Discount</th>--}}
                            {{--<th class="highlight" >Due Fine</th>--}}
                            {{--<th class="highlight" >Paid Amount</th>--}}
                            {{--<th class="highlight">Status</th>--}}
                            {{--</thead>--}}
                        {{--</tr>--}}
                        {{--@foreach ($inviceByYearMonth as $yearMonth => $invoiceListArray)--}}
                            {{--        Found {{ sizeof($invoiceListArray) }} classes for {{ $yearMonth }}--}}

                            {{--<tr>--}}
                                {{--@php $getAttendFine=0; $getDueFine=0;  @endphp--}}
                                {{--<td class="highlight" rowspan="{{sizeof($invoiceListArray)}}" style="margin: 0px auto;">{{date('F',strtotime($yearMonth))}}</td>--}}
                            {{--@foreach ($invoiceListArray as $invoice)--}}

                                {{-- attendance and due fine amount--}}

                                {{--@if($invoice->due_fine_amount())--}}
                                    {{--@php $due_fine_amount=$invoice->due_fine_amount()->fine_amount; @endphp--}}
                                {{--@else--}}
                                    {{--@php $due_fine_amount=0;--}}
                                         {{--$std=$invoice->payer()--}}
                                    {{--@endphp--}}
                                {{--@endif--}}

                                    {{--@if(!empty($invoice->fees()))--}}
                                        {{--<td>{{$invoice->id}}</td>--}}
                                        {{--@php--}}
                                            {{--$fees=$invoice->fees();--}}

                                        {{--@endphp--}}
                                        {{--<td>{{$fees->fee_name}}</td>--}}


                                        {{--@php $subtotal=0; $totalAmount=0; $totalDiscount=0; @endphp--}}
                                        {{--@foreach($fees->feesItems() as $amount)--}}
                                            {{--@php $subtotal += $amount->rate*$amount->qty;@endphp--}}

                                        {{--@endforeach--}}



                                        {{--Due Fine Amount--}}
                                        {{--@php--}}
                                            {{--$dueFinePaid=$invoice->invoice_payment_summary();--}}
                                            {{--$var_dueFine=0;--}}
                                            {{--if($dueFinePaid){--}}
                                                {{--$var_dueFine = json_decode($dueFinePaid->summary);--}}
                                            {{--}--}}
                                        {{--@endphp--}}

                                        {{--@if($invoice->invoice_status=="1")--}}
                                            {{--@if(!empty($var_dueFine))--}}
                                                {{--@php $getDueFine=$var_dueFine->due_fine->amount; @endphp--}}
                                            {{--@endif--}}
                                        {{--@else--}}
                                            {{--@if(!empty($invoice->findReduction()))--}}
                                                {{--@php $getDueFine=$invoice->findReduction()->due_fine; @endphp--}}
                                            {{--@else--}}
                                                {{--@php $getDueFine=get_fees_day_amount($invoice->fees()->due_date) @endphp--}}
                                            {{--@endif--}}
                                        {{--@endif--}}

                                        {{--@if($discount = $invoice->fees()->discount())--}}
                                            {{--@php $discountPercent=$discount->discount_percent;--}}
                                                    {{--$totalDiscount=(($subtotal*$discountPercent)/100);--}}
                                                    {{--$totalAmount=$subtotal-$totalDiscount--}}
                                            {{--@endphp--}}
                                        {{--@else--}}
                                            {{--@php--}}
                                                {{--$totalAmount=$subtotal;--}}
                                            {{--@endphp--}}

                                        {{--@endif--}}


                                        {{--waiver Check --}}
                                        {{--@if($invoice->waiver_type=="1")--}}
                                            {{--@php $totalWaiver=(($totalAmount*$invoice->waiver_fees)/100);--}}
                                                 {{--$totalAmount=$totalAmount-$totalWaiver--}}
                                            {{--@endphp--}}
                                        {{--@elseif($invoice->waiver_type=="2")--}}
                                            {{--@php $totalWaiver=$invoice->waiver_fees;--}}
                                                 {{--$totalAmount=$totalAmount-$totalWaiver--}}
                                            {{--@endphp--}}

                                        {{--@endif--}}


                                        {{--@if($discount = $invoice->fees()->discount())--}}
                                            {{--@php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp--}}
                                        {{--@endif--}}


                                        {{--@if(!empty($invoice->waiver_fees))--}}
                                            {{--@php $totalDiscount=$totalDiscount+$totalWaiver @endphp--}}
                                        {{--@endif--}}




                                        {{--<td>{{$subtotal+$getAttendFine+$getDueFine-$totalDiscount}}</td>--}}

                                        {{--<td>{{$subtotal}}</td>--}}
                                        {{--<td>{{$totalDiscount}} </td>--}}
                                        {{--<td>{{$getDueFine}}</td>--}}
                                          {{--<td>--}}
                                            {{--@if ($invoice->invoice_status=="2")--}}
                                                {{--{{$invoice->totalPayment()}}--}}
                                            {{--@elseif ($invoice->invoice_status=="1")--}}
                                                {{--{{$invoice->totalPayment()+$getDueFine}}--}}
                                            {{--@endif--}}

                                        {{--</td>--}}

                                        {{--<td>--}}

                                            {{--@if ($invoice->invoice_status=="2")--}}
                                                {{--<span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-danger">Un-Paid</span>--}}
                                            {{--@elseif ($invoice->invoice_status=="1")--}}
                                                {{--<span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-primary">Paid</span>--}}
                                            {{--@elseif ($invoice->invoice_status=="4")--}}
                                                {{--<span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-danger">Cancel</span>--}}
                                            {{--@elseif ($invoice->invoice_status=="3")--}}
                                                {{--<span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-success">Partial Payment</span>--}}
                                            {{--@endif--}}

                                            {{--<span id="cancelInvoiceStatus{{$invoice->id}}" class="label label-danger" style="display: none">Cancel</span>--}}
                                        {{--</td>--}}

                                {{--</tr>--}}
                                {{--@endif--}}


                                {{--<tr>--}}
                            {{--@endforeach--}}
                        {{--@endforeach--}}


                    {{--</table>--}}
                {{--@else--}}
                    {{--<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in text-center">--}}
                        {{--<h5 class="text-bold"><i class="fa fa-warning"></i> No Fees Invoice found </h5>--}}
                    {{--</div>--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}



{{--===============================Student Fees Month and Year Wise Invoice List--}}


    <div class="box-body table-responsive">
        @if(!empty($studentInviceByYearMonth))
            @foreach ($studentInviceByYearMonth as $yearMonth => $invoiceListArray)


                @php
                    $dateObj   = DateTime::createFromFormat('!Y', $yearMonth);
                @endphp
        <div class="panel-body" style="background: #efefef; font-weight: 700">Fees {{$dateObj->format('Y')}} </div>
        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
            <div id="w1" class="grid-view">

                    <table class="table table-bordered">
                        <tr class="highlight">
                            <thead>
                            <th class="highlight" width="20%">Month</th>
                            <th  class="highlight">Invoice ID</th>
                            <th class="highlight" >Fee Name</th>
                            <th class="highlight" >Fees</th>
                            <th class="highlight" >Discount</th>
                            <th class="highlight" >Due Fine</th>
                            <th class="highlight" >Total Amount</th>
                            <th class="highlight" >Paid Amount</th>
                            <th class="highlight">Status</th>
                            </thead>
                        </tr>
                            {{--        Found {{ sizeof($invoiceListArray) }} classes for {{ $yearMonth }}--}}
                            <tr>

                                @php $getAttendFine=0; $getDueFine=0;  @endphp
                                @foreach ($invoiceListArray as $key=>$invoiceList)
                                    @php
                                        $dateObj   = DateTime::createFromFormat('!m', $key);
                                    $rowSize=sizeof($invoiceList)+1;
                                    $monthlySumTotalPaymentAmount=0; $sumTotalAmount=0; $monthlySumSubTotal=0;$monthlySumTotalDiscount=0; $monthlySumTotalDueFine=0;
                                    @endphp
                                <td class="highlight" rowspan="{{$rowSize}}" style="margin: 0px auto;">{{$dateObj->format('F')}}</td>
                                @foreach ($invoiceList as $invoice)

                                    {{-- attendance and due fine amount--}}

                                    @if($invoice->due_fine_amount())
                                        @php $due_fine_amount=$invoice->due_fine_amount()->fine_amount; @endphp
                                    @else
                                        @php $due_fine_amount=0;
                                         $std=$invoice->payer()
                                        @endphp
                                    @endif

                                    @if(!empty($invoice->fees()))
                                        <td>{{$invoice->id}}</td>
                                        @php
                                            $fees=$invoice->fees();

                                        @endphp
                                        <td>{{$fees->fee_name}}</td>

                                        @php $subtotal=0; $totalAmount=0; $totalDiscount=0; @endphp
                                        @foreach($fees->feesItems() as $amount)
                                            @php $subtotal += $amount->rate*$amount->qty;@endphp

                                        @endforeach



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



                                        {{--<td>{{$subtotal+$getAttendFine+$getDueFine-$totalDiscount}}</td>--}}

                                        <td>{{$subtotal}}</td>
                                            @php $monthlySumSubTotal+=$subtotal @endphp
                                        <td>{{$totalDiscount}} </td>
                                            @php $monthlySumTotalDiscount+=$totalDiscount @endphp
                                        <td>{{$getDueFine}}</td>
                                            @php $monthlySumTotalDueFine+=$getDueFine @endphp
                                        <td>
                                             @php $sumTotalAmount+=$subtotal+$getDueFine-$totalDiscount @endphp
                                           {{$subtotal+$getDueFine-$totalDiscount}}</td>
                                        <td>

                                            @if ($invoice->invoice_status=="2")
                                                {{$invoice->totalPayment()}}
                                            @elseif ($invoice->invoice_status=="1")
                                                {{$subtotal+$getDueFine-$totalDiscount}}
                                                @php $monthlySumTotalPaymentAmount+=$subtotal+$getDueFine-$totalDiscount @endphp

                                            @endif

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





                        @endif



                        @endforeach
                        <tr style="background: limegreen; color: #FFF">
                            <td colspan="2">Monthly Total</td>
                            <td>{{$monthlySumSubTotal}}</td>
                            <td>{{$monthlySumTotalDiscount}}</td>
                            <td>{{$monthlySumTotalDueFine}}</td>
                            <td>{{$sumTotalAmount}}</td>
                            <td colspan="2">{{$monthlySumTotalPaymentAmount}}</td>

                        </tr>

                        <tr>
                        @endforeach

                    </table>
            </div>
        </div>
            @endforeach
            @endif
    </div>

    {{--$attenFineByYearMonth--}}


    {{--/////////////////////////--}}{{----}}

    <div class="box-body table-responsive">
        @if(!empty($attenFineByYearMonth))
            @foreach ($attenFineByYearMonth as $yearMonth => $invoiceListArray)

                @php
                    $dateObj   = DateTime::createFromFormat('!Y', $yearMonth);
                @endphp
                <div class="panel-body" style="background: #efefef; font-weight: 700">Attendance {{$dateObj->format('Y')}} </div>
        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
            <div id="w1" class="grid-view">

                    <table class="table table-bordered">
                        <tr class="highlight">
                            <thead>
                            <th class="highlight" width="20%">Month</th>
                            <th  class="highlight">Invoice ID</th>
                            <th class="highlight" >Fee Name</th>
                            <th class="highlight" >Fees</th>
                            <th class="highlight" >Discount</th>
                            <th class="highlight" >Due Fine</th>
                            <th class="highlight" >Paid Amount</th>
                            <th class="highlight">Status</th>
                            </thead>
                        </tr>
                            {{--        Found {{ sizeof($invoiceListArray) }} classes for {{ $yearMonth }}--}}

                            <tr>
                                @foreach ($invoiceListArray as $month => $attendmonthList)

                                @php $getAttendFine=0; $getDueFine=0;
                                 $rowSize=sizeof($attendmonthList)+1;

                              $monthlyInvoiceAmount=0; $monthlyPaidAttendanceFine=0;
                                @endphp
                                <td class="highlight" rowspan="{{$rowSize}}" style="margin: 0px auto;">{{date('F',strtotime($yearMonth))}}</td>
                                @foreach ($attendmonthList as $invoice)

                                    {{-- attendance and due fine amount--}}

                                    @if($invoice->due_fine_amount())
                                        @php $due_fine_amount=$invoice->due_fine_amount()->fine_amount; @endphp
                                    @else
                                        @php $due_fine_amount=0;
                                         $std=$invoice->payer()
                                        @endphp
                                    @endif
                                    @if(empty($invoice->fees()))

                                    <td>{{$invoice->id}}</td>
                                    <td>Attendance Fine</td>
                                            <td>{{$invoice->invoice_amount}}</td>
                                            @php $monthlyInvoiceAmount+=$invoice->invoice_amount @endphp
                                            {{--<td>0</td>--}}
                                            <td>0</td>
                                            <td> 													  {{$invoice->invoice_amount}}
                                            </td>
                                            <td>
                                                @if ($invoice->invoice_status=="1")
                                                    {{$invoice->invoice_amount}}
                                                    @php $monthlyPaidAttendanceFine+=$invoice->invoice_amount @endphp
                                                @else
                                                    0
                                                @endif
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
                        @endif


                        @endforeach

                        <tr style="background: limegreen; color: #FFF">
                            <td colspan="2">Monthly</td>
                            <td>{{$monthlyInvoiceAmount}}</td>
                            {{--<td>0</td>--}}
                            <td>0</td>
                            <td>0</td>
                            <td colspan="2">{{$monthlyPaidAttendanceFine}}</td>

                        </tr>


                        <tr>
                        @endforeach

                    </table>

            </div>
        </div>
            @endforeach
            @endif
    </div>
    @endsection

    <!-- page script -->
        @section('profile-scripts')
            <script type = "text/javascript">
                jQuery(document).ready(function () {


                });
            </script>
@endsection
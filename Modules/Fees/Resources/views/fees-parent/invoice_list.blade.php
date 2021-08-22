
{{--$inviceByYearMonth--}}

{{--@php ksort($classesByYearMonth) @endphp --}}


{{--@foreach ($inviceByYearMonth as $yearMonth => $invoiceListArray)--}}
    {{--Found {{ sizeof($invoiceListArray) }} classes for {{ $yearMonth }}--}}
    {{--@foreach ($invoiceListArray as $invoice)--}}
        {{--<div>--}}
            {{--<div>ID: {{ $invoice['id'] }}</div>--}}
            {{--<div>Fees_id: {{ $invoice['fees_id'] }}</div>--}}
            {{--<div>Payer_Type: {{ $invoice['payer_type'] }}</div>--}}
            {{--<div>Invoice Status: {{ $invoice['invoice_status'] }}</div>--}}
        {{--</div>--}}
        {{--<div class="border" style="border-top: 1px solid orange">----Bor----</div>--}}
    {{--@endforeach--}}
{{--@endforeach--}}



{{--@foreach ($inviceByYearMonth as $yearMonth => $invoiceListArray)--}}
    {{--Found {{ sizeof($invoiceListArray) }} classes for {{ $yearMonth }}--}}
    {{--@foreach ($invoiceListArray as $invoice)--}}
        {{--<div>--}}
            {{--<div>ID: {{ $invoice['id'] }}</div>--}}
            {{--<div>Fees_id: {{ $invoice['fees_id'] }}</div>--}}
            {{--<div>Payer_Type: {{ $invoice['payer_type'] }}</div>--}}
            {{--<div>Invoice Status: {{ $invoice['invoice_status'] }}</div>--}}
        {{--</div>--}}
        {{--<div class="border" style="border-top: 1px solid orange">----Bor----</div>--}}
    {{--@endforeach--}}
{{--@endforeach--}}
<style>
    .highlight {
        background: #48B04F !important;
        font-weight: 700;
        text-align: center;
        color: #fff !important;
    }

</style>



<div class="box-body table-responsive">
    <div class="panel-body" style="background: #efefef; font-weight: 700">Fees Invoice List</div>
    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        <div id="w1" class="grid-view">
            @if(!empty($inviceByYearMonth))
                <table class="table table-bordered">
                    <tr class="highlight">
                        <thead>
                        <th class="highlight" width="20%">Month</th>
                        <th  class="highlight">Invoice ID</th>
                        <th  class="highlight">Fee Name</th>
                        <th  class="highlight">Fees Amount</th>
                        <th  class="highlight">Amount</th>
                        <th  class="highlight">Discount</th>
                        <th  class="highlight">Due Fine</th>
                        <th class="highlight">Attendance</th>
                        <th class="highlight">Paid Amount</th>
                        <th class="highlight">Waiver</th>
                        <th class="highlight">Status</th>
                        </thead>
                    </tr>

                    @foreach ($inviceByYearMonth as $yearMonth => $invoiceListArray)
                    {{--        Found {{ sizeof($invoiceListArray) }} classes for {{ $yearMonth }}--}}
                        <tr>
                            @php $getAttendFine=0; $getDueFine=0;  @endphp
                            <td class="highlight" rowspan="{{sizeof($invoiceListArray)}}" style="margin: 0px auto;">{{date('F',strtotime($yearMonth))}}</td>
                            
                            @foreach ($invoiceListArray as $invoice)
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
                                    @php 
                                        $discountPercent=$discount->discount_percent;
                                        $totalDiscount=(($subtotal*$discountPercent)/100);
                                        $totalAmount=$subtotal-$totalDiscount
                                    @endphp
                                @else
                                    @php $totalAmount=$subtotal;  @endphp
                                @endif

                                @if($invoice->waiver_type=="1")
                                    @php 
                                        $totalWaiver=(($subtotal*$invoice->waiver_fees)/100);
                                        $totalAmount=$totalAmount-$totalWaiver
                                    @endphp

                                @elseif($invoice->waiver_type=="2")
                                    @php 
                                        $totalWaiver=$invoice->waiver_fees;
                                        $totalAmount=$subtotal-$totalWaiver
                                    @endphp
                                @endif

                                {{--Due Fine Amount--}}
                                @php
                                    $dueFinePaid=$invoice->invoice_payment_summary();
                                    if($dueFinePaid){
                                        $var_a = json_decode($dueFinePaid->summary);
                                    }
                                @endphp

                                @if(!empty($var_a))

                                    @php $getDueFine=$var_a->due_fine->amount; @endphp
                                @else
                                    @php $getDueFine=get_fees_day_amount($invoice->fees()->due_date) @endphp
                                @endif
                                {{--end due fine amount--}}


                                {{--attendace Fine Amount--}}
                                @php
                                    $attendanceFinePaid=$invoice->invoice_payment_summary();
                                    if($attendanceFinePaid){
                                        $var_a = json_decode($attendanceFinePaid->summary);
                                    }
                                @endphp

                                @if(!empty($var_a))
                                    @php $getAttendFine= $var_a->attendance_fine->amount; @endphp
                                @else
                                    @php $getAttendFine=getAttendanceFinePreviousMonth($invoice->id); @endphp
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

                                <td>{{$totalAmount+$getAttendFine+$getDueFine}}</td>

                                <td>{{$subtotal}}</td>
                                <td>{{$totalDiscount}} </td>
                                <td>{{$getDueFine}}</td>
                                <td>{{$getAttendFine}}</td>

                                <td>
                                    {{$invoice->totalPayment()+$due_fine_amount+$attendance_fine_amount}}

                                </td>

                                <td>
                                    @if(!empty($invoice->payer()->student_waiver()) && ($invoice->wf_status=='2'))
                                        <span class="label  label-default ">Applied</span>
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
                                {{--<td> @if ($fees->partial_allowed==1) <span class="btn-orange">Yes<span> @else <span>No</span> @endif</td>--}}
                                {{--<td>{{date('m-d-Y',strtotime($fees->due_date))}}</td>--}}
                                {{--<td>{{$fees->fee_status}}</td>--}}
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            @else
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in text-center">
                    <h5 class="text-bold"><i class="fa fa-warning"></i> No Fees Invoice found </h5>
                </div>
            @endif
        </div>
    </div>
</div>




{{--$attenFineByYearMonth--}}


{{--/////////////////////////--}}{{----}}

<div class="box-body table-responsive">
    <div class="panel-body" style="background: #efefef; font-weight: 700">Attendance Fine List</div>
    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        <div id="w1" class="grid-view">
            @if(!empty($attenFineByYearMonth))
            <table class="table table-bordered">
                <tr>
                    <thead>
                    <th class="highlight" width="20%">Month</th>
                    <th  class="highlight">Fine ID</th>
                    <th class="highlight">Fine Date</th>
                    <th class="highlight">Attendance Fine</th>
                    </thead>
                </tr>
                @foreach ((object)$attenFineByYearMonth as $yearMonth => $attenListArray)
                    {{--        Found {{ sizeof($attenListArray) }} classes for {{ $yearMonth }}--}}

                    <tr>
                        @php $getAttendFine=0; $getDueFine=0;  @endphp
                        <td class="highlight" rowspan="{{sizeof($attenListArray)}}" style="margin: 0px auto;">{{date('F',strtotime($yearMonth))}}</td>
                        @foreach ($attenListArray as $fine)
                            {{-- attendance and due fine amount--}}
                            <td>{{$fine->id}}</td>
                            <td>{{date('d-m-Y',strtotime($fine->date))}}</td>
                            <td>{{$fine->fine_amount}}</td>
                    </tr>
                    <tr>
                @endforeach
                @endforeach
            </table>

            @else
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in text-center">
                    <h5 class="text-bold"><i class="fa fa-warning"></i> No Attendance Fine found </h5>
                </div>
            @endif
        </div>
    </div>
</div>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{$reportTitle}}</title>
    <style>
        @page { margin: 100px 0px; }
        .header { position: fixed; left: 0px; top: -100px; right: 0px; height: 100px; text-align: center; }
        .footer { position: fixed; left: 0px; bottom: -50px; right: 0px; height: 50px;text-align: center;}
        .footer .pagenum:before { content: counter(page); }


        body { font: 10px/1.4 Georgia, serif; }
        textarea { border: 0; font: 10px Georgia, Serif; overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        table td, table th { border: 1px solid black; padding: 5px; }

        thead { display: table-header-group }
        tfoot { display: table-row-group }
        tr { page-break-inside: avoid }

        #feesInvocieListTable { clear: both; width: 100%; margin-left: 10px; margin-right: 30px; border: 1px solid black; }
        #feesInvocieListTable th { background: #eee; }
        #feesInvocieListTable textarea { width: 80px; height: 50px; }
        #feesInvocieListTable tr.item-row td { border: 0; vertical-align: top; }
        #feesInvocieListTable td.description { width: 300px; }
        #feesInvocieListTable td.item-name { width: 175px; }
        #feesInvocieListTable td.description textarea, #feesInvocieListTable td.item-name textarea { width: 100%; }
        #feesInvocieListTable td.total-line { border-right: 0; text-align: right; }
        #feesInvocieListTable td.total-value { border-left: 0; padding: 10px; }
        #feesInvocieListTable td.total-value textarea { height: 20px; background: none; }
        #feesInvocieListTable td.balance { background: #eee; }
        #feesInvocieListTable td.blank { border: 0; }

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
            font-size: 20px;
            font-weight: bold;
            margin-top: 0px;
        }
        p.address-section {
            font-size: 12px;
            margin-top: -30px;
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
{{-- -------------------------------------------------------- --}}
{{-- pour ajouter une entete et un pied de page au pdf généré --}}
{{-- -------------------------------------------------------- --}}
<div class="header">
    <div class="header-section">
        <div class="logo">
            <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:60px;height:60px">
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
<div class="footer">
    Page <span class="pagenum"></span>
</div>

<table id="feesInvocieListTable" class="table table-striped table-bordered">
    <tbody>

    <thead>
    <tr>
        <th>Invoice Id</th>
        <th><a  data-sort="sub_master_code">Fee Name</a></th>
        <th><a  data-sort="sub_master_code">Fees Type</a></th>
        <th><a  data-sort="sub_master_alias">Payer Name</a></th>
        <th><a  data-sort="sub_master_alias">Fees</a></th>
        <th><a  data-sort="sub_master_alias">Discount</a></th>
        <th><a  data-sort="sub_master_alias">Due Fine</a></th>
        <th><a  data-sort="sub_master_alias">Total Amount</a></th>
        <th><a  data-sort="sub_master_alias">Paid Amount</a></th>
        <th><a  data-sort="sub_master_alias">Status</a></th>
        <th><a  data-sort="sub_master_alias">Waiver</a></th>
    </tr>

    </thead>
    <tbody>

    @if(!empty($invoiceList))
        @php
            $invoiceLoopCounter=0;
                        $i = 1; $getAttendFine=0; $getDueFine=0;

                    // amount calculate variable

                   $totalPaidCalculate=0;   $subTotalSum=0;  $totalFeesAmountSum=0; $totalSumAmount=0; $totalDueAmountSum=0; $totalDiscountAmountSum=0; $totalPaidAmountSum=0;

        @endphp
        @foreach($invoiceList as $invoice)

            @php $std=$invoice->payer(); @endphp
            {{-- attendance and due fine amount--}}

            @if($invoice->due_fine_amount())
                @php $due_fine_amount=$invoice->due_fine_amount()->fine_amount; @endphp
            @else
                @php $due_fine_amount=0;
                @endphp
            @endif

            <tr class="gradeX">
                @if(!empty($invoice->fees()))
                    <td>{{$invoice->id}}</td>
                    @php
                        $fees=$invoice->fees();

                    @endphp
                    <td>{{$fees->fee_name}}</td>
                    <td>
                        <span  class="label label-success">F</span>
                    </td>


                    <td>@if(!empty($std)) {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}} @endif</td>


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
                    <td>{{$totalDiscount}} </td>
                    <td>{{$getDueFine}}</td>
                    <td>{{$subtotal+$getDueFine-$totalDiscount}}</td>

                    <td>
                        @if ($invoice->invoice_status=="1")
                            {{$invoice->totalPayment()+$getDueFine}}
                            @php $totalPaidCalculate=$invoice->totalPayment()+$getDueFine;  @endphp
                        @else
                            0
                            @php $totalPaidCalculate=0; @endphp

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
                    <td>


                        @if(!empty($invoice->payer()->student_waiver()) && ($invoice->payer()->student_waiver()->end_date>date('Y-m-d')) && ($invoice->wf_status=='1'))
                            @if($invoice->invoice_status=="2")
                                <a  class="label label-primary"   href="/fees/invoice/add-waiver-modal/{{$invoice->id}}/" title="Add Waiver" data-pjax="0" data-target="#globalModal" data-toggle="modal"  class="btn btn-success btn-xs wf_status" >Available</a>
                            @endif
                        @elseif(!empty($invoice->payer()->student_waiver()) && ($invoice->wf_status=='2'))
                            <span class="label  label-default ">Applied</span>
                        @endif</td>

                @else

                    @php $totalDiscount=0; $getDueFine=0; @endphp

                    <td>{{$invoice->id}}</td>
                    <td>Attendance Fine</td>
                    <td>
                        <span  class="label label-primary">A</span>
                    </td>
                    <td>@if(!empty($std)) {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}} @endif</td>
                    <td>{{$invoice->invoice_amount}}
                        @php $subtotal=$invoice->invoice_amount; @endphp
                    </td>
                    <td>{{$totalDiscount}}</td>
                    <td>{{$getDueFine}}</td>
                    <td>       @if ($invoice->invoice_status=="1")
                            {{$invoice->invoice_amount}}
                                   @else
                                   0
                        @endif</td>
                    <td>
                        @if ($invoice->invoice_status=="1")
                            {{$invoice->invoice_amount}}
                            @php $totalPaidCalculate=$invoice->invoice_amount; @endphp
                        @else
                            0
                            @php $totalPaidCalculate=0; @endphp
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
                    <td></td>
                @endif

            </tr>

            @php
                 $subTotalSum+=$subtotal;
                 $totalDiscountAmountSum+=$totalDiscount;
                 $totalDueAmountSum+=$getDueFine;
                 $totalSumAmount+=$subtotal+$getDueFine-$totalDiscount;
                 $totalPaidAmountSum+=$totalPaidCalculate;

             $invoiceLoopCounter +=1;
            @endphp
        @endforeach


        <tr>
        <th colspan="4">Total</th>
        <th >{{$subTotalSum}}</th>
        <th >{{$totalDiscountAmountSum}}</th>
        <th >{{$totalDueAmountSum}}</th>
        <th >{{$totalSumAmount}}</th>
        <th >{{$totalPaidAmountSum}}</th>
        <th ></th>
        <th ></th>
        </tr>


    </tbody>
</table>
@endif

</body>
</html>
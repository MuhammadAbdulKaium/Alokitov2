<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{$reportTitle}}</title>

    <style>

        @page { margin: 100px 0px; }
        .header { position: fixed; left: 0px; top: -100px; right: 0px; height: 100px; text-align: center; }
        .footer { position: fixed; left: 0px; bottom: -50px; right: 0px; height: 50px;text-align: center;}
        .footer .pagenum:before { content: counter(page); }

        .small-section {
            border-top: 2px solid #00a65a;
            padding: 10px;
        }

        .payment{
            padding-top: 10px;
            font-size: 16px;
        }

        #batch {
            float: left;
            margin-left: 50px;
            font-size: 14px;
        }

        #section{
            float: right;
            margin-right: 50px;
            font-size: 14px;
        }


        body { font: 12px/1.4 Georgia, serif; }
        textarea { border: 0; font: 14px Georgia, Serif; overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        table td, table th { border: 1px solid black; padding: 5px; }

        #tableDesign { clear: both; width: 100%; margin-left: 10; margin-right: 30px; border: 1px solid black; }
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
<div class="header">
    <div class="header-section">
        <div class="logo">
            <img src="{{public_path().'/assets/users/images/'.$instituteProfile->logo}}"  style="width:80px;height:80px">
        </div>
        <div class="text-section">
            <p class="title">{{$instituteProfile->institute_name}}</p><br/><p class="address-section">{{'Address: '.$instituteProfile->address1.',Phone: '.$instituteProfile->phone}}<br/>{{'E-mail: '.$instituteProfile->email.', Website: '.$instituteProfile->website}} </p>
        </div>
        <div class="report-title">
            <p>{{$reportTitle}}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>


<table id="tableDesign" class="transactions_table" style="margin-top: 20px;">

    <thead>
    <tr>
        <th>Fees ID</th>
        <th>Fees Name</th>
        <th>Fees Type</th>
        <th>Amount</th>
        <th>Discount</th>
        <th>Partial</th>
        <th>Due Date</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{$feesInfo->id}}</td>
        <td>{{$feesInfo->fee_name}}</td>
        <td>{{$feesInfo->fees_type()->fee_type_name}}</td>

        @php $subtotal=0; @endphp
        @foreach($feesInfo->feesItems() as $amount)
            @php $subtotal += $amount->rate*$amount->qty;@endphp

        @endforeach
        {{--dsicount --}}
        @if($discount =$feesInfo->discount())
            @php $discountPercent=$discount->discount_percent;
                                                    $totalDiscount=(($subtotal*$discountPercent)/100);
                                                    $totalAmount=$subtotal-$totalDiscount
            @endphp
        @endif

        <td>{{$subtotal}}</td>
        <td>
            {{--@php @endphp--}}
            @if($discount = $feesInfo->discount())
                @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
                {{$totalDiscount }}
            @endif

        </td>
        <td> @if ($feesInfo->partial_allowed=="0") <span class="btn-orange">NO<span> @else <span>Yes</span> @endif</td>
        <td>{{date('d-m-Y',strtotime($feesInfo->due_date))}}</td>
    </tr>
    </tbody>
</table>

<div class="small-section" style="clear: both" >
    <div class="payment">Waiver Report:</div>
</div>


@foreach($batchSectionList as $batchSection)
    @php   $calculateTotalDiscount=0; $batchSectionShorting = sectionSorter($batchSection->section, batchSorter($batchSection->batch, $feesWaiverInvoiceList)); @endphp

    <div class="" style="clear: both; padding-bottom: 20px;">
        <div id="batch">
            <h4>Batch : {{getBatchName($batchSection->batch)}} </h4>
        </div>
        <div id="section">
            <h4>Section : {{getSectionName($batchSection->section)}} </h4>
        </div>

    </div>

    <table id="tableDesign" class="transactions_table" style="margin-top: 20px;">

        <thead>
        <tr>
            <th>Std Id</th>
            <th>Payer Name</th>
            <th>Waiver</th>
            <th>Discount</th>
            <th>Total Discount</th>
        </tr>
        </thead>
        <tbody>

        @foreach($batchSectionShorting as $waiverInvoice)
            <tr>
                @php
                    $std=$waiverInvoice->payer();
                    $fees=$waiverInvoice->fees();
                @endphp
                @php $subtotal=0;
                  $totalAmount=0;
                  $totalDiscount=0;
                  $totalWaiver=0;
                @endphp
                @foreach($fees->feesItems() as $amount)
                    @php $subtotal += $amount->rate*$amount->qty;@endphp
                @endforeach
                {{--get totam amount--}}
                @if($discount = $waiverInvoice->fees()->discount())
                    @php $discountPercent=$discount->discount_percent;
               $totalDiscount=(($subtotal*$discountPercent)/100);
                $totalAmount=$subtotal-$totalDiscount
                    @endphp

                @else
                    @php $totalAmount=$subtotal;
                    @endphp
                @endif
                {{--get total Waiver--}}
                @if($waiverInvoice->waiver_type=="1")
                    @php $totalWaiver=(($totalAmount*$waiverInvoice->waiver_fees)/100);
                    $totalAmount=$totalAmount-$totalWaiver
                    @endphp

                @elseif($waiverInvoice->waiver_type=="2")
                    @php $totalWaiver=$waiverInvoice->waiver_fees;
                        $totalAmount=$totalAmount-$totalWaiver
                    @endphp
                @endif
                {{--//get total discount--}}
                @if($discount = $waiverInvoice->fees()->discount())
                    @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
                @endif
                @if(!empty($waiverInvoice->waiver_fees))
                    @php $totalWaiver=$totalWaiver;
                    @endphp
                @endif
                @php $calculateTotalDiscount+=$totalDiscount+$totalWaiver; @endphp

                <td>{{$waiverInvoice->payer_id}}</td>
                <td>{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}</td>
                <td>{{$waiverInvoice->waiver_fees}} @if($waiverInvoice->waiver_type=="1") % ({{$totalWaiver}} TK) @elseif($waiverInvoice->waiver_type=="2") TK. @endif
                </td>
                <td>@if($discount = $waiverInvoice->fees()->discount()) {{$totalDiscount}} @endif</td>
                <td>{{$totalDiscount+$totalWaiver}}</td>
            </tr>
        @endforeach

        </tbody>

        <tfoot>
        <tr>
            <th colspan="0"></th>
            <th colspan="0"></th>
            <th colspan="0"></th>
            <th>Total Discount</th>
            <th>{{$calculateTotalDiscount}}</th>
        </tr>
        </tfoot>
    </table>
    @endforeach
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Store Ledger Report</title>
    <style>
        .p-0 {
            padding: 0px !important;
        }

        .m-0 {
            margin: 0px !important;

        }

        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        img {
            width: 100px;
            height: 100px;
        }

        .header {
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0;
        }

        .logo {
            width: 8%;
            float: left;
            margin-bottom: 10px;
        }

        .headline {
            float: left;
            padding: 1px 1px;
        }

        .headline-details {
            float: right;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
            text-align: center !important;
        }
        th, td {
            text-align: left;
            padding: 4px;
            border: 1px solid #ddd;
        }
        table.table-bordered{
            border:1px solid #000000;
            margin-top:20px;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid #000000;
            text-align: center;
        }
        table.table-bordered > tbody > tr > td{
            border:1px solid #000000;
            text-align: center;
        }
        .select2-selection--single{
            height: 33px !important;
        }
        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            font-size: medium;
            background-color: #002d00;
            color: white;
            height: 50px;
        }
    </style>
</head>
<body>
    <footer>
        <div style="padding:.5rem">
            <span  >Printed from <b>CCIS</b> by {{$user->name}} on <?php echo date('l jS \of F Y h:i:s A'); ?> </span>
        
        </div>
        <script type="text/php">
        if (isset($pdf)) {
            $x = 1550;
            $y = 1170;
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = null;
            $size = 14;
            $color = array(255,255,255);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
        </script>
    </footer>

<main>
    <div class="header clearfix">
        <div class="logo">
            <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" height="60px!important" alt="">
        </div>
        <div class="headline">
            <h1>{{ $institute->institute_name }}</h1>
            <p>{{ $institute->address2 }}</p>
        </div>
        <div style="float: left;width: 14%;font-size: xx-small;padding: 0;margin: 0">

        </div>

        <h1 style="text-align: center;float: left">Store Ledger Report</h1>
        <div style="float: left;width: 2%;font-size: xx-small;padding: 0;margin: 0">

        </div>
    </div>
    <div>
        <h3>Product: {{ $product->product_name }}</h3>
        <h3>From: {{ $fromDate }} To: {{ $toDate }}</h3>
        <h3>Selected Parameters: Group: {{ $group->stock_group_name }}, Category: {{ $category->stock_category_name }}, Store: @if ($all=='all')All
                                                                                                    
            @else {{ '(' }} @foreach ($all as $al) {{ $al->store_name }}, 

            @endforeach {{ ')' }}           

            @endif
        </h3>
    </div>
    <div>
        @php
            $extraCol = 1;
            $rowNumber=0;
            $index1=0;
        @endphp
        <table class="table table-bordered ">
            <thead style="background: #dee2e6;">
                <tr>
                    <th rowspan="2" colspan="1" style="padding-bottom: 20px;">#</th>
                    <th colspan="4">Particulars</th>
                    <th colspan="3">Inward</th>
                    <th colspan="3">Outward</th>
                    <th colspan="3">Closing</th>
                </tr>
                <tr>
                    <th>Date</th>
                    <th>Vendor</th>
                    <th>Ref.#</th>
                    <th>Tran. Type</th>
                    <th>Qty.</th>
                    <th>Rate</th>
                    <th>Value</th>
                    <th>Qty.</th>
                    <th>Rate</th>
                    <th>Value</th>
                    <th>Qty.</th>
                    <th>Rate</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background: #dee2e6; font-weight: bold;">
                    <td></td>
                    <td colspan="10" style="text-align: left; padding-left: 40px">Opening</td>
                    <td>{{ (number_format((float) $openingQty, 2, '.', '')) }}</td>
                    <td>{{ (number_format((float) $openingRate, 3, '.', '')) }}</td>
                    <td>{{ (number_format((float) $openingValue, 3, '.', '')) }}</td>
                </tr>
                @foreach ($result as $ledger)
                    @if ((strtotime($ledger['tran_date']) >= strtotime($fromDate)) && (strtotime($ledger['tran_date']) <= strtotime($toDate)))
                        <tr @if($index1%2==1)  style="background: #dee2e6;" @endif>
                            <td>{{ $index1 + 1 }}</td>
                            @php
                                $index1++;
                            @endphp
                            <td>{{ $ledger['tran_date'] }}</td>
                            <td>@if (isset($ledger['vendor_name'])) {{ $ledger['vendor_name'] }} @endif</td>
                            <td>{{ $ledger['voucher_no'] }}</td>
                            <td>@if (($ledger['category'] == 'direct_purchase') || ($ledger['category'] == 'opening')) Stock In
                                @elseif ($ledger['category'] == 'purchase-order') Purchase Receive
                                @elseif ($ledger['category'] == 'direct_sale') Stock Out
                                @elseif ($ledger['category'] == 'requisition') Issue From Inventory
                                @endif
                            </td>
                            <td>@if ($ledger['type'] == 'inward') {{ (number_format((float) $ledger['qty'], 2, '.', '')) }} @endif</td>
                            <td>@if ($ledger['type'] == 'inward') {{ (number_format((float) $ledger['rate'], 2, '.', '')) }} @endif</td>
                            <td>@if ($ledger['type'] == 'inward') {{ (number_format((float) $ledger['amount'], 2, '.', '')) }} @endif</td>
                            <td>@if ($ledger['type'] == 'outward') {{ (number_format((float) $ledger['qty'], 2, '.', '')) }} @endif</td>
                            <td>@if ($ledger['type'] == 'outward') {{ (number_format((float) $ledger['rate'], 3, '.', '')) }} @endif</td>
                            <td>@if ($ledger['type'] == 'outward') {{ (number_format((float) $ledger['qty']*$ledger['rate'], 3, '.', '')) }} @endif</td>
                            <td>{{ (number_format((float) $closingQtys[$ledger['voucher_no']], 2, '.', '')) }}</td>
                            <td>{{ (number_format((float) $closingRates[$ledger['voucher_no']], 3, '.', '')) }}</td>
                            <td>{{ (number_format((float) $closingValues[$ledger['voucher_no']], 3, '.', '')) }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr style="font-weight: bold;">
                    <td colspan="5">Grand Total:</td>
                    <td>{{ (number_format((float) $inwardQtyGrandTotal, 2, '.', '')) }}</td>
                    <td>{{ (number_format((float) $inwardRateGrandTotal, 2, '.', '')) }}</td>
                    <td>{{ (number_format((float) $inwardValueGrandTotal, 2, '.', '')) }}</td>
                    <td>{{ (number_format((float) $outwardQtyGrandTotal, 2, '.', '')) }}</td>
                    <td>{{ (number_format((float) $outwardRateGrandTotal, 3, '.', '')) }}</td>
                    <td>{{ (number_format((float) $outwardValueGrandTotal, 3, '.', '')) }}</td>
                    <td>{{ (number_format((float) $closingQty, 2, '.', '')) }}</td>
                    <td>{{ (number_format((float) $closingRate, 3, '.', '')) }}</td>
                    <td>{{ (number_format((float) $closingValue, 3, '.', '')) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
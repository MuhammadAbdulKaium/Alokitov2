<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stock Summary Report</title>
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

            <h1 style="text-align: center;float: left">Stock Summary Report</h1>
            <div style="float: left;width: 2%;font-size: xx-small;padding: 0;margin: 0">

            </div>
        </div>
        <div>
            <h3>Stock Summary</h3>
            <h4>{{ $fromDate }} to {{ $toDate }}</h4>
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
                        <th rowspan="2" colspan="1">Name of Stockitem</th>
                        <th colspan="3">Opening</th>
                        <th colspan="3">Inward</th>
                        <th colspan="3">Outward</th>
                        <th colspan="3">Closing</th>
                    </tr>
                    <tr>
                        <th>Qty.</th>
                        <th>Rate</th>
                        <th>Value</th>
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
                    @foreach ($stocks as $stock)
                        @foreach ($stock as $product)
                            @if ($product['product_name'])                   
                                <tr>
                                    <td>{{ $index1 + 1 }}</td>
                                    @php
                                        $index1++;
                                    @endphp
                                    <td>{{ $product['product_name'] }}</td>
                                    <td>{{ $product['opening_qty'] }}</td>
                                    <td>{{ (number_format((float) $product['opening_rate'], 3, '.', '')) }}</td>
                                    <td>{{ (number_format((float)$product['opening_value'], 3, '.', '')) }}</td>
                                    <td>{{ $product['inward_qty'] }}</td>
                                    <td>{{ (number_format((float)$product['inward_rate'], 3, '.', '')) }}</td>
                                    <td>{{ (number_format((float)$product['inward_value'], 3, '.', '')) }}</td>
                                    <td>{{ $product['outward_qty'] }}</td>
                                    <td>{{ (number_format((float)$product['outward_rate'], 3, '.', '')) }}</td>
                                    <td>{{ (number_format((float)$product['outward_value'], 3, '.', '')) }}</td>
                                    <td>{{ $product['closing_qty'] }}</td>
                                    <td>{{ (number_format((float)$product['closing_rate'], 3, '.', '')) }}</td>
                                    <td>{{ (number_format((float)$product['closing_value'], 3, '.', '')) }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                    <tr style="font-weight: bold; background: #dee2e6;">
                        <td colspan="2">Grand Total:</td>
                        <td>{{ (number_format((float) $totalOpeningQty, 2, '.', '')) }}</td>
                        <td></td>
                        <td>{{ (number_format((float) $totalOpeningValue, 2, '.', '')) }}</td>
                        <td>{{ (number_format((float) $totalInwardQty, 2, '.', '')) }}</td>
                        <td></td>
                        <td>{{ (number_format((float) $totalInwardValue, 2, '.', '')) }}</td>
                        <td>{{ (number_format((float) $totalOutwardQty, 3, '.', '')) }}</td>
                        <td></td>
                        <td>{{ (number_format((float) $totalOutwardValue, 3, '.', '')) }}</td>
                        <td>{{ (number_format((float) $totalClosingQty, 2, '.', '')) }}</td>
                        <td></td>
                        <td>{{ (number_format((float) $totalClosingValue, 3, '.', '')) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
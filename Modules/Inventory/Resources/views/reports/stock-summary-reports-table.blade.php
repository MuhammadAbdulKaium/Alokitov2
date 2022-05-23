@php
    $extraCol = 1;
    $rowNumber=0;
    $index1=0;
@endphp

<style>
    h4{
        font-weight: bold;
        font-size: 18px;
    }
    h5{
        font-weight: bold;
        font-size: 16px;
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
</style>

<div>
    <h3>Stock Summary</h3>
    <h4>{{ $fromDate }} to {{ $toDate }}</h4>
    {{-- <h5>Group: , Category: , Store: </h5> --}}
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
                    @php
                        $index1++;
                    @endphp                  
                        <tr>
                            <td>{{ $index1 }}</td>
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
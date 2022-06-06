@php
    $extraCol = 1;
    $rowNumber=0;
    $index1=0;
    $group=null;
    $openingQtySub=0;
    $openingValueSub=0;
    $inwardQtySub=0;
    $inwardValueSub=0;
    $outwardQtySub=0;
    $outwardValueSub=0;
    $closingQtySub=0;
    $closingValueSub=0;
    $numberOfProducts = 0;
    $i=0
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
    <table class="table table-bordered ">
        <thead style="background: #dee2e6;">
            <tr>
                <th rowspan="2" colspan="1" style="padding-bottom: 20px;">#</th>
                <th colspan="3">Name of Stockitem</th>
                <th colspan="3">Opening</th>
                <th colspan="3">Inward</th>
                <th colspan="3">Outward</th>
                <th colspan="3">Closing</th>
            </tr>
            <tr>
                <th>Product</th>
                <th>UoM</th>
                <th>SKU</th>
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
                @php
                    $numberOfProducts = count($stock);
                    $i = 0;
                @endphp
                @foreach ($stock as $product)
                    @if ($product['product_name'])
                        @if(end($stock) && $product['group_name'] != $group && $group != null)
                            <tr style="font-weight: bold; background: #dee2e6;">
                                <td colspan="4">Sub Total:</td>
                                <td>{{ (number_format((float) $openingQtySub, 2, '.', '')) }}</td>
                                <td></td>
                                <td>{{ (number_format((float) $openingValueSub, 2, '.', '')) }}</td>
                                <td>{{ (number_format((float) $inwardQtySub, 2, '.', '')) }}</td>
                                <td></td>
                                <td>{{ (number_format((float) $inwardValueSub, 2, '.', '')) }}</td>
                                <td>{{ (number_format((float) $outwardQtySub, 3, '.', '')) }}</td>
                                <td></td>
                                <td>{{ (number_format((float) $outwardValueSub, 3, '.', '')) }}</td>
                                <td>{{ (number_format((float) $closingQtySub, 2, '.', '')) }}</td>
                                <td></td>
                                <td>{{ (number_format((float) $closingValueSub, 3, '.', '')) }}</td>
                            </tr> 
                        @endif
                        @php
                            $index1++;
                        @endphp  
                        @if ($product['group_name'] != $group || $group == null)
                            <tr>
                                <td></td>
                                <td style="text-align: left; font-weight: bold;">{{ $product['group_name'] }}</td>
                            </tr> 
                            @php
                                $group = $product['group_name'];
                                $openingQtySub=0;
                                $openingValueSub=0;
                                $inwardQtySub=0;
                                $inwardValueSub=0;
                                $outwardQtySub=0;
                                $outwardValueSub=0;
                                $closingQtySub=0;
                                $closingValueSub=0;
                                $i++;
                            @endphp
                        @endif
                        <tr>
                            <td>{{ $index1 }}</td>
                            <td style="text-align: left; padding-left:40px; width:240px">{{ $product['product_name'] }}</td>
                            <td>{{ $product['unit'] }}</td>
                            <td>{{ $product['sku'] }}</td>
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
                        @if ($product['group_name'] == $group)
                            @php
                                $openingQtySub += $product['opening_qty'];
                                $openingValueSub += $product['opening_value'];
                                $inwardQtySub += $product['inward_qty'];
                                $inwardValueSub += $product['inward_value'];
                                $outwardQtySub += $product['outward_qty'];
                                $outwardValueSub += $product['outward_value'];
                                $closingQtySub += $product['closing_qty'];
                                $closingValueSub += $product['closing_value'];
                            @endphp
                        @endif
                    @endif
                @endforeach
                @if($loop->last)
                    <tr style="font-weight: bold; background: #dee2e6;">
                        <td colspan="4">Sub Total:</td>
                        <td>{{ (number_format((float) $openingQtySub, 2, '.', '')) }}</td>
                        <td></td>
                        <td>{{ (number_format((float) $openingValueSub, 2, '.', '')) }}</td>
                        <td>{{ (number_format((float) $inwardQtySub, 2, '.', '')) }}</td>
                        <td></td>
                        <td>{{ (number_format((float) $inwardValueSub, 2, '.', '')) }}</td>
                        <td>{{ (number_format((float) $outwardQtySub, 3, '.', '')) }}</td>
                        <td></td>
                        <td>{{ (number_format((float) $outwardValueSub, 3, '.', '')) }}</td>
                        <td>{{ (number_format((float) $closingQtySub, 2, '.', '')) }}</td>
                        <td></td>
                        <td>{{ (number_format((float) $closingValueSub, 3, '.', '')) }}</td>
                    </tr> 
                @endif
            @endforeach
            <tr style="font-weight: bold; background: #dee2e6;">
                <td colspan="4">Grand Total:</td>
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
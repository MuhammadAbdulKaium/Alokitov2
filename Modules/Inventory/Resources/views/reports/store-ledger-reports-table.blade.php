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
    <h3>Store Ledger</h3>
    <h4>{{ $product->product_name }},  {{ $fromDate }} to {{ $toDate }}</h4>
    <h5>Group: {{ $group->stock_group_name }}, Category: {{ $category->stock_category_name }}, Store: @if ($all=='all')All
                                                                                                    
                                                                                                    @else {{ '(' }} @foreach ($all as $al) {{ $al->store_name }}, 
                                                                                                        
                                                                                                    @endforeach {{ ')' }}           
        
                                                                                                    @endif
    </h5>
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
    {{-- <ul>
        @foreach ($products as $product1)
            <li>{{ $product1->product_name }}</li>
        @endforeach
    </ul>
    

    <h4>Product Name, <span>From Date</span> to <span>To Date</span></h4> --}}
</div>
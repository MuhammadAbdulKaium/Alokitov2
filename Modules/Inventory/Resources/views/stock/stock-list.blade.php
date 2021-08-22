@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage  |<small>Stock List</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Inventory</a></li>
                <li class="active">Stock List</li>
            </ul>
        </section>

        <section class="content">

            <div id="p0">
                @if ($errors->any())
                    <div class="alert alert-danger alert-auto-hide">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(Session::has('message'))
                    <p class="alert alert-success alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
                @elseif(Session::has('alert'))
                    <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
                @elseif(Session::has('errorMessage'))
                    <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
                @endif
            </div>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> Stock List </h3>
                    <div class="box-tools">
                        <a class="btn btn-success btn-sm" href="{{ url('inventory/add/stock-product') }}" data-target="#globalModal" data-toggle="modal"><i class="fa fa-plus-square"></i> New</a>
                        <a class="btn btn-success btn-sm" href="#"><i class="fa fa-plus-square"></i> Import</a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Barcode</th>
                                    <th>QRCode</th>
                                    <th>Alias</th>
                                    <th>Group</th>
                                    <th>Category</th>
                                    <th>Current Qty</th>
                                    <th>Current Rate</th>
                                    <th>Current Value</th>
                                    <th>Min Level</th>
                                    <th>Reorder Level</th>
                                    <th>Item Type</th>
                                    <th>Decimal places</th>
                                    <th>Store Tagging</th>
                                    <th>History</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stocks as $stock)
                                    <?php 
                                        if(array_key_exists($stock->id,$itemWiseStockInfo)){
                                            $itemStock = $itemWiseStockInfo[$stock->id];
                                        }
                                        $decimal_point_place = (!empty($stock->decimal_point_place))?$stock->decimal_point_place:0;
                                        $sku = (!empty($stock->sku))?$stock->sku:'';
                                    ?>
                                    <tr>
                                        <td>#</td>
                                        <td>{{$stock->product_name}}</td>
                                        <td align="center" valign="middle">{{$stock->sku}}</td>
                                        <td align="center" valign="middle"><img src="data:image/png;base64,{{DNS1D::getBarcodePNG($sku, 'C39',1,33,array(0,0,0), true)}}" alt="barcode" width="90" height="30" /></td>
                                        <td>
                                            <?php echo DNS2D::getBarcodeHTML($sku, 'QRCODE',2,2); ?>
                                        </td>
                                        <td>{{$stock->alias}}</td>
                                        <td>{{$stock->stock_group_name}}</td>
                                        <td>{{$stock->stock_category_name}}</td>
                                        <td>{{@$itemStock['current_stock']}}</td>
                                        <td>{{@$itemStock['avg_cost_price']+0}}</td>
                                        <td>{{round(@$itemStock['current_stock']*@$itemStock['avg_cost_price'], $decimal_point_place)}}</td>
                                        <td>{{$stock->min_stock}}</td>
                                        <td>{{$stock->reorder_qty}}</td>
                                        <td>{{($stock->item_type==1)?'General Goods':'Finished Goods'}}</td>
                                        <td>{{$stock->decimal_point_place}}</td>
                                        <td>
                                            <?php $i=0; ?>
                                            @foreach($stock->store_tagging as $tag)
                                                <b class="text-info">{{@$stores[$tag]->store_name}} <?php echo (++$i==count($stock->store_tagging))?'':','; ?></b>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a class="btn btn-success btn-sm" href="/inventory/show/history/stock-product/{{$stock->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square"></i> History </a>
                                        </td>
                                        <td><a class="btn btn-success btn-sm" href="/inventory/edit/inventory/stock-product/{{$stock->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square"></i> Edit </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </section>
    </div>

    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 900px">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



@section('scripts')
<script>
    $('#dataTable').DataTable();

    $(document).ready(function() {
        $('#fromDate').datepicker();
        $('#toDate').datepicker();

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
</script>   
@endsection

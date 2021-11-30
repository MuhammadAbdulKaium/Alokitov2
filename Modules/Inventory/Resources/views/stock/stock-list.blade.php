@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage  |<small>Stock Master</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Inventory</a></li>
                <li class="active">Stock Master</li>
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
                    <h3 class="box-title"><i class="fa fa-search"></i> Stock Master </h3>
                    <div class="box-tools">
                        <a class="btn btn-success btn-sm" href="{{ url('inventory/add/stock-product') }}" data-target="#globalModal" data-toggle="modal"><i class="fa fa-plus-square"></i> New</a>
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
                                    <th class="no-sort">Current Qty</th>
                                    <th class="no-sort">Current Rate</th>
                                    <th class="no-sort">Current Value</th>
                                    <th>Min Level</th>
                                    <th>Reorder Level</th>
                                    <th>Item Type</th>
                                    <th>Decimal places</th>
                                    <th class="no-sort">Store Tagging</th>
                                    <th class="no-sort">History</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            
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
    //$('#dataTable').DataTable();
    $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{url('inventory/stock-list-data')}}",
        columns: [
            { data: 'id' },
            { data: 'product_name' },
            { data: 'sku' },
            { data: 'barcode' },
            { data: 'qrcode' },
            { data: 'alias' },
            { data: 'stock_group_name' },
            { data: 'stock_category_name' },
            { data: 'current_stock' },
            { data: 'avg_cost_price' },
            { data: 'current_value' },
            { data: 'min_stock' },
            { data: 'reorder_qty' },
            { data: 'item_type' },
            { data: 'decimal_point_place' },
            { data: 'store_name' },
            { data: 'history' },
            { data: 'action' }
        ],
        "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
        } ]
    });

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

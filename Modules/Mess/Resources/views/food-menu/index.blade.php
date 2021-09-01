@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
<link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">

    <style>
        .my-tooltip{
            background: #fff;
            position: absolute;
            min-width: 300px;
            height: auto;
            z-index: 10;
            display: none;
            padding: 8px;
            box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
        }
    </style>

@stop


{{-- Content --}}
@section('content')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Student Mess Management |<small>Food Menu</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Student Mess Management</a></li>
            <li class="active">Food Menu</li>
        </ul>
    </section>
    <section class="content">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif
        <div class="row">
            <div class="col-sm-3">
                <form action="{{ url('/mess/food-menu/store/category') }}" method="POST">
                    @csrf

                    <div class="box box-solid">
                        <div class="box-header">
                            <h4><i class="fa fa-plus-square"></i> Category</h5>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <input type="text" name="categoryName" class="form-control" placeholder="Category Name" required>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-success btn-create">Create</button>
                            <button type="button" class="btn btn-default reset-fields-btn">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-4">
                <form action="{{ url('/mess/food-menu/store/menu') }}" method="POST">
                    @csrf

                    <div class="box box-solid">
                        <div class="box-header">
                            <h4><i class="fa fa-plus-square"></i> Menu Name</h5>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-control" name="categoryId" id="" required>
                                            <option value="">--Select Category--</option>
                                            @foreach ($foodMenuCategories as $foodMenuCategory)
                                                <option value="{{ $foodMenuCategory->id }}">{{ $foodMenuCategory->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="menuName" class="form-control" placeholder="Menu Name" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-success btn-create">Create</button>
                            <button type="button" class="btn btn-default reset-fields-btn">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-5">
                <form action="{{ url('/mess/food-menu/store/menu/item') }}" method="POST">
                    @csrf

                    <div class="box box-solid">
                        <div class="box-header">
                            <h4><i class="fa fa-plus-square"></i> Menu Item</h5>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="itemName" class="form-control" placeholder="Menu Item" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <select class="form-control" name="uomId" id="" required>
                                            <option value="">--UoM--</option>
                                            @foreach ($uoms as $uom)
                                                <option value="{{ $uom->id }}">{{ $uom->formal_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="number" name="value" class="form-control" placeholder="Value" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="number" name="cost" class="form-control" placeholder="Costing" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-success btn-create">Create</button>
                            <button type="button" class="btn btn-default reset-fields-btn">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="box box-solid">
                    <div class="box-header">
                        <h4><i class="fa fa-plus-square"></i> Category List</h5>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="categoryTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($foodMenuCategories as $foodMenuCategory)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $foodMenuCategory->category_name }}</td>
                                        <td>
                                            <a class="btn btn-xs btn-primary" href="{{url('/mess/food-menu/category/edit/'.$foodMenuCategory->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-sm"><i class="fa fa-edit"></i></a>
                                            <a href="{{ url('/mess/food-menu/category/delete/'. $foodMenuCategory->id) }}"
                                                class="btn btn-danger btn-xs"
                                                onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                                data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="box box-solid">
                    <div class="box-header">
                        <h4><i class="fa fa-plus-square"></i> Menu Name List</h5>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover" id="menuNameTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Menu Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($foodMenus as $foodMenu)
                                    @php
                                        $menuItems = ($foodMenu->menu_items)?json_decode($foodMenu->menu_items, 1):[];
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $foodMenu->category->category_name }}</td>
                                        <td>
                                            <span class="menu-name">{{ $foodMenu->menu_name }}</span>
                                            <div class="my-tooltip">
                                                <h5><b># {{ $foodMenu->menu_name }}</b></h5>
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Item</th>
                                                            <th>Qty</th>
                                                            <th>H. Value</th>
                                                            <th>Costing</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $totalValue = 0;
                                                            $totalCost = 0;
                                                        @endphp
                                                        @forelse ($menuItems as $item)
                                                            @php
                                                                $foodMenuItem = $foodMenuItems->firstWhere('id', $item['id']);
                                                                $uom = $uoms->firstWhere('id', $foodMenuItem->uom_id);

                                                                $totalValue += $foodMenuItem->value * $item['qty'];
                                                                $totalCost += $foodMenuItem->cost * $item['qty'];
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $foodMenuItem->item_name }}</td>
                                                                <td>{{ $item['qty'] }} {{ $uom->symbol_name }}</td>
                                                                <td>{{ $foodMenuItem->value * $item['qty'] }}</td>
                                                                <td>{{ $foodMenuItem->cost * $item['qty'] }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5" style="text-align: center">No Items assigned</td>
                                                            </tr>
                                                        @endforelse
                                                        <tr>
                                                            <td></td>
                                                            <td>Total:</td>
                                                            <td>{{ $totalValue }}</td>
                                                            <td>{{ $totalCost }}</td>
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn btn-xs btn-success" href="{{url('/mess/food-menu/assign-item/view/'.$foodMenu->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">A</a>
                                            <a class="btn btn-xs btn-primary" href="{{url('/mess/food-menu/edit/'.$foodMenu->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-edit"></i></a>
                                            <a href=""
                                                class="btn btn-danger btn-xs"
                                                onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                                data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="box box-solid">
                    <div class="box-header">
                        <h4><i class="fa fa-plus-square"></i> Menu Item List</h5>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="menuItemTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Menu Item</th>
                                    <th>UoM</th>
                                    <th>H.Value</th>
                                    <th>Costing</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($foodMenuItems as $foodMenuItem)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td><span data-toggle="tooltip" data-placement="top" title="{{ $foodMenuItem->item_name }}">{{ $foodMenuItem->item_name }}</span></td>
                                        <td>{{ $foodMenuItem->uom->symbol_name }}</td>
                                        <td>{{ $foodMenuItem->value }}</td>
                                        <td>{{ $foodMenuItem->cost }}</td>
                                        <td>
                                            <a href="" class="btn btn-xs btn-success">A</a>
                                            <a class="btn btn-xs btn-primary" href="{{url('/mess/food-menu/item/edit/'.$foodMenuItem->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-edit"></i></a>
                                            <a href=""
                                                class="btn btn-danger btn-xs"
                                                onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                                data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
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
    </section>
</div>

@stop

{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('#categoryTable').DataTable();
        $('#menuNameTable').DataTable();
        $('#menuItemTable').DataTable();

        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        $('.menu-name').mouseover(function () {
            $(this).parent().find('.my-tooltip').css('display', 'block');            
        });
        $('.menu-name').mouseout(function () {
            $(this).parent().find('.my-tooltip').css('display', 'none');            
        });

        $('.reset-fields-btn').click(function () {
            var parent = $(this).parent().parent();
            var allFields = parent.find(':input');

            allFields.each((index, value) => {
                $(value).val('');
            }); 
        });
    });
</script>
@stop
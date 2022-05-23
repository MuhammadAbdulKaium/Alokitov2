@extends('layouts.master')

@section('styles')
<style>
    /* .select2-selection__rendered {
    line-height: 30px !important;
    } */
    .select2-container .select2-selection--single {
        height: 34px;
        border-radius: 1px;
    }
    .select2-selection__arrow {
        height: 31px !important;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />

    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Report |<small>Stock Summary Report</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/">Inventory</a></li>
            <li>Reports</li>
            <li class="active">Stock Summary Report</li>
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
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Stock Summary Report </h3>
            </div>
            <form id="search-results-form" method="POST" action="{{ url('/inventory/stock-summary-report/stock-report') }}" target="_blank">
                @csrf
                <input type="hidden" name="type" class="select-type" value="search">

                <div class="box-body">
                    <div class="row">
                        {{-- <form id="search-results-form" method="POST" action="{{ url('/inventory/stock-summary-report/stock-report') }}" target="_blank">
                            @csrf
                            <input type="hidden" name="type" class="select-type" value="search"> --}}

                            <div class="col-sm-2">
                                <label class="control-label" for="product_group">Product Group</label>
                                <select name="groupId[]" id="" class="form-control select-product-group" multiple required>
                                    <option value="all" selected>All</option>
                                    @foreach($stockGroups as $group)
                                        <option value="{{$group->id}}">{{$group->stock_group_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label" for="product_category">Product Category</label>
                                <select name="categoryId[]" id="" class="form-control select-product-category" multiple required>
                                    <option value="all" selected>All</option>
                                    @foreach($productCatagories as $category)
                                        <option value="{{$category->id}}">{{$category->stock_category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label" for="product_vendor">Product Store</label>
                                <select name="storeId[]" id="" class="form-control select-store" multiple required>
                                    <option value="all" selected>All</option>
                                    @foreach($stores as $store)
                                        <option value="{{$store->id}}">{{$store->store_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label" for="product">Product</label>
                                <select name="productId[]" id="" class="form-control select-product" style="height: 32px" multiple required>
                                    <option value="all" selected>All</option>
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}">{{$product->product_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label" for="academic_level">From Date</label>
                                <input type="date" name="fromDate" class="form-control select-from-date" required>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label" for="academic_level">To Date</label>
                                <input type="date" name="toDate" class="form-control select-to-date" required>
                            </div>
                        {{-- </form> --}}
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-success search-btn" style="margin-top: 23px"><i class="fa fa-search"></i> Search</button>
                            <button type="button" class="btn btn-primary print_btn" style="margin-top: 23px; margin-left: 20px"><i class="fa fa-print"></i> Print</button>
                            {{-- <button type="reset" class="btn btn-default" style="margin-top: 23px; margin-left: 20px">Reset</button> --}}
                            <button type="submit" class="print-submit-btn" style="display: none"></button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="marks-table-holder table-responsive">
                    
            </div>
        </div>
    </section>
</div>

@endsection


{{-- Scripts --}}
@section('scripts')
<script>
    $(document).ready(function () {
        $('.select-product').select2({
            placeholder: "Select product*",
        });
        $('.select-product-group').select2({
            
        });
        $('.select-product-category').select2({
            
        });
        $('.select-store').select2({
            
        });

        var groups = [];
        var categoryId = [];
        var storeId =[]
        var productId = [];
        var fromDate = [];
        var toDate = [];

        
        $('.search-btn').click(function() {
            groupId = $('.select-product-group').val();
            categoryId = $('.select-product-category').val();
            storeId = $('.select-store').val();
            productId = $('.select-product').val();
            fromDate = $('.select-from-date').val();
            toDate = $('.select-to-date').val();

            console.log(groupId);
            console.log(categoryId);
            console.log(storeId);
            console.log(productId);
            console.log(fromDate);
            console.log(toDate);

            if(groupId && categoryId && storeId && productId && fromDate && toDate) {
                $('.select-type').val('search');
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/inventory/stock-summary-report/stock-report') }}",
                    type: 'POST',
                    cache: false,
                    data: $('form#search-results-form').serialize(),
                    datatype: 'application/json',
                
                    beforeSend: function () {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                        console.log('beforeSend');
                    },
                
                    success: function (data) {
                        // hide waiting dialog
                        waitingDialog.hide();
                
                        console.log(data);

                        $('.marks-table-holder').html(data);

                    },
                
                    error: function (error) {
                        // hide waiting dialog
                        waitingDialog.hide();
                
                        console.log(error);
                        console.log('error');
                    }
                });
            } else {
                swal('Error!', 'Please Fill up all the required fields first.', 'error');
            }
        });


        $('.print_btn').click(function () {
            $('.select-type').val('print');
            $('.print-submit-btn').click();
        });

    });
</script>
@stop
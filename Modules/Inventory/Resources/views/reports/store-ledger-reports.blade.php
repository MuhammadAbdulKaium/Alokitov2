@extends('layouts.master')

@section('styles')
<style>
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
            <i class="fa fa-th-list"></i> Report |<small>Store Ledger Report</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/">Inventory</a></li>
            <li>Reports</li>
            <li class="active">Store Ledger Report</li>
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
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Store Ledger Report </h3>
            </div>
            <form id="search-results-form" method="GET" action="{{ url('/inventory/store-ledger-report/item-report') }}" target="_blank">
                @csrf
                <input type="hidden" name="type" class="select-type" value="search">

                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label" for="product_group">Product Group</label>
                            <select id="product_group" class="form-control select-product-group" name="productGroupId" required>
                                <option value="">--- Select Group* ---</option>
                                @foreach($stockGroups as $group)
                                    <option value="{{$group->id}}">{{$group->stock_group_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" for="product_category">Product Category</label>
                            <select id="product_category" class="form-control select-product-category" name="productCategoryId" required>
                                <option value="" selected>--- Select Category* ---</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" for="store">Product Store</label>
                            <select name="storeId[]" id="" class="form-control select-store" style="height: 32px" multiple required>
                                <option value="all" selected>All</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" for="product">Product</label>
                            <select id="product" class="form-control select-product" name="productId" style="height: 32px" required>
                                <option value="" selected>--- Select Product* ---</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" for="academic_level">From Date</label>
                            <input type="date" value="{{ $fromDate }}" name="fromDate" class="form-control select-from-date" required>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" for="academic_level">To Date</label>
                            <input type="date" value="{{ $toDate }}" name="toDate" class="form-control select-to-date" required>
                        </div>
                    
                        <div class="col-sm-12" >
                            <button type="button" class="btn btn-success search-btn"  style="margin-top: 23px;"><i class="fa fa-search"></i> Search</button>
                            <button type="button" class="btn btn-primary print-btn" style="margin-top: 23px; margin-left: 20px"><i class="fa fa-print"></i> Print</button>
                            <button type="reset" class="btn btn-default" style="margin-top: 23px; margin-left: 20px">Reset</button>
                            <button type="submit" class="print-submit-btn" style="display: none"></button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="marks-table-holder table-responsive box-body">
                    
            </div>
        </div>
    </section>
</div>

@endsection




@section('scripts')

<script>
    $(document).ready(function () {

        var groupId = null;
        var categoryId = null;
        var storeId = null;
        var productId = null;
        var fromDate = null;
        var toDate = null;

        $('.select-product-group').change(function () {
            groupId = $('.select-product-group').val();

            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/inventory/store-ledger-report/search-category') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'data': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                    console.log('beforeSend');
                },
            
                success: function (data) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log('success');


                    var txt = '<option value="" selected>--- Select Category* ---</option>';
                    var store = '<option value="all" selected>All</option> @foreach($stores as $store) <option value="{{$store->id}}">{{$store->store_name}}</option> @endforeach';
                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.stock_category_name+'</option>'
                    });

                    $('.select-product-category').html(txt);
                    $('.select-product').select2("val", "");
                    $('.select-store').html(store);
                },
            
                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);
                    console.log('error');
                }
            });
            // Ajax Request End
        });

        
        $('.select-store').select2({
            // placeholder: "All store",
        });

        $('.select-product').select2({
            placeholder: "--- Select Product* ---",
        });
        
        $('.select-product-category').change(function () {
            categoryId = $('.select-product-category').val();

            if(groupId && categoryId) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/inventory/store-ledger-report/search-product') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'data': $(this).val(),
                        'groupId': groupId,
                        'categoryId': categoryId,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                        console.log('beforeSend');
                    },
                
                    success: function (data) {
                        // hide waiting dialog
                        waitingDialog.hide();
                
                        console.log('success');

                        var txt = '<option value="" selected>--- Select Product* ---</option>';
                        data.forEach(element => {
                            txt += '<option value="'+element.id+'">'+element.product_name+'</option>'
                        });

                        $('.select-product').html(txt);
                        $('.select-product').select2("val", "");
                    },
                
                    error: function (error) {
                        // hide waiting dialog
                        waitingDialog.hide();
                
                        console.log(error);
                        console.log('error');
                    }
                });
                // Ajax Request End
            }
        });

        $('.select-from-date').change(function () {
            var fromDate = null;
            fromDate = $('.select-from-date').val();
        });

        $('.search-btn').click(function() {
            groupId = $('.select-product-group').val();
            categoryId = $('.select-product-category').val();
            storeId = $('.select-store').val();
            productId = $('.select-product').val();
            fromDate = $('.select-from-date').val();
            toDate = $('.select-to-date').val();


            if(groupId && categoryId && storeId && productId && fromDate && toDate) {
                $('.select-type').val('search');
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/inventory/store-ledger-report/item-report') }}",
                    type: 'GET',
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

        $('.print-btn').click(function () {
            $('.select-type').val('print');
            $('.print-submit-btn').click();
        });
    });
</script>
@stop
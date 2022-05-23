@extends('layouts.master')

@section('styles')
<style>
    .select2-selection__rendered {
    line-height: 30px !important;
    }
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
            {{-- @if(in_array('student/detail/report/layout', $pageAccessData)) --}}
                <div class="box-header with-border">
                    <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Print Store Ledger Report </h3>
                </div>
            {{-- @endif --}}
            <form id="store_ledger_search_form" method="POST">
                @csrf
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label" for="product_group">Product Group</label>
                            <select id="product_group" class="form-control select-product-group" name="product_group" required>
                                <option value="">--- Select Group* ---</option>
                                @foreach($stockGroups as $group)
                                    <option value="{{$group->id}}">{{$group->stock_group_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" for="product_category">Product Category</label>
                            <select id="product_category" class="form-control select-product-category" name="product_category" required>
                                <option value="" selected>--- Select Category* ---</option>
                                {{-- @foreach($productCatagories as $category)
                                    <option value="{{$category->id}}">{{$category->stock_category_name}}</option>
                                @endforeach --}}
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" for="product">Product</label>
                            <select id="product" class="form-control select-product" name="product" style="height: 32px" required>
                                <option value="" selected>--- Select Product* ---</option>
                                <!-- @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->product_name}}</option>
                                @endforeach -->
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" for="academic_level">From Date</label>
                            <input type="date" name="store_ledger_report_from_date" class="form-control select-from-date" required>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" for="academic_level">To Date</label>
                            <input type="date" name="store_ledger_report_to_date" class="form-control select-to-date" required>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-success search-btn"  style="margin-top: 23px">Search</button>
                            <button type="reset" class="btn btn-default" style="margin-top: 23px; margin-left: 20px">Reset</button>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 30px">
                        <div class="col-sm-12 student-list-holder">

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<div class="modal"  id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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
@endsection



{{-- Scripts --}}

@section('scripts')
<!-- <script src="{{ asset('ckeditor/ckeditor.js') }}"></script> -->

<script>
    $(document).ready(function () {

        var groupId = null;
        var categoryId = null;

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
                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.stock_category_name+'</option>'
                    });

                    $('.select-product-category').html(txt);
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


        $('.select-product').select2({
            placeholder: "--- Select Product* ---",
        });
        $('.select-product-category').change(function () {
            categoryId = $('.select-product-category').val();
            console.log(groupId);
            console.log(categoryId);

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

        var group = null;
        var category = null;
        var product = null;
        var fromDate = null;
        var toDate = null;

        $('.search-btn').click(function() {
            group = $('.select-product-group').val();
            category = $('.select-product-category').val();
            product = $('.select-product').val();
            fromDate = $('.select-from-date').val();
            toDate = $('.select-to-date').val();

            console.log(group);
            console.log(category);
            console.log(product);
            console.log(fromDate);
            console.log(toDate);

            if(group && category && product && fromDate && toDate) {
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
            }
        });
    });
</script>
@stop 
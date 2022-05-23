@extends('layouts.master')
@section('content')
<div id="app">
    <div class="content-wrapper">
        <div v-if="!pageLoader">
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
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Store Ledger Report </h3>
                    </div>
                    <div class="box-body">
                        <form action="" class="form-inline">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <multiselect :select-label="''" :deselect-label="''" v-if="product_group_list" name="product_group_model" v-model="filter.product_group_model" :options="dataList.product_group_list"  placeholder="Select Product Group" label="product_group" track-by="id" @input="selectProductGroup" :options-limit="10000"></multiselect>

                                        {{-- <label class="control-label" for="product_group">Product Group</label>
                                        <select id="product_group" class="form-control select-product-group" name="product_group" required>
                                            <option value="">--- Select Group* ---</option>
                                            @foreach($stockGroups as $group)
                                                <option value="{{$group->id}}">{{$group->stock_group_name}}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                    <div class="col-sm-2">
                                        <multiselect :select-label="''" :deselect-label="''" v-if="dataList.product_category_list" name="product_category_model" v-model="filter.product_category_model" :options="dataList.product_category_list"  placeholder="Select Product Category" label="product_category" track-by="id" @input="selectVoucher" :options-limit="10000"></multiselect>

                                        {{-- <label class="control-label" for="product_category">Product Category</label>
                                        <select id="product_category" class="form-control select-product-category" name="product_category" required>
                                            <option value="" selected>--- Select Category* ---</option>
                                            @foreach($productCatagories as $category)
                                                <option value="{{$category->id}}">{{$category->stock_category_name}}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                    <div class="col-sm-2">
                                        <multiselect :select-label="''" :deselect-label="''" v-if="dataList.product_list" name="product_id_model" v-model="filter.product_id_model" :options="dataList.product_list"  placeholder="Select Product" label="product" track-by="id" @input="selectProduct" :options-limit="10000"></multiselect>

                                        {{-- <label class="control-label" for="product">Product</label>
                                        <select id="product" class="form-control select-product" name="product" style="height: 32px" required>
                                            <option value="" selected>--- Select Product* ---</option>
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}">{{$product->product_name}}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                    <div class="col-sm-2">
                                        <v-date-picker v-model="filter.from_date" :config="dateOptions" style="width: 100%;" placeholder="From Date"></v-date-picker>

                                        {{-- <label class="control-label" for="academic_level">From Date</label>
                                        <input type="date" name="store_ledger_report_from_date" class="form-control select-from-date" required> --}}
                                    </div>
                                    <div class="col-sm-2">
                                        <v-date-picker v-model="filter.from_date" :config="dateOptions" style="width: 100%;" placeholder="To Date"></v-date-picker>

                                        {{-- <label class="control-label" for="academic_level">To Date</label>
                                        <input type="date" name="store_ledger_report_to_date" class="form-control select-to-date" required> --}}
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary" @click="getResults(1)"><i class="fa fa-search"></i> Search</button>
                                        <button type="button" class="btn btn-secondary"><i class="fa fa-print"></i> Print <i class="fa fa-caret-down"></i></button>
                                        <button type="reset" class="btn btn-default" style="margin-top: 23px; margin-left: 20px">Reset</button>
                                    </div>
                                </div>

                                <div class="marks-table-holder table-responsive">
                                
                                </div>
                                {{-- <div class="row" style="margin-top: 30px">
                                    <div class="col-sm-12 student-list-holder">

                                    </div>
                                </div> --}}
                            </div>
                        </form>

                        <div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Ledger Name</th>
                                        <th>Ref. #</th>
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
                                    <template>
                                        <tr>

                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr>
                                          <td colspan="13" class="text-center">No Record found!</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div v-if="pageLoader" class="loading-screen">
            <div class="loading-circle"></div>
            <p class="loading-text">Loading</p>
        </div>
    </div>

    {{-- <div class="modal"  id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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
    </div> --}}
</div>
@endsection



@section('scripts')
<script type="text/javascript">
    window.dataUrl = 'store-ledger-report-data';
    window.baseUrl = '{{url('/inventory')}}';
    window.token = '{{@csrf_token()}}';
</script>
<script src="{{URL::asset('vuejs/vue.min.js') }}"></script>
<script src="{{URL::asset('vuejs/uiv.min.js') }}"></script>
<script src="{{URL::asset('vuejs/vue-multiselect.min.js') }}"></script>
<script src="{{URL::asset('vuejs/axios.min.js') }}"></script>
<script src="{{URL::asset('vuejs/vee-validate.js') }}"></script>
<script src="{{URL::asset('vuejs/vue-toastr.umd.min.js') }}"></script>
<script src="{{URL::asset('vuejs/sweetalert2.all.min.js') }}"></script>
<script src="{{URL::asset('vuejs/moment.min.js') }}"></script>
<link href="{{ URL::asset('vuejs/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
<script src="{{URL::asset('vuejs/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{URL::asset('vuejs/vue-bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{URL::asset('vuejs/mixin.js') }}"></script>

<script>
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    Vue.use(VeeValidate);
    Vue.mixin(mixin);
    Vue.component('multiselect', window.VueMultiselect.default);
    Vue.component('v-date-picker', VueBootstrapDatetimePicker); 
    var app = new Vue({
        el: '#app',
        data: {
            filter:{
                product_group_model:'',

            },

        },
    })
</script>

{{-- <script>
    $(document).ready(function () {

        var groupId = null;
        var categoryId = null;
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

        $('.select-from-date').change(function () {
            var fromDate = null;
            fromDate = $('.select-from-date').val();
            console.log(fromDate);
        });

        // var group = null;
        // var category = null;
        // var product = null;
        // var fromDate = null;
        // var toDate = null;

        $('.search-btn').click(function() {
            groupId = $('.select-product-group').val();
            categoryId = $('.select-product-category').val();
            productId = $('.select-product').val();
            fromDate = $('.select-from-date').val();
            toDate = $('.select-to-date').val();

            console.log(groupId);
            console.log(categoryId);
            console.log(productId);
            console.log(fromDate);
            console.log(toDate);

            if(group && category && product && fromDate && toDate) {
                $('.select-type').val('search');
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/inventory/store-ledger-report/item-report') }}",
                    type: 'POST',
                    cache: false,
                    data: {
                        '_token': $_token,
                        // 'data': $(this).val(),
                        'groupId': groupId,
                        'categoryId': categoryId,
                        'productId': productId,
                        'fromDate': fromDate,
                        'toDate': toDate,
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

                        $('.marks-table-holder').html(data);

                        // var txt = '<option value="" selected>--- Select Product* ---</option>';
                        // data.forEach(element => {
                        //     txt += '<option value="'+element.id+'">'+element.product_name+'</option>'
                        // });

                        // $('.select-product').html(txt);
                        // $('.select-product').select2("val", "");
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
</script> --}}
@stop
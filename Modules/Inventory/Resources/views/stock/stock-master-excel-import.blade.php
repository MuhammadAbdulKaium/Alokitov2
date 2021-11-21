@extends('layouts.master')
@section('css')
<style type="text/css">
    
</style>
@endsection
@section('content')
<div id="app">
    <div class="content-wrapper">
        <div v-if="!pageLoader">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Stock Master Excel Import 
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li><a href="#">SOP Setup</a></li>
                    <li><a href="#">Stock</a></li>
                    <li class="active">Stock Master Excel Import</li>
                </ul>
            </section>

            <section class="content">
                <template v-if="!excel_import">
                    <form  @submit.prevent="submitStockExcelFileForm(formData)">
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-file-excel-o"></i> Select File</h3>
                            </div><!--./box-header-->
                            <div class="box-body">
                                <div class="row" style="margin-bottom: 15px">
                                    <div class="col-sm-2">
                                        <label for="">Select Import type:</label>
                                    </div>
                                    <div class="col-sm-3">
                                        <select name="import_type" class="form-control" v-mode="formData.import_type" required data-vv-as="Import type" v-validate="'required'">
                                            <option value="decentralized">Decentralized</option>
                                        </select>
                                        <span class="error" v-if="$validator.errors.has('import_type')">@{{$validator.errors.first('import_type')}}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group field-stumaster-importfile">
                                            <input @change="onFileChange($event, 'stock_master_excel')" id="student_import_file" type="file"  title="Browse Excel File" required>
                                            <span class="error" v-if="$validator.errors.has('stock_master_excel')">@{{$validator.errors.first('stock_master_excel')}}</span>
                                            <div class="hint-block">[<b>NOTE</b> : Only upload <b>.xlsx</b> file format.]</div>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="callout callout-info">
                                            <h4><i class="fa fa-bullhorn" aria-hidden="true"></i> Instructions :</h4>
                                            <ol>
                                                <li>Stock Item name, SKU, Alias, Descrioption, Unit, Type, Group, Category, Min Stock, Item Type and Store tagging are required in the system.</li>
                                                <li>Stock Item name, SKU and Alias should be unique in the system.</li>
                                                <li>Store tagging should be comma separeate if multiple</li>
                                                <li>Special character <strong>(#,&,@,?,(,),:,;,<,>,[,]) </strong> are not allowed in sku name</li>
                                                <li>Max upload records limit is <strong>500</strong>.</li>
                                            </ol>
                                            <h4>
                                                <strong>
                                                    <a href="{{asset("/download/Stock_Master_Template.xlsx")}}" target="_blank">
                                                        Click here to download
                                                    </a>
                                                </strong>
                                                sample format of import data in <b>XLSX</b> format.
                                            </h4>
                                        </div><!--./callout-->
                                    </div><!--./col-->
                                </div><!--./row-->
                            </div><!--./box-body-->
                            <div class="box-footer">
                                <button :disabled="buttonDisabled" type="submit" id="submitBtn" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button>   </div>
                        </div><!--./box-->
                    </form>
                </template>
                <template v-else>
                    <div class="box box-solid">
                        <form  @submit.prevent="submitStockExcelListData(formData)">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-file-excel-o"></i> Stock Item Import</h3>
                            </div><!--./box-header-->

                            <div class="row" v-if="has_error">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="callout callout-danger">
                                        <template v-if="stock_duplicate_errors.length>0 || alias_duplicate_errors.length>0 || sku_duplicate_errors.length>0">
                                            <h4><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Duplicate Errors:</h4>
                                            <template v-if="stock_duplicate_errors.length>0">
                                                <p><b>Stock Item Name duplicate:</b></p>
                                                <ol>
                                                    <li v-for="(error, index) in stock_duplicate_errors">@{{error}}</li>
                                                </ol>
                                            </template>
                                            <template v-if="alias_duplicate_errors.length>0">
                                                <p>Alias duplicate:</p>
                                                <ol>
                                                    <li v-for="(error, index) in alias_duplicate_errors">@{{error}}</li>
                                                </ol>
                                            </template>
                                            <template v-if="sku_duplicate_errors.length>0">
                                                <p>Sku duplicate:</p>
                                                <ol>
                                                    <li v-for="(error, index) in sku_duplicate_errors">@{{error}}</li>
                                                </ol>
                                            </template>
                                        </template>
                                        <template v-if="invalid_data_error.length>0">
                                            <h4><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Invalid Data Errors:</h4>
                                            <ol>
                                                <li v-for="(error, index) in invalid_data_error">@{{error}}</li>
                                            </ol>
                                        </template>
                                    </div><!--./callout-->
                                </div><!--./col-->
                            </div><!--./row-->


                            <div class="box-body">
                                <div class="table-responsive" style="max-height: 500px">
                                    <table class="table table-striped table-bordered m-b-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Stock Item Name</th>
                                                <th>Item Alias</th>
                                                <th>Item Description</th>
                                                <th>SKU</th>
                                                <th>Unit</th>
                                                <th>Type</th>
                                                <th>Group</th>
                                                <th>Catagory</th>
                                                <th>Warranty Month</th>
                                                <th>Min Stock</th>
                                                <th>Reorder Qty</th>
                                                <th>Store Tagging</th>
                                                <th>Additional Remarks</th>
                                                <th>Fraction</th>
                                                <th>Decimal</th>
                                                <th>Round of</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(list, index) in formData.stock_item_list" v-bind:key="index">
                                                <td>@{{index+1}}</td>
                                                <td>
                                                    <input v-bind:class="error_class[index+'_2']" type="text" name="product_name" v-model="list.product_name">
                                                    <span class="error" v-if="error_class[index+'_2']=='input-error'">Filed is required</span>
                                                </td>
                                                <td>
                                                    <input v-bind:class="error_class[index+'_3']" type="text" name="alias" v-model="list.alias">
                                                    <span class="error" v-if="error_class[index+'_3']=='input-error'">Filed is required</span>
                                                </td>
                                                <td>
                                                    <input v-bind:class="error_class[index+'_4']" type="text" name="product_description" v-model="list.product_description">
                                                    <span class="error" v-if="error_class[index+'_4']=='input-error'">Filed is required</span>
                                                </td>
                                                <td>
                                                    <input v-bind:class="error_class[index+'_5']" type="text" name="sku" v-model="list.sku">
                                                    <span class="error" v-if="error_class[index+'_5']=='input-error'">Filed is required</span>
                                                </td>
                                                <td>
                                                    <select name="unit" v-model="list.unit" class="form-control" style="width:150px" v-bind:class="error_class[index+'_6']">
                                                        <option value="">Select Unit</option>
                                                        <option v-for="unit in uom" :value="unit.symbol_name">@{{unit.symbol_name}}</option>
                                                    </select>
                                                    <span class="error" v-if="error_class[index+'_6']=='input-error'">Filed is required</span>
                                                </td>
                                                <td>
                                                    <select v-bind:class="error_class[index+'_7']" name="code_type_id" v-model="list.code_type_id" class="form-control" style="width:150px">
                                                        <option value="">Select Type</option>
                                                        <option value="General">General</option>
                                                        <option value="Finished">Finished</option>
                                                        <option value="Pharmacy">Pharmacy</option>
                                                    </select>
                                                    <span class="error" v-if="error_class[index+'_7']=='input-error'">Filed is required</span>
                                                </td>
                                                <td>
                                                    <select v-bind:class="error_class[index+'_8']" name="stock_group" v-model="list.stock_group" class="form-control" style="width:150px">
                                                        <option value="">Select Group</option>
                                                        <option v-for="group in stockGroup" :value="group.stock_group_name">@{{group.stock_group_name}}</option>
                                                    </select>
                                                    <span class="error" v-if="error_class[index+'_8']=='input-error'">Filed is required</span>
                                                </td>
                                                <td class="width-150">
                                                    <select v-bind:class="error_class[index+'_9']" name="category_id" v-model="list.category_id" class="form-control" style="width:150px">
                                                        <option value="">Select Category</option>
                                                        <option v-for="category in stockCategory" :value="category.stock_category_name">@{{category.stock_category_name}}</option>
                                                    </select>
                                                    <span class="error" v-if="error_class[index+'_9']=='input-error'">Filed is required</span>
                                                </td>
                                                <td>
                                                    <input type="number" name="warrenty_month" v-model="list.warrenty_month" v-bind:class="error_class[index+'_10']">
                                                </td>
                                                <td>
                                                    <input type="number" name="min_stock" v-model="list.min_stock" v-bind:class="error_class[index+'_11']">
                                                    <span class="error" v-if="error_class[index+'_11']=='input-error'">Filed is required</span>
                                                </td>
                                                <td>
                                                    <input type="number" name="reorder_qty" v-model="list.reorder_qty">
                                                </td>
                                                <td>
                                                    <input type="text" name="store_tagging" v-model="list.store_tagging" v-bind:class="error_class[index+'_13']">
                                                    <span class="error" v-if="error_class[index+'_13']=='input-error'">Filed is required</span>
                                                </td>
                                                <td>
                                                    <input type="text" name="additional_remarks" v-model="list.additional_remarks">
                                                </td>
                                                <td>
                                                    <select v-bind:class="error_class[index+'_15']" name="has_fraction" v-model="list.has_fraction" class="form-control" style="width:150px">
                                                        <option value="No">No</option>
                                                        <option value="Yes">Yes</option>
                                                    </select>
                                                    <span class="error" v-if="error_class[index+'_15']=='input-error'">Filed is required</span>
                                                </td>
                                                <td>
                                                    <select name="decimal_point_place" v-bind:class="error_class[index+'_16']" class="form-control" v-model="list.decimal_point_place" style="width:150px" >
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input v-bind:class="error_class[index+'_17']" type="number" name="round_of" v-model="list.round_of">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button @click="backToImport()" type="button" id="submitBtn" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back to Import</button>
                                <button :disabled="buttonDisabled" type="submit" id="submitBtn" class="btn btn-primary pull-right"><i class="fa fa-upload"></i> Submit</button>   
                            </div>
                        </form>
                    </div>
                    
                </template>
            </section>          
        </div>
        <div v-if="pageLoader" class="loading-screen">
          <div class="loading-circle"></div>
          <p class="loading-text">Loading</p>
        </div>
    </div>

    

</div>

@endsection



@section('scripts')
<script type="text/javascript">
    window.dataUrl = 'stock-master-excel-import-data';
    window.baseUrl = '{{url('/inventory')}}';
    window.token = '{{@csrf_token()}}';
</script>
<script src="{{URL::asset('vuejs/vue.min.js') }}"></script>
<script src="{{URL::asset('vuejs/uiv.min.js') }}"></script>
<script src="{{URL::asset('vuejs/axios.min.js') }}"></script>
<script src="{{URL::asset('vuejs/vee-validate.js') }}"></script>
<script src="{{URL::asset('vuejs/vue-toastr.umd.min.js') }}"></script>
<script src="{{URL::asset('vuejs/sweetalert2.all.min.js') }}"></script>
<script src="{{URL::asset('vuejs/mixin.js') }}"></script>
<script>
     axios.defaults.headers.common['X-CSRF-TOKEN'] = token; 
    Vue.use(VeeValidate);
    Vue.mixin(mixin);
    var app = new Vue({
      el: '#app',
      data: {
        formData:{
            import_type:'decentralized',
            need_validation:true,
            stock_item_list:[]
        },
        stockCategory:[],
        stockGroup:[],
        uom:[],
        excel_import:false,
        stock_duplicate_errors:[],
        alias_duplicate_errors:[],
        sku_duplicate_errors:[],
        invalid_data_error:[],
        error_class:[],
        has_error:false
      },
      created(){
      },
      methods:{        
        submitStockExcelFileForm(formData = this.formData) {
            const _this = this;
            const URL = _this.baseUrl + '/stock-master-excel-import-data/upload-stock-excel';
            _this.$validator.validate().then(valid => {
                if (valid) {
                    var formReqData = new FormData();
                    Object.keys(_this.imageData).forEach(key => {
                        formReqData.append(key, _this.imageData[key]);
                    });
                    Object.keys(formData).forEach(key => {
                        formReqData.append(key, formData[key]);
                    });
                    _this.pageLoader = true;
                    _this.buttonDisabled = true;
                    _this.formData.stock_item_list = [];
                    _this.stockCategory = [];
                    _this.stockGroup = [];
                    _this.uom = [];
                    axios.post(URL, formReqData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then((res) => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            if (res.data.status == 1){
                                _this.excel_import=true;
                                _this.formData.stock_item_list = res.data.stock_item_list;
                                _this.stockCategory = res.data.stockCategory;
                                _this.stockGroup = res.data.stockGroup;
                                _this.uom = res.data.uom;
                            }else{
                                _this.showToster(res.data);
                            }
                            _this.pageLoader = false;
                            _this.buttonDisabled = false;
                            
                        }

                    }).catch(function(error) {
                        _this.pageLoader = false;
                        _this.buttonDisabled = false;
                        if (error.response.status == 422) {
                            _this.assignValidationError(error.response.data.errors);
                            var msg = 'Opps! Invalid data';
                        } else {
                            var msg = 'Opps! something went wrong';
                        }
                        _this.showToster({
                            status: 0,
                            message: msg
                        });

                    });
                }
            });
        },
        submitStockExcelListData(formData = this.formData) {
            const _this = this;
            const URL = _this.baseUrl + '/' + _this.dataUrl();
            _this.$validator.validate().then(valid => {
                if (valid) {
                    _this.buttonDisabled = true;
                    _this.pageLoader = true;
                    _this.stock_duplicate_errors=[];
                    _this.alias_duplicate_errors=[];
                    _this.sku_duplicate_errors=[];
                    _this.error_class = [];
                    _this.invalid_data_error = [];
                    _this.has_error=false;
                    axios.post(URL, formData).then((res) => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            if (res.data.status == 1) {
                                _this.excel_import=false;
                                _this.formData={
                                    import_type:'decentralized',
                                    need_validation:true,
                                    stock_item_list:[]
                                };
                                _this.stock_duplicate_errors=[];
                                _this.alias_duplicate_errors=[];
                                _this.sku_duplicate_errors=[];
                                _this.error_class = [];
                                _this.invalid_data_error = [];
                                _this.has_error=false;
                            }else{
                                _this.stock_duplicate_errors=res.data.stock_duplicate_errors;
                                _this.alias_duplicate_errors=res.data.alias_duplicate_errors;
                                _this.sku_duplicate_errors=res.data.sku_duplicate_errors;
                                _this.error_class = res.data.error_class;
                                _this.invalid_data_error = res.data.invalid_data_error;
                                _this.has_error=res.data.has_error;
                            }
                            _this.pageLoader = false;
                            _this.buttonDisabled = false;
                            _this.showToster(res.data);
                        }
                    }).catch(function(error) {
                        _this.pageLoader = false;
                        _this.buttonDisabled = false;
                        if (error.response.status == 422) {
                            _this.assignValidationError(error.response.data.errors);
                            var msg = 'Opps! Invalid data';
                        } else {
                            var msg = 'Opps! something went wrong';
                        }
                        _this.showToster({
                            status: 0,
                            message: msg
                        });

                    });
                }
            });
        },
        backToImport(){
            const _this=this;
            _this.excel_import=false;
            _this.formData.stock_item_list = [];
            _this.stockCategory = [];
            _this.stockGroup = [];
            _this.uom = [];
            _this.stock_duplicate_errors=[];
            _this.alias_duplicate_errors=[];
            _this.sku_duplicate_errors=[];
            _this.error_class = [];
            _this.invalid_data_error = [];
            _this.has_error=false;
        }
      }
    });
    
    

</script>   
@endsection

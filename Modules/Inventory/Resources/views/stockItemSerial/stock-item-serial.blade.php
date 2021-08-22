@extends('layouts.master')
@section('content')
<div id="app">
    <div class="content-wrapper">
        <div v-if="!pageLoader">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Manage  |<small>Stock Item Serial</small>
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li>SOP Setup</li>
                    <li class="active">Stock Item Serial</li>
                </ul>
            </section>

            <section class="content">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Stock Item Serial List</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" @click="openModal('addForm', false,false,emptySerialForm)"><i class="fa fa-plus-square"></i> New</a></div>
                    </div>
                    <div class="box-body">
                        <form class="form-inline">
                            <div class="row" style="padding-bottom: 10px">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Per page</label>
                                        <select name="listPerPage" class="form-control" v-model="listPerPage" @change="getResults(1)">
                                            <option v-for="size in pageSize" :value="size">@{{size}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="dataList.serial_item_list" name="item_id_model" v-model="formData.item_id_model" :options="dataList.serial_item_list"  placeholder="Select item" label="product_name" track-by="id" @input="selectFilterItem"></multiselect>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" v-model="filter.search_key" class="form-control" placeholder="Search by keyword">
                                </div>                           
                                <div class="col-sm-1">
                                    <button class="btn btn-primary" @click="getResults(1)">Search</button>
                                </div>
                                
                            </div>
                        </form>
                        <table class="table table-striped table-bordered m-b-0">
                            <thead>
                                <tr>
                                    <th width="6%">#</th>
                                    <th class="sortable" v-bind:class="getSortingClass('item_id')" @click="sortingChanged('item_id')">Item Name</th>
                                    <th>Serial From</th>
                                    <th>Serial To</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="Object.keys(paginate_data).length > 0">
                                    <tr v-for="(list, index) in paginate_data" v-bind:key="index">
                                        <td>@{{previousPageTotal+index+1}}</td>
                                        <td>@{{list.product_name}}</td>
                                        <td>@{{list.serial_from}}</td>
                                        <td>@{{list.serial_to}}</td>
                                        <td>
                                            <a class="btn btn-primary btn-xs" :href="baseUrl+'/stock-item-serial-data/'+list.id" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-info-circle" aria-hidden="true"></i> Details</a>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                              <tr>
                                <td colspan="5" class="text-center">No Record found!</td>
                              </tr>
                            </template>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-12 pull-right">
                                <pagination v-model="currentPageNo" :total-page="totalPage" @change="getResults" :max-size="10"/>
                            </div>
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

    <div class="modal fade" id="addForm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="form-horizontal" @submit.prevent="submitForm(formData,{},emptySerialForm)">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                                aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Stock Item Serial Generate</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label required">Stock Item <span class="text-danger">*</span></label>
                                    <div class="col-md-8 p-b-15">
                                        <multiselect name="item_id_model" :select-label="''" :deselect-label="''" v-if="dataList.serial_item_list" name="item_id_model" v-model="formData.item_id_model" :options="dataList.serial_item_list"  placeholder="Select item" label="product_name" track-by="id" @input="selectItem" data-vv-as="Stock Item" v-validate="'required'"></multiselect>
                                        <span class="error" v-if="$validator.errors.has('item_id_model')">@{{$validator.errors.first('item_id_model')}}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label required">Serial To <span class="text-danger">*</span></label>
                                    <div class="col-md-8 p-b-15">
                                        <input name="serial_to" type="number" v-model="formData.serial_to" class="form-control" min="1" placeholder="Serial to" data-vv-as="Serial to" v-validate="'required'" @keyup="emptySerialList">
                                        <span class="error" v-if="$validator.errors.has('serial_to')">The Serial to field is required and  must be numeric</span>                                                
                                    </div>
                                </div>                           
                            </div>
                            <div class="col-sm-6">
                                 <div class="form-group">
                                    <label class="col-md-4 control-label required">Serial From <span class="text-danger">*</span></label>
                                    <div class="col-md-8 p-b-15">
                                        <input type="number" name="serial_from" v-model="formData.serial_from" class="form-control" min="1" placeholder="Serial From" data-vv-as="Serial from" v-validate="'required'" @keyup="emptySerialList">
                                        <span class="error" v-if="$validator.errors.has('serial_from')">The Serial from field is required and  must be numeric</span>                                              
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label required"></label>
                                    <div class="col-md-8 p-b-15">
                                        <button type="button" class="btn btn-success" :disabled="buttonDisabled" @click="generateSerial">Preview</button>                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body table-responsive" style="max-height: 300px" v-if="generate_status=='yes'">
                            <table class="responsive table table-striped table-bordered" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Serial Code</th>
                                        <th>Barcode</th>
                                        <th>QR Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="Object.keys(formData.serial_code_list).length > 0">
                                        <tr v-for="(list, index) in formData.serial_code_list" v-bind:key="index">
                                            <td valign="middle">@{{index+1}}</td>
                                            <td valign="middle">@{{list.serial_code}}</td>          
                                            <td valign="middle">
                                                <input type="text" name="barcode" v-model="list.barcode" class="form-control" maxlength="255" placeholder="Barcode">
                                            </td>              
                                            <td valign="middle">
                                                <input type="text" name="qrcode" v-model="list.qrcode" class="form-control" maxlength="255" placeholder="qrcode">
                                            </td>              
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td colspan="4">No code generate!</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" :disabled="buttonDisabled">
                            <span v-if="formData.id">Update</span>
                            <span v-else>Save</span>
                        </button>
                        <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="detailsForm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="form-horizontal" @submit.prevent="submitForm(formData,{},emptySerialForm)">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                                aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Stock Item Serial Details</h4>
                    </div>
                    <div class="modal-body" v-if="!pageLoader">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                   
                                        <tr>
                                            <td><b>Stock Item:</b> @{{modalData.stockItemSerial.product_name}}</td>
                                            <td><b>Prefix:</b> @{{modalData.stockItemSerial.prefix}}</td>
                                            <td><b>Suffix:</b> @{{modalData.stockItemSerial.suffix}}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Separator:</b> @{{modalData.stockItemSerial.separator_symbol}}</td>
                                            <td><b>Serial From:</b> @{{modalData.stockItemSerial.serial_from}}</td>
                                            <td><b>Serial To:</b> @{{modalData.stockItemSerial.serial_to}}</td>
                                        </tr>
                                   
                                </table>
                            </div>
                            
                        </div>
                        <div class="panel-body table-responsive" style="max-height: 300px">
                            <table class="responsive table table-striped table-bordered" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Serial Code</th>
                                        <th>Barcode</th>
                                        <th>QR Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="Object.keys(modalData.serial_code_list).length > 0">
                                        <tr v-for="(list, index) in modalData.serial_code_list" v-bind:key="index">
                                            <td valign="middle">@{{index+1}}</td>
                                            <td valign="middle">@{{list.serial_code}}</td>          
                                            <td valign="middle">
                                                @{{list.barcode}}
                                            </td>              
                                            <td valign="middle">
                                                @{{list.qrcode}}
                                            </td>              
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td colspan="4">No code generate!</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </form>
        </div>
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


</div>

@endsection

@section('scripts')
<script type="text/javascript">
    window.dataUrl = 'stock-item-serial-data';
    window.baseUrl = '{{url('/inventory')}}';
    window.token = '{{@csrf_token()}}';
</script>
<script src="{{URL::asset('vuejs/vue.min.js') }}"></script>
<script src="{{URL::asset('vuejs/uiv.min.js') }}"></script>
<script src="{{URL::asset('vuejs/vue-multiselect.min.js') }}"></script>
<script src="{{URL::asset('vuejs/axios.min.js') }}"></script>
<script src="{{URL::asset('vuejs/vee-validate.js') }}"></script>
<script src="{{URL::asset('vuejs/vue-toastr.umd.min.js') }}"></script>
<script src="{{URL::asset('vuejs/mixin.js') }}"></script>
<script>
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token; 
    Vue.use(VeeValidate);
    Vue.mixin(mixin);
    Vue.component('multiselect', window.VueMultiselect.default)
    var app = new Vue({
      el: '#app',
      data: {
        filter:{
            item_id_model:'',
            item_id:0
        },
        formData:{
            item_id:0,
            serial_code_list:[]
        },
        modalData:{
            stockItemSerial:{},
            serial_code_list:[]
        },
        generate_status:'no' 
      },
      created(){
        this.getResults(1);
      },
      methods:{
        selectFilterItem(item){
            if(item) this.filter.item_id = item.id;
            else this.filter.item_id = 0; 
            this.getResults();
        },
        selectItem(item){
            if(item) this.formData.item_id = item.id;
            else this.formData.item_id = 0;
            this.emptySerialList();
        },
        generateSerial(){
            const _this = this;
            _this.$validator.validate().then(valid => {
                if (valid) {
                    if(parseInt(_this.formData.serial_from)>parseInt(_this.formData.serial_to)){
                        alert('Serial to should be greater then serial from');
                    }else{
                        let URL = _this.baseUrl + '/stock-item-serial-generate';
                        _this.pageLoader = true;
                        axios.get(URL, {
                            params: _this.formData
                        }).then(res => {
                            if (res.data.status == 'logout') {
                                window.location.href = res.data.url;
                            } else {
                                if (res.data.status == 1) {
                                    _this.generate_status='yes';
                                    _this.formData.serial_code_list = res.data.serial_code_list;
                                }else{
                                    _this.generate_status='no';
                                    _this.showToster({
                                        status: 0,
                                        message: res.data.message
                                    });
                                }
                                _this.pageLoader = false;
                                
                            }
                        }).catch(error => {
                            _this.generate_status='no';
                            _this.pageLoader = false;
                            _this.showToster({
                                status: 0,
                                message: 'opps! something went wrong'
                            });
                        });

                    } // else 
                }
            });

        },
        emptySerialList(){
            this.generate_status='no';
            this.formData.serial_code_list=[];
        },
        emptySerialForm(formData={}){
            this.generate_status='no';
            this.formData.item_id_model='';
            this.formData.item_id=0;
            this.formData.serial_from='';
            this.formData.serial_to='';
            this.formData.serial_code_list=[];
        }
      }
    })

</script>   
@endsection

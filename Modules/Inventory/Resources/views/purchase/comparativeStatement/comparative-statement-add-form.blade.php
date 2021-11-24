@extends('layouts.master')
@section('content')
<div id="app">
    <div class="content-wrapper">
        <div v-if="!pageLoader">
            <section class="content-header">
                <h1>
                    <i class="fa fa-plus-square"></i> Add New Comparative Statement
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li><a href="#">SOP Setup</a></li>
                    <li><a href="#">Purchase</a></li>
                    <li class="active">Comparative Statement</li>
                </ul>
            </section>

            <section class="content">                
                <form  class="form-horizontal" @submit.prevent="submitListForm(formData, {},comparativeStatementFormData)">
                <div class="box box-solid">
                    <div class="box-body">
                        <div class="row scroll-table-300">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label required">Voucher No <span class="text-danger">*</span></label>
                                    <div class="col-md-8 p-b-15">
                                        <input type="text" name="voucher_no" v-model="formData.voucher_no"  class="form-control" required placeholder="CS-001" data-vv-as="voucher name" v-validate="'required'" :readonly="formData.auto_voucher">
                                        <span class="error" v-if="$validator.errors.has('voucher_no')">@{{$validator.errors.first('voucher_no')}}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label required">Date <span class="text-danger">*</span></label>
                                    <div class="col-md-8 p-b-15" v-if="formData.itemAdded=='no'">
                                        <v-date-picker name="date" data-vv-as="Date" v-validate="'required'" v-model="formData.date" :config="dateOptions" style="width: 100%;" placeholder="dd/mm/yyyy"></v-date-picker>
                                        <span class="error" v-if="$validator.errors.has('date')">@{{$validator.errors.first('date')}}</span>
                                    </div>
                                    <div class="col-md-8 p-b-15" v-else>
                                        <input type="text" name="date" class="form-control" v-model="formData.date" readonly>
                                        <span class="error" v-if="$validator.errors.has('date')">@{{$validator.errors.first('date')}}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label required">Instruction of <span class="text-danger">*</span></label>
                                    <div class="col-md-8 p-b-15" v-if="formData.itemAdded=='no'">
                                        <multiselect :select-label="''" :deselect-label="''" v-if="modalData.instruction_user_list" name="instruction_of" v-model="formData.instruction_of_model" :options="modalData.instruction_user_list"  placeholder="Select user" label="name" track-by="id" @input="selectInstructionOf" data-vv-as="Req By" v-validate="'required'"></multiselect>
                                       
                                        <span class="error" v-if="$validator.errors.has('instruction_of')">@{{$validator.errors.first('instruction_of')}}</span>                                               
                                    </div>
                                    <div class="col-md-8 p-b-15" v-else>
                                        <input type="text" name="instruction_of" v-model="formData.instruction_name" class="form-control" readonly>
                                        <span class="error" v-if="$validator.errors.has('instruction_of')">@{{$validator.errors.first('instruction_of')}}</span>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label required">Due Date <span class="text-danger">*</span></label>
                                    <div class="col-md-8 p-b-15" v-if="formData.itemAdded=='no'">
                                        <v-date-picker name="due_date" data-vv-as="Due Date" v-validate="'required'" v-model="formData.due_date" :config="dateOptions" style="width: 100%;" placeholder="dd/mm/yyyy"></v-date-picker>
                                        <span class="error" v-if="$validator.errors.has('due_date')">@{{$validator.errors.first('due_date')}}</span>
                                    </div>
                                    <div class="col-md-8 p-b-15" v-else>
                                        <input type="text" name="due_date" class="form-control" v-model="formData.due_date" readonly>
                                        <span class="error" v-if="$validator.errors.has('due_date')">@{{$validator.errors.first('due_date')}}</span>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label required">Campus <span class="text-danger">*</span></label>
                                    <div class="col-md-8 p-b-15" v-if="formData.itemAdded=='no'">
                                        <multiselect :select-label="''" :deselect-label="''" v-if="modalData.campus_list" name="campus_id" v-model="formData.campus_id_model" :options="modalData.campus_list"  placeholder="Select Campus" label="name" track-by="id" @input="selectCampus" data-vv-as="Campus" v-validate="'required'"></multiselect>
                                        <span class="error" v-if="$validator.errors.has('campus_id')">@{{$validator.errors.first('campus_id')}}</span>                  
                                    </div>
                                    <div class="col-md-8 p-b-15" v-else>
                                        <input type="text" name="campus_id" v-model="formData.campus_name" class="form-control" readonly>
                                        <span class="error" v-if="$validator.errors.has('campus_id')">@{{$validator.errors.first('campus_id')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                            
                                <div class="form-group">
                                    <label class="col-md-4 control-label required">Reference Type</label>
                                    <div class="col-md-8 p-b-15" v-if="formData.itemAdded=='no'">
                                        <select name="reference_type" id="reference_type" class="form-control" required v-model="formData.reference_type" @change="getRefData(formData.reference_type)" v-validate="'required'">
                                            <option value="">None</option>
                                            <option value="purchase-requisition">Purchase Requisition</option>
                                        </select>  
                                        <span class="error" v-if="$validator.errors.has('reference_type')">@{{$validator.errors.first('reference_type')}}</span>                                                 
                                    </div>
                                    <div class="col-md-8 p-b-15" v-else>
                                        <input type="text" name="reference_type" v-model="formData.reference_type" class="form-control" readonly>
                                        <span class="error" v-if="$validator.errors.has('reference_type')">@{{$validator.errors.first('reference_type')}}</span>                                                
                                    </div>
                                </div>
                                <div v-if="formData.reference_type=='purchase-requisition'">
                                    <div class="panel-body table-responsive">
                                        <table class="responsive table table-striped table-bordered" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Item Name</th>
                                                    <th>Ref</th>
                                                    <th>App. Qty</th>
                                                    <th>Avail. Qty</th>
                                                    <th>Due Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template v-if="Object.keys(modalData.refItemList).length > 0">
                                                    <tr  v-for="(ref, index) in modalData.refItemList" v-bind:key="index">
                                                        <td valign="middle"><input type="checkbox"true-value="1" false-value="0" v-model="ref.ref_check" @change="refCheck(ref.ref_check, ref)"></td>
                                                        <td valign="middle">@{{ref.product_name}}</td>
                                                        <td valign="middle">@{{ref.ref_voucher_name}}</td>          
                                                        <td valign="middle" class="text-right">@{{ref.req_qty}}</td>              
                                                        <td valign="middle" class="text-right">@{{ref.avail_qty}}</td>              
                                                        <td valign="middle">@{{ref.due_date}}</td>              
                                                    </tr>
                                                </template>
                                                <template v-else>
                                                    <tr>
                                                        <td colspan="6" class="text-center">No reference found</td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6"> 
                                                <label>
                                                    <input type="checkbox"true-value="1" false-value="0" v-model="formData.check_mandatory"> All Items are Mandatory
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-success" @click="GenerateCS($event)"><i class="icon-spinner icon-spin icon-large"></i>Generate CS</button>        
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="table-responsive" v-if="formData.generateCS=='yes'">
                            <template v-if="vendor_found=='yes'">
                                <table class="responsive table table-striped table-bordered" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th colspan="5"></th>
                                            <template v-for="(vendor, vi) in formData.vendorData">
                                                <th colspan="6" v-bind:key="vi">
                                                    <span style="float:left;">Vendor Name: @{{vendor.name}}</span>
                                                     <span style="float:right;">Gl Code: @{{vendor.gl_code}}</span>
                                                </th>
                                            </template>
                                        </tr>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Item SKU</th>
                                            <th>Qty</th>
                                            <th>UOM</th>
                                            <th>Remarks</th>

                                            <template v-for="(vendor, vi) in formData.vendorData" v-bind:key="vi">
                                                <th>Rate</th>
                                                <th>Amt.</th>
                                                <th>Discount</th>
                                                <th>VAT</th>
                                                <th>VAT Type</th>
                                                <th>Net Amt.</th>
                                            </template>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="formData.itemAdded=='yes'">
                                            <tr v-for="(data, index) in formData.voucherDetailsData" v-bind:key="index">
                                                <td valign="middle">@{{data.product_name}}</td>
                                                <td valign="middle">@{{data.sku}}</td>          
                                                <td class="text-right" valign="middle">@{{parseFloat(data.avail_qty).toFixed(data.decimal_point_place)}}</td> 
                                                <td valign="middle">@{{data.uom}}</td>
                                                <td valign="middle">@{{data.remarks}}</td>   

                                                <template v-for="(vendor, vi) in formData.vendorData" v-bind:key="vi">
                                                    <td valign="middle">@{{formData.price_catalog_component_data['rate_'+data.reference_details_id+'_'+data.item_id+'_'+vendor.id]}}</td>
                                                    <td valign="middle">@{{formData.price_catalog_component_data['amount_'+data.reference_details_id+'_'+data.item_id+'_'+vendor.id]}}</td>
                                                    <td valign="middle">@{{formData.price_catalog_component_data['discount_'+data.reference_details_id+'_'+data.item_id+'_'+vendor.id]}}</td>
                                                    <td valign="middle">@{{formData.price_catalog_component_data['vat_per_'+data.reference_details_id+'_'+data.item_id+'_'+vendor.id]}}</td>
                                                    <td valign="middle">@{{formData.price_catalog_component_data['vat_type_'+data.reference_details_id+'_'+data.item_id+'_'+vendor.id]}}</td>
                                                    <td valign="middle">@{{formData.price_catalog_component_data['net_amount_'+data.reference_details_id+'_'+data.item_id+'_'+vendor.id]}}</td>
                                                </template>
                                            </tr>
                                        </template>
                                        <template v-else>
                                            <tr>
                                                <td colspan="11" align="center">Nothing here</td>
                                            </tr>
                                        </template>
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td rowspan="6" colspan="5"></td>
                                            <template v-for="(vendor, vi) in formData.vendorData" v-bind:key="vi">
                                                <td colspan="6"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <template v-for="(vendor, vi) in formData.vendorData" v-bind:key="vi">
                                                <td colspan="6">
                                                    <span class="text-left" style="float: left;">Credit Limit: @{{vendor.credit_limit}}</span> 
                                                    <span class="text-left" style="float: right;">Credit Period: @{{vendor.credit_priod}}</span> 
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <template v-for="(vendor, vi) in formData.vendorData" v-bind:key="vi">
                                                <td colspan="6">
                                                    Terms & Coditions:
                                                    <ul>
                                                        <li v-for="(terms_condition, ti) in vendor.terms_condition" v-bind:key="ti">@{{terms_condition.term_condition}}</li>
                                                    </ul>
                                                </td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <template v-for="(vendor, vi) in formData.vendorData" v-bind:key="vi">
                                                <td colspan="6"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <template v-for="(vendor, vi) in formData.vendorData" v-bind:key="vi">
                                                <td colspan="6"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <template v-for="(vendor, vi) in formData.vendorData" v-bind:key="vi">
                                                <td colspan="6"> <input type="radio" :id="'vendor_id'+vendor.id" name="gender" :value="vendor.id" v-model="formData.vendor_id"> <label :for="'vendor_id'+vendor.id">Proceed with this vendor</label></td>
                                            </template>
                                        </tr>
                                    </tfoot>
                                </table>
                            </template>
                            <template v-else>
                                <table class="responsive table table-striped table-bordered">
                                    <tr><td class="text-center text-danger">Sorry! no vendor found</td></tr>
                                </table>
                                
                            </template>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Comments</label>
                                    <div class="col-md-9 p-b-15">
                                        <textarea name="comments" v-model="formData.comments" placeholder="Comments" class="form-control" maxlength="255"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" :disabled="buttonDisabled">Create</button> <a class="btn btn-default" href="{{url('inventory/comparative-statement')}}">Cancel</a>
                    </div>
                </div>
                </form>
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
    window.dataUrl = 'comparative-statement-data';
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

<script src="{{URL::asset('vuejs/mixin.js') }}"></script>
<script src="{{URL::asset('vuejs/moment.min.js') }}"></script>
<link href="{{ URL::asset('vuejs/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
<script src="{{URL::asset('vuejs/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{URL::asset('vuejs/vue-bootstrap-datetimepicker.min.js') }}"></script>
<script>
     axios.defaults.headers.common['X-CSRF-TOKEN'] = token; 
    Vue.use(VeeValidate);
    Vue.mixin(mixin);
    Vue.component('multiselect', window.VueMultiselect.default)
    Vue.component('v-date-picker', VueBootstrapDatetimePicker)
    var app = new Vue({
      el: '#app',
      data: {
        formData:{
            date:null,
            due_date:null,
            voucherDetailsData:[],
            vendorData:[],
            price_catalog_component_data:[],
            itemAdded:'no'
        },
        vendorData:[],
        price_catalog_component_data:[],
        vendor_found:'yes'
      },
      created(){
        this.formPageData('comparative-statement-create-form-data');
      },
      methods:{
        GenerateCS(event){
            event.preventDefault();
            const _this = this;
            if(_this.refDataList.length>0){
                _this.pageLoader = true;
                let URL = _this.baseUrl + '/generate-cs';
                var dataParams = {
                    check_mandatory: _this.formData.check_mandatory,
                    campus_id: _this.formData.campus_id,
                    date: _this.formData.date,
                    refDataList: _this.refDataList
                };
                axios.post(URL, dataParams).then(res => {
                    if (res.data.status == 'logout') {
                        window.location.href = res.data.url;
                    } else {
                        _this.pageLoader = false;
                        if(res.data.status == 0){
                            _this.showToster({
                                status: 0,
                                message: res.data.message
                            });
                        }else{
                            _this.formData.voucherDetailsData = res.data.voucherDetailsData;
                            _this.formData.itemAdded=res.data.vendor_found;
                            _this.formData.generateCS='yes';
                            _this.formData.vendorData = res.data.vendorData;
                            _this.formData.price_catalog_component_data = res.data.price_catalog_component_data;
                            _this.vendor_found = res.data.vendor_found;
                            _this.refDataList = [];
                            // uncheck all reference 
                            _this.modalData.refItemList.forEach(function(data, key){
                                data.ref_check = 0;
                            });
                        }
                    }
                })
                .catch(error => {
                    _this.pageLoader = false;
                    _this.showToster({
                        status: 0,
                        message: 'opps! something went wrong'
                    });
                });
            }else{
                alert('No reference are selected');
            }

        },
        comparativeStatementFormData() {
            const _this = this;
            var URL = _this.baseUrl + '/comparative-statement-create-form-data';
            axios.get(URL).then(res => {
                if (res.data.status == 'logout') {
                    window.location.href = res.data.url;
                } else {
                    if (typeof res.data.formData !== 'undefined') {
                        _this.formData = res.data.formData;
                    }
                    _this.modalData = res.data;
                }
            })
            .catch(error => {
                _this.showToster({
                    status: 0,
                    message: 'opps! something went wrong'
                });
                
            })

        },
        getRefData(reference_type){
            const _this=this;
            _this.refDataList = [];
            _this.$set(_this.modalData, 'refItemList', []);
            if(reference_type=='purchase-requisition'){
                if(_this.formData.date && _this.formData.campus_id){
                    _this.pageLoader = true;
                    let URL = _this.baseUrl + '/cs-reference-list';
                    var dataParams = {
                        date: _this.formData.date,
                        campus_id: _this.formData.campus_id
                    };
                    axios.get(URL, {
                        params: dataParams
                    }).then(res => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            _this.pageLoader = false;
                            if(res.data.status == 0){
                                _this.showToster({
                                    status: 0,
                                    message: res.data.message
                                });
                            }else{
                                _this.$set(_this.modalData, 'refItemList', res.data);
                            }
                        }
                    })
                    .catch(error => {
                        _this.pageLoader = false;
                        _this.showToster({
                            status: 0,
                            message: 'opps! something went wrong'
                        });
                    });

                }else{
                    _this.formData.reference_type='';
                    if(!_this.formData.date){
                        alert('Please select a date');
                        return false;
                    }
                    if(!_this.formData.campus_id){
                        alert('Please select a campus');
                        return false;
                    }
                }
            }

        },
        selectCampus(campus){
            if(campus){
                this.formData.campus_id = campus.id;
                this.$set(this.formData, 'campus_name', campus.name);
            } else { 
                this.formData.campus_id = 0; 
                this.$set(this.formData, 'campus_name', '');
            }
        },
        selectInstructionOf(user){
            if(user){
                this.formData.instruction_of = user.id;
                this.$set(this.formData, 'instruction_name', user.name);
            }else{
                this.formData.instruction_of = 0; 
                this.$set(this.formData, 'instruction_name', '');
            }
        },
        selectGridItem(item){
            if(item){
                this.$set(this.gridData, 'item_id', item.item_id);
                this.$set(this.gridData, 'uom', item.uom);
                this.$set(this.gridData, 'product_name', item.product_name);
                this.$set(this.gridData, 'decimal_point_place', item.decimal_point_place);
                this.$set(this.gridData, 'round_of', item.round_of);
                this.$set(this.gridData, 'has_fraction', item.has_fraction);
            } else{
                this.$set(this.gridData, 'item_id', 0);
                this.$set(this.gridData, 'uom', '');
                this.$set(this.gridData, 'product_name', '');
                this.$set(this.gridData, 'decimal_point_place', 0);
                this.$set(this.gridData, 'round_of', 0);
                this.$set(this.gridData, 'has_fraction', 0);
            }
           
        }
                

      }
    })

</script>   
@endsection


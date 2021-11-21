@extends('layouts.master')
@section('content')
<div id="app">
    <div class="content-wrapper">
        <div v-if="!pageLoader">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Manage  |<small>Stock In</small>
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li><a href="#">Purchase</a></li>
                    <li class="active">Purchase Requisition</li>
                </ul>
            </section>

            <section class="content">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Purchase Requisition List</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" @click="openModal('addForm', 'purchase-requisition-data/create',true)"><i class="fa fa-plus-square"></i> New</a></div>
                    </div>
                    <div class="box-body">
                        <form action="" class="form-inline">
                            <div class="row" style="margin-bottom: 10px">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Per page</label>
                                        <select name="listPerPage" class="form-control" v-model="listPerPage" @change="getResults(1)">
                                            <option v-for="size in pageSize" :value="size">@{{size}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="dataList.voucher_list" name="voucher_id_model" v-model="filter.voucher_id_model" :options="dataList.voucher_list"  placeholder="Select voucher" label="voucher_no" track-by="id" @input="selectVoucher" :options-limit="10000"></multiselect>
                                </div>
                                <div class="col-sm-3">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="dataList.item_list" name="item_id_model" v-model="filter.item_id_model" :options="dataList.item_list"  placeholder="Select item" label="product_name" track-by="item_id" @input="selectFilterItem" :options-limit="10000"></multiselect>
                                </div>
                                <div class="col-sm-3">
                                    <select name="status" class="form-control" v-model="filter.status" style="width:100%">
                                        <option  value="">Select Status</option>
                                        <option  value="p">Pending</option>
                                        <option  value="1">Approved</option>
                                        <option  value="2">Partial Approved</option>
                                        <option  value="3">Reject</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 30px">
                                <div class="col-sm-3">
                                    <v-date-picker v-model="filter.from_date" :config="dateOptions" style="width: 100%;" placeholder="From date"></v-date-picker>
                                </div>  
                                <div class="col-sm-3">
                                    <v-date-picker v-model="filter.to_date" :config="dateOptions" style="width: 100%;" placeholder="To date"></v-date-picker>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="search_key" placeholder="Search by keyword" class="form-control" v-model="filter.search_key" style="width: 100%;" autocomplete="off">
                                </div>  
                                <div class="col-sm-1">
                                    <button class="btn btn-primary" @click="getResults(1)"><i class="fa fa-search"></i> Search</button>
                                </div>
                                <div class="col-sm-1" style="padding-left: 0">
                                    <button type="button" class="btn btn-secondary"><i class="fa fa-print"></i> Print <i class="fa fa-caret-down"></i></button>
                                </div>
                            </div>
                        </form>
                        
                        <div class="table-responsive" style="max-height: 500px">
                            <table class="table table-striped table-bordered m-b-0">
                                <thead>
                                    <tr>
                                        <th width="6%">Select</th>
                                        <th class="sortable" v-bind:class="getSortingClass('voucher_no')" @click="sortingChanged('voucher_no')">Voucher #</th>
                                        <th class="sortable" v-bind:class="getSortingClass('item_id')" @click="sortingChanged('item_id')">Item Name</th>
                                        <th class="sortable" v-bind:class="getSortingClass('date')" @click="sortingChanged('date')">Date</th>
                                        <th class="sortable" v-bind:class="getSortingClass('inventory_purchase_requisition_details.status')" @click="sortingChanged('inventory_purchase_requisition_details.status')">Status</th>
                                        <th class="sortable" v-bind:class="getSortingClass('need_cs')" @click="sortingChanged('need_cs')">Need CS</th>
                                        <th>Remarks</th>
                                        <th>Approved By</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="Object.keys(paginate_data).length > 0">
                                        <tr v-for="(list, index) in paginate_data" v-bind:key="index">
                                            <td><input type="checkbox" true-value="1" false-value="0" v-model="list.del_id" @change="deleteCheck(list.del_id, list.id)"></td>
                                            <td>@{{list.voucher_no}}</td>
                                            <td>@{{list.product_name}}</td>
                                            <td>@{{list.stock_in_date}}</td>
                                            <td>
                                                <span v-if="list.status==0">Pending</span>
                                                <span v-if="list.status==1">Approved</span>
                                                <span v-if="list.status==2">Partial Approved</span>
                                                <span v-if="list.status==3">Reject</span>
                                            </td>
                                            <td>
                                                <span v-if="list.need_cs==0">No</span>
                                                <span v-if="list.need_cs==1">Yes</span>
                                            </td>
                                            <td>@{{list.remarks}}</td>
                                            <td>@{{list.approved_text}}</td>
                                            <td>
                                                <a v-if="list.has_approval=='yes'" class="btn btn-primary btn-xs" @click="voucherApproval('purchase-requisition-approval',list.id)"
                                                ><i class="fa fa-check-square" aria-hidden="true"></i> Approved</a>
                                                <a v-if="list.status==0" class="btn btn-primary btn-xs" @click="openModal('addForm', 'purchase-requisition-data/'+list.req_id+'/edit')" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a v-if="list.status==0"  @click="deleteItem(list.id)"
                                                    class="btn btn-danger btn-xs" data-placement="top"
                                                    data-content="delete" title="delete"><i class="fa fa-trash-o"></i></a>
                                                <a class="btn btn-primary btn-xs" @click="openModal('detailsForm', 'purchase-requisition-data/'+list.req_id)" 
                                                   ><i class="fa fa-info-circle" aria-hidden="true"></i> Details</a>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                  <tr>
                                    <td colspan="9" class="text-center">No Record found!</td>
                                  </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 p-t-15">
                                <button @click="deleteArrayItem()" class="btn btn-danger">Delete</button>
                            </div>
                            <div class="col-sm-10 pull-right">
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

    <div class="modal" id="addForm" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="form-horizontal" @submit.prevent="submitForm(formData,{},initNewVoucherData)">
            <div class="modal-content" v-if="!pageLoader">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                            aria-hidden="true">×</span></button>
                    <h4 class="modal-title">
                        <span v-if="formData.id">Edit Purchase Requisition</span>
                        <span v-else>Add Purchase Requisition</span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger alert-auto-hide" v-if="errorsList.length>0">
                        <ul>
                            <li v-for="(error, i) in errorsList" v-bind:key="i">@{{error}}</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Voucher No <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <input type="text" name="voucher_no" v-model="formData.voucher_no"  class="form-control" required placeholder="PURREQ-001" data-vv-as="voucher name" v-validate="'required'" :readonly="formData.auto_voucher">
                                    <span class="error" v-if="$validator.errors.has('voucher_no')">@{{$validator.errors.first('voucher_no')}}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Campus <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.campus_list" name="campus_id" v-model="formData.campus_id_model" :options="modalData.campus_list"  placeholder="Select Campus" label="name" track-by="id" @input="selectCampus" data-vv-as="Campus" v-validate="'required'"></multiselect>
                                   
                                    <span class="error" v-if="$validator.errors.has('campus_id')">@{{$validator.errors.first('campus_id')}}</span>                                               
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Requisition By <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.requisition_user_list" name="requisition_by_model" v-model="formData.requisition_by_model" :options="modalData.requisition_user_list"  placeholder="Select user" label="name" track-by="id" @input="selectRequisitionBy" data-vv-as="Req By" v-validate="'required'"></multiselect>
                                   
                                    <span class="error" v-if="$validator.errors.has('requisition_by_model')">@{{$validator.errors.first('requisition_by_model')}}</span>                                               
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Req Date <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <v-date-picker name="date" data-vv-as="Req Date" v-validate="'required'" v-model="formData.date" :config="dateOptions" style="width: 100%;" placeholder="dd/mm/yyyy"></v-date-picker>
                                    <span class="error" v-if="$validator.errors.has('date')">@{{$validator.errors.first('date')}}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Due Date <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <v-date-picker name="due_date" data-vv-as="Due Date" v-validate="'required'" v-model="formData.due_date" :config="dateOptions" style="width: 100%;" placeholder="dd/mm/yyyy"></v-date-picker>
                                    <span class="error" v-if="$validator.errors.has('due_date')">@{{$validator.errors.first('due_date')}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Need CS? <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <input type="radio" id="yes" value="1"  v-model="formData.need_cs">
                                    <label for="yes">Yes</label>
                                    <input type="radio" id="no" value="0" v-model="formData.need_cs">
                                    <label for="no">No</label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <p style="margin-top: 6px;margin-bottom: 0"><b>Choose Purchase Requisition Item:</b></p> 
                    
                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="40%">Item Name</th>
                                <th>UOM</th>
                                <th>Quantity</th>
                                <th>Remarks</th>
                                <th class="text-center" width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center table-multiselect" valign="middle" width="30%">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="dataList.item_list" name="item_id_model" v-model="gridData.item_id_model" :options="dataList.item_list"  placeholder="Select item" label="product_name" track-by="item_id" @input="selectGridItem" :options-limit="10000"></multiselect>
                                </td>
                                <td>@{{gridData.uom}}</td>
                                <td class="text-center" valign="middle">
                                    <input type="number" name="req_qty" v-model="gridData.req_qty" class="form-control" autocomplete="off" placeholder="Quantity" min="0">  
                                </td>
                                <td class="text-center" valign="middle">
                                    <input type="text" name="remarks" v-model="gridData.remarks" class="form-control" autocomplete="off" placeholder="Remarks" maxlength="255">  
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-info table-input-redious" @click="itemGridAdd($event)">ADD</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="margin-top: 6px;margin-bottom: 0"><b> Purchase Requisition Item List: </b></p>

                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th>Item Name</th>
                                <th>UOM</th>
                                <th>Qty</th>
                                <th>Remarks</th>
                                <th class="text-center" width="16%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="formData.itemAdded=='yes'">
                                <tr v-for="(data, index) in formData.voucherDetailsData" v-bind:key="index">
                                    <td valign="middle">@{{index+1}}</td>
                                    <td valign="middle">@{{data.product_name}}</td>          
                                    <td valign="middle">@{{data.uom}}</td>              
                                    <td class="text-right" valign="middle">@{{parseFloat(data.req_qty).toFixed(data.decimal_point_place)}}</td> 
                                    <td valign="middle">@{{data.remarks}}</td> 
                                    <td class="text-center" valign="middle">
                                        <button class="btn-info btn-xs" title="Edit" @click="itemGridEdit($event, data)"><i class="fa fa-pencil-square-o"></i></button>
                                        <button class="btn-xs btn-danger" title="Delete" @click="itemGridRemove($event, data)"><i class="fa fa-trash"></i></button>
                                    </td>              
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="6" align="center">Nothing here</td>
                                </tr>
                            </template>
                            
                        </tbody>
                        <!-- <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><b>Total</b></td>
                                <td class="text-right">@{{formData.totalQty}}</td>
                                <td></td>
                            </tr>
                        </tfoot> -->
                    </table>

                    <div class="form-group">
                        <label class="col-md-3 control-label required">Comments</label>
                        <div class="col-md-9 p-b-15">
                            <textarea name="comments" v-model="formData.comments" placeholder="Comments" class="form-control" maxlength="255"></textarea>
                           
                        </div>
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

    <div class="modal" id="detailsForm" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                            aria-hidden="true">×</span></button></h4>
                    <h4 class="modal-title">Purchase Requisition  Details
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Voucher No</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.voucher_no}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Campus</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.campus_name}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Req By</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.name}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Req Date</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.req_date}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Due Date</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.due_date_formate}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Need CS?</label>
                                <div class="col-md-8 p-b-15">
                                    @{{(formData.need_cs==1)?'Yes':'No'}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow">
                        <table class="responsive table table-striped table-bordered" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Item Name</th>
                                    <th>UOM</th>
                                    <th>Req. Qty</th>
                                    <th>App. Qty</th>
                                    <th>Remarks</th>
                                    <th width="16%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list, index) in formData.voucherDetailsData" v-bind:key="index">
                                    <td valign="middle">@{{index+1}}</td>
                                    <td valign="middle">@{{list.product_name}}</td>     
                                    <td valign="middle">@{{list.uom}}</td>                   
                                    <td class="text-right" valign="middle">@{{parseFloat(list.req_qty).toFixed(list.decimal_point_place)}}</td>
                                    <td class="text-right" valign="middle">@{{parseFloat((list.app_qty)?list.app_qty:0).toFixed(list.decimal_point_place)}}</td> 
                                    <td valign="middle">@{{list.remarks}}</td> 
                                    <td valign="middle">
                                        <span v-if="list.status==0">Pending</span>
                                        <span v-if="list.status==1">Approved</span>
                                        <span v-if="list.status==2">Partial Approved</span>
                                        <span v-if="list.status==3">Reject</span>
                                    </td>              
                                </tr>
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><b>Total</b></td>
                                    <td class="text-right">@{{parseFloat(formData.totalReqQty).toFixed(2)}}</td>
                                    <td class="text-right">@{{parseFloat(formData.totalAppQty).toFixed(2)}}</td>
                                    <td class="text-right"></td>
                                </tr>
                            </tfoot> -->
                        </table>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label required">Comments:</label>
                        <div class="col-md-9 p-b-15">
                            @{{formData.comments}}
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection



@section('scripts')
<script type="text/javascript">
    window.dataUrl = 'purchase-requisition-data';
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
    Vue.component('multiselect', window.VueMultiselect.default)
    Vue.component('v-date-picker', VueBootstrapDatetimePicker)
    var app = new Vue({
      el: '#app',
      data: {
        filter:{
            item_id_model:'',
            item_id:0,
            voucher_id_model:'',
            voucher_id:0,
            from_date:null,
            to_date:null,
            status:''
        },
        formData:{
            date:null,
            due_date:null,
            voucherDetailsData:[],
            itemAdded:'no'
        }
      },
      created(){
        this.getResults(1);
      },
      methods:{
        selectFilterItem(item){
            if(item) this.filter.item_id = item.item_id;
            else this.filter.item_id = 0; 
        },
        selectVoucher(voucher){
            if(voucher) this.filter.voucher_id = voucher.id;
            else this.filter.voucher_id = 0; 
        },
        selectCampus(campus){
            if(campus) this.formData.campus_id = campus.id;
            else this.formData.campus_id = 0; 
        },
        selectRequisitionBy(user){
            if(user) this.formData.requisition_by = user.id;
            else this.formData.requisition_by = 0; 
        },
        selectGridItem(item){
            if(item && item.item_id>0){
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
           
        },
        itemGridAdd(event){
            event.preventDefault();
            if(this.gridData.item_id && parseFloat(this.gridData.req_qty)>0 && this.gridData.remarks){
                var req_qty = parseFloat(this.gridData.req_qty);
                var checkExists =  this.gridDataExist(this.formData.voucherDetailsData,this.gridData);
                if(checkExists.length>0){ alert("You have already added the Item"); return false; }
                // check if item has fraction value
                if(Number(this.gridData.has_fraction)==0){
                    if(this.isFloat(this.gridData.req_qty)){
                        alert('You can not add fraction qty for this item');
                        return false;
                    }
                }
                this.formData.voucherDetailsData.push(this.gridData);
                this.gridData = {};
                this.formData.itemAdded='yes';
                this.totalQtyCalculate();
            }else{
                if(!this.gridData.item_id){
                    alert('Please select item');
                }else if(!this.gridData.req_qty){
                    alert('Please enter qty');
                }else if(this.gridData.req_qty<=0){
                    alert('Please enter qty greater than zero');
                }else{
                    alert('Please enter remarks');
                }
            }
        },
        itemGridRemove(event, data){
            event.preventDefault();
            let index = this.formData.voucherDetailsData.indexOf(data);
            this.formData.voucherDetailsData.splice(index, 1);
            this.totalQtyCalculate();
            if(this.formData.voucherDetailsData.length<1){
                this.formData.itemAdded='no';
            }
        },
        itemGridEdit(event, data){
            event.preventDefault();
            this.gridData = data;
            this.gridData.qty = parseFloat(data.qty).toFixed(data.decimal_point_place);
            let index = this.formData.voucherDetailsData.indexOf(data);
            this.formData.voucherDetailsData.splice(index, 1);
            this.totalQtyCalculate();
            if(this.formData.voucherDetailsData.length<1){
                this.formData.itemAdded='no';
            }
        },
        gridDataExist(list,input){
            return list.filter(function(row){
                return +row.item_id == +input.item_id; 
            });
        },
        totalQtyCalculate(){
            var totalQty = 0;
            this.formData.voucherDetailsData.forEach(function(item, index){
                totalQty+= Number(item.req_qty);
            
            });
            this.$set(this.formData, 'totalQty', totalQty.toFixed(2));
        },
        initNewVoucherData(){
            const _this = this;
            let URL = _this.baseUrl + '/purchase-requisition-data/create';
            axios.get(URL).then(res => {
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
                        if (typeof res.data.formData !== 'undefined') {
                            _this.formData = res.data.formData;
                        }
                        _this.modalData = res.data;
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

            
        },
        submitApproval(formData = this.formData) {
            const _this = this;
            const URL = _this.baseUrl + '/new-requisition/apporved-action';
            _this.pageLoader = true;
            _this.buttonDisabled = true;
            axios.post(URL, formData).then((res) => {
                if (res.data.status == 'logout') {
                    window.location.href = res.data.url;
                } else {
                    if (res.data.status == 1) {
                        _this.getResults(_this.currentPageNo);
                        $('#approvedForm').modal('hide');
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
               
        },
      }
    })

</script>   
@endsection

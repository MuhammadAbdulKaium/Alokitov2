@extends('layouts.master')
@section('content')
<div id="app">
    <div class="content-wrapper">
        <div v-if="!pageLoader">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Manage  |<small>Price Catalogue</small>
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li class="active">Price Catalogue</li>
                </ul>
            </section>

            <section class="content">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Price CatalogueList</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" @click="openModal('addForm', 'price-catalogue-data/create',true, gridDataInit)"><i class="fa fa-plus-square"></i> New</a></div>
                    </div>
                    <div class="box-body">
                        <form action="" class="form-inline">
                            <div class="row" style="margin-bottom: 10px">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Per page</label>
                                        <select name="listPerPage" class="form-control" v-model="listPerPage" @change="getResults(1)">
                                            <option v-for="size in pageSize" :value="size">@{{size}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                     <input type="text" name="search_key" v-model="filter.search_key" class="form-control" placeholder="Search by keyword" style="width:100%">
                                </div>
                                <div class="col-sm-2">
                                    <vuejs-datepicker name="from_date_show" v-model="filter.from_date_show" placeholder="From Date" :format="filterFromDateFormatter" :clear-button="true"></vuejs-datepicker>
                                </div>
                                <div class="col-sm-2">
                                    <vuejs-datepicker name="to_date_show" v-model="filter.to_date_show" placeholder="To Date" :format="filterToDateFormatter" :clear-button="true"></vuejs-datepicker>
                                </div>

                                <div class="col-sm-1">
                                    <button class="btn btn-primary" @click="getResults(1)"><i class="fa fa-search"></i> Search</button>
                                </div>
                                <div class="col-sm-1" style="padding-left: 0">
                                    <button class="btn btn-secondary"><i class="fa fa-print"></i> Print <i class="fa fa-caret-down"></i></button>
                                </div>

                            </div>
                        </form>
                        
                        <div class="table-responsive" style="max-height: 500px">
                            <table class="table table-striped table-bordered m-b-0">
                                <thead>
                                    <tr>
                                        <th width="6%">#</th>
                                        <th class="sortable" v-bind:class="getSortingClass('applicable_from')" @click="sortingChanged('applicable_from')">Effective Date</th>
                                        <th class="sortable" v-bind:class="getSortingClass('price_label')" @click="sortingChanged('price_label')">Lable name</th>
                                        <th>Remarks</th>
                                        <th class="sortable" v-bind:class="getSortingClass('status')" @click="sortingChanged('status')">Status</th>
                                        <th>Approved By</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="Object.keys(paginate_data).length > 0">
                                        <tr v-for="(list, index) in paginate_data" v-bind:key="index">
                                            <td>@{{index+1}}</td>
                                            <td>@{{list.effective_date}}</td>
                                            <td>@{{list.price_label}}</td>
                                            <td>@{{list.comments}}</td>
                                            <td>
                                                <span v-if="list.status==0">Pending</span>
                                                <span v-if="list.status==1">Approved</span>
                                                <span v-if="list.status==2">Partial Approved</span>
                                                <span v-if="list.status==3">Reject</span>
                                            </td>
                                            <td>@{{list.approved_text}}</td>
                                            <td>
                                                <a v-if="list.has_approval=='yes'" class="btn btn-primary btn-xs" @click="voucherApproval('price-catalogue-approval',list.id)"
                                                ><i class="fa fa-check-square" aria-hidden="true"></i> Approved</a>
                                                <a v-if="list.status==0" class="btn btn-primary btn-xs" @click="openModal('addForm', 'price-catalogue-data/'+list.id+'/edit', true, gridDataInit)" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a v-if="list.status==0"  @click="deleteItem(list.id)"
                                                    class="btn btn-danger btn-xs" data-placement="top"
                                                    data-content="delete" title="delete"><i class="fa fa-trash-o"></i></a>
                                                <a class="btn btn-primary btn-xs" @click="openModal('detailsForm', 'price-catalogue-data/'+list.id)" 
                                                   ><i class="fa fa-info-circle" aria-hidden="true"></i> Details</a>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                  <tr>
                                    <td colspan="7" class="text-center">No Record found!</td>
                                  </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 p-t-15">
                                
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
        <div class="modal-dialog modal-lg ref-modal-width">
            <form class="form-horizontal" @submit.prevent="submitForm(formData,{},initNewVoucherData)">
            <div class="modal-content" v-if="!pageLoader">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                            aria-hidden="true">×</span></button>
                    <h4 class="modal-title">
                        <span v-if="formData.id">Edit Price Catelogue</span>
                        <span v-else>Add Price Catelogue</span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger alert-auto-hide" v-if="errorsList.length>0">
                        <ul>
                            <li v-for="(error, i) in errorsList" v-bind:key="i">@{{error}}</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Select label</label>
                                <div class="col-md-8 p-b-15">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.price_catalogue_list" name="catalogue_ref_id_model" v-model="formData.catalogue_ref_id_model" :options="modalData.price_catalogue_list"  placeholder="Select label" label="price_label" track-by="id" @input="selectLabelItem" :options-limit="10000"></multiselect>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Price label <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <input name="price_label" v-model="formData.price_label" type="text" class="form-control" placeholder="Price label name" data-vv-as="Price label name" v-validate="'required'" maxlength="255" :readonly="formData.price_label_readonly=='yes'">
                                    <span class="error" v-if="$validator.errors.has('price_label')">@{{$validator.errors.first('price_label')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Applicable from <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <div class="input-group date bs-date">
                                        <vuejs-datepicker name="applicable_from" data-vv-as="Date" v-validate="'required'" v-model="formData.applicable_from_show" placeholder="Date" :format="applicableFromDateFormatter"></vuejs-datepicker>
                                        <span class="error" v-if="$validator.errors.has('applicable_from')">@{{$validator.errors.first('applicable_from')}}</span>
                                    </div>                                                 
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <p style="margin-top: 6px;margin-bottom: 0"><b>Choose Price Catelogue Item:</b></p> 
                    
                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="30%">Item Name</th>
                                <th>From Qty</th>
                                <th>To  Qty</th>
                                <th>UOM</th>
                                <th>Rate</th>
                                <th>Discount</th>
                                <th>Vat</th>
                                <th width="10%">Vat type</th>
                                <th class="text-center" width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center table-multiselect" valign="middle" width="30%">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.item_list" name="item_id_model" v-model="gridData.item_id_model" :options="modalData.item_list"  placeholder="Select item" label="product_name" track-by="item_id" @input="selectGridItem" :options-limit="10000"></multiselect>
                                </td>
                                <td>
                                    <input type="number" name="from_qty" v-model="gridData.from_qty" class="form-control" autocomplete="off" placeholder="From qty" min="0"> 
                                </td>
                                <td>
                                    <input type="number" name="to_qty" v-model="gridData.to_qty" class="form-control" autocomplete="off" placeholder="To qty" min="0"> 
                                </td>
                                <td>@{{gridData.uom}}</td>
                                <td class="text-center" valign="middle">
                                    <input type="number" name="rate" v-model="gridData.rate" class="form-control" autocomplete="off" placeholder="Rate" min="0">  
                                </td>
                                <td class="text-center" valign="middle">
                                    <input type="number" name="discount" v-model="gridData.discount" class="form-control" autocomplete="off" placeholder="Discount" min="0">  
                                </td>
                                <td class="text-center" valign="middle">
                                    <input type="number" name="vat_per" v-model="gridData.vat_per" class="form-control" autocomplete="off" placeholder="Vat" min="0">  
                                </td>
                                <td class="text-center" valign="middle">
                                    <select name="vat_type" v-model="gridData.vat_type" class="form-control">
                                        <option value="fixed">Fixed</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-info table-input-redious" @click="itemGridAdd($event)">ADD</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="margin-top: 6px;margin-bottom: 0"><b> Price Catelogue Item List: </b></p>

                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Item Name</th>
                                <th>UOM</th>
                                <th>From Qty</th>
                                <th>To Qty</th>
                                <th>Rate</th>
                                <th>Discount</th>
                                <th>Vat</th>
                                <th class="text-center" width="16%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="formData.itemAdded=='yes'">
                                <tr v-for="(data, index) in formData.voucherDetailsData" v-bind:key="index">
                                    <td valign="middle">@{{index+1}}</td>
                                    <td valign="middle">@{{data.product_name}}.</td>          
                                    <td valign="middle">@{{data.uom}}</td>              
                                    <td class="text-right" valign="middle">@{{parseFloat(data.from_qty).toFixed(data.decimal_point_place)}}</td> 
                                    <td class="text-right" valign="middle">@{{parseFloat(data.to_qty).toFixed(data.decimal_point_place)}}</td> 
                                    <td class="text-right" valign="middle">@{{parseFloat(data.rate).toFixed(2)}}</td>   
                                    <td class="text-right" valign="middle">@{{parseFloat((data.discount)?data.discount:0).toFixed(2)}}</td>   
                                    <td class="text-right" valign="middle">@{{parseFloat((data.vat_per)?data.vat_per:0).toFixed(2)}}</td>                
                                    <td class="text-center" valign="middle">
                                        <button class="btn-info btn-xs" title="Edit" @click="itemGridEdit($event, data)"><i class="fa fa-pencil-square-o"></i></button>
                                        <button class="btn-xs btn-danger" title="Delete" @click="itemGridRemove($event, data)"><i class="fa fa-trash"></i></button>
                                    </td>              
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="9" align="center">Nothing here</td>
                                </tr>
                            </template>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><b>Total</b></td>
                                <td class="text-right">@{{formData.totalFromQty}}</td>
                                <td class="text-right">@{{formData.totalToQty}}</td>
                                <td class="text-right">@{{formData.totalRate}}</td>
                                <td class="text-right">@{{formData.totalDiscount}}</td>
                                <td class="text-right">@{{formData.totalVat}}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    

                    <div class="form-group">
                        <label class="col-md-3 control-label required">Comments <span class="text-danger">*</span></label>
                        <div class="col-md-9 p-b-15">
                            <textarea name="comments" v-model="formData.comments" placeholder="Comments" class="form-control" data-vv-as="Comments" v-validate="'required'"></textarea>
                            <span class="error" v-if="$validator.errors.has('comments')">@{{$validator.errors.first('comments')}}</span>
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
                    <h4 class="modal-title">Price Catalogue Details
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Price label</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.price_label}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Applicable from</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.applicable_from_formate}}                                        
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
                                    <th>From Qty</th>
                                    <th>To Qty</th>
                                    <th>Rate</th>
                                    <th>Discount</th>
                                    <th>Vat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list, index) in formData.voucherDetailsData" v-bind:key="index">
                                    <td valign="middle">@{{index+1}}</td>
                                    <td valign="middle">@{{list.product_name}}</td>     
                                    <td valign="middle">@{{list.uom}}</td>                   
                                    <td class="text-right" valign="middle">@{{parseFloat(list.from_qty).toFixed(list.decimal_point_place)}}</td> 
                                    <td class="text-right" valign="middle">@{{parseFloat(list.to_qty).toFixed(list.decimal_point_place)}}</td>              
                                    <td class="text-right" valign="middle">@{{parseFloat(list.rate).toFixed(2)}}</td> 
                                    <td class="text-right" valign="middle"> 
                                        @{{parseFloat((list.discount)?list.discount:0).toFixed(2)}}
                                    </td>
                                    <td valign="middle">
                                        @{{parseFloat((list.vat_per)?list.vat_per:0).toFixed(2)}}
                                    </td>              
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><b>Total</b></td>
                                    <td class="text-right">@{{formData.totalFromQty}}</td>
                                    <td class="text-right">@{{formData.totalToQty}}</td>
                                    <td class="text-right">@{{formData.totalRate}}</td>
                                    <td class="text-right">@{{formData.totalDiscount}}</td>
                                    <td class="text-right">@{{formData.totalVat}}</td>
                                </tr>
                            </tfoot>
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
    window.dataUrl = 'price-catalogue-data';
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
<script src="{{URL::asset('vuejs/vuejs-datepicker.js') }}"></script>
<script src="{{URL::asset('vuejs/mixin.js') }}"></script>
<script>
     axios.defaults.headers.common['X-CSRF-TOKEN'] = token; 
    Vue.use(VeeValidate);
    Vue.mixin(mixin);
    Vue.component('multiselect', window.VueMultiselect.default)
    var app = new Vue({
      el: '#app',
      components: {
        vuejsDatepicker
      },
      data: {
        filter:{
            from_date:null,
            to_date:null
        },
        formData:{
            date:null,
            voucherDetailsData:[],
            itemAdded:'no'
        }
      },
      created(){
        this.getResults(1);
      },
      watch: {
        'filter.from_date_show': function (val, oldVal) {
            if(val==null){
                this.filter.from_date=null;
            }
        },
        'filter.to_date_show': function (val, oldVal) {
            if(val==null){
                this.filter.to_date=null;
            }
        }
      },
      methods:{
        applicableFromDateFormatter(date) {
            var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            var applicable_from = [day,month,year].join('/');
            this.$set(this.formData, 'applicable_from', applicable_from);
            return applicable_from              
        },
        filterFromDateFormatter(date) {
            var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            var from_date = [day,month,year].join('/');
            this.$set(this.filter, 'from_date', from_date);
            return from_date              
        },
        filterToDateFormatter(date) {
            var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            var to_date = [day,month,year].join('/');
            this.$set(this.filter, 'to_date', to_date);
            return to_date              
        },
        selectLabelItem(label){
            if(label){
                this.formData.catalogue_ref_id = label.id;
                this.formData.price_label_readonly = "yes";
                this.formData.price_label = label.price_label;
                this.labelWiseVoucherDetailsData(label.id);
            } else {
                this.formData.catalogue_ref_id = 0; 
                this.formData.price_label_readonly = "no";
                this.formData.price_label = "";
                this.formData.voucherDetailsData = [];
                this.formData.itemAdded = 'no';
                this.formData.totalRate = 0;
                this.formData.totalFromQty = 0;
                this.formData.totalToQty = 0;
                this.formData.totalDiscount = 0;
                this.formData.totalVat = 0;
            }
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
            if(this.gridData.item_id && parseFloat(this.gridData.from_qty)>0 && parseFloat(this.gridData.to_qty)>0 && parseFloat(this.gridData.rate)>0){
                // check if item has fraction value
                if(Number(this.gridData.has_fraction)==0){
                    if(this.isFloat(this.gridData.from_qty) || this.isFloat(this.gridData.to_qty)){
                        alert('You can not add fraction qty for this item');
                        return false;
                    }
                    if(this.isFloat(this.gridData.from_qty)){
                        alert('You can not add fraction qty for this item');
                        return false;
                    }
                }
                this.formData.voucherDetailsData.push(this.gridData);
                this.gridData = {};
                this.$set(this.gridData, 'vat_type', 'fixed');
                this.formData.itemAdded='yes';
                this.totalQtyCalculate();
            }else{
                if(!this.gridData.item_id){
                    alert('Please select item');
                }else if(!this.gridData.rate){
                    alert('Please enter price');
                }else{
                    alert('Please enter qty');
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
            this.gridData.from_qty = parseFloat(data.from_qty).toFixed(data.decimal_point_place);
            this.gridData.to_qty = parseFloat(data.to_qty).toFixed(data.decimal_point_place);
            let index = this.formData.voucherDetailsData.indexOf(data);
            this.formData.voucherDetailsData.splice(index, 1);
            this.totalQtyCalculate();
            if(this.formData.voucherDetailsData.length<1){
                this.formData.itemAdded='no';
            }
        },
        totalQtyCalculate(){
            var totalFromQty = 0; var totalToQty = 0;
            var totalRate = 0; var totalDiscount = 0; var totalVat = 0;
            this.formData.voucherDetailsData.forEach(function(item, index){
                totalFromQty+= Number(item.from_qty);
                totalToQty+= Number(item.to_qty);
                totalRate+= Number(item.rate);
                if(item.discount) totalDiscount+= Number(item.discount);
                if(item.vat_per)  totalVat+= Number(item.vat_per);
            });
            this.$set(this.formData, 'totalRate', totalRate.toFixed(2));
            this.$set(this.formData, 'totalFromQty', totalFromQty);
            this.$set(this.formData, 'totalToQty', totalToQty);
            this.$set(this.formData, 'totalDiscount', totalDiscount.toFixed(2));
            this.$set(this.formData, 'totalVat', totalVat.toFixed(2));
        },
        initNewVoucherData(){
            const _this = this;
            let URL = _this.baseUrl + '/price-catalogue-data/create';
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
        labelWiseVoucherDetailsData(catalogue_ref_id){
            const _this = this;
            let URL = _this.baseUrl + '/price-catalogue/label-wise-details/'+catalogue_ref_id;
            _this.pageLoader = true;
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
                        _this.formData.itemAdded = 'yes';
                        _this.formData.voucherDetailsData = res.data.voucherDetailsData;
                        _this.formData.totalRate = res.data.totalRate;
                        _this.formData.totalFromQty = res.data.totalFromQty;
                        _this.formData.totalToQty = res.data.totalToQty;
                        _this.formData.totalDiscount = res.data.totalDiscount;
                        _this.formData.totalVat = res.data.totalVat;
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
        gridDataInit(){
            const _this = this;
            this.$set(_this.gridData, 'vat_type', 'fixed');
        }
      }
    })

</script>   
@endsection

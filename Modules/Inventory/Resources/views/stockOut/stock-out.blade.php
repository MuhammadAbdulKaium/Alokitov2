@extends('layouts.master')
@section('css')
 <style type="text/css">
    .modal {
      overflow-y:auto;
    }    
 </style>
@endsection
@section('content')
<div id="app">
    <div class="content-wrapper">
        <div v-if="!pageLoader">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Manage  |<small>Stock Out</small>
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li class="active">Stock Out</li>
                </ul>
            </section>

            <section class="content">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Stock Out List</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" @click="openModal('addForm', 'stock-out-data/create',true)"><i class="fa fa-plus-square"></i> New</a></div>
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
                                    <vuejs-datepicker name="from_date_show" v-model="filter.from_date_show" placeholder="From Date" :format="filterFromDateFormatter"></vuejs-datepicker>
                                </div>     
                                <div class="col-sm-3">
                                    <vuejs-datepicker name="to_date_show" v-model="filter.to_date_show" placeholder="To Date" :format="filterToDateFormatter"></vuejs-datepicker>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="search_key" placeholder="Search by keyword" class="form-control" v-model="filter.search_key" style="width: 100%;" autocomplete="off">
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
                                        <th width="6%">Select</th>
                                        <th class="sortable" v-bind:class="getSortingClass('voucher_no')" @click="sortingChanged('voucher_no')">Voucher #</th>
                                        <th class="sortable" v-bind:class="getSortingClass('item_id')" @click="sortingChanged('item_id')">Item Name</th>
                                        <th>Category</th>
                                        <th>Store</th>
                                        <th>Qty</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th class="sortable" v-bind:class="getSortingClass('date')" @click="sortingChanged('date')">Date</th>
                                        <th class="sortable" v-bind:class="getSortingClass('inventory_direct_stock_out_details.status')" @click="sortingChanged('inventory_direct_stock_out_details.status')">Status</th>
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
                                            <td>@{{(list.category=='direct_sale')?'Direct Sale':''}}</td>
                                            <td>@{{list.store_name}}</td>
                                            <td class="text-right">@{{parseFloat(list.qty).toFixed(list.decimal_point_place)}}</td>
                                            <td class="text-right">@{{parseFloat(list.rate).toFixed(2)}}</td>
                                            <td class="text-right">@{{parseFloat(list.amount).toFixed(2)}}</td>
                                            <td>@{{list.stock_out_date}}</td>
                                            <td>
                                                <span v-if="list.status==0">Pending</span>
                                                <span v-if="list.status==1">Approved</span>
                                                <span v-if="list.status==2">Partial Approved</span>
                                                <span v-if="list.status==3">Reject</span>
                                            </td>
                                            <td>@{{list.approved_text}}</td>
                                            <td>
                                                <a v-if="list.has_approval=='yes'" class="btn btn-primary btn-xs" @click="voucherApproval('stock-out-approval',list.id)"
                                                ><i class="fa fa-check-square" aria-hidden="true"></i> Approved</a>
                                                <a v-if="list.status==0" class="btn btn-primary btn-xs" @click="openModal('addForm', 'stock-out-data/'+list.stock_out_id+'/edit')" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a v-if="list.status==0"  @click="deleteItem(list.id)"
                                                    class="btn btn-danger btn-xs" data-placement="top"
                                                    data-content="delete" title="delete"><i class="fa fa-trash-o"></i></a>
                                                <a class="btn btn-primary btn-xs" @click="openModal('detailsForm', 'stock-out-data/'+list.stock_out_id)" 
                                                   ><i class="fa fa-info-circle" aria-hidden="true"></i> Details</a>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                  <tr>
                                    <td colspan="12" class="text-center">No Record found!</td>
                                  </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 p-t-15">
                                <button @click="deleteArrayItem()" class="btn btn-danger">Delete</button>
                                <!-- <button @click="voucherApproval('stock-out-approval',0)" class="btn btn-info">Approve</button> -->
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
                        <span v-if="formData.id">Edit Stock Out</span>
                        <span v-else>Add Stock Out</span>
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
                                <label class="col-md-4 control-label required">Category <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <select name="category" v-model="formData.category" class="form-control"  data-vv-as="Category" v-validate="'required'">
                                        <option value="direct_sale">Dirrect Sales</option>
                                    </select>
                                    <span class="error" v-if="$validator.errors.has('category')">@{{$validator.errors.first('category')}}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Voucher No <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <input type="text" name="voucher_no" v-model="formData.voucher_no"  class="form-control" required placeholder="REQ-001" data-vv-as="voucher name" v-validate="'required'" :readonly="formData.numbering">
                                    <span class="error" v-if="$validator.errors.has('voucher_no')">@{{$validator.errors.first('voucher_no')}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Date <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <vuejs-datepicker name="date" data-vv-as="Date" v-validate="'required'" v-model="formData.date_show" placeholder="Date" :format="stockInDateFormatter"></vuejs-datepicker>
                                    <span class="error" v-if="$validator.errors.has('date')">@{{$validator.errors.first('date')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Campus <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.campus_list" name="campus_id" v-model="formData.campus_id_model" :options="modalData.campus_list"  placeholder="Select Campus" label="name" track-by="id" @input="selectCampus" data-vv-as="Campus" v-validate="'required'"></multiselect>
                                   
                                    <span class="error" v-if="$validator.errors.has('campus_id')">@{{$validator.errors.first('campus_id')}}</span>                                               
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Store <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15" v-if="formData.itemAdded=='no'">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.store_list" name="store_id" v-model="formData.store_id_model" :options="modalData.store_list"  placeholder="Select Store" label="store_name" track-by="id" @input="selectStore" data-vv-as="Store" v-validate="'required'"></multiselect>
                                   
                                    <span class="error" v-if="$validator.errors.has('store_id')">@{{$validator.errors.first('store_id')}}</span>                                               
                                </div>
                                <div class="col-md-8 p-b-15" v-else>
                                    <input type="text" name="store_name" v-model="formData.store_name" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Representative <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.representative_user_list" name="representative_id" v-model="formData.representative_id_model" :options="modalData.representative_user_list"  placeholder="Select user" label="name" track-by="id" @input="selectRepresentative" data-vv-as="Representative" v-validate="'required'"></multiselect>
                                    <span class="error" v-if="$validator.errors.has('representative_id')">@{{$validator.errors.first('representative_id')}}</span>                                               
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Costing</label>
                                <div class="col-md-8 p-b-15">
                                    <select name="costing" id="costing" v-modal="formData.costing" class="form-control" data-vv-as="Costing" v-validate="'required'">
                                        <option value="by_quality">By quality</option>
                                    </select>   
                                    <span class="error" v-if="$validator.errors.has('costing')">@{{$validator.errors.first('costing')}}</span> 
                                </div>
                            </div>


                        </div>
                    </div>
                    <p style="margin-top: 6px;margin-bottom: 0"><b>Choose Stock In Item: -@{{formData.itemAdded}}</b></p> 
                    
                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="40%">Item Name</th>
                                <th>SKU</th>
                                <th>UOM</th>
                                <th>Stock</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th class="text-center" width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center table-multiselect" valign="middle" width="30%">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.store_item_list" name="item_id_model" v-model="gridData.item_id_model" :options="modalData.store_item_list"  placeholder="Select item" label="product_name" track-by="item_id" @input="selectGridItem" :options-limit="10000"></multiselect>
                                </td>
                                <td>@{{gridData.sku}}</td>
                                <td>@{{gridData.uom}}</td>
                                <td>@{{parseFloat((gridData.current_stock)?gridData.current_stock:0).toFixed((gridData.decimal_point_place)?gridData.decimal_point_place:0)}}</td>
                                <td class="text-center" valign="middle">
                                    <input type="number" name="qty" v-model="gridData.qty" class="form-control" autocomplete="off" placeholder="Quantity" min="0">  
                                </td>
                                <td class="text-center" valign="middle">
                                    <input type="number" name="rate" v-model="gridData.rate" class="form-control" autocomplete="off" placeholder="Rate" min="0">  
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-info table-input-redious" @click="itemGridAdd($event)">ADD</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="margin-top: 6px;margin-bottom: 0"><b> Stock In Item List: </b></p>

                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th>Item Name</th>
                                <th>SKU</th>
                                <th>UOM</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th class="text-center" width="16%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="formData.itemAdded=='yes'">
                                <template v-for="(data, index) in formData.voucherDetailsData" v-bind:key="index">
                                    <tr v-if="data.use_serial==1">
                                        <td valign="middle">@{{index+1}}</td>
                                        <td valign="middle" style="color:red; cursor: pointer;" v-if="data.row_style=='invalid'" @click="openSerialModal($event, data, 'stock-out')"><span v-tooltip="{ content: `Click here for select item serial` }">@{{data.product_name}}</span></td>
                                        <td v-else valign="middle" style="cursor: pointer;"  @click="openSerialModal($event, data, 'stock-out')"><span v-tooltip="{ content: data.serial_html }">@{{data.product_name}}</span></td>          
                                        <td valign="middle">@{{data.sku}}</td>              
                                        <td valign="middle">@{{data.uom}}</td>              
                                        <td class="text-right" valign="middle">@{{parseFloat(data.qty).toFixed(data.decimal_point_place)}}</td> 
                                        <td class="text-right" valign="middle">@{{parseFloat(data.rate).toFixed(2)}}</td>   
                                        <td class="text-right" valign="middle">@{{parseFloat(data.amount).toFixed(2)}}</td>                
                                        <td class="text-center" valign="middle">
                                            <button class="btn-info btn-xs" title="Edit" @click="itemGridEdit($event, data)"><i class="fa fa-pencil-square-o"></i></button>
                                            <button class="btn-xs btn-danger" title="Delete" @click="itemGridRemove($event, data)"><i class="fa fa-trash"></i></button>
                                        </td>              
                                    </tr>
                                    <tr v-else>
                                        <td valign="middle">@{{index+1}}</td>
                                        <td valign="middle">@{{data.product_name}}</td> 
                                        <td valign="middle">@{{data.sku}}</td>              
                                        <td valign="middle">@{{data.uom}}</td>              
                                        <td class="text-right" valign="middle">@{{parseFloat(data.qty).toFixed(data.decimal_point_place)}}</td> 
                                        <td class="text-right" valign="middle">@{{parseFloat(data.rate).toFixed(2)}}</td>   
                                        <td class="text-right" valign="middle">@{{parseFloat(data.amount).toFixed(2)}}</td>                
                                        <td class="text-center" valign="middle">
                                            <button class="btn-info btn-xs" title="Edit" @click="itemGridEdit($event, data)"><i class="fa fa-pencil-square-o"></i></button>
                                            <button class="btn-xs btn-danger" title="Delete" @click="itemGridRemove($event, data)"><i class="fa fa-trash"></i></button>
                                        </td>              
                                    </tr>
                                </template>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="8" align="center">Nothing here</td>
                                </tr>
                            </template>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right"><b>Total</b></td>
                                <td class="text-right">@{{formData.totaRate}}</td>
                                <td class="text-right">@{{formData.totalAmount}}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    

                    <div class="form-group">
                        <label class="col-md-3 control-label required">Comments <span class="text-danger">*</span></label>
                        <div class="col-md-9 p-b-15">
                            <textarea name="comments" v-model="formData.comments" placeholder="Comments" class="form-control" data-vv-as="Comments" v-validate="'required'" maxlength="255"></textarea>
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
                    <h4 class="modal-title">Stock Out Details
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Category</label>
                                <div class="col-md-8 p-b-15">
                                    @{{(formData.category=='direct_sale')?'Direct sale':''}}    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Voucher No</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.voucher_no}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Date</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.stock_out_date}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Campus</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.campus_name}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Store</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.store_name}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Representative</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.name}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Costing</label>
                                <div class="col-md-8 p-b-15">
                                   @{{(formData.costing=='by_quality')?'By quality':''}}   
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
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th width="16%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list, index) in formData.voucherDetailsData" v-bind:key="index">
                                    <td valign="middle">@{{index+1}}</td>
                                    <td valign="middle"><span style="cursor:pointer;" v-tooltip="{ content: list.serial_html }">@{{list.product_name}}</span></td>        
                                    <td valign="middle">@{{list.uom}}</td>                   
                                    <td class="text-right" valign="middle">@{{parseFloat(list.qty).toFixed(list.decimal_point_place)}}</td>              
                                    <td class="text-right" valign="middle">@{{parseFloat(list.rate).toFixed(2)}}</td> 
                                    <td class="text-right" valign="middle">@{{parseFloat(list.amount).toFixed(2)}}</td>                       
                                    <td valign="middle">
                                        <span v-if="list.status==0">Pending</span>
                                        <span v-if="list.status==1">Approved</span>
                                        <span v-if="list.status==2">Partial Approved</span>
                                        <span v-if="list.status==3">Reject</span>
                                    </td>              
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><b>Total</b></td>
                                    <td class="text-right">@{{parseFloat(formData.totalRate).toFixed(2)}}</td>
                                    <td class="text-right">@{{parseFloat(formData.totalAmount).toFixed(2)}}</td>
                                    <td class="text-right"></td>
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

    <div class="modal" id="serialForm" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" v-if="!pageLoader">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                            aria-hidden="true">×</span></button></h4>
                    <h4 class="modal-title">Product serial details - @{{item_name}}
                </div>
                <div class="modal-body">
                    <div class="table-responsive" style="max-height:350px">
                        <table class="responsive table table-striped table-bordered" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="15%">
                                        <label>
                                        <input type="checkbox" true-value="1" false-value="0" v-model="serial_all_check" @change="serialAllCheck($event,serial_all_check)"> @{{serial_all_select_text}}</label></th>
                                    <th>Srial No</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="Object.keys(serial_details).length > 0">
                                    <tr v-for="(serial, index) in serial_details" v-bind:key="index">
                                        <td valign="middle" :class="'class_'+serial.id">
                                            <input type="checkbox" true-value="1" false-value="0" v-model="serial.checked_id" @click="serialCheck($event,serial.checked_id, serial)">
                                        </td>
                                        <td valign="middle">@{{serial.serial_code}}</td> 
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="2" class="text-center">No serial data found</td>
                                    </tr>
                                </template>
                            </tbody>
                            
                        </table>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" @click="selectSerial('stock-out')">Select</button>
                    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>  

    

</div>

@endsection





@section('scripts')
<script type="text/javascript">
    window.dataUrl = 'stock-out-data';
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
<script src="{{URL::asset('vuejs/v-tooltip.min.js') }}"></script>
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
        },
        item_name:''
      },
      created(){
        this.getResults(1);
      },
      methods:{
        stockInDateFormatter(date) {
            var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            var req_date = [day,month,year].join('/');
            this.$set(this.formData, 'date', req_date);
            return req_date              
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
        selectFilterItem(item){
            if(item) this.filter.item_id = item.item_id;
            else this.filter.item_id = 0; 
        },
        selectRepresentative(user){
            if(user) this.formData.representative_id = user.id;
            else this.formData.representative_id = 0; 
        },
        selectVoucher(voucher){
            if(voucher) this.filter.voucher_id = voucher.id;
            else this.filter.voucher_id = 0; 
        },
        selectCampus(campus){
            if(campus) this.formData.campus_id = campus.id;
            else this.formData.campus_id = 0; 
        },
        selectStore(store){
            if(store){
                this.formData.store_id = store.id;
                this.$set(this.formData, 'store_name', store.store_name);
                this.storeWiseItem(store.id);
            } else {
                this.formData.store_id = 0;
                this.modalData.store_item_list=[]; 
                this.$set(this.formData, 'store_name', '');
            }
            
        },
        selectGridItem(item){
            if(item && item.item_id>0){
                this.$set(this.gridData, 'item_id', item.item_id);
                this.$set(this.gridData, 'sku', item.sku);
                this.$set(this.gridData, 'current_stock', item.current_stock);
                this.$set(this.gridData, 'uom', item.uom);
                this.$set(this.gridData, 'product_name', item.product_name);
                this.$set(this.gridData, 'decimal_point_place', item.decimal_point_place);
                this.$set(this.gridData, 'round_of', item.round_of);
                this.$set(this.gridData, 'has_fraction', item.has_fraction);
                this.$set(this.gridData, 'rate', parseFloat(item.avg_cost_price).toFixed(2));
                this.$set(this.gridData, 'use_serial', item.use_serial);
                var row_style = (item.use_serial==0)?'valid' : 'invalid';
                this.$set(this.gridData, 'row_style', row_style);
            } else{
                this.$set(this.gridData, 'item_id', 0);
                this.$set(this.gridData, 'sku', '');
                this.$set(this.gridData, 'current_stock', 0);
                this.$set(this.gridData, 'uom', '');
                this.$set(this.gridData, 'product_name', '');
                this.$set(this.gridData, 'decimal_point_place', 0);
                this.$set(this.gridData, 'round_of', 0);
                this.$set(this.gridData, 'has_fraction', 0);
                this.$set(this.gridData, 'rate', '');
                this.$set(this.gridData, 'use_serial', 0);
            }
            this.$set(this.gridData, 'serial_data', []);
            this.$set(this.gridData, 'serial_html', '');
           
        },
        itemGridAdd(event){
            event.preventDefault();
            if(this.gridData.item_id && parseFloat(this.gridData.qty)>0 && parseFloat(this.gridData.rate)>0 && parseFloat(this.gridData.current_stock)>0 && parseFloat(this.gridData.current_stock)>= parseFloat(this.gridData.qty)){
                var qty = parseFloat(this.gridData.qty);
                var rate =  parseFloat(this.gridData.rate);
                var checkExists =  this.gridDataExist(this.formData.voucherDetailsData,this.gridData);
                if(checkExists.length>0){ alert("You have already added the Item"); return false; }
                // check if item has fraction value
                if(Number(this.gridData.has_fraction)==1){
                    if(this.isFloat(this.gridData.qty)){
                        var qtySplit = qty.toString().split(".");
                        var roundQty = parseInt(qtySplit[0]);
                        var franctionQty = qtySplit[1];
                        // str pad
                        var franctionQtyPad =  franctionQty.toString().padEnd(parseInt(this.gridData.decimal_point_place),0);
                        franctionQtyPad = parseInt(franctionQtyPad);
                        var franctionPrice = rate/Number(this.gridData.round_of);
                        var roundPrice  = roundQty*rate;
                        var fractionPrice  = +franctionPrice*franctionQtyPad;
                        var amount = +roundPrice + +fractionPrice; 
                        this.$set(this.gridData, 'amount', amount);
                    }else{
                        var amount = Number(this.gridData.qty) *  Number(this.gridData.rate);
                        this.$set(this.gridData, 'amount', amount);
                    }
                }else{
                    if(this.isFloat(this.gridData.qty)){
                        alert('You can not add fraction qty for this item');
                        return false;
                    }else{
                        var amount = Number(this.gridData.qty) *  Number(this.gridData.rate);
                        this.$set(this.gridData, 'amount', amount);
                    }
                }
                this.$set(this.gridData, 'avail_qty', this.gridData.qty);
                this.formData.voucherDetailsData.push(this.gridData);
                this.gridData = {};
                this.formData.itemAdded='yes';
                this.totalQtyCalculate();
            }else{
                if(parseFloat(this.gridData.current_stock)==0 || parseFloat(this.gridData.current_stock)< parseFloat(this.gridData.qty)){
                    alert('Insufficient stock'); 
                }else {
                    alert('Select all required field');
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
            data.serial_data = [];
            data.serial_html = '';
            var row_style = (data.use_serial==0)?'valid' : 'invalid';
            data.row_style = row_style;
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
            var totalRate = 0; var totalAmount = 0;
            this.formData.voucherDetailsData.forEach(function(item, index){
                totalRate+= Number(item.rate);
                totalAmount+= Number(item.amount);
            });
            this.$set(this.formData, 'totalRate', totalRate.toFixed(2));
            this.$set(this.formData, 'totalAmount', totalAmount.toFixed(2));
        },
        initNewVoucherData(){
            const _this = this;
            let URL = _this.baseUrl + '/stock-out-data/create';
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
                        if (typeof callback === 'function') {
                            callback();
                        }
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

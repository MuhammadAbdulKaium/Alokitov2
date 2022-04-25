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
                    <i class="fa fa-th-list"></i> Manage  |<small>Purchase Invoice</small>
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li class="active">Purchase Invoice</li>
                </ul>
            </section>

            <section class="content">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Purchase Invoice List</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" @click="openModal('addForm', 'purchase-invoice-data/create',true)"><i class="fa fa-plus-square"></i> New</a></div>
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
                                    <v-date-picker v-model="filter.from_date" :config="dateOptions" style="width: 100%;" placeholder="From Date"></v-date-picker>
                                </div>  
                                <div class="col-sm-3">
                                    <v-date-picker v-model="filter.to_date" :config="dateOptions" style="width: 100%;" placeholder="To Date"></v-date-picker>
                                </div>
                                
                                <div class="col-sm-2">
                                    <input type="text" name="search_key" placeholder="Search by keyword" class="form-control" v-model="filter.search_key" style="width: 100%;" autocomplete="off">
                                </div>
                                <div class="col-sm-1">
                                    <button class="btn btn-primary" @click="getResults(1)"><i class="fa fa-search"></i> Search</button>
                                </div>
                                <div class="col-sm-1" style="padding-left: 0">
                                    <button type="button" class="btn btn-secondary"><i class="fa fa-print"></i> Print <i class="fa fa-caret-down"></i></button>
                                </div>
                                <div class="col-sm-2" style="padding-left: 0">
                                    <a class="btn btn-success btn-xs"
                                    href="{{url('accounts/signatory-config-data',"purchase-invoice")}}"
                                    data-target="#globalModal" data-toggle="modal"
                                    data-modal-size="modal-lg">signatory-config</a>
                                </div>
                            </div>
                        </form>
                        
                        <div class="table-responsive" style="max-height: 500px">
                            <table class="table table-striped table-bordered m-b-0">
                                <thead>
                                    <tr>
                                        <th width="6%">Select</th>
                                        <th class="sortable" v-bind:class="getSortingClass('voucher_no')" @click="sortingChanged('voucher_no')">Voucher #</th>
                                        <th class="sortable" v-bind:class="getSortingClass('vendor_id')" @click="sortingChanged('vendor_id')">Party Name</th>
                                        <th>Total Amount</th>
                                        <th class="sortable" v-bind:class="getSortingClass('date')" @click="sortingChanged('date')">Date</th>
                                        <th class="sortable" v-bind:class="getSortingClass('inventory_purchase_receive_details.status')" @click="sortingChanged('inventory_purchase_receive_details.status')">Status</th>
                                        <th>Approved By</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="Object.keys(paginate_data).length > 0">
                                        <tr v-for="(list, index) in paginate_data" v-bind:key="index">
                                            <td><input type="checkbox" true-value="1" false-value="0" v-model="list.del_id" @change="deleteCheck(list.del_id, list.id)"></td>
                                            <td>@{{list.voucher_no}}</td>
                                            <td>@{{list.vendor_name}}</td>
                                            <td class="text-right">@{{parseFloat(list.grand_total_amount).toFixed(2)}}</td>
                                            <td>@{{list.pur_invoice_date}}</td>
                                            <td>
                                                <span v-if="list.status==0">Pending</span>
                                                <span v-if="list.status==1">Approved</span>
                                                <span v-if="list.status==2">Partial Approved</span>
                                                <span v-if="list.status==3">Reject</span>
                                            </td>
                                            <td>@{{list.approved_text}}</td>
                                            <td>
                                                <a v-if="list.has_approval=='yes'" class="btn btn-primary btn-xs" @click="voucherApproval('purchase-invoice-approval',list.id)"
                                                ><i class="fa fa-check-square" aria-hidden="true"></i> Approved</a>
                                                <a v-if="list.status==0" class="btn btn-primary btn-xs" @click="openModal('addForm', 'purchase-invoice-data/'+list.id+'/edit')" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a v-if="list.status==0"  @click="deleteItem(list.id)"
                                                    class="btn btn-danger btn-xs" data-placement="top"
                                                    data-content="delete" title="delete"><i class="fa fa-trash-o"></i></a>
                                                <a class="btn btn-primary btn-xs" @click="openModal('detailsForm', 'purchase-invoice-data/'+list.id)" 
                                                   ><i class="fa fa-info-circle" aria-hidden="true"></i> Details</a>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                  <tr>
                                    <td colspan="10" class="text-center">No Record found!</td>
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
        <div class="modal-dialog modal-lg ref-modal-width">
            <form class="form-horizontal" @submit.prevent="submitForm(formData,{},initPurchaseInvoiceVoucherData)">
            <div class="modal-content" v-if="!pageLoader">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                            aria-hidden="true">×</span></button>
                    <h4 class="modal-title">
                        <span v-if="formData.id">Edit Purchase Invoice</span>
                        <span v-else>Add Purchase Invoice</span>
                    </h4>
                </div>
                <div class="modal-body scroll-table-300">
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
                                    <input type="text" name="voucher_no" v-model="formData.voucher_no"  class="form-control" required placeholder="PURINV-001" data-vv-as="voucher name" v-validate="'required'" :readonly="formData.auto_voucher">
                                    <span class="error" v-if="$validator.errors.has('voucher_no')">@{{$validator.errors.first('voucher_no')}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Date<span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15" v-if="formData.itemAdded=='no' && !formData.id">
                                    <v-date-picker name="date" data-vv-as="Date" v-validate="'required'" v-model="formData.date" :config="dateOptions" style="width: 100%;" placeholder="dd/mm/yyyy" @dp-change="dateChange"></v-date-picker>
                                    <span class="error" v-if="$validator.errors.has('date')">@{{$validator.errors.first('date')}}</span>
                                </div>
                                <div class="col-md-8 p-b-15" v-else>
                                    <input type="text" name="date" class="form-control" v-model="formData.date" readonly>
                                    <span class="error" v-if="$validator.errors.has('date')">@{{$validator.errors.first('date')}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Due Date <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15" v-if="formData.itemAdded=='no' && !formData.id">
                                    <v-date-picker name="due_date" data-vv-as="Date" v-validate="'required'" v-model="formData.due_date" :config="dateOptions" style="width: 100%;" placeholder="dd/mm/yyyy" @dp-change="dateChange"></v-date-picker>
                                    <span class="error" v-if="$validator.errors.has('due_date')">@{{$validator.errors.first('due_date')}}</span>
                                </div>
                                <div class="col-md-8 p-b-15" v-else>
                                    <input type="text" name="due_date" class="form-control" v-model="formData.due_date" readonly>
                                    <span class="error" v-if="$validator.errors.has('due_date')">@{{$validator.errors.first('due_date')}}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Vendor Name <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15" v-if="formData.itemAdded=='no' && !formData.id">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.vendor_list" name="vendor_id" v-model="formData.vendor_id_model" :options="modalData.vendor_list"  placeholder="Select vendor" label="name" track-by="id" @input="selectVendor" data-vv-as="Vendor" v-validate="'required'"></multiselect>
                                   
                                    <span class="error" v-if="$validator.errors.has('vendor_id')">@{{$validator.errors.first('vendor_id')}}</span>                                               
                                </div>
                                <div class="col-md-8 p-b-15" v-else>
                                    <input type="text" name="vendor_id" v-model="formData.vendor_name" class="form-control" readonly>
                                    <span class="error" v-if="$validator.errors.has('vendor_id')">@{{$validator.errors.first('vendor_id')}}</span>                                               
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Campus <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15" v-if="formData.itemAdded=='no' && !formData.id">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.campus_list" name="campus_id" v-model="formData.campus_id_model" :options="modalData.campus_list"  placeholder="Select Campus" label="name" track-by="id" @input="selectCampus" data-vv-as="Campus" v-validate="'required'"></multiselect>
                                    <span class="error" v-if="$validator.errors.has('campus_id')">@{{$validator.errors.first('campus_id')}}</span>                                               
                                </div>
                                <div class="col-md-8 p-b-15" v-else>
                                    <input type="text" name="campus_id" class="form-control" v-model="formData.campus_name" readonly>
                                    <span class="error" v-if="$validator.errors.has('campus_id')">@{{$validator.errors.first('campus_id')}}</span>
                                </div>
                            </div> 
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Reference Type</label>
                                <div class="col-md-8 p-b-15" v-if="formData.itemAdded=='no' && !formData.id">
                                    <select name="reference_type" id="reference_type" class="form-control" v-model="formData.reference_type" @change="getRefData(formData.reference_type)" v-validate="'required'">
                                        <option value="">None</option>
                                        <option value="purchase-receive">Purchase Receive</option>
                                    </select>  
                                    <span class="error" v-if="$validator.errors.has('reference_type')">@{{$validator.errors.first('reference_type')}}</span>                                                 
                                </div>
                                <div class="col-md-8 p-b-15" v-else>
                                    <input type="text" name="reference_type" v-model="formData.reference_type" class="form-control" readonly>
                                    <span class="error" v-if="$validator.errors.has('reference_type')">@{{$validator.errors.first('reference_type')}}</span>                                                
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Ref</label>
                                <div class="col-md-8 p-b-15">
                                    <button type="button" class="btn btn-success" @click="addPurchaseInvoiceRefData($event)"><i class="icon-spinner icon-spin icon-large"></i>Add</button>                                        
                                </div>
                            </div>
                            <div class="panel-body table-responsive" v-if="formData.reference_type=='purchase-receive'">
                                <table class="responsive table table-striped table-bordered" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Item Name</th>
                                            <th>Ref</th>
                                            <th>Rec. Qty</th>
                                            <th>Avail. Qty <br> for Invoice</th>
                                            <th>Due Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="Object.keys(modalData.refItemList).length > 0">
                                            <tr  v-for="(ref, index) in modalData.refItemList" v-bind:key="index">
                                                <td valign="middle"><input type="checkbox"true-value="1" false-value="0" v-model="ref.ref_check" @change="refCheck(ref.ref_check, ref)"></td>
                                                <td valign="middle">@{{ref.product_name}}</td>
                                                <td valign="middle">@{{ref.ref_voucher_name}}</td>          
                                                <td valign="middle" class="text-right">@{{ref.app_rec_qty}}</td>              
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
                        </div>
                    </div>
                    
                    <p style="margin-top: 6px;margin-bottom: 0"><b> Purchase Invoice Item List: </b></p>

                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="20%">Item Name</th>
                                <th>SKU</th>
                                <th>UOM</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Vat</th>
                                <th width="10%">Vat type</th>
                                <th>Net amount</th>
                                <th>Reference</th>
                                <th>Remarks</th>
                                <th class="text-center" width="6%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="formData.itemAdded=='yes'">
                                <template v-for="(data, index) in formData.voucherDetailsData" v-bind:key="index">
                                    <tr>
                                        <td valign="middle" style="cursor:pointer;">@{{data.product_name}}</td>
                                        <td valign="middle">@{{data.sku}}</td> 
                                        <td valign="middle">@{{data.uom}}</td>         
                                        <td valign="middle">
                                            <input type="number" name="qty" v-model="data.qty" step="any" @keyup="checkQty($event, data)" @change="checkQty($event, data)" style="width: 80px;" min="0">
                                        </td>  
                                         <td valign="middle" class="text-right">
                                            @{{parseFloat(data.rate).toFixed(2)}}
                                        </td>           
                                        <td valign="middle" class="text-right">@{{parseFloat(data.total_amount).toFixed(2)}}</td>              
                                        <td class="text-right" valign="middle">
                                            @{{parseFloat((data.discount)?data.discount:0).toFixed(2)}}
                                        </td> 
                                        <td class="text-right" valign="middle">
                                            @{{parseFloat((data.vat_amount)?data.vat_amount:0).toFixed(2)}}
                                        </td>   
                                        <td class="text-right" valign="middle">
                                            @{{data.vat_type}}
                                        </td> 
                                        <td valign="middle" class="text-right">@{{parseFloat(data.net_total).toFixed(2)}}</td>
                                        <td valign="middle">@{{data.ref_voucher_name}}</td>
                                        <td valign="middle">@{{data.remarks}}</td>      
                                        <td class="text-center" valign="middle">
                                            <button class="btn-xs btn-danger" title="Delete" @click="itemGridRemove($event, data)"><i class="fa fa-trash"></i></button>
                                        </td>   
                                    </tr>
                                </template>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="13" align="center">Nothing here</td>
                                </tr>
                            </template>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-right">Total</th>
                                <th class="text-right">
                                @{{parseFloat((formData.grand_total_amount)?formData.grand_total_amount:0).toFixed(2)}}
                                </th>
                                <th class="text-right">
                                @{{parseFloat((formData.total_discount)?formData.total_discount:0).toFixed(2)}}
                                </th>
                                <th class="text-right">
                                @{{parseFloat((formData.total_vat)?formData.total_vat:0).toFixed(2)}}
                                </th>
                                <th class="text-right"></th>
                                <th class="text-right">
                                @{{parseFloat((formData.grand_net_amount)?formData.grand_net_amount:0).toFixed(2)}}
                                </th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Comments</label>
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
            <div class="modal-content" v-if="!pageLoader">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                            aria-hidden="true">×</span></button></h4>
                    <h4 class="modal-title">Purchase Invoice Details
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
                                <label class="col-md-4 control-label required">Vendor Name</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.vendor_name}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Campus</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.campus_name}}
                                </div>
                            </div>                            
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Date</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.pur_invoice_date}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Due Date</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.due_date_formate}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Reference Type</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.reference_type}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow">
                        <table class="responsive table table-striped table-bordered" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="20%">Item Name</th>
                                    <th>SKU</th>
                                    <th>UOM</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th>Discount</th>
                                    <th>Vat</th>
                                    <th width="10%">Vat type</th>
                                    <th>Net amount</th>
                                    <th>Reference</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in formData.voucherDetailsData" v-bind:key="index">
                                    <td valign="middle"><span style="cursor:pointer" v-tooltip="{ content: data.serial_html }">@{{data.product_name}}</span></td>
                                    <td valign="middle">@{{data.sku}}</td> 
                                    <td valign="middle">@{{data.uom}}</td>         
                                    <td valign="middle" class="text-right">
                                        @{{parseFloat(data.qty).toFixed(data.decimal_point_place)}}
                                    </td>  
                                     <td valign="middle" class="text-right">
                                        @{{parseFloat(data.rate).toFixed(2)}}
                                    </td>           
                                    <td valign="middle" class="text-right">@{{parseFloat(data.total_amount).toFixed(2)}}</td>              
                                    <td class="text-right" valign="middle">
                                        @{{parseFloat(data.discount).toFixed(2)}}
                                    </td> 
                                    <td class="text-right" valign="middle">
                                        @{{parseFloat(data.vat_per).toFixed(2)}}
                                    </td>   
                                    <td class="text-right" valign="middle">
                                        @{{data.vat_type}}
                                    </td> 
                                    <td valign="middle" class="text-right">@{{parseFloat(data.net_total).toFixed(2)}}</td>
                                    <td valign="middle">@{{data.ref_voucher_name}}</td>
                                    <td valign="middle">@{{data.remarks}}</td>       
                                </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="5" class="text-right">Total</th>
                                <th class="text-right">
                                @{{parseFloat((formData.grand_total_amount)?formData.grand_total_amount:0).toFixed(2)}}
                                </th>
                                <th class="text-right">
                                @{{parseFloat((formData.total_discount)?formData.total_discount:0).toFixed(2)}}
                                </th>
                                <th class="text-right">
                                @{{parseFloat((formData.total_vat)?formData.total_vat:0).toFixed(2)}}
                                </th>
                                <th class="text-right"></th>
                                <th class="text-right">
                                @{{parseFloat((formData.grand_net_amount)?formData.grand_net_amount:0).toFixed(2)}}
                                </th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
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
                    <button class="btn btn-success" @click="PrintPurchase(formData.id)"> <i class="fa fa-print"></i> Print</button>
                    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

      

</div>
<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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

@section('scripts')
<script type="text/javascript">
    window.dataUrl = 'purchase-invoice-data';
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
<script src="{{URL::asset('vuejs/v-tooltip.min.js') }}"></script>
<link href="{{ URL::asset('vuejs/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
<script src="{{URL::asset('vuejs/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{URL::asset('vuejs/vue-bootstrap-datetimepicker.min.js') }}"></script>

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
            item_id_model:'',
            item_id:0,
            voucher_id_model:'',
            voucher_id:0,
            from_date:null,
            to_date:null,
            status:'',
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
        //   print 
        PrintPurchase(id){
            const url = this.baseUrl+'/purchase/invoice/print/'+id;
            window.open(url, '_blank');
            // console.log(id);
        }, 
        addPurchaseInvoiceRefData(event){
            event.preventDefault();
            const _this = this;
            if(_this.refDataList.length>0){
                _this.refDataList.forEach(function(data, key){
                    var checkExists =  _this.voucherDetailsDataExist(data.reference_details_id);
                    if(checkExists.length==0){
                       _this.formData.voucherDetailsData.push(data); 
                    }else{
                        alert('Reference already added');
                    }
                    _this.formData.itemAdded='yes';
                });
                _this.totalAmountCalculate();
                _this.refDataList = [];
                // uncheck all reference 
                _this.modalData.refItemList.forEach(function(data, key){
                    data.ref_check = 0;
                });
                
            }else{
                alert('No reference are selected');
            }

        },
        getVoucherNo(event, purchase_category){
            event.preventDefault();
            const _this = this;
            if(purchase_category){
                var dataParams = {
                    purchase_category: purchase_category,
                };
                let URL = _this.baseUrl + '/purchase-order-voucher-no';
                axios.get(URL,{
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
                            _this.formData.voucher_int = 0;
                            _this.formData.voucher_no = '';
                        }else{
                            _this.formData.voucher_int = res.data.voucher_int;
                            _this.formData.voucher_no = res.data.voucher_no;
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
                _this.formData.purchase_category = '';
                _this.formData.voucher_int = 0;
                _this.formData.voucher_no = '';
            }

        },
        dateChange(obj){
            this.refDataList = [];
            this.$set(this.modalData, 'refItemList', []);
            this.formData.reference_type='';
        },
        selectFilterItem(item){
            if(item) this.filter.item_id = item.item_id;
            else this.filter.item_id = 0; 
        },
        selectVoucher(voucher){
            if(voucher) this.filter.voucher_id = voucher.id;
            else this.filter.voucher_id = 0; 
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
        selectVendor(vendor){
            this.refDataList = [];
            this.$set(this.modalData, 'refItemList', []);
            this.formData.reference_type='';
            if(vendor){
                this.formData.vendor_id = vendor.id;
                this.$set(this.formData, 'vendor_name', vendor.name);
            } else {
                this.formData.vendor_id = 0;
                this.$set(this.formData, 'vendor_name', '');
            }            
        },
        checkQty(event, data){
            event.preventDefault();
            if(parseFloat(data.avail_qty)<parseFloat(data.qty)){
                alert('You can not add more then avail qty'); 
                data.qty =  parseFloat(data.avail_qty).toFixed(data.decimal_point_place);
            }
            this.itemAmountCalculation(data);
        },
        itemAmountCalculation(data){
            var has_fraction = data.has_fraction;
            var qty = parseFloat(data.qty);
            if(qty>0){
                if(has_fraction==1 && this.isFloat(qty)){
                    var itemInfo = {
                        rate:data.rate,
                        round_of:data.round_of,
                        qty:qty,
                        decimal_point_place:data.decimal_point_place
                    };
                    var amount = this.fractionQtyRateMultiply(itemInfo);
                }else{
                    var amount = +data.rate * +qty;
                }
                var net_amount = amount; 
                if(data.vat_per){
                    if(data.vat_type=='fixed'){
                        var vat_amount = data.vat_per;
                    }else{
                        var vat_amount = (+amount/100)* +data.vat_per;
                    }
                    net_amount += +vat_amount;
                }else{
                    var vat_amount = 0;
                }
                if(data.discount){
                    net_amount -= +data.discount;
                }
                data.vat_amount = vat_amount;
                data.total_amount = amount;
                data.net_total = net_amount;
            }else{
                data.vat_amount = 0;
                data.total_amount = 0;
                data.net_total = 0;
            }
            this.totalAmountCalculate();
        },
        itemGridRemove(event, data){
            event.preventDefault();
            let index = this.formData.voucherDetailsData.indexOf(data);
            this.formData.voucherDetailsData.splice(index, 1);
            if(this.formData.voucherDetailsData.length<1){
                this.formData.itemAdded='no';
            }
            this.totalAmountCalculate();
        },
        totalAmountCalculate(){
            var grand_total_amount = 0; var total_discount = 0; 
            var total_vat = 0; var grand_net_amount = 0;
            this.formData.voucherDetailsData.forEach(function(list, index){
                grand_total_amount+= Number(list.total_amount);
                total_discount+= Number(list.discount);
                total_vat+= Number(list.vat_amount);
                grand_net_amount+= Number(list.net_total);
            });
            
            this.$set(this.formData, 'grand_total_amount', grand_total_amount);
            this.$set(this.formData, 'total_discount', total_discount);
            this.$set(this.formData, 'total_vat', total_vat);
            this.$set(this.formData, 'grand_net_amount', grand_net_amount);
        },
        initPurchaseInvoiceVoucherData(){
            const _this = this;
            let URL = _this.baseUrl + '/purchase-invoice-data/create';
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
        getRefData(reference_type){
            const _this=this;
            _this.refDataList = [];
            _this.$set(_this.modalData, 'refItemList', []);
            if(reference_type){
                if(_this.formData.date && _this.formData.campus_id && _this.formData.vendor_id){
                    _this.pageLoader = true;
                    let URL = _this.baseUrl + '/purchase-invoice-reference-list';
                    var dataParams = {
                        date: _this.formData.date,
                        campus_id: _this.formData.campus_id,
                        vendor_id: _this.formData.vendor_id,
                        reference_type: _this.formData.reference_type
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
                    if(!_this.formData.vendor_id){
                        alert('Please select a vendor');
                        return false;
                    }
                    
                }
            }

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

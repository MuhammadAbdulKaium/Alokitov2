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
                    <i class="fa fa-th-list"></i> Manage  |<small>Payment Voucher</small>
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Accounts</a></li>
                    <li class="active">Payment Voucher</li>
                </ul>
            </section>

            <section class="content">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Payment Voucher List</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" @click="openModal('addForm', 'payment-voucher-data/create',true)"><i class="fa fa-plus-square"></i> New</a></div>
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
                                    <select name="status" class="form-control" v-model="filter.status" style="width:100%">
                                        <option  value="">Select Status</option>
                                        <option  value="p">Pending</option>
                                        <option  value="1">Approved</option>
                                        <option  value="2">Reject</option>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <input type="text" name="search_key" placeholder="Search by keyword" class="form-control" v-model="filter.search_key" style="width: 100%;" autocomplete="off">
                                </div>  
                                
                                <div class="col-sm-1">
                                    <button class="btn btn-primary" @click="getResults(1)"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                            
                        </form>
                        
                        <div class="table-responsive" style="max-height: 500px">
                            <table class="table table-striped table-bordered m-b-0">
                                <thead>
                                    <tr>
                                        <th width="6%">#</th>
                                        <th class="sortable" v-bind:class="getSortingClass('voucher_no')" @click="sortingChanged('voucher_no')">Voucher #</th>
                                        <th class="sortable" v-bind:class="getSortingClass('amount')" @click="sortingChanged('amount')">Amount</th>
                                        <th class="sortable" v-bind:class="getSortingClass('trans_date')" @click="sortingChanged('trans_date')">Date</th>
                                        <th class="sortable" v-bind:class="getSortingClass('accounts_transaction.status')" @click="sortingChanged('accounts_transaction.status')">Status</th>
                                        <th>Approved By</th>
                                        <th>Remarks</th>
                                        <th width="18%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="Object.keys(paginate_data).length > 0">
                                        <tr v-for="(list, index) in paginate_data" v-bind:key="index">
                                            <td>@{{index+1}}</td>
                                            <td>@{{list.voucher_no}}</td>
                                            <td>@{{list.amount}}</td>
                                            <td>@{{list.trans_date_formate}}</td>
                                            <td>
                                                <span v-if="list.status==0">Pending</span>
                                                <span v-if="list.status==1">Approved</span>
                                                <span v-if="list.status==2">Reject</span>
                                            </td>
                                            
                                            <td>@{{list.approved_text}}</td>
                                            <td>@{{list.remarks}}</td>
                                            <td>
                                                <a v-if="list.has_approval=='yes'" class="btn btn-primary btn-xs" @click="voucherApproval('payment-voucher-approval',list.id)"
                                                ><i class="fa fa-check-square" aria-hidden="true"></i> Approved</a>
                                                <a v-if="list.status==0" class="btn btn-primary btn-xs" @click="openModal('addForm', 'payment-voucher-data/'+list.id+'/edit')" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a v-if="list.status==0"  @click="deleteItem(list.id)"
                                                    class="btn btn-danger btn-xs" data-placement="top"
                                                    data-content="delete" title="delete"><i class="fa fa-trash-o"></i></a>
                                                <a class="btn btn-primary btn-xs" @click="openModal('detailsForm', 'payment-voucher-data/'+list.id)" 
                                                   ><i class="fa fa-info-circle" aria-hidden="true"></i> Details</a>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                  <tr>
                                    <td colspan="8" class="text-center">No Record found!</td>
                                  </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
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

    <!-- Modal form -->

    <div class="modal" id="addForm" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width:1100px">
            <form class="form-horizontal" @submit.prevent="submitForm(formData,{},initNewVoucherData)">
            <div class="modal-content" v-if="!pageLoader">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                            aria-hidden="true">×</span></button>
                    <h4 class="modal-title">
                        <span v-if="formData.id">Edit Payment Voucher</span>
                        <span v-else>Payment Voucher</span>
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
                                <label class="col-md-4 control-label required">Voucher Type <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15" v-if="!formData.id">
                                    <select name="payment_receive_by" class="form-control" v-model="formData.payment_receive_by" data-vv-as="Voucher type" v-validate="'required'">
                                        <option value="">Select Type</option>
                                        <option value="Bank">Bank</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                    <span class="error" v-if="$validator.errors.has('payment_receive_by')">@{{$validator.errors.first('payment_receive_by')}}</span>
                                </div>
                                <div class="col-md-8 p-b-15" v-else>
                                    <input type="text" name="payment_receive_by" v-model="formData.payment_receive_by"  class="form-control" required data-vv-as="Voucher type" v-validate="'required'" readonly>
                                    <span class="error" v-if="$validator.errors.has('payment_receive_by')">@{{$validator.errors.first('payment_receive_by')}}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Payment Date <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <vuejs-datepicker name="trans_date" data-vv-as="Date" v-validate="'required'" v-model="formData.trans_date_show" placeholder="Payment Date" :format="transDateFormatter"></vuejs-datepicker>
                                    <span class="error" v-if="$validator.errors.has('trans_date')">@{{$validator.errors.first('trans_date')}}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Menual Ref. no</label>
                                <div class="col-md-8 p-b-15">
                                    <input type="text" name="menual_ref_no" v-model="formData.menual_ref_no"  class="form-control" placeholder="Ref. no">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Voucher No <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <input type="text" name="voucher_no" v-model="formData.voucher_no"  class="form-control" required data-vv-as="voucher name" v-validate="'required'" :readonly="formData.numbering">
                                    <span class="error" v-if="$validator.errors.has('voucher_no')">@{{$validator.errors.first('voucher_no')}}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Total Amount <span class="text-danger">*</span></label>
                                <div class="col-md-8 p-b-15">
                                    <input type="text" name="amount" v-model="formData.amount"  class="form-control" required data-vv-as="Amount" v-validate="'required'" readonly>
                                    <span class="error" v-if="$validator.errors.has('amount')">@{{$validator.errors.first('amount')}}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Narration <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <textarea name="remarks" v-model="formData.remarks" placeholder="Note" class="form-control" data-vv-as="Note" v-validate="'required'" maxlength="255"></textarea>
                                    <span class="error" v-if="$validator.errors.has('remarks')">@{{$validator.errors.first('remarks')}}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr style="margin:0 0 15px 0">

                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="25%">Ledger (Dr)</th>
                                <th>Dr Amount</th>
                                <th width="25%">Payment By (Cr)</th>
                                <th>Cr Amount</th>
                                <th>Remarks</th>
                                <th class="text-center" width="10%">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td valign="middle" class="text-center" rowspan="2" style="vertical-align:middle;">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.ledger" name="dr_sub_ledger_model" v-model="gridData.dr_sub_ledger_model" :options="modalData.ledger" group-values="child" group-label="parentAccountCode"  placeholder="Select Ledger" label="accountCode" track-by="id" @input="selectDrLedger" :options-limit="10000"><span slot="noResult">Oops! No elements found. Consider changing the search query.</span></multiselect>
                                </td>
                                <td valign="middle" class="text-right">
                                    <input type="number" name="dr_amount" v-model="gridData.dr_amount"  class="form-control" placeholder="e.g.5000">
                                </td> 
                                <td valign="middle">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.cashBankLedger" name="cr_sub_ledger_model" v-model="gridData.cr_sub_ledger_model" :options="modalData.cashBankLedger" group-values="child" group-label="parentAccountCode"  placeholder="Select Ledger" label="accountCode" track-by="id" @input="selectCrLedger" :options-limit="10000"><span slot="noResult">Oops! No elements found. Consider changing the search query.</span></multiselect>
                                </td>          
                                <td valign="middle" class="text-right">
                                    <input type="number" name="cr_amount" v-model="gridData.cr_amount"  class="form-control" placeholder="e.g.5000">
                                </td>              
                                <td class="text-right" valign="middle">
                                    <input type="text" name="narration" v-model="gridData.narration" class="form-control" placeholder="Narration">
                                </td> 
                                <td class="text-center" valign="middle" rowspan="2" style="vertical-align:middle;">
                                    <button type="button" class="btn btn-success" @click="voucherGridAdd($event)">Add</button>
                                </td>              
                            </tr>
                        </tbody>
                    </table>
                    <template v-if="gridData.pay_ledger=='bank'">
                    <p><b>Bank Cheque Information:</b></p>
                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th>A/C Payee</th>
                                <th>Pay to </th>
                                <th>Cheque No <span class="text-danger">*</span></th>
                                <th>Cheque Date <span class="text-danger">*</span></th>
                                <th>Draw On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <label><input type="checkbox" true-value="1" false-value="0" v-model="gridData.cr_ac_payee" name="cr_ac_payee"> A/C Payee </label>
                                </td>
                                <td>
                                    <input type="text" name="cr_pay_to" v-model="gridData.cr_pay_to" class="form-control" placeholder="Pay to">
                                </td>
                                <td>
                                    <input type="number" name="cr_cheque_no" v-model="gridData.cr_cheque_no" class="form-control" placeholder="Cheque no">
                                </td>
                                <td>
                                    <vuejs-datepicker name="cr_cheque_date" v-model="gridData.cr_cheque_date_show" placeholder="Cheque Date" :format="chequeDateFormatter"></vuejs-datepicker>
                                </td>
                                <td>
                                    <vuejs-datepicker name="cr_draw_on" v-model="gridData.cr_draw_on_show" placeholder="Draw Date" :format="drawDateFormatter"></vuejs-datepicker>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </template>
                    <p><b>Voucher Details Data:</b></p>
                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Ledger</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th class="text-center" width="16%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="formData.itemAdded=='yes'">
                                <template v-for="(data, index) in formData.voucherDetailsData" v-bind:key="index">
                                    <tr>
                                        <td valign="middle">@{{data.dr_accountCode}}</td>          
                                        <td valign="middle" class="text-right">@{{data.amount}}</td>              
                                        <td class="text-right" valign="middle">0</td> 
                                        <td class="text-center" valign="middle" rowspan="2" style="vertical-align:middle;">
                                            <button class="btn-xs btn-danger" title="Delete" @click="voucherGridRemove($event, data)"><i class="fa fa-trash"></i></button>
                                        </td>              
                                    </tr>
                                    <tr>
                                        <td valign="middle">
                                            @{{data.cr_accountCode}}
                                            <template v-if="data.pay_ledger=='bank'">
                                                <p v-if="data.cr_ac_payee==1" style="margin: 0;"><b>A/C Payee:</b> Yes</p>
                                                <p v-if="data.cr_pay_to && data.cr_pay_to!=''" style="margin: 0;"><b>Pay to:</b> @{{data.cr_pay_to}}</p>
                                                <p v-if="data.cr_cheque_no && data.cr_cheque_no!=''" style="margin: 0;"><b>Cheque No:</b> @{{data.cr_cheque_no}}</p>
                                                <p v-if="data.cr_cheque_date && data.cr_cheque_date!=''" style="margin: 0;"><b>Cheque Date:</b> @{{data.cr_cheque_date}}</p>
                                                <p v-if="data.cr_draw_on && data.cr_draw_on!=''" style="margin: 0;"><b>Draw On:</b> @{{data.cr_draw_on}}</p>
                                            </template>
                                        </td>          
                                        <td class="text-right" valign="middle">0</td>              
                                        <td class="text-right" valign="middle">@{{data.amount}}</td> 
                                    </tr>
                                </template>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="5" align="center">Nothing here</td>
                                </tr>
                            </template>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right"><b>Total</b></td>
                                <td class="text-right">@{{formData.totalDebit}}</td>
                                <td class="text-right">@{{formData.totalCredit}}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                   
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
        <div class="modal-dialog modal-lg" style="width:1100px">
            <div class="modal-content" v-if="!pageLoader">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">
                        <span>Payment Voucher</span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div style="text-align: center">
                                <h4><b>@{{ formData.institute }}</b></h4>
                                <h4><b>@{{ formData.campus }}</b></h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <span><b>Voucher No: @{{formData.voucher_no}}</b></span>
                        </div>
                        <div class="col-sm-6">
                            <span style="float: right"><b>Date: @{{formData.trans_date}}</b></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="responsive table table-striped table-bordered" cellspacing="0">
                                <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Ledger</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(data, index) in formData.voucherDetailsData" v-bind:key="index">
                                    <tr>
                                        <td valign="middle" class="text-center" rowspan="2" style="vertical-align:middle;">@{{index+1}}</td>
                                        <td valign="middle">@{{data.dr_accountCode}}</td>
                                        <td valign="middle" class="text-right">@{{data.amount}}</td>
                                        <td class="text-right" valign="middle">0</td>
                                    </tr>
                                    <tr>
                                        <td valign="middle">
                                            @{{data.cr_accountCode}}
                                            <template v-if="data.pay_ledger=='bank'">
                                                <p v-if="data.cr_ac_payee==1" style="margin: 0;"><b>A/C Payee:</b> Yes</p>
                                                <p v-if="data.cr_pay_to && data.cr_pay_to!=''" style="margin: 0;"><b>Pay to:</b> @{{data.cr_pay_to}}</p>
                                                <p v-if="data.cr_cheque_no && data.cr_cheque_no!=''" style="margin: 0;"><b>Cheque No:</b> @{{data.cr_cheque_no}}</p>
                                                <p v-if="data.cr_cheque_date && data.cr_cheque_date!=''" style="margin: 0;"><b>Cheque Date:</b> @{{data.cr_cheque_date}}</p>
                                                <p v-if="data.cr_draw_on && data.cr_draw_on!=''" style="margin: 0;"><b>Draw On:</b> @{{data.cr_draw_on}}</p>
                                            </template>
                                        </td>
                                        <td class="text-right" valign="middle">0</td>
                                        <td class="text-right" valign="middle">@{{data.amount}}</td>
                                    </tr>
                                </template>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="2" class="text-right"><b>Total</b></td>
                                    <td class="text-right">@{{formData.totalDebit}}</td>
                                    <td class="text-right">@{{formData.totalCredit}}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <p v-if="formData.totalDebit"><b>In Word: @{{ inWords(formData.totalDebit) }}</b></p>
                            <p><b>Remarks: </b>@{{formData.remarks}}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <span><i class="fa fa-print"></i> Print</span>
                    </button>
                    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


</div>

@endsection





@section('scripts')
<script type="text/javascript">
    window.dataUrl = 'payment-voucher-data';
    window.baseUrl = '{{url('/accounts')}}';
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
            from_date:null,
            to_date:null,
            status:''
        },
        formData:{
            date:null,
            due_date:null,
            voucherDetailsData:[],
            voucherDebitData:[],
            voucherCreditData:[],
            itemAdded:'no'
        }
      },
      created(){
        this.getResults(1);
      },
      methods:{
        transDateFormatter(date) {
            var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            var trans_date = [day,month,year].join('/');
            this.$set(this.formData, 'trans_date', trans_date);
            return trans_date   
        },
        chequeDateFormatter(date){
            var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            var cr_cheque_date = [day,month,year].join('/');
            this.$set(this.gridData, 'cr_cheque_date', cr_cheque_date);
            return cr_cheque_date;    
        },
        drawDateFormatter(date){
            var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            var cr_draw_on = [day,month,year].join('/');
            this.$set(this.gridData, 'cr_draw_on', cr_draw_on);
            return cr_draw_on;
        },
        selectDrLedger(ledger){
            if(ledger && ledger.id>0){
                this.$set(this.gridData, 'dr_sub_ledger', ledger.id);
                this.$set(this.gridData, 'dr_accountCode', ledger.accountCode);
            }else{
                this.$set(this.gridData, 'dr_sub_ledger', 0);
                this.$set(this.gridData, 'dr_accountCode', '');
            }
            if(!this.gridData.pay_ledger){
                this.$set(this.gridData, 'pay_ledger', 'cash');
            }
        },
        selectCrLedger(ledger){
            if(ledger && ledger.id>0){
                this.$set(this.gridData, 'cr_sub_ledger', ledger.id);
                this.$set(this.gridData, 'cr_accountCode', ledger.accountCode);
                if(this.modalData.bankLedgerIds.includes(ledger.id)){
                    this.$set(this.gridData, 'pay_ledger', 'bank');
                    this.$set(this.gridData, 'cr_cheque_date_show', this.modalData.current_date);
                    this.$set(this.gridData, 'cr_cheque_date', this.modalData.current_date);
                }else{
                    this.$set(this.gridData, 'pay_ledger', 'cash');
                }
            }else{
                this.$set(this.gridData, 'cr_sub_ledger', 0);
                this.$set(this.gridData, 'cr_accountCode', '');
                this.$set(this.gridData, 'pay_ledger', 'cash');
            }
            this.$set(this.gridData, 'cr_ac_payee', '0');
        },
        voucherGridAdd(event){
            event.preventDefault();
            if((this.gridData.dr_sub_ledger && parseFloat(this.gridData.dr_amount)>0) || (this.gridData.cr_sub_ledger && parseFloat(this.gridData.cr_amount)>0) && (this.gridData.pay_ledger=='cash' || (this.gridData.pay_ledger=='bank' && this.gridData.cr_cheque_no && this.gridData.cr_cheque_date))){
                var checkExists =  this.gridDataExist(this.formData.voucherDetailsData,this.gridData);
                if(checkExists.length>0){ alert("You have already added these Ledgers"); return false; }
                this.$set(this.gridData, 'id', 0);
                this.formData.voucherDetailsData.push(this.gridData);
                if(this.gridData.dr_sub_ledger){
                    this.formData.voucherDebitData.push(this.gridData);
                }
                if(this.gridData.cr_sub_ledger){
                    this.formData.voucherCreditData.push(this.gridData);
                }
                this.gridData = {};
                this.formData.itemAdded='yes';
                this.totalAmountCalculate();
            }else{
                if(!this.gridData.dr_sub_ledger && !this.gridData.cr_sub_ledger){
                    alert('Please select Ledger');
                }else if(!this.gridData.dr_amount && !this.gridData.cr_amount){
                    alert('Please enter amount');
                }else if(this.gridData.dr_amount<=0 && !this.gridData.cr_amount<=0){
                    alert('Please enter amount greater than zero');
                }else if(this.gridData.dr_sub_ledger && !parseFloat(this.gridData.dr_amount)>0){
                    alert('Please enter dr amount greater than zero');
                }else if(this.gridData.cr_sub_ledger && !parseFloat(this.gridData.cr_amount)>0){
                    alert('Please enter cr amount greater than zero');
                }else{
                    alert('bank');
                    if(this.gridData.pay_ledger=='bank'){
                        alert('Please fill all bank cheque details');
                    }
                }
            }

        },
        voucherGridRemove(event, data){
            event.preventDefault();
            let index = this.formData.voucherDetailsData.indexOf(data);
            this.formData.voucherDetailsData.splice(index, 1);
            this.totalAmountCalculate();
            if(this.formData.voucherDetailsData.length<1){
                this.formData.itemAdded='no';
            }
        },
        gridDataExist(list,input){
            console.log(list);
            if(input.dr_sub_ledger && input.cr_sub_ledger){
                return list.filter(function(row){
                    return +row.dr_sub_ledger == +input.dr_sub_ledger && +row.cr_sub_ledger == +input.cr_sub_ledger; 
                });
            }else if(input.dr_sub_ledger){
                return list.filter(function(row){
                    return +row.dr_sub_ledger == +input.dr_sub_ledger && (!row.cr_sub_ledger || row.cr_sub_ledger==0); 
                });
            }else{
                return list.filter(function(row){
                    return +row.cr_sub_ledger == +input.cr_sub_ledger && (!row.dr_sub_ledger || row.dr_sub_ledger==0); 
                });
            }
        },
        totalAmountCalculate(){
            var totalAmount = 0;
            this.formData.voucherDetailsData.forEach(function(list, index){
                totalAmount+= Number(list.amount);
            });
            total_amount = (totalAmount==0)?'':totalAmount;
            this.$set(this.formData, 'amount', total_amount);
            this.$set(this.formData, 'totalDebit', total_amount);
            this.$set(this.formData, 'totalCredit', total_amount);
        },
        initNewVoucherData(obj){
            const _this = this;
            _this.formData = {
                'payment_receive_by':"",
                'voucher_no':"Auto",
                'numbering':true,
                'trans_date':this.modalData.current_date,
                'trans_date_show':this.modalData.current_date,
                'voucherDetailsData':[],
                'itemAdded':"no"
            };
        }
        
      }
    })

</script>   
@endsection

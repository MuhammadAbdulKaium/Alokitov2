@extends('layouts.master')
@section('content')
<div id="app">
    <div class="content-wrapper">
        <div v-if="!pageLoader">
            <section class="content-header">
                <h1>
                    <i class="fa fa-plus-square"></i> Add New Customer
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li><a href="#">SOP Setup</a></li>
                    <li><a href="#">Sales</a></li>
                    <li class="active">Customer</li>
                </ul>
            </section>

            <section class="content">                
                <form  class="form-horizontal" @submit.prevent="submitCustomerForm()">
                <div class="box box-solid">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Category <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="category_id" class="form-control" v-model="formData.category_id" v-validate="'required'" data-vv-as="Category">
                                            <option value="1">Sundry Debtors</option>
                                        </select>
                                        <span class="error" v-if="$validator.errors.has('category_id')">@{{$validator.errors.first('category_id')}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Type <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="type" class="form-control" v-validate="'required'" v-model="formData.type" data-vv-as="Type">
                                            <option value="1">General</option>
                                        </select>
                                        <span class="error" v-if="$validator.errors.has('type')">@{{$validator.errors.first('type')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">General</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-3 control-label">Name <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="name" class="form-control" placeholder="Name" v-model="formData.name" v-validate="'required'" data-vv-as="Name">
                                                    <span class="error" v-if="$validator.errors.has('name')">@{{$validator.errors.first('name')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Picture</label>
                                                <div class="col-sm-9">
                                                    <input type="file" name="" class="form-control" @change="onFileChange($event, 'customer_image')" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">GL Code</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="gl_code" class="form-control" placeholder="GL Code" v-model="formData.gl_code" maxlength="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">Home</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-3 control-label p-r-0">Home Address</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="home_address" class="form-control" placeholder="Home Address" v-model="formData.home_address"  maxlength="255">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">City</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="home_city" class="form-control" placeholder="City" v-model="formData.home_city" maxlength="255">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">State</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="home_state" class="form-control" placeholder="State" v-model="formData.home_state" maxlength="50">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-3 control-label p-r-0">Zip Code</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="home_zip_code" class="form-control" placeholder="Zip Code" v-model="formData.home_zip_code" maxlength="50">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Mobile</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="home_mobile" class="form-control" placeholder="Mobile" v-model="formData.home_mobile" maxlength="20">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Phone</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="home_phone" class="form-control" placeholder="Phone" v-model="formData.home_phone" maxlength="20">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-3 control-label p-r-0">Fax</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="home_fax" class="form-control" placeholder="Fax" v-model="formData.home_fax" maxlength="50">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Web</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="home_web" class="form-control" placeholder="Web" v-model="formData.home_web" maxlength="100">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="birth_date_show" class="col-sm-3 control-label">Birth Date</label>
                                                <div class="col-sm-9">
                                                    <vuejs-datepicker name="birth_date_show" v-model="formData.birth_date_show" placeholder="Birth Date" :format="birthDateDateFormatter"></vuejs-datepicker>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="anniversary" class="col-sm-3 control-label">Anniversary</label>
                                                <div class="col-sm-9">
                                                    <vuejs-datepicker name="anniversary_show" v-model="formData.anniversary_show" placeholder="Anniversary Date" :format="anniversaryDateDateFormatter"></vuejs-datepicker>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="panel panel-default">
                              <div class="panel-heading">Business</div>
                              <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-3 control-label p-r-0">Company</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="business_company_name" class="form-control" placeholder="Company" v-model="formData.business_company_name" maxlength="255">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Job Title</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="business_job_title" class="form-control" placeholder="Job Title" v-model="formData.business_job_title" maxlength="255">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Department</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="business_department" class="form-control" placeholder="Department" v-model="formData.business_department" maxlength="255">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Address</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="business_address" class="form-control" placeholder="Address" v-model="formData.business_address" maxlength="255">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">State</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="business_state" class="form-control" placeholder="State" v-model="formData.business_state" maxlength="255">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-3 control-label p-r-0">Zip Code</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="business_zip_code" class="form-control" placeholder="Zip Code" v-model="formData.business_zip_code" maxlength="255">
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="business_country" class="col-sm-3 control-label p-r-0">Country</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="business_country" class="form-control" placeholder="Country" v-model="formData.business_country" maxlength="100">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="business_fax" class="col-sm-3 control-label p-r-0">Fax</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="business_fax" class="form-control" placeholder="Fax" v-model="formData.business_fax" maxlength="50">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="business_phone" class="col-sm-3 control-label">Phone</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="business_phone" class="form-control" placeholder="Phone" v-model="formData.business_phone" maxlength="50">
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="business_web" class="col-sm-3 control-label">Web</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="business_web" class="form-control" placeholder="Web" v-model="formData.business_web" maxlength="255">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="opening_balance" class="col-sm-3 control-label">Opening Balance</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="opening_balance" class="form-control" placeholder="Opening Balance" v-model="formData.opening_balance">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="opening_balance" class="col-sm-3 control-label">Opening Balance type</label>
                                                <div class="col-sm-9">
                                                    <select name="opening_balance_type" class="form-control" v-model="formData.opening_balance_type">
                                                        <option value="dr">Dr</option>
                                                        <option value="cr">Cr</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="credit_priod" class="col-sm-3 control-label">Credit period</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="credit_priod" class="form-control" placeholder="Credit period" v-model="formData.credit_priod">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="credit_limit" class="col-sm-3 control-label">Credit Limit</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="credit_limit" class="form-control" placeholder="Credit Limit" v-model="formData.credit_limit">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">Bill tracking</label>
                                                <div class="col-sm-9">
                                                    <select name="bill_tracking" class="form-control" v-model="formData.bill_tracking">
                                                        <option value="yes">Yes</option>
                                                        <option value="no">NO</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="price_cate_id" class="col-sm-3 control-label">Price label</label>
                                                <div class="col-sm-9">
                                                    <multiselect :select-label="''" :deselect-label="''" v-if="modalData.price_label_list" name="price_cate_id_model" v-model="formData.price_cate_id_model" :options="modalData.price_label_list"  placeholder="Select label" label="price_label" track-by="catalogue_uniq_id" @input="selectLabelItem"></multiselect>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="maintaining_cost_center" class="col-sm-3 control-label">Maintain Cost center</label>
                                                <div class="col-sm-9">
                                                    <select name="maintaining_cost_center" class="form-control" v-model="formData.maintaining_cost_center">
                                                        <option value="yes">Yes</option>
                                                        <option value="no">NO</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                              <div class="panel-heading">Commission</div>
                              <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="commission_type" class="col-sm-3 control-label">Type</label>
                                                <div class="col-sm-9">
                                                    <select name="commission_type" class="form-control" v-model="formData.commission_type">
                                                        <option value="fixed">Fixed</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="commission_value" class="col-sm-3 control-label">Value</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="commission_value" class="form-control" placeholder="Commission value" v-model="formData.commission_value">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="bill_by_bill" class="col-sm-3 control-label">Bill By Bill</label>
                                                <div class="col-sm-9">
                                                    <select name="bill_by_bill"  class="form-control" v-model="formData.bill_by_bill">
                                                        <option value="yes">Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                              </div>
                            </div>

                            <div class="panel panel-default">
                              <div class="panel-heading">Terms & Conditions</div>
                              <div class="panel-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="10%">#</th>
                                                <th width="80%">Text</th>
                                                <th width="10%">
                                                    <button class="btn btn-sm btn-primary" @click="internalGridAdd($event)">Add</button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row,index) in formData.add_row">
                                                <td>@{{index+1}}</td>
                                                <td>
                                                    <input v-model="row.term_condition" type="text" name="" class="form-control">
                                                </td>
                                                <td>
                                                   <button class="btn btn-sm btn-danger" @click="internalGridRemove($event, row)" >Remove</button> 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                              </div>
                            </div>

                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" :disabled="buttonDisabled">Create</button>   <a class="btn btn-default" href="{{url('inventory/customer')}}">Cancel</a>
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
    window.dataUrl = 'customer-data';
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
<script src="{{URL::asset('vuejs/moment.min.js') }}"></script>
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
        formData:{
            date:null,
            due_date:null
        }
      },
      created(){
        this.formPageData('customer-create-form-data');
      },
      methods:{
        submitCustomerForm(formData = this.formData) {
            const _this = this;
            const URL = _this.baseUrl + '/' + _this.dataUrl();
            _this.$validator.validate().then(valid => {
                if (valid) {
                    var formReqData = new FormData();
                    Object.keys(_this.imageData).forEach(key => {
                        formReqData.append(key, _this.imageData[key]);
                    });
                    Object.keys(formData).forEach(key => {
                        if(key=='add_row'){
                            var json_arr = JSON.stringify(formData[key]);
                            formReqData.append(key, json_arr);
                        }else{
                            formReqData.append(key, formData[key]);
                        }
                    });
                    _this.pageLoader = true;
                    _this.buttonDisabled = true;
                    axios.post(URL, formReqData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then((res) => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            if (res.data.status == 1) {
                                _this.formReset();
                                _this.customerFormData('customer-create-form-data');
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
        customerFormData(url = false) {
            const _this = this;
            if (url) {
                var URL = _this.baseUrl + '/' + url;
            } else {
                var URL = _this.baseUrl + '/' + _this.dataUrl();
            }
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

        selectLabelItem(label){
            if(label){
                this.formData.price_cate_id = label.catalogue_uniq_id;
            } else {
                this.formData.price_cate_id = 0; 
            }
        },
        birthDateDateFormatter(date) {
            var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            var birth_date = [day,month,year].join('/');
            this.$set(this.formData, 'birth_date', birth_date);
            return birth_date              
        },
        anniversaryDateDateFormatter(date) {
            var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            var anniversary = [day,month,year].join('/');
            this.$set(this.formData, 'anniversary', anniversary);
            return anniversary              
        },
        internalGridAdd(event){
            event.preventDefault();
            this.formData.add_row.push({id:0,term_condition:''});
        },
        internalGridRemove(event, item){
            event.preventDefault();
            var length = this.formData.add_row.length;
            if(length>1){
              let index = this.formData.add_row.indexOf(item);
              this.formData.add_row.splice(index, 1);
            }
        }

      }
    })

</script>   
@endsection


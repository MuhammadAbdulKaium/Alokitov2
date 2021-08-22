@extends('layouts.master')
@section('content')
<div id="app">
    <div class="content-wrapper">
        <div v-if="!pageLoader">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Manage  |<small>Vendor</small>
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li><a href="#">SOP Setup</a></li>
                    <li><a href="#">Purchase</a></li>
                    <li class="active">Vendor</li>
                </ul>
            </section>

            <section class="content">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Vendor List</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="{{ url('inventory/vendor-data/create') }}"><i class="fa fa-plus-square"></i> New</a></div>
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

                                <div class="col-sm-3">
                                     <input type="text" name="search_key" v-model="filter.search_key" class="form-control" placeholder="Search by keyword" style="width:100%">
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
                                        <th class="sortable" v-bind:class="getSortingClass('name')" @click="sortingChanged('name')">Name</th>
                                        <th class="sortable" v-bind:class="getSortingClass('category_id')" @click="sortingChanged('category_id')">Category</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="Object.keys(paginate_data).length > 0">
                                        <tr v-for="(list, index) in paginate_data" v-bind:key="index">
                                            <td>@{{index+1}}</td>
                                            <td>@{{list.name}}</td>
                                            <td>@{{(list.category_id==1)?'Sundry Creditors':list.category_id}}</td>
                                            <td>
                                                <a class="btn btn-primary btn-xs" :href="baseUrl+'/vendor-data/'+list.id+'/edit'" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a @click="deleteItem(list.id)"
                                                    class="btn btn-danger btn-xs" data-placement="top"
                                                    data-content="delete" title="delete"><i class="fa fa-trash-o"></i></a>
                                                <a class="btn btn-primary btn-xs" :href="baseUrl+'/vendor-data/'+list.id"   @click="openModal('detailsForm', 'vendor-data/'+list.id)" 
                                                   ><i class="fa fa-info-circle" aria-hidden="true"></i> Details</a>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                  <tr>
                                    <td colspan="4" class="text-center">No Record found!</td>
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


</div>

@endsection





@section('scripts')
<script type="text/javascript">
    window.dataUrl = 'vendor-data';
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
       
      },
      created(){
        this.getResults(1);
      },
      methods:{
        
      }
    })

</script>   
@endsection

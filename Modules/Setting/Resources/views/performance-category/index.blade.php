@extends('setting::layouts.master')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

@section('section-title')

    <h1>
        <i class="fa fa-th-list"></i> Manage  |<small>Performance</small>
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Setting</a></li>
        <li class="active">Configuration</li>
        <li class="active">Performance category</li>
    </ul>
@endsection

@section('page-content')
    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @endif
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-square"></i> Type</h3>
                </div>
                @if($insertOrEditType=='insert')
                    <form id="setting-city-form" name="setting_city_form" action="{{url('setting/performance/type/create')}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Type Name</label>
                                        <input type="text" id="name"  class="form-control" name="performance_type" placeholder="Category Name" aria-required="true">
                                        <div class="help-block">
                                            @if ($errors->has('performance_type'))
                                                <strong>{{ $errors->first('performance_type') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Create</button>
                            <button type="reset" class="btn btn-default btn-create">Reset</button>
                        </div>
                        <!-- /.box-footer-->
                    </form>
                @endif
                @if($insertOrEditType=='edit')
                    @foreach($type as $value)
                    <form id="setting-city-form" name="setting_city_form" action="{{ url('setting/performance/type/update', [$value->id]) }}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Category Name</label>
                                        <input type="text" id="name" value="{{$value->performance_type}}" class="form-control" name="performance_type" placeholder="Category Name" aria-required="true">
                                        <div class="help-block">
                                            @if ($errors->has('performance_type'))
                                                <strong>{{ $errors->first('performance_type') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Update</button>
                            <button type="reset" class="btn btn-default btn-create">Reset</button>
                        </div>
                        <!-- /.box-footer-->
                    </form>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-square"></i> Category</h3>
                </div>
                @if($insertOrEditCategory=='insert')
                    <form id="setting-city-form" name="setting_city_form" action="{{url('setting/performance/category/create')}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Type</label>                                        
                                        <select type="text" id="category_type_id" class="form-control" name="category_type_id" aria-required="true">
                                            <option >---Select Type---</option>
                                            @foreach($performancetype as $type)    
                                                <option value="{{$type->id}}">{{$type->performance_type}}</option>
                                            @endforeach    
                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('category_type_id'))
                                                <strong>{{ $errors->first('category_type_id') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Category Name</label>
                                        <input type="text" id="name"  class="form-control" name="category_name" placeholder="Category Name" aria-required="true">
                                        <div class="help-block">
                                            @if ($errors->has('category_name'))
                                                <strong>{{ $errors->first('category_name') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Mandatory</label>                                        
                                        <input type="checkbox" id="is_mandatory" name="is_mandatory" value="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Create</button>
                            <button type="reset" class="btn btn-default btn-create">Reset</button>
                        </div>
                        <!-- /.box-footer-->
                    </form>
                @endif
                @if($insertOrEditCategory=='edit')
                    @foreach($category as $value)
                    <form id="setting-city-form" name="setting_city_form" action="{{ url('setting/performance/category/update', [$value->id]) }}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Category</label>                                        
                                        <select type="text" id="category_type_id" class="form-control" name="category_type_id" aria-required="true">
                                            <option >---Select Category---</option>                                            
                                            @foreach($performancetype as $type)
                                                @if($value->category_type_id == $type->id)
                                                <option value="{{$type->id}}" selected>{{$type->performance_type}}</option>
                                                @else    
                                                <option value="{{$type->id}}">{{$type->performance_type}}</option>
                                                @endif
                                            @endforeach 
                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('category_type_id'))
                                                <strong>{{ $errors->first('category_type_id') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Category Name</label>
                                        <input type="text" id="name" value="{{$value->category_name}}" class="form-control" name="category_name" placeholder="Category Name" aria-required="true">
                                        <div class="help-block">
                                            @if ($errors->has('category_name'))
                                                <strong>{{ $errors->first('category_name') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Mandatory</label>                                        
                                        <input type="checkbox" id="is_mandatory" name="is_mandatory" value="1" 
                                        @if ($value->is_mandatory == 1)
                                            checked
                                        @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Update</button>
                            <button type="reset" class="btn btn-default btn-create">Reset</button>
                        </div>
                        <!-- /.box-footer-->
                    </form>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-square"></i> Activity</h3>
                </div>
                @if($insertOrEditActivity=='insert')
                    <form id="setting-city-form" name="setting_city_form" action="{{url('setting/performance/activity/create')}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Activity Name</label>
                                        <input type="text" id="activity_name"  class="form-control" name="activity_name" placeholder="Category Name" aria-required="true">
                                        <div class="help-block">
                                            @if ($errors->has('activity_name'))
                                                <strong>{{ $errors->first('activity_name') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Category</label>                                        
                                        <select type="text" id="cadet_category_id" class="form-control" name="cadet_category_id" aria-required="true">
                                            <option >---Select Category---</option>
                                            @foreach($performanceCategory as $Category)    
                                                <option value="{{$Category->id}}">{{$Category->category_name}}</option>
                                            @endforeach    
                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('cadet_category_id'))
                                                <strong>{{ $errors->first('cadet_category_id') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Create</button>
                            <button type="reset" class="btn btn-default btn-create">Reset</button>
                        </div>
                        <!-- /.box-footer-->
                    </form>
                @endif
                @if($insertOrEditActivity=='edit')
                    @foreach($activity as $value)
                    <form id="setting-city-form" name="setting_city_form" action="{{ url('setting/performance/activity/update', [$value->id]) }}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Activity Name</label>
                                        <input type="text" id="activity_name" value="{{$value->activity_name}}"  class="form-control" name="activity_name" placeholder="Category Name" aria-required="true">
                                        <div class="help-block">
                                            @if ($errors->has('activity_name'))
                                                <strong>{{ $errors->first('activity_name') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Category</label>                                        
                                        <select type="text" id="cadet_category_id" class="form-control" name="cadet_category_id" aria-required="true">
                                            @foreach($performanceCategory as $Category)
                                                @if($value->cadet_category_id == $Category->id)
                                                <option value="{{$Category->id}}" selected>{{$Category->category_name}}</option>
                                                @else    
                                                <option value="{{$Category->id}}">{{$Category->category_name}}</option>
                                                @endif
                                            @endforeach    
                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('cadet_category_id'))
                                                <strong>{{ $errors->first('cadet_category_id') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Update</button>
                            <button type="reset" class="btn btn-default btn-create">Reset</button>
                        </div>
                        <!-- /.box-footer-->
                    </form>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i>Type List</h3>
                </div>
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <div id="w1" class="grid-view">
        
                            <table id="myTable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a  data-sort="sub_master_name">Category Name</a></th>
                                    <th><a>Action</a></th>
                                </tr>
        
                                </thead>
                                <tbody>
                                    @if(isset($performancetype))
                                        @php
                                            $i = 1
                                        @endphp
                                        @foreach($performancetype as $per)
                                            <tr class="gradeX">
                                                <td>{{$i++}}</td>
                                                <td>{{$per->performance_type}}</td>
                                                <td>
                                                    {{--<a href="{{ route('--}}
                                                    {{---currency', $values->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}

                                                    <a href="{{ url('setting/performance/type/edit', $per->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                                    <a href="{{ url('setting/performance/type/delete', $per->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>                                                    
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif      
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i>Category List</h3>
                </div>
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <div id="w1" class="grid-view">
        
                            <table id="myTable1" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a  data-sort="sub_master_name">Category Type</a></th>
                                    <th><a  data-sort="sub_master_name">Category Name</a></th>
                                    <th><a>Action</a></th>
                                </tr>
        
                                </thead>
                                <tbody>
                                    @if(isset($performanceCategory))
                                        @php
                                            $i = 1
                                        @endphp
                                        @foreach($performanceCategory as $per)
                                            <tr class="gradeX">
                                                <td>{{$i++}}</td>
                                                <td>{{$per->PerformanceType()->performance_type}}</td>
                                                <td>{{$per->category_name}}</td>
                                                <td>
                                                    {{--<a href="{{ route('--}}
                                                    {{---currency', $values->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}

                                                    <a href="{{ url('setting/performance/category/edit', $per->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                                    <a href="{{ url('setting/performance/category/delete', $per->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>                                                    
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif      
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i>Activity List</h3>
                </div>
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <div id="w1" class="grid-view">
        
                            <table id="myTable2" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a  data-sort="sub_master_name">Activity Name</a></th>
                                    <th><a>Category</a></th>
                                    <th><a>Action</a></th>
                                </tr>
        
                                </thead>
                                <tbody>
                                    @if(isset($PerformanceActivity))
                                        @php
                                            $i = 1
                                        @endphp
                                        @foreach($PerformanceActivity as $act)
                                            <tr class="gradeX">
                                                <td>{{$i++}}</td>
                                                <td>{{$act->activity_name}}</td>
                                                <td>{{$act->selectCategory()->category_name}}</td>
                                                <td>
                                                    <a href="{{ url('setting/performance/activity/edit', $act->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                                    <a href="{{ url('setting/performance/activity/point', $act->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="delete" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-list-ol"></i></a>                                                    
                                                    <a href="{{ url('setting/performance/activity/delete', $act->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
    
    
    <!-- /.box-->
@endsection

@section('page-script')

    $('#myTable1').DataTable();
    $('#myTable2').DataTable();
    $('#myTable').DataTable();
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
            $(this).remove();
        });
    });


    // validate  form on keyup and submit
    $("#setting-city-form").validate({
    rules: {
    name: "required",
    country_id: "required",
    state_id: "required"
    },
    messages: {
    name: "Please enter city name",
    country_id: "Please enter country name",
    state_id: "Please enter state name"
    }
    });

    $("#setting-city-form-edit").validate({
    rules: {
    name: "required",
    country_id: "required",
    state_id: "required"
    },
    messages: {
    name: "Please enter city name",
    country_id: "Please enter country name",
    state_id: "Please enter state name"
    }
    });
@endsection


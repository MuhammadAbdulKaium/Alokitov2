@extends('setting::layouts.master')

@section('section-title')

    <h1>
        <i class="fa fa-th-list"></i> Manage  |<small>State/Province</small>
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Academics</a></li>
        <li class="active">Course Management</li>
        <li class="active">State/Province</li>
    </ul>

@endsection

@section('page-content')
    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @endif
    </div>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Academic Level</h3>
        </div>
        @if($insertOrEdit=='insert')
            <form id="setting-state-form" name="setting_state_form" action="{{url('setting/store-state')}}" method="post">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group field-subjectmaster-sub_master_name required">
                                <label class="control-label" for="academics_year_id">Country</label>
                                <select  id="country_id" class="form-control" name="country_id"  maxlength="60"  aria-required="true">
                                    <option></option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}">{{$country->name}}</option>

                                    @endforeach
                                </select>
                            </div>
                            <div class="help-block">
                                @if ($errors->has('country_id'))
                                    <strong>{{ $errors->first('country_id') }}</strong>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group field-subjectmaster-sub_master_name required">
                                <label class="control-label" for="state_province">State/Province</label>
                                <input type="text" id="name" class="form-control" name="name" maxlength="60" placeholder="State/Province" aria-required="true">
                                <div class="help-block">
                                    @if ($errors->has('name'))
                                        <strong>{{ $errors->first('name') }}</strong>
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
        @if($insertOrEdit=='edit')
            @foreach($editdata as $value)
                <form id="setting-state-form-edit" action="{{ url('setting/state-edit-perform', [$value->id]) }}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group field-subjectmaster-sub_master_name required">
                                    <label class="control-label" for="country_id">Country</label>
                                    <select type="text" id="country_id" class="form-control" name="country_id" maxlength="60"  aria-required="true">
                                        <option value="{{$value->country_id}}">{{$value->country()->name}}</option>
                                        @foreach($countries as $country)


                                            <option value="{{$country->country_id}}">{{$country->name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="help-block">
                                    @if ($errors->has('country_id'))
                                        <strong>{{ $errors->first('country_id') }}</strong>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group field-subjectmaster-sub_master_name required">
                                    <label class="control-label" for="state_province">State/Province</label>
                                    <input type="text" id="name" value="{{$value->name}}" class="form-control" name="name" maxlength="60" placeholder="State/Province" aria-required="true">
                                    <div class="help-block">
                                        @if ($errors->has('name'))
                                            <strong>{{ $errors->first('name') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-create">Update</button>
                        <a class="btn btn-default btn-create" href="{{url('setting/state') }}" >Cancel</a>
                    </div>
                    <!-- /.box-footer-->
                </form>
            @endforeach
        @endif
    </div>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> View State/Province List</h3>
        </div>
        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="myTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><a  data-sort="sub_master_name">Country</a></th>
                            <th><a  data-sort="sub_master_name">State/Province</a></th>
                            <th><a>Action</a></th>
                        </tr>
                        </thead>
                        <tbody>

                        @if(isset($states))
                            @php
                                $i=1;
                            @endphp
                            @foreach($states as $state)
                                <tr class="gradeX">
                                    <td>{{$i++}}</td>
                                    <td>{{$state->country()->name}}</td>
                                    <td>{{$state->name}}</td>
                                    <td>
                                        <a href="{{ url('setting/edit-state', $state->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                        <a href="{{ url('setting/delete-state', $state->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        {{--{{ $data->render() }}--}}

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box-->
@endsection

@section('page-script')

    function modalLoad(rowId) {

    var data = rowId.split('_'); //To get the row id
    $_token = "{{ csrf_token() }}";
    $.ajax({
    headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
    url: "{{ url('academics/view-academic-level') }}" + '/' + data[3],
    type: 'GET',
    cache: false,
    data: {'_token': $_token}, //see the $_token
    datatype: 'html',

    beforeSend: function () {
    },

    success: function (data) {

    // alert(data.length);
    //                    $('.modal-content').html(data);
    if (data.length > 0) {
    // remove modal body
    $('.modal-body').remove();
    // add modal content
    $('.modal-content').html(data);
    } else {
    // add modal content
    $('.modal-content').html('info');
    }
    }
    });

    }

    $('#myTable').DataTable();

    var year = 0;
    if(year.length == 0 || year == 0)
    {
    year = 0;
    }


    jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
    $(this).slideUp('slow', function() {
    $(this).remove();
    });
    });


    // validate  form on keyup and submit
    $("#setting-state-form").validate({
    rules: {
    country_id: "required",
    state_province: "required",

    },
    messages: {
    country_id: "Please enter country name",
    state_province: "Please enter state/province name",

    }
    });

@endsection


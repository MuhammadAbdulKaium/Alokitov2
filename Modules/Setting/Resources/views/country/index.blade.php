@extends('setting::layouts.master')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
@section('section-title')

    <h1>
        <i class="fa fa-th-list"></i> Manage  |<small>Counrty</small>
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Setting</a></li>
        <li class="active">Configuration</li>
        <li class="active">Country Name</li>
    </ul>

@endsection

@section('page-content')
    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @endif
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Country</h3>
            </div>
            @if($insertOrEdit=='insert')
                <form id="setting-country-form" name="setting_country_form" action="{{url('setting/store-country')}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group field-subjectmaster-sub_master_name required">
                                    <label class="control-label" for="name">Country Name</label>
                                    <input type="text" id="name" class="form-control" name="name" maxlength="60" placeholder="Enter Country Name" aria-required="true">
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
                    <form id="setting-country-form" action="{{ url('setting/edit-country-perform', [$value->id]) }}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group field-subjectmaster-sub_master_name required">
                                        <label class="control-label" for="name">Country Name</label>
                                        <input type="text" id="name" value="{{$value->name}}" class="form-control" name="academic_level_name" maxlength="60" placeholder="Academic Lecvel Name" aria-required="true">
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
                            <a class="btn btn-default btn-create" href="{{url('setting/country') }}" >Cancel</a>
                        </div>
                        <!-- /.box-footer-->
                    </form>
                @endforeach
            @endif
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> View Academic Level List</h3>
            </div>
            <div class="box-body table-responsive">
                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                    <div id="w1" class="grid-view">

                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><a  data-sort="sub_master_name">Country Name</a></th>
                                <th><a  data-sort="sub_master_name">Nationality</a></th>


                                <th><a>Action</a></th>
                            </tr>

                            </thead>
                            <tbody>

                            @if(isset($data))
                                @php
                                    $i = 1
                                @endphp
                                @foreach($data as $values)
                                    <tr class="gradeX">
                                        <td>{{$i++}}</td>
                                        <td>{{$values->name}}</td>
                                        <td>{{$values->nationality}}</td>
                                        <td>
                                            {{--<a href="{{ route('--}}
                                            {{---currency', $values->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}

                                            <a href="{{ url('setting/edit-country', $values->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                            <a href="{{ url('setting/delete-country', $values->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
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

        @endsection

        @section('page-script')

            $('#myTable').DataTable();
            });
            jQuery(document).ready(function() {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
            $(this).remove();
            });
            });

            // validate  form on keyup and submit
            $("#setting-country-form").validate({
            rules: {
            name: "required",
            },
            messages: {
            academic_level_name: "Please enter country name",

            }
            });


@endsection


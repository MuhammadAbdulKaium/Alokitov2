@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage  |<small>Subject</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Academics</a></li>
                <li class="active">Course Management</li>
                <li class="active">Subject</li>
            </ul>
        </section>

        <section class="content"> 

            <div id="p0">
                @if(Session::has('message'))
                    <p class="alert alert-success alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{Session::get('message')}}</p> 
                @endif
            </div>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Subject</h3>
                </div>
                @if($insertOrEdit=='insert')
                    <form id="subject-master-form" action="{{url('academics/store-subject')}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group field-subjectmaster-sub_master_name required">
                                        <label class="control-label" for="subject_name">Subject Name</label>
                                        <input type="text" id="subject_name" class="form-control" name="subject_name" maxlength="60" placeholder="Enter Subject Name" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group field-subjectmaster-sub_master_code required">
                                        <label class="control-label" for="subject_code">Subject Code</label>
                                        <input type="text" id="subject_code" class="form-control" name="subject_code" maxlength="10" placeholder="Enter Subject Code" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group field-subjectmaster-sub_master_alias required">
                                        <label class="control-label" for="subject_alias">Subject Alias</label>
                                        <input type="text" id="subject_alias" class="form-control" name="subject_alias" maxlength="10" placeholder="Enter Subject Alias" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group field-subjectmaster-sub_master_alias required">
                                        <label class="control-label" for="subject_alias">Group</label>
                                        <select name="division" id="" class="form-control" aria-required="true">
                                            @foreach ($divisionList as $division)
                                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
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
                        <form id="subject-master-form" action="{{ url('academics/edit-subject-perform', [$value->id]) }}" method="post">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group field-subjectmaster-sub_master_name required">
                                            <label class="control-label" for="subject_name">Subject Name</label>
                                            <input type="text" id="subject_name" value="{{$value->subject_name}}" class="form-control" name="subject_name" maxlength="60" placeholder="Enter Subject Name" aria-required="true">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group field-subjectmaster-sub_master_code required">
                                            <label class="control-label" for="subject_code">Subject Code</label>
                                            <input type="text" id="subject_code" value="{{$value->subject_code}}" class="form-control" name="subject_code" maxlength="10" placeholder="Enter Subject Code" aria-required="true">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group field-subjectmaster-sub_master_alias required">
                                            <label class="control-label" for="subject_alias">Subject Alias</label>
                                            <input type="text" id="subject_alias" value="{{$value->subject_alias}}" class="form-control" name="subject_alias" maxlength="10" placeholder="Enter Subject Alias" aria-required="true">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary btn-create">Update</button>
                                <a class="btn btn-default btn-create" href="{{ route('subject') }}" >Cancel</a>
                            </div>
                            <!-- /.box-footer-->
                        </form>
                    @endforeach
                @endif
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> View Subject List</h3>
                </div>
                <div class="box-body table-responsive">

                    <div id="w1" class="grid-view">
                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><a  data-sort="sub_master_name">Subject Name</a></th>
                                <th><a  data-sort="sub_master_code">Subject Code</a></th>
                                <th><a  data-sort="sub_master_alias">Subject Alias</a></th>
                                <th><a  data-sort="sub_master_alias">Group Name</a></th>

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
                                        <td>{{$values->subject_name}}</td>
                                        <td>{{$values->subject_code}}</td>
                                        <td>{{$values->subject_alias}}</td>
                                        <td>{{$values->division->name}}</td>

                                        <td>
                                            <a href="{{ route('edit-subject', $values->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                            <a href="{{ route('delete-subject', $values->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
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

            <!-- /.box-->
        </section>
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
{{----}}
@section('scripts')
    <script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            $('#myTable').DataTable();
        });


        jQuery(document).ready(function() {

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });

        });

        $().ready(function() {
            // validate signup form on keyup and submit
            $("#subject-master-form").validate({
                rules: {
                    subject_name: "required",
                    subject_code: "required",
                    subject_alias:"required",


                },
                messages: {
                    subject_name: "Please enter subject name",
                    subject_code: "Please enter subject code",
                    subject_alias:"Please enter subject alias",


                }
            });
        });
    </script>
@endsection

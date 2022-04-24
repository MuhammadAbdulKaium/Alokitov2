@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage  |<small>Academic Level</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Academics</a></li>
                <li class="active">Course Management</li>
                <li class="active">Academic Level</li>
            </ul>
        </section>
        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
            @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide dism " style="text-align: center">
                    <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}
                </p>
            @endif
            @if(Session::has('error'))
                <p class="alert alert-danger alert-auto-hide dism " style="text-align: center">
                    <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('error') }}
                </p>
            @endif
        </div>
        <section class="content">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Academic Level</h3>
                </div>
                @if($insertOrEdit=='insert')
                    <form id="academic-level-form" name="academic_level_form" action="{{url('academics/store-academic-level')}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group required">
                                        <label class="control-label" for="level_name">Academic Level Name</label>
                                        <input type="text" id="level_name" class="form-control" name="level_name" maxlength="60" placeholder="Academic Level Name" aria-required="true">
                                        <div class="help-block">
                                            @if ($errors->has('year_name'))
                                                <strong>{{ $errors->first('level_name') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group required">
                                        <label class="control-label" for="level_code">Academic Level Code</label>
                                        <input type="text" id="level_code" class="form-control" name="level_code" maxlength="10" placeholder="Academic Level Code" aria-required="true">
                                        <div class="help-block">
                                            @if ($errors->has('level_code'))
                                                <strong>{{ $errors->first('level_code') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group required">
                                        <label class="control-label" for="level_type">Academic Level Type</label>
                                        <select id="level_type" class="form-control" name="level_type" required>
                                            <option value="" selected disabled>--- Select Academic Type ---</option>
                                            <option value="0">School</option>
                                            <option value="1">College</option>
                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('level_type'))
                                                <strong>{{ $errors->first('level_type') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group required">
                                        <label class="control-label" for="is_active">Status</label>
                                        <select id="is_active" class="form-control" name="is_active" aria-required="true">
                                            <option value="" selected disabled>--- Select Academic Level ---</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('is_active'))
                                                <strong>{{ $errors->first('is_active') }}</strong>
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
                        <form id="academic-level-form" action="{{ url('academics/edit-academic-level-perform', [$value->id]) }}" method="post">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group required">
                                            <label class="control-label" for="level_name">Academic Level Name</label>
                                            <input type="text" id="level_name" value="{{$value->level_name}}" class="form-control" name="level_name" maxlength="60" placeholder="Academic Lecvel Name" aria-required="true">
                                            <div class="help-block">
                                                @if ($errors->has('level_name'))
                                                    <strong>{{ $errors->first('level_name') }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group required">
                                            <label class="control-label" for="level_code">Academic Level Code</label>
                                            <input type="text" id="level_code" value="{{$value->level_code}}" class="form-control" name="level_code" maxlength="10" placeholder="Academic Level Code" aria-required="true">
                                            <div class="help-block">
                                                @if ($errors->has('level_code'))
                                                    <strong>{{ $errors->first('level_code') }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group required">
                                            <label class="control-label" for="level_type">Academic Level Type</label>
                                            <select id="level_type" class="form-control" name="level_type" required>
                                                <option value="" selected disabled>--- Select Academic Type ---</option>
                                                <option value="0" {{$value->level_type==0?'selected':''}}>School</option>
                                                <option value="1" {{$value->level_type==1?'selected':''}}>College</option>
                                            </select>
                                            <div class="help-block">
                                                @if ($errors->has('level_type'))
                                                    <strong>{{ $errors->first('level_type') }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group required">
                                            <label class="control-label" for="status">Status</label>
                                            <select id="is_active" class="form-control" name="is_active" aria-required="true">
                                                <option value="" selected disabled>--- Select Status ---</option>
                                                <option value="1" {{$value->is_active==1?'selected':''}}>Active</option>
                                                <option value="0" {{$value->is_active==0?'selected':''}}>Inactive</option>
                                            </select>
                                            <div class="help-block">
                                                @if ($errors->has('is_active'))
                                                    <strong>{{ $errors->first('is_active') }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary btn-create">Update</button>
                                <a class="btn btn-default btn-create" href="{{url('academics/academic-level') }}" >Cancel</a>
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

                            <table id="myTable" class="table table-striped table-bordered text-center">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a data-sort="sub_master_name">Academic Level Name</a></th>
                                    <th><a data-sort="sub_master_code">Academic Level Code</a></th>
                                    <th><a data-sort="sub_master_code">Academic Level Type</a></th>
                                    <th><a  data-sort="sub_master_alias">Status</a></th>
                                    <th><a>Action</a></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($academicLevels))
                                    @php
                                        $i = 1
                                    @endphp
                                    @foreach($academicLevels as $academicLevel)
                                        <tr class="gradeX">
                                            <td>{{$i++}}</td>
                                            <td>{{$academicLevel->level_name}}</td>
                                            <td>{{$academicLevel->level_code}}</td>
                                            <td>{{$academicLevel->level_type==0?'School':'College'}}</td>
                                            <td>
                                                @if($academicLevel->is_active==1) <i class="fa fa-check"></i>@endif
                                                @if($academicLevel->is_active==0) <i class="fa fa-times"></i>@endif
                                            </td>
                                            <td>
                                                {{--<a href="{{ route('--}}
                                                {{---currency', $values->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}
                                                <a href="" class="btn btn-primary btn-xs" id="academic_level_view_{{$academicLevel->id}}" onclick="modalLoad(this.id)" data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>
                                                <a href="{{ url('academics/edit-academic-level', $academicLevel->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                                <a href="{{ url('academics/delete-academic-level', $academicLevel->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
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
        </section>
        <!-- Modal  -->
        <div class="modal fade" id="etsbModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
        </div>
    </div>
    <div id="slideToTop"><i class="fa fa-chevron-up"></i></div>

    <div class="modal" id="globalModal" tabindex="-1" role="dialog"  aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog" >
            <div class="modal-content" >
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

<!-- TO load view of each row -->
@section('scripts')
    <script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>
    <script type = "text/javascript">
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
    </script>
    <script type="text/javascript">

        jQuery(document).ready(function () {
            var year = 0;
            if(year.length == 0 || year == 0)
            {
                year = 0;
            }
        });

    </script>
    <script type="text/javascript">
        $(document).ready(function(){
//        alert();
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
            // validate  form on keyup and submit
            $("#academic-level-form").validate({
                rules: {
                    academics_year_id: "required",
                    level_name: "required",
                    level_code: "required",

                    is_active: "required",
                },
                messages: {
                    academics_year_id: "Please enter academic level name",
                    level_name: "Please enter academic level name",
                    level_code: "Please enter academic level code",

                    is_active: "Please enter status",
                }
            });
        });

    </script>

@endsection


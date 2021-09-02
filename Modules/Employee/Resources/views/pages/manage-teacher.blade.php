
@extends('layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>Teacher</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/hr/">Human Resource</a></li>
                <li><a href="/employee">Teacher Management</a></li>
                <li class="active">Manage Teacher</li>
            </ul>
        </section>
        <section class="content">
            {{--sesssion msg area--}}
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('alert'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif
            {{--teacher search area--}}
            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Search Teacher</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="{{url('/employee/create')}}"><i class="fa fa-plus-square"></i> Add</a>
                        </div>
                    </div>
                </div>
                <form id="manage_teacher_form">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="category" value="1">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="department">Department</label>
                                    <select id="department" class="form-control department" name="department">
                                        <option value="">--- Select Department ---</option>
                                        @if($allDepartments)
                                            @foreach($allDepartments as $department)
                                                <option value="{{$department->id}}">{{$department->name}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="designation">Designation</label>
                                    <select id="designation" class="form-control designation" name="designation">
                                        <option value="">--- Select Designation ---</option>
                                        @if($allDesignaitons)
                                            @foreach($allDesignaitons as $designation)
                                                <option value="{{$designation->id}}">{{$designation->name}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label" for="email">Email</label>
                                <div class="form-group">
                                    <input id="email" class="form-control" name="email" placeholder="Enter Email ID." type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label" for="emp_id">Employee ID</label>
                                <div class="form-group">
                                    <input id="emp_id" class="form-control" name="emp_id" placeholder="Enter Employee ID" type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button id="teacher_list_search_btn" type="submit" class="btn btn-primary pull-right">Search</button>
                        <button type="reset" class="btn btn-default pull-left">Reset</button>
                    </div>
                </form>
            </div>

            {{--teacher list container row--}}
            <div id="teacher_list_container">
                {{--teacer list will be here--}}
            </div>
        </section>
    </div>

@stop

@section('scripts')
    <script>
        jQuery(document).ready(function () {

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });


            // request for parent list using batch section id
            $('form#manage_teacher_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                $.ajax({
                    url: '/employee/manage/teacher',
                    type: 'POST',
                    cache: false,
                    data: $('form#manage_teacher_form').serialize(),
                    datatype: 'html',
                    // datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // refresh attendance container row
                        $('#teacher_list_container').html('');
                        $('#teacher_list_container').append(data);
                    },
                    error:function(){
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            });

            // request for section list using batch id
            jQuery(document).on('change','.department',function(){
                // get academic level id
                var dept_id = $(this).val();
                var op="";

                // checking
                if(dept_id){
                    // ajax request
                    $.ajax({
                        url: '/employee/find/designation/list/'+dept_id,
                        type: 'GET',
                        cache: false,
                        datatype: 'application/json',

                        beforeSend: function() {
                            //
                        },

                        success:function(data){
                            // refresh attendance container row
                            $('#teacher_list_container').html('');
                        },
                        error:function(){
                            // sweet alert
                            swal("Error", 'Unable to load data form server', "error");
                        }
                    });
                }
            });
            // request for section list using batch id
            jQuery(document).on('change','.designation',function(){
                // get academic level id
                // refresh attendance container row
                $('#teacher_list_container').html('');
            });
        });

    </script>
@stop

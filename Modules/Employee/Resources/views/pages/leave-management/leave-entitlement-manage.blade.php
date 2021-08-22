
@extends('layouts.master')

@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>Leave Entitlement</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Human Resource</a></li>
                <li><a href="#">Leave Management</a></li>
                <li class="active">Leave Entitlement</li>
            </ul>
        </section>
        <section class="content">
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

            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i>  Search </h3>
                        <div class="box-tools">
                            {{--<a class="btn btn-primary btn-sm" href="#"><i class="fa fa-download"></i> Import Entitlement</a>                --}}
                            <a class="btn btn-success btn-sm" href="/employee/manage/leave/entitlement/create" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Add</a>
                        </div>
                    </div>
                </div>
                <form id="leave-entitlement-list-search-form" action="" method="POST">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="department">Department</label>
                                    <select id="department" class="form-control academicChange" name="department">
                                        <option value="">--- Select Department ---</option>
                                        @foreach($allDepartments as $department)
                                            <option value="{{$department->id}}">{{$department->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="designation">Designation</label>
                                    <select id="designation" class="form-control academicChange" name="designation">
                                        <option value="">--- Select Designation ---</option>
                                        @foreach($allDesignations as $designation)
                                            <option value="{{$designation->id}}">{{$designation->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="category">Category</label>
                                    <select id="category" class="form-control academicChange" name="category">
                                        <option value="">--- Select category ---</option>
                                        <option value="1">General</option>
                                        <option value="2">Employee</option>
                                        <option value="3">Department</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="structure">Leave Structure</label>
                                    <select id="structure" class="form-control academicChange" name="structure">
                                        <option value="">--- Select Structure ---</option>
                                        <option value="custom" class="bg-gray-active"> Custom Structure (Extended)</option>
                                        @foreach($allLeaveStructures as $structure)
                                            <option value="{{$structure->id}}">{{$structure->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="employee">Employee</label>
                                    <select id="employee" class="form-control select2 select2-hidden-accessible academicChange" style="width: 100%;" aria-hidden="true" name="employee">
                                        <option value="">--- Select Employee ---</option>
                                        @foreach($allEmployeeList as $employee)
                                            <option value="{{$employee->id}}">{{$employee->first_name." ".$employee->middle_name." ".$employee->last_name   }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ./box-body -->
                    <div class="box-footer">
                        <p class="pull-right">
                            <button type="reset" class="btn btn-default">Reset</button>
                            <button type="submit" class="btn btn-info">Search</button>
                        </p>
                    </div>
                </form>
            </div>

            <div id="leave-entitlement-list-container">
                {{-- leave-entitlement-list will be here --}}
            </div>


            <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" id="modal-content">
                        <div class="modal-body" id="modal-body">
                            <div class="loader">
                                <div class="es-spinner">
                                    <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ URL::asset('js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- datatable script -->
    <script>

        jQuery(document).ready(function () {

            //Initialize Select2 Elements
            $(".select2").select2();

            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

            // request for section list using batch and section id
            jQuery(document).on('change','.academicChange',function(){
                $('#leave-entitlement-list-container').html('');
            });


            // request for section list using batch and section id
            $('form#leave-entitlement-list-search-form').on('submit', function (e) {

                e.preventDefault();

                // ajax request
                $.ajax({
                    url: '/employee/manage/leave/entitlement/search',
                    type: 'GET',
                    cache: false,
                    data: $('form#leave-entitlement-list-search-form').serialize(),
                    datatype: 'html',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        var list_container =  $('#leave-entitlement-list-container');
                        list_container.html('');
                        list_container.append(data);
                    },

                    error:function(){
                        // statements
                    }
                });
            });
        });

    </script>
@endsection

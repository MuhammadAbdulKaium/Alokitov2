<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 8/16/17
 * Time: 2:13 PM
 */
?>
@extends('layouts.master')
@section('styles')
    <style>
        .menuDesign {
            margin: 1px;
        }
        .menuDesign:hover {
            background: #008d4c !important;
        }
    </style>
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/><!-- DataTables -->
    <link href="{{ URL::asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>

@endsection


@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Attendance</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Human Resource</a></li>
                <li><a href="#">Employee Management</a></li>
                <li class="active">Attendance</li>
            </ul>
        </section>

        <section class="content">
            <div class="box-body">
                <div class="row">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> View Attendance </h3>

                            <a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/add-attendance" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                Add Attendance</a>
                            <a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/upload-attendance" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                Upload Attendance File</a>
                            <a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/employee-attendance/today"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                Today's Attendance</a>
                            <a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/employee-monthly-attendance"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                Monthly Attendance</a>
                            <a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/employee-attendance"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                Attendance</a>
                            <a class="btn btn-success btn-sm pull-right menuDesign " href="{{url('/employee/employee-attendance/custom')}}">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Custom Attendance
                            </a>

                        </div>
                        <form id="attendanceStdSearchForm" method="GET" action="/employee/employee-attendance">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group ">
                                            <label class="control-label">Date</label>
                                            <input type="text" id="access_date" required autocomplete="off" class="form-control" name="access_date" value="@if(!empty($allInputs->search_due_date)) {{date('d-m-Y',strtotime($allInputs->search_due_date))}}  @endif"  placeholder="Start Date">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ./box-body -->
                            <div class="box-footer">
                                <button type="reset" class="btn btn-default">Reset</button>
                                <button type="submit" class="btn btn-success">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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

                <div class="box-body table-responsive">
                    <div class="box-header" style="text-align: center">
                        <p style="font-size: 18px">{{ date('l j, F Y', strtotime($requestDate))}} </p>
                        <form style="margin-top: -30px" id="employee-create" action="{{url('employee/attendance/report/download')}}" method="POST">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="date" value="{{$requestDate}}">
                            <button type="sumbit" name="report_type" value="pdf" class="btn btn-success menuDesign" style="float: right">Download PDF</button>
                            <button type="sumbit" name="report_type" value="excel" class="btn btn-success menuDesign" style="float: right">Download Excel</button>
                        </form>
                    </div>
                    <div class="box-body">
                        <table id="example1" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee </th>
                                <th>Emp ID </th>
                                <th>Department </th>
                                <th>Designation </th>
                                <th>Sign In Time </th>
                                <th>Sign Out Time </th>
                                <th>Working Hour</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!isset($empAttAll))
                                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h4><i class="icon fa fa-check"></i> No Attendance Data Found!!!</h4>
                                </div>
                            @else
                                @foreach($empAttAll as $index=>$data)
                                    <tr>
                                        <td>{{($index+1)}}</td>
                                        <td>{{$data->first_name.' '.$data->middle_name.' '.$data->last_name}}</td>
                                        <td>e_{{$data->user_id}}</td>
                                        <td>{{$data->department}}</td>
                                        <td>{{$data->designation}}</td>
                                        <td>{{$data->in_time?(date('h:i:s A',strtotime($data->in_time))): '-'}}</td>
                                        <td>{{$data->out_time?(date('h:i:s A', strtotime($data->out_time))): '-'}}</td>
                                        <td>
                                            {{--checking in_time and out time--}}
                                            @if(($data->in_time) AND ($data->out_time))
                                                @php
                                                    $inTime =  $from_time = new DateTime($data->in_time);
                                                    $outTime =  $from_time = new DateTime($data->out_time);
                                                    // difference checking
                                                    $workingTime = $inTime->diff($outTime)->format('%h hours %i Min');
                                                @endphp
                                                {{--pring working time--}}
                                                {{$workingTime}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </section>

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
    </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('js/moment.min.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- datatable script -->
    <script>
        $(function () {
            $('#access_date').datepicker({format: 'dd-mm-yyyy'});
            $("#example2").DataTable();
            $('#example1').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "pageLength": 50
            });
        });

        jQuery(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
        });
    </script>
@endsection
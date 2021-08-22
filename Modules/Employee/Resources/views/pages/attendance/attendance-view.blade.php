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
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
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
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> View Attendance </h3>

                    <a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/add-attendance" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        Add Attendance</a>
                    <a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/upload-attendance" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        Upload Attendance File</a>
                    <a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/employee-attendance/today" oncontextmenu="return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        Today's Attendance</a>
                    <a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/employee-monthly-attendance" oncontextmenu="return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        Monthly Attendance</a>
                    <a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/employee-attendance" oncontextmenu="return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        Attendance</a>
                    <a class="btn btn-success btn-sm pull-right menuDesign " href="{{url('/employee/employee-attendance/custom')}}">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Custom Attendance
                    </a>
                </div>
                <div class="box-body table-responsive">
                    <div class="box-header" style="text-align: center">
                        <p style="font-size: 18px">{{ date('l j, F Y')}} </p>
                    </div>
                    <div class="box-body">
                        <table id="example1" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee </th>
                                    <th>Department </th>
                                    <th>Designation </th>
                                    {{--<th>Shift Name </th>--}}
                                    <th>Start Date </th>
                                    <th>In Time </th>
                                    <th>Out Time </th>

                                    {{--<th>Action </th>--}}
                                </tr>
                            </thead>
                            <tbody>

                            @if(!isset($empAttAll))
                                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h4><i class="icon fa fa-check"></i> No Attendance Data Found!!!</h4>
                                </div>
                            @else
                            <?php $i = 1?>
                            @foreach($empAttAll as $data)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{ \Modules\Employee\Entities\EmployeeInformation::empName($data->registration_id)}}</td>
                                    <td>{{ \Modules\Employee\Entities\EmployeeInformation::empDept($data->registration_id)}}</td>
                                    <td>{{ \Modules\Employee\Entities\EmployeeInformation::empDesig($data->registration_id)}}</td>
{{--                                    <td>{{ EmployeeInformation::empShift($data->emp_id)}}</td>--}}
                                    <td>{{date('d/m/Y',strtotime($data->access_date))}}</td>
                                    <td>{{date('h:i:s A',strtotime($data->access_time))}}</td>
                                    <td>{{date('h:i:s A',strtotime($data->max_access_time))}}</td>
                                    {{--<td>
                                        <a href="/employee/emp-attendance/{{$data->id}}" title="Update" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><span class="glyphicon glyphicon-pencil"></span></a>
                                    </td>--}}
                                </tr>
                                <?php $i++?>
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
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
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
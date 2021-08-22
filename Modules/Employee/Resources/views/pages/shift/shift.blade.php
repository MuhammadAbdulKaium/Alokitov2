<?php
use Modules\Employee\Http\Controllers\MyHelper;
?>
@extends('layouts.master')
@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Shift</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Human Resource</a></li>
                <li><a href="#">Employee Management</a></li>
                <li class="active">Shift</li>
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
                    <h3 class="box-title"><i class="fa fa-search"></i> View Shift List </h3>
                    <a class="btn btn-success btn-sm pull-right" href="/employee/shift/create" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        Add Shift</a>
                </div>
                <div class="box-body table-responsive">
                    <div class="box-header">
                    </div>
                    <div class="box-body">
                        <table id="example1" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Shift Name</th>
                                <th>Shift Time</th>
                                <th>First Holiday</th>
                                <th>Second Holiday</th>
                                <th>Late In Time</th>
                                <th>Absent In Time</th>
                                <th>Lunch Time</th>
                                <th>Over Time </th>
                                <th>Extra Over Time</th>
                                <th>Early Out Time</th>
                                <th>Last Out Time</th>
                                <th>Late Day Allow</th>
                                <th>Out Time Grace</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1;?>
                            @foreach ($allShift as $shift)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$shift->shiftName}}</td>
                                    <td>{{date("g:i a", strtotime($shift->shiftStartTime))}} - {{date("g:i a", strtotime($shift->shiftEndTime))}}</td>
                                    <td>{{MyHelper::dayName($shift->firstHoliday)}}</td>
                                    <td>{{MyHelper::dayName($shift->secondHoliday)}}</td>
                                    <td><?php if(!empty($shift->lateInTime)) echo date("g:i a", strtotime($shift->lateInTime)) ?></td>
                                    <td><?php if(!empty($shift->absentInTime)) echo date("g:i a", strtotime($shift->absentInTime)) ?></td>
                                    <td><?php if(!empty($shift->lunchStartTime)) echo date("g:i a", strtotime($shift->lunchStartTime)) ?> - <?php if(!empty($shift->lunchEndTime)) echo date("g:i a", strtotime($shift->lunchEndTime)) ?></td>
                                    <td><?php if(!empty($shift->overTimeStart)) echo date("g:i a", strtotime($shift->overTimeStart)) ?> - <?php if(!empty($shift->overTimeEnd)) echo date("g:i a", strtotime($shift->overTimeEnd)) ?></td>
                                    <td><?php if(!empty($shift->extraOverTimeStart)) echo date("g:i a", strtotime($shift->extraOverTimeStart)) ?> - <?php if(!empty($shift->extraOverTimeEnd)) echo date("g:i a", strtotime($shift->extraOverTimeEnd)) ?></td>
                                    <td><?php if(!empty($shift->earlyOutTime)) echo date("g:i a", strtotime($shift->earlyOutTime)) ?></td>
                                    <td><?php if(!empty($shift->lastOutTime)) echo date("g:i a", strtotime($shift->lastOutTime)) ?></td>
                                    <td>{{$shift->lateDayAllow}}</td>
                                    <td>{{$shift->outTimeGrace}}</td>

                                    <td>
                                        <a href="/employee/shift/{{$shift->id}}" title="View" data-target="#globalModal" data-toggle="modal"><span class="glyphicon glyphicon-eye-open"></span></a>
                                        <a href="/employee/shift/edit/{{$shift->id}}" title="Update" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><span class="glyphicon glyphicon-pencil"></span></a>
                                        <a href="{{ url('/employee/shift/delete/'.$shift->id) }}" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="get"><span class="glyphicon glyphicon-trash"></span></a>
                                    </td>
                            @endforeach
                            </tfoot>
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
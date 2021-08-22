<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 10/24/17
 * Time: 12:41 PM
 */
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
                <i class="fa fa-th-list"></i> Manage |<small>Employee Overtime Entry</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Human Resource</a></li>
                <li><a href="#">Payroll</a></li>
                <li class="active">Employee Overtime</li>
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
                    <h3 class="box-title"><i class="fa fa-search"></i> View Employee Overtime </h3>
                    <a class="btn btn-success btn-sm pull-right" href="/employee/employee-over-time-entry/add" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i>
                        Add Employee Overtime</a>
                </div>
                <div class="box-body table-responsive">
                    <div class="box-header"></div>
                    <div class="box-body">
                        <?php $i=1;?>
                        <table id="example1" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Approve Date</th>
                                <th>Effective Month</th>
                                <th>OT Hours</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($empOtHour as $data)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$data->empName($data->employee_id)}}</td>
                                    <td>{{date('d-m-Y',strtotime($data->approve_date))}}</td>
                                    <td>{{DateTime::createFromFormat('!m', $data->effective_month)->format('M')}}, {{$data->effective_year}}</td>

                                    <td>{{$data->ot_hours}}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#globalModal" onclick="showData({{$data->id}})"><i class="fa fa-eye"></i></a>
                                        <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#globalModal" onclick="editData({{$data->id}})"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-info btn-sm" onclick="salaryAssignDelete({{$data->id}})"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td></td>
                            </tr>
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


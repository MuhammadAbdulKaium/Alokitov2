<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 9/21/17
 * Time: 1:36 PM
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
                <i class="fa fa-th-list"></i> Manage | <small>Salary Assign</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Human Resource</a></li>
                <li><a href="#">Employee Management</a></li>
                <li class="active">Salary Assign</li>
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
                    <h3 class="box-title"><i class="fa fa-search"></i> View Employee Salary </h3>
                    <a class="btn btn-success btn-sm pull-right" href="/payroll/emp-salary-assign/add" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i>
                        Assign Employee Salary </a>
                </div>
                <div class="box-body table-responsive">
                    <div class="box-header"></div>
                    <div class="box-body">
                        <table id="example1" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Employee Name</th>
                                    <th>Pay Scale</th>
                                    <th>Salary Amount</th>
                                    <th>Salary Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                            @foreach($empSalaryAssign as $data)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$data->empName($data->employee_id)}}</td>
                                    <td>{{$data->salaryStructureName($data->salary_structure_id)}}</td>{{--$data->salary_structure_id--}}
                                    <td>{{$data->salary_amount}}</td>
                                    <td>@if($data->salary_type == 'B'){{'Basic'}}@elseif('G') {{'Gross'}}@endif</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#globalModal" onclick="showData({{$data->id}})"><i class="fa fa-eye"></i></a>
                                        {{--<a class="btn btn-info btn-sm" data-toggle="modal" data-target="#globalModal" onclick="editData({{$data->id}})"><i class="fa fa-edit"></i></a>--}}
                                        <a class="btn btn-info btn-sm" onclick="salaryAssignDelete({{$data->id}})"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
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
    <!-- datatable script -->
    <script>
        function salaryAssignDelete(id) {
            var c = confirm("Are You sure, you want to delete?")
            if(c){
                var token = "{{ csrf_token() }}";
                var dataSet = '_token='+token+'&id='+id;
                $.ajax({
                    url: "{{ url('payroll/emp-salary-assign/delete')}}",
                    type: 'post',
                    data: dataSet,
                    beforeSend: function () {
                    }, success: function (data) {
                        alert(data);
                        location.reload();
                    }
                });
            }
        }

        function showData(rowId) {
            var token = "{{ csrf_token() }}";
            var dataSet = '_token='+token+'&id='+rowId;
            $.ajax({
                url: "{{ url('payroll/emp-salary-assign/show')}}",
                type: 'post',
                data: dataSet,
                beforeSend: function () {
                }, success: function (data) {
                    $('#globalModal').html(data);
                }
            });
        }

        function editData(rowId) {
            var token = "{{ csrf_token() }}";
            var dataSet = '_token='+token+'&id='+rowId;
            $.ajax({
                url: "{{ url('payroll/emp-salary-assign/edit')}}",
                type: 'post',
                data: dataSet,
                beforeSend: function () {
                }, success: function (data) {
                    $('#globalModal').html(data);
                }
            });
        }

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
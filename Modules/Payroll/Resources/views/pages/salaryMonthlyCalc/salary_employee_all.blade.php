<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 11/19/17
 * Time: 4:09 PM
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
                <i class="fa fa-th-list"></i> Employee |<small>Salary</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Human Resource</a></li>
                <li><a href="#">Payroll</a></li>
                <li class="active">Employee Salary</li>
            </ul>
        </section>

        <section class="content">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> View Employee Salary</h3>
                </div>
                <div class="box-body table-responsive">
                    <div class="box-header">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="month">Month: </label>
                                    <select class="form-control" id="month">
                                        @for($i=1;$i<=12;$i++)
                                            <option value="{{$i}}"  @if(date('m') == $i) {{'selected'}}@endif>{{date('F', mktime(0, 0, 0, $i, 10))}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="year">Year: </label>
                                    <select class="form-control" id="year">
                                        @for($i=2017;$i<=date('Y');$i++)
                                            <option value="{{$i}}" @if(date('Y') == $i) {{'selected'}}@endif>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <a class="btn btn-success btn-sm pull-right" id="search">
                                    Search
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                    <div class="box-body" id="empSalaryMonthly">
                        <?php $i=1;?>
                        <table id="example2" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Month Year</th>
                                <th>Total Salary</th>
                                <th>Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($salaryEmployeeMonthly as $data)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{\Modules\Payroll\Entities\EmpSalaryAssign::empName($data->employee_id)}}</td>
                                    <td>{{date('F', mktime(0, 0, 0, $month, 10))}}, {{$year}}</td>
                                    <td>{{$data->amount}}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#globalModal" onclick="showData({{$data->employee_id}},{{$month}},{{$year}})"><i class="fa fa-eye"></i></a>
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

        function showData(id,month,year) {
            var token = "{{ csrf_token() }}";
            var dataSet = '_token='+token+'&id='+id+'&month='+month+'&year='+year
            $.ajax({
                url: "{{url('payroll/emp-salary/single_emp_monthly_sal')}}",
                type: 'post',
                data: dataSet,
                beforeSend: function () {
                }, success: function (data) {
                    $('#globalModal').html(data);
                }
            });
        }
        $('#search').click(function () {
            var month = $('#month').val();
            var year = $('#year').val();
            var token = "{{ csrf_token() }}";
            var dataSet = '_token='+token+'&month='+month+'&year='+year;
            $.ajax({
                url: "{{url('payroll/emp-salary/emp_monthly_sal')}}",
                type: 'post',
                data: dataSet,
                beforeSend: function () {
                }, success: function (data) {
                    $('#empSalaryMonthly').html(data);
                }
            });
        });
    </script>
@endsection

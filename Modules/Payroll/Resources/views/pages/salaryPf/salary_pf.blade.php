<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 10/8/17
 * Time: 3:19 PM
 */?>
@extends('layouts.master')
@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Provident fund Rules</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Human Resource</a></li>
                <li><a href="#">Payroll</a></li>
                <li class="active">Provident fund Rules</li>
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
                    <h3 class="box-title"><i class="fa fa-search"></i> View Provident fund Rules </h3>
                    <a class="btn btn-success btn-sm pull-right" href="/payroll/pf-rules/add" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i>
                        Add Provident fund Rules</a>
                </div>
                <div class="box-body table-responsive">
                    <div class="box-header"></div>
                    <div class="box-body">
                        <?php $i=1;?>
                        <table id="example2" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Pf type</th>
                                <th>Amount val</th>
                                <th>Com. Contribution</th>
                                <th>Min Time</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($SalaryPfRule as $data)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$data->pfType()}}</td>
                                    <td>{{$data->amt_val}}
                                        @if($data->pf_ded_type =='P'){{' %'}}
                                            @if($data->pf_ded_from=='G') {{' of Gross'}}
                                            @else {{' of Basic'}}
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{$data->comp_contribute}}
                                        @if($data->comp_contribute_type == 'B'){{' % of Basic'}}
                                        @elseif($data->comp_contribute_type == 'G'){{' % of Gross'}}
                                        @elseif($data->comp_contribute_type == 'V'){{' % of '}}{{$data->amt_val}}
                                        @endif
                                    </td>
                                    <td>{{$data->min_eligable_time}} Months</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#globalModal" onclick="showData({{$data->id}})"><i class="fa fa-eye"></i></a>
                                        {{--<a class="btn btn-info btn-sm" data-toggle="modal" data-target="#globalModal" onclick="editData({{$data->id}})"><i class="fa fa-edit"></i></a>--}}
                                        <a class="btn btn-info btn-sm" onclick="pfDelete({{$data->id}})"><i class="fa fa-trash"></i></a>
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

        function showData(id) {
            var token = "{{ csrf_token() }}";
            var dataSet = '_token='+token+'&id='+id;
            $.ajax({
                url: "{{url('payroll/pf-rules/show')}}",
                type: 'post',
                data: dataSet,
                beforeSend: function () {
                }, success: function (data) {
                    $('#globalModal').html(data);
                }
            });
        }
        function editData(id) {
            alert(id);
        }
        function pfDelete(id) {
            var c = confirm("Are You sure, you want to delete?")
            if(c) {
                var token = "{{ csrf_token() }}";
                var dataSet = '_token=' + token + '&id=' + id;
                $.ajax({
                    url: "{{url('payroll/pf-rules/delete')}}",
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
    </script>
@endsection
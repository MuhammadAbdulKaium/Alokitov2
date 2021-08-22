@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage  |<small>Batch Grid</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Academics</a></li>
                <li class="active">Course Management</li>
                <li class="active">Subject</li>
            </ul>
        </section>

        <section class="content">

            <div id="p0">
                @if(Session::has('message'))
                    <p class="alert alert-success alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
                @elseif(Session::has('alert'))
                    <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
                @elseif(Session::has('errorMessage'))
                    <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
                @endif
            </div>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> Batch </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" placeholder="Batch">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="fromDate" class="form-control hasDatepicker from-date"
                                name="fromDate" maxlength="10" placeholder="From Date" aria-required="true"
                                size="10">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="toDate" class="form-control hasDatepicker to-date" name="toDate"
                                maxlength="10" placeholder="To Date" aria-required="true" size="10">
                        </div>
                        <div class="col-sm-3">
                            <select name="" id="" class="form-control">
                                <option value="">-- Select --</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-success">Search</button>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered" id="dataTable" style="margin-top: 20px">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="50">No Data Found</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
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



@section('scripts')
<script>
    $(document).ready(function() {
        $('#fromDate').datepicker();
        $('#toDate').datepicker();

        $('#dataTable').DataTable();

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
</script>   
@endsection

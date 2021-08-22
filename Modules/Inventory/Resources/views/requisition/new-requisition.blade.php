@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage  |<small>New Requisition</small>
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
                    <h3 class="box-title"><i class="fa fa-search"></i> New Requisition List </h3>
                    <div class="box-tools">
                        <a class="btn btn-success btn-sm" href=""><i class="fa fa-plus-square"></i> New</a></div>
                </div>
                <div class="box-body">
                    <form action="">
                        @csrf

                        <div class="row" style="margin-bottom: 30px">
                            <div class="col-sm-3">
                                <select name="" id="" class="form-control">
                                    <option value="">Voucher No</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-sm-2">
                                <select name="" id="" class="form-control">
                                    <option value="">Decimal Places</option>
                                </select>
                            </div>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-1">
                                <button class="btn btn-primary">Search</button>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-secondary"><i class="fa fa-print"></i> Print <i class="fa fa-caret-down"></i></button>
                            </div>
                        </div>
                    </form>
                    <form action="">
                        @csrf

                        <table class="table table-striped table-bordered" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Store Name</th>
                                    <th>Location</th>
                                    <th>Category</th>
                                    <th>Address</th>
                                    <th>Contact</th>
                                    <th>Tagging</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td>General Store</td>
                                    <td>ABCDEF</td>
                                    <td>General</td>
                                    <td></td>
                                    <td></td>
                                    <td><a href="">Tagging</a></td>
                                    <td>
                                        <a class="btn btn-primary btn-xs"
                                            href=""
                                            data-target="#globalModal" data-toggle="modal" data-modal-size="modal-sm"><i
                                                class="fa fa-edit"></i></a>
                                        <a href=""
                                            class="btn btn-danger btn-xs"
                                            onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                            data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-danger">Delete</button>
                    </form>
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
    $('#dataTable').DataTable();

    $(document).ready(function() {
        $('#fromDate').datepicker();
        $('#toDate').datepicker();

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
</script>   
@endsection

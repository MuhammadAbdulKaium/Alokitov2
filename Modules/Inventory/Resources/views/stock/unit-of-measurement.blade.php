@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage  |<small>Unit Of Measurement</small>
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
                @if ($errors->any())
                    <div class="alert alert-danger alert-auto-hide">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

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
                    <h3 class="box-title"><i class="fa fa-search"></i> Unit Of Measurement </h3>
                    <div class="box-tools">
                        <a class="btn btn-success btn-sm" href="{{ url('inventory/add/unit-of-measurement') }}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> New</a></div>
                </div>
                <div class="box-body">
                    <form action="">
                        @csrf

                        <table class="table table-striped table-bordered" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Symbol</th>
                                    <th>Formal Name</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($uom)
                                @foreach($uom as $unit)
                                <tr>
                                    <td>#</td>
                                    <td>{{$unit->symbol_name}}</td>
                                    <td>{{$unit->formal_name}}</td>
                                    <td>
                                        <a id="update-guard-data" class="btn btn-success" href="/inventory/edit/unit-of-measurement/{{$unit->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"> Edit</a>
                                        <a href="/inventory/delete/unit-of-measurement/{{$unit->id}}" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete" class="btn btn-danger">Delete</a>

                                    </td>
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
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

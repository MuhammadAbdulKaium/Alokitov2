@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Investigation</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Health Care</a></li>
            <li>SOP Setup</li>
            <li class="active">Investigation</li>
        </ul>
    </section>
    <section class="content">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Investigation List </h3>
                        <div class="box-tools">
                            <a class="btn btn-success" href="{{ url('/healthcare/create/investigation') }}"><i class="fa fa-plus-square"></i> Create New</a></div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered" id="investigationTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Report Type</th>
                                    <th>Title</th>
                                    <th>Sample</th>
                                    <th>Lab ID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($investiagtions as $investigation)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $investigation->report_type }}</td>
                                        <td>{{ $investigation->title }}</td>
                                        <td>{{ $investigation->sample }}</td>
                                        <td>{{ $investigation->lab_id }}</td>
                                        <td>
                                            <a href="{{ url('/healthcare/edit/investigation/'.$investigation->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                                            <a href="{{ url('/healthcare/delete/investigation/'.$investigation->id) }}"
                                                class="btn btn-danger btn-xs"
                                                onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                                data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel"
            aria-hidden="true">
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
    </section>
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('#investigationTable').DataTable();

        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
</script>
@stop
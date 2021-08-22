@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Information System |<small>Teacher & Staff</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Human Resource</a></li>
            <li>SOP</li>
            <li class="active">Employee Status</li>
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
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-plus-square"></i> Employee Status </h3>
                <div class="box-tools">
                    <a class="btn btn-success pull-right" href="{{url('employee/employee/status/create')}}" oncontextmenu="return false;"
                        data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i
                            class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        Create Employee Status</a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered" id="employeeStatusTable">
                    <thead>
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Employee Status</th>
                            <th scope="col">category</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($statuses as $status)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$status->status}}</td>
                            <td>
                                @if ($status->category == 1)
                                    Active
                                @elseif($status->category == 2)
                                    Inactive
                                @elseif($status->category == 3)
                                    Closed
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-primary btn-xs" href="{{url('/employee/employee/status/edit/'.$status->id)}}" oncontextmenu="return false;"
                                data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <a href="" class="btn btn-info btn-xs">Benefit</a>
                                <a href="{{url('/employee/employee/status/delete/'.$status->id)}}" class="btn btn-danger btn-xs"
                                    onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                    data-content="delete"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('#employeeStatusTable').DataTable();

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
</script>
@stop
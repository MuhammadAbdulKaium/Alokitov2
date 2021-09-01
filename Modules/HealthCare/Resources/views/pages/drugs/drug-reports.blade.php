@extends('layouts.master')

@section('styles')
    <style>
        .select2-selection--single{
            height: 33px !important;
        }
    </style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Drug Reports</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Health Care</a></li>
            <li>SOP Setup</li>
            <li class="active">Drug Reports</li>
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
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Drug Reports </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered" id="prescriptionTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Drug Name</th>
                                    <th>Drug Quantity</th>
                                    <th>Patient Type</th>
                                    <th>Patient Name</th>
                                    <th>ID</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Creator</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($drugReports as $drugReport)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $drugReport->drug->product_name }}</td>
                                        <td>{{ $drugReport->quantity }}</td>
                                        @if ($drugReport->patient_type == 1)
                                            <td>Student</td>
                                            <td>{{ $drugReport->cadet->first_name }} {{ $drugReport->cadet->last_name }}</td>
                                            <td>{{ $drugReport->cadet->id }}</td>
                                        @elseif($drugReport->patient_type == 2) 
                                            <td>HR/FM</td>
                                            <td>{{ $drugReport->employee->first_name }} {{ $drugReport->employee->last_name }}</td>
                                            <td>{{ $drugReport->employee->id }}</td>  
                                        @endif 
                                        <td>{{ $drugReport->created_at }}</td>
                                        <td>
                                            @if ($drugReport->status == 1)
                                                Pending
                                            @elseif($drugReport->status == 2)
                                                Delivered
                                            @endif
                                        </td>
                                        <td>{{ $drugReport->createdBy->name }}</td>
                                        <td>
                                            @if ($drugReport->status == 1)
                                                <a href="{{ url('/healthcare/drug/status/change/'.$drugReport->id.'/2') }}" class="btn btn-primary btn-xs">Deliver</a>
                                            @endif
                                            <a href="{{ url('/healthcare/edit/prescription/'.$drugReport->prescription->id) }}" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
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
        $('#prescriptionTable').DataTable();

        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        $('#select-user').select2();
    });
</script>
@stop
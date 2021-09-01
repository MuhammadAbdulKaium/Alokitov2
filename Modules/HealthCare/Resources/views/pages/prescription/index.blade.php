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
            <i class="fa fa-th-list"></i> Manage |<small>Prescription</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Health Care</a></li>
            <li>SOP Setup</li>
            <li class="active">Prescription</li>
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
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> Create Prescription </h3>
                    </div>
                    <div class="box-body">
                        <form action="{{ url('/healthcare/create/prescription') }}" method="get">
                            @csrf

                            <div class="row">
                                <div class="col-sm-2">
                                    <select name="userType" id="select-user-type" class="form-control" required>
                                        <option value="">--Prescription For--</option>
                                        <option value="1">Student</option>
                                        <option value="2">HR/FM</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <select name="userId" id="select-user" class="form-control" required>
                                        <option value="">--User--</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="number" name="followUpId" class="form-control" placeholder="Follow Up Prescription Id"> 
                                </div>
                                <div class="col-sm-3">
                                    <button class="btn btn-success">Create Prescription</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Prescription List </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered" id="prescriptionTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
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
                                @foreach ($prescriptions as $prescription)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        @if ($prescription->patient_type == 1)
                                            <td>Student</td>
                                            <td>{{ $prescription->cadet->first_name }} {{ $prescription->cadet->last_name }}</td>
                                            <td>{{ $prescription->cadet->id }}</td>
                                        @elseif($prescription->patient_type == 2) 
                                            <td>HR/FM</td>
                                            <td>{{ $prescription->employee->first_name }} {{ $prescription->employee->last_name }}</td>
                                            <td>{{ $prescription->employee->id }}</td>  
                                        @endif 
                                        <td>{{ $prescription->created_at }}</td>
                                        <td>
                                            @if ($prescription->status == 1)
                                                Pending
                                            @elseif($prescription->status == 2)
                                                Admitted
                                            @elseif($prescription->status == 3)
                                                Closed
                                            @endif
                                        </td>
                                        <td>{{ $prescription->createdBy->name }}</td>
                                        <td>
                                            <a href="{{ url('/healthcare/edit/prescription/'.$prescription->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                                            <a href="{{ url('/healthcare/delete/prescription/'.$prescription->id) }}"
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
        $('#prescriptionTable').DataTable();

        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        $('#select-user').select2();

        $('#select-user-type').change(function () {
            var userType = $(this).val();
            

            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/healthcare/users/from/user-type') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'userType': userType,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    if (userType == 1) {
                        txt = '<option value="">--Student--</option>';
                    } else if(userType == 2){
                        txt = '<option value="">--HR/FM--</option>';
                    }else {
                        txt = '<option value="">--User--</option>';
                    }

                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">ID: '+element.id+' - '+element.first_name+' '+element.last_name+'</option>';
                    });

                    $('#select-user').html(txt);
                    $('#select-user').select2();
                }
            });
            // Ajax Request End
        });
    });
</script>
@stop
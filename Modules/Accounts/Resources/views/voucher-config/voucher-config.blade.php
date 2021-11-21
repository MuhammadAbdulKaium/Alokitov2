@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage  |<small>Voucher Config</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Accounts</a></li>
                <li class="active">Voucher Config</li>
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
                    <h3 class="box-title"><i class="fa fa-search"></i> Voucher Config List </h3>
                    <div class="box-tools">
                        <a class="btn btn-success btn-sm" href="{{ url('accounts/voucher-config-list/create') }}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square"></i> New</a>
                    </div>
                </div>
                <div class="box-body">
                    <form action="">
                        @csrf

                        <table class="table table-striped table-bordered" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Institute</th>
                                    <th>Campus</th>
                                    <th>Width</th>
                                    <th>Prefix</th>
                                    <th>Suffix</th>
                                    <th>Begining Number</th>
                                    <th>Numbering</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($vouchers)
                                @foreach($vouchers as $k => $voucher)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$voucher->voucher_name}}</td>
                                    <td>{{$voucher->institute_name}}</td>
                                    <td>{{$voucher->campus_name}}</td>
                                    <td>{{$voucher->numeric_part}}</td>
                                    <td>{{$voucher->prefix}}</td>
                                    <td>{{$voucher->suffix}}</td>
                                    <td>{{$voucher->starting_number}}</td>
                                    <td>{{$voucher->numbering}}</td>
                                    <td>{{($voucher->status==1)?'Active':'Inactive'}}</td>
                                    <td>
                                        <a id="update-guard-data" class="btn btn-success" href="/accounts/voucher-config-list/{{$voucher->id}}/edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"> Edit</a>
                                        <!-- <a href="/inventory/delete/voucher/{{$voucher->id}}" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete" class="btn btn-danger">Delete</a> -->

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
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
    
</script>   
@endsection
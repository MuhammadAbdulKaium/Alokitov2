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
                <i class="fa fa-th-list"></i> Accounts |<small>Budget Allocation</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="">Accounts</a></li>
                <li>SOP Setup</li>
                <li class="active">Budget Allocation</li>
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
                            <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Budget Allocation </h3>
                            <div class="box-tools">
                                <a class="btn btn-success" href="{{url('/accounts/budget-allocation/add-budget')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add Budget</a>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-bordered" id="accountsTable">
                                <thead>
                                <tr>
                                    <th>SL.</th>
                                    <th>Fiscal Year</th>
                                    <th>Control Group</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($budgets as $budget)
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$budget->fiscal_year}}</td>
                                            <td>[{{$budget->account->account_code}}] {{$budget->account->account_name}}</td>
                                            <td>{{$budget->amount}}/-</td>
                                            <td>
                                                <a class="btn btn-primary btn-xs" href="{{url('/accounts/budget-allocation/edit-budget/'.$budget->id)}}"
                                                   data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-edit"></i></a>
                                                <a class="btn btn-danger btn-xs" href="{{url('/accounts/budget-allocation/delete-budget/'.$budget->id)}}"
                                                   onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
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
            // $('#houseTable').DataTable();

            $('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });
        });
    </script>
@stop
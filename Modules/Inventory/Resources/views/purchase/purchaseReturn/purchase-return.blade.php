@extends('layouts.master')
@section('css')
 <link href="{{ URL::asset('css/custom.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage  |<small>Purchase Return</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Inventory</a></li>
                <li><a href="#">Purchase</a></li>
                <li class="active">Purchase Return</li>
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
                    <h3 class="box-title"><i class="fa fa-search"></i> Purchase Return List </h3>
                    <div class="box-tools">
                        <a class="btn btn-success btn-sm" href="{{ url('inventory/purchase-return/create') }}"><i class="fa fa-plus-square"></i> New</a></div>
                </div>
                <div class="box-body">
                    <form action="">
                        @csrf

                        <div class="row" style="margin-bottom: 30px">
                            <div class="col-sm-1">
                                <select name="" id="" class="form-control">
                                    <option value="">Per page</option>
                                    <option value="10" selected>10</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="" id="" class="form-control select2">
                                    <option value="">Voucher No</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" placeholder="Search by keyword">
                            </div>                           
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

                        <table class="table table-striped table-bordered m-b-0">
                            <thead>
                                <tr>
                                    <th width="6%">Select</th>
                                    <th>Voucher #</th>
                                    <th>Campus</th>
                                    <th>Party Name</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox" name=""></td>
                                    <td>#PORETO001</td>
                                    <td>Main Campus</td>
                                    <td>Inter Pack Ltd</td>
                                    <td>04/15/2021</td>
                                    <td class="text-right">500</td>
                                    <td>Pending</td>
                                    <td>
                                        <div class="dropdown dropdownButton status">
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Status
                                            <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                              <li><a href="#">Approved</a></li>
                                              <li><a href="#">Reject</a></li>
                                            </ul>
                                        </div>
                                        <a href=""
                                            class="btn btn-danger btn-xs"
                                            onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                            data-content="delete" title="delete"><i class="fa fa-trash-o"></i></a>
                                         <a class="btn btn-primary btn-xs"
                                            href="{{ url('inventory/purchase-return/1') }}"><i class="fa fa-info-circle" aria-hidden="true"></i> Details</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-2 p-t-15">
                                <button class="btn btn-danger">Delete</button>
                            </div>
                            <div class="col-sm-10">
                                <ul class="pagination pull-right">
                                  <li><a href="#">Previous</a></li>
                                  <li class="active"><a href="#">1</a></li>
                                  <li><a href="#">Next</a></li>
                                </ul>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </section>
    </div>

@endsection



@section('scripts')
<script>

    $(document).ready(function() {
        $('.select2').select2();
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
</script>   
@endsection

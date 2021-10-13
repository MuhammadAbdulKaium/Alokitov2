@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Fees Head | <small>Structure</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="{{URL::to('/home')}}"><i class="fa fa-home"></i>Home</a></li>
                <li class="active">Fees</li>
                <li class="active">Head</li>
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
                <div class="col-md-6">
                <div class="box box-solid">
                    <div class="et">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-plus"></i> Fees Head</h3>
                            <div class="box-tools">
                                <a class="btn btn-success btn-sm" href="<?php echo e(url('/cadetfees/fees/head/create')); ?>" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Add</a>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Head Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $i = 1
                        @endphp
                        @if(isset($feesHeads))
                            @foreach($feesHeads as $fees)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$fees->fees_head}}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="/cadetfees/fees/head/edit/{{$fees->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Edit</a>
                                        <a class="btn btn-danger btn-sm" href="" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box box-solid">
                        <div class="et">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-plus"></i> Fees Structure</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="<?php echo e(url('/cadetfees/fees/structure/create')); ?>" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Add</a>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Structure Name</th>
                                <th>Structure Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i = 1
                            @endphp
                            @if(isset($feesStructures))
                                @foreach($feesStructures as $structure)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$structure->structure_name}}</td>
                                        <td>{{$structure->total_fees}}</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="/cadetfees/fees/structure/edit/{{$structure->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Edit</a>
                                            <a class="btn btn-success btn-sm" href="/cadetfees/fees/structure/details/create/{{$structure->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Structure</a>
                                            <a class="btn btn-danger btn-sm" href="/cadetfees/fees/structure/details/delete/{{$structure->id}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
        </section>
    </div>

    <!-- global modal -->
    <div aria-hidden="true" aria-labelledby="esModalLabel" class="modal" id="globalModal" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw">
                            </i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
</script>
@endsection
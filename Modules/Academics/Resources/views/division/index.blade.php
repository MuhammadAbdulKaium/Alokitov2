@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Division</small></h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Academics</a></li>
                <li class="active">Course Management</li>
                <li class="active">Division</li>
            </ul>
        </section>
        <section class="content">
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif
            <div class="box box-solid">
                <div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i>View Division List</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="{{url('/academics/division/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-sm">Add Division</a>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table id="" class="table table-striped table-bordered text-center">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Short Code</th>
                            {{--<th>Status</th>--}}
                            <th>Action</th>
                        </tr>

                        </thead>
                        <tbody>
                        @if($divisionList->count()>0)
                            @foreach($divisionList as $index=>$division)
                                <tr>
                                    <td>{{($index+1)}}</td>
                                    <td>{{$division->name}}</td>
                                    <td>{{$division->short_name}}</td>
                                    {{--<td>--}}
                                        {{--<a href="{{url('/academics/division/'.$division->id.'/status')}}" class="label label-{{$division->satus==1?'success':'danger'}}" title="Status">--}}
                                            {{--{{$division->satus==1?'Active':'Pending'}}--}}
                                        {{--</a>--}}
                                    {{--</td>--}}
                                    <td>
                                        <a href="{{url('/academics/division/'.$division->id.'/edit')}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-sm">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{url('/academics/division/'.$division->id.'/delete')}}" title="Delete" onclick="return confirm('Are you sure want to delete this item?');">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div><!-- /.box-->
        </section>
    </div>

    <div class="modal"  id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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
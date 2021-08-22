@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Holiday Calender</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="#">Human Resource</a></li>
            <li><a href="#">Employee Management</a></li>
            <li class="active">Holiday Calender</li>
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
            <div class="col-sm-4">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Calender Category</h3>
                        @if ($holidayCalenderCategory)
                        <div class="box-tools">
                            <a class="btn btn-success" href="{{url('/employee/holiday-calender')}}" <i class="fa fa-plus-square"></i> Add</a></div>
                        @endif
                    </div>
                    <div class="box-body table-responsive">
                        <div class="box-body">
                            <form action="{{ ($holidayCalenderCategory)?url('employee/update/holiday-calender/'.$holidayCalenderCategory->id):url('employee/create/holiday-calender') }}" method="POST">
                                @csrf

                                <input type="text" class="form-control" name="name" style="margin-bottom: 15px"
                                    placeholder="Calender Category Name" value="{{ ($holidayCalenderCategory)?$holidayCalenderCategory->name:'' }}" required>

                                <button class="btn btn-success">
                                    {{ ($holidayCalenderCategory)?'Update':'Create' }}
                                </button>
                            </form>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i>
                            View calander Category
                        </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="box-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Calender Category Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($holidayCalenderCategories as $holidayCalenderCategory)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>{{$holidayCalenderCategory->name}}</td>
                                        <td>
                                            <a href="{{ url('/employee/holiday-calender/'.$holidayCalenderCategory->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"
                                                aria-hidden="true"></i></a>
                                            <a class="btn btn-primary btn-xs"
                                                href="{{url('/employee/holiday-calender/set-up/'.$holidayCalenderCategory->id)}}"
                                                data-target="#globalModal" data-toggle="modal"
                                                data-modal-size="modal-lg">Calender Set Up</a>
                                            <a href="{{ url('/employee/delete/holiday-calender/'.$holidayCalenderCategory->id) }}"
                                                class="btn btn-danger btn-xs"
                                                onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                                data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
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


@section('scripts')


@endsection
@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
<link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
@stop


{{-- Content --}}
@section('content')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Cadet Activity Directory</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/student/default/index">Cadet</a></li>
            <li class="active">Cadet Activity Directory</li>
        </ul>
    </section>
    <section class="content">

        <div id="p0">
            @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                    style="text-decoration:none" data-dismiss="alert"
                    aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @elseif(Session::has('alert'))
                <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
            @elseif(Session::has('errorMessage'))
                <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-4">
                <form action="{{url('student/store-category')}}" method="POST">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h4><i class="fa fa-plus-square"></i> Category</h5>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="categoryName"
                                            placeholder="Category Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <select id="activity" class="form-control select2" name="cadetHrFm[]"
                                            multiple="multiple" required>
                                            @foreach ($userTypes as $userType)
                                            <option value="{{$userType->id}}">{{$userType->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <input class="form-control" type="text" name="remarks" placeholder="Remarks" required>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary btn-create">Create</button>
                            <button class="btn btn-default btn-create">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-8">
                <form action="{{url('student/store-activity')}}" method="POST">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h4><i class="fa fa-plus-square"></i> Activity</h5>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select class="form-control" name="cmbCategory" id="cmbCategory" required>
                                            <option value="" selected>Select Category</option>
                                            @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select class="form-control" name="room_id" id="room">
                                            <option value="" selected>Select Room</option>
                                            @foreach ($rooms as $room)
                                            <option value="{{ $room->id }}">{{ $room->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="activityName"
                                            placeholder="Activity Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="activityRemarks"
                                            placeholder="Remarks" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary btn-create">Create</button>
                            <button class="btn btn-default btn-create">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="box box-solid">
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="categoryTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Cadet/HR/FM</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i = 1
                                @endphp
                                @foreach($categories as $values)
                                <tr class="gradeX">
                                    <td>{{$i++}}</td>
                                    <td>{{$values->category_name}}</td>
                                    <td>
                                        @if ($values->userTypes)
                                            @foreach ($values->userTypes as $userType)
                                                <div class="badge">{{$userType->title}}</div>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{$values->remarks}}</td>
                                    <td>
                                        <!-- <a href="{{ url('edit-category/'.$values->id) }}" class="btn btn-primary btn-xs"
                                            data-placement="top" data-content="update"><i class="fa fa-edit"></i></a> -->
                                        <a href="{{url('/student/category/'.$values->id.'/edit')}}"
                                            class="btn btn-primary btn-xs" title="Edit" data-target="#globalModal"
                                            data-toggle="modal" data-modal-size="modal-md">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ url('/student/delete-category/'.$values->id) }}"
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
            <div class="col-lg-8">
                <div class="box box-solid">
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover" id="activityTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Activity Name</th>
                                    <th>Category</th>
                                    <th>Room</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i = 1
                                @endphp
                                @foreach($activities as $values)
                                <tr class="gradeX">
                                    <td>{{$i++}}</td>
                                    <td>{{$values->activity_name}}</td>
                                    {{-- <td>{{$values->student_activity_directory_category_id}}</td> --}}
                                    <td>{{$values->studentActivityDirectoryCategories->category_name}}</td>
                                    <td>
                                        @if ($values->physicalRoom)
                                        {{ $values->physicalRoom->name }}
                                        @endif
                                    </td>
                                    <td>{{$values->remarks}}</td>
                                    <td>
                                        <a href="{{ url('student/edit-activity/'.$values->id) }}" class="btn btn-primary btn-xs"
                                            title="Edit" data-target="#globalModal" data-toggle="modal"
                                            data-modal-size="modal-md">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ url('student/delete-activity/'.$values->id) }}"
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

@stop

{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('#categoryTable').DataTable();
        $('#activityTable').DataTable();

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        $('.select2').select2();
        var categoryType = 1;

        if (categoryType == 2 || categoryType == 5 || categoryType == 4) {
            $("#graph").hide();
        } else {
            show_graph();
        }
    });
</script>
@stop
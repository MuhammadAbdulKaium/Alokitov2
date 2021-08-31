@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Physical Rooms</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/">Academics</a></li>
                <li>SOP Setup</li>
                <li class="active">Physical Rooms</li>
            </ul>
        </section>
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
            <div class="col-sm-4">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-plus-square"></i> Category</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{ asset('academics/physical/room/category/store') }}" method="POST">
                            @csrf

                            <div class="row" style="margin: 30px 0">
                                <div class="col-sm-7">
                                    <input type="text" name="category_name" class="form-control category-field"
                                        placeholder="Category Name" required>
                                    @error('category_name')
                                    <div class="text-danger" style="margin-bottom: 30px">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" name="cat_type" value="1"> Is Club
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-create">Create</button>
                            <button type="button" class="btn btn-secondary btn-create category-reset">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-plus-square"></i> Room</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{ url('academics/physical/room/store') }}" method="post">
                            @csrf

                            <div class="row" style="margin: 30px 0">
                                <div class="col-sm-3">
                                    <select name="category_id" id="" class="form-control room-field" required>
                                        <option value="">-- Category --</option>
                                        @foreach ($roomCategories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="name" class="form-control room-field" placeholder="Room Name"
                                        required>
                                    @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-1">
                                    <label for="">FM/HR:</label>
                                </div>
                                <div class="col-sm-3">
                                    <select name="employees[]" id="select-employees" class="form-control room-field" multiple
                                        required placeholder="FM/HR">
                                        <option value="">-- FM / HR --</option>
                                        @foreach ($employees as $employee)
                                        <option value="{{$employee->id}}">{{$employee->first_name}}
                                            {{$employee->last_name}}</option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row" style="margin: 30px 0">
                                <div class="col-sm-2">
                                    <label for="">No. of Rows:</label>
                                    <input type="number" class="form-control add-val room-field" name="rows" required>
                                    @error('rows')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="">No. of Columns:</label>
                                    <input type="number" class="form-control add-val room-field" name="cols" required>
                                    @error('cols')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="">Students Per Seat:</label>
                                    <input type="number" class="form-control add-val room-field" name="cadets_per_seat" required>
                                    @error('cadets_per_seat')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="">Total:</label>
                                    <input class="form-control add-total room-field" type="number" disabled>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-create">Create</button>
                            <button type="button" class="btn btn-secondary btn-create room-reset">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="box box-solid">
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered" id="categoryTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Category Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roomCategories as $category)
                                <tr>
                                    <th scope="row">{{$loop->index+1}}</th>
                                    <td>{{$category->name}}</td>
                                    <td>
                                        @if ($category->cat_type)
                                        {{($category->cat_type)?"Club":''}}
                                        @endif
                                    </td>
                                    <td>
                                        {{-- <a href="{{url('academics/physical/room/category/edit/{id}')}}" class="btn
                                        btn-primary btn-xs" data-placement="top" data-content="update"><i
                                            class="fa fa-edit"></i></a> --}}
                                        <a class="btn btn-primary btn-xs"
                                            href="{{url('academics/physical/room/category/edit/'.$category->id)}}"
                                            data-target="#globalModal" data-toggle="modal" data-modal-size="modal-sm"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="{{url('academics/physical/room/category/delete/'.$category->id)}}"
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
            <div class="col-md-8">
                <div class="box box-solid">
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered" id="roomTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">FM / HR</th>
                                    <th scope="col">RW</th>
                                    <th scope="col">CL</th>
                                    <th scope="col">CPS</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Forms</th>
                                    <th scope="col">Stu</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rooms as $room)
                                <tr>
                                    <th scope="row">{{ $loop->index+1 }}</th>
                                    <td>{{ $room->category->name }}</td>
                                    <td>{{ $room->name }}</td>
                                    <td>
                                        @foreach ($room->employees as $employee)
                                        <div class="badge badge-success">{{$employee->first_name}}
                                            {{$employee->last_name}}</div>
                                        @endforeach
                                    </td>
                                    <td>{{ $room->rows }}</td>
                                    <td>{{ $room->cols }}</td>
                                    <td>{{ $room->cadets_per_seat }}</td>
                                    <td>{{ $room->rows * $room->cols * $room->cadets_per_seat }}</td>
                                    <td>
                                        @if ($room->sections())
                                        @foreach ($room->sections() as $section)
                                        <span class="badge">{{$section->singleBatch->batch_name}} -
                                            {{$section->section_name}}</span>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if ($room->students())
                                        <span class="text-primary" data-toggle="tooltip" data-placement="left" title="
                                                @foreach ($room->students() as $student)
                                                    {{ $student->first_name }} {{ $student->last_name }}, 
                                                @endforeach
                                            ">
                                            {{ $room->students()->count() }}
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-success btn-xs"
                                            href="{{url('academics/physical/room/allocate/view/'.$room->id)}}"
                                            data-target="#globalModal" data-toggle="modal"
                                            data-modal-size="modal-lg">A</a>
                                        <a class="btn btn-success btn-xs"
                                            href="{{url('academics/physical/room/allocate/edit/view/'.$room->id)}}"
                                            data-target="#globalModal" data-toggle="modal"
                                            data-modal-size="modal-lg">EA</a>
                                        <a class="btn btn-primary btn-xs"
                                            href="{{url('academics/physical/room/edit/'.$room->id)}}"
                                            data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="{{ url('academics/physical/room/delete/'.$room->id) }}"
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
<script src="{{ asset('js/multiselect.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#categoryTable').DataTable();
        $('#roomTable').DataTable();
        $('#select-employees').select2();

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });


        // Category Reset functionalities
        $('.category-reset').click(function () {
           $('.category-field').val(''); 
        });

        // Room Reset functionalities
        $('.room-reset').click(function () {
           $('.room-field').val(''); 
        });


        $('.add-val').keyup(function () {
            var values = $('.add-val');
            var result = 1;
            var hasVal = false;
            values.each(function (index) {
                if ($(this).val()) {
                    hasVal = true;
                    result *= parseInt($(this).val());
                }
            });
            if (hasVal) {
                $('.add-total').val(result);
            } else {
                $('.add-total').val(0);
            }
        });
    });
</script>
@stop
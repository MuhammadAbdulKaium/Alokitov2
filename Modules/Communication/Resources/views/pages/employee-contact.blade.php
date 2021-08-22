
@extends('layouts.master')

@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Telephone Diary | <small>Employee</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/hr/">Human Resource</a></li>
                <li><a href="/employee">Employee Contact</a></li>
                <li class="active"> Telephone Diary Employee</li>
            </ul>
        </section>
        <section class="content">
            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Search Employee Contact</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="/employee/create"><i class="fa fa-plus-square"></i> Add</a>
                        </div>
                    </div>
                </div>
                <form id="w0" action="/communication/telephone-diary/employee-contact/search" method="POST">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="department">Department</label>
                                    <select id="department" class="form-control" name="department">
                                        <option value="">--- Select Department ---</option>
                                        @if($allDepartments)
                                            @foreach($allDepartments as $department)
                                                <option value="{{$department->id}}" @if($allInputs['department']==$department->id) selected="selected" @endif>{{$department->name}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="designation">Designation</label>
                                    <select id="designation" class="form-control" name="designation">
                                        <option value="">--- Select Designation ---</option>
                                        @if($allDesignaitons)
                                            @foreach($allDesignaitons as $designation)
                                                <option value="{{$designation->id}}" @if($allInputs['designation']==$designation->id) selected="selected" @endif>{{$designation->name}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="category">Category</label>
                                    <select id="category" class="form-control" name="category">
                                        <option value="">--- Select Category ---</option>
                                        <option value="0" @if($allInputs['category']=='0') selected="selected" @endif>Non-Teaching</option>
                                        <option value="1" @if($allInputs['category']=='1') selected="selected" @endif>Teaching</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="margin-top: 25px;">
                                <div class="form-group">
                                    <input id="email" class="form-control" name="email" value="@if($allInputs['email'] != null) {{$allInputs['email']}} @endif" placeholder="Enter Email Id." type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4" style="margin-top: 25px;">
                                <div class="form-group">
                                    <input id="emp_id" class="form-control" name="emp_id" value="@if($allInputs['id'] != null) {{$allInputs['id']}} @endif" placeholder="Enter Employee Id" type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                </form>
            </div>

            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('alert'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif


            @if($allEmployee AND $allEmployee->count()>0)
                <div class="box box-solid">
                    <div class="et">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> View Employee List</h3>
                            <div class="box-tools">
                                <form id="w0" action="{{url("/communication/telephone-diary/employee-contact/downlaod")}}" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="department" @if(!empty($allInputs['department'])) value="{{$allInputs['department']}} @endif">
                                    <input type="hidden" name="designation"  @if(!empty($allInputs['designation']))  value="{{$allInputs['designation']}}"  @endif>
                                    <input type="hidden" name="category"  @if(!empty($allInputs['category']))  value="{{$allInputs['category']}}" @endif>
                                    <input type="hidden" name="email"  @if(!empty($allInputs['email']))  value="{{$allInputs['email']}}" @endif>
                                    <input type="hidden" name="gr_no"  @if(!empty($allInputs['gr_no']))  value="{{$allInputs['gr_no']}}" @endif>
                                    <input type="hidden" name="emp_id"  @if(!empty($allInputs['id']))  value="{{$allInputs['id']}}" @endif>
                                    {{--<input type="hidden" name="section" value="{{$allSearchInputs['section']}}">--}}
                                    {{--<input type="hidden" name="stu_detail_search" value="{{$allSearchInputs['academic_level']}}">--}}
                                    <input type="hidden" name="student_name" value="{{csrf_token()}}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="icon-user icon-white"></i> Excel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="box-header">
                        </div>
                        <div class="box-body">
                            <table id="example1" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee No</th>
                                    <th>Name</th>
                                    <th>Email/Login Id</th>
                                    <th>Phone</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1; @endphp
                                @foreach($allEmployee as $employee)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$employee->id}}</td>
                                        <td><a href="{{url('/employee/profile/personal/'.$employee->id)}}">{{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}}</a></td>
                                        <td><a href="{{url('/employee/profile/personal/'.$employee->id)}}">{{$employee->email}}</a></td>
                                        <td>{{$employee->phone}}</td>
                                        <td>{{$employee->department()->name}}</td>
                                        <td>{{$employee->designation()->name}}</td>
                                        <td>@if($employee->category==1) Teaching @else Non-Teaching @endif</td>
                                        <td>
                                            <a href="#" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="get"><span class="glyphicon glyphicon-trash"></span></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
    </div>
    @else
        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="fa fa-warning"></i></i> No result found. </h5>
        </div>
        @endif
        </section>
        </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- datatable script -->
    <script>
        $(function () {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
        });

        jQuery(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
        });

    </script>
@endsection

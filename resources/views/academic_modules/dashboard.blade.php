@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
    {{--<link rel="stylesheet" type="text/css" href="https://www.pigno.se/barn/PIGNOSE-Calendar/demo/css/semantic.ui.min.css />--}}
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('template-2/pg-calender/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('template-2/pg-calender/css/pignose.calendar.css') }}" />

    <style type="text/css">
        .input-calendar {
            display: block;
            width: 100%;
            max-width: 360px;
            margin: 0 auto;
            height: 3.2em;
            line-height: 3.2em;
            font: inherit;
            padding: 0 1.2em;
            border: 1px solid #d8d8d8;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            -o-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            -moz-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            -webkit-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
        }

        .btn-calendar {
            display: block;
            width: 100%;
            max-width: 360px;
            height: 3.2em;
            line-height: 3.2em;
            background-color: #52555a;
            margin: 0 auto;
            font-weight: 600;
            color: #ffffff !important;
            text-decoration: none !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            -o-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            -moz-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            -webkit-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
        }

        .btn-calendar:hover {
            background-color: #5a6268;
        }

    </style>
@stop

{{-- Content --}}
@section('content')

    <section class="breadcrumb-bg">
        <div class="container-fluid">
            <div class="col-md-6">
                <h5 class="text-color-white"><i class="fa fa-bullhorn icon-margin"></i> Message of the day:</h5>
            </div>
        </div>
    </section>

{{--    <section class="breadcrumb-bg">--}}
{{--    <div class="container-fluid">--}}
{{--    <div class="col-md-6">--}}
{{--    <ul class="breadcrumb">--}}
{{--    <li><a href="#">Home</a></li>--}}
{{--    <li><a href="#">Student</a></li>--}}
{{--    <li class="active">Accessories</li>--}}
{{--    </ul>--}}
{{--    </div>--}}
{{--    <div class="col-md-6">--}}
{{--    <h5 class="text-color-white"><i class="fa fa-bullhorn icon-margin"></i> Message of the day:</h5>--}}
{{--    </div>--}}
{{--    </div>--}}
{{--    </section><!--breadcrumb and todayes news End-->--}}
{{--    <div class="clearfix"></div>--}}

    <section class="4-big-button">
        <div class="container-fluid">
            <div class="col-md-3 col-xs-6">
                <a href="{{url('/student/manage')}}" class="button-wrap">
                    <div class="icon-wrap hidden-xs">
                        <p><i class=" fa fa-users"></i></p>
                    </div>
                    <div class="icon-containt-wrap">
                        <h1 class="text-center">{{$totalStudent}}</h1>
                        <p class="text-center">Student</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-xs-6">
                <a href="{{url('/employee/manage')}}">
                    <div class="icon-wrap hidden-xs">
                        <p><i class=" fa fa-user"></i></p>
                    </div>
                    <div class="icon-containt-wrap">
                        <h1 class="text-center">{{$totalEmployee}}</h1>
                        <p class="text-center">{{trans('dashboard/index.teacher_admin')}}</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-xs-6">
                <a href="{{url('/communication/event')}}">
                    <div class="icon-wrap hidden-xs">
                        <p><i class=" fa fa-calendar"></i></p>
                    </div>
                    <div class="icon-containt-wrap ">
                        <h1 class="text-center">{{$allEventList->count()}}</h1>
                        <p class="text-center">{{trans('dashboard/index.event')}}</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-xs-6">
                <a href="#">
                    <div class="icon-wrap hidden-xs">
                        <p><i class=" fa fa-envelope"></i></p>
                    </div>
                    <div class="icon-containt-wrap">
                        <h1 class="text-center"> @if($totalSmsCreadit>0)
                                {{$totalSmsCreadit}}
                            @else
                                0
                            @endif</h1>
                        <p class="text-center">{{trans('dashboard/index.sms_credit')}}</p>
                    </div>
                </a>
            </div>
        </div><!--4-big-button Containert End-->
    </section><!--4-big-button Section End-->
    <section>
        <div class="row">
            <div class="col-md-6">
                <div class="atten-bg" style="min-height: 250px;">
                    <div id="graph" style="padding-top: 10px;">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="type">Academic Year</label>
                                <select id="year" class="form-control" name="year">
                                    @foreach($yearList as $year)
                                        <option value="{{$year->id}}" >{{$year->year_name}}</option>
                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="type">Academic Level</label>
                                <select id="AcademicLevel" class="form-control" name="AcademicLevel">
                                    <option value="" selected>-- Select --</option>
                                    @foreach($academicLevel as $level)
                                        <option value="{{$level->id}}" >{{$level->level_name}}</option>
                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="type">Class</label>
                                <select id="class" class="form-control" name="class">
                                    <option value="" selected disabled>-- Select --</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="type">Form</label>
                                <select id="section" class="form-control" name="ection">
                                    <option value="" selected disabled>-- Select --</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="show-for">Show Analysis for</label>
                                <select id="cattype" class="form-control" name="show-for">
                                    <option value="" selected disabled>-- Select --</option>
                                        @foreach($type as $item)
                                            <option value="{{$item->id}}">{{$item->performance_type}}</option>
                                        @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="category">Category</label>
                                <select id="categoryID" class="form-control" name="category">
                                    <option value="" selected disabled>-- Select --</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select id="categoryActivity" class="form-control" name="activity">
                                    <option value="" selected disabled>- Entity -</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            {{--                    <input name="student_id" type="hidden" value="{{$personalInfo->id}}">--}}
                            <div class="form-group">
                                <input type="radio" id="yearly" name="duration" value="yearly" checked="checked">
                                <label for="female">Yearly</label>
                                <input type="radio" id="monthly" name="duration" value="monthly">
                                <label for="male">Monthly</label><br>
                            </div>
                        </div>
{{--                        <div id="month_show" class="col-md-2" style="display: none;">--}}
{{--                            <div class="form-group">--}}
{{--                                <input id="month_name" type="text" class="form-control datepicker" name="month_name" placeholder="Select Year">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="radio" id="details" name="type" value="details" checked="checked">
                                <label for="details">Details</label>
                                <input type="radio" id="summary" name="type" value="summary">
                                <label for="summary">Summary</label><br>

                            </div>
                        </div>
                        <a href="javascript:void(0)" id="show_graph"><i class="fa fa-search fa-2x" aria-hidden="true"></i></a>
                    </div>
                    <div id="chtAnimatedBarChart" class="bcBar"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="atten-bg" style="min-height: 250px;">
                    <div id="graph" style="padding-top: 10px;">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="type-compare">Academic Year</label>
                                <select id="year-compare" class="form-control" name="year-compare">
                                    @foreach($yearList as $year)
                                        <option value="{{$year->id}}" >{{$year->year_name}}</option>
                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="type">Academic Level</label>
                                <select id="AcademicLevel-compare" class="form-control" name="AcademicLevel-compare">
                                    <option value="" selected>-- Select --</option>
                                    @foreach($academicLevel as $level)
                                        <option value="{{$level->id}}" >{{$level->level_name}}</option>
                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="class-compare">Class</label>
                                <select id="class-compare" class="form-control" name="class-compare">
                                    <option value="" selected disabled>-- Select --</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="section-compare">Form</label>
                                <select id="section-compare" class="form-control" name="section-compare">
                                    <option value="" selected disabled>-- Select --</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="show-for-compare">Show Analysis for</label>
                                <select id="cattype-compare" class="form-control" name="show-for-compare">
                                    <option value="" selected disabled>-- Select --</option>
                                        @foreach($type as $item)
                                            <option value="{{$item->id}}">{{$item->performance_type}}</option>
                                        @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="category-compare">Category</label>
                                <select id="categoryID-compare" class="form-control" name="category-compare">
                                    <option value="" selected disabled>-- Select --</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select id="categoryActivity-compare" class="form-control" name="activity-compare">
                                    <option value="" selected disabled>- Entity -</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="radio" id="yearly-compare" name="duration-compare" value="yearly" checked="checked">
                                <label for="female">Yearly</label>
                                <input type="radio" id="monthly-compare" name="duration-compare" value="monthly">
                                <label for="male">Monthly</label><br>
                            </div>
                        </div>
{{--                        <div id="month_show" class="col-md-2" style="display: none;">--}}
{{--                            <div class="form-group">--}}
{{--                                <input id="month_name-compare-" type="text" class="form-control datepicker" name="month_name-compare" placeholder="Select Year">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="radio" id="details-compare" name="type-compare" value="details" checked="checked">
                                <label for="details">Details</label>
                                <input type="radio" id="summary-compare" name="type-compare" value="summary">
                                <label for="summary">Summary</label><br>
                            </div>
                        </div>
                        <a href="javascript:void(0)" id="show_graph_compare"><i class="fa fa-search fa-2x" aria-hidden="true"></i></a>
                    </div>
                    <div id="chtAnimatedBarChart2" class="bcBar"></div>
                </div>
            </div>
        </div>
    </section>


    <div class="clearfix"></div>
    <section class="notice-atten">
        <div class=" container-fluid">
            <div class="col-md-6">
                <div class="notice-bg">
                    <div class="nav-tab-bg">
                        <h5 class="div-head"><span class="glyphicon glyphicon-list-alt icon-margin"></span> {{trans('dashboard/index.notice_board')}}</h5>
                        <ul class="nav nav-tabs notice-tabs">
                            <li class="active"><a data-toggle="tab" id="1" href="#general"><div class="hidden-xs" >{{trans('dashboard/index.general')}}</div><div class="visible-xs">GN</div></a></li>
                            @role(['student', 'admin', 'super-admin'])
                            <li><a data-toggle="tab" id="4" href="#student"><div class="hidden-xs" >Student</div><div class="visible-xs">ST</div></a></li>
                            @endrole
                            @role(['teacher', 'admin', 'super-admin'])
                            <li><a data-toggle="tab" id="3" href="#employee"><div class="hidden-xs" >{{trans('dashboard/index.employee')}}</div><div class="visible-xs">EM</div></a></li>
                            @endrole
                            @role(['parent', 'admin', 'super-admin'])
                            <li><a data-toggle="tab" id="2" href="#parents"><div class="hidden-xs" >{{trans('dashboard/index.parents')}}</div><div class="visible-xs">PA</div></a></li>
                            @endrole
                        </ul>
                    </div><!--notice Navigation End-->

                    <div class="tab-content"  id="notice-data">
                        <div id="general" class="tab-pane fade in active">
                            <div class="alert bg-warning text-warning">
                                No Notice....
                            </div>
                        </div>
                        <div id="student" class="tab-pane fade">
                            <div class="alert bg-warning text-warning">
                                No Notice....
                            </div>
                        </div>
                        <div id="employee" class="tab-pane fade">
                            <div class="alert bg-warning text-warning">
                                No Notice....
                            </div>
                        </div>
                        <div id="parents" class="tab-pane fade">
                            <div class="alert bg-warning text-warning">
                                No Notice....
                            </div>
                        </div>
                    </div>
                </div><!--notice Warape End-->

                <div id="noticelist">

                </div>
            </div>



            <div class="col-md-6">
                <div class="atten-bg">
                    <div class="box-title">
                        <h5><span class="glyphicon glyphicon-th-list icon-margin"></span> Today's Attendance(%)</h5>
                    </div>
                    <div class="theme-style"></div>
                    <div class="row attendance-section">
                        <div class="col-sm-8">
                            <div class="chart">
                                <canvas id="totalAttendanceChart"></canvas>
                            </div>
                        </div>
                        <div class="col-sm-4 dashboard-attendance-percent">
                            @if($attendanceInfo['status']=='success')
                                <P>
                                    <strong>Total Student:</strong><br/>{{$attendanceInfo['total_std']}}<br/>
                                    <strong>Present:</strong><br/>{{$attendanceInfo['total_present_percentage']}} %<br/>
                                    <strong>Absent:</strong><br/>{{$attendanceInfo['total_absent_percentage']}} %<br/>
                                    <strong> Male Present:</strong><br/>{{$attendanceInfo['male_present_percentage']}} %<br/>
                                    <strong> Female Present:</strong><br/>{{$attendanceInfo['female_present_percentage']}} %<br/>
                                </P>
                            @else
                                <P>
                                    <strong>Total Student:</strong><br/>{{$attendanceInfo['std_count']}}<br/>
                                    <strong>Present:</strong><br/>0.00 %<br/>
                                    <strong>Absent:</strong><br/>0.00 %<br/>
                                    <strong> Male Present:</strong><br/>0.00 %<br/>
                                    <strong> Female Present:</strong><br/>0.00 %<br/>
                                </P>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section><!--notice-atten End-->
    <div class="clearfix"></div>
    <section>
        <div class="container-fluid">
            <div class="col-md-6">
                <div class="calender-warp" style="width: 100%px">
                    <div class="main-calender">
                        <div class="calendar"></div>
                    </div>
                    <div class="main-date">
                        <h1>{{date('d')}}</h1>
                        <p class="text-center">{{date('l')}}</p>
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="time-box">
                    <div class="box-title">
                        <h5><span class="glyphicon glyphicon-time icon-margin"></span> {{trans('dashboard/index.today_time_table')}}</h5>
                    </div>
                    <div class="theme-style"></div>

                    <div class="panel-group" id="accordion">
                        @foreach($allAcademicLevel as $levelProfile)

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$levelProfile->id}}"><span class="glyphicon glyphicon-plus icon-margin"></span> {{$levelProfile->level_name}}</a>
                                        <span class="label label-success pull-right">{{$levelProfile->stdList()->count()}} Student(s)</span>
                                    </h4>
                                </div>
                                <div id="collapse{{$levelProfile->id}}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="row">
                                            @php $allBatch = $levelProfile->batch(); @endphp
                                            {{--checking--}}
                                            @if($allBatch->count()>0)
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="batch">Batch</label>
                                                        <select id="batch_{{$levelProfile->id}}" class="form-control academicBatch" name="batch">
                                                            <option value="" selected disabled>-Select Batch-</option>
                                                            @foreach($allBatch as $batchProfile)
                                                                @php $division = $batchProfile->get_division(); @endphp
                                                                <option value="{{$batchProfile->id}}">
                                                                    {{$batchProfile->batch_name.($division?" - ".$division->name:"")}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="section">Form</label>
                                                        <select id="section_{{$levelProfile->id}}" class="form-control academicSection" name="section">
                                                            <option value="" selected disabled>-Select Form-</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="section">Shift</label>
                                                    <select id="shift_{{$levelProfile->id}}" class="form-control academicShift" name="shift">
                                                        <option value="" selected>-Select Shift-</option>
                                                        <option value="0"> Day </option>
                                                        <option value="1"> Morning </option>
                                                    </select>
                                                    <div class="help-block"></div>
                                                </div>
                                            @else

                                                <div class="panel-heading ">
                                                    <h4 class="panel-title alert bg-warning text-warning">
                                                        No Batch and Form found for this segment
                                                    </h4>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </section><!--Calender And time table section End-->
    <div class="clearfix"></div>

    <section>
        <div class="container-fluid">
            <div class="col-md-6">
            </div><!--Courses Colum End-->
            <div class="col-md-6">
                <div class="birthday-bg">
                    <div class="nav-tab-bg">
                        <h5 class="div-head"><span class="glyphicon glyphicon-list-alt icon-margin"></span> Birth Day</h5>
                        <ul class="nav nav-tabs nav-tab-right birthday-tabs">
                            <li class="active"><a href="#birth-today" data-toggle="tab">Today</a></li>
                            <li><a href="#birth-upcoming" data-toggle="tab">Upcoming</a></li>
                        </ul>
                    </div><!--notice Navigation End-->
                    <div class="tab-content">
                        <div class="tab-pane active" id="birth-today">
                            @if($toDayStudentBirthday->count()>0 || $toDayEmployeetBirthday->count()>0)
                                <ul class="todo-list">
                                    @if($toDayStudentBirthday)
                                        @foreach($toDayStudentBirthday as $student)
                                            <li><a class="text-success" href="/student/profile/personal/{{$student->id}}"> {{$student->first_name." ".$student->middle_name." ".$student->last_name}}</a>  ({{ date('d M', strtotime($student->dob)) }})</li>
                                        @endforeach
                                    @endif

                                    @if($toDayEmployeetBirthday)
                                        @foreach($toDayEmployeetBirthday as $employee)
                                            <li><a class="text-warning" href="/employee/profile/personal/{{$employee->id}}"> {{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}}</a>  ({{ date('d M', strtotime($employee->dob)) }})</li>
                                        @endforeach
                                    @endif
                                </ul>
                            @else
                                <div class="alert bg-warning text-warning">No Birthday Found Today </div>
                            @endif

                        </div>
                        <div class="tab-pane" id="birth-upcoming">
                            @if($upComingStudentBirthday->count()>0 || $upComingEmployeeBirthday->count()>0)
                                <ul class="todo-list">
                                    @if($upComingStudentBirthday)
                                        @foreach($upComingStudentBirthday as $student)
                                            <li><a class="text-success" href="/student/profile/personal/{{$student->id}}"> {{$student->first_name." ".$student->middle_name." ".$student->last_name}}</a>  ({{ date('d M', strtotime($student->dob)) }})</li>
                                        @endforeach
                                    @endif

                                    @if($upComingEmployeeBirthday)
                                        @foreach($upComingEmployeeBirthday as $employee)
                                            <li><a class="text-warning" href="/employee/profile/personal/{{$employee->id}}"> {{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}}</a>  ({{ date('d M', strtotime($employee->dob)) }})</li>
                                        @endforeach
                                    @endif
                                </ul>
                            @else
                                <div class="alert bg-warning text-warning">No Birthday within 7 days duration</div>
                            @endif
                        </div>
                    </div>

                </div><!--birthday Warape End-->
            </div>
        </div><!-- Colum End-->

{{--        </div><!--Courses container End-->--}}
    </section><!--Courses Section End-->

    <div class="modal" id="globalModal" tabindex="-1" role="dialog"  aria-labelledby="esModalLabel" aria-hidden="true">
        <div id="modal-dialog" class="modal-dialog">
            <div class="modal-content" >
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
    <script src="{{URL::asset('template-2/js/chart.min.js')}}"></script>
    <link href="{{ asset('css/bar.chart.min.css') }}" rel="stylesheet"/>
    <script src='https://d3js.org/d3.v4.min.js'></script>
    <script src="{{asset('js/jquery.bar.chart.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('.select2').select2();

            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });

            $('input[name="duration"]').click(function(){
                var radio_select = $(this).val();
                if(radio_select == 'monthly')
                {
                    $("#month_show").show(200);
                }
                else
                {
                    $("#month_name").val("");
                    $("#month_show").hide();
                }
            });

            $("#show_graph").click(function (e){
                e.preventDefault();
                $("#chtAnimatedBarChart").html("");
                show_graph();
            });

            $("#show_graph_compare").click(function (e){
                e.preventDefault();
                $("#chtAnimatedBarChart2").html("");
                show_graph_compare();

            });

            $("#AcademicLevel").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: '/admin/dashboard/academicBatch/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            console.log(response);
                            $("#class").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#class").html("");
                }
            });

            $("#class").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: '/admin/dashboard/academicSection/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#section").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#section").html("");
                }
            });

            $("#cattype").change(function(){
                $("#activity").html("");
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: '/admin/dashboard/type/category/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#activity").html(response);
                            $("#categoryID").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#categoryID").html("");
                }
            });

            $("#categoryID").change(function(){
                $("#activity").html("");
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: '/admin/dashboard/category/activity/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#activity").html(response);
                            $("#categoryActivity").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }else
                {
                    $("#categoryActivity").html("");
                }
            });

            $("#AcademicLevel-compare").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: '/admin/dashboard/academicBatch/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            console.log(response);
                            $("#class-compare").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#class").html("");
                }
            });

            $("#class-compare").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: '/admin/dashboard/academicSection/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#section-compare").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#section").html("");
                }
            });

            $("#cattype-compare").change(function(){
                $("#activity").html("");
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: '/admin/dashboard/type/category/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            // $("#activity").html(response);
                            $("#categoryID-compare").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#categoryID").html("");
                }
            });

            $("#categoryID-compare").change(function(){
                $("#activity").html("");
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: '/admin/dashboard/category/activity/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            // $("#activity").html(response);
                            $("#categoryActivity-compare").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }else
                {
                    $("#categoryActivity").html("");
                }
            });


            function show_graph()
            {
                let instituteId = {{$instituteId}};
                let campusId = {{$campusId}};
                let AcademicYear = $("#year").val();
                let AcademicLevel = $("#AcademicLevel").val();
                let Class = $("#class").val();
                let Section = $("#section").val();
                let cattype = $("#cattype").val();
                let CategoryID = $("#categoryID").val();
                let categoryActivity = $("#categoryActivity").val();
                let duration = $("input[name='duration']:checked").val();
                let month_name = $("input[name=month_name]").val();
                let type = $("input[name='type']:checked").val();
                let _token   = '<?php echo csrf_token() ?>';

                $.ajax({
                    url: "{{ url('/admin/dashboard/barchart') }}",
                    type: "POST",
                    data:{
                        instituteId:instituteId,
                        campusId:campusId,
                        AcademicYear:AcademicYear,
                        AcademicLevel:AcademicLevel,
                        Class:Class,
                        Section:Section,
                        cattype:cattype,
                        CategoryID:CategoryID,
                        categoryActivity:categoryActivity,
                        duration:duration,
                        month_name:month_name,
                        type:type,
                        _token: _token
                    },
                    success: function (response) {
                        console.log(response);
                        $("#chtAnimatedBarChart").animatedBarChart({ data: response });
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(errorThrown);
                        console.log(errorThrown);
                    }
                });
            }

            function show_graph_compare()
            {
                let instituteId = {{$instituteId}};
                let campusId = {{$campusId}};
                let AcademicYear = $("#year-compare").val();
                let AcademicLevel = $("#AcademicLevel-compare").val();
                let Class = $("#class-compare").val();
                let Section = $("#section-compare").val();
                let cattype = $("#cattype-compare").val();
                let CategoryID = $("#categoryID-compare").val();
                let categoryActivity = $("#categoryActivity-compare").val();
                let duration = $("input[name='duration-compare']:checked").val();
                let month_name = $("input[name=month_name]").val();
                let type = $("input[name='type-compare']:checked").val();
                let _token   = '<?php echo csrf_token() ?>';

                $.ajax({
                    url: "{{ url('/admin/dashboard/barchart') }}",
                    type: "POST",
                    data:{
                        instituteId:instituteId,
                        campusId:campusId,
                        AcademicYear:AcademicYear,
                        AcademicLevel:AcademicLevel,
                        Class:Class,
                        Section:Section,
                        cattype:cattype,
                        CategoryID:CategoryID,
                        categoryActivity:categoryActivity,
                        duration:duration,
                        month_name:month_name,
                        type:type,
                        _token: _token
                    },
                    success: function (response) {
                        // console.log(response);
                        $("#chtAnimatedBarChart2").animatedBarChart({ data: response });
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(errorThrown);
                        console.log(errorThrown);
                    }
                });
            }

            // attendance chart
            var totalAttendanceChartCtx = document.getElementById("totalAttendanceChart").getContext('2d');
            var totalAttendanceChartChart = new Chart(totalAttendanceChartCtx, {
                type: 'pie',
                data: {
                    labels: ["Present", "Absent"],
                    datasets: [{
                        backgroundColor: ["#119117", "#dd0404"],
                        @if($attendanceInfo['status']=='success')
                        data: [{{$attendanceInfo['total_present_percentage']}}, {{$attendanceInfo['total_absent_percentage']}}],
                        @else
                        data: [0, 100],
                        @endif
                    }]
                }
            });



            // notice

            //When page loads...
            $("ul.notice-tabs li:first").addClass("active").show(); //Activate first tab
            //        $(".tab-content:first").show(); //Show first tab content

            //On Click Event
            $("ul.notice-tabs li").click(function() {
//        $("ul.nav-tabs li").removeClass("active"); //Remove any "active" class
//        $(this).addClass("active"); //Add "active" class to selected tab
//        $(".tab-pane").hide();

                var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
                var user_type = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content

                $.ajax({
                    url: '/communication/notice/all/'+user_type,
                    type: 'GET',
                    dataType: 'html',
                    success: function(content) {
                        $(activeTab).html(content);
                    }
                });

//        $(activeTab).fadeIn(); //Fade in the active ID content
//        return false;
            });


            $.ajax({
                url: '/communication/notice/all/1',
                type: 'GET',
                dataType: 'html',
                success: function(content) {
                    console.log(content);
                    $("#general").html(content);
                }});


            /// time table

            ////////////////////  timetable ////////////////////////
            // request for section list using batch id
            jQuery(document).on('change','.academicBatch',function(){
                var batch_id = $(this).attr('id');
                var level_id = batch_id.replace('batch_','');
                var shift_id = '#shift_'+level_id;
                // ajax request
                $.ajax({
                    url: "{{ url('/academics/find/section') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': $(this).val() },
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        var op='<option value="" selected disabled>-Select Section-</option>';
                        // looping
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                        }
                        // shift reset
                        $(shift_id+' option:first').prop('selected', true)
                        // set value to the academic batch
                        $('#section_'+level_id).html("");
                        $('#section_'+level_id).append(op);
                    },
                    error:function(){
                        // statements
                    },
                });
            });


            // request for section list using batch and section id
            jQuery(document).on('change','.academicSection',function(){
                var section_id = $(this).attr('id');
                var level_id = section_id.replace('section_','');
                var shift_id = '#shift_'+level_id;
                // shift reset
                $(shift_id+' option:first').prop('selected', true)

            });

            // request for section list using batch and section id
            jQuery(document).on('change','.academicShift',function(){

                var shift = $(this).attr('id');
                var level_id = shift.replace('shift_','');
                var class_id = $("#batch_"+level_id).val();
                var section_id = $("#section_"+level_id).val();
                var shift_id = $(this).val();
                if(shift_id){
                    if(class_id && section_id && shift_id){
                        // ajax request
                        $.ajax({
                            url: "/academics/find-class-section-timetable",
                            type: 'GET',
                            cache: false,
                            data: {'level':level_id,'batch': class_id,'section': section_id,'shift': shift_id, 'request_type':'dashboard' },
                            datatype: 'html',

                            beforeSend: function(data) {
                                // statements
                            },

                            success:function(data){
                                $('#globalModal').modal('toggle');
                                $('#modal-dialog').addClass('modal-lg');
                                $('.modal-body').html('');
                                $('.modal-body').html(data);
                            },

                            error:function(){
                                // statements
                            },
                        });

                    }else{
                        alert('please check all inputs are selected');
                    }
                }
            });


        });

    </script>
    {{--//calender section here --}}
    <script src="{{URL::asset('template-2/pg-calender/js/moment.latest.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('template-2/pg-calender/js/pignose.calendar.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            // json parse
            var month_schedule  = JSON.parse( '<?php echo json_encode($monthScheduleList); ?>' );
            // pignoseCalendar
            $('#wrapper .version strong').text('v' + $.fn.pignoseCalendar.ComponentVersion);

            // Default Calendar
            $('.calendar').pignoseCalendar({
                select:function onClickHandler(date, obj) {

                    var $calendar = obj.calendar;
                    var $box = $calendar.parent().siblings('.box').show();
                    var text = 'You choose date ';

                    if(date[0] !== null) {
                        //text += date[0].format('YYYY-MM-DD');
                        //alert(text);

                        // find today's event list
                        var selected_date = date[0].format('YYYY-MM-DD');
                        findMyEventList(selected_date);
                    }

                    if(date[0] !== null && date[1] !== null) {
                        text += ' ~ ';
                    } else if(date[0] === null && date[1] == null) {
                        text += 'nothing';
                    }

                    if(date[1] !== null) {
                        text += date[1].format('YYYY-MM-DD');
                    }

                    $box.text(text);
                },

                prev: function(info, context) {
                    // This is clicked arrow button element.
                    var $this = $(this);
                    // `info` parameter gives useful information of current date.
                    var type = info.type; // it will be `prev`.
                    var year = info.year; // current year (number type), ex: 2017
                    var month = info.month; // current month (number type), ex: 6
                    var day = info.day; // current day (number type), ex: 22

                    findMonthScheduleList(year, month);
                },

                next: function(info, context) {
                    // This is clicked arrow button element.
                    var $this = $(this);
                    // `info` parameter gives useful information of current date.
                    var type = info.type; // it will be `prev`.
                    var year = info.year; // current year (number type), ex: 2017
                    var month = info.month; // current month (number type), ex: 6
                    var day = info.day; // current day (number type), ex: 22

                    findMonthScheduleList(year, month);
                }

            });

            // setMonthSchedule
            setMonthSchedule(month_schedule);

            // find event list using selected date
            function findMyEventList(selected_date) {
                // checking
                if(selected_date.length>0){
                    // ajax request
                    $.ajax({
                        type: "POST",
                        url: '/communication/dashboard/event/list',
                        data: {'selected_date':selected_date, '_token':'{{csrf_token()}}'},
                        datatype: 'html',
                        // datatype: 'application/json',

                        // beforeSend
                        beforeSend: function() {
                            // statement
                        },

                        // success
                        success: function(data) {
                            $('#globalModal').modal('toggle');
                            $('#modal-dialog').addClass('modal-lg');
//                            $('.modal-body').attr("data-modal-size", 'modal-lg');
                            $('.modal-body').html('');
                            $('.modal-body').html(data);
                        }
                    });
                }else{

                }
            }

            // find month event schedule list
            function findMonthScheduleList(year, month) {
                // checking
                if(month){
                    // ajax request
                    $.ajax({
                        type: "GET",
                        url: '/communication/dashboard/month-schedule/'+year+'/'+month,
                         datatype: 'application/json',
                        // beforeSend
                        beforeSend: function() {},

                        // success
                        success: function(data) {
//                            alert(JSON.stringify(data));
                            // setMonthSchedule
                            setMonthSchedule(data);
                        }
                    });
                }else{

                }
            }

            // setMonthSchedule to the pignoseCalendar
            function setMonthSchedule(month_schedule) {
                // pignose-calendar-unit-date looping
                $('.pignose-calendar-unit-date').each(function() {
                    // get today's data
                    var date = $(this).attr('data-date');
                    // find date schedule list
                    var my_schedule = month_schedule[date];
                    // checking
                    if(my_schedule != undefined){
                        // checking
                        if(my_schedule.event){
                            $(this).children().addClass( "bg-gray text-white" );
                        }else if(my_schedule.holiday){
                            $(this).children().addClass( "bg-blue text-white" );
                        }else if(my_schedule.week_off_day){
                            $(this).children().addClass( "bg-red text-white" );
                        }
                    }
                });
            }

            // This use for DEMO page tab component.
            $('.menu .item').tab();
            // Add minus icon for collapse element which is open by default
            $(".collapse.in").each(function(){
                $(this).siblings(".panel-heading").find(".glyphicon").addClass("glyphicon-minus").removeClass("glyphicon-plus");
            });

            // Toggle plus minus icon on show hide of collapse element
            $(".collapse").on('show.bs.collapse', function(){
                $(this).parent().find(".glyphicon").removeClass("glyphicon-plus").addClass("glyphicon-minus");
            }).on('hide.bs.collapse', function(){
                $(this).parent().find(".glyphicon").removeClass("glyphicon-minus").addClass("glyphicon-plus");
            });
        });
    </script>

@stop

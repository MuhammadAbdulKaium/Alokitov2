
@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
    <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <style type="text/css">
        #full-calendar .popover {
            max-width:400px;
            width:400px;
        }
        #full-calendar .popover-content {
            padding: 0px;
        }

        #bd-list .product-img img {
            height: 44px;
            width: 45px;
        }
        .small-box p {
            font-size: 20px;
            font-weight: bold;
        }

        .bg-midblack{
            background: #54573A !important;
        }

        .bg-black {
            background: #2c3e50 !important;
        }

    </style>
@endsection

{{-- Content --}}
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-dashboard"></i>  {{trans('dashboard/index.dashboard')}} <!-- |<small> Employee </small>  -->       </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li class="active"></li>
            </ul>
        </section>

        <section class="content">
            <div class="callout callout-info show msg-of-day" style="background: #2c3e50 !important;">
                <h4><i class="fa fa-bullhorn"></i> {{trans('dashboard/index.messageof_day')}}</h4>
                <marquee onmouseout="this.setAttribute('scrollamount', 6, 0);" onmouseover="this.setAttribute('scrollamount', 0, 0);" scrollamount="6" behavior="scroll" direction="left">In real life, there is no such thing as second place. Either you are a winner, or you’re not.</marquee>
            </div>


            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{$totalStudent}}</h3>
                            <p>{{trans('dashboard/index.student')}}</p>
                        </div>
                        <div class="icon" style="color: #FFFFFF;">
                            <i class="fa fa-users" aria-hidden="true"></i>
                        </div>
                        <a href="/student/manage" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{$totalEmployee}}<sup style="font-size: 20px"></sup></h3>
                            <p>{{trans('dashboard/index.teacher_admin')}}</p>
                        </div>
                        <div class="icon" style="color: #FFFFFF;">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </div>
                        <a href="/employee/manage" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>44</h3>
                            <p>{{trans('dashboard/index.event')}}</p>
                        </div>
                        <div class="icon" style="color: #FFFFFF;">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua bg-midblack">
                        <div class="inner">
                            @if($totalSmsCreadit>0)
                                <h3>{{$totalSmsCreadit}}</h3>
                            @else
                                <h3>0</h3>
                            @endif
                            <p>{{trans('dashboard/index.sms_credit')}}</p>
                        </div>
                        <div class="icon" style="color: #FFFFFF;">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </div>
                        <a href="/communication/sms/sms_credit" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                {{--<!-- ./col -->--}}
                {{--<div class="col-lg-3 col-xs-6">--}}
                {{--<!-- small box -->--}}
                {{--<div class="small-box bg-red">--}}
                {{--<div class="inner">--}}
                {{--<h3>65</h3>--}}
                {{--<p>{{trans('dashboard/index.new_application')}}</p>--}}
                {{--</div>--}}
                {{--<div class="icon">--}}
                {{--<i class="ion ion-pie-graph"></i>--}}
                {{--</div>--}}
                {{--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<!-- ./col -->--}}
                {{--<div class="col-lg-3 col-xs-6">--}}
                {{--<!-- small box -->--}}
                {{--<div class="small-box bg-green">--}}
                {{--<div class="inner">--}}
                {{--<h3>{{trans('dashboard/index.learning')}}<sup style="font-size: 20px"></sup></h3>--}}
                {{--<p>Management System</p>--}}
                {{--</div>--}}
                {{--<div class="icon">--}}
                {{--<i class="ion ion-stats-bars"></i>--}}
                {{--</div>--}}
                {{--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<!-- ./col -->--}}
                {{--<div class="col-lg-3 col-xs-6">--}}
                {{--<!-- small box -->--}}
                {{--<div class="small-box bg-yellow">--}}
                {{--<div class="inner">--}}
                {{--<h3>{{trans('dashboard/index.general')}}</h3>--}}
                {{--<p>Settings</p>--}}
                {{--</div>--}}
                {{--<div class="icon">--}}
                {{--<i class="ion ion-person-add"></i>--}}
                {{--</div>--}}
                {{--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<!-- ./col -->--}}
                {{--<div class="col-lg-3 col-xs-6">--}}
                {{--<!-- small box -->--}}
                {{--<div class="small-box bg-red">--}}
                {{--<div class="inner">--}}
                {{--<h3>65</h3>--}}
                {{--<p>Social Learning</p>--}}
                {{--</div>--}}
                {{--<div class="icon">--}}
                {{--<i class="ion ion-pie-graph"></i>--}}
                {{--</div>--}}
                {{--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<!-- ./col -->--}}
            </div>

            <div class="row">
                <section class="col-lg-7">
                    <div class="nav-tabs-custom" id="notice-board">
                        <ul class="nav nav-tabs pull-right flip">
                            <li class="pull-left flip header">
                                <i class="fa fa-inbox"></i>{{trans('dashboard/index.notice_board')}}
                            </li>
                            <div class="mobile-menu-notice nav nav-tabs">

                            @role(['parent', 'admin'])
                            <li class="pull-right flip">
                                <a href="#parent-notice" id="2" data-toggle="tab">{{trans('dashboard/index.parents')}}</a>
                            </li>
                            @endrole

                            @role(['teacher', 'admin'])
                            <li class="pull-right flip">
                                <a href="#emp-notice" id="3" data-toggle="tab">{{trans('dashboard/index.employee')}}</a>
                            </li>
                            @endrole

                            @role(['student', 'admin'])
                            <li class="pull-right flip">
                                <a href="#stu-notice" id="4" data-toggle="tab">{{trans('dashboard/index.student')}}</a>
                            </li>
                            @endrole

                            <li class="pull-right flip active">
                                <a href="#nb-general" id="1" data-toggle="tab">{{trans('dashboard/index.general')}}</a>
                            </li>
                            </div>

                        </ul>
                        <div class="tab-content" id="notice-data">
                            <div class="tab-pane active" id="nb-general">
                                <div class="alert bg-warning text-warning">
                                    No Notice....
                                </div>
                            </div>
                            <div class="tab-pane" id="stu-notice">
                                <div class="alert bg-warning text-warning">
                                    No Notice....
                                </div>
                            </div>
                            <div class="tab-pane" id="emp-notice" >
                                <div class="alert bg-warning text-warning">
                                    No Notice....
                                </div>
                            </div>
                            <div class="tab-pane" id="parent-notice">
                                <div class="alert bg-warning text-warning">
                                    No Notice....
                                </div>
                            </div>
                        </div>
                        <!--  /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                    <div class="box box-solid" id="full-calendar">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-calendar"></i> {{trans('dashboard/index.calender')}}</h3>
                            <div class="box-tools pull-right">
                                <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                                <button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="w0" class="fullcalendar" data-plugin-name="fullCalendar">
                                <div class="fc-loading" style="display:none;">Loading ...</div>
                            </div>
                        </div>
                        <div class="overlay fc-loading" style='display:none;'>
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <ul class="legend col-sm-12 col-xs-12">
                                    <li>
                                        <span style="background-color:#148f14;"></span> Orientation Program </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box -->

                </section>

                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-5 connectedSortable">

                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-calendar-o"></i>
                                Today's Attendance(%)
                            </h3>
                        </div>

                        <div class="row">
                            <div class="col-sm-8">
                                <div class="chart">
                                    <canvas id="totalAttendanceChart"></canvas>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                @if(!empty($attendanceInfo))
                                    <P>
                                        <strong>Present:</strong><br/>{{$attendanceInfo->total_present_percentage}} %<br/>
                                        <strong>Absent:</strong><br/>{{$attendanceInfo->total_absent_percentage}} %<br/>

                                        <strong> Male Present:</strong><br/>{{$attendanceInfo->male_present_percentage}} %<br/>
                                        <strong> Female Present:</strong><br/>{{$attendanceInfo->female_present_percentage}} %<br/>
                                    </P>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="nav-tabs-custom" id="bd-list" style="min-height: 160px;">
                        <ul class="nav nav-tabs pull-right flip">
                            <li class="pull-right flip">
                                <a href="#birth-upcoming" data-toggle="tab">Upcoming</a>
                            </li>
                            <li class="active pull-right flip">
                                <a href="#birth-today" data-toggle="tab">Today</a>
                            </li>
                            <li class="pull-left flip header">
                                <i class="fa fa-birthday-cake"></i>{{trans('dashboard/index.birthday')}}    </li>
                        </ul>
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
                    </div>

                    <!-- /.nav-tabs-custom -->
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-calendar-o"></i> {{trans('dashboard/index.today_time_table')}}
                            </h3>
                        </div>
                        <div class="panel-group" id="accordion">
                            @php $levelCount =1;  @endphp
                            @foreach($allAcademicLevel as $levelProfile)
                                <div class="panel panel-default">
                                    <div class="panel-heading ">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$levelProfile->id}}">
                                                {{$levelProfile->level_name}}</a>
                                        </h4>
                                    </div>
                                    <div id="collapse{{$levelProfile->id}}" class="panel-collapse collapse {{$levelCount==1?'in':''}}">
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
                                                            <label class="control-label" for="section">Section</label>
                                                            <select id="section_{{$levelProfile->id}}" class="form-control academicSection" name="section">
                                                                <option value="" selected disabled>-Select Section-</option>
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
                                                            No Batch and Section found for this segment
                                                        </h4>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $levelCount =($levelCount+1);  @endphp
                            @endforeach
                        </div>
                        <!-- /.box-body -->
                    </div>




                    <!-- /.nav-tabs-custom -->
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <i class="ion ion-university"></i>
                            <h3 class="box-title"><i class="fa fa-graduation-cap"></i> {{trans('dashboard/index.course')}}   </h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" id="coursList">
                            <ul class="todo-list">
                                <li>
                                        <span class="handle">
                            <i class="fa fa-ellipsis-v"></i>
                            <i class="fa fa-ellipsis-v"></i>
                        </span>
                                    <span class="text hidden-xs">Preschool</span>
                                    <span class="text visible-xs-inline" title="Preschool">Preschool</span>
                                    <span class="notification-container pull-right text-teal" title="12 Students">
                            <i class="fa fa-users"></i>
                            <span class="label label-info notification-counter">12</span>
                                        </span>
                                </li>
                                <li>
                                        <span class="handle">
                            <i class="fa fa-ellipsis-v"></i>
                            <i class="fa fa-ellipsis-v"></i>
                        </span>
                                    <span class="text hidden-xs">Primary</span>
                                    <span class="text visible-xs-inline" title="Primary">Primary</span>
                                    <span class="notification-container pull-right text-teal" title="8 Students">
                            <i class="fa fa-users"></i>
                            <span class="label label-info notification-counter">8</span>
                                        </span>
                                </li>
                                <li>
                                        <span class="handle">
                            <i class="fa fa-ellipsis-v"></i>
                            <i class="fa fa-ellipsis-v"></i>
                        </span>
                                    <span class="text hidden-xs">Secondary</span>
                                    <span class="text visible-xs-inline" title="Secondary">Secondary</span>
                                    <span class="notification-container pull-right text-teal" title="12 Students">
                            <i class="fa fa-users"></i>
                            <span class="label label-info notification-counter">12</span>
                                        </span>
                                </li>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </section>
                <!-- right col -->
            </div>
            <!-- /.row (main row) -->
        </section>
    </div>
    <div class="modal" id="globalModal" tabindex="-1" role="dialog"  aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog">
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
    <script src="{{URL::asset('js/charts/chart.min.js')}}"></script>
    <script src="{{ asset('js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/fullcalendar.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrapx-clickover.js') }}" type="text/javascript"></script>
    <script type="text/javascript">

        jQuery(document).ready(function () {
            // Total Attendance Chart (pie)
            var totalAttendanceChartCtx = document.getElementById("totalAttendanceChart").getContext('2d');
            var totalAttendanceChartChart = new Chart(totalAttendanceChartCtx, {
                type: 'pie',
                data: {
                    labels: ["Present", "Absent"],
                    datasets: [{
                        backgroundColor: ["#F45B5B ", "#F7A35C "],
                        @if(!empty($attendanceInfo))
                        data: [{{$attendanceInfo->total_present_percentage}}, {{$attendanceInfo->total_absent_percentage}}],
                        @else
                        data: [0,0],
                        @endif
                    }]
                }
            });


            $('#notice-data').slimScroll({
                height: '350px'
            });

            var loading_container = jQuery('#w0 .fc-loading');
            jQuery('#w0').empty().append(loading_container);
            jQuery('#w0').fullCalendar({"loading":function(isLoading, view ) {
                $('.fc-loading').toggle(isLoading);
            },"fixedWeekCount":false,"weekNumbers":true,"editable":true,"eventLimit":true,"eventLimitText":"more Events","header":{"center":"title","left":"prev,next today","right":"month,agendaWeek,agendaDay"},"eventClick":function(event, jsEvent, view) {
                $('.fc-event').on('click', function (e) {
                    $('.fc-event').not(this).popover('hide');
                });
            },"eventRender":function (event, element) {
                var start_time = moment(event.start).format("DD-MM-YYYY, h:mm:ss a");
                var end_time = moment(event.end).format("DD-MM-YYYY, h:mm:ss a");

                element.clickover({
                    title: event.title,
                    placement: 'top',
                    html: true,
                    global_close: true,
                    container: '#full-calendar',
                    content: "<table class='table'><tr><th>Event Detail : </th><td>" + event.description + " </td></tr><tr><th> Event Type : </th><td>" + event.event_type + "</td></tr><tr><th> Start Time : </t><td>" + start_time + "</td></tr><tr><th> End Time : </th><td>" + end_time + "</td></tr></table>"
                });
            },"contentHeight":380,"timeFormat":"hh(:mm) A"}); /*,"events":"/dashboard/events/view-events"*/

            $('#timetableList').slimScroll({
                height: '300px'
            });


            $('#coursList').slimScroll({
                height: '250px'
            });

        });



        // notice

        //When page loads...
        $("ul.nav-tabs li:first").addClass("active").show(); //Activate first tab
        //        $(".tab-content:first").show(); //Show first tab content

        //On Click Event
        $("ul.nav-tabs li").click(function() {
            $("ul.nav-tabs li").removeClass("active"); //Remove any "active" class
            $(this).addClass("active"); //Add "active" class to selected tab
            $(".tab-pane").hide();

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

            $(activeTab).fadeIn(); //Fade in the active ID content
            return false;
        });


        $.ajax({
            url: '/communication/notice/all/1',
            type: 'GET',
            dataType: 'html',
            success: function(content) {
                $("#nb-general").html(content);
            }});



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
    </script>
@stop
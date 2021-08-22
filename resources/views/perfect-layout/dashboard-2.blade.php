@extends('layouts-2.master')

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
                <ul class="breadcrumb breadcrumb-section">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Student</a></li>
                    <li class="active">Accessories</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5 class="text-color-white"><i class="fa fa-bullhorn icon-margin"></i> Message of the day:</h5>
            </div>
        </div>
    </section>

    {{--<section class="breadcrumb-bg">--}}
        {{--<div class="container-fluid">--}}
            {{--<div class="col-md-6">--}}
                {{--<ul class="breadcrumb">--}}
                    {{--<li><a href="#">Home</a></li>--}}
                    {{--<li><a href="#">Student</a></li>--}}
                    {{--<li class="active">Accessories</li>--}}
                {{--</ul>--}}
            {{--</div>--}}
            {{--<div class="col-md-6">--}}
                {{--<h5 class="text-color-white"><i class="fa fa-bullhorn icon-margin"></i> Message of the day:</h5>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</section><!--breadcrumb and todayes news End-->--}}
    {{--<div class="clearfix"></div>--}}

    <section class="4-big-button">
        <div class="container-fluid">
            <div class="col-md-3 col-xs-6">
                <a href="#" class="button-wrap">
                    <div class="icon-wrap hidden-xs">
                        <p><i class=" fa fa-users"></i></p>
                    </div>
                    <div class="icon-containt-wrap">
                        <h1 class="text-center">{{$totalStudent}}</h1>
                        <p class="text-center">{{trans('dashboard/index.student')}}</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-xs-6">
                <a href="#">
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
                <a href="#">
                    <div class="icon-wrap hidden-xs">
                        <p><i class=" fa fa-calendar"></i></p>
                    </div>
                    <div class="icon-containt-wrap">
                        <h1 class="text-center">50</h1>
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

    <div class="clearfix"></div>
    <section class="notice-atten">
        <div class=" container-fluid">
            <div class="col-md-6">
                <div class="notice-bg">
                    <div class="nav-tab-bg">
                        <h5 class="div-head"><span class="glyphicon glyphicon-list-alt icon-margin"></span> {{trans('dashboard/index.notice_board')}}</h5>
                        <ul class="nav nav-tabs notice-tabs">
                            <li class="active"><a data-toggle="tab" id="1" href="#general"><div class="hidden-xs" >{{trans('dashboard/index.general')}}</div><div class="visible-xs">GN</div></a></li>
                            @role(['student', 'admin'])
                            <li><a data-toggle="tab" id="4" href="#student"><div class="hidden-xs" >{{trans('dashboard/index.student')}}</div><div class="visible-xs">ST</div></a></li>
                            @endrole
                            @role(['teacher', 'admin'])
                            <li><a data-toggle="tab" id="3" href="#employee"><div class="hidden-xs" >{{trans('dashboard/index.employee')}}</div><div class="visible-xs">EM</div></a></li>
                            @endrole
                            @role(['parent', 'admin'])
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
                <div class="course-wrap">
                    <div class="nav-tab-bg">
                        <h5><span class="glyphicon glyphicon-book icon-margin"></span> Course</h5>
                    </div>
                    <div class="custom-row">
                        <div class="col-xs-2 text-center"><span class="glyphicon glyphicon-th"></span></div>
                        <div class="col-xs-5"><p>Preschool</p></div>
                        <div class="col-xs-2 theme-color"><span class="glyphicon glyphicon-book"></span></div>
                        <div class="col-xs-3"><span class="badge">10</span></div>
                    </div>
                    <div class="custom-row">
                        <div class="col-xs-2 text-center"><span class="glyphicon glyphicon-th"></span></div>
                        <div class="col-xs-5"><p>Primary</p></div>
                        <div class="col-xs-2 theme-color"><span class="glyphicon glyphicon-book"></span></div>
                        <div class="col-xs-3"><span class="badge">10</span></div>
                    </div>
                    <div class="custom-row">
                        <div class="col-xs-2 text-center"><span class="glyphicon glyphicon-th"></span></div>
                        <div class="col-xs-5"><p>Secondary</p></div>
                        <div class="col-xs-2 theme-color"><span class="glyphicon glyphicon-book"></span></div>
                        <div class="col-xs-3"><span class="badge">10</span></div>
                    </div>
                    <div class="custom-row">
                        <div class="col-xs-2 text-center"><span class="glyphicon glyphicon-th"></span></div>
                        <div class="col-xs-5"><p>Higher Secondary</p></div>
                        <div class="col-xs-2 theme-color"><span class="glyphicon glyphicon-book"></span></div>
                        <div class="col-xs-3"><span class="badge">10</span></div>
                    </div>
                </div>
            </div><!--Courses Colum End-->
            <div class="col-md-6">
                <div class="birthday-bg">
                    <div class="nav-tab-bg">
                        <h5 class="div-head"><span class="glyphicon glyphicon-list-alt icon-margin"></span> Birth Day</h5>
                        <ul class="nav nav-tabs nav-tab-right birthday-tabs">
                            <li class="active"><a href="#birth-upcoming" data-toggle="tab">Upcoming</a></li>
                            <li><a href="#birth-today" data-toggle="tab">Today</a></li>
                        </ul>
                    </div><!--notice Navigation End-->
                    <div class="tab-content">
                        <div class="tab-pane" id="birth-today">
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
                        <div class="tab-pane active" id="birth-upcoming">
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

        </div><!--Courses container End-->







    </section><!--Courses Section End-->

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
    <script src="{{URL::asset('template-2/js/chart.min.js')}}"></script>
<script>

    $(document).ready(function () {


        // attendance chart
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
                    data: [0, 0],
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
        //<![CDATA[
        $(function() {
            $('#wrapper .version strong').text('v' + $.fn.pignoseCalendar.ComponentVersion);

            function onClickHandler(date, obj) {
                /**
                 * @date is an array which be included dates(clicked date at first index)
                 * @obj is an object which stored calendar interal data.
                 * @obj.calendar is an element reference.
                 * @obj.storage.activeDates is all toggled data, If you use toggle type calendar.
                 * @obj.storage.events is all events associated to this date
                 */

                var $calendar = obj.calendar;
                var $box = $calendar.parent().siblings('.box').show();
                var text = 'You choose date ';

                if(date[0] !== null) {
                    text += date[0].format('YYYY-MM-DD');
                    alert(text);
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
            }

            // Default Calendar
            $('.calendar').pignoseCalendar({
                select: onClickHandler,

            });

            // This use for DEMO page tab component.
            $('.menu .item').tab();
        });
        //]]>
    </script>


    <script>
        $(document).ready(function(){
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
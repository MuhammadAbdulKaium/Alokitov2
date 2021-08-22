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
    </style>
@endsection


{{-- Content --}}
@section('content')
    {{--student information--}}
    @php $stdUser = Auth::user(); @endphp
    @php $stdInfo = $stdUser->student(); @endphp
    @php $stdEnroll = $stdInfo->singleEnroll(); @endphp
    @php $stdSyllabus = $stdEnroll->academicSyllabus(); @endphp

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-dashboard"></i> Student Dashboard <!-- |<small> Employee </small>  -->       </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li class="active"></li>
            </ul>
        </section>
        <section class="content">
            {{--session msg--}}
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
                @php session()->forget('success'); @endphp
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="fa fa-times" aria-hidden="true"></i> {{ Session::get('warning') }} </h4>
                    @php session()->forget('warning'); @endphp
                </div>
            @endif
            {{--msg of the day--}}
            <div class="callout callout-info show msg-of-day"  style="background: #2c3e50 !important;">
                <h4><i class="fa fa-bullhorn"></i> Message of day</h4>
                <marquee onmouseout="this.setAttribute('scrollamount', 6, 0);" onmouseover="this.setAttribute('scrollamount', 0, 0);" scrollamount="6" behavior="scroll" direction="left">Life is an adventure in forgiveness.</marquee>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-md-2">
                                @if($stdInfo->singelAttachment("PROFILE_PHOTO"))
                                    <img class="center-block img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$stdInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:150px;height:150px">
                                @else
                                    <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:100px;height:100px">
                                @endif
                                <h2 class="text-success"><b>{{$stdInfo->user()->username}}</b></h2>
                            </div>
                            <div class="col-md-10">
                                <table class="table">
                                    <tr>
                                        <th>Name</th>
                                        <td colspan="5" ><a href="/student/profile/personal/{{$stdInfo->id}}">
                                                {{ $stdInfo->title." ".$stdInfo->first_name." ".$stdInfo->middle_name." ".$stdInfo->last_name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Gender:</th>
                                        <td>{{$stdInfo->gender }}</td>
                                        <th>Date of Birth:</th>
                                        <td>{{ date('d M, Y', strtotime($stdInfo->dob)) }}</td>
                                        <th>Bloodgroup:</th>
                                        <td>{{ $stdInfo->blood_group }}</td>
                                    </tr>
                                    <tr>
                                        <th>Merit Position:</th>
                                        <td>{{$stdEnroll->gr_no }}</td>
                                        <th>Enroll Detials:</th>
                                        @php
                                            $division = null;
                                            if($stdEnroll->batch()->get_division()){
                                                $division = ' ('.$stdEnroll->batch()->get_division()->name.') ';
                                            }
                                        @endphp
                                        <td>{{$stdEnroll->level()->level_name.' / '.$stdEnroll->batch()->batch_name.$division." - ".$stdEnroll->section()->section_name}}</td>
                                        <th>Academic Year:</th>
                                        <td>{{ $stdEnroll->academicsYear()->year_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address:</th>
                                        @if($address = $stdInfo->user()->singleAddress("STUDENT_PERMANENT_ADDRESS"))
                                            <td colspan="5">{{$address->address}}</td>
                                        @else
                                            <td colspan="5"> - </td>
                                        @endif
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>#</h3>
                            <p class="text-bold" style="font-size: 20px">Report Card</p>
                        </div>
                        <div class="icon" style="color: #FFFFFF;">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </div>
                        <a href="{{url('/student/report-card/'.$stdInfo->id)}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        {{--<a href="{{url('/academics/manage/assessments/report-card/download/'.$stdInfo->id)}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>#<sup style="font-size: 20px"></sup></h3>
                            <p class="text-bold" style="font-size: 20px">Assignments</p>
                        </div>
                        <div class="icon" style="color: #FFFFFF;">
                            <i class="fa fa-file-o" aria-hidden="true"></i>
                        </div>
                        <a href="{{$stdSyllabus?asset($stdSyllabus->syllabus_path):"#"}}" download class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>#</h3>
                            <p class="text-bold" style="font-size: 20px">Attendance</p>
                        </div>
                        <div class="icon" style="color: #FFFFFF;">
                            <i class="fa fa-users" aria-hidden="true"></i>
                        </div>
                        <a href="{{url('/student/attendance/info/'.$stdInfo->id)}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
{{--                        <a href="{{url('/student/report/profile/search/'.$stdInfo->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
                        {{--<a class="btn btn-app" >--}}
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>#</h3>
                            <p class="text-bold" style="font-size: 20px">Events</p>
                        </div>
                        <div class="icon" style="color: #FFFFFF;">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
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
                    <!-- /.row (main row) -->

                    <div class="box box-solid" id="full-calendar">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-calendar"></i> Calendar</h3>
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


                </div>

                <div class="col-md-6">

                    {{--attendance graph starts here--}}
                    <div class="box box-solid" style="height: 370px;">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-bar-chart"></i> Attendance Graph</h3>
                            <div class="box-tools pull-right">
                                <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                                <button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="academicYearMonth">Academic Months</label>
                                        <select id="academicYearMonth" class="form-control academicYearMonth">
                                            <option value="" selected disabled>-Select Monthly-</option>
                                            @php $monthList = ["January"=>'01', "February"=>'02', "March"=>'03', "April"=>'04', "May"=>'05', "Jun"=>'06', "July"=>'07',"August"=>'08',"September"=>'09', "October"=>'10', "November"=>'11', "December"=>'12'] @endphp
                                            @foreach($monthList as $key=>$value)
                                                <option value="{{$value}}" >{{$key}}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="chart">
                                        <canvas id="attendanceChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <P>
                                        <strong>Total Class(s):</strong><br/> <span id="month_total_class">N/A</span><br/>
                                        <strong>Present Class(s):</strong><br/> <span id="month_total_present_class">N/A</span><br/>
                                        <strong>Absent Class(s):</strong><br/> <span id="month_total_absent_class">N/A</span><br/>
                                        <strong>Present Class(%):</strong><br/> <span id="month_present_class_percentage">N/A</span><br/>
                                        <strong>Absent Class(%):</strong><br/> <span id="month_absent_class_percentage">N/A</span><br/>
                                    </P>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{--assessment graph starts here--}}
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-line-chart"></i> Assessment Graph</h3>
                            <div class="box-tools pull-right">
                                <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                                <button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="semesterSubject">Subject</label>
                                        <select id="semesterSubject" class="form-control semesterSubject" name="semesterSubject">
                                            <option value="" selected disabled>-Select Subject-</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="semesterGraph">Semester</label>
                                        <select id="semesterGraph" class="form-control semesterGraph" name="semesterGraph">
                                            <option value="" selected disabled>-Select Semester-</option>
                                            @foreach(getAcademicSemesters() as $semester)
                                                <option value="{{$semester->id}}" >{{$semester->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="chart">
                                <canvas id="assessmentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal"id="globalModal" tabindex="-1" role="dialog"  aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog" >
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
@endsection


{{-- Scripts --}}

@section('scripts')
    <script src="{{URL::asset('js/charts/chart.min.js')}}"></script>
    <script src="{{ asset('js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/fullcalendar.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrapx-clickover.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

            ////////////////// bar chart for attendance starts here //////////////////

            var attendanceChartCtx = document.getElementById("attendanceChart").getContext('2d');
            var genderChart = new Chart(attendanceChartCtx, {
                type: 'pie',
                data: {
                    labels: ["Present", "Absent"],
                    datasets: [{
                        backgroundColor: ["#00A65A", "#F45B5B"],
                        data: [0,100]
                    }]
                }
            });
//            var attendanceChartCtx = document.getElementById("attendanceChart").getContext('2d');
//            new Chart(attendanceChartCtx, {
//                type: 'bar',
//                data: {
//                    labels: ["January", "February", "March", "April", "May", "Jun", "July","August","September","October", "November", "December"],
//                    datasets: [{
//                        label: 'Present',
//                        data: [3, 5, 5, 5, 5, 5, 5, 4, 5, 4],
//                        backgroundColor: "#2B908F"
//                    }, {
//                        label: 'Absent',
//                        data: [2, 3, 4, 2, 1, 4, 5, 4, 5, 4],
//                        backgroundColor: "#F7A35C"
//                    }]
//                }
//
//            });

            ////////////////// Bar chart for attendance ends here //////////////////
            jQuery(document).on('change','.academicYearMonth',function(){

                var month_total_class = $('#month_total_class');
                var month_total_present_class = $('#month_total_present_class');
                var month_total_absent_class = $('#month_total_absent_class');
                var month_present_class_percentage = $('#month_present_class_percentage');
                var month_absent_class_percentage = $('#month_absent_class_percentage');


                // get academic semester id
                $.ajax({
                    url: "/academics/manage/attendance/month/graph",
                    type: 'GET',
                    cache: false,
                    data: {
                        'std_id':'{{$stdInfo->id}}',
                        'attendance_month': $(this).val()
                    },
                    datatype: 'application/json',

                    beforeSend: function() { },

                    success:function(responseData){

                        $("#attendanceChart").html('');
                        new Chart(attendanceChartCtx, {
                            type: 'pie',
                            data: {
                                labels: ["Present", "Absent"],
                                datasets: [{
                                    backgroundColor: ["#00A65A", "#F45B5B"],
                                    data: [responseData.present_percentage, responseData.absent_percentage]
                                }]
                            }
                        });
                        // set graph details
                        month_total_class.html(responseData.total);
                        month_total_present_class.html(responseData.present);
                        month_total_absent_class.html(responseData.absent);
                        month_present_class_percentage.html(responseData.present_percentage+' %');
                        month_absent_class_percentage.html(responseData.absent_percentage+' %');
                    },

                    error:function(responseData){
//                        alert(JSON.stringify(responseData));
                    }
                });
            });

            ////////////////// line chart for assessments starts here //////////////////
            $.ajax({
                url: "{{ url('/academics/find/subjcet') }}",
                type: 'GET',
                cache: false,
                data: {'class_id': '{{$stdEnroll->batch}}', 'section_id':'{{$stdEnroll->section}}'}, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    $('#semesterSubject').html("");
                },

                success:function(data){
                    var op = null;
                    op+='<option value="" selected disabled>--- Select Subject ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].sub_name+'</option>';
                    }
                    // set value to the academic batch
                    $('#semesterSubject').append(op);
                },
                error:function(){
                    // statements
                },
            });

            var assessmentChartCtx = document.getElementById("assessmentChart").getContext('2d');
            loadLineChart();

            // request for section list using batch id
            jQuery(document).on('change','.semesterSubject',function(){
                $('.semesterGraph option:first').prop('selected', true);
                loadLineChart();
            });

            jQuery(document).on('change','.semesterGraph',function(){
                // get academic semester id
                if($('#semesterSubject').val()){
                    $.ajax({
                        url: "{{ url('/academics/manage/assessments/semester/graph') }}",
                        type: 'GET',
                        cache: false,
                        data: {
                            'std_id': '{{$stdInfo->id}}',
                            'batch_id': '{{$stdEnroll->batch}}',
                            'section_id':'{{$stdEnroll->section}}',
                            'subject_id': $('#semesterSubject').val(),
                            'semester_id': $(this).val()
                        },
                        datatype: 'application/json',

                        beforeSend: function() { },

                        success:function(responseData){
                            if(responseData.status=='success'){
                                new Chart(assessmentChartCtx, {
                                    type: 'line',
                                    data: {
                                        labels: responseData.labels,
                                        datasets: [{
                                            backgroundColor: ["#FFFFFF"],
                                            borderColor: ["#000000"],
                                            lineTension:0,
                                            fill: false,
                                            label: 'Subject Assessment (%)',
                                            data: responseData.data
                                        }]
                                    },

                                });
                            }else{
                                alert(responseData.msg);
                                loadLineChart();
                            }
                        },

                        error:function(responseData){}
                    });
                }else{
                    alert('Please select a subject');
                }
            });

            function loadLineChart() {
                new Chart(assessmentChartCtx, {
                    type: 'line',
                    data: {
                        labels: [''],
                        datasets: [{
                            backgroundColor: ["#FFFFFF"],
                            borderColor: ["#000000"],
                            lineTension:0,
                            fill: false,
                            label: 'Subject Assessment (%)',
                            data: [100, 100]
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }

                });
            }

            ////////////////// line chart ends here //////////////////


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
            },"contentHeight":380,"timeFormat":"hh(:mm) A","events":"/dashboard/events/view-events"});

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
    </script>
@stop
@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
<link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet">
<style>
#full-calendar .popover {
                max-width:400px;
                width:400px;
            }
            #full-calendar .popover-content {
                padding: 0px; }

                #bd-list .product-img img {
        height: 44px;
        width: 45px;
    }
</style>
@stop


{{-- Content --}}
@section('content')
           <div class="content-wrapper">
            <section class="content-header">
                <h1>
            <i class="fa fa-dashboard"></i> Dashboard |<small> Employee </small>        </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li class="active">Employee Dashboard</li>
                </ul>
            </section>
            <section class="content">
                <div class="callout callout-info show msg-of-day">
                    <h4><i class="fa fa-bullhorn"></i> Message of day</h4>
                    <marquee onmouseout="this.setAttribute('scrollamount', 6, 0);" onmouseover="this.setAttribute('scrollamount', 0, 0);" scrollamount="6" behavior="scroll" direction="left">Life is an adventure in forgiveness.</marquee>
                </div>
                <div class="row">
                    <section class="col-lg-7">
                        <div class="nav-tabs-custom" id="notice-board">
                            <ul class="nav nav-tabs pull-right flip">
                                <li class="pull-left flip header">
                                    <i class="fa fa-inbox"></i>Notice Board </li>
                                <li class="pull-right flip">
                                    <a href="#parent-notice" data-toggle="tab">Parent</a>
                                </li>
                                <li class="pull-right flip">
                                    <a href="#emp-notice" data-toggle="tab">Employee</a>
                                </li>
                                <li class="pull-right flip">
                                    <a href="#stu-notice" data-toggle="tab">Student</a>
                                </li>
                                <li class="pull-right flip active">
                                    <a href="#nb-general" data-toggle="tab">General</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="notice-data">
                                <div class="tab-pane active" id="nb-general">
                                    <ul class="products-list product-list-in-box">
                                        <li class="item">
                                            <div class="fa-stack fa-lg pull-left" aria-hidden="true">
                                                <i class="fa fa-circle fa-stack-2x img-circle bg-aqua"></i>
                                                <i class="fa fa-thumb-tack fa-rotate-270 fa-stack-1x text-aqua"></i>
                                            </div>
                                            <div class="product-info">
                                                <a class="product-title" href="/dashboard/notice/view-popup?id=4" data-target="#globalModal" data-toggle="modal">
                                        Metting                                         <span class="text-muted pull-right">
                                            <i class="fa fa-calendar"></i> Sep 22, 2016                                        </span>
                                    </a>
                                                <span class="product-description">
                                        Board Metting for Employees and Parents.                                     </span>
                                            </div>
                                        </li>
                                        <li class="item">
                                            <div class="fa-stack fa-lg pull-left" aria-hidden="true">
                                                <i class="fa fa-circle fa-stack-2x img-circle bg-aqua"></i>
                                                <i class="fa fa-thumb-tack fa-rotate-270 fa-stack-1x text-aqua"></i>
                                            </div>
                                            <div class="product-info">
                                                <a class="product-title" href="/dashboard/notice/view-popup?id=3" data-target="#globalModal" data-toggle="modal">
                                        Global Event on Sports                                        <span class="text-muted pull-right">
                                            <i class="fa fa-calendar"></i> Sep 7, 2016                                        </span>
                                    </a>
                                                <span class="product-description">
                                        Sports Event in Next Week. Interested student complete registration process.                                     </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane" id="stu-notice">
                                    <div class="alert bg-warning text-warning">
                                        1No Notice.... </div>
                                </div>
                                <div class="tab-pane" id="emp-notice">
                                    <div class="alert bg-warning text-warning">
                                        No Notice.... </div>
                                </div>
                                <div class="tab-pane" id="parent-notice">
                                    <div class="alert bg-warning text-warning">
                                        No Notice.... </div>
                                </div>
                            </div>
                            <!--  /.tab-content -->
                        </div>
                        <!-- /.nav-tabs-custom -->
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
                        <!-- /.box -->
                    </section>
                    <!-- /.Left col -->
                    <!-- right col (We are only adding the ID to make the widgets sortable)-->
                    <section class="col-lg-5 connectedSortable">
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">
            <i class="fa fa-calendar-o"></i> Today's Timetable        </h3>
                            </div>
                            <div class="box-body box-comments dashBoardList" id="timetableList">
                                <div class='alert bg-warning text-warning'>
                                    No lecture for today. </div>
                            </div>
                            <!---/. box-body--->
                            <div class="box-footer text-center">
                                <a class="small-box-footer pull-right btn-default btn-sm" href="/timetable/timetable-details/emp-daily-timetable?id=1" style="font-size:13px" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
                            <!-- /.box-footer -->
                        </div>
                        <!---/. box--->
                        <div class="nav-tabs-custom" id="bd-list">
                            <ul class="nav nav-tabs pull-right flip">
                                <li class="pull-right flip">
                                    <a href="#birth-upcoming" data-toggle="tab">Upcoming</a>
                                </li>
                                <li class="active pull-right flip">
                                    <a href="#birth-today" data-toggle="tab">Today</a>
                                </li>
                                <li class="pull-left flip header">
                                    <i class="fa fa-birthday-cake"></i>Birthdays </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="birth-today">
                                    <div class="alert bg-warning text-warning">
                                        No Birthday Today </div>
                                </div>
                                <div class="tab-pane" id="birth-upcoming">
                                    <div class="alert bg-warning text-warning">
                                        No Birthday within 30 days duration </div>
                                </div>
                            </div>
                            <!--  /.tab-content -->
                        </div>
                        <!-- /.nav-tabs-custom -->
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <i class="ion ion-university"></i>
                                <h3 class="box-title"><i class="fa fa-graduation-cap"></i> Courses</h3>
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
                                    <li>
                                        <span class="handle">
                            <i class="fa fa-ellipsis-v"></i>
                            <i class="fa fa-ellipsis-v"></i>
                        </span>
                                        <span class="text hidden-xs">Computer Fundamentals</span>
                                        <span class="text visible-xs-inline" title="Computer Fundamentals">CF</span>
                                        <span class="notification-container pull-right text-teal" title="5 Students">
                            <i class="fa fa-users"></i>
                            <span class="label label-info notification-counter">5</span>
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
@stop

{{-- Scripts --}}

@section('scripts')
<script src="{{ asset('js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/fullcalendar.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/bootstrapx-clickover.js') }}" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function () {

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
},"contentHeight":380,"timeFormat":"hh(:mm) A","events":"/dashboard/events/view-events"});

    $('#timetableList').slimScroll({
        height: '300px'
    });


                $('#coursList').slimScroll({
                    height: '250px'
                });
            
});
</script> 
@stop
@extends('layouts.master')

{{-- Web site Title --}}


@section('styles')

<link href="{{ asset('css/html5input.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/kv-widgets.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/spectrum.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/spectrum-kv.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet">
@stop

<style>
    #full-calendar .popover {
        max-width: 400px;
        width: 400px;
    }
    
    #full-calendar .popover-content {
        padding: 0px;
    }
    </style>
    <style>
    .popover {
        max-width: 450px;
    }
    </style>
    <script type="text/javascript">
    var kvPalette_01af55ff = [
        ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(255, 255, 255)"],
        ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)", "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
        ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)"],
        ["rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)"],
        ["rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)"],
        ["rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)", "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)"],
        ["rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
    ];
    var spectrum_659a43ea = {
        "showInput": true,
        "showInitial": true,
        "showPalette": true,
        "showSelectionPalette": true,
        "showAlpha": true,
        "allowEmpty": true,
        "preferredFormat": "hex",
        "theme": "sp-krajee",
        "palette": kvPalette_01af55ff
    };
    </script>


{{-- Content --}}
@section('content')
            <div class="content-wrapper">
            <section class="content-header">
                <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Events</small>        </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Academics</a></li>
                    <li class="active">Event Management</li>
                </ul>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-3">
                        <div class="box box-solid" id="full-calendar">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Event Type</h3>
                            </div>
                            <div class="box-body">
                                <div class="input-group">
                                    <form id="event-type-form" action="/dashboard/event-type/create" method="post">
                                        <input type="hidden" name="_csrf" value="Yi1wUy5ncnEVbxUxRS0fKAhnJwJlKRgXBWsvJ28eNTshQ0ckHA88Jw==">
                                        <div class="form-group field-eventtype-et_name required">
                                            <label class="control-label" for="eventtype-et_name">Event Type</label>
                                            <input type="text" id="eventtype-et_name" class="form-control" name="EventType[et_name]" maxlength="80" placeholder="Enter Event Type" aria-required="true">
                                            <div class="help-block"></div>
                                        </div>
                                        <div class="form-group field-form-color-code required">
                                            <label class="control-label" for="form-color-code">Color Code</label>
                                            <!--[if lt IE 10]>
<input type="text" id="form-color-code" class="spectrum-input form-control" name="EventType[et_color_code]" placeholder="Select Color..." aria-required="true">
<br><div class="alert alert-warning">It is recommended you use an upgraded browser to display the text control properly.</div>
<![endif]-->
                                            <![if gt IE 9]>
                                            <div class="spectrum-group input-group input-group-html5"><span id="form-color-code-cont" class="kv-center-loading input-group-sp input-group-addon addon-text" style="width:60px"><input type="text" id="form-color-code-source" class="spectrum-source" name="form-color-code-source" style="display:none"></span>
                                                <input type="text" id="form-color-code" class="spectrum-input form-control" name="EventType[et_color_code]" placeholder="Select Color..." aria-required="true">
                                            </div>
                                            <![endif]>
                                            <div class="help-block"></div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-create">Create</button>
                                            <button type="reset" class="btn btn-default btn-create">Reset</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /input-group -->
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h4 class="box-title"><i class="fa fa-th-list"></i> Manage Event Types</h4>
                            </div>
                            <div class="box-body" id="event-type-box">
                                <div id='external-events'>
                                    <div class='external-event' style="background-color:#148f14;  cursor: auto;">
                                        <a href="/dashboard/event-type/update?id=1" title="Click name to Edit" style="color:#FFF;" data-target="#globalModal" data-toggle="modal">Orientation Program</a>
                                        <a class="label label-danger pull-right" href="/dashboard/event-type/delete?id=1" title="Remove/Delete Event type" data-confirm="Are you sure you want to delete this item?" data-method="post" style="font-size: 15px;"><i class="fa fa-trash-o"></i></a> </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /. box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="box box-primary">
                            <div class="box-body no-padding">
                                <!-- THE CALENDAR -->
                                <div id="w1" class="fullcalendar" language="en" data-plugin-name="fullCalendar">
                                    <div class="fc-loading" style="display:none;">Loading ...</div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /. box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
                <div id="eventModal" class="fade modal" role="dialog" tabindex="-1">
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3>Add Event</h3>
                            </div>
                            <div class="modal-body">
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                .fc-content {
                    white-space: normal!important;
                }
                </style>
            </section>
        </div>
@stop

{{-- Scripts --}}

@section('scripts')
<script src="{{ asset('js/kv-widgets.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/spectrum.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/spectrum-kv.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/fullcalendar.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/bootstrapx-clickover.js') }}" type="text/javascript"></script>

<script type="text/javascript">jQuery(document).ready(function () {
jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
kvInitHtml5('#form-color-code','#form-color-code-source');
if (jQuery('#form-color-code').data('spectrum')) { jQuery('#form-color-code').spectrum('destroy'); }
jQuery.when(jQuery("#form-color-code-source").spectrum(spectrum_659a43ea)).done(function(){jQuery("#form-color-code-source").spectrum('set',jQuery("#form-color-code").val());jQuery("#form-color-code-cont").removeClass('kv-center-loading');});

jQuery('#event-type-form').yiiActiveForm([{"id":"eventtype-et_name","name":"et_name","container":".field-eventtype-et_name","input":"#eventtype-et_name","enableAjaxValidation":true,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":""});yii.validation.string(value, messages, {"message":"Event Type must be a string.","max":80,"tooLong":"Event Type should contain at most 80 characters.","skipOnEmpty":1});}},{"id":"eventtype-et_color_code","name":"et_color_code","container":".field-form-color-code","input":"#form-color-code","enableAjaxValidation":true,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":""});yii.validation.string(value, messages, {"message":"Color Code must be a string.","max":50,"tooLong":"Color Code should contain at most 50 characters.","skipOnEmpty":1});}}], []);
var loading_container = jQuery('#w1 .fc-loading');
jQuery('#w1').empty().append(loading_container);
jQuery('#w1').fullCalendar({"loading":function(isLoading, view ) {
            $('.fc-loading').toggle(isLoading);
        },"fixedWeekCount":false,"weekNumbers":true,"editable":true,"selectable":true,"eventLimit":false,"eventLimitText":"more Events","selectHelper":true,"displayEventTime":false,"displayEventTitle":false,"aspectRatio":1,"header":{"center":"title","left":"prev,next today","right":"month,agendaWeek,agendaDay"},"select":  function(start, end, allDay) {
        var start = moment(start).unix();
        var end = moment(end).unix();
        $.ajax({
            url: "/dashboard/events/add-event",
            data: { start_date : start, end_date : end },
            type: "GET",
            success: function(data) {
                $('.modal-content').html(data);
                $('#eventModal').modal();
            }
        });
    },"eventClick": function(calEvent, jsEvent, view) {
        var eventId = calEvent.id;
        $.ajax({
            url: "/dashboard/events/update-event",
            data: { id : eventId },
            type: "GET",
            success: function(data) {
                $('.modal-content').html(data);
                $('#eventModal').modal();
            }
        });
        $(this).css('border-color', 'red');
    },"eventRender":    function (event, element) {
        var start_time = moment(event.start).format("DD-MM-YYYY, hh:mm:ss a");
        var end_time = moment(event.end).format("DD-MM-YYYY, hh:mm:ss a");
        
        element.popover({
            title: event.title,
            placement: function (context, source) {
                var position = $(source).position();
                if (position.left > 320) {
                    return "auto left";
                }
                if (position.left < 320 && position.left > 100) {
                    return "auto right";
                }
                if (position.top < 110){
                    return "auto bottom";
                }
                return "top";
            },
            html: true,
            container: '#full-calendar',
            trigger: 'hover',
            delay: {"show": 500},
            content: "<table class='table'><tr><th>Event Detail : </th><td>" + event.description + " </td></tr><tr><th> Event Type : </th><td>" + event.event_type + "</td></tr><tr><th> Start Time : </t><td>" + start_time + "</td></tr><tr><th> End Time : </th><td>" + end_time + "</td></tr></table>"
        });
    },"timeFormat":"hh(:mm) A","events":"/dashboard/events/view-events"});
jQuery('#eventModal').modal({"show":false});
});</script>

@stop
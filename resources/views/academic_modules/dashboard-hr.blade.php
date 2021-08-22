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
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-dashboard"></i> HR Dashboard <!-- |<small> Employee </small>  -->       </h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li class="active"></li>
			</ul>
		</section>
		<section class="content">
			<div class="callout callout-info show msg-of-day"  style="background: #2c3e50 !important;">
				<h4><i class="fa fa-bullhorn"></i> Message of day</h4>
				<marquee onmouseout="this.setAttribute('scrollamount', 6, 0);" onmouseover="this.setAttribute('scrollamount', 0, 0);" scrollamount="6" behavior="scroll" direction="left">Life is an adventure in forgiveness.</marquee>
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
						<a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
						<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
						<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
				</div>
				<div class="col-md-6">
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
			</div>
			<!-- /.row (main row) -->

		</section>

	</div>

	<div class="modal modal-lg "id="globalModal" tabindex="-1" role="dialog"  aria-labelledby="esModalLabel" aria-hidden="true">
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
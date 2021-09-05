@extends('admin::layouts.master')

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
	<section class="4-big-button">
		<div class="container-fluid">
			<div class="col-md-3 col-xs-6">
				<a href="#">
					<div class="icon-wrap hidden-xs">
						<p><i class="fa fa-university"></i></p>
					</div>
					<div class="icon-containt-wrap">
						<h1 class="text-center">{{$instituteList->count()}}</h1>
						<p class="text-center">Institute</p>
					</div>
				</a>
			</div>
			<div class="col-md-3 col-xs-6">
				<a href="#" class="button-wrap">
					<div class="icon-wrap hidden-xs">
						<p><i class=" fa fa-users"></i></p>
					</div>
					<div class="icon-containt-wrap">
						<h1 class="text-center">{{$stdCount}}</h1>
						<p class="text-center">Student</p>
					</div>
				</a>
			</div>
			<div class="col-md-3 col-xs-6">
				<a href="#">
					<div class="icon-wrap hidden-xs">
						<p><i class=" fa fa-user"></i></p>
					</div>
					<div class="icon-containt-wrap">
						<h1 class="text-center">{{$employeeCount}}</h1>
						<p class="text-center">{{trans('dashboard/index.teacher_admin')}}</p>
					</div>
				</a>
			</div>
			<div class="col-md-3 col-xs-6">
				<a href="#">
					<div class="icon-wrap hidden-xs">
						<p><i class=" fa fa-envelope"></i></p>
					</div>
					<div class="icon-containt-wrap">
						<h1 class="text-center">{{$smsCredit}}</h1>
						<p class="text-center">{{trans('dashboard/index.sms_credit')}}</p>
					</div>
				</a>
			</div>
		</div>
	</section>
	{{--clearfix--}}
	<div class="clearfix"></div>

	<section>
		<div class="container-fluid">
			<div class="col-md-12">

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

				<div class="atten-bg">
					<div class="box-title institute-list-box">
						<h4><i class="fa fa-university"></i> Institute List ({{$instituteList->count()}})
							<a  id="update-guard-data" class="label label-primary pull-right" href="{{url('/admin/institute/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">
								<i class="fa fa-plus" aria-hidden="true"></i> Add Institute
							</a>
						</h4>
					</div>
					<div class="theme-style"></div>
					<div class="panel-group" id="accordion">
						@foreach($instituteList as $institute)
							{{--student list--}}
							@php $allStudent = $institute->student(); @endphp
							{{--staff list--}}
							@php $allStaff = $institute->staff(); @endphp
							{{--campus list--}}
							@php $allCampus = $institute->campus(); @endphp
							<div class="panel panel-default institute-panel">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="institute-name" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$institute->id}}">
											<span class="glyphicon glyphicon-plus icon-margin"></span> {{$institute->institute_name}}
										</a>
										<a style="margin-left: 10px;" class="label label-primary pull-right add-campus" href="{{url('/admin/institute/'.$institute->id.'/campus/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">
											<i class="fa fa-plus" aria-hidden="true"></i> Add campus
										</a>
										<span style="margin-left: 10px;" class="label label-success pull-right">{{$allCampus->count()}} campus</span>
										<span style="margin-left: 10px;" class="label label-success pull-right">{{$allStaff->count()}} staff</span>
										<span style="margin-left: 10px;" class="label label-success pull-right"> &nbsp;{{$allStudent->count()}} Student(s)</span>
									</h4>
								</div>
								<div id="collapse{{$institute->id}}" class="panel-collapse collapse">
									<div class="panel-body">
										<div class="row">
											{{--checking--}}
											@if($allCampus->count()>0)
												@php $i=1; @endphp
												<table class="table table-responsive table-striped text-center" style="font-size:15px">
													<thead>
													<tr>
														<th>#</th>
														<th>Campus Name</th>
														<th> Student(s)</th>
														<th> Staff(s)</th>
													</tr>
													</thead>
													<tbody>
													@foreach ($allCampus as $campus)
														<tr>
															<td>{{$i}}</td>
															<td class="text-left"><a href="/admin/institute/login/campus/{{$campus->id}}" target="_blank">{{$campus->name}}</a></td>
															<td>{{$campus->student()->count()}}</td>
															<td>{{$campus->staff()->count()}}</td>
														</tr>
														@php $i+=1; @endphp
													@endforeach
													</tbody>
												</table>
											@else
												<div class="panel-heading ">
													<h4 class="panel-title alert bg-warning text-warning">
														There is no campus in this institute
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
	</section>

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
@endsection

{{-- Scripts --}}
@section('scripts')
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
@endsection

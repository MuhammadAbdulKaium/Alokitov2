
@extends('layouts.master')

@section('styles')
	<!-- DataTables -->
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-th-list"></i> Manage | <small>Leave Application</small>
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Human Resource</a></li>
				<li><a href="#">Leave Management</a></li>
				<li class="active">Leave Application</li>
			</ul>
		</section>
		<section class="content">
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

			<section class="content">
				@if(count($leaveStructure)>0)
					<div class="box box-solid">
						<div class="box-header with-border">
							<h3 class="box-title">
								<i class="fa fa-list"></i> Leave Details | {{$leaveStructure->name}} ({{date('d M, Y', strtotime($leaveStructure->start_date))." - ". date('d M, Y', strtotime($leaveStructure->end_date))}})
							</h3>
						</div>
						<div class="box-body table-responsive">
							<table class="table table-bordered text-center">
								<tbody>
								<tr>
									<th width="15%">Structure Name</th>
									<th class="text-primary">Total</th>
									<th class="text-danger">Consumed</th>
									<th class="text-success">Available</th>
								</tr>
								<tr>
									<td class="text-center">
										{{$leaveStructure->name}}
										@if($leaveStructure->parent > 0) ({{$leaveStructure->myParent()->name}}) @endif
									</td>

									{{--leave counting--}}
									@php
										$totalLeave = $leaveStructureTypes->sum('leave_days');
										$consumedLeave = $allLeaveHistory->sum('approved_leave_days');
										$availableLeave = ($totalLeave-$consumedLeave);
									@endphp

									<td class="text-primary">{{$totalLeave}}</td>
									<td class="text-danger">{{$consumedLeave}}</td>
									<td class="text-success">{{$availableLeave}}</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
					{{--leave details--}}
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<ul class="nav nav-tabs">
										<li class="my-tab active"><a data-toggle="tab" href="#leave_application">Leave Application</a></li>
										<li class="my-tab"><a data-toggle="tab" href="#leave_history">Leave History (Approved)</a></li>
										<li class="my-tab"><a data-toggle="tab" href="#leave_entitlement">Leave Entitlement</a></li>
									</ul>
									{{--<hr/>--}}
									<br/>
									<div class="tab-content">
										{{--institute module--}}
										<div id="leave_application" class="tab-pane fade in active">
											<div class="box box-solid">
												<div class="extraDiv">
													<div class="box-header">
														<h3 class="box-title">
															<i class="fa fa-list-alt"></i> Leave Application
														</h3>
														<div class="box-tools">
															<a class="btn btn-success" href="{{url('/employee/leave/application/apply')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Apply Leave</a>
														</div>
													</div>
												</div>
												<div class="box-body">
													<div>
														<div id="w1" class="grid-view">
															<table id="example1" class="table table-striped table-bordered text-center">
																<thead>
																<tr>
																	<th>#</th>
																	<th>Application Date</th>
																	<th>Leave Type</th>
																	<th>Start Date</th>
																	<th>End Date</th>
																	<th>Leave Days</th>
																	<th>Leave Reason</th>
																	<th>Status</th>
																	<th>Action</th>
																</tr>
																</thead>
																<tbody>


																{{--loop counter--}}
																@php $loopCounter = 1; @endphp
																{{--looping--}}
																@foreach($allLeaveApplications as $application)
																	<tr>
																		<td>{{$loopCounter}}</td>
																		<td>{{date('d M, Y', strtotime($application->applied_date))}}</td>
																		<td><a title="View Leave Application" href="{{url('/employee/manage/leave/application/'.$application->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">{{$application->leaveType()->name}}</a></td>
																		<td>{{date('d M, Y', strtotime($application->start_date))}}</td>
																		<td>{{date('d M, Y', strtotime($application->end_date))}}</td>
																		<td>{{$application->leave_days}}</td>
																		@php
																			$leaveReason = $application->leave_reason;
																			$str = substr($leaveReason, 0, 50) . '...';
																		@endphp
																		<td>{{strlen($leaveReason)>30?$str:$leaveReason}}</td>
																		@php $applicationStatus = $application->status; @endphp
																		<td>
																			@if($applicationStatus>0)
																				<p class="label {{$applicationStatus=='1'?'label-success':'label-danger'}}">{{$applicationStatus=='1'?'Approved':'Rejected'}}</p>
																			@else
																				<p class="label label-primary application-status-{{$application->id}}">Pending</p>
																			@endif
																		</td>
																		<td>{{$application->id}}</td>
																	</tr>
																	{{--loop counter increment--}}
																	@php $loopCounter = ($loopCounter+1); @endphp
																@endforeach
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
										{{--leave history--}}
										<div id="leave_history" class="tab-pane fade in">
											<table class="table table-striped table-bordered text-center">
												<thead>
												<tr>
													<th>#</th>
													<th>Application Date</th>
													<th>Approved Date</th>
													<th>Leave Type</th>
													<th>Leave Days</th>
												</tr>
												</thead>
												<tbody>

												@if($allLeaveHistory->count()>0)
													{{--loop counter--}}
													@php $historyLoopCounter = 1; @endphp
													{{--looping--}}
													@foreach($allLeaveHistory as $leaveHistory)
														<tr>
															<td>{{$historyLoopCounter}}</td>
															<td>{{date('d M, Y', strtotime($leaveHistory->application()->created_at))}}</td>
															<td>{{date('d M, Y', strtotime($leaveHistory->approved_date))}}</td>
															<td><a title="View Leave Application" href="{{url('/employee/manage/leave/application/'.$leaveHistory->application_id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">{{$leaveHistory->leaveType()->name}}</a></td>
															<td>{{$leaveHistory->approved_leave_days}}</td>
														</tr>
														{{--loop counter increment--}}
														@php $historyLoopCounter = ($historyLoopCounter+1); @endphp
													@endforeach
													<tr>
														<td></td>
														<td></td>
														<td></td>
														<th class="bg-gray-active">Total</th>
														<th class="bg-gray-active">{{$consumedLeave}}</th>
													</tr>
												@else
													<tr>
														<td colspan="9">
															<div class="empty">No results found.</div>
														</td>
													</tr>
												@endif
												</tbody>
											</table>
										</div>
										{{--leave entitlement--}}
										<div id="leave_entitlement" class="tab-pane fade in">
											<table class="table table-striped table-bordered text-center">
												<thead>
												<tr>
													<th>#</th>
													<th>Structure Name</th>
													<th>Leave Type</th>
													<th>Leave Days</th>
													<th>Consumed Leave</th>
													<th>Available Days</th>
												</tr>
												</thead>
												<tbody>
												@if($leaveStructureTypes->count()>0)
													{{--loop counter--}}
													@php $loopCounter = 1; @endphp
													{{--looping--}}
													@foreach($leaveStructureTypes as $structureType)
														<tr>
															<td>{{$loopCounter}}</td>
															@php $myStructure = $structureType->structure(); @endphp
															<td class="text-center">
																{{$myStructure->name}}
																@if($myStructure->parent > 0)
																	({{$myStructure->myParent()->name}})
																@endif
															</td>

															<td>{{$structureType->leaveType()->name}}</td>
															@php $leaveDays = $structureType->leave_days @endphp
															<td>{{$leaveDays}}</td>
															@php $leaveConsumed = $structureType->leaveHistory($employeeProfile->id) @endphp
															<td>{{$leaveConsumed}}</td>
															<td>{{$leaveDays-$leaveConsumed}}</td>
														</tr>
														{{--loop counter increment--}}
														@php $loopCounter = ($loopCounter+1); @endphp
													@endforeach
													<tr>
														<td></td>
														<td></td>
														<th class="bg-gray-active">Total</th>
														<th class="bg-gray-active">{{$totalLeave}}</th>
														<th class="bg-gray-active">{{$consumedLeave}}</th>
														<th class="bg-gray-active">{{$availableLeave}}</th>
													</tr>
												@else
													<tr>
														<td colspan="9">
															<div class="empty">No results found.</div>
														</td>
													</tr>
												@endif
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				@else
					<div class="box box-solid">
						<div class="box-body">
							<h5><i class="fa fa-warning"></i> No Leave Allocation for this employee </h5>
						</div>
					</div>
				@endif
			</section>

			<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" id="modal-content">
						<div class="modal-body" id="modal-body">
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

@section('scripts')
	<!-- DataTables -->
	<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
	<!-- datatable script -->
	<script>

        jQuery(document).ready(function () {

            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

            // request for section list using batch and section id
            jQuery(document).on('change','.academicChange',function(){
                $('#leave-entitlement-list-container').html('');
            });


            // request for section list using batch and section id
            $('form#leave-entitlement-list-search-form').on('submit', function (e) {

                e.preventDefault();

                // ajax request
                $.ajax({
                    url: '/employee/manage/leave/entitlement/search',
                    type: 'GET',
                    cache: false,
                    data: $('form#leave-entitlement-list-search-form').serialize(),
                    datatype: 'html',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        var list_container =  $('#leave-entitlement-list-container');
                        list_container.html('');
                        list_container.append(data);
                    },

                    error:function(){
                        // statements
                    }
                });
            });
        });

	</script>
@endsection


@extends('layouts.master')

@section('styles')
	<!-- DataTables -->
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-th-list"></i> Manage | <small>Leave Application </small>
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Human Resource</a></li>
				<li><a href="#">Leave Management</a></li>
				<li class="active">Leave Entitlement</li>
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
				<div class="box box-solid">
					<div class="extraDiv">
						<div class="box-header">
							<h3 class="box-title">
								<i class="fa fa-list-alt"></i> Leave Status
							</h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-6">
									<div class="info-box">
										<p><b>Name: Shakib Uz-Zaman</b></p>
										<p><b>Designation: Teacher</b></p>
										<p><b>Total Leave: 20 Day(s)</b></p>
										<p><b>Remain Leave: 20 Day(s)</b></p>
									</div>
								</div>
								<div class="col-md-6">
									<h3 class="text-center">Leave List</h3>
									<table class="table table-bordered">
										<tr>
											<th>Casual Leave</th>
											<td>10</td>
										</tr>
										<tr>
											<th>Sick Leave</th>
											<td>10</td>
										</tr>
										<tr>
											<th>Maternity Leave</th>
											<td>0</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="extraDiv">
						<div class="box-header">
							<h3 class="box-title">
								<i class="fa fa-list-alt"></i> Leave Application
							</h3>
							<div class="box-tools">
								<a class="btn btn-success" href="{{url('/employee/leave/application/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Create Leave Application</a>
							</div>
						</div>
					</div>
					<div class="box-body">
						<div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="10000">
							<div id="w1" class="grid-view">
								<table id="example1" class="table table-striped table-bordered text-center">
									<thead>
										<tr>
											<th>#</th>
											<th>Leave Type</th>
											<th>Req. Date</th>
											<th>Req. From</th>
											<th>Req. To</th>
											<th>Req. For</th>
											<th>Approve Date</th>
											<th>Approve From</th>
											<th>Approve To</th>
											<th>Approve For</th>
											<th>Available Day</th>
											<th>Application Day</th>
											<th>Remains</th>
											<th>Status</th>
											<th class="action-column">Action</th>
										</tr>
									</thead>
									<tbody>
									@foreach($allLeaveApplications as $leave)
										<tr>
											<td>1</td>
											<td>{{$leave->leave_type}}</td>
											<td>{{$leave->applied_date}}</td>
											<td>{{$leave->req_start_date}}</td>
											<td>{{$leave->req_end_date}}</td>
											<td>{{$leave->req_for_date}}</td>
											<td>{{$leave->approve_day ==null ? 'N/A' : $leave->approve_day}}</td>
											<td>{{$leave->approve_start_date ==null ? 'N/A' : $leave->approve_start_date}}</td>
											<td>{{$leave->approve_end_date ==null ? 'N/A' : $leave->approve_end_date}}</td>
											<td>{{$leave->approve_for_date ==null ? 'N/A' : $leave->approve_for_date}}</td>
											<td>{{$leave->available_day}}</td>
											<td>{{$leave->approve_day}}</td>
											<td>{{$leave->remains}}</td>
											<td>{{$leave->status == 1 ? 'Pending' : ($leave->status == 2 ? 'Approved' : 'Reject')}}</td>
											<td>
												<a href="" class="btn btn-primary">Edit</a>
											</td>
										</tr>
									@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>



			<div id="leave-entitlement-list-container">
				{{-- leave-entitlement-list will be here --}}
			</div>


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
                "paging": false,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true
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

<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 8/16/17
 * Time: 2:13 PM
 */
?>
@extends('layouts.master')
@section('styles')
	<style>
		.menuDesign {
			margin: 1px;
		}
		.menuDesign:hover {
			background: #008d4c !important;
		}
	</style>
	<!-- DataTables -->
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
	<div class="content-wrapper">

		<section class="content-header">
			<h1>
				<i class="fa fa-th-list"></i> Manage |<small>Attendance</small>
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Human Resource</a></li>
				<li><a href="#">Employee Management</a></li>
				<li class="active">Attendance</li>
			</ul>
		</section>

		<section class="content">
			<div class="box-body">
				<div class="row">
					<div class="box box-solid">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-search"></i> View Attendance </h3>

							<a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/add-attendance" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								Add Attendance</a>
							<a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/upload-attendance" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								Upload Attendance File</a>
							<a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/employee-attendance/today"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								Today's Attendance</a>
							<a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/employee-monthly-attendance"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								Monthly Attendance</a>
							<a class="btn btn-success btn-sm pull-right menuDesign " href="/employee/employee-attendance"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								Attendance</a>

							<a class="btn btn-success btn-sm pull-right menuDesign " href="{{url('/employee/employee-attendance/custom')}}">
								<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Custom Attendance
							</a>
						</div>
						<form id="attendanceSearchForm">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<div class="box-body">
								<div class="row text-center">
									<div class="col-md-2 col-md-offset-3">
										<div class="form-group">
											<label class="control-label">Start Date-Time</label>
											<input type="text" id="start_date" required class="form-control access_date" name="start_date" readonly placeholder="Start Date">
											<input type="time" required class="form-control" name="start_time" placeholder="Start Time">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group ">
											<label class="control-label">End Date-Time</label>
											<input type="text" id="end_date" required class="form-control access_date" name="end_date" readonly placeholder="End Date">
											<input type="time" required class="form-control" name="end_time" placeholder="End Time">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group ">
											<label class="control-label" for="user_type" >User Type</label>
											<select id="user_type" class="form-control" name="user_type">
												<option value="">-- User Type --</option>
												<option value="e">Employee</option>
												<option value="s">Student</option>
											</select>
											<input type="text" class="form-control" name="access_id" placeholder="Last Access ID">
										</div>
									</div>
								</div>
							</div>
							<!-- ./box-body -->
							<div class="box-footer">
								<button type="reset" class="btn btn-default">Reset</button>
								<button type="submit" class="btn btn-success pull-right">Search</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div id="att-box" class="box box-solid hide">
				<div class="box-body table-responsive">
					{{--<div class="box-header" style="text-align: center"> </div>--}}
					<div class="box-body">
						<div id="attendance-table-container"></div>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
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
	</div>
@endsection

@section('scripts')
	<!-- DataTables -->
	<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
	<script src="{{ URL::asset('js/moment.min.js') }}"></script>
	<script src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
	<!-- datatable script -->
	<script>
        $(function () {
            $('.access_date').datepicker({format: 'dd-mm-yyyy'});

            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });


            // request for section list using batch id
            $('form#attendanceSearchForm').on('submit', function (e) {
                // alert('hello');
                e.preventDefault();

                // checking start and end date
	            if($('#start_date').val() && $('#end_date').val()){
                    // ajax request
                    $.ajax({
                        url: '/employee/employee-attendance/custom',
                        type: 'POST',
                        cache: false,
                        data: $('form#attendanceSearchForm').serialize(),
                        datatype: 'html',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // hide waiting dialog
                            waitingDialog.hide();
                            // att-box show
                            $('#att-box').removeClass('hide');
                            $('#attendance-table-container').html('');
                            $('#attendance-table-container').html(data);

                        },
                        error:function(){
                            // hide waiting dialog
                            waitingDialog.hide();
                            // sweet alert
                            swal("Error", 'Unable to load data form server', "error");
                        }
                    });
	            }else{
                    // sweet alert
                    swal("Warning", 'Please Check all inputs are selected', "Warning");
	            }
            });


        });

        jQuery(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
        });
	</script>
@endsection
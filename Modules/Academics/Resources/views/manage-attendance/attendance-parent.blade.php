
@extends('layouts.master')
<!-- page content -->
@section('content')

	<link href="{{ asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
	<style>
		body {
			margin: 0;
			padding: 0;
			font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
			font-size: 14px;
		}
		#calendar {
			margin: 50px auto;
		}
		.fc-content{
			text-align: center;
		}
		.fc-title{
			cursor: pointer;
		}
	</style>

	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-plus-square"></i> {{$studentProfile?'View Attendance List':'View Student Attendance List'}}
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Student</a></li>
				<li><a href="#">Manage Attendance</a></li>
				<li class="active">{{$studentProfile?'View Attendance List':'View Student Attendance List'}}</li>
			</ul>
		</section>
		<section class="content">
			@if(Session::has('success'))
				<div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
				</div>
			@elseif(Session::has('warning'))
				<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
				</div>
			@endif
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="box box-solid">
							<form id="std_attendance_search_form" method="POST">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input type="hidden" name="doc_type" value="html">
								<input type="hidden" name="request_type" value="view">
								<div class="box-body">
									<div class="row">
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label" for="std_id">{{$studentProfile?'Student Name':'Student List'}}</label>
												<select id="std_id" class="form-control change" name="std_id">
													@if($studentProfile == null)
														<option value="" selected disabled>-- Select Student --</option>
														@foreach($studentList as $student)
															@php $stdInfo = $student->myStudent(); @endphp
															<option value="{{$stdInfo->id}}" >{{$stdInfo->first_name." ".$stdInfo->middle_name." ".$stdInfo->last_name}}</option>
														@endforeach
													@elseif($studentList == null)
														<option value="{{$studentProfile->id}}" selected>{{$studentProfile->first_name." ".$studentProfile->middle_name." ".$studentProfile->last_name}}</option>
													@endif
												</select>
												<div class="help-block"></div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="academic_semester">Academic Semester</label>
												<select id="academic_semester" class="form-control change" required>
													<option value="" disabled selected>--- Select Semester ---</option>
													@foreach($semesterList as $semester)
														<option value="{{$semester->id}}" data-id="{{ date('m/d/Y', strtotime($semester->start_date)) }}" data-key="{{ date('m/d/Y', strtotime($semester->end_date)) }}">{{$semester->name}}</option>
													@endforeach
												</select>
												<div class="help-block"></div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="from_date">From Date</label>
												<input readonly class="form-control change pull-right" name="from_date" id="from_date" type="text">
												<div class="help-block"></div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="to_date">To Date</label>
												<input readonly class="form-control change pull-right" name="to_date" id="to_date" type="text">
												<div class="help-block"></div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="attendance_type">Attendance Type</label>
												<select id="attendance_type" class="form-control change" name="attendance_type" required>
													<option value="" disabled selected>--- attendance Type ---</option>
													<option value="att_school">School Attendance</option>
													<option value="att_class">Class Attendance</option>
												</select>
												<div class="help-block"></div>
											</div>
										</div>
									</div>
								</div>

								<div class="modal-footer">
									<button class="btn btn-info" type="submit">Submit</button>
									<a href="{{ url()->previous() }}" class="btn btn-primary pull-left">Back</a>
								</div>
							</form>
						</div>
					</div>

					<div id="attendanceContainer" class="row"></div>
				</div>
			</div>
		</section>
	</div>

	<!-- global modal -->
	<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
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

@section('scripts')
	<script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>

	<script type="text/javascript">
        $(function() { // document ready

            //From Date picker
            $('#from_date').datepicker({
                autoclose: true
            });

            // To Date picker
            $('#to_date').datepicker({
                autoclose: true
            });


            jQuery(document).on('change','#academic_semester',function(){
                $('#from_date').val($(this).find(':selected').attr('data-id'));
                $('#to_date').val($(this).find(':selected').attr('data-key'));
            });


            jQuery(document).on('change','.change',function(){
                $('#attendanceContainer').html('');
            });

            // set today in the date picker
            //$('#datepicker').val($.datepicker.formatDate('mm/dd/yy',  new Date(Date.now())));

            // request for section list using batch and section id
            $('form#std_attendance_search_form').on('submit', function (e) {
                // prevent default
                e.preventDefault();

                if($("#std_id").val() && $("#from_date").val() && $("#to_date").val()){

                    // ajax request
                    $.ajax({
                        url: "{{ url('/student/parent/attendance/show/attendance') }}",
                        type: 'POST',
                        cache: false,
                        data: $('form').serialize(),
                        datatype: 'html',
                        //datatype: 'application/json',

                        beforeSend: function() {
                            // hide waiting dialog
                            waitingDialog.show();
                        },

                        success:function(data){
                            if(data){
                                // hide waiting dialog
                                waitingDialog.hide();

                                $('#attendanceContainer').html('');
                                $('#attendanceContainer').append(data);
                            }
                        },
                        error:function(){
                            // hide waiting dialog
                            waitingDialog.hide();
                            // sweet alert
                            swal("Error", 'No response form server', "error");
                        }
                    });
                }else{
                    // sweet alert
                    swal("Warning", 'Please double check all inputs are selected.', "warning");
                }
            });
        });


	</script>
@endsection

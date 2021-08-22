
@extends('academics::manage-attendance.index')
@section('page-content')

	@php $batchString="Class"; @endphp
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

	<div class="row">
		<div class="col-md-12">
			<ul class="nav nav-pills">
				<li class="my-tab active" id="attendance_upload_history_tab"><a data-toggle="tab" href="#attendance_upload_history">Attendance Upload History</a></li>
				<li class="my-tab" id="attendance_upload_tab"><a data-toggle="tab" href="#attendance_upload">Attendance Upload</a></li>
			</ul>
			{{--<hr/>--}}
			<br/>
			<div class="tab-content">
				{{--attendance_upload_history--}}
				<div id="attendance_upload_history" class="tab-pane fade in active">
					<div class="box-body">
						<div class="row">
							@include('academics::manage-attendance.modals.upload-list-history')
						</div>
					</div>
				</div>
				{{--attendance_upload--}}
				<div id="attendance_upload" class="tab-pane fade in">
					<div class="row">
						<form id="std_attendance_upload_form" action="{{url('/academics/upload/attendance/upload')}}" method="POST" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<input type="hidden" name="request_type" value="UPLOAD">
							<div class="box-body">
								<div class="row">
									<div class="col-md-4 col-md-offset-1">
										<div class="form-group">
											<label class="control-label" for="date_picker">Date</label>
											<input readonly class="form-control date_picker pull-right" name="attendance_date" id="date_picker" type="text">
											<div class="help-block"></div>
										</div>
									</div>

									<div class="col-md-4 col-md-offset-1">
										<div class="form-group">
											<label class="control-label" for="attendance_list">Attendance File</label>
											<input class="form-control pull-right" name="attendance_list" id="attendance_list" type="file">
											<div class="hint-block">[<b>NOTE</b> : Only upload <b>.xlsx</b> file format.]</div>
											<div class="help-block"></div>
										</div>
									</div>
								</div>
							</div>
							<!-- ./box-body -->
							<div class="box-footer">
								<button type="button" id="std_attendance_upload_form_submit_btn"  class="btn btn-info pull-right">Submit</button>
								<button type="reset" class="btn btn-default">Reset</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="uploadAttendanceContainer" class="row">
	</div>

@endsection

@section('page-script')
	<script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
	{{--<script src="{{ asset('js/fc/moment.js') }}" type="text/javascript"></script>--}}
	<script src="{{asset('js/jquery.form.js')}}" type="text/javascript"></script>
	<script type="text/javascript">
        $(function() { // document ready

            //Date picker
            $('.date_picker').datepicker({
                autoclose: true
            });


            $('.my-tab').click(function () {
                $('#uploadAttendanceContainer').html('');
            });


            // request for section list using batch and section id
            $('#std_attendance_upload_form_submit_btn').click(function () {

                var attendance_date = $('#date_picker').val();
                var attendance_list = $('#attendance_list').val();
                // checking
                if(attendance_date && attendance_list){
                    // submit form
                    $("#std_attendance_upload_form").ajaxForm({

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // hide waiting dialog
                            waitingDialog.hide();
                            // checking
                            if(data){
                                // sweet alert
                                swal("Success", data.msg, "success");
                                // reload
                                setTimeout(function () {
                                    location.reload();
                                }, 2000);
                            }else{
                                // sweet alert
                                swal("Warning", data.msg, "warning");
                            }
                        },

                        error:function () {
                            // hide waiting dialog
                            waitingDialog.hide();
                            // sweet alert
                            swal("Error", 'Please check upload file format', "error");
                        }
                    }).submit();
                }else{
                    // sweet alert
                    swal("Warning", 'Please double check all inputs are selected.', "warning");
                }
            });

            // request for section list using batch and section id
            $('.attendanceUpload').click(function () {
                // file id
                 var my_id = $(this).attr('id');

                $.ajax({
                    url: "{{url('/academics/manage/attendance/file/upload/') }}",
                    type: 'POST',
                    cache: false,
                    data: {'file_id': my_id, '_token':'{{csrf_token()}}' }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Uploading ...');
                    },

                    success:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();

                        // checking
                        if(data.status=='success'){

                            swal("Success", data.msg, "success");
                            $("#"+my_id).hide();
                        }else{
                            // sweet alert
                            swal("Warning", data.msg, "warning");
                        }
                    },

                    error:function(){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });

            });

        });


	</script>
@endsection

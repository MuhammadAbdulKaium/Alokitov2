
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
				<i class="fa fa-plus-square"></i> {{$studentProfile?'View Report Card':'View Student Report Card'}}
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Student</a></li>
				<li><a href="#">Manage Assessment</a></li>
				<li class="active">{{$studentProfile?'View Report Card':'View Student Report Card'}}</li>
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
							<form id="std_report_card_search_form" method="POST">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="box-body text-center">
									<div class="row">
										<div class="col-md-6">
											<label class="control-label" for="std_id">{{$studentProfile?'Student Name':'Select Student'}}</label><br/>
											<div class="form-group">
												<select id="std_id" class="form-control" name="std_id">
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
										<div class="col-sm-4">
											<label class="control-label" for="">Report Type</label><br/>
											<label class="radio-inline"><input type="radio" name="report_format" value="0" required> Default </label>
											<label class="radio-inline"><input type="radio" name="report_format" value="1"> W/A (Detail) </label>
											<label class="radio-inline"><input type="radio" name="report_format" value="2"> W/A (Summary) </label>
										</div>
										<div class="col-md-2">
											<label class="control-label" for="view_report_card">Action</label><br/>
											<button id="view_report_card" style="padding-right: 55px; text-align:center" class="btn btn-primary pull-left"><i class="fa fa-click"></i> View Report Card</button>
										</div>
									</div>
								</div>
							</form>

						</div>
					</div>

					<div id="reportCardContainer" class="row"></div>
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
            // request for section list using batch and section id
            $('form#std_report_card_search_form').on('submit', function (e) {
                // alert('hello');
                e.preventDefault();

                if($('#std_id').val()){
                    // ajax request
                    $.ajax({
                        url: '/academics/manage/assessments/report-card/show/',
                        type: 'POST',
                        cache: false,
                        data: $('form#std_report_card_search_form').serialize(),
                        datatype: 'html',
                        // datatype: 'json/application',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // show waiting dialog
                            waitingDialog.hide();
                            // checking
                            if(data.length>0){
                                $('#reportCardContainer').html('');
                                $('#reportCardContainer').append(data);
                            }else{
                                // sweet alert
                                swal("Warning", 'No data response from the server', "warning");
                            }
                        },

                        error:function(data){
                            // hide waiting dialog
                            waitingDialog.hide();
                            // sweet alert
                            swal("Error", 'Unable to load data form server', "error");
                            // empty report card container
                            $('#std_report_card_row').html('');

                        }
                    });
                }else{
                    // clear report card row
                    $('#reportCardContainer').html('');
                    swal("Warning", 'Please select a student', "warning");
                }

            });
        });


	</script>
@endsection

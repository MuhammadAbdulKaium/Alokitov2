@if($studentList->count()>0)

	<style>

		.id-card-wrapper{
			/*border: 1px solid red;*/
			border-radius: 5px;
			margin-bottom: 5px;
			width: {{$width}}px;
			height: {{$height}}px;
			padding: 10px;
			background-color: gainsboro;
		}

		.inst-logo{

		}
		.inst-logo img{
			width: 55px;
			height:50px;
		}

		.inst-info{

		}

		.inst-name{
			font-size: {{$fontSize}}px;
		}

		.id-card-label {
			border: 1px solid black;
			border-radius: 2px;
			font-size: 15px;
			font-weight: 700;
			margin-top: 5px;
		}

		.std-info-section{

		}

		.std-info{
			font-size: 13px;
			padding-right: 0px;
			padding-left: 15px;
		}

		.std-photo{
			padding-right: 0px;
			padding-left: 0px;
		}

		.std-photo img{
			width: 80px;
			height: 90px;
		}

		.row-margin {
			margin-top: 10px;
		}
	</style>

	<div class="row">
		<div class="col-sm-12 text-center">
			<p class="bg-blue-active text-bold" style="padding: 5px">Class Section Student ID Card @if($studentList->count()>0)<span style="padding: 5px; cursor: pointer" id="download-class-section-std-id-card" class="pull-right label label-success text-bold">Download ID Card</span>@endif</p>

			{{--<button type="button" class="btn btn-success pull-right" id="download-class-section-std-id-card">Download</button>--}}
			{{--array type studentList--}}
			@php $myStudentList = $studentList->toArray(); @endphp
			{{--looping--}}
			@for($i=0; $i<count($myStudentList); $i=$i+2)
				<div class="row row-margin">
					{{--first div--}}
					<div class="col-md-5 col-md-offset-1">
						@if($i< count($myStudentList))
							{{--get single std--}}
							@php $stdInfo = $myStudentList[$i]; @endphp
							@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
							@php $studentEnroll = $studentInfo->enroll(); @endphp
							{{--std ID Card--}}
							<div class="id-card-wrapper">
								<div class="row inst-info-section text-center">
									<div class="col-sm-2 inst-logo">
										@if($instituteInfo->logo)
											<img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
										@endif
									</div>
									<div class="col-sm-10 inst-info">
										<b class="inst-name">{{$instituteInfo->institute_name}}</b> <br/>
										{{$instituteInfo->address1}}
										{{--{{'Address: '.$instituteInfo->address1}}<br/>--}}
										{{--{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}--}}
									</div>
								</div>
								<p class="id-card-label">Student ID Card</p>
								<div class="row std-info-section">
									<div class="col-sm-8 std-info text-left">
										<p>
											<b>Name</b>: {{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}<br/>
											<b>Class Roll</b>: {{$studentInfo->enroll()->gr_no}}<br/>
											<b>Academic Year</b>: {{$studentEnroll->academicsYear()->year_name}}<br/>
											<b>Academic Level</b>: {{$studentEnroll->level()->level_name}}<br/>
											<b>Class</b>: {{$studentEnroll->batch()->batch_name}}<br/>
											<b>Section</b>: {{$studentEnroll->section()->section_name}}
										</p>
									</div>
									<div class="col-sm-4 std-photo">
										{{--sort std profile--}}
										@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
										{{--check std profile photo--}}
										@if($studentInfo->singelAttachment('PROFILE_PHOTO'))
											<img src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
										@else
											<img  src="{{asset('/assets/users/images/user-default.png')}}">
										@endif
									</div>
								</div>
							</div>
						@endif
					</div>
					{{--second div--}}
					<div class="col-md-5 col-md-offset-1">
						@if($i+1<count($myStudentList))
							@php $stdInfo = $myStudentList[$i+1]; @endphp
							@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
							@php $studentEnroll = $studentInfo->enroll(); @endphp
							{{--std ID Card--}}
							<div class="id-card-wrapper">
								<div class="row inst-info-section text-center">
									<div class="col-sm-2 inst-logo">
										@if($instituteInfo->logo)
											<img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
										@endif
									</div>
									<div class="col-sm-10 inst-info">
										<b class="inst-name">{{$instituteInfo->institute_name}}</b> <br/>
										{{$instituteInfo->address1}}
										{{--{{'Address: '.$instituteInfo->address1}}<br/>--}}
										{{--{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}--}}
									</div>
								</div>
								<p class="id-card-label">Student ID Card</p>
								<div class="row std-info-section">
									<div class="col-sm-8 std-info text-left">
										<p>
											<b>Name</b>: {{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}<br/>
											<b>Class Roll</b>: {{$studentInfo->enroll()->gr_no}}<br/>
											<b>Academic Year</b>: {{$studentEnroll->academicsYear()->year_name}}<br/>
											<b>Academic Level</b>: {{$studentEnroll->level()->level_name}}<br/>
											<b>Class</b>: {{$studentEnroll->batch()->batch_name}}<br/>
											<b>Section</b>: {{$studentEnroll->section()->section_name}}
										</p>
									</div>
									<div class="col-sm-4 std-photo">
										{{--sort std profile--}}
										@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
										{{--check std profile photo--}}
										@if($studentInfo->singelAttachment('PROFILE_PHOTO'))
											<img src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
										@else
											<img  src="{{asset('/assets/users/images/user-default.png')}}">
										@endif
									</div>
								</div>
							</div>
						@endif
					</div>
				</div>
			@endfor
		</div>
	</div>
	
	<script>
		$(document).ready(function () {
			$('#download-class-section-std-id-card').click(function () {
				// dynamic form
                $('<form id="std_id_card_download_form" action="/reports/student/id-card/download" method="POST"></form>')
                    .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                    .append('<input type="hidden" name="academic_year" value="'+$("#academic_year").val()+'"/>')
                    .append('<input type="hidden" name="academic_level" value="'+$("#academic_level").val()+'"/>')
                    .append('<input type="hidden" name="batch" value="'+$("#batch").val()+'"/>')
                    .append('<input type="hidden" name="section" value="'+$("#section").val()+'"/>')
                    .append('<input type="hidden" name="font_size" value="{{$fontSize}}"/>')
                    .append('<input type="hidden" name="width" value="{{$width}}"/>')
                    .append('<input type="hidden" name="height" value="{{$height}}"/>')
                    .append('<input type="hidden" name="margin_bottom" value="{{$margin_bottom}}"/>')
                    .append('<input type="hidden" name="request_type" value="pdf"/>').appendTo('body').submit();
                // remove form from the body
                $('#std_id_card_download_form').remove();
            });
        });
	</script>
	
@else
	<div class=" col-md-10 col-md-offset-1 text-center alert bg-warning text-warning" style="margin-bottom:0px;">
		<i class="fa fa-warning"></i> No record found.
	</div>
@endif


<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">

		/*institute part starts here*/
		#inst-photo{
			float:left;
			width: 15%;
		}
		#inst-info{
			float:left;
			width: 85%;
			font-size: 11px;
		}

		#inst-info b{
			font-size: 20px;
		}

		/*personal part starts here*/
		.applicant-info{
			float:left;
			width: 80%;
		}
		.applicant-photo{
			float:left;
			width: 20%;
		}

		/*signature part starts here*/
		.signature{
			margin-top: 10px;
		}

		/* section for all parts*/
		.container{
			width: 100%;
		}

		.section{
			padding-bottom: 5px;
			clear: both;
		}

		.text-center{
			text-align: center;
		}

		.label{
			width: 100%;
			text-align: center;
			color: white;
			font-weight: 700;
			background-color: grey;
		}

		.pull-left{
			float: left;
		}

		.pull-right{
			float: right;
		}

		.col-md-12{
			width: 100%;
		}

		.col-md-6{
			width: 50%;
		}

		.col-md-3{
			width: 25%;
		}

		.table-custom{
			width: 100%;
			border: 1px solid #dddddd;
			border-collapse: collapse;
		}

		.table {
			background-color: transparent;
			border-collapse: collapse;
			border-spacing: 0;
			margin-bottom: 20px;
			max-width: 100%;
			width: 100%;
		}

		.table-bordered {
			border: 1px solid #f4f4f4;
		}

		.table-striped > tbody > tr:nth-of-type(2n+1) {
			background-color: #f9f9f9;
		}

		.table > thead > tr > th,
		.table > tbody > tr > th,
		.table > tfoot > tr > th,
		.table > thead > tr > td,
		.table > tbody > tr > td,
		.table > tfoot > tr > td {
			padding: 2px;
			line-height: 1;
			vertical-align: top;
		}

		.admit-card{
			border: 1px solid black;
			border-radius: 8px;
			font-size: 20px;
			font-weight: 700;
			line-height: 25px;
			margin: auto;
			text-align: center;
			width: 500px;
		}

		.instruction {
			font-size: 13px;
		}

		html{
			margin: 25px;
		}
	</style>
</head>
<body>
<div class="container">
	{{--Institute info part--}}
	<div class="section text-center">
		{{--institute photo--}}
		<div id="inst-photo">
			@if($instituteInfo->logo)
				<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:100px;height:100px">
			@else
				<img src="{{public_path().'/assets/users/images/user-default.png'}}"  style="width:100px;height:100px">
			@endif
		</div>
		{{--institute info--}}
		<div id="inst-info">
			<b>{{$instituteInfo->institute_name}}</b><br/>
			{{'Address: '.$instituteInfo->address1}}<br/>
			{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
		</div>
	</div>

	<div class="section"> <hr> </div>

	{{--personal info part--}}
	<div class="section">
		<p class="text-center admit-card">Admit Card for Admission Test</p>
	</div>
	{{--personal info part--}}
	<div class="section">
		@php $examDetails = $applicantProfile->examDetails();  @endphp
		{{--applicant information--}}
		<div class="applicant-info">
			<table class="table table-striped table-bordered">
				<tbody>
				<tr>
					<th style="width: 150px">Application NO.</th>
					<td>{{$applicantProfile->application_no}}</td>
				</tr>
				<tr>
					<th style="width: 150px">Full Name</th>
					<td>{{$applicantProfile->name}}</td>
				</tr>
				<tr>
					<th style="width: 150px">Class</th>
					<td>{{$applicantProfile->batch()->batch_name}}</td>
				</tr>
				<tr>
					<th style="width: 150px">Gender</th>
					<td>{{$applicantProfile->gender==0?"Male":"Female"}}</td>
				</tr>
				<tr>
					<th style="width: 150px">Exam Date & Time</th>
					<td style="font-size: 13px">{{date('d M, Y', strtotime($examDetails->exam_date))}} AT {{$examDetails->exam_start_time." - ".$examDetails->exam_end_time}}</td>
				</tr>
				<tr>
					<th style="width: 150px">Exam Venue</th>
					<td>{{$examDetails->exam_venue}}</td>
				</tr>
				</tbody>
			</table>
		</div>
		{{--applicant photo--}}
		<div class="applicant-photo text-center">
			@if($profilePhoto = $applicantProfile->document('PROFILE_PHOTO'))
				<img src="{{URL::asset($profilePhoto->doc_path)}}"  style="width:125px;height:125px">
			@else
				<img src="{{public_path().'/assets/users/images/user-default.png'}}"  style="width:125px;height:125px">
			@endif
		</div>
	</div>

	<div class="section">
		<p class="label" >General instructions for applicants</p>
		<table id="instruction" class="table table-striped table-bordered">
			<tbody>
			<tr class="instruction">
				<th style="width: 10px">01</th>
				<td>Candidates must bring the ‘Admit Card’ and show it to the Invigilators on duty.</td>
			</tr>
			<tr class="instruction">
				<th style="width: 10px">02</th>
				<td>Candidates should bring Black Ballpoint Pen.</td>
			</tr>
			<tr class="instruction">
				<th style="width: 10px">03</th>
				<td>Candidates involved in unfair means/misconduct in examination hall, will be silently expelled.</td>
			</tr>
			<tr class="instruction">
				<th style="width: 10px">04</th>
				<td>Nobody is allowed to carry any mobile phone, Calculator, Digital Watch or any Electronic Device during exam.</td>
			</tr>
			<tr class="instruction">
				<th style="width: 10px">05</th>
				<td>
					Attendance sheets have been prepared with candidate’s photograph and specimen signature. Invigilator will check the candidates physically with both the photographs of admit card and attendance sheet. Candidate’s signature will also be checked by the invigilator in the same way.
				</td>
			</tr>
			</tbody>
		</table>
	</div>

	{{--Signature info part--}}
	<div class="section signature">
		<div class="text-center pull-left col-md-6">
			{{--...............................<br>--}}
			{{--<strong>Paid By</strong>--}}
		</div>
		<div class="text-center pull-right col-md-6">

			{{--checking auth sign--}}
			@if(isset($reportCardSetting->auth_sign) AND $reportCardSetting->auth_sign!=null AND !empty($reportCardSetting->auth_sign))
				<img src="{{public_path().'/assets/users/images/'.$reportCardSetting->auth_sign}}" style="height: 40px; padding: 0px; margin-bottom: -6px;">
			@endif
			<br>...............................<br>
			@if($applicantProfile->institute_id==29)
			<strong>Head Teacher</strong>
				@else
				<strong>Exam Controller</strong>
			@endif
		</div>
	</div>
</div>
</body>
</html>
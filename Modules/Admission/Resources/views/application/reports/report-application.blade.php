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
			float:right;
			width: 86%;
		}

		.applicant-photo{
			float:left;
			width: 12%;
		}

		/*signature part starts here*/
		.signature{
			margin-top: 50px;
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
			background-color: #00a65a;
			font-size: 15px;
		}

		.pull-left{
			float: left;
		}

		.pull-right{
			float: right;
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
			padding: 1px;
			line-height: 1;
			vertical-align: top;
		}

		body{
			font-size: 13px;
		}

		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
		}

		th, td {
			padding: 5px;
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

	{{--personal info part--}}
	<div class="section">
		@php $personalInfo = $applicantProfile->personalInfo();  @endphp
		<p class="label">Applicant Information</p>
		{{--applicant information--}}
		<div class="applicant-info">

			<table id="applicant-profile" style="width:100%; text-align: left;">
				<tr>
					<th width="120px">Student Name (English)</th>
					<th width="1px">:</th>
					<td colspan="4">{{$personalInfo->std_name}}</td>
				</tr>
				<tr>
					<th width="120px">Student Name (Bangla) </th>
					<th width="1px">:</th>
					<td colspan="4">{{$personalInfo->std_name_bn}}</td>
				</tr>
				<tr>
					<th>Father's name</th>
					<th>:</th>
					<td>{{$personalInfo->father_name}}</td>
					<th width="100px">Gender</th>
					<th>:</th>
					<td width="100px">{{$personalInfo->gender==0?"Male":'Female'}}</td>
				</tr>
				<tr>
					<th>Mother's Name</th>
					<th>:</th>
					<td>{{$personalInfo->mother_name}}</td>
					<th>Birth Date</th>
					<th width="1px">:</th>
					<td>{{date('d M, Y', strtotime($personalInfo->birth_date))}}</td>
				</tr>
				{{--<tr>--}}
				{{--<th>Nationality</th>--}}
				{{--<th>:</th>--}}
				{{--<td>{{$personalInfo->nationality()->nationality}}</td>--}}
				{{--<th>Religion</th>--}}
				{{--<th width="1px">:</th>--}}
				{{--<td>--}}
				{{--@php--}}
				{{--switch($personalInfo->religion) {--}}
				{{--case '1': echo "Islam"; break;--}}
				{{--case '2': echo "Hinduism"; break;--}}
				{{--case '3': echo "Christianity"; break;--}}
				{{--case '4': echo "Buddhism"; break;--}}
				{{--case '5': echo "Others"; break;--}}
				{{--}--}}
				{{--@endphp--}}
				{{--</td>--}}
				{{--</tr>--}}
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

	{{--Academic info part--}}
	<div class="section">
		<p class="label">Academic Information</p>
		{{--applicant enrollment--}}
		@php $enrollment = $applicantProfile->enroll(); @endphp
		<table border="1px solid" class="table-custom text-center">
			<tbody>
			<tr>
				<th>Academic Year</th>
				<th>Academic Level</th>
				<th>Batch</th>
			</tr>
			<tr>
				<td>{{$enrollment->academicYear()->year_name}}</td>
				<td>{{$enrollment->academicLevel()->level_name}}</td>
				<td>{{$enrollment->batch()->batch_name}}</td>
			</tr>
			@if($instituteInfo->id==27)
				{{--choice list--}}
				{{--@php--}}
					{{--$myBatch = $enrollment->batch();--}}
					{{--if ($myBatch->get_division()) {--}}
						{{--$myBatchName =$myBatch->get_division()->name;--}}
					{{--} else {--}}
						{{--$myBatchName = '';--}}
					{{--}--}}
				{{--@endphp--}}
				{{--choice list--}}
				@php $choiceList = json_decode($enrollment->choice_list); @endphp
				<tr>
					<td colspan="3">
						{{--<i style="font-size: 10px; margin-left: 10px;">1. {{$myBatchName}}</i>--}}
						{{--choice list looping--}}
						@foreach($choiceList as $index=>$batchId)
							@php
								$batch = $enrollment->batch($batchId);
								if ($batch->get_division()) {
									$batchName =$batch->get_division()->name;
								} else {
									$batchName = '';
								}
							@endphp
							<i style="font-size: 12px; padding-right: 20px;">{{$index}}.{{$batchName}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
						@endforeach
					</td>
				</tr>
			@endif
			</tbody>
		</table>
	</div>

	{{--Application info part--}}
	<div class="section">
		<p class="label">Application Information</p>
		<table border="1px solid" class="table-custom text-center">
			<tbody>
			<tr>
				<th>Application Date</th>
				<th>Application No</th>
				<th>Application Status</th>
				<th>Payment Status</th>
			</tr>
			<tr>
				<td>{{date('d M, Y', strtotime($applicantProfile->created_at))}}</td>
				<td>{{$applicantProfile->application_no}}</td>
				<td>
					{{--Applicant status--}}
					@php $applicationStatus = $applicantProfile->application_status; @endphp
					@if($applicationStatus==1)
						<span>Active</span>
					@elseif($applicationStatus==2)
						<span>Waiting</span>
					@elseif($applicationStatus==3)
						<span>Disapproved</span>
					@elseif($applicationStatus==4)
						<span>Approved</span>
					@else
						<span>Pending</span>
					@endif
				</td>
				<td> {{$applicantProfile->application_status==0?"UnPaid":"Paid"}}</td>
			</tr>
			</tbody>
		</table>
	</div>


	{{--Signature info part--}}
	<div class="section signature">
		{{--<div class="text-center pull-left col-md-6">--}}
		{{--...............................<br>--}}
		{{--<strong>Paid By</strong>--}}
		{{--</div>--}}
		<div class="text-center pull-right col-md-6">
			...................................................<br>
			<strong>Applicant Signature</strong>
		</div>
	</div>
</div>
</body>
</html>

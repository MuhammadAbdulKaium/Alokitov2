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
		}

		#inst-info b{
			font-size: 30px;
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
			margin-top: 100px;
		}

		/* section for all parts*/
		.container{
			width: 100%;
		}

		.section{
			padding-bottom: 20px;
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
			padding: 5px;
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
	@php $applicantAddress = $applicantProfile->address('PRESENT') @endphp
	<div class="section">
		<p>{{$applicantProfile->first_name." ".$applicantProfile->middle_name." ".$applicantProfile->last_name}}<br/>
		{{--<p>[Title]</p>--}}
		{{--<p>[Company Name]</p>--}}
		{{$applicantAddress->address}} <br/>{{$applicantAddress->city()->name.", ".$applicantAddress->state()->name.", ".$applicantAddress->country()->name.", ".$applicantAddress->zip}}</p>
		<p>Dear {{strtoupper($applicantProfile->middle_name)}}:</p>
		<p>We are pleased to inform you that you have been offered a spot in the class of {{date("Y")}} for the {{$instituteInfo->institute_name}}!</p>
		<p>After reviewing your application and all the supporting documents, we have determined that you are exactly the kind of student that we are looking for to carry on the {{$instituteInfo->institute_name}} tradition.</p>
		<p>Attached to this letter you will find a full admissions package, along with specific information on how to accept this offer. We ask that you respond within 4 weeks, as there are many other candidates who are waiting for any unaccepted spots! Once again, congratulations. We hope to hear from you soon!</p>
		<p>Sincerely, <br/>{{$instituteInfo->institute_name}}</p>
	</div>
</div>
</body>
</html>

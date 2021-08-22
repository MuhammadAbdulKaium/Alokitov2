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
			font-size: 25px;
		}

		/* section for all parts*/
		.container{
			width: 100%;
		}

		.section{
			padding-bottom: 10px;
			clear: both;
		}

		.text-center{
			text-align: center;
		}

		.label{
			width: 100%;
			text-align: center;
			font-weight: 700;
			padding: 5px;
			border: 1px solid black;
			border-radius: 5px;
		}


		.table {
			background-color: transparent;
			border-collapse: collapse;
			border-spacing: 0;
			margin-bottom: 20px;
			max-width: 100%;
			width: 100%;
			font-size: 13px;
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
		.table > tbody > tr > td,
		.table > tfoot > tr > td {
			padding: 5px;
			line-height: 1;
			vertical-align: top;
		}


		html{
			margin: 20px;
		}

		.table > thead > tr > th,.table > tbody > tr > th, .table > tbody > tr > td{
			line-height: 20px;
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

	{{--<div class="section"> <hr> </div>--}}

	{{--personal info part--}}
	<div class="section">
		<p class="text-center admit-card label">Admission Reports</p>
	</div>

	<div class="section">
		<table width="100%" border="1px solid" class="text-center  table table-bordered table-striped">
			<tbody>
			<tr>
				<th width="30%">Class</th>
				<th width="10%">Application(s)</th>
				<th>Paid</th>
				<th>Exam Fees</th>
				<th>Fees Collected</th>
				<th>Passed</th>
				<th>Admitted</th>
			</tr>
			{{--checking report summary--}}
			@if($admissionSummary)
				{{--reports summary calculation--}}
				@php $totalApplicant = 0; $totalPaidApplicant = 0; $totalFeesCollected = 0; $totalPassed = 0; $totalAdmitted = 0;@endphp
				{{--academic level list looping--}}
				@foreach($academicLevelList as $levelProfile)
					{{--find level report list--}}
					@if($levelReport = (array_key_exists($levelProfile->id, $admissionSummary)?$admissionSummary[$levelProfile->id]:[]))
						{{--find academic batch list--}}
						@php $batchList = $levelProfile->batch(); @endphp
						{{--batch list looping--}}
						@foreach($batchList as $bathProfile)
							{{--checking batch reports--}}
							@if($batchReport = (array_key_exists($bathProfile->id, $levelReport)?$levelReport[$bathProfile->id]:[]))
								<tr>
									<td>{{$batchReport['batch_name']}}</td>
									<td>{{$batchReport['total_application']}}</td>
									<td>{{$batchReport['paid_application']}}</td>
									<td>{{$batchReport['exam_fee']}}</td>
									<td>{{$batchReport['fees_collected']}}</td>
									<td>{{$batchReport['passed']}}</td>
									<td>{{$batchReport['admitted']}}</td>
								</tr>

								@php
									$totalApplicant += $batchReport['total_application'];
									$totalPaidApplicant += $batchReport['paid_application'];
									$totalFeesCollected += $batchReport['fees_collected'];
									$totalPassed += $batchReport['passed'];
								    $totalAdmitted += $batchReport['admitted'];
								@endphp

							@endif
						@endforeach
					@endif
				@endforeach
				<tr>
					<th>Total</th>
					<th>{{$totalApplicant}}</th>
					<th>{{$totalPaidApplicant}}</th>
					<th>-</th>
					<th>{{$totalFeesCollected}}</th>
					<th>{{$totalPassed}}</th>
					<th>{{$totalAdmitted}}</th>
				</tr>
			@else
				<tr><td colspan="7"> No Records Found.</td></tr>
			@endif
			</tbody>
		</table>
	</div>

</div>
</body>
</html>

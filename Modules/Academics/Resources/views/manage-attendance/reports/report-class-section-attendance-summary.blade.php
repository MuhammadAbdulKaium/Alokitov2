<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
	{{--checking report type--}}
	@if($reportType=='pdf')
		<style type="text/css">
			.label {font-size: 15px;  padding: 5px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
			.row-first{background-color: #b0bc9e;}
			.row-second{background-color: #e5edda;}
			.row-third{background-color: #00cc44;}
			.text-center {text-align: center; font-size: 12px}
			.text-left {text-align: left; font-size: 12px}
			.clear{clear: both;}

			#std-info {
				float:left;
				width: 79%;
			}
			#std-photo {
				float:left;
				width: 20%;
				margin-left: 10px;
			}
			#inst-photo{
				float:left;
				width: 15%;
			}
			#inst-info{
				float:left;
				width: 85%;
			}

			#inst{
				padding-bottom: 20px;
				width: 100%;
			}

			body{
				font-size: 10px;
				vertical-align: middle;
			}
			.report_card_table{
				border: 1px solid #dddddd;
				line-height: 8px;
				border-collapse: collapse;
			}

			html{
				margin: 20px;
			}
		</style>
	@endif
</head>


<body>

<div>
	{{--checking report type--}}
	@if($reportType=='pdf')
		<div id="inst" class="text-center clear" style="width: 100%;">
			<div id="inst-photo">
				@if($instituteInfo->logo)
					<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
				@endif
			</div>
			<div id="inst-info">
				<b style="font-size: 25px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
			</div>
		</div>
	@endif


	<div class="attendance clear">
		<br/>
		<table width="100%" border="1px solid black" class="report_card_table text-center" cellpadding="5">
			<thead>
			<tr class="row-second">
			<th colspan="7">{{$batchName.' - '.$sectionName}} ------  Attendance Summary Report ({{date('d M, Y', strtotime($fromDate)) .' - '.date('d M, Y', strtotime($toDate))}})</th>
			</tr>
			<tr class="row-second">
				<th width="3%">Roll</th>
				<th width="30%">Student Name</th>
				<th>Working Days</th>
				<th>Present Days</th>
				<th>Absent Days</th>
				<th>Percentage (%)</th>
				<th>Comments</th>
			</tr>
			</thead>
			<tbody>
			@foreach($studentArrayList as $index=>$stdProfile)
				{{--attendance calculation--}}
				@php
					// student profile array to object conversion
					$stdProfile = (object) $stdProfile;
					// student Name
					$stdName = (string) $stdProfile->name;
					// my attendance days
					$presentDays = $stdProfile->attendance;
					// working days
					$workingDays = $attendanceArrayList['working_days'];
					// absent days
					$absentDays = ($workingDays-$presentDays);
					// present percentage
					$presentPercentages = round((($presentDays/$workingDays)*100), 2, PHP_ROUND_HALF_UP);
				@endphp

				<tr>
					<td>{{$stdProfile->roll}}</td>
					<td class="text-left" style="padding-left: 10px;">{{$stdName}}</td>
					<th>{{$workingDays}}</th>
					<th>{{$presentDays}}</th>
					<th>{{$absentDays}}</th>
					<th>{{$presentPercentages}}</th>
					<th></th>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
</body>
</html>

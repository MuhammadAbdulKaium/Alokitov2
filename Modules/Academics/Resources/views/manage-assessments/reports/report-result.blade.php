<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Student Infromation -->
	<style type="text/css">
		.label {font-size: 15px;  padding: 5px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
		.row-first{background-color: #b0bc9e;}
		.row-second{background-color: #e5edda;}
		.row-third{background-color: #5a6c75;}
		.text-center {text-align: center; font-size: 12px}
		.clear{clear: both;}

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
			font-size: 11px;
		}
		.report_card_table{
			border: 1px solid #dddddd;
			border-collapse: collapse;
		}
	</style>
</head>
<body>
<div id="inst" class="text-center clear" style="width: 100%;">
	<div id="inst-photo">
		@if($instituteInfo->logo)
			<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
		@endif
	</div>
	<div id="inst-info">
		<b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
	</div>
</div>

<!-- attendance Report -->
<div class="clear">
	<p class="label row-second text-center">Result Details</p>
	<table width="100%" border="1px solid" class="text-center report_card_table" cellpadding="5">
		<thead>
		<tr class="text-center row-second">
			<th>Class</th>
			<th>Section</th>
			<th>Subject</th>
			<th>Semester</th>
			<th>Assessment</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>{{$assessmentInfo->class}}</td>
			<td>{{$assessmentInfo->section}}</td>
			<td>{{$assessmentInfo->subject}}</td>
			<td>{{$assessmentInfo->semester}}</td>
			<td>{{$assessmentInfo->assessment}}</td>
		</tr>
		</tbody>
	</table>
</div>


<!-- attendance Report -->
<div class="clear">
	@if(!empty($assMarks))
		<p class="label row-second text-center">Result Sheet ({{$listType}})</p>
		<table width="100%" border="1px solid" class="text-center report_card_table" cellpadding="5">
			<thead>
			<tr class="row-second">
				<th class="text-center">Std. ID</th>
				<th>Std. Name</th>
				<th class="text-center">Mark</th>
				<th class="text-center">Result</th>
			</tr>
			</thead>
			<tbody>
			@for($i=0; $i<count($studentList); $i++)
				@if(array_key_exists($studentList[$i]['id'], $assMarks))
					<tr>
						<td class="text-center">{{$studentList[$i]['id']}}</td>
						<td>{{$studentList[$i]['name']}}</td>
						@if(array_key_exists($studentList[$i]['id'], $assMarks))
							@php $stdMark = $assMarks[$studentList[$i]['id']]; @endphp
							<td class="text-center">{{$stdMark['ass_mark']}} / {{$stdMark['ass_points']}}</td>
							<td class="text-center">{{$stdMark['ass_result']}}</td>
						@else
							<td>N/A</td>
							<td>N/A</td>
						@endif
					</tr>
				@endif
			@endfor
			</tbody>
		</table>
	@else
		<p class="text-center row-second">No records found.</p>
	@endif
</div>

<div class="clear" style="width: 100%; margin-top: 50px;">
	<div style="float: left; width: 50%; text-align:center">
		...............................<br>
		<strong>Teacher</strong>
	</div>
	<div style="float: left; width: 50%; text-align:center;">
		...............................<br>
		<strong>Principal</strong>
	</div>
</div>

</body>
</html>

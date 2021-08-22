<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
		.label {font-size: 15px; padding: 5px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
		.row-first{background-color: #b0bc9e;}
		.row-second{background-color: #e5edda;}
		.row-third{background-color: #5a6c75;}
		.text-center {text-align: center; font-size: 12px}
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
		#std-photo img{
			width: 100%;
			height: 100%;
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
{{--			<img src="{{URL::asset('assets/users/images/'.$instituteInfo->logo)}}"  style="width:80px;height:80px">--}}
			<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
		@endif
	</div>
	<div id="inst-info">
		<b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
	</div>
</div>

<div class="clear">
	<p class="label text-center row-second">Class Section Details</p>
	<table width="100%" border="1px solid" class="report_card_table text-center" cellpadding="5">
		<thead>
		<tr>
			<th>Level : {{$classSectionInfo->level}} </th>
			<th>Class : {{$classSectionInfo->batch}}</th>
			<th>Section : {{$classSectionInfo->section}}</th>
		</tr>
		</thead>
	</table>
</div>

<!-- attendance Report -->
<p class="label text-center row-second">Student Absent Days Report</p>
<!-- subject wise attendance -->
<table width="100%" border="1px solid" class="report_card_table" cellpadding="5">
	<tbody>
	<tr class="row-second">
		<th class="text-center">Student Name</th>
		@foreach($academicSubjects as $key => $subject)
			<th class="text-center">{{$subject['name']}}</th>
		@endforeach
	</tr>
	@for($i=0; $i<count($studentList);$i++)
		<tr>
			<td class="text-center">{{$studentList[$i]['name']}}</td>

			@for($j =0; $j<count($academicSubjects);$j++)
				<td class="text-center">
					@if(array_key_exists('std_'.$studentList[$i]['id'], $attendanceList) == true)
						@php
							$result = $attendanceList['std_'.$studentList[$i]['id']]['sub_'.$academicSubjects[$j]['id']];
							$present = $result['P'];
							$absent = $result['A'];
						@endphp
						{{--checking present and absent result--}}
						@if($present==0 AND $absent==0)
							N/A
						@else
							{{--P: {{$present}}--}}
							A: {{$absent}}
						@endif
					@else
						N/A
					@endif
				</td>
			@endfor
		</tr>
	@endfor
	</tbody>
</table>
</body>
</html>

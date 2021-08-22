<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Student Infromation -->
	<style type="text/css">
		.label {font-size: 15px;  padding: 3px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
		.row-first{background-color: #b0bc9e;}
		.row-second{background-color: #e5edda;}
		.row-third{background-color: #5a6c75;}
		.text-center {text-align: center;}
		.clear{clear: both;}

		#std-info {
			float:left;
			width: 60%;
		}
		#std-photo {
			float:right;
			width: 30%;
			margin-left: 10px;
		}
		#inst-photo{
			float:left;
			width: 15%;
		}
		#inst-info{
			float:left;
			text-align: center;
			width: 85%;
		}

		#inst{
			padding-bottom: 3px;
			width: 100%;
		}

		body{
			font-size: 11px;
		}
		.report_card_table{
			border: 1px solid #dddddd;
			border-collapse: collapse;
			line-height: 12px;
		}

		/*th,td {line-height: 20px;}*/
		/*html{margin:25px}*/

	</style>
</head>
<body>
<div id="inst" class="text-center clear" style="width: 100%;">
	<div id="inst-photo">
		@if($instituteInfo->logo)
			<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:85px;height:85px; margin-bottom: 5px;">
		@endif
	</div>
	<div id="inst-info">
		<b style="font-size: 20px; margin-right: 100px">{{$instituteInfo->institute_name}}</b><br/>
		<span style="font-size:12px; margin-right: 100px">{{$instituteInfo->address1}}</span><br/>
	</div>
</div>


{{--{{dd($subjectHighestMarksList)}}--}}

<div class="clear">
	{{--checking semester result sheet--}}
	@if(count($semesterMeritList)>0)

		@php
			$passList = $semesterMeritList['pass_list'];
			$passMeritList = array_unique(array_values($passList));
			$failList = $semesterMeritList['fail_list'];
			$failSubList = $semesterMeritList['fail_sub_count'];
		@endphp

		{{--class subject list with student name--}}
		<table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5" style="font-size: 12px; line-height: 15px;">
			<thead>
			<tr>
				<th colspan="5"><b>{{$classInfo['batch_name']}} - {{$classInfo['section_name']}} {{$classInfo['semester_name']}} - Pass List</b></th>
			</tr>
			<tr>
				<th width="5%">Roll</th>
				<th width="15%">Student Name</th>
				<th width="15%"> Class - Section</th>
				<th width="15%">Obtained Marks</th>
				<th width="15%">Merit Position</th>
			</tr>
			</thead>
			<tbody>
			{{--{{dd($studentList)}}--}}
			{{--student list checking--}}
			@if(count($passList)>0)
				{{--student list checking--}}
				@foreach($passList as $stdId=>$obtainedMark)
					{{--student arry to object conversion --}}
					@php
						$student = (array_key_exists($stdId, $studentArrayList)?$studentArrayList[$stdId]:[]);
						$stdRoll = $student['roll'];
						$stdName = $student['name'];
						$batchSectionName = $student['batch_section'];
					@endphp
					{{--checking student in the pass list--}}
					@if(array_key_exists($stdId, $passList))
						<tr>
							<td>{{$stdRoll}}</td>
							<td width="30%" style="text-align: left; padding-left: 10px;">{{$stdName}}</td>
							<td>{{$batchSectionName}}</td>
							<td>{{($passList[$stdId]/100)}}</td>
							<td>{{array_search($passList[$stdId], $passMeritList)+1}}</td>
						</tr>
					@endif


				@endforeach
			@else
			@endif
			</tbody>
		</table>
		<div style="page-break-after:always;"></div>


		{{--class subject list with student name--}}
		<table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5" style="font-size: 12px; line-height: 15px;">
			<thead>
			<tr>
				<th colspan="5"><b>{{$classInfo['batch_name']}} - {{$classInfo['section_name']}} {{$classInfo['semester_name']}} - Fail List</b></th>
			</tr>
			<tr>
				<th width="5%">Roll</th>
				<th width="15%">Student Name</th>
				<th width="15%">Batch Section</th>
				<th width="15%">Obtained Marks</th>
				<th width="15%">Fail Subject(s)</th>
			</tr>
			</thead>
			<tbody>
			{{--{{dd($studentList)}}--}}
			{{--student list checking--}}
			@if(count($failSubList)>0)
				{{--student list checking--}}
				@foreach($failSubList as $stdId=>$subCount)
					{{--student arry to object conversion --}}
					@php
						$student = (array_key_exists($stdId, $studentArrayList)?$studentArrayList[$stdId]:[]);
						$stdRoll = $student['roll'];
						$stdName = $student['name'];
						$batchSectionName = $student['batch_section'];
					@endphp
					{{--checking student in the pass list--}}
					@if(array_key_exists($stdId, $failList))
						<tr>
							<td>{{$stdRoll}}</td>
							<td width="30%" style="text-align: left; padding-left: 10px;">{{$stdName}}</td>
							<td>{{$batchSectionName}}</td>
							<td>{{($failList[$stdId]/100)}}</td>
							<td>{{$subCount}}</td>
						</tr>
					@endif


				@endforeach
			@else
			@endif
			</tbody>
		</table>
		<div style="page-break-after:always;"></div>

	@else
		{{--semester restult not found msg--}}
		<p class="label row-first text-center">No Records found </p>
	@endif
</div>

</body>

</html>

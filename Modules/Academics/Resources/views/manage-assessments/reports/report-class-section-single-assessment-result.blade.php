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
		html{margin:10px}

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
		<b style="font-size: 25px; margin-right: 200px">{{$instituteInfo->institute_name}}</b><br/>
		<span style="font-size:12px; margin-right: 200px">{{'Address: '.$instituteInfo->address1}}</span><br/>
		<span style="font-size:12px;margin-right: 200px">{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}</span>
	</div>
</div>


{{--{{dd($subjectHighestMarksList)}}--}}

<div class="clear">
	<p class="label row-first text-center">{{$classInfo['batch_name']}} - {{$classInfo['section_name']}} ----- '{{$classInfo['assessment_name']}}' ----- Result Sheet ({{$classInfo['semester_name']}})</p>
	{{--checking semester result sheet--}}
	@if(count($semesterResultSheet)>0)
		{{--class subject list with student name--}}
		<table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5" style="font-size: 12px; line-height: 15px;">
			<thead>
			<tr>
				<th width="5%">Roll</th>
				<th width="15%">Student Name</th>
				{{--subject list checking--}}
				@if(count($classSubjects)>0)
					{{--subject list looping--}}
					@foreach($classSubjects as $index=>$subject)
						<th> {{$subject['name']}}</th>
					@endforeach
				@else
					<th>Subject Name</th>
				@endif
				<th>Total</th>
			</tr>
			</thead>
			<tbody>
			{{--student list checking--}}
			@if(count($studentList)>0)
				{{--student list checking--}}
				@foreach($studentList as $student)
					{{--student arry to object conversion --}}
					@php
						$stdTotalMark = 0;
						$student = (object)$student;
						$stdAddSubList = array_key_exists($student->std_id, $additionalSubjectList)? $additionalSubjectList[$student->std_id]:[];
					@endphp

					<tr>
						{{--student details--}}
						<td>{{$student->gr_no}}</td>
						<td>{{$student->first_name.' '.$student->middle_name.' '.$student->last_name}}</td>
						{{--subject list checking--}}
						@if(count($classSubjects)>0)
							{{--semester result checking for the student--}}
							@if(array_key_exists($student->std_id, $semesterResultSheet))
								{{--find student result sheet--}}
								@php $stdResultSheet = $semesterResultSheet[$student->std_id]; @endphp
								{{--subject list looping--}}
								@foreach($classSubjects as $subject)
									{{--subject arry to object conversion --}}
									@php $subject = (object)$subject; @endphp
									{{--student result sheet checking for subject--}}
									@if(array_key_exists($subject->cs_id, $stdResultSheet))
										{{--find subject result sheet--}}
										@php $subjectResultSheet = (object)$stdResultSheet[$subject->cs_id]; @endphp
										{{--checking--}}
										@if($subjectResultSheet->ass_points==1)
											<td>-</td>
										@else
											<td>{{round($subjectResultSheet->ass_mark, 2, PHP_ROUND_HALF_UP)}}</td>
											{{--calculate subject total mark--}}
											@php $stdTotalMark += round($subjectResultSheet->ass_mark, 2, PHP_ROUND_HALF_UP) @endphp
										@endif
									@else
										<td>-</td>
									@endif
								@endforeach
							@else
								<td colspan="{{count($classSubjects)}}">-</td>
							@endif
						@else
							<td> - </td>
						@endif
						<th>{{$stdTotalMark}}</th>
					</tr>
				@endforeach
			@else
			@endif
			</tbody>
		</table>
	@else
		{{--semester restult not found msg--}}
		<p class="label row-first text-center">No Records found </p>
	@endif
</div>

</body>

</html>

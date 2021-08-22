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


<div class="clear">
	<p class="label row-first text-center">{{$classInfo['batch_name']}} - {{$classInfo['section_name']}} - Final Result Sheet</p>
	{{--checking semester result sheet--}}
	@if(count($finalResultSheet)>0)
		{{--class subject list with student name--}}
		<table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5" style="font-size: 12px; line-height: 15px;">
			<thead>
			<tr class="row-first">
				<th width="3%">Roll</th>
				<th width="22%">Student Name</th>
				{{--subject list checking--}}
				@if(count($allSemester)>0)
					{{--subject list looping--}}
					@foreach($allSemester as $semester)
						<th> {{$semester['name']}}</th>
					@endforeach
				@else
					<th>Semester Name</th>
				@endif
				<th>Total Obtained</th>
				<th>Average <br/>{{count($allSemester)>0?'(Total / '.count($allSemester).')':''}}</th>
				<th>Percentage (%) </th>
				{{--<th>Letter Grade</th>--}}
				{{--<th>GPA</th>--}}
				<th>Merit (Section)</th>
				<th>Merit (Class)</th>
			</tr>
			</thead>
			<tbody>

			{{--{{dd($finalResultSheet)}}--}}

			{{--student list checking--}}
			@if(count($studentList)>0)
				{{--student list checking--}}
				@foreach($studentList as $student)
					{{--student arry to object conversion --}}
					@php
						$stdTotalMark = 0;
						$stdTotalObtainedMark = 0;
						$student = (object)$student;
						$stdFinalResultSheet = $finalResultSheet['status']=='success'?$finalResultSheet['std_list']:[];
						$stdSemResultSheet = (count($stdFinalResultSheet)>0 AND array_key_exists($student->id, $stdFinalResultSheet))?$stdFinalResultSheet[$student->id]['sem_result_summary_list']:[];
						$stdSectionFinalMeritList = $finalResultSheet['status']=='success'?$finalResultSheet['section_final_merit_list']['pass_list']:[];
						$stdSectionFinalFailList = $finalResultSheet['status']=='success'?$finalResultSheet['section_final_merit_list']['fail_sub_count']:[];
						$stdClassFinalMeritList = $finalResultSheet['status']=='success'?$finalResultSheet['class_final_merit_list']['pass_list']:[];

					@endphp
					<tr>
						{{--student details--}}
						<td>{{$student->gr_no}}</td>
						<td style="text-align: left; padding-left: 10px;">{{$student->name}}</td>
						{{--semester looping--}}
						@if(count($allSemester)>0)

							{{--subject list looping--}}
							@foreach($allSemester as $semester)
								@php $semesterId = $semester['id'];  @endphp
								{{--single semester result sheet--}}
								@php $semesterResultSheet = array_key_exists($semesterId, $stdSemResultSheet)?$stdSemResultSheet[$semesterId]:[] @endphp
								{{--checking semester result sheet--}}
								@if($semesterResultSheet)
									<td>{{$semesterResultSheet->total_obtained}}</td>
									{{--total marks calculation--}}
									@php
										$stdTotalMark += $semesterResultSheet->total_exam_marks;
										$stdTotalObtainedMark += $semesterResultSheet->total_obtained;
									@endphp
								@else
									<th>-</th>
								@endif
							@endforeach
						@else
							<th> - </th>
						@endif

						{{--semester average result--}}
						@php
							$semesterAvgMarks = count($allSemester)>0?($stdTotalObtainedMark/count($allSemester)):0;
							$percentage = $stdTotalMark>0?(round(($stdTotalObtainedMark/$stdTotalMark)*100, 2)):0;
							$gradeDetails = subjectGradeCalculation((int)$percentage, $gradeScaleDetails);
						@endphp

						<th>{{$stdTotalObtainedMark}}</th>
						<th>{{round($semesterAvgMarks, 2)}}</th>
						<th>{{$percentage}}</th>
						{{--<th>{{$gradeDetails?$gradeDetails['grade']:'N/A'}}</th>--}}
						{{--<th>{{$gradeDetails?$gradeDetails['point']:'N/A'}}</th>--}}
						@php
							$stdSectionFinalMeritList = array_unique(array_values($stdSectionFinalMeritList));
							$stdClassFinalMeritList = array_unique(array_values($stdClassFinalMeritList));

							$loopCounter = 0;
							$stdSectionMeritList = [];
							foreach ($stdSectionFinalMeritList as $index=>$stdMark){
								$stdSectionMeritList[$loopCounter] = $stdMark;
								$loopCounter += 1;
							}

							$loopCounter = 0;
							$stdClassMeritList = [];
							foreach ($stdClassFinalMeritList as $index=>$stdMark){
								$stdClassMeritList[$loopCounter] = $stdMark;
								$loopCounter += 1;
							}
						@endphp


						@php
							$myTotalObtainedMark = (int) round(($stdTotalObtainedMark*100), 2);
							// checking student section merit position
							if(in_array($myTotalObtainedMark, $stdSectionMeritList)){
								$stdSectionFinalMerit = (array_search($myTotalObtainedMark, $stdSectionMeritList)+1);
							}else{
								$stdSectionFinalMerit = '-';
							}

							// checking student class merit position
							if(in_array($myTotalObtainedMark, $stdClassMeritList)){
								$stdClassFinalMerit = (array_search($myTotalObtainedMark, $stdClassMeritList)+1);
							}else{
								$stdClassFinalMerit = '-';
							}
						@endphp
						{{--checking fail list--}}
						@if(array_key_exists($student->id, $stdSectionFinalFailList))
							<th><span style="color: red">F-{{($stdSectionFinalFailList[$student->id])}}</span></th>
						@else
							<th>{{$stdSectionFinalMerit}}</th>
						@endif
						<th>{{$stdClassFinalMerit}}</th>
						{{--dd($stdFinalMeritList);--}}
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

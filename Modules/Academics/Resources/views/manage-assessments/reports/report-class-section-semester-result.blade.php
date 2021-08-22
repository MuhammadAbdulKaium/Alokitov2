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
	<p class="label row-first text-center">{{$classInfo['batch_name']}} - {{$classInfo['section_name']}} {{$classInfo['semester_name']}} Result </p>
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
						<th> {{$subject['name']}} <br/>{{$subject['is_countable']==0?'(Uncountable)':''}}</th>
					@endforeach
					<th>Total</th>
					<th>%</th>
					{{--<th>GPA</th>--}}
					<th>Merit</th>
				@endif
			</tr>
			</thead>
			<tbody>
			{{--student list checking--}}
			@if(count($studentList)>0)
				{{--student list checking--}}
				@foreach($studentList as $student)
					{{--student arry to object conversion --}}
					@php $student = (object)$student; @endphp

					@php
						// semester subject highest marks sheet
						$meritList = $subjectHighestMarksList['merit_list'];
						// student additional subject list
						$stdAddSubList = array_key_exists($student->std_id, $additionalSubjectList)? $additionalSubjectList[$student->std_id]:[];
						// total exam marks
					    $totalClassMarks = 0;
					    // total obtained
					    $totalObtainedMarks = 0;
					    // total obtained
					    $totalFailedSubject = 0;
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
								@php
									$stdResultSheet = $semesterResultSheet[$student->std_id];
									$subjectGradeSheet = $stdResultSheet['grade']?$stdResultSheet['grade']:[];
									$stdTotalResult = (object)$stdResultSheet['result']?$stdResultSheet['result']:[];
								@endphp
								{{--subject list looping--}}
								@foreach($classSubjects as$subject)
									{{--subject arry to object conversion --}}
									@php $subject = (object)$subject; @endphp
									{{--student result sheet checking for subject--}}
									@if(array_key_exists($subject->cs_id, $subjectGradeSheet))
										{{--find subject result sheet--}}
										@php $subjectResultSheet = (object)$subjectGradeSheet[$subject->cs_id]; @endphp
										<td>
											@if($subjectResultSheet->letterGrade!='F')
												<span>{{round($subjectResultSheet->obtained, 2, PHP_ROUND_HALF_UP)}}</span>
											@else
												<span style="color: #ee5f5b">{{round($subjectResultSheet->obtained, 2, PHP_ROUND_HALF_UP)}}</span>
												@php $totalFailedSubject += ($subject->is_countable>0?1:0); @endphp
											@endif

											@php
												if($subject->is_countable>0){
												  $totalClassMarks += $subject->exam_mark;
												  $totalObtainedMarks += round($subjectResultSheet->obtained, 2, PHP_ROUND_HALF_UP);
												}
											@endphp

											{{--@if($subjectResultSheet->letterGrade!='F')--}}
											{{--<span> {{$subjectResultSheet->obtained}}</span>--}}
											{{--@else--}}
											{{--<span style="color: #ee5f5b"> {{$subjectResultSheet->obtained}}</span>--}}
											{{--@endif--}}
											{{--<span style="margin-left: 5px;">({{$subjectResultSheet->letterGrade}})</span>--}}
										</td>
									@else
										<td>-</td>
									@endif
								@endforeach
							@else
								<td colspan="{{count($classSubjects)}}">-</td>
							@endif
						@else
							<td>Subject Not Found</td>
						@endif

						{{--total marks--}}
						@php
							// $totalClassMarks = $stdTotalResult?($stdTotalResult['total_exam_marks']):0;
							// $totalObtainedMarks = $stdTotalResult?($stdTotalResult['total_obtained']):0;
							// $totalObtainedMarksPercentage = $stdTotalResult?($stdTotalResult['total_percent']):0;
							$totalObtainedMarksPercentage = round(($totalObtainedMarks/$totalClassMarks)*100, 2);
							$totalObtainedGPA = $stdTotalResult?($stdTotalResult['total_gpa']):0;

							// mark conversion (float to integer)
                            $myTotalMarks = (int) round($totalObtainedMarks*100);
						@endphp
						{{--student total percentage calculation--}}
						@php $percentage = $stdTotalResult?(round(($totalObtainedMarks*100)/$totalClassMarks, 2, PHP_ROUND_HALF_UP)):0; @endphp
						{{--Print total obtained marks--}}
						<td>{{round($totalObtainedMarks, 2, PHP_ROUND_HALF_UP)}}</td>
						{{--<td>{{$totalObtainedMarks.' /  '.$totalClassMarks}}</td>--}}
						<th>{{$totalObtainedMarksPercentage}}</th>
						{{--<th>{{$totalObtainedGPA}}</th>--}}
						{{--checking exam status--}}
						@if($examStatus->status==1 AND count($meritList)>0 AND in_array($myTotalMarks, $meritList))
							{{--set student merit list--}}
							<th>M-{{(array_search($myTotalMarks, array_unique(array_values($meritList)))+1)}}</th>
						@else
							<th>
								<i style="color: red">F-{{$totalFailedSubject}}</i>
							</th>
						@endif
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

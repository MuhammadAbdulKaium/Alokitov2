<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>
		.bg-gray {background-color: #d3d6df}
		.bg-green {background-color: green}
		.text-bold {font-weight: 700}
		.text-center {text-align: center}
		.label {font-size: 15px; border: 1px solid #000000; border-radius: 1px; margin-bottom: 5px;}
		.row{width: 100%; clear: both;}

		/*institute*/
		#inst-photo{ float:left; width: 15%; }
		#inst-info{ float:left; width: 85%; }

		/*student*/
		#std-info{float:left; width: 70%;}
		#grade-scale{float: right; width: 35%; margin-left:580px;}

		table{ border-collapse: collapse; text-align:center; }
		/*td{height: 10px}*/

		/*table*/
		.table{width: 100%; line-height: 13px; font-size: 11px;}
		/*student-profile-table*/
		#std-profile-table{font-size: 12px; text-align: left; line-height: 15px;}
		/*grade scale table*/
		#grade-sale-table{width: 60%; font-size:10px; border: 1px solid black; line-height: 12px}
		#grade-sale-table th, #grade-sale-table td{border: 1px solid black}

		/*report-first-table*/
		#report-first-table{border: 1px solid black; }
		#report-first-table th{ border: 1px solid black; }
		/*report-second-table*/
		#report-second-table{width: 100%; border-top: 1px solid black;}
		/*report-third-table*/
		#report-third-table{width: 100%; margin: 0 auto}


		#others-table{
			border: 1px solid black;
		}
		#others-table th{ border: 1px solid black; }


		.singnature{
			height: 30px;
			width: 100px;
		}

		.subject{
			text-align: left;
			padding-left: 10px;
		}

		html{margin:20px; margin-left: 30px; margin-right: 30px;}
		body{ font-size: 15px; }
	</style>
</head>
<body>

@php
	$finalMeritList = $examResultSheet['section_final_merit_list']['pass_list'];
	$classFinalMeritList = $examResultSheet['class_final_merit_list']['pass_list'];
	$stdSectionFinalMeritList = array_unique(array_values($finalMeritList));
	$stdClassFinalMeritList = array_unique(array_values($classFinalMeritList));

	$loopCounter = 0;
	$stdSectionMeritArrayList = [];
	foreach ($stdSectionFinalMeritList as $index=>$stdMark){
		$stdSectionMeritArrayList[$loopCounter] = $stdMark;
		$loopCounter += 1;
	}

	$loopCounter = 0;
	$stdClassMeritArrayList = [];
	foreach ($stdClassFinalMeritList as $index=>$stdMark){
		$stdClassMeritArrayList[$loopCounter] = $stdMark;
		$loopCounter += 1;
	}

@endphp

{{--checking studnet list--}}
@if(count($studentList)>0)

	{{--student list looping--}}
	@foreach($studentList as $student)
		{{--object conversion--}}
		@php
			$stdCount = 1;
			$student = (object)$student;
			$studentInfo = findStudent($student->id);
		@endphp
		{{--checking single student report card format--}}
		@if($stdId)
			{{--checking student id--}}
			@if($stdId!=$student->id)
				@continue
			@else

			@endif
		@endif





		{{--institute information section--}}
		<div class="text-center row">
			<div id="inst-photo">
				@if($instituteInfo->logo)
					<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:75px;height:75px;">
				@endif
			</div>
			<div id="inst-info">
				<b style="font-size: 25px;">{{$instituteInfo->institute_name}}</b><br/>
				<span style="font-size:12px;">{{'Address: '.$instituteInfo->address1}}</span><br/>
				<span style="font-size:12px;">{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}</span>
			</div>
		</div>

		{{--student information section--}}
		<div class="row">
			<p class="label text-center text-bold">Student Information</p>
			{{--Student Infromation--}}
			<div id="std-info">
				<table id="std-profile-table" class="table">
					<tr>
						<th width="18%">Name of Student</th>
						<th width="2%">:</th>
						<td><b>{{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</b></td>
					</tr>
					{{--checking institute--}}
					@if($instituteInfo->id!=22 AND $instituteInfo->id!=6)
						{{--parents information--}}
						@php $parents = $studentInfo->myGuardians(); @endphp
						{{--checking--}}
						@if($parents->count()>0)
							@foreach($parents as $index=>$parent)
								@php $guardian = $parent->guardian(); @endphp
								<tr>
									<th>{{$index%2==0?"Father's Name":"Mother's Name"}} </th>
									<th>:</th>
									<td>{{$guardian->title." ".$guardian->first_name." ".$guardian->last_name}}</td>
								</tr>
							@endforeach
						@endif
					@endif
					<tr>
						<th>Class - Section </th>
						<th>:</th>
						{{--student enrollment--}}
						@php
							$enrollment = $studentInfo->enroll();
							$level  = $enrollment->level();
							$batch  = $enrollment->batch();
							$section  = $enrollment->section();
							$division = null;
						@endphp

						@if($divisionInfo = $batch->get_division())
							@php $division = " (".$divisionInfo->name.") "; @endphp
						@endif
						<td>{{$batch->batch_name.$division}} - {{$section->section_name}} </td>
					</tr>
					<tr>
						<th>Class Roll </th>
						<th>:</th>
						<td>{{$enrollment->gr_no}}</td>
					</tr>
					{{--<tr>--}}
					{{--<th>Date of Birth </th>--}}
					{{--<th>:</th>--}}
					{{--<td>{{ date('d M, Y', strtotime($studentInfo->dob)) }}</td>--}}
					{{--</tr>--}}
				</table>
			</div>

			<div id="grade-scale">
				<table id="grade-sale-table" class="table">
					<thead>
					<tr>
						<th>Marks</th>
						<th>Grade</th>
						<th>Point</th>
					</tr>
					</thead>
					<tbody>
					@foreach($gradeScaleDetails as $gradeScaleDetail)
						<tr>
							<td>{{$gradeScaleDetail['min_per']. ' - '.$gradeScaleDetail['max_per']}}</td>
							<td>{{$gradeScaleDetail['name']}}</td>
							<td>{{$gradeScaleDetail['points']}}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>

		{{--final report card section--}}
		<div class="row">
			<p class="label text-center text-bold"> Aggregated Report Card </p>
			{{--checking exam result sheet--}}
			@if($examResultSheet['status']=='success')
				{{--find semester count--}}
				@php $totalSemester = count($allSemester); @endphp
				{{--all student result sheet--}}
				@php
					$allStdResultList = $examResultSheet['std_list'];
					// find result sheet
					$myResultSheet = $allStdResultList[$studentInfo->id];

					// result details
					$subList = $myResultSheet['sub_list'];
					$atdList = $myResultSheet['sem_atd_list'];
					$semMeritList = $myResultSheet['sem_merit_list'];
					$semResultSummaryList = $myResultSheet['sem_result_summary_list'];

					// all sub total exam marks
					$allSemExamMarks = 0;
					$allSemObtainedMarks = 0;
					$totalFailedCounter = 0;
				@endphp

				{{--chekcing std list--}}
				@if(count($allStdResultList)>0)
					<table id="report-first-table"  class="table">
						<thead>
						<tr class="bg-gray">
							<th width="2%">#</th>
							<th width="20%">Subject Name</th>
							<th width="6%">Full Marks</th>
							{{--checking semester list--}}
							@if($totalSemester>0)
								{{--semester list looping--}}
								@foreach($allSemester as $semester)
									<th>
										{{$semester['name']}}
										{{--find semester marks summary list--}}
										@php
											$semesterId = $semester['id'];
											$semSummary = (object)(array_key_exists($semesterId, $semResultSummaryList)? $semResultSummaryList[$semesterId]:null);
											// checking semester summary
											$allSemExamMarks += ($semSummary?($semSummary->total_exam_marks):0);
											$allSemObtainedMarks += ($semSummary?($semSummary->total_obtained):0);
										@endphp

										{{--<table id="report-second-table" class="table">--}}
										{{--<thead>--}}
										{{--<tr>--}}
										{{--<td width="25%" style="border-right: 1px solid black">Exam Mark</td>--}}
										{{--<td width="25%" style="border-right: 1px solid black">Obtained Mark</td>--}}
										{{--<td width="25%" style="border-right: 1px solid black">Grade</td>--}}
										{{--<td width="25%">Point</td>--}}
										{{--</tr>--}}
										{{--</thead>--}}
										{{--</table>--}}
									</th>
								@endforeach
							@endif
							<th>Total Obtained</th>
							<th>Aggregated Marks</th>
							<th>Percent (%)</th>
							<th>Letter Grade</th>
							<th>GP</th>
							{{--<th>GPA</th>--}}
						</tr>
						</thead>
						<tbody>

						{{--checking sub list--}}
						@if($subList)
							@php
								$subCounter = 1;
								$allSubTotalMarks = 0;
								$allSubObtainedTotalMarks = 0;
								$allSubObtainedAvgMarks = 0;
								$allSubExamMarks = 0;

								$allSubCountable = 0;
								$allSubObtainedPoint = 0;
								$allSubRemainingPoint = 0;
							@endphp
							{{--subject list looping--}}
							@foreach($subList as $subId=>$subDetails)
								{{--subject semester result sheet--}}
								@php
									$subSemResultSheet =  $subDetails['sub_sem_result'];
									$isCountable = $subDetails['is_countable'];
								@endphp
								<tr>
									<th>{{$subCounter}}</th>
									<th class="subject">{{$subDetails['sub_name'].($isCountable>0?'':' (UC)')}}</th>
									<th class="text-center">{{$subDetails['sub_exam_marks']}}</th>
									{{--calculate semester total result--}}
									@php
										$allSubExamMarks += ($isCountable>0?($subDetails['sub_exam_marks']):0);
										$subTotalObtainedMarks = 0;
									@endphp
									{{--checking semester list--}}
									@if($totalSemester>0)
										{{--semester list looping--}}
										@foreach($allSemester as $semester)
											{{--chekcing subject semester result sheet--}}
											@if(array_key_exists($semester['id'], $subSemResultSheet))
												{{--find subject details--}}
												@php $subjectResult =  (object)$subSemResultSheet[$semester['id']]@endphp
												{{--checking subject result--}}
												@if($subjectResult)
													{{--<th>--}}
													{{--<table class="table">--}}
													{{--<tbody>--}}
													{{--<tr>--}}
													<th>{{$subjectResult->obtained}}</th>
													{{--<td width="25%" style="border-right: 1px solid black">{{$subjectResult->obtained}}</td>--}}
													{{--<td width="25%" style="border-right: 1px solid black">{{$subjectResult->letterGrade}}</td>--}}
													{{--<td width="25%">{{$subjectResult->letterGradePoint}}</td>--}}
													{{--</tr>--}}
													{{--</tbody>--}}
													{{--</table>--}}
													{{--</th>--}}
													{{--calculate semester total result--}}
													@php
														// checking subject count type
														if($isCountable>0){
															$allSubTotalMarks += $subjectResult->total;
															$subTotalObtainedMarks += $subjectResult->obtained;
														}
													@endphp
												@else
													<td>-</td>
												@endif
											@else
												<td>-</td>
											@endif
										@endforeach
									@endif
									{{--single subject all semester total marks--}}
									<th>{{$isCountable>0?($subTotalObtainedMarks):'-'}}</th>
									{{--single subject all semester average marks--}}
									@php $subAvgObtainedMarks = round(($subTotalObtainedMarks/$totalSemester), 2); @endphp
									<th>{{$isCountable>0?($subAvgObtainedMarks):'-'}}</th>
									{{--total marks counter--}}
									@php $allSubObtainedTotalMarks += $subTotalObtainedMarks; @endphp
									{{--average marks counter--}}
									{{--@php $allSubObtainedAvgMarks += $subAvgObtainedMarks; @endphp--}}

									{{--subject result claculation--}}
									@php
										$subPercentage = round(($subAvgObtainedMarks/$subDetails['sub_exam_marks'])*100, 2);
										$subGradeDetails = subjectGradeCalculation((int)$subPercentage, $gradeScaleDetails);
										//$allSubObtainedPoint += $subGradeDetails?$subGradeDetails['point']:0;
									@endphp

									@if($isCountable>0)
										<th>{{$subPercentage}}</th>
										@php $myAggregatedGrade = $subGradeDetails?$subGradeDetails['grade']:'N/A'; @endphp
										<th>{{$myAggregatedGrade}} </th>
										<th>{{$subGradeDetails?$subGradeDetails['point']:'N/A'}} </th>
										{{--failed counter--}}
										@php
											$totalFailedCounter += ($myAggregatedGrade=='F'?1:0);
											$allSubCountable +=1;
											$allSubObtainedPoint += ($subGradeDetails?$subGradeDetails['point']:0);
										@endphp
									@else
										<th>-</th>
										<th>-</th>
										<th>-</th>
									@endif

									{{--checking subject counter--}}
									{{--@if($subCounter==1)--}}
									{{--<th rowspan="{{count($subList)+1}}">--}}
									{{--final grade point calculation--}}
									{{--@php--}}
									{{--$semPercentage = round(($allSemObtainedMarks/$allSemExamMarks)*100, 2);--}}
									{{--$semGradePoint = subjectGradeCalculation((int)$semPercentage, $gradeScaleDetails);--}}
									{{--@endphp--}}
									{{--checking failed counter--}}
									{{--@if($totalFailedCounter==0)--}}
									{{--{{$semGradePoint?$semGradePoint['point']:'N/A'}} <br/>--}}
									{{--({{$semGradePoint?$semGradePoint['grade']:'N/A'}})<br/>--}}
									{{--@else--}}
									{{--{{'F'}}--}}
									{{--@endif--}}
									{{--</th>--}}
									{{--@endif--}}
								</tr>
								@php $subCounter+=1; @endphp
							@endforeach
							<tr>
								<th colspan="2">Total:</th>
								<th>{{$allSubExamMarks}}</th>
								{{--checking semester list--}}
								@if($totalSemester>0)
									{{--semester list looping--}}
									@foreach($allSemester as $semester)
										{{--find semester marks summary list--}}
										@php $semesterId = $semester['id']; @endphp
										@php $semResultSummary = (object)(array_key_exists($semesterId, $semResultSummaryList)? $semResultSummaryList[$semesterId]:null) @endphp
										{{--<th>--}}
										{{--<table class="table">--}}
										{{--<thead>--}}
										{{--<tr>--}}
										<th> {{$semResultSummary?$semResultSummary->total_obtained:'-'}} </th>
										{{--<td width="25%" style="border-right: 1px solid black"> {{$semResultSummary?$semResultSummary->total_obtained:'-'}} </td>--}}
										{{--<td width="25%" style="border-right: 1px solid black">-</td>--}}
										{{--<td width="25%">{{$semResultSummary?$semResultSummary->total_gpa:'-'}}</td>--}}
										{{--</tr>--}}
										{{--</thead>--}}
										{{--</table>--}}
										{{--</th>--}}
									@endforeach
								@endif

								{{--final result claculation--}}
								@php
									//$allSubTotalExamMarks = $allSubExamMarks*$totalSemester;
									$allSubTotalExamMarks = $allSubTotalMarks;
									$percentage = round(($allSubObtainedTotalMarks/$allSubTotalExamMarks)*100, 2);
									$gradeDetails = subjectGradeCalculation((int)$percentage, $gradeScaleDetails);
								@endphp

								<th>{{$allSubObtainedTotalMarks}}</th>
								{{--<th>{{$allSubObtainedAvgMarks}}</th>--}}
								@php $allSubObtainedAvgMarks = round(($allSubObtainedTotalMarks/$totalSemester), 2); @endphp
								<th>{{$allSubObtainedAvgMarks}}</th>
								<th>-</th>
								<th>-</th>
								<th>{{$allSubObtainedPoint}}</th>
								{{--<th>-</th>--}}
							</tr>


							<tr class="bg-gray">
								<th colspan="{{8+$totalSemester}}" class="text-center">
									<table id="report-third-table" class="table">
										<thead>
										<tr>
											<td> Total: {{$allSubTotalExamMarks}} </td>
											<td> Obtained: {{$allSubObtainedTotalMarks}} </td>
											<td> Highest: {{count($stdSectionMeritArrayList)>0?($stdSectionMeritArrayList[0]/100):'N/A'}} </td>
											{{--<td> Aggregated: {{$allSubObtainedAvgMarks}} </td>--}}
											<td> Percentage: {{$percentage}} % </td>
											@php
												$myAllSubObtainedTotalMarks = (int) round(($allSubObtainedTotalMarks*100), 2);
											    // checking section merit position
                                                if(in_array($myAllSubObtainedTotalMarks, $stdSectionMeritArrayList)){
                                                    $aggregatedSectionMeritPosition = (array_search($myAllSubObtainedTotalMarks, $stdSectionMeritArrayList)+1);
                                                }else{
                                                    $aggregatedSectionMeritPosition = ' ';
                                                }
											    // checking class merit position
                                                if(in_array($myAllSubObtainedTotalMarks, $stdClassMeritArrayList)){
                                                    $aggregatedClassMeritPosition = (array_search($myAllSubObtainedTotalMarks, $stdClassMeritArrayList)+1);
                                                }else{
                                                    $aggregatedClassMeritPosition = ' ';
                                                }
											@endphp
											@if($totalFailedCounter==0)
												<td> GPA: {{round(($allSubObtainedPoint/$allSubCountable), 2)}}, </td>
												{{--<td> GPA: {{$gradeDetails?$gradeDetails['point']:'N/A'}}, </td>--}}
												<td> Merit (Section): {{$aggregatedSectionMeritPosition}}</td>
												<td> Merit (Class): {{$aggregatedClassMeritPosition}}</td>
											@else
												<td> Failed in {{$totalFailedCounter}} Subject </td>
											@endif
										</tr>
										</thead>
									</table>
								</th>
							</tr>
						</tbody>
						@else
							<tr>
								<td>No Subject Found</td>
							</tr>
						@endif
					</table>
					{{--checking semester list--}}
					@if($stdExtraBookList>0 AND $totalSemester>0)
						<p class="label text-center text-bold"> Others Marks </p>

						@php
							// find result sheet
							$extraResultList = array_key_exists('sem_extra_marks_list', $myResultSheet)?$myResultSheet['sem_extra_marks_list']:[];
							// semester total extra marks
							$totalExtraMarks = 0;
						@endphp

						<table id="others-table" class="table">
							<thead>
							<tr class="bg-gray">
								{{--semester list looping--}}
								@foreach($allSemester as $semester)
									<th>{{$semester['name']}}</th>
								@endforeach
								<th width="10%">Total Obtained</th>
								<th width="10%">Semester Average</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								{{--semester list looping--}}
								@foreach($allSemester as $semester)
									{{--single semester extra marks list--}}
									@php
										$semExtraMarks = array_key_exists($semester['id'], $extraResultList)?$extraResultList[$semester['id']]:[];
										$extraMarks = $semExtraMarks?$semExtraMarks->extra_marks->marks:[];
										//$semExtraMarks = array_key_exists($semester['id'], $extraResultList)?$extraResultList[$semester['id']]->extra_marks->marks:[];
										// semester total extra marks
										$semTotalExtraMarks = 0;
									@endphp
									{{--checking --}}
									@if(count($extraMarks))

										@foreach($extraMarks as $markId=>$marks)
											@php
												$semTotalExtraMarks += $marks;
												$totalExtraMarks += $marks;
											@endphp
										@endforeach
									@endif
									<th>{{$semTotalExtraMarks}}</th>
								@endforeach
								<th>{{$totalExtraMarks}}</th>
								<th>{{round($totalExtraMarks/$totalSemester, 2)}}</th>
							</tr>
							</tbody>
						</table>
					@endif

					<br/>
					<div class="clear" style="width: 100%; font-size: 11px">

						<div style="float: left; width: 30%; text-align:center; margin-top: 10px">
							{{--<span class="singnature"></span><br>--}}
							<div style="border: 1px dotted black; width: 250px; height: 50px"></div><br/>
							<strong>Class Teacher's Comment</strong>
						</div>

						<div style="float: left; width: 35%; text-align:center; margin-top: 25px">
							<span class="singnature"></span><br>
							............................................<br>
							<strong>Class Teacher</strong>
						</div>


						<div style="float: right; width: 38%; text-align:center; {{($reportCardSetting AND $reportCardSetting->auth_sign)?'':'padding: 20px;'}}">
							{{--checking auth sign--}}
							@if($reportCardSetting AND $reportCardSetting->auth_sign!=null AND !empty($reportCardSetting->auth_sign))
								<img class="singnature" src="{{public_path().'/assets/users/images/'.$reportCardSetting->auth_sign}}"> <br>
							@endif
							{{--auth name--}}
							@if($reportCardSetting AND $reportCardSetting->auth_name!=null AND !empty($reportCardSetting->auth_name))
								@if($reportCardSetting->auth_sign==null) <span class="singnature"></span><br> @endif
								............................................<br>
								<strong >@php echo  html_entity_decode($reportCardSetting->auth_name) @endphp </strong>
							@else
								<span class="singnature"></span><br>
								............................................<br>
								<strong>Principal / Head Teacher</strong>
							@endif
						</div>
					</div>

					{{--page breaker--}}
					@if($stdCount!=count($studentList))
						<div style="page-break-after:always;"></div>
						@php $stdCount+=1; @endphp
					@endif

				@else
					<div class="row">
						<p>No Student Result Found</p>
					</div>
				@endif
			@else
				<div class="row">
					<p>{{$examResultSheet['msg']}}</p>
				</div>
			@endif
		</div>

	@endforeach
@else

@endif

</body>
</html>
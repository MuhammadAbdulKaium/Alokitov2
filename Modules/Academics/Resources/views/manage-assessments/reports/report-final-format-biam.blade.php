<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<!-- Student Infromation -->
	<style type="text/css">
		.label {font-size: 15px;  padding: 5px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
		.row-first{background-color: #b0bc9e;}
		.row-second{background-color: #e5edda;}
		.row-third{background-color: #5a6c75;}
		.text-center {text-align: center; font-size: 12px}
		.clear{clear: both;}
		.text-bold{font-weight: 700;}

		.calculation i{
			margin-right: 10px;
		}

		#std-photo {
			float:right;
			width: 20%;
			margin-left: 10px;
		}

		#inst{
			padding-bottom: 20px;
			width: 100%;
		}

		.report_card_table{
			border: 1px solid black;
			border-collapse: collapse;
		}

		@if($reportCardSetting AND $reportCardSetting->is_label_color==1)
            .row-second{
			background-color: {{$reportCardSetting->label_bg_color}};
			color: {{$reportCardSetting->label_font_color}};
		}
		@endif


        @if($reportCardSetting AND $reportCardSetting->is_table_color==1)
        #customers {
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
			opacity: {{$reportCardSetting->tbl_opacity}};
			border: 1px solid black;
			line-height: 15px;
		}

		@endif

		.semester{
			font-size: 11px;
			height:  auto;
			width: 1200px;
			margin: 0 auto;
}
		.markSheet {
			border: 6px solid #1e2ead;
			border-radius: 5px;
			padding: 15px;
			height: 1060px;
			margin: 10px;
		}

		.singnature{
			height: 30px;
			width: 40px;
		}

		.std-info-table {
			font-size:15px;
			line-height: 17px;
			margin-bottom: 30px;
			text-align: left;
		}

		#std-photo{
			width: 32%;
			float: left;
			margin-top: -10px;
		}

		#inst-logo{
			width: 32%;
			float: left;
			text-align: center;
		}

		#grade-scale {
			width: 32%;
			float: right;
			margin-top: -23px;
		}

		/*width: 24%*/
		.report-comments{
			width: 31%;
			float: left;
		}

		#qr-code{
			width: 20%;
			float: right;
		}

		/*commenting table */
		.table {
			border-collapse: collapse;
			line-height: 15px;
		}

		.table, .table th, .table td {
			border: 1px solid black;
		}

		.subject{
			text-align: left;
			padding-left: 10px;
			font-weight: 900;
		}

		#header_row{
			line-height: 20px;
		}

		#total i {
			margin-left: 15px;
		}

		@media print {
			.semester {
				width: 100%;
			}
			.singnature-img {
				margin-top: -150px !important;
			}
			.breakNow {
				page-break-inside: avoid;
				page-break-after: always;
				margin-top: 10px;
			}
		}

	</style>
</head>
@php
	$allStdResultList = $examResultSheet['std_list'];
	$finalMeritList = $examResultSheet['section_final_merit_list']['pass_list'];
	$classFinalMeritList = $examResultSheet['class_final_merit_list']['pass_list'];
@endphp
<body>

@if(count($studentList)>0)

	@php $stdCount = 0; @endphp

	<div class="semester">
	{{--student list looping--}}
	@foreach($studentList as $student)

		{{--object conversion--}}
		@php
			$stdCount++;
			$student = (object)$student;
			$studentInfo = findStudent($student->id);
		@endphp

		<!-- attendance Report -->
		@if($examResultSheet['status']=='success')
			{{--find semester count--}}
			@php
				$totalSemester = count($allSemester);
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

				<div class="markSheet">
				<div id="inst" class="text-center clear" style="width: 100%;">
					<b style="font-size:35px">
						{{$instituteInfo->institute_name}}
					</b>
					<br/>
					<span style="font-size: 16px; font-weight: 500">
								{{$instituteInfo->address1}}
						<br/>
						{{$instituteInfo->website}}
							</span>
				</div>
				<div class="clear" style="width: 100%;">
					<div id="std-photo">
						@if($studentInfo->singelAttachment("PROFILE_PHOTO"))
							<img src="{{asset('assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"  style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
						@else
							<img src="{{asset('assets/users/images/user-default.png')}}"  style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
						@endif
					</div>
					<div id="inst-logo">
						@if($instituteInfo->logo)
							<img src="{{url('assets/users/images/'.getInstituteProfile()->logo)}}" class="center"  style="width:80px;height:80px;">
							<br/>
							<br/>
							<p style="font-size:15px; text-align: center; line-height: 2px; margin-left: 10px"><b>PROGRESS REPORT</b></p>
							<hr style="margin-left: 10px; color:black">
						@endif
					</div>
					<div id="grade-scale">
						<table width=60%" style="font-size: 10px; line-height: 13px; float: right" class="text-center table" cellpadding="2">
							<thead>
							<tr>
								<th width="2%">Range (%)</th>
								<th width="1%">Grade</th>
								<th width="1%">GP</th>
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
				{{--Student Infromation--}}
				<div class="clear" style="width: 100%;"></div>
				<div class="clear" style="width: 100%;">
					<div style="width: 69%; float: left">
						<table width="100%" class="std-info-table" cellpadding="1">
							<tr>
								<th width="25%">Student's Name</th>
								<th width="1%">:</th>
								@php $stdFullName=$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name @endphp
								<td><b>{{$stdFullName}}</b></td>
							</tr>

							{{--student enrollment--}}
							@php
								$enrollment = $studentInfo->enroll();
								$level  = $enrollment->level();
								$batch  = $enrollment->batch();
								$section  = $enrollment->section();
								$division = null;
							@endphp

							@php $division = null; @endphp
							@if($divisionInfo = $enrollment->batch()->get_division())
								@php $division = " (".$divisionInfo->name.") "; @endphp
							@endif


							{{--parents information--}}
							@php $parents = $studentInfo->myGuardians(); @endphp
							{{--checking--}}
							@if($parents->count()>0)
								@foreach($parents as $index=>$parent)
									@php $guardian = $parent->guardian(); @endphp
									<tr>
										<th>
											{{--{{$guardian->type==1?"Father's Name":"Mother's Name"}}--}}
											{{--checking guardin type--}}
											@if($guardian->type ==0)
												Mother's Name
											@elseif($guardian->type ==1)
												Father's Name
											@else
												{{$index%2==0?"Father's Name":"Mother's Name"}}
											@endif
										</th>
										<th>:</th>
										<td>{{$guardian->first_name." ".$guardian->last_name}}</td>
									</tr>
								@endforeach
							@endif
							<tr>
								<th>Examination </th>
								<th>:</th>
								@php $user = $studentInfo->user(); @endphp
								<td>Annual Exam</td>
							</tr>
						</table>
						@php
							$levelId = $level->id;
							$batchId = $batch->id;
							$sectionId = $section->id;
						@endphp
					</div>
					<div style="width: 30%; float: left">
						<table width="100%" class="std-info-table" cellpadding="1">
							<tr>
								<th width="30%">Class </th>
								<th width="1%">:</th>
								<td>{{$enrollment->batch()->batch_name.$division}} </td>
							</tr>
							<tr>
								<th>Section </th>
								<th>:</th>
								<td> {{$enrollment->section()->section_name}} </td>
							</tr>
							<tr>
								<th>Roll </th>
								<th>:</th>
								<td>{{$enrollment->gr_no}}</td>
							</tr>
							<tr>
								<th>Year </th>
								<th>:</th>
								<td>2019</td>
							</tr>
						</table>
					</div>
				</div>
				{{--main report card--}}
				<table id="customers" width="100%" class="text-center table" cellspacing="5">
					<thead>
					<tr class="row-second" id="header_row" class="text-center">
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
						<th>Grade Point</th>
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


						<tr id="total">
							<th colspan="{{8+$totalSemester}}" class="text-center">
								@php
									$finalMeritList = array_unique(array_values($finalMeritList));
									$classFinalMeritList = array_unique(array_values($classFinalMeritList));
								@endphp
								<i> Total: {{$allSubTotalExamMarks}} </i>
								<i> Obtained: {{$allSubObtainedTotalMarks}} </i>
								<i> Highest: {{count($finalMeritList)>0?($finalMeritList[0]/100):'N/A'}} </i>
								{{--<td> Aggregated: {{$allSubObtainedAvgMarks}} </td>--}}
								<i> Percentage: {{$percentage}} % </i>
								@php
									$myAllSubObtainedTotalMarks = (int) round(($allSubObtainedTotalMarks*100), 2);
									// checking section merit position
									if(in_array($myAllSubObtainedTotalMarks, $finalMeritList)){
										$aggregatedSectionMeritPosition = (array_search($myAllSubObtainedTotalMarks, $finalMeritList)+1);
									}else{
										$aggregatedSectionMeritPosition = ' ';
									}
									// checking class merit position
									if(in_array($myAllSubObtainedTotalMarks, $classFinalMeritList)){
										$aggregatedClassMeritPosition = (array_search($myAllSubObtainedTotalMarks, $classFinalMeritList)+1);
									}else{
										$aggregatedClassMeritPosition = ' ';
									}
								@endphp
								@if($totalFailedCounter==0)
									<i> GPA: {{round(($allSubObtainedPoint/$allSubCountable), 2)}}, </i>
									{{--<td> GPA: {{$gradeDetails?$gradeDetails['point']:'N/A'}}, </td>--}}
									<i> Merit (Section): {{$aggregatedSectionMeritPosition}}</i>
									<i> Merit (Class): {{$aggregatedClassMeritPosition}}</i>
								@else
									<i> Failed in {{$totalFailedCounter}} Subject </i>
								@endif
							</th>
						</tr>
					</tbody>
					@else
						<tr>
							<td>No Subject Found</td>
						</tr>
					@endif
				</table>



				<div class="clear" style="width: 100%; margin-top:15px;">
					@php
						// find result sheet
						$extraResultList = array_key_exists('sem_extra_marks_list', $myResultSheet)?$myResultSheet['sem_extra_marks_list']:[];
						// semester total extra marks
						$totalExtraMarks = 0;
					@endphp
					<table width="100%" class="text-center table" cellspacing="5" style="line-height: 20px">
						<thead>
						<t>
							<th colspan="{{2+count($allSemester)}}">Others Marks</th>
						</t>
						<tr class="bg-gray">
							{{--semester list looping--}}
							@foreach($allSemester as $semester)
								<th>{{$semester['name']}}</th>
							@endforeach
							<th width="10%">Total</th>
							<th width="10%">Average</th>
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
									$semTotalExtraMarks = 0;
								@endphp
								{{--checking --}}
								@if(!empty($extraMarks))

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
				</div>
				<div class="clear" style="width: 100%; margin-top:15px;">
					<div class="report-comments">
						<table width="100%" class="text-center table" cellpadding="1" style="line-height: 20px;">
							<tr>
								<th>Result Status </th>
{{--								<th></th>--}}
								<th>{{$totalFailedCounter>0?'Failed':'Passed'}}</th>
							</tr>
							<tr>
								<th>Failed Subjects </th>
{{--								<th>-</th>--}}
								<th>{{$totalFailedCounter>0?$totalFailedCounter:'-'}}</th>
							</tr>
							<tr>
								<th>Working Days</th>
								<th>-</th>
								{{--<th>{{$totalWorkingDays}}</th>--}}
							</tr>
							<tr>
								<th>Total Present</th>
								<th>
									{{--{{$presentPercentage}}--}}
								</th>
							</tr>
							<tr>
								<th>Total Absent</th>
								<th>
									{{--{{$absentPercentage}}--}}
								</th>
							</tr>
						</table>
					</div>
					{{--margin-left:20px;--}}
					<div class="report-comments" style="margin-left:30px">
						<table width="100%" class="text-center table" cellpadding="1" style="line-height: 20px; margin-left:0px">
							<tr>
								<th colspan="2">Moral & Behaviour </th>
							</tr>
							<tr>
								<th width="20%"></th>
								<th>Excellent</th>
							</tr>
							<tr>
								<th></th>
								<th>Good</th>
							</tr>
							<tr>
								<th></th>
								<th>Average</th>
							</tr>
							<tr>
								<th></th>
								<th>Poor</th>
							</tr>
						</table>
					</div>

					{{--margin-left: 20px; float: left;--}}
					<div class="report-comments" style="float: right">
						<table width="100%" class="text-center table" cellpadding="1" style="line-height: 20px">
							<tr>
								<th colspan="2">Co-Curricular Activities</th>
							</tr>
							<tr>
								<th width="20%"></th>
								<th>Sports</th>
							</tr>
							<tr>
								<th></th>
								<th>Cultural Function</th>
							</tr>
							<tr>
								<th></th>
								<th>Scout / BNCC</th>
							</tr>
							<tr>
								<th></th>
								<th>Math Olympiad</th>
							</tr>
						</table>
					</div>

					{{--<div id="qr-code">--}}
					{{--@php $qrCode="Name:".$stdFullName.", Total: ".$resultTotalMarks.", Obtained: ".$resultObtainedMarks.", Percent: ".$semesterPercentage."%, GPA: ".$semesterGradePoint['point'].", Merit Position: ".$semesterMeritPosition @endphp--}}
					{{--<div class="qrcode">--}}
					{{--										<img style="margin-left:0px; width: 140px; height: 140px; margin-top: -15px" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($qrCode))!!} ">--}}
					{{--</div>--}}
					{{--</div>--}}
				</div>
				<div class="comment_row" style="width: 100%; height: auto; margin-top: 150px">
					<div class="comment" style="width: 70%; float: left">
					{{--table width:77%--}}
					<table width="100%" class="table" cellpadding="1" style="text-align:left; line-height: 20px;">
						<tr>
							<th>Comments: </th>
						</tr>
						<tr>
							<th style="padding: 20px"></th>
						</tr>
					</table>
					</div>
					<div class="qrcode" style="width: 30%; float: left;">
						<img src="https://chart.googleapis.com/chart?cht=qr&amp;chs=100x100&amp;choe=UTF-8&amp;chl=Name: {{$stdFullName}}, GPA:{{round(($allSubObtainedPoint/$allSubCountable), 2)}}, Mark:{{$allSubObtainedTotalMarks}}, {{$instituteInfo->institute_name}} " alt="" style="margin-left: 70px;
    margin-top: -10;">
					</div>
				</div>
					<div class="singature_row" style="margin-top: 270px;">
						<div style="float: left; width: 30%; text-align:center; padding: 20px;">

							<span style="display:{{($reportCardSetting AND $reportCardSetting->parent_sign==1)?'block':'none'}}">
							<span class="singnature"> </span><br>
							............................................<br>
							<strong>Parent Signature</strong>
							</span>
						</div>

						<div style="float: left;  text-align:center; padding: 20px">
							<span style="display:{{($reportCardSetting AND $reportCardSetting->teacher_sign==1)?'block':'none'}}">
							<span class="singnature"></span><br>
							............................................<br>
							<strong>Teacher Signature</strong>
							</span>
						</div>

						<div style="float: right; width: 30%; text-align:center; {{($reportCardSetting AND $reportCardSetting->auth_sign)?'':'padding: 20px;'}}">
							{{--checking auth sign--}}
							@if($reportCardSetting AND $reportCardSetting->auth_sign!=null AND !empty($reportCardSetting->auth_sign))
								<img class="singnature singnature-img" src="{{URL::to('assets/users/images/',$reportCardSetting->auth_sign)}}"> <br>
							@endif
							{{--auth name--}}
							@if($reportCardSetting AND $reportCardSetting->auth_name!=null AND !empty($reportCardSetting->auth_name))
								@if($reportCardSetting->auth_sign==null) <span class="singnature"></span><br> @endif
								............................................<br>
								<strong>@php echo html_entity_decode($reportCardSetting->auth_name) @endphp </strong>
							@else
								<span class="singnature"></span><br>
								............................................<br>
								<strong>Principal / Head Teacher</strong>
							@endif
						</div>
					</div>
					</div>


				<div class="breakNow"></div>


		@endif

	@endforeach
	</div>


@endif
</body>
</html>

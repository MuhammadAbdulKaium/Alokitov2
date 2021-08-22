<html>
<title>Student Report Card</title>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<!-- Student Infromation -->
	<style type="text/css">

		body {
			width: 100%;
			height: 100%;
			margin: 0;
			padding: 0;
			font: 12pt "Tahoma";
		}
		* {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
			width: 210mm;
			min-height: 297mm;
			margin: 10px auto;
		}
		.subpage {
			padding: 1cm;
			background-image: url("{{URL::to('assets/users/images/certificate.png')}}");
			height: 297mm;
			background-repeat: no-repeat;
			background-size: 210mm 297mm;
		}

		@page {
			size: A4;
			margin: 0;
		}
		@media print {
			html, body {
				width: 210mm;
				height: 297mm;
			}
			.page {
				margin: 0;
				border: initial;
				border-radius: initial;
				width: initial;
				min-height: initial;
				box-shadow: initial;
				background: initial;
				page-break-after: always;
			}
		}

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
			padding: 15px;
			height: 1266px;
			{{--@if($reportCardSetting AND $reportCardSetting->is_border_color==1)--}}
				{{--@php $border = $reportCardSetting->border_width.'px '.$reportCardSetting->border_type.' '.$reportCardSetting->border_color;  @endphp--}}
				{{--border: {{$border}};--}}
			{{--border-radius: 5px;--}}
		{{--@endif--}}
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
			line-height: 25px;
		}

		.table, .table th, .table td {
			border: 1px solid black;
		}

		.subject{
			text-align: left;
			padding-left: 10px;
			font-weight: 900;
			height: 25px;
			font-size: 13px;
		}

		#header_row{
			line-height: 20px;
		}

	</style>
</head>


<body>

<div class="book">

@if($classSectionStudentList)
	@php $stdCount = 0; @endphp
	@foreach($classSectionStudentList as $singleStudent)
			<div class="page">
				<div class="subpage">
		{{--std count--}}
		@php $stdCount++; @endphp
		{{--Student Information--}}
		@php $studentInfo  = $singleStudent->student(); @endphp
		{{--semester loop counter--}}
		@php $semesterLoopCounter = 0; @endphp

		<!-- attendance Report -->
		@if(count($semesterResultSheet)>0 && count($allSemester)>0)
			{{--myResult For Calculating Final Result--}}
			@php $myResult = array(); @endphp
			{{--semester looping--}}
			@for($m=0; $m<count($allSemester); $m++)
				{{--semester loop count--}}
				@php $semesterLoopCounter += 1; @endphp

				@if(array_key_exists($allSemester[$m]['id'], $semesterResultSheet)==true)

					@if($allSemester[$m]['id'] != $semester) @continue @endif

					<div class="semester">

						@php
							// subject assessment array list
							$subjectAssArrayList = (array)($subjectAssessmentArrayList['subject_list']);
						@endphp
						<div id="inst" class="text-center clear" style="width: 100%;">
							<b style="font-size:35px">
								{{$instituteInfo->institute_name}}
							</b>
							<br/>
							<span style="font-size: 16px; font-weight: 500"> {{$instituteInfo->address1}} </span>
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
									<img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}" class="center"  style="width:80px;height:80px; margin-left: 95px">
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
						<div class="clear" style="width: 100%;">
						</div>
						<br/>
						<br/>
						<div class="clear" style="width: 100%;">
							<div style="width: 60%; float: left">
								<table width="100%" class="std-info-table" cellpadding="1">
									<tr>
										<th width="30%">Student's Name</th>
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
										<th>Student's ID </th>
										<th>:</th>
										@php $user = $studentInfo->user(); @endphp
										<td>{{$user->username}}</td>
									</tr>
									<tr>
										<th>Examination </th>
										<th>:</th>
										<td> {{$allSemester[$m]['name']}} </td>
									</tr>
									<tr>
										<th>Year </th>
										<th>:</th>
										<td>{{date('Y', strtotime($allSemester[$m]['start_date']))}}</td>
									</tr>

								</table>
								@php
									$levelId = $level->id;
									$batchId = $batch->id;
									$sectionId = $section->id;
								@endphp
							</div>
							<div style="width: 40%; float: left">
								<table width="100%" class="std-info-table" cellpadding="1">
									<tr>
										<th width="20%">Class </th>
										<th width="1%">:</th>
										<td>{{$enrollment->batch()->batch_name.$division}} </td>
									</tr>
									<tr>
										<th>Group </th>
										<th>:</th>
										<td>-</td>
									</tr>
									<tr>
										<th>Section </th>
										<th>:</th>
										<td> {{$enrollment->section()->section_name}} </td>
									</tr>
									<tr>
										<th>Shift </th>
										<th>:</th>
										<td>-</td>
									</tr>
									<tr>
										<th>Roll </th>
										<th>:</th>
										<td>{{$enrollment->gr_no}}</td>
									</tr>
								</table>
							</div>
						</div>
						<br/>
						{{--@php $stdSubjectGrades = $semesterResultSheet[$allSemester[$m]['id']]; @endphp--}}
						@php $stdSubjectGrades = (array)$semesterResultSheet[$allSemester[$m]['id']][$studentInfo->id]; @endphp

						{{--assesssement details array list--}}
						@php $assessmentInfo = array(); $assessmentWAInfo = array(); $assessmentResultCountInfo = array();@endphp

						@php
							// semester subject highest marks sheet
							$semesterAssessmentArrayList = (array)$subjectHighestMarksList[$allSemester[$m]['id']];
							$subjectHighestMarksArrayList = $semesterAssessmentArrayList['subject_highest_marks'];
							$meritList = (array)$semesterAssessmentArrayList['merit_list'];
							$extraHighestMarksArrayList = $semesterAssessmentArrayList['extra_highest_marks'];
							$meritListWithExtraMark = $semesterAssessmentArrayList['merit_list_with_extra_mark'];
							$classSectionExtraCategory = 0;
							// semester attendance sheet
							$semesterAttendanceList = $semesterAttendanceSheet[$allSemester[$m]['id']];
							// semester extraBook mark sheet
							$stdExtraBookArrayList = $stdExtraBookMarkSheet[$allSemester[$m]['id']];
							// student additional subject list
							$stdAddSubList = array_key_exists($studentInfo->id, $additionalSubjectList)? $additionalSubjectList[$studentInfo->id]:[];
						@endphp

						{{--subject grade list checking--}}
						@if(count($stdSubjectGrades)>0)

							<div id="my_report_card" class="clear" style="width: 100%; margin-top: 30px;">
								{{--@if($stdSubjectGrades == null) @continue @endif--}}

								{{--<table class="report_card_table">--}}
								<table id="customers" width="100%" class="text-center table" cellspacing="5">
									<thead>
									<tr id="header_row" class="text-center">

									<th width="25%">Subject</th>
									{{--<th width="5%">Full Marks</th>--}}
									{{--<th width="5%">Highest Marks</th>--}}
									@php
										$semesterId = $allSemester[$m]['id'];
										$myAssessmentCounter = 0;
										$assessmentCategoryTotalMarks = 0;
										$assessmentsCount = $gradeScale->assessmentsCount()
									@endphp

									{{--checking $assessmentsCount--}}
									@if($assessmentsCount>0)
										{{--assessment category list--}}
										@php $allCategoryList = $gradeScale->assessmentCategory() @endphp
										{{--Category list empty checking--}}
										@if(!empty($allCategoryList) AND $allCategoryList->count()>0)
											{{--Category list loopint--}}
											@foreach($allCategoryList as $category)
												{{--checking category type--}}
												@if($category->is_sba==0)
													{{--category all assessment list--}}
													@php $allAssessmentList = $category->allAssessment($gradeScale->id); @endphp
													{{--assessment list empty checking--}}
													@if(!empty($allAssessmentList) AND $allAssessmentList->count()>0)
														{{--category result count--}}
														@php $resultCount = $category->resultCount($batchId, $sectionId, $semesterId); @endphp
														{{--my assessment category counter--}}
														@php $myAssessmentCounter +=1; @endphp
														{{--assessment list loopint--}}
														@foreach($allAssessmentList as $assessment)
															{{--my assessment category counter--}}
															@php $myAssessmentCounter +=1; @endphp
{{--															<th>{{$assessment->name}}</th>--}}
															{{--store assessment info--}}
															@php $assessmentInfo[$category->id][] = ['id'=>$assessment->id]; @endphp
														@endforeach
														{{--checking result count for best result average--}}
														@if($resultCount AND $resultCount->result_count>0)
															@php $assessmentResultCountInfo[$category->id] = $resultCount->result_count;@endphp
														@endif
{{--														<th>{{$category->name}}</th>--}}
														{{--<th>W/A</th>--}}
													@endif
												@endif
											@endforeach
										@endif
									@endif
									{{--<th width="7%">Sub Total</th>--}}
									<th width="7%">Total <br/></th>
									<th width="7%">Grade</th>
									<th width="7%">Point</th>


								@php
									// total marks counter
									$allSubTotalMarks = 0;
									$allSubObtainedMarks = 0;
									// point counter
									$totalPoints = 0;
									$obtainedPoints = 0;
									// subject counter
									$totalSubCount = 0;
									$totalFailedSubCount = 0;


									// general subject list
									$generalSubjectList = $stdSubjectGrades[0];
									 // result sheet
									$gradeResult = $stdSubjectGrades['result'];
									// student additional subject list
									$stdAddSubList = array_key_exists($studentInfo->id, $additionalSubjectList)? $additionalSubjectList[$studentInfo->id]:[];

									// semester grade list for group subject
$string ='';
$subjectcount = 0;
$gpaTotal = [];
$rowspan = 0;
									foreach ($subjectGroupList as $subGroupId=>$subGroupDetails){
$subjectcount++;
									$column_2_val = $column_3_val = $column_4_val = $column_4_val= $column_4_val_2 = $column_5_val =0;

										// group subject details
										$subGroupId = $subGroupDetails['s_g_id'];
										$subGroupName = $subGroupDetails['s_g_name'];
										$groupSubList = $subGroupDetails['s_list'];

										// checking subGroupId exits or not
										if(array_key_exists($subGroupId, $stdSubjectGrades)==true){
											// group subject grade list
											$groupGradeList = $stdSubjectGrades[$subGroupId];
											// group subject total marks details
											$groupSubTotalMark = 0;
											$groupSubPassMark = 0;
											$groupSubTotalObtainedMark = 0;
											$groupSubStatus = true;
											// group marks calculation
											$subGroupTotal = $groupGradeList['g_total'];
											$subGroupObtained = $groupGradeList['g_obtained'];
											$subGroupPercentage = round($subGroupTotal>0?(($subGroupObtained/$subGroupTotal)*100):0, 0, PHP_ROUND_HALF_UP);

											// looping
											for($mm=0; $mm<count($groupSubList); $mm++){
												// singleSubject
												$singleSubject = $groupSubList[$mm];
												#sub details
												$subId = $singleSubject['sub_id'];
												// class subject id
												$csId = $singleSubject['cs_id'];
												// sub_name
												$subName = $singleSubject['sub_name'];
												// sub exam mark
												$subExamMark = $singleSubject['exam_mark'];
												// sub pass mark
												$subPassMark = $singleSubject['pass_mark'];
												// sub pass mark
												$isCountable = $singleSubject['is_countable'];
												// group subject status
												$subjectObtainedMark = 0;
												// group subject status
												$subjectStatus = true;
												$subType = 0;

												$string.= "<tr>";

											   // if($subType==0 AND array_key_exists($csId, $stdAddSubList) AND $stdAddSubList[$csId]==0){
												 //   echo "<td>".$subjectName." <b>(Optional)</b></td>";
												//}else{
												//	echo "<td>".$subName."</td>";
												//}
	//1
	$column_1='';
												if($isCountable==1){
		                                            if($subType==0 AND array_key_exists($subId, $stdAddSubList) AND $stdAddSubList[$subId]==0){
		                                                $column_1 =  '<td class="subject">'.$subGroupName.' (Optional)</td>';
		                                            }else{
		                                                $column_1 = '<td class="subject">'.$subGroupName.'</td>';
		                                            }
		                                        }else{
		                                            $column_1 =  '<td class="subject">'.$subGroupName.' (UnCountable)</td>';
		                                        }

		                                        //2
		                                        // exam marks
		                                        $column_2_val += $subExamMark;
		                                        $column_2 = '<td>'.$column_2_val.'</td>';
//3
$column_3='';
		                                         // checking subject highest marks
												 if(array_key_exists($subId, $subjectHighestMarksArrayList)==true){
												 $column_3_val += $subjectHighestMarksArrayList[$subId];
														$column_3_val =  '<td>'.$column_3_val.'</td>';
												  }else{
														$column_3 = '<td>-</td>';
												 }



												// single subject assessment array list
											   $subCatAssArrayList = (array)(array_key_exists($subId, $subjectAssArrayList)?$subjectAssArrayList[$subId]['cat_list']:[]);

												// assessment counter checking
												if($assessmentsCount>0 AND count($subCatAssArrayList)>0){

													$sub_grade = $groupGradeList[$subId];
													// subject category marks list
													$subjectCatMarksList = $sub_grade['cat_marks_list'];
													$mark = (array) $sub_grade['mark'];

													foreach ($assessmentInfo as $headCatId=>$headAssList){
														  // custom catId
														  $catId = 'cat_'.$headCatId;
														  // category status (pass or fail)
														  $catStatus = true;

														  // total assessment and points
														  $totalAssessmentMarks = 0;
														  $totalAssessmentPoints = 0;
														  // assessment mark list
														  $assessmentMarkList = array();
														  // assessment count
														  $assessmentCount = 0;


														  // find category assessment mark list
														  $singleCatMarksList = (array) (array_key_exists($headCatId, $subCatAssArrayList)?$subCatAssArrayList[$headCatId]:[]);
														  // subject assessment mark list
														  $subjectAssMarkList = (array) (array_key_exists('ass_list', $singleCatMarksList)?$singleCatMarksList['ass_list']:[]);
														  // checking category id
														  if($singleCatMarksList){
															  $catExamMark = floatval($singleCatMarksList['exam_mark']);
															  $catPassMark = floatval($singleCatMarksList['pass_mark']);
														  }else{
															  $catExamMark = 0; $catPassMark = 0;
														  }

														  // catId checking
														  if($catExamMark>0 AND array_key_exists($catId, $mark)){
															  // find category marks list from mark
															  $categoryMarksList = (array)$mark[$catId];
															  // assessment list looping
															  foreach ($headAssList as $headAssDetails){
																  // assId
																  $myAssId = $headAssDetails['id'];
																  // custom assId
																  $assId = 'ass_'.$myAssId;
																  // assessment status (pass or fail)
																  $assStatus = true;
																  // assId checking
																  if(array_key_exists($assId, $categoryMarksList)){
																	// checking assessment
																	if(array_key_exists($myAssId, $subjectAssMarkList)==true){
																		$assExamMark = floatval($subjectAssMarkList[$myAssId]['exam_mark']);
																		$assPassMark = floatval($subjectAssMarkList[$myAssId]['pass_mark']);
																	}else{
																		$assExamMark = 0; $assPassMark = 0;
																	}

																	  // find assessment marks list from categoryMarksList
																	  $assessment = (array)($categoryMarksList[$assId]);
																	  // assessment details
																	  $ass_mark = floatval($assessment['ass_mark']);
																	  $ass_points = $assExamMark;
																	  //$ass_points = float val($assessment['ass_points']);
																	  $ass_mark_percentage = ($ass_mark/$ass_points)*100;

																	  // count total assessment marks and points
																	  $totalAssessmentMarks += $ass_mark;
																	  $totalAssessmentPoints += $ass_points;
																	  // $ass_mark_percentage
																	  $assessmentMarkList[$myAssId] = $ass_mark_percentage;
																	  // assessment counter
																	  $assessmentCount += 1;

																	  // checking for subject pass fail
																	if($ass_mark<$assPassMark){
																		// subject status
																		$subjectStatus = false;
																		// subject category-assessment status
																		$assStatus = false;
																	}


																	// checking subject mark for pass or fail
																	$color = ($assStatus==false?'red':'black');
																	// print assessment subject marks and points
																	//echo '<td width="7%"><span style="color:'.$color.'">'.$ass_mark.' /  '.$ass_points.'</span></td>';

																  }else{
																	 // print assessment subject marks and points
																	// echo '<td width="7%"> - </td>';
																  }
															  }
														  }else{
															  // assessment list looping
															  foreach ($headAssList as $headAssDetails){
																 // print assessment subject marks and points
																// echo '<td width="7%"> - </td>';
															  }
														  }

															// checking $totalAssessmentPoints
															if($totalAssessmentPoints>0){
															   $catMarkPercentage = ($totalAssessmentMarks*100)/$totalAssessmentPoints;
															   $catWeightedAverage = round(($catMarkPercentage*$catExamMark)/100, 2);
															}else{
																$catMarkPercentage = 0;
																$catWeightedAverage = $totalAssessmentMarks;
															}
															// calculate subject total marks
															$subjectObtainedMark += $catWeightedAverage;

															// checking category passing mark
															if($totalAssessmentMarks<$catPassMark){
																// update category status
																$catStatus = false;
																// update subject status
																$subjectStatus = false;
															}


															 //  checking assCateStatus for text color
															 $color = ($catStatus==false?'red':'black');
															 // weighted average
															 //column_4
															 $column_4_val+=$catWeightedAverage;
															 $column_4_val_2 +=$catExamMark;
														  $column_4 = '<td width="7%"> <span style="color:'.$color.'">'.$column_4_val.' / '.$column_4_val_2.'</span></td>';
													}
													$column_5_val += $subjectObtainedMark;
													// subject sub total marks
													$column_5 = '<td width="7%">'.$column_5_val.'</span></td>';

													// subject final grade calculation
													$subMarkPercent = ($subjectObtainedMark*100/$subExamMark);
													// find subject grade points
													$subGradePoint = (object) subjectGradeCalculation((int)$subGroupPercentage, $gradeScaleDetails);

													// checking for subject pass fail
													if($subjectObtainedMark<$subPassMark){
														// subject status
														$subjectStatus = false;
													}

													// checking subject is countable or not
													if($isCountable==1){
														// total subject counter
														$totalSubCount += 1;

														// all subject total and obtained mark counting
														$allSubTotalMarks += $subExamMark;
														$allSubObtainedMarks += $subjectObtainedMark;

														// subject obtained grade point
														$subjectObtainedGradePoint = $subGradePoint->point;

														// checking subject type
														if($subType==0 AND array_key_exists($csId, $stdAddSubList) AND $stdAddSubList[$csId]==0){
															// checking subject grade points
															if(($subGradePoint->point)>2){
															   $subjectObtainedGradePoint = ($subGradePoint->point-2);
															}
														}

														// total and obtained grade point counting
														$totalPoints += $subGradePoint->max_point;
														$obtainedPoints += $subjectObtainedGradePoint;
													}


		                                        $column_2_val += $subExamMark;
		                                        $column_2 = '<td>'.$column_2_val.'</td>';


												   if($mm==0 AND count($groupSubList)>1){
													    // checking group subject failed counter
													    		                                        //2
		                                        // exam marks


														if($subGroupObtained<66){
															$groupSubStatus = false;
															$totalFailedSubCount += 1;
														}else{
															$groupSubStatus = true;
														}

														//  checking assCateStatus for text color
														$color = ($groupSubStatus==false?'red':'black');
														$column_1;
														// subject total marks
														$column_6= '<td width="7%" rowspan="2"> <span style="color:'.$color.'">'.$subGroupObtained.'</span></td>';
														$column_7 = '<td width="7%" rowspan="2"> <span style="color:'.$color.'">'.$subGradePoint->grade.'</span></td>';
														$column_8= '<td width="7%" rowspan="2"> <span style="color:'.$color.'">'.$subjectObtainedGradePoint.'</span></td>';

	$string .=$column_1.$column_6.$column_7.$column_8;
	if($rowspan == 0){ $string .= '###SPANROW###';$rowspan++; }
	$string .='</tr>';
	$gpaTotal[] =$subjectObtainedGradePoint;

												   }else if($mm==0 AND count($groupSubList)==1){

													    // checking group subject failed counter
														if($subjectStatus==false){
															$totalFailedSubCount += 1;
														}
														//  checking assCateStatus for text color
														$color = ($subjectStatus==false?'red':'black');
														// subject total marks
														 $column_1;
														$column_6=  '<td width="7%"> <span style="color:'.$color.'">'.$subGroupObtained.'</span></td>';
														$column_7=  '<td width="7%"> <span style="color:'.$color.'">'.$subGradePoint->grade.'</span></td>';
														$column_8=  '<td width="7%"> <span style="color:'.$color.'">'.$subjectObtainedGradePoint.'</span></td>';
															$string .=$column_1.$column_6.$column_7.$column_8.'</tr>';
															$gpaTotal[] =$subjectObtainedGradePoint;
													}
												}
											}

										}
									}


									// general subject list looping
									foreach ($generalSubjectList as $subId=>$subjectDetails){
$subjectcount++;
										// class subject id
										$csId = $subjectDetails['cs_id'];
										// subject name
										$subjectName = $subjectDetails['sub_name'];
										// sub exam mark
										$subExamMark = $subjectDetails['exam_mark'];
										// // sub pass mark
										$subPassMark = $subjectDetails['pass_mark'];
										// // sub pass mark
										$isCountable = $subjectDetails['is_countable'];
										// subject type
										$subType = $subjectDetails['type'];
										// subject status
										$subjectObtainedMark = 0;
										// $subjectObtainedGradePoint
										$subjectObtainedGradePoint = 0;
										// subject status
										$subjectStatus = true;

										 $string .= "<tr>";

										if($isCountable==1){
		                                     if($subType==0 AND array_key_exists($subId, $stdAddSubList) AND $stdAddSubList[$subId]==0){
		                                          $string .= '<td class="subject">'.$subjectName.' (Optional)</td>';
		                                     }else{
		                                         $string .= '<td class="subject">'.$subjectName.'</td>';
		                                     }
		                                 }else{
		                                     $string .= '<td class="subject">'.$subjectName.' (UnCountable)</td>';
		                                 }

		                                 // exam marks
		                               //  echo '<td>'.$subExamMark.'</td>';

		                                  // checking subject highest marks
										 if(array_key_exists($subId, $subjectHighestMarksArrayList)==true){
										//		echo '<td>'.$subjectHighestMarksArrayList[$subId].'</td>';
										  }else{
										//		echo '<td>-</td>';
										 }

										$mySubjectCatMarksList = $subjectDetails['cat_marks_list'];
										$mark = (array)$subjectDetails['mark'];

										// single subject assessment array list
									   $subCatAssArrayList = (array)(array_key_exists($subId, $subjectAssArrayList)?$subjectAssArrayList[$subId]['cat_list']:[]);

										// assessment counter checking
										if($assessmentsCount>0 AND count($subCatAssArrayList)>0){
											foreach ($assessmentInfo as $headCatId=>$headAssList){
												  // custom catId
												  $catId = 'cat_'.$headCatId;
												  // category status (pass or fail)
												  $catStatus = true;

												  // total assessment and points
												  $totalAssessmentMarks = 0;
												  $totalAssessmentPoints = 0;
												  // assessment mark list
												  $assessmentMarkList = array();
												  // assessment count
												  $assessmentCount = 0;


												  // find category assessment mark list
												  $singleCatMarksList = (array) (array_key_exists($headCatId, $subCatAssArrayList)?$subCatAssArrayList[$headCatId]:[]);
												  // subject assessment mark list
												  $subjectAssMarkList = (array) (array_key_exists('ass_list', $singleCatMarksList)?$singleCatMarksList['ass_list']:[]);
												  // checking category id
												  if($singleCatMarksList){
													  $catExamMark = floatval($singleCatMarksList['exam_mark']);
													  $catPassMark = floatval($singleCatMarksList['pass_mark']);
												  }else{
													  $catExamMark = 0; $catPassMark = 0;
												  }

												  // catId checking
												  if($catExamMark>0 AND array_key_exists($catId, $mark)){
													  // find category marks list from mark
													  $categoryMarksList = (array)$mark[$catId];
													  // assessment list looping
													  foreach ($headAssList as $headAssDetails){
														  // assId
														  $myAssId = $headAssDetails['id'];
														  // custom assId
														  $assId = 'ass_'.$myAssId;
														  // assessment status (pass or fail)
														  $assStatus = true;
														  // assId checking
														  if(array_key_exists($assId, $categoryMarksList)){
															// checking assessment
															if(array_key_exists($myAssId, $subjectAssMarkList)==true){
																$assExamMark = floatval($subjectAssMarkList[$myAssId]['exam_mark']);
																$assPassMark = floatval($subjectAssMarkList[$myAssId]['pass_mark']);
															}else{
																$assExamMark = 0; $assPassMark = 0;
															}

															  // find assessment marks list from categoryMarksList
															  $assessment = (array)($categoryMarksList[$assId]);
															  // assessment details
															  $ass_mark = floatval($assessment['ass_mark']);
															  $ass_points = $assExamMark;
															  //$ass_points = float val($assessment['ass_points']);
															  $ass_mark_percentage = ($ass_mark/$ass_points)*100;

															  // count total assessment marks and points
															  $totalAssessmentMarks += $ass_mark;
															  $totalAssessmentPoints += $ass_points;
															  // $ass_mark_percentage
															  $assessmentMarkList[$myAssId] = $ass_mark_percentage;
															  // assessment counter
															  $assessmentCount += 1;

															  // checking for subject pass fail
															if($ass_mark<$assPassMark){
																// subject status
																$subjectStatus = false;
																// subject category-assessment status
																$assStatus = false;
															}


															// checking subject mark for pass or fail
															$color = ($assStatus==false?'red':'black');
															// print assessment subject marks and points
															//echo '<td width="7%"><span style="color:'.$color.'">'.$ass_mark.' /  '.$ass_points.'</span></td>';

														  }else{
															 // print assessment subject marks and points
															// echo '<td width="7%"> - </td>';
														  }
													  }
												  }else{
													  // assessment list looping
													  foreach ($headAssList as $headAssDetails){
														 // print assessment subject marks and points
														 //echo '<td width="7%"> - </td>';
													  }
												  }

													// checking $totalAssessmentPoints
													if($totalAssessmentPoints>0){
													   $catMarkPercentage = ($totalAssessmentMarks*100)/$totalAssessmentPoints;
													   $catWeightedAverage = round(($catMarkPercentage*$catExamMark)/100, 2);
													}else{
														$catMarkPercentage = 0;
														$catWeightedAverage = $totalAssessmentMarks;
													}
													// calculate subject total marks
													$subjectObtainedMark += $catWeightedAverage;

													// checking category passing mark
													if($totalAssessmentMarks<$catPassMark){
														// update category status
														$catStatus = false;
														// update subject status
														$subjectStatus = false;
													}


													 //  checking assCateStatus for text color
													 $color = ($catStatus==false?'red':'black');
													 // weighted average
												 // echo '<td width="7%"> <span style="color:'.$color.'">'.$catWeightedAverage.' / '.$catExamMark.'</span></td>';
											}
											// subject sub total marks
											//echo '<td width="7%">'.$subjectObtainedMark.'</span></td>';

											// subject final grade calculation
											$subMarkPercent = ($subjectObtainedMark*100/$subExamMark);
											// find subject grade points
											$subGradePoint = (object) subjectGradeCalculation((int)$subMarkPercent, $gradeScaleDetails);

											// checking for subject pass fail
											if($subjectObtainedMark<$subPassMark){
												// subject status
												$subjectStatus = false;
											}

											// checking subject is countable or not
											if($isCountable==1){
												// total subject counter
												$totalSubCount += 1;

												// all subject total and obtained mark counting
												$allSubTotalMarks += $subExamMark;
												$allSubObtainedMarks += $subjectObtainedMark;

												// subject obtained grade point
												$subjectObtainedGradePoint = $subGradePoint->point;

												// checking subject type
												if($subType==0 AND array_key_exists($csId, $stdAddSubList) AND $stdAddSubList[$csId]==0){
													// checking subject grade points
													if(($subGradePoint->point)>2){
													   $subjectObtainedGradePoint = ($subGradePoint->point-2);
													}
												}

												// total and obtained grade point counting
												$totalPoints += $subGradePoint->max_point;
												$obtainedPoints += $subjectObtainedGradePoint;

												// checking for subject pass fail
												if($subjectStatus==false){
													// subject fail counter
													$totalFailedSubCount += 1;
												}
											}

											//  checking assCateStatus for text color
											$color = ($subjectStatus==false?'red':'black');
											// subject total marks
											$string .= '<td width="7%"> <span style="color:'.$color.'">'.$subjectObtainedMark.'</span></td>';
											$string .= '<td width="7%"> <span style="color:'.$color.'">'.$subGradePoint->grade.'</span></td>';
											$string .= '<td width="7%"> <span style="color:'.$color.'">'.$subjectObtainedGradePoint.'</span></td>';
											if($isCountable==1){
												$gpaTotal[] =$subjectObtainedGradePoint;
											}
										}
									}

								@endphp
									<th width="7%">GPA</th>
									{{--<th width="7%" colspan="{{$subjectcount}}">GPA Point</th>--}}
								</tr>
								</thead>
								<tbody id="report_card_table_body" class="text-center" style="line-height: 15px;">
								@php
									$gpaTotalOut;
									if(in_array(0,$gpaTotal)){
										$gpaTotalOut = '0.0';
									}else{
									$sum = array_sum($gpaTotal);
									$gpaTotalOut = sprintf("%.2f",$sum/count($gpaTotal));
									}
				$subjectcount=$subjectcount+5;
																	$rowspancount = "<td rowspan=\"{$subjectcount}\" abcd>{$gpaTotalOut}</td>";
																	echo str_replace("###SPANROW###",$rowspancount,$string);

										@endphp

								<tr style="height: 20px;">
									<th colspan="{{(6+$myAssessmentCounter)}}">
										{{--<i>Total Subjects: {{$totalSubCount}}</i>--}}
										<i>Total Marks: {{$allSubTotalMarks}}, </i>
										<i>Obtained Marks: {{$allSubObtainedMarks}}, </i>
										{{--calculating marks percentage--}}
										@php $semesterMarksPercentage = round((($allSubObtainedMarks/$allSubTotalMarks)*100), 2, PHP_ROUND_HALF_UP); @endphp
										<i>Percent: {{$semesterMarksPercentage}} % </i>

										{{--<i> ------  </i>--}}

										{{--<i>Total Points: {{$totalPoints}}, </i>--}}
										{{--<i>Obtained Points: {{$obtainedPoints}}, </i>--}}

										{{--calculating total remaining points--}}
										@php $resultRemainingPoints = ($totalPoints-$obtainedPoints); @endphp
										{{--checking remainging points--}}
										@if($totalFailedSubCount>0)
											<i> Failed in {{$totalFailedSubCount}} {{$totalFailedSubCount>1?'subjects':'subject'}}</i>
										@else
											{{--print subject marks details (total, obtained, percentage letterGrade, letterGradePoints)--}}
											{{--@php $semesterGradePoint = (object) subjectGradeCalculation((int)$semesterMarksPercentage, $gradeScaleDetails); @endphp--}}

{{--											<i> GPA: {{round(($obtainedPoints/$totalSubCount), 2, PHP_ROUND_HALF_UP)}} </i>--}}
											{{--<i> Merit Position: {{in_array($allSubObtainedMarks, $meritList)?(array_search($allSubObtainedMarks, array_values($meritList))+1):' N/A'}} </i>--}}
										@endif
									</th>
								</tr>

								</tbody>
							</table>

							{{--checking student extra book list--}}
							@php
								$extraBookProfile = array_key_exists($studentInfo->id,$stdExtraBookArrayList)?$stdExtraBookArrayList[$studentInfo->id]:null;
								// checking student extra book list
								$extraMarkAssList = $extraBookProfile?$extraBookProfile['mark_list']:[];
							@endphp

							{{--checking $assessmentsCount--}}
							@if($gradeScale->assessmentsCount()>0)
								@php
									$allCategoryList = $gradeScale->assessmentCategory();
									$allExtraMarksList = $gradeScale->getAssessmentExtraList();
								@endphp
								{{--student extra book mark list--}}
								{{--Category list empty checking--}}
								@if(!empty($allCategoryList) AND $allCategoryList->count()>0 AND $allExtraMarksList AND $allExtraMarksList->count()>0)
									<div class="clear" style="width: 100%; margin-top: 25px;">
										{{--<p class="label text-center row-second">Other Marks</p>--}}
										<table width="100%" class="text-center table" cellspacing="5" style="line-height: 20px">

											{{--Category list loopint--}}
											@foreach($allCategoryList as $category)
												{{--checking category type--}}
												@if($category->is_sba==1)
													{{--category all assessment list--}}
													@php $allAssessmentList = $category->allAssessment($gradeScale->id); @endphp
													{{--assessment list empty checking--}}
													@if($allAssessmentList AND $allAssessmentList->count()>0)
														<thead>
														<tr class="text-center"><th colspan="{{$allExtraMarksList->count()+2}}">Other Marks</th></tr>
														<tr class="text-center">
															@php $totalAssessmentPointCounter = 0; @endphp
															{{--assessment list loopint--}}
															@foreach($allAssessmentList as $assessment)
																{{--checking assessment type--}}
																@if($assessment->counts_overall_score==0) @continue @endif
																{{--assessemnt details--}}
																<th>{{$assessment->name.' ('.$assessment->points.')'}}</th>
																{{--total Assessment Point Counter--}}
																@php $totalAssessmentPointCounter += $assessment->points; @endphp
															@endforeach
															<th>Total ({{$totalAssessmentPointCounter}})</th>
															<th>Highest</th>
														</tr>
														</thead>
														{{--break the loop --}}
														@break
													@endif
												@endif
											@endforeach
											<tbody>
											<tr class="text-center">
												{{--assessment list counter--}}
												@php $assessmentTotalCount = 0; $assessmentTotalMarks = 0; $assessmentTotalObtainedMarks = 0; @endphp
												{{--Category list loopint--}}
												@foreach($allCategoryList as $category)
													{{--checking category type--}}
													@if($category->is_sba==1)
														{{--category all assessment list--}}
														@php $allAssessmentList = $category->allAssessment($gradeScale->id); @endphp
														{{--assessment list empty checking--}}
														@if(!empty($allAssessmentList) AND $allAssessmentList->count()>0)

															{{--assessment list loopint--}}
															@foreach($allAssessmentList as $assessment)
																{{--checking assessment type--}}
																@if($assessment->counts_overall_score==0) @continue @endif
																{{--assessemnt details--}}
																{{--assessment mark--}}
																@php
																	$assessmentPoints = $assessment->points;
																	$assessmentMarks = array_key_exists($assessment->id, $extraMarkAssList)?$extraMarkAssList[$assessment->id]:null;
																@endphp

																{{--Checking assessment marks--}}
																@if($assessmentMarks)
																	<td>{{$assessmentMarks}}</td>
																	{{--assessment all calculation--}}
																	@php $assessmentTotalObtainedMarks += $assessmentMarks; @endphp
																@else
																	<td>-</td>
																@endif
																{{--assessment list counter--}}
																@php $assessmentTotalCount += 1; $assessmentTotalMarks += $assessmentPoints; @endphp
															@endforeach
															<td>{{$assessmentTotalObtainedMarks}}</td>
															<td>{{$extraHighestMarksArrayList?reset($extraHighestMarksArrayList):' - '}}</td>
															{{--break the loop --}}
															@break
														@endif
													@endif
												@endforeach
											</tr>

											{{--checking total extra marks --}}
											@if($assessmentTotalObtainedMarks>0)
												<tr class="text-bold calculation">
													<th colspan="{{(2+$assessmentTotalCount)}}">
														<i>Total: {{$resultTotalMarks+$assessmentTotalMarks}}, </i>
														@php $semesterExtraTotalObtained = ($resultObtainedMarks+$assessmentTotalObtainedMarks); @endphp
														<i>Obtained: {{$semesterExtraTotalObtained}}, </i>
														{{--calculating marks percentage--}}
														@php $semesterFinalMarksPercentage = (($resultObtainedMarks+$assessmentTotalObtainedMarks)/($resultTotalMarks+$assessmentTotalMarks))*100; @endphp
														{{--Extra Highest Marks array list --}}
														@php $extraHighestMarkArrayList = array_unique(array_values($meritListWithExtraMark)); @endphp
														<i>Highest: {{count($extraHighestMarkArrayList)>0?$extraHighestMarkArrayList[0]:'N/A'}}, </i>
														<i> Percent: {{round($semesterFinalMarksPercentage, 2, PHP_ROUND_HALF_UP)}}% </i>
														@if($resultFailedSubjectCount>0)
															{{--<i>--}}
															{{--Failed in {{$resultFailedSubjectCount}} {{$resultFailedSubjectCount>1?'subjects':'subject'}}--}}
															{{--</i>--}}
														@else
															@php $semesterFinalGradePoint = subjectGradeCalculation((int)$semesterFinalMarksPercentage, $gradeScaleDetails); @endphp
															<i> GPA: {{$semesterFinalGradePoint['point']}} , </i>
															<i> Merit Position: {{in_array($semesterExtraTotalObtained, $meritListWithExtraMark)?(array_search($semesterExtraTotalObtained, array_values($meritListWithExtraMark))+1):' N/A'}} </i>
														@endif
													</th>
												</tr>
											@endif
											</tbody>
										</table>
									</div>
								@endif
							@endif

							{{--semester attendance details--}}
							@if($semesterAttendanceList AND $semesterAttendanceList->status=='success')
								@php
									// $precision
									$precision = 2;
									// attendance details
									$holidayCount = count($semesterAttendanceList->holiday_list);
									$weekOffDayCount = count($semesterAttendanceList->week_off_day_list);
									$totalAttendanceDays = $semesterAttendanceList->total_attendance_day;
									$totalWorkingDays = $totalAttendanceDays-($holidayCount+$weekOffDayCount);
									$attendanceList = $semesterAttendanceList->attendance_list;
									// checking student attendance list
									if(array_key_exists($studentInfo->id, $attendanceList)==true){
										// My Attendance list
										$myAttendanceInfo = (object)$attendanceList[$studentInfo->id];
										$myPresentDays = $myAttendanceInfo->present;
										$myAbsentDays = $myAttendanceInfo->absent;
										// present percentage
										$presentPercentage = floatval(($myPresentDays/$totalWorkingDays)*100);
										$absentPercentage = floatval(($myAbsentDays/$totalWorkingDays)*100);
									}else{
										// My Attendance list
										$myAttendanceInfo = null;
									}

									// attendance percentage calculation
									$presentPercentage = $myAttendanceInfo?$myPresentDays.' ('.(substr(number_format($presentPercentage, $precision + 1, '.', ''), 0, -1)).' %)':'-';
									$absentPercentage = $myAttendanceInfo?$myAbsentDays.' ('.(substr(number_format($absentPercentage, $precision + 1, '.', ''), 0, -1)).' %)':'-';
								@endphp
							@else
								@php $totalWorkingDays = ' '; $presentPercentage = '  '; $absentPercentage = '  '; @endphp
							@endif

							<div class="clear" style="width: 100%; margin-top: 50px;">
								{{--table width:77%--}}
								<table width="100%" class="table" cellpadding="1" style="text-align:left; line-height: 20px;">
									<tr>
										<th style="padding-left: 10px">Comments: </th>
									</tr>
									<tr>
										<th style="padding: 30px"></th>
									</tr>
								</table>
							</div>

							<br/>
							<br/>
							<div class="clear" style="width: 100%;">

								{{--parent sign--}}
								<div style="float: left; width: 30%; text-align:center; padding: 12px;">
                                    <span style="display:{{($reportCardSetting AND $reportCardSetting->parent_sign==1)?'block':'none'}}">
                                        <span class="singnature"> </span><br>
                                        ............................................<br>
                                        <strong>Parent Signature</strong>
							        </span>
								</div>

								{{--teacher sing--}}
								<div style="float: left; width: 30%; text-align:center; padding: 12px">
                                    <span style="display:{{($reportCardSetting AND $reportCardSetting->teacher_sign==1)?'block':'none'}}">
                                        <span class="singnature"></span><br>
                                        ............................................<br>
                                        <strong>Teacher Signature</strong>
                                    </span>
								</div>

								<div style="float: right; width: 30%; text-align:center; {{($reportCardSetting AND $reportCardSetting->auth_sign)?'':'padding-top: 12px;'}}">
									{{--checking auth sign--}}
									@if($reportCardSetting AND $reportCardSetting->auth_sign!=null AND !empty($reportCardSetting->auth_sign))
										<img class="singnature" src="{{public_path().'/assets/users/images/'.$reportCardSetting->auth_sign}}"> <br>
									@endif
									{{--auth name--}}
									@if($reportCardSetting AND $reportCardSetting->auth_name!=null AND !empty($reportCardSetting->auth_name))
										@if($reportCardSetting->auth_sign==null) <span class="singnature"></span><br> @endif
										............................................<br>
										<strong>@php echo html_entity_decode($reportCardSetting->auth_name) @endphp </strong>
									@else
										<span class="singnature"></span><br>
										............................................<br>
										<strong style="font-size: 14px" >Principal / Head Teacher</strong>
									@endif
								</div>
							</div>
						@else
							<div class="row clear">
								{{--<p class="label text-center">Report Card </p>--}}
								<p class="label text-center">Result not published </p>
							</div>

							{{--page breaker--}}
							@if($stdCount<count($classSectionStudentList))
								<div style="page-break-after:always;"></div>
							@endif

							@break
						@endif

						{{--page breaker--}}
						@if($stdCount<count($classSectionStudentList))

					</div>
					<div style="page-break-after:always;"></div>
				@endif
				@else
					@continue
				@endif
			@endfor
		@else
			<div class="row clear">
				<p class="label text-center row-second">Report Card </p>
				<p class="text-center">
					<b>No records found</b><br/>
					Please check following instructions: <br/>
					1. Empty Semester List<br/>
					2. Empty Result Sheet
				</p>
			</div>
		@endif
					</div>
				</div>
	@endforeach
@endif

</div>
</body>
</html>

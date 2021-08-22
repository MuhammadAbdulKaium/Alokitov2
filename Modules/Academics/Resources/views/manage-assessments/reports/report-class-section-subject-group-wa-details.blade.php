<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<style type="text/css">
		.label {font-size: 25px;  padding: 20px 10px; font-weight: 900;}
		#progress {
			border: 2px solid black;
			padding: 5px 15px;
			border-radius: 10px;
			font-size: 20px;
			font-weight: 900;
		}
		.row-first{background-color: #b0bc9e;}
		.row-second{background-color: #e5edda;}
		.row-third{background-color: #5a6c75;}
		.text-center {text-align: center;}
		.clear{clear: both;}
		.text-bold{font-weight: 700;}

		.calculation i{
			margin-right: 15px;
		}

		.report_card_table {
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			text-align: center;
		}

		.report_card_table td, .report_card_table th { border: 1px solid black; padding: 2px; }
		.report_card_table th { padding:6px; text-align: center; color: black;  }

		.singnature{ height: 30px;  width: 40px;}
		.row { width:100%; clear: both; text-align: center}
		#std-photo{width: 30%; float: left; text-align: left}
		#inst-info{ width: 50%; float: left}
		#grade-scale{ width: 20%; float: right}

		/*.std-info-table { }*/

		#report_card {}
		#report_card th { font-size: 15px; }
		#report_card td { font-size: 14px; }
		#report_card tr>td:first-child {text-align: left; font-weight: 500; padding-left: 12px; }

		i {margin-right: 30px;}

		/*commenting table */
		.comments {
			border-collapse: collapse;
			line-height: 25px;
		}

		.comments, .comments th, .comments td {
			border: 1px solid black;
		}

	</style>
</head>


<body>

@if($classSectionStudentList)
	@php $stdCount = 0; @endphp
	@foreach($classSectionStudentList as $singleStudent)
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

					<div class="row">
						<div id="std-photo">
							@if($studentInfo->singelAttachment("PROFILE_PHOTO"))
								{{--<img src="{{public_path().'/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name}}"  style=" width:90px;height:100px; border: 2px solid #efefef">--}}
								<img src="{{asset('assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"  style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
							@else
								<img src="{{public_path().'/assets/users/images/user-default.png'}}"  style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
							@endif

							<br/>
							<br/>
							<table class="std-info-table" cellpadding="1" style="font-size:14px; line-height: 13px; text-align: left;">
								<tr>
									<th>Student's Name</th>
									<th width="3%">:</th>
									@php $stdFullName=$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name @endphp

									<td><b>{{$stdFullName}}</b></td>
								</tr>
								{{--parents information--}}
								@php $parents = $studentInfo->myGuardians(); @endphp
								{{--checking--}}
								@if($instituteInfo->id!=29 AND $instituteInfo->id!=13)
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
								@endif
								<tr>
									<th>Class </th>
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
									<th>Roll </th>
									<th>:</th>
									<td>{{$enrollment->gr_no}}</td>
								</tr>
							</table>
							@php
								$levelId = $level->id;
								$batchId = $batch->id;
								$sectionId = $section->id;
							@endphp
						</div>

						<div id="inst-info">
							<b style="font-size: 25px;">{{$instituteInfo->institute_name}}</b><br/>
							<span style="font-size:18px">{{$instituteInfo->address1}}</span>
							<br/><br/>
							@if($instituteInfo->logo)
								<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}" class="center"  style="width:80px;height:80px;">
								<br/>
								<br/>
								<p class="text-center" ><span id="progress">PROGRESS REPORT</span></p>
							@endif
						</div>
						<div id="grade-scale">
							<table style="font-size:12px; float: right; line-height: 12px" class="report_card_table" cellpadding="1">
								<thead>
								<tr>
									<th>Marks (%)</th>
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

							<table width="100%" cellpadding="1" style="font-size:14px; line-height: 13px; text-align: left; margin-top: 30px; float: right">
								<tr>
									<th>Exam</th>
									<th>:</th>
									<td>{{$allSemester[$m]['name']}}</td>
								</tr>
								<tr>
									<th>Duration </th>
									<th>:</th>
									<td>{{date('d M, Y', strtotime($allSemester[$m]['start_date']))}} - {{date('d M, Y', strtotime($allSemester[$m]['end_date']))}}</td>
								</tr>
							</table>
						</div>
					</div>
					@php
						$stdSubjectGrades = (array)$semesterResultSheet[$allSemester[$m]['id']][$studentInfo->id];
						// semester subject highest marks sheet
						$classSemesterAssessmentArrayList = $subjectHighestMarksListInClass[$allSemester[$m]['id']];
						$classMeritList = $classSemesterAssessmentArrayList['merit_list'];
						// section wise array list
						$semesterAssessmentArrayList = $subjectHighestMarksList[$allSemester[$m]['id']];
						$subjectHighestMarksArrayList = $semesterAssessmentArrayList['subject_highest_marks'];
						$meritList = $semesterAssessmentArrayList['merit_list'];
						$failedMeritList = $semesterAssessmentArrayList['failed_merit_list'];
						$extraHighestMarksArrayList = $semesterAssessmentArrayList['extra_highest_marks'];
						$meritListWithExtraMark = $semesterAssessmentArrayList['merit_list_with_extra_mark'];
						$classSectionExtraCategory = 0;
						// subject assessment array list
						$subjectAssArrayList = (array)($subjectAssessmentArrayList['subject_list']);
						// semester attendance sheet
						$semesterAttendanceList = $semesterAttendanceSheet[$allSemester[$m]['id']];
						// semester extraBook mark sheet
						$stdExtraBookArrayList = $stdExtraBookMarkSheet[$allSemester[$m]['id']];
						// student additional subject list
						$stdAddSubList = array_key_exists($studentInfo->id, $additionalSubjectList)? $additionalSubjectList[$studentInfo->id]:[];
					@endphp

					{{--assesssement details array list--}}
					@php $assessmentInfo = array(); @endphp
					@php $assessmentWAInfo = array(); @endphp
					@php $assessmentResultCountInfo = array(); @endphp
					{{--subject grade list checking--}}
					@if(count($stdSubjectGrades)>0)
						<div class="clear" style="width: 100%;">
							<br/>
							{{--<p style="font-size: 13px;" class="label text-center row-first">{{$allSemester[$m]['name']}} ({{date('d M, Y', strtotime($allSemester[$m]['start_date']))." - ".date('d M, Y', strtotime($allSemester[$m]['end_date']))}}) </p>--}}
							{{--<table class="report_card_table">--}}

							<table id="report_card" width="100%" class="text-center report_card_table" cellspacing="5">
								<thead>
								<tr class="text-center row-first">
									<th width="25%">Subject</th>
									<th width="5%">Full Marks</th>
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
															<th>{{$assessment->name}}</th>
															{{--store assessment info--}}
															@php $assessmentInfo[$category->id][] = ['id'=>$assessment->id]; @endphp
														@endforeach
														{{--checking result count for best result average--}}
														@if($resultCount AND $resultCount->result_count>0)
															@php $assessmentResultCountInfo[$category->id] = $resultCount->result_count;@endphp
														@endif
														{{--<th>{{$category->name}}</th>--}}
														<th>W/A</th>
													@endif
												@endif
											@endforeach
										@endif
									@endif
									<th width="7%">Sub Total</th>
									<th width="7%">Total <br/></th>
									<th width="7%">Grade</th>
									<th width="7%">Point</th>
								</tr>
								</thead>
								<tbody id="report_card_table_body" class="text-center" style="line-height: 15px;">


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
									foreach ($subjectGroupList as $subGroupId=>$subGroupDetails){
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

												echo "<tr>";

											   // if($subType==0 AND array_key_exists($csId, $stdAddSubList) AND $stdAddSubList[$csId]==0){
												 //   echo "<td>".$subjectName." <b>(Optional)</b></td>";
												//}else{
												//	echo "<td>".$subName."</td>";
												//}

												if($isCountable==1){
		                                           // if($subType==0 AND array_key_exists($subId, $stdAddSubList) AND $stdAddSubList[$subId]==0){

		                                             if(($subType==2 ||$subType==3)|| in_array($subGroupId, $stdAddSubList)){
		                                                // checking additional subject
                                                        if($subGroupId==end($stdAddSubList)){
		                                                    echo '<td class="subject">'.$subName.' (Optional)</td>';
		                                                }else{
		                                                    echo '<td class="subject">'.$subName.'</td>';
		                                                }
		                                            }else{
		                                                echo '<td class="subject">'.$subName.'</td>';
		                                            }
		                                        }else{
		                                            echo '<td class="subject">'.$subName.' (UnCountable)</td>';
		                                        }
		                                        // exam marks
		                                        echo '<td>'.$subExamMark.'</td>';

		                                         // checking subject highest marks
												 //if(array_key_exists($subId, $subjectHighestMarksArrayList)==true){
												//		echo '<td>'.$subjectHighestMarksArrayList[$subId].'</td>';
												 // }else{
												//		echo '<td>-</td>';
												 //}



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
																	echo '<td width="7%"><span style="color:'.$color.'">'.$ass_mark.'</span></td>';
																	// echo '<td width="7%"><span style="color:'.$color.'">'.$ass_mark.' /  '.$ass_points.'</span></td>';

																  }else{
																	 // print assessment subject marks and points
																	 echo '<td width="7%"> - </td>';
																  }
															  }
														  }else{
															  // assessment list looping
															  foreach ($headAssList as $headAssDetails){
																 // print assessment subject marks and points
																 echo '<td width="7%"> - </td>';
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
														  echo '<td width="7%"> <span style="color:'.$color.'">'.$catWeightedAverage.'</span></td>';
														  // echo '<td width="7%"> <span style="color:'.$color.'">'.$catWeightedAverage.' / '.$catExamMark.'</span></td>';
													}
													// subject sub total marks
													echo '<td width="7%">'.$subjectObtainedMark.'</span></td>';

													// subject final grade calculation
													$subMarkPercent = round(($subjectObtainedMark*100/$subExamMark), 2, PHP_ROUND_HALF_UP);
													// find subject grade points
													$subGradePoint = (object) subjectGradeCalculation((int)$subGroupPercentage, $gradeScaleDetails);

													// checking for subject pass fail
													if($subjectObtainedMark<$subPassMark){
														// subject status
														$subjectStatus = false;
													}

													// checking subject is countable or not
													if($isCountable==1){
														// all subject total and obtained mark counting
														$allSubTotalMarks += $subExamMark;
														$allSubObtainedMarks += $subjectObtainedMark;

														// subject obtained grade point
														$subjectObtainedGradePoint = $subGradePoint->point;

														// checking subject type
														 if(($subType==2 ||$subType==3)|| in_array($subGroupId, $stdAddSubList)){
			                                                // checking additional subject
	                                                        if($subGroupId==end($stdAddSubList)){
																// checking subject grade points
																if(($subGradePoint->point)>2){
																   $subjectObtainedGradePoint = ($subGradePoint->point-2);
																}
															}else{
																// total subject counter
																$totalSubCount += 1;
															}
														 }else{
															// total subject counter
															$totalSubCount += 0.5;
														 }
													}

												   if($mm==0 AND count($groupSubList)>1){
													    // batch array list [batchId=>Pass_marks]
													    $myBatchArrayList = [85=>50, 86=>50, 105=>50, 106=>50];
													    // checking batch id
													    $subGroupPassMarks = array_key_exists($batchId, $myBatchArrayList)?($myBatchArrayList[$batchId]):66;

													    // checking group subject failed counter
														if($subGroupObtained<$subGroupPassMarks){
															$groupSubStatus = false;
															$totalFailedSubCount += 1;
														}else{
															$groupSubStatus = true;
														}


														// total and obtained grade point counting
														$totalPoints += $subGradePoint->max_point;
														$obtainedPoints += $subjectObtainedGradePoint;

														//  checking assCateStatus for text color
														$color = ($groupSubStatus==false?'red':'black');
														// subject total marks
														echo '<td width="7%" rowspan="2"> <span style="color:'.$color.'">'.$subGroupObtained.'</span></td>';
														echo '<td width="7%" rowspan="2"> <span style="color:'.$color.'">'.$subGradePoint->grade.'</span></td>';
														echo '<td width="7%" rowspan="2"> <span style="color:'.$color.'">'.$subjectObtainedGradePoint.'</span></td>';

												   }else if($mm==0 AND count($groupSubList)==1){

														// checking subject type
														 if(($subType==2 ||$subType==3)|| in_array($subGroupId, $stdAddSubList)){
			                                                // checking additional subject
	                                                        if($subGroupId!=end($stdAddSubList)){
	                                                        	// checking group subject failed counter
																if($subjectStatus==false){ $totalFailedSubCount += 1;}
																// total and obtained grade point counting
																$totalPoints += $subGradePoint->max_point;
															}
														 }else{
														 	// checking group subject failed counter
															if($subjectStatus==false){ $totalFailedSubCount += 1;}
														 }


														$obtainedPoints += $subjectObtainedGradePoint;

														//  checking assCateStatus for text color
														$color = ($subjectStatus==false?'red':'black');
														// subject total marks
														echo '<td width="7%"> <span style="color:'.$color.'">'.$subGroupObtained.'</span></td>';
														echo '<td width="7%"> <span style="color:'.$color.'">'.$subGradePoint->grade.'</span></td>';
														echo '<td width="7%"> <span style="color:'.$color.'">'.$subjectObtainedGradePoint.'</span></td>';
													}
												}
											}

										}
									}


									// general subject list looping
									foreach ($generalSubjectList as $subId=>$subjectDetails){

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

										echo "<tr>";

										if($isCountable==1){
		                                     if($subType==0 AND array_key_exists($subId, $stdAddSubList) AND $stdAddSubList[$subId]==0){
		                                         echo '<td class="subject">'.$subjectName.' (Optional)</td>';
		                                     }else{
		                                         echo '<td class="subject">'.$subjectName.'</td>';
		                                     }
		                                 }else{
		                                     echo '<td class="subject">'.$subjectName.' (UnCountable)</td>';
		                                 }
		                                 
		                                 // exam marks
		                                 echo '<td>'.$subExamMark.'</td>';

		                                  // checking subject highest marks
										 //if(array_key_exists($subId, $subjectHighestMarksArrayList)==true){
										//		echo '<td>'.$subjectHighestMarksArrayList[$subId].'</td>';
										  //}else{
										//		echo '<td>-</td>';
										 //}

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
															echo '<td width="7%"><span style="color:'.$color.'">'.$ass_mark.'</span></td>';
															//echo '<td width="7%"><span style="color:'.$color.'">'.$ass_mark.' /  '.$ass_points.'</span></td>';

														  }else{
															 // print assessment subject marks and points
															 echo '<td width="7%"> - </td>';
														  }
													  }
												  }else{
													  // assessment list looping
													  foreach ($headAssList as $headAssDetails){
														 // print assessment subject marks and points
														 echo '<td width="7%"> - </td>';
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
												  echo '<td width="7%"> <span style="color:'.$color.'">'.$catWeightedAverage.'</span></td>';
												  // echo '<td width="7%"> <span style="color:'.$color.'">'.$catWeightedAverage.' / '.$catExamMark.'</span></td>';
											}
											// subject sub total marks
											echo '<td width="7%">'.$subjectObtainedMark.'</span></td>';

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
											echo '<td width="7%"> <span style="color:'.$color.'">'.$subjectObtainedMark.'</span></td>';
											echo '<td width="7%"> <span style="color:'.$color.'">'.$subGradePoint->grade.'</span></td>';
											echo '<td width="7%"> <span style="color:'.$color.'">'.$subjectObtainedGradePoint.'</span></td>';
										}
									}
								@endphp

								<tr>
									<th colspan="{{(6+$myAssessmentCounter)}}">
										{{--<i>Compulsory Subjects: {{$totalSubCount}}</i>--}}
										<i>Total Marks: {{$allSubTotalMarks}}, </i>
										@php
											$allSubObtainedMarks = array_key_exists($studentInfo->id, $meritList)?($meritList[$studentInfo->id]/100):$allSubObtainedMarks;
										@endphp
										<i>Obtained Marks: {{$allSubObtainedMarks}}, </i>
										{{--Subject Highest Marks array list --}}
										@php $subHighestMarkArrayList = array_unique(array_values($meritList)); @endphp
										{{--class merit list--}}
										@php $classMeritList = array_unique(array_values($classMeritList)); @endphp
										<i>Highest: {{count($subHighestMarkArrayList)>0?round($subHighestMarkArrayList[0], 2)/100:'N/A'}}, </i>
										{{--calculating marks percentage--}}
										@php
											$semesterMarksPercentage = round((($allSubObtainedMarks/$allSubTotalMarks)*100), 2, PHP_ROUND_HALF_UP);
										@endphp
										<i>Percent: {{$semesterMarksPercentage}} %, </i>
										{{--<i>Total GP: {{$totalPoints}}, </i>--}}
										{{--<i>Obtained GP: {{$obtainedPoints}}, </i>--}}

										{{--calculating total remaining points--}}
										@php $resultRemainingPoints = ($totalPoints-$obtainedPoints); @endphp
										{{--checking remainging points--}}
										@if($totalFailedSubCount>0)
											<i> Failed in {{$totalFailedSubCount}} {{count($totalFailedSubCount)>1?'subjects':'subject'}}</i>
										@else

											<i> GPA: {{round((($obtainedPoints>$totalPoints?$totalPoints:$obtainedPoints)/$totalSubCount), 2, PHP_ROUND_HALF_UP)}}, </i>

											{{--print subject marks details (total, obtained, percentage letterGrade, letterGradePoints)--}}
											@php
												$semesterGradePoint = (object) subjectGradeCalculation((int)$semesterMarksPercentage, $gradeScaleDetails);

											    // mark conversion (float to integer)
                                                $myTotalMarks = (int) round($allSubObtainedMarks*100);
                                                // checking section merit position
                                                if(in_array($myTotalMarks, $subHighestMarkArrayList)){
                                                    $semesterSectionMeritPosition = (array_search($myTotalMarks, $subHighestMarkArrayList)+1);
                                                }else{
                                                    $semesterSectionMeritPosition = 'N/A';
                                                }
                                                // checking class merit position
                                                if(in_array($myTotalMarks, $classMeritList)){
                                                    $semesterClassMeritPosition = (array_search($myTotalMarks, $classMeritList)+1);
                                                }else{
                                                    $semesterClassMeritPosition = 'N/A';
                                                }

											@endphp
											{{--											<i> GPA: {{$semesterGradePoint->point}}, </i>--}}
											<i> Merit Position (Section): {{$semesterSectionMeritPosition}}, </i>
											<i> Merit Position (Class):  {{$semesterClassMeritPosition}} </i>

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


							{{--semester attendance details--}}
							@if($semesterAttendanceList AND $semesterAttendanceList->status=='success')
								<div class="clear" style="width: 100%; margin-top: 20px;">
									<table width="100%" class="text-center report_card_table" cellspacing="5">
										<thead>
										<tr class="text-center"><td colspan="5">Attendance Details</td></tr>
										<tr class="text-center row-first">
											<td>Total Days</td>
											<td>Holiday + Week Off Day</td>
											<td>Working Day</td>
											<td>Present</td>
											<td>Absent</td>
										</tr>
										</thead>
										<tbody>
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
										@endphp
										<tr>
											<td>{{$totalAttendanceDays}}</td>
											<td>{{$holidayCount.' + '.$weekOffDayCount.' = '.($holidayCount+$weekOffDayCount)}}</td>
											<td>{{$totalWorkingDays}}</td>
											<td>{{$myAttendanceInfo?$myPresentDays.' ('.(substr(number_format($presentPercentage, $precision + 1, '.', ''), 0, -1)).' %)':'-'}}</td>
											<td>{{$myAttendanceInfo?$myAbsentDays.' ('.(substr(number_format($absentPercentage, $precision + 1, '.', ''), 0, -1)).' %)':'-'}}</td>
										</tr>
										</tbody>
									</table>
								</div>
							@endif

						</div>
						<div class="clear" style="width: 100%; margin-top: 50px;">
							{{--table width:77%--}}
							<table width="100%" class="comments" cellpadding="1" style="text-align:left; line-height: 20px;">
								<tr>
									<th style="padding-left: 10px">Comments: </th>
								</tr>
								<tr>
									<th style="padding: 38px"></th>
								</tr>
							</table>
						</div>

						<br/>
						<br/>
						<div class="clear" style="width: 100%;">
							<div style="float: left; width: 30%; text-align:center; padding: 20px;">
                                <span style="display:{{($reportCardSetting AND $reportCardSetting->parent_sign==1)?'block':'none'}}">
                                    <span class="singnature"> </span><br>
                                ............................................<br>
                                    <strong>Guardian</strong>
                                </span>
							</div>

							<div style="float: left; width: 30%; text-align:center; padding: 20px">
                                <span style="display:{{($reportCardSetting AND $reportCardSetting->teacher_sign==1)?'block':'none'}}">
                                    <span class="singnature"></span><br>
                                ............................................<br>
                                    <strong>Class Teacher</strong>
                                </span>
							</div>

							{{--                            @php $qrCode="Name:".$stdFullName.", Total: ".$totalClassMarks.", Obtained: ".$totalStdObtainedMarks.", Percent: ".$semesterMarksPercentage."%, GPA: ".$semesterGradePoint.", Merit Position: ".$semesterMeritPosition @endphp--}}

							{{--<div class="qrcode" style="width: 50% ; float: left; margin: 0px; padding: 0px">--}}
							{{--<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($qrCode))!!} ">--}}
							{{--</div>--}}

							<div style="float: right; width: 30%; text-align:center; {{($reportCardSetting AND $reportCardSetting->auth_sign)?'':'padding: 20px;'}}">
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
									<strong>Principal / Head Teacher</strong>
								@endif
							</div>
						</div>

					@else
						<div class="row clear">
							<p class="label text-center">Report Card </p>
							<p class="text-center">
								<b>Result not published</b><br/>
							</p>
						</div>
						@break
					@endif





					{{--page breaker--}}
					@if($stdCount<count($classSectionStudentList))
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
	@endforeach
@endif
</body>
</html>

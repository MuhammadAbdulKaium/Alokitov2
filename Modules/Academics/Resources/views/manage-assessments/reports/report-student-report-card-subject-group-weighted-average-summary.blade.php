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

		#std-info {
			float:left;
			width: 79%;
		}
		#std-photo {
			float:right;
			width: 20%;
			margin-left: 10px;
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

		/*th,td {line-height: 20px;}*/
		/*html{margin:25px}*/

	</style>
</head>
<body>

{{--semester loop counter--}}
@php $semesterLoopCounter = 0; @endphp

<!-- attendance Report -->
@if(count($semesterResultSheet)>0 && count($allSemester)>0)

	{{--semester looping--}}
	@for($m=0; $m<count($allSemester); $m++)

		{{--semester loop count--}}
		@php $semesterLoopCounter += 1; @endphp

		@if(array_key_exists($allSemester[$m]['id'], $semesterResultSheet)==true)

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

			{{--Student Infromation--}}
			<div class="clear" style="width: 100%;">
				<p class="label text-center" style="font-size: 18px">PROGRESS REPORT</p>
				<div id="std-info">
					<table width="100%" cellpadding="1" style="font-size:14px">
						<tr>
							<th width="20%">Name of Student</th>
							<th width="1%">:</th>
							<td><b>{{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</b></td>
						</tr>
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

							@php $division = null; @endphp
							@if($divisionInfo = $enrollment->batch()->get_division())
								@php $division = " (".$divisionInfo->name.") "; @endphp
							@endif
							<td>{{$enrollment->batch()->batch_name.$division}} - {{$enrollment->section()->section_name}} </td>
						</tr>
						<tr>
							<th>Roll </th>
							<th>:</th>
							<td>{{$enrollment->gr_no}}</td>
						</tr>
						<tr>
							<th>Date of Birth </th>
							<th>:</th>
							<td>{{ date('d M, Y', strtotime($studentInfo->dob)) }}</td>
						</tr>
					</table>
					@php
						$levelId = $level->id;
						$batchId = $batch->id;
						$sectionId = $section->id;
					@endphp
				</div>
				<div id="std-photo" style="margin-left: 562px">
					<table width="100%" style="font-size: 10px;" border="1px solid" class="text-center report_card_table" cellpadding="2">
						<thead>
						<tr>
							<th>Marks</th>
							<th>Grade</th>
							<th>Point</th>
						</tr>
						</thead>
						<tbody>
						@foreach($gradeScaleDetails as $gradeScaleDetail){
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

			@php
				$stdSubjectGrades = $semesterResultSheet[$allSemester[$m]['id']];
				// semester subject highest marks sheet
				$semesterAssessmentArrayList = $subjectHighestMarksList[$allSemester[$m]['id']];
				$subjectHighestMarksArrayList = $semesterAssessmentArrayList['subject_highest_marks'];
				$meritList = $semesterAssessmentArrayList['merit_list'];
				$extraHighestMarksArrayList = $semesterAssessmentArrayList['extra_highest_marks'];
				$meritListWithExtraMark = $semesterAssessmentArrayList['merit_list_with_extra_mark'];
				$classSectionExtraCategory = 0;
				// subject assessment array list
				$subjectAssArrayList = (array)($subjectAssessmentArrayList['subject_list']);
				// semester attendance sheet
				$semesterAttendanceList = $semesterAttendanceSheet[$allSemester[$m]['id']];
				// semester extraBook mark sheet
				$stdExtraBookArrayList = $stdExtraBookMarkSheet[$allSemester[$m]['id']];
			@endphp

			{{--assesssement details array list--}}
			@php $assessmentInfo = array(); @endphp
			@php $assessmentWAInfo = array(); @endphp
			@php $assessmentResultCountInfo = array(); @endphp
			{{--subject grade list checking--}}
			@if(count($stdSubjectGrades)>0)
				<div class="clear" style="width: 100%">
					{{--@if($stdSubjectGrades == null) @continue @endif--}}
					<p class="label text-center row-second">{{$allSemester[$m]['name']}} ({{date('d M, Y', strtotime($allSemester[$m]['start_date']))." - ".date('d M, Y', strtotime($allSemester[$m]['end_date']))}}) </p>
					{{--<table class="report_card_table">--}}
					<table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5">
						<thead>
						<tr class="text-center row-second">
							<th width="25%">Subject</th>
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
													{{--store assessment info--}}
													@php $assessmentInfo[$category->id][] = ['id'=>$assessment->id]; @endphp
												@endforeach
												{{--checking result count for best result average--}}
												@if($resultCount AND $resultCount->result_count>0)
													@php $assessmentResultCountInfo[$category->id] = $resultCount->result_count;@endphp
												@endif
												<th>{{$category->name}}</th>
											@endif
										@endif
									@endforeach
								@endif
							@endif
							{{--<th>Total</th>--}}
							<th width="7%">Total <br/></th>
							<th width="7%">Grade</th>
							<th width="7%">Point</th>
						</tr>
						</thead>
						<tbody id="report_card_table_body" class="text-center" style="line-height: 20px;">


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
									// subject group average grade list
									$subAverageGrade = ['total'=>0, 'obtained'=>0, 'letterGrade'=>0];
									// subject total exam and pass mark mark
									$subjectExamMark = 0;
									$subjectPassMark = 0;
									// group subject status
                                    $subjectStatus = true;

									$subCatAverageMark = array();
									// looping
									for($mm=0; $mm<count($groupSubList); $mm++){
										// singleSubject
										$singleSubject = $groupSubList[$mm];
										#sub details
										$subId = $singleSubject['sub_id'];
										// class subject id
                                        $csId = $singleSubject['cs_id'];
										// sub exam mark
                                        $subExamMark = $singleSubject['exam_mark'];
                                        // sub pass mark
                                        $subPassMark = $singleSubject['pass_mark'];
                                        // sub pass mark
                                        $isCountable = $singleSubject['is_countable'];


                                        // single subject assessment array list
                                       $subCatAssArrayList = (array)(array_key_exists($subId, $subjectAssArrayList)?$subjectAssArrayList[$subId]['cat_list']:[]);

										// subject grade list
										if(array_key_exists($subId, $groupGradeList)==true){

											$sub_grade = $groupGradeList[$subId];
											// subject category marks list
											$subjectCatMarksList = $sub_grade['cat_marks_list'];

											// assessment counter checking
											if($assessmentsCount>0){
												// assessment category list
												$allCategoryList = $gradeScale->assessmentCategory();
												// {{--Category list empty checking--}}
												if(!empty($allCategoryList) AND $allCategoryList->count()>0){
													// Category list looping
													foreach($allCategoryList as $category){
														// checking category type
														if($category->is_sba==0){
															// subject category marks
															$catMark = $subjectCatMarksList[$category->id];
															// checking
															if(array_key_exists($category->id, $subCatAverageMark)){
																$subCatAverageMark[$category->id] += $catMark;
															}else{
																$subCatAverageMark[$category->id] = $catMark;
															}
														}
													}
												}
											}

											// subject exam and pass mark
											$subjectExamMark += $subExamMark;
											$subjectPassMark += $subPassMark;

											// grade details
											$subAverageGrade['total'] += $sub_grade['total'];
											$subAverageGrade['obtained'] += $sub_grade['obtained'];
										}else{
										   $subAverageGrade['total'] = 0;
										   $subAverageGrade['obtained'] = 0;
										}
									}


									// checking
									if($subAverageGrade['total']==0 AND $subAverageGrade['obtained']==0 AND $subAverageGrade['letterGrade']==0){
										echo '<tr><td>'.$subGroupName.'</td><td colspan="5"> All subject result not published </td></tr>';
									}else{

										$totalMarks = $subAverageGrade['total'];
										$obtainedMarks = $subAverageGrade['obtained'];
										$percentage = (($obtainedMarks/$totalMarks)*100);
										// letter grade details
										$letterGradeDetails = (object) subjectGradeCalculation((int)$percentage, $gradeScaleDetails);
										// total and obtained grade point counting
										$totalPoints += $letterGradeDetails->max_point;
										$obtainedPoints += $letterGradeDetails->point;

										echo '<tr>';
										echo '<td>'.$subGroupName.'</td>';
										// assessment counter checking
										if($assessmentsCount>0){
											// assessment category list
											$allCategoryList = $gradeScale->assessmentCategory();
											// {{--Category list empty checking--}}
											if(!empty($allCategoryList) AND $allCategoryList->count()>0){
												// Category list looping
												foreach($allCategoryList as $category){
													// checking $categoryWAMark
													if($category->is_sba==0){
														// checking
														if(array_key_exists($category->id, $subCatAverageMark)){
															$myCatMark = $subCatAverageMark[$category->id];
															//$myCatMark = $subCatAverageMark[$category->id]/count($groupSubList);
														}else{
														 $myCatMark = ' - ';
														}
														// print subject category marks
														echo '<td>'.$myCatMark.'</td>';
													}
												}
											}
										}


										// checking for subject pass fail
										if($obtainedMarks<$subjectPassMark){
										    // subject status
										    $subjectStatus = false;
										}
										// checking for subject pass fail
										if($subjectStatus==false){
										   $totalFailedSubCount += 1;
										}

										//  checking assCateStatus for text color
                                        $color = ($subjectStatus==false?'red':'black');

										echo '<td> <span style="color:'.$color.'">'.$obtainedMarks.'</span></td>';
										echo '<td> <span style="color:'.$color.'">'.$letterGradeDetails->grade.'</span></td>';
										echo '<td> <span style="color:'.$color.'">'.$letterGradeDetails->point.'</span></td>';
										echo '</tr>';

										// calculating total marks
										$allSubTotalMarks += $totalMarks;
										$allSubObtainedMarks += $obtainedMarks;

										// total subject counter
										$totalSubCount += 1;
									}
								}else{
									echo '<tr><td>'.$subGroupName.'</td><td colspan="5"> Subject result not published </td></tr>';
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
								// subject status
	                            $subjectStatus = true;

								$mySubjectCatMarksList = $subjectDetails['cat_marks_list'];
								echo "<tr>";
								echo "<td>".$subjectDetails['sub_name']."</td>";
								// assessment counter checking
								if($assessmentsCount>0){
									// assessment category list
									$allCategoryList = $gradeScale->assessmentCategory();
									// {{--Category list empty checking--}}
									if(!empty($allCategoryList) AND $allCategoryList->count()>0){
										// Category list looping
										foreach($allCategoryList as $category){
											// $categoryWAMark
											$categoryWAMark = array_key_exists($category->id, $weightedAverageArrayList)==true?$weightedAverageArrayList[$category->id]:0;
											// checking $categoryWAMark
											if($categoryWAMark>0){
												// checking
												if(array_key_exists($category->id, $mySubjectCatMarksList)){
													// subject category marks
													$catMark = $mySubjectCatMarksList[$category->id];
													echo "<td>".$catMark."</td>";
												}else{
													echo "<td>0</td>";
												}
											}
										}
									}
								}

								// subject obtained marks
								$subjectObtainedMark = $subjectDetails['obtained'];
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

									// checking subject type
                                    if($subType==0 AND array_key_exists($csId, $stdAddSubList) AND $stdAddSubList[$csId]==0){
                                        // checking subject grade points
                                        if(($subGradePoint->point)>2){
                                            $obtainedPoints += (($subGradePoint->point)-2);
                                        }
                                    }else{
										// total and obtained grade point counting
										$totalPoints += $subGradePoint->max_point;
										$obtainedPoints += $subGradePoint->point;
                                    }

									// checking for subject pass fail
	                                if($subjectStatus==false){
	                                    // subject fail counter
	                                    $totalFailedSubCount += 1;
	                                }
								}

								//  checking assCateStatus for text color
                                $color = ($subjectStatus==false?'red':'black');
								echo '<td> <span style="color:'.$color.'">'.$subjectObtainedMark."</span></td>";
								echo '<td> <span style="color:'.$color.'">'.$subGradePoint->grade."</span></td>";
								echo '<td> <span style="color:'.$color.'">'.$subGradePoint->point."</span></td>";
								echo "</tr>";
							}
						@endphp

						<tr>
							<th colspan="{{(4+$myAssessmentCounter)}}">
								{{--<i>Total Subjects: {{$totalSubCount}}</i>--}}
								<i>Total: {{$allSubTotalMarks}}, </i>
								<i>Obtained: {{$allSubObtainedMarks}}, </i>
								{{--calculating marks percentage--}}
								@php $semesterMarksPercentage = round((($allSubObtainedMarks/$allSubTotalMarks)*100), 2, PHP_ROUND_HALF_UP); @endphp
								<i> Percent: {{$semesterMarksPercentage}}%, </i>

								{{--<i> ------  </i>--}}

								{{--<i>Total Points: {{$totalPoints}}, </i>--}}
								{{--<i>Obtained Points: {{$obtainedPoints}}, </i>--}}

								{{--calculating total remaining points--}}
								{{--@php $resultRemainingPoints = $resultTotalPoints-$resultObtainedPoints; @endphp--}}
								{{--checking remainging points--}}
								{{--@if($resultFailedSubjectCount>0)--}}
								{{--<i>--}}
								{{--Failed in {{$resultFailedSubjectCount}} {{count($resultFailedSubjectCount)>1?'subjects':'subject'}}--}}
								{{--</i>--}}
								{{--@else--}}
								{{--// print subject marks details (total, obtained, percentage letterGrade, letterGradePoints)--}}
								@php $semesterGradePoint = subjectGradeCalculation((int)$semesterMarksPercentage, $gradeScaleDetails); @endphp

								{{--<i> GPA: {{$semesterGradePoint['point']}}</i>--}}
								<i> GPA: {{round(($obtainedPoints/$totalSubCount), 2, PHP_ROUND_HALF_UP)}} </i>
								{{--<i> Merit Position: {{array_key_exists($studentInfo->id, $meritList)?(array_search($studentInfo->id, array_keys($meritList))+1):' N/A'}} </i>--}}
								{{--@endif--}}
							</th>
						</tr>

						</tbody>
					</table>
				</div>


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
						<div class="clear" style="width: 100%; margin-top: 20px;">
							{{--<p class="label text-center row-second">Other Marks</p>--}}
							<table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5">

								{{--Category list loopint--}}
								@foreach($allCategoryList as $category)
									{{--$categoryWAMark--}}
									@php $categoryWAMark = array_key_exists($category->id, $weightedAverageArrayList)==true?$weightedAverageArrayList[$category->id]:0; @endphp
									{{--checking $categoryWAMark--}}
									@if($categoryWAMark==0)
										{{--category all assessment list--}}
										@php $allAssessmentList = $category->allAssessment($gradeScale->id); @endphp
										{{--assessment list empty checking--}}
										@if($allAssessmentList AND $allAssessmentList->count()>0)
											<thead>
											<tr class="text-center row-second"><th colspan="{{$allExtraMarksList->count()+2}}">Other Marks</th></tr>
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
										{{--$categoryWAMark--}}
										@php $categoryWAMark = array_key_exists($category->id, $weightedAverageArrayList)==true?$weightedAverageArrayList[$category->id]:0; @endphp


										{{--checking $categoryWAMark--}}
										@if($categoryWAMark==0)
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
									<tr>
										<th colspan="{{(2+$assessmentTotalCount)}}">
											<i>Total: {{$resultTotalMarks+$assessmentTotalMarks}}, </i>
											<i>Obtained: {{$resultObtainedMarks+$assessmentTotalObtainedMarks}}, </i>
											{{--calculating marks percentage--}}
											@php $semesterFinalMarksPercentage = (($resultObtainedMarks+$assessmentTotalObtainedMarks)/($resultTotalMarks+$assessmentTotalMarks))*100; @endphp
											<i> Percent: {{(int)$semesterFinalMarksPercentage}}%, </i>
											@if($resultFailedSubjectCount>0)
												<i>
													Failed in {{$resultFailedSubjectCount}} {{count($resultFailedSubjectCount)>1?'subjects':'subject'}}
												</i>
											@else
												@php $semesterFinalGradePoint = subjectGradeCalculation((int)$semesterFinalMarksPercentage, $gradeScaleDetails); @endphp
												<i> GPA: {{$semesterFinalGradePoint['point']}} , </i>
												<i> Merit Position: {{array_key_exists($studentInfo->id, $meritListWithExtraMark)?(array_search($studentInfo->id, array_keys($meritListWithExtraMark))+1):' N/A'}} </i>
											@endif
										</th>
									</tr>
								@endif
								</tbody>
							</table>
						</div>
					@endif
				@endif

				@if($semesterAttendanceList AND $semesterAttendanceList->status=='success')
					<div class="clear" style="width: 100%; margin-top: 20px;">
						<table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5">
							<thead>
							<tr class="text-center row-second"><th colspan="5">Attendance Details</th></tr>
							<tr class="text-center">
								<th>Total Days</th>
								<th>Holiday + Week Off Day</th>
								<th>Working Day</th>
								<th>Present</th>
								<th>Absent</th>
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

				<br/>
				<div class="clear" style="width: 100%; margin-top: 50px;">
					<div style="float: left; width: 50%; text-align:center">
						{{--...............................<br>--}}
						{{--<strong>Parent</strong>--}}
					</div>
					<div style="float: left; width: 50%; text-align:center;">
						..................................................<br>
						<strong>Principal / Head Teacher</strong>
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
		@endif
		@if($semesterLoopCounter != count($allSemester))
			<div style="page-break-after:always;"></div>
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
</body>
</html>

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

		/*body{*/
		/*font-size: 10px;*/
		/*}*/
		.report_card_table{
			border: 1px solid #dddddd;
			border-collapse: collapse;
			line-height: 12px;
			font-size: 10px;
		}

		.second-table{
			font-size: 10px;
			/*line-height: 5px;*/
		}

		/*th,td {line-height: 20px;}*/
		html{margin:40px}

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
	<p class="label row-first text-center">{{$classInfo['batch_name']}} - {{$classInfo['section_name']}} {{$classInfo['semester_name']}} Tabulation Sheet </p>
	{{--checking semester result sheet--}}
	@if(count($semesterResultSheet)>0)

		{{--subject assessment array list--}}
		@php
			// subject assessment array list
			$subjectAssArrayList = (array)($subjectAssessmentArrayList['subject_list']);
			// assessment list
			$assessmentArrayList = [];

			// request Category
			$requestCategory = $category;

		@endphp

		{{--class subject list with student name--}}
		<table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5">
			<thead>
			<tr>
				<th width="2%" rowspan="2">Roll</th>
				<th width="10%" rowspan="2">Student Name</th>
				{{--subject list checking--}}
				@if(count($classSubjects)>0)
					{{--subject list looping--}}
					@foreach($classSubjects as $index=>$subject)
						{{--@if($subject['cs_id']==699) @continue @endif--}}
						<th> {{$subject['name']}} <br/>{{$subject['is_countable']==0?'(Uncountable)':''}}</th>
					@endforeach
				@endif
				{{--total marks with merit position--}}
				@if($instituteInfo->id==13)
					<th width="3%" rowspan="2">Total</th>
					<th width="3%" rowspan="2">Merit</th>
				@endif
			</tr>
			<tr>
				{{--subject list checking--}}
				@if(count($classSubjects)>0)
					{{--subject list looping--}}
					@foreach($classSubjects as $index=>$subject)
						@if(!$requestCategory) @continue @endif
						<th>
							<table width="100%" class="text-center second-table">
								<tbody>
								<tr>
									{{--assessment looping--}}
									@php
										$assessmentsCount = $gradeScale->assessmentsCount();
										// subject id
										$subId = $subject['id'];
										// single subject assessment array list
										$subCatAssArrayList = (array)array_key_exists($subId, $subjectAssArrayList)?$subjectAssArrayList[$subId]['cat_list']:[];
									@endphp
									{{--checking $assessmentsCount--}}
									@if($assessmentsCount>0)
										{{--assessment category list--}}
										@php $allCategoryList = $gradeScale->assessmentCategory() @endphp
										{{--Category list empty checking--}}
										@if(!empty($allCategoryList) AND $allCategoryList->count()>0)
											{{--Category list loopint--}}
											@foreach($allCategoryList as $category)

												{{--checking category type is_sba on not--}}
												@if($category->is_sba==1 || ($category->id!=$requestCategory))
													@continue
												@endif
												{{--category all assessment list--}}
												@php
													$allAssessmentList = $category->allAssessment($gradeScale->id);
												    // find category assessment mark list
				                                    $singleCatMarksList = (array) (array_key_exists($category->id, $subCatAssArrayList)?$subCatAssArrayList[$category->id]:[]);
				                                    // subject assessment mark list
				                                    $subjectAssMarkList = (array) (array_key_exists('ass_list', $singleCatMarksList)?$singleCatMarksList['ass_list']:[]);
				                                    // checking category id
				                                    if($singleCatMarksList){
				                                        $catExamMark = floatval($singleCatMarksList['exam_mark']);
				                                        $catPassMark = floatval($singleCatMarksList['pass_mark']);
				                                    }else{
				                                        $catExamMark = 0; $catPassMark = 0;
				                                    }
												@endphp
												{{--assessment list empty checking--}}
												@if(!empty($allAssessmentList) AND $allAssessmentList->count()>0 AND $catExamMark>0)
													{{--assessment list looping--}}
													@foreach($allAssessmentList as $index=>$assessment)
														{{--assessment marks--}}
														@php
															// assessment exam and passing marks
															if(array_key_exists($assessment->id, $subjectAssMarkList)==true){
																$assExamMark = floatval($subjectAssMarkList[$assessment->id]['exam_mark']);
																$assPassMark = floatval($subjectAssMarkList[$assessment->id]['pass_mark']);
															}else{
																$assExamMark = 0; $assPassMark = 0;
															}
														@endphp
														{{--checking assessment marks--}}
														@if($assExamMark>0)
															{{--assessment loop counter--}}
															@php $assessmentArrayList[$subId][$assessment->id]=$assessment->name; @endphp
														@endif
													@endforeach

													{{--assessment loop counter--}}
													@php $assLoopCounter = 1; @endphp
													{{--assessemnt list--}}
													@php $myAssessmentList = (array) (array_key_exists($subId, $assessmentArrayList)?$assessmentArrayList[$subId]:[]); @endphp
													{{--assessemnt looping--}}
													@foreach($myAssessmentList as $assId=>$assessmentName)
														{{--checking--}}
														@if(count($myAssessmentList)>1 AND $assLoopCounter<count($myAssessmentList))
															<td width="20%" style="border-right: 1px solid black;">{{$assessmentName}}</td>
														@else
															<td width="20%">{{$assessmentName}}</td>
														@endif
														{{--assessment loop counter--}}
														@php $assLoopCounter += 1; @endphp
													@endforeach
												@endif
											@endforeach
										@endif
									@endif
								</tr>
								</tbody>
							</table>
						</th>
					@endforeach
				@endif
			</tr>
			</thead>
			<tbody>
			{{--student list checking--}}
			@if(count($studentList)>0)
				{{--student list checking--}}
				@foreach($studentList as $student)
					{{--student arry to object conversion --}}
					@php
						$student = (object)$student;
						// semester subject highest marks sheet
						$meritList = $subjectHighestMarksList['merit_list'];
						// student additional subject list
						$stdAddSubList = array_key_exists($student->std_id, $additionalSubjectList)? $additionalSubjectList[$student->std_id]:[];
					@endphp
					<tr>
						{{--student details--}}
						<th>{{$student->gr_no}}</th>
						<th style="text-align: left; padding-left: 5px;">{{$student->first_name.' '.$student->middle_name.' '.$student->last_name}}</th>
						{{--subject list checking--}}
						@if(count($classSubjects)>0)
							{{--semester result checking for the student--}}
							@if(array_key_exists($student->std_id, $semesterResultSheet))
								{{--find student result sheet--}}
								@php
									$stdResultSheet = $semesterResultSheet[$student->std_id];
									$subjectGradeSheet = $stdResultSheet['grade']?$stdResultSheet['grade']:[];
									$stdTotalResult = $stdResultSheet['result']?$stdResultSheet['result']:null;
									// fail subject counter
									$failSubjectCounter = 0;
								@endphp
								{{--subject list looping--}}
								@foreach($classSubjects as$subject)
									{{--subject arry to object conversion --}}
									@php $subject = (object)$subject; @endphp
									{{--student result sheet checking for subject--}}
									@if(array_key_exists($subject->cs_id, $subjectGradeSheet) AND $requestCategory)
										<th>
											<table width="100%" class="text-center second-table">
												<tbody>
												<tr>
													{{--assessment looping--}}
													@php
														$assessmentsCount = $gradeScale->assessmentsCount();
														// subject id
														$subId = $subject->id;
														// subject id
														$subStatus = true;
														// single subject assessment array list
														$subCatAssArrayList = (array)array_key_exists($subId, $subjectAssArrayList)?$subjectAssArrayList[$subId]['cat_list']:[];
														// subject grade sheet
														$singleSubGradeSheet = $subjectGradeSheet[$subject->cs_id];
														$singleSubMarkSheet =  (array) (array_key_exists('mark', $singleSubGradeSheet)?$singleSubGradeSheet['mark']:[]);
														// checking pass or fail
														if(($singleSubGradeSheet['letterGrade']=="F") && ($singleSubGradeSheet['is_countable']==1) ){
															// fail count
															$failSubjectCounter +=1;
															// sub status
															$subStatus = false;
														}
													@endphp

													{{--checking $assessmentsCount--}}
													@if($assessmentsCount>0)
														{{--assessment category list--}}
														@php $allCategoryList = $gradeScale->assessmentCategory() @endphp
														{{--Category list empty checking--}}
														@if(!empty($allCategoryList) AND $allCategoryList->count()>0)
															{{--Category list loopint--}}
															@foreach($allCategoryList as $category)

																{{--checking category type is_sba on not--}}
																@if($category->is_sba==1 || ($category->id!=$requestCategory))
																	@continue
																@endif
																{{--category all assessment list--}}
																@php
																	// my category id
																	$myCatId = 'cat_'.$category->id;
																	$allAssessmentList = $category->allAssessment($gradeScale->id);
																	// find category assessment mark list
																	$singleCatMarksList = (array) (array_key_exists($category->id, $subCatAssArrayList)?$subCatAssArrayList[$category->id]:[]);
																	// subject assessment mark list
																	$subjectAssMarkList = (array) (array_key_exists('ass_list', $singleCatMarksList)?$singleCatMarksList['ass_list']:[]);
																	// checking category id
																	if($singleCatMarksList){
																		$catExamMark = floatval($singleCatMarksList['exam_mark']);
																		$catPassMark = floatval($singleCatMarksList['pass_mark']);
																	}else{
																		$catExamMark = 0; $catPassMark = 0;
																	}

																	// category mark sheet
																	$categoryMarkSheet = (array) (array_key_exists($myCatId, $singleSubMarkSheet)?$singleSubMarkSheet[$myCatId]:[]);
																@endphp

																{{--assessment list empty checking--}}
																@if(!empty($allAssessmentList) AND $allAssessmentList->count()>0 AND $catExamMark>0)
																	{{--assessment list loopint--}}
																	@php $assLoopCounter = 1; @endphp
																	{{--assessemnt list--}}
																	@php $myAssessmentList = (array) (array_key_exists($subId, $assessmentArrayList)?$assessmentArrayList[$subId]:[]); @endphp
																	{{--assessemnt looping--}}
																	@foreach($myAssessmentList as $assId=>$assessmentName)
																		{{--assessment marks--}}
																		@php
																			// my assessment id
																			$myAssId = 'ass_'.$assId;
																			// assessment exam and passing marks
																			if(array_key_exists($assId, $subjectAssMarkList)==true){
																				$assExamMark = floatval($subjectAssMarkList[$assId]['exam_mark']);
																				$assPassMark = floatval($subjectAssMarkList[$assId]['pass_mark']);
																			}else{
																				$assExamMark = 0; $assPassMark = 0;
																			}
																			// category mark sheet
																			$assessmentMarkSheet = (array) (array_key_exists($myAssId, $categoryMarkSheet)?$categoryMarkSheet[$myAssId]:[]);
																			// ass mark
																			$assMark = $assessmentMarkSheet?$assessmentMarkSheet['ass_mark']:null;
																		@endphp

																		{{--checking--}}
																		@if(count($myAssessmentList)>1 AND $assLoopCounter<count($myAssessmentList))
																			<td width="20%" style="border-right: 1px solid black; color:{{(($assMark!=null) AND ($assMark>=$assPassMark))?'black':'red'}};">
																				{{array_key_exists('ass_mark', $assessmentMarkSheet)?round($assessmentMarkSheet['ass_mark'], 2, PHP_ROUND_HALF_UP):'-'}}
																			</td>
																		@else
																			<td width="20%" style="color:{{(($assMark!=null) AND ($assMark>=$assPassMark))?'black':'red'}};">
																				{{array_key_exists('ass_mark', $assessmentMarkSheet)?round($assessmentMarkSheet['ass_mark'], 2, PHP_ROUND_HALF_UP):'-'}}
																			</td>
																		@endif
																		{{--assessment loop counter--}}
																		@php $assLoopCounter += 1; @endphp
																	@endforeach

																@endif
															@endforeach
														@endif
													@else
														<td>-</td>
													@endif
												</tr>
												</tbody>
											</table>
										</th>
									@else
										<th>-</th>
									@endif
								@endforeach
							@else
								<td colspan="{{count($classSubjects)}}">-</td>
							@endif
							{{--total marks with merit position--}}
							@if($instituteInfo->id==13)
								@php
									// student total
									$stdTotalMarks = ($stdTotalResult?$stdTotalResult['total_obtained']:0);
									// mark conversion (float to integer)
									$myTotalMarks = (int) round($stdTotalMarks*100);
									// checking merit list
									if(in_array($myTotalMarks, $meritList)){
										$stdMerit = (array_search($myTotalMarks, array_unique(array_values($meritList)))+1);
									}else{
										$stdMerit = '-';
									}
								@endphp
								<th><span style="color:{{$failSubjectCounter>0?'red':'black'}}">{{$stdTotalMarks}}</span></th>
								<th><span style="color:{{$failSubjectCounter>0?'red':'black'}}">{{$failSubjectCounter>0?('F-'.$failSubjectCounter):$stdMerit}}</span></th>
							@endif
						@else
							<td>Subject Not Found</td>
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

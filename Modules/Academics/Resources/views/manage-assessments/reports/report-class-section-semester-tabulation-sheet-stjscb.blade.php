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
			font-size: 9px;
		}
		.report_card_table{
			border: 1px solid #dddddd;
			border-collapse: collapse;
			line-height: 12px;
		}

		.second-table{
			/*line-height: 5px;*/
		}

		/*th,td {line-height: 20px;}*/
		html{margin:25px}

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
	<p class="label row-first text-center">{{$classInfo['batch_name']}} - {{$classInfo['section_name']}} {{$classInfo['semester_name']}} Tabulation Sheet </p>
	{{--checking semester result sheet--}}
	@if(count($semesterResultSheet)>0)

		{{--subject assessment array list--}}
		@php
			// subject assessment array list
			$subjectAssArrayList = (array)($subjectAssessmentArrayList['subject_list']);
			// subject category assessment list
			$classSubCatAssArrayList = [];
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
						@if($subject['is_countable']==0) @continue @endif
						{{--@if($subject['cs_id']==699) @continue @endif--}}
						<th> {{$subject['name']}}</th>
					@endforeach
				@endif
			</tr>
			<tr>
				{{--subject list checking--}}
				@if(count($classSubjects)>0)
					{{--subject list looping--}}
					@foreach($classSubjects as $index=>$subject)
						@if($subject['is_countable']==0) @continue @endif
						<th>
							<table width="100%" class="text-center second-table">
								<tbody>
								<tr>
									{{--assessment looping--}}
									@php
										$assessmentsCount = $gradeScale->assessmentsCount();
										// subject id
										$subId = $subject['id'];
										// subject id
										$csId = $subject['cs_id'];
										// single subject assessment array list
										$subCatAssArrayList = (array)array_key_exists($subId, $subjectAssArrayList)?$subjectAssArrayList[$subId]['cat_list']:[];
									@endphp
									{{--checking $assessmentsCount--}}
									@if($assessmentsCount>0)
										{{--assessment category list--}}
										@php $allCategoryList = $gradeScale->assessmentCategory() @endphp
										{{--Category list empty checking--}}
										@if(!empty($allCategoryList) AND $allCategoryList->count()>0)
											{{--assessment array list--}}
											@php $assessmentArrayList = []; @endphp
											{{--Category list loopint--}}
											@foreach($allCategoryList as $category)
												{{--checking category type is_sba on not--}}
												{{--@if($category->is_sba==1 || ($category->id!=$requestCategory))--}}
												{{--@continue--}}
												{{--@endif--}}
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
															@php
																$assessmentArrayList[$csId][$assessment->id]=$assessment->name;
																$classSubCatAssArrayList[$csId]['cat_list'][$category->id][$assessment->id]=$assessment->name;
															@endphp
														@endif
													@endforeach
												@endif
											@endforeach

											{{--assessment loop counter--}}
											@php $assLoopCounter = 1; @endphp
											{{--assessemnt list--}}
											@php $myAssessmentList = (array) (array_key_exists($csId, $assessmentArrayList)?$assessmentArrayList[$csId]:[]); @endphp
											{{--assessemnt looping--}}
											@foreach($myAssessmentList as $assId=>$assessmentName)
												{{--checking--}}
												@if(count($myAssessmentList)>1 AND $assLoopCounter<count($myAssessmentList))
													<td width="20%" style="border-right: 1px solid black;">{{$assessmentName}}</td>
												@else
													<td width="20%">{{$assessmentName}}</td>
													@php $classSubCatAssArrayList[$csId]['last'] = $assId; @endphp
												@endif
												{{--assessment loop counter--}}
												@php $assLoopCounter += 1; @endphp
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
									$stdTotalResult = (object)$stdResultSheet['result']?$stdResultSheet['result']:[];
								@endphp
								{{--{{dd($subject)}}--}}
								{{--subject list looping--}}
								@foreach($classSubjects as$subject)
									@if($subject['is_countable']==0) @continue @endif
									{{--subject arry to object conversion --}}
									@php
										$subject = (object)$subject;
										// subject id
										$csId = $subject->cs_id;
									@endphp
									{{--student result sheet checking for subject--}}
									@if(array_key_exists($csId, $classSubCatAssArrayList) AND $classSubCatAssArrayList)
										{{-- find subject catergory list --}}
										@php
											$mySubCategoryList = array_key_exists($csId, $classSubCatAssArrayList)?$classSubCatAssArrayList[$csId]['cat_list']:[];
											$mySubLastAssId = array_key_exists($csId, $classSubCatAssArrayList)?$classSubCatAssArrayList[$csId]['last']:0;
											// subject grade sheet
											$singleSubGradeSheet = array_key_exists($csId, $subjectGradeSheet)?$subjectGradeSheet[$csId]:[];
											$singleSubMarkSheet =  (array) (array_key_exists('mark', $singleSubGradeSheet)?$singleSubGradeSheet['mark']:[]);
										@endphp
										{{--checking subject category list--}}
										@if(count($mySubCategoryList)>0)
											<th>
												<table width="100%" class="text-center second-table">
													<tbody>
													<tr>
														{{--category list looping--}}
														@foreach($mySubCategoryList as $catId=>$assessmentList)
															@php
																// my category id
																$myCatId = 'cat_'.$catId;
																// category mark sheet
																$categoryMarkSheet = (array) (array_key_exists($myCatId, $singleSubMarkSheet)?$singleSubMarkSheet[$myCatId]:[]);
															@endphp

															{{--assessment list looping--}}
															@foreach($assessmentList as $assId=>$assessmentName)
																@php
																	// my assessment id
																	$myAssId = 'ass_'.$assId;
																	// assessment mark sheet
																	$assessmentMarkSheet = (array) (array_key_exists($myAssId, $categoryMarkSheet)?$categoryMarkSheet[$myAssId]:[]);
																@endphp
																{{--checking assessment last id--}}
																@if($mySubLastAssId==$assId)
																	<td width="20%">
																		{{array_key_exists('ass_mark', $assessmentMarkSheet)?round($assessmentMarkSheet['ass_mark'], 2, PHP_ROUND_HALF_UP):'-'}}
																	</td>
																@else
																	<td width="20%" style="border-right: 1px solid black;">
																		{{array_key_exists('ass_mark', $assessmentMarkSheet)?round($assessmentMarkSheet['ass_mark'], 2, PHP_ROUND_HALF_UP):'-'}}
																	</td>
																@endif
															@endforeach
														@endforeach
													</tr>
													</tbody>
												</table>
											</th>
										@endif
									@else
										<th>-</th>
									@endif
								@endforeach
							@else
								<td colspan="{{count($classSubjects)}}">-</td>
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

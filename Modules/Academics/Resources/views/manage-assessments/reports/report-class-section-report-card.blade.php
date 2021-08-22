<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

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
			font-size: 8px;
		}
		#std-photo {
			float:left;
			width: 19%;
			margin-left: 15px;
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
			border: 1px solid #000000;
			border-collapse: collapse;
		}
		/*th,td {line-height: 20px;}*/
		/*html{margin:5px}*/
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

					<div id="inst" class="text-center clear" style="width: 100%;">
						<div id="inst-photo">
							@if($instituteInfo->logo)
								<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px; margin-bottom: 20px;">
							@endif
						</div>
						<div id="inst-info">
							<b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
						</div>
					</div>

					{{--Student Infromation--}}
					<div class="clear">
						<p class="label text-center" style="font-size: 18px;">PROGRESS REPORT</p>
						<div id="std-info">
							<table width="100%" style="font-size: 15px; text-align: left">
								<tr>
									<th width="20%">Name of Student</th>
									<th>:</th>
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
						<div id="std-photo">
							<table width="100%" style="font-size:12px; margin-left:2px" border="1 px slid"  class="text-center report_card_table" cellpadding="1">
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

					@php $stdSubjectGrades = $semesterResultSheet[$allSemester[$m]['id']][$studentInfo->id]; @endphp

					{{--{{dd($stdSubjectGrades)}}--}}

					{{--assesssement details array list--}}
					@php $assessmentInfo = array(); @endphp
					{{--subject grade list checking--}}
					@if(count($stdSubjectGrades)>0)
						<div class="clear" style="width: 100%">
							<br/>
							{{--@if($stdSubjectGrades == null) @continue @endif--}}
							<p class="label text-center">{{$allSemester[$m]['name']}} ({{date('d M, Y', strtotime($allSemester[$m]['start_date']))." - ".date('d M, Y', strtotime($allSemester[$m]['end_date']))}}) </p>
							{{--<table class="report_card_table">--}}
							<table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5">
								<thead>
								<tr class="text-center row-second">
									<th>Subject</th>
									@php
										$myAssessmentCounter = 0;
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
												{{--$categoryWAMark--}}
												@php $categoryWAMark = array_key_exists($category->id, $weightedAverageArrayList)==true?$weightedAverageArrayList[$category->id]:0; @endphp
												{{--checking $categoryWAMark--}}
												@if($categoryWAMark>0)
													{{--category all assessment list--}}
													@php $allAssessmentList = $category->allAssessment($gradeScale->id); @endphp
													{{--assessment list empty checking--}}
													@if(!empty($allAssessmentList) AND $allAssessmentList->count()>0)
														{{--assessment list loopint--}}
														@foreach($allAssessmentList as $assessment)
															{{--my assessment counter--}}
															@php $myAssessmentCounter +=1; @endphp
															<th>{{$assessment->name}}</th>
															{{--store assessment info--}}
															@php $assessmentInfo[$category->id][] = ['id'=>$assessment->id,'points'=>$assessment->points]; @endphp
														@endforeach
													@endif
												@endif
											@endforeach
										@endif
									@endif
									<th>Total</th>
									<th>%</th>
									<th>Grade</th>
									<th>Point</th>
								</tr>
								</thead>
								<tbody id="report_card_table_body" class="text-center" style="line-height: 22px;">
								@php
									$grade = $stdSubjectGrades['grade'];
                                    $gradeResult = $stdSubjectGrades['result'];
                                    // result looping
		                            foreach ($classSubjects as $singleClassSubject){
		                              // class subject id
		                               $csId = $singleClassSubject['cs_id'];
		                               // checking class subject in the grade list
		                               if(array_key_exists($csId, $grade)==false) continue;
		                                // find subject grade list
									    $std_grade = $grade[$csId];
                                        $grade_id = $std_grade['grade_id'];
                                        $sub_id = $std_grade['cs_id'];
                                        $sub_name = $std_grade['sub_name'];
                                        $mark_id = $std_grade['mark_id'];
                                        $mark = (array)$std_grade['mark'];
                                        $credit = $std_grade['credit'];
                                        $total = $std_grade['total'];
                                        $obtained = $std_grade['obtained'];
                                        $percentage = $std_grade['percentage'];
                                        $letterGrade = $std_grade['letterGrade'];
                                        $letterGradePoint = $std_grade['letterGradePoint'];
                                        $cat_count = $mark['cat_count'];
                                        $cat_list = (array)$mark['cat_list'];
                                        // print assessment subject name
                                        echo '<tr><td width="15%">'.$sub_name.'</td>';
                                        // category looping
                                        foreach ($assessmentInfo as $headCatId=>$headAssList){
                                            // custom catId
                                            $catId = 'cat_'.$headCatId;
                                            // catId checking
                                            if(array_key_exists($catId, $mark)){
                                                // find category marks list from mark
                                                $categoryMarksList = (array)$mark[$catId];
                                                // assessment list looping
                                                foreach ($headAssList as $headAssDetails){
                                                    // custom assId
                                                    $assId = 'ass_'.$headAssDetails['id'];
                                                    // assId checking
                                                    if(array_key_exists($assId, $categoryMarksList)){
                                                        // find assessment marks list from categoryMarksList
                                                        $assessment = (array)$categoryMarksList[$assId];
                                                        // assessment details
                                                        $ass_mark = $assessment['ass_mark'];
                                                        $ass_points = $assessment['ass_points'];
                                                        // checking
                                                        // print assessment subject marks and points
                                                        echo '<td>'.$ass_mark.' /  '.$ass_points.'</td>';
                                                    }else{
                                                        // print assessment subject marks and points
                                                        echo '<td>-</td>';
                                                    }
                                                }
                                            }else{
                                                // assessment list looping
                                                foreach ($headAssList as $headAssDetails){
                                                    // print assessment subject marks and points
                                                    if($headAssDetails['points']>0){
                                                    	echo '<td>0 / '.$headAssDetails['points'].'</td>';
                                                    }else{
                                                    	echo '<td>-</td>';
                                                    }

                                                }
                                            }
                                        }
                                       // subject grade point
								$subjectGradePoint = subjectGradeCalculation((int)$percentage, $gradeScaleDetails);
								//
								echo '<td width="7%">'.$obtained.' / '.$total.'</td><td  width="5%">'.(int)$percentage.' %</td><td width="7%">'.$subjectGradePoint['grade'].'</td> <td  width="7%">'.$subjectGradePoint['point'].' / '.$subjectGradePoint['max_point'].'</td> </tr>';
                                    }
								@endphp

								{{--<tr>--}}
								{{--<th colspan="{{(4+$myAssessmentCounter)}}">--}}
								{{--<i>Total: {{$gradeResult['total_marks']}}, </i>--}}
								{{--<i>Obtained: {{$gradeResult['total_obtained']}}, </i>--}}
								{{--<i>--}}
								{{--@if($gradeResult['total_points_incomplete']>0)--}}
								{{--Incomplete: {{$gradeResult['total_points_incomplete']}} Points,--}}
								{{--@else--}}
								{{--Incomplete: 0 Points,--}}
								{{--@endif--}}
								{{--</i>--}}
								{{--<i>--}}
								{{--Percent: {{(int)$gradeResult['total_percent']}}%,--}}
								{{--</i>--}}
								{{--<i>--}}
								{{--GPA: {{$gradeResult['total_gpa']}}--}}
								{{--</i>--}}
								@php $myResult[$allSemester[$m]['id']] = (object)['total_points'=>$gradeResult['total_points'],'total_gpa'=>$gradeResult['total_gpa']]; @endphp
								{{--</th>--}}
								{{--</tr>--}}

								</tbody>
							</table>
						</div>
					@else
						<div class="row">
							<p class="label text-center">Report Card </p>
							<p class="text-center">
								<b>Result not published</b><br/>
							</p>
							<div style="page-break-after:always;"></div>
						</div>
						@break
					@endif

					@if(!empty($myResult))
						<div class="clear" style="width: 100%; margin-top: 50px;">
							<div style="float: left; width: 50%; text-align:center">
								{{--...............................<br>--}}
								{{--<strong>Parent</strong>--}}
							</div>
							<div style="float: right; width: 50%; text-align:center;">
								............................................<br>
								<strong>Principal / Head Teacher</strong>
							</div>
						</div>
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

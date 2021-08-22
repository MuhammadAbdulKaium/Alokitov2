<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Student Information -->
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
		@page { margin: 10px; }
		body{
			font-size: 11px;
			background-image: url({{public_path().'/assets/users/images/'.$instituteInfo->logo}});
			{{--background-image: url({{public_path().'/assets/users/images/watermark-biam.png'}});--}}
			 margin: 10px;
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
						<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
					@endif
				</div>
				<div id="inst-info">
					<b style="font-size: 20px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
				</div>
			</div>


			{{--Student Infromation--}}
			<div class="clear" style="width: 100%;">
				<p class="label text-center" style="font-size: 12px">PROGRESS REPORT</p>
				@if(!empty($reportCardSetting) && ($reportCardSetting->is_image==1))
				<div class="image" style="width: 15%; text-align: left; float: left">
					@if($studentInfo->singelAttachment("PROFILE_PHOTO"))
					<img src="{{public_path().'/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name}}"  style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
					@else
						<img src="{{public_path().'/assets/users/images/user-default.png'}}"  style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
					@endif
				</div>
				@endif

				<div id="std-info" style="width: 85%">
					<table width="100%" cellpadding="1" style="font-size:14px">
						<tr>
							<th width="20%">Name of Student</th>
							<th width="2%">:</th>
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
							@php $enrollment = $studentInfo->enroll(); @endphp
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
				</div>

				<div class="grading-system" style="width: 20%;margin-left: 605px; margin-top: 0px">
					<table width="100%" style="font-size: 10px;" border="1px solid" class="text-center report_card_table" cellpadding="2">
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


			@php $stdSubjectGrades = $semesterResultSheet[$allSemester[$m]['id']]; @endphp

			{{--assesssement details array list--}}
			@php $assessmentInfo = array(); @endphp
			{{--subject grade list checking--}}
			@if(count($stdSubjectGrades)>0)
				<div class="clear" style="width: 100%">
					{{--@if($stdSubjectGrades == null) @continue @endif--}}
					<p class="label text-center row-second">{{$allSemester[$m]['name']}} ({{date('d M, Y', strtotime($allSemester[$m]['start_date']))." - ".date('d M, Y', strtotime($allSemester[$m]['end_date']))}}) </p>
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

													@php $myAssessmentPoints = array_key_exists($assessment->id, $subjectAssessmentArrayList)==true?$subjectAssessmentArrayList[$assessment->id]:0;  @endphp

													{{--my assessment counter--}}
													@php $myAssessmentCounter +=1; @endphp

													<th width="10%">{{$assessment->name}}</th>
													{{--store assessment info--}}
													{{--@php $assessmentInfo[$category->id][] = ['id'=>$assessment->id,'points'=>$assessment->points]; @endphp--}}
													@php $assessmentInfo[$category->id][] = ['id'=>$assessment->id,'points'=>$myAssessmentPoints]; @endphp
												@endforeach
											@endif
										@endif
									@endforeach
								@endif
							@endif
							<th width="8%">Total</th>
							<th width="6%">%</th>
							<th width="6%">Grade</th>
							<th width="7%">Point</th>
						</tr>
						</thead>
						<tbody id="report_card_table_body" class="text-center" style="line-height: 20px;">
						@php
							$grade = $stdSubjectGrades['grade'];
							$gradeResult = $stdSubjectGrades['result'];

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
												// print assessment subject marks and points
												echo '<td>'.$ass_mark.' /  '.$ass_points.'</td>';
											}else{
												// checking assessment points
												if($headAssDetails['points']>0){
													// print assessment subject marks and points
													echo '<td>  / '.$headAssDetails['points'].'</td>';
												}else{
													// print assessment subject marks and points
													echo '<td> - </td>';
												}
											}
										}
									}else{
										// assessment list looping
										foreach ($headAssList as $headAssDetails){
											// checking assessment points
											if($headAssDetails['points']>0){
												// print assessment subject marks and points
												echo '<td>No Marks / '.$headAssDetails['points'].'</td>';
											}else{
												// print assessment subject marks and points
												echo '<td> - </td>';
											}
										}
									}
								}

								// print subject marks details (total, obtained, percentage letterGrade, letterGradePoints)
								// echo '<td width="7%">'.$obtained.' / '.$total.'</td><td  width="5%">'.$percentage.'%</td><td  width="5%">'.$letterGrade.'</td> <td  width="5%">'.$letterGradePoint.'</td> </tr>';
								// subject grade point
								$subjectGradePoint = subjectGradeCalculation((int)$percentage, $gradeScaleDetails);
								//
								echo '<td width="7%">'.$obtained.' / '.$total.'</td><td  width="5%">'.(int)$percentage.' %</td><td  width="5%">'.$subjectGradePoint['grade'].'</td> <td  width="5%">'.$subjectGradePoint['point'].' / '.$subjectGradePoint['max_point'].'</td> </tr>';

							}
						@endphp

						{{--<tr>--}}
						{{--<th colspan="{{(3+$myAssessmentCounter)}}">--}}
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
				<div class="row clear">
					<p class="label text-center">Report Card </p>
					<p class="text-center">
						<b>Result not published</b><br/>
					</p>
				</div>
				@break
			@endif
			<br/>
			@if(!empty($myResult))
				<div class="clear" style="width: 100%; margin-top: 50px;">

					{{--<div style="float: left; width: 50%">--}}
						{{--<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate('My First QR code'))!!} ">--}}
					{{--</div>--}}
					<div style="float: left; width: 50%; text-align:center; margin-top: 30px">
						..................................................<br>
						<strong>Principal / Head Teacher</strong>
					</div>
				</div>
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

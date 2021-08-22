<!DOCTYPE html>
<html>
<head>
	<title>Report Card Design</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<style type="text/css">

	.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
		padding: 0px !important;
	}

	@media screen {
		body {
			font-family: arial;
			font-size: 12px
		}
		.fix {
			overflow: hidden
		}
		.page-border {
			border: 5px solid #812F00;
			margin: 0 auto;
			width: 80%;
		}
		.page-border-2 {
			border: 5px solid #FFCE44;
		}
		.page-border-3 {
			border: 5px solid #000;
			padding: 5px 10px;
			border-style: double;
		}
		.head_column {
			width: 33%;
			float: left;
			text-align: center
		}
		.head_column img {
			height: 50px;
			margin: 10px 0px
		}
		.marksheets h2 {
			text-align: center;
			font-size: 22px;
			margin: 0px;
			margin-top: 10px;
			font-family: arial;
			padding-bottom: 10px
		}
		.marksheets h3 {
			text-align: center;
			font-size: 16px;
			margin: 0px;
			border-bottom: 1px solid #0D9E3D;
			font-family: arial;
		}
		.head_column table tr th,
		td {
			border: 1px solid;
			text-align: center;
			font-size: 10px
		}
		.head_column table th {
			background: #FFF7E6;
			font-family: arial;
		}
		.grade_table table tr td {
			font-family: arial;
			font-size: 8px
		}
		.head_column table {
			width: 60%;
			float: right
		}
		.clear {
			clear: both;
		}
		ul{
			list-style: outside none none;
			padding: 0px;
			margin: 0px;
		}
		.name_address_left{
			padding: 0px;
		}
		.name_address_left ul li span.a {
			width: 120px;
			display: inline-block;
			font-family: arial;
			padding: 0px;
		}
		.name_address_left ul li span.b {
			width: 20px;
			display: inline-block;
			font-family: arial;
		}
		.name_address_right ul li span.a {
			width: 70px;
			display: inline-block;
			font-family: arial;
		}
		.name_address_right ul li span.b {
			width: 20px;
			display: inline-block;
			font-family: arial;
		}
		.name_section {
			margin-top: 10px
		}
		.main_body {
			margin-top: 10px;
		}
		.main_body table {
			width: 100%
		}
		.main_body th {
			border: 1px solid;
			font-size: 12px;
			text-align: center
		}
		.total td {
			font-weight: bold;
			color: green;
			background: #F5F5DC;
			font-size: 14px;
		}
		.head_column table tr th,
		td {
			font-size: 12px
		}
		.body_second {
			margin-top: 0px
		}
		.left_side {
			width: 80%;
			float: left
		}
		.right_side {
			width: 20%;
			float: left
		}
		.left_table {
			width: 93%;
			float: left;
			margin-right: 10px;
			margin-top: 5px;
		}
		.left_table th {
			font-weight: bold;
			background: #EDFAFA;
			border: 1px solid;
			font-size: 14px;
			text-align: center;
		}
		.right_side img {
			height: 60px;
			margin-top: 3px
		}
		.comment {
			text-align: left;
			font-weight: bold;
			font-size: 12px;
			border: 1px solid;
			margin-top: 5px;
			width: 93%;
			padding: 5px 5px;
		}
		.signature {
			width: 100%;
			/*margin-top: 240px*/
		}
		.sign {
			width: 15%;
			float: left;
			margin-right: 22%;
			text-align: center;
			display: block
		}
		.signs {
			width: 15%;
			float: left;
			text-align: center;
			display: block
		}
		.sig {
			border-top: 1px dashed;
		}
		.sg {
			height: 30px;
			width: 100%
		}
		.sg img {
			width: 100px;
		}
	}

	@media only screen and (max-width: 767px) {
		.left_side {
			width: 100%;
		}
		.right_side {
			width: 100%;
		}
		.chart {
			display: none
		}
		.left_table {
			width: 100%;
		}
		.left_table tbody {
			display: block;
			overflow: auto;
		}
		.signature {
			display: none;
		}
	}

	@media only screen and (min-width: 480px) and (max-width: 767px) {
		.left_side {
			width: 100%;
		}
		.right_side {
			width: 100%;
		}
		.chart {
			display: none
		}
		.left_table {
			width: 100%;
		}
		.left_table tbody {
			display: block;
			overflow: auto;
		}
		.signature {
			display: none;
		}
	}

	@media print {
		body {
			font-family: arial;
			font-size: 12px;
			margin: 5px;
		}
		.fix {
			overflow: hidden
		}
		.page-border {
			border: 5px solid #812F00;
			margin-top: 20px;
		}
		.page-border-2 {
			border: 5px solid #FFCE44;
		}
		.page-border-3 {
			border: 5px solid #000;
			padding: 10px 20px;
			border-style: double;
			height: 100%
		}
		ul{
			list-style: outside none none;
			padding: 0px;
			margin: 0px;
		}
		.head_column {
			width: 33%;
			float: left;
			text-align: center
		}
		.head_column img {
			height: 50px;
			margin: 2px 0px
		}
		.marksheets h2 {
			text-align: center;
			font-size: 22px;
			margin: 0px;
			font-family: arial;
			padding-bottom: 10px
		}
		.marksheets h3 {
			text-align: center;
			font-size: 16px;
			margin: 0px;
			border-bottom: 1px solid #0D9E3D;
			font-family: arial;
		}
		.head_column table tr th,
		td {
			border: 1px solid;
			text-align: center;
			font-size: 10px
		}
		.head_column table th {
			background: #FFF7E6;
			font-family: arial;
		}
		.grade_table table tr td {
			font-family: arial;
			font-size: 8px
		}
		.head_column table {
			width: 60%;
			float: right
		}
		.clear {
			clear: both;
		}
		.name_address_left {
			width: 60%;
			float: left;
			padding: 0px !important;
		}
		.name_address_left ul li span.a {
			width: 120px;
			display: inline-block;
			font-family: arial;
		}
		.name_address_left ul li span.b {
			width: 20px;
			display: inline-block;
			font-family: arial;
		}
		.name_address_right ul li span.a {
			width: 70px;
			display: inline-block;
			font-family: arial;
		}
		.name_address_right ul li span.b {
			width: 20px;
			display: inline-block;
			font-family: arial;
		}
		.name_address_right {
			width: 40%;
			float: right
		}
		.name_section {
			margin-top: 10px
		}
		.main_body {
			margin-top: 10px;
		}
		.main_body table {
			width: 100%
		}
		.main_body th {
			border: 1px solid;
			font-size: 12px;
			text-align: center
		}
		.total td {
			font-weight: bold;
			color: green;
			background: #F5F5DC;
			font-size: 14px;
		}
		.head_column table tr th,
		td {
			font-size: 12px
		}
		.body_second {
			margin-top: 0px
		}
		.left_side {
			width: 80%;
			float: left
		}
		.right_side {
			width: 20%;
			float: left
		}
		.left_table {
			width: 100%;
			float: left;
			margin-right: 10px;
			margin-top: 5px;
		}
		.right_side img {
			height: 60px;
		}
		.comment {
			text-align: left;
			font-size: 12px;
			border: 1px solid;
			width: 93%;
			padding: 5px 0px;
		}
		.signature {
			width: 100%;
			margin-top: 10px
		}
		.sign {
			width: 15%;
			float: left;
			margin-right: 22%;
			text-align: center;
			display: block
		}
		.signs {
			width: 15%;
			float: left;
			text-align: center;
			display: block
		}
		.sig {
			border-top: 1px dashed;
		}
		.sg {
			height:50px;
			width: 100%
		}
		.sg img {
			width: 100px;
		}

		.breakNow {
			page-break-inside: avoid;
			page-break-after: always;
			margin-top: 10px;
		}
	}
</style>
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
		<div class="marksheets page-border">
			<div class="page-border-2">
				<div class="page-border-3 fix">
					<div class="head_section">
						<div class="head_left_content"></div>
						<div class="head_right_content">
							<h2>{{$instituteInfo->institute_name}}</h2>
							<div style=" text-align:left" class="head_column">
								{{--                                                <img style="border:1px solid #ccc; padding:1px;" src="https://plhsc.edu.bd/sa/uploads/20190106/154675403105.jpg" alt="">--}}

								@if($studentInfo->singelAttachment("PROFILE_PHOTO"))
									<img src="{{URL::to('assets/users/images',$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"  style="height:80px;">
								@else
									<img src="{{URL::to('assets/users/images/user-default.png')}}"  style="height:80px;">
								@endif
							</div>
							<div class="head_column">
								<p>{{$instituteInfo->address1}}</p>
								<img src="{{URL::asset('assets/users/images/'.$instituteInfo->logo)}}"  height="60px" alt="dd">
								<h3>Aggregated Report Card</h3>
							</div>
							<div class="head_column grade_table">
								<table>
									<tbody>
									<tr>
										<th>Range</th>
										<th>Grade</th>
										<th>GPA</th>
									</tr>
									<tr>
										<td>80-100</td>
										<td>A+</td>
										<td>5</td>
									</tr>
									<tr>
										<td>70-79</td>
										<td>A</td>
										<td>4</td>
									</tr>
									<tr>
										<td>60-69</td>
										<td>A-</td>
										<td>3.5</td>
									</tr>
									<tr>
										<td>50-59</td>
										<td>B</td>
										<td>3</td>
									</tr>
									<tr>
										<td>40-49</td>
										<td>C</td>
										<td>2</td>
									</tr>
									<tr>
										<td>33-39</td>
										<td>D</td>
										<td>1</td>
									</tr>
									<tr>
										<td>0-32</td>
										<td>F</td>
										<td>0</td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="clear"></div>
					<div class="name_section fix">
						<div class="name_address_left col-sm-1 col-md-8">
							<ul>
								@php $stdFullName=$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name @endphp

								<li><span class="a">Student's Name</span><span class="b">:</span><b>{{$stdFullName}}</b></li>

								@php $parents = $studentInfo->myGuardians(); @endphp
								{{--checking--}}
								@if($parents->count()>0)
									@foreach($parents as $index=>$parent)
										@php $guardian = $parent->guardian(); @endphp
										<li>
                                                            <span class="a">
                                                                {{--{{$guardian->type==1?"Father's Name":"Mother's Name"}}--}}
																{{--checking guardin type--}}
																@if($guardian->type ==0)
																	Mother's Name
																@elseif($guardian->type ==1)
																	Father's Name
																@else
																	{{$index%2==0?"Father's Name":"Mother's Name"}}
																@endif
                                                            </span><span class="b">:</span><b>{{$guardian->first_name." ".$guardian->last_name}}</b></li>
									@endforeach
								@endif
								<li><span class="a">Student's ID</span><span class="b">:</span><b>ST0001179</b></li>
								{{--											<li><span class="a">Examination</span><span class="b">:</span><b>{{$allSemester[$m]['name']}}</b></li>--}}

							</ul>
						</div>
						<div class="name_address_right col-sm-1 col-md-4">
							<ul>

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
								<li><span class="a">Class</span><span class="b">:</span><b>{{$batch->batch_name.$division}} </b></li>
								{{--                                                <li><span class="a">Group</span><span class="b">:</span><b>SCIENCE</b></li>--}}
								<li><span class="a">Section</span><span class="b">:</span><b>{{$section->section_name}}</b></li>
								{{--                                                <li><span class="a">Shift</span><span class="b">:</span><b>Morning</b></li>--}}
								<li><span class="a">Roll</span><span class="b">:</span><b>{{$enrollment->gr_no}}</b></li>
								<li><span class="a">Year</span><span class="b">:</span><b>2019</b></li>
							</ul>
						</div>
					</div>
					<div class="clear"></div>
					<div class="main_body table-responsive">
						{{--final report card section--}}
						{{--									<div class="row" style="margin-top: 140px;">--}}
						{{--checking exam result sheet--}}
						@if($examResultSheet['status']=='success')
							{{--find semester count--}}
							@php $totalSemester = count($allSemester); @endphp
							{{--all student result sheet--}}
							@php
								$allStdResultList = $examResultSheet['std_list'];
                                $finalMeritList = $examResultSheet['section_final_merit_list']['pass_list'];
                                $classFinalMeritList = $examResultSheet['class_final_merit_list']['pass_list'];
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
								<table id="report_card"  class="text-center report_card_table">

									<tr class="bg-gray">
										<th width="2%" rowspan="2">#</th>
										<th width="15%" rowspan="2">Subject Name</th>
										<th width="3%" rowspan="2">Full Marks</th>
										{{--checking semester list--}}
										@if($totalSemester>0)
											{{--semester list looping--}}
											@foreach($allSemester as $semester)
												<th colspan="3">
												{{$semester['name']}}
												{{--find semester marks summary list--}}
												@php
													$semesterId = $semester['id'];
                                                    $semSummary = (object)(array_key_exists($semesterId, $semResultSummaryList)? $semResultSummaryList[$semesterId]:null);
                                                    // checking semester summary
                                                    $allSemExamMarks += ($semSummary?($semSummary->total_exam_marks):0);
                                                    $allSemObtainedMarks += ($semSummary?($semSummary->total_obtained):0);
                                                $catCount = count($cateAssessmentArrayList);
                                                $catLoop = 1;
												@endphp

												{{--checking category list--}}
												@if($catCount>0)
													{{--category list looping--}}
													@foreach($cateAssessmentArrayList as $catId=>$catDetails)
														{{--find assessment list--}}
														@php
															$assList = $catDetails['ass_list'];
                                                            $arrkey = array_keys($assList);
                                                            $lastAssId = array_pop($arrkey);
														@endphp
														{{--checking--}}
														@if(count($assList)>0)
															{{--assessment list looping--}}
															{{--{{(($catCount==$catLoop)AND($lastAssId==$assId))?'':'1px solid black'}}--}}
															@foreach($assList as $assId=>$assName)
																{{--																<td width="25%" style="border-right:1px solid black">{{$assName}}</td>--}}
																{{--																					<th width="15%" colspan="4">Half Yearly Exam</th>--}}
															@endforeach
														@endif
													@endforeach
												@endif


											@endforeach
										@endif
										<th rowspan="2">Total Obtained</th>
										<th rowspan="2">Aggregated Marks</th>
										<th rowspan="2">Grand Total </th>
										<th rowspan="2">Percent (%)</th>
										<th rowspan="2">Letter Grade</th>
										<th rowspan="2">GP</th>
									</tr>


									@if($totalSemester>0)
										<tr>
											{{--semester list looping--}}
											@foreach($allSemester as $semester)

												{{--													<th colspan="3">--}}
												{{--														{{$semester['name']}}--}}
												{{--find semester marks summary list--}}
												@php
													$semesterId = $semester['id'];
                                                    $semSummary = (object)(array_key_exists($semesterId, $semResultSummaryList)? $semResultSummaryList[$semesterId]:null);
                                                    // checking semester summary
                                                    $allSemExamMarks += ($semSummary?($semSummary->total_exam_marks):0);
                                                    $allSemObtainedMarks += ($semSummary?($semSummary->total_obtained):0);
                                                $catCount = count($cateAssessmentArrayList);
                                                $catLoop = 1;
												@endphp

												{{--checking category list--}}
												@if($catCount>0)
													{{--category list looping--}}
													@foreach($cateAssessmentArrayList as $catId=>$catDetails)
														{{--find assessment list--}}
														@php
															$assList = $catDetails['ass_list'];
                                                            $arrkey = array_keys($assList);
                                                            $lastAssId = array_pop($arrkey);
														@endphp
														{{--checking--}}
														@if(count($assList)>0)
															{{--assessment list looping--}}
															{{--{{(($catCount==$catLoop)AND($lastAssId==$assId))?'':'1px solid black'}}--}}
															@foreach($assList as $assId=>$assName)
																<td>{{$assName}}</td>
																{{--																					<th width="15%" colspan="4">Half Yearly Exam</th>--}}
															@endforeach
														@endif
													@endforeach
												@endif


											@endforeach
										</tr>
									@endif

									{{--											<tr>--}}
									{{--												<td>CQ</td>--}}
									{{--												<td>MCQ</td>--}}
									{{--												<td>PR</td>--}}

									{{--												<td>CQ</td>--}}
									{{--												<td>MCQ</td>--}}
									{{--												<td>PR</td>--}}

									{{--											</tr>--}}

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

                                                $grandBn = 0;
                                                $grandEn = 0;
											@endphp



											{{--subject list looping--}}
											@foreach($subList as $subId=>$subDetails)
												{{--subject semester result sheet--}}
												@php
													$subSemResultSheet =  $subDetails['sub_sem_result'];
                                                    $isCountable = $subDetails['is_countable'];
												@endphp

												{{--calculate semester total result--}}
												@php
													$subTotalObtainedMarks = 0;
												@endphp
												{{--checking semester list--}}
												@if($totalSemester>0)
													{{--semester list looping--}}
													@foreach($allSemester as $semester)
														{{--chekcing subject semester result sheet--}}
														@if(array_key_exists($semester['id'], $subSemResultSheet))
															{{--find subject details--}}
															@php
																$subjectResult =  (object)$subSemResultSheet[$semester['id']];
                                                                $subjectMarksList =  (array)$subjectResult->mark;
															@endphp
															{{--checking subject result--}}
															@if($subjectResult AND $subjectMarksList)
																{{--calculate semester total result--}}
																@php
																	// checking subject count type
                                                                    if($isCountable>0){
                                                                        $subTotalObtainedMarks += $subjectResult->obtained;
                                                                    }
																@endphp
															@else
															@endif
														@else
														@endif
													@endforeach
												@endif
												{{--single subject all semester total marks--}}
												{{--single subject all semester average marks--}}
												@php $subAvgObtainedMarks = round(($subTotalObtainedMarks/$totalSemester), 2); @endphp
												{{--total marks counter--}}
												@if($isCountable>0)
													@if($subCounter==1 || $subCounter==2)
														@php $grandBn += $subAvgObtainedMarks; @endphp
													@elseif($subCounter==3 || $subCounter==4)
														@php $grandEn += $subAvgObtainedMarks; @endphp
													@endif
												@endif
												@php $subCounter+=1; @endphp
											@endforeach

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
												<th style="text-align: left; padding-left: 5px" class="subject">{{$subDetails['sub_name'].($isCountable>0?'':' (UC)')}}</th>
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
															@php
																$subjectResult =  (object)$subSemResultSheet[$semester['id']];
                                                                $subjectMarksList =  (array)$subjectResult->mark;
															@endphp
															{{--checking subject result--}}
															@if($subjectResult AND $subjectMarksList)

																{{--																			<table class="report-second-table table">--}}
																{{--																				<tbody>--}}
																{{--																				<tr>--}}
																{{--checking category list--}}
																@if(count($cateAssessmentArrayList)>0)
																	{{--category list looping--}}
																	@foreach($cateAssessmentArrayList as $catId=>$catDetails)
																		{{--find assessment list--}}
																		@php
																			$myCatId = 'cat_'.$catId;
                                                                            $catMarksList = array_key_exists($myCatId, $subjectMarksList)?$subjectMarksList[$myCatId]:[];
                                                                            $assList = $catDetails['ass_list'];
                                                                            $arrkey = array_keys($assList);
                                                                            $lastAssId = array_pop($arrkey);
																		@endphp
																		{{--checking--}}
																		@if(count($assList)>0)
																			{{--assessment list looping--}}
																			@foreach($assList as $assId=>$assName)
																				{{--find assessment list--}}
																				@php
																					$myAssId = 'ass_'.$assId;
                                                                                    $assMarksDetails = array_key_exists($myAssId, $catMarksList)?$catMarksList->$myAssId:[];
																				@endphp

{{--																					<td>0</td>--}}
																				<td>{{count($assMarksDetails)>0?($assMarksDetails->ass_mark):'-'}}</td>
																				{{--																				<td width="25%" style="border-right:1px solid black">{{count($assMarksDetails)>0?($assMarksDetails->ass_mark):'-'}}</td>--}}
																			@endforeach
																		@endif
																	@endforeach
																@endif
																{{--<th>{{$subjectResult->obtained}}</th>--}}
																{{--<td width="25%" style="border-right: 1px solid black">{{$subjectResult->obtained}}</td>--}}
																{{--<td width="25%" style="border-right: 1px solid black">{{$subjectResult->letterGrade}}</td>--}}
																{{--<td width="25%">{{$subjectResult->letterGradePoint}}</td>--}}
																{{--																				</tr>--}}
																{{--																				</tbody>--}}
																{{--																			</table>--}}

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
													@if($subCounter==1)
														@php
															$grandBn = $grandBn/count($allSemester);
                                                            $grandBnPer = round((($grandBn/100)*100), 2);
                                                            $bnGradePoint = subjectGradeCalculation((int)$grandBnPer, $gradeScaleDetails);
														@endphp
														{{--{{ str_replace("world","Peter","Hello world!")}}--}}
														<th rowspan="2">{{$grandBn}}</th>
														<th rowspan="2">{{$grandBnPer}}</th>
														<th rowspan="2">{{$bnGradePoint?$bnGradePoint['grade']:'N/A'}} </th>
														<th rowspan="2">{{$bnGradePoint?$bnGradePoint['point']:'N/A'}} </th>
														{{--failed counter--}}
														@php
															$totalFailedCounter += ($bnGradePoint['grade']=='F'?1:0);
                                                            $allSubCountable +=1;
                                                            $allSubObtainedPoint += ($bnGradePoint?$bnGradePoint['point']:0);
														@endphp


													@elseif($subCounter==3)
														@php
															$grandEn = $grandEn/count($allSemester);
                                                            $grandEnPer = round((($grandEn/100)*100), 2);
                                                            $enGradePoint = subjectGradeCalculation((int)$grandEnPer, $gradeScaleDetails);
														@endphp
														<th rowspan="2">{{$grandEn}}</th>
														<th rowspan="2">{{$grandEnPer}}</th>
														<th rowspan="2">{{$bnGradePoint?$enGradePoint['grade']:'N/A'}} </th>
														<th rowspan="2">{{$enGradePoint?$enGradePoint['point']:'N/A'}} </th>
														{{--failed counter--}}
														@php
															$totalFailedCounter += ($enGradePoint['grade']=='F'?1:0);
                                                            $allSubCountable +=1;
                                                            $allSubObtainedPoint += ($enGradePoint?$enGradePoint['point']:0);
														@endphp

													@elseif($subCounter==2)

													@elseif($subCounter==4)

													@else

														@php $myAggregatedGrade = $subGradeDetails?$subGradeDetails['grade']:'N/A'; @endphp
														<th>{{$subAvgObtainedMarks}}</th>
														<th>{{$subPercentage}}</th>
														<th>{{$myAggregatedGrade}} </th>
														<th>{{$subGradeDetails?$subGradeDetails['point']:'N/A'}} </th>

														{{--failed counter--}}
														@php
															$totalFailedCounter += ($myAggregatedGrade=='F'?1:0);
                                                            $allSubCountable +=1;
                                                            $allSubObtainedPoint += ($subGradeDetails?$subGradeDetails['point']:0);
														@endphp

													@endif
												@else
													<th>-</th>
													<th>-</th>
													<th>-</th>
													<th>-</th>
												@endif
											</tr>
											@php $subCounter+=1; @endphp
										@endforeach
										{{--<tr>--}}
										{{--<th colspan="2">Total:</th>--}}
										{{--<th>{{$allSubExamMarks}}</th>--}}
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
												{{--										<th> {{$semResultSummary?$semResultSummary->total_obtained:'-'}} </th>--}}
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

										{{--								<th>{{$allSubObtainedTotalMarks}}</th>--}}
										{{--<th>{{$allSubObtainedAvgMarks}}</th>--}}
										@php $allSubObtainedAvgMarks = round(($allSubObtainedTotalMarks/$totalSemester), 2); @endphp
										{{--								<th>{{$allSubObtainedAvgMarks}}</th>--}}
										{{--<th>-</th>--}}
										{{--<th>-</th>--}}
										{{--								<th>{{$allSubObtainedPoint}}</th>--}}
										{{--<th>-</th>--}}
										{{--</tr>--}}


									@else
										<tr>
											<td>No Subject Found</td>
										</tr>
									@endif
								</table>
							@endif
					</div>


					<div class="body_second fix" style="margin-top: 10px;">
						<div class="left_side fix">
							<table class="left_table table">
								<tbody>
								<tr>

									<td>Total</td>
									<td>Obtained</td>
{{--									<td>Highest</td>--}}
									<td>Percent</td>
									<td>GPA</td>
									<td>Merit Position (Section):</td>
									<td>Merit Position (Class)</td>
									<td>Status</td>
								</tr>
								@php
									$finalMeritList = array_unique(array_values($finalMeritList));
                                    $classFinalMeritList = array_unique(array_values($classFinalMeritList));
								@endphp
								<tr>
									<td> {{$allSubTotalExamMarks}} </td>
									<td>  {{$allSubObtainedTotalMarks}} </td>
{{--									<td> {{count($finalMeritList)>0?($finalMeritList[0]/100):'N/A'}} </td>--}}
									{{--<td> Aggregated: {{$allSubObtainedAvgMarks}} </td>--}}
									<td>{{$percentage}} % </td>
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
										<td> {{round(($allSubObtainedPoint/$allSubCountable), 2)}}</td>
										{{--<td> GPA: {{$gradeDetails?$gradeDetails['point']:'N/A'}}, </td>--}}
										<td>{{$aggregatedSectionMeritPosition}}</td>
										<td>{{$aggregatedClassMeritPosition}}</td>
										<td>Passed</td>
									@else
										<td>-</td>
										<td>-</td>
										<td>-</td>
										<td> Failed in {{$totalFailedCounter}} Subject </td>
									@endif
								</tr>
								</tbody>
							</table>

							<div class="clear"></div>
							{{--										<div class="comment">--}}
							{{--											<p>Comments</p>--}}
							{{--										</div>--}}
						</div>
						<div class="right_side col-sm-1 chart">
							@if($totalFailedCounter==0)
								<img src="https://chart.googleapis.com/chart?cht=qr&amp;chs=300x300&amp;choe=UTF-8&amp;chl=Name: {{$stdFullName}}, GPA:{{round(($allSubObtainedPoint/$allSubCountable), 2)}}, Mark:{{$allSubObtainedTotalMarks}}, {{$instituteInfo->institute_name}}" alt="">
							@else
								<img src="https://chart.googleapis.com/chart?cht=qr&amp;chs=300x300&amp;choe=UTF-8&amp;chl=Name: {{$stdFullName}},  Failed {{$totalFailedCounter}} Subject {{$instituteInfo->institute_name}}" alt="">
							@endif
						</div>
					</div>
					<div class="clear"></div>
					<div class="signature">
						<div class="sign">
							<div class="sg"></div>
							<div class="sig">Guardian</div>
						</div>
						<div class="sign">
							<div class="sg"></div>
							<div class="sig">Class Teacher</div>
						</div>
						<div class="signs">

							@if($reportCardSetting AND $reportCardSetting->auth_sign!=null AND !empty($reportCardSetting->auth_sign))
								<div class="sg"><img src="{{URL::to('assets/users/images/',$reportCardSetting->auth_sign)}}" height="50px;" alt=""></div>
							@endif
							<div class="sig">Head Master</div>
						</div>
					</div>
				</div>
			</div>
		</div>

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
		{{--            @break--}}
		<div class="breakNow"></div>
	@endforeach
@endif
</body>
</html>
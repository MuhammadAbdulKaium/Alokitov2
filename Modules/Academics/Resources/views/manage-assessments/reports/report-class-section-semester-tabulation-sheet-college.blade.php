<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{{--Student Information--}}
	<style type="text/css">

		.reportFooter {
			margin-top: 50px; font-weight: bold; display: inline-block;
		}
		.convener {
			margin-left: 25mm;
		}
		.principal {
			margin-left: 150mm;
		}
		.label {font-size: 15px;  padding: 3px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
		.text-center {text-align: center;}
		.clear{clear: both;}

		#inst-photo{float:left; width: 15%;}
		#inst{padding-bottom: 3px; width: 100%;}
		#inst-info{float:left; text-align: center; width: 85%;}


		/*html{margin:25px}*/
		body{font-size: 11px;}

		table { text-align: center; width: 100%; border-collapse: collapse;}
		th, td{ border: 1px solid #000; line-height: 13px; font-size: 12px;}

		/*second table*/
		.tbl-second tr>th {border-top: none; border-left: none; }
		.tbl-second tr>th:last-child {border-right: none;}
		.tbl-second tr>td {border-top: none; border-bottom: none; border-left: none;}
		.tbl-second tr>td:last-child {border-right: none;}
		/*last row*/
		#row-last td {border: 1px solid #000;}
		#row-last td {border-bottom: none; border-left: none;}
		#row-last td:last-child {border-right: none;}
		#summary i {
			text-align: center;
			padding-right: 25px;
			margin-top: 10px;
			font-weight: 700;
			vertical-align: middle;
		}

		/** Define the footer rules **/
		footer {
			position: fixed;
			bottom: 0cm;
			left: 0cm;
			right: 0cm;
			height: 2cm;

			/** Extra personal styles **/
			background-color: #03a9f4;
			color: white;
			text-align: center;
			line-height: 1.5cm;
		}
		/*div.divFooter {*/
		/*	display: none;*/
		/*}*/

		@media print {
			.breakNow {
				page-break-inside:avoid; page-break-after:always;
				margin-top: 10px;
			}
			div.divFooter {
				position: fixed;
				bottom: 0;
			}
			.hidden-print{
				display: none;
			}
		}

	</style>
</head>
<body>

@php
	$subGradeList =  array_key_exists('grade_count', $tabulationSheet)?$tabulationSheet['grade_count']:[];
	$aPlus = array_key_exists('A+',$subGradeList)?$subGradeList['A+']:0;
	$a = array_key_exists('A',$subGradeList)?$subGradeList['A']:0;
	$aMinus = array_key_exists('A-',$subGradeList)?$subGradeList['A-']:0;
	$b = array_key_exists('B',$subGradeList)?$subGradeList['B']:0;
	$c = array_key_exists('C',$subGradeList)?$subGradeList['C']:0;
	$d = array_key_exists('D',$subGradeList)?$subGradeList['D']:0;
	$f = array_key_exists('F',$subGradeList)?$subGradeList['F']:0;
	$totalPass= ($aPlus+$a+ $aMinus + $b + $c+ $d);
	$totalExaminee= ($totalPass+ $f);
	$passPercentage= $totalPass>0?(round((($totalPass * 100) / $totalExaminee),2)):0;
@endphp


{{--tabulation sheet result count--}}


{{--<div class="clear">--}}
	{{--<p class="label row-first text-center"></p>--}}
	{{--class subject list with student name--}}
	@if(count($studentList)>0)
		@foreach($studentList->chunk(10) as $studentChunk)
			<div id="inst" class="text-center clear" style="width: 100%; margin-top: 20px;" >
				<div id="inst-photo">
					@if($instituteInfo->logo)
						<img src="{{URL::asset('assets/users/images/'.$instituteInfo->logo)}}"  style="width:85px;height:85px; margin-bottom: 5px;">
					@endif
				</div>
				<div id="inst-info">
					<b style="font-size: 25px; margin-right: 100px">{{$instituteInfo->institute_name}}</b><br/>
					<span style="font-size:12px; margin-right: 100px">{{'Address: '.$instituteInfo->address1}}</span><br/>
					<span style="font-size:12px;margin-right: 100px">{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}</span><br/>
					<h1 style="font-size:12px;margin-right: 200px">{{$classInfo['batch_name']}} ---- {{$classInfo['section_name']}} ---- {{$classInfo['semester_name']}} --- Tabulation Sheet</h1>
				</div>
			</div>

			<table style="margin-top: 10mm">
				<thead>
				<tr>
					<th width="3%">Roll</th>
					<th width="12%">Student's Name</th>
					<th>Subject Wise Marks</th>
					<th width="3%">Total</th>
					<th width="3%">GPA (Main)</th>
					<th width="3%">GPA (All)</th>
				</tr>
				</thead>
				<tbody>
				{{--{{dd(count($studentList))}}--}}
				{{--student list checking--}}
				{{--student list checking--}}
				@foreach($studentChunk as $student)
					{{--student arry to object conversion --}}
					@php
						$student = (object)$student;
						// find student tabulation sheet
						$myTabulationSheet = array_key_exists($student->std_id, $tabulationSheet)?$tabulationSheet[$student->std_id]:[];
						// find student subject list
						$mySubjectList = array_key_exists('sub_list', $myTabulationSheet)?$myTabulationSheet['sub_list']:[];
						// find student result sheet
						$myResultSheet = array_key_exists('result', $myTabulationSheet)?$myTabulationSheet['result']:[];
						// find student additional subject list
						$myAdditionalSubList = array_key_exists($student->std_id, $additionalSubArrayList)?$additionalSubArrayList[$student->std_id]:[];
					@endphp
					<tr>
						{{--student details--}}
						<td>{{$student->gr_no}}</td>
						<td style="text-align: left; padding-left: 4px">{{$student->first_name.' '.$student->middle_name.' '.$student->last_name}}</td>
						{{--subject row--}}
						<td>
							{{--nested row--}}
							<table class="tbl-second">
								<tr>
									{{--student enrollment looping--}}
									@foreach($classSubArrayList as $groupId=>$groupDetails)
										{{--array to object conversion--}}
										@php
											$groupDetails = (object)$groupDetails;
											$subType = $groupDetails->type;
										@endphp
										{{--checking subject type--}}
										@if($subType==1 || in_array($groupId, $myAdditionalSubList))
											<th width="1%">{{$groupDetails->name}} {{$groupId==end($myAdditionalSubList)?'(4th)':''}}</th>
										@endif
									@endforeach
								</tr>
								<tr>
									{{--student enrollment looping--}}
									@foreach($classSubArrayList as $groupId=>$groupDetails)
										{{--array to object conversion--}}
										@php
											$groupDetails = (object)$groupDetails;
											$subType = $groupDetails->type;
											$subList = $groupDetails->subject;
											$subCodes = (array) $groupDetails->code;
										@endphp

										{{--checking subject type--}}
										@if($subType==1 || in_array($groupId, $myAdditionalSubList))
											{{--find my subject tabulation sheet--}}
											@php
												$subTabulation = array_key_exists($groupId, $mySubjectList)?$mySubjectList[$groupId]:[];
												// find assessment list
												$myAssList = array_key_exists('ass_list', $subTabulation)?$subTabulation['ass_list']:[];
											@endphp
											<td>
												<table>
													<tr>
														{{--category list looping--}}
														@foreach($catDetailArrayList as $catId=>$assList)
															@if($category!=$catId) @continue @endif
															{{--assessment list looping--}}
															@foreach($assList as $assId=>$assName)
																<th>{{$assName}}</th>
															@endforeach
														@endforeach
														<th>Total</th>
														<th>LG</th>
														<th>GP</th>
													</tr>
													<tr>
														{{--subject status--}}
														@php
															$lg = $subTabulation?$subTabulation['lg']:'';
															$gp = $subTabulation?$subTabulation['gp']:'';
															$total = $subTabulation?$subTabulation['obtained_mark']:'';
														@endphp
														{{--category list looping--}}
														@foreach($catDetailArrayList as $catId=>$assList)
															@if($category!=$catId) @continue @endif
															{{--assessment list looping--}}
															@foreach($assList as $assId=>$assName)
																{{--find assessment marks--}}
																@php $assMark = array_key_exists($assId, $myAssList)?$myAssList[$assId]:'-'; @endphp
																{{--set assessment marks--}}
																<td  style="color:{{(($lg=="F")||($assMark=="ABSENT"))?'red':''}}">{{$assMark}}</td>
															@endforeach
														@endforeach
														{{--categroy grade details--}}
														<td style="color:{{$lg=="F"?'red':''}}">{{$total}}</td>
														<td style="color:{{$lg=="F"?'red':''}}">{{$lg}}</td>
														<td style="color:{{$lg=="F"?'red':''}}">{{$gp}}</td>
													</tr>
												</table>
											</td>
										@endif
									@endforeach
								</tr>

							</table>
						</td>
						{{--result status--}}
						@php $failedCounter = $myResultSheet?$myResultSheet['failed']:0 @endphp
						<th style="color:{{$failedCounter>0?'red':''}}"> {{$myResultSheet?$myResultSheet['obtained']:'-'}}</th>
						<th style="color:{{$failedCounter>0?'red':''}}"> {{$myResultSheet?$myResultSheet['gpa_with_out_optional']:'-'}}</th>
						<th style="color:{{$failedCounter>0?'red':''}}"> {{$myResultSheet?$myResultSheet['gpa']:'-'}}</th>
					</tr>



				@endforeach
				<tr>
					<th colspan="6">
						<span id="summary" class="text-center">
							<i>A+ = {{ $aPlus }}</i>
							<i>A = {{ $a }}</i>
							<i>A- = {{ $aMinus }}</i>
							<i>B = {{ $b }}</i>
							<i>C = {{ $c }}</i>
							<i>D = {{ $d }}</i>
							<i>F = {{ $f }}</i>
							<i>Total Examinee = {{ $totalExaminee }}</i>
							<i>Pass (%) = {{ $passPercentage }}</i>
						</span>
					</th>
				</tr>
				</tbody>
			</table>

<p class="convener reportFooter">Convener   </p>
<p class="principal reportFooter">Principal   </p>
{{--<div class="divFooter">--}}
{{--	<pre>Convener                                                                                                                                       Principal</pre>--}}

{{--</div>--}}
<div class="breakNow"></div>
@endforeach
@endif







<script>
    window.print();
</script>

</body>

</html>

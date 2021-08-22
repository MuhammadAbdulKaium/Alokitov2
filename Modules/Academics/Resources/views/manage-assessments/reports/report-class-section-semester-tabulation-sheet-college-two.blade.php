<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{{--Student Information--}}
	<style type="text/css">
		.label {font-size: 15px;  padding: 3px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
		.text-center {text-align: center;}
		.clear{clear: both;}

		#inst-photo{float:left; width: 15%;}
		#inst{padding-bottom: 3px; width: 100%;}
		#inst-info{float:left; text-align: center; width: 85%;}


		html{margin:25px}
		body{font-size: 7px;}

		table { text-align: center; width: 100%; border-collapse: collapse;}
		th, td{ border: 1px solid #000; }

		/*second table*/
		.tbl-second tr>th {border-top: none; border-left: none; }
		.tbl-second tr>th:last-child {border-right: none;}
		.tbl-second tr>td {border-top: none; border-bottom: none; border-left: none;}
		.tbl-second tr>td:last-child {border-right: none;}
		/*last row*/
		#row-last td {border: 1px solid #000;}
		#row-last td {border-bottom: none; border-left: none;}
		#row-last td:last-child {border-right: none;}
	</style>
</head>
<body>
{{--<div id="inst" class="text-center clear" style="width: 100%;">--}}
{{--<div id="inst-photo">--}}
{{--@if($instituteInfo->logo)--}}
{{--<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:85px;height:85px; margin-bottom: 5px;">--}}
{{--@endif--}}
{{--</div>--}}
{{--<div id="inst-info">--}}
{{--<b style="font-size: 25px; margin-right: 200px">{{$instituteInfo->institute_name}}</b><br/>--}}
{{--<span style="font-size:12px; margin-right: 200px">{{'Address: '.$instituteInfo->address1}}</span><br/>--}}
{{--<span style="font-size:12px;margin-right: 200px">{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}</span>--}}
{{--</div>--}}
{{--</div>--}}

<div class="clear">
	{{--<p class="label row-first text-center">{{$classInfo['batch_name']}} - {{$classInfo['section_name']}} {{$classInfo['semester_name']}} Tabulation Sheet </p>--}}
	{{--class subject list with student name--}}
	<table>
		<thead>
		<tr>
			<th width="2%">Roll</th>
			<th width="8%">Student Name</th>
			<th>Subject List</th>
			<th width="3%">GPA</th>
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
					// find student additional subject list
					$myAdditionalSubList = array_key_exists($student->std_id, $additionalSubArrayList)?$additionalSubArrayList[$student->std_id]:[];
				@endphp
				<tr>
					{{--student details--}}
					<th>{{$student->gr_no}}</th>
					<th>{{$student->first_name.' '.$student->middle_name.' '.$student->last_name}}</th>
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
										$subCodes = (array) $groupDetails->code;
									@endphp
									{{--checking subject type--}}
									@if($subType==1 || in_array($groupId, $myAdditionalSubList))
										<th width="1%">{{$groupDetails->name}}</th>
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
										<td>
											<table>
												<tr>
													<th>Code</th>
													<th>CT</th>
													<th>MCQ</th>
													<th>CW</th>
													<th>Total</th>
													<th>GT</th>
													<th>LG</th>
													<th>GP</th>
												</tr>
												<tr>
													<td>{{array_key_exists(0, $subCodes)?$subCodes[0]:'-'}}</td>
													<td>#</td>
													<td>#</td>
													<td>#</td>
													<td>#</td>
													<td>#</td>
													<td>#</td>
													<td>#</td>
												</tr>
												{{--checkig subject code--}}
												{{--@if(array_key_exists(1, $subCodes))--}}
												<tr id="row-last">
													<td>{{array_key_exists(1, $subCodes)?$subCodes[1]:'-'}}</td>
													<td>#</td>
													<td>#</td>
													<td>#</td>
													<td>#</td>
													<td>#</td>
													<td>#</td>
													<td>#</td>
												</tr>
												{{--@endif--}}

												{{--sublist loop counter--}}
												{{--@php $subLoopCounter = 0; @endphp--}}
												{{--student subject list looping--}}
												{{--@foreach($subList as $subId=>$subName)--}}
													{{--@if(array_key_exists($subLoopCounter, $subCodes))--}}
														{{--<tr id="{{$subLoopCounter==1?'row-last':''}}">--}}
															{{--<td>{{$subId}}</td>--}}
															{{--<td>{{$subTabulation?$subTabulation['exam']:'-'}}</td>--}}
															{{--<td>{{$subTabulation?$subTabulation['obtained']:'-'}}</td>--}}
															{{--<td>{{$subTabulation?$subTabulation['percentage']:'-'}}</td>--}}
														{{--</tr>--}}
													{{--@endif--}}
													{{--sublist loop counter--}}
													{{--@php $subLoopCounter +=1; @endphp--}}
												{{--@endforeach--}}
												{{--count and check subject codes--}}
												{{--@if(count($subCodes)==1)<tr id="row-last"> <td colspan="3">-</td></tr>@endif--}}

											</table>
										</td>
									@endif
								@endforeach
							</tr>
						</table>

					</td>
					<th>#</th>
				</tr>
			@endforeach
		@endif
		</tbody>
	</table>
</div>

</body>

</html>

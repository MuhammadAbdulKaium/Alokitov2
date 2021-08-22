<!DOCTYPE html>
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
		}
		#std-photo {
			float:left;
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
	</style>
</head>

<body>
@php $days = array(1=>'Saturday',2=>'Sunday',3=>'Monday',4=>'Tuesday',5=>'Wednesday',6=>'Thursday',7=>'Friday'); @endphp
@for($d=1; $d<=count($days); $d++)

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

	<div class="routine clear">
		@if($batchSectionPeriodId>0)
			@if($allClassPeriods->count()>0)
				<div class="row">
					<div class="col-md-12">

						@php
							if($division = $batchProfile->division()){
								$batchName = $batchProfile->batch_name ." - ". $division->name;
							}else{
								$batchName = $batchProfile->batch_name;
							}
						@endphp

						<p class="text-center label row-second">{{$batchName. " (".$sectionProfile->section_name.")"}}</p>
{{--						<p class="text-center label row-second">{{$days[$d]}} ({{$batchName. " - ".$sectionProfile->section_name}})</p>--}}
						<table width="100%" border="1px solid" class="text-center report_card_table" cellpadding="5">
							<thead>
							<tr class="row-second"><th colspan="{{$allClassPeriods->count()+1}}">{{$days[$d]}}</th></tr>
							<tr class="row-second">
								<th>#</th>
								@if($allClassPeriods)
									@foreach($allClassPeriods as $period)
										<th>{{$period->period_name}}<br/>
											<span style="font-size: 8px; text-align: center">({{$period->period_start_hour % 10== $period->period_start_hour?'0':''}}{{$period->period_start_hour}}:{{$period->period_start_min % 10== $period->period_start_min?'0':''}}{{$period->period_start_min}} {{$period->period_start_meridiem==0 ? 'AM' : 'PM'}} - {{$period->period_end_hour % 10== $period->period_end_hour?'0':''}}{{$period->period_end_hour}}:{{$period->period_end_min % 10== $period->period_end_min?'0':''}}{{$period->period_end_min}} {{$period->period_end_meridiem==0 ? 'AM' : 'PM'}})</span></th>
									@endforeach
								@endif
							</tr>
							</thead>

							<tbody>
							@for($i=0; $i<count($classTeacherList); $i++)

								{{--class teacher--}}
								@php $teacher = $classTeacherList[$i]; @endphp

								<tr>
									{{--tacher name--}}
									<td>{{$teacher->name}}</td>
									{{--<td>{{$teacher->alias}}</td>--}}

									{{--day timetable sorter--}}
									@php $daySortedTimetableProfile = daySorter($d, $allTimetables); @endphp

									{{--period settings--}}
									@foreach($allClassPeriods as $period)
										<td>
											@php
												$sortedTimetableProfile = sortTimetableForTeacher($teacher->id,$period->id, $daySortedTimetableProfile);
												$timetableCount = $sortedTimetableProfile->count();
											@endphp

											{{--Checking timetable count--}}
											@if($timetableCount>0)
												@php
													$timetableProfile = (array) $sortedTimetableProfile->toArray();
													$timetableProfile = reset($timetableProfile);
													$teacherProfile = $period->teacher($timetableProfile['teacher']);
													$classSubjectProfile = $period->subject($timetableProfile['subject']);
													$subjectProfile = $classSubjectProfile->subject();
												@endphp
												{{--subject name--}}
												<span>{{$subjectProfile->subject_name}}</span>
											@else
												<span> - </span>
											@endif
										</td>
									@endforeach
								</tr>
							@endfor
							</tbody>
						</table>
					</div>
				</div>
			@else
				<div class=" col-md-10 col-md-offset-1 text-center alert bg-primary text-warning" style="margin-bottom:0px;">
					<i class="fa fa-warning"></i> No record found.
				</div>
			@endif
		@else
			<div class=" col-md-10 col-md-offset-1 text-center alert bg-primary text-warning" style="margin-bottom:0px;">
				<i class="fa fa-warning"></i> No Period Category is assigned for this class - section.
			</div>
		@endif
	</div>

	{{--checking--}}
	@if($d<count($days))
		<div style="page-break-after:always;"></div>
	@endif

@endfor

</body>
</html>
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
	<p class="label text-center row-second">Class Routine</p>
	<table width="100%" border="1px solid" class="text-center report_card_table" cellpadding="5">
		@php
			$tdCounter = 0;
			$days = array(1=>'Saturday',2=>'Sunday',3=>'Monday',4=>'Tuesday',5=>'Wednesday',6=>'Thursday',7=>'Friday');
		@endphp

		@for($k=1; $k<=count($days); $k++)
			@php
				$timetableCounter = daySorter($k, $teacherTimeTables)->count();
				if($timetableCounter>$tdCounter){
					$tdCounter = $timetableCounter;
				}
			@endphp
		@endfor

		<tbody>
		@for($i=1; $i<=count($days); $i++)
			<tr>
				{{--day name--}}
				<td><strong>{{$days[$i]}}</strong></td>
				{{--teacher class periods--}}
				@php
					$sortedTimetables = daySorter($i, $teacherTimeTables);
					$timetableCount = $sortedTimetables->count();
				@endphp

				@if($timetableCount>0)
					@foreach($sortedTimetables as $timetable)
							@php
								$classSubjectProfile = $timetable->classSubject();
								$subjectProfile = $classSubjectProfile->subject();
								$period = $timetable->classPeriod();
								$batchProfile = $classSubjectProfile->batch();
								$sectionProfile = $classSubjectProfile->section()
							@endphp
						<td>
							{{$classSubjectProfile->subject_code}}<br/>
							<span style="font-size: 10px">({{$period->period_start_hour % 10== $period->period_start_hour?'0':''}}{{$period->period_start_hour}}:{{$period->period_start_min % 10== $period->period_start_min?'0':''}}{{$period->period_start_min}} {{$period->period_start_meridiem==0 ? 'AM' : 'PM'}} - {{$period->period_end_hour % 10== $period->period_end_hour?'0':''}}{{$period->period_end_hour}}:{{$period->period_end_min % 10== $period->period_end_min?'0':''}}{{$period->period_end_min}} {{$period->period_end_meridiem==0 ? 'AM' : 'PM'}})</span><br/>
							<span>
{{$batchProfile->batch_name}}@if($division = $batchProfile->get_division()) - {{$division->name}}@endif  {{'('.$sectionProfile->section_name.')'}}
{{--{{'('.$batchProfile->academicsLevel()->level_name.')'}}--}}
							</span>
						</td>
					@endforeach
					{{----}}
					@if($tdCounter > $timetableCount)
						@php $myTdCounter = ($tdCounter-$timetableCount); @endphp
						@for($j=1; $j<=$myTdCounter; $j++)
							<td> - </td>
						@endfor
					@endif
				@else
					@if($tdCounter>0)
						@for($h=1; $h<=$tdCounter; $h++)
							<td> - </td>
						@endfor
					@else
						<td> - </td>
					@endif
				@endif
			</tr>
		@endfor
		</tbody>
	</table>
</div>
</body>
</html>
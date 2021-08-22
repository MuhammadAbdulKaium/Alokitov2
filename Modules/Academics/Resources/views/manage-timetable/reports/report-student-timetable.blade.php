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
	@if($batchSectionPeriodId>0)
		<p class="label text-center row-second">Class Routine</p>
		<table width="100%" border="1px solid" class="text-center report_card_table" cellpadding="5">
			<thead>
			<tr class="row-second">
				<th>#</th>
				@if($allClassPeriods)
					@foreach($allClassPeriods as $period)
						<th>{{$period->period_name}}<br/>
							<span style="font-size: 7px">({{$period->period_start_hour % 10== $period->period_start_hour?'0':''}}{{$period->period_start_hour}}:{{$period->period_start_min % 10== $period->period_start_min?'0':''}}{{$period->period_start_min}} {{$period->period_start_meridiem==0 ? 'AM' : 'PM'}} - {{$period->period_end_hour % 10== $period->period_end_hour?'0':''}}{{$period->period_end_hour}}:{{$period->period_end_min % 10== $period->period_end_min?'0':''}}{{$period->period_end_min}} {{$period->period_end_meridiem==0 ? 'AM' : 'PM'}})</span>
						</th>
					@endforeach
				@endif
			</tr>
			</thead>

			<tbody>
			@php $days = array(1=>'Saturday',2=>'Sunday',3=>'Monday',4=>'Tuesday',5=>'Wednesday',6=>'Thursday',7=>'Friday'); @endphp
			@for($i=1; $i<=count($days); $i++)
				<tr>
					{{--day name--}}
					<td><strong>{{$days[$i]}}</strong></td>

					{{--period settings--}}
					@foreach($allClassPeriods as $period)
						<td>
							@php
								$sortedTimetableProfile = sortTimetable($i, $period->id, $stdTimeTables);
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

								<div class="form-group">
								<span class="{{$timetableCount>0?'':''}}">
									@if($timetableCount>0)
										<strong>{{$classSubjectProfile->subject_code}}</strong>
										{{--{{$classSubjectProfile->subject_code}} <br/> ({{$classSubjectProfile->subject_type==1?'Mandatory':'Optional'}})--}}
									@else
										N/A
									@endif
								</span>
								</div>
								<div class="form-group">
								<span class="{{$timetableCount>0?'':''}}">
									@if($timetableCount>0)
										({{$teacherProfile->alias}})
									@else
										-
									@endif
								</span>
								</div>
							@else
								-
							@endif
						</td>
					@endforeach
				</tr>
			@endfor
			</tbody>
		</table>
	@else
		<p>No Period Category is assigned for this class - section.</p>
	@endif
</div>
</body>
</html>
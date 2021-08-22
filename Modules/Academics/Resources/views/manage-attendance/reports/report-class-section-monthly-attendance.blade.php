<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
	{{--checking report type--}}
	@if($reportType=='pdf')
		<style type="text/css">
			.label {font-size: 15px;  padding: 5px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
			.row-first{background-color: #b0bc9e;}
			.row-second{background-color: #e5edda;}
			.row-third{background-color: #00cc44;}
			.text-center {text-align: center; font-size: 12px}
			.text-left {text-align: left; font-size: 12px}
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
				font-size: 10px;
			}
			.report_card_table{
				border: 1px solid #dddddd;
				line-height: 8px;
				border-collapse: collapse;
			}

			html{
				margin: 20px;
			}
		</style>
	@endif
</head>


<body>

<div>
	{{--checking report type--}}
	@if($reportType=='pdf')
		<div id="inst" class="text-center clear" style="width: 100%;">
			<div id="inst-photo">
				@if($instituteInfo->logo)
					<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
				@endif
			</div>
			<div id="inst-info">
				<b style="font-size: 25px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
			</div>
		</div>
	@endif


	{{--current month date range finding--}}
	@php
		$monthName = date('F', mktime(0, 0, 0, $month, 1));
		$fromDate = date('01', strtotime($year.'-'.$month.'-01'));
		$toDate = date('t', strtotime($year.'-'.$month.'-01'));

		$academicHolidayList = $academicHolidayList?$academicHolidayList:[];
		$academicWeeKOffDayList = $academicWeeKOffDayList['status']=='success'?$academicWeeKOffDayList['week_off_list']:[];
	@endphp

	<div class="attendance clear">
		<br/>
		<table width="100%" border="1px solid black" class="report_card_table text-center" cellpadding="5">
			<thead>
			<tr class="row-second">
				<th colspan="{{$toDate+4}}">{{$batchName.' - '.$sectionName}} ------  Attendance Report ({{$monthName .' - '.$year}})</th>
			</tr>
			<tr class="row-second">
				<th width="3%">Roll</th>
				<th width="20%">Student Name</th>
				{{--month date looping--}}
				@for($day=$fromDate; $day<=$toDate; $day++)
					<th>{{$day}}</th>
				@endfor
				<th>P</th>
				<th>A</th>
			</tr>
			</thead>
			<tbody>
			@foreach($stdList as $index=>$stdProfile)
				{{--present  and absent counter--}}
				@php $present = 0; $absent = 0; @endphp
				<tr>
					<td>{{$stdProfile->gr_no}}</td>
					<td class="text-left" style="padding-left: 10px;">{{$stdProfile->first_name.' '.$stdProfile->middle_name.' '.$stdProfile->last_name}}</td>
					{{--std attendance list--}}
					@php $studentAttendanceList = array_key_exists($stdProfile->std_id, $attendanceArrayList)?$attendanceArrayList[$stdProfile->std_id]:[]; @endphp

					{{--input dates are in a month--}}
					@for($day=$fromDate; $day<=$toDate; $day++)
						{{--date formatting--}}
						@php $toDayDate = date('Y-m-d', strtotime($year.'-'.$month.'-'.$day)); @endphp
						@php $dayName = date('D', strtotime($year.'-'.$month.'-'.$day)); @endphp
						{{--array key exist checking--}}
						@if(array_key_exists($toDayDate , $studentAttendanceList)==true)
							<td>P</td>
							{{--present counter--}}
							@php $present +=1; @endphp
						@else
							@if(array_key_exists($toDayDate , $academicHolidayList)==true)
								<td>-</td>
							@else
								@if(array_key_exists($toDayDate , $academicWeeKOffDayList)==true)
									<td>-</td>
								@else
									<td>{{$dayName=='Fri'?'-':'A'}}</td>
									{{--absent counter--}}
									@php $absent +=($dayName=='Fri'?0:1); @endphp
								@endif
							@endif
						@endif
					@endfor
					<th class="row-second">{{$present}}</th>
					<th class="row-second">{{$absent}}</th>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
</body>
</html>

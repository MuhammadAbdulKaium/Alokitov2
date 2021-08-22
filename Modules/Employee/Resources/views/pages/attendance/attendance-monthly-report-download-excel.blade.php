{{--current month date range finding--}}
@php
	$monthName = date('F', mktime(0, 0, 0, $month, 1));
	$fromDate = date('01', strtotime($year.'-'.$month.'-01'));
	$toDate = date('t', strtotime($year.'-'.$month.'-01'));
	// national holidays list
	$academicHolidayList = $academicHolidayList?$academicHolidayList:[];
@endphp

@if($allEmployeeList->count()>0)
	{{--employee attendance list--}}
	<table style="text-align: center">
		<thead>
		<tr style="text-align: center">
			<th colspan="{{($toDate*2)+4}}">Staff Attendance Report ({{$monthName .' - '.$year}})</th>
		</tr>
		{{--date row--}}
		<tr style="text-align: center">
			<th rowspan="2">#</th>
			<th rowspan="2">Employee</th>
			@for($day=$fromDate; $day<=$toDate; $day++)
				{{--date formatting--}}
				@php $toDayDate = date('d-m-Y', strtotime($year.'-'.$month.'-'.$day)); @endphp
				{{--print date--}}
				<th colspan="2">{{$toDayDate}}</th>
			@endfor
			<th>Present</th>
			<th>Absent</th>
		</tr>

		{{--in / out level row--}}
		<tr>
			@for($day=$fromDate; $day<=$toDate; $day++)
				<td style="text-align: center">In Time</td>
				<td style="text-align: center">Out Time</td>
			@endfor
			<td style="text-align: center">-</td>
			<td style="text-align: center">-</td>
		</tr>
		</thead>
		<tbody>

		{{--emmployee list looping--}}
		@foreach($allEmployeeList as $index=>$employee)
			{{--present  and absent counter--}}
			@php $present = 0; $absent = 0; @endphp
			<tr>
				<td>{{($index+1)}}</td>
				<td style="text-align: left">{{$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name}}</td>
				{{--find employee attendance list--}}
				@php $myAttendanceList = array_key_exists($employee->id, $employeeAttendanceList)?$employeeAttendanceList[$employee->id]:[] @endphp

				{{--input dates are in a month--}}
				@for($day=$fromDate; $day<=$toDate; $day++)

					{{--date formatting--}}
					@php
						$toDayDate = date('Y-m-d', strtotime($year.'-'.$month.'-'.$day));
						$toDayDateId = date('w', strtotime($year.'-'.$month.'-'.$day));
					@endphp

					{{--array key exist checking--}}
					@if(array_key_exists($toDayDate , $myAttendanceList)==true)

						{{--find today's attendance--}}
						@php $toDayAttendance = $myAttendanceList[$toDayDate]; @endphp
						{{--print in out time--}}
						<td> {{$toDayAttendance['in_time']?(date('h:i:s a', strtotime($toDayAttendance['in_time']))):'-'}} </td>
						<td>{{$toDayAttendance['out_time']?(date('h:i:s a', strtotime($toDayAttendance['out_time']))):'-'}}</td>
						{{--present counter--}}
						@php $present +=1; @endphp

					@else

						{{--checking national holiday or friday--}}
						@if(array_key_exists($toDayDate , $academicHolidayList)==true || ($toDayDateId==5))
							<td colspan="2"> - </td>
						@else
							<td colspan="2"> A </td>
							{{--abesent counter--}}
							@php $absent +=1; @endphp
						@endif

					@endif

				@endfor
				{{--print present and absent days--}}
				<th class="row-second">{{$present}}</th>
				<th class="row-second">{{$absent}}</th>
			</tr>
		@endforeach
		</tbody>
	</table>
@endif



<table>
	<thead>
	<tr>
		<th>Title</th>
		<th>Detail</th>
		<th class="text-center">User Type</th>
		<th class="text-center">Start Time</th>
		<th class="text-center">End Time</th>
	</tr>
	</thead>
	<tbody>
	@foreach($eventList as $event)
		<tr>
			<td>{{$event->title}}</td>
			<td>{{$event->detail}}</td>
			@php $userType = $event->user_type; @endphp
			<td class="text-center">
				{{--user type column--}}
				@if($userType=='1')
					General
				@elseif($userType=='2')
					Employee
				@elseif($userType=='3')
					Student
				@elseif($userType=='4')
					Parent
				@else
					Not Found
				@endif
			</td>
			<td class="text-center">{{date('d-M-Y H:i:s', strtotime($event->start_date_time))}}</td>
			<td class="text-center">{{date('d-M-Y H:i:s', strtotime($event->end_date_time))}}</td>
		</tr>
	@endforeach
	</tbody>
</table>
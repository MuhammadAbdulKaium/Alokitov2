
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">
		<span aria-hidden="true">×</span> <span class="sr-only">Close</span>
	</button>
	<h4 class="modal-title">
		<i class="fa fa-pencil-square"></i> <b> Event List</b>
	</h4>
</div>

<style>
	table{
		font-size: 13px;
	}
</style>
{{--modal body--}}
<div class="row">
	<div class="nav-tab-bg" style="border-radius: 0px;">
		<h5 class="div-head"> <i class="fa fa-pencil-square"></i> <b>{{date("Y-m-d", strtotime($selectedDate))}} (Date Detail)</b></h5>
		<ul class="nav nav-tabs notice-tabs">
			<li class="active"><a data-toggle="tab" href="#events">Events</a></li>
			<li ><a data-toggle="tab" href="#week-off-day">Week-Off Day</a></li>
			<li><a data-toggle="tab" href="#holiday">National Holiday</a></li>
		</ul>
	</div>

	<div class="tab-content">
		<div id="events" class="tab-pane fade in active">
			<div class="col-md-12">
				<br/>
				<table class="table text-center table-striped table-bordered">
					<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">User</th>
						<th width="20%">Title</th>
						<th width="20%" class="text-center">Start Time</th>
						<th width="20%" class="text-center">End Time</th>
						<th>Detail</th>
					</tr>
					</thead>
					<tbody>
					@if($eventList->count()>0)
						@foreach($eventList as $index=>$event)
							<tr>
								<td class="text-center">{{($index+1)}}</td>
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
								<td>{{$event->title}}</td>
								<td class="text-center">{{date('d-M-Y (l) h:i:s a', strtotime($event->start_date_time))}}</td>
								<td class="text-center">{{date('d-M-Y (l) h:i:s a', strtotime($event->end_date_time))}}</td>
								<td>{{$event->detail}}</td>
							</tr>
						@endforeach
					@else
						<tr><td colspan="6"> No records found</td></tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
		<div id="week-off-day" class="tab-pane fade">
			<div class="col-md-12">
				<br/>
				<table class="table text-center table-striped table-bordered">
					<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Department</th>
						<th class="text-center">Week-Off Date</th>
					</tr>
					</thead>
					<tbody>
					@if($weekOffDayList->count()>0)
						@foreach($weekOffDayList as $index=>$weekOffDay)
							<tr>
								<td class="text-center">{{($index+1)}}</td>
								<td class="text-center">{{$weekOffDay->department()->name}}</td>
								<td class="text-center">{{date('d M, Y (l)', strtotime($weekOffDay->date))}}</td>
							</tr>
						@endforeach
					@else
						<tr><td colspan="3"> No records found</td></tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
		<div id="holiday" class="tab-pane fade">
			<div class="col-md-12">
				<br/>
				<table class="table text-center table-striped table-bordered">
					<thead>
					<tr>
						{{--<th class="text-center">Date</th>--}}
						<th width="30%" class="text-center">Holiday Title</th>
						<th width="15%" class="text-center">Start Date</th>
						<th width="15%" class="text-center">End Date</th>
						<th class="text-center">Total</th>
						<th class="text-center">Remarks</th>
					</tr>
					</thead>
					<tbody>
					@if($nationalHolidayDetailsProfile)
						@php $nationalHolidayProfile = $nationalHolidayDetailsProfile->holiday(); @endphp
						<tr>
							{{--<td class="text-center">{{date('d M, Y (l)', strtotime($nationalHolidayDetailsProfile->date))}}</td>--}}
							<td class="text-center">{{$nationalHolidayProfile->name}}</td>
							<td class="text-center">{{date('d M, Y (l)', strtotime($nationalHolidayProfile->start_date))}}</td>
							<td class="text-center">{{date('d M, Y (l)', strtotime($nationalHolidayProfile->end_date))}}</td>
							@php
								$datetime1 = new DateTime(date('Y-m-d', strtotime($nationalHolidayProfile->start_date)));
                                $datetime2 = new DateTime(date('Y-m-d', strtotime($nationalHolidayProfile->end_date)));
                                $interval = $datetime1->diff($datetime2);
							@endphp
							<td>{{$interval->format('%R%a')+1}} Days</td>
							<td>{{$nationalHolidayProfile->remarks}}</td>
						</tr>
					@else
						<tr><td colspan="5"> No records found</td></tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal-footer">
	<button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
</div>

<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
	<h4 class="modal-title"><i class="fa fa-plus-square"></i> Class - Teacher Timetable</h4>
</div>
<div class="modal-body">
	@if($batchSectionPeriodId>0)
		@if($allClassPeriods->count()>0)
			<div class="row">
				<h4 class="text-center text-bold bg-green-gradient">Class Details</h4>
				<div class="col-sm-12">
					<table class="table table-bordered table-striped text-center">
						<thead>
						<tr>
							<th>Teacher</th>
							<th>Subject</th>
							<th>Batch</th>
							<th>Section</th>
							<th>Shift</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>
								{{$teacherProfile->first_name." ".$teacherProfile->middle_name." ".$teacherProfile->last_name}}
							</td>
							<td>
								{{$classSubjectProfile->subject()->subject_name}}
							</td>
							<td>
								@php $batchProfile = $classSubjectProfile->batch() @endphp
								{{$batchProfile->batch_name}}@if($division = $batchProfile->get_division()) - {{$division->name}}@endif {{'('.$batchProfile->academicsLevel()->level_name.')'}}
							</td>
							<td>
								{{$classSubjectProfile->section()->section_name}}
							</td>
							<td>Shift Name</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<h4 class="text-center text-bold bg-green-gradient">Class TimeTable</h4>
				<div class="col-sm-12">
					<table class="table table-bordered text-center table-striped">
						<thead>
						<tr class="bg-green">
							<th>#</th>
							@if($allClassPeriods)
								@foreach($allClassPeriods as $period)
									<th>{{$period->period_name}}<br/>
										<span style="font-size: 8px">({{$period->period_start_hour % 10== $period->period_start_hour?'0':''}}{{$period->period_start_hour}}:{{$period->period_start_min % 10== $period->period_start_min?'0':''}}{{$period->period_start_min}} {{$period->period_start_meridiem==0 ? 'AM' : 'PM'}} - {{$period->period_end_hour % 10== $period->period_end_hour?'0':''}}{{$period->period_end_hour}}:{{$period->period_end_min % 10== $period->period_end_min?'0':''}}{{$period->period_end_min}} {{$period->period_end_meridiem==0 ? 'AM' : 'PM'}})</span></th>
								@endforeach
							@endif
						</tr>
						</thead>

						<tbody>
						@php $days = array(1=>'Saturday',2=>'Sunday',3=>'Monday',4=>'Tuesday',5=>'Wednesday',6=>'Thursday',7=>'Friday'); @endphp
						@for($i=1; $i<=count($days); $i++)
							<tr class="{{$i%2==0?'bg-gray':'bg-gray-active'}}">
								{{--day name--}}
								<td>{{$days[$i]}}</td>

								{{--period settings--}}
								@foreach($allClassPeriods as $period)
									<td>
										@php
											$sortedTimetableProfile = sortTimetable($i, $period->id, $teacherTimeTables);
											$timetableCount = $sortedTimetableProfile->count();
										@endphp

										{{--Checking timetable count--}}
										@if($timetableCount>0)
											@php
												$timetableProfile = (array) $sortedTimetableProfile->toArray();
												$timetableProfile = reset($timetableProfile);
											$classSubjectProfile = $period->subject($timetableProfile['subject']);
											$subjectProfile = $classSubjectProfile->subject();
											@endphp
										@endif
										<div class="form-group">
										<span class="{{$timetableCount>0?'label alert-success':''}}">
											@if($timetableCount>0){{$classSubjectProfile->subject_code}} @else - @endif
										</span>
										</div>
									</td>
								@endforeach
							</tr>
						@endfor
						</tbody>
					</table>
				</div>
			</div>
		@else
			<div class=" col-md-10 col-md-offset-1 text-center alert bg-warning text-warning" style="margin-bottom:0px;">
				<i class="fa fa-warning"></i> No record found.
			</div>
		@endif
	@else
		<div class=" col-md-10 col-md-offset-1 text-center alert bg-warning text-warning" style="margin-bottom:0px;">
			<i class="fa fa-warning"></i> No Period Category is assigned for this class - section.
		</div>
	@endif
</div>

<!--./modal-body-->
<div class="modal-footer">
	{{--<button class="btn btn-primary pull-left" type="button">Submit</button>--}}
	<button data-dismiss="modal" class="btn btn-success pull-right" type="button">Close</button>
</div>
<!--./modal-footer-->

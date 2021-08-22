
@if($batchSectionPeriodId>0)
	@if($allClassPeriods->count()>0)
		<div class="row">
			<div class="col-md-12">
				<h4 class="text-center text-bold bg-green-gradient">Class TimeTable</h4>
				@php $days = array(1=>'Saturday',2=>'Sunday',3=>'Monday',4=>'Tuesday',5=>'Wednesday',6=>'Thursday',7=>'Friday'); @endphp
				<p><a id="download-class-section-day-routine" class="btn btn-success pull-right">Download PDF</a></p> <br/><br/>
				@for($d=1; $d<=count($days); $d++)
					<p class="text-center text-bold bg-green-active">{{$days[$d]}}</p>
					<table class="table table-bordered table-striped text-center">
						<thead>
						<tr class="bg-green">
							<th>#</th>
							@if($allClassPeriods)
								@foreach($allClassPeriods as $period)
									<th>{{$period->period_name}}<br/>
										<span style="font-size: 10px">({{$period->period_start_hour % 10== $period->period_start_hour?'0':''}}{{$period->period_start_hour}}:{{$period->period_start_min % 10== $period->period_start_min?'0':''}}{{$period->period_start_min}} {{$period->period_start_meridiem==0 ? 'AM' : 'PM'}} - {{$period->period_end_hour % 10== $period->period_end_hour?'0':''}}{{$period->period_end_hour}}:{{$period->period_end_min % 10== $period->period_end_min?'0':''}}{{$period->period_end_min}} {{$period->period_end_meridiem==0 ? 'AM' : 'PM'}})</span></th>
								@endforeach
							@endif
						</tr>
						</thead>

						<tbody>
						@for($i=0; $i<count($classTeacherList); $i++)

							{{--class teacher--}}
							@php $teacher = $classTeacherList[$i]; @endphp

							<tr class="{{$i%2==0?'bg-gray':'bg-gray-active'}}">
								{{--tacher name--}}
								<td>{{$teacher->name}}</td>

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
										@endif

										<span  class="{{$timetableCount>0?'label label-success':''}}">
										@if($timetableCount>0){{$subjectProfile->subject_name}} @else - @endif
										</span>
									</td>
								@endforeach
							</tr>
						@endfor
						</tbody>
					</table>
				@endfor
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

{{--page script--}}
<script>
    $(document).ready(function () {
        $('#download-class-section-day-routine').click(function () {
            // dynamic html form
            $('<form id="class_section_day_routine_form" action="/academics/timetable/classSectionDayTimetable" method="POST"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="level" value="'+$("#level").val()+'"/>')
                .append('<input type="hidden" name="batch" value="'+$("#batch").val()+'"/>')
                .append('<input type="hidden" name="section" value="'+$("#section").val()+'"/>')
                .append('<input type="hidden" name="shift" value="'+$("#shift").val()+'"/>')
                .append('<input type="hidden" name="request_type" value="download"/>').appendTo('body').submit();
            // remove form from the body
            $('#class_section_day_routine_form').remove();
        });
    });
</script>
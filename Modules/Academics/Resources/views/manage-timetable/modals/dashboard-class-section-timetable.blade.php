
@if($batchSectionPeriodId>0)
	@if($allClassPeriods->count()>0)
		<div class="row">
			<div class="col-md-12">
				<div class="modal-header">
					<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title">
						<i class="fa fa-info-circle"></i> Class Section Timetable
					</h4>
				</div>
				<h4 class="text-center text-bold bg-green">TimeTable</h4>
				<table class="table table-bordered text-center table-striped">
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
					@php $days = array(1=>'Saturday',2=>'Sunday',3=>'Monday',4=>'Tuesday',5=>'Wednesday',6=>'Thursday',7=>'Friday'); @endphp
					@for($i=1; $i<=count($days); $i++)
						<tr class="{{$i%2==0?'bg-gray':'bg-gray-active'}}">
							{{--day name--}}
							<td>{{$days[$i]}}</td>

							{{--period settings--}}
							@foreach($allClassPeriods as $period)
								<td>
									<input id="class_day_{{$i.$period->id}}" type="hidden" value="{{$i}}"/>
									<input id="class_period_{{$i.$period->id}}" type="hidden" value="{{$period->id}}"/>

									@php
										$sortedTimetableProfile = sortTimetable($i, $period->id, $allTimetables);
										$timetableCount = $sortedTimetableProfile->count();
									@endphp

									{{--Checking timetable count--}}
									@if($timetableCount>0)
										@php
											$timetableProfile = (array) $sortedTimetableProfile->toArray();
											$timetableProfile = reset($timetableProfile);
											$teacherProfile = $period->teacher($timetableProfile['teacher']);
											$classSubjectProfile = findClassSubject($timetableProfile['subject']);
											$subjectProfile = $classSubjectProfile?$classSubjectProfile->subject():null;
										@endphp
										<input id="timetable_id_{{$i.$period->id}}" type="hidden" value="{{$timetableProfile['id']}}"/>
									@else
										<input id="timetable_id_{{$i.$period->id}}" type="hidden" value="0"/>
									@endif


									<div id="class_subject_list_{{$i.$period->id}}" class="form-group">
										<label class="control-label" for="class_subject">Subject</label>
										<select id="class_subject_{{$i.$period->id}}" class="form-control class_subject hide" name="class_subject">
											<option value="" selected disabled>Select Subject</option>
											@foreach($allClassSubjects as $classSubject)
												<option  value="{{$classSubject->id}}" @if($timetableCount>0){{$classSubject->id==$timetableProfile['subject']?'selected':''}}@endif >{{$classSubject->subject()->subject_name}}</option>
											@endforeach
										</select>
										<br/>

										<span id="class_subject_name_{{$i.$period->id}}" class="classSubject {{$timetableCount>0?'':''}}">
											@if($timetableCount>0){{$subjectProfile?$subjectProfile->subject_name:'(removed)'}} @else - @endif
										</span>
										<div class="help-block"></div>
									</div>
									<div  class="form-group">
										<label class="control-label" for="subject_teacher">Teacher</label>
										<select id="subject_teacher_{{$i.$period->id}}" class="form-control subject_teacher hide" name="subject_teacher">
											<option value="" selected disabled>Select Teacher</option>
										</select>
										<br/>
										<span id="subject_teacher_name_{{$i.$period->id}}" class="{{$timetableCount>0?'':''}}">
										@if($timetableCount>0)
												{{$teacherProfile->first_name." ".$teacherProfile->middle_name." ".$teacherProfile->last_name}}
												<input type="hidden" id="subject_teacher_id_{{$i.$period->id}}" value="{{$timetableProfile['teacher']}}"/>
											@else
												-
											@endif
									</span>
										<div class="help-block"></div>
									</div>
								</td>
							@endforeach
						</tr>
					@endfor
					</tbody>
				</table>
				<div class="modal-footer">
					<a class="btn btn-success pull-right" href="#" data-dismiss="modal">Cancel</a>
				</div>
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
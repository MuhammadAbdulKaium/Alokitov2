@if($subjectList->count()>0)
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1">
			<p class="bg-green-active text-bold text-center">Manage Class Section Timetable</p>
			{{--<a class="pull-right btn btn-primary" href="{{url('/academics/timetable')}}">Manage Timetable</a>--}}
			<table class="table table-bordered table-striped">
				<thead>
				<tr>
					<th>Subject Name</th>
					<th class="text-center">Teacher List</th>
					<th class="text-center">Action</th>
				</tr>
				</thead>
				<tbody>
				@foreach($subjectList as $subject)
					@php $teachCount = $subject->teacher()->count(); @endphp
					<tr>
						<td>{{$subject->subject()->subject_name}}</td>
						<td>
							@if($teachCount>0)
								<table class="table table-bordered" style="margin-bottom: 0px;">
									<tbody>
									@php $x =1; @endphp
									@foreach($subject->teacher() as $subTeacher)
										@php $teacherProfile = $subTeacher->employee(); @endphp
										<tr>
											<td class="text-center" width="30px">{{$x}}</td>
											<td><a href="{{url('/employee/profile/personal/'.$teacherProfile->id)}}">{{$teacherProfile->first_name." ".$teacherProfile->middle_name." ".$teacherProfile->last_name}} </a></td>
											<td class="text-center" width="100px">
												{{ucfirst(strtolower($subTeacher->status))}}
											</td>
											<td class="text-center" width="150px">
												@if($subTeacher->is_active==1)
													<a href="/academics/timetable/teacherTimeTable/{{$subTeacher->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">View Class Timetable</a>@else <i>Teacher is not active</i> @endif<br/>
												@php $x =($x+1); @endphp
											</td>
										</tr>
									@endforeach
									</tbody>
								</table>
							@else
								<div class="text-center alert bg-warning text-warning" style="margin-bottom:0px;"> <i class="fa fa-warning"></i> there is no teacher for this subject  </div>
							@endif
						</td>
						<td class="text-center">
							<a class="btn btn-success" href="/academics/manage/subjcet/teacher/assign/{{$subject->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">@if($teachCount>0) Assign More Teacher @else Assign Teacher @endif </a>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@else
	<div class=" col-md-10 col-md-offset-1 text-center alert bg-warning text-warning" style="margin-bottom:0px;">
		<i class="fa fa-warning"></i> No record found.
	</div>
@endif


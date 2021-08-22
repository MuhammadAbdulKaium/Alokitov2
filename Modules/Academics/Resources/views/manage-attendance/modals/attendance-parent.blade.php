<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<div class="col-md-12">
	<p class="text-center bg-blue-gradient text-bold">Student Information</p>

	<div class="row">
		<div class="panel-body">
			<div class="col-md-2 text-center">
				@php $photo = $studentInfo->singelAttachment("PROFILE_PHOTO") @endphp
				@if($photo)
					<img class="center-block img-thumbnail img-circle img-responsive" src="{{URL::asset('assets/users/images/'.$photo->singleContent()->name)}}" alt="No Image" style="width:100px;height:100px">
				@else
					<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:100px;height:100px">
				@endif
			</div>
			<div class="col-md-10">
				<table class="table table-bordered table-striped text-center table-responsive">
					<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Gr. No</th>
						<th>Section</th>
						<th>Batch</th>
						<th>Gender</th>
						<th>Birth Date</th>
						<th>Blood Group</th>
					</tr>
					</thead>

					<thead>
					<tr>
						<td>{{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</td>
						<td>{{$studentInfo->email}}</td>
						@php
							$enrollment = $studentInfo->singleEnroll();
							$batch  = $enrollment->batch();
							$section  = $enrollment->section();
						@endphp
						<td>{{$enrollment->gr_no}}</td>
						<td>{{$section->section_name}}</td>
						<td>{{$batch->batch_name}}@if($division = $batch->get_division())  ({{$division->name}})@endif</td>
						<td>{{$studentInfo->gender}}</td>
						<td>{{ date('d M, Y', strtotime($studentInfo->dob)) }}</td>
						<td>{{$studentInfo->blood_group}}</td>
					</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>

	<p class="text-center bg-blue-gradient text-bold">Attendance Report</p>

	<div class="col-md-12">
		@if($reportAttendanceType=='att_class')
			{{--checking attendance list--}}
			@if($studentAttendanceList)
				{{--checking attType subject/date--}}
				@if($attType=='subject')
					<table id="example1" class="table table-bordered table-striped table-responsive text-center">
						<thead>
						<tr>
							<th>Date</th>
							@foreach($academicSubjects as $key => $subject)
								<th class="text-center">{{$subject['name']}}</th>
							@endforeach
						</tr>
						</thead>
						<tbody class="text-bold">
						@for($i=0; $i<count($studentAttendanceList);$i++)
							<tr>
								<td>{{$studentAttendanceList[$i]['date']}}</td>
								@php $attendanceList = (array )$studentAttendanceList[$i]['attendance']; @endphp
								@for($j =0; $j<count($academicSubjects);$j++)

									@if(array_key_exists($academicSubjects[$j]['id'], $attendanceList) == true)
										@php $myAttendance = $attendanceList[$academicSubjects[$j]['id']]; @endphp
										<td class="alert {{$myAttendance == 0 ?'alert-danger':'alert-success'}}"> {{$myAttendance == 0 ?'A':'P'}} </td>
									@else
										<td>N/A</td>
									@endif
								@endfor
							</tr>
						@endfor
						</tbody>
					</table>
				@else
					<table id="example1" class="table table-bordered table-striped table-responsive text-center">
						<thead>
						<tr>
							<th>Date</th>
							<th>Attendance</th>
						</tr>
						</thead>
						<tbody>
						@for($i=0; $i<count($studentAttendanceList);$i++)
							<tr>
								<td>{{$studentAttendanceList[$i]['date']}}</td>
								@php $myAttendance = $studentAttendanceList[$i]['attendance']; @endphp
								<td class="alert {{$myAttendance ==0?'alert-danger':'alert-success'}}"> {{$myAttendance==0?'A':'P'}}</td>
							</tr>
						@endfor
						</tbody>
					</table>
				@endif
			@else
				<div id="w0-success-0" class="alert-warning text-center alert-auto-hide alert fade in" style="opacity: 417.759;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h5 class="text-bold"><i class="fa fa-warning"></i> No record found. </h5>
				</div>
			@endif
		@else
			<table id="example1" class="table table-bordered table-striped table-responsive text-center">
				<thead>
				<tr class="row-second">
					<th class="text-center">Date</th>
					<th class="text-center">Attendance Type</th>
					<th class="text-center">Entry Time</th>
				</tr>
				</thead>

				<tbody>
				@if($academicHolidayList)
					@if($academicWeeKOffDayList['status']=='success')
						@php $academicWeeKOffDayList = $academicWeeKOffDayList['week_off_list'] @endphp
						{{--date, month finding--}}
						@php
							// from_date details
                            $fromYear = date('Y',strtotime($startDate));
                            $fromMonth = date('m',strtotime($startDate));
                            $fromDate = date('d',strtotime($startDate));
                            // to_date details
                            $toYear = date('Y',strtotime($endDate));
                            $toMonth = date('m',strtotime($endDate));
                            $toDate = date('d',strtotime($endDate));
						@endphp

						{{--date, month and year checking--}}
						@if($fromYear==$toYear AND $fromMonth==$toMonth)
							{{--for same year and same month--}}

							{{--month looping--}}
							@for($day=$fromDate; $day<=$toDate; $day++)
								{{--date formatting--}}
								@php $toDayDate = date('Y-m-d', strtotime(date('Y-m-'.$day, strtotime($startDate)))); @endphp
								{{--attendance row--}}
								<tr>
									<td>{{$toDayDate}}</td>
									@if(array_key_exists($toDayDate , $studentAttendanceList))
										<td>P</td>
										<td>{{$studentAttendanceList[$toDayDate]}}</td>
									@else
										@if(array_key_exists($toDayDate , $academicHolidayList))
											<td>{{$academicHolidayList[$toDayDate]}}</td>
											<td></td>
										@else
											@if(array_key_exists($toDayDate , $academicWeeKOffDayList))
												<td>{{$academicWeeKOffDayList[$toDayDate]}}</td>
												<td></td>
											@else
												<td>A</td>
												<td>N/A</td>
											@endif
										@endif
									@endif
								</tr>
							@endfor

						@elseif($fromYear==$toYear AND $fromMonth<$toMonth)
							{{--for same year and different month--}}

							{{--month looping--}}
							@for($month=$fromMonth; $month<=$toMonth; $month++)
								{{--current month date range finding--}}
								@php $monthFirstDate = date('01', strtotime($fromYear.'-'.$month.'-01')); @endphp
								@php $monthLastDate = date('t', strtotime($fromYear.'-'.$month.'-01')); @endphp
								{{--date range reset--}}
								@php
									if($fromMonth==$month){$monthFirstDate = $fromDate;}
                                    if($toMonth==$month){$monthLastDate = $toDate;}
								@endphp
								{{--current month date looping--}}
								@for($day=$monthFirstDate; $day<=$monthLastDate; $day++)
									{{--date formatting--}}
									@php $toDayDate = date('Y-m-d', strtotime($fromYear."-".$month."-".$day)); @endphp
									{{--attendance row--}}
									<tr>
										<td>{{$toDayDate}}</td>
										@if(array_key_exists($toDayDate , $studentAttendanceList))
											<td>P</td>
											<td>{{$studentAttendanceList[$toDayDate]}}</td>
										@else
											@if(array_key_exists($toDayDate , $academicHolidayList))
												<td>{{$academicHolidayList[$toDayDate]}}</td>
												<td></td>
											@else
												@if(array_key_exists($toDayDate , $academicWeeKOffDayList))
													<td>{{$academicWeeKOffDayList[$toDayDate]}}</td>
													<td></td>
												@else
													<td>A</td>
													<td>N/A</td>
												@endif
											@endif
										@endif
									</tr>
								@endfor
							@endfor
						@elseif($fromYear<$toYear)
							{{--for different year--}}

							{{--year looping--}}
							@for($year=$fromYear; $year<=$toYear; $year++)
								@php $yearMonths = 12; @endphp
								{{--month looping--}}
								@for($month=1; $month<=$yearMonths; $month++)
									{{--current month date range finding--}}
									@php $monthFirstDate = date('01', strtotime($year.'-'.$month.'-01')); @endphp
									@php $monthLastDate = date('t', strtotime($year.'-'.$month.'-01')); @endphp
									{{--date, month range reset--}}
									@php
										if($toYear==$year){$yearMonths = $toMonth;}
                                        if($toYear==$year AND $toMonth==$month){$monthLastDate = $toDate;}
                                        if($fromYear==$year AND $fromMonth==$month){$monthFirstDate = $fromDate;}
									@endphp
									{{--current month date looping--}}
									@for($day=$monthFirstDate; $day<=$monthLastDate; $day++)
										{{--date formatting--}}
										@php $toDayDate = date('Y-m-d', strtotime($year."-".$month."-".$day)); @endphp
										{{--attendance row--}}
										<tr>
											<td>{{$toDayDate}}</td>
											@if(array_key_exists($toDayDate , $studentAttendanceList))
												<td>P</td>
												<td>{{$studentAttendanceList[$toDayDate]}}</td>
											@else
												@if(array_key_exists($toDayDate , $academicHolidayList))
													<td>{{$academicHolidayList[$toDayDate]}}</td>
													<td></td>
												@else
													@if(array_key_exists($toDayDate , $academicWeeKOffDayList))
														<td>{{$academicWeeKOffDayList[$toDayDate]}}</td>
														<td></td>
													@else
														<td>A</td>
														<td>N/A</td>
													@endif
												@endif
											@endif
										</tr>
									@endfor
								@endfor
							@endfor
						@else
							<tr class="bg-aqua-active">
								<td colspan="3">Invalid Date format</td>
							</tr>
						@endif
					@else
						<tr><td colspan="3">{{$academicWeeKOffDayList['msg']}}</td></tr>
					@endif
				@else
					<tr><td colspan="3">National Holiday List is empty</td></tr>
				@endif
				</tbody>
			</table>
		@endif
	</div>
</div>

<!-- DataTables -->
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- datatable script -->
<script>
    $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>

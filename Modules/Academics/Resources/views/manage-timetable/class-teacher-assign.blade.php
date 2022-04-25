
@extends('academics::manage-timetable.index')
@section('page-content')

	<div class="row">
		<div class="col-sm-12">
			<div class="row">
<!--				<div class="col-sm-12">
					<h4 class="pull-left"><strong>Class Teacher List</strong></h4>
					<a class="btn btn-success pull-right" href="/academics/timetable/class-teacher/assign" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i> Class Teacher Add</a>
				</div>-->
			</div>
			<div class="box-body table-responsive">
				<div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
					<div id="w1" class="grid-view">
						<table id="myTable" class="table table-striped table-bordered text-center">
							<thead>
							<tr><th>#</th>
								<th><a  data-sort="sub_master_alias">Class Name</a></th>
								<th><a  data-sort="sub_master_alias">Group</a></th>
								<th><a  data-sort="sub_master_alias">Form Name</a></th>
								<th><a  data-sort="sub_master_alias">Form Master </a></th>
								<th><a>Action</a></th>
							</tr>

							</thead>
							<tbody>

							@if(isset($sections))
								@php $i = 1; @endphp
								@foreach($sections as $values)
									@php $batch = $values->batchName(); @endphp
									{{--checking--}}
									@if($batch==null) @continue @endif
									<tr class="gradeX">
										<form action="{{URL::to('/academics/timetable/class-teacher-assign')}}" method="post">
											@csrf
												<td>{{$i++}}</td>
												<td>{{$batch?$batch->batch_name:''}}</td>
												@php $division = $batch->get_division(); @endphp
												{{-- <td>{{$division?$division->name:'-'}}</td> --}}
												<td>

													@foreach ($values->divisions as $division)
														<div class="badge badge-info">{{ $division->name }}</div>
													@endforeach
												</td>

												<td>

												{{$values->section_name}}
												</td>
												<td style="width: 60%;color: black">
													<input type="hidden" value="{{$values->id}}" name="section">
													<input type="hidden" value="{{$values->batch_id}}" name="batch">
													<input type="hidden" value="{{$values->singleBatch->academics_level_id}}" name="academic_level1">
												@php
												if(isset($teacherArray[$values->id]))
													{
														$selectedTeachers=$teacherArray[$values->id]->pluck('teacher_id')->toArray();
													}else{
    													$selectedTeachers=[];
													}





												@endphp

										<select name="teacherID[]" id="" class="form-control select-students" multiple >
														<option value="">--Form Master--</option>

															@foreach ($empList as $key=>$singleEmployee)
																@php
																	$selected = '';
                                                                    if(in_array($singleEmployee->id, $selectedTeachers)){
                                                                        $selected = 'selected';
                                                                    }
																@endphp
																<option value="{{$singleEmployee->id}}"  style="color:#000;" {{$selected}}>
																	Name:{{ $singleEmployee->title }}
																	{{ $singleEmployee->first_name }}
																	{{ $singleEmployee->last_name }}
																	@if($singleEmployee->singleUser!=null)ID:{{ $singleEmployee->singleUser->username }} @endif
																	@if($singleEmployee->department!=0) Department:{{ $singleEmployee->singleDepartment->name  }}@endif
																	@if($singleEmployee->singleDesignation!=null) Designation:{{ $singleEmployee->singleDesignation->name }} @endif</option>
															@endforeach
														{{--@endif--}}
													</select>



												</td>
											<td>
												{{-- @if (in_array('academics/timetable.class-teacher.add', $pageAccessData)) --}}
													<button class="btn btn-success" type="submit">Update</button>
												{{-- @endif --}}
											</td>
										</form>
									</tr>
								@endforeach
							@endif
							{{--{{ $data->render() }}--}}

							</tbody>
						</table>
					</div>		</div>    </div><!-- /.box-body -->



		</div>

	</div>
@endsection

@section('page-script')
	<script>
		$(document).ready(function () {
			$('.select-students').select2({
				placeholder: "Select Form Master",
			});

			var globalStudents = [];

		/*	$('.select-house').change(function () {
				var parent = $(this).parent().parent().parent();
				var selectStudents = parent.find('.select-students');
				if($(this).val()){
					parent.find('.select-batch').val('');
					parent.find('.select-batch').attr('disabled', true);
					parent.find('.select-section').val('');
					parent.find('.select-section').attr('disabled', true);
				}else{
					parent.find('.select-batch').attr('disabled', false);
					parent.find('.select-section').attr('disabled', false);
				}

				// Ajax Request Start
				$_token = "{{ csrf_token() }}";
				$.ajax({
					headers: {
						'X-CSRF-Token': $('meta[name=_token]').attr('content')
					},
					url: "{{ url('/event/get/students/from/house') }}",
					type: 'GET',
					cache: false,
					data: {
						'_token': $_token,
						'houseId': $(this).val(),
					}, //see the _token
					datatype: 'application/json',

					beforeSend: function () {},

					success: function (data) {
						var txt = '';
						data.forEach(element => {
							txt += '<option value="'+element.std_id+'">'+element.first_name+' '+element.last_name+'</option>';
						});

						selectStudents.empty();
						selectStudents.append(txt);
						selectStudents.val(null).trigger('change');
					}
				});
				// Ajax Request End
			});

			$('.select-batch').change(function () {
				var parent = $(this).parent().parent().parent();
				var selectStudents = parent.find('.select-students');
				if($(this).val()){
					parent.find('.select-house').val('');
					parent.find('.select-house').attr('disabled', true);
				}else{
					parent.find('.select-house').attr('disabled', false);
				}

				// Ajax Request Start
				$_token = "{{ csrf_token() }}";
				$.ajax({
					headers: {
						'X-CSRF-Token': $('meta[name=_token]').attr('content')
					},
					url: "{{ url('/event/get/sections/students/from/batch') }}",
					type: 'GET',
					cache: false,
					data: {
						'_token': $_token,
						'batchId': $(this).val(),
					}, //see the _token
					datatype: 'application/json',

					beforeSend: function () {},

					success: function (data) {
						var batches = '<option value="">--Section--</option>';
						globalStudents = data[1];
						var students = '';

						data[0].forEach(element => {
							batches += '<option value="'+element.id+'">'+element.section_name+'</option>';
						});

						data[1].forEach(element => {
							students += '<option value="'+element.std_id+'">'+element.first_name+' '+element.last_name+'</option>';
						});

						$('.select-section').empty();
						$('.select-section').append(batches);

						selectStudents.empty();
						selectStudents.append(students);
						selectStudents.val(null).trigger('change');
					}
				});
				// Ajax Request End
			});

			$('.select-section').change(function () {
				var selectStudents = $(this).parent().parent().find('.select-students');

				// Ajax Request Start
				$_token = "{{ csrf_token() }}";
				$.ajax({
					headers: {
						'X-CSRF-Token': $('meta[name=_token]').attr('content')
					},
					url: "{{ url('/event/get/students/from/section') }}",
					type: 'GET',
					cache: false,
					data: {
						'_token': $_token,
						'sectionId': $(this).val(),
					}, //see the _token
					datatype: 'application/json',

					beforeSend: function () {},

					success: function (data) {
						var students = '';

						if (data.length < 1) {
							globalStudents.forEach(element => {
								students += '<option value="'+element.std_id+'">'+element.first_name+' '+element.last_name+'</option>';
							});
						}else{
							data.forEach(element => {
								students += '<option value="'+element.std_id+'">'+element.first_name+' '+element.last_name+'</option>';
							});
						}

						selectStudents.empty();
						selectStudents.append(students);
						selectStudents.val(null).trigger('change');
					}
				});
				// Ajax Request End
			});

			$('.delete-team-btn').click(function () {
				var team = $(this).parent().parent().parent();
				var teamId = $(this).data('team-id');

				// Ajax Request Start
				$_token = "{{ csrf_token() }}";
				$.ajax({
					headers: {
						'X-CSRF-Token': $('meta[name=_token]').attr('content')
					},
					url: "{{ url('/event/delete/event/team') }}",
					type: 'GET',
					cache: false,
					data: {
						'_token': $_token,
						'teamId': teamId,
					}, //see the _token
					datatype: 'application/json',

					beforeSend: function () {},

					success: function (data) {
						if (data == 1) {
							team.empty();
						}else if (data == 2) {
							swal('Error', 'This team is assigned to an event date, can not delete.', 'error');
						}
					}
				});
				// Ajax Request End
			});*/
		});
	</script>
@endsection

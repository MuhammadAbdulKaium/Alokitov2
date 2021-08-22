
@extends('academics::manage-timetable.index')
@section('page-content')
	<div class="row">
		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-12">
					<h4 class="pull-left"><strong>Class Teacher List</strong></h4>
					<a class="btn btn-success pull-right" href="/academics/timetable/class-teacher/assign" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i> Class Teacher Add</a>
				</div>
			</div>

			@if(!empty($classTeacherList))
						<table class="table table-bordered table-striped text-center">
							<thead>
							<tr>
								<th> SL. </th>
								<th>Name</th>
								<th>Class</th>
								<th>Section</th>
								<th>Action</th>
							</tr>
							</thead>

							<tbody>
							@php $i=1; @endphp
							@foreach($classTeacherList as $classTeacher)
								@php $teacherProfile=$classTeacher->teacher(); @endphp
							<tr>
								<th> {{$i++}} </th>

								<th>{{$teacherProfile->first_name.' '.$teacherProfile->middle_name.' '.$teacherProfile->last_name}}</th>
								<th>{{$classTeacher->batch()->batch_name}}</th>
								<th>{{$classTeacher->section()->section_name}}</th>
								<th><a class="btn btn-danger" href="{{URL::to('/academics/timetable/class-teacher/assign/delete',$classTeacher->id)}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a></th>
							</tr>
								@endforeach

							</tbody>
						</table>
			@else

				<div class="text-center alert bg-warning text-warning" style="margin:30px 0px 0px 0px;">
					<i class="fa fa-warning"></i> No record found.
				</div>
				@endif

		</div>

	</div>
@endsection

@section('page-script')
@endsection

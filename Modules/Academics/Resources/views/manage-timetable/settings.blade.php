
@extends('academics::manage-timetable.index')
@section('page-content')
	<div class="row">
		<div class="col-sm-7">
			<div class="row">
				<div class="col-sm-12">
					<h4 class="pull-left"><strong>Class Periods</strong></h4>
					<a class="btn btn-success pull-right" href="/academics/timetable/period/add" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i> Add Period</a>
				</div>
			</div>

			@if($classPeriodCategories->count() > 0)
				@foreach($classPeriodCategories as $category)
					@php $allPeriods = $category->periods(); @endphp
					@if($allPeriods->count()>0)
						<h4 class="text-center text-bold bg-green-active">{{$category->name}}</h4>
						<table class="table table-bordered table-striped text-center">
							<thead>
							<tr>
								<th> # </th>
								<th>Name</th>
								{{--<th>Category</th>--}}
								<th>Shift</th>
								<th>Start Time</th>
								<th>End Time</th>
								<th>Type</th>
								<th>Action</th>
							</tr>
							</thead>

							<tbody>
							@php $i=1; @endphp
							@foreach($allPeriods as $period)
								<tr>
									<td>{{$i}}</td>
									<td>{{$period->period_name}}</td>
									{{--<td>{{$period->category()->name}}</td>--}}
									<td>{{$period->period_shift==0?'Day':'Morning'}}</td>
									<td>{{$period->period_start_hour % 10== $period->period_start_hour?'0':''}}{{$period->period_start_hour}}:{{$period->period_start_min % 10== $period->period_start_min?'0':''}}{{$period->period_start_min}} {{$period->period_start_meridiem==0 ? 'AM' : 'PM'}}
									</td>
									<td>{{$period->period_end_hour % 10== $period->period_end_hour?'0':''}}{{$period->period_end_hour}}:{{$period->period_end_min % 10== $period->period_end_min?'0':''}}{{$period->period_end_min}} {{$period->period_end_meridiem==0 ? 'AM' : 'PM'}}
									</td>
									<td>{{$period->is_break==1 ? 'Break' : 'Class Time' }}</td>

									<td> <a href="{{url('/academics/timetable/period/edit/'.$period->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" >Edit</a>   |   <a href="{{url('/academics/timetable/period/delete/'.$period->id)}}"  onclick="return confirm('Are you sure?');">Delete</a> </td>
								</tr>
								@php $i=($i+1); @endphp
							@endforeach
							</tbody>
						</table>
					@endif
				@endforeach
			@else
				<div class="text-center alert bg-warning text-warning" style="margin:30px 0px 0px 0px;">
					<i class="fa fa-warning"></i> No record found.
				</div>
			@endif
		</div>

		<div class="col-sm-4 col-sm-offset-1">
			<div class="row">
				<div class="col-sm-12">
					<h4 class="pull-left"><strong>Categories</strong></h4>
					@if($classPeriodCategories->count() <= 10)<a style="margin-left: 5px" class="btn btn-success pull-right" href="/academics/timetable/period/category/add" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square" aria-hidden="true"></i> Add New</a>@endif
					@if($classPeriodCategories->count() >0)<a class="btn btn-success pull-right" href="/academics/timetable/period/category/assign/class" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i> Assign</a>@endif
				</div>
			</div>

			@if($classPeriodCategories->count()>0)
				<table class="table table-bordered text-center table-striped">
					<thead>
					<tr>
						<th> # </th>
						<th>Category Name</th>
						<th>Action</th>
					</tr>
					</thead>

					<tbody>
					@php $i=1; @endphp
					@foreach($classPeriodCategories as $category)
						<tr>
							<td>{{$i}}</td>
							<td>{{$category->name}}</td>
							<td>
								<a href="{{url('/academics/timetable/period/category/edit/'.$category->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								</a>
								| <a href="{{url('/academics/timetable/period/category/delete/'.$category->id)}}"  onclick="return confirm('Are you sure?');"><i class="fa fa-trash-o" aria-hidden="true"></i>
								</a>
								| <a href="{{url('/academics/timetable/period/category/assign/'.$category->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Details</a>

						</td>
						</tr>
						@php $i=($i+1); @endphp
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

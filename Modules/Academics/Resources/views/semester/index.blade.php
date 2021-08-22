
@extends('layouts.master')
<!-- page content -->
@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-plus-square"></i> Semester
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Academics</a></li>
				<li class="active">Semester</li>
			</ul>
		</section>
		<section class="content">
			@if(Session::has('success'))
				<div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
				</div>
			@elseif(Session::has('warning'))
				<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
				</div>
			@endif
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<p class="pull-right flip">
								<a class="btn btn-success" href="{{url('/academics/semester/batch/assign')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Assign</a>
								<a class="btn btn-success" href="{{url('/academics/semester/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Add Semester</a>
							</p>
						</div>
					</div>
					<div class="row">
						<div class="box box-solid">
							<div class="col-md-12">
								@if($allSemester->count()>0)
									<table class="table table-striped table-bordered">
										<thead>
										<tr>
											<th>Name</th>
											<th>Start Date</th>
											<th>End Date</th>
											<th class="text-center">Status</th>
											<th class="text-center">Action</th>
										</tr>
										</thead>

										<tbody id="semester_table_body">
										@foreach($allSemester as $semester)
											<tr>
												<td>{{$semester->name}}</td>
												<td>{{ date('d M, Y', strtotime($semester->start_date)) }}</td>
												<td>{{ date('d M, Y', strtotime($semester->end_date)) }}</td>
												<td class="text-center">
													<a href="{{url('/academics/semester/status/'.$semester->id)}}" title="Status" onclick="return confirm('Are you sure to change status?');">
														<i class="{{$semester->status==0?'fa fa-ban text-red':'fa fa-check text-green'}}" aria-hidden="true"></i>
													</a>
												</td>
												<td class="text-center">
													<a href="{{url('/academics/semester/'.$semester->id.'/edit')}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
														<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
													</a>
													<a href="{{url('/academics/semester/delete/'.$semester->id)}}" title="Delete" onclick="return confirm('Are you sure want to delete this item?');">
														<i class="fa fa-trash-o" aria-hidden="true"></i>
													</a>
												</td>
											</tr>
										@endforeach
										</tbody>
									</table>
								@else
									<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
										<h5><i class="fa fa-warning text-bold"></i></i> No records found. </h5>
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

	<!-- global modal -->
	<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="loader">
						<div class="es-spinner">
							<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

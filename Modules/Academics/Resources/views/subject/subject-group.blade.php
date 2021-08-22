@extends('layouts.master')

@section('content')
	<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-th-list"></i> Manage  |<small>Subject Group</small>
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Academics</a></li>
				<li class="active">Course Management</li>
				<li class="active">Subject Group</li>
			</ul>
		</section>

		<section class="content">
			<div class="box box-solid">
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="box-header">
								<h3 class="box-title"><i class="fa fa-plus-square"></i> Group Subject List</h3>
								@if($subjectGroupList->count()>0)
									@foreach($subjectGroupList as $subjectGroup)
										<h5 class="bg-success text-center text-bold text-success" style="padding: 5px">{{$subjectGroup->name}}

											@if($subjectGroup->type==1)
												(Compulsory)
											@elseif($subjectGroup->type==2)
												(Elective)
											@else
												(Optional)
											@endif
											<a href="{{url('/academics/subject/group/assign/'.$subjectGroup->id)}}" class="label label-success btn-success pull-right" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Assign Subject</a>
										</h5>

										@php $groupSubjectsList = $subjectGroup->groupSubjects(); @endphp

										<input type="hidden" id="sub_group_table_{{$subjectGroup->id}}_row_count" value="{{$groupSubjectsList->count()}}">
										<table id="sub_group_table_{{$subjectGroup->id}}" class="table table-responsive table-striped text-center {{$groupSubjectsList->count()==0?'hide':''}}">
											<thead>
											<tr>
												<th>#</th>
												<th>Subject Name</th>
												<th>Action</th>
											</tr>
											</thead>
											@php $x = 1; @endphp
											<tbody id="sub_group_table_body_{{$subjectGroup->id}}">
											@foreach($groupSubjectsList as $GroupSubject)
												{{--subject profile--}}
												@php $subjectProfile = $GroupSubject->subject(); @endphp
												<tr>
													<td>{{$x}}</td>
													<td>{{$subjectProfile->subject_name . " (" . $subjectProfile->subject_code.")" }}</td>
													<td>
														<a href="{{url('/academics/subject/group/assign/delete/'.$GroupSubject->id)}}" onclick="confirm('Are you sure to delete this item?')" title="Delete">
															<i class="fa fa-trash-o" aria-hidden="true"></i></a>
													</td>
												</tr>
												@php $x += 1; @endphp
											@endforeach
											</tbody>
										</table>
									@endforeach
								@else
									<p class="text-center bg-success text-bold text-success">Create Subject Group First</p>
								@endif
							</div>
						</div>
						<div class="col-md-6">
							<div class="box-header">
								<h3 class="box-title pull-left"><i class="fa fa-plus-square"></i> Subject Group</h3>
								<a href="{{url('/academics/subject/group/create')}}" class="btn btn-success pull-right" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
									<i class="fa fa-plus-square"></i> Add Group
								</a>
							</div>

							@if($subjectGroupList->count()>0)
								<table class="table table-responsive table-striped text-center">
									<thead>
									<tr>
										<th>#</th>
										<th>Group Name</th>
										<th>Type</th>
										<th>Action</th>
									</tr>
									</thead>
									<tbody>
									@php $x = 1; @endphp
									@foreach($subjectGroupList as $subjectGroup)
										<tr>
											<td>{{$x}}</td>
											<td>{{$subjectGroup->name}}</td>
											<td>
												@if($subjectGroup->type==1)
													Compulsory
												@elseif($subjectGroup->type==2)
													Elective
												@elseif($subjectGroup->type==3)
													Optional
												@else
													-
												@endif
											</td>
											<td class="text-center">
												{{--edit button--}}
												<a href="{{url('/academics/subject/group/edit/'.$subjectGroup->id)}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
													<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												</a>
												{{--delete button--}}
												<a href="{{url('/academics/subject/group/delete/'.$subjectGroup->id)}}" onclick="confirm('Are you sure to delete this item?')" title="Delete">
													<i class="fa fa-trash-o" aria-hidden="true"></i></a>
											</td>
										</tr>
										@php $x += 1; @endphp
									@endforeach
									</tbody>
								</table>
							@else
								<p class="text-center bg-success text-bold text-success">No subject group found</p>
							@endif
						</div>
					</div>
				</div>
			</div>
			<!-- /.box-->
		</section>
	</div>

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
{{----}}
@section('scripts')
	<script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>
	<script type="text/javascript">
        $(document).ready(function(){
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });
        });
	</script>
@endsection

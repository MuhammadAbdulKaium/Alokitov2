
@extends('setting::manage-user-rights.index')

{{--page styles--}}
@section('page-styles')

@endsection

{{--page content--}}
@section('page-content')
	<div class="row">
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-12">
					<p class="pull-right flip">
						<a class="btn btn-success" href="/setting/rights/role/create" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Create Role</a>
					</p>
				</div>
			</div>
			@if($allRole->count() > 0)
				<table class="table table-striped table-bordered">
					<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Display Name</th>
						<th>Description</th>
						<th class="text-center">Status</th>
						<th class="text-center">Action</th>
					</tr>
					</thead>
					<tbody>
					@php $x=1; @endphp
					@foreach($allRole as $role)
						<tr>
							<td>{{$x++}}</td>
							<td><a href="#">{{$role->name}}</a></td>
							<td>{{$role->display_name}}</td>
							<td>{{$role->description}}</td>
							<td class="text-center">
								<a data-key="status" data-id="{{$role->id}}" href="{{url('#')}}" class="menu-status-delete" title="Status">
						<i id="role_status_{{$role->id}}"class="{{$role->status==0?'fa fa-ban text-red':'fa fa-check text-green'}}" aria-hidden="true"></i>
					</a>
							</td>
							<td class="text-center">
								<a href="{{url('/setting/rights/role/edit/'.$role->id)}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
									<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								</a>
								<a href="{{url('/setting/rights/role/delete/'.$role->id)}}" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');">
									<i class="fa fa-trash-o" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@php $x=($x++); @endphp
					@endforeach
					</tbody>
				</table>
			@else
				<div class="alert-warning alert-auto-hide alert fade in" style="opacity: 474.119;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h6><i class="fa fa-warning"></i> No records found. </h6>
				</div>
			@endif
		</div>
		{{--role gorup--}}
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-12">
					<p class="pull-right flip">
						<a class="btn btn-success" href="/setting/rights/role/create" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Create Role Group</a>
					</p>
				</div>
			</div>
			@if($allRole->count() > 0)
				<table class="table table-striped table-bordered">
					<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						{{--<th>Display Name</th>--}}
						<th>Description</th>
						{{--<th class="action-column">&nbsp;</th>--}}
						<th class="text-center">Action</th>
					</tr>
					</thead>
					<tbody>
					@php $i=1; @endphp
					@foreach($allRole as $role)
						<tr>
							<td>{{$i++}}</td>
							<td><a href="#">{{$role->name}}</a></td>
							{{--<td>{{$role->display_name}}</td>--}}
							<td>{{$role->description}}</td>
							{{--<td class="text-center"><a href="#" title="View assigned users"><i class="fa fa-users"></i></a>  </td>--}}
							<td class="text-center">
								<a href="{{url('/setting/rights/role/edit/'.$role->id)}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
									<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								</a>
								<a href="{{url('/setting/rights/role/delete/'.$role->id)}}" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');">
									<i class="fa fa-trash-o" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@php $i=($i++); @endphp
					@endforeach
					</tbody>
				</table>
			@else
				<div class="alert-warning alert-auto-hide alert fade in" style="opacity: 474.119;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h6><i class="fa fa-warning"></i> No records found. </h6>
				</div>
			@endif
		</div>
	</div>
@endsection

{{--page scripts--}}
@section('page-script')
	<script>

	</script>
@endsection

@extends('setting::manage-user-rights.index')

{{--page content--}}
@section('page-content')
	<div class="row">
		{{--module list--}}
		<div class="col-md-7">
			{{--<p class="text-center text-bold bg-aqua-gradient">Module List</p>--}}
			<div class="row">
				<div class="col-md-12">
					<p class="pull-right flip">
						<a class="btn btn-success" href="{{url('/setting/rights/module/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Add Sub-Module</a>
					</p>
				</div>
			</div>
			@if($allModule->count()>0)
				@foreach($allModule as $module)
				@php $subModules = $module->subModules('all'); @endphp
				@if($subModules->count()<=0)@continue @endif
				<p class="text-center bg-blue-gradient text-bold"> {{$module->name}} (Module)</p>
				<table class="table table-responsive table-bordered table-striped">
					<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Icon</th>
						<th>Name</th>
						<th>Route</th>
						<th class="text-center">Status</th>
						<th class="text-center">Action</th>
					</tr>
					</thead>
					<tbody>
					@php $x=1; @endphp
					@foreach($subModules as $subMmodule)
						<tr>
							<td class="text-center">{{$x++}}</td>
							<td class="text-center"><i class="{{$subMmodule->icon}}" aria-hidden="true"></i></td>
							<td>{{$subMmodule->name}}</td>
							<td>#{{$subMmodule->route}}</td>
							<td class="text-center">
								<a href="/setting/rights/module/status/{{$subMmodule->id}}" title="Status" onclick="return confirm('Are you sure to change status?');">
									<i class="{{$subMmodule->status==0?'fa fa-ban text-red':'fa fa-check text-green'}}" aria-hidden="true"></i>
								</a>
							</td>
							<td class="text-center">
								<a href="/setting/rights/module/edit/{{$subMmodule->id}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
									<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								</a>
								<a href="/setting/rights/module/delete/{{$subMmodule->id}}" title="Delete" onclick="return confirm('Are you sure to delete this item?');">
									<i class="fa fa-trash-o" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@php $x=($x++); @endphp
					@endforeach
					</tbody>
				</table>
				@endforeach

			@else
				<div class="alert-warning alert-auto-hide alert fade in" style="opacity: 474.119;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h6><i class="fa fa-warning"></i> No records found. </h6>
				</div>
			@endif
		</div>
		{{--allModule--}}
		<div class="col-md-5">
			<div class="row">
				<div class="col-md-12">
					<p class="pull-right flip">
						<a class="btn btn-success" href="{{url('/setting/rights/module/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Add Module</a>
					</p>
				</div>
			</div>
			@if($allModule->count() > 0)
				<table class="table table-striped table-bordered">
					<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Icon</th>
						<th>Name</th>
						<th>Route</th>
						<th class="text-center">Status</th>
						<th class="text-center">Action</th>
					</tr>
					</thead>
					<tbody>
					@php $i=1; @endphp
					@foreach($allModule as $module)
						<tr>
							<td class="text-center">{{$i++}}</td>
							<td class="text-center"><i class="{{$module->icon}}" aria-hidden="true"></i></td>
							<td>{{$module->name}}</td>
							<td>#{{$module->route}}</td>
							<td class="text-center">
								<a href="/setting/rights/module/status/{{$module->id}}" title="Status" onclick="return confirm('Are you sure to change status?');">
									<i class="{{$module->status==0?'fa fa-ban text-red':'fa fa-check text-green'}}" aria-hidden="true"></i>
								</a>
							</td>
							<td class="text-center">
								<a href="/setting/rights/module/edit/{{$module->id}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
									<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								</a>
								<a href="/setting/rights/module/delete/{{$module->id}}" title="Delete" onclick="return confirm('Are you sure to delete this item?');">
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
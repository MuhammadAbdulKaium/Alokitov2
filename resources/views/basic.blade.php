@extends('layouts.secure')

{{-- Web site Title --}}
@section('title')
	{{ $title }}
@stop

{{-- Content --}}
@section('content')
	<div class="page-header clearfix">
		<div class="pull-right">
			<a href="{{ url($type.'/create') }}" class="btn btn-sm btn-primary">
				<i class="fa fa-plus-circle"></i> {{ trans('table.new') }}</a>
		</div>
	</div>
	<table id="data" class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>{{ trans('parent.full_name') }}</th>
				<th>{{ trans('parent.student_full_name') }}</th>
				<th>{{ trans('table.actions') }}</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
@stop

{{-- Scripts --}}
@section('scripts')

@stop

@extends('layouts.master')
{{--styles--}}
@section('styles')
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<!-- page styles -->
	@yield('page-styles')
@endsection

<!-- page content -->
@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-plus-square"></i> Manage User Rights
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="/academics/">Setting</a></li>
				<li class="active">Manage User Rights</li>
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
					<div>
						<ul class="nav-tabs margin-bottom nav">
							<li class="{{$page == "role"?'active':''}}"><a href="/setting/rights/role">Roles</a></li>
							<li class="{{$page == "permission"?'active':''}}"><a href="/setting/rights/permission">Permission</a></li>
							<li class="{{$page == "module"?'active':''}}"><a href="/setting/rights/module">Module</a></li>
							<li class="{{$page == "menu"?'active':''}}"><a href="/setting/rights/menu">Menu</a></li>
							<li class="{{$page == "setting"?'active':''}}"><a href="/setting/rights/setting">Setting</a></li>
							<li class="{{$page == "user-permission"?'active':''}}"><a href="/setting/rights/user-permission">User Permission</a></li>
						</ul>
						<!-- page content div -->
						@yield('page-content')
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

@section('scripts')
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

            $('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

        });
	</script>
	{{--page scripts--}}
	@yield('page-script')
@endsection
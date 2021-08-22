
@extends('admin::layouts.master')

@section('styles')
	<!-- DataTables -->
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-th-list"></i> Manage | <small>Bill</small>
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Bill Management</a></li>
				<li class="active">Institute Payment List</li>
			</ul>
		</section>
		<section class="content">
			@if(Session::has('success'))
				<div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
				</div>
			@elseif(Session::has('alert'))
				<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
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
							<ul id="assessmentNav" class="nav-tabs margin-bottom nav">
								<li @if($page == "bill-info") class="active" @endif id="tab-setup"><a href="/admin/bills/bill-info">Billing Info</a></li>
								<li @if($page == "manage-bill") class="active" @endif id="tab-assessment"><a href="/admin/bills/manage-bill">Bill Management</a></li>
								<li @if($page == "subscription-management") class="active" @endif id="tab-assessment"><a href="/admin/bills/subscription-management">Subscription Management</a></li>
								<li @if($page == "bill-reports") class="active" @endif id="tab-reportcard"><a href="/admin/bills/bill-reports">Reports</a></li>
							</ul>
							<!-- page content div -->
							@yield('page-content')
						</div>
					</div>
					<div class="box box-solid">

						<div class="et">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-search"></i> Institute Payment List</h3>
								<div class="box-tools">
									<a class="btn btn-success btn-sm" href="{{url('admin/bill/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
										<i class="fa fa-plus-square"></i> Add Bill
									</a>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<table id="example1" class="table table-bordered table-responsive table-striped text-center">
										<thead>
										<tr>
											<th>#</th>
											<th>Institute Name</th>
											@for($monthNum=1; $monthNum<=12; $monthNum++)
												{{--find month name--}}
												@php $monthName = date("F", mktime(0, 0, 0, $monthNum, 10)); @endphp
												{{--print month name--}}
												<th width="5%" class="text-center">{{$monthName}}</th>
											@endfor
										</tr>
										</thead>

										<tbody>
										{{--institute list looping--}}
										@foreach($instituteList as $index=>$instituteProfile)
											<tr>
												<td>{{$index+1}}</td>
												<th width="25%">{{$instituteProfile->institute_name}}</th>
												{{--institute bill list--}}
												@php $instBillList = array_key_exists($instituteProfile->id, $billArrayList)?$billArrayList[$instituteProfile->id]:[]; @endphp

												@for($monthId=1; $monthId<=12; $monthId++)
													@if(array_key_exists($monthId, $instBillList))
														{{--institute month bill profile--}}
														@php $instMonthBill = (object)$instBillList[$monthId]; @endphp
														<td>
															<i class="fa fa-check-circle {{$instMonthBill->status==1?'text-green':'text-red'}} text-bold"></i>
														</td>
													@else
														<td><i class="fa fa-times-circle text-red text-bold"></i></td>
													@endif
												@endfor
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>


			{{--global modal--}}
			<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" id="modal-content">
						<div class="modal-body" id="modal-body">
							<div class="loader">
								<div class="es-spinner">
									<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('scripts')
	<!-- DataTables -->
	<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
	<!-- datatable script -->
	<script>

        jQuery(document).ready(function () {

            $("#example2").DataTable();
            $('#example1').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": true
            });

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

        });

	</script>
@endsection

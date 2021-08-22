@extends('communication::layouts.master')
@section('section-title')
	<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
	<section class="content-header">
		<h1>
			<i class="fa fa-th-list"></i> Manage |<small> Events</small>
		</h1>
		<ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
			<li><a href="#">Communication</a></li>
			<li class="active">Events</li>
		</ul>
	</section>
@endsection
<!-- page content -->
@section('page-content')

	<div class="box box-solid">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-search"></i> Event List</h3>
			<div class="box-tools">
				<a class="btn btn-success btn-sm" href="{{url('/communication/event/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square"></i> Create Event</a>
			</div>
		</div>
		<div class="box-body">
			@if($eventList->count()>0)
				<table id="example1" class="table table-striped table-bordered">
					<thead>
					<tr>
						<th class="text-center">#</th>
						<th width="20%">Title</th>
						<th>Detail</th>
						<th width="10%" class="text-center">User Type</th>
						<th width="10%" class="text-center">Start Time</th>
						<th width="10%" class="text-center">End Time</th>
						<th class="text-center">Status</th>
						<th class="text-center">Action</th>
					</tr>
					</thead>
					<tbody>
					@php $i=1; @endphp
					@foreach($eventList as $event)
						<tr id="row_id_{{$event->id}}">
							<td class="text-center">{{$i}}</td>
							<td>{{$event->title}}</td>
							<td>{{$event->detail}}</td>
							@php $userType = $event->user_type; @endphp
							<td class="text-center">
								{{--user type column--}}
								@if($userType=='1')
									General
								@elseif($userType=='2')
									Employee
								@elseif($userType=='3')
									Student
								@elseif($userType=='4')
									Parent
								@else
									Not Found
								@endif
							</td>
							<td class="text-center">{{date('d-M-Y H:i:s', strtotime($event->start_date_time))}}</td>
							<td class="text-center">{{date('d-M-Y H:i:s', strtotime($event->end_date_time))}}</td>
							<td class="text-center">
								<a style="cursor: pointer" onclick="checkStatus({{$event->id}})" title="Status">
									<i id="event_status_{{$event->id}}" class="{{$event->status==0?'fa fa-ban text-red':'fa fa-check text-green'}}" aria-hidden="true"></i>
								</a>
							</td>
							<td class="text-center">
								{{--edit button--}}
								<a href="{{url('/communication/event/'.$event->id.'/edit')}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">
									<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								</a>
								{{--delete button--}}
								<a href="javascript:checkDelete({{$event->id}});" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
							</td>
						</tr>
						@php $i +=1; @endphp
					@endforeach
					</tbody>
				</table>


				{{--paginate--}}
				<div class="text-center">
					{{$eventList->render()}}
				</div>
			@else
				<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 406.049;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h5><i class="fa fa-warning"></i> No result found. </h5>
				</div>
			@endif
		</div>
	</div><!-- /.box-->

	{{--global modal--}}
	<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content" id="modal-content">
				<div class="modal-body">
					<div class="loader">
						<div class="es-spinner"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	{{--datatable scripts--}}
	<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
	<script>

        $(document).ready(function () {

//            $("#example1").DataTable();
//            $('#example2').DataTable({
//                "paging": true,
//                "lengthChange": false,
//                "searching": true,
//                "ordering": false,
//                "info": true,
//                "autoWidth": false
//            });


        });

        // status change function
        function checkStatus(id) {
            if (confirm('Are You Sure to change status?')) {
                var event_id = id;
                var token = '{{csrf_token()}}';

                $.ajax({
                    type: "POST",
                    url: '/communication/event/status',
                    data: {'event_id':event_id, '_token':token},
                    datatype: 'application/json',

                    success: function(result) {
                        // checking
                        if(result.status=='success'){
                            var event_status = $('#event_status_'+event_id);
                            // checking
                            if(result.event_status==1){
                                event_status.removeClass('fa-ban text-red');
                                event_status.addClass('fa-check text-green');
                            }else{
                                event_status.removeClass('fa-check text-green');
                                event_status.addClass('fa-ban text-red');
                            }
                        }else{
                            alert(result.msg);
                        }
                    }
                });
            }
        }
        // delete function
        function checkDelete(id) {
            if (confirm('Are You Sure to Delete This Item?')) {
                var event_id = id;
                var token = '{{csrf_token()}}';

                $.ajax({
                    type: "POST",
                    url: '/communication/event/delete',
                    data: {'event_id':event_id, '_token':token},
                    datatype: 'application/json',

                    success: function(result) {
                        // checking
                        if(result.status=='success'){
                            $('#row_id_'+event_id).remove();
                        }else{
                            alert(result.msg);
                        }
                    }
                });
            }
        }
	</script>
@endsection

@section('page-script')
	$('#notice-date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});


@endsection


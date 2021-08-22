@if($applicantEnquiry->count()>0)
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<div class="box box-solid">
		<div class="box-body table-responsive" style="overflow-x:inherit">
			<div>
				<table id="example2" class="table table-striped table-bordered text-center">
					<thead>
					<tr>
						<th class="text-center" width="10px">#</th>
						<th class="text-center" width="100px">Applied Date</th>
						<th class="text-center" width="8%">App. No</th>
						<th class="text-center">Name</th>
						<th class="text-center">Academic Details</th>
						{{--<th>Email</th>--}}
						<th class="text-center" width="100px">Payment Status</th>
						<th class="text-center" width="120px">Applicant Status</th>
						{{--<th>Action</th>--}}
					</tr>
					</thead>
					<tbody>
					@foreach($applicantEnquiry as $index=>$applicant)
						<tr>
							<td>{{$index+1}}</td>
							{{--<td><img class="img-circle img-sm" src="#" alt="NA"></td>--}}
							<td>{{date('d M, Y', strtotime($applicant->created_at))}}</td>
							<td class="text-center" style="width:25px">{{$applicant->application_no}}</td>
							<td>
								<a href="{{url('/admission/application/'.$applicant->applicant_id)}}">{{$applicant->name}}</a>
							</td>
							<td class="text-center">
								{{$applicant->academicYear()->year_name}} /
								{{$applicant->academicLevel()->level_name}}
								({{$applicant->batch()->batch_name}}{{$applicant->section()?" - ".$applicant->section()->section_name:''}})
							</td>
{{--							<td class="text-center">{{$applicant->email}}</td>--}}
							@php $paymentStatus = $applicant->payment_status; @endphp
							<td class="text-center"><span class="label {{$paymentStatus=='1'?'label-success':'label-danger'}}">{{$paymentStatus=='1'?'Paid':'Unpaid'}}</span></td>
							<td class="text-center">
								{{--Applicant status--}}
								@php $applicationStatus = $applicant->application_status; @endphp
								@if($applicationStatus==1)
									<span class="label label-info">Active</span>
								@elseif($applicationStatus==2)
									<span class="label label-primary">Waiting</span>
								@elseif($applicationStatus==3)
									<span class="label label-danger">Disapproved</span>
								@elseif($applicationStatus==4)
									<span class="label label-success">Approved</span>
								@else
									<span class="label label-primary">Pending</span>
								@endif
							</td>
							{{--<td class="text-center">--}}
								{{--<div class="btn-group pull-right" style="display:flex;">--}}
									{{--<button id="w4" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">--}}
										{{--Action <span class="caret"></span>--}}
									{{--</button>--}}
									{{--<ul id="w5" class="dropdown-menu dropdown-menu-right">--}}
										{{--<li>--}}
											{{--<a href="#" data-confirm="Are you sure you want to delete this item?" data-method="post" tabindex="-1">Delete</a>--}}
										{{--</li>--}}
										{{--<li>--}}
											{{--<a href="#" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" tabindex="-1">Letter</a>--}}
										{{--</li>--}}
									{{--</ul>--}}
								{{--</div>--}}
							{{--</td>--}}
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div><!-- /.box-body -->
	</div><!-- /.box-->
@else
	<div class="alert-auto-hide alert alert-warning alert-dismissable" style="opacity: 257.188;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-info-circle"></i> No record found.
	</div>
@endif

<!-- DataTables -->
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- datatable script -->
<script>
    $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
	        "pageLength": 50
        });
    });
</script>
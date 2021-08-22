@if($applicantEnquiry->count()>0)
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<div class="box box-solid">
		<div class="box-body" style="overflow-x:inherit">
			<table id="example1" class="table table-striped table-bordered table-responsive text-center">
				<thead>
				<tr>
					<th>#</th>
					<th>Photo</th>
					<th width="8%">App. No</th>
					<th>Name</th>
					<th>Email</th>
					<th>Payment Status</th>
					<th>Applied Date</th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
				@foreach($applicantEnquiry as $index=>$applicant)
					<tr>
						<td>{{$index+1}}</td>
						{{--get applicant photo--}}
						@php $profilePhoto = $applicant->document('PROFILE_PHOTO'); @endphp
						<td>
							{{--set applicant photo--}}
							<img class="profile-user-img img-responsive img-circle" src="{{URL::asset($profilePhoto?$profilePhoto->doc_path.'/'.$profilePhoto->doc_name:'assets/users/images/user-default.png')}}" alt="No Image" style="height:30px; width: 30px">
						</td>
						<td class="text-center" style="width:25px">{{$applicant->application_no}}</td>
						<td>
							<a href="{{url('/admission/application/'.$applicant->applicant_id)}}">
								{{$applicant->name}}
							</a>
						</td>
						<td>{{$applicant->email}}</td>
						{{--<td class="text-center">{{$applicant->academicYear()->year_name}}</td>--}}
						{{--<td class="text-center">--}}
							{{--{{$applicant->academicLevel()->level_name}}--}}
							{{--({{$applicant->batch()->batch_name}})--}}
						{{--</td>--}}
						<td class="text-center">
							@if($applicant->payment_status==1)
								<i id="payment_status_{{$applicant->applicant_id}}" class="label label-success">Paid</i>
								<a id="payment_invoice_{{$applicant->applicant_id}}" href="{{url('/admission/fees/invoice/'.$applicant->applicant_id)}}">
									<i class="fa fa-download" aria-hidden="true"></i>
								</a><br/>
								<i style="font-size: 10px;" class="text-info">({{date('d M, Y', strtotime($applicant->fees()->created_at))}})</i>
							@else
								<i id="payment_status_{{$applicant->applicant_id}}" class="label label-danger">Not Paid</i>
								<a id="payment_invoice_{{$applicant->applicant_id}}" class="hide" href="{{url('/admission/fees/invoice/'.$applicant->applicant_id)}}">
									<i class="fa fa-download" aria-hidden="true"></i>
								</a><br/>
								<i style="font-size: 10px;" id="payment_date_{{$applicant->applicant_id}}" class="text-info hide"></i>
							@endif

						</td>
						<td class="text-center">{{date('d M, Y', strtotime($applicant->created_at))}}</td>
						<td class="text-center">
							@if($applicant->payment_status==0)
								<a class="btn btn-primary text-bold" id="payment_action_{{$applicant->applicant_id}}" href="{{url('/admission/fees/show/'.$applicant->applicant_id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"> Add Fees </a>
							@else
								<a class="btn btn-success text-bold" id="payment_action_{{$applicant->applicant_id}}" href="{{url('/admission/fees/show/'.$applicant->applicant_id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Fees Details</a>
							@endif
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>

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
        $("#example2").DataTable();
        $('#example1').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "pageLength": 50
        });
    });
</script>
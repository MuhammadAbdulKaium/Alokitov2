
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<div class="box box-solid">
	<div class="box-body table-responsive" style="overflow-x:inherit">
		<div>
			@if($applicantList->count()>0)
				<table id="example2" class="table table-striped table-bordered text-center">
					<thead>
					<tr>
						<th>#</th>
						<th>Photo</th>
						<th>App. ID</th>
						<th>Roll / Reg.</th>
						<th>App. Date</th>
						<th>Student Name</th>
						<th>Father's Name</th>
						<th>Gender</th>
						<th>Class</th>
						{{--<th>Mobile</th>--}}
						<th>Status</th>
						{{--<th>Applicant Status</th>--}}
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					@foreach($applicantList as $index=>$applicant)
						<tr>
							<td>{{($index+1)}}</td>
							<td>
								{{--checking std photo--}}
								@if($applicant->std_photo)
									<img src="{{$applicant->std_photo}}" width="45px" height="45px">
								@else
									<img src="{{asset('assets/users/images/user-default.png')}}" width="45px" height="45px">
								@endif
							</td>
							<td>{{$applicant->a_no}} <br/> ({{$applicant->password_rand}})</td>
							<td>{{$applicant->exam_roll}} <br/> {{$applicant->exam_reg}}</td>
							<td>
								{{$applicant->created_at->format("d M, Y")}}<br/>
								({{$applicant->created_at->format("H:i:s a")}})
							</td>
							<td>
								{{$applicant->s_name}} <br/>
								{{$applicant->s_name_bn}}
							</td>
							<td>
								{{$applicant->f_name}} <br/>
								{{$applicant->f_name_bn}}
							</td>
							<td>{{$applicant->gender==0?'Male':'Female'}}</td>
							{{--batch --}}
							@php
								$myBatch = $applicant->batch();
								// checking batch division
								if($division = $myBatch->get_division()){
									$divisionName = $division->name;
								}else{
									$divisionName = '';
								}
							@endphp

							<td>
								{{$myBatch->batch_name}} <br/>
								@if($myBatch->get_division()) ({{$divisionName}}) @endif
							</td>
							{{--<td>{{$applicant->s_mobile}}</td>--}}
							<td>{{$applicant->p_status==0?'Un-Paid':'Paid'}}</td>
							{{--<td class="text-green">{{$applicant->a_status==0?'Pending':'Active'}}</td>--}}
							<td>
								<a href="{{url('/admission/hsc/applicant/'.$applicant->id)}}" target="_blank">View</a>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			@else
				<div class="alert-auto-hide alert alert-info text-center" style="opacity: 257.188;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<i class="fa fa-info-circle"></i> No record found.
				</div>
			@endif
		</div>
	</div><!-- /.box-body -->
</div><!-- /.box-->

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
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "pageLength": 50
        });
    });
</script>
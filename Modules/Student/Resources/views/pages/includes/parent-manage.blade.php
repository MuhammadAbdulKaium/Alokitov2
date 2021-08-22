@if($studentList->count()>0)
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<div class="box box-solid">
		<div class="et">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-search"></i> View Parent List</h3>
			</div>
		</div>
		<div class="box-body table-responsive">
			<div class="box-header">
			</div>
			<div class="box-body">
				<table id="example1" class="table table-bordered text-center">
					<thead>
					<tr>
						<th>#</th>
						<th>Student Name</th>
						<th>Parent List</th>
					</tr>
					</thead>
					<tbody>
					@php $i = 1; @endphp
					@foreach($studentList as $student)
						@php $studentProfile = $student->student(); @endphp
						@php $parentList = $studentProfile->myGuardians(); @endphp
						<tr>
							<td>{{$i++}}</td>
							<td>
								<a href="/student/profile/personal/{{$student->std_id}}">
									{{$student->first_name." ".$student->middle_name." ".$student->last_name}}
								</a>
							</td>
							<td>
								@if($parentList->count()>0)
									@foreach($parentList as $parent)
										@php $guardians = $parent->guardian(); @endphp
										<div class="btn btn-default" style="margin-right: 5px">
											<a>{{$guardians->first_name." ".$guardians->last_name}}</a> {{$parent->is_emergency=='1'?"(emergency)":""}}
											<br>({{$guardians->email}})</div>
									@endforeach
								@else
									<i class="btn btn-danger"> No Parent(s) </i>
								@endif
							</td>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
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
        });
	</script>
@else
	<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h6><i class="fa fa-warning"></i></i> No result found. </h6>
	</div>
@endif
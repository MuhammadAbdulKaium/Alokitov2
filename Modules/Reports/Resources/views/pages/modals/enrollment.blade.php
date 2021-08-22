{{--datatable style sheet--}}
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<div class="row">
	<div class="col-md-12">
		<p class="bg-blue-active text-bold text-center" style="padding: 5px">
			Enrollment History
			@if($enrollHistory->count()>0)
				<span style="padding: 5px; cursor: pointer" id="download-enroll-history" class="pull-right label label-success text-bold">Download</span>
			@endif
		</p>
		<table id="example1" class="table table-bordered table-responsive table-striped text-center">
			<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Academic Year</th>
				<th>Level</th>
				<th>Batch</th>
				<th>Section</th>
				<th>Enroll Status</th>
			</tr>
			</thead>
			<tbody>
			{{--enroll list checking--}}
			@if($enrollHistory->count()>0)
				{{--enroll list looping--}}
				@foreach($enrollHistory as $index=>$enroll)
					{{--student porfile--}}
					@php $stdProfile = (object) $enroll->enroll()->student(); @endphp
					<tr>
						<td>{{($index+1)}}</td>
						<td>{{$stdProfile->first_name.' '.$stdProfile->middle_name.' '.$stdProfile->last_name}}</td>
						<td>{{$enroll->academicsYear()->year_name}}</td>
						<td>{{$enroll->level()->level_name}}</td>
						<td>{{$enroll->batch()->batch_name}}</td>
						<td>{{$enroll->section()->section_name}}</td>
						<td>{{$enroll->batch_status}}</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td colspan="7">No Records found.</td>
				</tr>
			@endif
			</tbody>
		</table>
	</div>
</div>
{{--dataTable--}}
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>

<script>
    $(document).ready(function () {

        // dataTable
        $("#example1").DataTable();

        $('#download-enroll-history').click(function () {
            // academic details
	        var academic_year = $('#academic_year').val();
	        var academic_level = $('#academic_level').val();
	        var batch = $('#batch').val();
	        var section = $('#section').val();
	        var enroll_type = $('#enroll_type').val();

            // dynamic form
           var enrollment_history_table =  $('<form id="enrollment_history_table" action="/reports/student/enrollment/history" method="POST" target="_blank"></form>');
           // append csrf token
            enrollment_history_table.append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>');
            // checking academic_year
	        if(academic_year) enrollment_history_table.append('<input type="hidden" name="academic_year" value="'+academic_year+'"/>');
			// checking academic_level
	        if(academic_level) enrollment_history_table.append('<input type="hidden" name="academic_level" value="'+academic_level+'"/>');
	        // checking batch
	        if(batch) enrollment_history_table.append('<input type="hidden" name="batch" value="'+batch+'"/>');
	        // checking section
	        if(section) enrollment_history_table.append('<input type="hidden" name="section" value="'+section+'"/>');
	        // checking enroll_type
	        if(enroll_type) enrollment_history_table.append('<input type="hidden" name="enroll_type" value="'+enroll_type+'"/>');
	        // submit enrollment_history_table
            enrollment_history_table.append('<input type="hidden" name="request_type" value="download"/>').appendTo('body').submit();
            // remove form from the body
            $('#enrollment_history_table').remove();
        });
    });
</script>


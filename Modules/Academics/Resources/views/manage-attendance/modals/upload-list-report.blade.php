{{--datatable style sheet--}}
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<h4 class="text-center text-bold bg-blue-gradient" style="line-height: 28px">
	Uploaded Attendance List
	@if(count($attendanceArrayList)>0)
		<span data-id="pdf" class="pull-right label label-success download-upload-attendance-report" style="margin: 5px; cursor: pointer">Download PDF</span>
		<span data-id="xlsx" class="pull-right label label-success download-upload-attendance-report" style="margin: 5px; cursor: pointer">Download Excel</span>
	@endif
</h4>
<div class="col-md-12">
	@if(count($attendanceArrayList)>0)

		{{--{{dd($attendanceArrayList)}}--}}

		<table id="example1" class="table table-bordered table-responsive table-striped text-center">
			<thead>
			<tr class="bg-gray">
				<th>GR. NO</th>
				<th>Full Name</th>
				<th>Class (Section)</th>
				<th>Attendance Date</th>
				<th>Entry Date Time</th>
				<th>Out Date Time</th>
				<th>Attendance Type</th>
			</tr>
			</thead>
			<tbody class="text-bold">
			@php $i=1; @endphp
			@foreach ($attendanceArrayList as $key=>$attendance)
				{{--checking report type--}}
				@if($reportType=='ALL')
					{{--ALL type statements--}}
				@elseif ($reportType=='PRESENT')
					@if($attendance['att_type'] == 'ABSENT') @continue @endif
				@elseif ($reportType=='LATE_PRESENT')
					@if($attendance['att_type'] == 'PRESENT' || $attendance['att_type'] == 'ABSENT') @continue @endif
				@elseif ($reportType=='ABSENT')
					@if($attendance['att_type'] != 'ABSENT') @continue @endif
				@endif
				{{--student profile--}}
				@php $stdProfile = $attendance['std_profile'] @endphp
				<tr>
					<td> {{$stdProfile->gr_no}} </td>
					<td> {{$stdProfile->name}} </td>
					<td> {{$stdProfile->enroll}} </td>
					<td> {{date('d M, Y', strtotime($attendance['att_date']))}} </td>
					<td class="{{$attendance['att_type']=='ABSENT'?'text-danger':''}}"> {{($attendance['entry_date_time'])}} </td>
					<td class="{{$attendance['att_type']=='ABSENT'?'text-danger':''}}"> {{($attendance['out_date_time'])}} </td>
					<td class="{{$attendance['att_type']=='ABSENT'?'text-danger':''}}"> {{$attendance['att_type']}} </td>
				</tr>
				@php $i+=1; @endphp
			@endforeach
			</tbody>
		</table>
	@else
		<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in text-center">
			<h5 class="text-bold"><i class="fa fa-warning"></i> No records found </h5>
		</div>
	@endif
</div>

<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script>
    $(document).ready(function () {

        // dataTable
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });

        $('.download-upload-attendance-report').click(function () {

            var level = $('#academic_level').val();
            var batch = $('#batch').val();
            var section = $('#section').val();
            var report_type = $('#report_type').val();
            var attendance_date = $('#attendance_date').val();
            var request_type= $(this).attr('data-id');
            // dynamic form
            $('<form id="upload_att_report_download_form" action="/academics/upload/attendance/report" method="POST" target="_blank"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="report_type" value="'+report_type+'"/>')
                .append('<input type="hidden" name="attendance_date" value="'+attendance_date+'"/>')
                .append('<input type="hidden" name="request_type" value="'+request_type+'"/>').appendTo('body');
            // checking
            if(level) $('#upload_att_report_download_form').append('<input type="hidden" name="level_id" value="'+level+'"/>');
            if(batch) $('#upload_att_report_download_form').append('<input type="hidden" name="class_id" value="'+batch+'"/>');
            if(section) $('#upload_att_report_download_form').append('<input type="hidden" name="section_id" value="'+section+'"/>');
            // submit
            $('#upload_att_report_download_form').submit();
            // remove form from the body
            $('#upload_att_report_download_form').remove();
        });
    });
</script>

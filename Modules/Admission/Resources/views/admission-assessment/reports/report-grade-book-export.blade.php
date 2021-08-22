<html>
	<head>
		<style type="text/css">
			table>thead>tr>th, table>tbody>tr>td{ text-align: center; height: 20px; }
		</style>
	</head>

	<body>
		<table>
			<thead>
			<tr>
				<th>Applicant ID</th>
				<th>Grade ID</th>
				<th>Applicant Name</th>
				<th>Class</th>
				<th>Application NO.</th>
				<th>Exam Marks</th>
				<th>Grade Marks</th>
			</tr>
			</thead>
			<tbody>
				@if($applicantProfiles->count()>0)
					@foreach($applicantProfiles as $applicantProfile)
						{{--checking applicant payment status--}}
						@if($applicantProfile->payment_status==0)  @continue @endif
						{{--grade details--}}
						@php $gradeDetails = $applicantProfile->grade(); @endphp
						{{--exam details--}}
						@php $examDetails = $applicantProfile->examDetails(); @endphp
						{{--tabel row--}}
						<tr>
							<td>{{$applicantProfile->applicant_id}}</td>
							<td>{{$gradeDetails?$gradeDetails->id:'0'}}</td>
							<td>{{$applicantProfile->name}}</td>
							<td>{{$applicantProfile->batch()->batch_name}}</td>
							<td>{{$applicantProfile->application_no}}</td>
							<td>{{$examDetails?$examDetails->exam_marks:'0'}}</td>
							<td>{{$gradeDetails?$gradeDetails->applicant_grade:''}}</td>
						</tr>
					@endforeach
				@else
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				@endif
			</tbody>
		</table>
	</body>
</html>
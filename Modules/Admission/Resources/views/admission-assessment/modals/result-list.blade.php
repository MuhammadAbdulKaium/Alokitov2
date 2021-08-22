@if($applicantResultSheet->count()>0)
	<div class="box-header">
		<div class="row">
			<div class="col-sm-6"> </div>
			<div class="col-sm-6">
				<button  class="btn btn-primary pull-left download-passed-list" type="submit">Download
				</button>
			</div>
		</div>
	</div>

	<table id="myTable" class="table table-striped table-bordered table-responsive">
		<thead>
		<tr>
			<th>#</th>
			<th>
				<img class="profile-user-img img-responsive img-circle" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="height:30px; width: 30px">
			</th>
			<th style="width: 120px" class="text-center"><a>Application No</a></th>
			<th><a>Name</a></th>
			<th class="text-center"><a>Academic Details</a></th>
			<th class="text-center"><a>Exam Date</a></th>
			<th class="text-center"><a>Exam Marks</a></th>
			<th class="text-center"><a>Exam Result</a></th>
			<th class="text-center"><a>Merit Position</a></th>
		</tr>
		</thead>
		<tbody>
		@php $i=1; @endphp
		@foreach($applicantResultSheet as $applicantResult)
			{{--applicant ID--}}
			@php $applicant = $applicantResult->application(); @endphp
			{{--table row--}}
			<tr>
				<td>{{$i++}}</td>
				{{--get applicant photo--}}
				@php $profilePhoto = $applicantResult->document('PROFILE_PHOTO'); @endphp
				<td>
					{{--set applicant photo--}}
					<img class="profile-user-img img-responsive img-circle" src="{{URL::asset($profilePhoto?$profilePhoto->doc_path.'/'.$profilePhoto->doc_name:'assets/users/images/user-default.png')}}" alt="No Image" style="height:30px; width: 30px">
				</td>
				<td class="text-center" style="width:25px">{{$applicant->application_no}}</td>
				<td>
					@php $applicantInfo = $applicant->personalInfo(); @endphp
					<a href="{{url('/admission/application/'.$applicantResult->applicant_id)}}">
						{{$applicantInfo->std_name}}
					</a>
				</td>
				<td class="text-center">
					{{$applicantResult->academicYear()->year_name}} / {{$applicantResult->academicLevel()->level_name}} ({{$applicantResult->batch()->batch_name}})
				</td>
				@php $examDetails = $applicantResult->examDetails(); @endphp
				<td class="text-center">
					{{date('d M, Y', strtotime($examDetails->exam_date))}}
				</td>
				@php $examGrade = $applicantResult->grade(); @endphp
				<td class="text-center"> {{$examGrade->applicant_grade." / ".$examDetails->exam_marks}} </td>
				<td class="text-center">
					@if($applicantResult->applicant_exam_result=='0')
						<p class="label label-danger text-bold">Failed</p>
					@else
						<p class="label label-success text-bold">Passed</p>
					@endif
				</td>
				<td class="text-center">{{$applicantResult->applicant_merit_position}}</td>
			</tr>
			@php $i = ($i++); @endphp
		@endforeach
		</tbody>
	</table>
@else
	<div id="generate_applicant_result_container" class="box-body text-center">
		<p id="generate_applicant_result" class="btn btn-primary">Generate Result</p>
	</div>
@endif

<script>
    $(document).ready(function () {
        // download result sheet
        $('.download-passed-list').click(function () {
            // dynamic html form
            $('<form id="all_applicant_result_list_download_form" action="/admission/assessment/result/download" target="_blank" method="POST"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="academic_year" value="'+$("#academic_year").val()+'"/>')
                .append('<input type="hidden" name="academic_level" value="'+$("#academic_level").val()+'"/>')
                .append('<input type="hidden" name="batch" value="'+$("#batch").val()+'"/>')
                .append('<input type="hidden" name="request_list_type" value="ALL"/>').appendTo('body').submit();
            // remove form from the body
            $('#all_applicant_result_list_download_form').remove();
        });

			//alert();
		$('#myTable').DataTable({
			"bPaginate": false
		});

	});



</script>
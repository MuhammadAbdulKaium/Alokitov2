@if($applicantResultSheet->count()>0)
	<br/>
	<table id="example1" class="table table-striped table-bordered table-responsive">
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
	<div class="alert-auto-hide alert alert-info alert-dismissable" style="opacity: 450.741;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-info-circle"></i>  No Records found.
	</div>
@endif

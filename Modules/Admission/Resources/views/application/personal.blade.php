@extends('admission::layouts.applicant-profile-layout')
<!-- page content -->
@section('profile-content')
	<div id="w1-tab0" class="tab-pane active">
		{{--personale info--}}
		@php $personalInfo = $applicantProfile->personalInfo();  @endphp
{{--		{{dd($personalInfo)}}--}}
		<div class="row">
			<div class="col-md-12">
				<p class="pull-right flip">
					<a class="btn btn-success" href="{{url('/admission/applicant/personal/'.$personalInfo->id.'/edit')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Edit</a>
				</p>
			</div>
		</div>
		<table class="table table-striped table-bordered">
			<colgroup>
				<col style="width:20px">
				<col style="width:170px">
				<col style="width:20px">
				<col style="width:170px">
			</colgroup>
			<tr>
				<th>Applicant's Name</th>
				<td>{{$personalInfo->std_name}}</td>
				<th>Applicant's Name (bn)</th>
				<td>{{$personalInfo->std_name_bn}}</td>
			</tr>
			<tr>
				<th>Gender</th>
				<td>{{$personalInfo->gender==0?"Male":"Female"}}</td>
				<th>Date of Birth</th>
				<td>{{date('d M, Y', strtotime($personalInfo->birth_date))}}</td>
			</tr>
			{{--parent section --}}
			<tr> <th colspan="4"><h4 class="text-bold"> <i class="fa fa-user" aria-hidden="true"></i> Parent Information</h4></th></tr>
			<tr>
				<th>Father's Name</th>
				<td>{{$personalInfo->father_name}}</td>
				<th>Mother's Name</th>
				<td>{{$personalInfo->mother_name}}</td>
			</tr>
			<tr>
				<th>Father's Name (bn)</th>
				<td>{{$personalInfo->father_name_bn}}</td>
				<th>Mother's Name (bn)</th>
				<td>{{$personalInfo->mother_name_bn}}</td>
			</tr>
			<tr>
				<th>Father's Qualification</th>
				<td>{{$personalInfo->father_education}}</td>
				<th>Mother's Qualification</th>
				<td>{{$personalInfo->mother_education}}</td>
			</tr>
			<tr>
				<th>Father's Occupation</th>
				<td>{{$personalInfo->father_occupation}}</td>
				<th>Mother's Occupation</th>
				<td>{{$personalInfo->mother_occupation}}</td>
			</tr>
			<tr>
				<th>Father's Phone</th>
				<td>{{$personalInfo->father_phone}}</td>
				<th>Mother's Phone</th>
				<td>{{$personalInfo->mother_phone}}</td>
			</tr>
			{{--present address section--}}
			<tr> <th colspan="4"><h4 class="text-bold"> <i class="fa fa-map-marker" aria-hidden="true"></i> Present Address</h4> </th></tr>
			<tr>
				<th>Address</th>
				<td colspan="3">{{$personalInfo->add_pre_address}}</td>
			</tr>
			<tr>
				<th>Post Office</th>
				<td>{{$personalInfo->add_pre_post}}</td>
				<th>City</th>
				<td>{{$personalInfo->preCity()->name}}</td>
			</tr>
			<tr>
				<th>State</th>
				<td>{{$personalInfo->preState()->name}}</td>
				<th> Phone</th>
				<td>{{$personalInfo->add_pre_phone}}</td>
			</tr>
			{{--Permanent address section--}}
			<tr> <th colspan="4"><h4 class="text-bold"> <i class="fa fa-map-marker" aria-hidden="true"></i> Permanent Address </h4></th></tr>
			<tr>
				<th>Address</th>
				<td colspan="3">{{$personalInfo->add_per_address}}</td>
			</tr>
			<tr>
				<th>Post Office</th>
				<td>{{$personalInfo->add_per_post}}</td>
				<th>City</th>
				<td>{{$personalInfo->perCity()->name}}</td>
			</tr>
			<tr>
				<th>State</th>
				<td>{{$personalInfo->perState()->name}}</td>
				<th> Phone</th>
				<td>{{$personalInfo->add_per_phone}}</td>
			</tr>
			{{--Guardian section--}}
			<tr> <th colspan="4"><h4 class="text-bold"> <i class="fa fa-user" aria-hidden="true"></i> Guardian</h4></th></tr>
			<tr>
				<th>Guardian's Name</th>
				<td>{{$personalInfo->gud_name}}</td>
				<th>Guardian's Phone</th>
				<td>{{$personalInfo->gud_phone}}</td>
			</tr>
			<tr>
				<th>Guardian's Income</th>
				<td>{{$personalInfo->gud_income}}</td>
				<th>Guardian's Income (bn)</th>
				<td>{{$personalInfo->gud_income_bn}}</td>
			</tr>

			{{--Pervious School information--}}
			<tr> <th colspan="4"><h4 class="text-bold"><i class="fa fa-university" aria-hidden="true"></i> Previous School Information</h4></th></tr>
			<tr>
				<th>Previous School</th>
				<td colspan="3">{{$personalInfo->psc_school}}</td>
			</tr>
			<tr>
				<th>PSC GPA</th>
				<td>{{$personalInfo->psc_gpa}}</td>
				<th>PSC Roll</th>
				<td>{{$personalInfo->psc_roll. ' ('.$personalInfo->psc_year.')'}}</td>
			</tr>
			<tr>
				<th>Testimonial No.</th>
				<td>{{$personalInfo->psc_tes_no}}</td>
				<th>Testimonial Date</th>
				<td>{{$personalInfo->psc_tes_date}}</td>
			</tr>
		</table>
	</div>
@endsection

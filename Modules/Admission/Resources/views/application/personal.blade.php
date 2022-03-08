@extends('admission::layouts.applicant-profile-layout')
<!-- page content -->
@section('profile-content')
	<div id="w1-tab0" class="tab-pane active">
		{{--personale info--}}
		@php $personalInfo = $applicantProfile->personalInfo();

		$father=$applicantProfile->father;
		$mother=$applicantProfile->mother;

		@endphp

	{{--	{{dd($personalInfo)}}--}}
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
				<td>{{$personalInfo->first_name}} {{$personalInfo->last_name}}</td>
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
				<td>{{$father->name}}</td>
				<th>Mother's Name</th>
				<td>{{$mother->name}}</td>
			</tr>
			<tr>
				<th>Father's Name (bn)</th>
				<td>{{$father->bengali_name}}</td>
				<th>Mother's Name (bn)</th>
				<td>{{$mother->bengali_name}}</td>
			</tr>

			<tr>
				<th>Father's Occupation</th>
				<td>{{$father->profession}}</td>
				<th>Mother's Occupation</th>
				<td>{{$mother->	profession}}</td>
			</tr>
			<tr>
				<th>Father's Designation</th>
				<td>{{$father->designation}}</td>
				<th>Mother's Designation</th>
				<td>{{$mother->designation}}</td>
			</tr>
			<tr>
				<th>Father's Phone</th>
				<td>{{$personalInfo->father_phone}}</td>
				<th>Mother's Phone</th>
				<td>{{$mother->contact_phone}}</td>
			</tr>

		</table>
	</div>
@endsection

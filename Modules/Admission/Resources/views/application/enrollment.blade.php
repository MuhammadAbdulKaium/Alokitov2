@extends('admission::layouts.applicant-profile-layout')
<!-- page content -->
@section('profile-content')
	<div id="w1-tab2" class="tab-pane active">
		{{--applicant enrollment--}}
		@php $enrollment = $applicantProfile->enroll(); @endphp
		<table class="table table-striped table-bordered">
			<colgroup>
				<col style="width:130px">
				<col style="width:130px">
				<col style="width:130px">
				<col style="width:130px">
			</colgroup>
			<tr>
				<th>Academic Year</th>
				<td>{{$enrollment->academicYear()->year_name}}</td>
				<th>Academic Level</th>
				<td>{{$enrollment->academicLevel()->level_name}}</td>
			</tr>
			<tr>
				<th>Batch</th>
				<td>{{$enrollment->batch()->batch_name}}</td>
				<th>Section</th>
				<td>{{$enrollment->section()?$enrollment->section()->section_name:'Not Assigned'}}</td>
			</tr>
			<tr>
				<th>Applied Time</th>
				<td>{{date('d M, Y H:i:s', strtotime($applicantProfile->created_at))}}</td>
				<th>Applied User</th>
				<td>admin@alokito.com</td>
			</tr>
			<tr>
				<th>Updated Time</th>
				<td>Aug 7, 2017, 12:02:57 PM</td>
				<th>Updated User</th>
				<td>admin@alokito.com</td>
			</tr>
		</table>
	</div>
@endsection
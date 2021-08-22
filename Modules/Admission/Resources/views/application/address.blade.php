@extends('admission::layouts.applicant-profile-layout')
<!-- page content -->
@section('profile-content')
	<div id="w1-tab1" class="tab-pane active">
	@for($i =0; $i<2; $i++)
		{{--address_type--}}
		@php $addressType = ($i==0?'PRESENT':'PERMANENT'); @endphp
		{{--applicant-present-address--}}
		@php $address = $applicantProfile->address($addressType); @endphp
		<div class="row">
			<div class="col-md-12">
				<p class="pull-right flip">
					<a class="btn btn-success" href="{{url('/admission/applicant/address/'.$address->id.'/edit')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Edit {{ucfirst(strtolower($addressType))}} Address</a>
				</p>
			</div>
		</div>

		<table class="table table-striped table-bordered">
			<colgroup>
				<col style="width:130px">
				<col style="width:130px">
				<col style="width:130px">
				<col style="width:130px">
			</colgroup>
			<tr>
				<th>Address</th>
				<td colspan="3"> {{$address?$address->address:'-'}} </td>
			</tr>
			<tr>
				<th>City</th>
				<td> {{$address?$address->city()->name:'-'}} </td>
				<th>State</th>
				<td> {{$address?$address->state()->name:'-'}} </td>
			</tr>
			<tr>
				<th>Country</th>
				<td> {{$address?$address->country()->name:'-'}} </td>
				<th>Zip Code</th>
				<td> {{$address?$address->zip:'-'}} </td>
			</tr>
			<tr>
				<th>House No</th>
				<td> {{$address?$address->house:'-'}} </td>
				<th>Phone No</th>
				<td> {{$address?$address->phone:'-'}} </td>
			</tr>
		</table>
		@endfor
	</div>
@endsection
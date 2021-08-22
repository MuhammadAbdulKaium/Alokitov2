
{{--<div class="row">--}}
{{--<div class="col-md-10 col-md-offset-1">--}}
<form id="applicant_information_update_form" action="{{url('/admission/applicant/personal/'.$personalInfo->id.'/update')}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h3 class="modal-title"> <i class="fa fa-info-circle"></i> Update Applicant Details</h3>
	</div>
	<!--modal-header-->
	<div class="modal-body">
		{{--Personal Information--}}
		<div class="row">
			<h4 class="bg-primary text-center">Persona Information</h4>
			<div class="col-sm-8">
				<div class="form-group {{ $errors->has('std_name') ? ' has-error' : '' }}">
					<label class="control-label" for="std_name">Applicant's Name</label>
					<input id="std_name" class="form-control" name="std_name" value="{{$personalInfo->std_name}}" type="text">
					<div class="help-block">
						@if ($errors->has('std_name'))
							<strong>{{ $errors->first('std_name') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<label class="control-label" for="gender">Gender</label>
				<select id="gender" class="form-control" name="gender">
					<option value="" disabled>--- Select Gender ---</option>
					<option value="0" @if($personalInfo->gender =="0") selected="selected" @endif>Male</option>
					<option value="1" @if($personalInfo->gender =="1") selected="selected" @endif>Female</option>
				</select>
				<div class="help-block">
					@if ($errors->has('gender'))
						<strong>{{ $errors->first('gender') }}</strong>
					@endif
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-8">
				<div class="form-group {{ $errors->has('std_name_bn') ? ' has-error' : '' }}">
					<label class="control-label" for="std_name_bn">Applicant's Name (bn)</label>
					<input id="std_name_bn" class="form-control" name="std_name_bn" value="{{$personalInfo->std_name_bn}}" type="text">
					<div class="help-block">
						@if ($errors->has('std_name_bn'))
							<strong>{{ $errors->first('std_name_bn') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('birth_date') ? ' has-error' : '' }}">
					<label class="control-label" for="birth_date">Date of Birth</label>
					<input id="birth_date" class="form-control datepicker" name="birth_date" value="{{date('m/d/Y', strtotime($personalInfo->birth_date))}}" size="10" type="text" readonly>
					<div class="help-block">
						@if ($errors->has('birth_date'))
							<strong>{{ $errors->first('birth_date') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('blood_group') ? ' has-error' : '' }}">
					<label class="control-label" for="blood_group">Bloodgroup</label>
					<select id="blood_group" class="form-control" name="blood_group" disabled>
						<option value="" selected disabled>--- Select Blood Group ---</option>
						<option value="Unknown" {{$personalInfo->blood_group=="Unknown"?'selected':''}}>Unknown</option>
						<option value="A+" {{$personalInfo->blood_group=="A+"?'selected':''}}>A+</option>
						<option value="A-" {{$personalInfo->blood_group=="A-"?'selected':''}}>A-</option>
						<option value="B+" {{$personalInfo->blood_group=="B+"?'selected':''}}>B+</option>
						<option value="B-" {{$personalInfo->blood_group=="B-"?'selected':''}}>B-</option>
						<option value="AB+" {{$personalInfo->blood_group=="AB+"?'selected':''}}>AB+</option>
						<option value="AB-" {{$personalInfo->blood_group=="AB-"?'selected':''}}>AB-</option>
						<option value="O+" {{$personalInfo->blood_group=="O+"?'selected':''}}>O+</option>
						<option value="O-" {{$personalInfo->blood_group=="O-"?'selected':''}}>O-</option>
					</select>
					<div class="help-block">
						@if ($errors->has('blood_group'))
							<strong>{{ $errors->first('blood_group') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('religion') ? ' has-error' : '' }}">
					<label class="control-label" for="religion">Religion</label>
					<select id="blood_group" class="form-control" name="religion">
						<option value="" disabled>--- Select Religion ---</option>
						<option value="1" {{$personalInfo->religion=="1"?'selected':''}} >Islam</option>
						<option value="2" {{$personalInfo->religion=="2"?'selected':''}} >Hinduism</option>
						<option value="3" {{$personalInfo->religion=="3"?'selected':''}} >Christian</option>
						<option value="4" {{$personalInfo->religion=="4"?'selected':''}} >Buddhism</option>
						<option value="5" {{$personalInfo->religion=="5"?'selected':''}} >Others</option>
					</select>
					<div class="help-block">
						@if ($errors->has('religion'))
							<strong>{{ $errors->first('religion') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('nationality') ? ' has-error' : '' }}">
					<label class="control-label" for="nationality">Nationality</label>
					<select id="nationality" class="form-control" name="nationality">
						<option value="" disabled>--- Select Nationality ---</option>
						<option value="1" {{$personalInfo->nationality=="1"?'selected':''}}>Bangladeshi</option>
					</select>
					<div class="help-block">
						@if ($errors->has('nationality'))
							<strong>{{ $errors->first('nationality') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>

		{{--Father Information--}}
		<div class="row">
			<h4 class="bg-primary text-center">Parent Information</h4>
			<div class="col-sm-6">
				<div class="form-group {{ $errors->has('father_name') ? ' has-error' : '' }}">
					<label class="control-label" for="father_name">Father's Name</label>
					<input id="father_name" class="form-control" name="father_name" value="{{$personalInfo->father_name}}" type="text">
					<div class="help-block">
						@if ($errors->has('father_name'))
							<strong>{{ $errors->first('father_name') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
					<label class="control-label" for="father_name_bn">Father's Name (bn)</label>
					<input id="father_name_bn" class="form-control" name="father_name_bn" value="{{$personalInfo->father_name_bn}}" type="text">
					<div class="help-block">
						@if ($errors->has('father_name_bn'))
							<strong>{{ $errors->first('father_name_bn') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('father_education') ? ' has-error' : '' }}">
					<label class="control-label" for="father_education">Father's Qualification</label>
					<input id="father_education" class="form-control" name="father_education" value="{{$personalInfo->father_education}}" type="text">
					<div class="help-block">
						@if ($errors->has('father_education'))
							<strong>{{ $errors->first('father_education') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('father_occupation') ? ' has-error' : '' }}">
					<label class="control-label" for="father_occupation">Father's Occupation</label>
					<input id="father_occupation" class="form-control" name="father_occupation" value="{{$personalInfo->father_occupation}}" type="text">
					<div class="help-block">
						@if ($errors->has('father_occupation'))
							<strong>{{ $errors->first('father_occupation') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('father_phone') ? ' has-error' : '' }}">
					<label class="control-label" for="father_phone">Father's Phone</label>
					<input id="father_phone" class="form-control" name="father_phone" value="{{$personalInfo->father_phone}}" type="text">
					<div class="help-block">
						@if ($errors->has('father_phone'))
							<strong>{{ $errors->first('father_phone') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>
		<hr>
		{{--Mother Information--}}
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group {{ $errors->has('mother_name') ? ' has-error' : '' }}">
					<label class="control-label" for="mother_name">Mother's Name</label>
					<input id="mother_name" class="form-control" name="mother_name" value="{{$personalInfo->mother_name}}" type="text">
					<div class="help-block">
						@if ($errors->has('mother_name'))
							<strong>{{ $errors->first('mother_name') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group {{ $errors->has('mother_name_bn') ? ' has-error' : '' }}">
					<label class="control-label" for="mother_name_bn">Mother's Name (bn)</label>
					<input id="mother_name_bn" class="form-control" name="mother_name_bn" value="{{$personalInfo->mother_name_bn}}" type="text">
					<div class="help-block">
						@if ($errors->has('mother_name_bn'))
							<strong>{{ $errors->first('mother_name_bn') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('mother_education') ? ' has-error' : '' }}">
					<label class="control-label" for="mother_education">Mother's Qualification</label>
					<input id="mother_education" class="form-control" name="mother_education" value="{{$personalInfo->mother_education}}" type="text">
					<div class="help-block">
						@if ($errors->has('mother_education'))
							<strong>{{ $errors->first('mother_education') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('mother_occupation') ? ' has-error' : '' }}">
					<label class="control-label" for="mother_occupation">Mother's Occupation</label>
					<input id="mother_occupation" class="form-control" name="mother_occupation" value="{{$personalInfo->mother_occupation}}" type="text">
					<div class="help-block">
						@if ($errors->has('mother_occupation'))
							<strong>{{ $errors->first('mother_occupation') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('mother_phone') ? ' has-error' : '' }}">
					<label class="control-label" for="mother_phone">Mother's Phone</label>
					<input id="mother_phone" class="form-control" name="mother_phone" value="{{$personalInfo->mother_phone}}" type="text">
					<div class="help-block">
						@if ($errors->has('mother_phone'))
							<strong>{{ $errors->first('mother_phone') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>

		{{--Present Address--}}
		<div class="row">
			<h4 class="bg-primary text-center">Present Address</h4>
			<div class="col-sm-8">
				<div class="form-group {{ $errors->has('add_pre_address') ? ' has-error' : '' }}">
					<label class="control-label" for="add_pre_address">Address</label>
					<input id="add_pre_address" class="form-control" name="add_pre_address" value="{{$personalInfo->add_pre_address}}" type="text">
					<div class="help-block">
						@if ($errors->has('add_pre_address'))
							<strong>{{ $errors->first('add_pre_address') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('add_pre_post') ? ' has-error' : '' }}">
					<label class="control-label" for="add_pre_post">Post Office</label>
					<input id="add_pre_post" class="form-control" name="add_pre_post" value="{{$personalInfo->add_pre_post}}" type="text">
					<div class="help-block">
						@if ($errors->has('add_pre_post'))
							<strong>{{ $errors->first('add_pre_post') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('add_pre_state') ? ' has-error' : '' }}">
					<label class="control-label" for="add_pre_state">State</label>
					{{--<input id="add_pre_state" class="form-control" name="add_pre_state" value="{{$personalInfo->add_pre_state}}" type="text">--}}
					<select id="add_pre_state" class="form-control" name="add_pre_state">
						<option value="" selected disabled>--- Select Present State ---</option>
						@if($allState->count()>0)
							{{--state list looping--}}
							@foreach($allState as $state)
								<option value="{{$state->id}}" {{$state->id==$personalInfo->add_pre_state?'selected':''}} >{{$state->name}}</option>
							@endforeach
						@endif
					</select>
					<div class="help-block">
						@if ($errors->has('add_pre_state'))
							<strong>{{ $errors->first('add_pre_state') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('add_pre_city') ? ' has-error' : '' }}">
					<label class="control-label" for="add_pre_city">City</label>
					{{--<input id="add_pre_city" class="form-control" name="add_pre_city" value="{{$personalInfo->add_pre_city}}" type="text">--}}
					{{--present state--}}
					@php $allPreCity = $personalInfo->preState()?$personalInfo->preState()->allCity():null; @endphp
					<select id="add_pre_city" class="form-control" name="add_pre_city">
						<option value="" selected disabled>--- Select Present City ---</option>
						@if($allPreCity->count()>0)
							{{--state list looping--}}
							@foreach($allPreCity as $city)
								<option value="{{$city->id}}" {{$city->id==$personalInfo->add_pre_city?'selected':''}} >{{$city->name}}</option>
							@endforeach
						@endif
					</select>
					<div class="help-block">
						@if ($errors->has('add_pre_city'))
							<strong>{{ $errors->first('add_pre_city') }}</strong>
						@endif
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('add_pre_phone') ? ' has-error' : '' }}">
					<label class="control-label" for="add_pre_phone">Phone</label>
					<input id="add_pre_phone" class="form-control" name="add_pre_phone" value="{{$personalInfo->add_pre_phone}}" type="text">
					<div class="help-block">
						@if ($errors->has('add_pre_phone'))
							<strong>{{ $errors->first('add_pre_phone') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>


		{{--permanent Address--}}
		<div class="row">
			<h4 class="bg-primary text-center">Permanent Address</h4>
			<div class="col-sm-8">
				<div class="form-group {{ $errors->has('add_per_address') ? ' has-error' : '' }}">
					<label class="control-label" for="add_per_address">Address</label>
					<input id="add_per_address" class="form-control" name="add_per_address" value="{{$personalInfo->add_per_address}}" type="text">
					<div class="help-block">
						@if ($errors->has('add_per_address'))
							<strong>{{ $errors->first('add_per_address') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('add_per_post') ? ' has-error' : '' }}">
					<label class="control-label" for="add_per_post">Post Office</label>
					<input id="add_per_post" class="form-control" name="add_per_post" value="{{$personalInfo->add_per_post}}" type="text">
					<div class="help-block">
						@if ($errors->has('add_per_post'))
							<strong>{{ $errors->first('add_per_post') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('add_per_state') ? ' has-error' : '' }}">
					<label class="control-label" for="add_per_state">State</label>
					{{--<input id="add_per_state" class="form-control" name="add_per_state" value="{{$personalInfo->add_per_state}}" type="text">--}}
					<select id="add_per_state" class="form-control" name="add_per_state">
						<option value="" selected disabled>--- Select Permanent State ---</option>
						@if($allState->count()>0)
							{{--state list looping--}}
							@foreach($allState as $state)
								<option value="{{$state->id}}" {{$state->id==$personalInfo->add_per_state?'selected':''}} >{{$state->name}}</option>
							@endforeach
						@endif
					</select>
					<div class="help-block">
						@if ($errors->has('add_per_state'))
							<strong>{{ $errors->first('add_per_state') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('add_per_city') ? ' has-error' : '' }}">
					<label class="control-label" for="add_per_city">City</label>
					{{--<input id="add_per_city" class="form-control" name="add_per_city" value="{{$personalInfo->add_per_city}}" type="text">--}}

					{{--present state--}}
					@php $allPerCity = $personalInfo->perState()?$personalInfo->perState()->allCity():null; @endphp
					<select id="add_per_city" class="form-control" name="add_per_city">
						<option value="" selected disabled>--- Select Permanent City ---</option>
						@if($allPerCity->count()>0)
							{{--state list looping--}}
							@foreach($allPerCity as $city)
								<option value="{{$city->id}}" {{$city->id==$personalInfo->add_per_city?'selected':''}} >{{$city->name}}</option>
							@endforeach
						@endif
					</select>
					<div class="help-block">
						@if ($errors->has('add_per_city'))
							<strong>{{ $errors->first('add_per_city') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('add_per_phone') ? ' has-error' : '' }}">
					<label class="control-label" for="add_per_phone">Phone</label>
					<input id="add_per_phone" class="form-control" name="add_per_phone" value="{{$personalInfo->add_per_phone}}" type="text">
					<div class="help-block">
						@if ($errors->has('add_per_phone'))
							<strong>{{ $errors->first('add_per_phone') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>

		{{--Guardian Informaiton--}}
		<div class="row">
			<h4 class="bg-primary text-center">Guardian Information</h4>
			<div class="col-sm-12">
				<div class="form-group {{ $errors->has('gud_name') ? ' has-error' : '' }}">
					<label class="control-label" for="gud_name">Guardian's Name (If Applicable)</label>
					<input id="gud_name" class="form-control" name="gud_name" value="{{$personalInfo->gud_name}}" type="text">
					<div class="help-block">
						@if ($errors->has('gud_name'))
							<strong>{{ $errors->first('gud_name') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('gud_phone') ? ' has-error' : '' }}">
					<label class="control-label" for="first_name">Guardian's Phone</label>
					<input id="gud_phone" class="form-control" name="gud_phone" value="{{$personalInfo->gud_phone}}" type="text">
					<div class="help-block">
						@if ($errors->has('gud_phone'))
							<strong>{{ $errors->first('gud_phone') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('gud_income') ? ' has-error' : '' }}">
					<label class="control-label" for="gud_income">Guardian's Income</label>
					<input id="gud_income" class="form-control" name="gud_income" value="{{$personalInfo->gud_income}}" type="text">
					<div class="help-block">
						@if ($errors->has('gud_income'))
							<strong>{{ $errors->first('gud_income') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('gud_income_bn') ? ' has-error' : '' }}">
					<label class="control-label" for="gud_income_bn">Guardian's Income (bn)	</label>
					<input id="gud_income_bn" class="form-control" name="gud_income_bn" value="{{$personalInfo->gud_income_bn}}" type="text">
					<div class="help-block">
						@if ($errors->has('gud_income_bn'))
							<strong>{{ $errors->first('gud_income_bn') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>

		{{--psc Information--}}
		<div class="row">
			<h4 class="bg-primary text-center">Previous Public Examination Information</h4>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('psc_gpa') ? ' has-error' : '' }}">
					<label class="control-label" for="psc_gpa">PSC GPA</label>
					<input id="psc_gpa" class="form-control" name="psc_gpa" value="{{$personalInfo->psc_gpa}}" type="text">
					<div class="help-block">
						@if ($errors->has('psc_gpa'))
							<strong>{{ $errors->first('psc_gpa') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('psc_roll') ? ' has-error' : '' }}">
					<label class="control-label" for="psc_roll">PSC Roll</label>
					<input id="psc_roll" class="form-control" name="psc_roll" value="{{$personalInfo->psc_roll}}" type="text">
					<div class="help-block">
						@if ($errors->has('psc_roll'))
							<strong>{{ $errors->first('psc_roll') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('psc_year') ? ' has-error' : '' }}">
					<label class="control-label" for="psc_year">PSC Year</label>
					<input id="psc_year" class="form-control" name="psc_year" value="{{$personalInfo->psc_year}}" type="text">
					<div class="help-block">
						@if ($errors->has('psc_year'))
							<strong>{{ $errors->first('psc_year') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>

		{{--jsc Information--}}
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('jsc_gpa') ? ' has-error' : '' }}">
					<label class="control-label" for="jsc_gpa">JSC GPA</label>
					<input id="jsc_gpa" class="form-control" name="jsc_gpa" value="{{$personalInfo->jsc_gpa}}" type="text">
					<div class="help-block">
						@if ($errors->has('jsc_gpa'))
							<strong>{{ $errors->first('jsc_gpa') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('jsc_roll') ? ' has-error' : '' }}">
					<label class="control-label" for="jsc_roll">JSC Roll</label>
					<input id="jsc_roll" class="form-control" name="jsc_roll" value="{{$personalInfo->jsc_roll}}" type="text">
					<div class="help-block">
						@if ($errors->has('jsc_roll'))
							<strong>{{ $errors->first('jsc_roll') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('jsc_year') ? ' has-error' : '' }}">
					<label class="control-label" for="jsc_year">PSC Year</label>
					<input id="jsc_year" class="form-control" name="jsc_year" value="{{$personalInfo->jsc_year}}" type="text">
					<div class="help-block">
						@if ($errors->has('jsc_year'))
							<strong>{{ $errors->first('jsc_year') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>

		{{--previous school Information--}}
		<div class="row">
			<h4 class="bg-primary text-center">Previous School Information</h4>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('psc_school') ? ' has-error' : '' }}">
					<label class="control-label" for="psc_school">Previous School</label>
					<input id="psc_school" class="form-control" name="psc_school" value="{{$personalInfo->psc_school}}" type="text">
					<div class="help-block">
						@if ($errors->has('psc_school'))
							<strong>{{ $errors->first('psc_school') }}</strong>
						@endif
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('psc_tes_no') ? ' has-error' : '' }}">
					<label class="control-label" for="psc_tes_no">Testimonial No.</label>
					<input id="psc_tes_no" class="form-control" name="psc_tes_no" value="{{$personalInfo->psc_tes_no}}" type="text">
					<div class="help-block">
						@if ($errors->has('psc_tes_no'))
							<strong>{{ $errors->first('psc_tes_no') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('psc_tes_date') ? ' has-error' : '' }}">
					<label class="control-label" for="psc_tes_date">Testimonial Date</label>
					<input id="psc_tes_date" class="form-control datepicker" readonly name="psc_tes_date" value="{{date('m/d/Y', strtotime($personalInfo->psc_tes_date))}}" type="text">
					<div class="help-block">
						@if ($errors->has('psc_tes_date'))
							<strong>{{ $errors->first('psc_tes_date') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-info pull-left">Update</button>
		<a class="btn btn-default pull-right" href="#" data-dismiss="modal">Cancel</a>
	</div>
</form>
{{--</div>--}}
{{--</div>--}}



<script type ="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.datepicker').datepicker({
            "changeMonth":true,"changeYear":true,"autoSize":true,"dateFormat":"yy-mm-dd",
            "changeMonth": true,
            "yearRange": "1900:2018",
            "changeYear": true,
            "autoSize": true,
            "dateFormat": "yy-mm-dd"
        });
    });


    // request state list for the selelcted prsent country
    jQuery(document).on('change','#add_pre_state',function(){
        // console.log("hmm its change");

        // get country id
        var state_id = $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/setting/find/city/') }}",
            type: 'GET',
            cache: false,
            data: {'id': state_id }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log('state_id');

            },

            success:function(data){
                console.log('success');

                //console.log(data.length);
                op+='<option value="0" selected disabled>--- Select Present City ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                }

                // set value to the academic batch
                $('#add_pre_city').html("");
                $('#add_pre_city').append(op);
            },

            error:function(){

            }
        });
    });


    jQuery(document).on('change','#add_per_state',function(){
        // console.log("hmm its change");

        // get country id
        var state_id = $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/setting/find/city/') }}",
            type: 'GET',
            cache: false,
            data: {'id': state_id }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log('state_id');

            },

            success:function(data){
                console.log('success');

                //console.log(data.length);
                op+='<option value="0" selected disabled>--- Select Permanent City ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                }

                // set value to the academic batch
                $('#add_per_city').html("");
                $('#add_per_city').append(op);
            },

            error:function(){

            }
        });
    });

</script>

@extends('layouts.master')

@section('styles')
<style>
	.waiverSection {
		display:none;
	}

	.required{
		color:red;
	}
</style>

@endsection
	<!-- page content -->
@section('content')

{{--batch string--}}
@php $batchString="Class"; @endphp


	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-plus-square"></i> Create <small> New Student </small>
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="/student">Student</a></li>
				<li><a href="/student/manage/profile">Manage Student</a></li>
				<li class="active">Create New Student</li>
			</ul>
		</section>
		<section class="content">
			@if(Session::has('success'))
				<div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
				</div>
			@elseif(Session::has('warning'))
				<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
				</div>
			@endif
			<div class="box box-solid">
				<div class="box-body">
					<form id="student_create_form" name="student_create_form" action="{{url('student/profile/store')}}" method="post">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<h2 class="page-header edusec-page-header-1">
							<i class="fa fa-info-circle"></i> Personal Details
						</h2>
						<div class="row">

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
									<label class="control-label" for="title">Student Title</label>
									<select id="title" class="form-control" name="title">
										<option value="">--- Select Student Title ---</option>
										<option value="Cadet" @if(old('title')=='Cadet') selected="selected" @endif>Student</option>
										<option value="FM" @if(old('title')=='FM') selected="selected" @endif>FM</option>
										<option value="Mr." @if(old('title')=='Mr.') selected="selected" @endif>Mr.</option>
										<option value="Mrs." @if(old('title')=='Mrs.') selected="selected" @endif>Mrs.</option>
										<option value="Ms." @if(old('title')=='Ms.') selected="selected" @endif>Ms.</option>
									</select>
									<div class="help-block">
										@if ($errors->has('title'))
											<strong>{{ $errors->first('title') }}</strong>
										@endif
									</div>
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
									<label class="control-label" for="first_name">First Name <span class="required">*</span></label>
									<input type="text" id="first_name" class="form-control" name="first_name" value="{{old('first_name')}}" required>
									<div class="help-block">
										@if ($errors->has('first_name'))
											<strong>{{ $errors->first('first_name') }}</strong>
										@endif
									</div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
									<label class="control-label" for="last_name">Last Name: </label>
									<input type="text" id="last_name" class="form-control" name="last_name" value="{{old('last_name')}}" >
									<div class="help-block">
										@if ($errors->has('last_name'))
											<strong>{{ $errors->first('last_name') }}</strong>
										@endif
									</div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
									<label class="control-label" for="type">Student Type</label>
									<select id="type" class="form-control" name="type">
										<option value="">--- Select Student Type ---</option>
										<option value="2">Pre Admission</option>
										<option value="1">Regular</option>
									</select>
									<div class="help-block">
										@if ($errors->has('type'))
											<strong>{{ $errors->first('type') }}</strong>
										@endif
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
									<label class="control-label" for="dob">Date of Birth <span class="required">*</span></label>
									<input type="text" id="dob" class="form-control" name="dob" value="{{old('dob')}}" required readonly>
									<div class="help-block">
										@if ($errors->has('dob'))
											<strong>{{ $errors->first('dob') }}</strong>
										@endif
									</div>
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }}">
									<label class="control-label" for="gender">Gender <span class="required">*</span></label>
									<select id="gender" class="form-control" name="gender" required>
										<option value="">--- Select Gender ---</option>
										<option value="Male" @if(old('gender')=="Male") selected="selected" @endif>Male</option>
										<option value="Female" @if(old('gender')=="Female") selected="selected" @endif>Female</option>
									</select>
									<div class="help-block">
										@if ($errors->has('gender'))
											<strong>{{ $errors->first('gender') }}</strong>
										@endif
									</div>
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
									<label class="control-label" for="email">Student Number <span class="required">*</span></label>
									<input type="text" id="email" class="form-control" name="email" maxlength="60" value="{{old('email')}}" required>
									<div class="help-block">
										@if ($errors->has('email'))
											<strong>{{ $errors->first('email') }}</strong>
										@endif
									</div>
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
									<label class="control-label" for="phone">Phone No <span class="required">*</span></label>
									<input type="text" id="phone" class="form-control" name="phone" value="{{old('phone')}}" maxlength="12" required>
									<div class="help-block">
										@if ($errors->has('phone'))
											<strong>{{ $errors->first('phone') }}</strong>
										@endif
									</div>
								</div>
							</div>

						</div>

						<div class="row">
							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('blood_group') ? ' has-error' : '' }}">
									<label class="control-label" for="blood_group">Blood Group</label>
									<select id="blood_group" class="form-control" name="blood_group" required>
										<option value="">--- Select Blood Group ---</option>
										<option value="Unknown" @if(old('blood_group')=="Unknown") selected="selected" @endif>Unknown</option>
										<option value="A+" @if(old('blood_group')=="A+") selected="selected" @endif>A+</option>
										<option value="A-" @if(old('blood_group')=="A-") selected="selected" @endif>A-</option>
										<option value="B+" @if(old('blood_group')=="B+") selected="selected" @endif>B+</option>
										<option value="B-" @if(old('blood_group')=="B-") selected="selected" @endif>B-</option>
										<option value="AB+" @if(old('blood_group')=="AB+") selected="selected" @endif>AB+</option>
										<option value="AB-" @if(old('blood_group')=="AB-") selected="selected" @endif>AB-</option>
										<option value="O+" @if(old('blood_group')=="O+") selected="selected" @endif>O+</option>
										<option value="O-" @if(old('blood_group')=="O-") selected="selected" @endif>O-</option>
									</select>
									<div class="help-block">
										@if ($errors->has('blood_group'))
											<strong>{{ $errors->first('blood_group') }}</strong>
										@endif
									</div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('religion') ? ' has-error' : '' }}">
									<label class="control-label" for="religion">Religion</label>
									<select id="religion" class="form-control" name="religion" required>
										<option value="">--- Select Religion ---</option>
										<option value="1" @if(old('religion')==1) selected="selected" @endif>Islam</option>
										<option value="2" @if(old('religion')==2) selected="selected" @endif>Hinduism</option>
										<option value="3" @if(old('religion')==3) selected="selected" @endif>Christian</option>
										<option value="4" @if(old('religion')==4) selected="selected" @endif>Buddhism</option>
										<option value="5" @if(old('religion')==5) selected="selected" @endif>Others</option>
									</select>
									<div class="help-block">
										@if ($errors->has('religion'))
											<strong>{{ $errors->first('religion') }}</strong>
										@endif
									</div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('birth_place') ? ' has-error' : '' }}">
									<label class="control-label" for="birth_place">Birthplace</label>
									<input type="text" id="birth_place" class="form-control" name="birth_place" value="{{old('birth_place')}}" required>
									<div class="help-block">
										@if ($errors->has('birth_place'))
											<strong>{{ $errors->first('birth_place') }}</strong>
										@endif
									</div>
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('nationality') ? ' has-error' : '' }}">
									<label class="control-label" for="nationality">Nationality <span class="required">*</span></label>
									<select id="student_nationality" class="form-control" name="nationality" required>
										<option value="">--- Select Nationality ---</option>
										@if($allNationality)
											@foreach($allNationality as $nationality)
												<option value="{{$nationality->id}}" {{old('nationality')==$nationality->id?'selected':''}}>{{$nationality->nationality}}</option>
											@endforeach
										@endif
									</select>
									<div class="help-block">
										@if ($errors->has('nationality'))
											<strong>{{ $errors->first('nationality') }}</strong>
										@endif
									</div>
								</div>
							</div>
							<div class="col-sm-3">
                                <div class="form-group {{ $errors->has('language') ? ' has-error' : '' }}">
                                    <label class="control-label" for="language">Language</label>
                                    <input type="text" id="language" class="form-control" name="language" value="{{old('language')}}">
                                    <div class="help-block">
                                        @if ($errors->has('language'))
                                            <strong>{{ $errors->first('language') }}</strong>
                                        @endif
                                    </div>
                                </div>
							</div>
							<div class="col-sm-9">
                                <div class="form-group {{ $errors->has('identification_mark') ? ' has-error' : '' }}">
                                    <label class="control-label" for="identification_mark">Identification Mark</label>
                                    <input type="text" id="identification_mark" class="form-control" name="identification_mark" value="{{old('identification_mark')}}">
                                    <div class="help-block">
                                        @if ($errors->has('identification_mark'))
                                            <strong>{{ $errors->first('identification_mark') }}</strong>
                                        @endif
                                    </div>
                                </div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group {{ $errors->has('present_address') ? ' has-error' : '' }}">
									<label class="control-label" for="present_address">Present Address</label>
									<textarea name="present_address" id="present_address" class="form-control" rows="1">{{old('present_address')}}</textarea>
									<div class="help-block">
										@if ($errors->has('present_address'))
											<strong>{{ $errors->first('present_address') }}</strong>
										@endif
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group {{ $errors->has('permanent_address') ? ' has-error' : '' }}">
									<label class="control-label" for="permanent_address">Permanent Address</label>
									<textarea name="permanent_address" id="permanent_address" class="form-control" rows="1">{{old('permanent_address')}}</textarea>
									<div class="help-block">
										@if ($errors->has('permanent_address'))
											<strong>{{ $errors->first('permanent_address') }}</strong>
										@endif
									</div>
								</div>
							</div>
						</div>

						<h2 class="page-header edusec-page-header-1">
							<i class="fa fa-info-circle"></i> বাংলায়
						</h2>
						<div class="row">

							<div class="col-sm-12">
								<div class="form-group">
									<label class="control-label" for="">ছাত্র/ছাত্রীর পূর্ণ নামঃ </label>
									<input type="text" id="bn_fullname" class="form-control" name="bn_fullname" value="{{old('bn_fullname')}}">
								</div>
							</div>

						</div>


							<h2 class="page-header edusec-page-header-1">
							<i class="fa fa-info-circle"></i> Academic Details
						</h2>
						<div class="row">

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('campus') ? ' has-error' : '' }}">
									<label class="control-label" for="">Campus <span class="required">*</span></label>
									<select id="campus" class="form-control" name="campus" required>
										<option value="" disabled="true" selected="true">--- Select Campus ---</option>
										@if($allCampus)
											@foreach($allCampus as $campus)
												<option value="{{$campus->id}}" @if(old('campus')==$campus->id) selected="selected" @endif>{{$campus->name." (".$campus->campus_code.")"}}</option>
											@endforeach
										@endif
									</select>
									<div class="help-block">
										@if ($errors->has('campus'))
											<strong>{{ $errors->first('campus') }}</strong>
										@endif
									</div>
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('gr_no') ? ' has-error' : '' }}">
									<label class="control-label" for="gr_no">Merit Position <span class="required">*</span></label>
									<input type="text" id="gr_no" class="form-control" name="gr_no" value="{{old('gr_no')}}" required>
									<div class="help-block">
										@if ($errors->has('gr_no'))
											<strong>{{ $errors->first('gr_no') }}</strong>
										@endif
									</div>
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('admission_year') ? ' has-error' : '' }}">
									<label class="control-label" for="admission_year">Admission Year <span class="required">*</span></label>
									<select id="admission_year" class="form-control" name="admission_year" required>
										<option value="">--- Select Admission Year ---</option>
										@if($admissionYears)
											@foreach($admissionYears as $year)
												<option value="{{$year->id}}" @if(old('admission_year')==$year->id) selected="selected" @endif>{{$year->year_name}}</option>
											@endforeach
										@endif
									</select>
									<div class="help-block">
										@if ($errors->has('admission_year'))
											<strong>{{ $errors->first('admission_year') }}</strong>
										@endif
									</div>
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('academic_year') ? ' has-error' : '' }}">
									<label class="control-label" for="academic_year">Academic Year <span class="required">*</span></label>
									<select id="academic_year" class="form-control academicYear" name="academic_year" required>
										<option value="">--- Select Academic Year ---</option>
										@if($academicYears)
											@foreach($academicYears as $year)
												<option value="{{$year->id}}" @if(old('academic_year')==$year->id) selected="selected" @endif>{{$year->year_name}}</option>
											@endforeach
										@endif
									</select>
									<div class="help-block">
										@if ($errors->has('academic_year'))
											<strong>{{ $errors->first('academic_year') }}</strong>
										@endif
									</div>
								</div>
							</div>
						</div>
						<div class="row">

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('academic_level') ? ' has-error' : '' }}">
									<label class="control-label" for="academic_level">Academic Level <span class="required">*</span></label>
									<select id="academic_level" class="form-control academicLevel" name="academic_level" required>
										<option value="" disabled="true" selected="true">--- Select Level ---</option>
									</select>
									<div class="help-block">
										@if ($errors->has('academic_level'))
											<strong>{{ $errors->first('academic_level') }}</strong>
										@endif
									</div>
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('batch') ? ' has-error' : '' }}">
									<label class="control-label" for="batch">{{$batchString}} <span class="required">*</span></label>
									<select id="batch" class="form-control academicBatch" name="batch" required>
										<option value="" disabled="true" selected="true">---Select {{$batchString}} ---</option>
									</select>
									<div class="help-block">
										@if ($errors->has('batch'))
											<strong>{{ $errors->first('batch') }}</strong>
										@endif
									</div>
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('section') ? ' has-error' : '' }}">
									<label class="control-label" for="section">Section <span class="required">*</span></label>
									<select id="section" class="form-control academicSection" name="section" required>
										<option value="" disabled="true" selected="true">--- Select Section ---</option>
									</select>
									<div class="help-block">
										@if ($errors->has('section'))
											<strong>{{ $errors->first('section') }}</strong>
										@endif
									</div>
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('academic_group') ? ' has-error' : '' }}">
									<label class="control-label" for="academic_group">Group <span class="required">*</span></label>
									<input type="text" id="academic_group" class="form-control" name="academic_group" value="{{old('academic_group')}}" required>
									<div class="help-block">
										@if ($errors->has('academic_group'))
											<strong>{{ $errors->first('academic_group') }}</strong>
										@endif
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('enrolled_at') ? ' has-error' : '' }}">
									<label class="control-label" for="enrolled_at">Date of Enroll <span class="required">*</span></label>
									<input type="text" id="enrolled_at" class="form-control" name="enrolled_at" value="{{old('enrolled_at')}}" required readonly>
									<div class="help-block">
										@if ($errors->has('enrolled_at'))
											<strong>{{ $errors->first('enrolled_at') }}</strong>
										@endif
									</div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('batch_no') ? ' has-error' : '' }}">
									<label class="control-label" for="batch_no">Batch No <span class="required">*</span></label>
									<input type="text" id="batch_no" class="form-control" name="batch_no" value="{{old('batch_no')}}" required>
									<div class="help-block">
										@if ($errors->has('batch_no'))
											<strong>{{ $errors->first('batch_no') }}</strong>
										@endif
									</div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group {{ $errors->has('tuition_fees') ? ' has-error' : '' }}">
									<label class="control-label" for="tuition_fees">Tuition Fees <span class="required">*</span></label>
									<input type="text" id="tuition_fees" class="form-control" name="tuition_fees" value="{{old('tuition_fees')}}" required>
									<div class="help-block">
										@if ($errors->has('tuition_fees'))
											<strong>{{ $errors->first('tuition_fees') }}</strong>
										@endif
									</div>
								</div>
							</div>
						</div>


						<h2 class="page-header edusec-page-header-1">
							<input  id="waiverCheckBoxSeclectHidden" name="waivercheck" type="hidden" value="0">
							<i class="fa fa-info-circle"></i> Student Waiver <input id="waiverCheckBoxSeclect" name="waivercheck" type="checkbox" value="1">
						</h2>

					<div class="waiverSection">
						<div class="row">
								<div class="col-sm-4">
									<div class="form-group field-waiver-lbt_issue_date required">
										<label class="control-label">Waiver Type</label>
										<br>
										<select class="form-control" name="waiver_type" required>
											<option value="">Select Waiver Type</option>
											<option value="1">General Waiver</option>
											<option value="2">Upobritti</option>
											<option value="3">Scholarship</option>
										</select>
									</div>
								</div>

								<div class="col-sm-4">
									<div class="form-group field-waiver-lbt_issue_date required">
										<label class="control-label">Waiver</label>
										<br>
										<label class="radio-inline"><input type="radio" id="percent" required name="type" value="1" >Percent</label>
										<label class="radio-inline"><input type="radio" id="amount" required name="type" value="2" >Amount</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group field-waiver-lbt_issue_date required">
									<label class="control-label required" for="waiver-lbt_issue_date">Value</label>
									<div class="input-group">
										<span class="input-group-addon" id="addOn"></span>
										<input type="text" class="form-control" name="value" required placeholder="Value"  aria-describedby="basic-addon1">
									</div>
									</div>
								</div>
						</div>

							<div class="row">
								<div class="col-md-4">
									<div class="form-group field-waiver-lbt_issue_date required">
										<label class="control-label" for="waiver-lbt_issue_date" >Start Date</label>
										<input class="form-control datepicker" name="start_date"  required readonly="" size="10" type="text">

										<div class="help-block"></div>
									</div>		</div>
								<div class="col-sm-4">
									<div class="form-group field-waiver-lbt_due_date required">
										<label class="control-label" for="waiver-lbt_due_date">End Date</label>
										<input id="waiver-lbt_due_date" class="form-control datepicker" required  name="end_date" readonly="" size="10" type="text">
										<div class="help-block"></div>
									</div>
							</div>
							</div>


							{{--<div class="col-sm-3">--}}
							{{--<div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">--}}
							{{--<label class="control-label" for="type">Student Type</label>--}}
							{{--<select id="type" class="form-control" name="type" required>--}}
							{{--<option value="">--- Select Student Type ---</option>--}}
							{{--<option value="1" @if(old('type')==1) selected="selected" @endif>Pre Admission</option>--}}
							{{--<option value="2" @if(old('type')==2) selected="selected" @endif>Regular</option>--}}
							{{--</select>--}}
							{{--<div class="help-block">--}}
							{{--@if ($errors->has('type'))--}}
							{{--<strong>{{ $errors->first('type') }}</strong>--}}
							{{--@endif--}}
							{{--</div>--}}
							{{--</div>--}}
							{{--</div>--}}

							{{--<div class="col-sm-3">--}}
							{{--<div class="form-group {{ $errors->has('passport_no') ? ' has-error' : '' }}">--}}
							{{--<label class="control-label" for="passport_no">Passport No. </label>--}}
							{{--<input type="text" id="passport_no" class="form-control" name="passport_no" value="{{old('passport_no')}}" required>--}}
							{{--<div class="help-block">--}}
							{{--@if ($errors->has('passport_no'))--}}
							{{--<strong>{{ $errors->first('passport_no') }}</strong>--}}
							{{--@endif--}}
							{{--</div>--}}
							{{--</div>--}}
							{{--</div>--}}

							{{--<div class="col-sm-3">--}}
							{{--<div class="form-group {{ $errors->has('residency') ? ' has-error' : '' }}">--}}
							{{--<label class="control-label" for="residency">Residential Status</label>--}}
							{{--<select id="residency" class="form-control" name="residency" required>--}}
							{{--<option value="">--- Select Residential Status ---</option>--}}
							{{--<option value="1" @if(old('residency')=="1") selected="selected" @endif>Residential</option>--}}
							{{--<option value="0" @if(old('residency')=="0") selected="selected" @endif>Non-Residential</option>--}}
							{{--</select>--}}
							{{--<div class="help-block">--}}
							{{--@if ($errors->has('residency'))--}}
							{{--<strong>{{ $errors->first('residency') }}</strong>--}}
							{{--@endif--}}
							{{--</div>--}}
							{{--</div>--}}
							{{--</div>--}}
							{{--</div>--}}
						</div>

						<!-- /.box-body -->
						<div class="box-footer pull-right">
							<a class="btn btn-default btn-create" href="/">Cancel</a>
							<button type="submit" class="btn btn-primary btn-create">Create</button>
						</div><!-- /.box-footer-->
					</form>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
        $(document).ready(function(){

//            $('#dob').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
//            $('#enrolled_at').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});

            $('#dob').datepicker({
                autoclose: true
            });

            $('#enrolled_at').datepicker({
                autoclose: true
            });


			// waiver check box here
            $('#waiverCheckBoxSeclect').change(function(){
                if(this.checked) {
                    $('#waiverCheckBoxSeclectHidden').disabled = true;
                    $('.waiverSection').fadeIn('slow');
                }
                else {
                    $('.waiverSection').fadeOut('slow');
                }

            });



            //        $('#waiver-name').attr('disabled', 'disabled');
            $('.datepicker').datepicker({"dateFormat":"dd-mm-yy"});

            $(document).ready(function(){
                $('input[name="type"]').change(function(){
                    if($('#percent').prop('checked')){
                        $("#addOn").html("");
                        $("#addOn").html("%");

                    }else{
                        $("#addOn").html("");
                        $("#addOn").html("৳");
                    }
                });
            });


            // add letters only to the validator
            $.validator.addMethod("lettersonlys", function(value, element) {
                return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
            }, "Letters only please");
            // username
            $.validator.addMethod("loginRegex", function(value, element) {
                return this.optional(element) || /^[a-z0-9\-]+$/i.test(value);
            }, "Username must contain only letters, numbers, or dashes.");


            // validate signup form on keyup and submit
            var validator = $("#student_create_form").validate({
                // Specify validation rules
                rules: {
                    first_name: {
                        required: true,
                        minlength: 2,
                        maxlength: 20,
                    },
                    gender: 'required',
                    dob: {
                        required: true,
                    },
                    birth_place: {
                        required: false,
                        minlength: 2,
                        maxlength: 20,
                        lettersonlys: true
                    },
                    email: {
                        required: true,
                        minlength: 4,
                        maxlength:60,
                    },
                    phone: {
                        required: true,
                        number: true,
                        minlength: 11,
                        maxlength: 15,
                    },

                    nationality: 'required',
                    gr_no: {
                        required: false,
                        minlength: 1,
                        maxlength: 20,
                    },
                    campus: 'required',
                    academic_level: 'required',
                    batch:{
                        required: true,
                    },
                    section: 'required',
                    residency: 'required',
                    enrolled_at: {
                        required: true,
                    },
                    academic_year: 'required',
                    addmission_year: 'required',
                },

                // Specify validation error messages
                messages: {
                },

                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },

                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                    $(element).closest('.form-group').addClass('has-success');
                },

                debug: true,
                success: "valid",
                errorElement: 'span',
                errorClass: 'help-block',

                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },

                submitHandler: function(form) {
                    form.submit();
                }
            });


            // request for batch list using level id
            jQuery(document).on('change','.academicYear',function(){
                // console.log("hmm its change");

                // get academic year id
                var year_id = $(this).val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "{{ url('/academics/find/level') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': year_id }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        console.log(year_id);

                    },

                    success:function(data){
                        console.log('success');

                        //console.log(data.length);
                        op+='<option value="0" selected disabled>--- Select Level ---</option>';
                        for(var i=0;i<data.length;i++){
                            // console.log(data[i].level_name);
                            op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                        }

                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');

                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append('<option value="" selected disabled>--- Select {{$batchString}} ---</option>');

                        // set value to the academic batch
                        $('.academicLevel').html("");
                        $('.academicLevel').append(op);
                    },

                    error:function(){

                    }
                });
            });

            // request for batch list using level id
            jQuery(document).on('change','.academicLevel',function(){
                // console.log("hmm its change");

                // get academic level id
                var level_id = $(this).val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "{{ url('/academics/find/batch') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': level_id }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // console.log(level_id);
                    },

                    success:function(data){
                        console.log('success');

                        //console.log(data.length);
                        op+='<option value="" selected disabled>--- Select {{$batchString}} ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                        }

                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append(op);

                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');
                    },

                    error:function(){

                    }
                });
            });


            // request for section list using batch id
            jQuery(document).on('change','.academicBatch',function(){
                console.log("hmm its change");

                // get academic level id
                var batch_id = $(this).val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "{{ url('/academics/find/section') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': batch_id }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        console.log(batch_id);
                    },

                    success:function(data){
                        console.log('success');

                        //console.log(data.length);
                        op+='<option value="" selected disabled>--- Select Section ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                        }

                        // set value to the academic batch
                        $('.academicSection').html("");
                        $('.academicSection').append(op);
                    },

                    error:function(){

                    },
                });
            });

        });
	</script>
@endsection

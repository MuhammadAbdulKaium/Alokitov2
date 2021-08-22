@extends('admission::layouts.admission-layout')
<!-- page content -->
@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1><i class="fa fa-plus-square"></i> Online Application</h1>
			<ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Enquiry</a></li>
				<li><a href="#">Manage Enquiry</a></li>
				<li class="active">Online Application</li>
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
				<form id="online-admission-form" action="{{url('/admission/application')}}" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="box-body">

						{{--personal information--}}
						<fieldset>
							<legend>
								<abbr title="Enter your personal information like. First name, Middle name, Last name, Gender, DOB, etc." data-toggle = "tooltip">
									<i class="fa fa-user"></i>
									Personal Information
								</abbr>
							</legend>
							<div class="row">
								<div class="col-sm-3">
									<div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
										<label class="control-label" for="title">Title</label>
										<select id="title" class="form-control" name="title" required>
											<option value="" selected disabled>--- Select Title ---</option>
											<option value="Mr." {{old('title')=='Mr.'?'selected':''}}>Mr.</option>
											<option value="Mrs." {{old('title')=='Mrs.'?'selected':''}}>Mrs.</option>
											<option value="Ms." {{old('title')=='Ms.'?'selected':''}}>Ms.</option>
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
										<label class="control-label" for="first_name">First Name</label>
										<input type="text" id="first_name" class="form-control" name="first_name" value="{{old('first_name')}}" maxlength="50" placeholder="Enter First Name" required>
										<div class="help-block">
											@if ($errors->has('first_name'))
												<strong>{{ $errors->first('first_name') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group {{ $errors->has('middle_name') ? ' has-error' : '' }}">
										<label class="control-label" for="middle_name">Middle Name</label>
										<input type="text" id="middle_name" class="form-control" value="{{old('middle_name')}}" name="middle_name" maxlength="50" placeholder="Enter Middle Name" required>
										<div class="help-block">
											@if ($errors->has('middle_name'))
												<strong>{{ $errors->first('middle_name') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
										<label class="control-label" for="last_name">Last Name</label>
										<input type="text" id="last_name" class="form-control" name="last_name" value="{{old('last_name')}}" maxlength="50" placeholder="Enter Last Name" required>
										<div class="help-block">
											@if ($errors->has('last_name'))
												<strong>{{ $errors->first('last_name') }}</strong>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }}">
										<label class="control-label" for="gender">Gender</label>
										<select id="gender" class="form-control" name="gender" required>
											<option value="" selected disabled>--- Select Gender ---</option>
											<option value="0" {{old('gender')=='0'?'selected':''}}>Male</option>
											<option value="1" {{old('gender')=='1'?'selected':''}}>Female</option>
										</select>
										<div class="help-block">
											@if ($errors->has('gender'))
												<strong>{{ $errors->first('gender') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group {{ $errors->has('birth_date') ? ' has-error' : '' }}">
										<label class="control-label" for="birth_date">Date of Birth</label>
										<input type="text" id="birth_date" class="form-control" name="birth_date" value="{{old('birth_date')}}" placeholder="Select Date of Birth" readonly required>
										<div class="help-block">
											@if ($errors->has('birth_date'))
												<strong>{{ $errors->first('birth_date') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group {{ $errors->has('blood_group') ? ' has-error' : '' }}">
										<label class="control-label" for="blood_group">Blood Group</label>
										<select id="blood_group" class="form-control" name="blood_group" required>
											<option value="" selected disabled>--- Select Blood Group ---</option>
											<option value="Unknown" {{old('blood_group')=="Unknown"?'selected':''}}>Unknown</option>
											<option value="A+" {{old('blood_group')=="A+"?'selected':''}}>A+</option>
											<option value="A-" {{old('blood_group')=="A-"?'selected':''}}>A-</option>
											<option value="B+" {{old('blood_group')=="B+"?'selected':''}}>B+</option>
											<option value="B-" {{old('blood_group')=="B-"?'selected':''}}>B-</option>
											<option value="AB+" {{old('blood_group')=="AB+"?'selected':''}}>AB+</option>
											<option value="AB-" {{old('blood_group')=="AB-"?'selected':''}}>AB-</option>
											<option value="O+" {{old('blood_group')=="O+"?'selected':''}}>O+</option>
											<option value="O-" {{old('blood_group')=="O-"?'selected':''}}>O-</option>
										</select>
										<div class="help-block">
											@if ($errors->has('blood_group'))
												<strong>{{ $errors->first('blood_group') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
										<label class="control-label" for="phone">Phone No</label>
										<input type="text" id="phone" class="form-control" name="phone" {{old('phone')}} maxlength="12" placeholder="Enter Mobile No.">
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
									<div class="form-group {{ $errors->has('religion') ? ' has-error' : '' }}">
										<label class="control-label" for="religion">Religion</label>
										<input type="text" id="religion" class="form-control" name="religion" {{old('religion')}} maxlength="50" placeholder="Enter Religion" required>
										<div class="help-block">
											@if ($errors->has('religion'))
												<strong>{{ $errors->first('religion') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group {{ $errors->has('nationality') ? ' has-error' : '' }}">
										<label class="control-label" for="nationality">Nationality</label>
										<select id="nationality" class="form-control" name="nationality" required>
											<option value="">--- Select Nationality ---</option>
											@foreach($allCountry as $country)
												<option value="{{$country->id}}" {{$country->id == old('nationality')?'selected':''}}>
													{{$country->nationality}}
												</option>
											@endforeach
										</select>
										<div class="help-block">
											@if ($errors->has('nationality'))
												<strong>{{ $errors->first('nationality') }}</strong>
											@endif
										</div>
									</div>
								</div>
							</div>
						</fieldset>

						{{--Select Course & Batch--}}
						<fieldset>
							<legend>
								<abbr title="You should select batch and course for admission. Process is first select course that you want to study than after base on course selection you need to select batch." data-toggle="tooltip">
									<i class="fa fa-graduation-cap"></i>
									Select Batch & Section
								</abbr>
							</legend>

							<div class="row">
								<div class="col-sm-4">
									<div class="form-group {{ $errors->has('academic_year') ? ' has-error' : '' }}">
										<label class="control-label" for="academic_year">Academic Year</label>
										<select id="academic_year" class="form-control academicYear" name="academic_year" required>
											<option value="" selected disabled>--- Select Year ---</option>
											@foreach($academicYears as $academicYear)
												<option value="{{$academicYear->id}}">{{$academicYear->year_name}}</option>
											@endforeach
										</select>
										<div class="help-block">
											@if ($errors->has('academic_year'))
												<strong>{{ $errors->first('academic_year') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group {{ $errors->has('academic_level') ? ' has-error' : '' }}">
										<label class="control-label" for="academic_level">Academic Level</label>
										<select id="academic_level" class="form-control academicLevel" name="academic_level" required>
											<option value="" selected disabled>--- Select Level ---</option>
										</select>
										<div class="help-block">
											@if ($errors->has('academic_level'))
												<strong>{{ $errors->first('academic_level') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group {{ $errors->has('batch') ? ' has-error' : '' }}">
										<label class="control-label" for="batch">Batch</label>
										<select id="batch" class="form-control academicBatch" name="batch" required>
											<option value="" selected disabled>--- Select Batch ---</option>
										</select>
										<div class="help-block">
											@if ($errors->has('batch'))
												<strong>{{ $errors->first('batch') }}</strong>
											@endif
										</div>
									</div>
								</div>
							</div>
						</fieldset>

						{{--address information--}}
						<fieldset>
							<legend>
								<abbr title="Enter your address information" data-toggle="tooltip">
									<i class="fa fa-home"></i>
									Address Information
								</abbr>
							</legend>
							<div class="col-md-12">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
											<label class="control-label" for="address">Address</label>
											<textarea id="address" class="form-control" name="address" maxlength="255" placeholder="Enter Address" required>{{old('address')}}</textarea>
											<div class="help-block">
												@if ($errors->has('address'))
													<strong>{{ $errors->first('address') }}</strong>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group {{ $errors->has('country') ? ' has-error' : '' }}">
											<label class="control-label" for="country">Country</label>
											<select id="country" class="form-control country" name="country" required>
												<option value="" selected disabled>--- Select Country ---</option>
												@foreach($allCountry as $country)
													<option value="{{$country->id}}">{{$country->name}}</option>
												@endforeach
											</select>
											<div class="help-block">
												@if ($errors->has('country'))
													<strong>{{ $errors->first('country') }}</strong>
												@endif
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->has('state') ? ' has-error' : '' }}">
											<label class="control-label" for="state">City</label>
											<select id="state" class="form-control state" name="state" required>
												<option value="" disabled>--- Select City  ---</option>
											</select>
											<div class="help-block">
												@if ($errors->has('state'))
													<strong>{{ $errors->first('state') }}</strong>
												@endif
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
											<label class="control-label" for="city">Area</label>
											<select id="city" class="form-control city" name="city" required>
												<option value="" disabled>--- Select Area---</option>
											</select>
											<div class="help-block">
												@if ($errors->has('city'))
													<strong>{{ $errors->first('city') }}</strong>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}">
											<label class="control-label" for="zip">Zip Code</label>
											<input type="text" id="zip" class="form-control" name="zip" maxlength="6" value="{{old('zip')}}" required placeholder="Enter Zip Code">
											<div class="help-block">
												@if ($errors->has('zip'))
													<strong>{{ $errors->first('zip') }}</strong>
												@endif
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->has('house_no') ? ' has-error' : '' }}">
											<label class="control-label" for="house_no">House No</label>
											<input type="text" id="house_no" class="form-control" name="house_no" value="{{old('house_no')}}" required maxlength="25" placeholder="Enter House No.">
											<div class="help-block">
												@if ($errors->has('house_no'))
													<strong>{{ $errors->first('house_no') }}</strong>
												@endif
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->has('phone_no') ? ' has-error' : '' }}">
											<label class="control-label" for="phone_no">Phone No</label>
											<input type="text" id="phone_no" class="form-control" name="phone_no" {{old('phone_no')}} required maxlength="25" placeholder="Enter home Phone No.">
											<div class="help-block">
												@if ($errors->has('phone_no'))
													<strong>{{ $errors->first('phone_no') }}</strong>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
						</fieldset>

						{{--upload documents--}}
						<fieldset>
							<legend>
								<abbr title="You need to login" data-toggle = "tooltip">
									<i class="fa fa-user"></i>
									User Login Details
								</abbr>
							</legend>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
										<label class="control-label" for="email">Email Id</label>
										<input type="email" id="email" class="form-control" name="email" {{old('email')}} maxlength="65" placeholder="Enter Email ID" required>
										<div class="help-block">
											@if ($errors->has('email'))
												<strong>{{ $errors->first('email') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
										<label class="control-label" for="password">Password</label>
										<input type="password" id="password" class="form-control" name="password" maxlength="65" placeholder="Enter password" required>
										<div class="help-block">
											@if ($errors->has('password'))
												<strong>{{ $errors->first('password') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group {{ $errors->has('confirm_password') ? ' has-error' : '' }}">
										<label class="control-label" for="confirm_password">Confirm Password</label>
										<input type="password" id="confirm_password" class="form-control" name="confirm_password" maxlength="65" placeholder="Confirm password" required>
										<div class="help-block">
											@if ($errors->has('confirm_password'))
												<strong>{{ $errors->first('confirm_password') }}</strong>
											@endif
										</div>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
					{{--/.box-body--}}

					<div class="box-footer text-center">
						<button type="submit" class="btn btn-primary btn-create">Submit</button>
						{{--<a class="btn btn-default btn-create" href="#">Cancel</a>--}}
					</div>
					{{--/.box-footer--}}
				</form>
			</div>
		</section>
	</div>
@endsection

@section('scripts')
	<script>
        $(document).ready(function () {

            //birth_date picker
            $('#birth_date').datepicker({
                autoclose: true
            });

            // request for batch list using level id
            jQuery(document).on('change','.academicYear',function(){
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
                        $('.academicBatch').append('<option value="" selected disabled>--- Select Batch ---</option>');

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
                        op+='<option value="" selected disabled>--- Select Batch ---</option>';
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


            // request state list for the selelcted prsent country
            jQuery(document).on('change','.country',function(){
                //console.log("hmm its change");

                // get country id
                var country_id = $(this).val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "{{ url('/setting/find/state') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': country_id }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        console.log(country_id);

                    },

                    success:function(data){
                        //console.log('success');

                        //console.log(data.length);
                        op+='<option value="0" selected disabled>--- Select City---</option>';
                        for(var i=0;i<data.length;i++){
                            // console.log(data[i].level_name);
                            op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                        }

                        // set value to the academic batch
                        $('.state').html("");
                        $('.state').append(op);

                        // set value to the academic batch
                        $('.city').html("");
                        $('.city').append('<option value="" selected disabled>--- Select City ---</option>');
                    },

                    error:function(){

                    }
                });
            });


            // request state list for the selelcted prsent country
            jQuery(document).on('change','.state',function(){
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
                        op+='<option value="0" selected disabled>--- Select Area---</option>';
                        for(var i=0;i<data.length;i++){
                            // console.log(data[i].level_name);
                            op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                        }

                        // set value to the academic batch
                        $('.city').html("");
                        $('.city').append(op);
                    },

                    error:function(){

                    }
                });
            });
        });
	</script>
@endsection

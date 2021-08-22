
@extends('layouts.master')

<!-- page content -->
@section('content')
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
      @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-check"></i> {{ Session::get('message') }} </h4>
        </div>
      @elseif(Session::has('warning'))
        <div class="alert alert-warning alert-dismissible">
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
              <div class="col-sm-6">
                <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
                  <label class="control-label" for="type">Student Type</label>
                  <select id="type" class="form-control" name="type" required>
                    <option value="">--- Select Student Type ---</option>
                    <option value="1" @if(old('type')==1) selected="selected" @endif>Type one</option>
                    <option value="2" @if(old('type')==2) selected="selected" @endif>Type two</option>
                    <option value="3" @if(old('type')==3) selected="selected" @endif>Type three</option>
                  </select>
                  <div class="help-block">
                    @if ($errors->has('type'))
                      <strong>{{ $errors->first('type') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                  <label class="control-label" for="title">Student Title</label>
                  <select id="title" class="form-control" name="title" required>
                    <option value="">--- Select Student Title ---</option>
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
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                  <label class="control-label" for="first_name">First Name</label>
                  <input type="text" id="first_name" class="form-control" name="first_name" value="{{old('first_name')}}" required>
                  <div class="help-block">
                    @if ($errors->has('first_name'))
                      <strong>{{ $errors->first('first_name') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('middle_name') ? ' has-error' : '' }}">
                  <label class="control-label" for="middle_name">Middle Name</label>
                  <input type="text" id="middle_name" class="form-control" name="middle_name" value="{{old('middle_name')}}" required>
                  <div class="help-block">
                    @if ($errors->has('middle_name'))
                      <strong>{{ $errors->first('middle_name') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                  <label class="control-label" for="last_name">Last Name: <span class="required">*</span></label>
                  <input type="text" id="last_name" class="form-control" name="last_name" value="{{old('last_name')}}" required>
                  <div class="help-block">
                    @if ($errors->has('last_name'))
                      <strong>{{ $errors->first('last_name') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }}">
                  <label class="control-label" for="gender">Gender</label>
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
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                  <label class="control-label" for="email">Email/Login Id</label>
                  <input type="email" id="email" class="form-control" name="email" maxlength="60" value="{{old('email')}}" required>
                  <div class="help-block">
                    @if ($errors->has('email'))
                      <strong>{{ $errors->first('email') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                  <label class="control-label" for="phone">Phone No</label>
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
              <div class="col-sm-4">
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
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('religion') ? ' has-error' : '' }}">
                  <label class="control-label" for="religion">Religion</label>
                  <select id="religion" class="form-control" name="religion" required>
                    <option value="">--- Select Religion ---</option>
                    <option value="1" @if(old('religion')==1) selected="selected" @endif>Type one</option>
                    <option value="2" @if(old('religion')==2) selected="selected" @endif>Type twp</option>
                    <option value="3" @if(old('religion')==3) selected="selected" @endif>Type three</option>
                  </select>
                  <div class="help-block">
                    @if ($errors->has('religion'))
                      <strong>{{ $errors->first('religion') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
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
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
                  <label class="control-label" for="dob">Date of Birth</label>
                  <input type="text" id="dob" class="form-control" name="dob" value="{{old('dob')}}" required readonly>
                  <div class="help-block">
                    @if ($errors->has('dob'))
                      <strong>{{ $errors->first('dob') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('passport_no') ? ' has-error' : '' }}">
                  <label class="control-label" for="passport_no">Passport No. </label>
                  <input type="text" id="passport_no" class="form-control" name="passport_no" value="{{old('passport_no')}}" required>
                  <div class="help-block">
                    @if ($errors->has('passport_no'))
                      <strong>{{ $errors->first('passport_no') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('nationality') ? ' has-error' : '' }}">
                  <label class="control-label" for="nationality">Nationality</label>
                  <select id="student_nationality" class="form-control" name="nationality" required>
                    <option value="">--- Select Nationality ---</option>
                    <option value="1" @if(old('nationality')==1) selected="selected" @endif>British</option>
                    <option value="2" @if(old('nationality')==2) selected="selected" @endif>French</option>
                    <option value="3" @if(old('nationality')==3) selected="selected" @endif>German</option>
                  </select>
                  <div class="help-block">
                    @if ($errors->has('nationality'))
                      <strong>{{ $errors->first('nationality') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <h2 class="page-header edusec-page-header-1">
              <i class="fa fa-info-circle"></i> Academic Details
            </h2>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('gr_no') ? ' has-error' : '' }}">
                  <label class="control-label" for="gr_no">GR No.</label>
                  <input type="text" id="gr_no" class="form-control" name="gr_no" value="{{old('gr_no')}}" required>
                  <div class="help-block">
                    @if ($errors->has('gr_no'))
                      <strong>{{ $errors->first('gr_no') }}</strong>
                    @endif
                  </div>
                </div>
              </div>

              <!-- 'academicLevels', 'academicBatches', 'academicSections', 'academicYears' -->

              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('campus') ? ' has-error' : '' }}">
                  <label class="control-label" for="">Campus </label>
                  <select id="campus" class="form-control" name="campus" required>
                    <option value="">--- Select Student Campus ---</option>
                    <option value="1" @if(old('campus')==1) selected="selected" @endif>Campus one </option>
                    <option value="2" @if(old('campus')==2) selected="selected" @endif>Campus two </option>
                  </select>
                  <div class="help-block">
                    @if ($errors->has('campus'))
                      <strong>{{ $errors->first('campus') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('academic_level') ? ' has-error' : '' }}">
                  <label class="control-label" for="academic_level">Academic Level</label>
                  <select id="academic_level" class="form-control academicLevel" name="academic_level" required>
                    <option value="">--- Select Course ---</option>
                    <!-- @foreach($academicLevels as $level) -->
                    <!-- <option value="{{$level->id}}">{{$level->academic_level_name}}</option> -->
                    <option value="1">level one</option>
                    <option value="1">level two</option>
                    <option value="1">level three</option>
                    <!-- @endforeach -->
                  </select>
                  <div class="help-block">
                    @if ($errors->has('academic_level'))
                      <strong>{{ $errors->first('academic_level') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('batch') ? ' has-error' : '' }}">
                  <label class="control-label" for="batch">Batch</label>
                  <select id="batch" class="form-control academicBatch" name="batch" required>
                    <option value="" disabled="true" selected="true">Batch Name</option>
                  </select>
                  <div class="help-block">
                    @if ($errors->has('batch'))
                      <strong>{{ $errors->first('batch') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('section') ? ' has-error' : '' }}">
                  <label class="control-label" for="section">Section</label>
                  <select id="section" class="form-control academicSection" name="section" required>
                    <option value="" disabled="true" selected="true">Section Name</option>
                  </select>
                  <div class="help-block">
                    @if ($errors->has('section'))
                      <strong>{{ $errors->first('section') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('residency') ? ' has-error' : '' }}">
                  <label class="control-label" for="residency">Residential Status</label>
                  <select id="residency" class="form-control" name="residency" required>
                    <option value="">--- Select Residential Status ---</option>
                    <option value="3" @if(old('residency')==1) selected="selected" @endif>type one</option>
                    <option value="1" @if(old('residency')==2) selected="selected" @endif>type two</option>
                    <option value="2" @if(old('residency')==3) selected="selected" @endif>type three</option>
                  </select>
                  <div class="help-block">
                    @if ($errors->has('residency'))
                      <strong>{{ $errors->first('residency') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('academic_year') ? ' has-error' : '' }}">
                  <label class="control-label" for="academic_year">Academic Year</label>
                  <select id="academic_year" class="form-control" name="academic_year" required>
                    <option value="">--- Select Academic Year ---</option>
                    <!-- @foreach($academicYears as $year) -->
                    <!-- <option value="{{$year->id}}">{{$year->academic_name}}</option> -->
                    <option value="1">year one</option>
                    <option value="2">year two</option>
                    <option value="3">year three</option>
                    <!-- @endforeach -->
                  </select>
                  <div class="help-block">
                    @if ($errors->has('academic_year'))
                      <strong>{{ $errors->first('academic_year') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('admission_year') ? ' has-error' : '' }}">
                  <label class="control-label" for="admission_year">Admission Year</label>
                  <select id="admission_year" class="form-control" name="admission_year" required>
                    <option value="">--- Select Admission Year ---</option>
                    <option value="1" @if(old('admission_year')==1) selected="selected" @endif>2015-16</option>
                    <option value="2" @if(old('admission_year')==2) selected="selected" @endif>2016-17</option>
                    <option value="3" @if(old('admission_year')==3) selected="selected" @endif>2017-18</option>
                  </select>
                  <div class="help-block">
                    @if ($errors->has('admission_year'))
                      <strong>{{ $errors->first('admission_year') }}</strong>
                    @endif
                  </div>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="form-group {{ $errors->has('enrolled_at') ? ' has-error' : '' }}">
                  <label class="control-label" for="enrolled_at">Date of Enrol</label>
                  <input type="text" id="enrolled_at" class="form-control" name="enrolled_at" value="{{old('enrolled_at')}}" required readonly>
                  <div class="help-block">
                    @if ($errors->has('enrolled_at'))
                      <strong>{{ $errors->first('enrolled_at') }}</strong>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <button type="submit" class="btn btn-primary btn-create">Create</button>
              <a class="btn btn-default btn-create" href="">Cancel</a>
            </div><!-- /.box-footer-->
          </form>
        </div>
    </section>
  </div>
@endsection

@section('scripts')
  <script type="text/javascript">
    $(document).ready(function(){

            jQuery('#dob').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
            jQuery('#enrolled_at').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});

      // validate signup form on keyup and submit
      $("#student_create_form").validate({
                // Specify validation rules
                rules: {
                    type: {
                      required: true,
                  },

                    title: {
                      required: true,
                  },
                    first_name: {
                        required: true,
                    },
                    middle_name: {
                      required: true,
                    },
                    last_name: {
                      required: true,
                    },
                    gender: {
                      required: true,
                    },
                    dob: {
                      required: true,
                    },
                    blood_group: {
                      required: true,
                    },
                    religion: {
                      required: true,
                    },
                    birth_place: {
                      required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                        minlength: 5,
                        maxlength:100,
                    },
                    phone: {
                        required: true,
                        number: true,
                        minlength: 11,
                        maxlength: 12,

                    },
                    passport_no: {
                      required: true,
                    },
                    nationality: {
                      required: true,
                    },

                    gr_no: {
                      required: true,
                    },
                    campus: {
                      required: true,
                    },
                    academic_level:{
                      required: true,
                    },
                    batch:{
                      required: true,
                    },
                    section: {
                      required: true,
                    },
                    residency: {
                      required: true,
                    },
                    enrolled_at: {
                      required: true,
                    },
                    academic_year: {
                      required: true,
                    },
                    addmission_year: {
                      required: true,
                    },
                },

                // Specify validation error messages
                messages: {
                    type: {
                      required: 'Please select student type',
                    },
                    title:{
                      required: 'Please select student title',
                    },
                    first_name: {
                      required: 'Please enter student first name',
                    },
                    middle_name: {
                      required: 'Please enter student middle name',
                    },
                    last_name: {
                      required: 'Please enter student last name',
                    },
                    gender: {
                      required: 'Please select student gender',
                    },
                    dob: {
                      required: 'Please select student birth date',
                    },
                    blood_group: {
                      required: 'Please select student blood group',
                    },
                    religion: {
                      required: 'Please select student religion',
                    },
                    birth_place: {
                      required: 'Please enter student birth place',
                    },
                    email: {
                        required: 'Please enter student email address',
                        email: 'Please enter a vaild email address',
                        minlength: "Email address can't contain at most 100 characters.",
                        maxlength: 'Email address should contain at most 60 characters.',
                    },
                    phone: {
                        required: 'Please enter student phone number',
                        number: 'phone number must be number',
                        minlength: 'phone number must have 11 charater',
                        maxlength: 'phone number can not be more than 12 charater',
                        
                    },
                    passport_no: {
                      required: 'Please enter student passport no',
                    },
                    nationality: {
                      required: 'Please select student nationality',
                    },
                    gr_no: {
                      required: 'Please enter student GR No',
                    },
                    campus: {
                      required: 'Please select student Campus',
                    },
                    academic_level: {
                      required: 'Please select a academic level',
                    },
                    section: {
                      required: 'Please select a section',
                    },
                    residency: {
                      required: 'Please select a Residential Status',
                    },
                    enrolled_at: {
                      required: 'Please enter enroll date ',
                    },
                    academic_year: {
                      required: 'Please select a academic year',
                    },
                    addmission_year: {
                      required: 'Please select a admission year',
                    },
                },

                highlight: function(element) {
                  $(element).closest('.form-group').addClass('has-error');
              },

              unhighlight: function(element) {
                  $(element).closest('.form-group').removeClass('has-error');
              },

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
          jQuery(document).on('change','.academicLevel',function(){
              // console.log("hmm its change");

              // get academic level id
              var level_id = $(this).val();
              // console.log(cat_id);
              var div = $(this).parent();
              var op="";

          $.ajax({
              url: "{{ url('/academics/find/batch/') }}",
              type: 'GET',
              cache: false,
              data: {'id': level_id }, //see the $_token
              datatype: 'application/json',

              beforeSend: function() {
                console.log(level_id);
              },

                  success:function(data){
                      console.log('success');

                      // console.log(data[i].batch_name);

                      //console.log(data.length);
                      op+='<option value="0" selected disabled>Select Batch</option>';
                      for(var i=0;i<data.length;i++){
                         // console.log(data[i].batch_name);
                      op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                     }

                     // set value to the academic secton
                     $('.academicSection').html("");
                     $('.academicSection').append('<option value="0" selected disabled>Section Name</option>');

                     // set value to the academic batch
                     $('.academicBatch').html("");
                     $('.academicBatch').append(op);
                  },

                  error:function(){

                  }
              });
          });

          // request for section list using batch id
          jQuery(document).on('change','.academicBatch',function(){
              // console.log("hmm its change");

              // get academic level id
              var level_id = $(this).val();
              // console.log(cat_id);
              var div = $(this).parent();
              var op="";

          $.ajax({
              url: "{{ url('/academics/find/section/') }}",
              type: 'GET',
              cache: false,
              data: {'id': level_id }, //see the $_token
              datatype: 'application/json',

              beforeSend: function() {
                console.log(level_id);
              },

                  success:function(data){
                      console.log('success');

                      // console.log(data[i].batch_name);

                      //console.log(data.length);
                      op+='<option value="0" selected disabled>Select Batch</option>';
                      for(var i=0;i<data.length;i++){
                         // console.log(data[i].batch_name);
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

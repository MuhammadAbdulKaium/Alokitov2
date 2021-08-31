
<form id="student_information_update_form" name="student_information_update_form" action="{{url('/student/profile/personal/update', [$personalInfo->id])}}" method="POST">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
   <div class="modal-header">
      <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
      <h3 class="modal-title">
         <i class="fa fa-info-circle"></i> Personal Details
      </h3>
   </div>
   <!--modal-header-->
   <div class="modal-body">
      <div class="row">
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
               <label class="control-label" for="type">Student Type</label>
               <select id="type" class="form-control" name="type">
                  <option value="">--- Select Student Type ---</option>
                  <option value="2" @if($personalInfo->type =='1') selected="selected" @endif>Pre Admission</option>
                  <option value="1" @if($personalInfo->type =='2') selected="selected" @endif>Regular</option>
               </select>
               <div class="help-block">
                  @if ($errors->has('type'))
                     <strong>{{ $errors->first('type') }}</strong>
                  @endif
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
               <label class="control-label" for="title">Student Title</label>
               <select id="title" class="form-control" name="title">
                  <option value="">--- Select Student Title ---</option>
                  <option value="Cadet" @if($personalInfo->title=='Cadet') selected="selected" @endif>Student</option>
                  <option value="FM" @if($personalInfo->title=='FM') selected="selected" @endif>FM</option>
                  <option value="Mr." @if($personalInfo->title =='Mr.') selected="selected" @endif>Mr.</option>
                  <option value="Mrs." @if($personalInfo->title =='Mrs.') selected="selected" @endif>Mrs.</option>
                  <option value="Ms." @if($personalInfo->title =='Ms.') selected="selected" @endif>Ms.</option>
               </select>
               <div class="help-block">
                  @if ($errors->has('title'))
                     <strong>{{ $errors->first('title') }}</strong>
                  @endif
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('batch_no') ? ' has-error' : '' }}">
               <label class="control-label" for="batch_no">Batch No</label>
               <input id="batch_no" class="form-control" name="batch_no" value="{{$personalInfo->batch_no}}" type="text">
               <div class="help-block">
                  @if ($errors->has('batch_no'))
                     <strong>{{ $errors->first('batch_no') }}</strong>
                  @endif
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
               <label class="control-label" for="first_name">First Name <span class="text-red">*</span></label>
               <input id="first_name" class="form-control" name="first_name" value="{{$personalInfo->first_name}}" type="text">
               <div class="help-block">
                  @if ($errors->has('first_name'))
                     <strong>{{ $errors->first('first_name') }}</strong>
                  @endif
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
               <label class="control-label" for="last_name">Last Name</label>
               <input id="last_name" class="form-control" name="last_name" value="{{$personalInfo->last_name}}" type="text">
               <div class="help-block">
                  @if ($errors->has('last_name'))
                     <strong>{{ $errors->first('last_name') }}</strong>
                  @endif
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('language') ? ' has-error' : '' }}">
               <label class="control-label" for="language">Language</label>
               <input id="language" class="form-control" name="language" value="{{$personalInfo->language}}" type="text">
               <div class="help-block">
                  @if ($errors->has('language'))
                     <strong>{{ $errors->first('language') }}</strong>
                  @endif
               </div>
            </div>
         </div>
      </div>

      <div class="row">

         <div class="col-sm-8">
            <div class="form-group">
               <label class="control-label" for="">ছাত্রের পূর্ণ নামঃ </label>
               <input type="text" id="bn_fullname" class="form-control" name="bn_fullname" value="{{$personalInfo->bn_fullname}}">
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="">Student Number* </label>
               <input type="text" id="email" class="form-control" name="email" value="{{$personalInfo->email}}" required>
            </div>
         </div>

      </div>

      <div class="row">
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
               <label class="control-label" for="phone">Mobile No</label>
               <input id="phone" class="form-control" name="phone" value="{{$personalInfo->phone}}" maxlength="12" type="text">
               <div class="help-block">
                  @if ($errors->has('phone'))
                     <strong>{{ $errors->first('phone') }}</strong>
                  @endif
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }}">
               <label class="control-label" for="gender">Gender <span class="text-red">*</span></label>
               <select id="gender" class="form-control" name="gender">
                  <option value="">--- Select Gender ---</option>
                  <option value="Male" @if($personalInfo->gender =="Male") selected="selected" @endif>Male</option>
                  <option value="Female" @if($personalInfo->gender =="Female") selected="selected" @endif>Female</option>
               </select>
               <div class="help-block">
                  @if ($errors->has('gender'))
                     <strong>{{ $errors->first('gender') }}</strong>
                  @endif
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
               <label class="control-label" for="dob">Date of Birth <span class="text-red">*</span></label>
               <input id="dob" class="form-control" name="dob" value="{{date('Y-m-d', strtotime($personalInfo->dob))}}" size="10" type="text" readonly>
               <div class="help-block">
                  @if ($errors->has('dob'))
                     <strong>{{ $errors->first('dob') }}</strong>
                  @endif
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('birth_place') ? ' has-error' : '' }}">
               <label class="control-label" for="birth_place">Birthplace</label>
               <input id="birth_place" class="form-control" name="birth_place" value="{{$personalInfo->birth_place}}" maxlength="45" type="text">
               <div class="help-block">
                  @if ($errors->has('birth_place'))
                     <strong>{{ $errors->first('birth_place') }}</strong>
                  @endif
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('nationality') ? ' has-error' : '' }}">
               <label class="control-label" for="nationality">Nationality</label>
               <select id="nationality" class="form-control" name="nationality">
                  <option value="">--- Select Nationality ---</option>
                  @if($allNationality)
                     @foreach($allNationality as $nationality)
                        <option value="{{$nationality->id}}" {{$nationality->id==$personalInfo->nationality?'selected':''}}>{{$nationality->nationality}}</option>
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
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('religion') ? ' has-error' : '' }}">
               <label class="control-label" for="religion">Religion</label>
               <select id="religion" class="form-control" name="religion">
                  <option value="">--- Select Religion ---</option>
                  <option value="1" {{$personalInfo->religion =='1'?'selected':''}}>Islam</option>
                  <option value="2" {{$personalInfo->religion =='2'?'selected':''}}>Hinduism</option>
                  <option value="3" {{$personalInfo->religion =='3'?'selected':''}}>Christianity</option>
                  <option value="4" {{$personalInfo->religion =='4'?'selected':''}}>Buddhism</option>
                  <option value="5" {{$personalInfo->religion =='5'?'selected':''}}>Others</option>
               </select>
               <div class="help-block">
                  @if ($errors->has('religion'))
                     <strong>{{ $errors->first('religion') }}</strong>
                  @endif
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <div class="form-group {{ $errors->has('blood_group') ? ' has-error' : '' }}">
               <label class="control-label" for="blood_group">Bloodgroup</label>
               <select id="blood_group" class="form-control" name="blood_group">
                  <option value="Unknown" selected="">Unknown</option>
                  <option value="A+" @if($personalInfo->blood_group =="A+") selected="selected" @endif>A+</option>
                  <option value="A-" @if($personalInfo->blood_group =="A-") selected="selected" @endif>A-</option>
                  <option value="B+" @if($personalInfo->blood_group =="B+") selected="selected" @endif>B+</option>
                  <option value="B-" @if($personalInfo->blood_group =="B-") selected="selected" @endif>B-</option>
                  <option value="AB+" @if($personalInfo->blood_group =="AB+") selected="selected" @endif>AB+</option>
                  <option value="AB-" @if($personalInfo->blood_group =="AB-") selected="selected" @endif>AB-</option>
                  <option value="O+" @if($personalInfo->blood_group =="O+") selected="selected" @endif>O+</option>
                  <option value="O-" @if($personalInfo->blood_group =="O-") selected="selected" @endif>O-</option>
               </select>
               <div class="help-block">
                  @if ($errors->has('blood_group'))
                     <strong>{{ $errors->first('blood_group') }}</strong>
                  @endif
               </div>
            </div>
         </div>
         <div class="col-sm-5">
            <div class="form-group {{ $errors->has('identification_mark') ? ' has-error' : '' }}">
               <label class="control-label" for="identification_mark">Identification Mark</label>
               <input id="identification_mark" class="form-control" name="identification_mark" value="{{$personalInfo->identification_mark}}" type="text">
               <div class="help-block">
                  @if ($errors->has('identification_mark'))
                     <strong>{{ $errors->first('identification_mark') }}</strong>
                  @endif
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group {{ $errors->has('tuition_fees') ? ' has-error' : '' }}">
               <label class="control-label" for="tuition_fees">Tuition Fees</label>
               <input id="tuition_fees" class="form-control" name="tuition_fees" value="@if($personalInfo->tuitionFees){{$personalInfo->tuitionFees->fees}}@endif" type="text">
               <div class="help-block">
                  @if ($errors->has('tuition_fees'))
                     <strong>{{ $errors->first('tuition_fees') }}</strong>
                  @endif
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-6">
            <div class="form-group {{ $errors->has('present_address') ? ' has-error' : '' }}">
               <label class="control-label" for="present_address">Present Address</label>
               <textarea name="present_address" id="present_address" class="form-control" rows="1">@if($personalInfo->presentAddress()){{$personalInfo->presentAddress()->address}}@endif</textarea>
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
               <textarea name="permanent_address" id="permanent_address" class="form-control" rows="1">@if($personalInfo->permanentAddress()){{$personalInfo->permanentAddress()->address}}@endif</textarea>
               <div class="help-block">
                  @if ($errors->has('permanent_address'))
                     <strong>{{ $errors->first('permanent_address') }}</strong>
                  @endif
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <button type="submit" class="btn btn-info">Update</button>
      <a class="btn btn-default pull-right" href="#" data-dismiss="modal">Cancel</a>
   </div>
</form>

<script type ="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#dob').datepicker({format: 'yyyy-mm-dd'});

//        jQuery("form[name='student_information_update_form']").validate({
//            // Specify validation rules
//            rules: {
//                student_type: "required",
//                student_prefix: "required",
//                student_firstname: "required",
//                student_middlename: "required",
//                student_lastname: "required",
//                student_gender: "required",
//                student_dob: "required",
//                student_blood_group: "required",
//                student_religion: "required",
//                student_birth_place: "required",
//                student_phone: {
//                    required: true,
//                    minlength: 11,
//                    maxlength: 15,
//                },
//                student_nationality: "required",
//            },
//
//            // Specify validation error messages
//            messages: {
//                student_type: "Please select studenttype",
//                student_prefix: "Please select student prefix",
//                student_firstname: "Please enter studnet firstname",
//                student_middlename: "Please enter studnet middlename",
//                student_lastname: "Please enter studnet lastname",
//                student_gender: "Please enter studnet gender",
//                student_dob: "Please enter student birthdate",
//                student_blood_group: "Please enter studnet blood group",
//                student_religion: "Please enter studnet religion",
//                student_birth_place: "Please enter studnet student birth place",
//                student_phone: {
//                    required: 'Please enter student phone number',
//                    minlength: 'phone number must have 11 charater',
//                    maxlength: 'phone number can not be more than 15 charater',
//                },
//                student_nationality: "Please select student nationality.",
//
//            },
//
//            submitHandler: function(form) {
//                form.submit();
//            }
//        });



    });

</script>
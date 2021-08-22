<div class="modal-header">
   <button aria-label="Close" data-dismiss="modal" class="close" type="button">
      <span aria-hidden="true">×</span>
   </button>
   <h4 class="modal-title">
      <i class="fa fa-plus-square"></i> Add Guardian
   </h4>
</div>
<form id="stu-master-update" action="{{url('/employee/profile/guardian/store/')}}" method="post" enctype="multipart/form-data">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
   <input type="hidden" name="emp_id" value="{{$emp_id}}">
   <div class="modal-body">
      <div class="row">
         <div class="col-md-6">
            <div class="col-sm-6">
               <div class="form-group">
                  <label class="control-label" for="title">Title</label>
                  <select id="title" class="form-control" name="title">
                     <option value="">--- Select Title ---</option>
                     <option value="Mr.">Mr.</option>
                     <option value="Mrs.">Mrs.</option>
                     <option value="Ms.">Ms.</option>
                     <option value="Prof.">Prof.</option>
                     <option value="Dr.">Dr.</option>
                  </select>
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-sm-6">
               <div class="form-group">
                  <label class="control-label" for="type">Type</label>
                  <select id="type" class="form-control" name="type">
                     <option value="" selected disabled>--- Select Type ---</option>
                     <option value="0">Mother</option>
                     <option value="1">Father</option>
                     <option value="2">Sister</option>
                     <option value="3">Brother</option>
                     <option value="4">Relative</option>
                     <option value="5">Other</option>
                     <option value="6">Spouse</option>
                     <option value="7">Son</option>
                     <option value="8">Daughter</option>
                  </select>
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-sm-6">
               <div class="form-group">
                  <label class="control-label" for="first_name">First Name</label>
                  <input id="first_name" class="form-control" name="first_name" maxlength="65" type="text" required>
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-sm-6">
               <div class="form-group">
                  <label class="control-label" for="last_name">Last Name</label>
                  <input id="last_name" class="form-control" name="last_name" maxlength="65" type="text" required>
                  <div class="help-block"></div>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="col-md-12">
               <div class="form-group">
                  <label for="">Photos</label>
                  <input type="file" name="guardian_photo" class="form-control">
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group">
                  <label for="">Signature</label>
                  <input type="file" name="guardian_signature" class="form-control">
               </div>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="first_name">বাংলায় পূর্ণ নাম</label>
               <input id="bn_fullname" class="form-control" name="bn_fullname" maxlength="65" type="text">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="last_name">শিক্ষাগত যোগ্যতা</label>
               <input id="last_name" class="form-control" name="bn_edu_qualification" maxlength="65" type="text">
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="email">Email/Login Id</label>
               <input id="email" class="form-control" name="email" maxlength="65" type="email">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-3">
            <div class="form-group">
               <label class="control-label">Date of Birth</label>
               <input type="text" id="dateOfBirth" class="form-control hasDatepicker" name="date_of_birth" maxlength="10"
                      placeholder="From Date" aria-required="true" size="10">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-3">
            <div class="form-froup">
               <label class="control-label">Gender</label>
               <select name="gender" class="form-control">
                  <option value="">Choose Gender</option>
                  <option value="1">Male</option>
                  <option value="2">Female</option>
                  <option value="3">Other</option>
               </select>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="mobile">Mobile No</label>
               <input id="mobile" class="form-control" name="mobile" maxlength="12" type="text" >
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="phone">Phone No</label>
               <input id="phone" class="form-control" name="phone" maxlength="25" type="text">
               <div class="help-block"></div>
            </div>
         </div>
         {{--<div class="col-sm-4">--}}
         {{--<div class="form-group">--}}
         {{--<label class="control-label" for="relation">Relation</label>--}}
         {{--<input id="relation" class="form-control" name="relation" maxlength="30" type="text" required>--}}
         {{--<div class="help-block"></div>--}}
         {{--</div>--}}
         {{--</div>--}}
      </div>
      <div class="row">
      </div>
      <div class="row">
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="income">Income</label>
               <input id="income" class="form-control" name="income" maxlength="50" type="text">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="qualification">Qualification</label>
               <input id="qualification" class="form-control" name="qualification" maxlength="50" type="text">
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="occupation">Occupation</label>
               <input id="occupation" class="form-control" name="occupation" maxlength="50" type="text">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="office_address">Office Address</label>
               <textarea id="office_address" class="form-control" name="office_address" maxlength="255"></textarea>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="home_address">Home Address</label>
               <textarea id="home_address" class="form-control" name="home_address" maxlength="255"></textarea>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label">Remarks</label>
               <textarea id="remarks" class="form-control" name="remarks" maxlength="255"></textarea>
               <div class="help-block"></div>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-sm-6">
            <div class="col-md-6">
               <div class="form-group">
                  <label class="control-label" for="home_address">NID Number</label>
                  <input type="text" class="form-control" name="nid_number" maxlength="255"/>
                  <input type="file" name="nid_file" class="form-control">
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label class="control-label" for="home_address">Birth Certificates Number</label>
                  <input type="text" class="form-control" name="birth_certificate" maxlength="255"/>
                  <input type="file" name="birth_file" class="form-control">
                  <div class="help-block"></div>
               </div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="home_address">TIN Number</label>
               <input type="text" class="form-control" name="tin_number" maxlength="255"/>
               <input type="file" name="tin_file" class="form-control">
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="home_address">Passport Number</label>
               <input type="text" class="form-control" name="passport_number" maxlength="255"/>
               <input type="file" name="passport_file" class="form-control">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="home_address">Driving License Number</label>
               <input type="text" class="form-control" name="dln" maxlength="255"/>
               <input type="file" name="driving_lic_file" class="form-control">
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div id="inputFormRow" style="margin: 10px 0; overflow: hidden">
            <div class="col-md-3">
               <div class="input-group">
                  <input type="text" name="institute_name[]" class="form-control m-input" placeholder="Institute" autocomplete="off">
               </div>
            </div>
            <div class="col-md-3">
               <div class="input-group">
                  <input type="text" name="certificate_name[]" class="form-control m-input" placeholder="Certificate Name" autocomplete="off">
               </div>
            </div>
            <div class="col-md-2">
               <div class="input-group">
                  <input type="text" name="passing_year[]" class="form-control m-input" placeholder="Year" autocomplete="off">
               </div>
            </div>
            <div class="col-md-2">
               <div class="input-group">
                  <input type="file" name="certificate_file[]" class="form-control m-input">
               </div>
            </div>
            <div class="col-md-2">
               <div class="input-group-append">
                  <button id="removeRow" type="button" class="btn btn-danger">-</button>
               </div>
            </div>
         </div>
         <div id="newRow"></div>
         <div style="margin: 10px 0; overflow: hidden">
            <div class="col-sm-10"></div>
            <div class="col-sm-2">
               <div class="input-group-append">
                  <button id="addRow" type="button" class="btn btn-info">+</button>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <button type="submit" class="btn btn-success pull-left">Create</button> <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
   </div>
</form>

<script type="text/javascript">
   $('#dateOfBirth').datepicker();

   $("#addRow").click(function () {
      var html = '';
      html += '<div id="inputFormRow" style="margin: 10px 0; overflow: hidden">';
      html += '<div class="col-md-3">';
      html += '<div class="input-group">';
      html += '<input type="text" name="institute_name[]" class="form-control m-input" placeholder="Institute" autocomplete="off">';
      html += '</div>';
      html += '</div>';
      html += '<div class="col-md-3">';
      html += '<div class="input-group">';
      html += '<input type="text" name="certificate_name[]" class="form-control m-input" placeholder="Certificate Name" autocomplete="off">';
      html += '</div>';
      html += '</div>';
      html += '<div class="col-md-2">';
      html += '<div class="input-group">';
      html += '<input type="text" name="passing_year[]" class="form-control m-input" placeholder="Year" autocomplete="off">';
      html += '</div>';
      html += '</div>';
      html += '<div class="col-md-2">';
      html += '<div class="input-group">';
      html += '<input type="file" name="certificate_file[]" class="form-control m-input" autocomplete="off">';
      html += '</div>';
      html += '</div>';
      html += '<div class="col-md-2">';
      html += '<div class="input-group-append">';
      html += '<button id="removeRow" type="button" class="btn btn-danger">-</button>';
      html += '</div>';
      html += '</div>';

      $('#newRow').append(html);
   });

   // remove row
   $(document).on('click', '#removeRow', function () {
      $(this).closest('#inputFormRow').remove();
   });
</script>

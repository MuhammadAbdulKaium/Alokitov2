
         <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button">
            <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">
               <i class="fa fa-plus-square"></i> Update Guardian        
            </h4>
         </div>
         <form id="stu-master-update" action="{{url('/student/profile/guardian/update', [$guardian->id])}}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="modal-body">
               <div class="row">
                  <div class="col-sm-3">
                     <div class="form-group">
                        <label class="control-label" for="title">Title</label>
                        <select id="title" class="form-control" name="title">
                           <option value="">--- Select Title ---</option>
                           <option value="Mr." @if($guardian) @if($guardian->title == 'Mr.') Selected @endif @endif>Mr.</option>
                           <option value="Mrs." @if($guardian) @if($guardian->title == 'Mrs.') Selected @endif @endif>Mrs.</option>
                           <option value="Ms." @if($guardian) @if($guardian->title == 'Ms.') Selected @endif @endif>Ms.</option>
                           <option value="Prof." @if($guardian) @if($guardian->title == 'Prof.') Selected @endif @endif>Prof.</option>
                           <option value="Dr." @if($guardian) @if($guardian->title == 'Dr.') Selected @endif @endif>Dr.</option>
                        </select>
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="form-group">
                        <label class="control-label" for="type">Type</label>
                        <select id="type" class="form-control" name="type">
                           <option value="" selected disabled>--- Select Type ---</option>
                           <option value="0" {{$guardian->type=='0'?'selected':''}}>Mother</option>
                           <option value="1" {{$guardian->type=='1'?'selected':''}}>Father</option>
                           <option value="2" {{$guardian->type=='2'?'selected':''}}>Sister</option>
                           <option value="3" {{$guardian->type=='3'?'selected':''}}>Brother</option>
                           <option value="4" {{$guardian->type=='4'?'selected':''}}>Relative</option>
                           <option value="5" {{$guardian->type=='5'?'selected':''}}>Other</option>
                        </select>
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Photos</label>
                        <input type="file" name="guardian_photo" class="form-control">
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-sm-3">
                     <div class="form-group">
                        <label class="control-label" for="first_name">First Name</label>
                        <input id="first_name" class="form-control" name="first_name" maxlength="65" type="text" value="@if($guardian){{$guardian->first_name}}@endif" required>
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="form-group">
                        <label class="control-label" for="last_name">Last Name</label>
                        <input id="last_name" class="form-control" name="last_name" value="@if($guardian){{$guardian->last_name}}@endif" maxlength="65" type="text">
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Signature</label>
                        <input type="file" name="guardian_signature" class="form-control">
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="first_name">বাংলায় পূর্ণ নাম</label>
                        <input id="bn_fullname" class="form-control" name="bn_fullname" value="@if($guardian){{$guardian->bn_fullname}}@endif" maxlength="65" type="text">
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="last_name">শিক্ষাগত যোগ্যতা</label>
                        <input id="last_name" class="form-control" name="bn_edu_qualification"  value="@if($guardian){{$guardian->bn_edu_qualification}}@endif" maxlength="65" type="text">
                        <div class="help-block"></div>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="email">Email/Login Id</label>
                        <input id="email" class="form-control" name="email" value="@if($guardian){{$guardian->email}}@endif" maxlength="65" type="email" required>
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="form-group">
                        <label class="control-label">Date of Birth</label>
                        <input type="text" id="dateOfBirth" class="form-control hasDatepicker" name="date_of_birth" maxlength="10"
                        placeholder="From Date" aria-required="true" size="10" value="@if($guardian) @if($guardian->date_of_birth) {{date('Y-m-d', strtotime($guardian->date_of_birth))}} @endif @endif">
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="form-froup">
                        <label class="control-label">Gender</label>
                        <select name="gender" class="form-control">
                           <option value="">Choose Gender</option>
                           <option value="1" {{($guardian->gender == 1)?"selected":""}}>Male</option>
                           <option value="2" {{($guardian->gender == 2)?"selected":""}}>Female</option>
                           <option value="3" {{($guardian->gender == 3)?"selected":""}}>Other</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="mobile">Mobile No</label>
                        <input id="mobile" class="form-control" name="mobile" value="@if($guardian){{$guardian->mobile}}@endif" maxlength="12" type="text">
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="phone">Phone No</label>
                        <input id="phone" class="form-control" name="phone" value="@if($guardian){{$guardian->phone}}@endif" maxlength="25" type="text">
                        <div class="help-block"></div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="income">Income</label>
                        <input id="income" class="form-control" name="income" value="@if($guardian){{$guardian->income}}@endif" maxlength="50" type="text">
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="qualification">Qualification</label>
                        <input id="qualification" class="form-control" name="qualification" value="@if($guardian){{$guardian->qualification}}@endif" maxlength="50" type="text">
                        <div class="help-block"></div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="occupation">Occupation</label>
                        <input id="occupation" class="form-control" name="occupation" value="@if($guardian){{$guardian->occupation}}@endif" maxlength="50" type="text">
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="office_address">Office Address</label>
                        <textarea id="office_address" class="form-control" name="office_address" maxlength="255"> @if($guardian){{$guardian->office_address}}@endif </textarea>
                        <div class="help-block"></div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="home_address">Home Address</label>
                        <textarea id="home_address" class="form-control" name="home_address" maxlength="255"> @if($guardian){{$guardian->home_address}}@endif </textarea>
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label">Remarks</label>
                        <textarea id="remarks" class="form-control" name="remarks" maxlength="255">@if($guardian){{$guardian->remarks}}@endif</textarea>
                        <div class="help-block"></div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label" for="home_address">NID Number</label>
                           <input type="text" class="form-control" name="nid_number" value="@if($guardian){{$guardian->nid_number}}@endif" maxlength="255"/>
                           <input type="file" name="nid_file" class="form-control">
                           <div class="help-block"></div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label" for="home_address">Birth Certificates Number</label>
                           <input type="text" class="form-control" name="birth_certificate" value="@if($guardian){{$guardian->birth_certificate}}@endif" maxlength="255"/>
                           <input type="file" name="birth_file" class="form-control">
                           <div class="help-block"></div>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="home_address">TIN Number</label>
                        <input type="text" class="form-control" name="tin_number" value="@if($guardian){{$guardian->tin_number}}@endif" maxlength="255"/>
                        <input type="file" name="tin_file" class="form-control">
                        <div class="help-block"></div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="home_address">Passport Number</label>
                        <input type="text" class="form-control" name="passport_number" value="@if($guardian){{$guardian->passport_number}}@endif" maxlength="255"/>
                        <input type="file" name="passport_file" class="form-control">
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="home_address">Driving License Number</label>
                        <input type="text" class="form-control" name="dln" value="@if($guardian){{$guardian->dln}}@endif" maxlength="255"/>
                        <input type="file" name="driving_lic_file" class="form-control">
                        <div class="help-block"></div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  @php
                     $instituteInfos = null;
                     if ($guardian->institute_info){
                         $instituteInfos = json_decode($guardian->institute_info, 1);
                     }
                  @endphp

                  @if($instituteInfos)
                     @foreach($instituteInfos as $instituteInfo)
                        <div id="inputFormRow" style="margin: 10px 0; overflow: hidden">
                           <div class="col-md-3">
                              <div class="input-group">
                                 <input type="text" name="institute_name[]" class="form-control m-input" value="{{$instituteInfo['institute_name']}}" autocomplete="off">
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="input-group">
                                 <input type="text" name="certificate_name[]" class="form-control m-input" value="{{$instituteInfo['certificate_name']}}" autocomplete="off">
                              </div>
                           </div>
                           <div class="col-md-2">
                              <div class="input-group">
                                 <input type="text" name="passing_year[]" class="form-control m-input" value="{{$instituteInfo['passing_year']}}" autocomplete="off">
                              </div>
                           </div>
                           <div class="col-md-2">
                              <div class="input-group">
                                 <input type="file" name="certificate_file[]" class="form-control m-input" placeholder="Enter title" autocomplete="off">
                              </div>
                           </div>
                           <div class="col-md-2">
                              <div class="input-group-append">
                                 <button id="removeRow" type="button" class="btn btn-danger">-</button>
                              </div>
                           </div>
                        </div>
                     @endforeach
                  @endif

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
               <button type="submit" class="btn btn-success pull-left">Update</button> <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
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
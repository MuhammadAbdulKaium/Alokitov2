
<form id="employee-update" action="{{url('/employee/profile/personal/update', [$employeeInfo->id])}}" method="POST">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
   <div class="modal-header">
      <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
      <h4 class="modal-title">
         <i class="fa fa-info-circle"></i> Personal Details
      </h4>
   </div>
   <div class="modal-body">
      <div class="row">
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="role">Role <span class="text-red">*</span></label>
               <select id="role" class="form-control" name="role">
                  <option value="">--- Select Role ---</option>
                  @foreach($allRole as $role)
                     <option value="{{$role->id}}" @if($employeeInfo->user()->roles()->count()>0) {{$employeeInfo->user()->roles()->first()->id==$role->id?'selected':''}} @endif>{{$role->display_name}}</option>
                  @endforeach
               </select>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="title">Title</label>
               <select id="title" class="form-control" name="title">
                  <option value="">--- Select Title ---</option>
                  <option value="Fm." @if($employeeInfo->title =="Fm.") selected="selected" @endif>Fm.</option>
                  <option value="Mr." @if($employeeInfo->title =="Mr.") selected="selected" @endif>Mr.</option>
                  <option value="Mrs." @if($employeeInfo->title =="Mrs.") selected="selected" @endif>Mrs.</option>
                  <option value="Ms." @if($employeeInfo->title =="Ms.") selected="selected" @endif>Ms.</option>
                  <option value="Prof." @if($employeeInfo->title =="Prof.") selected="selected" @endif>Prof.</option>
                  <option value="Dr." @if($employeeInfo->title =="Dr.") selected="selected" @endif>Dr.</option>
               </select>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="gender">Gender <span class="text-red">*</span></label>
               <select id="gender" class="form-control" name="gender" required>
                  <option value="">--- Select Gender ---</option>
                  <option value="Male" @if($employeeInfo->gender =="Male") selected="selected" @endif>Male</option>
                  <option value="Female" @if($employeeInfo->gender =="Female") selected="selected" @endif>Female</option>
               </select>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="first_name">First Name <span class="text-red">*</span></label>
               <input id="first_name" class="form-control" name="first_name" value="{{$employeeInfo->first_name}}" maxlength="35" type="text">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="employee_no">Employee No</label>
               <input id="employee_no" class="form-control" name="employee_no" value="{{$employeeInfo->employee_no}}" maxlength="35" type="text">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="last_name">Last Name</label>
               <input id="last_name" class="form-control" name="last_name" value="{{$employeeInfo->last_name}}" maxlength="35" type="text">
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="alias">Name Alias</label>
               <input id="alias" class="form-control" name="alias" value="{{$employeeInfo->alias}}" maxlength="10" type="text">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="dob">Date of Birth <span class="text-red">*</span></label>
               <input id="dob" class="form-control dob" name="dob" value="{{$employeeInfo->dob?date('m/d/Y', strtotime($employeeInfo->dob)):''}}" size="10" type="text" readonly required>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="birth_place">Birthplace</label>
               <input id="birth_place" class="form-control" name="birth_place" maxlength="50" type="text" value="{{$employeeInfo->birth_place}}">
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="nationality">Nationality</label>
               <select id="nationality" class="form-control" name="nationality" required>
                  <option value="">--- Select Nationality ---</option>
                  @if($allNationality)
                     @foreach($allNationality as $nationality)
                        <option value="{{$nationality->id}}" {{$employeeInfo->nationality==$nationality->id?'selected':''}}>{{$nationality->nationality}}</option>
                     @endforeach
                  @endif
               </select>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="religion">Religion</label>
               <select id="religion" class="form-control" name="religion">
                  <option value="">Select Religion</option>
                  <option value="1" {{$employeeInfo->religion =='1'?'selected':''}}>Islam</option>
                  <option value="2" {{$employeeInfo->religion =='2'?'selected':''}}>Hinduism</option>
                  <option value="3" {{$employeeInfo->religion =='3'?'selected':''}}>Christian</option>
                  <option value="4" {{$employeeInfo->religion =='4'?'selected':''}}>Buddhism</option>
                  <option value="5" {{$employeeInfo->religion =='5'?'selected':''}}>Others</option>
               </select>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="marital_status">Marital Status</label>
               <select id="marital_status" class="form-control" name="marital_status">
                  <option value="">Select Status</option>
                  <option value="MARRIED" {{$employeeInfo->marital_status =="MARRIED"?'selected':''}}>MARRIED</option>
                  <option value="UNMARRIED" {{$employeeInfo->marital_status =="UNMARRIED"?'selected':''}}>UNMARRIED</option>
                  <option value="DIVORCED" {{$employeeInfo->marital_status =="DIVORCED"?'selected':''}}>DIVORCED</option>
                  <option value="Priest" {{$employeeInfo->marital_status =="Priest"?'selected':''}}>Priest</option>
                  <option value="Nun" {{$employeeInfo->marital_status =="Nun"?'selected':''}}>Nun</option>
               </select>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-4 col-lg-4">
            <div class="form-group">
               <label class="control-label" for="phone">Phone No</label>
               <input id="phone" class="form-control" name="phone" value="{{$employeeInfo->phone}}" maxlength="12" type="text" required>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="blood_group">Blood Group</label>
               <select id="blood_group" class="form-control" name="blood_group">
                  <option value="Unknown" @if($employeeInfo->blood_group =="Unknown") selected="selected" @endif>Unknown</option>
                  <option value="A+" @if($employeeInfo->blood_group =="A+") selected="selected" @endif>A+</option>
                  <option value="A-" @if($employeeInfo->blood_group =="A-") selected="selected" @endif>A-</option>
                  <option value="B+" @if($employeeInfo->blood_group =="B+") selected="selected" @endif>B+</option>
                  <option value="B-" @if($employeeInfo->blood_group =="B-") selected="selected" @endif>B-</option>
                  <option value="AB+" @if($employeeInfo->blood_group =="AB+") selected="selected" @endif>AB+</option>
                  <option value="AB-" @if($employeeInfo->blood_group =="AB-") selected="selected" @endif>AB-</option>
                  <option value="O+" @if($employeeInfo->blood_group =="O+") selected="selected" @endif>O+</option>
                  <option value="O-" @if($employeeInfo->blood_group =="O-") selected="selected" @endif>O-</option>
               </select>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="category">Category <span class="text-red">*</span></label>
               <select id="category" class="form-control" name="category" required>
                  <option value="">--- Select Category ---</option>
                  <option value="1" @if($employeeInfo->category =="1") selected="selected" @endif>Teaching</option>
                  <option value="0" @if($employeeInfo->category =="0") selected="selected" @endif>Non-Teaching</option>
               </select>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="doj">Joining Date</label>
               <input class="form-control dob" name="doj" readonly value="@if($employeeInfo){{date('m/d/Y', strtotime($employeeInfo->doj))}}@endif" size="10" type="text">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="department">Department <span class="text-red">*</span></label>
               <select id="department" class="form-control" name="department">
                  <option value="">--- Select Department ---</option>
                  @if($allDepartments)
                     @foreach($allDepartments as $department)
                        <option value="{{$department->id}}" @if($employeeInfo->department ==$department->id) selected="selected" @endif>{{$department->name}} </option>
                     @endforeach
                  @endif
               </select>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="designation">Designation <span class="text-red">*</span></label>
               <select id="designation" class="form-control" name="designation" required>
                  <option value="">--- Select Designation ---</option>
                  @php $allDesignaitons = $employeeInfo->designations() @endphp
                  @if($allDesignaitons->count()>0)
                     @foreach($allDesignaitons as $designation)
                        <option value="{{$designation->id}}" @if($employeeInfo->designation ==$designation->id) selected="selected" @endif>{{$designation->name}} </option>
                     @endforeach
                  @endif
               </select>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-4">
            <div>
               <label for="totalExperience"> Total Experience</label>
            </div>
            <div class="col-sm-6 col-xs-12 no-padding">
               <div class="form-group">
                  <select id="experience_year" class="form-control" name="experience_year">
                     <option value="">Year</option>
                     @for($i =0 ;$i<=40 ;$i++)
                        <option value="{{$i}}" @if($employeeInfo->experience_year ==$i) selected="selected" @endif>{{$i}}</option>
                     @endfor
                     {{--<option value="0" @if($employeeInfo->experience_year ==0) selected="selected" @endif>0</option>
                     <option value="1" @if($employeeInfo->experience_year ==1) selected="selected" @endif>1</option>
                     <option value="2" @if($employeeInfo->experience_year ==2) selected="selected" @endif>2</option>
                     <option value="3" @if($employeeInfo->experience_year ==3) selected="selected" @endif>3</option>
                     <option value="4" @if($employeeInfo->experience_year ==4) selected="selected" @endif>4</option>
                     <option value="5" @if($employeeInfo->experience_year ==5) selected="selected" @endif>5</option>
                     <option value="6" @if($employeeInfo->experience_year ==6) selected="selected" @endif>6</option>
                     <option value="7" @if($employeeInfo->experience_year ==7) selected="selected" @endif>7</option>
                     <option value="8" @if($employeeInfo->experience_year ==8) selected="selected" @endif>8</option>
                     <option value="9" @if($employeeInfo->experience_year ==9) selected="selected" @endif>9</option>
                  --}}</select>
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-sm-6 col-xs-12 no-padding">
               <div class="form-group">
                  <select id="experience_month" class="form-control" name="experience_month" required>
                     <option value="">Month</option>
                     <option value="0" @if($employeeInfo->experience_month ==0) selected="selected" @endif>0</option>
                     <option value="1" @if($employeeInfo->experience_month ==1) selected="selected" @endif>1</option>
                     <option value="2" @if($employeeInfo->experience_month ==2) selected="selected" @endif>2</option>
                     <option value="3" @if($employeeInfo->experience_month ==3) selected="selected" @endif>3</option>
                     <option value="4" @if($employeeInfo->experience_month ==4) selected="selected" @endif>4</option>
                     <option value="5" @if($employeeInfo->experience_month ==5) selected="selected" @endif>5</option>
                     <option value="6" @if($employeeInfo->experience_month ==6) selected="selected" @endif>6</option>
                     <option value="7" @if($employeeInfo->experience_month ==7) selected="selected" @endif>7</option>
                     <option value="8" @if($employeeInfo->experience_month ==8) selected="selected" @endif>8</option>
                     <option value="9" @if($employeeInfo->experience_month ==9) selected="selected" @endif>9</option>
                     <option value="10" @if($employeeInfo->experience_month ==10) selected="selected" @endif>10</option>
                     <option value="11" @if($employeeInfo->experience_month ==11) selected="selected" @endif>11</option>
                  </select>
                  <div class="help-block"></div>
               </div>
            </div>
         </div>
         <div class="col-sm-4 col-lg-4">
            <div class="form-group">
               <label class="control-label" for="sort_order">Web Position</label>
               <input id="sort_order" class="form-control" name="sort_order" value="{{$employeeInfo->sort_order}}" maxlength="12" type="text">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="position_serial">Position Serial</label>
               <input id="position_serial" class="form-control" name="position_serial" value="{{$employeeInfo->position_serial}}" maxlength="35" type="text">
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="dor">Date Of Retirement</label>
               <input id="dor" class="form-control" name="dor" value="{{$employeeInfo->dor}}" maxlength="35" type="text">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="present_address">Current Address</label>
               <textarea name="present_address" id="" class="form-control" rows="1">{{ $employeeInfo->presentAddress() }}</textarea>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="permanent_address">Permanent Address</label>
               <textarea name="permanent_address" id="" class="form-control" rows="1">{{ $employeeInfo->permanentAddress() }}</textarea>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
   </div>
   <div class="box-footer">
      <button type="submit" class="btn btn-info">Update</button>  <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
   </div>
</form>


<script type="text/javascript">
    $(document).ready(function(){

        $('.dob').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
        $('#dor').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
       // $('#joining_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});

        // add letters only to the validator
        $.validator.addMethod("lettersonlys", function(value, element) {
            return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
        }, "Letters only please");
        // username
        $.validator.addMethod("loginRegex", function(value, element) {
            return this.optional(element) || /^[a-z0-9\-]+$/i.test(value);
        }, "Username must contain only letters, numbers, or dashes.");


        // validate signup form on keyup and submit
        var validator = $("#employee-update").validate({
            // Specify validation rules
            rules: {
                // title:'required',
                first_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 35,
                },
//                middle_name: {
//                    required: true,
//                    minlength: 1,
//                    maxlength: 35,
//                },
//                last_name: {
//                    //required: true,
//                    minlength: 1,
//                    maxlength: 35,
//                },
                alias: {
                    //required: false,
                    minlength: 1,
                    maxlength: 20,
                },
                gender: 'required',
                dob: 'required',
                // doj: 'required',

                //blood_group: 'required',
                //religion: 'required',

                department: 'required',
                designation: 'required',
                category: 'required',
                email: {
                    //required: true,
                    email: true,
                    minlength: 5,
                    maxlength:60,
                },
                phone: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 15,
                },
                //marital_status: 'required',
               // nationality: 'required',
                experience_year: 'required',
                experience_month: 'required',
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

            // request for designation list using department id
            jQuery(document).on('change','#department',function(){
                // get department id
                var dept_id = $(this).val();
                // ajax request
                $.ajax({
                    url: '/employee/find/designation/list/'+dept_id,
                    type: 'GET',
                    cache: false,
                    datatype: 'application/json',

                    beforeSend: function() {
                    },

                    success:function(data){

                        var op = ''; 
                        //console.log(data.length);
                        op+='<option value="0" selected disabled>--- Select Designation ---</option>';
                        for(var i=0;i<data.length;i++){
                            // console.log(data[i].level_name);
                            op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                        }

                        // set value to the designation
                        $('#designation').html("");
                        $('#designation').append(op);
                    },

                    error:function(){

                    }
                });
            });

    });
</script>

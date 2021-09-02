
@extends('layouts.master')

<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-plus-square"></i> Add Employee
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/hr/default/index">Human Resource</a></li>
                <li><a href="/employee/default/index">Employee Management</a></li>
                <li><a href="/employee/emp-master/index">Manage Employee</a></li>
                <li class="active">Add Employee</li>
            </ul>
        </section>
        <section class="content">
            <div class="box box-solid">
                <form id="employee-create" action="{{url('employee/store')}}" method="POST">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('campus') ? ' has-error' : '' }}">
                                    <label class="control-label" for="">Campus <span class="text-red">*</span></label>
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
                                <div class="form-group {{ $errors->has('role') ? ' has-error' : '' }}">
                                    <label class="control-label" for="role">Employee Role <span class="text-red">*</span></label>
                                    <select id="role" class="form-control" name="role" required>
                                        <option value="">--- Select Role ---</option>
                                        @foreach($allRole as $role)
                                            <option value="{{$role->id}}" @if(old('role')==$role->id) selected="selected" @endif>{{$role->display_name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block">
                                        @if ($errors->has('role'))
                                            <strong>{{ $errors->first('role') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label class="control-label" for="title">Title</label>
                                    <select id="title" class="form-control" name="title" required>
                                        <option value="">--- Select Title ---</option>
                                        <option value="Fm." @if(old('title')=="Fm.") selected="selected" @endif>Fm.</option>
                                        <option value="Mr." @if(old('title')=="Mr.") selected="selected" @endif>Mr.</option>
                                        <option value="Mrs." @if(old('title')=="Mrs.") selected="selected" @endif>Mrs.</option>
                                        <option value="Ms." @if(old('title')=="Ms.") selected="selected" @endif>Ms.</option>
                                        <option value="Prof." @if(old('title')=="Prof.") selected="selected" @endif>Prof.</option>
                                        <option value="Dr." @if(old('title')=="Dr.") selected="selected" @endif>Dr.</option>
                                    </select>
                                    <div class="help-block">
                                        @if ($errors->has('title'))
                                            <strong>{{ $errors->first('title') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }}">
                                    <label class="control-label" for="gender">Gender <span class="text-red">*</span></label>
                                    <select id="gender" class="form-control" name="gender">
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

                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label class="control-label" for="first_name">First Name <span class="text-red">*</span></label>
                                    <input id="first_name" class="form-control" name="first_name" maxlength="35" type="text" value="{{old('first_name')}}" required>
                                    <div class="help-block">
                                        @if ($errors->has('first_name'))
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    <label class="control-label" for="last_name">Last Name</label>
                                    <input id="last_name" class="form-control" name="last_name" maxlength="35" type="text" value="{{old('last_name')}}" >
                                    <div class="help-block">
                                        @if ($errors->has('last_name'))
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('employee_no') ? ' has-error' : '' }}">
                                    <label class="control-label" for="employee_no">Employee No</label>
                                    <input id="employee_no" class="form-control" name="employee_no" maxlength="35" type="text" value="{{old('employee_no')}}">
                                    <div class="help-block">
                                        @if ($errors->has('employee_no'))
                                            <strong>{{ $errors->first('employee_no') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('alias') ? ' has-error' : '' }}">
                                    <label class="control-label" for="alias">Name Alias</label>
                                    <input id="alias" class="form-control" name="alias" maxlength="10" type="text" value="{{old('alias')}}" required>
                                    <div class="help-block">
                                        @if ($errors->has('alias'))
                                            <strong>{{ $errors->first('alias') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">


                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
                                    <label class="control-label" for="dob">Date of Birth <span class="text-red">*</span></label>
                                    <input id="dob" class="form-control" name="dob" size="10" type="text" value="{{old('dob')}}" required readonly>
                                    <div class="help-block">
                                        @if ($errors->has('dob'))
                                            <strong>{{ $errors->first('dob') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('doj') ? ' has-error' : '' }}">
                                    <label class="control-label" for="doj">Joining Date</label>
                                    <input id="doj" class="form-control" name="doj" size="10" type="text" value="{{old('doj')}}" required readonly>
                                    <div class="help-block">
                                        @if ($errors->has('doj'))
                                            <strong>{{ $errors->first('doj') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('department') ? ' has-error' : '' }}">
                                    <label class="control-label" for="department">Department <span class="text-red">*</span></label>
                                    <select id="department" class="form-control" name="department">
                                        <option value="" selected disabled>Select Department</option>
                                        @if($allDepartments)
                                            @foreach($allDepartments as $department)
                                                <option value="{{$department->id}}" @if(old('department')==$department->id) selected="selected" @endif>{{$department->name}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block">
                                        @if ($errors->has('department'))
                                            <strong>{{ $errors->first('department') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('designation') ? ' has-error' : '' }}">
                                    <label class="control-label" for="designation">Designation <span class="text-red">*</span></label>
                                    <select id="designation" class="form-control" name="designation" required>
                                        <option value="" selected disabled>Select Designation</option>
                                        @if($allDesignations)
                                            @foreach($allDesignations as $designation)
                                                <option value="{{$designation->id}}" @if(old('designation')==$designation->id) selected="selected" @endif>{{$designation->name}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block">
                                        @if ($errors->has('designation'))
                                            <strong>{{ $errors->first('designation') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('category') ? ' has-error' : '' }}">
                                    <label class="control-label" for="category">Category <span class="text-red">*</span></label>
                                    <select id="category" class="form-control" name="category" required>
                                        <option value="" selected disabled>Select Category</option>
                                        <option value="1" {{old('category')=="1"?'selected':''}}>Teaching</option>
                                        <option value="0" {{old('category')=="0"?'selected':''}}>Non-Teaching</option>
                                    </select>
                                    <div class="help-block">
                                        @if ($errors->has('category'))
                                            <strong>{{ $errors->first('category') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="control-label" for="email">Email/Login Id <span class="text-red">*</span></label>
                                    <input id="email" class="form-control" name="email" maxlength="65" placeholder="abc@example.com" type="text" value="{{old('email')}}" required>
                                    <div class="help-block">
                                        @if ($errors->has('email'))
                                            <strong>{{ $errors->first('email') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                                    <label class="control-label" for="phone">Phone No</label>
                                    <input id="phone" class="form-control" name="phone" maxlength="12" type="text" value="{{old('phone')}}" required>
                                    <div class="help-block">
                                        @if ($errors->has('phone'))
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('marital_status') ? ' has-error' : '' }}">
                                    <label class="control-label" for="marital_status">Marital Status</label>
                                    <select id="marital_status" class="form-control" name="marital_status" required>
                                        <option value="">Select Status</option>
                                        <option value="MARRIED" @if(old('marital_status')=="MARRIED") selected="selected" @endif>MARRIED</option>
                                        <option value="UNMARRIED" @if(old('marital_status')=="UNMARRIED") selected="selected" @endif>UNMARRIED</option>
                                        <option value="DIVORCED" @if(old('marital_status')=="DIVORCED") selected="selected" @endif>DIVORCED</option>
                                        <option value="Priest" @if(old('marital_status')=="Priest") selected="selected" @endif>Priest</option>
                                        <option value="Nun" @if(old('marital_status')=="Nun") selected="selected" @endif>Nun</option>
                                    </select>
                                    <div class="help-block">
                                        @if ($errors->has('marital_status'))
                                            <strong>{{ $errors->first('marital_status') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>

<!--                             <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('nationality') ? ' has-error' : '' }}">
                                    <label class="control-label" for="nationality">Nationality</label>
                                    <select id="nationality" class="form-control" name="nationality" required>
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
                            </div> -->


<!--                             <div class="col-sm-3">
                                <div class=""><label for="experience_year">Total Experience</label></div>
                                <div class="col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group {{ $errors->has('experience_year') ? ' has-error' : '' }}">
                                        <select id="experience_year" class="form-control" name="experience_year" required>
                                            <option value="">Select Year</option>
                                            <option value="0" @if(old('experience_year')==0) selected="selected" @endif>0</option>
                                            <option value="1" @if(old('experience_year')==1) selected="selected" @endif>1</option>
                                            <option value="2" @if(old('experience_year')==2) selected="selected" @endif>2</option>
                                            <option value="3" @if(old('experience_year')==3) selected="selected" @endif>3</option>
                                            <option value="4" @if(old('experience_year')==4) selected="selected" @endif>4</option>
                                            <option value="5" @if(old('experience_year')==5) selected="selected" @endif>5</option>
                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('experience_year'))
                                                <strong>{{ $errors->first('experience_year') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group {{ $errors->has('experience_month') ? ' has-error' : '' }}">
                                        <select id="experience_month" class="form-control" name="experience_month" required>
                                            <option value="">Month</option>
                                            <option value="0" @if(old('experience_month')==0) selected="selected" @endif>0</option>
                                            <option value="1" @if(old('experience_month')==1) selected="selected" @endif>1</option>
                                            <option value="2" @if(old('experience_month')==2) selected="selected" @endif>2</option>
                                            <option value="3" @if(old('experience_month')==3) selected="selected" @endif>3</option>
                                            <option value="4" @if(old('experience_month')==4) selected="selected" @endif>4</option>
                                            <option value="5" @if(old('experience_month')==5) selected="selected" @endif>5</option>
                                            <option value="6" @if(old('experience_month')==6) selected="selected" @endif>6</option>
                                            <option value="7" @if(old('experience_month')==7) selected="selected" @endif>7</option>
                                            <option value="8" @if(old('experience_month')==8) selected="selected" @endif>8</option>
                                            <option value="9" @if(old('experience_month')==9) selected="selected" @endif>9</option>
                                            <option value="10" @if(old('experience_month')==10) selected="selected" @endif>10</option>
                                            <option value="11" @if(old('experience_month')==11) selected="selected" @endif>11</option>
                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('experience_month'))
                                                <strong>{{ $errors->first('experience_month') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('position_serial') ? ' has-error' : '' }}">
                                    <label class="control-label" for="position_serial">Position Serial</label>
                                    <input id="position_serial" class="form-control" name="position_serial" maxlength="35" type="text" value="{{old('position_serial')}}" >
                                    <div class="help-block">
                                        @if ($errors->has('position_serial'))
                                            <strong>{{ $errors->first('position_serial') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('dor') ? ' has-error' : '' }}">
                                    <label class="control-label" for="dor">Date of Retirement</label>
                                    <input id="dor" class="form-control" name="dor" maxlength="35" type="text" value="{{old('dor')}}" >
                                    <div class="help-block">
                                        @if ($errors->has('dor'))
                                            <strong>{{ $errors->first('dor') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('present_address') ? ' has-error' : '' }}">
                                    <label class="control-label" for="present_address">Present Address</label>
                                    <textarea name="present_address" id="" class="form-control" rows="1">{{old('present_address')}}</textarea>
                                    <div class="help-block">
                                        @if ($errors->has('present_address'))
                                            <strong>{{ $errors->first('present_address') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group {{ $errors->has('permanent_address') ? ' has-error' : '' }}">
                                    <label class="control-label" for="permanent_address">Permanent Address</label>
                                    <textarea name="permanent_address" id="" class="form-control" rows="1">{{old('permanent_address')}}</textarea>
                                    <div class="help-block">
                                        @if ($errors->has('permanent_address'))
                                            <strong>{{ $errors->first('permanent_address') }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Create</button>   <a class="btn btn-default" href="#">Cancel</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){

            $('#dob').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
            $('#doj').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
            $('#dor').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});

            // add letters only to the validator
            $.validator.addMethod("lettersonlys", function(value, element) {
              return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
            }, "Letters only please");
            // username
            $.validator.addMethod("loginRegex", function(value, element) {
              return this.optional(element) || /^[a-z0-9\-]+$/i.test(value);
            }, "Username must contain only letters, numbers, or dashes.");


            // validate signup form on keyup and submit
            var validator = $("#employee-create").validate({
                // Specify validation rules
                rules: {
                    title:'required',
                    first_name: {
                        required: true,
                        minlength: 1,
                        maxlength: 35,
                    },
                    alias: {
                        required: true,
                        minlength: 1,
                        maxlength: 20,
                    },
                    gender: 'required',
                    dob: 'required',
                    doj: 'required',
                    department: 'required',
                    designation: 'required',
                    category: 'required',
                    email: {
                        required: true,
                        email: true,
                        minlength: 5,
                        maxlength:60,
                    },
                    phone: {
                        required: true,
                        number: true,
                        minlength: 11,
                        maxlength: 15,
                    },
                    marital_status: 'required',
                    nationality: 'required',
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

        });
    </script>
@endsection

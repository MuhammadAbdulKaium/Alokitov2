<style>
    .input {
        width: 190px !important;
    }

    table p {
        width: 110px !important;
    }

    .checkbox {
        width: 50px !important;
    }

    #allCheckbox {
        width: 20px !important;
        text-align: start !important;
        /* margin-left: 14px */
    }

</style>

<div class="box">
    <div class="box-body">
        <div class="box-body table-responsive">
            <h5><i class="fa fa-search"></i> Total Search Result({{ $allEmployee ? count($allEmployee) : 0 }})
            </h5>
            <form id="employee_bulk_edit" method="POST" action="{{ url('/employee/bulk/edit/save') }}">
                <div class="text-right">
                    <button type="submit" class="btn btn-info "><i class="fa fa-upload"></i>
                        Submit</button>
                </div>
                {{-- <input type="hidden" name="" value=""> --}}
                <div id="w1" class="grid-view">

                    <div class="table-responsive">
                        @csrf

                        <table id="myTable" class="table table-bordered table-striped  display" border="2">
                            @if (!$selectForm)

                                <thead>
                                    <tr>
                                        <th class="text-center">Si</th>
                                        <th class="text-start">
                                            <input type="checkbox" name="" id="allCheckbox"> <label
                                                for="allCheckbox">All Mark</label>
                                        </th>
                                        <th class="text-center">User Name</th>
                                        <th class="text-center">Role</th>
                                        <th class="text-center">Title</th>
                                        <th class="text-center">First Name</th>
                                        <th class="text-center">Middle Name</th>
                                        <th class="text-center">Last Name</th>
                                        <th class="text-center">Employee No</th>
                                        <th class="text-center">Position Serial</th>
                                        <th class="text-center">Name Alias</th>
                                        <th class="text-center">Gender</th>
                                        <th class="text-center">BirthDay </th>
                                        <th class="text-center">Joining Date</th>
                                        <th class="text-center">Retirement Date</th>
                                        <th class="text-center">Department</th>
                                        <th class="text-center">Designation</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Phone</th>
                                        <th class="text-center">Alt Mobile</th>
                                        <th class="text-center">Blood Group</th>
                                        <th class="text-center">Birth Place</th>
                                        <th class="text-center">Religion</th>
                                        <th class="text-center">Marital Status</th>
                                        <th class="text-center">Nationality</th>
                                        <th class="text-center">Experience Year</th>
                                        <th class="text-center">Experience Month</th>
                                        <th class="text-center">Present Address</th>
                                        <th class="text-center">Permanent Address</th>
                                    </tr>

                                </thead>
                                @if (count($allEmployee) > 0)

                                    <tbody class="row_position">
                                        <input type="hidden" id="drag_drop" name="drag">
                                        @foreach ($allEmployee as $key => $employee)
                                            <tr id="{{ $employee->id }}"
                                                data-position-serial="{{ $employee->position_serial }}">
                                                <td class="si_no"> {{ $key + 1 }}</td>

                                                <td class="employee-web-eid">

                                                    <input type="checkbox" name="employee_id[{{ $employee->id }}]"
                                                        class="checkbox">
                                                </td>
                                                <td>
                                                    <p class="input">
                                                        {{ $employee->singleUser ? $employee->singleUser->username : '' }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <select name="role[{{ $employee->id }}]" id=""
                                                        class="form-control input">
                                                        <option value="">__Select Role__</option>
                                                        @foreach ($allRole as $role)
                                                            <option value="{{ $role->id }}"
                                                                @if ($employee->user()->roles()->count() > 0) {{ $employee->user()->roles()->first()->id == $role->id? 'selected': '' }} @endif>
                                                                {{ $role->display_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                @php
                                                    $titles = ['FM', 'Mr.', 'Mrs.', 'Ms.', 'Prof.', 'Dr.'];
                                                @endphp
                                                <td>
                                                    <select class="form-control input"
                                                        name="title[{{ $employee->id }}]">

                                                        <option value="">--Select Title--</option>
                                                        @foreach ($titles as $title)
                                                            <option
                                                                {{ $employee->title == $title ? 'selected' : '' }}
                                                                value="{{ $title }}">{{ $title }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </td>

                                                <td>
                                                    <input name="first_name[{{ $employee->id }}]"
                                                        value="{{ $employee->first_name }}" type="text"
                                                        class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="middle_name[{{ $employee->id }}]"
                                                        value="{{ $employee->middle_name }}" type="text"
                                                        class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="last_name[{{ $employee->id }}]"
                                                        value="{{ $employee->last_name }}" type="text"
                                                        class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="employee_no[{{ $employee->id }}]"
                                                        value="{{ $employee->employee_no }}" type="text"
                                                        class="form-control input">
                                                </td>
                                                <td>
                                                    <input name="position_serial[{{ $employee->id }}]"
                                                        value="{{ $employee->position_serial }}" type="text"
                                                        class="form-control position_serial input" readonly>
                                                </td>
                                                <td class="">
                                                    <input name="alias[{{ $employee->id }}]"
                                                        value="{{ $employee->alias }}" type="text"
                                                        class="form-control input">
                                                </td>
                                                <td class="">
                                                    <select name="gender[{{ $employee->id }}]" id=""
                                                        class="form-control input">
                                                        <option value="">--Select Gender--</option>
                                                        <option {{ $employee->gender == 'Male' ? 'selected' : '' }}
                                                            value="Male">Male</option>
                                                        <option {{ $employee->gender == 'Female' ? 'selected' : '' }}
                                                            value="Female">Female</option>
                                                    </select>
                                                </td>
                                                <td class="">
                                                    <input name="dob[{{ $employee->id }}]"
                                                        value="{{ $employee->dob }}" type="date"
                                                        class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="doj[{{ $employee->id }}]"
                                                        value="{{ $employee->doj }}" type="date"
                                                        class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="dor[{{ $employee->id }}]"
                                                        value="{{ $employee->dor }}" type="date"
                                                        class="form-control input">
                                                </td>
                                                <td class="">
                                                    <select name="department[{{ $employee->id }}]"
                                                        class="form-control search-select2 input">>

                                                        <option value="0">__Select Department__</option>
                                                        @foreach ($allDepartment as $department)
                                                            <option
                                                                @if ($employee->department()) {{ $employee->department()->id == $department->id ? 'selected' : '' }} @endif
                                                                value="{{ $department->id }}">
                                                                {{ $department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="">
                                                    <select name="designation[{{ $employee->id }}]"
                                                        class="form-control search-select2 input">>

                                                        <option value="0">__Select Designation __</option>
                                                        @foreach ($allDesignation as $designation)
                                                            <option
                                                                @if ($employee->designation()) {{ $employee->designation()->id == $designation->id ? 'selected' : '' }} @endif
                                                                value="{{ $designation->id }}">
                                                                {{ $designation->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="">
                                                    <select name="category[{{ $employee->id }}]"
                                                        class="form-control input">>
                                                        <option value="2">--Select Category--</option>
                                                        <option {{ $employee->category == 1 ? 'selected' : '' }}
                                                            value="1">
                                                            Teaching</option>
                                                        <option {{ $employee->category == 0 ? 'selected' : '' }}
                                                            value="0">
                                                            Non Teaching</option>
                                                    </select>
                                                </td>
                                                <td class="">
                                                    <input name="email[{{ $employee->id }}]"
                                                        value="{{ $employee->email }}" type="email"
                                                        class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="phone[{{ $employee->id }}]"
                                                        value="{{ $employee->phone }}" type="text"
                                                        class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="alt_mobile[{{ $employee->id }}]"
                                                        value="{{ $employee->alt_mobile }}" type="text"
                                                        class="form-control input">
                                                </td>
                                                @php
                                                    $bloodGroups = ['A+', 'A-', 'B+', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                                @endphp
                                                <td class="">
                                                    <select name="blood_group[{{ $employee->id }}]" id=""
                                                        class="form-control input">

                                                        <option value="">--Select Blood Group--</option>
                                                        @foreach ($bloodGroups as $group)
                                                            <option
                                                                {{ $employee->blood_group == $group ? 'selected' : '' }}
                                                                value="{{ $group }}">
                                                                {{ $group }}</option>
                                                        @endforeach

                                                    </select>
                                                </td>
                                                <td class="">
                                                    <input name="birth_place[{{ $employee->id }}]"
                                                        value="{{ $employee->birth_place }}" type="text"
                                                        class="form-control input">
                                                </td>
                                                @php
                                                    $religions = ['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Others'];
                                                @endphp
                                                <td class="">
                                                    <select name="religion[{{ $employee->id }}]" id=""
                                                        class="form-control input">

                                                        <option value="0">--Select Religion--</option>
                                                        @foreach ($religions as $key => $value)
                                                            <option
                                                                {{ $employee->religion == $key + 1 ? 'selected' : '' }}
                                                                value="{{ $key + 1 }}">{{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                @php
                                                    $maritalStatus = ['MARRIED', 'UNMARRIED', 'DIVORCED', 'Priest', 'Nun'];
                                                @endphp
                                                <td class="">
                                                    <select class="form-control input"
                                                        name="marital_status[{{ $employee->id }}]">

                                                        <option value="">--- Select Marital Status ---</option>
                                                        @foreach ($maritalStatus as $status)
                                                            <option
                                                                {{ $employee->marital_status == $status ? 'selected' : '' }}
                                                                value="{{ $status }}">
                                                                {{ $status }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="">
                                                    <select class="form-control input"
                                                        name="nationality[{{ $employee->id }}]">
                                                        <option value="">--- Select Nationality ---</option>
                                                        @if ($allNationality)
                                                            @foreach ($allNationality as $nationality)
                                                                <option value="{{ $nationality->id }}"
                                                                    {{ $employee->nationality == $nationality->id ? 'selected' : '' }}>
                                                                    {{ $nationality->nationality }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </td>

                                                <td class="">
                                                    <select id="experience_year" class="form-control input"
                                                        name="experience_year[{{ $employee->id }}]">

                                                        <option value="">--- Select E.Y ---</option>
                                                        @for ($i = 0; $i <= 40; $i++)
                                                            <option
                                                                {{ $employee->experience_year == $i ? 'selected' : '' }}
                                                                value="{{ $i }}">{{ $i }}
                                                            </option>
                                                        @endfor

                                                    </select>
                                                </td>
                                                <td class="">
                                                    <select id="experience_month" class="form-control input"
                                                        name="experience_month[{{ $employee->id }}]">


                                                        <option value="">--- Select E.M ---</option>
                                                        @for ($i = 0; $i <= 11; $i++)
                                                            <option
                                                                {{ $employee->experience_month == $i ? 'selected' : '' }}
                                                                value="{{ $i }}">{{ $i }}
                                                            </option>
                                                        @endfor


                                                    </select>

                                                </td>
                                                <td>
                                                    @php
                                                        $presentAddress = '';
                                                        $permanentAddress = '';
                                                    @endphp
                                                    @if ($employee->getEmployeAddress)
                                                        @foreach ($employee->getEmployeAddress as $key => $address)
                                                            @if ($address->type == 'EMPLOYEE_PRESENT_ADDRESS')
                                                                @php
                                                                    $presentAddress = $address->address;
                                                                @endphp
                                                            @endif
                                                            @if ($address->type == 'EMPLOYEE_PERMANENT_ADDRESS')
                                                                @php
                                                                    $permanentAddress = $address->address;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    <input name="present_address[{{ $employee->id }}]"
                                                        class="form-control input" value="{{ $presentAddress }}" />
                                                </td>
                                                <td>
                                                    <input name="permanent_address[{{ $employee->id }}]"
                                                        class="form-control input"
                                                        value=" {{ $permanentAddress }} " />

                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                @else
                                    <tbody class="row_position">
                                        <tr>
                                            <td colspan="500" class="text-center">Data Not Found</td>
                                        </tr>
                                    </tbody>

                                @endif
                            @else
                                <thead>
                                    <tr>
                                        <th class="">Si</th>
                                        <th class="text-start">
                                            <input type="checkbox" class="input" name="" id="allCheckbox">
                                            <label for="allCheckbox">All Mark</label>
                                        </th>
                                        @foreach ($selectForm as $form)
                                            <input type="hidden" class="selectForm" name="selectForms[]"
                                                value="{{ $form }}">
                                            @if ($form == 'userName')
                                                <th>User Name</th>
                                            @endif
                                            @if ($form == 'role')
                                                <th>Role</th>
                                            @endif
                                            @if ($form == 'title')
                                                <th>Title</th>
                                            @endif
                                            @if ($form == 'first_name')
                                                <th>First Name</th>
                                            @endif
                                            @if ($form == 'middle_name')
                                                <th>Middle Name</th>
                                            @endif
                                            @if ($form == 'last_name')
                                                <th>Last Name</th>
                                            @endif
                                            @if ($form == 'employee_no')
                                                <th>Employee No</th>
                                            @endif
                                            @if ($form == 'position_serial')
                                                <th>Position Serial</th>
                                            @endif
                                            @if ($form == 'alias')
                                                <th>Name Alias</th>
                                            @endif
                                            @if ($form == 'gender')
                                                <th>Gender</th>
                                            @endif
                                            @if ($form == 'dob')
                                                <th>BirthDay</th>
                                            @endif
                                            @if ($form == 'doj')
                                                <th> Joining Date </th>
                                            @endif
                                            @if ($form == 'dor')
                                                <th>Retirement Date</th>
                                            @endif
                                            @if ($form == 'department')
                                                <th>Department</th>
                                            @endif
                                            @if ($form == 'designation')
                                                <th>Designation</th>
                                            @endif
                                            @if ($form == 'category')
                                                <th>Category</th>
                                            @endif
                                            @if ($form == 'email')
                                                <th>Email</th>
                                            @endif
                                            @if ($form == 'phone')
                                                <th>Phone</th>
                                            @endif
                                            @if ($form == 'alt_mobile')
                                                <th>Alt Mobile</th>
                                            @endif
                                            @if ($form == 'religion')
                                                <th>Religion</th>
                                            @endif
                                            @if ($form == 'blood_group')
                                                <th>Blood Group</th>
                                            @endif
                                            @if ($form == 'birth_place')
                                                <th>BirthDay Place</th>
                                            @endif
                                            @if ($form == 'marital_status')
                                                <th>Marital Status</th>
                                            @endif
                                            @if ($form == 'nationality')
                                                <th>Nationality</th>
                                            @endif
                                            @if ($form == 'experience_year')
                                                <th>Experience Year</th>
                                            @endif
                                            @if ($form == 'experience_month')
                                                <th>Experience Month</th>
                                            @endif
                                            @if ($form == 'present_address')
                                                <th>Present Address</th>
                                            @endif
                                            @if ($form == 'permanent_address')
                                                <th>Permanent Address</th>
                                            @endif
                                        @endforeach
                                    </tr>

                                </thead>
                                @if (count($allEmployee) > 0)

                                    <tbody class="row_position">
                                        <input type="hidden" id="drag_drop" name="drag">
                                        @foreach ($allEmployee as $key => $employee)
                                            <tr id="{{ $employee->id }}"
                                                data-position-serial="{{ $employee->position_serial }}">
                                                <td class="si_no">
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="employee_id[{{ $employee->id }}]"
                                                        value="{{ $employee->first_name }}" class="checkbox input">
                                                </td>

                                                @foreach ($selectForm as $form)
                                                    @if ($form == 'userName')
                                                        <td>
                                                            <p class="input">
                                                                {{ $employee->singleUser ? $employee->singleUser->username : '' }}
                                                            </p>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'first_name')
                                                        <td>
                                                            <input name="first_name[{{ $employee->id }}]"
                                                                value="{{ $employee->first_name }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @elseif ($form == 'role')
                                                        <td>
                                                            <select name="role[{{ $employee->id }}]" id=""
                                                                class="form-control input">
                                                                @foreach ($allRole as $role)
                                                                    <option value="{{ $role->id }}"
                                                                        @if ($employee->user()->roles()->count() > 0) {{ $employee->user()->roles()->first()->id == $role->id? 'selected': '' }} @endif>
                                                                        {{ $role->display_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    @elseif ($form == 'title')
                                                        @php
                                                            $titles = ['FM', 'Mr.', 'Mrs.', 'Ms.', 'Prof.', 'Dr.'];
                                                        @endphp
                                                        <td>
                                                            <select class="form-control input"
                                                                name="title[{{ $employee->id }}]">

                                                                <option value="">--Select Title--</option>
                                                                @foreach ($titles as $title)
                                                                    <option
                                                                        {{ $employee->title == $title ? 'selected' : '' }}
                                                                        value="{{ $title }}">
                                                                        {{ $title }}
                                                                    </option>
                                                                @endforeach

                                                            </select>
                                                        </td>
                                                    @elseif ($form == 'middle_name')
                                                        <td class="">
                                                            <input name="middle_name[{{ $employee->id }}]"
                                                                value="{{ $employee->middle_name }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif

                                                    @if ($form == 'last_name')
                                                        <td class="">
                                                            <input name="last_name[{{ $employee->id }}]"
                                                                value="{{ $employee->last_name }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'employee_no')
                                                        <td class="">
                                                            <input name="employee_no[{{ $employee->id }}]"
                                                                value="{{ $employee->employee_no }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'position_serial')
                                                        <td class="">
                                                            <input name="position_serial[{{ $employee->id }}]"
                                                                value="{{ $employee->position_serial }}" type="text"
                                                                class="form-control position_serial input" readonly>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'alias')
                                                        <td class="">
                                                            <input name="alias[{{ $employee->id }}]"
                                                                value="{{ $employee->alias }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'gender')
                                                        <td class="">
                                                            <select name="gender[{{ $employee->id }}]" id=""
                                                                class="form-control input">
                                                                <option value="">--Select Gender--</option>
                                                                <option
                                                                    {{ $employee->gender == 'Male' ? 'selected' : '' }}
                                                                    value="Male">Male</option>
                                                                <option
                                                                    {{ $employee->gender == 'Female' ? 'selected' : '' }}
                                                                    value="Female">Female</option>
                                                            </select>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'dob')
                                                        <td class="">
                                                            <input name="dob[{{ $employee->id }}]"
                                                                value="{{ $employee->dob }}" type="date"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'doj')
                                                        <td class="">
                                                            <input name="doj[{{ $employee->id }}]"
                                                                value="{{ $employee->doj }}" type="date"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'dor')
                                                        <td class="">
                                                            <input name="dor[{{ $employee->id }}]"
                                                                value="{{ $employee->dor }}" type="date"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'department')
                                                        <td class="">
                                                            <select name="department[{{ $employee->id }}]"
                                                                class="form-control search-select2 input">>

                                                                <option value="0">__Select Department__</option>
                                                                @foreach ($allDepartment as $department)
                                                                    <option
                                                                        @if ($employee->department()) {{ $employee->department()->id == $department->id ? 'selected' : '' }} @endif
                                                                        value="{{ $department->id }}">
                                                                        {{ $department->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'designation')
                                                        <td class="">
                                                            <select name="designation[{{ $employee->id }}]"
                                                                class="form-control search-select2 input">>

                                                                <option value="0">__Select Designation __</option>
                                                                @foreach ($allDesignation as $designation)
                                                                    <option
                                                                        @if ($employee->designation()) {{ $employee->designation()->id == $designation->id ? 'selected' : '' }} @endif
                                                                        value="{{ $designation->id }}">
                                                                        {{ $designation->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'category')
                                                        <td class="">
                                                            <select name="category[{{ $employee->id }}]"
                                                                class="form-control input">>
                                                                <option value="2">--Select Category--</option>
                                                                <option
                                                                    {{ $employee->category == 1 ? 'selected' : '' }}
                                                                    value="1">Teaching</option>
                                                                <option
                                                                    {{ $employee->category == 0 ? 'selected' : '' }}
                                                                    value="0">Non Teaching</option>
                                                            </select>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'email')
                                                        <td class="">
                                                            <input name="email[{{ $employee->id }}]"
                                                                value="{{ $employee->email }}" type="email"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'phone')
                                                        <td class="">
                                                            <input name="phone[{{ $employee->id }}]"
                                                                value="{{ $employee->phone }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'alt_mobile')
                                                        <td class="">
                                                            <input name="alt_mobile[{{ $employee->id }}]"
                                                                value="{{ $employee->alt_mobile }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'blood_group')
                                                        @php
                                                            $bloodGroups = ['A+', 'A-', 'B+', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                                        @endphp
                                                        <td class="">
                                                            <select name="blood_group[{{ $employee->id }}]" id=""
                                                                class="form-control input">

                                                                <option value="">--Select Blood Group--</option>
                                                                @foreach ($bloodGroups as $group)
                                                                    <option
                                                                        {{ $employee->blood_group == $group ? 'selected' : '' }}
                                                                        value="{{ $group }}">
                                                                        {{ $group }}</option>
                                                                @endforeach

                                                            </select>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'birth_place')
                                                        <td class="">
                                                            <input name="birth_place[{{ $employee->id }}]"
                                                                value="{{ $employee->birth_place }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'religion')
                                                        @php
                                                            $religions = ['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Others'];
                                                        @endphp
                                                        <td class="">
                                                            <select name="religion[{{ $employee->id }}]" id=""
                                                                class="form-control input">

                                                                <option value="0">--Select Religion--</option>
                                                                @foreach ($religions as $key => $value)
                                                                    <option
                                                                        {{ $employee->religion == $key + 1 ? 'selected' : '' }}
                                                                        value="{{ $key + 1 }}">
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'marital_status')
                                                        @php
                                                            $maritalStatus = ['MARRIED', 'UNMARRIED', 'DIVORCED', 'Priest', 'Nun'];
                                                        @endphp
                                                        <td class="">
                                                            <select class="form-control input"
                                                                name="marital_status[{{ $employee->id }}]">

                                                                <option value="">--- Select Marital Status ---</option>
                                                                @foreach ($maritalStatus as $status)
                                                                    <option
                                                                        {{ $employee->marital_status == $status ? 'selected' : '' }}
                                                                        value="{{ $status }}">
                                                                        {{ $status }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'nationality')
                                                        <td class="">
                                                            <select class="form-control input"
                                                                name="nationality[{{ $employee->id }}]">
                                                                <option value="">--- Select Nationality ---</option>
                                                                @if ($allNationality)
                                                                    @foreach ($allNationality as $nationality)
                                                                        <option value="{{ $nationality->id }}"
                                                                            {{ $employee->nationality == $nationality->id ? 'selected' : '' }}>
                                                                            {{ $nationality->nationality }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'experience_year')
                                                        <td class="">
                                                            <select id="experience_year" class="form-control input"
                                                                name="experience_year[{{ $employee->id }}]">

                                                                <option value="">--- Select E.Y ---</option>
                                                                @for ($i = 0; $i <= 40; $i++)
                                                                    <option
                                                                        {{ $employee->experience_year == $i ? 'selected' : '' }}
                                                                        value="{{ $i }}">
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'experience_month')
                                                        <td class="">
                                                            <select id="experience_month" class="form-control input"
                                                                name="experience_month[{{ $employee->id }}]">


                                                                <option value="">--- Select E.M ---</option>
                                                                @for ($i = 0; $i <= 40; $i++)
                                                                    <option
                                                                        {{ $employee->experience_month == $i ? 'selected' : '' }}
                                                                        value="{{ $i }}">
                                                                        {{ $i }}</option>
                                                                @endfor

                                                            </select>

                                                        </td>
                                                    @endif
                                                    @php
                                                        $presentAddress = '';
                                                        $permanentAddress = '';
                                                    @endphp
                                                    @if ($employee->getEmployeAddress)
                                                        @foreach ($employee->getEmployeAddress as $key => $address)
                                                            @if ($address->type == 'EMPLOYEE_PRESENT_ADDRESS')
                                                                @php
                                                                    $presentAddress = $address->address;
                                                                @endphp
                                                            @endif
                                                            @if ($address->type == 'EMPLOYEE_PERMANENT_ADDRESS')
                                                                @php
                                                                    $permanentAddress = $address->address;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if ($form == 'present_address')
                                                        <td>
                                                            <input name="present_address[{{ $employee->id }}]"
                                                                class="form-control input"
                                                                value="{{ $presentAddress }} " />
                                                        </td>
                                                    @endif
                                                    @if ($form == 'permanent_address')
                                                        <td>
                                                            <input name="permanent_address[{{ $employee->id }}]"
                                                                class="form-control input"
                                                                value="{{ $permanentAddress }} " />

                                                        </td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                @else
                                    <tbody class="row_position">
                                        <tr>
                                            <td colspan="500" class="text-center">Data Not Found</td>
                                        </tr>
                                    </tbody>
                                @endif
                            @endif

                        </table>
                        <!--./modal-body-->

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info pull-right"><i class="fa fa-upload"></i>
                            Submit</button>

                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // drag && drop 
        $(".row_position").sortable({
            delay: 150,
            stop: function() {
                var si = 0;
                var ps_Array = new Array();
                var getPosition_serial = $(this).find('.position_serial');
                var drag_drop = $('#drag_drop');
                drag_drop.attr('value', 1);
                $('.row_position>tr').each(function() {

                    var position_serial = $(this).data('position-serial');
                    var employee_id = $(this).attr("id");
                    var employee_info = {
                        id: employee_id,
                        position_serial
                    };
                    ps_Array.push(position_serial);

                    var getsi_no = $(this).find('.si_no');
                    ps_Array.sort(function(a, b) {
                        return a - b
                    });
                    getPosition_serial.each((_, element) => {
                        element.value = ps_Array[_];
                    })

                    getsi_no.each((_, sino) => {
                        sino.innerText = si + 1;
                    })
                    si++;

                });

            }
        });

        $('.search-select2').select2();
        // select checkbox
        $('#allCheckbox').click(function(e) {
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
        });


    })
</script>

<style>
    .input {
        width: 190px !important;
    }

    .checkbox {
        width: 50px !important;
    }

    #allCheckbox {
        width: 20px !important;
        text-align: start !important;
        margin-left: 14px
    }

</style>
<div class="box">
    <div class="box-body">
        <div class="box-body table-responsive">
            <div id="w1" class="grid-view">
                <h5><i class="fa fa-search"></i> Total Search Result({{ $studentInfos ? count($studentInfos) : 0 }})
                </h5>

                <form id="std_cadet_bulk_edit" method="POST" action="{{ url('/student/cadet/bulk/edit/save') }}">
                    <div class="text-right">
                        <button type="submit" class="btn btn-info   mb-4"><i class="fa fa-upload"></i>Submit</button>
                    </div>
                    <div class="table-responsive">
                        @csrf
                        <table id="myTable" class="table table-striped table-bordered display">
                            @if (!$selectForms)

                                <thead>
                                    <tr>
                                        <th class="text-center">Si</th>
                                        <th class="text-start">
                                            <input type="checkbox" name="" id="allCheckbox"> <label
                                                for="allCheckbox">All Mark</label>
                                        </th>
                                        <th>Student Number</th>
                                        <th class="text-center">First Name</th>
                                        <th class="text-center">Last Name</th>
                                        <th class="text-center">Middle Name</th>
                                        <th class="text-center">Bengali Name</th>
                                        <th class="text-center">Gender</th>
                                        <th class="text-center">Date of Birth</th>
                                        <th class="text-center">Birth Place</th>
                                        <th class="text-center"> Religion </th>
                                        <th class="text-center">Blood Group</th>
                                        <th class="text-center">Merit Position</th>
                                        <th class="text-center">Present Address</th>
                                        <th class="text-center">Permanent Address</th>
                                        <th class="text-center">Nationality</th>
                                        <th class="text-center">Language</th>
                                        <th class="text-center">Identification Marks</th>
                                        <th class="text-center">Hobby</th>
                                        <th class="text-center">Aim</th>
                                        <th class="text-center">Dream</th>
                                        <th class="text-center">Idol</th>
                                        {{-- <th class="text-center">Tution Fees</th> --}}
                                        <th class="text-center">Father's Name</th>
                                        <th class="text-center">Father's Occupation </th>
                                        <th class="text-center">Father's Contact</th>
                                        <th class="text-center">Father's Email</th>
                                        <th class="text-center">Mother's Name</th>
                                        <th class="text-center">Mother's Occupation </th>
                                        <th class="text-center">Mother's Contact</th>
                                        <th class="text-center">Mother's Email</th>
                                        <th class="text-center">Admission Year</th>
                                        <th class="text-center">Academic Year</th>
                                        <th class="text-center">Academic Level</th>
                                        <th class="text-center">Batch</th>
                                        <th class="text-center">Section</th>
                                    </tr>

                                </thead>
                                @if (count($studentInfos) > 0)

                                    <tbody>
                                        @foreach ($studentInfos as $key => $studentInfo)
                                            <tr>
                                                <td>
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="upload[{{ $studentInfo->std_id }}]"
                                                        value="{{ $studentInfo->email }}" class="checkbox">
                                                </td>
                                                <td>
                                                    <input name="user_name[{{ $studentInfo->std_id }}]"
                                                        value="{{ $studentInfo->singleUser->username }}" type="text"
                                                        class="form-control input" readonly>
                                                </td>

                                                <td class="">
                                                    <input name="first_name[{{ $studentInfo->std_id }}]"
                                                        value="{{ $studentInfo->singleStudent->first_name }}"
                                                        type="text" class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="last_name[{{ $studentInfo->std_id }}]"
                                                        value="{{ $studentInfo->singleStudent->last_name }}"
                                                        type="text" class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="nickname[{{ $studentInfo->std_id }}]"
                                                        value="{{ $studentInfo->singleStudent->middle_name }}"
                                                        type="text" class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="bn_fullname[{{ $studentInfo->std_id }}]"
                                                        value="{{ $studentInfo->singleStudent->bn_fullname }}"
                                                        type="text" class="form-control input">
                                                </td>
                                                <td class="">
                                                    <select name="gender[{{ $studentInfo->std_id }}]" id=""
                                                        class="form-control input">
                                                        <option value="">-- Select Gender --</option>
                                                        <option
                                                            {{ $studentInfo->singleStudent->gender == 'Male' ? ' selected ' : '' }}
                                                            value="Male">Male</option>
                                                        <option
                                                            {{ $studentInfo->singleStudent->gender == 'Female' ? ' selected ' : '' }}
                                                            value="Female">Female</option>
                                                    </select>
                                                </td>
                                                <td class="">
                                                    <input name="dob[{{ $studentInfo->std_id }}]"
                                                        value="{{ $studentInfo->singleStudent->dob }}" type="date"
                                                        class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="birth_place[{{ $studentInfo->std_id }}]"
                                                        value="{{ $studentInfo->singleStudent->birth_place }}"
                                                        type="text" class="form-control input">
                                                </td>
                                                @php
                                                    $religions = ['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Others'];
                                                @endphp
                                                <td class="">
                                                    <select name="religion[{{ $studentInfo->std_id }}]" id=""
                                                        class="form-control input">
                                                        <option value="0">-- Select Religion --</option>
                                                        @foreach ($religions as $key => $value)
                                                            <option
                                                                {{ $studentInfo->singleStudent->religion == $key + 1 ? ' selected ' : '' }}
                                                                value="{{ $key + 1 }}">{{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                @php
                                                    $bloodGroups = ['A+', 'A-', 'B+', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                                @endphp
                                                <td class="">
                                                    <select name="blood_group[{{ $studentInfo->std_id }}]" id=""
                                                        class="form-control input">
                                                        <option value="">-- Select Blood Group --</option>
                                                        @foreach ($bloodGroups as $group)
                                                            <option
                                                                {{ $studentInfo->singleStudent->blood_group == $group ? ' selected ' : '' }}
                                                                value="{{ $group }}">{{ $group }}
                                                            </option>
                                                        @endforeach

                                                </td>
                                                <td class="">
                                                    <input type="number" value="{{ $studentInfo->gr_no }}"
                                                        name="gr_no[{{ $studentInfo->std_id }}]"
                                                        class="form-control input" />
                                                </td>
                                                @php
                                                    $presentAddress = '';
                                                    $permanentAddress = '';
                                                @endphp
                                                @if ($studentInfo->getStudentAddress)
                                                    @foreach ($studentInfo->getStudentAddress as $key => $address)
                                                        @if ($address->type == 'STUDENT_PRESENT_ADDRESS')
                                                            @php
                                                                $presentAddress = $address->address;
                                                            @endphp
                                                        @endif
                                                        @if ($address->type == 'STUDENT_PERMANENT_ADDRESS')
                                                            @php
                                                                $permanentAddress = $address->address;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <td class="">


                                                    <input name="presentaddress[{{ $studentInfo->std_id }}]"
                                                        value="{{ $presentAddress }}" type="text"
                                                        class="form-control input">
                                                </td>
                                                <td class="">

                                                    <input name="permanentaddress[{{ $studentInfo->std_id }}]"
                                                        value="{{ $permanentAddress }}" type="text"
                                                        class="form-control input">
                                                </td>
                                                <td class="">

                                                    <select name="nationality[{{ $studentInfo->std_id }}]"
                                                        class="form-control input" id="">

                                                        <option value="">-- Select Nationality --</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}"
                                                                @if ($studentInfo->singleStudent->nationalitys) @if ($studentInfo->singleStudent->nationalitys->id == $country->id)
                                                                    selected @endif
                                                                @endif
                                                                >
                                                                {{ $country->nationality }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </td>
                                                <td class="">
                                                    <input name="language[{{ $studentInfo->std_id }}]"
                                                        value="{{ $studentInfo->singleStudent->language }}"
                                                        type="text" class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="identification_mark[{{ $studentInfo->std_id }}]"
                                                        value="{{ $studentInfo->singleStudent->identification_mark }}"
                                                        type="text" class="form-control input">
                                                </td>
                                                @php
                                                    $hobby = '';
                                                    $aim = '';
                                                    $dream = '';
                                                    $idol = '';
                                                @endphp
                                                @if ($studentInfo->singleStudent->hobbyDreamIdolAim)
                                                    @foreach ($studentInfo->singleStudent->hobbyDreamIdolAim as $value)
                                                        @if ($value->type == 3)
                                                            @php
                                                                $hobby = $value->remarks;
                                                            @endphp
                                                        @endif
                                                        @if ($value->type == 4)
                                                            @php
                                                                $aim = $value->remarks;
                                                            @endphp
                                                        @endif
                                                        @if ($value->type == 5)
                                                            @php
                                                                $dream = $value->remarks;
                                                            @endphp
                                                        @endif
                                                        @if ($value->type == 6)
                                                            @php
                                                                $idol = $value->remarks;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <td class="">
                                                    <input name="hobby[{{ $studentInfo->std_id }}]"
                                                        value="{{ $hobby }}" type="text"
                                                        class="form-control input">

                                                </td>
                                                <td class="">
                                                    <input name="aim[{{ $studentInfo->std_id }}]"
                                                        value="{{ $aim }}" type="text"
                                                        class="form-control input">

                                                </td>
                                                <td class="">
                                                    <input name="dream[{{ $studentInfo->std_id }}]"
                                                        value="{{ $dream }}" type="text"
                                                        class="form-control input">

                                                </td>
                                                <td class="">
                                                    <input name="idol[{{ $studentInfo->std_id }}]"
                                                        value="{{ $idol }}" type="text"
                                                        class="form-control input">

                                                </td>
                                                {{-- <td class="">
                                                    <input name="tution_fees[{{ $studentInfo->std_id }}]"
                                                        value="{{ $studentInfo->singleStudent->singleEnrollment->tution_fees }}"
                                                        type="number" class="form-control input">
                                                </td> --}}
                                                @php
                                                    $fatherName = '';
                                                    $fatcherOCCPation = '';
                                                    $fatherContact = '';
                                                    $fatherEmail = '';
                                                    $motherName = '';
                                                    $motherOccpation = '';
                                                    $motherContact = '';
                                                    $motherEmail = '';
                                                @endphp
                                                @if ($studentInfo->singleStudent->singleParent)
                                                    @foreach ($studentInfo->singleStudent->singleParent as $parent)
                                                        @if($parent->singleGuardian)
                                                            @if ($parent->singleGuardian->type == 1)
                                                                @php
                                                                    $fatherName = $parent->singleGuardian->first_name;
                                                                    $fatcherOCCPation = $parent->singleGuardian->occupation;
                                                                    $fatherContact = $parent->singleGuardian->mobile;
                                                                    $fatherEmail = $parent->singleGuardian->email;
                                                                @endphp
                                                            @endif
                                                            @if ($parent->singleGuardian->type == 0)
                                                                @php
                                                                    $motherName = $parent->singleGuardian->first_name;
                                                                    $motherOccpation = $parent->singleGuardian->occupation;
                                                                    $motherContact = $parent->singleGuardian->mobile;
                                                                    $motherEmail = $parent->singleGuardian->email;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <td class="">
                                                    <input name="fathername[{{ $studentInfo->std_id }}]"
                                                        value="{{ $fatherName }}" type="text"
                                                        class="form-control input">

                                                </td>
                                                <td class="">
                                                    <input name="fatheroccupation[{{ $studentInfo->std_id }}]"
                                                        value="{{ $fatcherOCCPation }}" type="text"
                                                        class="form-control input">
                                                </td>
                                                <td class="">
                                                    <input name="fathercontact[{{ $studentInfo->std_id }}]"
                                                        value="{{ $fatherContact }}" type="text"
                                                        class="form-control input">

                                                </td>
                                                <td class="">
                                                    <input name="fatheremail[{{ $studentInfo->std_id }}]"
                                                        value="{{ $fatherEmail }}" type="email"
                                                        class="form-control input">

                                                </td>
                                                <td class="">
                                                    <input name="mothername[{{ $studentInfo->std_id }}]"
                                                        value="{{ $motherName }}" type="text"
                                                        class="form-control input">

                                                </td>
                                                <td class="">
                                                    <input name="motheroccupation[{{ $studentInfo->std_id }}]"
                                                        value="{{ $motherOccpation }}" type="text"
                                                        class="form-control input">

                                                </td>
                                                <td class="">
                                                    <input name="mothercontact[{{ $studentInfo->std_id }}]"
                                                        value="{{ $motherContact }}" type="text"
                                                        class="form-control input">

                                                </td>
                                                <td class="">
                                                    <input name="motheremail[{{ $studentInfo->std_id }}]"
                                                        value="{{ $motherEmail }}" type="email"
                                                        class="form-control input">

                                                </td>
                                                @if ($authRole == 'super-admin' || $authRole == 'admin')
                                                    <td>
                                                        <select name="admissionYear[{{ $studentInfo->std_id }}]"
                                                            id="" class="form-control input">
                                                            <option value="0">--select Year--</option>
                                                            @foreach ($academicAdmissionYear as $aciYear)
                                                                <option
                                                                    @if ($studentInfo->singleStudent->singleEnrollment->admissionYear) @if ($studentInfo->singleStudent->singleEnrollment->admissionYear->id == $aciYear->id)
                                                                        selected @endif
                                                                    @endif

                                                                    value="{{ $aciYear->id }}">
                                                                    {{ $aciYear->year_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>

                                                        <select name="academicYear[{{ $studentInfo->std_id }}]" id=""
                                                            class="form-control input">
                                                            <option value="0">--select Year--</option>
                                                            @foreach ($academicYears as $aciYear)
                                                                <option
                                                                    @if ($studentInfo->academicYear) @if ($studentInfo->academicYear->id == $aciYear->id)
                                                                        selected @endif
                                                                    @endif
                                                                    value="{{ $aciYear->id }}">
                                                                    {{ $aciYear->year_name }}</option>
                                                            @endforeach
                                                        </select>

                                                    </td>
                                                    <td>
                                                        <select name="academicLevel[{{ $studentInfo->std_id }}]"
                                                            id="" class="form-control input">
                                                            <option value="0">--select Level--</option>
                                                            @foreach ($levels as $level)
                                                                <option
                                                                    @if ($studentInfo->academicLevel) @if ($studentInfo->academicLevel->id == $level->id)
                                                                selected @endif
                                                                    @endif
                                                                    value="{{ $level->id }}">
                                                                    {{ $level->level_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="batch[{{ $studentInfo->std_id }}]" id=""
                                                            class="form-control input select-batch">
                                                            <option value="0">--select Batch--</option>
                                                            @foreach ($batches as $batch)
                                                                <option
                                                                    @if ($studentInfo->singleBatch) @if ($studentInfo->singleBatch->id == $batch->id)
                                                                selected @endif
                                                                    @endif
                                                                    value="{{ $batch->id }}">
                                                                    {{ $batch->batch_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>

                                                        <select name="section[{{ $studentInfo->std_id }}]"
                                                            class="form-control input select-section2">
                                                            @if ($studentInfo->singleSection)
                                                                <option
                                                                    value="{{ $studentInfo->singleSection->id }}">
                                                                    {{ $studentInfo->singleSection->section_name }}
                                                                </option>
                                                            @else
                                                                <option value="0">--select Section--</option>
                                                            @endif

                                                        </select>
                                                    </td>
                                                @else
                                                    <td>
                                                        <p class="inpu">
                                                            {{ $studentInfo->singleStudent->singleEnrollment->admissionYear? $studentInfo->singleStudent->singleEnrollment->admissionYear->year_name: '' }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="inpu">
                                                            {{ $studentInfo->academicYear ? $studentInfo->academicYear->year_name : 'null' }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="inpu">
                                                            {{ $studentInfo->academicLevel ? $studentInfo->academicLevel->level_name : 'null' }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="inpu">
                                                            {{ $studentInfo->singleBatch ? $studentInfo->singleBatch->batch_name : 'null' }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="inpu">
                                                            {{ $studentInfo->singleSection ? $studentInfo->singleSection->section_name : 'null' }}
                                                        </p>
                                                    </td>
                                                @endif

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
                                            <input type="checkbox" name="" id="allCheckbox">
                                            <label for="allCheckbox">All Mark</label>
                                        </th>
                                        @foreach ($selectForms as $form)
                                            @if ($form == 'CadetNumber')
                                                <th>Student Number</th>
                                            @endif
                                            @if ($form == 'admissionYear')
                                                <th>Admission Year</th>
                                            @endif
                                            @if ($form == 'academicYear')
                                                <th>Academic Year</th>
                                            @endif
                                            @if ($form == 'academicLevel')
                                                <th>Academic Level</th>
                                            @endif
                                            @if ($form == 'batch')
                                                <th>Batch</th>
                                            @endif
                                            @if ($form == 'section')
                                                <th>Section</th>
                                            @endif
                                            @if ($form == 'FirstName')
                                                <th>First Name</th>
                                            @endif
                                            @if ($form == 'LastName')
                                                <th>Last Name</th>
                                            @endif
                                            @if ($form == 'NickName')
                                                <th>Middle Name</th>
                                            @endif
                                            @if ($form == 'BengaliName')
                                                <th>Bengali Name</th>
                                            @endif
                                            @if ($form == 'Gender')
                                                <th>Gender</th>
                                            @endif
                                            @if ($form == 'DateofBirth')
                                                <th>Date of Birth</th>
                                            @endif
                                            @if ($form == 'BirthPlace')
                                                <th>Birth Place</th>
                                            @endif
                                            @if ($form == 'Religion')
                                                <th> Religion </th>
                                            @endif
                                            @if ($form == 'BloodGroup')
                                                <th>Blood Group</th>
                                            @endif
                                            @if ($form == 'MeritPosition')
                                                <th>Merit Position</th>
                                            @endif
                                            @if ($form == 'PresentAddress')
                                                <th>Present Address</th>
                                            @endif
                                            @if ($form == 'PermanentAddress')
                                                <th>Permanent Address</th>
                                            @endif
                                            @if ($form == 'Nationality')
                                                <th>Nationality</th>
                                            @endif
                                            @if ($form == 'Language')
                                                <th>Language</th>
                                            @endif
                                            @if ($form == 'IdentificationMarks')
                                                <th>Identification Marks</th>
                                            @endif
                                            @if ($form == 'Hobby')
                                                <th>Hobby</th>
                                            @endif
                                            @if ($form == 'Aim')
                                                <th>Aim</th>
                                            @endif
                                            @if ($form == 'Dream')
                                                <th>Dream</th>
                                            @endif
                                            @if ($form == 'Idol')
                                                <th>Idol</th>
                                            @endif
                                            {{-- @if ($form == 'TutionFees')
                                                    <th>Tution Fees</th>
                                                @endif --}}
                                            @if ($form == 'FatherName')
                                                <th>Father's Name</th>
                                            @endif
                                            @if ($form == 'FatherOccupation')
                                                <th>Father's Occupation </th>
                                            @endif
                                            @if ($form == 'FatherContact')
                                                <th>Father's Contact</th>
                                            @endif
                                            @if ($form == 'FatherEmail')
                                                <th>Father's Email</th>
                                            @endif
                                            @if ($form == 'MotherName')
                                                <th>Mother's Name</th>
                                            @endif
                                            @if ($form == 'MotherOccupation')
                                                <th>Mother's Occupation </th>
                                            @endif
                                            @if ($form == 'MotherContact')
                                                <th>Mother's Contact</th>
                                            @endif
                                            @if ($form == 'MotherEmail')
                                                <th>Mother's Email</th>
                                            @endif
                                        @endforeach
                                    </tr>

                                </thead>
                                @if (count($studentInfos) > 0)

                                    <tbody>
                                        @foreach ($studentInfos as $key => $studentInfo)
                                            <tr>
                                                <td>
                                                    {{ $key + 1 }}
                                                </td>
                                                <td class="text-start">
                                                    <input type="checkbox" name="upload[{{ $studentInfo->std_id }}]"
                                                        value="{{ $studentInfo->singleUser->username }}"
                                                        class="checkbox">
                                                </td>

                                                @foreach ($selectForms as $form)
                                                    @if ($form == 'CadetNumber')
                                                        <td>
                                                            <input name="user_name[{{ $studentInfo->std_id }}]"
                                                                value="{{ $studentInfo->singleUser->username }}"
                                                                type="text" class="form-control input" readonly>
                                                        </td>
                                                    @endif

                                                    @if ($form == 'FirstName')
                                                        <td class="">
                                                            <input name="first_name[{{ $studentInfo->std_id }}]"
                                                                value="{{ $studentInfo->singleStudent->first_name }}"
                                                                type="text" class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'LastName')
                                                        <td class="">
                                                            <input name="last_name[{{ $studentInfo->std_id }}]"
                                                                value="{{ $studentInfo->singleStudent->last_name }}"
                                                                type="text" class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'NickName')
                                                        <td class="">
                                                            <input name="nickname[{{ $studentInfo->std_id }}]"
                                                                value="{{ $studentInfo->singleStudent->middle_name }}"
                                                                type="text" class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'BengaliName')
                                                        <td class="">
                                                            <input name="bn_fullname[{{ $studentInfo->std_id }}]"
                                                                value="{{ $studentInfo->singleStudent->bn_fullname }}"
                                                                type="text" class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'Gender')
                                                        <td class="">
                                                            <select name="gender[{{ $studentInfo->std_id }}]" id=""
                                                                class="form-control input">
                                                                <option value="">-- Select Gender --</option>
                                                                <option
                                                                    {{ $studentInfo->singleStudent->gender == 'Male' ? ' selected ' : '' }}
                                                                    value="Male">Male</option>
                                                                <option
                                                                    {{ $studentInfo->singleStudent->gender == 'Female' ? ' selected ' : '' }}
                                                                    value="Female">Female</option>
                                                            </select>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'DateofBirth')
                                                        <td class="">
                                                            <input name="dob[{{ $studentInfo->std_id }}]"
                                                                value="{{ $studentInfo->singleStudent->dob }}"
                                                                type="date" class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'BirthPlace')
                                                        <td class="">
                                                            <input name="birth_place[{{ $studentInfo->std_id }}]"
                                                                value="{{ $studentInfo->singleStudent->birth_place }}"
                                                                type="text" class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'Religion')
                                                        @php
                                                            $religions = ['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Others'];
                                                        @endphp
                                                        <td class="">
                                                            <select name="religion[{{ $studentInfo->std_id }}]"
                                                                id="" class="form-control input">
                                                                <option value="0">-- Select Religion --</option>
                                                                @foreach ($religions as $key => $value)
                                                                    <option
                                                                        {{ $studentInfo->singleStudent->religion == $key + 1 ? ' selected ' : '' }}
                                                                        value="{{ $key + 1 }}">
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    @endif
                                                    @if ($form == 'BloodGroup')
                                                        @php
                                                            $bloodGroups = ['A+', 'A-', 'B+', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                                        @endphp
                                                        <td class="">
                                                            <select name="blood_group[{{ $studentInfo->std_id }}]"
                                                                id="" class="form-control input">
                                                                <option value="">-- Select Blood Group --</option>
                                                                @foreach ($bloodGroups as $group)
                                                                    <option
                                                                        {{ $studentInfo->singleStudent->blood_group == $group ? ' selected ' : '' }}
                                                                        value="{{ $group }}">
                                                                        {{ $group }}
                                                                    </option>
                                                                @endforeach

                                                        </td>
                                                    @endif
                                                    @if ($form == 'MeritPosition')
                                                        <td class="">

                                                            <input type="number" value="{{ $studentInfo->gr_no }}"
                                                                name="gr_no[{{ $studentInfo->std_id }}]"
                                                                class="input form-control">
                                                        </td>
                                                    @endif
                                                    @php
                                                        $presentAddress = '';
                                                        $permanentAddress = '';
                                                    @endphp
                                                    @if ($studentInfo->getStudentAddress)
                                                        @foreach ($studentInfo->getStudentAddress as $key => $address)
                                                            @if ($address->type == 'STUDENT_PRESENT_ADDRESS')
                                                                @php
                                                                    $presentAddress = $address->address;
                                                                @endphp
                                                            @endif
                                                            @if ($address->type == 'STUDENT_PERMANENT_ADDRESS')
                                                                @php
                                                                    $permanentAddress = $address->address;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if ($form == 'PresentAddress')
                                                        <td class="">

                                                            <input name="presentaddress[{{ $studentInfo->std_id }}]"
                                                                value="{{ $presentAddress }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'PermanentAddress')
                                                        <td class="">
                                                            <input
                                                                name="permanentaddress[{{ $studentInfo->std_id }}]"
                                                                value="{{ $permanentAddress }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'Nationality')
                                                        <td class="">

                                                            <select name="nationality[{{ $studentInfo->std_id }}]"
                                                                class="form-control input" id="">

                                                                <option value="">-- Select Nationality --</option>
                                                                @foreach ($countries as $country)
                                                                    <option value="{{ $country->id }}"
                                                                        @if ($studentInfo->singleStudent->nationalitys) @if ($studentInfo->singleStudent->nationalitys->id == $country->id)
                                                                            selected @endif
                                                                        @endif
                                                                        >
                                                                        {{ $country->nationality }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                        </td>
                                                    @endif
                                                    @if ($form == 'Language')
                                                        <td class="">
                                                            <input name="language[{{ $studentInfo->std_id }}]"
                                                                value="{{ $studentInfo->singleStudent->language }}"
                                                                type="text" class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'IdentificationMarks')
                                                        <td class="">
                                                            <input
                                                                name="identification_mark[{{ $studentInfo->std_id }}]"
                                                                value="{{ $studentInfo->singleStudent->identification_mark }}"
                                                                type="text" class="form-control input">
                                                        </td>
                                                    @endif
                                                    @php
                                                        $hobby = '';
                                                        $aim = '';
                                                        $dream = '';
                                                        $idol = '';
                                                    @endphp
                                                    @if ($studentInfo->singleStudent->hobbyDreamIdolAim)
                                                        @foreach ($studentInfo->singleStudent->hobbyDreamIdolAim as $value)
                                                            @if ($value->type == 3)
                                                                @php
                                                                    $hobby = $value->remarks;
                                                                @endphp
                                                            @endif
                                                            @if ($value->type == 4)
                                                                @php
                                                                    $aim = $value->remarks;
                                                                @endphp
                                                            @endif
                                                            @if ($value->type == 5)
                                                                @php
                                                                    $dream = $value->remarks;
                                                                @endphp
                                                            @endif
                                                            @if ($value->type == 6)
                                                                @php
                                                                    $idol = $value->remarks;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if ($form == 'Hobby')
                                                        <td class="">
                                                            <input name="hobby[{{ $studentInfo->std_id }}]"
                                                                value="{{ $hobby }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'Aim')
                                                        <td class="">
                                                            <input name="aim[{{ $studentInfo->std_id }}]"
                                                                value="{{ $aim }}" type="text"
                                                                class="form-control input">

                                                        </td>
                                                    @endif
                                                    @if ($form == 'Dream')
                                                        <td class="">
                                                            <input name="dream[{{ $studentInfo->std_id }}]"
                                                                value="{{ $dream }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'Idol')
                                                        <td class="">
                                                            <input name="idol[{{ $studentInfo->std_id }}]"
                                                                value="{{ $idol }}" type="text"
                                                                class="form-control input">
                                                        </td class="">
                                                    @endif
                                                    {{-- @if ($form == 'TutionFees')
                                                        <td class="">
                                                            <input name="tution_fees[{{ $studentInfo->std_id }}]"
                                                                value="{{ $studentInfo->singleStudent->singleEnrollment->tution_fees }}"
                                                                type="number" class="form-control input">
                                                        </td>
                                                    @endif --}}
                                                    @php
                                                        $fatherName = '';
                                                        $fatcherOCCPation = '';
                                                        $fatherContact = '';
                                                        $fatherEmail = '';
                                                        $motherName = '';
                                                        $motherOccpation = '';
                                                        $motherContact = '';
                                                        $motherEmail = '';
                                                    @endphp
                                                     @if ($studentInfo->singleStudent->singleParent)
                                                     @foreach ($studentInfo->singleStudent->singleParent as $parent)
                                                         @if($parent->singleGuardian)
                                                             @if ($parent->singleGuardian->type == 1)
                                                                 @php
                                                                     $fatherName = $parent->singleGuardian->first_name;
                                                                     $fatcherOCCPation = $parent->singleGuardian->occupation;
                                                                     $fatherContact = $parent->singleGuardian->mobile;
                                                                     $fatherEmail = $parent->singleGuardian->email;
                                                                 @endphp
                                                             @endif
                                                             @if ($parent->singleGuardian->type == 0)
                                                                 @php
                                                                     $motherName = $parent->singleGuardian->first_name;
                                                                     $motherOccpation = $parent->singleGuardian->occupation;
                                                                     $motherContact = $parent->singleGuardian->mobile;
                                                                     $motherEmail = $parent->singleGuardian->email;
                                                                 @endphp
                                                             @endif
                                                         @endif
                                                     @endforeach
                                                 @endif
                                                    @if ($form == 'FatherName')
                                                        <td class="">
                                                            <input name="fathername[{{ $studentInfo->std_id }}]"
                                                                value="{{ $fatherName }}" type="text"
                                                                class="form-control input">

                                                        </td>
                                                    @endif
                                                    @if ($form == 'FatherOccupation')
                                                        <td class="">
                                                            <input
                                                                name="fatheroccupation[{{ $studentInfo->std_id }}]"
                                                                value="{{ $fatcherOCCPation }}" type="text"
                                                                class="form-control input">

                                                        </td>
                                                    @endif
                                                    @if ($form == 'FatherContact')
                                                        <td class="">
                                                            <input name="fathercontact[{{ $studentInfo->std_id }}]"
                                                                value="{{ $fatherContact }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'FatherEmail')
                                                        <td class="">
                                                            <input name="fatheremail[{{ $studentInfo->std_id }}]"
                                                                value="{{ $fatherEmail }}" type="email"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'MotherName')
                                                        <td class="">
                                                            <input name="mothername[{{ $studentInfo->std_id }}]"
                                                                value="{{ $motherName }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'MotherOccupation')
                                                        <td class="">
                                                            <input
                                                                name="motheroccupation[{{ $studentInfo->std_id }}]"
                                                                value="{{ $motherOccpation }}" type="text"
                                                                class="form-control input">

                                                        </td>
                                                    @endif
                                                    @if ($form == 'MotherContact')
                                                        <td class="">
                                                            <input name="mothercontact[{{ $studentInfo->std_id }}]"
                                                                value="{{ $motherContact }}" type="text"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($form == 'MotherEmail')
                                                        <td class="">
                                                            <input name="motheremail[{{ $studentInfo->std_id }}]"
                                                                value="{{ $motherEmail }}" type="email"
                                                                class="form-control input">
                                                        </td>
                                                    @endif
                                                    @if ($authRole == 'super-admin' || $authRole == 'admin')
                                                        @if ($form == 'admissionYear')
                                                            <td>

                                                                <select
                                                                    name="admissionYear[{{ $studentInfo->std_id }}]"
                                                                    id="" class="form-control input">
                                                                    <option value="">--select Year--</option>
                                                                    @foreach ($academicAdmissionYear as $aciYear)
                                                                        <option
                                                                            @if ($studentInfo->singleStudent->singleEnrollment->admissionYear) @if ($studentInfo->singleStudent->singleEnrollment->admissionYear->id == $aciYear->id)
                                                                                selected @endif
                                                                            @endif

                                                                            value="{{ $aciYear->id }}">
                                                                            {{ $aciYear->year_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        @endif
                                                        @if ($form == 'academicYear')
                                                            <td>

                                                                <select
                                                                    name="academicYear[{{ $studentInfo->std_id }}]"
                                                                    id="" class="form-control input">
                                                                    <option value="0">--select Year--</option>
                                                                    @foreach ($academicYears as $aciYear)
                                                                        <option
                                                                            @if ($studentInfo->academicYear) @if ($studentInfo->academicYear->id == $aciYear->id)
                                                                                selected @endif
                                                                            @endif
                                                                            value="{{ $aciYear->id }}">
                                                                            {{ $aciYear->year_name }}</option>
                                                                    @endforeach
                                                                </select>

                                                            </td>
                                                        @endif
                                                        @if ($form == 'academicLevel')
                                                            <td>
                                                                <select
                                                                    name="academicLevel[{{ $studentInfo->std_id }}]"
                                                                    id="" class="form-control input">
                                                                    <option value="0">--select Level--</option>
                                                                    @foreach ($levels as $level)
                                                                        <option
                                                                            @if ($studentInfo->academicLevel) @if ($studentInfo->academicLevel->id == $level->id)
                                                                        selected @endif
                                                                            @endif
                                                                            value="{{ $level->id }}">
                                                                            {{ $level->level_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        @endif
                                                        @if ($form == 'batch')
                                                            <td>
                                                                <select name="batch[{{ $studentInfo->std_id }}]"
                                                                    id="" class="form-control input select-batch">
                                                                    <option value="0">--select Batch--</option>
                                                                    @foreach ($batches as $batch)
                                                                        <option
                                                                            @if ($studentInfo->singleBatch) @if ($studentInfo->singleBatch->id == $batch->id)
                                                                        selected @endif
                                                                            @endif
                                                                            value="{{ $batch->id }}">
                                                                            {{ $batch->batch_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        @endif
                                                        @if ($form == 'section')
                                                            <td>

                                                                <select name="section[{{ $studentInfo->std_id }}]"
                                                                    class="form-control input select-section2">
                                                                    @if ($studentInfo->singleSection)
                                                                        <option
                                                                            value="{{ $studentInfo->singleSection->id }}">
                                                                            {{ $studentInfo->singleSection->section_name }}
                                                                        </option>
                                                                    @else
                                                                        <option value="0">--select Section--</option>
                                                                    @endif

                                                                </select>
                                                            </td>
                                                        @endif
                                                    @else
                                                        @if ($form == 'admissionYear')
                                                            <td>
                                                                <p class="inpu">
                                                                    {{ $studentInfo->singleStudent->singleEnrollment->admissionYear? $studentInfo->singleStudent->singleEnrollment->admissionYear->year_name: '' }}
                                                                </p>
                                                            </td>
                                                        @endif
                                                        @if ($form == 'academicYear')
                                                            <td>
                                                                <p class="inpu">
                                                                    {{ $studentInfo->academicYear ? $studentInfo->academicYear->year_name : 'null' }}
                                                                </p>
                                                            </td>
                                                        @endif
                                                        @if ($form == 'academicLevel')
                                                            <td>
                                                                <p class="inpu">
                                                                    {{ $studentInfo->academicLevel ? $studentInfo->academicLevel->level_name : 'null' }}
                                                                </p>
                                                            </td>
                                                        @endif
                                                        @if ($form == 'batch')
                                                            <td>
                                                                <p class="inpu">
                                                                    {{ $studentInfo->singleBatch ? $studentInfo->singleBatch->batch_name : 'null' }}
                                                                </p>
                                                            </td>
                                                        @endif
                                                        @if ($form == 'section')
                                                            <td>
                                                                <p class="inpu">
                                                                    {{ $studentInfo->singleSection ? $studentInfo->singleSection->section_name : 'null' }}
                                                                </p>
                                                            </td>
                                                        @endif
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
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('#allCheckbox').click(function(e) {
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
        });

    })

    $('.select-batch').change(function() {

        // Ajax Request Start
        var parent = $(this).parent().parent();
        $_token = "{{ csrf_token() }}";

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            },
            url: "{{ url('/student/cadet/search-section') }}",
            type: 'GET',
            cache: false,
            data: {
                '_token': $_token,
                'batch': $(this).val()
            }, //see the _token
            datatype: 'application/json',

            beforeSend: function() {},

            success: function(data) {
                var txt = '<option value="0">Select Section*</option>';
                data.forEach(element => {
                    txt += '<option value="' + element.id + '">' + element
                        .section_name + '</option>';
                });

                var childSection = parent.find(
                    '.select-section2');
                childSection.empty();
                childSection.append(txt)


            }
        });
        // Ajax Request End
    });
</script>

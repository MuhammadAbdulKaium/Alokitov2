<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Profile Edit</title>
    <style>
        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        

        .header {
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0;
        }

        .logo {
            width: 20%;
            float: left;
            text-align: center;
        }
        .logo img{
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }

        .headline {
            width: 80%;
            float: right;
            padding: 0 20px;
            text-align: left;
            margin-top: 25px;
           
        }
        .headline p{
            font-size: 30px;
        }

        td {
            font-size: 16px;
            padding: 3px 0;
        }

        a {
            text-decoration: none
        }



       

        .top-table {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .top-table table {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        .top-table table tr td,
        .top-table table tr th {
            border: 1px solid #000;
            text-align: center;
            word-break: break-all;
            padding: 3px !important;
            word-wrap: break-word;
            font-size: 20px;
        }

        /* .bottom-table {
            width: 100%;
            overflow: hidden;
        }

        */
        .bottom-table table {
            border-collapse: collapse;

        }

       

        .hidden_border {
            border-style: none !important;
        }

        .terms {
            border-bottom: 2px solid black;
            width: 175px;
        }

        ul {
            color: blue;
            list-style: none;
            margin: 0;
            padding: 0;
        }


        h1,
        h3,
        h4,
        p {
            margin: 5px 0;
        }

        h2 {
            margin: 10px 0;
        }

        .text-center {
            text-align: center;
        }

        .approved {
            text-transform: capitalize;
            color: rgba(3, 80, 3, 0.911);
            font-size: 18px
        }

        .pending {
            text-transform: capitalize;
            color: red;
            font-size: 18px;
        }

        .signature {
            width: 18%;
            /* float: left; */
            margin: 0 5.4px;
            display: inline-block;
            height: 250px;
            height: auto;
            vertical-align: top;
        }



        .signature p {
            /* border-top: 1px solid #f1f1f1; */
            margin: 0;
            font-size: 12px;
        }

        .signotry_img {
            height: 60px;
            widows: 100%;
            text-align: center;
        }

        .signotry_img img {
            height: 100%;
            widows: 100%;
            overflow: hidden;
        }

        .footer {
            
            margin-top: 30px;
            font-size: 40px;
        }

        .cadet_Name {
            margin-top: 0;
            font-size: 60px;
            font-weight: 900;
        }
        .title{
            text-align: center;
            font-size: 42px;
            font-weight: 900;
            padding: 5px 0;
        }
        footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            font-size: 40px;
            /* background-color: #002d00; */
            color: black;
            height: 50px;
        }
    </style>
</head>

<body>
    <footer >
        <div style="padding:.5rem">
            <span  >Printed from <b>{{ $institute->institute_alias }}</b> by {{$user->name}} on <?php echo date('l jS \of F Y h:i:s A'); ?> </span>
    
        </div>
        <script type="text/php">
        if (isset($pdf)) {
            $x = 2230;
            $y = 1640;
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = null;
            $size = 25;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
    
    
    </footer>
    <div class="header clearfix">
        <div class="logo">
            <img src="{{ public_path('assets/users/images/' . $institute->logo) }}" alt="logo">
        </div>
        <div class="headline">
            <h1 class="cadet_Name">{{ $institute->institute_name }}</h1>
            <p>{{ $institute->address1 }} </p>
            <p>
                <strong>Phone:</strong>{{ $institute->phone }} <strong>Email:</strong> <a
                    href="{{ $institute->email }}" target="_blank">{{ $institute->email }}</a> <strong>Web:</strong>
                <a href=" {{ $institute->website }}" target="_blank"> {{ $institute->website }}</a>
            </p>
        </div>
    </div>
    <div class="content clearfix">

        <h1 class="title">Student Profile Edit</h1>


        <div class="clearfix">
            <div class="top-table">
                <table id="myTable" class="table table-striped table-bordered display">
                    @if (!$selectForms)

                        <thead>
                            <tr>
                                <th class="text-center">Si</th>
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
                                            {{ $studentInfo->singleUser ? $studentInfo->singleUser->username : ' ' }}
                                        </td>

                                        <td class="">
                                            {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->first_name : ' ' }}
                                        </td>
                                        <td class="">
                                            {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->last_name : ' ' }}
                                        </td>
                                        <td class="">
                                            {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->middle_name : ' ' }}
                                        </td>
                                        <td class="">
                                            {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->bn_fullname : ' ' }}
                                        </td>
                                        <td class="">
                                            {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->gender : ' ' }}
                                        </td>
                                        <td class="">
                                            {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->dob : ' ' }}
                                        </td>
                                        <td class="">
                                            {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->birth_place : ' ' }}
                                        </td>
                                        @php
                                            $religions = ['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Others'];
                                        @endphp
                                        <td class="">
                                            @foreach ($religions as $key => $value)
                                                @if ($studentInfo->singleStudent)
                                                    @if ($studentInfo->singleStudent->religion && $studentInfo->singleStudent->religion == $key + 1)
                                                        {{ $value }}
                                                    @endif
                                                @endif
                                            @endforeach
                                        </td>

                                        <td class="">
                                            {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->blood_group : ' ' }}
                                        </td>
                                        <td class="">
                                            {{ $studentInfo->gr_no }}
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
                                            {{ $presentAddress }}
                                        </td>
                                        <td class="">

                                            {{ $permanentAddress }}
                                        </td>
                                        <td class="">

                                            {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->nationalitys->name : '' }}

                                        </td>
                                        <td class="">
                                            {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->language : ' ' }}
                                        </td>
                                        <td class="">
                                            {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->identification_mark : ' ' }}
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
                                            {{ $hobby }}

                                        </td>
                                        <td class="">
                                            {{ $aim }}

                                        </td>
                                        <td class="">
                                            {{ $dream }}

                                        </td>
                                        <td class="">
                                            {{ $idol }}

                                        </td>

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
                                                @if ($parent->singleGuardian)
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
                                            {{ $fatherName }}

                                        </td>
                                        <td class="">
                                            {{ $fatcherOCCPation }}
                                        </td>
                                        <td class="">
                                            {{ $fatherContact }}

                                        </td>
                                        <td class="">
                                            {{ $fatherEmail }}

                                        </td>
                                        <td class="">
                                            {{ $motherName }}

                                        </td>
                                        <td class="">
                                            {{ $motherOccpation }}

                                        </td>
                                        <td class="">
                                            {{ $motherContact }}

                                        </td>
                                        <td class="">
                                            {{ $motherEmail }}

                                        </td>
                                        @if ($authRole == 'super-admin' || $authRole == 'admin')
                                            <td>
                                                @if ($studentInfo->singleStudent->singleEnrollment)
                                                    {{ $studentInfo->singleStudent->singleEnrollment->admissionYear? $studentInfo->singleStudent->singleEnrollment->admissionYear->year_name: ' ' }}
                                                @endif

                                            </td>
                                            <td>
                                                {{ $studentInfo->academicYear ? $studentInfo->academicYear->year_name : ' ' }}
                                            </td>
                                            <td>
                                                {{ $studentInfo->academicLevel ? $studentInfo->academicLevel->level_name : ' ' }}

                                            </td>
                                            <td>
                                                {{ $studentInfo->singleBatch ? $studentInfo->singleBatch->batch_name : ' ' }}

                                            </td>
                                            <td>

                                                {{ $studentInfo->singleSection ? $studentInfo->singleSection->section_name : ' ' }}
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
                                    <td colspan="34" class="text-center">Data Not Found</td>
                                </tr>
                            </tbody>
                        @endif
                    @else
                        <thead>
                            <tr>
                                <th class="">Si</th>
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


                                        @foreach ($selectForms as $form)
                                            @if ($form == 'CadetNumber')
                                                <td>
                                                    {{ $studentInfo->singleUser ? $studentInfo->singleUser->username : ' ' }}
                                                </td>
                                            @endif

                                            @if ($form == 'FirstName')
                                                <td class="">
                                                    {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->first_name : ' ' }}
                                                </td>
                                            @endif
                                            @if ($form == 'LastName')
                                                <td class="">
                                                    {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->last_name : ' ' }}
                                                </td>
                                            @endif
                                            @if ($form == 'NickName')
                                                <td class="">
                                                    {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->middle_name : ' ' }}
                                                </td>
                                            @endif
                                            @if ($form == 'BengaliName')
                                                <td class="">
                                                    {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->bn_fullname : ' ' }}
                                                </td>
                                            @endif
                                            @if ($form == 'Gender')
                                                <td class="">
                                                    {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->gender : ' ' }}
                                                </td>
                                            @endif
                                            @if ($form == 'DateofBirth')
                                                <td class="">
                                                    {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->dob : ' ' }}
                                                </td>
                                            @endif
                                            @if ($form == 'BirthPlace')
                                                <td class="">
                                                    {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->birth_place : ' ' }}
                                                </td>
                                            @endif
                                            @if ($form == 'Religion')
                                                @php
                                                    $religions = ['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Others'];
                                                @endphp
                                                <td class="">
                                                    @foreach ($religions as $key => $value)
                                                        @if ($studentInfo->singleStudent)
                                                            @if ($studentInfo->singleStudent->religion && $studentInfo->singleStudent->religion == $key + 1)
                                                                {{ $value }}
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @endif
                                            @if ($form == 'BloodGroup')

                                                <td class="">
                                                    {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->blood_group : ' ' }}

                                                </td>
                                            @endif
                                            @if ($form == 'MeritPosition')
                                                <td class="">

                                                    {{ $studentInfo->gr_no }}
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

                                                    {{ $presentAddress }}
                                                </td>
                                            @endif
                                            @if ($form == 'PermanentAddress')
                                                <td class="">
                                                    {{ $permanentAddress }}
                                                </td>
                                            @endif
                                            @if ($form == 'Nationality')
                                                <td class="">

                                                    {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->nationalitys->name : '' }}

                                                </td>
                                            @endif
                                            @if ($form == 'Language')
                                                <td class="">
                                                    {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->language : ' ' }}
                                                </td>
                                            @endif
                                            @if ($form == 'IdentificationMarks')
                                                <td class="">
                                                    {{ $studentInfo->singleStudent ? $studentInfo->singleStudent->identification_mark : ' ' }}
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
                                                    {{ $hobby }}
                                                </td>
                                            @endif
                                            @if ($form == 'Aim')
                                                <td class="">
                                                    {{ $aim }}

                                                </td>
                                            @endif
                                            @if ($form == 'Dream')
                                                <td class="">
                                                    {{ $dream }}
                                                </td>
                                            @endif
                                            @if ($form == 'Idol')
                                                <td class="">
                                                    {{ $idol }}
                                                </td>
                                            @endif

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
                                                    @if ($parent->singleGuardian)
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
                                                    {{ $fatherName }}

                                                </td>
                                            @endif
                                            @if ($form == 'FatherOccupation')
                                                <td class="">
                                                    {{ $fatcherOCCPation }}

                                                </td>
                                            @endif
                                            @if ($form == 'FatherContact')
                                                <td class="">
                                                    {{ $fatherContact }}
                                                </td>
                                            @endif
                                            @if ($form == 'FatherEmail')
                                                <td class="">
                                                    {{ $fatherEmail }}
                                                </td>
                                            @endif
                                            @if ($form == 'MotherName')
                                                <td class="">
                                                    {{ $motherName }}
                                                </td>
                                            @endif
                                            @if ($form == 'MotherOccupation')
                                                <td class="">
                                                    {{ $motherOccpation }}

                                                </td>
                                            @endif
                                            @if ($form == 'MotherContact')
                                                <td class="">
                                                    {{ $motherContact }}
                                                </td>
                                            @endif
                                            @if ($form == 'MotherEmail')
                                                <td class="">
                                                    {{ $motherEmail }}
                                                </td>
                                            @endif
                                            @if ($authRole == 'super-admin' || $authRole == 'admin')
                                                @if ($form == 'admissionYear')
                                                    <td>

                                                        @if ($studentInfo->singleStudent->singleEnrollment)
                                                            {{ $studentInfo->singleStudent->singleEnrollment->admissionYear? $studentInfo->singleStudent->singleEnrollment->admissionYear->year_name: ' ' }}
                                                        @endif
                                                    </td>
                                                @endif
                                                @if ($form == 'academicYear')
                                                    <td>

                                                        {{($studentInfo->academicYear)?$studentInfo->academicYear->year_name:" "}}

                                                    </td>
                                                @endif
                                                @if ($form == 'academicLevel')
                                                    <td>
                                                        {{ ($studentInfo->academicLevel)?$studentInfo->academicLevel->level_name:" " }}
                                                    </td>
                                                @endif
                                                @if ($form == 'batch')
                                                    <td>
                                                        {{ ($studentInfo->singleBatch)?$studentInfo->singleBatch->batch_name:" " }}
                                                    </td>
                                                @endif
                                                @if ($form == 'section')
                                                    <td>

                                                        {{ ($studentInfo->singleSection)?$studentInfo->singleSection->section_name:" " }}
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
                                    <td colspan="34" class="text-center">Data Not Found</td>
                                </tr>
                            </tbody>
                        @endif
                    @endif

                </table>
            </div>


        </div>
    </div>

    {{-- <div class="footer">
        <p class="footer">
            Printed from {{ $institute->institute_alias }} ICT {{ Auth::user()->username }}, on
            {{ date('d/M/Y') }} at {{date('H:i:s A',time())}}
        </p>
    </div> --}}
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HR Profile Edit</title>
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

        .logo img {
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

        .headline p {
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

        .title {
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
    <footer>
        <div style="padding:.5rem">
            <span>Printed from <b>{{ $institute->institute_alias }}</b> by {{ $user->name }} on <?php echo date('l jS \of F Y h:i:s A'); ?>
            </span>

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

        <h1 class="title">HR Profile Edit</h1>


        <div class="clearfix">
            <div class="top-table">
                <table id="myTable" class="table table-bordered table-striped  display" border="2">
                    @if (!$selectForm)

                        <thead>
                            <tr>
                                <th class="text-center">Si</th>
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

                                @foreach ($allEmployee as $key => $employee)
                                    <tr>
                                        <td class="si_no"> {{ $loop->index + 1 }}</td>
                                        <td>
                                            <p class="input">
                                                {{ $employee->singleUser ? $employee->singleUser->username : '' }}
                                            </p>
                                        </td>
                                        <td>
                                            @if ($employee->singleUser->singleroleUser)
                                                {{ $employee->singleUser->singleroleUser->singleRole? $employee->singleUser->singleroleUser->singleRole->display_name: ' ' }}
                                            @endif

                                        </td>

                                        <td>
                                            {{ $employee->title ? $employee->title : ' ' }}
                                        </td>

                                        <td>
                                            {{ $employee->first_name }}
                                        </td>
                                        <td>
                                            {{ $employee->middle_name }}
                                        </td>
                                        <td class="">
                                            {{ $employee->last_name }}
                                        </td>
                                        <td class="">
                                            {{ $employee->employee_no }}
                                        </td>
                                        <td>
                                            {{ $employee->position_serial }}
                                        </td>
                                        <td class="">
                                            {{ $employee->alias }}
                                        </td>
                                        <td class="">
                                            {{ $employee->gender }}
                                        </td>
                                        <td class="">
                                            {{ $employee->dob }}
                                        </td>
                                        <td class="">
                                            {{ $employee->doj }}
                                        </td>
                                        <td class="">
                                            {{ $employee->dor }}
                                        </td>
                                        <td class="">
                                            {{ $employee->singleDepartment ? $employee->singleDepartment->name : ' ' }}

                                        </td>
                                        <td class="">
                                            {{ $employee->singleDesignation ? $employee->singleDesignation->name : ' ' }}
                                        </td>
                                        <td class="">
                                            @switch($employee->category)
                                                @case(1)
                                                    Teaching
                                                @break

                                                @default
                                                    Non Teaching
                                            @endswitch

                                        </td>
                                        <td class="">
                                            {{ $employee->email }}
                                        </td>
                                        <td class="">
                                            {{ $employee->phone }}
                                        </td>
                                        <td class="">
                                            {{ $employee->alt_mobile }}
                                        </td>

                                        <td class="">
                                            {{ $employee->blood_group }}
                                        </td>
                                        <td class="">
                                            {{ $employee->birth_place }}
                                        </td>
                                        @php
                                            $religions = ['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Others'];
                                        @endphp
                                        <td class="">
                                            @foreach ($religions as $key => $value)
                                                @if ($employee->religion == $key + 1)
                                                    {{ $value }}
                                                @endif
                                            @endforeach
                                        </td>

                                        <td class="">
                                            {{ $employee->marital_status }}
                                        </td>
                                        <td class="">
                                            @if ($allNationality)
                                                @foreach ($allNationality as $nationality)
                                                    @if ($employee->nationality == $nationality->id)
                                                        {{ $nationality->nationality }}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>

                                        <td class="">
                                            {{ $employee->experience_year }}

                                        </td>
                                        <td class="">
                                            {{ $employee->experience_month }}

                                        </td>
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
                                        <td>

                                            {{ $presentAddress }}
                                        </td>
                                        <td>
                                            {{ $permanentAddress }}

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                            <tbody class="row_position">
                                <tr>
                                    <td colspan="29" class="text-center">Data Not Found</td>
                                </tr>
                            </tbody>

                        @endif
                    @else
                        <thead>
                            <tr>
                                <th class="">Si</th>
                                @foreach ($selectForm as $form)
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

                                @foreach ($allEmployee as $key => $employee)
                                    <tr>
                                        <td class="si_no">
                                            {{ $loop->index + 1 }}
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
                                                    {{ $employee->first_name }}
                                                </td>
                                            @elseif ($form == 'role')
                                                <td>
                                                    @if ($employee->singleUser->singleroleUser)
                                                        {{ $employee->singleUser->singleroleUser->singleRole? $employee->singleUser->singleroleUser->singleRole->display_name: ' ' }}
                                                    @endif
                                                </td>
                                            @elseif ($form == 'title')
                                                <td>
                                                    {{ $employee->title ? $employee->title : ' ' }}
                                                </td>
                                            @elseif ($form == 'middle_name')
                                                <td class="">
                                                    {{ $employee->middle_name }}
                                                </td>
                                            @endif

                                            @if ($form == 'last_name')
                                                <td class="">
                                                    {{ $employee->last_name }}
                                                </td>
                                            @endif
                                            @if ($form == 'employee_no')
                                                <td class="">
                                                    {{ $employee->employee_no }}
                                                </td>
                                            @endif
                                            @if ($form == 'position_serial')
                                                <td class="">
                                                    {{ $employee->position_serial }}
                                                </td>
                                            @endif
                                            @if ($form == 'alias')
                                                <td class="">
                                                    {{ $employee->alias }}
                                                </td>
                                            @endif
                                            @if ($form == 'gender')
                                                <td class="">
                                                    {{ $employee->gender }}
                                                </td>
                                            @endif
                                            @if ($form == 'dob')
                                                <td class="">
                                                    {{ $employee->dob }}
                                                </td>
                                            @endif
                                            @if ($form == 'doj')
                                                <td class="">
                                                    {{ $employee->doj }}
                                                </td>
                                            @endif
                                            @if ($form == 'dor')
                                                <td class="">
                                                    {{ $employee->dor }}
                                                </td>
                                            @endif
                                            @if ($form == 'department')
                                                <td class="">
                                                    {{ $employee->singleDepartment ? $employee->singleDepartment->name : ' ' }}
                                                </td>
                                            @endif
                                            @if ($form == 'designation')
                                                <td class="">
                                                    {{ $employee->singleDesignation ? $employee->singleDesignation->name : ' ' }}
                                                </td>
                                            @endif
                                            @if ($form == 'category')
                                                <td class="">
                                                    @switch($employee->category)
                                                        @case(1)
                                                            Teaching
                                                        @break

                                                        @default
                                                            Non Teaching
                                                    @endswitch
                                                </td>
                                            @endif
                                            @if ($form == 'email')
                                                <td class="">
                                                    {{ $employee->email }}
                                                </td>
                                            @endif
                                            @if ($form == 'phone')
                                                <td class="">
                                                    {{ $employee->phone }}
                                                </td>
                                            @endif
                                            @if ($form == 'alt_mobile')
                                                <td class="">
                                                    {{ $employee->alt_mobile }}
                                                </td>
                                            @endif
                                            @if ($form == 'blood_group')
                                                <td class="">
                                                    {{ $employee->blood_group }}
                                                </td>
                                            @endif
                                            @if ($form == 'birth_place')
                                                <td class="">
                                                    {{ $employee->birth_place }}
                                                </td>
                                            @endif
                                            @if ($form == 'religion')
                                                @php
                                                    $religions = ['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Others'];
                                                @endphp
                                                <td class="">
                                                    @foreach ($religions as $key => $value)
                                                        @if ($employee->religion == $key + 1)
                                                            {{ $value }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @endif
                                            @if ($form == 'marital_status')
                                                <td class="">
                                                    {{ $employee->marital_status }}
                                                </td>
                                            @endif
                                            @if ($form == 'nationality')
                                                <td class="">
                                                    @if ($allNationality)
                                                        @foreach ($allNationality as $nationality)
                                                            @if ($employee->nationality == $nationality->id)
                                                                {{ $nationality->nationality }}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                            @endif
                                            @if ($form == 'experience_year')
                                                <td class="">

                                                    {{ $employee->experience_year }}
                                                </td>
                                            @endif
                                            @if ($form == 'experience_month')
                                                <td class="">
                                                    {{ $employee->experience_month }}
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
                                                    {{ $presentAddress }}
                                                </td>
                                            @endif
                                            @if ($form == 'permanent_address')
                                                <td>
                                                    {{ $permanentAddress }}

                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                            <tbody class="row_position">
                                <tr>
                                    <td colspan="29" class="text-center">Data Not Found</td>
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

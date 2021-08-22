<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>

        table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
            border: 1px solid black;
        }

        tr, td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 7px;
            line-height: 5px;
        }
        /*.heading {*/
        /*border: none !important;*/
        /*}*/

        .header { left: 0px; right: 0px; height: 50px; text-align: center;}



        .header-section {
            width: 100%;
            position: relative;
        }
        .header-section .logo {
            width: 20%;
            float: left;
        }

        .header-section .logo img {
            float: left;
        }

        .header-section .text-section {
            width: 80%;
            float: left;
            text-align: center;
        }
        .header-section .text-section p {
            margin-right: 200px;
        }
        p.title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 0px;
        }
        p.address-section {
            font-size: 12px;
            margin-top: -30px;
        }
        .heading{
            margin: 0px;
            padding: 7px;
            font-size: 13px;
            font-weight: bold;
        }


        /*th,td {line-height: 20px;}*/
        html{margin:25px 45px}

    </style>
</head>
<body>

<div class="header">
    <div class="header-section">
        <div class="logo">
            <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:60px;height:60px; margin-left: 10px">
        </div>
        <div class="text-section">
            <p class="title">{{$instituteInfo->institute_name}}</p><br/>
            <p class="address-section">
                {{$instituteInfo->address1}} <br/>
                {{$instituteInfo->website}}
            </p>
            <br/>
            <p class="address-section" style="font-size: 16px; font-weight: 700;">HSC Admission Form</p>
            <br/>
            <br/>
            <br/>
            <br/>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
{{--checking std photo--}}
@if($appProfile->std_photo)
    <img style="float: right; margin-top: 40px" src="{{$appProfile->std_photo}}" width="80px" height="80px">
@else
    <img style="float: right; margin-top: 40px" src="{{asset('assets/users/images/user-default.png')}}" width="80px" height="80px">
@endif

<table style="width:78%; height:50px;  margin-top: 50px; padding: 0px ">
    <tr>
        <th width="20%">Applicant Name</th>
        <td colspan="3">{{$appProfile->s_name}}</td>

        {{--<td width=20%" rowspan="2" class="text-center" style="text-align: center">--}}
        {{--            <img style="margin: 0px; padding:0px;" src="{{public_path('assets/users/images/user-default.png')}}"  width="50px" height="50px">--}}
        {{--<img src="{{$appProfile->std_photo}}" width="100px" height="100px">--}}
        {{--</td>--}}
    </tr>
    {{--<tr>--}}
    {{--<th>Name (bn)</th>--}}
    {{--<td>--}}
    {{--{{$appProfile->s_name_bn}}--}}
    {{--</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
    {{--<th>Username</th>--}}
    {{--<td width="45%">{{$appProfile->username}}</td>--}}
    {{--<th width="20%">Payment Status</th>--}}
    {{--<td>{{$appProfile->p_status==0?'Un-Paid':'Paid'}}</td>--}}
    {{--</tr>--}}
    <tr>
        <th>Application ID</th>
        <td>{{$appProfile->a_no}}</td>
        <th width="19%">Application Date</th>
        <td width="16%"> {{$appProfile->created_at->format("d M, Y")}}</td>
    </tr>


</table>


<p class="heading">Basic Information</p>
<table style="width:100%">
    <tr>
        {{--left side informaiton--}}
        <th width="15%">Class / Batch </th>
        {{--batch --}}
        @php $myBatch = $appProfile->batch(); @endphp
        <td width="35%">{{$myBatch->batch_name}}</td>
        {{--right side information--}}
        <th width="15%">Group</th>
        {{--batch group --}}
        @php
            // checking batch division
            if($division = $myBatch->get_division()){ $divisionName = $division->name;}else{$divisionName = '-';}
        @endphp
        <td width="35%">{{$divisionName}}</td>
    </tr>
    <tr>
        <th>Year</th>
        <td>{{$appProfile->year()->year_name}}</td>
        <th>Gender</th>
        <td>{{$appProfile->gender==0?'Male':'Female'}}</td>
    </tr>
    <tr>
        <th>Date of birth</th>
        <td>{{date("d M, Y", strtotime($appProfile->b_date))}}</td>
        <th>Blood Group</th>
        <td>{{$appProfile->b_group}}</td>
    </tr>
    <tr>
        <th>Mobile No</th>
        <td>{{$appProfile->s_mobile}}</td>
        @if($instituteInfo->id == 30)
            <th>NID</th>
        @else
            <th>Birth Certificate </th>
        @endif
        <td>{{$appProfile->s_nid}}</td>
    </tr>
    <tr>
        <th>Nationality</th>
        <td>{{$appProfile->nationality()->nationality}}</td>
        <th>Religion</th>
        <td>
            @if($appProfile->religion==1)
                Islam
            @elseif($appProfile->religion==2)
                Hinduism
            @elseif($appProfile->religion==3)
                Christian
            @elseif($appProfile->religion==4)
                Buddhism
            @elseif($appProfile->religion==5)
                Others
            @endif
        </td>
    </tr>
</table>

{{--Father Information Here --}}
<p class="heading">Father's  Information</p>
<table style="width:100%">
    <tr>
        <th width="15%">Full Name</th>
        <td width="35%">{{$appProfile->f_name}}</td>
        <th width="15%">National ID</th>
        <td width="35%">{{$appProfile->f_nid}}</td>
    </tr>
    {{--<tr>--}}
    {{--<th>Full Name (bn)</th>--}}
    {{--<td>--}}
    {{--{{$appProfile->f_name_bn}}--}}
    {{--</td>--}}
    {{--<th>Mobile No</th>--}}
    {{--<td>{{$appProfile->f_mobile}}</td>--}}
    {{--</tr>--}}
    <tr>
        <th>Education</th>
        <td>{{$appProfile->f_education}}</td>
        <th>Occupation</th>
        <td>{{$appProfile->f_occupation}}</td>
    </tr>
    <tr>
        <th>Income</th>
        <td>{{$appProfile->f_income}}</td>
        <th>Mobile No</th>
        <td>{{$appProfile->f_mobile}}</td>
        {{--<th colspan="3" width="15%"></th>--}}
    </tr>
</table>

{{--Mother Information Here --}}
<p class="heading">Mother's  Information</p>
<table style="width:100%">
    <tr>
        <th width="15%">Full Name</th>
        <td width="35%">{{$appProfile->m_name}}</td>
        <th width="15%">National ID</th>
        <td width="35%">{{$appProfile->m_nid}}</td>
    </tr>
    {{--<tr>--}}
    {{--<th>Full Name (bn)</th>--}}
    {{--<td>--}}
    {{--{{$appProfile->m_name_bn}}--}}
    {{--</td>--}}
    {{--<th>Mobile No</th>--}}
    {{--<td>{{$appProfile->m_mobile}}</td>--}}
    {{--</tr>--}}
    <tr>
        <th>Education</th>
        <td>{{$appProfile->m_education}}</td>
        <th>Occupation</th>
        <td>{{$appProfile->m_occupation}}</td>
    </tr>
    <tr>
        <th>Income</th>
        <td>{{$appProfile->m_income}}</td>
        <th>Mobile No</th>
        <td>{{$appProfile->m_mobile}}</td>
    </tr>
</table>

{{--Address Information Here --}}
<p class="heading">Address  Information</p>
<table style="width:100%">
    <tr>
        <th width="15%">Zilla</th>
        <td width="35%">{{$appProfile->zilla()->name}}</td>
        <th width="15%">Thana</th>
        <td width="35%">{{$appProfile->thana()->name}}</td>
    </tr>
    <tr>
        <th width="15%">Village</th>
        <td width="35%">{{$appProfile->vill}}</td>
        <th width="15%">Post Office</th>
        <td width="35%">{{$appProfile->post}}</td>
    </tr>
</table>


{{--SSC EQI Information Information Here --}}
<p class="heading">SSC/EQUI  Information</p>
<table style="width:100%">
    <tr>
        <th width="15%">Name of Exam</th>
        <td width="35%">{{$appProfile->exam_name}}</td>
        <th width="15%">Board</th>
        <td width="35%">{{ucfirst(strtolower($appProfile->exam_board))}}</td>
    </tr>
    <tr>
        <th>Session</th>

        <td>{{$appProfile->exam_session}}</td>
        <th>Reg. No</th>

        <td>{{$appProfile->exam_reg}}</td>
    </tr>
    <tr>
        <th>Roll No</th>

        <td>{{$appProfile->exam_roll}}</td>
        <th>Result (GPA)</th>

        <td>{{$appProfile->exam_gpa}}</td>
    </tr>
    <tr>
        <th>Passing Year</th>

        <td>{{$appProfile->exam_year}}</td>
        <th>Institute</th>

        <td>{{ucfirst(strtolower($appProfile->exam_institute))}}</td>
    </tr>
</table>


{{--Chooses Subject Here --}}
<p class="heading">Subject List</p>
<table style="width:100%; font-size: 10px;">
    <tbody>
    {{--checking subject_group and and subject list--}}
    @if($appProfile->group_list AND $appProfile->sub_list)
        @php
            // my group subject list
			$mySubGroup = (array) json_decode($appProfile->group_list);
			//my subject list
			$myElectiveOne = ($mySubGroup AND array_key_exists('e_1', $mySubGroup))?($mySubGroup['e_1']):[];
			$myElectiveTwo = ($mySubGroup AND array_key_exists('e_2', $mySubGroup))?($mySubGroup['e_2']):[];
			$myElectiveThree = ($mySubGroup AND array_key_exists('e_3', $mySubGroup))?($mySubGroup['e_3']):[];
			$myOptional = ($mySubGroup AND array_key_exists('opt', $mySubGroup))?($mySubGroup['opt']):[];

			// class subject list
			$compulsory = array_key_exists('compulsory', $groupSubject)?$groupSubject['compulsory']:[];
			$electiveOne = array_key_exists('elective_one', $groupSubject)?$groupSubject['elective_one']:[];
			$electiveTwo = array_key_exists('elective_two', $groupSubject)?$groupSubject['elective_two']:[];
			$electiveThree =array_key_exists('elective_three', $groupSubject)?$groupSubject['elective_three']:[];
			$optional =array_key_exists('optional', $groupSubject)?$groupSubject['optional']:[];
        @endphp
        <tr>
            <th width="15%">Compulsory</th>
            <td  colspan="4">
                {{--checking compulsory subject list--}}
                @if($compulsory)
                    {{--sub counter--}}
                    @php $subCounter = 1; @endphp
                    {{--compulsory list looping--}}
                    @foreach($compulsory as $sub)
                        <i>{{$subCounter}}. {{$sub['name']}} </i>
                        {{--sub counter--}}
                        @php $subCounter += 1; @endphp
                    @endforeach
                @else
                    <i> ***** No Compulsory Subject found *****</i>
                @endif
            </td>
        </tr>
        <tr>
            <th>Elective</th>
            <td  colspan="4">
                {{--elective sub counter--}}
                @php $eSubCounter = 1; @endphp
                {{--checking elective one subject list--}}
                @if($electiveOne && $myElectiveOne)
                    {{--elective three subject list looping--}}
                    @foreach($electiveOne as $subId=>$sub)
                        {{--cheking my elective two subject--}}
                        @if($subId!=$myElectiveOne) @continue @endif
                        {{--subject name--}}
                        <i>{{$eSubCounter}}. {{$sub['name']}} </i>
                        {{--sub counter--}}
                        @php $eSubCounter += 1; @endphp
                    @endforeach
                @endif

                {{--checking elective two subject list--}}
                @if($electiveTwo && $myElectiveTwo)
                    {{--elective two subject list looping--}}
                    @foreach($electiveTwo as $subId=>$sub)
                        {{--cheking my elective two subject--}}
                        @if($subId!=$myElectiveTwo) @continue @endif
                        {{--subject name--}}
                        <i>{{$eSubCounter}}. {{$sub['name']}} </i>
                        {{--sub counter--}}
                        @php $eSubCounter += 1; @endphp
                    @endforeach
                @endif

                {{--checking elective three subject list--}}
                @if($electiveThree && $myElectiveThree)
                    {{--elective three subject list looping--}}
                    @foreach($electiveThree as $subId=>$sub)
                        {{--cheking my elective three subject--}}
                        @if($subId!=$myElectiveThree) @continue @endif
                        {{--subject name--}}
                        <i>{{$eSubCounter}}. {{$sub['name']}} </i>
                        {{--sub counter--}}
                        @php $eSubCounter += 1; @endphp
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <th>Optional</th>
            <td colspan="4">
                {{--checking optional subject list--}}
                @if($optional && $myOptional)
                    {{--sub counter--}}
                    @php $subCounter = 1; @endphp
                    {{--optional list looping--}}
                    @foreach($optional as $subId=>$sub)
                        {{--cheking my optional subject--}}
                        @if($subId!=$myOptional) @continue @endif
                        {{--subject name--}}
                        <i>{{$subCounter}}. {{$sub['name']}} </i>
                        {{--sub counter--}}
                        @php $subCounter += 1; @endphp
                    @endforeach
                @endif
            </td>
        </tr>
    @else
        {{--checking class subject list--}}
        @if($classSubject)
            {{--class subject count--}}
            @php $csCount = count($classSubject); @endphp
            {{--class subject list looping--}}
            @for($x=0; $x<=$csCount; $x+=3)
                <tr>
                    {{--td one--}}
                    <td>
                        @if($x < $csCount) <i>{{$classSubject[$x]['type']==3?'Opt: ':''}}{{$classSubject[$x]['code']}} ({{$classSubject[$x]['name']}}) </i> @else - @endif
                    </td>
                    {{--td two--}}
                    <td>
                        @if(($x+1) < $csCount) <i>{{$classSubject[$x+1]['type']==3?'Opt: ':''}}{{$classSubject[$x+1]['code']}} ({{$classSubject[$x+1]['name']}}) </i> @else - @endif
                    </td>
                    {{--td three--}}
                    <td>
                        @if(($x+2) < $csCount) <i>{{$classSubject[$x+2]['type']==3?'Opt: ':''}}{{$classSubject[$x+2]['code']}} ({{$classSubject[$x+2]['name']}}) </i> @else - @endif
                    </td>
                </tr>
            @endfor
        @else
            <tr><td colspan="4"> <i> ***** No Class Subject found *****</i> </td></tr>
        @endif
    @endif
    </tbody>
</table>


{{--Chooses Subject Here --}}
<h3 style="text-align: center; font-size:15px; margin-bottom:0px">NOTE OF DECLARATION</h3>
<hr/>
<p style="margin: 0px; font-size: 15px;">
    I <b>{{$appProfile->s_name}}</b> Herby declare that all the given information are true and correct. Again, I declare that I will not take part in any activity subversive of state or of any disciplinary action of the college. I wll abide by all the rules and regulation of this college and attend the classes regularly.
</p>
<br/>
<br/>
<div style="clear: both; width:100%; margin-top: 10px;">
    {{--Student's Signature--}}
    <div style="float: left; width: 25%; text-align:center;">
        .......................................... <br/>
        Student Signature
    </div>
    {{--Guardian's signature--}}
    <div style="float: left; width: 50%; text-align:center;">
        .......................................... <br/>
        Guardian Signature
    </div>
    {{--principal signature--}}
    <div style="float: left; width: 25%; text-align:center">
        .......................................... <br/>
        Principal Signature
    </div>
</div>


</body>
</html>
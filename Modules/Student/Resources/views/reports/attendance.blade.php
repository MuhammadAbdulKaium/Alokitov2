<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
    <style type="text/css">
        .label {font-size: 15px;  padding: 5px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
        .row-first{background-color: #b0bc9e;}
        .row-second{background-color: #e5edda;}
        .row-third{background-color: #5a6c75;}
        .text-center {text-align: center; font-size: 12px}
        .clear{clear: both;}

        #std-info {
            float:left;
            width: 79%;
        }
        #std-photo {
            float:left;
            width: 20%;
            margin-left: 10px;
        }
        #inst-photo{
            float:left;
            width: 15%;
        }
        #inst-info{
            float:left;
            width: 85%;
        }

        #inst{
            padding-bottom: 20px;
            width: 100%;
        }

        body{
            font-size: 11px;
        }
        .report_card_table{
            border: 1px solid #dddddd;
            border-collapse: collapse;
        }
    </style>
</head>


<body>

<div>
    <div id="inst" class="text-center clear" style="width: 100%;">
        <div id="inst-photo">
            @if($instituteInfo->logo)
                <img src="{{URL::asset('assets/users/images/'.$instituteInfo->logo)}}"  style="width:80px;height:80px">
            @endif
        </div>
        <div id="inst-info">
            <b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
        </div>
    </div>

    {{--Student Infromation--}}
    <div class="clear" style="width: 100%;">
        <p class="label text-center row-second">Student Information</p>
        <div id="std-info">
            <table width="100%" border="1px solid" class="report_card_table" cellpadding="5">
                <tr>
                    <th>Name:</th>
                    <td colspan="3"> {{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</td>
                </tr>
                <tr>
                    <th>Date of Birth:</th>
                    <td>{{ date('d M, Y', strtotime($studentInfo->dob)) }}</td>
                    <th>Gender:</th>
                    <td>{{$studentInfo->gender}}</td>
                </tr>
                <tr>
                    <th>Nationality:</th>
                    <td>
                        {{$studentInfo->nationality()?$studentInfo->nationality()->nationality:' - '}}
                    </td>
                    <th>Religion:</th>
                    <td>
                                        @php
                                          switch($studentInfo->religion) 
                                          {
                                             case '1': echo "Islam"; break;
                                             case '2': echo "Hinduism"; break;
                                             case '3': echo "Christianity"; break;
                                             case '4': echo "Buddhism"; break;
                                             case '5': echo "Others"; break;
                                          }
                                      @endphp
                    </td>
                </tr>
                <tr>
                    <th>Birthplace:</th>
                    <td>{{$studentInfo->birth_place}}</td>
                    <th>Bloodgroup:</th>
                    <td>{{$studentInfo->blood_group}}</td>
                </tr>
                <tr>
                    <th>Student Type:</th>
                    <td>
                        @php
                            switch($studentInfo->type) {
							   case '1': echo "Pre Admission"; break;
							   case '2': echo "Regular"; break;
							}
                        @endphp
                    </td>
                    <th>Languages:</th>
                    <td>{{$studentInfo->nationality()?$studentInfo->nationality()->nationality:' - '}}</td>
                </tr>
            </table>
        </div>
        <div id="std-photo">
            @if($studentInfo->singelAttachment('PROFILE_PHOTO'))
                <img src="{{URL::asset('assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"  style="width:130px;height:125px">
            @else
                <img  src="{{URL::asset('assets/users/images/user-default.png')}}" style="width:130px;height:125px">
            @endif
        </div>
    </div>

    <div class="attendance clear">
        <!-- attendance Report -->
        <p class="label text-center row-second">Attendance Report</p>
    @if($attType=='subject')
        <!-- subject wise attendance -->
            <table width="100%" border="1px solid" class="report_card_table" cellpadding="5">
                <tbody>
                <tr class="row-second">
                    <th class="text-center">Date</th>
                    @foreach($academicSubjects as $key => $subject)
                        <th class="text-center">{{$subject['name']}}</th>
                    @endforeach
                </tr>

                @for($i=0; $i<count($studentAttendanceList);$i++)
                    <tr>
                        <td class="text-center">{{$studentAttendanceList[$i]['date']}}</td>
                        <?php $attendanceList = (array)$studentAttendanceList[$i]['attendance']; ?>
                        @for($j =0; $j<count($academicSubjects);$j++)
                            <td class="text-center">
                                @if(array_key_exists($academicSubjects[$j]['id'], $attendanceList) == true)
                                    @if($attendanceList[$academicSubjects[$j]['id']] == 0 )
                                        A
                                    @else
                                        P
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endfor
                </tbody>
            </table>
    @else
        <!-- date wise attendance -->
            <table width="100%" border="1px solid" class="report_card_table" cellpadding="5">
                <tbody>
                <tr class="row-second">
                    <th class="text-center">Date</th>
                    <th class="text-center">Attndance</th>
                </tr>
                @for($i=0; $i<count($studentAttendanceList);$i++)
                    <tr>
                        <td class="text-center">{{$studentAttendanceList[$i]['date']}}</td>
                        <td class="text-center">
                            @if($studentAttendanceList[$i]['attendance']==1)
                                A
                            @else
                                P
                            @endif
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
        @endif
    </div>

    <div class="clear" style="width: 100%; margin-top: 50px;">
        <div style="float: left; width: 50%; text-align:center">
            ...............................<br>
            <strong>Parent</strong>
        </div>
        <div style="float: left; width: 50%; text-align:center;">
            ...............................<br>
            <strong>Principal</strong>
        </div>
    </div>
</div>
</body>
</html>

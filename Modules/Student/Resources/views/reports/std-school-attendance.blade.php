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
{{--                <img src="{{URL::asset('assets/users/images/'.$instituteInfo->logo)}}"  style="width:80px;height:80px">--}}
                <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
            @endif
        </div>
        <div id="inst-info">
            <b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
        </div>
    </div>

    @if($academicHolidayList)
        @if($academicWeeKOffDayList['status']=='success')
            @php $academicWeeKOffDayList = $academicWeeKOffDayList['week_off_list'] @endphp
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
{{--                        <img src="{{URL::asset('assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"  style="width:130px;height:125px">--}}
                        <img src="{{public_path().'/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name}}"  style="width:130px;height:125px">
                    @else
{{--                        <img  src="{{URL::asset('assets/users/images/user-default.png')}}" style="width:130px;height:125px">--}}
                        <img  src="{{public_path().'/assets/users/images/user-default.png'}}" style="width:130px;height:125px">
                    @endif
                </div>
            </div>

            <div class="attendance clear">
                <br/>
                {{--<p class="label text-center row-second">Attendance Details</p>--}}
                <table width="100%" border="1px solid" class="report_card_table text-center" cellpadding="5">
                    <tbody>
                    <tr class="row-second">
                        <th colspan="3" class="text-center">Attendance Details</th>
                    </tr>
                    <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Attendance Type</th>
                        <th class="text-center">Entry Time</th>
                    </tr>

                    @php
                        $totalDay = 0;
                        $totalWorkingDay = 0;
                        $totalPresent = 0;
                        $totalAbsent = 0;
                        $totalHoliday = 0;
                        $totalWeekOffDay = 0;
                    @endphp

                    {{--date, month finding--}}
                    @php
                        // from_date details
                        $fromYear = date('Y',strtotime($startDate));
                        $fromMonth = date('m',strtotime($startDate));
                        $fromDate = date('d',strtotime($startDate));
                        // to_date details
                        $toYear = date('Y',strtotime($endDate));
                        $toMonth = date('m',strtotime($endDate));
                        $toDate = date('d',strtotime($endDate));
                    @endphp

                    {{--date, month and year checking--}}
                    @if($fromYear==$toYear AND $fromMonth==$toMonth)
                        {{--for same year and same month--}}
                        <tr class="row-first">
                            <td colspan="3">
                                {{date('M-Y', strtotime($fromYear.'-'.$toMonth.'-01'))}}
                            </td>
                        </tr>
                        {{--input dates are in a month--}}
                        @for($day=$fromDate; $day<=$toDate; $day++)
                            {{--date formatting--}}
                            @php $toDayDate = date('Y-m-d', strtotime(date('Y-m-'.$day, strtotime($startDate)))); @endphp
                            <tr>
                                <td>{{$toDayDate}}</td>
                                {{--total Day Count--}}
                                @php $totalDay += 1; @endphp
                                {{--array key exist checking--}}
                                @if(array_key_exists($toDayDate , $studentAttendanceList)==true)
                                    <td>P</td>
                                    <td>{{$studentAttendanceList[$toDayDate]}}</td>
                                    {{--total present and working day count--}}
                                    @php $totalPresent += 1; @endphp
                                    @php $totalWorkingDay += 1; @endphp
                                @else
                                    @if(array_key_exists($toDayDate , $academicHolidayList)==true)
                                        <td colspan="2">{{$academicHolidayList[$toDayDate]}}</td>
                                        {{--total holiday count--}}
                                        @php $totalHoliday += 1; @endphp
                                    @else
                                        @if(array_key_exists($toDayDate , $academicWeeKOffDayList)==true)
                                            <td colspan="2">{{$academicWeeKOffDayList[$toDayDate]}}</td>
                                            {{--total week off day count--}}
                                            @php $totalWeekOffDay += 1; @endphp
                                        @else
                                            <td>A</td>
                                            <td>N/A</td>
                                            {{--total absent and working day count--}}
                                            @php $totalAbsent += 1; @endphp
                                            @php $totalWorkingDay += 1; @endphp
                                        @endif
                                    @endif
                                @endif
                            </tr>
                        @endfor

                    @elseif($fromYear==$toYear AND $fromMonth<$toMonth)
                        {{--for same year and different month--}}

                        {{--month looping--}}
                        @for($month=$fromMonth; $month<=$toMonth; $month++)
                            {{--current month date range finding--}}
                            @php $monthFirstDate = date('01', strtotime($fromYear.'-'.$month.'-01')); @endphp
                            @php $monthLastDate = date('t', strtotime($fromYear.'-'.$month.'-01')); @endphp
                            {{--date range reset--}}
                            @php
                                if($fromMonth==$month){$monthFirstDate = $fromDate;}
                                if($toMonth==$month){$monthLastDate = $toDate;}
                            @endphp
                            <tr class="row-first"><td colspan="3">{{date('M-Y', strtotime($fromYear.'-'.$month.'-01'))}}</td></tr>
                            {{--current month date looping--}}
                            @for($day=$monthFirstDate; $day<=$monthLastDate; $day++)
                                {{--today's dtate formatting--}}
                                @php $toDayDate = date('Y-m-d', strtotime($fromYear."-".$month."-".$day)); @endphp
                                <tr>
                                    <td>{{$toDayDate}}</td>
                                    {{--total Day Count--}}
                                    @php $totalDay += 1; @endphp
                                    {{--array key exist checking--}}
                                    @if(array_key_exists($toDayDate , $studentAttendanceList)==true)
                                        <td>P</td>
                                        <td>{{$studentAttendanceList[$toDayDate]}}</td>
                                        {{--total present and working day count--}}
                                        @php $totalPresent += 1; @endphp
                                        @php $totalWorkingDay += 1; @endphp
                                    @else
                                        @if(array_key_exists($toDayDate , $academicHolidayList)==true)
                                            <td colspan="2">{{$academicHolidayList[$toDayDate]}}</td>
                                            {{--total holiday count--}}
                                            @php $totalHoliday += 1; @endphp
                                        @else
                                            @if(array_key_exists($toDayDate , $academicWeeKOffDayList)==true)
                                                <td colspan="2">{{$academicWeeKOffDayList[$toDayDate]}}</td>
                                                {{--total week off day count--}}
                                                @php $totalWeekOffDay += 1; @endphp
                                            @else
                                                <td>A</td>
                                                <td>N/A</td>
                                                {{--total absent and working day count--}}
                                                @php $totalAbsent += 1; @endphp
                                                @php $totalWorkingDay += 1; @endphp
                                            @endif
                                        @endif
                                    @endif
                                </tr>
                            @endfor
                        @endfor
                    @elseif($fromYear<$toYear)
                        {{--for different year--}}

                        {{--year looping--}}
                        @for($year=$fromYear; $year<=$toYear; $year++)
                            @php $yearMonths = 12; @endphp
                            {{--month looping--}}
                            @for($month=1; $month<=$yearMonths; $month++)
                                {{--current month date range finding--}}
                                @php $monthFirstDate = date('01', strtotime($year.'-'.$month.'-01')); @endphp
                                @php $monthLastDate = date('t', strtotime($year.'-'.$month.'-01')); @endphp
                                {{--date, month range reset--}}
                                @php
                                    if($toYear==$year){$yearMonths = $toMonth;}
                                    if($toYear==$year AND $toMonth==$month){$monthLastDate = $toDate;}
                                    if($fromYear==$year AND $fromMonth==$month){$monthFirstDate = $fromDate;}
                                @endphp
                                {{--current month row--}}
                                <tr class="row-first"><td colspan="3">{{date('M-Y', strtotime($year.'-'.$month.'-01'))}}</td></tr>
                                {{--current month date looping--}}
                                @for($day=$monthFirstDate; $day<=$monthLastDate; $day++)
                                    {{--today's dtate formatting--}}
                                    @php $toDayDate = date('Y-m-d', strtotime($year."-".$month."-".$day)); @endphp
                                    <tr>
                                        <td>{{$toDayDate}}</td>
                                        {{--total Day Count--}}
                                        @php $totalDay += 1; @endphp
                                        {{--array key exist checking--}}
                                        @if(array_key_exists($toDayDate , $studentAttendanceList)==true)
                                            <td>P</td>
                                            <td>{{$studentAttendanceList[$toDayDate]}}</td>
                                            {{--total present and working day count--}}
                                            @php $totalPresent += 1; @endphp
                                            @php $totalWorkingDay += 1; @endphp
                                        @else
                                            @if(array_key_exists($toDayDate , $academicHolidayList)==true)
                                                <td colspan="2">{{$academicHolidayList[$toDayDate]}}</td>
                                                {{--total holiday count--}}
                                                @php $totalHoliday += 1; @endphp
                                            @else
                                                @if(array_key_exists($toDayDate , $academicWeeKOffDayList)==true)
                                                    <td colspan="2">{{$academicWeeKOffDayList[$toDayDate]}}</td>
                                                    {{--total week off day count--}}
                                                    @php $totalWeekOffDay += 1; @endphp
                                                @else
                                                    <td>A</td>
                                                    <td>N/A</td>
                                                    {{--total absent and working day count--}}
                                                    @php $totalAbsent += 1; @endphp
                                                    @php $totalWorkingDay += 1; @endphp
                                                @endif
                                            @endif
                                        @endif
                                    </tr>
                                @endfor
                            @endfor
                        @endfor
                    @else
                        <tr class="row-first">
                            <td colspan="3">Invalid Date format</td>
                        </tr>
                    @endif

                    </tbody>
                </table>
                <br/>
                <!-- attendance Report -->
                <table width="100%" border="1px solid" class="report_card_table text-center" cellpadding="5">
                    <tbody>
                    <tr class="row-second">
                        <th colspan="6" class="text-center">Attendance Summary</th>
                    </tr>
                    <tr>
                        <th class="text-center">Total Days</th>
                        <th class="text-center">Holiday + Week Off Day</th>
                        <th class="text-center">Working Day</th>
                        <th class="text-center">Present</th>
                        <th class="text-center">Absent</th>
                        <th class="text-center"> % (Present)</th>
                    </tr>
                    <tr>
                        <td class="text-center">{{$totalDay}}</td>
                        <td class="text-center">{{$totalHoliday.' + '.$totalWeekOffDay.' = '.($totalHoliday+$totalWeekOffDay)}}</td>
                        <td class="text-center">{{$totalWorkingDay}}</td>
                        <td class="text-center">{{$totalPresent}}</td>
                        <td class="text-center">{{$totalAbsent}}</td>
                        <td class="text-center">{{$totalWorkingDay>0?(number_format((float) $totalPresent/$totalWorkingDay*100, 2, '.', '')):0.00}} %</td>
                    </tr>

                    </tbody>
                </table>
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
        @else
            <p class="clear label text-center">{{$academicWeeKOffDayList['msg']}}</p>
        @endif
    @else
        <p class="clear label text-center">National Holiday List is empty</p>
    @endif
</div>
</body>
</html>

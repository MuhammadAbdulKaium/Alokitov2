<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
    <style type="text/css">
        .label {font-size: 15px;  padding: 5px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
        .row-first{background-color: #b0bc9e;}
        .row-second{background-color: #e5edda;}
        .row-third{background-color: #00cc44;}
        .text-center {text-align: center; font-size: 12px}
        .text-left {text-align: left; font-size: 12px}
        .clear{clear: both;}

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
            font-size: 10px;
        }
        .report_card_table{
            border: 1px solid #dddddd;
            line-height: 7px;
            vertical-align: middle;
            border-collapse: collapse;
        }

        html{
            margin: 20px;
        }
    </style>
</head>

<body>

<div id="inst" class="text-center clear" style="width: 100%;">
    <div id="inst-photo">
        @if($instituteInfo->logo)
            <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
        @endif
    </div>
    <div id="inst-info">
        <b style="font-size: 20px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
    </div>
</div>

{{--current month date range finding--}}
@php
    $monthName = date('F', mktime(0, 0, 0, $month, 1));
	$fromDate = date('01', strtotime($year.'-'.$month.'-01'));
	$toDate = date('t', strtotime($year.'-'.$month.'-01'));

	$academicHolidayList = $academicHolidayList?$academicHolidayList:[];
@endphp

<div class="attendance clear">
    <br/>
    @if($allEmployeeList->count()>0)
        {{--employee attendance list--}}
        <table width="100%" border="1px solid black" class="report_card_table text-center" cellpadding="5">
            <thead>
            <tr class="row-second">
                <th colspan="{{$toDate+4}}">Staff Attendance Report ({{$monthName .' - '.$year}})</th>
            </tr>
            {{--date row--}}
            <tr class="row-second">
                <th>#</th>
                <th>Employee</th>
                @for($day=$fromDate; $day<=$toDate; $day++)
                    {{--print date--}}
                    <th>{{$day}}</th>
                @endfor
                <th>Present</th>
                <th>Absent</th>
            </tr>
            </thead>
            <tbody>

            {{--emmployee list looping--}}
            @foreach($allEmployeeList as $index=>$employee)
                {{--present  and absent counter--}}
                @php $present = 0; $absent = 0; @endphp
                <tr>
                    <td>{{($index+1)}}</td>
                    <td width="18%" class="text-left">{{$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name}}</td>
                    {{--find employee attendance list--}}
                    @php $myAttendanceList = array_key_exists($employee->id, $employeeAttendanceList)?$employeeAttendanceList[$employee->id]:[] @endphp

                    {{--input dates are in a month--}}
                    @for($day=$fromDate; $day<=$toDate; $day++)
                        {{--date formatting--}}
                        @php
                            $toDayDate = date('Y-m-d', strtotime($year.'-'.$month.'-'.$day));
                            $toDayDateId = date('w', strtotime($year.'-'.$month.'-'.$day));
                        @endphp
                        {{--array key exist checking--}}
                        @if(array_key_exists($toDayDate , $myAttendanceList)==true)
                            {{--find today's attendance--}}
                            @php $toDayAttendance = $myAttendanceList[$toDayDate]; @endphp
                            {{--checking report type--}}
                            <td>P</td>

                            {{--present counter--}}
                            @php $present +=1; @endphp

                        @else

                            {{--checking national holiday or friday--}}
                            @if(array_key_exists($toDayDate , $academicHolidayList)==true || ($toDayDateId==5))
                                <td>-</td>
                            @else
                                <td>A</td>

                                {{--absent counter--}}
                                @php $absent +=1; @endphp
                            @endif

                        @endif

                    @endfor
                    <th class="row-second">{{$present}}</th>
                    <th class="row-second">{{$absent}}</th>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> No Attendance Data Found!!!</h4>
        </div>
    @endif
</div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Subject List</title>
    <style type="text/css">

        table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            text-align: center;
            font-size: 10px;
            color: black;
            width: 100%;
        }
        table td, table th { border: 1px solid #000000; padding: 3px;}

        .row { width: 100%;}
        .text-center {text-align: center;}
        #title { font-size: 25px; margin-top: -55px;}
        #address { font-size: 15px;}

    </style>
</head>
<body>
<div class="row">
    <p class="text-center">
        <span id="title">{{$instituteInfo->institute_name}}</span> <br/>
        <span id="address">{{$instituteInfo->address1}}</span>
    </p>
</div>
<div class="row">
    <table>
        <thead>
        @php
            if ($classProfile->get_division()) {
					$batchName = $classProfile->batch_name . " - " . $classProfile->get_division()->name;
				} else {
					$batchName = $classProfile->batch_name;
				}
        @endphp
        <tr>
            <td colspan="7" >Class Name: <strong>{{$batchName}} </strong>,  Section Name : <strong>{{$sectionProfile->section_name}}</strong></td>
        </tr>
        <tr>
            <th width="1%">#</th>
            <th style="font-size: 11px; text-align: left; padding-left: 10px;">
                Student Name <br/>
                Father Name <br/>
                Mother Name
            </th>
            <th width="10%">
                Admission Date <br/>
                Class Roll
            </th>
            <th width="6%">
                SSC/EQUI <br/>
                Reg No <br/>
                Roll NO <br/>
                Academic Year
            </th>
            <th width="9%">
                SSC/EQUI  <br/>
                Passing Year <br/>
                Board Name
            </th>
            <th>Studies subject name with code name</th>
            <th width="6%">4th Subject</th>
        </tr>
        </thead>
        <tbody>
        @php $i=1; @endphp
        @foreach($allEnrollments as $enroll)

            {{--student gurdians--}}
            @php
                $stdProfile = $enroll->student();
                $stdParents = $stdProfile->myGuardians();
                $mySubList = array_key_exists($stdProfile->id, $studentSubList)?$studentSubList[$stdProfile->id]:[];
            @endphp
            <tr>
                <td>{{$i++}}</td>
                <td style="font-size: 11px; text-align: left; padding-left: 10px;">
                    {{--student name--}}
                    {{$enroll->first_name." ".$enroll->middle_name." ".$enroll->last_name}} <br/>
                    {{--student guardian information--}}
                    @if(!empty($stdParents) and ($stdParents->count()>0))
                        @foreach($stdParents as $parent)
                            {{--single gardian profile list--}}
                            @php $guardian = $parent->guardian(); @endphp
                            {{--guardian name--}}
                            {{$guardian->first_name." ".$guardian->last_name}}<br/>
                        @endforeach
                    @endif
                </td>
                <td>
                    @php $studentSscInfo=json_decode($enroll->registerStudent()->ssc,true) @endphp
                    2017-02-12 <br/>
                    {{$enroll->gr_no}}
                    {{--<p>{{$studentSscInfo['exam_info'][]}}</p>--}}
                </td>

                <td>
                    {{$studentSscInfo['exam_info']['exam_reg']}} <br/>
                    {{$studentSscInfo['exam_info']['exam_roll']}} <br/>
                    {{$studentSscInfo['exam_info']['exam_session']}}
                </td>
                <td>
                    {{$studentSscInfo['exam_info']['exam_year']}} <br/>
                    {{ucfirst($studentSscInfo['exam_info']['exam_board'])}}
                </td>

                <td>
                    {{--checing my subject list--}}
                    @if(count($mySubList)>0)
                        <table>
                            <tbody>
                            <tr>
                                {{--student subject list looping--}}
                                @foreach($mySubList as $gSudId=>$singleSubject)
                                    <td width="1%">{{$singleSubject['name']}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                {{--student subject list looping--}}
                                @foreach($mySubList as $gSudId=>$singleSubject)
                                    <td>
                                        {{array_key_exists(0, $singleSubject['sub_list'])?$singleSubject['sub_list'][0]:''}}{{array_key_exists(1, $singleSubject['sub_list'])?', '.$singleSubject['sub_list'][1]:''}}
                                    </td>
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                    @endif
                </td>
                <td>
                    {{--student subject list looping--}}
                    @foreach($mySubList as $gSudId=>$singleSubject)
                        @if($singleSubject['type']!=0) @continue @endif
                        {{$singleSubject['name']}} <br/>
	                    {{array_key_exists(0, $singleSubject['sub_list'])?$singleSubject['sub_list'][0]:''}}{{array_key_exists(1, $singleSubject['sub_list'])?', '.$singleSubject['sub_list'][1]:''}}
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
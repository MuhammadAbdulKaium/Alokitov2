<html>
<title>Student Report Card</title>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Student Infromation -->
    <style type="text/css">

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font: 12pt "Tahoma";
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10px auto;
        }

        .subpage {
            padding: 1cm;
            background-image: url("{{URL::to('assets/users/images/certificate.png')}}");
            {{--background-image: url("{{URL::to('assets/users/images/certificate.png')}}");--}}
               height: 294mm;
            /*border: 5px solid orange;*/
            background-repeat: no-repeat;
            background-size: 210mm 294mm;
        }

        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            html, body {
                width: 210mm;
                height: 297mm;
            }

            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }

        .label {
            font-size: 15px;
            padding: 5px;
            border: 1px solid #000000;
            border-radius: 1px;
            font-weight: 700;
        }

        .row-first {
            background-color: #b0bc9e;
        }

        .row-second {
            background-color: #e5edda;
        }

        .row-third {
            background-color: #5a6c75;
        }

        .text-center {
            text-align: center;
            font-size: 14px
        }

        .clear {
            clear: both;
        }

        .text-bold {
            font-weight: 700;
        }

        .calculation i {
            margin-right: 10px;
        }

        #std-photo {
            float: right;
            width: 20%;
            margin-left: 10px;
        }

        #inst {
            padding-bottom: 20px;
            width: 100%;
        }

        .report_card_table {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .sing {
            width: 25%;
            float: left;
            margin-top: 0px;
            text-align: center;
            line-height: 2px;
            font-size: 12px;
            font-weight: 600;
        }


        .semester {
            font-size: 11px;
            padding: 15px;
            /*height: 1266px;*/
            height: 1048px;
        {{--@if($reportCardSetting AND $reportCardSetting->is_border_color==1)--}}
            {{--@php $border = $reportCardSetting->border_width.'px '.$reportCardSetting->border_type.' '.$reportCardSetting->border_color;  @endphp--}}
            {{--border: {{$border}};--}}
        {{--border-radius: 5px;--}}
    {{--@endif--}}



        }

        .singnature {
            height: 30px;
            width: 40px;
        }

        .std-info-table {
            font-size: 15px;
            line-height: 17px;
            margin-bottom: 30px;
            text-align: left;
        }

        #std-photo {
            width: 32%;
            float: left;
            margin-top: -10px;
        }

        #inst-logo {
            width: 32%;
            float: left;
            text-align: center;
        }

        #grade-scale {
            width: 32%;
            float: right;
            margin-top: -23px;
        }

        /*width: 24%*/
        .report-comments {
            width: 31%;
            float: left;
        }

        #qr-code {
            width: 20%;
            float: right;
        }

        /*commenting table */
        .table {
            border-collapse: collapse;
            line-height: 40px;
        }

        .table, .table th, .table td {
            border: 1px solid black;
        }

        .subject {
            text-align: left;
            padding-left: 10px;
            font-weight: 900;
            height: 25px;
            font-size: 13px;
        }

        #header_row {
            line-height: 20px;
        }

    </style>
</head>


<body>

<div class="book">
    @php $i=1; $height = []; $passList=[];$failList =[];@endphp


    {{--ONLY FOR FIND HIEIGHT MARKS PER SUBJECT--}}
    @foreach($studentList as $stdProfile)
        @php $stdId = $stdProfile->std_id;
        @endphp
        @php $myAssList = [];  @endphp
        @foreach($catDetailArrayList as $catId=>$assList)
            @if($category!=$catId) @continue @endif
            @php $myAssList = $assList;  @endphp
        @endforeach
        @php $i=1; @endphp
        @foreach($classSubGroupArrayList as $sgId => $sgDetail)
            @php
                $subjectList = $tabulationSheet[$stdId]['sub_list'];
                $result = $tabulationSheet[$stdId]['result'];
                if($result['lg'] =='F'){
                    $failList[$stdId]=(int) ($result['obtained'] * 100);
                }else{
                    $passList[$stdId]=(int) ($result['obtained'] * 100);
                }

            @endphp
            @if(array_key_exists($sgId, $subjectList))
                @php
                    $subjectMarks = $subjectList[$sgId];
                    $subAssList = array_key_exists('ass_list',$subjectMarks)?$subjectMarks['ass_list']:[];
                @endphp
                @php $totalMark=0; @endphp
                @foreach($myAssList as $assId=>$assName)
                    @php
                        $subMark = array_key_exists($assId, $subAssList)?$subAssList[$assId]:0;
                         if(!array_key_exists($sgId,$height)){
                             $height[$sgId] = $subMark;
                         }
                         if(array_key_exists($assId, $subAssList)){
                                 if($subAssList[$assId] !='Abs' || !empty($subAssList[$assId])){
                                     $totalMark += (int) $subAssList[$assId];
                                 }
                         }
                        if( $height[$sgId] <= $totalMark){
                            $height[$sgId] = $totalMark;
                        }




                    @endphp
                @endforeach
            @endif
            @php $i++;
            @endphp
        @endforeach
    @endforeach
    {{--    {{ dd($passList)}}--}}

    @php
        $myPassList = $passList;
        rsort($passList);
        $passList = array_unique($passList);
        // pass array list
        $passArrayList = [];
        //
        $z = 0;
        foreach($passList as $index=>$mark){
            $passArrayList[$z] =$mark;
            $z++;
        }
    @endphp

    {{--    HTML VIEW--}}
    @php  $GrandTotalMark=0; $index=0; @endphp
    @foreach($studentList as $stdProfile)
        {{--{{dd($stdId)}}--}}
        @if($myStdId && $myStdId!=$stdProfile->std_id) @continue @endif

        @php $stdId = $stdProfile->std_id; @endphp
        <div class="page">
            <div class="subpage">

                <div class="semester">

                    <div id="inst" class="text-center clear" style="width: 100%;">
                        <b style="font-size:35px">
                            {{$instituteInfo->institute_name}}
                        </b>
                        <br/>
                        <span style="font-size: 16px; font-weight: 500"> {{$instituteInfo->address1}} </span>
                    </div>

                    <div class="clear" style="width: 100%;">
                        <div id="std-photo">
                            @if($stdProfile->singelAttachment("PROFILE_PHOTO"))
                                <img src="{{asset('assets/users/images/'.$stdProfile->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"
                                     style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
                            @else
                                <img src="{{asset('assets/users/images/user-default.png')}}"
                                     style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
                            @endif
                        </div>
                        <div id="inst-logo">
                            @if($instituteInfo->logo)
                                <img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}" class="center"
                                     style="width:80px;height:80px;">
                                <br/>
                                <br/>
                                <p style="font-size:15px; text-align: center; line-height: 2px; margin-left: 10px; margin-top: 70px">
                                    <b>PROGRESS REPORT</b></p>
                                <hr style="margin-left: 10px; color:black">
                            @endif
                        </div>
                        <div id="grade-scale">
                            <table width="60%&quot;" style="font-size: 10px; line-height: 13px; float: right"
                                   class="text-center table" cellpadding="2">
                                <thead>
                                <tr>
                                    <th width="2%">Range (%)</th>
                                    <th width="1%">Grade</th>
                                    <th width="1%">GP</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>80 - 100</td>
                                    <td>A+</td>
                                    <td>5</td>
                                </tr>
                                <tr>
                                    <td>70 - 79</td>
                                    <td>A</td>
                                    <td>4</td>
                                </tr>
                                <tr>
                                    <td>60 - 69</td>
                                    <td>A-</td>
                                    <td>3.5</td>
                                </tr>
                                <tr>
                                    <td>50 - 59</td>
                                    <td>B</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td>40 - 49</td>
                                    <td>C</td>
                                    <td>2</td>
                                </tr>
                                <tr>
                                    <td>33 - 39</td>
                                    <td>D</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <td>0 - 32</td>
                                    <td>F</td>
                                    <td>0</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{--Student Infromation--}}
                    <div class="clear" style="width: 100%;">
                    </div>
<br>
                @php $myAssList = [];  @endphp


                {{--categroy looping--}}
                @foreach($catDetailArrayList as $catId=>$assList)
                    {{--category checking--}}
                    @if($category!=$catId) @continue @endif
                    @php $myAssList = $assList;  @endphp
                @endforeach

                <!-- Table row -->
                    <div id="my_report_card" class="clear" style="width: 100%; margin-top: 0px;">
                        {{--@if($stdSubjectGrades == null) @continue @endif--}}

                        {{--<table class="report_card_table">--}}
                        <table id="customers" width="100%" class="text-center table" cellspacing="5">
                            <thead>
                            <tr>
                                <td id="summary" colspan="12">
                                    <i><b style="font-size: 15px">{{$category_name}}</b></i>
                                </td>
                            </tr>
                            <tr align="center">
                                <td id="summary" colspan="12" style="line-height:2">
                                    <i><b>Student
                                            Name:</b> {{$stdProfile->first_name.' '.$stdProfile->middle_name.' '.$stdProfile->last_name}}
                                        </i>
                                    <br>
                                    <i><b>Class:</b> {{$stdProfile->batch()->batch_name}},</i>
                                    <i><b>Roll :</b> {{$stdProfile->gr_no}}, </i>
                                    <i><b>Group:</b> @php
                                            $data ='';
                                    if ($stdProfile->batch()->get_division()) {
                                    $data = $stdProfile->batch()->get_division()->name;
                                 } else {
                                    $data = '';
                                 }

                                        @endphp
                                        {{$data}}</i>
                                    <i><b>Year:</b> {{$acdemicYear->year_name}}</i>
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="2" width="20%" align="left" style="padding-left: 5px">Subject</td>
                                <td width="15%" colspan="{{count($myAssList)}}" align="center">Marks</td>
                                <td rowspan="2">Total Mark</td>
                                <td rowspan="2">Letter Grade</td>
                                {{--<td  rowspan="2">Highest Marks</td>--}}
                                <td rowspan="2">Grade Point</td>
                                <td rowspan="2">Highest Mark</td>
                                <td rowspan="2" width="10%">GPA</td>
                                <td rowspan="2">GPA (without 4th subject)</td>
                                <td rowspan="2">Position in Class</td>
                            </tr>

                            <tr>
                                {{--assessment looping--}}
                                @foreach($myAssList as $assId=>$assName)
                                    <td>{{$assName}}</td>
                                @endforeach
                            </tr>
                            </thead>

                            <tbody>
                            @php $i=1; @endphp







                            @foreach($classSubGroupArrayList as $sgId => $sgDetail)
                                @php
                                    $subjectList = $tabulationSheet[$stdId]['sub_list'];
                                    $result = $tabulationSheet[$stdId]['result'];
                                @endphp
                                {{--"exam" => 140--}}
                                {{--"obtained" => 28--}}
                                {{--"percentage" => 20.0--}}
                                {{--"failed" => 3--}}
                                {{--"total_gp" => 30.0--}}
                                {{--"obtained_gp" => 6.0--}}
                                {{--"obtained_gp_main" => 6.0--}}
                                {{--"lg" => "F"--}}
                                {{--"gpa" => 0--}}
                                {{--"gpa_with_out_optional" => 0--}}

                                @if(array_key_exists($sgId, $subjectList))
                                    @php
                                        $subjectMarks = $subjectList[$sgId];

                                        $subAssList = array_key_exists('ass_list',$subjectMarks)?$subjectMarks['ass_list']:[];
                                        $subFailAssList = array_key_exists('fail_ass_list',$subjectMarks)?$subjectMarks['fail_ass_list']:[];
                                    @endphp

                                    @php $totalMark=0; @endphp
                                    @foreach($myAssList as $assId=>$assName)
                                        {{--                                        //total--}}
                                        @php
                                            $subMark = array_key_exists($assId, $subAssList)?$subAssList[$assId]:0;
                                             if(!array_key_exists($sgId,$height)){
                                                 $height[$sgId] = $subMark;
                                             }


                                             if(array_key_exists($assId, $subAssList)){
                                                     if($subAssList[$assId] !='Abs' || !empty($subAssList[$assId])){
                                                         $totalMark += (int) $subAssList[$assId];
                                                     }
                                             }

                                            if( $height[$sgId] <= $totalMark){
                                                $height[$sgId] = $totalMark;
                                            }

                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td align="left"
                                            style="padding-left: 5px"> {{ ucfirst(strtolower($sgDetail['name'])) }}</td>
                                        {{--assessment looping--}}
                                        @php $totalMark=0; @endphp
                                        @foreach($myAssList as $assId=>$assName)
                                            @php
                                                if(array_key_exists($assId, $subAssList)){
                                                if($subAssList[$assId] !='Abs' || !empty($subAssList[$assId])){
                                                    $totalMark += (int) $subAssList[$assId];
                                                }
        }

                                            @endphp
                                            <td style="color:{{in_array($assId, $subFailAssList)?'red':'black'}};"> {{array_key_exists($assId, $subAssList)?$subAssList[$assId]:'-'}}</td>

                                        @endforeach
                                        <td>{{$totalMark }}
                                            @php $totalMark=0; @endphp
                                        </td>
                                        <td style="color: {{$subjectMarks['lg']=='F'?'red':'black'}}">{{$subjectMarks['lg']}}</td>
                                        <td style="color: {{$subjectMarks['lg']=='F'?'red':'black'}}">{{$subjectMarks['gp']}}</td>
                                        <td>{{ $height[$sgId] }}</td>
                                        @if($i==1)
                                            <td rowspan="{{count($subjectList)}}"
                                                style="vertical-align : middle; color: {{$result['gpa']==0?'red':'black'}}">{{$result['gpa']}}</td>
                                            <td rowspan="{{count($subjectList)}}"
                                                style="vertical-align : middle; color: {{$result['gpa']==0?'red':'black'}}">{{$result['gpa_with_out_optional']}}</td>
                                            {{--                                    <td rowspan="{{count($subjectList)}}">1st</td>--}}

                                            @php
                                                if(array_key_exists($stdId, $myPassList)){
                                                    $grandTotal = $myPassList[$stdId];
                                                }else{
                                                    $grandTotal =0;
                                                }
                                            @endphp
                                            <td rowspan="{{count($subjectList)}}">
                                                {{$grandTotal>0?(ordinal(array_search($grandTotal, $passArrayList)+1)):'-' }}
                                            </td>
                                        @endif

                                    </tr>
                                @endif

                                @php $i++;  @endphp
                            @endforeach
                            @php $GrandTotalMark =0 ;  @endphp
                            </tbody>
                        </table>

                        <table class="table" style="margin-top: 20px; width: 100%">
                            <tr style="line-height:20px">
                                <td>Comment</td>
                            </tr>

                            <tr style="height: 40px">
                                <td></td>
                            </tr>
                        </table>
                    <!-- /.col -->
                    </div>
                    <div class="row" style="margin-top: 100px">
                        <div class="sing">
                            <p class="parents reportFooter">........................ </p>
                            <p class="parents reportFooter">Guardian </p>
                        </div>
                        <div class="sing">
                            <p class="parents reportFooter">........................ </p>
                            <p class="parents reportFooter">Group Teacher </p>

                        </div>
                        <div class="sing">
                            <p class="parents reportFooter">........................ </p>
                            <p class="parents reportFooter">Convener </p>

                        </div>
                        <div class="sing">
                            <p class="parents reportFooter">........................ </p>
                            <p class="parents reportFooter">Principal </p>
                            {{--                            <p class="parents reportFooter"> {{$instituteInfo->institute_name}}   </p>--}}
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <p>Date: 30/12/2019</p>
                    </div>
                </div>

            </div>

        </div>

        <div class="breakNow"></div>



    @endforeach

</body>
</html>

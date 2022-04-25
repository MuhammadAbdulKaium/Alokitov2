<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .clearfix {
            overflow: auto;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .container{
            width: 100%;
            margin: 0 auto;
        }
        img{
            width: 100%;
        }
        .header{
            border-bottom: 1px solid #f1f1f1;   
            padding: 10px 0;
        }
        .logo{
            width: 16%;
            float: left;
        }
        .headline{
            width: 40%;
            float: left;
            padding: 0 20px;
        }
        .headline-details{
            float: right;
        }
        .sub-header{
            background: #d9edf7;
            padding: 0 10px;
        }
        .sub-header-content{
            width: 33%;
            float: left;
        }
        .content{
            margin: 30px 0;
        }
        .left-content{
            float: left;
            width: 35%;
        }
        .right-content{
            float: left;
            padding-left: 10px;
            border-left: 1px solid #f1f1f1; 
        }
        .prescription-topics{
            min-height: 100px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .footer{
            text-align: center;
            border-top: 1px solid #f1f1f1; 
            padding: 20px 0;  
        }
    </style>
</head>
<body>
    <h2 style="text-align: center">Exam Mark Sheet</h2>

    <div class="header clearfix">
        <div class="logo">
            <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" alt="">
        </div>
        <div class="headline">
            <h3>{{ $institute->institute_name }}</h3>
            <p>{{ $institute->address2 }}</p>
        </div>
        <div class="headline-details">
            <p><b>Year: </b>{{ $academicsYear->year_name }}</p>
            <p><b>Term: </b>{{ $semester->name }}</p>
            <p><b>Class: </b>{{ $batch->batch_name }}</p>
            <p><b>Form: </b>{{ $section->section_name }}</p>
            <p><b>Exam: </b>{{ $exam->exam_name }}</p>
            <p><b>Subject: </b>{{ $subject->subject_name }}</p>
        </div>
    </div>

    <div class="content">
        <table id="myTable" class="table table-striped table-bordered display" width="100%">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center"><a data-sort="sub_master_name">Cadet Photo</a></th>
                <th class="text-center"><a data-sort="sub_master_name">Cadet Name</a></th>
                <th class="text-center"><a data-sort="sub_master_name">Cadet Number</a></th>
                <th class="text-center"><a data-sort="sub_master_name">Full Marks</a></th>
                <th class="text-center"><a data-sort="sub_master_name">Pass Marks</a></th>
                <th class="text-center"><a data-sort="sub_master_name">Conversion</a></th>
                <th class="text-center"><a data-sort="sub_master_name">Pass Conversion</a></th>
                @foreach ($parameters as $parameter)
                    <th class="text-center"><a data-sort="sub_master_name">{{$parameter->name}} ({{$parameterMarks[$parameter->id]}})</a></th>
                @endforeach
                <th class="text-center"><a data-sort="sub_master_name">Total</a></th>
                <th class="text-center"><a data-sort="sub_master_name">Out of ({{$examParameter->full_mark_conversion}})</a></th>
                <th class="text-center"><a data-sort="sub_master_name">%</a></th>
                <th class="text-center"><a data-sort="sub_master_name">Grade</a></th>
            </tr>
            </thead>
            <tbody>
            @foreach($getStudent as $stuInfo)
                @php
                    $marks = null;
                    $myExamMark = null;
                    foreach ($examMarks as $examMark) {
                        if ($examMark->student_id == $stuInfo->std_id) {
                            $myExamMark = $examMark;
                            $marks = json_decode($examMark->breakdown_mark, true);
                        }
                    }
                    $isFail = false;
                @endphp
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>
                        @if($stuInfo->singelAttachment("PROFILE_PHOTO"))
                            <img class="center-block img-circle img-thumbnail img-responsive" src="{{public_path('assets/users/images/'.$stuInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">
                        @else
                            <img class="center-block img-circle img-thumbnail img-responsive" src="{{public_path('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px;height:50px">
                        @endif
                    </td>
                    <td>{{$stuInfo->first_name}} {{$stuInfo->last_name}}</td>
                    <td>{{$stuInfo->singleUser->username}}</td>
                    <td>{{$examParameter->full_marks}}</td>
                    <td>{{$examParameter->pass_marks}}</td>
                    <td>{{$examParameter->full_mark_conversion}}</td>
                    <td>{{$examParameter->pass_mark_conversion}}</td>
                    @foreach ($parameterMarks as $key => $parameterMark)
                        @php
                            $attendance = null;
                            $status = 'disabled';
                            foreach ($examAttendances as $examAttendance) {
                                if ($examAttendance->criteria_id == $key) {
                                    $attendance = json_decode($examAttendance->attendance);
                                }
                            }
                            if ($attendance) {
                                foreach ($attendance as $stdKey => $attStatus) {
                                    if ($stdKey == $stuInfo->std_id) {
                                        if ($attStatus) {
                                            $status = '';
                                        }
                                    }
                                }
                            }
                            if (isset($marks[$key]) && isset($parameterPassMarks[$key])) {
                                if ($parameterPassMarks[$key] > $marks[$key]) {
                                    $isFail = true;
                                }
                            } else {
                                $isFail = true;
                            }
                        @endphp

                        <td>{{($marks)?(isset($marks[$key]))?$marks[$key]:'':''}}</td>
                    @endforeach
                    @php
                        if ($grades) {
                            if($isFail){
                                $grade = grade($grades, '0');
                            } else {
                                $grade = grade($grades, (($myExamMark)?$myExamMark->on_100:''));
                            }
                        } else {
                            $grade = "";
                        }
                    @endphp 
                    <td class="total-mark">{{($myExamMark)?$myExamMark->total_mark:''}}</td>
                    <td class="total-conversion-mark">{{($myExamMark)?$myExamMark->total_conversion_mark:''}}</td>
                    <td class="on-100">{{($myExamMark)?$myExamMark->on_100:''}}</td>
                    <td>{{ ($myExamMark)?$grade:'' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

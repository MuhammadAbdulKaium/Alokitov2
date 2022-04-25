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
    <h2 style="text-align: center">Attendance Sheet</h2>

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
        @if (sizeof($students) > 0)
            @foreach ($criterias as $criteria)
                <h5 class="attendance-table-heading">
                    <b>{{ $criteria->name }} Exam Date:</b> {{ Carbon\Carbon::parse($schedule[$criteria->id]['date'])->format('d/m/Y') }} | 
                    <b>Start Time:</b> {{ Carbon\Carbon::parse($schedule[$criteria->id]['startTime'])->format('g:i a') }} | 
                    <b>End Time:</b> {{ Carbon\Carbon::parse($schedule[$criteria->id]['endTime'])->format('g:i a') }}
                </h5>
            @endforeach
        
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Cadet Photo</th>
                        <th>Cadet Name</th>
                        <th>Cadet ID</th>
                        <th>Class</th>
                        <th>Form</th>
                        <th>Merit Position</th>
                        @foreach ($criterias as $criteria)
                            <th>{{ $criteria->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td>
                                @if($student->singelAttachment("PROFILE_PHOTO"))
                                    <img class="" src="{{public_path('assets/users/images/'.$student->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">
                                @else
                                    <img class="" src="{{public_path('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px;height:50px">
                                @endif
                            </td>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>{{ $student->singleUser->username }}</td>
                            <td>@if ($student->singleBatch) {{ $student->singleBatch->batch_name }} @endif</td>
                            <td>@if ($student->singleSection) {{ $student->singleSection->section_name }} @endif</td>
                            <td>{{ $student->gr_no }}</td>
                            @foreach ($criterias as $criteria)
                                @php
                                    $checked = "";
                                    if(isset($previousAttendance[$criteria->id])){
                                        $attendance = json_decode($previousAttendance[$criteria->id]->attendance, true);
                                        if (isset($attendance[$student->std_id])) {
                                            $checked = ($attendance[$student->std_id])?"checked":"";
                                        }
                                    }
                                @endphp
                                <td>
                                    @if ($checked == "checked")
                                        <span class="text-success"><b>Present</b></span>
                                    @else
                                        <span class="text-danger"><b>Absent</b></span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-danger" style="text-align: center">No Students Found!</div>
        @endif
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .page-break {
            page-break-after: always;
        }

        .page-break:last-child {
            page-break-after: never;
        }

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
            width: 100%;
            text-align: center;
            padding: 0 20px;
        }
        .headline h2{
            margin-top: 0;
        }
        .headline-details{
            float: right;
        }
        .heading{
            margin-top: 10px;
            border: 1px solid;
        }
        .heading h3{
            margin: 0;
            padding: 5px;
            text-align: center;
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

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            font-size: medium;
            background-color: #002d00;
            color: white;
            height: 50px;
        }

        .student-info-block{
            width: 33.33%;
            float: left;
        }
    </style>
</head>
<body>
    <footer>
        <div style="padding:.5rem">
            <span  >Printed from <b>CCIS</b> by {{$user->name}} on <?php echo date('l jS \of F Y h:i:s A'); ?> </span>
        </div>
        <script type="text/php">
            if (isset($pdf)) {
                $x = 730;
                $y = 574;
                $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
                $font = null;
                $size = 14;
                $color = array(255,255,255);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script>
    </footer>

    @foreach ($students as $student)
        <div class="header clearfix">
            <div class="logo">
                <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" alt="">
            </div>
            <div class="headline">
                <h2>{{ $institute->institute_name }}</h2>
                <p>{{ $institute->address2 }}</p>
            </div>
            {{-- <div class="headline-details">
                <p><b>Year: </b>{{ $academicsYear->year_name }}</p>
                <p><b>Term: </b>{{ $semester->name }}</p>
                <p><b>Exam: </b>{{ $exam->exam_name }}</p> --}}
            </div>
        </div>

        <div class="heading">
            <h3>Admit Card</h3>
        </div>

        <div class="student-info clearfix">
            <div class="student-info-block">
                <ul>
                    <li><b>Name: </b>{{ $student->first_name }} {{ $student->last_name }}</li>
                    <li><b>Year: </b>{{ $academicsYear->year_name }}</li>
                    <li><b>Class: </b></li>
                </ul>
            </div>
            <div class="student-info-block">
                <ul>
                    <li><b>Roll No: </b></li>
                    <li><b>Level: </b></li>
                    <li><b>Semester: </b></li>
                </ul>
            </div>
            <div class="student-info-block" style="text-align: right">
                @if($student->singelAttachment("PROFILE_PHOTO"))
                    <img class="center-block img-thumbnail img-responsive" src="{{public_path('assets/users/images/'.$student->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:70px;height:70px">
                @else
                    <img class="center-block img-thumbnail img-responsive" src="{{public_path('assets/users/images/user-default.png')}}" alt="No Image" style="width:70px;height:70px">
                @endif
            </div>
        </div>

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Subject</th>
                        @foreach ($classes as $class)
                            <th>{{ $class->batch_name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subjectMarks as $subjectMark)
                        @php
                            $updateStatus = false;
                        @endphp
                        <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td>{{ $subjectMark[0]->subject->subject_name }}</td>
                            @foreach ($classes as $class)
                                @php
                                    $subjectMarksByBatch = $subjectMark->firstWhere('batch_id', $class->id);
                                    $marks = ($subjectMarksByBatch)?json_decode($subjectMarksByBatch->marks, 1):null;
                                    $criteriaIds = ($marks)?array_keys($marks['fullMarks']):null;
                                    $criterias = ($criteriaIds)?$markParameters->whereIn('id', $criteriaIds):null;
            
                                    $prevScheduleBySub = $previousSchedules->where('subject_id', $subjectMark[0]->subject_id);
                                    $prevScheduleBySubBatch = $prevScheduleBySub->firstWhere('batch_id', $class->id);
                                    if ($prevScheduleBySubBatch) {
                                        $prevScheduleBySubBatch = json_decode($prevScheduleBySubBatch->schedules, 1);
                                        $updateStatus = true;
                                    }
                                @endphp
                                
                                <td>
                                    @if ($criterias)
                                        @foreach ($criterias as $criteria)
                                            @php
                                                $prevScheduleByCriteria = ($prevScheduleBySubBatch)?$prevScheduleBySubBatch[$criteria->id]:null;
                                            @endphp
                                            <div>
                                                <span><b>{{ $criteria->name }}:</b></span>
                                                <span>
                                                    @if ($prevScheduleByCriteria)
                                                        {{ $prevScheduleByCriteria['date'] }}, {{ date("g:i a", strtotime($prevScheduleByCriteria['startTime'])) }} - {{ date("g:i a", strtotime($prevScheduleByCriteria['endTime'])) }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="50">
                                <div style="text-align: center">No Subject Marks Setup Found!</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="page-break"></div>
    @endforeach
</body>
</html>

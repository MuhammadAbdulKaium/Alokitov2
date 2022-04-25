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
    <h2 style="text-align: center">Exam Schedules</h2>

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
            <p><b>Exam: </b>{{ $exam->exam_name }}</p>
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
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Exam Seat Plan</title>
    <style>
        .page-break {
            page-break-after: always;
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
    <h2 style="text-align: center">Exam Seat Plan</h2>

    <div class="header clearfix">
        <div class="logo">
            <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" alt="">
        </div>
        <div class="headline">
            <h3>{{ $institute->institute_name }}</h3>
            <p>{{ $institute->address2 }}</p>
        </div>
        <div class="headline-details">
            <p><b>Exam Category: </b>{{ $exam->ExamCategory->exam_category_name }}</p>
            <p><b>Exam: </b>{{ $exam->exam_name }}</p>
            <p><b>Date: </b>{{ Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
            <p><b>From: </b>{{ Carbon\Carbon::parse($fromTime)->format('g:i a') }}</p>
            <p><b>To: </b>{{ Carbon\Carbon::parse($toTime)->format('g:i a') }}</p>
        </div>
    </div>

    <div class="summary clearfix page-break">
        <h3>Summary</h3>

        <table>
            <thead>
                <tr>
                    <th>Summmary</th>
                    <th>Invigilators</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($seatPlans as $seatPlan)
                    @php
                        $room = $physicalRooms->firstWhere('id', $seatPlan['roomId']);
                        $batches = $batches->whereIn('id', $seatPlan['batchIds']);
                    @endphp
                    <tr data-room-id="{{$room->id}}">
                        <td>{{ $room->name }} ({{ $room->rows }}*{{ $room->cols }}*{{ $room->cadets_per_seat }} = {{ $room->rows * $room->cols * $room->cadets_per_seat }}): Assigned Cadets: {{ $seatPlan['noOfStudents'] }}, Assigned Classes: 
                            @foreach ($batches as $batch)
                                {{ $batch->batch_name }} 
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @if ($selectedEmployeeIds[$room->id])
                                @foreach ($selectedEmployeeIds[$room->id] as $employeeId)
                                    <b>{{ $employees[$employeeId]->first_name }} {{ $employees[$employeeId]->last_name }} </b> 
                                    - {{ $employees[$employeeId]->singleUser->username }} 
                                    @if ($employees[$employeeId]->singleDepartment)
                                        - {{ $employees[$employeeId]->singleDepartment->name }} 
                                    @endif
                                    @if ($employees[$employeeId]->singleDesignation)
                                        - {{ $employees[$employeeId]->singleDesignation->name }}
                                    @endif
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="content">
        @foreach ($seatPlans as $seatPlan)
            @php
                $room = $physicalRooms->firstWhere('id', $seatPlan['roomId']);
                $seats = $seatPlan['seatPlan'];
                $seatNo = 1;
            @endphp

            <table class="table-bordered page-break" style="margin-bottom: 20px">
                <caption><h3>{{$room->name}} ({{ $room->rows }}*{{ $room->cols }}*{{ $room->cadets_per_seat }} = {{ $room->rows * $room->cols * $room->cadets_per_seat }})</h3></caption>
                <tbody>
                    @for ($i = 0; $i < $room->rows; $i++)
                        <tr>
                            @for ($j = 0; $j < $room->cols; $j++)
                                @for ($k = 0; $k < $room->cadets_per_seat; $k++)
                                    @php
                                        if (sizeof($seats) > $seatNo  && sizeof($seats) > 0) {
                                            $student = $students->firstWhere('std_id', $seats[$seatNo]);
                                        } else {
                                            $student = null;
                                        }
                                    @endphp
                                    <td>
                                        @if ($student)
                                            <div style="text-align: center">
                                                <h3 style="color: green">{{ $seatNo++ }}</h3>
                                                <div>
                                                    <span style="display: none">{{ $student->std_id }}</span>
                                                    <div>{{ $student->first_name }} {{ $student->last_name }}</div>
                                                    <div>{{ $student->singleBatch->batch_name }}, Form {{ $student->singleSection->section_name }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <div style="text-align: center">
                                                <h3 style="color: red">{{ $seatNo++ }}</h3>
                                                <div>
                                                    <span style="display: none"></span>
                                                    Empty!
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                @endfor
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>   
        @endforeach
    </div>
    
</body>
</html>
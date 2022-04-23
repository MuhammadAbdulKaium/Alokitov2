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
          .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        /* header Style Start */
        .header{
            width: 100%;
        
        }
        .header-left{
            width: 30%;
            float: left;
        }
        .student-img img{
            width: 100px;
            height: 100px;
        }
        .tab-1{
            margin-left: 1em;
        }
        .tab-2{
            margin-left: 0.7em;
        }
        .tab-3{
            margin-left: 5.1em;
        }
        .tab-4{
            margin-left: 5.6em;
        }
        .student-info{
            margin-top: 20px;
        }
        .student-info p{
            margin: 5px 0;
        }
        .header-middle{
            width: 50%;
            float: left;
            text-align: center;
            padding-top: 20px;
        }
        .institute-info h2{
            margin: 0;
            padding: 0;
        }
        .institute-info p{
           margin-top: 10px;
            padding: 0;
        }
        .institute-logo img{
            width: 80px;
            height: 80px;
        }
        .institute-report{
            margin-top: 10px;
        }
        .institute-report .headline{
            border: 1px solid black;
            padding: 5px 0;
            border-radius: 20px;
           width: 60%;
           margin: 0 auto;

        }
        .header-right{
            float: right;
            width: 20%;
            padding-top: 10px;
        }
        .grade-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .grade-table table th{
            font-size: 12px;
        }
        .grade-table table td{
            font-size: 10px;
        }
        .grade-table table th,.grade-table table td {
            border: 1px solid #000;
            text-align: center;
            padding: 2px;
        }
        .exam-detail{
            padding-top: 10px;
        }
        .exam-detail p{
            font-size: 12px;
            margin: 3px 0;

        }
        /* header Style End */
        /* main Content Start */
        .content{
            padding-top: 20px;
        }
        .main_table table {
            width: 100%;
            border-collapse: collapse;
        }
        .main_table table thead{
            background-color: #B0BC9E;
        }
        .main_table table th{
            border: 1px solid #000;
            text-align: center;
            padding: 5px;
        }
        .main_table table td{
            border: 1px solid #000;
            text-align: center;
            padding: 2px;
            font-size: 14px;
        }
        .color-red{
            color: red;
        }
        .tabel_footer b{
            margin: 0 10px;
            padding: 5px 0 !important;
        }
        /* main Content End */
        /* signature Style Start */
        .signature{
            width: 100%;
            padding-top: 80px;
            text-align: center;

        }
        .singature-left{
            width: 33.33%;
            float: left;
        }
        .singature-left p{
            border-top: 2px dotted #000;
            width: 50%;
            margin: 10px auto;
        }
        .singature-middle{
            width: 33.33%;
            float: left;
        }
        .singature-middle p{
            border-top: 2px dotted #000;
            width: 50%;
            margin: 10px auto;
        }
        .singature-right{
            width: 33.33%;
            float: right;
        }
        .singature-right p{
            border-top: 2px dotted #000;
            width: 50%;
            margin: 10px auto;
        }
        /* signature Style End */
    </style>
</head>
<body>
        @if (sizeof($students)>0)
            @foreach ($students as $student )
                
            <div class="header clearfix">
                <div class="header-left">
                    <div class="student-details">
                        <div class="student-img">
                            <img src="{{ public_path('assets/users/images/' . $institute->logo) }}" alt="logo">
                        </div>
                        @php
                            $studentFullName = $student->first_name.' '. $student->middle_name.' '. $student->last_name;
                            if($student->singleParent){
                                $fatherName = " ";
                                $motherName = " ";
                                foreach ($student->singleParent as $key => $guardian) {
                                    if($guardian->singleGuardian->type == 1){
                                        $fatherName = $guardian->singleGuardian->title.' '.$guardian->singleGuardian->first_name.' '.$guardian->singleGuardian->last_name;
                                    }
                                    if($guardian->singleGuardian->type == 0){
                                        $motherName = $guardian->singleGuardian->title.' '.$guardian->singleGuardian->first_name.' '.$guardian->singleGuardian->last_name;
                                    }
                                }
                            }
                        @endphp
                        <div class="student-info">
                            <p><b>Name of Student :</b> {{$studentFullName}} </p>
                            <p><b>Father's Name<span class="tab-1">:</span> </b> {{$fatherName}} </p>
                            <p><b>Mother's Name<span class="tab-2">:</span> </b> {{$motherName}} </p>
                            <p><b>Class<span class="tab-3">:</span> </b> @if(isset($studentEnrollments[$student->std_id])) {{($studentEnrollments[$student->std_id]->singleBatch)?$studentEnrollments[$student->std_id]->singleBatch->batch_name:""}} @endif </p>
                            <p><b>Roll<span class="tab-4">:</span> </b></p>
                        </div>
                    </div>
                </div>
                <div class="header-middle">
                    <div class="institute-info">
                        <h2 class="cadet_Name">{{$institute->institute_name}}</h2>
                        <p>{{ $institute->address1 }} </p>
                    </div>
                    <div class="institute-logo">
                        <img src="{{ public_path('assets/users/images/' . $institute->logo) }}" alt="logo">
                    </div>
                    <div class="institute-report">
                        <h2 class="headline">PROGRESS REPORT</h2>
                    </div>
                </div>
                <div class="header-right">
                    <div class="grade-table">
                        @foreach ($batch as $ba)
                            <table>
                                <thead>
                                    <tr>
                                        <th>LP</th>
                                        <th>GP</th>
                                        <th>Marks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($grades[$ba->id])
                                        @foreach($grades[$ba->id] as $grade)
                                            <tr>
                                                <td>{{$grade->name}} </td>
                                                <td>{{$grade->points}} </td>
                                                <td> {{$grade->min_per}} - {{$grade->max_per}} </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        @endforeach
                    </div>
                    <div class="exam-detail">
                        <p><b>Exam :</b> {{$examName->exam_name}} </p>
                        {{-- <p><b>Duration :</b> 14 Nov, 2019 - 28 Nov, 2019 </p> --}}
                    </div>
                </div>
            </div>
            <div class="content clearfix">
                <div class="main_table ">
    
                    <table>
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Mark</th>
                                @foreach ($examCategories as $examCategory)
                                    @if ($termFinalExamCategory->id != $examCategory->id)
                                        <th>{{ $examCategory->exam_category_name }}</th>
                                    @endif
                                @endforeach
                                @foreach($criteriaUniqueIds as $uniqueId)
                                    <th>
                                        {{$criteriasAll[$uniqueId]->name}}
                                    </th>
                                @endforeach
                                <th>Total</th>
                                <th>AVG</th>
                                <th>Grade</th>
                                <th>Point</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subjects as $key => $subjectGroup)
                                @foreach ($subjectGroup as $subject)
                                    <tr>
                                        <td>{{ $subject['subject_name'] }}</td>
                                        <td>{{ $sheetData[$student->std_id][$key][$subject['id']]['totalFullMark'] }}</td>
                                        @foreach ($examCategories as $examCategory)
                                            @if ($termFinalExamCategory->id != $examCategory->id)
                                                <td style="color: {{ ($sheetData[$student->std_id][$key][$subject['id']][$examCategory->id]['isFail'])?'red':'black' }}">
                                                    {{ $sheetData[$student->std_id][$key][$subject['id']][$examCategory->id]['mark'] }} /
                                                    {{ $sheetData[$student->std_id][$key][$subject['id']][$examCategory->id]['fullMark'] }}
                                                </td>
                                            @endif
                                        @endforeach
                                        @foreach($criteriaUniqueIds as $uniqueId)
                                            @isset($sheetData[$student->std_id][$key][$subject['id']])
                                                @php
                                                    $marks = json_decode($subjectMarksExamWise[$subject['id']]->marks, 1);
                                                @endphp
                                                @isset($sheetData[$student->std_id][$key][$subject['id']][$termFinalExamCategory->id]['details'][$uniqueId])
                                                    <td rowspan="1" ><span style="color: {{ $sheetData[$student->std_id][$key][$subject['id']][$termFinalExamCategory->id]['details'][$uniqueId]['color'] }}">
                                                        {{ $sheetData[$student->std_id][$key][$subject['id']][$termFinalExamCategory->id]['details'][$uniqueId]['mark'] }} /
                                                        {{ $sheetData[$student->std_id][$key][$subject['id']][$termFinalExamCategory->id]['details'][$uniqueId]['fullMark'] }}
                                                    </span></td>
                                                @else
                                                    <td></td>
                                                @endisset
                                            @endisset
                                        @endforeach
                                        <td>{{ $sheetData[$student->std_id][$key][$subject['id']]['totalMark'] }}</td>
                                        <td>{{ $sheetData[$student->std_id][$key][$subject['id']]['avgMark'] }}</td>
                                        <td>{{ $sheetData[$student->std_id][$key][$subject['id']]['grade'] }}</td>
                                        <td>{{ $sheetData[$student->std_id][$key][$subject['id']]['gradePoint'] }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr>
                                <td colspan="9" class="tabel_footer">
                                   <i>
                                       <b>Total: {{ $sheetData[$student->std_id]['grandTotalFullMark'] }},</b>
                                       <b>Obtained: {{ $sheetData[$student->std_id]['grandTotal'] }},</b>
                                       <b>Highest: N/A, </b>
                                       <b>Percent: {{ $sheetData[$student->std_id]['avg'] }}%,</b>
                                       <b>Failed in 7 subject</b>
                                   </i>
                                </td>
                            </tr>
                            
                        </tbody>
                        
                    </table>
                </div>
                <div class="signature clearfix">
                    <div class="singature-left">
                        <p>Guardian</p>
                    </div>
                    <div class="singature-middle">
                        <p>Class Teacher</p>
                    </div>
                    <div class="singature-right">
                        <p>{{$institute->institute_name}}</p>
                    </div>
                </div>
            </div>
            <div class="clearfix page-break"></div>
            @endforeach
        @endif

</body>
</html>
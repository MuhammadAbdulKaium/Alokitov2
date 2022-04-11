<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tabulation Sheet Report (Exam Wise)</title>
    <style>
        .p-0 {
            padding: 0px !important;
        }

        .m-0 {
            margin: 0px !important;

        }

        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        img {
            width: 100px;
            height: 100px;
        }

        .header {
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0;
        }

        .logo {
            width: 8%;
            float: left;
            margin-bottom: 10px;
        }

        .headline {
            float: left;
            padding: 1px 1px;
        }

        .headline-details {
            float: right;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #423e3e;
            text-align: center !important;
            page-break-inside: auto;
        }

        th, td {
            text-align: left;
            padding: 1px;
            border: 1px solid #a49494;
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

        p {
            page-break-after: always;
        }

        p:last-child {
            page-break-after: never;
        }

    </style>
</head>
<body style="font-size: x-small">

<footer>
    <div style="padding:.5rem">
        <span>Printed from <b>CCIS</b> by {{$user->name}} on <?php echo date('l jS \of F Y h:i:s A'); ?> </span>

    </div>
    <script type="text/php">
    if (isset($pdf)) {
        $x = 1550;
        $y = 1170;
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


<main>
    <div class="header clearfix" >
        <div class="logo">
            <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" height="60px!important" alt="">
        </div>
        <div class="headline">
            <h1>{{ $institute->institute_name }}</h1>
            <p>{{ $institute->address2 }}</p>
        </div>
        <div style="float: left;width: 14%;font-size: xx-small;padding: 0;margin: 0">

        </div>
        <h1 style="text-align: center;float: left">Tabulation Sheet Report (Exam Wise)</h1>
        <div style="float: left;width: 2%;font-size: xx-small;padding: 0;margin: 0">

        </div>
        <div style="float: right;width: 10%;font-size: xx-small;padding-left:4px ;margin: 0">
            @foreach ($batch as $ba)
                <table class="table-bordered" >
                    <tr>
                        <td colspan="3">Grading System {{ $ba->batch_name }}</td>
                    </tr>
                    <tr>
                        <td>LG </td>
                        <td>GP</td>
                        <td>Marks</td>
                    </tr>
                    @if ($grades[$ba->id])
                        @foreach($grades[$ba->id] as $grade)
                            <tr >
                                <td  class="m-0 p-0">{{$grade->name}} </td>
                                <td  class="m-0 p-0">{{$grade->points}} </td>
                                <td class="m-0 p-0" > {{$grade->min_per}}% to {{$grade->max_per}}% </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            @endforeach
        </div>

        <div style="float: right;width: 10%;font-size: xx-small;padding-left: 4px;margin: 0">
            <table class="" style="border: none">
                @php
                    $count=0
                @endphp
                @foreach ($subjects as $subject)
                    @php

                    ($count++)
                    @endphp
                    @if($count>=9)
                        <tr style="border: none;text-align: left" class="p-0 m-0">
                            <td class="p-0 m-0">
                                <span>{{$subject->subject_name}}</span>
                            </td  >
                            <td class="p-0 m-0">
                                <span>{{ $subject->subject_alias}} </span><br>
                            </td>
                        </tr>
                    @endif
                @endforeach

            </table>
        </div>
        <div style="float: right;width: 10%;font-size: xx-small;padding-left:4px ;margin: 0">
            <table class="" style="border: none">
                @php
                    $count=0
                @endphp
                @foreach ($subjects as $subject)
                    @php

                    ($count++)
                    @endphp
                    @if($count<9)
                        <tr style="border: none;text-align: left" class="p-0 m-0">
                            <td class="p-0 m-0">
                                <span>{{$subject->subject_name}}</span>
                            </td  >
                            <td class="p-0 m-0">
                                <span>{{ $subject->subject_alias}} </span><br>
                            </td>
                        </tr>
                    @endif
                @endforeach

            </table>
        </div>

    </div>
    <div>
        <h3>
            Selected Parameters: Academic Year :{{ $academicsYear->year_name }}
            Term: {{ $sem->name }}
            <b>Class: </b>
            @foreach ($batch as $ba)
                {{ $ba->batch_name }} @if (!$loop->last) , @endif
            @endforeach
            @if ($section)
                <b>Form: </b>{{ $section->section_name }}
            @endif


        </h3>
    </div>


    @php
        $extraCol = 1;
    @endphp
    <table class="table table-bordered">
        <thead style="background:#e9ecef">
        <tr>
            <th rowspan="2">#</th>
            @if($compact==null)
                <th rowspan="2">Photo</th>
            @endif
            <th rowspan="2">Cadet No</th>
            <th rowspan="2">Cadet Info</th>
            <th rowspan="2">House</th>

            @foreach ($subjects as $subject)
                @php
                    if(isset($subjectMarks[$subject->id])){
                        $marks = json_decode($subjectMarks[$subject->id]->marks, 1);
                        $colspan = sizeof($marks['fullMarks'])+$extraCol;
                    }
                @endphp
                <th rowspan="1" colspan="{{ $colspan }}">{{ $subject->subject_alias }}</th>
            @endforeach
            <th rowspan="2">G.T</th>
            <th rowspan="2">AVG</th>
            <th rowspan="2">%</th>
            <th rowspan="2">GPA</th>
            <th rowspan="2">Position</th>
        </tr>
        <tr>

            @foreach ($subjects as $subject)
                @isset($subjectMarks[$subject->id])
                    @php
                        $marks = json_decode($subjectMarks[$subject->id]->marks, 1);
                        $conversionPoint = ($subjectMarks[$subject->id]->full_marks != 0)?$subjectMarks[$subject->id]->full_mark_conversion/$subjectMarks[$subject->id]->full_marks:0;
                    @endphp
                    @foreach ($marks['fullMarks'] as $key => $mark)
                        <th rowspan="1">
                            {{ substr($criterias[$key]->name, 0, 2) }} <br>
                            ({{ round($mark*$conversionPoint, 2) }})
                        </th>
                    @endforeach
                    <th rowspan="1">T</th>
                @endisset
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach ($students as $student)

            <tr @if(($loop->index+1)%2==0)  style=" background:#e9ecef;" @endif>
                <td>{{ $loop->index+1 }}</td>
                @if($compact==null)
                    <td>
                        @if($student->singelAttachment("PROFILE_PHOTO"))
                            <img class="center-block img-circle img-thumbnail img-responsive"
                                 src="{{public_path('assets/users/images/'.$student->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"
                                 alt="No Image" style="width:70px;height:auto">
                        @else
                            <img class="center-block img-circle img-thumbnail img-responsive"
                                 src="{{public_path('assets/users/images/user-default.png')}}" alt="No Image"
                                 style="width:70px;height:auto">
                        @endif
                    </td>
                @endif
                <td>
                    <span style="font-weight: bold" class="text-success">{{ $student->singleUser->username }}</span>
                    <br>
                </td>

                <td>
                    <span style="font-weight: bold">{{ $student->singleStudent->nickname }}</span> <br>
                    @if($compact==null)
                        AcYr: @if($student->singleYear) {{$student->singleYear->year_name}}, @endif
                        AdYr: @if($student->singleEnroll->admissionYear) {{$student->singleEnroll->admissionYear->year_name}}
                        , @endif
                        Batch: @if(isset($studentEnrollments[$student->std_id])) {{($studentEnrollments[$student->std_id]->singleBatch)?$studentEnrollments[$student->std_id]->singleBatch->batch_name:""}} @endif<br>
                        Form: @if(isset($studentEnrollments[$student->std_id])) {{($studentEnrollments[$student->std_id]->singleSection)?$studentEnrollments[$student->std_id]->singleSection->section_name:""}} @endif<br>
                    @endif
                </td>
                <td>
                    @if($student->roomStudent) {{($houses[$student->roomStudent->house_id])?substr($houses[$student->roomStudent->house_id]->name,0,3):""}} @endif
                </td>


                @foreach ($subjects as $subject)
                    @isset($sheetData[$student->std_id][$subject->id])
                        @php
                            $marks = json_decode($subjectMarks[$subject->id]->marks, 1);
                        @endphp
                        @foreach ($marks['fullMarks'] as $key => $mark)
                            <td rowspan="1"><span
                                        style="color: {{ $sheetData[$student->std_id][$subject->id][$key]['color'] }}">{{ $sheetData[$student->std_id][$subject->id][$key]['mark'] }}</span>
                            </td>
                        @endforeach
                        <td rowspan="1"
                            style="color: {{ ($sheetData[$student->std_id][$subject->id]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id][$subject->id]['totalMark'] }}</td>
                    @endisset
                @endforeach
                <td style="{{ ($sheetData[$student->std_id]['isFail'])?'color: red; font-weight: bold':'' }}">{{ $sheetData[$student->std_id]['grandTotalMark'] }}</td>
                <td style="{{ ($sheetData[$student->std_id]['isFail'])?'color: red; font-weight: bold':'' }}">{{ $sheetData[$student->std_id]['totalAvgMark'] }}</td>
                <td style="{{ ($sheetData[$student->std_id]['isFail'])?'color: red; font-weight: bold':'' }}">{{ $sheetData[$student->std_id]['totalAvgMarkPercentage'] }}</td>
                <td style="{{ ($sheetData[$student->std_id]['isFail'])?'color: red; font-weight: bold':'' }}">{{ $sheetData[$student->std_id]['gpa'] }}</td>
                <td  style="{{ ($sheetData[$student->std_id]['isFail'])?'color: red; font-weight: bold':'' }}">@if ($sheetData[$student->std_id]['hasMark']) {{ $sheetData[$student->std_id]['position'] }} @endif</td>
            </tr>
        @endforeach

        </tbody>
    </table>

</main>
</body>

</html>
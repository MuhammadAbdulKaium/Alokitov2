@php
    $extraCol = 1;
    $colspan = sizeof($allSemesters);
    $rowNumber=0;
@endphp

<style>
    table.table-bordered{
        border:1px solid #000000;
        margin-top:20px;
    }
    table.table-bordered > thead > tr > th{
        border:1px solid #000000;
        @if($compact)
        font-size: x-small;
    @endif
    }
    table.table-bordered > tbody > tr > td{
        border:1px solid #000000;
        @if($compact)
        font-size: x-small;
    @endif
    }
    .select2-selection--single{
        height: 33px !important;
    }
    .drug-tooltip{
        position: relative;
    }
    .tooltip-open-text{
        padding: 3px;margin: 2px;background: darkgreen;color: white;

    }
    .drug-tooltip-details{
        background: #FBF7AA;
        padding: 6px;
        border-radius: 3px;
        position: absolute;
        left: 0;
        top:-5px;
        width: 300px;
        display: none;
        z-index: 5;
        box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
    }
    .drug-tooltip-details table{
        width: 100%;
        border-collapse: collapse;
    }

    .drug-tooltip-details table,.drug-tooltip-details td,.drug-tooltip-details th {
        border: 1px solid black;
        padding: 1px 2px;
    }

    .tooltip-open-text{
        cursor: pointer;
    }

    .drug-tooltip-cross-btn{
        float: right;
        color: red;
        cursor: pointer;
    }

</style>

<div style="display: flex">
    <div class="drug-tooltip">
        <span class="tooltip-open-text " style="">Grade</span>
        <div class="drug-tooltip-details">
            <span class="drug-tooltip-cross-btn">X</span>
            <h5><b>Subject Grades</b></h5>
            <div class="tooltip-content">
                @foreach ($batch as $ba)
                    <table class="table-bordered" >
                        <tr>
                            <td colspan="3" style="font-size: 14px">Grading System - {{ $ba->batch_name }}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px">LG </td>
                            <td style="font-size: 14px">GP</td>
                            <td style="font-size: 14px">Marks</td>
                        </tr>
                        @if ($grades[$ba->id])
                            @foreach($grades[$ba->id] as $grade)
                                <tr style="padding:0 3px">
                                    <td  style="padding:0 3px; font-size: 14px">{{$grade->name}} </td>
                                    <td  style="padding:0 3px; font-size: 14px">{{$grade->points}} </td>
                                    <td  style="padding:0 3px; font-size: 14px"> {{$grade->min_per}}% to {{$grade->max_per}}% </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                @endforeach
            </div>
        </div>
    </div>
    <div class="drug-tooltip">
        <span class="tooltip-open-text">Subject Alias</span>
        <div class="drug-tooltip-details">
            <span class="drug-tooltip-cross-btn">X</span>
            <h5><b>Subject Alias</b></h5>
            <div class="tooltip-content">


                <table class="table-bordered" >
                    @foreach ($subjects as $key => $subjectGroup)
                        @foreach ($subjectGroup as $subject)
                            <tr>
                                <td>
                                    <span style="font-size: 14px">{{ $subject['subject_name'] }}</span>
                                </td>
                                <td>
                                    <span style="font-size: 14px">{{ $subject['subject_code'] }}</span><br>
                                </td>
                            </tr>

                        @endforeach

                    @endforeach

                </table>



            </div>
        </div>
    </div>
</div>
<table class="table table-bordered table-striped">
    <thead class="table-secondary" style="background:#dee2e6;font-size:large">
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2" style="min-width: 80px!important;" @if($rowNumber%2==1) style="background:rgba(192,183,183,0.39);color: #111111"  @endif>Cadet No</th>
            @if($compact==null)
            <th rowspan="2">Photo</th>
            @endif
            <th rowspan="2" style="@if($compact==null) min-width: 200px @else min-width:80px @endif">Cadet Info</th>
            <th rowspan="2" style="min-width: 100px!important;">House</th>
            @foreach ($subjects as $key => $subjectGroup)
                @php
                $rowNumber++;
                @endphp
                @foreach ($subjectGroup as $subject)
                    <th  rowspan="1" colspan="{{ ($key)?$colspan:$colspan+$extraCol }}">{{ $subject['subject_code'] }}</th>
                @endforeach
                @if ($key)
                    <th  colspan="{{ $extraCol }}"></th>
                @endif
            @endforeach
            <th rowspan="2">G.T</th>
            <th rowspan="2">AVG</th>
            <th rowspan="2">GPA</th>
            <th rowspan="2">Pos</th>
        </tr>
        <tr>
            @php
                $rowNumber=0;
            @endphp
            @foreach ($subjects as $key => $subjectGroup)
                @php
                    $rowNumber++;
                @endphp
                @foreach ($subjectGroup as $subject)
                    @foreach ($allSemesters as $semester)
                        <th  rowspan="1" style="font-size: 9px">{{ $semester->name }}</th>
                    @endforeach
                    @if (!$key)
                        <th rowspan="1" >T</th>
                    @endif
                @endforeach
                @if ($key)
                    <th rowspan="1" >T</th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $student)

            <tr @if($loop->index%2==1)  style="background: #dee2e6;" @endif>
                <td>{{ $loop->index+1 }}</td>
                <td><span style="font-weight: bold" class="text-success">{{ $student->singleUser->username }}</span> <br></td>
                @if($compact==null)
                <td>
                    @if($student->singelAttachment("PROFILE_PHOTO"))
                        <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$student->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:70px;height:auto">
                    @else
                        <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:70px;height:auto">
                    @endif
                </td>
                @endif

                <td>

                    <span style="font-weight: bold">{{ $student->singleStudent->nickname }}</span> <br>
                    @if($compact==null)
                        AcYr: @if($student->singleYear) {{$student->singleYear->year_name}}, @endif
                        AdYr: @if($student->singleEnroll->admissionYear) {{$student->singleEnroll->admissionYear->year_name}}, @endif
                        Batch: @if(isset($studentEnrollments[$student->std_id])) {{($studentEnrollments[$student->std_id]->singleBatch)?$studentEnrollments[$student->std_id]->singleBatch->batch_name:""}} @endif<br>
                        Form: @if(isset($studentEnrollments[$student->std_id])) {{($studentEnrollments[$student->std_id]->singleSection)?$studentEnrollments[$student->std_id]->singleSection->section_name:""}} @endif<br>

                    @endif
                </td>
                <td>
                    @if($student->roomStudent) {{($houses[$student->roomStudent->house_id])?$houses[$student->roomStudent->house_id]->name:""}} @endif
                </td>
                @php
                    $rowNumber=0;
                @endphp
                @foreach ($subjects as $key => $subjectGroup)
                    @php
                        $rowNumber++;
                    @endphp
                    @foreach ($subjectGroup as $subject)
                        @foreach ($allSemesters as $semester)
                            <td >{{ $sheetData[$student->std_id][$key][$subject['id']][$semester->id]['totalMark'] }}</td>
                        @endforeach
                        @if (!$key)
                            <td style="font-weight: bold"> {{ $sheetData[$student->std_id][$key][$subject['id']]['totalAvg'] }}</td>
                        @endif
                    @endforeach
                    @if ($key)
                        <td style="font-weight: bold" >{{ $sheetData[$student->std_id][$key]['totalAvg'] }}</td>
                    @endif
                @endforeach
                <td  >{{ $sheetData[$student->std_id]['grandTotal'] }}</td>
                <td >{{ $sheetData[$student->std_id]['avg'] }}</td>
                <td  >{{ $sheetData[$student->std_id]['grade'] }}</td>
                <td >@if ($sheetData[$student->std_id]['hasMark']) {{ $sheetData[$student->std_id]['position'] }} @endif</td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $('.tooltip-open-text').click(function () {
            $(this).next().css('display', 'block');
        });

        $('.drug-tooltip-cross-btn').click(function () {
            $(this).parent().css('display', 'none');
        });
    });
</script>
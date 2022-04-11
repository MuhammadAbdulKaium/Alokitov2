@php
    $extraCol = 1;
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
        font-size: xx-small;
    @endif
    }
    table.table-bordered > tbody > tr > td{
        border:1px solid #000000;
        @if($compact)
        font-size: xx-small;
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
                    @foreach ($subjects as $subject)
                        <tr>
                            <td style="font-size: 14px">
                                <span >{{$subject->subject_name}}</span>
                            </td>
                            <td style="font-size: 14px">
                                <span >{{ $subject->subject_alias}} </span><br>
                            </td>
                        </tr>

                    @endforeach

                </table>



            </div>
        </div>
    </div>
    <div class="drug-tooltip">
        <span class="tooltip-open-text">Approval Info</span>
        <div class="drug-tooltip-details">
            <span class="drug-tooltip-cross-btn">X</span>
            <h5><b>Approval Layers</b></h5>
            <div class="tooltip-content">
                <ul>
                    @forelse ($approvalLogs as $approvalLog)
                        <li class="{{ ($approvalLog->action_status == 1)?'text-success':'' }}">
                            Step {{ $approvalLog->approval_layer }}: 
                            {{ ($approvalLog->action_status == 1)?'Approved':'' }} by 
                            {{ $approvalLog->user->name }}, {{ Carbon\Carbon::parse($approvalLog->created_at)->diffForHumans() }}
                        </li>
                    @empty
                        <li>No approvals!</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    @if($examList)    
        <div class="" style="margin: 0 10px">
            @if ($examList->publish_status) Status: @endif
            @if ($examList->publish_status == 1)
                <span class="text-warning">Pending</span>
            @elseif ($examList->publish_status == 2)
                <span class="text-success">Published</span>
            @elseif ($examList->publish_status == 3)
                <span class="text-danger">Rejected</span>
            @endif
        </div>
    @endif
    @if ($approvalStatus && $examList->publish_status == 1)
        <button class="btn btn-xs btn-success" id="exam-approve-btn" 
        data-exam-list-id="{{ ($examList)?$examList->id:0 }}">Approve this Exam</button>
    @endif
</div>
<table class="table table-bordered ">
    <thead style="background: #dee2e6;">
        <tr>
            <th rowspan="2">#</th>
            @if($compact==null)
            <th rowspan="2">Photo</th>
            @endif
            <th rowspan="2" style="min-width: 80px!important;" >Cadet No</th>
            <th rowspan="2" >Cadet Info</th>
            <th rowspan="2" style="min-width: 100px!important;">House</th>
            @foreach ($subjects as $subject)
                @php
                $rowNumber++;
                    if(isset($subjectMarks[$subject->id])){
                        $marks = json_decode($subjectMarks[$subject->id]->marks, 1);
                        $colspan = sizeof($marks['fullMarks'])+$extraCol;
                    }
                @endphp
                <th rowspan="1" class="text-center" colspan="{{ $colspan }}" >{{ $subject->subject_alias}}</th>
            @endforeach
            <th rowspan="2"  >G.T</th>
            <th rowspan="2"  >AVG</th>
            <th rowspan="2" >%</th>
            <th rowspan="2" >GPA</th>
            <th rowspan="2" >Position</th>
        </tr>
        <tr>
            @php
                $rowNumber=0;
            @endphp
            @foreach ($subjects as $subject)
                @isset($subjectMarks[$subject->id])
                    @php
                    $rowNumber++;
                        $marks = json_decode($subjectMarks[$subject->id]->marks, 1);
                        $conversionPoint = ($subjectMarks[$subject->id]->full_marks != 0)?$subjectMarks[$subject->id]->full_mark_conversion/$subjectMarks[$subject->id]->full_marks:0;
                    @endphp
                    @foreach ($marks['fullMarks'] as $key => $mark)
                        <th rowspan="1"  >
                            {{ substr($criterias[$key]->name, 0, 2) }} <br>
                            ({{ round($mark*$conversionPoint, 2) }})
                        </th>
                    @endforeach
                    <th rowspan="1" >T</th>
                @endisset
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $student)
            <tr @if($loop->index%2==1)  style="background: #dee2e6;" @endif>
                <td>{{ $loop->index+1 }}</td>
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
                    <span style="font-weight: bold" class="text-success">{{ $student->singleUser->username }}</span>

                </td>
                <td   style="@if($compact==null)min-width: 200px @else min-width:100px @endif">
                    <span style="font-weight: bold;font-min-size: .3rem">{{ $student->singleStudent->nickname }}</span>
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
                @foreach ($subjects as $subject)
                    @isset($sheetData[$student->std_id][$subject->id])
                        @php
                        $rowNumber++;
                            $marks = json_decode($subjectMarks[$subject->id]->marks, 1);
                        @endphp
                        @foreach ($marks['fullMarks'] as $key => $mark)
                            <td rowspan="1"  ><span style="color: {{ $sheetData[$student->std_id][$subject->id][$key]['color'] }}">{{ $sheetData[$student->std_id][$subject->id][$key]['mark'] }}</span></td>
                        @endforeach
                        <td rowspan="1"  style="color: {{ ($sheetData[$student->std_id][$subject->id]['isFail'])?'red':'black' }}; font-weight: bold">{{ $sheetData[$student->std_id][$subject->id]['totalMark'] }}</td>
                    @endisset
                @endforeach
                <td  style="{{ ($sheetData[$student->std_id]['isFail'])?'color: red; font-weight: bold':'' }}">{{ $sheetData[$student->std_id]['grandTotalMark'] }}</td>
                <td  style="{{ ($sheetData[$student->std_id]['isFail'])?'color: red; font-weight: bold':'' }}">{{ $sheetData[$student->std_id]['totalAvgMark'] }}</td>
                <td  style="{{ ($sheetData[$student->std_id]['isFail'])?'color: red; font-weight: bold':'' }}">{{ $sheetData[$student->std_id]['totalAvgMarkPercentage'] }}</td>
                <td  style="{{ ($sheetData[$student->std_id]['isFail'])?'color: red; font-weight: bold':'' }}">{{ $sheetData[$student->std_id]['gpa'] }}</td>
                <td  style="{{ ($sheetData[$student->std_id]['isFail'])?'color: red; font-weight: bold':'' }}">@if ($sheetData[$student->std_id]['hasMark']) {{ $sheetData[$student->std_id]['position'] }} @endif</td>
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
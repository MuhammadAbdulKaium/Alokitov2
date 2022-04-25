@if (sizeof($students) > 0)
    @foreach ($criterias as $criteria)
        <h5 class="attendance-table-heading">
            <b>{{ $criteria->name }} Exam Date:</b> {{ Carbon\Carbon::parse($schedule[$criteria->id]['date'])->format('d/m/Y') }} | 
            <b>Start Time:</b> {{ Carbon\Carbon::parse($schedule[$criteria->id]['startTime'])->format('g:i a') }} | 
            <b>End Time:</b> {{ Carbon\Carbon::parse($schedule[$criteria->id]['endTime'])->format('g:i a') }}
        </h5>
    @endforeach

    <table class="table table-bordered attendance-table">
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
                    <th>@if ($type == "search")<input type="checkbox" class="attendance-all-select" data-criteria-id="{{ $criteria->id }}">@endif {{ $criteria->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $loop->index+1 }}</td>
                    <td>
                        @if($student->singelAttachment("PROFILE_PHOTO"))
                            <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$student->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">
                        @else
                            <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px;height:50px">
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
                            @if ($type == "search")
                                @if (Carbon\Carbon::now() >= Carbon\Carbon::parse($schedule[$criteria->id]['date']) )
                                    <input type="checkbox" class="attendance-select" data-criteria-id="{{ $criteria->id }}" value="{{ $student->std_id }}" {{ $checked }}>
                                @else
                                    <i class="fa fa-pause-circle"></i>
                                @endif
                            @elseif($type == "view")
                                @if ($checked == "checked")
                                    <span class="text-success"><b>Present</b></span>
                                @else
                                    <span class="text-danger"><b>Absent</b></span>
                                @endif
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="attendance-save-btn-holder">
        @if ($type == "search" && $canSave)
            @if ($previousAttendance)
                <button class="btn btn-success attendance-save-btn" style="float: right;">Update</button>
            @else
                <button class="btn btn-success attendance-save-btn" style="float: right;">Save</button>
            @endif
        @endif
    </div>
@else
    <div class="text-danger" style="text-align: center">No Students Found!</div>
@endif

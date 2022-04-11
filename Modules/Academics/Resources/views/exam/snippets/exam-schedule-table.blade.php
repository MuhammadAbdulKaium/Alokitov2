<table class="table table-bordered" id="marksTable">
    <thead>
        <tr>
            <th>SL</th>
            <th>Subject</th>
            @foreach ($classes as $class)
                <th>{{ $class->batch_name }}</th>
            @endforeach
            @if ($type == 'search')
                <th>Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if ($type == 'search')
            <tr class="bg-warning">
                <td>#</td>
                <td>Master Fields</td>
                @foreach ($classes as $class)
                    <td>
                        <div class="row">
                            <div class="col-sm-2"><b>All:</b></div>
                            <div class="col-sm-10">
                                <input type="text" class="master-parameter-date" data-class-id="{{ $class->id }}" placeholder="Date"> 
                                <input type="time" class="master-parameter-start-time" data-class-id="{{ $class->id }}"> - 
                                <input type="time" class="master-parameter-end-time" data-class-id="{{ $class->id }}"> 
                            </div>
                        </div>
                    </td>
                @endforeach
                <td>
                    <button class="btn btn-primary all-save-btn">Save All</button>
                </td>
            </tr>
        @endif
        @forelse ($subjectMarks as $subjectMark)
            @php
                $updateStatus = false;
            @endphp
            <form>
                <tr class="subject-tr" data-subject-id="{{ $subjectMark[0]->subject_id }}">
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
                        
                        <td class="{{ ($criterias)?'batch-td':'' }}" data-batch-id="{{ $class->id }}">
                            @if ($criterias)
                                @foreach ($criterias as $criteria)
                                    @php
                                        $prevScheduleByCriteria = ($prevScheduleBySubBatch)?$prevScheduleBySubBatch[$criteria->id]:null;
                                    @endphp
                                    <div class="row">
                                        <div class="col-sm-2"><b>{{ $criteria->name }}:</b></div>
                                        <div class="col-sm-10">
                                            @if ($type == 'search')    
                                                <input type="text" class="parameter-date" data-class-id="{{ $class->id }}" data-parameter-id="{{ $criteria->id }}" value="{{ ($prevScheduleByCriteria)?$prevScheduleByCriteria['date']:"" }}" placeholder="Date" required> 
                                                <input type="time" class="parameter-start-time" data-class-id="{{ $class->id }}" data-parameter-id="{{ $criteria->id }}" value="{{ ($prevScheduleByCriteria)?$prevScheduleByCriteria['startTime']:"" }}" required> - 
                                                <input type="time" class="parameter-end-time" data-class-id="{{ $class->id }}" data-parameter-id="{{ $criteria->id }}" value="{{ ($prevScheduleByCriteria)?$prevScheduleByCriteria['endTime']:"" }}" required>    
                                            @else
                                                @if ($prevScheduleByCriteria)
                                                    {{ $prevScheduleByCriteria['date'] }}, {{ date("g:i a", strtotime($prevScheduleByCriteria['startTime'])) }} - {{ date("g:i a", strtotime($prevScheduleByCriteria['endTime'])) }}
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </td>
                    @endforeach
                    @if ($type == 'search' && $canSave)
                        @if ($updateStatus)
                            <td style="vertical-align: middle"><button class="btn btn-success save-button">Update</button></td>
                        @else
                            <td style="vertical-align: middle"><button class="btn btn-success save-button">Save</button></td>
                        @endif
                    @endif
                </tr>
            </form>
        @empty
            <tr>
                <td colspan="50">
                    <div class="text-warning text-center">No Subject Marks Setup Found!</div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    $('.master-parameter-date').datepicker();
    $('.parameter-date').datepicker();
</script>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-history"></i> Invigilators Histories</h4>
</div>
<div class="modal-body table-responsive">
    <table class="table table-bordered history-table">
        <thead>
            <tr>
                <th>Term</th>
                <th>Exam</th>
                <th>Employee Name</th>
                <th>Room</th>
                <th>Date</th>
                <th>From</th>
                <th>To</th>
                <th>Hour</th>
                <th>Total Hour</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($examSeatPlans as $examSeatPlanExamWise)
                @foreach ($employeeIds as $employeeId)
                    @php
                        $c = 0;
                        $i = 0;
                        $totalHour = '00:00';
                    @endphp
    
                    {{-- To Generate Total Time Start --}}
                    @foreach ($examSeatPlanExamWise as $examSeatPlan)
                        @php
                            $employeeIdsWithRoom = json_decode($examSeatPlan->employee_ids, 1);
                        @endphp
                        @foreach ($employeeIdsWithRoom as $employeeIdWithRoom)
                            @if (in_array($employeeId, $employeeIdWithRoom))
                                @php
                                    $fromTime = Carbon\Carbon::parse($examSeatPlan->from_time);
                                    $toTime = Carbon\Carbon::parse($examSeatPlan->to_time);
                                    $hour = $toTime->diff($fromTime)->format('%H:%I');
                                    $totalHour = sum_time($totalHour, $hour);
                                    $c++;
                                @endphp
                            @endif
                        @endforeach
                    @endforeach
                    {{-- To Generate Total Time End --}}
    
    
                    @foreach ($examSeatPlanExamWise as $examSeatPlan)
                        @php
                            $employeeIdsWithRoom = json_decode($examSeatPlan->employee_ids, 1);
                        @endphp
                        @foreach ($employeeIdsWithRoom as $roomKey => $employeeIdWithRoom)
                            @if (in_array($employeeId, $employeeIdWithRoom))
                                @php
                                    $fromTime = Carbon\Carbon::parse($examSeatPlan->from_time);
                                    $toTime = Carbon\Carbon::parse($examSeatPlan->to_time);
                                    $hour = $toTime->diff($fromTime)->format('%H:%I');
                                    $i++;
                                @endphp
                                <tr>
                                    <td>{{ $semesters[$examSeatPlan->semester_id]->name }}</td>
                                    <td>{{ $exams[$examSeatPlan->exam_id]->exam_name }}</td>
                                    <td>{{ $employees[$employeeId]->first_name }}</td>
                                    <td>{{ ($rooms[$roomKey])?$rooms[$roomKey]->name:"" }}</td>
                                    <td>{{ Carbon\Carbon::parse($examSeatPlan->date)->format("d/m/Y") }}</td>
                                    <td>{{ $fromTime->format("g:i a") }}</td>
                                    <td>{{ $toTime->format("g:i a") }}</td>
                                    <td>{{ $hour }}</td>
                                    @if ($i == 1)
                                        <td rowspan="{{ $c }}" style="vertical-align: middle">{{ $totalHour }}</td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach          
                    @endforeach
    
                @endforeach
            @empty
                <tr>
                    <td colspan="50" class="text-center">No Results Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        // $('.history-table').DataTable();
    });
</script>
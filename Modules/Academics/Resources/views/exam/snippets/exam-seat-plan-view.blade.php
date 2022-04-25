<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Summary </h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Summmary</th>
                            <th>Invigilator</th>
                            <th>History</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($seatPlans as $seatPlan)
                            @php
                                $room = $physicalRooms->firstWhere('id', $seatPlan['roomId']);
                                $batches = $batches->whereIn('id', $seatPlan['batchIds']);
                            @endphp
                            <tr class="room-summary-row" data-room-id="{{$room->id}}">
                                <td>{{ $room->name }} ({{ $room->rows }}*{{ $room->cols }}*{{ $room->cadets_per_seat }} = {{ $room->rows * $room->cols * $room->cadets_per_seat }}): Assigned Cadets: {{ $seatPlan['noOfStudents'] }}, Assigned Classes: 
                                    @foreach ($batches as $batch)
                                        {{ $batch->batch_name }} 
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    <select name="" class="form-control select-invigilator" multiple>
                                        @foreach ($employees as $employee)
                                            @php
                                                $selected = "";
                                                if (isset($selectedEmployeeIds[$room->id])) {
                                                    if (in_array($employee->id, $selectedEmployeeIds[$room->id])) {
                                                        $selected = "selected";
                                                    }
                                                }
                                            @endphp
                                            <option value="{{ $employee->id }}" {{ $selected }}>
                                                <b>{{ $employee->first_name }} {{ $employee->last_name }} </b> 
                                                - {{ $employee->singleUser->username }} 
                                                @if ($employee->singleDepartment)
                                                    - {{ $employee->singleDepartment->name }} 
                                                @endif
                                                @if ($employee->singleDesignation)
                                                    - {{ $employee->singleDesignation->name }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    @if ($canCheckInvigilatorHistory)
                                    <button class="btn btn-primary btn-xs invigilator-history-btn"><i class="fa fa-history"></i></button>
                                    @endif
                                    <a href=""
                                            data-target="#globalModal" data-toggle="modal" style="display: none"
                                            class="hidden-history-modal-btn" data-modal-size="modal-lg"></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-1"></div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-home"></i> Rooms </h3>
                <div class="box-tools" style="top: 20px">
                    Total Examinee: {{ $totalStudents }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    @foreach ($seatPlans as $seatPlan)
        @php
            $room = $physicalRooms->firstWhere('id', $seatPlan['roomId']);
            $seats = $seatPlan['seatPlan'];
            $seatNo = 1;
        @endphp
        <div class="col-sm-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title" style="line-height: 40px"><i class="fa fa-th"></i> {{$room->name}} ({{ $room->rows }}*{{ $room->cols }}*{{ $room->cadets_per_seat }} = {{ $room->rows * $room->cols * $room->cadets_per_seat }}) </h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered">
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
                                                    <div style="text-align: center" draggable="true" class="exam-seat" data-room-id="{{$room->id}}" data-seat-no="{{$seatNo}}" >
                                                        <h3 class="text-success">{{ $seatNo++ }}</h3>
                                                        <div class="student-in-seat">
                                                            <span class="seat-student-id" style="display: none">{{ $student->std_id }}</span>
                                                            <div>{{ $student->first_name }} {{ $student->last_name }}</div>
                                                            <div>{{ $student->singleBatch->batch_name }}, Form {{ $student->singleSection->section_name }}</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div style="text-align: center" draggable="true" class="exam-seat" data-room-id="{{$room->id}}" data-seat-no="{{$seatNo}}">
                                                        <h3 class="text-danger">{{ $seatNo++ }}</h3>
                                                        <div class="student-in-seat">
                                                            <span class="seat-student-id" style="display: none"></span>
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
                </div>
            </div>
        </div>     
    @endforeach
</div>
<div class="row">
    <div class="col-sm-12">
        @if ($canSave)
        <button class="btn btn-success" id="save-seat-plan-btn" style="float: right;">Save</button>
        @endif
        @if ($canPrint)
        <button class="btn btn-primary" id="print-seat-plan-btn" style="float: right; margin-right:10px;">Print</button>
        @endif
    </div>
</div>

<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.select-invigilator').select2({
            placeholder: "Choose Invigilators",
            allowClear: true
        });

        $('.invigilator-history-btn').click(function () {
            $('#globalModal').removeData('bs.modal')

            var yearId = $('#select-year').val();
            var termId = $('#select-term').val();
            var examId = $('#select-exam').val();
            var employeeIds = $(this).parent().parent().find('.select-invigilator').val();
            var hiddenModalBtn = $(this).parent().find('.hidden-history-modal-btn');

            hiddenModalBtn.attr("href", "{{ url('/academics/invigilator/history') }}"+"/"+yearId+"/"+termId+"/"+examId+"/"+JSON.stringify(employeeIds)+"");

            $(this).parent().find('.hidden-history-modal-btn').click();
        });
    });
</script>
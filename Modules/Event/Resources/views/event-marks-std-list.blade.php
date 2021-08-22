<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Students Event Marks Form </h3>
    </div>

    <div class="box-body">
        <div class="box-body table-responsive">
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <form method="POST" action="{{url('/event/save/students/event-marks')}}">
                        @csrf

                        <input type="hidden" name="eventId" value="{{$event->id}}">
                        <input type="hidden" name="dateTime" value="{{$dateTime}}">
                        <input type="hidden" name="teamId" value="{{$team->id}}">
    
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Student Info</th>
                                    <th>Performance</th>
                                    <th>Point</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    @php
                                        $previousMark = $previousMarks->where('student_id', $student->std_id)->first();
                                    @endphp
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>
                                            <div>{{$student->first_name}} {{$student->last_name}}</div>
                                        </td>
                                        <td>
                                            <select name="marks[{{$student->std_id}}]" class="form-control select-performance" required>
                                                <option value="">--Performance--</option>
                                                @foreach ($activityPoints as $activityPoint)
                                                    <option value="{{$activityPoint->point}}" {{($previousMark)?($previousMark->mark == $activityPoint->point)?'selected':'':''}}>{{$activityPoint->value}}</option>    
                                                @endforeach
                                            </select>    
                                        </td>
                                        <td class="point">{{($previousMark)?$previousMark->mark:0}}</td>
                                        <td><input type="text" name="remarks[{{$student->std_id}}]" class="form-control" placeholder="Remarks" value="{{($previousMark)?$previousMark->remarks:''}}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <div class="modal-footer">
                            <button class="btn btn-info pull-right"><i class="fa fa-upload"></i> {{($previousMark)?'Update':'Save'}}</button>
                        </div>
                    </form>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
       $('.select-performance').change(function () {
          var pointHolder = $(this).parent().parent().find('.point');
          var point = ($(this).val())?$(this).val():0;

          pointHolder.text(point);
       });
    });
</script>
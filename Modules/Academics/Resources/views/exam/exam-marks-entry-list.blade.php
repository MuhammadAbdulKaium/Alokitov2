<div class="box">
    <div class="box-body">
        <div class="box-body table-responsive">
            <div id="w1" class="grid-view">
                <form id="std_list_import_form" method="POST" action="{{url('/academics/exam/save/student/marks')}}">
                    @csrf

                    <input type="hidden" name="academicYearId" value="{{$yearId}}">
                    <input type="hidden" name="semesterId" value="{{$semesterId}}">
                    <input type="hidden" name="examId" value="{{$examId}}">
                    <input type="hidden" name="batchId" value="{{$getClass}}">
                    <input type="hidden" name="sectionId" value="{{$getSection}}">
                    <input type="hidden" name="subjectId" value="{{$getSubject}}">
                    <input type="hidden" name="subjectMarksId" value="{{$examParameter->id}}">

                    <table id="myTable" class="table table-striped table-bordered display" width="100%">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center"><a data-sort="sub_master_name">Cadet Name</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Cadet Number</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Full Marks</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Pass Marks</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Conversion</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Pass Conversion</a></th>
                            @foreach ($parameters as $parameter)
                                <th class="text-center"><a data-sort="sub_master_name">{{$parameter->name}} ({{$parameterMarks[$parameter->id]}})</a></th>
                            @endforeach
                            <th class="text-center"><a data-sort="sub_master_name">Total</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Out of ({{$examParameter->full_mark_conversion}})</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">%</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($getStudent as $stuInfo)
                            @php
                                $marks = null;
                                $myExamMark = null;
                                foreach ($examMarks as $examMark) {
                                    if ($examMark->student_id == $stuInfo->std_id) {
                                        $myExamMark = $examMark;
                                        $marks = json_decode($examMark->breakdown_mark, true);
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$stuInfo->first_name}} {{$stuInfo->last_name}}</td>
                                <td>{{$stuInfo->email}}</td>
                                <td>{{$examParameter->full_marks}}</td>
                                <td>{{$examParameter->pass_marks}}</td>
                                <td>{{$examParameter->full_mark_conversion}}</td>
                                <td>{{$examParameter->pass_mark_conversion}}</td>
                                @foreach ($parameterMarks as $key => $parameterMark)
                                    @php
                                        $attendance = null;
                                        $status = 'disabled';
                                        foreach ($examAttendances as $examAttendance) {
                                            if ($examAttendance->criteria_id == $key) {
                                                $attendance = json_decode($examAttendance->attendance);
                                            }
                                        }
                                        if ($attendance) {
                                            foreach ($attendance as $stdKey => $attStatus) {
                                                if ($stdKey == $stuInfo->std_id) {
                                                    if ($attStatus) {
                                                        $status = '';
                                                    }
                                                }
                                            }
                                        }
                                    @endphp

                                    <td><input name="marks[{{$stuInfo->std_id}}][{{$key}}]" class="form-control mark-field" type="number" min="0" max="{{$parameterMark}}" value="{{($marks)?(isset($marks[$key]))?$marks[$key]:'0':'0'}}" {{$status}}></td>
                                @endforeach
                                <td class="total-mark">{{($myExamMark)?$myExamMark->total_mark:'0'}}</td>
                                <td class="total-conversion-mark">{{($myExamMark)?$myExamMark->total_conversion_mark:'0'}}</td>
                                <td class="on-100">{{($myExamMark)?$myExamMark->on_100:'0'}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!--./modal-body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info pull-right"><i class="fa fa-upload"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
       $('.mark-field').keyup(function () {
            var parent = $(this).parent().parent();
            var markFields = parent.find('.mark-field');
            var totalMark = parent.find('.total-mark');
            var totalConversionMark = parent.find('.total-conversion-mark');
            var on100 = parent.find('.on-100');

            var totalMarkVal = 0;
            var fullMark = {!! $examParameter->full_marks !!};
            var fullMarkConversion = {!! $examParameter->full_mark_conversion !!};

            markFields.each((index, value) => {
                if($(value).val()){
                    totalMarkVal += parseInt($(value).val());
                }
            });

            var on100Val = (totalMarkVal/fullMark)*100;

            totalMark.text(totalMarkVal);
            on100.text(parseInt(on100Val));
            totalConversionMark.text(parseInt((on100Val*fullMarkConversion)/100));
       });
    });
</script>
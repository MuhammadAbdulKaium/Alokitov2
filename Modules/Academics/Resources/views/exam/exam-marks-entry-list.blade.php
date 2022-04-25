<div class="box">
    <div class="box-body">
        <div class="box-body table-responsive">
            <div id="w1" class="grid-view">
                @if ($canSave)
                <form id="std_list_import_form" method="POST" action="{{url('/academics/exam/save/student/marks')}}">
                @endif
                    @csrf

                    @if ($examList)
                        @if ($examList->publish_status == 1)
                            <div class="alert alert-warning text-center">Exam is in Approval!</div>
                        @elseif ($examList->publish_status == 2)
                            <div class="alert alert-success text-center">Exam Published!</div>
                        @endif
                    @endif
                    @if ($type == 'search' && $canSave)
                        <button type="submit" class="btn btn-success pull-right" style="margin-bottom: 10px"><i class="fa fa-upload"></i> Submit</button>
                        <button type="button" class="btn btn-info pull-right btn-danger delete-marks-btn" style="margin-bottom: 10px ;margin-right: 4px"><i class="fa fa-trash"></i> Remove Marks</button>
                        <button type="button"  id="blank-button" class="btn btn-info pull-right btn-warning " style="margin-bottom: 10px ;margin-right: 4px"><i class="fa fa-exclamation-triangle"></i> All Blank</button>
                    @endif
                    <input type="hidden" name="academicYearId" value="{{$yearId}}">
                    <input type="hidden" name="semesterId" value="{{$semesterId}}">
                    <input type="hidden" name="examId" value="{{$examId}}">
                    <input type="hidden" name="batchId" value="{{$getClass}}">
                    <input type="hidden" name="sectionId" value="{{$getSection}}">
                    <input type="hidden" name="subjectId" value="{{$getSubject}}">
                    <input type="hidden" name="subjectMarksId" value="{{$examParameter->id}}">

                    <table id="myTable" class="table table-bordered display" width="100%">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">All <input type="checkbox" class="check-all-stu"></th>
                            <th class="text-center"><a data-sort="sub_master_name">Cadet Photo</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Cadet Info</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Full Marks</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Pass Marks</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Conversion</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Pass Conversion</a></th>
                            @foreach ($parameters as $parameter)
                                <th class="text-center "><a data-sort="sub_master_name">{{$parameter->name}} ({{$parameterMarks[$parameter->id]}})</a></th>
                            @endforeach
                            <th class="text-center"><a data-sort="sub_master_name">Total</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Out of ({{$examParameter->full_mark_conversion}})</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">%</a></th>
                            <th class="text-center"><a data-sort="sub_master_name">Grade</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($getStudent as $stuInfo)
                            @php
                                $myExamMark = null;
                                $marks = null;
                                if (isset($examMarks[$stuInfo->std_id])) {
                                    $myExamMark = $examMarks[$stuInfo->std_id];
                                    $marks = json_decode($examMarks[$stuInfo->std_id]->breakdown_mark, true);
                                }
                                $isFail = false;
                            @endphp
                            <tr @if($myExamMark) class="bg-info" @endif>
                                <td>{{$loop->index+1}}</td>
                                <td><input type="checkbox" class="check-stu" name="stuChecks[]" value="{{ $stuInfo->std_id }}"></td>
                                <td>
                                    @if($stuInfo->singelAttachment("PROFILE_PHOTO"))
                                        <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$stuInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">
                                    @else
                                        <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px;height:50px">
                                    @endif
                                </td>
                                <td>
                                    {{$stuInfo->first_name}} {{$stuInfo->last_name}} <br>
                                    <b>ID: </b>{{$stuInfo->singleUser->username}}
                                </td>
                                <td>{{$examParameter->full_marks}}</td>
                                <td>{{$examParameter->pass_marks}}</td>
                                <td>{{$examParameter->full_mark_conversion}}</td>
                                <td>{{$examParameter->pass_mark_conversion}}</td>
                                @foreach ($parameterMarks as $key => $parameterMark)
                                    @php
                                        if (isset($marks[$key]) && isset($parameterPassMarks[$key])) {
                                            if ($marks[$key]) {
                                                if ($parameterPassMarks[$key] > $marks[$key]) {
                                                    $isFail = true;
                                                }
                                            }
                                        }
                                    @endphp

                                    <td>
                                        @if ($type == 'search')
                                            <input name="marks[{{$stuInfo->std_id}}][{{$key}}]" class="form-control mark-field " type="number" step="any" min="0" max="{{$parameterMark}}" value="{{($marks)?(isset($marks[$key]))?$marks[$key]:'':''}}">
                                        @elseif($type == 'view')
                                            {{($marks)?(isset($marks[$key]))?$marks[$key]:'':''}}
                                        @endif
                                    </td>
                                @endforeach
                                @php
                                    if ($grades) {
                                        if($isFail){
                                            $grade = grade($grades, '0');
                                        } else {
                                            $grade = (($myExamMark)?($myExamMark->on_100!==null)?grade($grades, $myExamMark->on_100):'':'');
                                        }
                                    } else {
                                        $grade = "";
                                    }
                                @endphp 
                                <td class="total-mark">{{($myExamMark)?($myExamMark->total_mark!==null)?round($myExamMark->total_mark, 2):'':''}}</td>
                                <td class="total-conversion-mark">{{($myExamMark)?($myExamMark->total_conversion_mark!==null)?round($myExamMark->total_conversion_mark, 2):'':''}}</td>
                                <td class="on-100">{{($myExamMark)?($myExamMark->on_100!==null)?round($myExamMark->on_100, 2):'':''}}</td>
                                <td>{{ ($myExamMark)?$grade:'' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!--./modal-body-->
                    <div class="modal-footer">
                        @if ($type == 'search' && $canSave)
                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-upload"></i> Submit</button>
                            <button type="button" class="btn btn-danger pull-right delete-marks-btn" style="margin-right: 4px"><i class="fa fa-trash"></i> Remove Marks</button>
                        @endif
                    </div>
                @if ($canSave) 
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        $('#blank-button').on('click',()=>{

            $('.mark-field').attr('value', '');
            $('.mark-field').keyup();

        });

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
                    totalMarkVal += parseFloat($(value).val());
                }
            });

            var on100Val = (totalMarkVal/fullMark)*100;

            totalMark.text(totalMarkVal.toFixed(2));
            on100.text(parseFloat(on100Val).toFixed(2));
            totalConversionMark.text(parseFloat((on100Val*fullMarkConversion)/100).toFixed(2));
       });

        $('.check-all-stu').click(function () {
            if ($(this).is(':checked')) {
                $('.check-stu').prop('checked', true);
            } else{
                $('.check-stu').prop('checked', false);
            }
        });
    });
</script>
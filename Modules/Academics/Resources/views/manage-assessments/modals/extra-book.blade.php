<h4 class="text-center bg-green">Class - Section (Semester) Extra Book</h4>
<style>
    .input-group {
        min-width: 200px;
    }
</style>
@if(!(empty($gradeScale)))
    @if(!empty($studentList) AND count($studentList)>0)
        @if(!empty($semesterAttendanceSheet) AND $semesterAttendanceSheet AND $semesterAttendanceSheet->status=='success')
            {{--student attendance details--}}
            @php
                $attendanceList = $semesterAttendanceSheet->attendance_list;
                $holidayList = $semesterAttendanceSheet->holiday_list;
                $weekOffDayList = $semesterAttendanceSheet->week_off_day_list;
                $totalAttendanceDay = $semesterAttendanceSheet->total_attendance_day;
                // total working day counting
                $totalWorkingDay = ($totalAttendanceDay-(count($holidayList)+count($weekOffDayList)))
            @endphp

            <div class="col-md-12">
                <form id="extra_book_setup_form" >
                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>

                    <table width="100%" class="table table-bordered table-striped text-center table-responsible" style="overflow: scroll; display: block; table-layout: fixed;">
                        <thead>
                        <tr class="bg-info text-center">
                            {{--student Name and id th--}}
                            <th>
                                <p class="bg-gray-active">#</p>
                                <table cellpadding="10">
                                    <tbody>
                                    <tr>
                                        {{--student id--}}
                                        <td width="10%">
                                            <input type="text" readonly class="form-control text-center" value="Roll">
                                        </td>
                                        {{--student Name--}}
                                        <td>
                                            <input type="text" readonly class="form-control text-center input-group" value=" Student Name">
                                        </td>
                                        {{--student school present percentage--}}
                                        <td width="25%">
                                            <input type="text" readonly class="form-control text-center" value="School Present (%)">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </th>

                            @if($gradeScale->assessmentsCount()>0)
                                @if($gradeScale->assessmentCategory())
                                    @foreach($gradeScale->assessmentCategory() as $category)
                                        {{-- Category All Asssessments list--}}
                                        @php $assessmentList = $category->allAssessment($gradeScale->id); @endphp
                                        {{--checking--}}
                                        @if($assessmentList->count()>0)
                                            {{--category weighted average mark--}}
                                            @php $categoryWAMark = array_key_exists($category->id, $weightedAverageArrayList)==true?$weightedAverageArrayList[$category->id]:0; @endphp
                                            {{--checking $categoryWAMark--}}
                                            @if($categoryWAMark==0)
                                                <th>
                                                    {{--category Name--}}
                                                    <p class="bg-gray-active"> {{$category->name}} </p>
                                                    {{--cat id--}}
                                                    <input type="hidden" name="category[cat_id]" value="{{$category->id}}"/>
                                                    <input type="hidden" name="category[cat_name]" value="{{$category->name}}"/>
                                                    {{--assessment list table--}}
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            @foreach($assessmentList as $assessment)
                                                                <td>
                                                                    <input type="text" readonly class="form-control text-center input-group" value="{{$assessment->name}} ({{$assessment->points}})">
                                                                    <input type="hidden" name="category[cat_ass_list][{{$assessment->id}}]" value="{{$assessment->name}}"/>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </th>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                        </tr>
                        </thead>

                        {{--table body--}}
                        <tbody id="extra_book_setup_table_body">
                        @foreach($studentList as $studentProfile)
                            @php $studentProfile = (object)$studentProfile; @endphp
                            <tr>

                                <th>
                                    <table cellpadding="10">
                                        <tbody>
                                        <tr>
                                            {{--student id--}}
                                            <td width="10%">
                                                <input type="text" readonly class="form-control text-center" value="{{$studentProfile->gr_no}}">
                                            </td>
                                            {{--student Name--}}
                                            <td>
                                                <input type="text" readonly class="form-control text-center input-group" value=" {{$studentProfile->name}}">
                                            </td>
                                            {{--find single student attendance information--}}
                                            @php
                                                // $precision
                                                $precision = 2;
                                                // checking
                                                if(array_key_exists($studentProfile->id, $attendanceList)){
                                                    $myAttendanceInfo = (object)$attendanceList[$studentProfile->id];
                                                    // present and absent days
                                                    $myPresentDays = $myAttendanceInfo->present;
                                                    $myAbsentDays = $myAttendanceInfo->absent;
                                                    // my attendance percentage
                                                    $myAttendancePercentage = floatval(($myPresentDays/$totalWorkingDay)*100);
                                                }else{
                                                    $myAttendanceInfo = null;
                                                }
                                            @endphp
                                            {{--student attendane information--}}
                                            <td width="25%">
                                                <input type="text" readonly class="form-control text-center" value="{{$myAttendanceInfo?(substr(number_format($myAttendancePercentage, $precision + 1, '.', ''), 0, -1)).' %':'-'}}">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </th>

                                {{--checking student extra book list--}}
                                @php $extraBookProfile = array_key_exists($studentProfile->id,$stdExtraBookArrayList)?$stdExtraBookArrayList[$studentProfile->id]:null @endphp

                                {{--assessment list looping--}}
                                @if($gradeScale->assessmentsCount()>0)
                                    @if($gradeScale->assessmentCategory())
                                        @foreach($gradeScale->assessmentCategory() as $category)
                                            {{-- Category All Asssessments list--}}
                                            @php $assessmentList = $category->allAssessment($gradeScale->id); @endphp
                                            {{--checking--}}
                                            @if($assessmentList->count()>0)
                                                @php $categoryWAMark = array_key_exists($category->id, $weightedAverageArrayList)==true?$weightedAverageArrayList[$category->id]:0; @endphp
                                                {{--checking $categoryWAMark--}}
                                                @if($categoryWAMark==0)
                                                    {{--checking student extra book list--}}
                                                    @php $extraMarkAssList = $extraBookProfile?$extraBookProfile['mark_list']:[] @endphp
                                                    <input type="hidden" name="assessment[{{$studentProfile->id}}][mark_id]" value="{{$extraBookProfile?$extraBookProfile['mark_id']:0}}"/>
                                                    <th>
                                                        <table>
                                                            <tbody>
                                                            <tr>
                                                                @foreach($assessmentList as $assessment)
                                                                    {{--assessment mark--}}
                                                                    @php
                                                                        $assessmentPoints = $assessment->points;
                                                                        $assessmentMarks = array_key_exists($assessment->id, $extraMarkAssList)?$extraMarkAssList[$assessment->id]:null;
                                                                    @endphp
                                                                    <td>
                                                                        <input type="text" data-key="{{$assessmentPoints}}" class="form-control input-group mark-input text-center" name="assessment[{{$studentProfile->id}}][mark_list][{{$assessment->id}}]" maxlength="3" value="{{($assessmentMarks?$assessmentMarks:'')}}" required>
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </th>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                    <div class="modal-footer">
                        {{--checking exam status--}}
                        @if($examStatus AND $examStatus->status==0)
                            <button id="extra_book_setup_table_reset_btn" type="reset" class="btn btn-info pull-left">Reset</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        @else
                            <button type="button" class="btn btn-default">Exam Result Published</button>
                        @endif
                    </div>
                </form>
            </div>
        @else
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div id="w0-success-0" class="alert-warning alert-auto-hide text-center alert fade in" style="opacity: 374.435;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-warning"></i> {{$semesterAttendanceSheet->msg}}</h5>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div id="w0-success-0" class="alert-warning alert-auto-hide text-center alert fade in" style="opacity: 374.435;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="fa fa-warning"></i> No Student found.</h5>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div id="w0-success-0" class="alert-warning alert-auto-hide text-center alert fade in" style="opacity: 374.435;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="fa fa-warning"></i> There is no <b>Grade Scale</b> for this <b>Batch</b> and <b>Section</b></h5>
            </div>
        </div>
    </div>
@endif




<script type="text/javascript">

    $(document).ready(function () {

        // request for section list using batch and section id
        $('form#extra_book_setup_form').on('submit', function (e) {
            // alert('hello');
            e.preventDefault();
            // append academics details
            var extra_book_setup_form = $(this)
                .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                .append('<input type="hidden" name="section" value="'+$('#section').val()+'"/>')
                .append('<input type="hidden" name="subject" value="'+$('#subject').val()+'"/>')
                .append('<input type="hidden" name="semester" value="'+$('#semester').val()+'"/>');
            var dataJson = $(extra_book_setup_form).serializeArray();
            //alert(dataJson.length);
            dataJson = objectifyForm(dataJson);

            // ajax request
            $.ajax({
                url: "{{ url('/academics/manage/assessments/extra-book/store') }}",
                type: 'POST',
                cache: false,
                data: dataJson,
                datatype: 'html',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();

                    // assessment_table_row
                    var assessment_table_row = $('#assessment_table_row');
                    // checking
                    if(data.status=='success'){
                        // sweet alert success
                        swal("Success", data.msg, "success");
                    }else{
                        // sweet alert warning
                        swal("Warning", data.msg, "warning");
                    }
                },

                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // sweet alert error
                    swal("Error", 'No data response from the server', "error");
                }
            });
        });


        // reset btn action
        $('#extra_book_setup_table_reset_btn').click(function () {
            // checking
            if(confirm('Are you sure to reset the Extra Book ???')){
                // replace input values
                $("#extra_book_setup_table_body input.mark-input").attr('value', '');
            }
        });

        // replace input values
        $("#extra_book_setup_table_body input.mark-input").keyup(function(){
            $(this).attr('value', $(this).val());

            var my_grade_mark = parseFloat($(this).val());
            // ass marks
            var ass_points = $(this).attr('data-key');
            // checking
            if(my_grade_mark && ass_points && $.isNumeric($(this).val()) && parseFloat(ass_points)>0){
                // checking
                if((my_grade_mark>ass_points) || (my_grade_mark<0) || (ass_points==0)){
                    // sweet alert
                    swal("Warning", "Mark Should be 0 - "+ass_points, "warning");
                    // reset mark
                    $(this).val('');
                    // reset mark
                    $(this).attr('value', '');
                    // background-color
                    $(this).css("background-color", "pink");
                }else{
                    // set value
                    $(this).attr('value', $(this).val());
                    // background-color
                    $(this).css("background-color", "white");
                }
            }else{
                // chekcing
                if($.isNumeric($(this).val()) && parseFloat(ass_points)>0){
                    // set value
                    $(this).attr('value', $(this).val());
                    // background-color
                    $(this).css("background-color", "white");
                }else{
                    // reset mark
                    $(this).val('');
                    // reset mark
                    $(this).attr('value', '');
                    // background-color
                    $(this).css("background-color", "pink");
                }
            }
        });


        /**
         * This function takes serializeArray output and jquery and make it javascript JSON object
         */
        function objectifyForm(formArray) {//serialize data function

            var returnArray = {};
            returnArray['assessment'] = {};

            for (var i = 0; i < formArray.length; i++){
                var attr_name = formArray[i]['name'];
                if(attr_name.indexOf("assessment[") >= 0){
                    attr_name = attr_name.replace("assessment[","");
                    returnArray['assessment'][attr_name] = formArray[i]['value'];
                }else{
                    returnArray[attr_name] = formArray[i]['value'];
                }


            }
            return returnArray;
        }


    });

</script>


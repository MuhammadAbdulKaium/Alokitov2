<p class="text-center bg-green text-bold">Student Information</p>
<style>
    .input-group {
        min-width: 130px;
        width: 100%;
    }
</style>
<div class="row">
    <div class="panel-body">
        <div class="col-md-2 text-center">
            @php $photo = $studentInfo->singelAttachment("PROFILE_PHOTO") @endphp
            @if($photo)
                <img class="center-block img-thumbnail img-circle img-responsive" src="{{URL::asset('assets/users/images/'.$photo->singleContent()->name)}}" alt="No Image" style="width:100px;height:100px">
            @else
                <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:100px;height:100px">
            @endif
        </div>
        <div class="col-md-10">
            <table class="table table-bordered table-striped text-center table-responsive">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roll NO.</th>
                    <th>Section</th>
                    <th>Class</th>
                    <th>Gender</th>
                    <th>Birth Date</th>
                    <th>Blood Group</th>
                </tr>
                </thead>

                <thead>
                <tr>
                    <td>{{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</td>
                    <td>{{$studentInfo->email}}</td>
                    @php
                        $enrollment = $studentInfo->singleEnroll();
                        $level  = $enrollment->level();
                        $batch  = $enrollment->batch();
                        $section  = $enrollment->section();
                    @endphp
                    <td>{{$enrollment->gr_no}}</td>
                    <td>{{$section->section_name}}</td>
                    <td>{{$batch->batch_name}}@if($division = $batch->get_division())  ({{$division->name}})@endif</td>
                    <td>{{$studentInfo->gender}}</td>
                    <td>{{ date('d M, Y', strtotime($studentInfo->dob)) }}</td>
                    <td>{{$studentInfo->blood_group}}</td>

                    {{--input student academics details--}}
                    @php
                        $levelId = $level->id;
                        $batchId = $batch->id;
                        $sectionId = $section->id;
                    @endphp
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<p class="text-center text-bold bg-green" >Student Report Card</p>
{{--assesssement details array list--}}
@php
    $assessmentLoop = 0;
    $assessmentInfo = array();
@endphp
@if(count($semesterResultSheet)>0 && count($allSemester)>0)
    <div class="col-md-12">

        <p class="text-right">
            <a id="download_std_report_card" href="{{url('/academics/manage/assessments/report-card/download/'.$studentInfo->id)}}" class="btn btn-success" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
                <i class="fa fa-plus-square" aria-hidden="true"></i> Download
            </a>
        </p>

        <form style="overflow: scroll">
            @for($i=0; $i<count($allSemester); $i++)
                @if(array_key_exists($allSemester[$i]['id'], $semesterResultSheet)==true)
                    @php
                        $semesterId = $allSemester[$i]['id'];
                        $assessmentInfo['ass_cat_result_count'][$semesterId][] = null;
                        $stdSubjectGrades = $semesterResultSheet[$allSemester[$i]['id']];
                    @endphp
                    @if($stdSubjectGrades == null) @continue @endif
                    <p class="bg-gray-active text-center text-bold">{{$allSemester[$i]['name']}} <br/>  ({{date('d M, Y', strtotime($allSemester[$i]['start_date']))." - ".date('d M, Y', strtotime($allSemester[$i]['end_date']))}}) </p>
                    <table id="grade_book_setup_table" class="table table-bordered table-striped text-center">
                        <thead id="grade_book_setup_table_head">
                        <tr class="bg-info text-center">
                            {{--student Name and id th--}}
                            <th width="200px">
                                <table class="table-bordered" cellpadding="10">
                                    <tbody>
                                    <tr>
                                        {{--Subject Name--}}
                                        <td>
                                            <p class="bg-gray-active">#</p>
                                            <div class="input-group">
                                                <input style="width: 200px" type="text" readonly class="form-control text-center" value="Subject">
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </th>
                            @if($gradeScale->assessmentsCount()>0)
                                @if($gradeScale->assessmentCategory() AND $gradeScale->assessmentCategory()->count()>0)
                                    @foreach($gradeScale->assessmentCategory() as $category)
                                        {{--$categoryWAMark--}}
                                        @php $categoryWAMark = array_key_exists($category->id, $weightedAverageArrayList)==true?$weightedAverageArrayList[$category->id]:0; @endphp
                                        {{--checking $categoryWAMark--}}
                                        @if($categoryWAMark>0)
                                            {{-- Category All Asssessments list--}}
                                            @php $assessmentList = $category->allAssessment($gradeScale->id); @endphp

                                            {{--checking--}}
                                            @if($assessmentList->count()>0)

                                                {{--category result count--}}
                                                @php $resultCount = $category->resultCount($batchId, $sectionId, $semesterId); @endphp
                                                <th>
                                                    {{--category Name--}}
                                                    {{--{{$category->name}}--}}
                                                    <p class="bg-gray-active">{{$category->name}} ({{$categoryWAMark}})</p>
                                                    <input id="cate_{{$category->id}}" type="hidden" value="{{$categoryWAMark}}">

                                                    @if($assessmentLoop==0)
                                                        @php $assessmentInfo['ass_cat_list'][]= $category->id;@endphp
                                                    @endif
                                                    <table width="100%" class="table-bordered" cellpadding="10">
                                                        <tbody>
                                                        <tr>
                                                            @foreach($assessmentList as $assessment)
                                                                {{--find assessment points--}}
                                                                @php $myAssessmentPoints = array_key_exists($assessment->id, $subjectAssessmentArrayList)==true?$subjectAssessmentArrayList[$assessment->id]:0;  @endphp
                                                                @if($assessmentLoop==0)
                                                                    @php $assessmentInfo['ass_list'][$category->id][]= $assessment->id; @endphp
                                                                    {{--@php $assessmentInfo['point_list'][$assessment->id]= $assessment->points; @endphp--}}
                                                                    @php $assessmentInfo['point_list'][$assessment->id]= $myAssessmentPoints; @endphp
                                                                @endif
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="text" readonly class="form-control text-center" value="{{$assessment->name}}">
                                                                    </div>
                                                                </td>
                                                            @endforeach

                                                            {{--checking result count for best result average--}}
                                                            @if($resultCount AND $resultCount->result_count>0)
                                                                {{--<td style="width: 150px">--}}
                                                                {{--<div class="input-group">--}}
                                                                {{--<input type="text" readonly class="form-control text-center" value="A/V">--}}
                                                                {{--</div>--}}
                                                                {{--</td>--}}
                                                                {{--input result count with category id--}}
                                                                @php $assessmentInfo['ass_cat_result_count'][$semesterId][$category->id]= $resultCount->result_count;@endphp
                                                            @endif

                                                            {{--Weighted Avderage--}}
                                                            {{--<td width="350px">--}}
                                                            {{--<div class="input-group">--}}
                                                            {{--<input type="text" readonly class="form-control text-center" value="W/A">--}}
                                                            {{--</div>--}}
                                                            {{--</td>--}}
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </th>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                            <th width="80px">
                                <p class="bg-gray-active">Result</p>
                                <table class="table-bordered" cellpadding="10">
                                    <tbody>
                                    <tr>
                                        {{--Subject Name--}}
                                        <td>
                                            <div class="input-group">
                                                <input type="text" readonly class="form-control text-center" value="%">
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="report_card_table_body" id="report_card_table_body_{{$allSemester[$i]['id']}}"> </tbody>
                    </table>
                @endif
                @php $assessmentLoop +=1; @endphp
            @endfor
        </form>
    </div>
@else
    <div class="col-md-12">
        <div  class="alert-warning alert-auto-hide alert fade in text-center" style="opacity: 423.642;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="fa fa-warning"></i> No records found
        </div>
    </div>
@endif
<script type="text/javascript">

    $(function(){

        var class_subjects_list = JSON.parse('<?php echo json_encode($classSubjects);?>');
        var assessment_info = JSON.parse('<?php echo json_encode($assessmentInfo);?>');
        var semester_list = JSON.parse('<?php echo json_encode($allSemester);?>');
        var semester_result_sheet = JSON.parse('<?php echo json_encode((array)$semesterResultSheet);?>');
        // checking semester length
        if(semester_list.length >0  ){
            $('.report_card_table_body').html('');
            // semester looping
            for(var kk=0; kk<semester_list.length; kk++){
                // semester id
                var semester_id = semester_list[kk]['id'];
                // subject grades
                var subject_grades = semester_result_sheet[semester_id];
                // load grade details
                load_grade_book(class_subjects_list, subject_grades,semester_id);
            }
        }

        // load grade book
        function load_grade_book(class_subjects_list, data, semester_id) {

            // my custom assessment category list
            var custom_ass_cat_list = assessment_info['ass_cat_list'];
            // ass_cat_result_count
            var ass_cat_result_count = assessment_info['ass_cat_result_count'];

            // receive grade result from the dat
            var grade = data['grade'];
            $('#report_card_table_body_'+semester_id).html('');

            // result looping
            for(var i=0; i<class_subjects_list.length; i++){
                // single class subject
                var cs_profile = class_subjects_list[i];
                var c_subject_id = cs_profile.cs_id;
                // checking class subject
                if(grade[c_subject_id]== undefined) continue;
                // find class subject grade list
                var std_grade = grade[c_subject_id];
                var grade_id = std_grade['grade_id'];
                var cs_id = std_grade['cs_id']; // std_is is cs_id
                var sub_name = std_grade['sub_name']; // std_name is cs_name
                var mark_id = std_grade['mark_id'];
                var mark = std_grade['mark'];
                var credit = std_grade['credit'];
                var total = std_grade['total'];
                var obtained = std_grade['obtained'];
                var percentage = std_grade['percentage'];
                var letterGrade = std_grade['letterGrade'];
                var cat_count = mark['cat_count'];
                var cat_list =mark['cat_list'];
                var tr = '<tr id="row_'+cs_id+'">';
                // tabe row data
                tr += '<td><table class="table-bordered" cellpadding="10"><tbody><tr><td><div class="input-group text-center"><input style="min-width: 200px" type="text" readonly  value="'+sub_name+'" class="form-control"></div></td></tr></tbody></table></td>';

                // category looping
                for(x=0; x<custom_ass_cat_list.length; x++){
                    // ass list for best result count
                    var assessmentMarksList = {};

                    var cat_id = custom_ass_cat_list[x];
                    var custom_ass_list = assessment_info['ass_list'][cat_id];
                    var ass_count = custom_ass_list.length;

                    var td = '<td><input type="hidden" name="grade[std_'+std_id+'][mark][cat_list][cat_'+x+']" value="'+cat_id+'"/><input type="hidden" name="grade[std_'+std_id+'][mark][cat_'+cat_id+'][cat_id]" value="'+cat_id+'"/><input type="hidden" name="grade[std_'+std_id+'][mark][cat_'+cat_id+'][ass_count]" value="'+ass_count+'"/><table width="100%" class="table-bordered" cellpadding="10"><tbody><tr>';

                    // checking
                    if(mark['cat_'+cat_id] != undefined){
                        var category = mark['cat_'+cat_id];
                        var ass_list = category['ass_list'];
                        //  total assessment mark
                        var total_ass_mark = 0;
                        // total assessment points
                        var total_ass_points = 0;
                        // total assessment points
                        var total_ass_count = 0;

                        // assessment looping
                        for(var k=1; k<=ass_count; k++){
                            // my assessment id
                            var myAssId = 'ass_'+k;
                            // new data for best result count
                            var new_data = {};

                            // checking
                            if(ass_list[myAssId] != undefined){
                                var ass_id = ass_list[myAssId];
                                var assessment = category['ass_'+ass_id];
                                var ass_mark = assessment['ass_mark'];
                                var ass_points = parseFloat(assessment['ass_points']);
                                var ass_mark_percentage = parseFloat((ass_mark/ass_points)*100);
                                if(ass_mark == null){ass_mark = '';}

                                // checking ass_points
                                if(ass_points>0){

                                    // new data for best result count
                                    new_data[ass_id] = ass_mark_percentage;
                                    $.extend(true,assessmentMarksList, new_data);

                                    total_ass_mark+=parseFloat(ass_mark);
                                    total_ass_points+=parseFloat(ass_points);

                                    // new data for best result count
                                    new_data[ass_id] = ass_mark_percentage;
                                    $.extend(true,assessmentMarksList, new_data);

                                    // total_ass_count
                                    total_ass_count+=1;


                                    td += '<td><div class="input-group text-center"><input type="text" readonly value="'+ass_mark+' / '+ass_points+'" class="form-control text-center mark-input"></div></td>';
                                }else{
                                    td += '<td><div class="input-group text-center"><input type="text" readonly value="-" class="form-control text-center mark-input"></div></td>';
                                }
                            }else{
                                // assessment point
                                var my_ass_mark = 0.00;
                                // assessment point
                                var my_ass_points = parseFloat(assessment_info['point_list'][myAssId]);
                                // checking ass_points
                                if(my_ass_points>0){

                                    total_ass_mark+=my_ass_mark;
                                    total_ass_points+=parseFloat(my_ass_points);

                                    // total_ass_count
                                    total_ass_count+=1;
                                    // new data for best result count
                                    new_data[k] = my_ass_mark;
                                    $.extend(true,assessmentMarksList, new_data);

                                    td += '<td><div class="input-group text-center"><input type="text" readonly value="/ '+my_ass_points+'" class="form-control text-center mark-input"></div></td>';
                                }else{
                                    td += '<td><div class="input-group text-center"><input type="text" readonly value="-" class="form-control text-center mark-input"></div></td>';
                                }
                            }
                        }

                    }else{
//                        // custom assessment loop (for new assessment adding)
//                        for(var zz=0; zz<ass_count; zz++){
//                            var custom_ass_id = custom_ass_list[zz];
//                            var custom_ass_points = parseFloat(assessment_info['point_list'][custom_ass_id]);
//                            // checking custom_ass_points
//                            if(custom_ass_points>0){
//
//                                td += '<td style="width: 300px"><div class="input-group text-center"><input type="text" readonly value=" " class="form-control text-center mark-input"><div class="input-group-addon"> / '+custom_ass_points+'</div></div></td>';
//                            }else{
//                                td += '<td style="width: 300px"><div class="input-group text-center"><input type="text" readonly value="-" class="form-control text-center mark-input"></div></td>';
//                            }
//
//                        }
                    }


                    // category weighted average calculation
                    var category_assigned_mark = null;
                    var category_average_mark = null;
                    var category_weighted_average = null;


//                    // checking result count for best result average
//                    if(ass_cat_result_count[semester_id][cat_id] != undefined){
//                        // ass_count
//                        var my_ass_cat_result = ass_cat_result_count[semester_id][cat_id];
//                        // ass_cat_result_count
//                        var my_ass_cat_result_count = (my_ass_cat_result<=total_ass_count?my_ass_cat_result:total_ass_count);
//
//                        // marks list
//                        var my_ass_marks_list = [];
//                        for (var t in assessmentMarksList){
//                            if (assessmentMarksList.hasOwnProperty(t)) {
//                                // alert("Key is " + t + ", value is" + assessmentMarksList[t]);
//                                my_ass_marks_list.push(parseInt(assessmentMarksList[t]));
//                            }
//                        }
//                        //Sort numerically and ascending:
//                        my_ass_marks_list =  my_ass_marks_list.sort(function(a,b){return a - b}).reverse();
//
//                        var weightedMarks = $('#cate_'+cat_id).val();
//                        var assTotalMarks = (100*my_ass_cat_result_count);
//                        var assTotalMarksObtained = null;
//
//                        // now looping for final calculation
//                        for(var mm=0; mm<my_ass_cat_result_count; mm++){
//                            assTotalMarksObtained += my_ass_marks_list[mm];
//                        }
//
//                        // final calculation
//                        var finalAssTotalMarks = parseFloat((assTotalMarksObtained/assTotalMarks)*weightedMarks).toFixed(2);
//
//                        // average td
////                        td +=  '<td style="width: 250px">' +
////                            '<div class="input-group text-center">' +
////                            '<input type="text" readonly value="'+finalAssTotalMarks+'" class="form-control text-center mark-input">' +
////                            '<input type="hidden" value="'+JSON.stringify(my_ass_marks_list)+'">' +
////                            '</div>' +
////                            '</td>';
//
//                        category_assigned_mark = weightedMarks;
//                        // category_average_mark = (parseFloat(finalAssTotalMarks)*100)/parseFloat(total_ass_points);
//                        //category_weighted_average = parseFloat((category_average_mark*category_assigned_mark)/100).toFixed(2);
//                        category_weighted_average = finalAssTotalMarks;
//
//                        // calculate percentage
//                        percentage += parseFloat(category_weighted_average);
//
//                    }else{
//
//                        category_assigned_mark = $('#cate_'+cat_id).val();
//                        category_average_mark = (parseFloat(total_ass_mark)*100)/parseFloat(total_ass_points);
//                        category_weighted_average = parseFloat((category_average_mark*category_assigned_mark)/100).toFixed(2);
//
//                        // calculate percentage
//                        percentage += parseFloat(category_weighted_average);
//                        // weighted average
////                        td += '<td><div class="input-group text-center"><input type="text" readonly value="'+category_weighted_average+' / '+category_assigned_mark+'" class="form-control text-center mark-input"></div></td>';
//                    }

                    td +='</tr></tbody></table></td>';
                    //
                    tr += td;
                }
//                tr += '<td width="250px"><table class="table-bordered" cellpadding="10"><tbody><tr> <td><div class="input-group"><input type="text" readonly class="form-control text-center" value="'+percentage+'"><div class="input-group-addon">%</div></div></td> <td><div class="input-group"><input type="text" readonly class="form-control text-center" value="'+letterGrade+'"></td>  </tr></tbody></table></td></tr>';

                tr += '<td><input type="text" readonly class="form-control text-center" value="'+percentage+' %"></td></tr>';

                // append table row to the table body
                $('#report_card_table_body_'+semester_id).append(tr);
            }
        }

    });

</script>


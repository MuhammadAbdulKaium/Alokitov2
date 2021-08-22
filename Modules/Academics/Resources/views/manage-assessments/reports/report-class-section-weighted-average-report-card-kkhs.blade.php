<!DOCTYPE html>
<html>
<head>
    <title>Report Car</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
    <style type="text/css">
        @media screen {
            body {
                font-family: arial;
                font-size: 12px
            }
            .fix {
                overflow: hidden
            }
            .page-border {
                border: 5px solid #812F00;
                margin: 0 auto;
                width: 80%;
            }
            .page-border-2 {
                border: 5px solid #FFCE44;
            }
            .page-border-3 {
                border: 5px solid #000;
                padding: 5px 10px;
                border-style: double;
            }
            .head_column {
                width: 33%;
                float: left;
                text-align: center
            }
            .head_column img {
                height: 100px;
                margin: 10px 0px
            }
            .marksheets h2 {
                text-align: center;
                font-size: 22px;
                margin: 0px;
                margin-top: 10px;
                font-family: arial;
                padding-bottom: 10px
            }
            .marksheets h3 {
                text-align: center;
                font-size: 16px;
                margin: 0px;
                border-bottom: 1px solid #0D9E3D;
                font-family: arial;
            }
            .head_column table tr th,
            td {
                border: 1px solid;
                text-align: center;
                font-size: 10px
            }
            .head_column table th {
                background: #FFF7E6;
                font-family: arial;
            }
            .grade_table table tr td {
                font-family: arial;
                font-size: 8px
            }
            .head_column table {
                width: 60%;
                float: right
            }
            .clear {
                clear: both;
            }
            ul{
                list-style: outside none none;
                padding: 0px;
                margin: 0px;
            }
            .name_address_left ul li span.a {
                width: 120px;
                display: inline-block;
                font-family: arial;
                padding: 0px;
            }
            .name_address_left ul li span.b {
                width: 20px;
                display: inline-block;
                font-family: arial;
            }
            .name_address_right ul li span.a {
                width: 70px;
                display: inline-block;
                font-family: arial;
            }
            .name_address_right ul li span.b {
                width: 20px;
                display: inline-block;
                font-family: arial;
            }
            .name_section {
                margin-top: 10px
            }
            .main_body {
                margin-top: 10px;
            }
            .main_body table {
                width: 100%
            }
            .main_body th {
                font-weight: bold;
                background: #EDFAFA;
                border: 1px solid;
                font-size: 14px;
                text-align: center
            }
            .total td {
                font-weight: bold;
                color: green;
                background: #F5F5DC;
                font-size: 14px;
            }
            .head_column table tr th,
            td {
                font-size: 12px
            }
            .body_second {
                margin-top: 0px
            }
            .left_side {
                width: 80%;
                float: left
            }
            .right_side {
                width: 20%;
                float: left
            }
            .left_table {
                width: 93%;
                float: left;
                margin-right: 10px;
                margin-top: 5px;
            }
            .left_table th {
                font-weight: bold;
                background: #EDFAFA;
                border: 1px solid;
                font-size: 14px;
                text-align: center;
            }
            .right_side img {
                height: 150px;
                margin-top: 3px
            }
            .comment {
                text-align: left;
                font-weight: bold;
                font-size: 18px;
                border: 1px solid;
                margin-top: 5px;
                width: 93%;
                padding: 5px 5px;
            }
            .signature {
                width: 100%;
                margin-top: 300px
            }
            .sign {
                width: 15%;
                float: left;
                margin-right: 22%;
                text-align: center;
                display: block
            }
            .signs {
                width: 15%;
                float: left;
                text-align: center;
                display: block
            }
            .sig {
                border-top: 1px dashed;
            }
            .sg {
                height: 60px;
                width: 100%
            }
            .sg img {
                width: 100px;
            }
        }

        @media only screen and (max-width: 767px) {
            .left_side {
                width: 100%;
            }
            .right_side {
                width: 100%;
            }
            .chart {
                display: none
            }
            .left_table {
                width: 100%;
            }
            .left_table tbody {
                display: block;
                overflow: auto;
            }
            .signature {
                display: none;
            }
        }

        @media only screen and (min-width: 480px) and (max-width: 767px) {
            .left_side {
                width: 100%;
            }
            .right_side {
                width: 100%;
            }
            .chart {
                display: none
            }
            .left_table {
                width: 100%;
            }
            .left_table tbody {
                display: block;
                overflow: auto;
            }
            .signature {
                display: none;
            }
        }

        @media print {
            body {
                font-family: arial;
                font-size: 12px;
                margin: 5px;
            }
            .fix {
                overflow: hidden
            }
            .page-border {
                border: 5px solid #812F00;
                margin-top: 20px;
            }
            .page-border-2 {
                border: 5px solid #FFCE44;
            }
            .page-border-3 {
                border: 5px solid #000;
                padding: 10px 20px;
                border-style: double;
                height: 100%
            }
            ul{
                list-style: outside none none;
                padding: 0px;
                margin: 0px;
            }
            .head_column {
                width: 33%;
                float: left;
                text-align: center
            }
            .head_column img {
                height: 100px;
                margin: 10px 0px
            }
            .marksheets h2 {
                text-align: center;
                font-size: 22px;
                margin: 0px;
                font-family: arial;
                padding-bottom: 10px
            }
            .marksheets h3 {
                text-align: center;
                font-size: 16px;
                margin: 0px;
                border-bottom: 1px solid #0D9E3D;
                font-family: arial;
            }
            .head_column table tr th,
            td {
                border: 1px solid;
                text-align: center;
                font-size: 10px
            }
            .head_column table th {
                background: #FFF7E6;
                font-family: arial;
            }
            .grade_table table tr td {
                font-family: arial;
                font-size: 8px
            }
            .head_column table {
                width: 60%;
                float: right
            }
            .clear {
                clear: both;
            }
            .name_address_left {
                width: 60%;
                float: left;
                padding: 0px;
            }
            .name_address_left ul li span.a {
                width: 120px;
                display: inline-block;
                font-family: arial;
            }
            .name_address_left ul li span.b {
                width: 20px;
                display: inline-block;
                font-family: arial;
            }
            .name_address_right ul li span.a {
                width: 70px;
                display: inline-block;
                font-family: arial;
            }
            .name_address_right ul li span.b {
                width: 20px;
                display: inline-block;
                font-family: arial;
            }
            .name_address_right {
                width: 40%;
                float: right
            }
            .name_section {
                margin-top: 10px
            }
            .main_body {
                margin-top: 10px;
            }
            .main_body table {
                width: 100%
            }
            .main_body th {
                font-weight: bold;
                background: #EDFAFA;
                border: 1px solid;
                font-size: 14px;
                text-align: center
            }
            .total td {
                font-weight: bold;
                color: green;
                background: #F5F5DC;
                font-size: 14px;
            }
            .head_column table tr th,
            td {
                font-size: 12px
            }
            .body_second {
                margin-top: 0px
            }
            .left_side {
                width: 80%;
                float: left
            }
            .right_side {
                width: 20%;
                float: left
            }
            .left_table {
                width: 100%;
                float: left;
                margin-right: 10px;
                margin-top: 5px;
            }
            .right_side img {
                height: 150px;
                margin-top: 3px
            }
            .comment {
                text-align: left;
                font-weight: bold;
                font-size: 18px;
                border: 1px solid;
                margin-top: 5px;
                width: 93%;
                padding: 5px 0px;
            }
            .signature {
                width: 100%;
                margin-top: 190px
            }
            .sign {
                width: 15%;
                float: left;
                margin-right: 22%;
                text-align: center;
                display: block
            }
            .signs {
                width: 15%;
                float: left;
                text-align: center;
                display: block
            }
            .sig {
                border-top: 1px dashed;
            }
            .sg {
                height: 60px;
                width: 100%
            }
            .sg img {
                width: 100px;
            }

            .breakNow {
                page-break-inside: avoid;
                page-break-after: always;
                margin-top: 10px;
            }
        }
    </style>
    @if($classSectionStudentList)
        @php $stdCount = 0; @endphp
        @foreach($classSectionStudentList as $singleStudent)
            {{--std count--}}
            @php $stdCount++; @endphp
            {{--Student Information--}}
            @php $studentInfo  = $singleStudent->student(); @endphp
            {{--semester loop counter--}}
            @php $semesterLoopCounter = 0; @endphp

            <!-- attendance Report -->
            @if(count($semesterResultSheet)>0 && count($allSemester)>0)
                {{--myResult For Calculating Final Result--}}
                @php $myResult = array(); @endphp
                {{--semester looping--}}
                @for($m=0; $m<count($allSemester); $m++)

                    {{--semester loop count--}}
                    @php $semesterLoopCounter += 1; @endphp

                    @if(array_key_exists($allSemester[$m]['id'], $semesterResultSheet)==true)

                        <div class="marksheets page-border">
                            <div class="page-border-2">
                                <div class="page-border-3 fix">
                                    <div class="head_section">
                                        <div class="head_left_content"></div>
                                        <div class="head_right_content">
                                            <h2>{{$instituteInfo->institute_name}}</h2>
                                            <div style=" text-align:left" class="head_column">
{{--                                                <img style="border:1px solid #ccc; padding:1px;" src="https://plhsc.edu.bd/sa/uploads/20190106/154675403105.jpg" alt="">--}}

                                                @if($studentInfo->singelAttachment("PROFILE_PHOTO"))
                                                    <img src="{{URL::to('assets/users/images',$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"  style="height:80px;">
                                                @else
                                                    <img src="{{URL::to('assets/users/images/user-default.png')}}"  style="height:80px;">
                                                @endif
                                            </div>
                                            <div class="head_column">
                                                <p>{{$instituteInfo->address1}}</p>
                                                <img src="{{URL::asset('assets/users/images/'.$instituteInfo->logo)}}"  height="60px" alt="dd">
                                                <h3>PROGRESS REPORT</h3>
                                            </div>
                                            <div class="head_column grade_table">
                                                <table>
                                                    <tbody>
                                                    <tr>
                                                        <th>Range</th>
                                                        <th>Grade</th>
                                                        <th>GPA</th>
                                                    </tr>
                                                    <tr>
                                                        <td>80-100</td>
                                                        <td>A+</td>
                                                        <td>5</td>
                                                    </tr>
                                                    <tr>
                                                        <td>70-79</td>
                                                        <td>A</td>
                                                        <td>4</td>
                                                    </tr>
                                                    <tr>
                                                        <td>60-69</td>
                                                        <td>A-</td>
                                                        <td>3.5</td>
                                                    </tr>
                                                    <tr>
                                                        <td>50-59</td>
                                                        <td>B</td>
                                                        <td>3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>40-49</td>
                                                        <td>C</td>
                                                        <td>2</td>
                                                    </tr>
                                                    <tr>
                                                        <td>33-39</td>
                                                        <td>D</td>
                                                        <td>1</td>
                                                    </tr>
                                                    <tr>
                                                        <td>0-32</td>
                                                        <td>F</td>
                                                        <td>0</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="name_section fix">
                                        <div class="name_address_left col-sm-1 col-md-8">
                                            <ul>
                                                @php $stdFullName=$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name @endphp

                                                <li><span class="a">Student's Name</span><span class="b">:</span><b>{{$stdFullName}}</b></li>

                                                @php $parents = $studentInfo->myGuardians(); @endphp
                                                {{--checking--}}
                                                @if($parents->count()>0)
                                                    @foreach($parents as $index=>$parent)
                                                        @php $guardian = $parent->guardian(); @endphp
                                                        <li>
                                                            <span class="a">
                                                                {{--{{$guardian->type==1?"Father's Name":"Mother's Name"}}--}}
                                                                {{--checking guardin type--}}
                                                                @if($guardian->type ==0)
                                                                    Mother's Name
                                                                @elseif($guardian->type ==1)
                                                                    Father's Name
                                                                @else
                                                                    {{$index%2==0?"Father's Name":"Mother's Name"}}
                                                                @endif
                                                            </span><span class="b">:</span><b>{{$guardian->first_name." ".$guardian->last_name}}</b></li>
                                                    @endforeach
                                                @endif
                                                <li><span class="a">Student's ID</span><span class="b">:</span><b>ST0001179</b></li>
                                                <li><span class="a">Examination</span><span class="b">:</span><b>{{$allSemester[$m]['name']}}</b></li>

                                            </ul>
                                        </div>
                                        <div class="name_address_right col-sm-1 col-md-4">
                                            <ul>

                                                @php
                                                    $enrollment = $studentInfo->enroll();
                                                    $level  = $enrollment->level();
                                                    $batch  = $enrollment->batch();
                                                    $section  = $enrollment->section();
                                                    $division = null;
                                                @endphp

                                                @if($divisionInfo = $batch->get_division())
                                                    @php $division = " (".$divisionInfo->name.") "; @endphp
                                                @endif
                                                <li><span class="a">Class</span><span class="b">:</span><b>{{$batch->batch_name.$division}} </b></li>
{{--                                                <li><span class="a">Group</span><span class="b">:</span><b>SCIENCE</b></li>--}}
                                                <li><span class="a">Section</span><span class="b">:</span><b>{{$section->section_name}}</b></li>
{{--                                                <li><span class="a">Shift</span><span class="b">:</span><b>Morning</b></li>--}}
                                                <li><span class="a">Roll</span><span class="b">:</span><b>{{$enrollment->gr_no}}</b></li>
                                                <li><span class="a">Year</span><span class="b">:</span><b>2019</b></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="main_body table-responsive">
                                        @php
                                            $levelId = $level->id;
                                            $batchId = $batch->id;
                                            $sectionId = $section->id;
                                        @endphp


                                        @php
                                            $stdSubjectGrades = (array)$semesterResultSheet[$allSemester[$m]['id']][$studentInfo->id];
                                            // semester subject highest marks sheet
                                            $classSemesterAssessmentArrayList = $subjectHighestMarksListInClass[$allSemester[$m]['id']];
                                            $classMeritList = $classSemesterAssessmentArrayList['merit_list'];
                                            // section wise array list
                                            $semesterAssessmentArrayList = $subjectHighestMarksList[$allSemester[$m]['id']];
                                            $subjectHighestMarksArrayList = $semesterAssessmentArrayList['subject_highest_marks'];
                                            $meritList = $semesterAssessmentArrayList['merit_list'];
                                            $failedMeritList = $semesterAssessmentArrayList['failed_merit_list'];
                                            $extraHighestMarksArrayList = $semesterAssessmentArrayList['extra_highest_marks'];
                                            $meritListWithExtraMark = $semesterAssessmentArrayList['merit_list_with_extra_mark'];
                                            $classSectionExtraCategory = 0;
                                            // subject assessment array list
                                            $subjectAssArrayList = (array)($subjectAssessmentArrayList['subject_list']);
                                            // semester attendance sheet
                                            $semesterAttendanceList = $semesterAttendanceSheet[$allSemester[$m]['id']];
                                            // semester extraBook mark sheet
                                            $stdExtraBookArrayList = $stdExtraBookMarkSheet[$allSemester[$m]['id']];
                                            // student additional subject list
                                            $stdAddSubList = array_key_exists($studentInfo->id, $additionalSubjectList)? $additionalSubjectList[$studentInfo->id]:[];
                                        @endphp

                                        {{--assesssement details array list--}}
                                        @php $assessmentInfo = array(); @endphp
                                        @php $assessmentWAInfo = array(); @endphp
                                        @php $assessmentResultCountInfo = array(); @endphp
                                        {{--subject grade list checking--}}
                                        @if(count($stdSubjectGrades)>0)
                                                {{--<p style="font-size: 13px;" class="label text-center row-first">{{$allSemester[$m]['name']}} ({{date('d M, Y', strtotime($allSemester[$m]['start_date']))." - ".date('d M, Y', strtotime($allSemester[$m]['end_date']))}}) </p>--}}
                                                {{--<table class="report_card_table">--}}
                                                <table id="report_card" width="100%" class="text-center report_card_table" cellspacing="5">
                                                    <thead>
                                                    <tr class="text-center row-first">
                                                        <th width="15%">Subject</th>
                                                        <th width="5%">Mark</th>
                                                        @php
                                                            $semesterId = $allSemester[$m]['id'];
                                                            $myAssessmentCounter = 0;
                                                            $assessmentsCount = $gradeScale->assessmentsCount()
                                                        @endphp
                                                        {{--checking $assessmentsCount--}}
                                                        @if($assessmentsCount>0)
                                                            {{--assessment category list--}}
                                                            @php $allCategoryList = $gradeScale->assessmentCategory() @endphp
                                                            {{--Category list empty checking--}}
                                                            @if(!empty($allCategoryList) AND $allCategoryList->count()>0)
                                                                {{--Category list loopint--}}
                                                                @foreach($allCategoryList as $category)
                                                                    {{--checking category type--}}
                                                                    @if($category->is_sba==0)
                                                                        {{--category result count--}}
                                                                        @php $resultCount = $category->resultCount($batchId, $sectionId, $semesterId); @endphp

                                                                        {{--category all assessment list--}}
                                                                        @php $allAssessmentList = $category->allAssessment($gradeScale->id); @endphp
                                                                        {{--assessment list empty checking--}}
                                                                        @if(!empty($allAssessmentList) AND $allAssessmentList->count()>0)
                                                                            {{--my assessment category counter--}}
                                                                            @php $myAssessmentCounter +=1; @endphp

                                                                            {{--assessment list loopint--}}
                                                                            @foreach($allAssessmentList as $assessment)
                                                                                @php
                                                                                    $myAssessmentPoints = (array_key_exists($assessment->id, $subjectAssArrayList)?$subjectAssArrayList[$assessment->id]:0);
                                                                                @endphp
                                                                                {{--my assessment counter--}}
                                                                                @php $myAssessmentCounter +=1; @endphp

                                                                                <th>{{$assessment->name}}</th>
                                                                                @php $assessmentInfo[$category->id][] = ['id'=>$assessment->id,'points'=>$myAssessmentPoints]; @endphp
                                                                            @endforeach

                                                                            {{--checking result count for best result average--}}
                                                                            @if($resultCount AND $resultCount->result_count>0)
                                                                                @php $assessmentResultCountInfo[$category->id] = $resultCount->result_count;@endphp
                                                                            @endif

                                                                            <th>W/A</th>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                        <th>Total</th>
                                                        <th>Highest</th>
                                                        <th>Grade </th>
                                                        <th>Point</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="report_card_table_body" class="text-center" style="font-size: 12px">
                                                @php
                                                    $grade = $stdSubjectGrades['grade'];
                                                    $gradeResult = $stdSubjectGrades['result'];

                                                    // result details
                                                    $resultTotalMarks = 0;
                                                    $resultObtainedMarks = 0;

                                                    $resultTotalPoints = 0;
                                                    $resultObtainedPoints = 0;

                                                    $resultTotalSubjectCount = 0;
                                                    $resultFailedSubjectCount = 0;

                                                    // result looping
                                                    foreach ($classSubjects as $singleClassSubject){
                                                        // class subject id
                                                        $subId = $singleClassSubject['id'];
                                                        // sub exam mark
                                                        $subExamMark = $singleClassSubject['exam_mark'];
                                                         // sub pass mark
                                                        $subPassMark = $singleClassSubject['pass_mark'];
                                                         // sub pass mark
                                                        $isCountable = $singleClassSubject['is_countable'];
                                                        // class subject id
                                                        $csId = $singleClassSubject['cs_id'];
                                                        // single subject assessment array list
                                                        $subCatAssArrayList = (array)(array_key_exists($subId, $subjectAssArrayList)?$subjectAssArrayList[$subId]['cat_list']:[]);
                                                        // subject status (pass or fail)
                                                        $subjectStatus = true;
                                                       // checking class subject in the grade list
                                                       if(array_key_exists($csId, $grade)==false) continue;
                                                        // checking subject type
                                                        $subType = $singleClassSubject['type'];

                                                        // find subject grade list
                                                        $std_grade = $grade[$csId];
                                                        $grade_id = $std_grade['grade_id'];
                                                        $sub_id = $std_grade['cs_id'];
                                                        $sub_name = $std_grade['sub_name'];
                                                        $mark_id = $std_grade['mark_id'];
                                                        $mark = (array)$std_grade['mark'];
                                                        $credit = $std_grade['credit'];
                                                        $total = $std_grade['total'];
                                                        $obtained = $std_grade['obtained'];
                                                        $percentage = 0.00;
                                                        $letterGrade = $std_grade['letterGrade'];
                                                        $letterGradePoint = $std_grade['letterGradePoint'];
                                                        $cat_count = $mark['cat_count'];
                                                        $cat_list = (array)$mark['cat_list'];

                                                        // print subject name
                                                        if($isCountable==1){
                                                             // checking subject type
                                                            if($subType==3){
                                                                echo "<tr><td style='text-align: left;padding-left: 5px'>".$sub_name.' (Opt.)</td>';
                                                            }else{
                                                                echo "<tr><td style='text-align: left;padding-left: 5px'>".$sub_name.'</td>';
                                                            }
                                                        }else{
                                                            echo "<tr><td style='text-align: left;padding-left: 5px'>".$sub_name.' (UC)</td>';
                                                        }


                                                        // subject exam marks
                                                         echo '<td>'.$subExamMark.'</td>';

                                                        // category looping
                                                        $totalSubjectMarks = 0;


                                                        foreach ($assessmentInfo as $headCatId=>$headAssList){
                                                            // custom catId
                                                            $catId = 'cat_'.$headCatId;
                                                            // assessment-category status (pass or fail)
                                                            $assCatStatus = true;

                                                            // total assessment and points
                                                            $totalAssessmentMarks = 0;
                                                            $totalAssessmentPoints = 0;
                                                            // assessment mark list
                                                            $assessmentMarkList = array();
                                                            // assessment count
                                                            $assessmentCount = 0;
                                                            // find category assessment mark list
                                                            $singleCatMarksList = (array) (array_key_exists($headCatId, $subCatAssArrayList)?$subCatAssArrayList[$headCatId]:[]);
                                                            // subject assessment mark list
                                                            $subjectAssMarkList = (array) (array_key_exists('ass_list', $singleCatMarksList)?$singleCatMarksList['ass_list']:[]);
                                                            // checking category id
                                                            if($singleCatMarksList){
                                                                $catExamMark = floatval($singleCatMarksList['exam_mark']);
                                                                $catPassMark = floatval($singleCatMarksList['pass_mark']);
                                                            }else{
                                                                $catExamMark = 0; $catPassMark = 0;
                                                            }

                                                            // catId checking
                                                            if($catExamMark>0 AND array_key_exists($catId, $mark)){
                                                                // find category marks list from mark
                                                                $categoryMarksList = (array)$mark[$catId];
                                                                // assessment list looping

                                                                foreach ($headAssList as $headAssDetails){
                                                                    // assId
                                                                    $myAssId = $headAssDetails['id'];
                                                                    // custom assId
                                                                    $assId = 'ass_'.$myAssId;
                                                                    // assessment status
                                                                    $assStatus = true;
                                                                    // assId checking
                                                                    if(array_key_exists($assId, $categoryMarksList)){

                                                                        // checking assessment
                                                                        if(array_key_exists($myAssId, $subjectAssMarkList)==true){
                                                                            $assExamMark = floatval($subjectAssMarkList[$myAssId]['exam_mark']);
                                                                            $assPassMark = floatval($subjectAssMarkList[$myAssId]['pass_mark']);
                                                                        }else{
                                                                            $assExamMark = 0; $assPassMark = 0;
                                                                        }
                                                                        // find assessment marks list from categoryMarksList
                                                                        $assessment = (array)$categoryMarksList[$assId];
                                                                        // assessment details
                                                                        $ass_mark = $assessment['ass_mark'];
                                                                        $ass_points = $assExamMark;
                                                                        $ass_mark_percentage = (($ass_mark/$ass_points)*100);

                                                                        // checking for subject pass fail
                                                                        if($ass_mark<$assPassMark){
                                                                            // subject status
                                                                            $subjectStatus = false;
                                                                            // subject category-assessment status
                                                                            $assStatus = false;
                                                                            $assCatStatus = false;
                                                                        }
                                                                        //  checking assCateStatus for text color
                                                                        $color = ($assStatus==false?'red':'black');
                                                                        // weighted average

                                                                        // print assessment subject marks and points
                                                                        if($headCatId==6 AND ($singleClassSubject['id']==77 || $singleClassSubject['id']==100)){
                                                                             // print assessment subject marks and points
                                                                            echo '<td width="7%">-</td>';
                                                                        }else{
                                                                            // print assessment subject marks and points
                                                                            echo '<td width="7%"><span style="color:'.$color.'"> '.$ass_mark.' /  '.$ass_points.'</span></td>';
                                                                             $totalSubjectMarks += $ass_mark;
                                                                        }

                                                                        // count total assessment marks and points
                                                                        $totalAssessmentMarks += $ass_mark;
                                                                        $totalAssessmentPoints += $ass_points;

                                                                        // $ass_mark_percentage
                                                                        $assessmentMarkList[$myAssId] = $ass_mark_percentage;
                                                                        // assessment counter
                                                                        $assessmentCount += 1;

                                                                    }else{
                                                                        // print assessment subject marks and points
                                                                        echo '<td width="7%"> - </td>';
                                                                    }
                                                                }
                                                            }else{
                                                                // assessment list looping
                                                                foreach ($headAssList as $headAssDetails){
                                                                    // print assessment subject marks and points
                                                                    echo '<td width="7%"> - </td>';
                                                                }
                                                            }

                                                            // find category assessment mark list
                                                            $singleCatMarksList = (array) (array_key_exists($headCatId, $subCatAssArrayList)?$subCatAssArrayList[$headCatId]:[]);
                                                            // not best result count applicable
                                                            $catAssignedMark = $singleCatMarksList?$singleCatMarksList['exam_mark']:0;

                                                            // checking result count for best result average  of this category
                                                            if(array_key_exists($headCatId, $assessmentResultCountInfo)==true){

                                                                // ass_count
                                                                $my_ass_cat_result = $assessmentResultCountInfo[$headCatId];
                                                                // ass_cat_result_count
                                                                $my_ass_cat_result_count = ($my_ass_cat_result<=$assessmentCount?$my_ass_cat_result:$assessmentCount);
                                                                // assessment sorted marks list
                                                                arsort($assessmentMarkList);

                                                                // mark list
                                                                $cateAssMarkList = array();
                                                                // looping
                                                                foreach ($assessmentMarkList as $assId=>$marks){
                                                                    $cateAssMarkList[] = $marks;
                                                                }

                                                                $assTotalMarks = (100*$my_ass_cat_result_count);
                                                                $assTotalMarksObtained = 0;;

                                                                // now looping for final calculation
                                                                for($mm=0; $mm<$my_ass_cat_result_count; $mm++){
                                                                    $assTotalMarksObtained += $cateAssMarkList[$mm];
                                                                }

                                                                // final calculation
                                                                $catWeightedAverage = round(($assTotalMarksObtained/$assTotalMarks)*$catAssignedMark);

                                                            }else{
                                                                // checking $totalAssessmentPoints
                                                                if($totalAssessmentPoints>0){
                                                                   $catMarkPercentage = ($totalAssessmentMarks*100)/$totalAssessmentPoints;
                                                                   $catWeightedAverage = round(($catMarkPercentage*$catAssignedMark)/100, 2);
                                                                }else{
                                                                    $catMarkPercentage = 0;
                                                                    $catWeightedAverage = round($totalAssessmentMarks, 2, PHP_ROUND_HALF_UP);
                                                                }
                                                            }
                                                            // calculate subject total marks percentage
                                                            $percentage += round($catWeightedAverage, 2, PHP_ROUND_HALF_UP);
                                                            // checking subject category assigned marks
                                                            if($catAssignedMark>0){
                                                                //  checking assCateStatus for text color
                                                                $color = ($assCatStatus==false?'red':'black');
                                                                // weighted average
                                                                echo '<td width="7%"> <span style="color:'.$color.'">'.$catWeightedAverage.' / '.$catAssignedMark.'</span></td>';
                                                            }else{
                                                                echo '<td width="5%">-</td>';
                                                            }
                                                        }
                                                        // subject final grade calculation
                                                        $gradePercentage = (($percentage*100)/$subExamMark);

                                                        // find subject grade points
                                                        $subjectGradePoint = (array)(subjectGradeCalculation((int)$gradePercentage, $gradeScaleDetails));

                                                        // checking for subject pass fail
                                                        if((($percentage<$subPassMark) || ($subjectGradePoint['grade']=='F') || ($subjectStatus==false))){
                                                            // subject fail counter
                                                           if($isCountable==1){ $resultFailedSubjectCount+=1; }

                                                            $subjectStatus=false;
                                                        }
                                                        //  checking assCateStatus for text color
                                                        $color = ($subjectStatus==false?'red':'black');

                                                        // print subject marks details (total, obtained, percentage letterGrade, letterGradePoints)
                                                        echo '<td  width="5%"><span style="color:'.$color.'">'.round($percentage, 2, PHP_ROUND_HALF_UP).'</span></td>';
                                                        // checking for highest marks
                                                        if(array_key_exists($sub_id, $subjectHighestMarksArrayList)==true){
                                                            echo '<td width="5%">'.round($subjectHighestMarksArrayList[$sub_id], 2).'</td>';
                                                         }else{
                                                            echo '<td width="5%">-</td>';
                                                        }
                                                        // checking subject status for grade and points
                                                        if($subjectStatus){
                                                            // show subject final grade details
                                                            echo '<td  width="5%">'.$subjectGradePoint['grade'].'</td> <td  width="5%">'.$subjectGradePoint['point'].'</td> </tr>';
                                                        }else{
                                                           echo '<td width="5%"><span style="color:'.$color.'">F</span></td> <td width="5%"><span style="color:'.$color.'">0</span></td> </tr>';
                                                        }

                                                        // checking subject is_countable or not
                                                        if($isCountable==1){
                                                             // calculate all subject exam marks
                                                             $resultTotalMarks += $subExamMark;
                                                             // calculate all subject obtained marks
                                                             $resultObtainedMarks += $percentage;
                                                             // total subject counter
                                                             // $resultTotalSubjectCount += 1;
                                                        }
                                                    }
                                                @endphp
                                                @endif

{{--                                                <tr class="total">--}}
{{--                                                    <td colspan="{{(6+$myAssessmentCounter)}}">--}}
                                                        {{--student subject total assigned and obtained marks--}}
                                                        @php $totalClassMarks =  $resultTotalMarks; @endphp
                                                        @php $totalStdObtainedMarks = $resultObtainedMarks; @endphp

{{--                                                        <i>Total: {{$totalClassMarks}}, </i>--}}
{{--                                                        <i>Obtained: {{$totalStdObtainedMarks}}, </i>--}}
                                                        {{--Subject Highest Marks array list --}}
                                                        @php $subHighestMarkArrayList = array_unique(array_values($meritList)); @endphp
                                                        {{--class merit list--}}
                                                        @php $classMeritList = array_unique(array_values($classMeritList)); @endphp
{{--                                                        <i>Highest: {{count($subHighestMarkArrayList)>0?round($subHighestMarkArrayList[0], 2)/100:'N/A'}}, </i>--}}
                                                        <i>
                                                            @php $semesterMarksPercentage=round((($totalStdObtainedMarks*100)/$totalClassMarks), 2, PHP_ROUND_HALF_UP); @endphp
{{--                                                            Percent: {{$semesterMarksPercentage}}%,--}}
                                                        </i>
                                                @php $semesterSectionMeritPosition="N/A" @endphp
                                                @php $semesterClassMeritPosition="N/A" @endphp
                                                        @if($resultFailedSubjectCount>0)
                                                            <i>
{{--                                                                Failed in {{$resultFailedSubjectCount}} {{$resultFailedSubjectCount>1?'subjects':'subject'}}--}}
                                                            </i>
                                                        @else
                                                            @php
                                                                $semesterGradePoint=$gradeResult['total_gpa'];
                                                                // mark conversion (float to integer)
                                                                $myTotalMarks = (int) round($totalStdObtainedMarks*100);
                                                                // checking section merit position
                                                                if(in_array($myTotalMarks, $subHighestMarkArrayList)){
                                                                    $semesterSectionMeritPosition = (array_search($myTotalMarks, $subHighestMarkArrayList)+1);
                                                                }else{
                                                                    $semesterSectionMeritPosition = 'N/A';
                                                                }
                                                                // checking class merit position
                                                                if(in_array($myTotalMarks, $classMeritList)){
                                                                    $semesterClassMeritPosition = (array_search($myTotalMarks, $classMeritList)+1);
                                                                }else{
                                                                    $semesterClassMeritPosition = 'N/A';
                                                                }
                                                            @endphp
{{--                                                            <i> GPA: {{$gradeResult['total_gpa']}}, </i>--}}
{{--                                                            <i> Merit Position (Section):  {{$semesterSectionMeritPosition}}, </i>--}}
{{--                                                            <i> Merit Position (Class):  {{$semesterClassMeritPosition}} </i>--}}
                                                        @endif
                                                        @php $myResult[$allSemester[$m]['id']] = (object)['total_points'=>$gradeResult['total_points'],'total_gpa'=>$gradeResult['total_gpa']]; @endphp
{{--                                                    </td>--}}

{{--                                                </tr>--}}
                                                    </tbody>
                                                </table>

                                    </div>


                                    <div class="body_second fix" style="margin-top: 10px;">
                                        <div class="left_side fix">
                                            <table class="left_table table">
                                                <tbody>
                                                <tr>

                                                    <td>Total</td>
                                                    <td>Obtained</td>
                                                    <td>Highest</td>
                                                    <td>Percent</td>
                                                    <td>GPA</td>
                                                    <td>Merit Position (Section):</td>
                                                    <td>Merit Position (Class)</td>
                                                    <td>Status</td>
                                                </tr>
                                                <tr>
                                                    <td>{{$totalClassMarks}}</td>
                                                    <td>{{$totalStdObtainedMarks}}</td>
                                                    <td>{{count($subHighestMarkArrayList)>0?round($subHighestMarkArrayList[0], 2)/100:'N/A'}}</td>
                                                    <td> {{$semesterMarksPercentage}}</td>
                                                    <td>{{$gradeResult['total_gpa']}}</td>
                                                    <td>{{$semesterSectionMeritPosition}}</td>
                                                    <td>{{$semesterClassMeritPosition}}</td>
                                                    <td></td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            <div class="clear"></div>
                                            <div class="comment">
                                                <p>Comments</p>
                                            </div>
                                        </div>
                                        <div class="right_side col-sm-1 chart">
                                            <img src="https://chart.googleapis.com/chart?cht=qr&amp;chs=300x300&amp;choe=UTF-8&amp;chl=Name: {{$stdFullName}}, GPA:{{$gradeResult['total_gpa']}}, Mark:{{$totalStdObtainedMarks}}, {{$instituteInfo->institute_name}}" alt="">
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="signature">
                                        <div class="sign">
                                            <div class="sg"></div>
                                            <div class="sig">Guardian</div>
                                        </div>
                                        <div class="sign">
                                            <div class="sg"></div>
                                            <div class="sig">Class Teacher</div>
                                        </div>
                                        <div class="signs">

                                            @if($reportCardSetting AND $reportCardSetting->auth_sign!=null AND !empty($reportCardSetting->auth_sign))
                                                <div class="sg"><img src="{{URL::to('assets/users/images/',$reportCardSetting->auth_sign)}}" height="50px;" alt=""></div>
                                            @endif
                                            <div class="sig">Head Master</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        @continue
                    @endif
                @endfor
            @else
                <div class="row clear">
                    <p class="label text-center row-second">Report Card </p>
                    <p class="text-center">
                        <b>No records found</b><br/>
                        Please check following instructions: <br/>
                        1. Empty Semester List<br/>
                        2. Empty Result Sheet
                    </p>
                </div>
            @endif
{{--            @break--}}
            <div class="breakNow"></div>
        @endforeach
    @endif
</body>
</html>
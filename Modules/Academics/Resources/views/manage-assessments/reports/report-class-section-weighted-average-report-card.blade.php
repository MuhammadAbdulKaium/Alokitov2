<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style type="text/css">
        .label {font-size: 25px;  padding: 20px 10px; font-weight: 900;}
        #progress {
            border: 2px solid black;
            padding: 5px 15px;
            border-radius: 10px;
            font-size: 20px;
            font-weight: 900;
        }
        .row-first{background-color: #b0bc9e;}
        .row-second{background-color: #e5edda;}
        .row-third{background-color: #5a6c75;}
        .text-center {text-align: center;}
        .clear{clear: both;}
        .text-bold{font-weight: 700;}

        .calculation i{
            margin-right: 25px;
        }

        .report_card_table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            text-align: center;
        }

        .report_card_table td, .report_card_table th { border: 1px solid black; padding: 2px; }
        .report_card_table th { padding:6px; text-align: center; color: black;  }

        .singnature{ height: 30px;  width: 40px;}
        .row { width:100%; clear: both; text-align: center}
        #std-photo{width: 30%; float: left; text-align: left}
        #inst-info{ width: 50%; float: left}
        #grade-scale{ width: 20%; float: right}

        /*.std-info-table { }*/

        #report_card {}
        #report_card th { font-size: 15px; }
        #report_card td { font-size: 14px; }
        #report_card tr>td:first-child {text-align: left; font-weight: 500; padding-left: 12px; }


        .report-comments{
            /*width: 24%;*/
            float: left;
            margin-right: 17px;
        }
        /*commenting table */
        .comments-table {
            width: 100%;
            border-collapse: collapse;
        }

        .comments-table, .comments-table th, .comments-table td {
            border: 1px solid black;
        }

        .comments-table th, .comments-table td {
            line-height: 18px;
            font-size:14px;
            text-align: left;
            padding-left: 15px;
            color: black;
        }

    </style>
</head>


<body>

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

                    <div class="row">
                        <div id="std-photo">
                            @if($studentInfo->singelAttachment("PROFILE_PHOTO"))
                                {{--<img src="{{public_path().'/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name}}"  style=" width:90px;height:100px; border: 2px solid #efefef">--}}
                                <img src="{{asset('assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"  style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
                            @else
                                <img src="{{public_path().'/assets/users/images/user-default.png'}}"  style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
                            @endif

                            <br/>
                            <br/>
                            <table class="std-info-table" cellpadding="1" style="font-size:14px; line-height: 13px; text-align: left;">
                                <tr>
                                    <th>Name of Student</th>
                                    <th width="3%">:</th>
                                    @php $stdFullName=$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name @endphp

                                    <td><b>{{$stdFullName}}</b></td>
                                </tr>
                                {{--parents information--}}
                                @php $parents = $studentInfo->myGuardians(); @endphp
                                {{--checking--}}
                                @if($instituteInfo->id!=29 AND $instituteInfo->id!=13)
                                    @if($parents->count()>0)
                                        @foreach($parents as $index=>$parent)
                                            @php $guardian = $parent->guardian(); @endphp
                                            <tr>
                                                <th>
                                                    {{--{{$guardian->type==1?"Father's Name":"Mother's Name"}}--}}
                                                    {{--checking guardin type--}}
                                                    @if($guardian->type ==0)
                                                        Mother's Name
                                                    @elseif($guardian->type ==1)
                                                        Father's Name
                                                    @else
                                                        {{$index%2==0?"Father's Name":"Mother's Name"}}
                                                    @endif
                                                </th>
                                                <th>:</th>
                                                <td>{{$guardian->first_name." ".$guardian->last_name}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                                <tr>
                                    <th>Class </th>
                                    <th>:</th>
                                    {{--student enrollment--}}
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
                                    <td>{{$batch->batch_name.$division}} - {{$section->section_name}} </td>
                                </tr>
                                <tr>
                                    <th>Roll </th>
                                    <th>:</th>
                                    <td>{{$enrollment->gr_no}}</td>
                                </tr>
                            </table>
                            @php
                                $levelId = $level->id;
								$batchId = $batch->id;
								$sectionId = $section->id;
                            @endphp
                        </div>

                        <div id="inst-info">
                            <b style="font-size: 25px;">{{$instituteInfo->institute_name}}</b><br/>
                            <span style="font-size:18px">{{$instituteInfo->address1}}</span>
                            <br/><br/>
                            @if($instituteInfo->logo)
                                <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}" class="center"  style="width:80px;height:80px;">
                                <br/>
                                <br/>
                                <p class="text-center" ><span id="progress">PROGRESS REPORT</span></p>
                            @endif
                        </div>
                        <div id="grade-scale">
                            <table style="font-size:12px; float: right; line-height: 12px" class="report_card_table" cellpadding="1">
                                <thead>
                                <tr>
                                    <th>Marks (%)</th>
                                    <th>Grade</th>
                                    <th>Point</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($gradeScaleDetails as $gradeScaleDetail)
                                    <tr>
                                        <td>{{$gradeScaleDetail['min_per']. ' - '.$gradeScaleDetail['max_per']}}</td>
                                        <td>{{$gradeScaleDetail['name']}}</td>
                                        <td>{{$gradeScaleDetail['points']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <table width="100%" cellpadding="1" style="font-size:14px; line-height: 13px; text-align: left; margin-top: 30px; float: right">
                                <tr>
                                    <th>Exam</th>
                                    <th>:</th>
                                    <td>{{$allSemester[$m]['name']}}</td>
                                </tr>
                                <tr>
                                    <th>Duration </th>
                                    <th>:</th>
                                    <td>{{date('d M, Y', strtotime($allSemester[$m]['start_date']))}} - {{date('d M, Y', strtotime($allSemester[$m]['end_date']))}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
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
                        <div class="clear" style="width: 100%;">
                            <br/>
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

                                    $resultSubjectCountable = 0;
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
                                                echo "<tr><td>".$sub_name.' (Opt.)</td>';
                                            }else{
                                                echo "<tr><td>".$sub_name.'</td>';
                                                // countable subject
                                                $resultSubjectCountable +=1;
                                            }
                                        }else{
                                            echo '<tr><td>'.$sub_name.' (UC)</td>';
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

                                             if($subType==3){
                                                // my point
                                                $myOptSubPoint = ($subjectGradePoint['point']-2);
                                                // obtained point
                                                $resultObtainedPoints += $myOptSubPoint>0?$myOptSubPoint:0;
                                             }else{
                                                // obtained point
                                                $resultObtainedPoints += $subjectGradePoint['point'];
                                             }
                                        }
                                    }
                                @endphp

                                <tr class="text-bold calculation">
                                    <th colspan="{{(6+$myAssessmentCounter)}}">
                                        {{--student subject total assigned and obtained marks--}}
                                        @php $totalClassMarks =  $resultTotalMarks; @endphp
                                        @php
                                            $totalStdObtainedMarks = array_key_exists($studentInfo->id, $meritList)?($meritList[$studentInfo->id]/100):$resultObtainedMarks;
                                        @endphp

                                        <i>Total: {{$totalClassMarks}}, </i>
                                        <i>Obtained: {{$totalStdObtainedMarks}}, </i>
                                        {{--Subject Highest Marks array list --}}
                                        @php $subHighestMarkArrayList = array_unique(array_values($meritList)); @endphp
                                        {{--class merit list--}}
                                        @php $classMeritList = array_unique(array_values($classMeritList)); @endphp
                                        <i>Highest: {{count($subHighestMarkArrayList)>0?round($subHighestMarkArrayList[0], 2)/100:'N/A'}}, </i>
                                        <i>
                                            @php $semesterMarksPercentage=round((($totalStdObtainedMarks*100)/$totalClassMarks), 2, PHP_ROUND_HALF_UP); @endphp
                                            Percent: {{$semesterMarksPercentage}}%,
                                        </i>

                                        @if($resultFailedSubjectCount>0)
                                            <i>
                                                Failed in {{$resultFailedSubjectCount}} {{$resultFailedSubjectCount>1?'subjects':'subject'}}
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
                                            {{--<i> GPA: {{$gradeResult['total_gpa']}}, </i>--}}
                                            <i> Merit Position (Section):  {{$semesterSectionMeritPosition}}, </i>
                                            <i> Merit Position (Class):  {{$semesterClassMeritPosition}}, </i>
                                            <i> GPA: {{round(($resultObtainedPoints/$resultSubjectCountable), 1)}} </i>
                                        @endif
                                        @php $myResult[$allSemester[$m]['id']] = (object)['total_points'=>$gradeResult['total_points'],'total_gpa'=>$gradeResult['total_gpa']]; @endphp
                                    </th>
                                </tr>
                                </tbody>
                            </table>

                            {{--{{dd($subHighestMarkArrayList)}}--}}
                            {{--{{dd($failedMeritList)}}--}}
                            {{--{{dd($classMeritList)}}--}}

                            {{--checking student extra book list--}}
                            @php
                                $extraBookProfile = array_key_exists($studentInfo->id,$stdExtraBookArrayList)?$stdExtraBookArrayList[$studentInfo->id]:null;
								// checking student extra book list
								$extraMarkAssList = $extraBookProfile?$extraBookProfile['mark_list']:[];
                            @endphp

                            {{--checking $assessmentsCount--}}
                            @if($gradeScale->assessmentsCount()>0)
                                @php
                                    $allCategoryList = $gradeScale->assessmentCategory();
									$allExtraMarksList = $gradeScale->getAssessmentExtraList();
                                @endphp
                                {{--student extra book mark list--}}
                                {{--Category list empty checking--}}
                                @if(!empty($allCategoryList) AND $allCategoryList->count()>0 AND $allExtraMarksList AND $allExtraMarksList->count()>0)
                                    <div class="clear" style="width: 100%; margin-top: 20px;">
                                        {{--<p class="label text-center row-second">Other Marks</p>--}}
                                        <table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5">

                                            {{--Category list loopint--}}
                                            @foreach($allCategoryList as $category)
                                                {{--checking category type--}}
                                                @if($category->is_sba==1)
                                                    {{--category all assessment list--}}
                                                    @php $allAssessmentList = $category->allAssessment($gradeScale->id); @endphp
                                                    {{--assessment list empty checking--}}
                                                    @if($allAssessmentList AND $allAssessmentList->count()>0)
                                                        <thead>
                                                        <tr class="text-center row-second"><th colspan="{{$allExtraMarksList->count()+2}}">Other Marks</th></tr>
                                                        <tr class="text-center">
                                                            @php $totalAssessmentPointCounter = 0; @endphp
                                                            {{--assessment list loopint--}}
                                                            @foreach($allAssessmentList as $assessment)
                                                                {{--checking assessment type--}}
                                                                @if($assessment->counts_overall_score==0) @continue @endif
                                                                {{--assessemnt details--}}
                                                                <th>{{$assessment->name.' ('.$assessment->points.')'}}</th>
                                                                {{--total Assessment Point Counter--}}
                                                                @php $totalAssessmentPointCounter += $assessment->points; @endphp
                                                            @endforeach
                                                            <th>Total ({{$totalAssessmentPointCounter}})</th>
                                                            <th>Highest</th>
                                                        </tr>
                                                        </thead>
                                                        {{--break the loop --}}
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach
                                            <tbody>
                                            <tr class="text-center">
                                                {{--assessment list counter--}}
                                                @php $assessmentTotalCount = 0; $assessmentTotalMarks = 0; $assessmentTotalObtainedMarks = 0; @endphp
                                                {{--Category list loopint--}}
                                                @foreach($allCategoryList as $category)
                                                    {{--checking category type--}}
                                                    @if($category->is_sba==1)
                                                        {{--category all assessment list--}}
                                                        @php $allAssessmentList = $category->allAssessment($gradeScale->id); @endphp
                                                        {{--assessment list empty checking--}}
                                                        @if(!empty($allAssessmentList) AND $allAssessmentList->count()>0)

                                                            {{--assessment list loopint--}}
                                                            @foreach($allAssessmentList as $assessment)
                                                                {{--checking assessment type--}}
                                                                @if($assessment->counts_overall_score==0) @continue @endif
                                                                {{--assessment mark--}}
                                                                @php
                                                                    $assessmentPoints = $assessment->points;
																	$assessmentMarks = array_key_exists($assessment->id, $extraMarkAssList)?$extraMarkAssList[$assessment->id]:null;
                                                                @endphp

                                                                {{--Checking assessment marks--}}
                                                                @if($assessmentMarks)
                                                                    <td>{{$assessmentMarks}}</td>
                                                                    {{--assessment all calculation--}}
                                                                    @php $assessmentTotalObtainedMarks += $assessmentMarks; @endphp
                                                                @else
                                                                    <td>-</td>
                                                                @endif
                                                                {{--assessment list counter--}}
                                                                @php $assessmentTotalCount += 1; $assessmentTotalMarks += $assessmentPoints; @endphp
                                                            @endforeach
                                                            <td>{{$assessmentTotalObtainedMarks}}</td>
                                                            <td>{{$extraHighestMarksArrayList?reset($extraHighestMarksArrayList):' - '}}</td>
                                                            {{--break the loop --}}
                                                            @break
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </tr>

                                            {{--checking total extra marks --}}
                                            @if($assessmentTotalObtainedMarks>0)
                                                {{--<tr class="text-bold calculation" style="font-weight: 700; font-size: 13px;">--}}
                                                {{--<th colspan="{{(2+$assessmentTotalCount)}}">--}}
                                                {{--<i>Total: {{$totalClassMarks+$assessmentTotalMarks}}, </i>--}}
                                                {{--@php $totalObtainedMarkWithExtra = $totalStdObtainedMarks+$assessmentTotalObtainedMarks; @endphp--}}
                                                {{--<i>Obtained: {{$totalObtainedMarkWithExtra}}, </i>--}}
                                                {{--Extra Highest Marks array list --}}
                                                {{--@php $extraHighestMarkArrayList = array_unique(array_values($meritListWithExtraMark)); @endphp--}}
                                                {{--<i>Highest: {{count($extraHighestMarkArrayList)>0?round($extraHighestMarkArrayList[0], 2):'N/A'}}, </i>--}}
                                                {{--calculating marks percentage--}}
                                                {{--@php $semesterFinalMarksPercentage = (($totalStdObtainedMarks+$assessmentTotalObtainedMarks)/($totalClassMarks+$assessmentTotalMarks))*100; @endphp--}}
                                                {{--<i> Percent: {{round($semesterFinalMarksPercentage, 2, PHP_ROUND_HALF_UP)}}%, </i>--}}

                                                {{--@if($resultFailedSubjectCount>0)--}}
                                                {{--<i>--}}
                                                {{--Failed in {{$resultFailedSubjectCount}} {{$resultFailedSubjectCount>1?'subjects':'subject'}}--}}
                                                {{--</i>--}}
                                                {{--@else--}}
                                                {{--@php--}}
                                                {{--$semesterFinalGradePoint = subjectGradeCalculation((int)$semesterFinalMarksPercentage, $gradeScaleDetails);--}}

                                                {{--// mark conversion (float to integer)--}}
                                                {{--$myTotalMarksWithExtra = (int) round($totalObtainedMarkWithExtra*100);--}}
                                                {{--// checking section merit position--}}
                                                {{--if(in_array($myTotalMarksWithExtra, $extraHighestMarkArrayList)){--}}
                                                {{--$semesterSectionMeritPositionWithExtra = (array_search($myTotalMarksWithExtra, $extraHighestMarkArrayList)+1);--}}
                                                {{--}else{--}}
                                                {{--$semesterSectionMeritPositionWithExtra = 'N/A';--}}
                                                {{--}--}}
                                                {{--@endphp--}}
                                                {{--<i> GPA: {{$semesterFinalGradePoint['point']}} , </i>--}}
                                                {{--<i> Merit Position: {{array_key_exists($studentInfo->id, $meritListWithExtraMark)?(array_search($totalObtainedMarkWithExtra, $extraHighestMarkArrayList)+1):' N/A'}} </i>--}}
                                                {{--<i> Merit Position: {{$semesterSectionMeritPositionWithExtra}} </i>--}}
                                                {{--@endif--}}
                                                {{--</th>--}}
                                                {{--</tr>--}}
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @endif




                            {{--semester attendance details--}}
                            @if($semesterAttendanceList AND $semesterAttendanceList->status=='success')

                                @php
                                    // $precision
									$precision = 2;
									// attendance details
									$holidayCount = count($semesterAttendanceList->holiday_list);
									$weekOffDayCount = count($semesterAttendanceList->week_off_day_list);
									$totalAttendanceDays = $semesterAttendanceList->total_attendance_day;
									$totalWorkingDays = $totalAttendanceDays-($holidayCount+$weekOffDayCount);
									$attendanceList = $semesterAttendanceList->attendance_list;
									// checking student attendance list
									if(array_key_exists($studentInfo->id, $attendanceList)==true){
										// My Attendance list
										$myAttendanceInfo = (object)$attendanceList[$studentInfo->id];
										$myPresentDays = $myAttendanceInfo->present;
										$myAbsentDays = $myAttendanceInfo->absent;
										// present percentage
										$presentPercentage = floatval(($myPresentDays/$totalWorkingDays)*100);
										$absentPercentage = floatval(($myAbsentDays/$totalWorkingDays)*100);
									}else{
										// My Attendance list
										$myAttendanceInfo = null;
									}

									// attendance percentage calculation
									$presentPercentage = $myAttendanceInfo?$myPresentDays.' ('.(substr(number_format($presentPercentage, $precision + 1, '.', ''), 0, -1)).' %)':'-';
									$absentPercentage = $myAttendanceInfo?$myAbsentDays.' ('.(substr(number_format($absentPercentage, $precision + 1, '.', ''), 0, -1)).' %)':'-';
                                @endphp
                                {{--@else--}}
                                {{--@php $totalWorkingDays = ' '; $presentPercentage = '  '; $absentPercentage = '  '; @endphp--}}
                                {{--@endif--}}

                                {{--checking afec id--}}
                                @if($instituteInfo->id!=5)
                                    <div class="row clear" style="margin-top:25px;">
                                        {{--Result Status--}}
                                        <div class="report-comments" style="width: 20%">
                                            <table class="text-center comments-table" cellpadding="1">
                                                <tr>
                                                    <td width="50%">Result Status </td>
                                                    <td>{{$resultFailedSubjectCount>0?'Failed':'Passed'}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Failed Subjects </td>
                                                    <td>{{$resultFailedSubjectCount>0?$resultFailedSubjectCount:'-'}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Working Days</td>
                                                    <td>{{$totalWorkingDays}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Present</td>
                                                    <td> {{$presentPercentage}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Absent</td>
                                                    <td> {{$absentPercentage}} </td>
                                                </tr>
                                            </table>
                                        </div>
                                        {{--Moral & Behaviour--}}
                                        <div class="report-comments" style="width: 20%">
                                            <table class="text-center comments-table" cellpadding="1">
                                                <tr>
                                                    <td colspan="2">Moral & Behaviour </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%"></td>
                                                    <td>Excellent</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Good</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Average</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Need improvement</td>
                                                </tr>
                                            </table>
                                        </div>
                                        {{--Co-Curricular Activities--}}
                                        <div class="report-comments" style="width: 20%">
                                            <table class="text-center comments-table" cellpadding="1">
                                                <tr>
                                                    <td colspan="2">Co-Curricular Activities</td>
                                                </tr>
                                                <tr>
                                                    <td width="20%"></td>
                                                    <td>Sports</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Cultural Function</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Scout / BNCC</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Math Olympiad</td>
                                                </tr>
                                            </table>
                                        </div>
                                        {{--Comments--}}
                                        <div class="report-comments" style="width: 36%; margin-right: 0px;">
                                            <table class="text-center comments-table" cellpadding="1">
                                                <tr>
                                                    <td colspan="2">Comments</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 42px"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                @else
                                    <div class="clear" style="width: 100%; margin-top: 20px;">
                                        <table width="100%" class="text-center report_card_table" cellspacing="5">
                                            <thead>
                                            <tr class="text-center"><td colspan="5">Attendance Details</td></tr>
                                            <tr class="text-center row-first">
                                                <td>Total Days</td>
                                                <td>Holiday + Week Off Day</td>
                                                <td>Working Day</td>
                                                <td>Present (%)</td>
                                                <td>Absent (%)</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{$totalAttendanceDays}}</td>
                                                <td>{{$holidayCount.' + '.$weekOffDayCount.' = '.($holidayCount+$weekOffDayCount)}}</td>
                                                <td>{{$totalWorkingDays}}</td>
                                                <td>{{$presentPercentage}}</td>
                                                <td>{{$absentPercentage}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @else
                                {{--checking institute--}}
                                @if($instituteInfo->id==13)
                                    <div class="clear" style="width: 100%; margin-top: 50px;">
                                        {{--table width:77%--}}
                                        <table width="100%" class="comments-table" cellpadding="1" style="text-align:left; line-height: 20px;">
                                            <tr>
                                                <th style="padding-left: 10px">Comments: </th>
                                            </tr>
                                            <tr>
                                                <th style="padding: 38px"></th>
                                            </tr>
                                        </table>
                                    </div>
                                @endif
                            @endif

                        </div>
                    @else
                        <div class="row clear">
                            <p class="label text-center">Report Card </p>
                            <p class="text-center">
                                <b>Result not published</b><br/>
                            </p>
                        </div>
                        @break
                    @endif
                    <br/>
                    <br/>


                    @if(!empty($myResult))
                        <div class="clear" style="width: 100%;">
                            <div style="float: left; width: 30%; text-align:center; padding: 20px;">
                                <span style="display:{{($reportCardSetting AND $reportCardSetting->parent_sign==1)?'block':'none'}}">
                                    <span class="singnature"> </span><br>
                                ............................................<br>
                                    <strong>Guardian</strong>
                                </span>
                            </div>

                            <div style="float: left; width: 30%; text-align:center; padding: 20px">
                                <span style="display:{{($reportCardSetting AND $reportCardSetting->teacher_sign==1)?'block':'none'}}">
                                    <span class="singnature"></span><br>
                                ............................................<br>
                                    <strong>Class Teacher</strong>
                                </span>
                            </div>

                            {{--                            @php $qrCode="Name:".$stdFullName.", Total: ".$totalClassMarks.", Obtained: ".$totalStdObtainedMarks.", Percent: ".$semesterMarksPercentage."%, GPA: ".$semesterGradePoint.", Merit Position: ".$semesterMeritPosition @endphp--}}

                            {{--<div class="qrcode" style="width: 50% ; float: left; margin: 0px; padding: 0px">--}}
                            {{--<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($qrCode))!!} ">--}}
                            {{--</div>--}}

                            <div style="float: right; width: 30%; text-align:center; {{($reportCardSetting AND $reportCardSetting->auth_sign)?'':'padding: 20px;'}}">
                                {{--checking auth sign--}}
                                @if($reportCardSetting AND $reportCardSetting->auth_sign!=null AND !empty($reportCardSetting->auth_sign))
                                    <img src="{{public_path().'/assets/users/images/'.$reportCardSetting->auth_sign}}" style="height: 40px; padding: 0px; margin-bottom: -6px;">
                                @endif
                                {{--auth name--}}
                                @if($reportCardSetting AND $reportCardSetting->auth_name!=null AND !empty($reportCardSetting->auth_name))
                                    @if($reportCardSetting->auth_sign==null)
                                        <span class="singnature"></span><br>
                                    @endif
                                    ............................................<br>
                                    <strong>@php echo html_entity_decode($reportCardSetting->auth_name) @endphp </strong>
                                @else
                                    <span class="singnature"></span><br>
                                    ............................................<br>
                                    <strong>Principal / Assistant Headmaster</strong>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{--page breaker--}}
                    @if($stdCount<count($classSectionStudentList))
                        <div style="page-break-after:always;"></div>
                    @endif
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
    @endforeach
@endif
</body>
</html>

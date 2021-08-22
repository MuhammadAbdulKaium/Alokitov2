<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Student Infromation -->
    <style type="text/css">
        .label {font-size: 15px;  padding: 3px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
        .row-first{background-color: #b0bc9e;}
        .row-second{background-color: #e5edda;}
        .row-third{background-color: #5a6c75;}
        .text-center {text-align: center;}
        .clear{clear: both;}
        .text-bold{font-weight: 700;}

        .calculation i{
            margin-right: 15px;
            line-height: 15px;
        }
        #std-info {
            float:left;
            width: 60%;
        }
        #std-photo {
            float:right;
            width: 30%;
            margin-left: 10px;
        }
        #inst-photo{
            float:left;
            width: 15%;
        }
        #inst-info{
            float:left;
            width: 85%;
        }

        #inst{
            padding-bottom: 3px;
            width: 100%;
        }

        body{
            font-size: 11px;
        }
        .report_card_table{
            border: 1px solid #dddddd;
            border-collapse: collapse;
            line-height: 12px;
        }

        /*th,td {line-height: 20px;}*/
        html{margin:30px}

        .singnature{
            height: 30px;
            width: 40px;
        }

        .subject{
            text-align: left;
            padding-left: 10px;
        }

    </style>
</head>
<body>

{{--find user information--}}
@php $user = \Illuminate\Support\Facades\Auth::user()->userInfo()->first(); @endphp

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

            <div id="inst" class="text-center clear" style="width: 100%;">
                <div id="inst-photo">
                    @if($instituteInfo->logo)
                        <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:60px;height:60px">
                    @endif
                </div>
                <div id="inst-info">
                    <b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
                </div>
            </div>

            {{--Student Infromation--}}
            <div class="clear" style="width: 100%;">
                <p class="label text-center" style="font-size: 15px">PROGRESS REPORT</p>

                @if(!empty($reportCardSetting) && ($reportCardSetting->is_image==1))
                    <div class="image" style="width: 11%; text-align: left; float: left;">
                        @if($studentInfo->singelAttachment("PROFILE_PHOTO"))
                            <img src="{{public_path().'/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name}}"  style="margin-top: 0px; width:90px;height:100px; border: 2px solid #efefef">
                        @else
                            <img src="{{public_path().'/assets/users/images/user-default.png'}}"  style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
                        @endif
                    </div>
                @endif

                <div id="std-info" style="width: 85%">
                    <table width="100%" cellpadding="1" style="font-size:13px; line-height: 12px;">
                        <tr>
                            <th width="18%">Name of Student</th>
                            <th width="3%">:</th>
                            @php $stdFullName=$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name @endphp

                            <td><b>{{$stdFullName}}</b></td>
                        </tr>
                        {{--parents information--}}
                        @php $parents = $studentInfo->myGuardians(); @endphp
                        {{--checking--}}
                        @if($parents->count()>0)
                            @foreach($parents as $index=>$parent)
                                @php $guardian = $parent->guardian(); @endphp
                                <tr>
                                    <th>
                                        {{--checking guardin type--}}
                                        @if($guardian->type)
                                            {{$guardian->type==1?"Father's Name":"Mother's Name"}}
                                        @else
                                            {{$index%2==0?"Father's Name":"Mother's Name"}}
                                        @endif
                                    </th>
                                    <th>:</th>
                                    <td>{{$guardian->title." ".$guardian->first_name." ".$guardian->last_name}}</td>
                                </tr>
                            @endforeach
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
                        <tr>
                            <th>Date of Birth </th>
                            <th>:</th>
                            <td>{{ date('d M, Y', strtotime($studentInfo->dob)) }}</td>
                        </tr>
                    </table>

                    @php
                        $levelId = $level->id;
                        $batchId = $batch->id;
                        $sectionId = $section->id;
                    @endphp
                </div>
                <div id="std-photo" style="margin-left:580px;">
                    <table width="70%" style="font-size:10px;" border="1 px slid"  class="text-center report_card_table" cellpadding="1">
                        <thead>
                        <tr>
                            <th>Marks</th>
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
                </div>
            </div>

            @php
                $stdSubjectGrades = (array)$semesterResultSheet[$allSemester[$m]['id']];
				// semester subject highest marks sheet
				$semesterAssessmentArrayList = $subjectHighestMarksList[$allSemester[$m]['id']];
				$subjectHighestMarksArrayList = $semesterAssessmentArrayList['subject_highest_marks'];
				$meritList = $semesterAssessmentArrayList['merit_list'];
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
                <div class="clear">
                    {{--@if($stdSubjectGrades == null) @continue @endif--}}
                    <p class="label text-center row-second" style="font-size: 12px;">{{$allSemester[$m]['name']}} ({{date('d M, Y', strtotime($allSemester[$m]['start_date']))." - ".date('d M, Y', strtotime($allSemester[$m]['end_date']))}}) </p>
                    {{--<table class="report_card_table">--}}
                    <table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5" style="font-size: 10px; line-height: 13px;">
                        <thead>
                        <tr class="text-center row-second">
                            <th width="8%">Subject</th>
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
                                        {{--checking category type is_sba on not--}}
                                        @if($category->is_sba==1) @continue @endif
                                        @if($category->id!=$categoryId) @continue @endif
                                        {{--category result count--}}
                                        @php $resultCount = $category->resultCount($batchId, $sectionId, $semesterId); @endphp
                                        {{--category all assessment list--}}
                                        @php $allAssessmentList = $category->allAssessment($gradeScale->id); @endphp
                                        {{--assessment list empty checking--}}
                                        @if(!empty($allAssessmentList) AND $allAssessmentList->count()>0)
                                            {{--my assessment category counter for weighted average--}}
                                            @php $myAssessmentCounter +=1; @endphp
                                            {{--assessment list loopint--}}
                                            @foreach($allAssessmentList as $assessment)
                                                {{--my assessment counter--}}
                                                @php $myAssessmentCounter +=1; @endphp
                                                <th>{{$assessment->name}}</th>
                                                @php $assessmentInfo[$category->id][] = ['id'=>$assessment->id]; @endphp
                                            @endforeach
                                            {{--checking result count for best result average--}}
                                            @if($resultCount AND $resultCount->result_count>0)
                                                {{--input result count with category id--}}
                                                @php $assessmentResultCountInfo[$category->id] = $resultCount->result_count;@endphp
                                            @endif
                                            <th>{{$category->name}} (W/A)</th>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
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
                                $subCatAssArrayList = (array)array_key_exists($subId, $subjectAssArrayList)?$subjectAssArrayList[$subId]['cat_list']:[];
                                // subject status (pass or fail)
                                $subjectStatus = true;

                                // checking class subject in the grade list
                                if(array_key_exists($csId, $grade)==false) continue;
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
                                // $percentage = $std_grade['percentage'];
                                $percentage = 0;
                                $letterGrade = $std_grade['letterGrade'];
                                $letterGradePoint = $std_grade['letterGradePoint'];
                                $cat_count = $mark['cat_count'];
                                $cat_list = (array)$mark['cat_list'];
                                // print subject name
                                if($isCountable==1){
                                    echo '<tr><td class="subject">'.$sub_name.'</td>';
                                }else{
                                    echo '<tr><td class="subject">'.$sub_name.' (UnCountable)</td>';
                                }

                                foreach ($assessmentInfo as $headCatId=>$headAssList){
                                    // checking category id
                                    if($headCatId!=$categoryId){continue;}
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
                                                $assessment = (array)($categoryMarksList[$assId]);
                                                // assessment details
                                                $ass_mark = floatval($assessment['ass_mark']);
                                                $ass_points = $assExamMark;
                                                //$ass_points = float val($assessment['ass_points']);
                                                $ass_mark_percentage = ($ass_points>0?(($ass_mark/$ass_points)*100):0);

                                                // checking subject mark for pass or fail
                                                $color = ($ass_mark<$assPassMark?'red':'black');
                                                // print assessment subject marks and points
                                                echo '<td width="7%"><span style="color:'.$color.'">'.$ass_mark.' /  '.$ass_points.'</span></td>';

                                                // count total assessment marks and points
                                                $totalAssessmentMarks += $ass_mark;
                                                $totalAssessmentPoints += $ass_points;
                                                // $ass_mark_percentage
                                                $assessmentMarkList[$myAssId] = $ass_mark_percentage;
                                                // assessment counter
                                                $assessmentCount += 1;

      											// checking for subject pass fail
												if($ass_mark<$assPassMark){
												    $resultFailedSubjectCount+=1;
												    // subject status
												    $subjectStatus = false;
												    // subject category-assessment status
												    $assCatStatus = false;
												}
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
									$singleCatMarksList = (array) (array_key_exists($headCatId, $subCatAssArrayList)==true?$subCatAssArrayList[$headCatId]:[]);
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
                                        $catWeightedAverage = ($assTotalMarksObtained/$assTotalMarks)*$catAssignedMark;

                                    }else{

                                        // checking $totalAssessmentPoints
										if($totalAssessmentPoints>0){
										   $catMarkPercentage = ($totalAssessmentMarks*100)/$totalAssessmentPoints;
										   $catWeightedAverage = round(($catMarkPercentage*$catAssignedMark)/100, 2);
										}else{
										    $catMarkPercentage = 0;
										    $catWeightedAverage = $totalAssessmentMarks;
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
                                        echo '<td>-</td>';
                                    }
                                }
                              }
                        @endphp
                        </tbody>
                    </table>
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

                <div class="clear" style="width: 100%;">
                    <div style="float: left; width: 30%; text-align:center; padding: 20px;">
                    <span class="singnature"> </span><br>
                    ............................................<br>
                    <strong>Parent Signature</strong>
                    </span>
                    </div>

                    <div style="float: left; width: 30%; text-align:center; padding: 20px">
                    <span class="singnature"></span><br>
                    ............................................<br>
                    <strong>Teacher Signature</strong>
                    </span>
                    </div>



                    <div style="float: right; width: 38%; text-align:center; {{($reportCardSetting AND $reportCardSetting->auth_sign)?'':'padding: 20px;'}}">
                        {{--checking auth sign--}}
                        @if($reportCardSetting AND $reportCardSetting->auth_sign!=null AND !empty($reportCardSetting->auth_sign))
                            <img class="singnature" src="{{public_path().'/assets/users/images/'.$reportCardSetting->auth_sign}}"> <br>
                        @endif
                        {{--auth name--}}
                        @if($reportCardSetting AND $reportCardSetting->auth_name!=null AND !empty($reportCardSetting->auth_name))
                            @if($reportCardSetting->auth_sign==null) <span class="singnature"></span><br> @endif
                            ............................................<br>
                            <strong>@php echo html_entity_decode($reportCardSetting->auth_name) @endphp </strong>
                        @else
                            <span class="singnature"></span><br>
                            ............................................<br>
                            <strong>Principal / Head Teacher</strong>
                        @endif
                    </div>
                </div>
        @endif

        @if($semesterLoopCounter != count($allSemester))
            <div style="page-break-after:always;"></div>
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
</body>
</html>

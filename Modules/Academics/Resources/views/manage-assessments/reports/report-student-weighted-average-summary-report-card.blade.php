<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Student Infromation -->
    <style type="text/css">
        .label {font-size: 15px;  padding: 5px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
        .row-first{background-color: #b0bc9e;}
        .row-second{background-color: #e5edda;}
        .row-third{background-color: #5a6c75;}
        .text-center {text-align: center; font-size: 12px}
        .clear{clear: both;}
        .text-bold{font-weight: 700;}

        .calculation i{
            margin-right: 10px;
            line-height: 15px;
        }
        #std-info {
            float:left;
            width: 79%;
        }
        #std-photo {
            float:right;
            width: 20%;
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
            padding-bottom: 20px;
            width: 100%;
        }

        .report_card_table{
            border: 1px solid #dddddd;
            border-collapse: collapse;
        }

        @if($reportCardSetting AND $reportCardSetting->is_label_color==1)
            .row-second{
            background-color: {{$reportCardSetting->label_bg_color}};
            color: {{$reportCardSetting->label_font_color}};
        }
        @endif

        @if($reportCardSetting AND $reportCardSetting->is_watermark==1)
            #my_report_card {
            background-image: url({{$reportCardSetting->wm_url}});
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
            opacity: {{$reportCardSetting->wm_opacity}};
        }
        @endif

        @if($reportCardSetting AND $reportCardSetting->is_table_color==1)
        #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            opacity: {{$reportCardSetting->tbl_opacity}};
            border: 1px solid black;
        }

        /*#customers th{*/
        /*border: 1px solid #ddd;*/
        /*}*/


        #customers td, #customers th {
            border: 1px solid {{$reportCardSetting->tbl_header_tr_bg_color}};
        }


        #header_row th  {
            border-right: 1px solid {{$reportCardSetting->tbl_odd_tr_bg_color}};
        }
        #header_row th:last-child  {
            border-right: 1px solid {{$reportCardSetting->tbl_header_tr_bg_color}};
        }

        #customers td {
            line-height: 22px;
        }

        #customers tr:nth-child(odd){
            background-color: {{$reportCardSetting->tbl_odd_tr_bg_color}};
            color: {{$reportCardSetting->tbl_odd_tr_font_color}};
        }

        #customers tr:nth-child(even){
            background-color: {{$reportCardSetting->tbl_even_tr_bg_color}};
            color: {{$reportCardSetting->tbl_even_tr_font_color}};
        }

        #customers th {
            background-color: {{$reportCardSetting->tbl_header_tr_bg_color}};
            color: {{$reportCardSetting->tbl_header_tr_font_color}};
        }
        @endif

				/*th,td {line-height: 20px;}*/
        html{margin:15px;}

        body{
            font-size: 11px;
            @if($reportCardSetting AND $reportCardSetting->is_border_color==1)
                @php $border = $reportCardSetting->border_width.'px '.$reportCardSetting->border_type.' '.$reportCardSetting->border_color;  @endphp
		    border: {{$border}};
            border-radius: 5px;
            @endif
            padding: 15px;
        }

        .singnature{
            height: 30px;
            width: 40px;
        }

        .subject{
            text-align: left;
            padding-left: 10px;
            font-weight: 300;
        }

    </style>
</head>
<body>
<div id="container">
{{--semester loop counter--}}
@php $semesterLoopCounter = 0; @endphp

<!-- attendance Report -->
    @if(count($semesterResultSheet)>0 && count($allSemester)>0)

        {{--semester looping--}}
        @for($m=0; $m<count($allSemester); $m++)

            {{--semester loop count--}}
            @php $semesterLoopCounter += 1; @endphp

            @if(array_key_exists($allSemester[$m]['id'], $semesterResultSheet)==true)

                <div id="inst" class="text-center clear" style="width: 100%;">
                    <div id="inst-photo">
                        @if($instituteInfo->logo)
                            <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
                        @endif
                    </div>
                    <div id="inst-info">
                        <b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
                    </div>
                </div>

                {{--Student Infromation--}}
                <div class="clear" style="width: 100%;">
                    <p class="label text-center" style="font-size: 18px">PROGRESS REPORT</p>

                    @if(!empty($reportCardSetting) && ($reportCardSetting->is_image==1))
                        <div class="image" style="width: 15%; text-align: left; float: left">
                            @if($studentInfo->singelAttachment("PROFILE_PHOTO"))
                                <img src="{{public_path().'/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name}}"  style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
                            @else
                                <img src="{{public_path().'/assets/users/images/user-default.png'}}"  style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
                            @endif
                        </div>
                    @endif

                    <div id="std-info" style="width: 85%">
                        <table width="100%" cellpadding="1" style="font-size:14px">
                            <tr>
                                <th width="20%">Name of Student</th>
                                <th width="1%">:</th>
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

                                @php $division = null; @endphp
                                @if($divisionInfo = $enrollment->batch()->get_division())
                                    @php $division = " (".$divisionInfo->name.") "; @endphp
                                @endif
                                <td>{{$enrollment->batch()->batch_name.$division}} - {{$enrollment->section()->section_name}} </td>
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
                    <div id="std-photo" style="margin-left: {{$reportCardSetting?'578px':'585px'}}">
                        <table width="100%" style="font-size: 10px;" border="1px solid" class="text-center report_card_table" cellpadding="2">
                            <thead>
                            <tr>
                                <th>Marks</th>
                                <th>Grade</th>
                                <th>Point</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($gradeScaleDetails as $gradeScaleDetail){
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
                    <div class="clear" style="width: 100%">
                        <p class="label text-center row-second">{{$allSemester[$m]['name']}} ({{date('d M, Y', strtotime($allSemester[$m]['start_date']))." - ".date('d M, Y', strtotime($allSemester[$m]['end_date']))}}) </p>
                        <div id="my_report_card" class="clear" style="width: 100%">
                            {{--@if($stdSubjectGrades == null) @continue @endif--}}

                            {{--<table class="report_card_table">--}}
                            <table id="customers" width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5">
                                <thead>
                                <tr id="header_row" class="text-center row-second">
                                    <th width="25%">Subject</th>
                                    <th width="7%">Marks</th>
                                    @php
                                        $semesterId = $allSemester[$m]['id'];
										$myAssessmentCounter = 0;
										$assessmentCategoryTotalMarks = 0;
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
                                                @if($category->is_sba==0)
                                                    {{--category all assessment list--}}
                                                    @php $allAssessmentList = $category->allAssessment($gradeScale->id); @endphp
                                                    {{--assessment list empty checking--}}
                                                    @if(!empty($allAssessmentList) AND $allAssessmentList->count()>0)

                                                        {{--category result count--}}
                                                        @php $resultCount = $category->resultCount($batchId, $sectionId, $semesterId); @endphp

                                                        {{--my assessment category counter--}}
                                                        @php $myAssessmentCounter +=1; @endphp

                                                        {{--assessment list loopint--}}
                                                        @foreach($allAssessmentList as $assessment)
                                                            {{--myAssessmentPoints--}}
                                                            @php $myAssessmentPoints = 0; @endphp
                                                            {{--store assessment info--}}
                                                            @php $assessmentInfo[$category->id][] = ['id'=>$assessment->id]; @endphp
                                                        @endforeach

                                                        {{--checking result count for best result average--}}
                                                        @if($resultCount AND $resultCount->result_count>0)
                                                            @php $assessmentResultCountInfo[$category->id] = $resultCount->result_count;@endphp
                                                        @endif

                                                        <th>{{$category->name}}</th>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                    <th width="7%">Total</th>
                                    <th width="7%">Highest</th>
                                    <th width="7%">Grade</th>
                                    <th width="7%">Point</th>
                                </tr>
                                </thead>
                                <tbody id="report_card_table_body" class="text-center" style="line-height: 20px;">
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
                                       $subCatAssArrayList = (array)(array_key_exists($subId, $subjectAssArrayList)?$subjectAssArrayList[$subId]['cat_list']:[]);
                                       // subject status (pass or fail)
                                       $subjectStatus = true;
                                       // checking class subject in the grade list
                                       if(array_key_exists($csId, $grade)==false) continue;
                                       // find subject grade list
										$std_grade = $grade[$csId];
										// checking subject type
										$subType = $singleClassSubject['type'];

										$grade_id = $std_grade['grade_id'];
										$sub_id = $std_grade['cs_id'];
										$sub_name = $std_grade['sub_name'];
										$mark_id = $std_grade['mark_id'];
										$mark = (array)$std_grade['mark'];
										$credit = $std_grade['credit'];
										$total = $std_grade['total'];
										$obtained = $std_grade['obtained'];
										//$percentage = $std_grade['percentage'];
										$percentage = 0;
										$letterGrade = $std_grade['letterGrade'];
										$letterGradePoint = $std_grade['letterGradePoint'];
										$cat_count = $mark['cat_count'];
										$cat_list = (array)$mark['cat_list'];


                                        // print subject name
                                        if($isCountable==1){
                                            if($subType==0 AND array_key_exists($sub_id, $stdAddSubList) AND $stdAddSubList[$sub_id]==0){
                                                echo '<tr><td class="subject">'.$sub_name.' (Optional)</td>';
                                            }else{
                                                echo '<tr><td class="subject">'.$sub_name.'</td>';
                                            }
                                        }else{
                                            echo '<tr><td class="subject">'.$sub_name.' (UnCountable)</td>';
                                        }


                                        // exam marks
                                        echo '<td>'.$subExamMark.'</td>';
										// category looping
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
													$assId = 'ass_'.$headAssDetails['id'];
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
														$ass_mark = floatval($assessment['ass_mark']);
														//$ass_points = float val($assessment['ass_points']);
														$ass_points = $assExamMark;
														$ass_mark_percentage = (($ass_mark/$ass_points)*100);

														// count total assessment marks and points
														$totalAssessmentMarks += $ass_mark;
														$totalAssessmentPoints += $ass_points;

														// $ass_mark_percentage
														$assessmentMarkList[$myAssId] = $ass_mark_percentage;
														// assessment counter
														$assessmentCount += 1;

														// checking for subject pass fail
														if($ass_mark<$assPassMark){
														    // $resultFailedSubjectCount+=1;
														    // subject status
														    $subjectStatus = false;
														    // subject category-assessment status
														    $assCatStatus = false;
														}

														// checking for subject pass fail
														if($subjectStatus==false){
														    $resultFailedSubjectCount+=1;
														}
													}
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
                                                // $catWeightedAverage = ($assTotalMarksObtained/$assTotalMarks)*$catAssignedMark;
												$catWeightedAverage = $assTotalMarks>0?(round(($assTotalMarksObtained/$assTotalMarks)*$catAssignedMark)):0;

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
                                                echo '<td width="7%"> <span style="color:'.$color.'">'.$catWeightedAverage.'</span></td>';
                                            }else{
                                                echo '<td>-</td>';
                                            }
										}

                                        // subject final grade calculation
                                        $gradePercentage = ($percentage*100/$subExamMark);
                                        // find subject grade points
                                        $subjectGradePoint = (array)subjectGradeCalculation((int)$gradePercentage, $gradeScaleDetails);
                                         // checking for subject pass fail
                                        if((($percentage<$subPassMark) || ($subjectGradePoint['grade']=='F')) AND $subjectStatus==true){
                                            // subject fail counter
                                            $resultFailedSubjectCount+=1;
                                            // subject status
                                            $subjectStatus = false;
                                        }
                                        //  checking assCateStatus for text color
                                        $color = ($subjectStatus==false?'red':'black');

                                        // print subject marks details (total, obtained, percentage letterGrade, letterGradePoints)
                                        echo '<td  width="5%"><span style="color:'.$color.'">'.round($percentage, 2, PHP_ROUND_HALF_UP).'</span></td>';

										 // checking subject highest marks
										 if(array_key_exists($sub_id, $subjectHighestMarksArrayList)==true){
												echo '<td>'.round($subjectHighestMarksArrayList[$sub_id], 2).'</td>';
										  }else{
												echo '<td>-</td>';
										 }

                                         //  checking assCateStatus for text color
                                          $color = ($subjectStatus==false?'red':'black');
                                         // checking subject status for grade and points
                                         if($subjectStatus){
                                            // find subject grade points
                                            echo '<td  width="5%">'.$subjectGradePoint['grade'].'</td> <td  width="5%">'.$subjectGradePoint['point'].'</td> </tr>';
                                         }else{
                                            echo '<td width="5%"><span style="color:'.$color.'">F</span></td> <td width="5%"><span style="color:'.$color.'">0</span></td> </tr>';
                                         }

                                        // checking subject is_countable or not
                                        if($isCountable==1){
                                            // result details
                                            $resultTotalMarks += $subExamMark;
                                            // $resultTotalMarks += 100;
                                            $resultObtainedMarks += $percentage;
                                            // checking subject type
                                            if($subType==0 AND array_key_exists($sub_id, $stdAddSubList) AND $stdAddSubList[$sub_id]==0){
                                                // checking subject grade points
                                                if($subjectGradePoint['point']>2){
                                                    $resultObtainedPoints += ($subjectGradePoint['point']-2);
                                                    //$resultObtainedPoints += ($subjectGradePoint['point']);
                                                }
                                            }else{
                                                // subject counter
                                                $resultObtainedPoints += $subjectGradePoint['point'];
                                                $resultTotalPoints += $subjectGradePoint['max_point'];
                                            }
                                            // subject counter
                                            $resultTotalSubjectCount += 1;
                                        }

									}
                                @endphp

                                {{--student subject total assigned and obtained marks--}}
                                @php $totalClassMarks =  $resultTotalMarks; @endphp
                                @php $totalStdObtainedMarks = $resultObtainedMarks; @endphp
                                @php $totalGPA = $resultObtainedPoints/$resultTotalSubjectCount; @endphp
                                <tr class="text-bold calculation">
                                    <th colspan="{{(6+$myAssessmentCounter)}}">


                                        {{--<i>total points: {{$resultTotalPoints}}, </i>--}}
                                        {{--<i>obtained point: {{$resultObtainedPoints}}, </i>--}}
                                        {{--<i>obtained GPA: {{round($totalGPA, 2, PHP_ROUND_HALF_UP)}}, </i>--}}


                                        <i>Total: {{$resultTotalMarks}}, </i>
                                        <i>Obtained: {{$resultObtainedMarks}}, </i>
                                        {{--Subject Highest Marks array list --}}
                                        @php $subHighestMarkArrayList = array_unique(array_values($meritList)); @endphp
                                        <i>Highest: {{count($subHighestMarkArrayList)>0?round($subHighestMarkArrayList[0], 2):' N/A'}}, </i>
                                        {{--calculating marks percentage--}}
                                        @php
                                            $semesterMarksPercentage = ($resultObtainedMarks/$resultTotalMarks)*100;
                                            $semesterPoints = $resultObtainedPoints/$resultTotalSubjectCount;
                                            $semesterPercentage = round($semesterMarksPercentage, 2, PHP_ROUND_HALF_UP);
                                        @endphp
                                        <i> Percent: {{round($semesterMarksPercentage, 2, PHP_ROUND_HALF_UP)}}%, </i>
                                        {{--calculating total remaining points--}}
                                        {{--@php $resultRemainingPoints = $resultTotalPoints-$resultObtainedPoints; @endphp--}}
                                        {{--checking remainging points--}}
                                        @if($resultFailedSubjectCount>0)
                                            <i>
                                                Failed in {{$resultFailedSubjectCount}} {{$resultFailedSubjectCount>1?'subjects':'subject'}}
                                            </i>
                                        @else
                                            {{--// print subject marks details (total, obtained, percentage letterGrade, letterGradePoints)--}}
                                            @php $semesterGradePoint = subjectGradeCalculation((int)$semesterMarksPercentage, $gradeScaleDetails); @endphp
                                            <i> GPA : {{$semesterGradePoint['point']}} </i>
                                            @php $semesterMeritPosition=(array_search($totalStdObtainedMarks, array_unique(array_values($meritList)))+1) @endphp
                                            {{--<i> GPA : {{round($semesterPoints, 2, PHP_ROUND_HALF_UP)}} , </i>--}}
                                            <i> Merit Position: {{$semesterMeritPosition}} </i>
                                        @endif
                                    </th>
                                </tr>

                                </tbody>
                            </table>
                        </div>


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
                                                            {{--assessemnt details--}}
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
                                            <tr class="text-bold calculation">
                                                <th colspan="{{(2+$assessmentTotalCount)}}">
                                                    <i>Total: {{$resultTotalMarks+$assessmentTotalMarks}}, </i>
                                                    @php $semesterExtraTotalObtained = ($resultObtainedMarks+$assessmentTotalObtainedMarks); @endphp
                                                    <i>Obtained: {{$semesterExtraTotalObtained}}, </i>
                                                    {{--calculating marks percentage--}}
                                                    @php $semesterFinalMarksPercentage = (($resultObtainedMarks+$assessmentTotalObtainedMarks)/($resultTotalMarks+$assessmentTotalMarks))*100; @endphp
                                                    {{--Extra Highest Marks array list --}}
                                                    @php $extraHighestMarkArrayList = array_unique(array_values($meritListWithExtraMark)); @endphp
                                                    <i>Highest: {{count($extraHighestMarkArrayList)>0?$extraHighestMarkArrayList[0]:'N/A'}}, </i>
                                                    <i> Percent: {{round($semesterFinalMarksPercentage, 2, PHP_ROUND_HALF_UP)}}%, </i>
                                                    @if($resultFailedSubjectCount>0)
                                                        <i>
                                                            Failed in {{$resultFailedSubjectCount}} {{$resultFailedSubjectCount>1?'subjects':'subject'}}
                                                        </i>
                                                    @else
                                                        @php $semesterFinalGradePoint = subjectGradeCalculation((int)$semesterFinalMarksPercentage, $gradeScaleDetails); @endphp
                                                        <i> GPA: {{$semesterFinalGradePoint['point']}} , </i>
                                                        <i> Merit Position: {{(array_search($semesterExtraTotalObtained, array_unique(array_values($meritListWithExtraMark)))+1)}} </i>
                                                    @endif
                                                </th>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif

                        @if($semesterAttendanceList AND $semesterAttendanceList->status=='success')
                            <div class="clear" style="width: 100%; margin-top: 20px;">
                                <table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5">
                                    <thead>
                                    <tr class="text-center row-second"><th colspan="5">Attendance Details</th></tr>
                                    <tr class="text-center">
                                        <th>Total Days</th>
                                        <th>Holiday + Week Off Day</th>
                                        <th>Working Day</th>
                                        <th>Present</th>
                                        <th>Absent</th>
                                    </tr>
                                    </thead>
                                    <tbody>
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
                                    @endphp
                                    <tr>
                                        <td>{{$totalAttendanceDays}}</td>
                                        <td>{{$holidayCount.' + '.$weekOffDayCount.' = '.($holidayCount+$weekOffDayCount)}}</td>
                                        <td>{{$totalWorkingDays}}</td>
                                        <td>{{$myAttendanceInfo?$myPresentDays.' ('.(substr(number_format($presentPercentage, $precision + 1, '.', ''), 0, -1)).' %)':'-'}}</td>
                                        <td>{{$myAttendanceInfo?$myAbsentDays.' ('.(substr(number_format($absentPercentage, $precision + 1, '.', ''), 0, -1)).' %)':'-'}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <br/>
                        <div class="clear" style="width: 100%;">
                            {{--<div style="float: left; width: 30%; text-align:center; padding: 20px;">--}}
                                {{--<span style="display:{{($reportCardSetting AND $reportCardSetting->parent_sign==1)?'block':'none'}}">--}}
                                    {{--<span class="singnature"> </span><br>--}}
                                {{--............................................<br>--}}
                                    {{--<strong>Parent Signature</strong>--}}
                                {{--</span>--}}
                            {{--</div>--}}

                            {{--<div style="float: left; width: 30%; text-align:center; padding: 20px">--}}
                                {{--<span style="display:{{($reportCardSetting AND $reportCardSetting->teacher_sign==1)?'block':'none'}}">--}}
                                    {{--<span class="singnature"></span><br>--}}
                                {{--............................................<br>--}}
                                    {{--<strong>Teacher Signature</strong>--}}
                                {{--</span>--}}
                            {{--</div>--}}

{{--                            @php $qrCode="Name:".$stdFullName.", Total: ".$resultTotalMarks.", Obtained: ".$resultObtainedMarks.", Percent: ".$semesterPercentage."%, GPA: ".$semesterGradePoint['point'].", Merit Position: ".$semesterMeritPosition @endphp--}}

                            {{--<div class="qrcode" style="width: 50% ; float: left">--}}
                                {{--<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($qrCode))!!} ">--}}
                            {{--</div>--}}

                            <div style="float: right; width: 38%; text-align:center; {{($reportCardSetting AND $reportCardSetting->auth_sign)?'':'padding: 20px;'}}">
                                {{--checking auth sign--}}
                                @if($reportCardSetting AND $reportCardSetting->auth_sign!=null AND !empty($reportCardSetting->auth_sign))
                                    <img class="singnature" src="{{public_path().'/assets/users/images/'.$reportCardSetting->auth_sign}}"> <br>
                                @endif
                                {{--auth name--}}
                                @if($reportCardSetting AND $reportCardSetting->auth_name!=null AND !empty($reportCardSetting->auth_name))


                                @if($reportCardSetting->auth_sign==null) <span class="singnature"></span><br> @endif
                                    ............................................<br>
                                    <strong>@php echo  html_entity_decode($reportCardSetting->auth_name) @endphp </strong>
                                @else
                                    <span class="singnature"></span><br>
                                    ............................................<br>
                                    <strong>Principal / Head Teacher</strong>
                                @endif
                            </div>
                        </div>
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
</div>
</body>
</html>

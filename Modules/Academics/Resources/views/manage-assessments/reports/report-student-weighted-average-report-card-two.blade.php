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
        html{margin:25px}

        .singnature{
            height: 30px;
            width: 40px;
        }

    </style>
</head>
<body>

{{--find user information--}}
{{--@php $user = \Illuminate\Support\Facades\Auth::user()->userInfo()->first(); @endphp--}}

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

            <style>

                #std-photo{
                    width:20%;
                    float: left;
                }

                #inst-info{
                    width:58%;
                    float: left;
                    text-align: center;
                }

                #grade-scale{
                    width:20%;
                    float: right;
                    margin-left:910px;
                }

                .std-info-table {
                    font-size:13px;
                    line-height: 12px;
                    margin-bottom: 10px;
                }

                #report-comments{
                    width: 74%;
                    float: left;
                }

                #qr-code{
                    width: 20%;
                    float: right;
                }

                /*commenting table */
                .table {
                    border-collapse: collapse;
                    line-height: 25px;
                }

                .table, .table th, .table td {
                    border: 1px solid black;
                }

                #qr-code{
                    width: 25%;
                    float: right;
                }


                html{margin:15px;}

                body{
                    font-size: 11px;
                    padding: 15px;

                    @if($reportCardSetting AND $reportCardSetting->is_border_color==1)
						@php $border = $reportCardSetting->border_width.'px '.$reportCardSetting->border_type.' '.$reportCardSetting->border_color;  @endphp
					border: {{$border}};
                    border-radius: 5px;
                @endif
			}

            </style>

            <div class="clear" style="width: 100%;">
                <div id="std-photo">
                    @if(!empty($reportCardSetting) && ($reportCardSetting->is_image==1))
                        <div class="image" style="width: 11%; text-align: left; float: left;">
                            @if($studentInfo->singelAttachment("PROFILE_PHOTO"))
                                <img src="{{public_path().'/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name}}"  style="margin-top: 0px; width:90px;height:100px; border: 2px solid #efefef">
                            @else
                                <img src="{{public_path().'/assets/users/images/user-default.png'}}"  style="margin-top: 5px; width:90px;height:100px; border: 2px solid #efefef">
                            @endif
                        </div>
                    @endif
                </div>
                <div id="inst-info">
                    <b style="font-size: 22px">{{$instituteInfo->institute_name}}</b>
                    <br/>{{'Address: '.$instituteInfo->address1}}

                    <br/>
                    @if($instituteInfo->logo)
                        <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}" class="center"  style="width:65px;height:65px; margin-top: 15px">
                        <br/>
                        <br/>
                        <p style="font-size:15px; text-align: center; line-height: 2px"><b>PROGRESS REPORT</b></p>
                        <hr style="color: {{$reportCardSetting->border_color}}">
                    @endif
                </div>
                <div id="grade-scale">
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


            {{--Student Infromation--}}
            <div class="clear" style="width: 100%;">
            </div>

            <div class="clear" style="width: 100%;">
                <div style="width: 65%; float: left">
                    <table width="100%" class="std-info-table" cellpadding="1">
                        <tr>
                            <th width="20%">Student's Name</th>
                            <th width="1%">:</th>
                            @php $stdFullName=$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name @endphp
                            <td><b>{{$stdFullName}}</b></td>
                        </tr>

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

                        {{--parents information--}}
                        @php $parents = $studentInfo->myGuardians(); @endphp
                        {{--checking--}}
                        @if($parents->count()>0)
                            @foreach($parents as $index=>$parent)
                                @php $guardian = $parent->guardian(); @endphp
                                <tr>
                                    <th>{{$index%2==0?"Father's Name":"Mother's Name"}} </th>
                                    <th>:</th>
                                    <td>{{$guardian->title." ".$guardian->first_name." ".$guardian->last_name}}</td>
                                </tr>
                            @endforeach
                        @endif

                        <tr>
                            <th>Student's ID </th>
                            <th>:</th>
                            @php $user = $studentInfo->user(); @endphp
                            <td>{{$user->username}}</td>
                        </tr>
                        <tr>
                            <th>Examination </th>
                            <th>:</th>
                            <td> {{$allSemester[$m]['name']}} </td>
                        </tr>
                        <tr>
                            <th>Year </th>
                            <th>:</th>
                            <td>{{date('Y', strtotime($allSemester[$m]['start_date']))}}</td>
                        </tr>

                    </table>
                    @php
                        $levelId = $level->id;
						$batchId = $batch->id;
						$sectionId = $section->id;
                    @endphp
                </div>
                <div style="width: 34%; float: right">
                    <table width="100%" class="std-info-table" cellpadding="1">
                        <tr>
                            <th>Class </th>
                            <th>:</th>
                            <td>{{$enrollment->batch()->batch_name.$division}} </td>
                        </tr>
                        <tr>
                            <th>Group </th>
                            <th>:</th>
                            <td>-</td>
                        </tr>
                        <tr>
                            <th>Section </th>
                            <th>:</th>
                            <td> {{$enrollment->section()->section_name}} </td>
                        </tr>
                        <tr>
                            <th>Shift </th>
                            <th>:</th>
                            <td>-</td>
                        </tr>
                        <tr>
                            <th>Roll </th>
                            <th>:</th>
                            <td>{{$enrollment->gr_no}}</td>
                        </tr>
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
                    {{--<table class="report_card_table">--}}
                    <table width="100%" border="1px solid" class="text-center report_card_table" cellspacing="5" style="font-size: 10px; line-height: 13px;">
                        <thead>
                        <tr class="text-center row-second">
                            <th width="15%">Subject</th>
                            <th width="5%">Full Marks</th>
                            <th width="5%">Highest Marks</th>
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
                                            <th>W/A</th>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                            <th width="5%">Total Marks</th>
                            <th width="5%">Letter Grade</th>
                            <th width="5%">Grade Point</th>
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
                                    echo '<tr><td>'.$sub_name.'</td>';
                                }else{
                                    echo '<tr><td>'.$sub_name.' (UnCountable)</td>';
                                }
                                // subject mark
                                echo '<td>'.$subExamMark.'</td>';

                                // checking for highest marks
							    if(array_key_exists($sub_id, $subjectHighestMarksArrayList)==true){
						   		    echo '<td>'.$subjectHighestMarksArrayList[$sub_id].'</td>';
							     }else{
						   		    echo '<td>-</td>';
							    }

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
                                                $ass_mark_percentage = ($ass_mark/$ass_points)*100;

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
                                     $resultTotalSubjectCount += 1;
                                }
                              }
                        @endphp


                        <tr class="text-bold calculation">
                            <th colspan="{{(6+$myAssessmentCounter)}}">
                                {{--student subject total assigned and obtained marks--}}
                                @php $totalClassMarks =  $resultTotalMarks; @endphp
                                @php $totalStdObtainedMarks = $resultObtainedMarks; @endphp

                                <i>Total Marks: {{$totalClassMarks}}, </i>
                                <i>Obtained Marks: {{$totalStdObtainedMarks}}, </i>
                                {{--semester final grade points--}}
                                @php $semesterMarksPercentage = round((($totalStdObtainedMarks*100)/$totalClassMarks), 2, PHP_ROUND_HALF_UP); @endphp
                                {{--Subject Highest Marks array list --}}
                                @php $subHighestMarkArrayList = array_unique(array_values($meritList)); @endphp
                                <i>Highest Marks: {{count($subHighestMarkArrayList)>0?$subHighestMarkArrayList[0]:'N/A'}}, </i>
                                <i>  Percent: {{$semesterMarksPercentage}}% </i>

                                @if($resultFailedSubjectCount>0)
                                    <i>, Failed in {{$resultFailedSubjectCount}} {{$resultFailedSubjectCount>1?'subjects':'subject'}} </i>
                                @else
                                    @php $semesterGradePoint = (array)subjectGradeCalculation((int)$semesterMarksPercentage, $gradeScaleDetails); @endphp
                                    {{--<i> GPA: {{$semesterGradePoint['point']}}, </i>--}}
                                    @php $semesterMeritPosition=array_key_exists($studentInfo->id, $meritList)?(array_search($studentInfo->id, array_keys($meritList))+1):' N/A' @endphp

                                    {{--                                    <i> Merit Position: {{$semesterMeritPosition}}</i>--}}
                                @endif
                                @php $myResult[$allSemester[$m]['id']] = (object)['total_points'=>$gradeResult['total_points'],'total_gpa'=>$gradeResult['total_gpa']]; @endphp
                            </th>
                        </tr>

                        </tbody>
                    </table>
                </div>

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

                <div class="clear" style="width: 100%; margin-top: 20px;">
                    <div id="report-comments">
                        <table width="80%" class="table text-center" cellpadding="1" style="line-height: 20px;">
                            <tr>
                                <th width="100px">Section Position </th>
                                <th width="10px">: </th>
                                <th width="15px">{{$semesterMeritPosition}}</th>
                                <th width="100px">GPA </th>
                                <th width="10px">: </th>
                                <th width="15px">{{$semesterGradePoint['point']}}</th>
                                <th width="100px">Status </th>
                                <th width="10px">: </th>
                                <th width="15px">-</th>
                            </tr>
                            <tr>
                                <th>Class Position </th>
                                <th width="10px">: </th>
                                <th width="15px">{{$semesterMeritPosition}}</th>
                                <th>Grade </th>
                                <th width="10px">: </th>
                                <th width="15px">-</th>
                                <th>Attendance </th>
                                <th width="10px">: </th>
                                <th width="15px">
                                    {{$myAttendanceInfo?$myPresentDays:'-'}}
                                </th>
                            </tr>
                            <tr>
                                <td colspan="9"><b>Very Good! Thanks</b></td>
                            </tr>
                        </table>
                    </div>
                    <div id="qr-code">

                        @php $qrCode="Name:".$stdFullName.", Total: ".$totalClassMarks.", Obtained: ".$totalStdObtainedMarks.", Percent: ".$semesterMarksPercentage."%, GPA: ".$semesterGradePoint['point'].", Merit Position: ".$semesterMeritPosition @endphp

                        <div class="qrcode">
                            {{--<img style="margin-left: 30px; width: 100px; height: 100px; margin-top: -15px" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($qrCode))!!} ">--}}
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
            <br/>

            @if(!empty($myResult))
                <div class="clear" style="width: 100%;">
                    <div style="float: left; width: 30%; text-align:center; padding: 20px;">
                        <span style="display:{{($reportCardSetting AND $reportCardSetting->parent_sign==1)?'block':'block'}}">
                            <span class="singnature"> </span><br>
                        ............................................<br>
                            <strong>Parent Signature</strong>
                        </span>
                    </div>

                    <div style="float: left; width: 30%; text-align:center; padding: 20px">
                        <span style="display:{{($reportCardSetting AND $reportCardSetting->teacher_sign==1)?'block':'block'}}">
                            <span class="singnature"></span><br>
                        ............................................<br>
                            <strong>Teacher Signature</strong>
                        </span>
                    </div>


                    <div style="float: right; width: 30%; text-align:center; padding: 20px">
                        <span style="display:{{($reportCardSetting AND $reportCardSetting->teacher_sign==1)?'block':'block'}}">
                            <span class="singnature"></span><br>
                        ............................................<br>
                        <strong>Principal / Head Teacher</strong>
                        </span>
                    </div>
                </div>
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
</body>
</html>

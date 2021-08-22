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

        #std-info {
            float:left;
            width: 79%;
        }
        #std-photo {
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

        body{
            font-size: 13px;
        }

        .report_card_table{
            border: 1px solid #dddddd;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<!-- attendance Report -->
@if(count($semesterResultSheet)>0 && count($allSemester)>0)
    {{--myResult For Calculating Final Result--}}
    @php $myResult = array(); @endphp
    {{--semester looping--}}
    @for($m=0; $m<count($allSemester); $m++)
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
            <div id="std-info">
                <table width="100%" cellpadding="1" style="font-size:14px">
                    <tr>
                        <th width="20%">Name of Student</th>
                        <th width="2%">:</th>
                        <td><b>{{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</b></td>
                    </tr>
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
                        <th>Class </th>
                        <th>:</th>
                        {{--student enrollment--}}
                        @php $enrollment = $studentInfo->enroll(); @endphp
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
            </div>
            <div id="std-photo" style="margin-left:562px;">
                {{--@if($studentInfo->singelAttachment('PROFILE_PHOTO'))--}}
                {{--<img src="{{public_path().'/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name}}"  style="width:130px;height:125px">--}}
                {{--@else--}}
                {{--<img  src="{{public_path().'/assets/users/images/user-default.png'}}" style="width:130px;height:125px">--}}
                {{--@endif--}}

                <table width="100%" style="font-size: 10px;" border="1px solid" class="text-center report_card_table" cellpadding="2">
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
        @if(array_key_exists($allSemester[$m]['id'], $semesterResultSheet)==true)

            @php
                $stdSubjectGrades = $semesterResultSheet[$allSemester[$m]['id']];
				// semester subject highest marks sheet
				$semesterAssessmentArrayList = $subjectHighestMarksList[$allSemester[$m]['id']];
				$subjectHighestMarksArrayList = $semesterAssessmentArrayList['subject_highest_marks'];
				$meritList = $semesterAssessmentArrayList['merit_list'];
				$extraHighestMarksArrayList = $semesterAssessmentArrayList['extra_highest_marks'];
				$meritListWithExtraMark = $semesterAssessmentArrayList['merit_list_with_extra_mark'];
				$classSectionExtraCategory = 0;
				// semester attendance sheet
				$semesterAttendanceList = $semesterAttendanceSheet[$allSemester[$m]['id']];
				// semester extraBook mark sheet
				$stdExtraBookArrayList = $stdExtraBookMarkSheet[$allSemester[$m]['id']];
            @endphp


            @if(count($stdSubjectGrades)>0)
                <div class="clear" style="width: 100%">
                    {{--@if($stdSubjectGrades == null) @continue @endif--}}
                    <p class="label text-center row-second">{{$allSemester[$m]['name']}} ({{date('d M, Y', strtotime($allSemester[$m]['start_date']))." - ".date('d M, Y', strtotime($allSemester[$m]['end_date']))}}) </p>
                    {{--<table class="report_card_table">--}}
                    <table width="100%" border="1px solid" class="text-center report_card_table">
                        <thead>
                        <tr class="text-center row-second" style="line-height: 18px;">
                            <th>Subject</th>
                            <th>Full Marks</th>
                            <th>Obtained Marks</th>
                            <th>%</th>
                            <th>Grade</th>
                            <th>Point</th>
                        </tr>
                        </thead>
                        <tbody id="report_card_table_body" class="text-center">
                        @php
                            // semester result
                            $allSubTotalMarks = 0;
                            $allSubObtainedMarks = 0;
                            $gradeResult = $stdSubjectGrades['result'];
                            $creditIncompleteCounter = 0;
                            $totalPoints = 0;
                            $obtainedPoints = 0;
                            // general subject list
                             $generalSubjectList = $stdSubjectGrades[0];

                            // semester grade list for group subject
                            foreach ($subjectGroupList as $subGroupId=>$subGroupDetails){
                                // group subject details
                                $subGroupId = $subGroupDetails['s_g_id'];
                                $subGroupName = $subGroupDetails['s_g_name'];
                                $groupSubList = $subGroupDetails['s_list'];

                                // checking subGroupId exits or not
                                if(array_key_exists($subGroupId, $stdSubjectGrades)==true){
                                    // group grade list
                                    $groupGradeList = $stdSubjectGrades[$subGroupId];
                                    // subject group average grade list
                                    $subAverageGrade = ['total'=>0, 'obtained'=>0, 'letterGrade'=>0];
                                    // looping
                                    for($mm=0; $mm<count($groupSubList); $mm++){
                                        // singleSubject
                                        $singleSubject = $groupSubList[$mm];
                                        #sub details
                                        $subId = $singleSubject['sub_id'];
                                        // subject grade list
                                        if(array_key_exists($subId, $groupGradeList)==true){
                                            $sub_grade = $groupGradeList[$subId];
                                            // grade details
                                            $subAverageGrade['total'] += $sub_grade['total'];
                                            $subAverageGrade['obtained'] += $sub_grade['obtained'];
                                            if($sub_grade['letterGrade']=='F') $subAverageGrade['letterGrade'] = 1;
                                        }else{
                                           $subAverageGrade['total'] = 0;
                                           $subAverageGrade['obtained'] = 0;
                                           $subAverageGrade['letterGrade'] = 0;
                                        }
                                    }
                                    // checking
                                    if($subAverageGrade['total']==0 AND $subAverageGrade['obtained']==0 AND $subAverageGrade['letterGrade']==0){
                                        echo '<tr><td>'.$subGroupName.'</td><td colspan="5"> All subject result not published </td></tr>';
                                    }else{
                                        $totalMarks = $subAverageGrade['total']/count($groupSubList);
                                        $obtainedMarks = $subAverageGrade['obtained']/count($groupSubList);
                                        $percentage = (int)(($obtainedMarks/$totalMarks)*100);
                                        $letterGradeDetails = (object)subjectGradeCalculation($percentage, $gradeScaleDetails);

                                        echo '<tr>';
                                        echo '<td>'.$subGroupName.'</td>';
                                        echo '<td>'.$totalMarks.'</td>';
                                        echo '<td>'.$obtainedMarks.'</td>';
                                        echo '<td>'.$percentage.' %</td>';
                                        echo '<td>'.(($subAverageGrade['letterGrade']==1)?'F':($letterGradeDetails?$letterGradeDetails->grade:'N/A')).'</td>';
                                        echo '<td>'.($letterGradeDetails?((($subAverageGrade['letterGrade']==1)?0:$letterGradeDetails->point)):'N/A').'</td>';
                                        echo '</tr>';

                                        // total point count
                                        $totalPoints += ($letterGradeDetails?$letterGradeDetails->max_point:0);
                                        // checking incomplete
                                        if($subAverageGrade['letterGrade']==1){
                                            $creditIncompleteCounter += $letterGradeDetails?($letterGradeDetails->max_point):0;
                                        }else{
                                            // obtained point count
                                            $obtainedPoints += $letterGradeDetails?($letterGradeDetails->point*$letterGradeDetails->max_point):0;
                                        }

                                        // calculating total marks
                                        $allSubTotalMarks += $totalMarks;
                                        $allSubObtainedMarks += $obtainedMarks;
                                    }
                                }else{
                                    echo '<tr><td>'.$subGroupName.'</td><td colspan="5"> Subject result not published </td></tr>';
                                }
                            }

                            // general subject list looping
                            foreach ($generalSubjectList as $subId=>$subjectDetails){
                                echo "<tr>";
                                echo "<td>".$subjectDetails['sub_name']."</td>";
                                echo "<td>".$subjectDetails['total']."</td>";
                                echo "<td>".$subjectDetails['obtained']."</td>";
                                echo "<td>".$subjectDetails['percentage']."  %</td>";
                                echo "<td>".$subjectDetails['letterGrade']."</td>";
                                echo "<td>".$subjectDetails['letterGradePoint']."</td>";
                                echo "</tr>";

                                // calculating total marks
                                $allSubTotalMarks += $subjectDetails['total'];
                                $allSubObtainedMarks += $subjectDetails['obtained'];
                            }
                        @endphp

                        {{--checking--}}
                        @if($totalPoints>0 AND $obtainedPoints>0)
                            @php
                                $precision = 3;
                                $percentage = $totalPoints==0?0:(substr(number_format((($allSubObtainedMarks*100)/$allSubTotalMarks), $precision+1, '.', ''), 0, -1));
                                $semesterLetterGradeDetails = (object)subjectGradeCalculation($percentage, $gradeScaleDetails);
                            @endphp
                            <tr>
                                <td colspan="6">
                                    <table width="80%" style="text-align: center; margin: 0 auto;">
                                        <thead>
                                        <tr>
                                            <th> Total : {{$allSubTotalMarks}}</th>
                                            <th>Obtained : {{$allSubObtainedMarks}} ({{$percentage}} %)</th>
                                            <th>Grade: {{$subAverageGrade['letterGrade']==0?($semesterLetterGradeDetails?$semesterLetterGradeDetails->grade:'N/A'):'F'}}</th>
                                        </tr>
                                        </thead>
                                    </table>

                                </td>
                            </tr>
                        @endif
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
                                    {{--$categoryWAMark--}}
                                    @php $categoryWAMark = array_key_exists($category->id, $weightedAverageArrayList)==true?$weightedAverageArrayList[$category->id]:0; @endphp
                                    {{--checking $categoryWAMark--}}
                                    @if($categoryWAMark==0)
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
                                        {{--$categoryWAMark--}}
                                        @php $categoryWAMark = array_key_exists($category->id, $weightedAverageArrayList)==true?$weightedAverageArrayList[$category->id]:0; @endphp


                                        {{--checking $categoryWAMark--}}
                                        @if($categoryWAMark==0)
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
                                    <tr>
                                        <th colspan="{{(2+$assessmentTotalCount)}}">
                                            <i>Total: {{$resultTotalMarks+$assessmentTotalMarks}}, </i>
                                            <i>Obtained: {{$resultObtainedMarks+$assessmentTotalObtainedMarks}}, </i>
                                            {{--calculating marks percentage--}}
                                            @php $semesterFinalMarksPercentage = (($resultObtainedMarks+$assessmentTotalObtainedMarks)/($resultTotalMarks+$assessmentTotalMarks))*100; @endphp
                                            <i> Percent: {{(int)$semesterFinalMarksPercentage}}%, </i>
                                            @if($resultFailedSubjectCount>0)
                                                <i>
                                                    Failed in {{$resultFailedSubjectCount}} {{count($resultFailedSubjectCount)>1?'subjects':'subject'}}
                                                </i>
                                            @else
                                                @php $semesterFinalGradePoint = subjectGradeCalculation((int)$semesterFinalMarksPercentage, $gradeScaleDetails); @endphp
                                                <i> GPA: {{$semesterFinalGradePoint['point']}} , </i>
                                                <i> Merit Position: {{array_key_exists($studentInfo->id, $meritListWithExtraMark)?(array_search($studentInfo->id, array_keys($meritListWithExtraMark))+1):' N/A'}} </i>
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




            @else
                <div class="row">
                    <p class="label text-center">Report Card </p>
                    <p class="text-center">
                        <b>Result not published</b><br/>
                    </p>
                </div>
                @break
            @endif
            <br/>
        @endif

        <div class="clear" style="width: 100%; margin-top: 50px;">
            <div style="float: left; width: 50%; text-align:center">
                .........................................<br>
                <strong>Parents / Guardian</strong>
            </div>
            <div style="float: left; width: 50%; text-align:center;">
                ...................................................<br>
                <strong>Principal / Head Teacher</strong>
            </div>
        </div>

        {{--checking--}}
        @if(count($allSemester)>1 AND $m<count($allSemester))
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

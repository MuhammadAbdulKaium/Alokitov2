
<style type="text/css">
    table>thead>tr>th, table>tbody>tr>td{
        text-align: center;
        height: 20px;
    }
</style>

{{--// find cat exam and pass marks array list--}}
@php $catExamPassMarksList = (array)(array_key_exists('cat_list', $subjectAssessmentArrayList)?$subjectAssessmentArrayList['cat_list']:[]); @endphp

<table>
    <thead>
    <tr>
        <th>Mark ID</th>
        <th>Std ID</th>
        <th>Roll</th>
        <th>Name</th>
        {{--assessment list--}}
        @if($gradeScale->assessmentsCount()>0)
            @if($gradeScale->assessmentCategory())
                {{--category list looping--}}
                @foreach($gradeScale->assessmentCategory() as $category)
                    {{--checking category type is_sba on not--}}
                    @if($category->is_sba==0)

                        {{--find single category exam pass marks--}}
                        @php
                            $myCatExamPassMarks = (array)(array_key_exists($category->id, $catExamPassMarksList)?$catExamPassMarksList[$category->id]:[]);
							// category exam marks
							$catExamMarks = $myCatExamPassMarks?$myCatExamPassMarks['exam_mark']:0;
							// subject ass array list
							$myAssExamPassMarks = $myCatExamPassMarks?$myCatExamPassMarks['ass_list']:[];
							// Category All Assessments list
							$assessmentList = $category->allAssessment($gradeScale->id);
                        @endphp

                        {{--checking $categoryWAMark--}}
                        @if($catExamMarks>0 AND $assessmentList->count()>0)
                            {{--assessment list looping--}}
                            @foreach($assessmentList as $assessment)
                                {{--find assessment point--}}
                                @php $myAssessmentPoints = array_key_exists($assessment->id, $myAssExamPassMarks)==true?$myAssExamPassMarks[$assessment->id]['exam_mark']:0;  @endphp
                                {{--checking--}}
                                @if($myAssessmentPoints>0)
                                    <th>{{$assessment->name}}</th>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach
            @endif
        @endif
    </tr>
    </thead>
    <tbody>
    @if($subjectGrades)
        {{--for old grade book--}}

        @php $allGrades = $subjectGrades['grade']; @endphp
        {{--grade list looping--}}
        @for($i=0; $i<count($allGrades); $i++)
            <tr>
                @php $stdGrade = $allGrades[$i];  @endphp
                @php $stdMarks = (array)$stdGrade['mark'];  @endphp
                {{--std grade details--}}
                <td>{{$stdGrade['mark_id']}}</td>
                <td>{{$stdGrade['std_id']}}</td>
                <td>{{$stdGrade['gr_no']}}</td>
                <td>{{$stdGrade['std_name']}}</td>
                {{--assessment category checking--}}
                @if($gradeScale->assessmentCategory())
                    @foreach($gradeScale->assessmentCategory() as $category)
                        {{--checking category type is_sba on not--}}
                        @if($category->is_sba==0)

                            {{--find single category exam pass marks--}}
                            @php
                                $myCatExamPassMarks = (array)(array_key_exists($category->id, $catExamPassMarksList)?$catExamPassMarksList[$category->id]:[]);
								// category exam marks
								$catExamMarks = $myCatExamPassMarks?$myCatExamPassMarks['exam_mark']:0;
								// subject ass array list
								$myAssExamPassMarks = $myCatExamPassMarks?$myCatExamPassMarks['ass_list']:[];
								// Category All Assessments list
								$assessmentList = $category->allAssessment($gradeScale->id);
                            @endphp

                            {{--checking $categoryWAMark--}}
                            @if($catExamMarks>0 AND $assessmentList->count()>0)
                                {{--category id--}}
                                @php $catId = 'cat_'.$category->id; @endphp

                                {{--assessment looping--}}
                                @foreach($assessmentList as $assessment)
                                    {{--find assessment point--}}
                                    @php $myAssessmentPoints = array_key_exists($assessment->id, $myAssExamPassMarks)==true?$myAssExamPassMarks[$assessment->id]['exam_mark']:0;  @endphp
                                    {{--checking--}}
                                    @if($myAssessmentPoints>0)
                                        @php
                                            $assId = 'ass_'.$assessment->id;
											if($stdMarks AND array_key_exists($catId, $stdMarks)){
												if(array_key_exists($assId, $stdMarks[$catId])){
													$assProfile = $stdMarks[$catId]->$assId;
												}else{ $assProfile = null;}
											}else{ $assProfile = null;}
                                        @endphp
                                        {{--ass marks--}}
                                        <td>{{$assProfile?$assProfile->ass_mark:''}}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                @endif
            </tr>
        @endfor
    @else
        {{--for new gradebook--}}
        @for($i=0; $i<count($studentList); $i++)
            <tr>
                <td>0</td>
                <td>{{$studentList[$i]['id']}}</td>
                <td>{{$studentList[$i]['gr_no']}}</td>
                <td>{{$studentList[$i]['name']}}</td>
                {{--assessment list--}}
                @if($gradeScale->assessmentsCount()>0)
                    @if($gradeScale->assessmentCategory())
                        @foreach($gradeScale->assessmentCategory() as $category)
                            {{--checking category type is_sba on not--}}
                            @if($category->is_sba==0)
                                {{--find single category exam pass marks--}}
                                @php
                                    $myCatExamPassMarks = (array)(array_key_exists($category->id, $catExamPassMarksList)?$catExamPassMarksList[$category->id]:[]);
									// category exam marks
									$catExamMarks = $myCatExamPassMarks?$myCatExamPassMarks['exam_mark']:0;
									// subject ass array list
									$myAssExamPassMarks = $myCatExamPassMarks?$myCatExamPassMarks['ass_list']:[];
									// Category All Assessments list
									$assessmentList = $category->allAssessment($gradeScale->id);
                                @endphp

                                {{--checking $categoryWAMark--}}
                                @if($catExamMarks>0 AND $assessmentList->count()>0)
                                    {{--assessment list looping--}}
                                    @foreach($allAssessmentList as $assessment)
                                        {{--find assessment point--}}
                                        {{--find assessment point--}}
                                        @php $myAssessmentPoints = array_key_exists($assessment->id, $myAssExamPassMarks)==true?$myAssExamPassMarks[$assessment->id]['exam_mark']:0;  @endphp
                                        {{--checking--}}
                                        @if($myAssessmentPoints>0)
                                            <td></td>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    @endif
                @endif
            </tr>
        @endfor
    @endif
    </tbody>
</table>
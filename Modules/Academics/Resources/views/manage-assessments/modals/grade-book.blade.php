<h4 class="text-center bg-green">Grade Book</h4>
<style>
    .input-group { min-width: 130px;}
    .input-group-roll { min-width: 50px;}
    .input-group-std { min-width: 200px;}
</style>

@if(count($studentList)>0)
    @if(!(empty($gradeScale)))
        {{--checking subject assessment array list--}}
        @if(!empty($subjectAssessmentArrayList))
            {{--// find cat exam and pass marks array list--}}
            @php $catExamPassMarksList = (array)(array_key_exists('cat_list', $subjectAssessmentArrayList)?$subjectAssessmentArrayList['cat_list']:[]); @endphp
            <div class="col-md-12">
                <p id="grade_book_export_import_btn" class="text-right {{$subjectGrades == null?'hide':''}}">

                    {{--checking semester result status--}}
                    @if($examStatus->status==0)
                        <a class="btn btn-success" href="/academics/manage/assessments/gradebook/import/" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square" aria-hidden="true"></i> Import</a>
                    @endif
                    <button type="submit" id="grade_book_export_btn" class="btn btn-success"><i class="fa fa-plus-square" aria-hidden="true"></i> Export</button>
                </p>


                <form id="grade_book_setup_form"  style="overflow: scroll">
                    <table id="grade_book_setup_table" class="table table-bordered table-striped text-center scroll">
                        {{--<input id="grade_batch" type="hidden" name="_token" value="{{csrf_token()}}"/>
                       --}} <thead id="grade_book_setup_table_head">
                        <tr class="bg-info text-center">
                            {{--student Name and id th--}}
                            <th width="250">
                                <p class="bg-gray-active">#</p>
                                <table cellpadding="10">
                                    <tbody>
                                    <tr>
                                        {{--student id--}}
                                        <td style="width: 70px">
                                            <input type="text" readonly class="form-control text-center input-group-roll" value="Roll">
                                        </td>
                                        {{--student Name--}}
                                        <td style="width: 250px">
                                            <input type="text" readonly class="form-control text-center input-group-std" value=" Student Name">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </th>

                            @php $categoryIds = array(); @endphp
                            @if($gradeScale->assessmentsCount()>0)
                                @if($gradeScale->assessmentCategory())
                                    @foreach($gradeScale->assessmentCategory() as $category)

                                        {{--checking--}}
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
                                                {{--category ids--}}
                                                @php $categoryIds[] = $category; @endphp
                                                <th>
                                                    {{--category Name--}}
                                                    <p class="bg-gray-active">{{$category->name}}</p>
                                                    <table width="100%" class="table-bordered" cellpadding="10">
                                                        <tbody>
                                                        <tr>
                                                            @foreach($assessmentList as $assessment)
                                                                @php $myAssessmentPoints = array_key_exists($assessment->id, $myAssExamPassMarks)==true?$myAssExamPassMarks[$assessment->id]['exam_mark']:0;  @endphp
                                                                    {{--checking assessment exam marks--}}
                                                                    @if($myAssessmentPoints>0)
                                                                    <td>
                                                                        <input type="text" readonly class="form-control text-center" value="{{$assessment->name}}">
                                                                    </td>
                                                                @endif
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

                            {{--checking exam status--}}
                            @if($examStatus->status==0)
                                <th width="100px" id="grade_book_setup_table_head_action">
                                    <p class="bg-gray-active">#</p>
                                    <input type="text" readonly class="form-control text-center" value="Action">
                                </th>
                            @endif
                        </tr>
                        </thead>



                        <tbody id="grade_book_setup_table_body">
                        {{--Grade BooK Inputs--}}
                        @if($allGrades==null)
                            @for($i=0; $i<count($studentList); $i++)
                                {{--student id--}}
                                @php
                                    $stdId = $studentList[$i]['id'];

                                    if(array_key_exists($stdId, (array)$subjectGrades)){
                                        $stdGrades = $subjectGrades[$stdId];
                                        $stdMarks = $stdGrades['mark'];

                                    }else{
                                        $stdGrades = null;
                                        $stdMarks = null;
                                    }
                                @endphp

                                {{--student assessment row--}}
                                <tr id="row_{{$studentList[$i]['id']}}">
                                    <input type="hidden" name="grade[std_count]" value="{{count($studentList)}}"/>
                                    <input type="hidden" {{'name=grade[std_list][std_'.($i+1).']'}} value="{{$studentList[$i]['id']}}"/>
                                    {{--student Name and id td--}}
                                    <td width="250">
                                        <table width="100%" class="table-bordered" cellpadding="10">
                                            <tbody>
                                            <tr>
                                                {{--student id--}}
                                                <td style="width: 70px">
                                                    <input type="text" readonly  value="{{$studentList[$i]['gr_no']}}" class="form-control text-center input-group-roll">
                                                </td>
                                                {{--student Name--}}
                                                <td style="width: 250px">
                                                    <input type="text" readonly  value="{{$studentList[$i]['name']}}" class="form-control input-group-std">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>

                                    @if($gradeScale->assessmentCategory())
                                        <input type="hidden" {{'name=grade[std_'.$studentList[$i]['id'].'][std_id]'}} value="{{$studentList[$i]['id']}}"/>
                                        <input id="std_mark_{{$studentList[$i]['id']}}" type="hidden" {{'name=grade[std_'.$studentList[$i]['id'].'][mark_id]'}} value="{{$stdGrades?$stdGrades['mark_id']:'0'}}"/>
                                        <input type="hidden" {{'name=grade[std_'.$studentList[$i]['id'].'][mark][cat_count]'}} value="{{$gradeScale->assessmentCategory()->count()}}"/>

                                        @php $x=1; @endphp
                                        @foreach($gradeScale->assessmentCategory() as $category)

                                            {{--checking categoryWAMark--}}
                                            @if($category->is_sba==0)

                                                {{--find single category exam pass marks--}}
                                                @php
                                                    $myCatExamPassMarks = (array)(array_key_exists($category->id, $catExamPassMarksList)?$catExamPassMarksList[$category->id]:[]);
													// category exam marks
													$catExamMarks = $myCatExamPassMarks?$myCatExamPassMarks['exam_mark']:0;
													// subject ass array list
													$myAssExamPassMarks = $myCatExamPassMarks?$myCatExamPassMarks['ass_list']:[];
                                                @endphp


                                                {{--category id--}}
                                                @php $catId = 'cat_'.$category->id; @endphp
                                                {{--student grades--}}

                                                {{-- Category All Asssessments list--}}
                                                @php $assessmentList = $category->allAssessment($gradeScale->id); @endphp
                                                {{--checking--}}
                                                @if($assessmentList->count()>0 AND $catExamMarks>0)
                                                    <td>
                                                        <input type="hidden" {{'name=grade[std_'.$studentList[$i]['id'].'][mark][cat_list][cat_'.$x.']'}} value="{{$category->id}}"/>
                                                        <input type="hidden" {{'name=grade[std_'.$studentList[$i]['id'].'][mark][cat_'.$category->id.'][cat_id]'}} value="{{$category->id}}"/>
                                                        <input type="hidden" {{'name=grade[std_'.$studentList[$i]['id'].'][mark][cat_'.$category->id.'][ass_count]'}} value="{{$category->allAssessment($gradeScale->id)->count()}}"/>
                                                        <table width="100%" class="table-bordered" cellpadding="10">
                                                            <tbody>
                                                            <tr>
                                                                @php $y=1; @endphp
                                                                @foreach($assessmentList as $assessment)
                                                                    {{--assessment id--}}
                                                                    @php
                                                                    $assId = 'ass_'.$assessment->id;
                                                                        if($stdMarks AND array_key_exists($catId, $stdMarks)){
                                                                            if(isset( $stdMarks[$catId]->$assId)){
                                                                                $assProfile = $stdMarks[$catId]->$assId;
                                                                            }else{
                                                                                $assProfile = null;
                                                                            }
                                                                        }else{
                                                                            $assProfile = null;
                                                                        }

                                                                    @endphp

                                                                    {{--find assessment points--}}
                                                                    @php $myAssessmentPoints = array_key_exists($assessment->id, $myAssExamPassMarks)==true?$myAssExamPassMarks[$assessment->id]['exam_mark']:0;  @endphp
                                                                    @if($myAssessmentPoints>0)
                                                                        <input type="hidden" {{'name=grade[std_'.$studentList[$i]['id'].'][mark][cat_'.$category->id.'][ass_list][ass_'.$y.']'}} value="{{$assessment->id}}"/>
                                                                        <td>
                                                                            <input type="hidden" {{'name=grade[std_'.$studentList[$i]['id'].'][mark][cat_'.$category->id.'][ass_'.$assessment->id.'][ass_id]'}} value="{{$assessment->id}}"/>
                                                                            <div class="input-group text-center">
                                                                                <input id="std_ass_mark_{{$studentList[$i]['id']}}" data-id="{{$studentList[$i]['id']}}" type="text"  {{'name=grade[std_'.$studentList[$i]['id'].'][mark][cat_'.$category->id.'][ass_'.$assessment->id.'][ass_mark]'}} maxlength="5" value="{{$stdGrades?($assProfile?$assProfile->ass_mark:''):''}}" data-key="{{$myAssessmentPoints}}" class="form-control mark-input text-center">

                                                                                <input type="hidden" {{'name=grade[std_'.$studentList[$i]['id'].'][mark][cat_'.$category->id.'][ass_'.$assessment->id.'][ass_points]'}} value="{{$myAssessmentPoints}}"/>

                                                                                {{--<div class="input-group-addon">{{" / ".$assessment->points}}</div>--}}
                                                                                <div class="input-group-addon">{{" / ".$myAssessmentPoints}}</div>
                                                                            </div>
                                                                        </td>
                                                                    @endif
                                                                    @php $y++; @endphp
                                                                @endforeach
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                @endif
                                            @endif
                                            @php $x++; @endphp
                                        @endforeach
                                    @endif

                                    @if($examStatus->status==0)
                                        <td>
                                            <p id="btn_{{$studentList[$i]['id']}}" data-key="{{$stdGrades?'update':'store'}}" class="btn {{$stdGrades?'btn-success update':'btn-info'}}">{{$stdGrades?'Assigned':'Not Assigned'}}</p>
                                            <input type="hidden" id="grade_{{$studentList[$i]['id']}}" value="{{$stdGrades?$stdGrades['grade_id']:'0'}}"/>
                                            <input type="hidden" id="mark_{{$studentList[$i]['id']}}" value="{{$stdGrades?$stdGrades['mark_id']:'0'}}"/>
                                        </td>
                                    @endif
                                </tr>
                            @endfor
                        @else
                            {{--grade book import section--}}
                            @if($allGrades = json_decode($allGrades, TRUE))
                                @if (is_array($allGrades) || is_object($allGrades))
                                    <?php $gradeCount =1;?>
                                    @foreach($allGrades as $grade)
                                        <tr id="row_{{$grade['std_id']}}">
                                            <input type="hidden" name="grade[std_count]" value="{{count($allGrades)}}"/>
                                            <input type="hidden" {{'name=grade[std_list][std_'.$gradeCount.']'}} value="{{$grade['std_id']}}"/>
                                            {{--student Name and id td--}}
                                            <td width="250">
                                                <table width="100%" class="table-bordered" cellpadding="10">
                                                    <tbody>
                                                    <tr>
                                                        {{--student id--}}
                                                        <td style="width:70px">
                                                            <div class="input-group text-center">
                                                                <input type="text" readonly  value="{{$grade['roll']}}" class="form-control text-center">
                                                            </div>
                                                        </td>
                                                        {{--student Name--}}
                                                        <td style="width: 250px">
                                                            <div class="input-group text-center">
                                                                <input type="text" readonly  value="{{$grade['name']}}" class="form-control ">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>


                                            @if($gradeScale->assessmentCategory())
                                                <input type="hidden" {{'name=grade[std_'.$grade['std_id'].'][std_id]'}} value="{{$grade['std_id']}}"/>
                                                <input type="hidden" {{'name=grade[std_'.$grade['std_id'].'][mark_id]'}} value="{{$grade['mark_id']}}"/>
                                                <input type="hidden" {{'name=grade[std_'.$grade['std_id'].'][mark][cat_count]'}} value="{{$gradeScale->assessmentCategory()->count()}}"/>

                                                @php $x=1; @endphp
                                                @foreach($gradeScale->assessmentCategory() as $category)

                                                    {{--checking $categoryWAMark--}}
                                                    @if($category->is_sba==0)

                                                        {{-- Category All Asssessments list--}}
                                                        @php $assessmentList = $category->allAssessment($gradeScale->id); @endphp
                                                        {{--checking--}}
                                                        @if($assessmentList->count()>0 AND $catExamMarks>0)
                                                            {{--find single category exam pass marks--}}
                                                            @php
                                                                $myCatExamPassMarks = (array)array_key_exists($category->id, $catExamPassMarksList)?$catExamPassMarksList[$category->id]:[];
																// category exam marks
																$catExamMarks = $myCatExamPassMarks?$myCatExamPassMarks['exam_mark']:0;
																// subject ass array list
																$myAssExamPassMarks = $myCatExamPassMarks?$myCatExamPassMarks['ass_list']:[];
                                                            @endphp

                                                            <td>
                                                                <input type="hidden" {{'name=grade[std_'.$grade['std_id'].'][mark][cat_list][cat_'.$x.']'}} value="{{$category->id}}"/>
                                                                <input type="hidden" {{'name=grade[std_'.$grade['std_id'].'][mark][cat_'.$category->id.'][cat_id]'}} value="{{$category->id}}"/>
                                                                {{--@if($category->allAssessment($gradeScale->id))--}}
                                                                <input type="hidden" {{'name=grade[std_'.$grade['std_id'].'][mark][cat_'.$category->id.'][ass_count]'}} value="{{$category->allAssessment($gradeScale->id)->count()}}"/>

                                                                <table width="100%" class="table-bordered" cellpadding="10">
                                                                    <tbody>
                                                                    <tr>
                                                                        @php $y=1; @endphp
                                                                        @foreach($assessmentList as $assessment)
                                                                            {{--find assessment points--}}
                                                                            @php $myAssessmentPoints = array_key_exists($assessment->id, $myAssExamPassMarks)==true?$myAssExamPassMarks[$assessment->id]['exam_mark']:0;  @endphp
                                                                            @if($myAssessmentPoints>0)
                                                                                <input type="hidden" {{'name=grade[std_'.$grade['std_id'].'][mark][cat_'.$category->id.'][ass_list][ass_'.$y.']'}} value="{{$assessment->id}}"/>
                                                                                <td style="width: 250px">
                                                                                    <input type="hidden" {{'name=grade[std_'.$grade['std_id'].'][mark][cat_'.$category->id.'][ass_'.$assessment->id.'][ass_id]'}} value="{{$assessment->id}}"/>
                                                                                    <div class="input-group text-center">
                                                                                        @php $assMarkValue = $grade[strtolower(preg_replace('/(\W)*[^A-Za-z0-9]/', '_', $assessment->name))]; @endphp
                                                                                        @php $assPointValue = $myAssessmentPoints; @endphp
                                                                                        <input style="{{$assMarkValue<=$assPointValue?'':'background-color:pink'}}" type="text" {{'name=grade[std_'.$grade['std_id'].'][mark][cat_'.$category->id.'][ass_'.$assessment->id.'][ass_mark]'}} maxlength="5" value="{{$assMarkValue<=$assPointValue?$assMarkValue:''}}" class="form-control mark-input text-center" data-key="{{$assPointValue}}">
                                                                                        <input type="hidden" {{'name=grade[std_'.$grade['std_id'].'][mark][cat_'.$category->id.'][ass_'.$assessment->id.'][ass_points]'}} value="{{$assPointValue}}"/>
                                                                                        {{--<div class="input-group-addon">{{" / ".$assessment->points}}</div>--}}
                                                                                        <div class="input-group-addon">{{" / ".$assPointValue}}</div>
                                                                                    </div>
                                                                                </td>
                                                                            @endif
                                                                            @php $y++; @endphp
                                                                        @endforeach
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        @endif
                                                    @endif
                                                    @php $x++; @endphp
                                                @endforeach
                                            @endif
                                            {{--row update action btn--}}
                                            @if($examStatus->status==0)
                                                <td>
                                                    <table width="100%" class="table-bordered" cellpadding="10">
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <p id="btn_{{$grade['std_id']}}" data-key="store" class="btn btn-info">Not Assigned</p>
                                                                <input type="hidden" id="grade_{{$grade['std_id']}}" value="0"/>
                                                                <input type="hidden" id="mark_{{$grade['std_id']}}" value="{{$grade['mark_id']}}"/>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            @endif
                                        </tr>
                                        @php $gradeCount++; @endphp
                                    @endforeach
                                @else
                                    hello
                                @endif
                            @endif
                        @endif
                        </tbody>
                    </table>



                    {{--checking semester result status--}}
                    @if($examStatus->status==0)

                        @if(isset($resultModule) && ($resultModule->count()>0))
                            <div class="form-group">
                                <button class="btn btn-success autoMaticSms" data-toggle="modal" data-target="#autoMaticSms" type="button">Send Auto Matic SMS </button>
                            </div>
                        @endif


                        <div id="assesmentHtml"></div>

                        <div class="modal-footer">
                            <button id="grade_book_setup_table_reset_btn" type="reset" class="btn btn-default pull-left">Reset</button>
                            <button id="grade_book_setup_table_submit_btn" type="submit" class="btn btn-success">Submit</button>
                        </div>
                    @endif
                    <input type="hidden" id="ass_value" name="ass_value" value="0"/>

                    @if($gradeScale->assessmentsCount()>0)
                        @if($gradeScale->assessmentCategory())
                            <input type="hidden" name="category_id_list" value="@for($i = 0 ; $i<count($categoryIds); $i++){{$categoryIds[$i]->id}},@endfor"/>
                            @for($i = 0 ; $i<count($categoryIds); $i++)
                                @php $category = $categoryIds[$i]; @endphp

                                @if($category->allAssessment($gradeScale->id))

                                    {{--find single category exam pass marks--}}
                                    @php
                                        $myCatExamPassMarks = (array)(array_key_exists($category->id, $catExamPassMarksList)?$catExamPassMarksList[$category->id]:[]);
										// category exam marks
										$catExamMarks = $myCatExamPassMarks?$myCatExamPassMarks['exam_mark']:0;
										// subject ass array list
										$myAssExamPassMarks = $myCatExamPassMarks?$myCatExamPassMarks['ass_list']:[];
                                    @endphp

                                    @php $myAssessmentPoints = array_key_exists($assessment->id, $myAssExamPassMarks)?$myAssExamPassMarks[$assessment->id]['exam_mark']:0;  @endphp
                                    {{--@if($myAssessmentPoints>0)--}}
                                    <input type="hidden" id="assCategory_{{$category->id}}"  name="assCategory_{{$category->id}}[]" data-key="" value="">
                                    {{--@endif--}}
                                @endif
                            @endfor
                        @endif
                    @endif
                </form>
            </div>


            {{--checking semester result status--}}
            @if($examStatus->status==0)
                <!-- Modal -->
                <div id="autoMaticSms" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Send Auto Matic SMS</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group" id="resultGradeBookSelect">
                                    <div class="assesment-checkbox">
                                        @if($gradeScale->assessmentsCount()>0)
                                            @if($gradeScale->assessmentCategory())
                                                @foreach($gradeScale->assessmentCategory() as $category)
                                                    {{--// Category All Assessments list--}}
                                                    @php $assessmentList = $category->allAssessment($gradeScale->id); @endphp

                                                    {{-- Category All Asssessments --}}
                                                    @if($category->is_sba==0 AND $assessmentList->count()>0)

                                                        {{--find single category exam pass marks--}}
                                                        @php
                                                            $myCatExamPassMarks = (array)(array_key_exists($category->id, $catExamPassMarksList)?$catExamPassMarksList[$category->id]:[]);
															// category exam marks
															$catExamMarks = $myCatExamPassMarks?$myCatExamPassMarks['exam_mark']:0;
															// subject ass array list
															$myAssExamPassMarks = $myCatExamPassMarks?$myCatExamPassMarks['ass_list']:[];
                                                        @endphp

                                                        @foreach($assessmentList as $assessment)
                                                            {{--find assessment points--}}
                                                            @php $myAssessmentPoints = array_key_exists($assessment->id, $myAssExamPassMarks)?$myAssExamPassMarks[$assessment->id]['exam_mark']:0;  @endphp
                                                            @if($myAssessmentPoints>0)
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" onchange="javascript:ass_value_change(this)"class="gateData" name="assValue[]" data-key="{{$category->id}}" value="{{$assessment->id}}"> {{$assessment->name}} </label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary gradeBookSelectSave ">Submit</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        @else
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div id="w0-success-0" class="alert-warning alert-auto-hide text-center alert fade in" style="opacity: 374.435;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-warning"></i> There is no <b>Subject Assessment</b> setting for this <b>Batch</b> and <b>Section</b></h5>
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
@else
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div id="w0-success-0" class="alert-warning alert-auto-hide text-center alert fade in" style="opacity: 374.435;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="fa fa-warning"></i> There is no student for this <b>Batch</b> and <b>Section</b></h5>
            </div>
        </div>
    </div>
@endif




<script type="text/javascript">
    $(function() {
        $('form#grade_book_setup_form').on('submit', function (e) {
            e.preventDefault();
            // grade scale
            var grade_scale = '{{$scale}}';
            // append academics details
            var grade_book_setup_form = $(this)
                .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                .append('<input type="hidden" name="section" value="'+$('#section').val()+'"/>')
                .append('<input type="hidden" name="subject" value="'+$('#subject').val()+'"/>')
                .append('<input type="hidden" name="semester" value="'+$('#semester').val()+'"/>')
                .append('<input type="hidden" name="grade_scale" value="'+grade_scale+'"/>');
            var dataJson = $(grade_book_setup_form).serializeArray();
            //alert(dataJson.length);
            dataJson = objectifyForm(dataJson);


            $.ajax({
                type: 'post',
                cache: false,
                url: '/academics/manage/assessments/gradebook/store',
                data: {_token : '{{csrf_token()}}', data: dataJson },
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Submitting...');

                },

                success: function (data) {
                    // hide dialog
                    waitingDialog.hide();
                    // statement
                    if(data.status == 'success'){
                        var mark_list = data.std_marks;
                        for(var i=0; i<mark_list.length; i++){
                            var marks_detils = mark_list[i];

                            var std_id = marks_detils['std_id'];
                            var mark_id = marks_detils['mark_id'];
                            var type = marks_detils['type'];
                            if(type=='create'){
                                var grade_id = marks_detils['grade_id'];
                                $('#grade_'+std_id).val(grade_id);
                            }

                            $('#std_mark_'+std_id).val(mark_id);
                            $('#mark_'+std_id).val(mark_id);

                            var std_btn = $('#btn_'+std_id);
                            // change button attributes
                            std_btn.removeClass('btn-info');
                            std_btn.addClass('btn-success');
                            std_btn.html('Assigned');

                            // grade_book_export_import_btn
                            var grade_book_export_import_btn = $('#grade_book_export_import_btn');
                            // checking
                            if(grade_book_export_import_btn.hasClass('hide')){
                                // show the export / import btn
                                grade_book_export_import_btn.removeClass('hide');
                            }
                        }
                        // sweet alert
                        swal("Success", data.msg, "success");
                    }else{
                        // sweet alert
                        swal("Warning", data.msg, "warning");
                    }
                },

                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // sweet alert
                    swal("Error", 'Unable to load data form server', "error");
                }
            });



        });

        $('.update').click(function () {
            // student id
            var std_id = $(this).attr('id').replace('btn_','');
            var grade_id = $('#grade_'+std_id).val();
            var mark_id = $('#mark_'+std_id).val();
            // dynamic html form
            var grade_update =  $('<form id="std_grade_update_form" action="" method="POST"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="std_id" value="'+std_id+'"/>')
                .append('<input type="hidden" name="grade_id" value="'+grade_id+'"/>')
                .append('<input type="hidden" name="mark_id" value="'+mark_id+'"/>')
                .append('<table><tbody><tr>'+$('#row_'+std_id).html()+'</tr></tbody></table>');

            // ajax request
            $.ajax({
                url: '/academics/manage/assessments/gradebook/update/',
                type: 'post',
                cache: false,
                data: $(grade_update).serialize(),
                datatype: 'json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Updating...');
                },

                success:function(data){
                    // hide dialog
                    waitingDialog.hide();
                    // checking
                    if(data.status=='success'){
                        var std_btn = $('#btn_'+std_id);
                        std_btn.removeClass('btn-primary');
                        std_btn.addClass('btn-success');
                        std_btn.html('Assigned');
                        // sweet alert
                        swal("Success", data.msg, "success");
                    }else{
                        // sweet alert
                        swal("Warning", data.msg, "warning");
                    }
                },
                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // sweet alert
                    swal("Error", 'Unable to load data form server', "error");
                }
            });
        });

        // replace input values
        $("#grade_book_setup_table_body input[type='text']").keyup(function(){
            $(this).attr('value', $(this).val());
        });

        // reset btn action
        $('#grade_book_setup_table_reset_btn').click(function () {
            // checking
            if(confirm('Are you sure to reset the gradebook ???')){
                // replace input values
                $("#grade_book_setup_table_body input.mark-input").attr('value', '');
            }
        });

        // replace input values
        $("#grade_book_setup_table_body input.mark-input").keyup(function(){
            var std_id = $(this).attr('data-id');
            $(this).attr('value', $(this).val());
            var std_btn = $('#btn_'+std_id);
            std_btn.removeClass('btn-success');
            std_btn.addClass('btn-primary');
            std_btn.html('Update');


            var my_grade_mark = parseFloat($(this).val());
            // ass marks
            var ass_points = $(this).attr('data-key');
            // checking
            if(my_grade_mark != null && $.isNumeric($(this).val()) && parseFloat(ass_points)>0){
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
                // reset mark
                $(this).val('');
                // reset mark
                $(this).attr('value', '');
                // background-color
                $(this).css("background-color", "pink");
            }
        });

        $('#grade_book_export_btn').click(function () {
            // grade scale
            var grade_scale = '{{$scale}}';
            // dynamic html form
            $('<form id="grade_book_export_form" action="/academics/manage/assessments/gradebook/export" method="POST" style="display:none;"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                .append('<input type="hidden" name="section" value="'+$('#section').val()+'"/>')
                .append('<input type="hidden" name="subject" value="'+$('#subject').val()+'"/>')
                .append('<input type="hidden" name="semester" value="'+$('#semester').val()+'"/>')
                .append('<input type="hidden" name="grade_scale" value="'+grade_scale+'"/>')
                // append to body and submit the form
                .appendTo('body').submit();
            // remove form from the body
            $('#grade_book_export_form').remove();
        });

    });
    /**
     * This function takes serializeArray output and jquery and make it javascript JSON object
     */
    function objectifyForm(formArray) {//serialize data function

        var returnArray = {};
        returnArray['grade'] = {};

        for (var i = 0; i < formArray.length; i++){
            var attr_name = formArray[i]['name'];
            if(attr_name.indexOf("grade[") >= 0){
                attr_name = attr_name.replace("grade[","");
                returnArray['grade'][attr_name] = formArray[i]['value'];
            }else{
                returnArray[attr_name] = formArray[i]['value'];
            }


        }
        return returnArray;
    }

    //romesh

    $(".gradeBookSelectSave").click(function () {
        /*var assesmentHtml= $(".assesment-checkbox").html();
         $("#assesmentHtml").append(assesmentHtml);
         */
        $("#autoMaticSms").modal("hide");

    });

    function ass_value_change(checkboxItem){
        if(checkboxItem.checked){

            var data =[];
            var ass_value_data = $("#assCategory_"+$(checkboxItem).attr('data-key')).val();
            if(ass_value_data.length>0)
                data.push(ass_value_data);
            data.push($(checkboxItem).val());

            $("#assCategory_"+$(checkboxItem).attr('data-key')).val(data);
            var check_assvalue= $("#ass_value").val();
            check_assvalue++;
            $("#ass_value").val(check_assvalue);
        } else{
            var data =[];
            var ass_value_data = $("#assCategory_"+$(checkboxItem).attr('data-key')).val();
            data = ass_value_data.split(",");
            for(var i=0; i<data.length; i++){
                if(data[i] == $(checkboxItem).val()){
                    data.splice(i);
                }
            }

            var check_assvalue= $("#ass_value").val();
            check_assvalue--;
            $("#ass_value").val(check_assvalue);

            $("#assCategory_"+$(checkboxItem).attr('data-key')).val(data);
        }
    }

    //end romesh



</script>


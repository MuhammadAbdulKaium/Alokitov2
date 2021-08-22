<style>
    .input-group {
        min-width: 95px;
        width: 100%;
    }

    .input-group-subject{
        min-width: 250px;
    }

    #setting-table {
        overflow:scroll;
    }

</style>
<h4 class="text-center bg-green">Grade Scale Assessment Marks Assignment</h4>
@if(count($classSubjects)>0)
    @if(!(empty($gradeScale)))
        <div class="col-md-12">
            <form id="subject_assessment_grade_mark_assign" method="POST">
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                @php $subjectAssessmentId = count($subjectAssessmentArrayList)>0?$subjectAssessmentArrayList['sub_ass_id']:0; @endphp
                <input type="hidden" name="sub_ass_id" value="{{$subjectAssessmentId}}">
                <div id="setting-table">
                    <table width="100%"class="table table-bordered table-striped text-center">
                        <thead>
                        <tr class="bg-info text-center">
                            {{--student Name and id th--}}
                            <th>
                                <p class="bg-gray-active">Subject Name</p>
                                <input type="text" readonly class="form-control text-center text-bold" value="#">
                                <input type="text" readonly class="form-control text-center text-bold" value="#">
                            </th>
                            @if($gradeScale->assessmentsCount()>0)
                                @if($gradeScale->assessmentCategory())
                                    @foreach($gradeScale->assessmentCategory() as $category)
                                        {{--checking category type--}}
                                        @if($category->is_sba==0)
                                            {{-- Category All Asssessments list--}}
                                            @php $assessmentList = $category->allAssessment($gradeScale->id); @endphp
                                            {{--checking--}}
                                            @if($assessmentList->count()>0)
                                                <th>
                                                    {{--category Name--}}
                                                    <p class="bg-gray-active text-blue"> {{$category->name}} </p>
                                                    {{--assessment list table--}}
                                                    <table width="100%" class="table-bordered" cellpadding="10">
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="text" readonly class="form-control text-center text-blue" value="Mark"><br/>
                                                                    <input type="text" readonly class="form-control text-center text-blue" value="#">
                                                                </div>
                                                            </td>
                                                            @foreach($assessmentList as $assessment)
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="text" readonly class="form-control text-center text-green" value="{{$assessment->name}}">
                                                                    </div>
                                                                    {{--assessment mark distribution--}}
                                                                    <table width="100%" class="table-bordered" cellpadding="10">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <input type="text" readonly class="form-control text-center text-red" value="Pass Mark">
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <input type="text" readonly class="form-control text-center text-green" value="Exam Mark">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
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
                        <tbody>

                        @foreach($classSubjects as $subject)
                            @php
                                // subject id
								$mySubId = $subject['id'];

								// my / single subject assessment array list
								$mySubjectAssessmentArrayList = [];
								// checking $subjectAssessmentArrayList

								if(count($subjectAssessmentArrayList)>0){
									// checking array key
									if(array_key_exists($mySubId, $subjectAssessmentArrayList['subject_list'])){
										 $mySubjectAssessmentArrayList = $subjectAssessmentArrayList['subject_list'][$mySubId];
									}
								}
									 // checking $subjectAssessmentList
								if($mySubjectAssessmentArrayList){

									 $subjectAssessmentDetailId = array_key_exists('sub_ass_detail_id', $mySubjectAssessmentArrayList)?$mySubjectAssessmentArrayList['sub_ass_detail_id']:0;

									 $subjectCatMarkList = (array) (array_key_exists('cat_list', $mySubjectAssessmentArrayList)?$mySubjectAssessmentArrayList['cat_list']:[]);
								}else{
									 $subjectAssessmentDetailId = 0;
									 $subjectCatMarkList = [];
								}
                            @endphp

                            <tr>
                                <td>
                                    <div class="input-group-subject">
                                        <input type="text" readonly class="form-control text-center" value="{{$subject['name']}}">
                                        <input type="hidden" name="assessment[{{$subject['id']}}][sub_ass_detail_id]" value="{{$subjectAssessmentDetailId}}">
                                    </div>
                                </td>

                                {{--checking assessment count--}}
                                @if($gradeScale->assessmentsCount()>0)
                                    @if($gradeScale->assessmentCategory())
                                        @foreach($gradeScale->assessmentCategory() as $category)
                                            {{--checking category type--}}
                                            @if($category->is_sba==0)
                                                {{-- Category All Asssessments list--}}
                                                @php $assessmentList = $category->allAssessment($gradeScale->id); @endphp
                                                {{--checking--}}
                                                @if($assessmentList->count()>0)
                                                    @php $categoryWAMark = array_key_exists($category->id, $weightedAverageArrayList)==true?$weightedAverageArrayList[$category->id]:0; @endphp

                                                    @php
                                                        $singleCatMarksList = (array)(array_key_exists($category->id, $subjectCatMarkList)==true?$subjectCatMarkList[$category->id]:[]);
														// subject assessment mark list
														$subjectAssMarkList = (array)($singleCatMarksList?$singleCatMarksList['ass_list']:[]);
                                                    @endphp
                                                    <th>
                                                        <table width="100%" class="table-bordered" cellpadding="10">
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <input type="hidden" name="assessment[{{$subject['id']}}][marks][cat_list][{{$category->id}}][pass_mark]" value="0" required>
                                                                        <input type="hidden" name="assessment[{{$subject['id']}}][marks][cat_list][{{$category->id}}][is_count]" value="1" required>

                                                                        <input type="text" class="form-control text-center text-blue" name="assessment[{{$subject['id']}}][marks][cat_list][{{$category->id}}][exam_mark]" maxlength="5" value="{{$singleCatMarksList?$singleCatMarksList['exam_mark']:$categoryWAMark}}" required>
                                                                    </div>
                                                                </td>
                                                                @foreach($assessmentList as $assessment)
                                                                    @php $singleAssMarkList = (array_key_exists($assessment->id, $subjectAssMarkList)?$subjectAssMarkList[$assessment->id]:$mySubjectAssessmentArrayList); @endphp
                                                                    <td>
                                                                        {{--assessment mark distribution--}}
                                                                        <table width="100%" class="table-bordered" cellpadding="10">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control text-center text-red" name="assessment[{{$subject['id']}}][marks][cat_list][{{$category->id}}][ass_list][{{$assessment->id}}][pass_mark]" maxlength="5" value="{{count($singleAssMarkList)>0?array_key_exists('pass_mark',$singleAssMarkList)?$singleAssMarkList['pass_mark']:'0':'0'}}" required>

                                                                                        <input type="hidden" name="assessment[{{$subject['id']}}][marks][cat_list][{{$category->id}}][ass_list][{{$assessment->id}}][is_count]" value="1" required>

                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="input-group">
                                                                                        {{--checking exam mark--}}
                                                                                        @if(array_key_exists('exam_mark',$singleAssMarkList))
                                                                                            <input type="text" class="form-control text-center text-green" name="assessment[{{$subject['id']}}][marks][cat_list][{{$category->id}}][ass_list][{{$assessment->id}}][exam_mark]" maxlength="3" value="{{$singleAssMarkList['exam_mark']}}" required>
                                                                                        @else
                                                                                            <input type="text" class="form-control text-center text-green" name="assessment[{{$subject['id']}}][marks][cat_list][{{$category->id}}][ass_list][{{$assessment->id}}][exam_mark]" maxlength="3" value="{{array_key_exists($assessment->id,$singleAssMarkList)?$singleAssMarkList[$assessment->id]:''}}" required>
                                                                                        @endif
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>

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
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-info pull-left">Reset</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
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
                <h5><i class="fa fa-warning"></i> There is no Subject for this <b>Batch</b> and <b>Section</b></h5>
            </div>
        </div>
    </div>
@endif




<script type="text/javascript">

    $(document).ready(function () {

        // request for section list using batch and section id
        $('form#subject_assessment_grade_mark_assign').on('submit', function (e) {
            // alert('hello');
            e.preventDefault();

            $(this).append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'" />');
            $(this).append('<input type="hidden" name="batch" value="'+$('#batch').val()+'" />');
            $(this).append('<input type="hidden" name="section" value="0" />');
            $(this).append('<input type="hidden" name="grade_scale_id" value="{{!empty($gradeScale)?$gradeScale->id:0}}" />');

            // ajax request
            $.ajax({
                url: "{{ url('/academics/manage/assessments/category/setting/manage') }}",
                type: 'POST',
                cache: false,
                data: $('form#subject_assessment_grade_mark_assign').serialize(),
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
                        // append
                        assessment_table_row.html('');
                        assessment_table_row.append(data.content);
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


    });

</script>


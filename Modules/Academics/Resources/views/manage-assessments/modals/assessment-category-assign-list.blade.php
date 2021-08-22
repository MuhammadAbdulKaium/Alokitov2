{{--checking grade list count--}}
<div class="col-md-12">
    @if($classGradeScaleProfile)
        {{--find grade scale--}}
        @php $gradeScale = $classGradeScaleProfile->gradeScale() @endphp
        {{--checking grade scale--}}
        @if($gradeScale->assessmentsCount()>0)
            <h5 class="text-bold text-center bg-green-active">Assessments Category List ( {{$gradeScale->name}} )</h5>

            <form id="assessment_category_mark_assign_form">
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                <input type="hidden" name="scale_id" value="{{$gradeScale->id}}" />
                <table class="table table-responsive table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Assessment Category</th>
                        <th>Marks</th>
                    </tr>
                    </thead>
                    <tbody id="assessment_table_body">
                    {{-- Category All Asssessments list--}}
                    @php
                        // $myCategffforyMarkCounterdfasdfasd = 0;
                        $assessmentCategoryList = $gradeScale->assessmentsCategoryList();
                    @endphp
                    {{--checking--}}
                    @if($assessmentCategoryList AND $assessmentCategoryList->count()>0)
                        {{--Category list looping--}}
                        @foreach($assessmentCategoryList as $index=>$assessmentProfile)
                            {{--category profile--}}
                            @php $category = $assessmentProfile->gradeCategory(); @endphp
                            {{--checking--}}
                            @if(!empty($category) || $category!=null)
                                {{--{{$category->id}},--}}
                                {{--{{$category->name}} ---}}
                                @php $assessmentCategoryMark = (array_key_exists($category->id, $weightedAverageArrayList)==true?$weightedAverageArrayList[$category->id]['id']:0); @endphp
                                <tr>
                                    <td>{{($index+1)}}</td>
                                    <td>{{$category->name}}</td>
                                    <td>
                                        <input type="text" class="form-control text-center assessment-marks" name="marks[{{$category->id}}][mark]" value="{{array_key_exists($category->id, $weightedAverageArrayList)==true?$weightedAverageArrayList[$category->id]['mark']:""}}"
                                               onKeyUp="if($.isNumeric(this.value)){if(this.value>100){this.value='100';}else if(this.value<0){this.value='0';}}else{this.value='';}" maxlength="5" required>
                                        <input id="ass_cat_{{$category->id}}" type="hidden" name="marks[{{$category->id}}][id]" value="{{$assessmentCategoryMark}}">
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    <tr>
                        {{--<td colspan="2"><strong>Total</strong></td>--}}
                        {{--<td><p id="ass_mark_total_countf">{{$myCategffforyMarkCounterdfasdfasd}}</p></td>--}}
                    </tr>
                    </tbody>
                </table>
                <div id="grade_assign_table_submit_btn" class="modal-footer">
                    <button class="btn btn-success pull-right" type="submit">Submit</button>
                </div>
            </form>

        @else
            <div class="alert-warning alert-auto-hide alert fade in text-center" style="opacity: 474.119;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="fa fa-warning"></i> No Assessment Found. </h5>
            </div>
        @endif
    @else
        <div class="alert-warning alert-auto-hide alert fade in text-center" style="opacity: 474.119;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="fa fa-warning"></i> No Grade Scale found. </h5>
        </div>
    @endif
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $('.checkbox-grade-scale').click(function () {
            if($(this).is(":checked")){
                $('#grade_assign_table_submit_btn').removeClass('hide');
            }
        });

//
//        $("input").keyup(function(){
//            $(this).css("background-color", "pink");
//
//            //            // total count
//            var total_assessment_count = 0;
//            // looping
//            $("input").each(function() {
//                // count assessment mark
//                total_assessment_count += $(this).val();
//            });
//            // set total assessment marks
////            $('#ass_mark_total_count').(ass_mark_total_count);
//            alert(total_assessment_count);
//
//        });

//        $("input").onblur(function(){
//            alert(1);

//        });

        // request for section list using batch and section id
        $('form#assessment_category_mark_assign_form').on('submit', function (e) {
            e.preventDefault();

            // checking
            if($('#my_level_id').val()==undefined){
                // add request type to the form
                $(this).append('<input id="my_level_id" type="hidden" name="level_id" value="{{$academicInfo->level}}" />');
                $(this).append('<input type="hidden" name="batch_id" value="{{$academicInfo->batch}}" />');
                $(this).append('<input type="hidden" name="request_type" value="ASSIGN" />');
            }


            // ajax request
            $.ajax({
                url: '/academics/manage/assessments/grade/weight_average/assign',
                type: 'POST',
                cache: false,
                data: $('form#assessment_category_mark_assign_form').serialize(),
                datatype: 'html',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Submitting...');
                },

                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();

                    // checking data
                    if(data.status=='success'){
                        // cat_mark_list
                        var cat_mark_list = data.cat_mark_list;
                        // cat_mark_list looping
                        for(var catId in cat_mark_list) {
                            // ass_cat_id
                            var ass_cat_id = cat_mark_list[catId];
                            // replace ass_cat_id
                            $('#ass_cat_'+catId).val(ass_cat_id);
                        }
                        // sweet alert success
                        swal("Success", data.msg, "success");
                    }else{
                        // sweet alert success
                        swal("Warning", data.msg, "warning");
                    }

                },

                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // sweet alert error
                    swal("Warning", 'No Response form server', "warning");

                }
            });
        });
    });
</script>
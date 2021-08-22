{{--token input css --}}
<link href="{{ URL::asset('css/token-input.css') }}" rel="stylesheet" type="text/css"/>
<style>
    ul.token-input-list {
        overflow: hidden;
        height: auto !important;
        height: 1%;
        width: 240px;
        border: 1px solid #999;
        cursor: text;
        font-size: 12px;
        font-family: Verdana;
        z-index:2147483647;
        margin: 0;
        padding: 0;
        background-color: #fff;
        list-style-type: none;
        clear: left;
    }
    ul.token-input-list li {
        list-style-type: none;
    }
    ul.token-input-list li input {
        border: 0;
        width: 200px;
        padding: 3px 8px;
        background-color: white;
        -webkit-appearance: caret;
    }
    li.token-input-token {
        overflow: hidden;
        height: auto !important;
        height: 1%;
        margin: 3px;
        padding: 3px 5px;
        background-color: #d0efa0;
        color: #000;
        font-weight: bold;
        cursor: default;
        display: block;
    }
    li.token-input-token p {
        float: left;
        padding: 0;
        margin: 0;
    }
    li.token-input-token span {
        float: right;
        color: #777;
        cursor: pointer;
    }
    li.token-input-selected-token {
        background-color: #08844e;
        color: #fff;
    }
    li.token-input-selected-token span {
        color: #bbb;
    }
    div.token-input-dropdown {
        position: absolute;
        width: 400px;
        background-color: #fff;
        overflow: hidden;
        border-left: 1px solid #ccc;
        border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        cursor: default;
        font-size: 12px;
        font-family: Verdana;
        z-index:2147483647
    }
    div.token-input-dropdown p {
        margin: 0;
        padding: 5px;
        font-weight: bold;
        color: #777;
    }
    div.token-input-dropdown ul {
        margin: 0;
        padding: 0;
    }
    div.token-input-dropdown ul li {
        background-color: #fff;
        padding: 3px;
        list-style-type: none;
    }
    div.token-input-dropdown ul li.token-input-dropdown-item {
        background-color: #fafafa;
    }
    div.token-input-dropdown ul li.token-input-dropdown-item2 {
        background-color: #fff;
    }
    div.token-input-dropdown ul li em {
        font-weight: bold;
        font-style: normal;
    }
    div.token-input-dropdown ul li.token-input-selected-dropdown-item {
        background-color: #d0efa0;
    }

</style>

@if($assessmentProfile)
    <form id="assessment-form" action="{{url('/academics/manage/assessments/assessment/update', [$assessmentProfile->id])}}" method="POST">
        @else
            <form id="assessment-form" action="{{url('/academics/manage/assessments/assessment/store/')}}" method="POST">
                @endif
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" id="class_section_counter" name="cs_count" value="0">
                        <i class="fa fa-info-circle"></i> @if($assessmentProfile)Update @else Add @endif Assessment
                    </h4>
                </div>
                <!--modal-header-->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="name">Assessment Name</label>
                                <input name="name" maxlength="35" value="@if($assessmentProfile){{$assessmentProfile->name}}@endif" class="form-control" type="text" placeholder="Assessment Name">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="">Grading Category</label>
                                <select name="grading_category" class="form-control">
                                    <option value="" selected disabled> Grading Category</option>
                                    @if($allGradeCategory)
                                        @foreach($allGradeCategory as $gradeCategory)
                                            <option value="{{$gradeCategory->id}}" @if($assessmentProfile) @if($gradeCategory->id == $assessmentProfile->grading_category_id) selected @endif @endif>{{$gradeCategory->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="grade">Grading Scale</label>
                                <select id="grade" name="grade" class="form-control">
                                    <option value="" selected disabled> Select Grading Scale </option>
                                    @if($allGradeScale)
                                        @foreach($allGradeScale as $gradeScale)
                                            @if($gradeScale->allGarde()->count() < 1)
                                                @continue
                                            @endif
                                            {{--<option disabled style="font-size: 18px; background: gray; color: white;">{{$gradeScale->name}} (Category)</option>--}}
                                            @if($gradeScale->allGarde())
                                                @foreach($gradeScale->allGarde() as $grade)
                                                    <option value="{{$grade->id}}" @if($assessmentProfile)  @if($grade->id == $assessmentProfile->grade_id) selected @endif @endif>{{$grade->name}}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="">Status</label>
                                <select name="status" class="form-control">
                                    <option value="" selected disabled> Select Status </option>
                                    <option value="1" @if($assessmentProfile) @if($assessmentProfile->status == 1) selected @endif @endif > Published </option>
                                    <option value="0" @if($assessmentProfile) @if($assessmentProfile->status == 0) selected @endif @endif> Unpublished </option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="counts_overall_score">Counts towords Overall Score</label>  <br/>
                                <label><input id="counts_overall_score_on" type="radio" value="1" name="counts_overall_score" @if($assessmentProfile) @if($assessmentProfile->counts_overall_score == 1) checked="checked" @endif @endif> Yes</label>
                                <label><input id="counts_overall_score_off" type="radio" value="0" name="counts_overall_score" @if($assessmentProfile) @if($assessmentProfile->counts_overall_score == 0) checked="checked"  @endif @else checked="checked" @endif> No</label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3 overall_score hide">
                            <div class="form-group">
                                <label class="control-label" for="">Points</label>
                                <input id="points" name="points" disabled maxlength="3"  value="{{$assessmentProfile?$assessmentProfile->points:''}}" class="form-control" type="text" placeholder="Enter Points" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3 overall_score hide">
                            <div class="form-group">
                                <label class="control-label" for="">Passing Points</label>
                                <input id="passing_points" name="passing_points" disabled maxlength="5" value="{{$assessmentProfile?$assessmentProfile->passing_points:''}}" class="form-control" type="text" placeholder="Passing Points" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            {{--<div class="form-group">--}}
                            {{--<label class="control-label" for="">Applied To</label> <br/>--}}
                            {{--<input type="radio" id="applied_to_class_all" class="applied_to" name="applied_to" value="all"@if($assessmentProfile) @if($assessmentProfile->applied_to == 'all') checked="checked" @endif @endif>All--}}
                            {{--<input type="radio" id="applied_to_class_section" class="applied_to" name="applied_to" value="class"@if($assessmentProfile) @if($assessmentProfile->applied_to == 'class') checked="checked" @endif @endif>Class-Section--}}
                            {{--<input type="radio" id="applied_to_class_subject" class="applied_to"  name="applied_to" value="subject"@if($assessmentProfile) @if($assessmentProfile->applied_to == 'subject') checked="checked" @endif @endif>Particular Subjects--}}
                            {{--<div class="help-block"></div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                        <div id="class-section-div" class="row" style="display: none">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-group">
                                    <label class="control-label" for="class-section"> Select Class - Section</label>
                                    <input id="class-section" class="form-control" type="text" placeholder="Select Class - Section">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success pull-left">Submit</button>
                        <a class="btn btn-default pull-right" href="#" data-dismiss="modal">Cancel</a>
                    </div>
            </form>

            {{--token input script --}}
            {{--<script src="{{ asset('js/jquery.tokeninput.js') }}" type="text/javascript"></script>--}}
            <script type="text/javascript">
                $(document).ready(function () {
//                 // applied_to input click function
//                 $('.applied_to').click(function () {
//                     if($(this).attr('id') == 'applied_to_class_section'){
//                         $('#class-section-div').show();
//                         class_section_search();
//                     }else{
//                         $('#class-section-div').hide()
//                         $('#class-section').tokenInput("clear");
//                         $('#class_section_counter').val(0);
//                     }
//                 });


                    // counts_overall_score_on
                    $('#counts_overall_score_on').click(function () {
                        $('.overall_score').removeClass('hide');
                        // active input fields
                        $('#points').removeAttr('disabled');
                        $('#passing_points').removeAttr('disabled');
                    });

                    // counts_overall_score_off
                    $('#counts_overall_score_off').click(function () {
                        $('.overall_score').addClass('hide');
                        // disabled input fields
                        $('#points').attr('disabled', 'disabled');
                        $('#passing_points').attr('disabled', 'disabled');
                    });

                    @if($assessmentProfile)
                            @if($assessmentProfile->counts_overall_score == 1)
                                $('.overall_score').removeClass('hide');
                                // active input fields
                                $('#points').removeAttr('disabled');
                                $('#passing_points').removeAttr('disabled');
                            @endif
                    @endif



                });

                //             function class_section_search() {
                //                 $.ajax({
                //                     url: '/academics/find/batch/section',
                //                     dataType: 'json',
                //                     async: false,
                //
                //                     success: function (data) {
                //
                //                         // token input class-section select function
                //                         $("#class-section").tokenInput(data, {
                //                             preventDuplicates: true,
                //                             searchDelay: 200,
                //
                //                             onAdd: function (item) {
                //                                 var item_id = item.id;
                //                                 var cs_count = parseInt($('#class_section_counter').val());
                //                                 // create batch-section
                //                                 var batch = '<input id="item_' + item_id + '_batch" type="hidden" name="batch[]" value="' + item.batch_id + '">';
                //                                 var section = '<input id="item_' + item_id + '_section" type="hidden" name="section[]" value="' + item.section_id + '">';
                //                                 // append batch-section
                //                                 $('#assessment-form').append(batch);
                //                                 $('#assessment-form').append(section);
                //                                 // count item
                //                                 $('#class_section_counter').val(cs_count + 1);
                //                             },
                //
                //                             onDelete: function (item) {
                //                                 var item_id = item.id;
                //                                 var cs_count = parseInt($('#class_section_counter').val());
                //                                 // remove batch-section
                //                                 $('#item_' + item_id + '_batch').remove();
                //                                 $('#item_' + item_id + '_section').remove();
                //                                 // count item
                //                                 $('#class_section_counter').val(cs_count - 1);
                //                             }
                //                         });
                //                     }
                //                 });
                //             }

            </script>
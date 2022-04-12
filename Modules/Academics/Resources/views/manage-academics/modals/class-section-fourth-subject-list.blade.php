<div class="col-md-10 col-md-offset-1">
    {{--checking compulsory subject list--}}
    @if($studentList->count()>0)
        <p class="text-center bg-green text-bold">Compulsory Subject List</p>
        <table class="table table-bordered table-striped table-responsive table-striped text-center">
            <thead>
            <tr>
                {{--checking compulsory list--}}
                @if(count($compulsorySubList)>0)
                    {{--compulsory subject list looping--}}
                    @foreach($compulsorySubList as $gId=>$compulsoryList)
                        {{--group subject profile--}}
                        @php $groupSubject = array_key_exists($gId, $subGroupArrayList)?$subGroupArrayList[$gId]:[] @endphp
                        {{--checking group subject--}}
                        @if($groupSubject)
                            {{-- compulsory subject looping --}}
                            <td width="10%">{{$groupSubject?$groupSubject['name']:'-'}}</td>
                        @endif
                    @endforeach
                @else
                    <td width="100%">No Compulsory subject list found.</td>
                @endif
            </tr>
            </thead>
        </table>

        <p class="text-center bg-green text-bold">Elective & Optional Subject Assignment List</p>

        <form id="class_section_fourth_subject_assign" action="#" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <table id="example1" class="table table-responsive table-bordered table-striped text-center">
                <thead>
                <tr class="bg-gray-active">
                    <td colspan="3"> <h4>#</h4></td>
                    <td>
                        <select id="e_one" class="form-control selectSubject">
                            <option value="0" selected>--- Elective One ---</option>
                            {{--checking additional subject list--}}
                            @if(count($electiveSubListOne)>0)
                                {{--additional subject looping--}}
                                @foreach($electiveSubListOne as $gId=>$electiveOneList)
                                    {{--group subject profile--}}
                                    @php $groupSubject = array_key_exists($gId, $subGroupArrayList)?$subGroupArrayList[$gId]:[] @endphp
                                    {{--checking group subject--}}
                                    @if($groupSubject)
                                        <option value="{{$gId}}">{{$groupSubject['name']}}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </td>
                    <td>
                        <select id="e_two" class="form-control selectSubject">
                            <option value="0" selected>--- Elective Two ---</option>
                            {{--checking additional subject list--}}
                            @if(count($electiveSubListTwo)>0)
                                {{--additional subject looping--}}
                                @foreach($electiveSubListTwo as $gId=>$electiveTwoList)
                                    {{--group subject profile--}}
                                    @php $groupSubject = array_key_exists($gId, $subGroupArrayList)?$subGroupArrayList[$gId]:[] @endphp
                                    {{--checking group subject--}}
                                    @if($groupSubject)
                                        <option value="{{$gId}}">{{$groupSubject['name']}}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </td>
                    <td>
                        <select id="e_three" class="form-control selectSubject">
                            <option value="0" selected>--- Elective Three ---</option>
                            {{--checking additional subject list--}}
                            @if(count($electiveSubListThree)>0)
                                {{--additional subject looping--}}
                                @foreach($electiveSubListThree as $gId=>$electiveThreeList)
                                    {{--group subject profile--}}
                                    @php $groupSubject = array_key_exists($gId, $subGroupArrayList)?$subGroupArrayList[$gId]:[] @endphp
                                    {{--checking group subject--}}
                                    @if($groupSubject)
                                        <option value="{{$gId}}">{{$groupSubject['name']}}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </td>
                    <td>
                        <select id="optional" class="form-control selectSubject">
                            <option value="0" selected>--- Optional ---</option>
                            {{--checking additional subject list--}}
                            @if(count($optionalSubList)>0)
                                {{--additional subject looping--}}
                                @foreach($optionalSubList as $gId=>$groupSubject)
                                    {{--group subject profile--}}
                                    @php $groupSubject = array_key_exists($gId, $subGroupArrayList)?$subGroupArrayList[$gId]:[] @endphp
                                    {{--checking group subject--}}
                                    @if($groupSubject)
                                        <option value="{{$gId}}">{{$groupSubject['name']}}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Roll</th>
                    <th>Student Name</th>
                    <th>Elective One</th>
                    <th>Elective Two</th>
                    <th>Elective Three</th>
                    <th>Optional Subject</th>
                </tr>
                </thead>
                <tbody>
                {{--checking student list--}}
                @if($studentList->count()>0)
                    {{--student loop counter--}}
                    @php $stdCount = 01; @endphp
                    {{--student list looping--}}
                    @foreach($studentList as $studentProfile)
                        <tr>
                            {{--additional subject profile id--}}
                            <td>{{$stdCount}}</td>
                            <td>{{$studentProfile->gr_no}}</td>
                            <td>
                                {{$studentProfile->first_name." ".$studentProfile->middle_name." ".$studentProfile->last_name}}
                                {{--checkig student additional subject list--}}
                                @if(array_key_exists($studentProfile->std_id, $stdAddSubArrayList)==true)
                                    @php
                                        $myAddSubList = (object)$stdAddSubArrayList[$studentProfile->std_id];
										// find subject list
										$group = (array)$myAddSubList->group_list;
                                    @endphp
                                @else
                                    @php
                                        $myAddSubList = [];
                                        $group = [];
                                    @endphp
                                @endif
                                <input type="hidden" name="std_list[{{$studentProfile->std_id}}][add_id]" value="{{$myAddSubList?$myAddSubList->add_id:0}}">
                            </td>


                            <td>
                                <input type="hidden" id="e_one_{{$studentProfile->std_id}}_group" name="std_list[{{$studentProfile->std_id}}][group_list][e_1]" value="{{array_key_exists('e_1', $group)?$group['e_1']:''}}">

                                <select id="e_one_{{$studentProfile->std_id}}" class="form-control e_one changeSubject" name="std_list[{{$studentProfile->std_id}}][sub_list][e_1]">
                                    <option value="0" selected id="e_one_{{$studentProfile->std_id}}0">--- Elective One ---</option>

                                    {{--checking additional subject list--}}
                                    @if(count($electiveSubListOne)>0)
                                        {{--additional subject looping--}}
                                        @foreach($electiveSubListOne as $gId=>$electiveOneList)
                                            {{--group subject profile--}}
                                            @php
                                                $groupSubject = array_key_exists($gId, $subGroupArrayList)?$subGroupArrayList[$gId]:[];
                                                $subjectGroup = array_key_exists('e_1', $group)?$group['e_1']:0;
                                            @endphp
                                            {{--checking group subject--}}
                                            @if($groupSubject)
                                                <option {{$subjectGroup==$gId?'selected':''}} id="e_one_{{$studentProfile->std_id.$gId}}" value="{{json_encode($electiveOneList)}}">{{$groupSubject['name']}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </td>


                            <td>
                                <input type="hidden" id="e_two_{{$studentProfile->std_id}}_group" name="std_list[{{$studentProfile->std_id}}][group_list][e_2]" value="{{array_key_exists('e_2', $group)?$group['e_2']:''}}">
                                <select id="e_two_{{$studentProfile->std_id}}" class="form-control e_two changeSubject" name="std_list[{{$studentProfile->std_id}}][sub_list][e_2]">
                                    <option value="0" selected id="e_two_{{$studentProfile->std_id}}0">--- Elective Two ---</option>
                                    {{--checking additional subject list--}}
                                    @if(count($electiveSubListTwo)>0)
                                        {{--additional subject looping--}}
                                        @foreach($electiveSubListTwo as $gId=>$electiveTwoList)
                                            {{--group subject profile--}}
                                            @php
                                                $groupSubject = array_key_exists($gId, $subGroupArrayList)?$subGroupArrayList[$gId]:[];
                                                $subjectGroup = array_key_exists('e_2', $group)?$group['e_2']:0;
                                            @endphp
                                            {{--checking group subject--}}
                                            @if($groupSubject)
                                                <option {{$subjectGroup==$gId?'selected':''}} id="e_two_{{$studentProfile->std_id.$gId}}" value="{{json_encode($electiveTwoList)}}">{{$groupSubject['name']}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
                                <input type="hidden" id="e_three_{{$studentProfile->std_id}}_group" name="std_list[{{$studentProfile->std_id}}][group_list][e_3]" value="{{array_key_exists('e_3', $group)?$group['e_3']:''}}">
                                <select id="e_three_{{$studentProfile->std_id}}" class="form-control e_three changeSubject" name="std_list[{{$studentProfile->std_id}}][sub_list][e_3]">
                                    <option value="0" selected id="e_three_{{$studentProfile->std_id}}0">--- Elective Three ---</option>
                                    {{--checking additional subject list--}}
                                    @if(count($electiveSubListThree)>0)
                                        {{--additional subject looping--}}
                                        @foreach($electiveSubListThree as $gId=>$electiveSThreeList)
                                            {{--group subject profile--}}
                                            @php
                                                $groupSubject = array_key_exists($gId, $subGroupArrayList)?$subGroupArrayList[$gId]:[];
                                                $subjectGroup = array_key_exists('e_3', $group)?$group['e_3']:0;
                                            @endphp
                                            {{--checking group subject--}}
                                            @if($groupSubject)
                                                <option {{$subjectGroup==$gId?'selected':''}} id="e_three_{{$studentProfile->std_id.$gId}}" value="{{json_encode($electiveSThreeList)}}">{{$groupSubject['name']}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
                                <input type="hidden" id="optional_{{$studentProfile->std_id}}_group" name="std_list[{{$studentProfile->std_id}}][group_list][opt]" value="{{array_key_exists('opt', $group)?$group['opt']:''}}">
                                <select id="optional_{{$studentProfile->std_id}}" class="form-control optional changeSubject" name="std_list[{{$studentProfile->std_id}}][sub_list][opt]">
                                    <option value="0" selected id="optional_{{$studentProfile->std_id}}0">--- Optional ---</option>
                                    {{--checking additional subject list--}}
                                    @if(count($optionalSubList)>0)
                                        {{--additional subject looping--}}
                                        @foreach($optionalSubList as $gId=>$optionalList)
                                            {{--group subject profile--}}
                                            @php
                                                $groupSubject = array_key_exists($gId, $subGroupArrayList)?$subGroupArrayList[$gId]:[];
                                                $subjectGroup = array_key_exists('opt', $group)?$group['opt']:0;
                                            @endphp
                                            {{--checking group subject--}}
                                            @if($groupSubject)
                                                <option {{$subjectGroup==$gId?'selected':''}} id="optional_{{$studentProfile->std_id.$gId}}" value="{{json_encode($optionalList)}}">{{$groupSubject['name']}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                        </tr>

                        {{--student loop counter increment--}}
                        @php $stdCount += 1; @endphp
                    @endforeach
                @else
                    {{'No Student List Found'}}
                @endif
                </tbody>
            </table>
            {{--submit button--}}
            <div class="modal-footer">
                <button type="reset" class="btn btn-default pull-left">Reset</button>
                @if ($canSave)
                <button type="submit" class="btn btn-success pull-right">Submit</button>
                @endif
            </div>
        </form>

        <script type="text/javascript">
            $(document).ready(function () {

                // request for section list using batch id
                jQuery(document).on('change','.changeSubject',function(){
                    var subject_type_id = $(this).attr('id');
                    var subject_id = $('option:selected', this).attr('id');
                    var group_id = subject_id.replace(subject_type_id, '');
                    // set group subject id
                    $('#'+subject_type_id+'_group').val(group_id);
                });

                // request for section list using batch id
                jQuery(document).on('change','.selectSubject',function(){
                    // get subject details
                    var subject_id = $(this).val();
                    var subject_type = $(this).attr('id');
                    // subject looping
                    $("."+subject_type).each(function() {
                        // att_id
                        var std_id = $(this).attr('id');
                        // my group subject
                        $('#'+std_id+subject_id).prop("selected", true);
                        // set group subject id
                        $('#'+std_id+'_group').val(subject_id);
                    });
                });


                // store/manage class section student additional subject list
                // request for section list using batch and section id
                $('form#class_section_fourth_subject_assign').on('submit', function (e) {
                    e.preventDefault();

                    // class_section_id
                    var class_section_id = $('#class_section_id').val();

                    // checking class section id
                    if(class_section_id){
                        // append related information
                        $(this).append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                            .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                            .append('<input type="hidden" name="section" value="'+class_section_id+'"/>');

                        // ajax request
                        $.ajax({
                            url: "/academics/manage/class/section/fourth/subject/store",
                            type: 'POST',
                            data: $('form#class_section_fourth_subject_assign').serialize(),
                            datatype: 'application/json',

                            beforeSend: function() {
                                // show waiting dialog
                                waitingDialog.show('Submitting...');
                            },
                            success:function(data){
                                // hide waiting dialog
                                waitingDialog.hide();
                                // checking
                                if(data.status=='success'){
                                    // sweet alert
                                    swal("Success", data.msg, "success");
                                }else{
                                    // sweet alert
                                    swal("Warning", data.msg, "warning");
                                    // empty fourth_subject_list_container
                                    fourth_subject_list_container.html('');
                                }
                            },
                            error:function(){
                                // hide waiting dialog
                                waitingDialog.hide();
                                // sweet alert
                                swal("Error", 'Unable to load data form server', "error");
                                // empty fourth_subject_list_container
                                fourth_subject_list_container.html('');
                            }
                        });
                    }else{

                    }

                });



            });
        </script>
    @else
        <div class=" col-md-12 text-center alert bg-warning text-warning" style="margin-bottom:0px;">
            <i class="fa fa-warning"></i> No students found
        </div>
    @endif


</div>
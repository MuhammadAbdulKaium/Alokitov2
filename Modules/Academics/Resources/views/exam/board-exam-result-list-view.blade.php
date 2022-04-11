
<div class="box">

    <div class="box-body">
        <div class="box-body table-responsive">
            <div id="w1" class="grid-view">



                    <table id="myTable" class="table table-striped table-bordered display" width="100%">
                        <thead>
                        <tr>
                            <th >S/N</th>
                            <th >Photo</th>
                            <th >Cadet Name</th>
                            <th >Academic Year</th>
                            <th >Class Form</th>
                            <th>
                                Board <br>


                            </th>
                            <th >Session</th>
                            <th >Registration</th>
                            <th >Roll number</th>

                            @foreach($getSubject as $key => $subject)
                                <th>{{$subject['name']}}</th>
                            @endforeach

                            <th >Total Score</th>
                            <th >Total GPA</th>
                            <th >Total Marks</th>
                        </tr>




                        </thead>

                        <tbody>

                        @foreach($getStudent as $stuInfo)
                            <tr>

                                <td>{{$loop->index+1}}</td>
                                <td>
                                    @if($stuInfo->singelAttachment("PROFILE_PHOTO"))
                                        <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$stuInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">
                                    @else
                                        <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px;height:50px">
                                    @endif
                                </td>
                                <td>{{$stuInfo->first_name}} {{$stuInfo->last_name}}</td>
                                <td>{{$yearName->year_name}}</td>
                                <td>{{$className->batch_name}}<br>
                                    @if($sectionName!=null)
                                        {{$sectionName->section_name}}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $boardName = "";
                                        if(isset($boardResults[$stuInfo->std_id]['board_name'])){
                                            $boardName = $boardResults[$stuInfo->std_id]['board_name'];
                                        }
                                    @endphp
                                    {{$boardName}}
                                </td>
                                <td>@if(isset($boardResults[$stuInfo->std_id]['session_year'])){{$boardResults[$stuInfo->std_id]['session_year']}}@endif</td>
                                <td>@if(isset($boardResults[$stuInfo->std_id]['board_exam_reg'])){{$boardResults[$stuInfo->std_id]['board_exam_reg']}}@endif</td>
                                <td>@if(isset($boardResults[$stuInfo->std_id]['board_exam_roll'])){{$boardResults[$stuInfo->std_id]['board_exam_roll']}}@endif</td>
                                @foreach($getSubject as $key => $subject)
                                    @php
                                        if(isset($boardResults[$stuInfo->std_id]->boardExamSubject))
                                            $subMark = $boardResults[$stuInfo->std_id]->boardExamSubject->firstWhere('subject_id',$subject['id']);
                                        else
                                            $subMark=null;
                                    @endphp
                                    <td>

                                        @if($subMark){{$subMark->subject_gpa}}@endif<br>
                                        @if($subMark){{$subMark->subject_marks}}@endif<br>
                                        @if($subMark){{$subMark->subject_score}}@endif

                                    </td>
                                @endforeach

                                <td>@if(isset($boardResults[$stuInfo->std_id]['total_score'])) {{$boardResults[$stuInfo->std_id]['total_score']}}@endif</td>
                                <td>@if(isset($boardResults[$stuInfo->std_id]['total_gpa'])) {{$boardResults[$stuInfo->std_id]['total_gpa']}}@endif</td>
                                <td>@if(isset($boardResults[$stuInfo->std_id]['total_marks'])) {{$boardResults[$stuInfo->std_id]['total_marks']}}@endif</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!--./modal-body-->


            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#all_select').change(function (event) {
            let val= $(this).val();
            let txt= $(this).text();

            console.log(val);
            $(".single_select").val(val);

        });
    });
</script>

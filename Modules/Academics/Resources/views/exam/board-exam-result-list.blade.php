
<div class="box">
    <div class="box-body">
        <div class="box-body table-responsive">
            <div id="w1" class="grid-view">
                <form id="std_list_import_form" method="POST" action="{{url('/academics/exam/save/student/board-exam')}}">
                    @csrf
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info pull-left"><i class="fa fa-upload"></i> Submit</button>
                    </div>
                    <input type="hidden" name="academicYearId" value="{{$yearId}}">
                    <input type="hidden" name="batchId" value="{{$getClass}}">

                    <input type="hidden" name="sectionId" value="{{$getSection}}">

                    <input type="hidden" name="boardExamType" value="{{$boardExamType}}">
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
                                <select name="" id="all_select" >
                                    <option value="">--Select --</option>
                                    <option value="barishal">Barishal</option>
                                    <option value="dhaka">Dhaka</option>
                                    <option value="chittagong">Chittagong</option>
                                    <option value="comilla">Comilla</option>
                                    <option value="dinajpur">Dinajpur</option>
                                    <option value="jessore">Jessore</option>
                                    <option value="rajshahi">Rajshahi</option>
                                    <option value="sylhet">Sylhet</option>
                               </select>

                            </th>
                            <th >Session
                                <br>
                                <input type="text" id="all_session" style="width: 100px;margin-bottom: 2px;" >

                            </th>
                            <th >Registration<br>
                                <input type="text" id="all_registration" style="width: 100px;margin-bottom: 2px;">
                            </th>
                            <th >Roll number <br>
                                <input type="number"  min="0" id="all_roll" style="width: 100px;margin-bottom: 2px;">
                            </th>

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
                                    <select name="boardName[{{$stuInfo->std_id}}]" class="single_select">
                                        @php
                                            $boardName = "";
                                            if(isset($boardResults[$stuInfo->std_id]['board_name'])){
                                                $boardName = $boardResults[$stuInfo->std_id]['board_name'];
                                            }
                                        @endphp
                                        <option value="">--Select --</option>
                                        <option value="barishal" @if($boardName == "barishal") selected @endif>Barishal</option>
                                        <option value="chittagong" @if($boardName == "chittagong") selected @endif>Chittagong</option>
                                        <option value="comilla" @if($boardName == "comilla") selected @endif>Comilla</option>
                                        <option value="dhaka" @if($boardName == "dhaka") selected @endif>Dhaka</option>
                                        <option value="dinajpur" @if($boardName == "dinajpur") selected @endif>Dinajpur</option>
                                        <option value="jessore" @if($boardName == "jessore") selected @endif>Jessore</option>
                                        <option value="rajshahi" @if($boardName == "rajshahi") selected @endif>Rajshahi</option>
                                        <option value="sylhet" @if($boardName == "sylhet") selected @endif>Sylhet</option>

                                    </select>
                                </td>
                                <td><input type="text" class="single_session" value="@if(isset($boardResults[$stuInfo->std_id]['session_year'])){{$boardResults[$stuInfo->std_id]['session_year']}}@endif" style="width: 100px;margin-bottom: 2px;" placeholder="Session Year" name="sessionYear[{{$stuInfo->std_id}}]"></td>
                                <td><input type="text" class="single_registration" value="@if(isset($boardResults[$stuInfo->std_id]['board_exam_reg'])){{$boardResults[$stuInfo->std_id]['board_exam_reg']}}@endif"  style="width: 100px;margin-bottom: 2px;" placeholder="Reg No" name="boardExamReg[{{$stuInfo->std_id}}]"></td>
                                <td><input type="number" class="single_roll" min="0" value="@if(isset($boardResults[$stuInfo->std_id]['board_exam_roll'])){{$boardResults[$stuInfo->std_id]['board_exam_roll']}}@endif" style="width: 100px;margin-bottom: 2px;" placeholder="Roll No" name="boardExamRoll[{{$stuInfo->std_id}}]"></td>
                                @foreach($getSubject as $key => $subject)
                                    @php
                                    if(isset($boardResults[$stuInfo->std_id]->boardExamSubject))
                                        $subMark = $boardResults[$stuInfo->std_id]->boardExamSubject->firstWhere('subject_id',$subject['id']);
                                    else
                                        $subMark=null;
                                    @endphp
                                    <td>

                                        <input type="text" maxlength="2" style="width: 80px;margin-bottom: 2px;" placeholder="GPA" name="gpa[{{$subject['id']}}][{{$stuInfo->std_id}}]" value="@if($subMark){{$subMark->subject_gpa}}@endif" step=".01" max="6"><br>
                                        <input type="number" min="0" max="100" style="width: 80px;margin-bottom: 2px;" placeholder="Marks" name="marks[{{$subject['id']}}][{{$stuInfo->std_id}}]" value="@if($subMark){{$subMark->subject_marks}}@endif"><br>
                                        <input type="number" min="0" step=".01" max="5" style="width: 80px;margin-bottom: 2px; " placeholder="Score" name="score[{{$subject['id']}}][{{$stuInfo->std_id}}]" value="@if($subMark){{$subMark->subject_score}}@endif">
                                    </td>
                                @endforeach

                                <td ><input type="number" min="0" type="number" step=".01" max="5" value="@if(isset($boardResults[$stuInfo->std_id]['total_score'])){{$boardResults[$stuInfo->std_id]['total_score']}}@endif" name="totalScore[{{$stuInfo->std_id}}]" style="width: 80px;margin-bottom: 2px;"></td>
                                <td ><input type="text" maxlength="2" value="@if(isset($boardResults[$stuInfo->std_id]['total_gpa'])){{$boardResults[$stuInfo->std_id]['total_gpa']}}@endif" name="totalGpa[{{$stuInfo->std_id}}]" style="width: 80px;margin-bottom: 2px;"></td>
                                <td ><input type="number" min="0" name="totalMarks[{{$stuInfo->std_id}}]" style="width: 80px;margin-bottom: 2px;" value="@if(isset($boardResults[$stuInfo->std_id]['total_marks'])){{$boardResults[$stuInfo->std_id]['total_marks']}}@endif"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!--./modal-body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info pull-left"><i class="fa fa-upload"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('form#std_list_import_form').on('submit', function (e) {
            e.preventDefault();
            $.ajax({

                url: "/academics/exam/save/student/board-exam",
                type: 'POST',
                cache: false,
                data: $('form#std_list_import_form').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success:function(data){
                    console.log(data);
                    // hide waiting dialog
                    waitingDialog.hide();
                    swal('Success','Board Exam result saved successfully',"success");
                },

                error:function(data){
                    waitingDialog.hide();

                    alert(JSON.stringify(data));
                }
            });
            });

        $('#all_select').change(function (event) {
               let val= $(this).val();

console.log(val);
            $(".single_select").val(val);

        });
        $('#all_registration').change(function (event) {
            let val= $(this).val();

            console.log(val);
            $(".single_registration").val(val);

        });
        $('#all_roll').change(function (event) {
            let val= $(this).val();

            console.log(val);
            $(".single_roll").val(val);

        });
        $('#all_session').change(function (event) {
            let val= $(this).val();

            console.log(val);
            $(".single_session").val(val);

        });
    });
</script>

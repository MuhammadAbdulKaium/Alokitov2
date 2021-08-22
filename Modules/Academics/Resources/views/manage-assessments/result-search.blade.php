
@extends('academics::manage-assessments.index')

<!-- page content -->
@section('page-content')

    {{--batch string--}}
    @php $batchString="Class"; @endphp

    <style>
        .nav-pills>li.active>a, .nav-pills>li.active>a:hover, .nav-pills>li.active>a:focus {
            color: #fff;
            background-color: #00a65a;
            border-top-color: #00a65a;
        }
    </style>

    <div class="row">
        <div class="box box-solid">
            <div class="col-md-12">
                <ul class="nav nav-pills">
                    <li class="my-tab active"><a data-toggle="tab" href="#assessment_marks">Assessment Marks</a></li>
                    <li class="my-tab"><a data-toggle="tab" href="#semester_result_sheet">Semester Result Sheet</a></li>
                    {{--<li class="my-tab"><a data-toggle="tab" href="#semester_result_sheet_college">Result Sheet (College)</a></li>--}}
                    <li class="my-tab"><a data-toggle="tab" href="#semester_tabulation_sheet">Semester Tabulation Sheet</a></li>
                    <li class="my-tab"><a data-toggle="tab" href="#semester_tabulation_sheet_college">Tabulation Sheet (College)</a></li>
                    <li class="my-tab"><a data-toggle="tab" href="#merit_position">Merit Position (Semester)</a></li>
                    <li class="my-tab"><a data-toggle="tab" href="#final_result_sheet">Final Result Sheet</a></li>
                </ul>

                <br/>
                <div class="tab-content">

                    {{--assessment marks--}}
                    <div id="assessment_marks" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="std_assessment_result_sorting_form" method="POST">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="academic_level">Academic Level</label>
                                                        <select id="academic_level" class="form-control academicLevel" name="academic_level">
                                                            <option value="" selected disabled>--- Select Level ---</option>
                                                            @foreach($allAcademicsLevel as $level)
                                                                <option value="{{$level->id}}">{{$level->level_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="batch">{{$batchString}}</label>
                                                        <select id="batch" class="form-control academicBatch" name="batch" onchange="">
                                                            <option value="" selected disabled>--- Select {{$batchString}} ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="section">Section</label>
                                                        <select id="section" class="form-control academicSection" name="section">
                                                            <option value="" selected disabled>--- Select Section ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="subject">Subjcet</label>
                                                        <select id="subject" class="form-control academicSubject" name="subject" onchange="">
                                                            <option value="" selected disabled>--- Select Subject ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="semester">Semester</label>
                                                        <select id="semester" class="form-control academicSemester" name="semester" onchange="">
                                                            <option value="" selected disabled>--- Select Semester ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="assessment">Assessment</label>
                                                        <select id="assessment" class="form-control academicAssessment" name="ass_id">
                                                            <option value="" selected disabled>--- Select Assessment ---</option>
                                                        </select>
                                                        <input id="ass_cat_id" type="hidden" name="ass_cat_id" value=""/>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="operation">Operator</label>
                                                        <select id="operation" class="form-control operation" name="operation">
                                                            <option value="" selected>--- Select Operation ---</option>
                                                            <option value="1"> Equal(=) </option>
                                                            <option value="2"> Greater Than (>) </option>
                                                            <option value="3"> Less Than (<) </option>
                                                            <option value="4"> Greater Than and Equal (>=) </option>
                                                            <option value="5"> Less Than and Equal(<=) </option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="ass_marks">Marks</label>
                                                        <input class="form-control" id="ass_marks" name="ass_marks" placeholder="Type Assessment Marks" type="number" min="1" max="100">
                                                        <input type="hidden" id="ass_points" value="0">
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-success" type="submit"> Submit </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- semester_result_sheet --}}
                    <div id="semester_result_sheet" class="tab-pane fade in">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="std_semester_exam_result_search_form" method="POST" action="{{url('/academics/manage/assessment/semester/result-sheet')}}" target="_blank">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="control-label" for="academic_level_exam_result">Academic Level</label>
                                                        <select id="academic_level_exam_result" class="form-control academicLevel" name="academic_level">
                                                            <option value="" selected disabled>--- Select Level ---</option>
                                                            @foreach($allAcademicsLevel as $level)
                                                                <option value="{{$level->id}}">{{$level->level_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="control-label" for="batch_exam_result">{{$batchString}}</label>
                                                        <select id="batch_exam_result" class="form-control academicBatch" name="batch" onchange="">
                                                            <option value="" selected disabled>--- Select {{$batchString}} ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="control-label" for="section_exam_result">Section</label>
                                                        <select id="section_exam_result" class="form-control academicSection" name="section">
                                                            <option value="" selected disabled>--- Select Section ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="semester_exam_result">Semester</label>
                                                        <select id="semester_exam_result" class="form-control academicSemester" name="semester">
                                                            <option value="" selected disabled>--- Select Semester ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="assessment_exam_result">Assessment</label>
                                                        <input type="hidden" id="category_exam_result" name="category">
                                                        <select id="assessment_exam_result" class="form-control academicAssessment" name="assessment">
                                                            <option value="" selected disabled>--- Select Assessment ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-success" type="submit"> Submit </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- semester_result_sheet --}}
                    <div id="semester_result_sheet_college" class="tab-pane fade in">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="std_semester_exam_result_college_search_form" method="POST" action="{{url('/academics/manage/assessment/semester/result-sheet-college')}}" target="_blank">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="control-label" for="academic_level_exam_result_college">Academic Level</label>
                                                        <select id="academic_level_exam_result_college" class="form-control academicLevel" name="academic_level">
                                                            <option value="" selected disabled>--- Select Level ---</option>
                                                            @foreach($allAcademicsLevel as $level)
                                                                <option value="{{$level->id}}">{{$level->level_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="control-label" for="batch_exam_result_college">{{$batchString}}</label>
                                                        <select id="batch_exam_result_college" class="form-control academicBatch" name="batch" onchange="">
                                                            <option value="" selected disabled>--- Select {{$batchString}} ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="control-label" for="section_exam_result_college">Section</label>
                                                        <select id="section_exam_result_college" class="form-control academicSection" name="section">
                                                            <option value="" selected disabled>--- Select Section ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="semester_exam_result_college">Semester</label>
                                                        <select id="semester_exam_result_college" class="form-control academicSemester" name="semester">
                                                            <option value="" selected disabled>--- Select Semester ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="assessment_exam_result_college">Assessment</label>
                                                        <input type="hidden" id="category_exam_result_college" name="category">
                                                        <select id="assessment_exam_result_college" class="form-control academicAssessment" name="assessment">
                                                            <option value="" selected disabled>--- Select Assessment ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-success" type="submit"> Submit </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- semester_result_sheet --}}
                    <div id="semester_tabulation_sheet" class="tab-pane fade in">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="std_semester_exam_tabulation_search_form" method="POST" action="{{url('/academics/manage/assessment/semester/tabulation-sheet')}}" target="_blank">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="result_sheet_type" value="TABULATION">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-2 col-md-offset-1">
                                                    <div class="form-group">
                                                        <label class="control-label" for="academic_level_exam_tabulation">Academic Level</label>
                                                        <select id="academic_level_exam_tabulation" class="form-control academicLevel" name="academic_level" required>
                                                            <option value="" selected disabled>--- Select Level ---</option>
                                                            @foreach($allAcademicsLevel as $level)
                                                                <option value="{{$level->id}}">{{$level->level_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="control-label" for="batch_exam_tabulation">{{$batchString}}</label>
                                                        <select id="batch_exam_tabulation" class="form-control academicBatch" name="batch" required>
                                                            <option value="" selected disabled>--- Select {{$batchString}} ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="control-label" for="section_exam_tabulation">Section</label>
                                                        <select id="section_exam_tabulation" class="form-control academicSection" name="section" required>
                                                            <option value="" selected disabled>--- Select Section ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="control-label" for="semester_exam_tabulation">Semester</label>
                                                        <select id="semester_exam_tabulation" class="form-control academicSemester" name="semester" required>
                                                            <option value="" selected disabled>--- Select Semester ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="category">Select Category:</label>
                                                        <select name="category" class="form-control" id="category" required>
                                                            <option value="">Select Category</option>
                                                            @foreach($allGradeCategory as $category)
                                                                <option value="{{$category->id}}">{{$category->name}}</option>

                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-success" type="submit"> Submit </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    {{-- semester_result_sheet --}}
                    <div id="semester_tabulation_sheet_college" class="tab-pane fade in">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="std_semester_exam_tabulation_college_search_form" method="POST" action="{{url('/academics/manage/assessment/semester/tabulation-sheet-college')}}" target="_blank">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="result_sheet_type" value="TABULATION_COLLEGE">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-2 col-md-offset-1">
                                                    <div class="form-group">
                                                        <label class="control-label" for="academic_level_exam_tabulation_college">Academic Level</label>
                                                        <select id="academic_level_exam_tabulation_college" class="form-control academicLevel" name="academic_level" required>
                                                            <option value="" selected disabled>--- Select Level ---</option>
                                                            @foreach($allAcademicsLevel as $level)
                                                                <option value="{{$level->id}}">{{$level->level_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="control-label" for="batch_exam_tabulation_college">{{$batchString}}</label>
                                                        <select id="batch_exam_tabulation_college" class="form-control academicBatch" name="batch" required>
                                                            <option value="" selected disabled>--- Select {{$batchString}} ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="control-label" for="section_exam_tabulation_college">Section</label>
                                                        <select id="section_exam_tabulation_college" class="form-control academicSection" name="section" required>
                                                            <option value="" selected disabled>--- Select Section ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="control-label" for="semester_exam_tabulation_college">Semester</label>
                                                        <select id="semester_exam_tabulation_college" class="form-control academicSemester" name="semester" required>
                                                            <option value="" selected disabled>--- Select Semester ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="category_college">Select Category:</label>
                                                        <select name="category" class="form-control" id="category_college" required>
                                                            <option value="">Select Category</option>
                                                            @foreach($allGradeCategory as $category)
                                                                <option value="{{$category->id}}">{{$category->name}}</option>

                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-success" type="submit"> Submit </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    {{--merit position--}}
                    <div id="merit_position" class="tab-pane fade in">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="std_semester_exam_position_search_formm" action="{{ url('/academics/manage/assessment/semester/merit-list') }}" method="POST" target="_blank">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="academic_level_merit">Academic Level</label>
                                                        <select id="academic_level_merit" class="form-control academicLevel" name="academic_level">
                                                            <option value="" selected disabled>--- Select Level ---</option>
                                                            @foreach($allAcademicsLevel as $level)
                                                                <option value="{{$level->id}}">{{$level->level_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="batch_merit">{{$batchString}}</label>
                                                        <select id="batch_merit" class="form-control academicBatch" name="batch" onchange="">
                                                            <option value="" selected disabled>--- Select {{$batchString}} ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="section_merit">Section</label>
                                                        <select id="section_merit" class="form-control academicSection" name="section">
                                                            <option value="" selected disabled>--- Select Section ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label" for="semester_merit">Semester</label>
                                                        <select id="semester_merit" class="form-control academicSemester" name="semester">
                                                            <option value="" selected disabled>--- Select Semester ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-success" type="submit"> Submit </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- semester_result_sheet --}}
                    <div id="final_result_sheet" class="tab-pane fade in">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="std_final_exam_result_search_form" method="POST" action="{{url('/academics/manage/assessment/final/result-sheet')}}" target="_blank">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-8 col-md-offset-2">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="academic_level_exam_result">Academic Level</label>
                                                        <select id="academic_level_exam_result" class="form-control academicLevel" name="academic_level">
                                                            <option value="" selected disabled>--- Select Level ---</option>
                                                            @foreach($allAcademicsLevel as $level)
                                                                <option value="{{$level->id}}">{{$level->level_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="batch_exam_result">{{$batchString}}</label>
                                                        <select id="batch_exam_result" class="form-control academicBatch" name="batch" onchange="">
                                                            <option value="" selected disabled>--- Select {{$batchString}} ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="section_exam_result">Section</label>
                                                        <select id="section_exam_result" class="form-control academicSection" name="section">
                                                            <option value="" selected disabled>--- Select Section ---</option>
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-success" type="submit"> Submit </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="assessment_table_row" class="row">
        {{--grade book table will be displayed here--}}
    </div>
@endsection

@section('page-script')
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {

            // request for batch list using level id
            jQuery(document).on('change','.academicLevel',function(){
                // get academic level id
                var level_id = $(this).val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "{{ url('/academics/find/batch') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': level_id }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },
                    success:function(data){
                        op+='<option value="" selected disabled>--- Select {{$batchString}} ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                        }
                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append(op);

                        // set value to the academic section
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');

                        // set value to the academic subject
                        $('.academicSubject').html("");
                        $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                        // set value to the academic assessment
                        $('.academicAssessment option:first').prop('selected',true);
                        // semester list reset
                        resetSemester();
                        resetSemesterForMeritList();

                        $('#ass_points').val(0);
                        $('#assessment_table_row').html('');
                    },
                    error:function(){
                        // statements
                    }
                });
            });


            // request for section list using batch id
            jQuery(document).on('change','.academicBatch',function(){
                // get academic level id
                var batch_id = $(this).val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "{{ url('/academics/find/section') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': batch_id }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        op+='<option value="" selected disabled>--- Select Section ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                        }

                        // set value to the academic subject
                        $('.academicSubject').html("");
                        $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                        // set value to the academic subject
                        $('.academicAssessment option:first').prop('selected',true);
                        // semester list reset
                        resetSemester();

                        resetSemesterForMeritList();
                        resetSemesterForResult();
                        resetSemesterForCollegeResult();
                        resetSemesterForTabulation();
                        resetSemesterForCollegeTabulation();

                        // set value to the academic batch
                        $('.academicSection').html("");
                        $('.academicSection').append(op);
                        $('#assessment_table_row').html('');
                        $('#ass_points').val(0);
                    },
                    error:function(){
                        // statements
                    },
                });
            });


            // request for section list using batch and section id
            jQuery(document).on('change','.academicSection',function(){
                // get academic level id
                var batch_id = $("#batch").val();
                var section_id = $(this).val();
                var op="";

                $.ajax({
                    url: "{{ url('/academics/find/subjcet') }}",
                    type: 'GET',
                    cache: false,
                    data: {'class_id': batch_id, 'section_id':section_id}, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){

                        op+='<option value="" selected disabled>--- Select Subject ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].sub_name+'</option>';
                        }

                        // set value to the academic subject
                        $('.academicAssessment option:first').prop('selected',true);

                        // set value to the academic batch
                        $('.academicSubject').html("");
                        $('.academicSubject').append(op);

                        $('#assessment_table_row').html('');
                        $('#ass_points').val(0);

                        resetSemester();

                        resetAssessmentListForCollege();
                        resetSemesterForMeritList();
                        resetSemesterForResult();
                        resetSemesterForCollegeResult();
                        resetSemesterForTabulation();
                        resetSemesterForCollegeTabulation();

                        //console.log(op);
                    },
                    error:function(){
                        // statements
                    },
                });
            });

            // request for section list using batch and section id
            jQuery(document).on('change','.academicSubject',function(){
                $('#assessment_table_row').html('');
                // set value to the academic assessment
                $('.academicAssessment option:first').prop('selected',true);
                // semester list reset
                resetSemester();
            });


            // request for section list using batch and section id
            jQuery(document).on('change','.academicSemester',function(){
                $('#assessment_table_row').html('');
                // set value to the academic assessment
                $('.academicAssessment option:first').prop('selected',true);
                $('#ass_points').val(0);
            });


            // request for assessment list for semester exam single assessment result of all subject
            jQuery(document).on('change','#semester_exam_result',function(){

                var op = null;

                // get academic batch id
                var batch_id = $("#batch_exam_result").val();
                // get academic level id
                var level_id = $("#academic_level_exam_result").val();
                // get academic batch id
                var section_id = $("#section_exam_result").val();
                // checking
                if(level_id && batch_id && section_id){
                    // ajax request
                    $.ajax({
                        url: "/academics/find/assessment-list",
                        type: 'GET',
                        cache: false,
                        data: {'batch': batch_id, 'level':level_id,'section': section_id}, //see the $_token
                        datatype: 'application/json',

                        beforeSend: function() {
                            // statements
                        },

                        success:function(data){

                            op+='<option value="" selected>--- Select Assessment ---</option>';
                            for(var i=0;i<data.length;i++){
                                op+='<option value="'+data[i].id+'" data-id="'+data[i].cat_id+'">'+data[i].name+'</option>';
                            }
                            // set value to the academic semester
                            $('#assessment_exam_result').html('');
                            $('#assessment_exam_result').append(op);
                        },
                        error:function(){
                            // statements
                        }
                    });
                }else{

                }
            });

            // request for assessment list for semester exam single assessment result of all subject
            jQuery(document).on('change','#assessment_exam_result',function(){
                // find assessment data id
                var category_id = $('option:selected', this).attr('data-id');
                $('#category_exam_result').val(category_id);
            });

            // request for section list using batch and section id
            jQuery(document).on('change','.academicAssessment',function(){
                $('#assessment_table_row').html('');
                // find category id
                var ass_cate_id = $(this).find('option:selected').attr('data-key');
                var ass_points = $(this).find('option:selected').attr('data-id');
                // set category id
                $('#ass_cat_id').val(ass_cate_id);
                $('#ass_points').val(ass_points);
            });

            // replace input values
            $("#ass_marks").keyup(function(){
                var my_id = $(this).attr('id');
                var my_grade_mark = JSON.parse($(this).val());
                // ass marks
                var ass_points = $('#ass_points').val();
                // checking
                if(JSON.parse(ass_points)>0){
                    // checking
                    if(my_grade_mark>ass_points || ass_points==0){
                        alert("Please insert a mark between (0 - "+ass_points+")");
                        $(this).val('');
                    }else{
                        $(this).attr('value', $(this).val());
                    }
                }else{
                    // alert msg
                    alert('Please Select a assessment');
                }
            });

            $('.my-tab').click(function () {
                $('#assessment_table_row').html('');
            });

            // request for section list using batch and section id
            $('form#std_assessment_result_sorting_form').on('submit', function (e) {
                // alert('hello');
                e.preventDefault();

                if($('#subject').val() && $('#assessment').val() && $('#semester').val()){
                    // ajax request
                    $.ajax({
                        url: "{{ url('/academics/manage/assessment/result') }}",
                        type: 'POST',
                        cache: false,
                        data: $('form#std_assessment_result_sorting_form').serialize(),
                        datatype: 'html',

                        beforeSend: function() {
                            // statements
                            // show waiting dialog
//                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
//                          // show waiting dialog
                            //waitingDialog.hide();

//                            waitingDialog.hide();
                            if(data.length>0){
                                //alert(JSON.stringify(data));
                                $('#assessment_table_row').html('');
                                $('#assessment_table_row').append(data);
                            }else{
                                alert('No data response from the server');
                            }
                        },

                        error:function(data){
                            // statements
                            alert(JSON.stringify(data));
                        }
                    });
                }else{
                    $('#assessment_table_row').html('');
                    alert('Please double check all inputs are selected.');
                }
            });


            // request for section list using batch and section id
            $('form#std_semester_exam_position_search_form').on('submit', function (e) {
                // alert('hello');
                e.preventDefault();

                if($('#semester_merit').val()){
                    // ajax request
                    $.ajax({
                        url: "{{ url('/academics/manage/assessment/semester/merit-list') }}",
                        type: 'POST',
                        cache: false,
                        data: $('form#std_semester_exam_position_search_form').serialize(),
                        datatype: 'html',

                        beforeSend: function() {
                            // statements
                            // show waiting dialog
//                            waitingDialog.show('Loading...');
                        },

                        success:function(response){
//                            alert(JSON.stringify(data));

////                            waitingDialog.hide();
                            if(response.status=='success'){
                                //alert(JSON.stringify(data));
                                $('#assessment_table_row').html('');
                                $('#assessment_table_row').append(response.data);
                                // show waiting dialog
//                                waitingDialog.hide();
                            }else{
                                // sweet alert
                                swal("Warning", response.msg, "warning");
                            }
                        },

                        error:function(data){
                            // sweet alert
                            swal("Error", 'No response form server.', "error");
                        }
                    });
                }else{
                    $('#assessment_table_row').html('');
                    alert('Please double check all inputs are selected.');
                }
            });

            // reset semester list
            function  resetSemester() {
                // get academic batch id
                var batch_id = $("#batch").val();
                // get academic level id
                var level_id = $("#academic_level").val();
                // select option
                var op="";
                // checking
                if(batch_id && level_id){
                    // ajax request
                    $.ajax({
                        url: "/academics/find/batch/semester",
                        type: 'GET',
                        cache: false,
                        data: {'batch': batch_id, 'academic_level':level_id}, //see the $_token
                        datatype: 'application/json',

                        beforeSend: function() {
                            // statements
                        },

                        success:function(data){
                            op+='<option value="" selected disabled>--- Select Semester ---</option>';
                            for(var i=0;i<data.length;i++){
                                op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                            }
                            // set value to the academic semester
                            $('.academicSemester').html('');
                            $('.academicSemester').append(op);

                            // resetAssessmentList
                            resetAssessmentList();
                        },
                        error:function(){
                            // statements
                        },
                    });
                }else{
                    op+='<option value="" selected disabled>--- Select Semester ---</option>';
                    // set value to the academic semester
                    $('.academicSemester').html('');
                    $('.academicSemester').append(op);
                }
            }

            // reset assessment list
            function  resetAssessmentListForCollege() {

                // get academic batch id
                var batch_id = $("#batch_exam_result_college").val();
                // get academic level id
                var level_id = $("#academic_level_exam_result_college").val();
                // get academic batch id
                var section_id = $("#section_exam_result_college").val();

                // select option
                var op="";
                // checking
                if(batch_id && level_id && section_id){

                    // ajax request
                    $.ajax({
                        url: "/academics/find/assessment-list",
                        type: 'GET',
                        cache: false,
                        data: {'batch': batch_id, 'level':level_id,'section': section_id}, //see the $_token
                        datatype: 'application/json',

                        beforeSend: function() {
                            // statements
                        },

                        success:function(data){

                            op+='<option value="" selected disabled>--- Select Assessment ---</option>';
                            for(var i=0;i<data.length;i++){
                                op+='<option value="'+data[i].id+'" data-id="'+data[i].points+'" data-key="'+data[i].cat_id+'">'+data[i].name+'</option>';
                            }
                            // set value to the academic semester
                            $('.academicAssessment').html('');
                            $('.academicAssessment').append(op);
                        },
                        error:function(errorResponse){
                            // statements
                            alert(JSON.stringify(errorResponse))
                        }
                    });
                }else{
                    op+='<option value="" selected disabled>--- Select Assessment ---</option>';
                    // set value to the academic Assessment
                    $('.academicAssessment').html('');
                    $('.academicAssessment').append(op);
                }
            }

            // reset assessment list
            function  resetAssessmentList() {

                // get academic batch id
                var batch_id = $("#batch").val();
                // get academic level id
                var level_id = $("#academic_level").val();
                // get academic batch id
                var section_id = $("#section").val();
                // get academic level id
                var subject_id = $("#subject").val();
                // select option
                var op="";
                // checking
                if(batch_id && level_id && section_id && subject_id){
                    // ajax request
                    $.ajax({
                        url: "/academics/find/assessment-list",
                        type: 'GET',
                        cache: false,
                        data: {'batch': batch_id, 'level':level_id,'section': section_id, 'subject':subject_id}, //see the $_token
                        datatype: 'application/json',

                        beforeSend: function() {
                            // statements
                        },

                        success:function(data){

//                            alert(JSON.stringify(data));

                            op+='<option value="" selected disabled>--- Select Assessment ---</option>';
                            for(var i=0;i<data.length;i++){
                                op+='<option value="'+data[i].id+'" data-id="'+data[i].points+'" data-key="'+data[i].cat_id+'">'+data[i].name+'('+data[i].points+')</option>';
                            }
                            // set value to the academic semester
                            $('.academicAssessment').html('');
                            $('.academicAssessment').append(op);
                        },
                        error:function(){
                            // statements
                        }
                    });
                }else{
                    op+='<option value="" selected disabled>--- Select Assessment ---</option>';
                    // set value to the academic Assessment
                    $('.academicAssessment').html('');
                    $('.academicAssessment').append(op);
                }
            }

            // reset semester list for merit l
            function  resetSemesterForMeritList() {
                // get academic batch id
                var batch_id = $("#batch_merit").val();
                // get academic level id
                var level_id = $("#academic_level_merit").val();
                // select option
                var op="";
                // checking
                if(batch_id && level_id){
                    // ajax request
                    $.ajax({
                        url: "/academics/find/batch/semester",
                        type: 'GET',
                        cache: false,
                        data: {'batch': batch_id, 'academic_level':level_id}, //see the $_token
                        datatype: 'application/json',

                        beforeSend: function() {
                            // statements
                        },

                        success:function(data){
                            op+='<option value="" selected disabled>--- Select Semester ---</option>';
                            for(var i=0;i<data.length;i++){
                                op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                            }
                            // set value to the academic semester
                            $('.academicSemester').html('');
                            $('.academicSemester').append(op);
                        },
                        error:function(){
                            // statements
                        },
                    });
                }else{
                    op+='<option value="" selected disabled>--- Select Semester ---</option>';
                    // set value to the academic semester
                    //$('.academicSemester').html('');
                    // $('.academicSemester').append(op);
                }
            }

            // reset semester list for semester final report
            function  resetSemesterForResult() {
                // get academic batch id
                var batch_id = $("#batch_exam_result").val();
                // get academic level id
                var level_id = $("#academic_level_exam_result").val();
                // select option
                var op="";
                // checking
                if(batch_id && level_id){
                    // ajax request
                    $.ajax({
                        url: "/academics/find/batch/semester",
                        type: 'GET',
                        cache: false,
                        data: {'batch': batch_id, 'academic_level':level_id}, //see the $_token
                        datatype: 'application/json',

                        beforeSend: function() {
                            // statements
                        },

                        success:function(data){
                            op+='<option value="" selected disabled>--- Select Semester ---</option>';
                            for(var i=0;i<data.length;i++){
                                op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                            }
                            // set value to the academic semester
                            $('.academicSemester').html('');
                            $('#semester_exam_result').append(op);
                        },
                        error:function(){
                            // statements
                        },
                    });
                }else{
                    op+='<option value="" selected disabled>--- Select Semester ---</option>';
                    // set value to the academic semester
                    $('.academicSemester').html('');
                    $('#semester_exam_result').append(op);
                }
            }

            // reset semester list for semester final report
            function  resetSemesterForCollegeResult() {
                // get academic batch id
                var batch_id = $("#batch_exam_result_college").val();
                // get academic level id
                var level_id = $("#academic_level_exam_result_college").val();
                // select option
                var op="";
                // checking
                if(batch_id && level_id){
                    // ajax request
                    $.ajax({
                        url: "/academics/find/batch/semester",
                        type: 'GET',
                        cache: false,
                        data: {'batch': batch_id, 'academic_level':level_id}, //see the $_token
                        datatype: 'application/json',

                        beforeSend: function() {
                            // statements
                        },

                        success:function(data){
                            op+='<option value="" selected disabled>--- Select Semester ---</option>';
                            for(var i=0;i<data.length;i++){
                                op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                            }
                            // set value to the academic semester
                            $('.academicSemester').html('');
                            $('#semester_exam_result_college').append(op);
                        },
                        error:function(){
                            // statements
                        },
                    });
                }else{
                    op+='<option value="" selected disabled>--- Select Semester ---</option>';
                    // set value to the academic semester
                    $('.academicSemester').html('');
                    $('#semester_exam_result_college').append(op);
                }
            }

            // reset semester list for semester final report
            function  resetSemesterForTabulation() {
                // get academic batch id
                var batch_id = $("#batch_exam_tabulation").val();
                // get academic level id
                var level_id = $("#academic_level_exam_tabulation").val();
                // select option
                var op="";
                // checking
                if(batch_id && level_id){
                    // ajax request
                    $.ajax({
                        url: "/academics/find/batch/semester",
                        type: 'GET',
                        cache: false,
                        data: {'batch': batch_id, 'academic_level':level_id}, //see the $_token
                        datatype: 'application/json',

                        beforeSend: function() {
                            // statements
                        },

                        success:function(data){
                            op+='<option value="" selected disabled>--- Select Semester ---</option>';
                            for(var i=0;i<data.length;i++){
                                op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                            }
                            // set value to the academic semester
                            $('.academicSemester').html('');
                            $('#semester_exam_tabulation').append(op);
                        },
                        error:function(){
                            // statements
                        },
                    });
                }else{
                    op+='<option value="" selected disabled>--- Select Semester ---</option>';
                    // set value to the academic semester
                    $('.academicSemester').html('');
                    $('#semester_exam_tabulation').append(op);
                }
            }
            // reset semester list for semester final report
            function  resetSemesterForCollegeTabulation() {
                // get academic batch id
                var batch_id = $("#batch_exam_tabulation_college").val();
                // get academic level id
                var level_id = $("#academic_level_exam_tabulation_college").val();
                // select option
                var op="";
                // checking
                if(batch_id && level_id){
                    // ajax request
                    $.ajax({
                        url: "/academics/find/batch/semester",
                        type: 'GET',
                        cache: false,
                        data: {'batch': batch_id, 'academic_level':level_id}, //see the $_token
                        datatype: 'application/json',

                        beforeSend: function() {
                            // statements
                        },

                        success:function(data){
                            op+='<option value="" selected disabled>--- Select Semester ---</option>';
                            for(var i=0;i<data.length;i++){
                                op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                            }
                            // set value to the academic semester
                            $('.academicSemester').html('');
                            $('#semester_exam_tabulation_college').append(op);
                        },
                        error:function(){
                            // statements
                        },
                    });
                }else{
                    op+='<option value="" selected disabled>--- Select Semester ---</option>';
                    // set value to the academic semester
                    $('.academicSemester').html('');
                    $('#semester_exam_tabulation_college').append(op);
                }
            }
        });


    </script>
@endsection

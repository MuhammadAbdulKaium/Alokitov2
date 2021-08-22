
@extends('academics::manage-assessments.index')

<!-- page content -->
@section('page-content')

    {{--batch string--}}
    @php $batchString="Class"; @endphp

    <div class="row">
        <div class="box box-solid">
            <form id="std_grade_book_search_form" method="POST">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-2 col-md-offset-3">
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
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label" for="batch">{{$batchString}}</label>
                                <select id="batch" class="form-control academicBatch" name="batch">
                                    <option value="" selected disabled>--- Select {{$batchString}} ---</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        {{--<div class="col-sm-2">--}}
                            {{--<div class="form-group">--}}
                                {{--<label class="control-label" for="section">Section</label>--}}
                                {{--<select id="section" class="form-control academicSection" name="section">--}}
                                    {{--<option value="" selected disabled>--- Select Section ---</option>--}}
                                {{--</select>--}}
                                {{--<div class="help-block"></div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-sm-2">--}}
                            {{--<div class="form-group">--}}
                                {{--<label class="control-label" for="semester">Semester</label>--}}
                                {{--<select id="semester" class="form-control academicSemester" name="semester">--}}
                                    {{--<option value="" selected disabled>--- Select Semester ---</option>--}}
                                {{--</select>--}}
                                {{--<div class="help-block"></div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="col-sm-2 text-center">
                            <label class="control-label" >Action</label>
                            <button class="btn btn-success pull-left" type="submit" style="padding:4px 60px; font-size:18px; "> Submit </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="assessment_table_row" class="row">
        {{--grade book table will be displayed here--}}
    </div>
@endsection

@section('page-script')
    <script type="text/javascript">
        $(function() { // document ready


            // request for section list using batch and section id
            $('form#std_grade_book_search_form').on('submit', function (e) {
                // alert('hello');
                e.preventDefault();

                if($('#batch').val() ){
                    // ajax request
                    $.ajax({
                        url: "{{ url('/academics/manage/assessments/category/setting') }}",
                        type: 'POST',
                        cache: false,
                        data: $('form#std_grade_book_search_form').serialize(),
                        datatype: 'html',

                        beforeSend: function() {
                            // statements
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
                                assessment_table_row.html('');
                                assessment_table_row.append(data.content);
                            }else{
                                alert(data.msg);
                            }
                        },

                        error:function(data){
                            // show waiting dialog
                            waitingDialog.hide();
                            // statements
                            alert('No data response from the server');
                        }
                    });
                }else{
                    $('#assessment_table_row').html('');
                    alert('Please double check all inputs are selected.');
                }
            });





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
                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');

                        $('.academicSubject').html("");
                        $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                        $('#assessment_table_row').html('');
                        // semester list reset
                        resetSemester();
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
                        // set value to the academic batch
                        $('.academicSection').html("");
                        $('.academicSection').append(op);

                        $('.academicSubject').html("");
                        $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                        $('#assessment_table_row').html('');
                        // semester list reset
                        resetSemester();
                    },
                    error:function(){
                        // statements
                    },
                });
            });


            // request for section list using batch and section id
            jQuery(document).on('change','.academicSection',function(){
                $('#assessment_table_row').html('');
                // semester list reset
                resetSemester();
            });

            // request for section list using batch and section id
            jQuery(document).on('change','.academicSemester',function(){
                $('#assessment_table_row').html('');
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

        });

    </script>
@endsection

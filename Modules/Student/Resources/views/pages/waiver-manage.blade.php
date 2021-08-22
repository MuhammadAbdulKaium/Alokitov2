@extends('layouts.master')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-plus-square"></i> Cadet <small> Waiver List </small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/student">Cadet</a></li>
                <li><a href="/student/manage/profile">Waiver List</a></li>
                <li class="active">Waiver List</li>
            </ul>
        </section>
        <section class="content">
            <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-search"></i> Cadet Manage Waiver
                        </h3>
                    </div>
    {{--batch string--}}
    @php $batchString="Class"; @endphp
            <form id="std_waiver_search_form" method="POST">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <div class="row">
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
                                <select id="batch" class="form-control academicBatch" name="batch">
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
                        <div class="col-sm-2 text-center">
                            <label class="control-label" >Action</label>
                            <button class="btn btn-success pull-left" type="submit" style="padding:4px 60px; font-size:18px; ">  Search </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    <div id="waiver_table_row" class="row">
        {{--grade book table will be displayed here--}}
    </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">


        $(function() { // document ready
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
//                        resetSemester();
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
//                        resetSemester();
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
                        // set value to the academic batch
                        $('.academicSubject').html("");
                        $('.academicSubject').append(op);
                        $('#assessment_table_row').html('');
                        // semester list reset
//                        resetSemester();
                    },
                    error:function(){
                        // statements
                    },
                });
            });



            // request for section list using batch and section id
            $('form#std_waiver_search_form').on('submit', function (e) {
                // alert('hello');
                e.preventDefault();
                    // ajax request
                    $.ajax({
                        url: "/student/manage/waiver",
                        type: 'POST',
                        cache: false,
                        data: $('form#std_waiver_search_form').serialize(),
                        datatype: 'html',

                        beforeSend: function() {
                            // statements
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // show waiting dialog
                            waitingDialog.hide();
                            // checking
                            if(data.length>0){
                                //alert(JSON.stringify(data));
                                $('#waiver_table_row').html('');
                                $('#waiver_table_row').append(data);
                            }else{
                                alert('No data response from the server');
                            }
                        },

                        error:function(data){
                            // show waiting dialog
                            waitingDialog.hide();
                            // statements
                            alert(JSON.stringify(data));
                        }
                    });
            });



        });

    </script>
@endsection

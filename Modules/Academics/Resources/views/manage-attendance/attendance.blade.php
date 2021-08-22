
@extends('academics::manage-attendance.index')
@section('page-content')
    <link href="{{ asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
            font-size: 14px;
        }
        #calendar {
            margin: 50px auto;
        }
        .fc-content{
            text-align: center;
        }
        .fc-title{
            cursor: pointer;
        }
    </style>

    @php $batchString="Class"; @endphp

    @if(!empty($attendanceSettingProfile) AND $allAcademicsLevel->count()>0)
        <div class="row">
            <div class="box box-solid">
                <form id="attendanceStdSearchForm" method="POST">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="academic_level">Academic Level</label>
                                    <select id="academic_level" class="form-control academicLevel" name="academic_level" required>
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
                                    <label class="control-label" for="batch">{{$batchString}}</label>
                                    <select id="batch" class="form-control academicBatch" name="batch" required>
                                        <option value="" selected disabled>--- Select {{$batchString}} ---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="section">Section</label>
                                    <select id="section" class="form-control academicSection" name="section" required>
                                        <option value="" selected disabled>--- Select Section ---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if($attendanceSettingProfile->subject_wise==1)
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="subject">Subject</label>
                                        <select id="subject" class="form-control academicSubject" name="subject" required>
                                            <option value="" selected disabled>--- Select Subject ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            @endif

                            @if($attendanceSettingProfile->multiple_sessions==1)
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="session">Session</label>
                                        <select id="session" class="form-control academicSession" name="session">
                                            <option value="" selected disabled>--- Select session ---</option>
                                            @foreach($allAttendanceSession as $session)
                                                <option value="{{$session->id}}">{{$session->session_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" id="session" name="session" value="0">
                            @endif
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="datepicker">Date</label>
                                    <input readonly class="form-control pull-right" name="datepicker" id="datepicker" type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ./box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success pull-right">Search</button>
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert-auto-hide alert alert-info alert-dismissable" style="opacity: 408.083;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="fa fa-info-circle"></i> There is no <b>Institute Attendance Setting Profile</b>.
        </div>
    @endif

    <div id="attendanceContainer" class="row">
    </div>

@endsection

@section('page-script')
    <script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(function() { // document ready

            //Date picker
            $('#datepicker').datepicker({
                autoclose: true
            });
            // set today in the date picker
            //$('#datepicker').val($.datepicker.formatDate('mm/dd/yy',  new Date(Date.now())));


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
                        // console.log(level_id);
                    },
                    success:function(data){
                        op+='<option value="" selected disabled>--- Select {{$batchString}} ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                        }
                        // refresh attendance container row
                        $('#attendanceContainer').html('');
                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append(op);
                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');
                        // set value to the academic secton
                        $('.academicSubject').html("");
                        $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');
                        // set value to the academic secton
                        $('.academicSession option:first').prop('selected',true);
                    },
                    error:function(){
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
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
                        //
                    },

                    success:function(data){
                        op+='<option value="" selected disabled>--- Select Section ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                        }
                        // refresh attendance container row
                        $('#attendanceContainer').html('');
                        // set value to the academic batch
                        $('.academicSection').html("");
                        $('.academicSection').append(op);

                        // set value to the academic secton
                        $('.academicSubject').html("");
                        $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');
                        // set value to the academic secton
                        $('.academicSession option:first').prop('selected',true);
                    },
                    error:function(){
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
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
                        // console.log(batch_id+" "+section_id);
                    },

                    success:function(data){

                        op+='<option value="" selected disabled>--- Select Subject ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].sub_name+'</option>';
                        }
                        // refresh attendance container row
                        $('#attendanceContainer').html('');
                        // set value to the academic batch
                        $('.academicSubject').html("");
                        $('.academicSubject').append(op);
                        // set value to the academic secton
                        $('.academicSession option:first').prop('selected',true);
                        //console.log(op);
                    },
                    error:function(){
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            });

            // // refresh attendance container row on academic subject change
            jQuery(document).on('change','.academicSubject',function(){
                // refresh attendance container row
                $('#attendanceContainer').html('');
                $('.academicSession option:first').prop('selected',true);
            });

            // // refresh attendance container row on academic subject change
            jQuery(document).on('change','.academicSession',function(){
                // refresh attendance container row
                $('#attendanceContainer').html('');
            });

            // request for section list using batch and section id
            $('form#attendanceStdSearchForm').on('submit', function (e) {
                e.preventDefault();
                // class section details
                var class_id = $("#batch").val();
                var section_id = $("#section").val();
                var session_id = $("#session").val();
                if(!$('#datepicker').val()) $('#datepicker').datepicker('setDate', new Date());
                // checking
                if(class_id && section_id && session_id){
                    // ajax request
                    $.ajax({
                        url: "{{ url('/academics/manage/attendance/getAttendanceStudent') }}",
                        type: 'POST',
                        cache: false,
                        data: $('form').serialize(),
                        datatype: 'application/json',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // show waiting dialog
                            waitingDialog.hide();
                            // attendanceContainer
                            var attendanceContainer = $('#attendanceContainer');
                            attendanceContainer.html('');
                            // checking
                            if(data.status=='success'){
                                attendanceContainer.html(data.content);
                            }else {
                                // sweet alert
                                swal("Warning", data.msg, "warning");
                            }
                        },

                        error:function(){
                            // show waiting dialog
                            waitingDialog.hide();
                            // sweet alert
                            swal("Error", 'Unable to load data form server', "error");
                        }
                    });
                }else{
                    // sweet alert
                    swal("Warning", 'Please double check all inputs are selected.', "warning");
                }
            });
        });


    </script>
@endsection

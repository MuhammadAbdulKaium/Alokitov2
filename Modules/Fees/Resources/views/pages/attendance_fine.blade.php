@extends('fees::layouts.master')
@section('page-content')

    @php $batchString="Class"; @endphp
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

    <div class="row">
        <div class="box box-solid">
            <form id="upload_attendance_report_search_form"  method="post">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="academic_level">Academic Level</label>
                                <select id="academic_level" class="form-control academicLevel" name="level" required>
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
                                <select id="batch" class="form-control academicBatch" name="batch">
                                    <option value="" selected disabled>--- Select {{$batchString}} ---</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="section">Section</label>
                                <select id="section" class="form-control academicSection" name="section">
                                    <option value="" selected disabled>--- Select Section ---</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="attendance_date">Date</label>
                                <input readonly class="form-control pull-right" name="attendance_date" id="attendance_date" type="text" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ./box-body -->
                <div class="box-footer">
                    <button type="button" id="upload_attendance_report_search_form_submit_btn" class="btn btn-info pull-right">Search</button>
                    <button type="reset" class="btn btn-default">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <div id="uploadAttendanceReportContainer" class="row">
    </div>

@endsection
{{--<script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>--}}
{{--<script src="{{ asset('js/fc/moment.js') }}" type="text/javascript"></script>--}}
{{--<script src="{{asset('js/jquery.form.js')}}" type="text/javascript"></script>--}}
@section('page-script')

        $(function() { // document ready

            //Date picker
            $('#attendance_date').datepicker({format: 'dd-mm-yyyy', autoclose: true});



            // request for section list using batch and section id
            $('#upload_attendance_report_search_form_submit_btn').click(function () {
                // report details
                var level = $('#academic_level').val();
                var batch = $('#batch').val();
                var section = $('#section').val();
                var report_type = $('#report_type').val();
                var attendance_date = $('#attendance_date').val();
                // checking
                if(attendance_date){
                    // ajax request
                    $.ajax({
                        url: "/fees/attendance/fine/list/",
                        type: 'POST',
                        cache: false,
                        data: {
                            'level_id':level,
                            'class_id': batch,
                            'section_id':section,
                            'attendance_date':attendance_date,
                            '_token':'{{csrf_token()}}'
                        }, //see the $_token
                        datatype: 'html',
//                        datatype: 'application/json',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // hide waiting dialog
                            waitingDialog.hide();
                            // append data
                            var uploadAttendanceReportContainer  = $('#uploadAttendanceReportContainer');
                            uploadAttendanceReportContainer.html('');
                            uploadAttendanceReportContainer.append(data);
                        },
                        error:function(){
                            //console.log(data);
                        },
                    });

                }else{
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
                        // console.log(level_id);
                    },
                    success:function(data){
                        op+='<option value="" selected>--- Select {{$batchString}} ---</option>';
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
                        $('.academicSection').append('<option value="" selected>--- Select Section ---</option>');
                    },
                    error:function(){
                        //
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
                        op+='<option value="" selected>--- Select Section ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                        }
                        // refresh attendance container row
                        $('#attendanceContainer').html('');
                        // set value to the academic batch
                        $('.academicSection').html("");
                        $('.academicSection').append(op);
                    },
                    error:function(){
                        //
                    },
                });
            });


            // request for section list using batch and section id
            jQuery(document).on('change','.academicSection',function(){
                // statement
            });
        });


    {{--</script>--}}
@endsection

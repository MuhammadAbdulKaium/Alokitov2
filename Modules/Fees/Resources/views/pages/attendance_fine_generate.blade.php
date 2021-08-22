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
                                <label class="control-label" for="attendance_from_date">From Date</label>
                                <input readonly class="form-control pull-right" name="attendance_from_date" id="attendance_from_date" type="text" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="attendance_date_to">To Date</label>
                                <input readonly class="form-control pull-right" name="attendance_to_date" id="attendance_to_date" type="text" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-md-1" style="margin-top: 20px">
                            <button type="button" id="upload_attendance_report_search_form_submit_btn" class="btn btn-info pull-right">Search</button>
                        </div>
                    </div>
                </div>
                <!-- ./box-body -->
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
            $('#attendance_from_date').datepicker({
                autoclose: true
            });

        $('#attendance_to_date').datepicker({
                 autoclose: true
        });


            // request for section list using batch and section id
            $('#upload_attendance_report_search_form_submit_btn').click(function () {
                // report details
                var attendance_from_date = $('#attendance_from_date').val();
                var attendance_to_date = $('#attendance_to_date').val();
                // checking
                if(attendance_from_date && attendance_to_date){
                    // ajax request
                    $.ajax({
                        url: "/fees/attendance/fine/student/date_range",
                        type: 'POST',
                        cache: false,
                        data: {
                            'attendance_from_date':attendance_from_date,
                            'attendance_to_date':attendance_to_date,
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

        });


    {{--</script>--}}
@endsection

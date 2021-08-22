@php
    $allAttendanceList = $attendanceArrayList['attendance_list'];
    if($reportType=='ALL') $attendanceList = $allAttendanceList['all_list'];
    if($reportType=='PRESENT') $attendanceList = $allAttendanceList['present_list'];
    if($reportType=='ABSENT') $attendanceList = $allAttendanceList['absent_list'];
    if($reportType=='LATE_PRESENT') $attendanceList = $allAttendanceList['late_present_list'];
@endphp

{{--datatable style sheet--}}
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
{{--<h4 class="text-center text-bold bg-blue-gradient" style="line-height: 28px">--}}
    {{--Uploaded Attendance List--}}
    {{--@if($attendanceList)--}}
        {{--<span data-id="pdf" class="pull-right label label-success download-upload-attendance-report" style="margin: 5px; cursor: pointer">Download PDF</span>--}}
        {{--<span data-id="xlsx" class="pull-right label label-success download-upload-attendance-report" style="margin: 5px; cursor: pointer">Download Excel</span>--}}
    {{--@endif--}}
{{--</h4>--}}
<form  id="attendanceFineGenerate" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="col-md-12">
    @if($attendanceList)

        <table id="example1" class="table table-bordered table-responsive table-striped text-center">
            <thead>
            <tr class="bg-gray">
                <th>GR. NO</th>
                <th>Full Name</th>
                <th>Class (Section)</th>
                <th>Attendance Date</th>
                <th>Entry Date Time</th>
                <th>Attendance Type</th>
                <th>Fine</th>
            </tr>
            </thead>
            <tbody class="text-bold">
            @php $i=1; @endphp
            @foreach ($attendanceList as $key=>$attendance)
                @php $stdProfile = $attendance['std_profile'] @endphp

                <tr>
                    <td> {{$stdProfile->gr_no}} <input type="hidden" name="attendanceFine[{{$stdProfile->id}}][std_id]" value="{{$stdProfile->id}}"> </td>
                    <td> {{$stdProfile->name}} </td>
                    <td> {{$stdProfile->enroll}} </td>
                    <td> {{date('d M, Y', strtotime($attendance['att_date']))}} <input type="hidden" name="attendanceFine[{{$stdProfile->id}}][date]" value="{{date('d M, Y', strtotime($attendance['att_date']))}}"</td>
                    <td class="{{$attendance['att_type']=='ABSENT'?'text-danger':''}}"> {{($attendance['entry_date_time'])}} </td>
                    <td class="{{$attendance['att_type']=='ABSENT'?'text-danger':''}}"> {{$attendance['att_type']}} </td>
                    <td>{{$attendance['fine']}}  <input type="hidden" name="attendanceFine[{{$stdProfile->id}}][amount]" value="{{$attendance['fine']}}"</td>
                </tr>
                @php $i+=1; @endphp
            @endforeach
            </tbody>
        </table>

    @else
        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in text-center">
            <h5 class="text-bold"><i class="fa fa-warning"></i> No records found </h5>
        </div>
    @endif
</div>
    <button type="submit" class="btn btn-primary" style="margin-left: 20px">Generate</button>
</form>

<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script>
    $(document).ready(function () {

        // dataTable
        $("#example2").DataTable();
        var myDataTable = $('#example1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });

        $('.download-upload-attendance-report').click(function () {

            var level = $('#academic_level').val();
            var batch = $('#batch').val();
            var section = $('#section').val();
            var report_type = $('#report_type').val();
            var attendance_date = $('#attendance_date').val();
            var request_type= $(this).attr('data-id');
            // dynamic form
            $('<form id="upload_att_report_download_form" action="/academics/upload/attendance/report" method="POST"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="report_type" value="'+report_type+'"/>')
                .append('<input type="hidden" name="attendance_date" value="'+attendance_date+'"/>')
                .append('<input type="hidden" name="request_type" value="'+request_type+'"/>').appendTo('body');
            // checking
            if(level) $('#upload_att_report_download_form').append('<input type="hidden" name="level_id" value="'+level+'"/>');
            if(batch) $('#upload_att_report_download_form').append('<input type="hidden" name="class_id" value="'+batch+'"/>');
            if(section) $('#upload_att_report_download_form').append('<input type="hidden" name="section_id" value="'+section+'"/>');
            // submit
            $('#upload_att_report_download_form').submit();
            // remove form from the body
            $('#upload_att_report_download_form').remove();
        });



        // request for student fine generate
        $('form#attendanceFineGenerate').on('submit', function (e) {
            e.preventDefault();

            waitingDialog.show('Loading...');

            var rowData = $();
            for (var i = 0; i < myDataTable.rows()[0].length; i++) {
                rowData = rowData.add(myDataTable.row(i).node())
            }

            var json_obj = {};
            // input token
            json_obj['_token'] = '{{csrf_token()}}';
            // input others data
            rowData.find('input').each(function(i, el) {
                json_obj[$(el).attr('name')] = $(el).val();
            });


// ajax request
            $.ajax({

                url: '/fees/attendance/fine/generate',
                type: 'POST',
                cache: false,
                data: json_obj, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // alert($('form#PayerStudent').serialize());
                },

                success:function(data){
                    waitingDialog.hide();
                    if(data=='success') {
                        swal("Success!", "Attendance fine  successfully generated", "success");
                    } else {
                        swal("Waining!", "Attendance find already exits", "warning");
                    }

                    },

                error:function(data){
                    swal("Waining!", "Warning please try again", "warning");

                }
            });


        });



    });




</script>

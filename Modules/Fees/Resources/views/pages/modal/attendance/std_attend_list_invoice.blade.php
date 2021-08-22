


{{--datatable style sheet--}}
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
{{--<h4 class="text-center text-bold bg-blue-gradient" style="line-height: 28px">--}}
    {{--Uploaded Attendance List--}}
    {{--@if($attendanceList)--}}
        {{--<span data-id="pdf" class="pull-right label label-success download-upload-attendance-report" style="margin: 5px; cursor: pointer">Download PDF</span>--}}
        {{--<span data-id="xlsx" class="pull-right label label-success download-upload-attendance-report" style="margin: 5px; cursor: pointer">Download Excel</span>--}}
    {{--@endif--}}
{{--</h4>--}}
<form  id="attendanceFineGenerateInvoice"   method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="col-md-12">
    @if(!empty($studentAttendanceFineArray))
        <table  class="table table-bordered table-responsive table-striped text-center">
            <thead>
            <tr class="bg-gray">
                <th>GR. NO</th>
                <th>Full Name</th>
                <th>Class (Section)</th>
                <th>Total Fine</th>
            </tr>
            </thead>
            <tbody class="text-bold">
            @php $i=1; @endphp
            @foreach ($studentAttendanceFineArray as $stdId=>$stdDetails)
                     <tr>


                         <td>{{$stdDetails['gr_no']}}
                             <input type="hidden" name="attendanceFineInvoice[{{$stdDetails['std_id']}}][absent_id]" value="{{$stdDetails['id']}}">
                             <input type="hidden" name="attendanceFineInvoice[{{$stdDetails['std_id']}}][std_id]" value="{{$stdDetails['std_id']}}"> </td>
                         <td>{{$stdDetails['name']}}</td>
                         <td>{{$stdDetails['enroll']}}</td>
                         <td>{{$stdDetails['total_attendance_fine']}}  <input type="hidden" name="attendanceFineInvoice[{{$stdDetails['std_id']}}][total_amount]" value="{{$stdDetails['total_attendance_fine']}}"</td>


                         {{--'std_name'=> $std->id,--}}
                         {{--'gr_no'=>$stdEnroll->gr_no,--}}
                         {{--'name'=>$std->first_name." ".$std->middle_name." ".$std->last_name,--}}
                         {{--'enroll'=>$batch->batch_name." (".$section->section_name.") ",--}}
                         {{--'total_attendance_fine'=>$studentAttendanceFineSum--}}



                </tr>
                @php $i+=1; @endphp
            @endforeach
            </tbody>
        </table>
        <button type="button" id="attendance_fine_invoice"  class="btn btn-primary" style="margin-left: 20px">Generate Invoice</button>

    @else
        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in text-center">
            <h5 class="text-bold"><i class="fa fa-warning"></i> No records found </h5>
        </div>
    @endif
</div>
</form>

<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script>
    $(document).ready(function () {

        // dataTable
//        $("#example2").DataTable();
//        var myDataTable = $('#example1').DataTable({
//            "paging": true,
//            "lengthChange": true,
//            "searching": true,
//            "ordering": true,
//            "info": true,
//            "autoWidth": false
//        });

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
        $('#attendance_fine_invoice').click(function (e) {
            e.preventDefault();

    // ajax request
            $.ajax({

                url: '/fees/attendance/fine/generate/invoice',
                type: 'POST',
                cache: false,
                data: $('form#attendanceFineGenerateInvoice').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
//                     alert($('form#attendanceFineGenerateInvoice').serialize());
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success:function(response){
                    // hide waiting dialog
                    waitingDialog.hide();
                    alert(response);
                },

                error:function(data){
                    alert('error');
                }
            });


        });



    });




</script>

{{--datatable style sheet--}}
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<h4 class="text-center text-bold bg-blue-gradient">Uploaded Attendance List</h4>
<div class="col-md-12">
    @if(!empty($attendanceArrayList))
        @if(count($attendanceArrayList)<=2000)
            <form id="final_attendance_upload_form" method="POST">
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                <table id="example1" class="table table-bordered table-responsive table-striped text-center">
                    <thead>
                    <tr class="bg-gray">
                        <th>GR. NO</th>
                        <th>Full Name</th>
                        <th>Class (Section)</th>
                        <th>Punch ID</th>
                        <th>Entry Date Time</th>
                    </tr>
                    </thead>

                    <tbody class="text-bold">
                    @php $i=0; @endphp
                    @foreach($attendanceArrayList as $singleAttendance)
                        <tr>
                            <td>
                                {{$singleAttendance->gr_no}}
                                <input type="hidden" name="attendance[{{$i+1}}][std_id]" value="{{$singleAttendance->id}}">
                                <input type="hidden" name="attendance[{{$i+1}}][std_gr_no]" value="{{$singleAttendance->gr_no}}">
                            </td>

                            <td>
                                {{$singleAttendance->name}}
                            </td>

                            <td>
                                {{$singleAttendance->enroll}}
                                <input type="hidden" name="attendance[{{$i+1}}][year]" value="{{$singleAttendance->year}}">
                                <input type="hidden" name="attendance[{{$i+1}}][level]" value="{{$singleAttendance->level}}">
                                <input type="hidden" name="attendance[{{$i+1}}][batch]" value="{{$singleAttendance->batch}}">
                                <input type="hidden" name="attendance[{{$i+1}}][section]" value="{{$singleAttendance->section}}">
                            </td>

                            <td>
                                {{$singleAttendance->card_no}}
                                <input type="hidden" name="attendance[{{$i+1}}][card_no]" value="{{$singleAttendance->card_no}}">
                            </td>
                            <td>
                                {{$singleAttendance->entry_date_time}}
                                <input type="hidden" name="attendance[{{$i+1}}][entry_date_time]" value="{{$singleAttendance->entry_date_time}}">
                            </td>
                        </tr>
                        @php $i+=1; @endphp
                    @endforeach
                    </tbody>
                </table>
                <div class="modal-footer">
                    <button id="final_attendance_upload_form_submit_btn" type="button" class="btn btn-info {{$attendanceHistory?'':'hide'}}">Submit</button>
                    <button type="button" class="btn btn-warning {{$attendanceHistory?'hide':''}}">Unable to Upload Attendance File</button>
                </div>
            </form>
        @else
            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in">
                <h5 class="text-bold"><i class="fa fa-warning"></i> Can not Upload above 2000 !!! </h5>
            </div>
        @endif
    @else
        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in">
            <h5 class="text-bold"><i class="fa fa-warning"></i> Attendance List is empty !!!</h5>
        </div>
    @endif
</div>
<script src="{{ asset('js/fc/moment.js') }}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.form.js')}}" type="text/javascript"></script>
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
            "ordering": false,
            "info": true,
            "pageLength": 100,
            "autoWidth": false
        });

        // submit button click action
        $("#final_attendance_upload_form_submit_btn").click(function () {
            // show waiting dialog
            waitingDialog.show('Loading...');
            // resize table data
            var rowData = $();
            var totalDataSize = myDataTable.rows()[0].length;
            var chunck = 100;
            var loopCount = totalDataSize / chunck;
            if(isFloat(loopCount))
                loopCount =  parseInt(loopCount + 1);
            var start =0;
            var end = start + chunck;
            var successCount = 0;
            // checking
            if(end>totalDataSize) {
                end = totalDataSize;
            }



            for(var j =0; j<loopCount; j++){

                var rowData = $();
                for (var i = start; i < end; i++) {
                    rowData = rowData.add(myDataTable.row(i).node())
                }

                var json_obj = {};
                // input token
                json_obj['_token'] = '{{csrf_token()}}';
                json_obj['start'] = start;
                json_obj['end'] = end;
                json_obj['total_chunk'] = loopCount;
                json_obj['current_chunk'] = (j+1);
                json_obj['att_history_id'] = '{{$attendanceHistory?$attendanceHistory->id:0}}';
                // input others data
                rowData.find('input').each(function(i, el) {
                    json_obj[$(el).attr('name')] = $(el).val();
                });

                // ajax request
                $.ajax({
                    url: "/academics/upload/attendance/final-upload",
                    type: 'POST',
                    cache: false,
                    data: json_obj, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        // checking
                        if(data.status==='success'){

                            successCount++;

                            if(successCount == loopCount){
                                $('#attendance_list').val('');
                                $('#date_picker').val('');
                                $('#history_date_picker').val('');

                                $('#uploadAttendanceContainer').html('');

                                $('#attendance_upload_tab').removeClass('active');
                                $('#attendance_upload').removeClass('active');

                                $('#attendance_upload_history_tab').addClass('active');
                                $('#attendance_upload_history').addClass('active fade in');
                                // hide waiting dialog
                                waitingDialog.hide();
                            }
                        }else{
                            // hide waiting dialog
                            waitingDialog.hide();
                            // sweet alert
                            swal("Warning", data.msg, "warning");
                        }
                    },

                    error:function(){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // sweet alert
                        swal("Error", 'Unable to submit to the server', "error");
                    }
                });

                start = start + chunck;
                end = end + chunck;
                if(end> totalDataSize) end = totalDataSize;
            }

        });
    });

    function isFloat(n){
        return Number(n) === n && n % 1 !== 0;
    }
</script>
{{--datatable style sheet--}}
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<h4 class="text-center text-bold bg-blue-gradient" style="line-height: 28px">
    Attendance List
        <a style="margin: 5px; cursor: pointer"  class="pull-right label label-default" href="{{url('/academics/attendance/import')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Import</a>
        <span id="attendance_export_btn" class="pull-right label label-default" style="margin: 5px; cursor: pointer">Export</span>
</h4>
<div class="col-md-12">
    @if(!empty($studentList) && count($studentList)>0)
        <form id="std_attendance_form">
            <input type="hidden" name="_token" value="{{csrf_token()}}"/>
            {{--attendance date--}}
            <input  type="hidden" id="att_date" name="att_date" value="{{date("m/d/Y", strtotime($attendanceDate))}}"/>
            <input  type="hidden" id="std_count" name="std_count" value="{{count($studentList)}}"/>
            {{--attendacne table--}}
            <table id="example1" class="table table-bordered table-responsive table-striped text-center">
                <thead>
                <tr class="bg-gray-active">
                    <th colspan="3" class="text-right">
                        <label><input type="radio" name="call-attendance" id="absent" class="call-attendance" value="0">Absent</label>
                        <label><input type="radio" name="call-attendance" id="present" class="call-attendance" value="1">Present</label>
                        <label><input type="radio" name="call-attendance" id="late_present" class="call-attendance" value="2"> Present (L)</label>
                    </th>
                </tr>
                <tr class="bg-gray">
                    <th>GR. No</th>
                    <th>Name</th>
                    <th>Type</th>
                </tr>
                </thead>

                <tbody class="text-bold">
                {{--looping--}}
                @for($i=0; $i<count($studentList); $i++)
                    {{--std id--}}
                    @php $stdId = $studentList[$i]['id'];  @endphp
                    @php $stdGrNo = $studentList[$i]['gr_no'];  @endphp
                    {{--table row--}}
                    <tr class="{{$i%2==0?'bg-gray-active':'bg-gray'}}">
                        <td>
                            {{$stdGrNo}}
                            <input type="hidden" name="std_list[{{$stdId}}][std_id]" value="{{$stdId}}" />
                            <input type="hidden" name="std_list[{{$stdId}}][std_gr_no]" value="{{$stdGrNo}}" />
                        </td>
                        <td>{{$studentList[$i]['name']}}</td>
                        @if(array_key_exists($stdId, $attendanceList)==true)
                            {{--std attendance details--}}
                            @php $attendanceProfile = $attendanceList[$stdId];  @endphp
                            {{--std attendance type--}}
                            @php $attType = $attendanceProfile['att_type'];  @endphp
                            @php $attColor = $attendanceProfile['att_color'];  @endphp
                            <td id="{{$stdId}}" style="cursor: pointer;" class="attendance set-attendance alert {{$attColor}}">
                                @if($attType==1)
                                    P
                                @elseif($attType==2)
                                    L
                                @elseif($attType==0)
                                    A
                                @else
                                    N/A
                                @endif
                            </td>
                            <input id="att_id_{{$stdId}}" type="hidden" name="std_list[{{$stdId}}][att_id]" value="{{$attendanceProfile['att_id']}}" />
                            <input class="attendance-type" id="att_type_{{$stdId}}" type="hidden" name="std_list[{{$stdId}}][att_type]" value="{{$attType}}" />
                        @else
                            <td id="{{$stdId}}" style="cursor: pointer;" class="attendance set-attendance alert alert-info">
                                N/A
                            </td>
                            <input id="att_id_{{$stdId}}" type="hidden" name="std_list[{{$stdId}}][att_id]" value="0" />
                            <input class="attendance-type" id="att_type_{{$stdId}}" type="hidden" name="std_list[{{$stdId}}][att_type]"/>
                        @endif
                    </tr>
                @endfor
                </tbody>
            </table>

            <div class="modal-footer">
                    <div class="pull-left">
                        @if($attendanceModule->count()>0)
                            <p>Send Automatic SMS  <label><input type="radio" id="send_automatic_sms" name="send_automatic_sms" value="1">Yes</label> <label><input type="radio" class="send_automatic_sms" name="send_automatic_sms" value="0" checked="checked">No</label></p>
                        @endif
                    </div>
                <button id="std_attendance_form_submit_btn" type="button" class="btn btn-success">Submit</button>
            </div>
        </form>
    @else
        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in">
            <h5 class="text-bold"><i class="fa fa-warning"></i> Attendance List is empty !!!</h5>
        </div>
    @endif
</div>
{{--<script src="{{ asset('js/fc/moment.js') }}" type="text/javascript"></script>--}}
{{--<script src="{{URL::asset('js/jquery.form.js')}}" type="text/javascript"></script>--}}
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script>
    $(document).ready(function () {

        @if(count($attendanceList)==0)
            $('#std_attendance_form_submit_btn').addClass('hide');
        @else
            $('#datepicker').datepicker('setDate', '{{date("m/d/Y", strtotime($attendanceDate))}}');
        @endif

        // export attendance list
        $('#attendance_export_btn').click(function () {
            if($('#subject').length != 0){
                var subject = $('#subject').val();
            }else{
                var subject = 0;
            }

            // dynamic html form
            $('<form id="attendance_export_form" action="/academics/attendance/export" method="GET" style="display:none;"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                .append('<input type="hidden" name="section" value="'+$('#section').val()+'"/>')
                .append('<input type="hidden" name="subject" value="'+subject+'"/>')
                .append('<input type="hidden" name="session" value="'+$('#session').val()+'"/>')
                // append to body and submit the form
                .appendTo('body').submit();
            // remove form from the body
            $('#attendance_export_form').remove();
        });

        // submit button click action
        $(".call-attendance").click(function () {
            // std_attendance_form_submit_btn
            std_attendance_form_submit_btn = $('#std_attendance_form_submit_btn');
            // check submit btn
            if(std_attendance_form_submit_btn.hasClass('hide')) std_attendance_form_submit_btn.removeClass('hide');
            var att_type = $(this).val();
            // checking
            if(att_type==1){
                update_attendance(1, 'P', 'alert-success')
            }else if (att_type==2){
                update_attendance(2, 'L', 'alert-warning')
            }else {
                update_attendance(0, 'A', 'alert-danger')
            }
            // update_attendance
            function update_attendance(att_type, att_type_name, att_type_color) {
                // attendance looping
                $(".attendance").each(function() {
                    // att_id
                    var std_id = $(this).attr('id');
                    // set att type
                    $('#att_type_'+std_id).val(att_type);
                    // remove class
                    $(this).removeClass('alert-info alert-success alert-warning alert-danger');
                    // add class
                    $(this).addClass(att_type_color);
                    // set att type name
                    $(this).html('');
                    $(this).html(att_type_name);
                });
            }
        });

        // dataTable
        $("#example2").DataTable();
        var myDataTable = $('#example1').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "pageLength": 10
        });


        $("#std_attendance_form_submit_btn").click(function () {
            // url
            var url = '/academics/manage/attendance/manage-attendance-2';

            // subject info
            var subjectInfo = $('#subject');
            var subjectValue = null;
            if(subjectInfo.length != 0){ subjectValue = subjectInfo.val();}else { subjectValue = 0; }
            // dataTable row calculation
            var rowData = $();
            for (var i = 0; i < myDataTable.rows()[0].length; i++) {
                rowData = rowData.add(myDataTable.row(i).node())
            }
            // json obj
            var json_obj = {};
            // input token
            json_obj['_token'] = '{{csrf_token()}}';
            json_obj['att_year'] = $('#academic_year').val();
            json_obj['att_level'] = $('#academic_level').val();
            json_obj['att_class'] = $('#batch').val();
            json_obj['att_section'] = $('#section').val();
            json_obj['att_subject'] = subjectValue;
            json_obj['att_session'] = $('#session').val();
            json_obj['att_date'] = $('#att_date').val();
            json_obj['std_count'] = $('#std_count').val();
            json_obj['send_automatic_sms'] = $("input[name=send_automatic_sms]:checked").val();
            // input others data
            rowData.find('input').each(function(i, el) {
                json_obj[$(el).attr('name')] = $(el).val();
            });

            // ajax request
            $.ajax({
                url: url,
                type: 'POST',
                cache: false,
                data: json_obj, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // checking
                    if(data.status=='success'){
                        // att list
                        var att_list = data.att_list;
                        // checking updated_list
                        if(att_list){
                            for (var i=0; i<att_list.length; i++){
                                // update attendance info
                                $('#att_id_'+att_list[i]['std_id']).val(att_list[i]['att_id']);
                            }
                        }else{
                            $('#attendanceContainer').html('');
                            // sweet alert
                            swal("Success", data.msg, "success");
                        }
                    }else{
                        $('#attendanceContainer').html('');
                        // sweet alert
                        swal("Warning", data.msg, "warning");
                    }
                },

                error:function(data){
                    // show waiting dialog
                    waitingDialog.hide();
                    // sweet alert
                    swal("Error", 'Unable to load data form server', "error");
                }
            });
        });

        // attendance click action
        $('.set-attendance').click(function () {
            // td id
            var id = $(this).attr('id');
            // find attendance type
            if($(this).hasClass('alert-info')) {
                setAttendance($(this), id,'alert-info', 'alert-success', 1, 'P');
            }else if($(this).hasClass('alert-success')){
                setAttendance($(this), id,'alert-success', 'alert-danger', 0, 'A');
            }else if($(this).hasClass('alert-danger')){
                setAttendance($(this), id,'alert-danger', 'alert-warning', 2, 'L');
            }else if($(this).hasClass('alert-warning')){
                setAttendance($(this), id, 'alert-warning', 'alert-success', 1, 'P');
            }

            // set attendance detail
            function setAttendance(context, id, remove_class, add_class, att_type, att_text) {
                context.removeClass(remove_class);
                context.addClass(add_class);
                context.text(att_text);
                $('#att_type_'+id).val(att_type);
            }
        });
    });
</script>
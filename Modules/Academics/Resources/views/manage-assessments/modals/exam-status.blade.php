
<div class="col-md-10  col-md-offset-1">
    <h5 class="text-bold text-center bg-green">Semester Exam Status</h5>
    <form id="exam_status_update_form" method="POST">
        {{--exam status--}}
        @php $esStatus = $examStatus->status; @endphp
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="es_id" value="{{$examStatus->id}}" />
        <input type="hidden" id="e_status" value="{{$esStatus}}" />
        <table class="table table-responsive table-bordered table-striped text-center">
            <thead>
            <tr>
                <th>Academic Year</th>
                <th>Semester Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Exam Status</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{$academicYear->year_name}}</td>
                <td>{{$semesterProfile->name}}</td>
                <td>{{date('d M, Y', strtotime($semesterProfile->start_date))}}</td>
                <td>{{date('d M, Y', strtotime($semesterProfile->end_date))}}</td>
                <td>
                    <button id="es_button" class="btn {{$esStatus==0?'btn-warning':'btn-success'}}" type="submit">
                        {{$esStatus==0?'Not Published':'Published'}}
                    </button>

                    @if($esStatus==1)
                        <button id="es_update_button" class="btn btn-danger" type="button">Update</button>
                        <button id="es_sendSms_button" class="btn btn-danger" type="button">Send SMS</button>
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    </form>
</div>


<script type="text/javascript">
    $(document).ready(function(){

        // request for section list using batch and section id
        $('form#exam_status_update_form').on('submit', function (e) {
            e.preventDefault();

            // checking
            if($('#e_status').val()==0){
                // add academic details
                $(this)
                    .append('<input type="hidden" name="year" value="'+$('#year').val()+'"/>')
                    .append('<input type="hidden" name="level" value="'+$('#level').val()+'"/>')
                    .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                    .append('<input type="hidden" name="section" value="'+$('#section').val()+'"/>')
                    .append('<input type="hidden" name="semester" value="'+$('#semester').val()+'"/>');

                // ajax request
                $.ajax({
                    url: '/academics/manage/assessments/exam/status/update',
                    type: 'POST',
                    cache: false,
                    data: $('form#exam_status_update_form').serialize(),
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Submitting...');
                    },

                    success:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // checking
                        if(data.status=='success'){
                            var es_button = $('#es_button');
                            es_button.removeClass('btn-info');
                            es_button.addClass('btn-success');
                            es_button.text('Published');
                            // es status
                            $('#e_status').val(1);
                        }else{
                            alert(data.msg);
                        }

                    },

                    error:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // statements
                        alert('Unable to perform the action');
                    }
                });
            }else{
                // statements
                alert('Exam Result Already Published')
            }
        });


        // request for section list using batch and section id

        $('#es_update_button').click(function () {

            // add academic details
            $(this)
                .append('<input type="hidden" name="year" value="'+$('#year').val()+'"/>')
                .append('<input type="hidden" name="level" value="'+$('#level').val()+'"/>')
                .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                .append('<input type="hidden" name="section" value="'+$('#section').val()+'"/>')
                .append('<input type="hidden" name="semester" value="'+$('#semester').val()+'"/>');

            // ajax request
            $.ajax({
                url: '/academics/manage/assessments/exam/status/update',
                type: 'POST',
                cache: false,
                data: $('form#exam_status_update_form').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Submitting...');
                },

                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // checking
                    if(data.status=='success'){
                        alert(data.msg);
                    }else{
                        alert(data.msg);
                    }

                },

                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // statements
                    alert('Unable to perform the action');
                }
            });
        });


        // request for section list using batch and section id

        $('#es_sendSms_button').click(function () {

            // add academic details
            $(this)
                .append('<input type="hidden" name="year" value="'+$('#year').val()+'"/>')
                .append('<input type="hidden" name="level" value="'+$('#level').val()+'"/>')
                .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                .append('<input type="hidden" name="section" value="'+$('#section').val()+'"/>')
                .append('<input type="hidden" name="semester" value="'+$('#semester').val()+'"/>');

            // ajax request
            $.ajax({
                url: '/academics/manage/assessments/exam/result/sendsms',
                type: 'POST',
                cache: false,
                data: $('form#exam_status_update_form').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('SMS sending...');
                },

                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // checking
                    if(data=='success') {
                        swal("Success!", "Sms Successfully Sent", "success");
                    } else {
                        swal("Alert", "Can't sent sms low sms credit", "error");
                    }

                },

                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // statements
                    alert('Unable to perform the action');
                }
            });
        });



    });
</script>
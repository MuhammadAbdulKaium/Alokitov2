
        <div class="box-body table-responsive">
            @if($allEnrollments->count()>0)
                <form id="manage_student_profile_form_update">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="request_type" value="manage_std_profile">
                    <input type="hidden" name="std_count" id="std_count" value="{{$allEnrollments->count()}}">

                    <table id="updateStudentList" class="table table-striped text-center">
                        <thead>
                        <tr class="bg-gray">
                            <th>#</th>
                            <th>Roll NO.</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Punch ID</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($allEnrollments as $index=>$enroll)
                            <tr>
                                <td>{{($index+1)}}</td>
                                <td>
                                    <input type="hidden" id="std_id_{{$index+1}}" value="{{$enroll->id}}">
                                    <input type="hidden" id="user_id_{{$index+1}}" value="{{$enroll->user_id}}">
                                    <div class="input-group">
                                        <input type="text"  class="form-control text-center" name="std_list[{{$enroll->id}}][gr_no]" value="{{$enroll->roll_no}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="std_list[{{$enroll->id}}][name]" value="{{$enroll->name}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="std_list[{{$enroll->id}}][username]" value="{{$enroll->username}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" id="email_{{$index+1}}" class="form-control text-center studentemail" data-id="email_{{$index+1}}" name="std_list[{{$enroll->id}}][email]" value="{{$enroll->email}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" id="student_punch_id_{{$index+1}}" data-id="student_punch_id_{{$index+1}}" class="form-control text-center studentpunch" name="std_list[{{$enroll->id}}][punch_id]" value="{{$enroll->punch_id}}">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success pull-right">Submit</button>
                    </div>
                </form>
            @else
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in text-center" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="fa fa-warning"></i> No result found. </h5>
                </div>
            @endif
        </div>


        <script>


            $(function () {
                var myDataTable = $('#updateStudentList').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": true,
                    "pageLength": 25
                });

            // request for parent list using batch section id
            $('form#manage_student_profile_form_update').on('submit', function (e) {
                e.preventDefault();

                // subject info
                // dataTable row calculation
                var rowData = $();
                for (var i = 0; i < myDataTable.rows()[0].length; i++) {
                    rowData = rowData.add(myDataTable.row(i).node())
                }
                // json obj
                var json_obj = {};
                // input token
                json_obj['_token'] = '{{csrf_token()}}';
                json_obj['request_type'] = 'manage_std_profile';
                json_obj['std_count'] = $('#std_count').val();
                // input others data
                rowData.find('input').each(function(i, el) {
                    json_obj[$(el).attr('name')] = $(el).val();
                });

                // ajax request
                $.ajax({
                    url: "/student/update/profile",
                    type: 'POST',
                    cache: false,
                    data: json_obj,
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
                            // sweet alert
                            swal("Success", data.msg, "success");
                        }else{
                            // sweet alert
                            swal("Warning", data.msg, "warning");

                        }
                    },

                    error:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            });



            // check stuenent single punch id

                $('input.studentpunch').keyup(function (e) {
                    var student_punch_id=$(this).attr("data-id");
                    var student_punch_value=$('#'+student_punch_id).val();


                    $.ajax({
                        url: "{{ url('/student/profile/check/single/punchid') }}",
                        type: 'POST',
                        cache: false,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "student_punch_id": student_punch_value,
                        }, //see the $_token
                        datatype: 'application/json',

                        beforeSend: function() {
                            //console.log(JSON.stringify(std_emails));

                        },
                        success:function(data){
                            if(data==0){
                                $('#'+student_punch_id).next('.alert-warning').remove();
                            } else {
                                $('#'+student_punch_id).next('.alert-warning').remove();
                                var warning ='<div class="alert-warning"> Punch Id Aleardy Exists</div>';
                                $('#'+student_punch_id).after(warning);
                            }

                        },

                        error:function(){

                        }
                    });



                });

                // check single email id


                // check stuenent single punch id

                $('input.studentemail').keyup(function (e) {
                    var student_email_id=$(this).attr("data-id");
                    var student_email_value=$('#'+student_email_id).val();


                    $.ajax({
                        url: "{{ url('/student/profile/check/single/email') }}",
                        type: 'POST',
                        cache: false,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "email": student_email_value,
                        }, //see the $_token
                        datatype: 'application/json',

                        beforeSend: function() {
                            //console.log(JSON.stringify(std_emails));

                        },
                        success:function(data){
                            if(data==0){
                                $('#'+student_email_id).next('.alert-warning').remove();
                            } else {
                                $('#'+student_email_id).next('.alert-warning').remove();
                                var warning ='<div class="alert-warning"> Email Id Aleardy Exists</div>';
                                $('#'+student_email_id).after(warning);
                            }

                        },

                        error:function(){

                        }
                    });



                });




//                alert('ddd');









            });


        </script>
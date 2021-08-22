<!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Import Student List</h4>
            </div>
            <div class="modal-body">
                <form id="studentImportForm" action="/student/update/profile/import/excel" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group field-stumaster-importfile">

                                <input id="student_import_file" type="file" name="student_import" title="Browse Excel File" required>
                                <div class="hint-block">[<b>NOTE</b> : Only upload <b>.xlsx</b> file format.]</div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="callout callout-info">
                                <h4><i class="fa fa-bullhorn" aria-hidden="true"></i> Instructions :</h4>
                                <ol>

                                    {{--<li>All date must be enter <strong>DD-MM-YYYY</strong> format.</li>--}}

                                    {{--<li>Birth date must be less than current date.</li>--}}
                                    <li>Email ID should be in valid email format and unique in the system.</li>
                                    <li>Max upload records limit is <strong>300</strong>.</li>
                                    <li>Student import data must match with current application language.</li>
                                </ol>
                            </div><!--./callout-->
                        </div><!--./col-->
                    </div><!--./row-->
                </div><!--./box-body-->
                <div class="box-footer">
                    <a id="submitBtn" class="btn btn-primary"><i class="fa fa-upload"></i> Import</a>	</div>
            </div><!--./box-->
        </form>
        </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>

<script>

//    $("#submitBtn").click(function(){
//
//
//        $('#globalModal').modal('hide');
//        $('#student_profile_list_container').html('');
//
//    });

        $('#submitBtn').click(function(){
                        // ajax request

            var formData = new FormData($('#studentImportForm')[0]);

            $.ajax({
                url: "/student/update/profile/import/excel",
                type: 'POST',
                async: false,
                contentType: false,
                processData: false,
                cache: false,
                data: formData,
                beforeSend: function() {
                    // show waiting dialog
                    $('#globalModal').modal('hide');
                    waitingDialog.show('Loading...');
                },

                success:function(data){
                    // hide waiting dialog

                    waitingDialog.hide();

                    // checking
                    if(data.status=='success'){
                        $('#studentList').html('');
                        $('#studentList').append(data.html);
                        studentEmailCheck();
                        studentCheckPunchId();

                    }else{
                        swal("Error", 'Unable to load data form server', "error");
                    }
                },

                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    swal("Error", 'Unable to load data form server', "error");

        //                        alert(JSON.stringify(data));
                }
            });
        });





function studentCheckPunchId(){
    // check punch id

    var student_punch_ids = [];
    var std_ids = [];
    var user_ids = [];
    var std_count = $("#std_count").val();
    for(var i = 1 ; i<= std_count; i++){
        student_punch_ids[i] = $("#student_punch_id_"+i).val();
        std_ids[i] = $("#std_id_"+i).val();
        user_ids[i] = $("#user_id_"+i).val();
    }
    if(std_count > 0){
        $.ajax({
            url: "{{ url('/student/profile/check/punchids') }}",
            type: 'POST',
            cache: false,
            data: {
                "_token": "{{ csrf_token() }}",
                "student_id": JSON.stringify(std_ids),
                "student_punch_id": JSON.stringify(student_punch_ids),
                "user_id": JSON.stringify(user_ids),
            }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                //console.log(JSON.stringify(std_emails));

            },

            success:function(data){
                console.log(data);
                for(var i = 1 ; i<= std_count; i++){
                    if(data[i] == 1){
                        var warning ='<div class="alert-warning"> Punch Id Aleardy Exists</div>';
                        $("#student_punch_id_"+i).after(warning);
                    }
                }

            },

            error:function(){

            }
        });
    }

}



function studentEmailCheck(){
    // check email id

    var std_emails = [];
    var std_ids = [];
    var user_ids = [];
    var std_count = $("#std_count").val();
    for(var i = 1 ; i<= std_count; i++){
        std_emails[i] = $("#email_"+i).val();
        std_ids[i] = $("#std_id_"+i).val();
        user_ids[i] = $("#user_id_"+i).val();
    }
    if(std_count > 0){
        $.ajax({
            url: "{{ url('/student/profile/check/emails') }}",
            type: 'POST',
            cache: false,
            data: {
                "_token": "{{ csrf_token() }}",
                "student_id": JSON.stringify(std_ids),
                "student_email": JSON.stringify(std_emails),
                "user_id": JSON.stringify(user_ids),
            }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                //console.log(JSON.stringify(std_emails));

            },

            success:function(data){
                console.log(data);
                for(var i = 1 ; i<= std_count; i++){
                    if(data[i] == 1){
                        var warning ='<div class="alert-warning"> Email Id Aleardy Exists</div>';
                        $("#email_"+i).after(warning);
                    }
                }

            },

            error:function(){

            }
        });
    }

}



</script>


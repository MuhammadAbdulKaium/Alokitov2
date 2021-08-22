<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 8/21/17
 * Time: 4:30 PM
 */
?>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Employee Attendance Upload </h3>
</div>
<form id="att-file-up-form" action="{{url('/employee/upload-attendance/fileUpSave')}}" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label" for="startDate">File</label>
                        <input type="file" class="form-control" name="myFile" id="myFile">
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <a class="btn btn-danger pull-right" id="upload"> Upload </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-12">
                    <p>
                        <br><br>You can <a href="{{url('demo_attendance_employee_file.xls')}}">Download</a> Demo Attendance File as your guide.
                        <br>Enter everything as text. No need any formation.
                        <br>".xls" and ".xlsx" file format  only.
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div id="myDataTable" style="display: none">
                </div>
            </div>
        </div>
    </div>
    <!--./modal-body-->
    <div class="modal-footer">
        <div class="col-sm-12">
            <button type="submit" class="btn btn-info pull-left" id="create"></i> Save </button>
            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
        </div>
    </div>
    <!--./modal-footer-->
</form>

<div class="modal" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-body" id="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#upload').click(function () {
        var token = "{{ csrf_token() }}";
        var file_data = $('#myFile').prop('files')[0];
        var dataSet = new FormData();
        dataSet.append('file', file_data);
        dataSet.append('_token', token);
        $.ajax({
            url: "{{ url('employee/upload-attendance/fileUp')}}",
            type: 'post',
            data: dataSet,
            dtatType:'text',
            cache: false,
            contentType: false,
            processData: false,
            success: function (data){
                $('#myDataTable').show().html(data);
            }
        });
    });
    $(document).ready(function(){
        // validate signup form on keyup and submit
        var validator = $("#att-file-up-form").validate({
            // Specify validation rules
            rules: {
                myFile: {
                    required: true,
                },
            },

            // Specify validation error messages
            messages: {
                //name:"Shift Name is required.",
                //startTime:"Shift Start Time is required.",
                //endTime:"Shift End Time is required.",
            },

            //errorLabelContainer: '.errorTxt',


            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },

            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
                $(element).closest('.form-group').addClass('has-success');
            },

            debug: true,
            success: "valid",
            errorElement: 'span',
            errorClass: 'help-block',

            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 8/16/17
 * Time: 2:13 PM
 */
//use Modules\Employee\Http\Controllers\MyHelper;
?>
<style>
    #myDataTable {
        height: 300px;
        overflow: auto;
        width: 100%;
    }
    #myDataTable table{
        width:100%;
    }
</style>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Employee Attendance </h3>
</div>
<form autocomplete="off" id="shift-create-form" action="{{url('/employee/add-attendance/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="department">Department </label>
                    <select class="form-control" id="department">
                        <option value="">Select</option>
                        @foreach($allDep as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="designation">Designation </label>
                    <select id="designation" class="form-control designation" name="designation">
                        <option value="">--- Select Designation ---</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-12">
                <button class="btn btn-primary pull-right" type="button" id="find">Find</button>
            </div>
        </div>

        <div class="col-sm-12">
            <div id="myDataTable" style="display: none">

            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="startDate">Start Date</label>
                    <input type="text" class="form-control" name="startDate" id="startDate">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="name">Start Time</label>
                    <div class='input-group date' id="startTime">
                        <input required name="startTime" type='text' class="form-control" />
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="endDate">End Date</label>
                    <input type="text" class="form-control" name="endDate" id="endDate">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="name">End Time</label>
                    <div class='input-group date' id="endTime">
                        <input required name="endTime"  type='text' class="form-control" />
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--./modal-body-->
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-left" id="create"></i> Save </button>
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
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


<style>
    .pad-right{
        padding-right: 20px;
    }
</style>

<script type="text/javascript">
    $('#find').click(function (){
        var token = "{{ csrf_token() }}";
        var department = $('#department').val();
        var designation = $('#designation').val();
        var dataSet = '_token='+token+'&department='+department+'&designation='+designation;
        $.ajax({
            url: "{{ url('employee/employee-attendance/emp_list')}}",
            type: 'post',
            data: dataSet,
            success: function (data) {
                $('#myDataTable').show().html(data);
            }
        });
    });
    $(document).ready(function(){
        $('#startDate').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
        $('#endDate').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});

        // validate signup form on keyup and submit
        var validator = $("#shift-create-form").validate({
            // Specify validation rules
            rules: {
                startDate: {
                    required: true,
                },startTime: {
                    required: true,
                },endDate: {
                    required: true,
                },endTime: {
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
                if($('.attenAloChkBox:checked').length >0){
                    form.submit();
                }else{
                    alert("No employee selected.");
                }
            }
        });


        // request for section list using batch id
        jQuery(document).on('change','#department',function(){
            // get academic level id
            var dept_id = $(this).val();
            var op="";

            $.ajax({
                url: '/employee/find/designation/list/'+dept_id,
                type: 'GET',
                cache: false,
                datatype: 'application/json',

                beforeSend: function() {
                    //
                },

                success:function(data){
                    op+='<option value="0" selected disabled>--- Select Designation ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                    }
                    // refresh attendance container row
                    $('#employee_list_container').html('');
                    // set value to the academic batch
                    $('.designation').html("");
                    $('.designation').append(op);
                },
                error:function(){
                    // sweet alert
                    swal("Error", 'Unable to load data form server', "error");
                }
            });
        });



        /// date time picker for add attendnace


        $(document).ready(function(){
            $('#startTime').datetimepicker({
                format: 'LT'
            });

            $('#endTime').datetimepicker({
                format: 'LT'
            });
        });



    });
</script>
<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 8/9/17
 * Time: 1:06 PM
 */
use Modules\Employee\Http\Controllers\MyHelper;
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
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Shift Assign </h3>
</div>
<form id="shift-create-form" action="{{url('/employee/shift_allocation/store')}}" method="POST">
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
                    <select class="form-control" id="designation">
                        <option value="">Select</option>
                        @foreach($allDes as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                        @endforeach
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
                    <label class="control-label" for="shiftName">Shift Name</label>
                    <select class="form-control" id="shiftName" name="shiftName">
                        <option value="">Select</option>
                        @foreach($allShift as $data)
                        <option value="{{$data->id}}">{{$data->shiftName}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="effectiveDateFrom">Effective Date From</label>
                    <input class="form-control" name="effectiveDateFrom" id="effectiveDateFrom" readonly>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="effectiveDateTo">Effective Date To</label>
                    <input class="form-control" name="effectiveDateTo" id="effectiveDateTo" readonly>
                    <div class="help-block"></div>
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
                url: "{{ url('employee/shift_allocation/emp_list')}}",
                type: 'post',
                data: dataSet,
                success: function (data) {
                    $('#myDataTable').show().html(data);
                }
            });
        });
    $(document).ready(function(){
        $('#effectiveDateFrom').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
        $('#effectiveDateTo').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});

        // validate signup form on keyup and submit
        var validator = $("#shift-create-form").validate({
            // Specify validation rules
            rules: {
                shiftName: {
                    required: true,
                },
                effectiveDateFrom: {
                    required: true,
                },
                effectiveDateTo: {
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
                if($('.shiftAloChkBox:checked').length >0){
                    form.submit();
                }else{
                    alert("No employee selected.");
                }
            }
        });
    });
</script>
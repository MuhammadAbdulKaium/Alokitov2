<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 8/28/17
 * Time: 4:16 PM
 */
/*foreach($accChart as $d){
      echo $d;
}*/
?>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Salary Component </h3>
</div>
<form id="salaryComponent-create-form" action="{{url('/payroll/salary-component/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="errorTxt"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="salaryComponentName">Salary Component Name: </label>
                    <input id="salaryComponentName" class="form-control" name="salaryComponentName" maxlength="50" type="text">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="code">Code: </label>
                    <input id="code" class="form-control" name="code" maxlength="50" type="text">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="type">Type: </label>
                    <select class="form-control" id="type" name="type">
                        <option value="">Select</option>
                        <option value="A">Allowance</option>
                        <option value="D">Deduction</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="amountType">Amount Type: </label>
                    <select class="form-control" id="amountType" name="amountType">
                        <option value="">Select</option>
                        <option value="B">Basic</option>
                        <option value="OT">Over Time</option>
                        <option value="LN">Lone</option>
                        <option value="PF">Provident Fund</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6" id="fa">
                <div class="form-group">
                    <label class="control-label" for="fixedAmount">Fixed Amount: </label>
                    <input id="fixedAmount" class="form-control" name="fixedAmount" maxlength="50" type="text">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6" id="fp">
                <div class="form-group">
                    <label class="control-label" for="fixedPercentage">Fixed Percentage: </label>
                    <input id="fixedPercentage" class="form-control" name="fixedPercentage" maxlength="50" type="text">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6" id="pbo">
                <div class="form-group">
                    <label class="control-label" for="percentageBase">Percentage Based On: </label>
                    <select class="form-control" id="percentageBase" name="percentageBase">
                        <option value="">Select</option>
                        <option value="B">Basic</option>
                        <option value="G">Gross</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        {{--<div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="salaryComponentHead">Salary Component Head: </label>
                    <select class="form-control" id="salaryComponentHead" name="salaryComponentHead">
                        <option value="">Select Salary Component Head</option>
                        @foreach($accChart as $d){
                        <option value="{{$d->id}}">{{$d->chart_code}} ----- {{$d->chart_name}}</option>
                        }
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>--}}
    </div>
    <!--./modal-body-->
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-left" id="create"></i> Create</button>
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
    $('#amountType').change(function (){
        var value = $(this).val();
        if(value == 'F'){
            $('#fa').show(250);
            $('#fp').hide(250);
            $('#pbo').hide(250);
        }else if(value == 'P'){
            $('#fa').hide(250);
            $('#fp').show(250);
            $('#pbo').show(250);
        }else{
            $('#fa').hide(250);
            $('#fp').hide(250);
            $('#pbo').hide(250);
        }
    });

    $(document).ready(function(){
        $('#fa').hide();
        $('#fp').hide();
        $('#pbo').hide();
        // validate signup form on keyup and submit
        var validator = $("#salaryComponent-create-form").validate({
            // Specify validation rules
            rules: {
                salaryComponentName: {
                    required: true,
                    minlength: 3,
                    maxlength: 100,
                },
                code: {
                    required: true,
                    minlength: 2,
                    maxlength: 10,
                },
                type: {
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

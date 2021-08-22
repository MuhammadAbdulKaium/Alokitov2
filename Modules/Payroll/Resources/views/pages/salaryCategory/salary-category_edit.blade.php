<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 9/10/17
 * Time: 4:26 PM
 */
?>
{{--{{dd($SalaryComponent)}}--}}
<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Salary Component</h4>
        </div>
        <div class="modal-body">
            <form id="salaryComponent-create-form" action="{{url('/payroll/salary-component/update')}}" method="POST">
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
                                <input id="salaryComponentName" value="{{$SalaryComponent->name}}" class="form-control" name="salaryComponentName" maxlength="50" type="text">
                                <input id="id"name="id" value="{{$SalaryComponent->id}}"  type="hidden">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="code">Code: </label>
                                <input id="code" value="{{$SalaryComponent->code}}"  class="form-control" name="code" maxlength="50" type="text">
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="type">Type: </label>
                                <select class="form-control" id="type" name="type">
                                    <option selected value="">Select</option>
                                    <option @if($SalaryComponent->type=='A') selected @endif value="A">Allowance</option>
                                    <option @if($SalaryComponent->type=='D') selected @endif value="D">Deduction</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="amountType">Amount Type: </label>
                                <select class="form-control" id="amountType" name="amountType">
                                    <option selected value="">Select</option>
                                    <option @if($SalaryComponent->amount_type == 'B') selected @endif value="B">Basic</option>
                                    <option @if($SalaryComponent->amount_type == 'OT') selected @endif value="OT">Over Time</option>
                                    <option @if($SalaryComponent->amount_type == 'LN') selected @endif value="LN">Lone</option>
                                    <option @if($SalaryComponent->amount_type == 'PF') selected @endif value="PF">Provident fund</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6" id="fa" <?php if($SalaryComponent->amount_type=='F'){ echo '';}else{echo 'style="display:none"';}?>>
                            <div class="form-group">
                                <label class="control-label" for="fixedAmount">Fixed Amount: </label>
                                <input id="fixedAmount" value="{{$SalaryComponent->fixed_amount}}" class="form-control" name="fixedAmount" maxlength="50" type="text">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6" id="fp" <?php if($SalaryComponent->amount_type=='P'){ echo '';}else{echo 'style="display:none"';}?>>
                            <div class="form-group">
                                <label class="control-label" for="fixedPercentage">Fixed Percentage: </label>
                                <input id="fixedPercentage" value="{{$SalaryComponent->fixed_percent}}" class="form-control" name="fixedPercentage" maxlength="50" type="text">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6" id="pbo" <?php if($SalaryComponent->amount_type=='P'){ echo '';}else{echo 'style="display:none"';}?>>
                            <div class="form-group">
                                <label class="control-label" for="percentageBase">Percentage Based On: </label>
                                <select class="form-control" id="percentageBase" name="percentageBase">
                                    <option selected value="">Select</option>
                                    <option @if($SalaryComponent->percent_base=='B') selected @endif value="B">Basic</option>
                                    <option @if($SalaryComponent->percent_base=='G') selected @endif value="G">Gross</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--./modal-body-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info pull-left" id="create"></i> Create</button>
                    <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
                </div>
                <!--./modal-footer-->
            </form>
        </div>
    </div>
</div>



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
/*        $('#fa').hide();
        $('#fp').hide();
        $('#pbo').hide();*/
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
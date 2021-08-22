<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 10/8/17
 * Time: 3:37 PM
 */
?>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Provident fund Rules </h3>
</div>
<form id="salaryPF-create-form" action="{{url('payroll/pf-rules/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="approveDate">Approve Date</label>
                    <input class="form-control" type="date" name="approveDate" id="approveDate">
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <label for="pfType">Provident fund Type</label>
                    <select class="form-control" name="pfType" id="pfType">
                        <option value="">Select provident fund Component</option>
                        @foreach($SalaryPfLists as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="pfDedRule">PF Assign Rule For Deduction</label>
                    <select class="form-control" name="pfDedRule" id="pfDedRule">
                        {{--
                        TR-> This Rule (if choose this: assigned or nor only this rule will be used)
                        EA-> if Empty this Alternet rule
                        AR-> Assigned Rule
                        --}}
                        <option value="">Select Provident fund Assign Rule</option>
                        <option value="TR">This Rule Always</option>
                        <option value="EA">This Rule If assigned rule is empty</option>
                        <option value="AR">Assigned Rule Always</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="dedType">Deduct Type</label>
                    <select class="form-control" name="dedType" id="dedType">
                        <option value="">Select Type</option>
                        <option value="P">Percent(%)</option>
                        <option value="A">Amount</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="pfDedFrom">Provident fund Deduct From</label>
                    <select class="form-control" name="pfDedFrom" id="pfDedFrom">
                        <option value="">Select Deduct form</option>
                        <option value="B">Basic</option>
                        <option value="G">Gross</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="amtVal">Value</label>
                    <input type="number" class="form-control" name="amtVal" id="amtVal">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="compContributeType">Company Contribution Type</label>
                    <select class="form-control" name="compContributeType" id="compContributeType">
                        <option value="">Select Company Contribution Type</option>
                        <option value="B">Basic (%)</option>
                        <option value="G">Gross (%)</option>
                        <option value="V">Value of Emp Deduction (%)</option>
                        <option value="A">Amount</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="compContribute">Company Contributio value</label>
                    <input type="number" class="form-control"  name="compContribute" id="compContribute">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="minEligableTime">Minimum Eligable Time (Month)</label>
                    <input type="number" class="form-control"  name="minEligableTime" id="minEligableTime">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="deductionDate">Deduction Date </label>
                    <input type="date" class="form-control"  name="deductionDate" id="deductionDate">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" name="remarks" id="remarks" rows="3"></textarea>
                </div>
            </div>
        </div>
        <!--./modal-body-->
        <div class="modal-footer">
            <button type="submit" class="btn btn-info pull-left" id="create"> Create</button>
            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
        </div>
    </div>
    <!--./modal-footer-->
</form>
<script type="text/javascript">
    $(document).ready(function(){
        // validate signup form on keyup and submit
        var validator = $("#salaryPF-create-form").validate({
            // Specify validation rules
            rules: {
                pfType: {
                    required: true,
                }, pfDedRule: {
                    required: true,
                }, pfDedFrom: {
                    required: true,
                }, dedType: {
                    required: true,
                }, amtVal: {
                    required: true,
                }, compContributeType: {
                    required: true,
                },compContribute: {
                    required: true,
                },minEligableTime: {
                    required: true,
                },deductionDate: {
                    required: true,
                },
            },

            // Specify validation error messages
            messages: {
            },

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
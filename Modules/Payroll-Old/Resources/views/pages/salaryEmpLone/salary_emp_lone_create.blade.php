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
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Loan </h3>
</div>
<form id="salaryLone-create-form" action="{{url('payroll/emp-lones/store')}}" method="POST">
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
                    <label for="emp_id">Employee List</label>
                    <select class="form-control" name="emp_id" id="emp_id">
                        <option value="">Select Employee</option>
                        @foreach($emp as $data)
                            <option value="{{$data->id}}">{{$data->empName($data->id).' ( '.$data->department()->name.', '.$data->designation()->name.' )'}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="loanType">Loan Type</label>
                    <select class="form-control" name="loanType" id="loanType">
                        <option value="">Select Loan</option>
                        @foreach($SalaryLnLists as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="loanAmount">Loan Amount</label>
                    <input class="form-control" type="number" name="loanAmount" id="loanAmount">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="loanFeeType">Loan Fee Type </label>
                    <select class="form-control" name="loanFeeType" id="loanFeeType">
                        <option value="">None</option>
                        <option value="A">Amount</option>
                        <option value="P">Percent</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="installmentNo">Number of Installment (Months) </label>
                    <input class="form-control" type="number" name="installmentNo" id="installmentNo">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="loanFeeAmount">Loan Fee Amount</label>
                    <input class="form-control" type="number" name="loanFeeAmount" id="loanFeeAmount">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="installmentAmount">Repayment Installment Amount</label>
                    <input class="form-control" type="number" name="installmentAmount" id="installmentAmount">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="deductionDate">Deduction Date</label>
                    <input class="form-control" type="date" name="deductionDate" id="deductionDate">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" name="remarks" id="remarks"></textarea>
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
<script>
    $('#loanAmount').keyup(function () { calc(); });
    $('#loanAmount').click(function () { calc(); });
    $('#loanFeeType').change(function () { calc(); });
    $('#installmentNo').keyup(function () { calc(); });
    $('#installmentNo').click(function () { calc(); });
    $('#loanFeeAmount').keyup(function () { calc(); });
    $('#loanFeeAmount').click(function () { calc(); });
    $('#installmentAmount').keyup(function () { calc(); });

    function calc() {
        var loanAmt = $('#loanAmount').val() !='' ? parseFloat($('#loanAmount').val()) * 1  : 0;
        var feeType = $('#loanFeeType').val() !='' ? $('#loanFeeType').val() : 0;
        var instNo = $('#installmentNo').val() != '' ? parseFloat($('#installmentNo').val()) * 1 : 0;
        var feeAmt = $('#loanFeeAmount').val() != '' ? parseFloat($('#loanFeeAmount').val()) * 1 : 0;
        var InstAmt = 0;
        var amt = 0;

        if(feeType == 'A' && feeAmt !=0){
            amt = loanAmt+ feeAmt;
            InstAmt = amt/instNo;
        }else if(feeType == 'P' && feeAmt !=0){
            amt = loanAmt + loanAmt*(feeAmt)/100;
            InstAmt = amt/instNo;
        }else{
            feeAmt = 0;
            amt = loanAmt;
            InstAmt = amt/instNo;
        }

        $('#loanAmount').val(loanAmt);
        $('#installmentNo').val(instNo);
        $('#loanFeeAmount').val(feeAmt);
        $('#installmentAmount').val(InstAmt);
    }

    $(document).ready(function(){
        // validate signup form on keyup and submit
        var validator = $("#salaryLone-create-form").validate({
            // Specify validation rules
            rules: {
                emp_id: {required: true
                }, loanType: {required: true
                }, loanAmount: {required: true, digits: true, minlength: 3,
                }, installmentNo: {required: true, digits: true, min:1
                }, deductionDate: {required: true
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












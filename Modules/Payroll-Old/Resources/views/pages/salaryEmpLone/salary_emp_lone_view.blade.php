<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 11/5/17
 * Time: 12:36 PM
 */
?>
<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            <h3 class="box-title"><i class="fa fa-eye"></i>Overtime Rule And Rate </h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <label>Approve Date</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$empLone->approve_date}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Employee Name</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$empLone->empName($empLone->employee_id)}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Loan Name</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$empLone->loan_type_id}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Loan Amount</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$empLone->loan_amount}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Loan Fee Type</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$empLone->loan_fee_type}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Loan Installment</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$empLone->installment_no}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Loan Fee Amount</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$empLone->loan_fee_amount}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Loan Installment Per Month</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$empLone->installment_amount}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Deduction Date</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$empLone->deduction_date}}
                </div>
            </div><br>


            <!--./modal-body-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            </div>
        </div>
    </div>
</div>
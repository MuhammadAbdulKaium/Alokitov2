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
            <h3 class="box-title"><i class="fa fa-eye"></i> Monthly Deduction and Allowance </h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <label>Employee Name</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$empMonDedAllo->empName($empMonDedAllo->employee_id)}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Salary component</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$empMonDedAllo->salCompName($empMonDedAllo->component_id)}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Salary component</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$empMonDedAllo->amount}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Effective Month</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    @php
                        $monthNum  = $empMonDedAllo->effective_month;
                        $monthName = date('F', mktime(0, 0, 0, $monthNum, $empMonDedAllo->effective_month))
                    @endphp
                    {{$monthName}}, {{$empMonDedAllo->effective_year}}
                </div>
            </div><br>

            <!--./modal-body-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            </div>
        </div>
    </div>
</div>
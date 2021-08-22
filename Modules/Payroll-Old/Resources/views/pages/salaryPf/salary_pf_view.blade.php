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
            <h3 class="box-title"><i class="fa fa-eye"></i>Provident fund Rule </h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <label>Approve Date</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$salPfRule->approve_date}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Provident fund Type</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$salPfRule->pf_type_id}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>PF Assign Rule For Deduction</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    @if( $salPfRule->pf_ded_rule ='TR'){{'Select Provident fund Assign Rule'}}
                    @elseif( $salPfRule->pf_ded_rule ='EA'){{'This Rule Always'}}
                    @elseif( $salPfRule->pf_ded_rule ='AR'){{'This Rule If assigned rule is empty'}}
                    @endif
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Deduct Type</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    @if( $salPfRule->pf_ded_rule ='P'){{'Percent(%)'}}
                    @elseif( $salPfRule->pf_ded_rule ='A'){{'Amount'}}
                    @endif
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Provident fund Deduct From</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    @if( $salPfRule->pf_ded_type ='B'){{'Basic'}}
                    @elseif( $salPfRule->pf_ded_type ='G'){{'Gross'}}
                    @endif
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Value</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$salPfRule->amt_val}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Company Contribution Type</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    @if( $salPfRule->comp_contribute_type ='B'){{'Basic (%)'}}
                    @elseif( $salPfRule->comp_contribute_type ='G'){{'Gross (%)'}}
                    @elseif( $salPfRule->comp_contribute_type ='V'){{'Value of Emp Deduction (%)'}}
                    @elseif( $salPfRule->comp_contribute_type ='A'){{'Amount'}}
                    @endif
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Company Contributio value</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$salPfRule->comp_contribute}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Minimum Eligable Time</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$salPfRule->min_eligable_time}} Months
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Deduction Date</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$salPfRule->deduction_date}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Remarks</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$salPfRule->remarks}}
                </div>
            </div><br>
            <!--./modal-body-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            </div>
        </div>
    </div>
</div>
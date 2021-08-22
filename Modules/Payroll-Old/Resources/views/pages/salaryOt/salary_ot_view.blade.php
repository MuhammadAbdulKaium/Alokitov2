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
                    {{$otRule->approve_date}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Effective Date</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$otRule->effective_date}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>OT Start</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$otRule->ot_start}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>OT End</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$otRule->ot_end}}
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Minimum OT</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$otRule->min_ot}} Hours
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>Maximum OT</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$otRule->max_ot}} Hours
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>OT Grace</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$otRule->ot_grace}} Min
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <label>remarks</label>
                </div>
                <div class="col-md-1">:</div>
                <div class="col-md-5">
                    {{$otRule->remarks}}
                </div>
            </div><br>

            <!--./modal-body-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            </div>
        </div>
    </div>
</div>
<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 11/5/17
 * Time: 12:36 PM
 */
?>
<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 9/11/17
 * Time: 6:17 PM
 */
?>
<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            <h3 class="box-title"><i class="fa fa-eye"></i> Show Salary Component </h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3"><b> Salary Component Name  </b></div>
                <div class="col-md-1">:</div>
                <div class="col-md-6" style="text-align:left">{{$SalaryComponent->name}}</div>
            </div><br>
            <div class="row">
                <div class="col-md-3"> <b>Salary Component Code  </b></div>
                <div class="col-md-1">:</div>
                <div class="col-md-6" style="text-align:left">{{$SalaryComponent->code}}</div>
            </div><br>
            <div class="row">
                <div class="col-md-3"> <b>Component Type  </b></div>
                <div class="col-md-1">:</div>
                <div class="col-md-6" style="text-align:left">
                    @if($SalaryComponent->type == 'A'){{'Allowance'}}
                    @elseif($SalaryComponent->type == 'D'){{'Deduction'}}
                    @endif
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3"> <b>Amount Type  </b></div>
                <div class="col-md-1">:</div>
                <div class="col-md-6" style="text-align:left">
                        @if($SalaryComponent->amount_type == 'B'){{'Basic'}}
                        @elseif($SalaryComponent->amount_type == 'OT'){{'Over Time'}}
                        @elseif($SalaryComponent->amount_type == 'LN'){{'Lone'}}
                        @elseif($SalaryComponent->amount_type == 'PF'){{'Provident fund'}}
                        @else{{'Normal Salary Element'}}
                    @endif
                </div>
            </div><br>
            <!--./modal-body-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            </div>
        </div>
    </div>
</div>
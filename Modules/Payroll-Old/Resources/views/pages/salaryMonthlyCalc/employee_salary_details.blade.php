<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 11/20/17
 * Time: 12:49 PM
 */
?>
<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            <h3 class="box-title"><i class="fa fa-eye"></i>Employee Detail salary </h3>
        </div>
        <div class="modal-body">
            <div>
                @php
                    foreach($salCalc as $data){
                        $component_id[$data->component_id] = $data->component_id;
                        $amount[$data->component_id] = $data->amount;
                    }
                @endphp
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-3">
                            <label>Name: </label>
                        </div>
                        <div class="col-md-1">:</div>
                        <div class="col-md-5">
                            {{\Modules\Payroll\Entities\EmpSalaryAssign::empName($data->employee_id)}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-3">
                            <label>Month</label>
                        </div>
                        <div class="col-md-1">:</div>
                        <div class="col-md-5">
                            {{date('F', mktime(0, 0, 0, $month, 10))}}, {{$year}}
                        </div>
                    </div>
                </div><br>
                <div class="col-md-3"></div>
                <div class="col-md-9">
                    @foreach($salaryComponents as $dataSalCom)
                        @if(in_array($dataSalCom->id,$component_id))
                            <div class="row">
                                <div class="col-md-3">
                                    <label>{{$dataSalCom->name}}</label>
                                </div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-2" style="text-align: right">
                                    {{money_format('%i',floatval($amount[$dataSalCom->id]))}}
                                </div>
                            </div><br>
                        @endif
                    @endforeach
                    @php for($i=1;$i<=8;$i++) echo '---------- ';@endphp
                    <div class="row">
                        <div class="col-md-3">
                            <label>Total</label>
                        </div>
                        <div class="col-md-1">:</div>
                        <div class="col-md-2" style="text-align: right">
                            {{money_format('%i', floatval(array_sum($amount)))}}
                        </div>
                    </div><br>
                </div>

                <!--./modal-body-->
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
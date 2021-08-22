<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 9/28/17
 * Time: 1:35 PM
 */
?>
<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-body">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h3 class="box-title"><i class="fa fa-edit"></i> {{$empSalaryAssign->empName($empSalaryAssign->employee_id)}}'s Salary </h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Employee Name</label>
                    </div>
                    <div class="col-sm-1">
                        <label>:</label>
                    </div><div class="col-sm-6">
                        {{$empSalaryAssign->empName($empSalaryAssign->employee_id)}}
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-sm-3">
                        <label>Pay Scale</label>
                    </div>
                    <div class="col-sm-1">
                        <label>:</label>
                    </div><div class="col-sm-6">
                        {{$empSalaryAssign->salaryStructureName($empSalaryAssign->salary_structure_id)}}
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-sm-3">
                        <label>Salary Amount</label>
                    </div>
                    <div class="col-sm-1">
                        <label>:</label>
                    </div><div class="col-sm-6">
                        @php
                            $allSalComp = $empSalaryAssign->empSalaryStructureAll($empSalaryAssign->employee_id);
                            echo $salary = $empSalaryAssign->salary_amount;
                        @endphp
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-sm-3">
                        <label>Salary Type</label>
                    </div>
                    <div class="col-sm-1">
                        <label>:</label>
                    </div><div class="col-sm-6">
                        @if($empSalaryAssign->salary_type == 'B') {{"Basic"}}
                        @elseif($empSalaryAssign->salary_type == 'G') {{"Gross"}}
                        @endif
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-sm-3">
                        <label>Salary Description</label>
                    </div>
                    <div class="col-sm-1">
                        <label>:</label>
                    </div><div class="col-sm-6">
                        @php
                            $totalPercent = $totalPercentDeduction = $totAlallo = $totalDeduction = $totalAmt =0;
                            //dd($allSalComp);
                            foreach($allSalComp as $data1){
                                    if(!empty($data1->percent)) $totalPercent = $totalPercent + intval($data1->amount);
                                    if(!empty($data1->percent) && $data1->type == 'D') $totalPercentDeduction = $totalPercentDeduction + intval($data1->amount);
                                    if(empty($data1->percent) && $data1->type == 'A') $totAlallo = $totAlallo + intval($data1->amount);
                                    if(empty($data1->percent) && $data1->type == 'D') $totalDeduction = $totalDeduction + intval($data1->amount);
                                }
                            foreach($allSalComp as $data){
                                if($empSalaryAssign->salary_type == 'B'){
                                    if(!empty($data->percent)) $amt = $salary * $data->amount * .01;
                                    else  $amt = $data->amount;

                                    if($data->type == "D") $amount = $amt * (-1);
                                    else $amount = $amt;
                                }elseif($empSalaryAssign->salary_type == 'G'){
                                    if(!empty($data->percent)) $amt = (($salary + $totalDeduction - $totAlallo) / ($totalPercent))  * $data->amount;
                                    else  $amt = $data->amount;

                                    if($data->type == "D") {$amount = $amt * (-1);}
                                    else {$amount = $amt;}
                                }

                                $totalAmt = $totalAmt +  $amount;

                                $show = '';
                                $show .= '<div class="row">';
                                $show .= '<div class="col-sm-4">';
                                $show .= '<label>';
                                $show .=  $data->name;
                                $show .= '</label>';
                                $show .= '</div>';
                                $show .= '<div class="col-sm-1">';
                                $show .= '<label>:</label>';
                                $show .= '</div><div class="col-sm-3">';
                                $show .= $data->amount;
                                $show .= (!empty($data->amount) && $data->percent== 'P') ? ' %' : '';
                                $show .= '</div><div class="col-sm-3" style="text-align: right">';
                                $show .= money_format('%i', $amount);
                                $show .= '</div></div>';
                                echo $show;
                            }
                        for($i=1;$i<=8;$i++) echo '---------- ';
                        @endphp
                        <div class="row">
                            <div class="col-sm-4"><label>Total</label></div>
                            <div class="col-sm-1"><label>:</label></div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3" style="text-align: right">{{money_format('%i',$totalAmt)}}</div>
                        </div><br>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-sm-3">
                        <label>Effective Date</label>
                    </div>
                    <div class="col-sm-1">
                        <label>:</label>
                    </div><div class="col-sm-6">
                        {{$empSalaryAssign->effective_date}}
                    </div>
                </div><br>
                <!--./modal-body-->
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 10/3/17
 * Time: 11:33 AM
 */
use Modules\Payroll\Entities\EmpSalaryAssign;
$date = date('Y-m-d');
$toDate = date('Y-m-t');
?>
@extends('layouts.master')
@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Monthly |<small>Salary Calculation</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Human Resource</a></li>
                <li><a href="#">Employee Management</a></li>
                <li class="active">Monthly Salary Calculation</li>
            </ul>
        </section>

        <section class="content">
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('alert'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif

            <div class="box box-solid">
                <form action="{{url('/payroll/emp-salary-monthly/calc')}}" method="POST">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> View Monthly Salary Calculation </h3>
                        <button type="submit" class="btn btn-success btn-sm pull-right">
                            <i class="fa fa-check"></i>Confirm Calculation
                        </button>
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-5"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date">Effective Date: </label>
                                <input type="date" class="form-control"  name="date" id="date" value="{{$date}}">
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="box-header"></div>
                        <div class="box-body">
                            <?php $i=1;?>
                            <table id="example1" class="table table-striped">
                                <thead>
                                <tr>
                                    <th style="text-align:center">#</th>
                                    <th style="text-align:center">Name</th>
                                    <th style="text-align:center">Pay Scale</th>
                                    <th style="text-align:center" colspan="{{count($salary_component) + 4 }}">Salary</th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center">Total</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    @php($salary_component_id = array())
                                    @foreach($salary_component as $data_sal_comp)
                                        <th>{{ ucfirst($data_sal_comp->name) }}</th>
                                        @php(array_push($salary_component_id,$data_sal_comp->id))
                                    @endforeach
                                    <th>Gross</th>
                                    <th>Lone</th>
                                    <th>Provident Fund</th>
                                    <th>Over Time</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $empIdList = array();
                                    $avoidFromGross = array();
                                    $avoidFromGrossDatas = \Modules\Payroll\Entities\SalaryComponent::whereIn('amount_type', ['OT', 'LN','PF'])->get(['id']);
                                    foreach($avoidFromGrossDatas as $data){
                                        array_push($avoidFromGross,$data->id);
                                    }
                                $ttl=0;
                                @endphp
                                @foreach($emp_salary_assign as $data_sal_ass)
                                    <tr>
                                        @php
                                            $component_ids_ = array();
                                            $names = array();
                                            $types = array();
                                            $amount_types = array();
                                            $amounts = array();
                                            $percents = array();
                                            $pf_id = '';
                                            $id = '';
                                            $component_totoal = array();

                                            $empid = $data_sal_ass->employee_id;
                                            $sal_st_id = $data_sal_ass->salary_structure_id;
                                            $salary = $data_sal_ass->salary_amount;
                                            $allSalaryStructurs = new \Modules\Payroll\Entities\EmpSalaryAssign();
                                            $allSalaryStructur = $allSalaryStructurs->empSalaryStructureAll($empid);
                                            $salary_type = $data_sal_ass->salary_type;
                                            array_push($empIdList,$empid);
                                            foreach($allSalaryStructur as $sal_data){
                                                $component_ids_[$sal_data->component_id] = $sal_data->component_id;
                                                $names[$sal_data->component_id] = $sal_data->name;
                                                $types[$sal_data->component_id] = $sal_data->type;
                                                $amount_types[$sal_data->component_id] = $sal_data->amount_type;
                                                $amounts[$sal_data->component_id] = $sal_data->amount;
                                                $percents[$sal_data->component_id] = $sal_data->percent;
                                            }
                                        @endphp

                                        <td>{{$i++}}.</td>
                                        <td>
                                            {{EmpSalaryAssign::empName($empid)}}
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <input type="hidden" name='empId[]' value="{{$empid}}">
                                        </td>
                                        <td>
                                            {{ EmpSalaryAssign::salaryStructureNameStt($empid)}}
                                            <input type="hidden" name="salStrId[{{$empid}}]" value="{{EmpSalaryAssign::salaryStructureId($empid)}}">
                                        </td>
                                        @php
                                            $extra_ded_allo = $salary_monthly_assign->where('employee_id',$empid)
                                            ->get(['component_id','amount','percent']);
                                            if(!empty($extra_ded_allo)){
                                                foreach ($extra_ded_allo as $d){
                                                $info = \Modules\Payroll\Entities\SalaryComponent::where('id',$d->component_id)->first();
                                                    $component_ids_[$d->component_id] = $d->component_id;
                                                    $names[$d->component_id] = $info->name;
                                                    $types[$d->component_id] = $info->type;
                                                    $amount_types[$d->component_id] = $info->amount_type;
                                                    $amounts[$d->component_id] = $d->amount;
                                                    $percents[$d->component_id] = $d->percent;
                                                }
                                            }
                                        $component_ids = array_diff($component_ids_, $avoidFromGross);
                                        $grossSalElement = array_diff($salary_component_id, $avoidFromGross);
                                        $countDiff = count($grossSalElement) - count($component_ids);

                                            $empTotalPercent = $empTotalPercentDeduction = $empTotAlallo = $empTotalDeduction = $empTotalAmt = $amount = $basic = $ot_amt = $pf = $lone = 0;
                                            foreach($component_ids as $data1){
                                            if(!empty($percents[$data1])) $empTotalPercent = $empTotalPercent + intval($amounts[$data1]);
                                            if(!empty($percents[$data1]) && $types[$data1] == 'D') $empTotalPercentDeduction = $empTotalPercentDeduction + intval($amounts[$data1]);
                                            if(empty($percents[$data1]) && $types[$data1] == 'A') $empTotAlallo = $empTotAlallo + intval($amounts[$data1]);
                                            if(empty($percents[$data1]) && $types[$data1] == 'D') $empTotalDeduction = $empTotalDeduction + intval($amounts[$data1]);
                                        }
                                        @endphp
                                        @foreach($salary_component_id as $data_sal_comp)
                                            @php
                                                $amount = 0;
                                                if(in_array($data_sal_comp, $component_ids)){
                                                    if($salary_type == 'B'){
                                                        if(!empty($percents[$data_sal_comp])) $amt = $salary * $amounts[$data_sal_comp] * .01;
                                                        else  $amt = $amounts[$data_sal_comp];

                                                        if($types[$data_sal_comp] == "D") $amount = $amt * (-1);
                                                        else $amount = $amt;
                                                    }elseif($salary_type == 'G'){
                                                        if(!empty($percents[$data_sal_comp])) $amt = (($salary + $empTotalDeduction - $empTotAlallo) / ($empTotalPercent))  * $amounts[$data_sal_comp];
                                                        else  $amt = $amounts[$data_sal_comp];

                                                        if($types[$data_sal_comp] == "D"){$amount = $amt * (-1);}
                                                        else {$amount = $amt;}
                                                    }
                                                    $empTotalAmt = $empTotalAmt +  $amount;
                                                    $component_totoal[$empid][$data_sal_comp] = $amount;
                                                }
                                            if(!empty($amount_types[$data_sal_comp]) && $amount_types[$data_sal_comp] == 'B'){
                                                $basic = $amount;
                                            }
                                            @endphp
                                            <td>{{money_format('%i', $amount)}}
                                                @if(!empty($amount))
                                                    <input type="hidden" name="empSalComAmount[{{$empid}}][{{$data_sal_comp}}]" value="{{$amount}}">
                                                @endif
                                            </td>
                                        @endforeach
                                        {{--//////////////////////////////////--}}
                                        <td><strong>{{money_format('%i', $empTotalAmt)}}</strong></td>
                                        @php
                                            $empGross[$empid] = $gross = $empTotalAmt;
                                        @endphp
                                        <td>
                                            @php
                                                $empLone[$empid] = $lone = \Modules\Payroll\Entities\SalaryComponent::loneCalcMonth($empid,$date);
                                                $loneId = \Modules\Payroll\Entities\SalaryComponent::loneMonthId($empid,$date);
                                                $lone = -1*$lone;
                                                echo !empty($lone) ? money_format('%i', $lone) : '0.00';
                                                $empTotalAmt = $empTotalAmt + $lone;
                                            @endphp
                                            <input type="hidden" name="empSalComAmount[{{$empid}}][{{$loneId}}]" value="{{$lone}}">
                                        </td>
                                        <td>
                                            @php //dd('azad');
                                                foreach($salary_pf as $data_salary_pf){
                                                    $pf_id = $data_salary_pf->id;
                                                    $empPf[$empid] = $pf = $data_salary_pf->pfCalcMonth($pf_id,$empid,$date,$basic,$gross);
                                                    }
                                                    $pf = -1*$pf;
                                                    echo !empty($pf) ? money_format('%i', $pf):'0.00';
                                                $empTotalAmt = $empTotalAmt + $pf;
                                            @endphp
                                            <input type="hidden" name="empSalComAmount[{{$empid}}][{{$pf_id}}]" value="{{$pf}}">
                                        </td>
                                        <td>@php
                                                foreach($salary_ot as $data_salary_ot){
                                                    $id = $data_salary_ot->id;
                                                    if(in_array($id, $component_ids_)){
                                                    $empOt[$empid] = $ot_amt = $data_salary_ot->otCalcMonth($id,$empid,$date,$toDate);
                                                    }
                                                   echo $ot = $data_salary_ot->otCalcMonth($data_salary_ot->id,$empid,$date,$toDate);
                                               }
                                                $empTotalAmt = $empTotalAmt + $ot_amt;
                                            @endphp
                                            <input type="hidden" name="empSalComAmount[{{$empid}}][{{$id}}]" value="{{$ot_amt}}">
                                        </td>
                                        {{--//////////////////////////////////--}}
                                        <td>@php echo money_format('%i', $empTotalAmt); $empGT[$empid] = $empTotalAmt;@endphp</td>
                                        <td></td>
                                        @endforeach
                                    </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Grand Total:</th>
                                    @foreach($salary_component as $data_sal_comp)
                                        <th>
                                            @php
                                                $id = $data_sal_comp->id;
                                                if(in_array($id, $salary_component_id)){
                                                    foreach($empIdList as $emp_id){
                                                        $amt = !empty($component_totoal[$emp_id][$id]) ? $component_totoal[$emp_id][$id] : 0;
                                                        $ttl += floatval($amt);
                                                    }
                                                    echo money_format('%i',$ttl);
                                                }else{echo '0.00';}
                                            @endphp
                                        </th>
                                    @endforeach
                                    <th style="text-align: right" > @if(!empty($empGross)) {{money_format('%i',array_sum($empGross))}} @else {{0}} @endif</th>
                                    <th style="text-align: right" > @if(!empty($empLone)) {{money_format('%i',array_sum($empLone))}} @else {{0}} @endif <input type="hidden" name="totalLone" value="@if(!empty($empLone)) {{array_sum($empLone)}} @else {{0}} @endif"></th>
                                    <th style="text-align: right" > @if(!empty($empPf)) {{money_format('%i',array_sum($empPf))}} @else {{0}} @endif <input type="hidden" name="totalPf" value="@if(!empty($empPf)) {{array_sum($empPf)}} @else {{0}} @endif"> </th>
                                    <th style="text-align: right" > @if(!empty($empOt)) {{money_format('%i',array_sum($empOt))}} @else {{0}} @endif </th>
                                    <th style="text-align: right" > @if(!empty($empGT)) {{money_format('%i',array_sum($empGT))}} @else {{0}} @endif <input type="hidden" name="totalEmpSal" value="@if(!empty($empGT)) {{array_sum($empGT)}} @else {{0}} @endif"> </th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </form>
            </div>
        </section>

        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" id="modal-content">
                    <div class="modal-body" id="modal-body">
                        <div class="loader">
                            <div class="es-spinner">
                                <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
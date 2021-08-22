<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 11/19/17
 * Time: 11:40 AM
 */
use Modules\Payroll\Entities\EmpSalaryAssign;
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
                <i class="fa fa-th-list"></i> Monthly |<small>Employee Salary</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Human Resource</a></li>
                <li><a href="#">Employee Management</a></li>
                <li class="active">Monthly Employee Salary</li>
            </ul>
        </section>

        <section class="content">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> View Monthly Employee Salary</h3>
                </div>
                <div class="box-body table-responsive">
                    <div class="box-header"></div>
                    <div class="box-body">
                        <?php $i=1;?>
                        <table id="example2" class="table table-striped" >
                            <thead>
                            <tr>
                                <th style="text-align:center">#</th>
                                <th style="text-align:center">Name</th>
                                <th style="text-align:center">Pay Scale</th>
                                <th style="text-align:center" colspan="{{count($salary_component) + 4 }}">Salary</th>
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
                            </tr>
                            </thead>
                            <tbody>
                            @php($emp_ids = $emp_amount = $empPf = array())
                            @foreach($emp_salary_assign as $data_sal_ass)
                                @php
                                    $gross = $amt = $lone = $ott = 0;
                                    $empLone = $empOt = array();
                                    $empid = $data_sal_ass->employee_id;
                                    array_push($emp_ids,$empid);
                                    $empMonthlySalary = \Modules\Payroll\Entities\SalaryEmployeeMonthly::empMonthlySalary($empid,date('m'),date('Y'));
                                    $emp_component_id = array();
                                    foreach ($empMonthlySalary as $data){
                                        array_push($emp_component_id,$data->component_id);
                                        $emp_amount[$empid][$data->component_id] = $data->amount;
                                    }
                                @endphp
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{EmpSalaryAssign::empName($empid)}}</td>
                                    <td>{{ EmpSalaryAssign::salaryStructureNameStt($empid)}}</td>
                                    @foreach($salary_component_id as $component_id)
                                        @if(in_array($component_id,$emp_component_id))
                                            @php($amt = $emp_amount[$empid][$component_id])
                                            @php($empGross[] = $gross += $amt)
                                            <td>{{number_format($amt,2, ".", ",")}}</td>
                                        @else <td>0.00</td>
                                        @endif
                                    @endforeach
                                    <td>{{number_format($gross,2, ".", ",")}}</td>
                                    <td>@php
                                            foreach ($salary_ln as $lon){
                                            if(in_array($lon->id, $emp_component_id))
                                            $empLone[] = $lone = $emp_amount[$empid][$lon->id];
                                            }
                                            //echo money_format('%i',$lone);
                                            echo number_format($lone,2, ".", ",");
                                        $gross += $lone;
                                        @endphp
                                    </td>
                                    <td>@php
                                            foreach ($salary_pf as $pf){
                                            if(in_array($pf->id, $emp_component_id))
                                            $empPf[] = $pf = $emp_amount[$empid][$pf->id];
                                            else $pf = 0;
                                            }
                                            //echo money_format('%i',$pf);
                                            echo number_format($pf,2, ".", ",");
                                        $gross += $pf;
                                        @endphp
                                    </td>
                                    <td>@php
                                            foreach ($salary_ot as $ot){
                                            if(in_array($ot->id, $emp_component_id))
                                            $empOt[] = $ott = $emp_amount[$empid][$ot->id];
                                            }
                                            //echo money_format('%i',$ott);
                                            echo number_format($ott,2, ".", ",");
                                        $gross += $ott;
                                        @endphp
                                    </td>
                                    <td>{{number_format($gross,2, ".", ",")}}@php($empGrossTotal[] = $gross)</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2"></th>
                                <th>Grand Total:</th>
                                @foreach($salary_component as $data_sal_comp)
                                    <th>
                                        @php
                                            $ttl=0;
                                            $id = $data_sal_comp->id;
                                            if(in_array($id, $salary_component_id)){
                                                foreach($emp_ids as $emp_id){
                                                    $amt = !empty($emp_amount[$emp_id][$id]) ? $emp_amount[$emp_id][$id] : 0;
                                                    $ttl += floatval($amt);
                                                }
                                                echo number_format($ttl,2, ".", ",");
                                            }else{echo '0.00';}
                                        @endphp
                                    </th>
                                @endforeach
                                    <th>{{number_format(array_sum($empGross),2, ".", ",")}}</th>
                                    <th>{{number_format(array_sum($empLone),2, ".", ",")}}</th>
                                    <th>{{number_format(array_sum($empPf),2, ".", ",")}}</th>
                                    <th>{{number_format(array_sum($empOt),2, ".", ",")}}</th>
                                    <th>{{number_format(array_sum($empGrossTotal),2, ".", ",")}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
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

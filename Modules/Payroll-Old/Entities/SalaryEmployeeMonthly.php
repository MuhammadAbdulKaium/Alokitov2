<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SalaryEmployeeMonthly extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'pay_emp_monthly_salary';

    // The attribute that should be used for softDelete.
    protected $dates = ['deleted_at'];

    public static function empMonthlySalary($empId,$month,$year){
        $from = $year.'-'.$month.'-1';
        $to = $year.'-'.$month.'-31';
        return SalaryEmployeeMonthly::where('employee_id',$empId)
            ->where('effective_date','>=',$from)
            ->where('effective_date','<=',$to)
            ->get(['employee_id', 'component_id', 'amount']);
    }
    public static function allEmpSalSummery($month,$year){
        $from = $year.'-'.$month.'-1';
        $to = $year.'-'.$month.'-31';
        /*$data = SalaryEmployeeMonthly::selectraw('employee_id,sum(amount) as amount')
            ->where('effective_date_','>=',"'$from'")
            ->where('effective_date','<=',"'$to'")
            ->groupBy('employee_id')->get();*/
        return $data = DB::select("select employee_id, sum(amount) as amount 
                          from `pay_emp_monthly_salary` 
                          where `effective_date` >= '$from' 
                          and `effective_date` <= '$to' 
                          and `pay_emp_monthly_salary`.`deleted_at` is null 
                          group by `employee_id`");
    }
}

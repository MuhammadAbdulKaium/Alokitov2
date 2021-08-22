<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\EmployeeOtHour;

class SalaryComponent extends Model
{
    private $companyId = 1;
    private $branchId = 1;

    use SoftDeletes;

    // Table name
    protected $table = 'pay_salary_component';

    // The attribute that should be used for softDelete.
    protected $dates = ['deleted_at'];

    /**
     * @param $empid
     * @param $date
     * @return mixed
     */
    public static function loneCalcMonth($empid,$date)
    {
        $result = new SalaryComponent();
        $result = $result->loneMonth($empid,$date);
        if(!empty($result)) return $result->installment_amount;
    }

    /**
     * @param $empid
     * @param $date
     * @return mixed
     */
    public static function loneMonthId($empid,$date)
    {
        $result = new SalaryComponent();
        $result = $result->loneMonth($empid,$date);
        if(!empty($result)) return $result->loan_type_id;
    }

    /**
     * @param $empid
     * @param $date
     * @return mixed
     */
    public function loneMonth($empid,$date)
    {
        $this->companyId=institution_id();
        $this->branchId=campus_id();
        $salEmpLone = SalaryEmpLone::where('employee_id', $empid)->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])->get();
        foreach ( $salEmpLone as $data){
            $fromDate = strtotime($data->deduction_date);
            $date = strtotime($date);
            $toDate = strtotime(date('Y-m-t',strtotime($data->deduction_date.' + '. $data->installment_no .' month')));

            $fromDate = intval( date('Ymd',$fromDate ) );
            $date  = intval( date('Ymd',$date ) );
            $toDate = intval( date('Ymd',$toDate ) );
            if($fromDate<$date && $date<$toDate){
                return $data;
            }
        }
    }

    /**
     * @param $compId
     * @return mixed
     */
    public static function salCompName($compId){
        $d = SalaryComponent::where('id',$compId)->first();
        return $d->name;
    }
    /**
     * @param $empid
     * @return all salary component of an employee
     */
    public function empSalaComp($empid){
        $this->companyId=institution_id();
        $this->branchId=campus_id();
        $date = date('Y-m-d');
        $data = DB::select("
                    SELECT 
                        component_id
                    FROM
                        pay_salary_structure_detail
                    WHERE
                        structure_id = (SELECT 
                                empsalass.salary_structure_id
                            FROM
                                pay_emp_salary_assign empsalass
                                    INNER JOIN
                                (SELECT 
                                    employee_id,
                                        salary_structure_id,
                                        MAX(effective_date) effective_date
                                FROM
                                    employee_informations empInfo
                                INNER JOIN pay_emp_salary_assign empSal ON empInfo.id = empSal.employee_id
                                WHERE
                                    effective_date <= '$date'
                                        AND employee_id = $empid
                                GROUP BY employee_id , salary_structure_id) assign_emp ON empsalass.effective_date = assign_emp.effective_date
                            WHERE
                                empsalass.employee_id = assign_emp.employee_id
                            GROUP BY salary_structure_id) 
                    UNION 
                    SELECT 
                        component_id
                    FROM
                        pay_emp_salary_assign_extra
                    WHERE
                        assign_id = (SELECT 
                                empsalass.salary_structure_id
                            FROM
                                pay_emp_salary_assign empsalass
                                    INNER JOIN
                                (SELECT 
                                    employee_id,
                                        salary_structure_id,
                                        MAX(effective_date) effective_date
                                FROM
                                    employee_informations empInfo
                                INNER JOIN pay_emp_salary_assign empSal ON empInfo.id = empSal.employee_id
                                WHERE
                                    effective_date <= '$date'
                                        AND employee_id = $empid
                                GROUP BY employee_id , salary_structure_id) assign_emp ON empsalass.effective_date = assign_emp.effective_date
                            WHERE
                                empsalass.employee_id = assign_emp.employee_id
                            GROUP BY salary_structure_id) 
        ");
        //dd($data);
        $a=array();
        foreach ($data as $d){
            array_push($a,$d->component_id);
        }
        return $a;
    }


    /**
     * @param $id
     * @param $empid
     * @param $date
     * @param $basic
     * @param $gross
     * @return int
     */
    public function pfCalcMonth($id,$empid,$date,$basic,$gross)
    {
        $this->companyId=institution_id();
        $this->branchId=campus_id();
        $empSalaryComp = $this->empSalaComp($empid);
        if(in_array($id,$empSalaryComp)){
            $pfRule = SalaryPfRule::where('pf_type_id',$id)
                ->whereraw("deduction_date <= '".$date."'")
                ->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])
                ->first(['pf_ded_rule','pf_ded_from','pf_ded_type','amt_val']);
            if($pfRule->pf_ded_type == 'P'){
                $amt = $pfRule->amt_val * 0.01;
                if($pfRule->pf_ded_from == 'B'){
                    $amount = $amt * $basic;
                }elseif ($pfRule->pf_ded_from == 'G'){
                    $amount = $amt * $gross;
                }
            }else{
                $amount = $pfRule->amt_val;
            }
            if($amount){
                return $amount;
            }
        }
    }

    public function otCalcMonth($otId,$empid,$date, $toDate)
    {
        $this->companyId=institution_id();
        $this->branchId=campus_id();
        $date_m = date('m',strtotime($date));
        $date_y = date('Y',strtotime($date));

        $toDate_m = date('m',strtotime($toDate));
        $toDate_y = date('Y',strtotime($toDate));

        $ot_rule = SalaryOtRule::where('ot_type_id',$otId)->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])->first();
        $rate = $ot_rule->ot_rate;

        $empOtInfo =  EmployeeOtHour::where('employee_id',$empid)
            ->whereraw("effective_month >= '$date_m' and effective_month <= '$toDate_m' and effective_year >= '$date_y' and effective_year <= '$toDate_y' and approve_date >= '$date' and approve_date <= '$toDate'")
            ->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])
            ->get();

        $ot_hour = 0;
        foreach($empOtInfo as $item){
            $ot_hour = $ot_hour + $item->ot_hours;
        }
        return $rate * $ot_hour;
    }
}
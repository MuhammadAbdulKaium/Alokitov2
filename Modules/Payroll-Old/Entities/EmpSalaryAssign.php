<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Payroll\Entities\SalaryStructure;
class EmpSalaryAssign extends Model
{
    private $companyId = 1;
    private $branchId = 1;

    use SoftDeletes;
    // Table name
    protected $table = 'pay_emp_salary_assign';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    public static function empName($empId){
        return  EmployeeInformation::empName($empId);
    }

    public function salaryStructureName($id){
        $data = SalaryStructure::where('id',$id)->first();
        return $data->name;
    }
    public function empSalaryStructureAll($empid){
        $this->companyId=institution_id();
        $this->branchId=campus_id();
        $date = date('Y-m-d');
        $data = DB::select("select component_id,amount, percent,name, type, amount_type  from 
                    (SELECT 
                        component_id,amount, percent
                    FROM
                        pay_salary_structure_detail salStrDet
                            INNER JOIN
                        (SELECT
                            empsalass.id,
                                empsalass.employee_id,
                                empsalass.salary_structure_id,
                                empsalass.effective_date
                        FROM
                            pay_emp_salary_assign empsalass
                        INNER JOIN (SELECT 
                            employee_id,
                                salary_structure_id,
                                MAX(effective_date) effective_date
                        FROM
                            employee_informations empInfo
                        INNER JOIN pay_emp_salary_assign empSal ON empInfo.id = empSal.employee_id
                        WHERE
                            effective_date <= '$date'
                            AND employee_id = $empid
                            AND company_id =  $this->companyId
                            AND brunch_id =  $this->branchId
                        GROUP BY employee_id , salary_structure_id) assign_emp 
                        ON empsalass.effective_date = assign_emp.effective_date)empSalStr 
                        ON salStrDet.structure_id = empSalStr.salary_structure_id ) comp_lst
                        left join pay_salary_component comp on comp.id = comp_lst.component_id
                    UNION 
                    select component_id,amount, percent,name,type,amount_type  from 
                    (SELECT 
                        component_id,amount, percent
                    FROM
                        pay_emp_salary_assign_extra a
                            INNER JOIN
                        (SELECT 
                            empsalass.id,
                                empsalass.employee_id,
                                empsalass.salary_structure_id,
                                empsalass.effective_date
                        FROM
                            pay_emp_salary_assign empsalass
                        INNER JOIN (SELECT 
                            employee_id,
                                salary_structure_id,
                                MAX(effective_date) effective_date
                        FROM
                            employee_informations empInfo
                        INNER JOIN pay_emp_salary_assign empSal ON empInfo.id = empSal.employee_id
                        WHERE
                            effective_date <= '$date'
                            AND employee_id = $empid
                            AND company_id =  $this->companyId
                            AND brunch_id =  $this->branchId
                        GROUP BY employee_id , salary_structure_id) assign_emp 
                        ON empsalass.effective_date = assign_emp.effective_date) b 
                        ON a.assign_id = b.id) comp_lst
                        left join pay_salary_component comp on comp.id = comp_lst.component_id");
        return $data;
    }

    public static function salaryStructureNameStt($empid){
        $empSalaryAssign = new EmpSalaryAssign();
        return $empSalaryAssign->empSalStrInfo($empid)->name;
    }

    public static function salaryStructureId($empid){
        $empSalaryAssign = new EmpSalaryAssign();
        return $empSalaryAssign->empSalStrInfo($empid)->salary_structure_id;
    }
    public function empSalStrInfo($empid){
        $this->companyId=institution_id();
        $this->branchId=campus_id();
        $date = date('Y-m-d');
        $data = DB::select("SELECT 
                            employee_id,
                            effective_date,
                            salary_structure_id,
                            name,
                            details
                        FROM
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
                                AND company_id =  $this->companyId
                                AND brunch_id =  $this->branchId
                            GROUP BY employee_id , salary_structure_id) a
                                LEFT JOIN
                            pay_salary_structure b ON a.salary_structure_id = b.id
                            ");
        return $data[0];
    }

    public function allSalaryStructur($id){
        $this->companyId=institution_id();
        $this->branchId=campus_id();
        return $SalaryStructureDetails =  SalaryStructureDetails::
        leftJoin('pay_salary_component', 'pay_salary_component.id', 'pay_salary_structure_detail.component_id')
            ->select('component_id', 'name', 'type', 'amount_type', 'amount', 'percent')
            ->where([['company_id', $this->companyId],['brunch_id', $this->branchId]])
            ->where([['structure_id', $id]])->get();
    }

    public function  empSalAssign(){
        $this->companyId=institution_id();
        $this->branchId=campus_id();
        $date = date('Y-m-t');
        return DB::select("SELECT
                            a.employee_id,a.salary_structure_id,a.effective_date,a.salary_type,a.salary_amount
                        FROM
                            pay_emp_salary_assign a,
                            (SELECT 
                                employee_id,
                                    salary_structure_id,
                                    MAX(effective_date) effective_date
                            FROM
                                `pay_emp_salary_assign`
                            WHERE
                                `effective_date` <= '$date'
                                 AND company_id =  $this->companyId
                                 AND brunch_id =  $this->branchId
                            GROUP BY `employee_id` , `salary_structure_id`) b
                        WHERE
                            a.employee_id = b.employee_id
                                AND a.salary_structure_id = b.salary_structure_id
                                AND a.effective_date = b.effective_date
                                AND company_id =  $this->companyId
                                AND brunch_id =  $this->branchId
                                and a.deleted_at is null");
    }
}

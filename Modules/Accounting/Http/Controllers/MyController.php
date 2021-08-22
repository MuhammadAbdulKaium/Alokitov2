<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 5/24/17
 * Time: 6:00 PM
 */
namespace Modules\Accounting\Http\Controllers;
use Illuminate\Routing\Controller;
use Modules\Accounting\Entities\AccFYear;

class MyController extends Controller
{
    private $acc_f_year_id;
    private $start_date;
    private $end_date;
    private $companyId;
    private $branchId;

    function __construct(){
        $accFYears = AccFYear::where('status', '=', 1)->where('company_id',10)->get();
        if(count($accFYears) == 0){
            return view('accounting::pages.accFYear.accFYear');
            //die('Please Open a Financial year <br><a href="'.url('accounting/accfyear/add').'" class="btn btn-info btn-sm">Click to Open</a>');
        }
        $this->acc_f_year_id = $accFYears[0]-> id;
        $this->start_date = $accFYears[0]-> start_date;
        $this->end_date = $accFYears[0]-> end_date;
    }

    public function getStartDate(){
        return $this->start_date;
    }

    public function getEndDate(){
        return $this->end_date;
    }

    public function getCompanyId(){
        return  $this->companyId;
    }

    public function getBranchId(){
        return  $this->branchId;
    }

    public function getAcc_f_year_id(){
        return  $this->acc_f_year_id;
    }
}
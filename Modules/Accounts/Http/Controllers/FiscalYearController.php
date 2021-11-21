<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\FiscalYearModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\AccountsHelper;

class FiscalYearController extends Controller
{

    use AccountsHelper;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $checkFiscalYear = FiscalYearModel::module()->first();
        if(empty($checkFiscalYear)){
            DB::beginTransaction();
            try {
                $user_id = Auth::user()->id;
                $institute_id = self::getInstituteId();
                $campus_id = self::getCampusId();
                FiscalYearModel::insert([
                    'from_month'=>'07',
                    'to_month'=>'06',
                    'from_month_name'=>'July',
                    'to_month_name'=>'Jun',
                    'institute_id'=>$institute_id,
                    'campus_id'=>$campus_id,
                    'created_by'=>$user_id,
                    'created_at'=>date('Y-m-d H:i:s')
                ]);
                DB::table('accounts_fiscal_year_history')->insert([
                    'remarks'=>"Fiscal year insert",
                    'institute_id'=>$institute_id,
                    'campus_id'=>$campus_id,
                    'created_by'=>$user_id,
                    'created_at'=>date('Y-m-d H:i:s')
                ]);
                DB::commit();
            } catch (Throwable $e) {
                DB::rollback();
                throw $e;
            }  

        } 
        $data['fiscalYear'] = FiscalYearModel::module()->get();
        return view('accounts::fiscal-year.fiscal-year', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('accounts::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('accounts::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('accounts::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}

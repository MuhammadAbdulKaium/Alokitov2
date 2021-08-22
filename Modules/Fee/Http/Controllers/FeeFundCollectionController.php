<?php

namespace Modules\Fee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\FeeFundCollection;
use Illuminate\Support\Facades\DB;
use Modules\Fee\Entities\FeeInvoice;
use Carbon\Carbon;
class FeeFundCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function testFundResult()
    {

//        return $monthlysales = array_fill_keys(range(1, 12), 0);

//        $query = DB::table('fee_invoice')
//            ->select(DB::raw('sum(paid_amount) as sums, extract(month from fee_invoice.created_at) as month'))
//            ->where('fee_invoice.head_id', 1)
//            ->groupBy('month')
//            ->pluck('sums', 'month');
////        return $query;
//
////        return  array_fill_keys(range(1, 12), 0);
//        return  array_replace(array_fill_keys(range(1, 12), 0), $query->all());


        $orders = DB::table('fee_transaction')
            ->join('fee_invoice', 'fee_transaction.invoice_id', '=', 'fee_invoice.id')
//           ->select(DB::raw('sum(fee_transaction.amount) as sums, extract(month from fee_transaction.payment_date) as month'))
           ->select(DB::raw('fee_invoice.head_id, sum(fee_transaction.amount) as sums, extract(month from fee_transaction.payment_date) as month'))
            ->whereIn('fee_invoice.head_id',[1,2,3,4])
            ->groupBy('month')
            ->groupBy('fee_invoice.head_id')
//            ->pluck('sums', 'month');
            ->get();


//        return $orders;

        $data=[];
        foreach ($orders as $order){
            $data[$order->head_id][$order->month] = $order->sums;
        }


//        return $data;
        $mainData=[];
        foreach ($data as $key=>$value){

            $mainData[$key]=array_replace(array_fill_keys(range(1, 12), 0), $data[$key]);
        }
        return $mainData;



    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('fee::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('fee::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('fee::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}

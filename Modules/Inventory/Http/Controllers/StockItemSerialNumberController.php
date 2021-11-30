<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\StockItemSerialModel;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\StockItemSerialDetailsModel;
use Modules\Setting\Entities\Campus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\InventoryHelper;
use DNS2D;

class StockItemSerialNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use InventoryHelper;
    public function index(Request $request)
    {

        $listPerPage = $request->input('listPerPage');
        $search_key = $request->input('search_key');
        $item_id = $request->item_id;
        $order = $request->input('order');
        $sort = $request->input('sort');

        $data['serial_item_list'] = CadetInventoryProduct::select('id','product_name')->module()->where('use_serial', 1)->get();
        $paginate_data_query =StockItemSerialModel::module()->valid()
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_item_serial_info.item_id')
            ->select('inventory_item_serial_info.*', 'cadet_stock_products.product_name')
            ->when($item_id, function($query, $item_id){
                $query->where('inventory_item_serial_info.item_id',$item_id);
            })
            ->where(function($query)use($search_key){
                if(!empty($search_key)){
                    $query->where('inventory_item_serial_info.serial_from','LIKE','%'.$search_key.'%')
                        ->orWhere('inventory_item_serial_info.serial_to',$search_key);
                }
            })->orderBy($sort,$order);     

        $paginate_data = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);
        $data['paginate_data'] = $paginate_data;
        return response()->json($data);
    }

    public function page(Request $request){
       return view('inventory::stockItemSerial.stock-item-serial'); 
    }


    public function stockItemSerialGenerate(Request $request){
        $item_id = $request->item_id;
        $serial_from = $request->serial_from;
        $serial_to = $request->serial_to;
        $itemInfo = CadetInventoryProduct::module()->where('id', $item_id)->first(); 
        if(!empty($itemInfo)){
            if($itemInfo->use_serial==1){
                if($serial_from<=$serial_to){
                    if(self::isFloat($serial_from) || self::isFloat($serial_to)){
                        $data=[
                            'status'=>0,
                            'message'=>"Serial from and serial to must be integer value"
                        ];
                    }else{
                        if(is_numeric($serial_from) && is_numeric($serial_to)){
                            $checkSerial = StockItemSerialDetailsModel::module()->valid()->where('item_id', $item_id)->whereBetween('serial_int_no', [$serial_from, $serial_to])->first(); 
                            if(empty($checkSerial)){
                                $numeric_part = $itemInfo->numeric_part; 
                                $prefix = $itemInfo->prefix; 
                                $suffix = $itemInfo->suffix; 
                                $separator_symbol = $itemInfo->separator_symbol;
                                $serial_code_list = [];
                                for ($i=$serial_from; $i <= $serial_to; $i++) { 
                                    $serial = str_pad($i,$numeric_part,"0", STR_PAD_LEFT);
                                    if(!empty($suffix)){
                                        $serial_code = $prefix.$separator_symbol.$serial.$separator_symbol.$suffix;
                                    }else{
                                        $serial_code = $prefix.$separator_symbol.$serial;
                                    }
                                    
                                    $serial_code_list[] = [
                                        'serial_code'=>$serial_code,
                                        'serial_int_no'=>$i,
                                        'barcode'=>"",
                                        'qrcode'=>"",
                                    ];
                                }
                                $data['status'] = 1;
                                $data['serial_code_list'] = $serial_code_list;

                            }else{
                                $data=[
                                    'status'=>0,
                                    'message'=>"Sorry serial alredy generated in this range.Check and try again"
                                ];
                            }

                        }else{
                            $data=[
                                'status'=>0,
                                'message'=>"Serial from and serial to must be integer value"
                            ];
                        }
                    }
                }else{
                    $data=[
                        'status'=>0,
                        'message'=>"Serial to must be greater then serial from"
                    ];
                }
            }else{
                $data=[
                    'status'=>0,
                    'message'=>"Item product has no serial setup"
                ];
            }
        }else{
            $data=[
                'status'=>0,
                'message'=>"Item product is not found"
            ];
        }
        return response()->json($data);

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('inventory::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required',
            'serial_from' => 'required',
            'serial_to' => 'required',
        ]);
        $serial_code_list = $request->serial_code_list;
        if(count($serial_code_list)>0){
            DB::beginTransaction();
            try {
                $item_id = $request->item_id;
                $serial_from = $request->serial_from;
                $serial_to = $request->serial_to;
                $itemInfo = CadetInventoryProduct::module()->where('id', $item_id)->first();
                if($itemInfo->use_serial==1){
                    if($serial_from<=$serial_to){
                        $checkSerial = StockItemSerialDetailsModel::module()->valid()->where('item_id', $item_id)->whereBetween('serial_int_no', [$request->serial_from, $request->serial_to])->first(); 
                        if(empty($checkSerial)){
                            $data = $request->only('item_id','serial_from', 'serial_to');
                            $data['created_by'] = Auth::user()->id;
                            $data['created_at'] = date('Y-m-d H:i:s');
                            $stockSerial  = StockItemSerialModel::create($data);
                            $serialData = [];

                            foreach ($serial_code_list as $v) {
                                $serialData[] = [
                                    'item_id'=>$request->item_id,
                                    'serial_id'=>$stockSerial->id,
                                    'serial_code'=>$v['serial_code'],
                                    'serial_int_no'=>$v['serial_int_no'],
                                    'barcode'=>$v['serial_code'],
                                    'qrcode'=>$v['serial_code'],
                                    'institute_id'=>self::getInstituteId(),
                                    'campus_id'=>self::getCampusId(),
                                    'created_by'=>Auth::user()->id,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                ];
                            }
                            StockItemSerialDetailsModel::insert($serialData);
                            DB::commit();
                            $output = ['status' => 1, 'message' => "Serial generate success"];
                        }else{
                            $output = ['status' => 0, 'message' => "Sorry serial alredy generated in this range.Check and try again"];
                        }
                    }else{
                        $output = ['status' => 0, 'message' => "Serial to must be greater then serial from"];
                    }
                }else{
                    $output = ['status' => 0, 'message' => "Item product has no serial setup"];
                }
            } catch (\Exception $e) {
                DB::rollback();
            }
        }else{
            $output = ['status' => 0, 'message' => "Empty Serial list"];
        }
        return response()->json($output);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['stockItemSerial'] = StockItemSerialModel::module()->valid()
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_item_serial_info.item_id')
            ->select('inventory_item_serial_info.*', 'cadet_stock_products.product_name', 'cadet_stock_products.prefix', 'cadet_stock_products.suffix', 'cadet_stock_products.separator_symbol')
            ->find($id);
        $serial_code_list = StockItemSerialDetailsModel::module()->select('serial_int_no','serial_code','barcode','qrcode')
            ->valid()->where('serial_id', $id)
            ->groupBy('serial_int_no','serial_code','barcode','qrcode')
            ->orderBy('serial_int_no','asc')
            ->get();
        $data['serial_code_list'] = $serial_code_list; 
        $data['charAr'] = ['#','&','@','?','(',')',':',';','<','>','[',']'];
        return view('inventory::stockItemSerial.stock-item-serial-details', $data);
        //return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('inventory::edit');
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

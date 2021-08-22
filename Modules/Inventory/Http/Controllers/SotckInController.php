<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\StockInModel;
use Modules\Inventory\Entities\StockInDetailsModel;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\StoreWiseItemModel;
use Modules\Inventory\Entities\InventoryStore;
use Modules\Setting\Entities\Campus;
use Modules\Inventory\Entities\UserVoucherApprovalLayerModel;
use Modules\Inventory\Entities\VoucherApprovalLogModel;
use Modules\Inventory\Entities\AllStockInModel;
use Modules\Inventory\Entities\StockInSerialDetailsModel;
use Modules\Inventory\Entities\StockItemSerialDetailsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\InventoryHelper;
use App\User;
use Illuminate\Validation\Rule;
use DateTime;

class SotckInController extends Controller
{
    use InventoryHelper;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            $user_id = Auth::user()->id;
            $this->AccessStore = self::UserAccessStore($user_id);
            $this->campus_id = self::getCampusId();
            $this->institute_id = self::getInstituteId();
            return $next($request);
        });

        date_default_timezone_set('Asia/Dhaka');
        
    }

    public function index(Request $request)
    {
        $listPerPage = $request->input('listPerPage');
        $search_key = $request->input('search_key');
        $item_id = $request->item_id;
        $voucher_id = $request->voucher_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $status = $request->status;
        if(!empty($from_date)){
            $from_date = DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
        }
        if(!empty($to_date)){
            $to_date = DateTime::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');
        }  
        $order = $request->input('order');
        $sort = $request->input('sort');
        if($sort=='id') $sort='voucher_int';

        $item_list = self::itemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $data['item_list'] = self::mergeEmtyArryObj($item_list, ['item_id'=>0, 'product_name'=>'Select item']);
        $voucher_list = StockInDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_direct_stock_in', 'inventory_direct_stock_in.id','=', 'inventory_direct_stock_in_details.stock_in_id')
            ->select('inventory_direct_stock_in_details.stock_in_id as id', 'inventory_direct_stock_in.voucher_no')
            ->orderBy('voucher_int', 'desc')
            ->groupBy(['stock_in_id','voucher_no'])
            ->get();

        $data['voucher_list'] =  self::mergeEmtyArryObj($voucher_list, ['id'=>0, 'voucher_no'=>'Select voucher']);
        
        $paginate_data_query = StockInDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_direct_stock_in', 'inventory_direct_stock_in.id','=', 'inventory_direct_stock_in_details.stock_in_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_direct_stock_in_details.item_id')
            ->join('cadet_inventory_store', 'cadet_inventory_store.id','=', 'inventory_direct_stock_in.store_id')
            ->select('inventory_direct_stock_in_details.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS stock_in_date"), 'inventory_direct_stock_in.voucher_no', 'cadet_stock_products.product_name', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_store.store_name')
            ->when($item_id, function($query, $item_id){
                $query->where('inventory_direct_stock_in_details.item_id',$item_id);
            })
            ->when($voucher_id, function($query, $voucher_id){
                $query->where('inventory_direct_stock_in_details.stock_in_id',$voucher_id);
            })
            ->when($from_date, function($query, $from_date){
                $query->where('inventory_direct_stock_in.date','>=',$from_date);
            })
            ->when($to_date, function($query, $to_date){
                $query->where('inventory_direct_stock_in.date','<=',$to_date);
            })
            ->when($status, function($query, $status){
                if($status=='p') $status=0;
                $query->where('inventory_direct_stock_in_details.status',$status);
            })
            ->where(function($query)use($search_key){
                if(!empty($search_key)){
                    $query->where('inventory_direct_stock_in.voucher_no','LIKE','%'.$search_key.'%')
                        ->orWhere('cadet_stock_products.product_name','LIKE','%'.$search_key.'%');
                }
            })           
            ->orderBy($sort,$order);     

        $paginate_data = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);
        if(count($paginate_data)>0){
            $auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', 'stock_in')->where('is_role', 0)->get();
            $step=1; $approval_access=true; $approval_log_group = []; $approval_step_log=[];
            if(count($UserVoucherApprovalLayer)>0){
                $UserVoucherApprovalKeyBy = $UserVoucherApprovalLayer->keyBy('approval_id')->all();
                if(array_key_exists($auth_user_id, $UserVoucherApprovalKeyBy)){
                    $step = $UserVoucherApprovalKeyBy[$auth_user_id]->step;
                }else{
                   $approval_access=false; 
                }
            }
            //dd($approval_access);
            $voucher_details_ids = $paginate_data->pluck('id')->all(); 
            // for approval text
            $approval_log_group = VoucherApprovalLogModel::module()->valid()
                ->join('users', 'users.id', '=', 'inventory_voucher_approval_log.approval_id')
                ->select('inventory_voucher_approval_log.*', 'users.name')
                ->where('voucher_type', 'stock_in')
                ->where('is_role', 0)
                ->whereIn('voucher_details_id', $voucher_details_ids)
                ->orderBy('inventory_voucher_approval_log.approval_layer', 'asc')
                ->get()->groupBy('voucher_details_id')->all();
            // check if his step is approval or not
            $approval_step_log = VoucherApprovalLogModel::module()->valid()
                ->where('voucher_type', 'stock_in')
                ->whereIn('voucher_details_id', $voucher_details_ids)
                ->where('approval_layer', $step)
                ->where('is_role', 0)
                ->where('approval_id', $auth_user_id)
                ->get()->keyBy('voucher_details_id')->all();

            foreach ($paginate_data as $v){
                if($approval_access && $v->approval_level==$step && !array_key_exists($v->id, $approval_step_log)){
                    $v->has_approval = 'yes';
                }else{
                    $v->has_approval = 'no';
                }
                if(array_key_exists($v->id, $approval_log_group)){
                    $approved_by = [];
                    foreach ($approval_log_group[$v->id] as $log) {
                        $status = '';
                        if($log->action_status==1){
                            $status = 'approved';
                        }else if($log->action_status==2){
                            $status = 'partial approved';
                        }else if($log->action_status==3){
                            $status = 'reject';
                        }
                        $approved_by[] = "Step ".$log->approval_layer.' '.$status.' By '.$log->name.' on '.$log->date;
                    }
                    $v->approved_text = implode(" , ",$approved_by);
                }else{
                    $v->approved_text = '';
                }
            }
        }
       
        $data['paginate_data'] = $paginate_data;
        return response()->json($data);
    }

    public function page(Request $request){
        return view('inventory::stockIn.stock-in');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $voucherInfo = self::getVoucherNo('stockIn');
        if($voucherInfo['voucher_no']){
            $data['representative_user_list'] = User::select('id', 'name')->module()->get();
            $representative_id_model=User::select('id', 'name')->where('id', Auth::user()->id)->first();
            $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
            $campus_id_model=Campus::select('id', 'name')->where('id', self::getCampusId())->first();
            $data['store_list'] = InventoryStore::select('id','store_name')->access($this)->get();
            $data['formData'] = ['category'=>'opening','voucher_no'=>$voucherInfo['voucher_no'],'voucher_int'=>$voucherInfo['voucher_int'],'numbering'=>$voucherInfo['numbering'], 'date'=>date('Y-m-d'),'date_show'=>date('Y-m-d'),'campus_id_model'=>$campus_id_model,'campus_id'=>self::getCampusId(),'store_id'=>0,'representative_id_model'=>$representative_id_model,'representative_id'=>Auth::user()->id, 'voucherDetailsData'=>[], 'itemAdded'=>'no'];
            $data['store_item_list'] = [];
        }else{
            $data = ['status'=>0, 'message'=>"Setup voucher configuration first"];
        }
        return response()->json($data);

        //return view('inventory::stockIn.modal.add-new-stock-in');
    }

    public function storeWiseItem($store_id){
        $itemList = self::storeWiseItemList($store_id);
        $itemList =  self::mergeEmtyArryObj($itemList, ['item_id'=>0, 'product_name'=>'Select item']);
        return response()->json($itemList);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $campus_id = self::getCampusId();
        $institute_id = self::getInstituteId();
        $validated = $request->validate([
            'category' => 'required',
            'voucher_no' => [
                'required',
                'max:100',
                Rule::unique('inventory_direct_stock_in')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id)->where('valid',1);
                })->ignore($id, 'id')
            ],
            'representative_id' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'store_id' => 'required',
            'campus_id' => 'required',
            'comments' => 'required|max:255',
        ]);

        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');           

        $voucherDetailsData = $request->voucherDetailsData;
        if(count($voucherDetailsData)>0){
            if(!empty($id)){
                $voucherDetailsData_db = StockInDetailsModel::module()->valid()->where('stock_in_id', $id)->get();
                $voucherDetailsData_db_keyBy = $voucherDetailsData_db->keyBy('id')->all(); 
                $db_item_ids = $voucherDetailsData_db->pluck('item_id')->all(); 
                $req_item_ids = collect($voucherDetailsData)->pluck('item_id')->all(); 
                $merge_item_ids = collect($db_item_ids)->merge($req_item_ids)->all();
                $item_ids = collect($merge_item_ids)->unique()->values()->all();
            }else{
                $item_ids = collect($voucherDetailsData)->pluck('item_id')->all();
            }
            
            $itemList = CadetInventoryProduct::module()->whereIn('id', $item_ids)->get()->keyBy('id')->all();
            $flag = true; $msg = []; $item_approval = false; $has_qty=false;
            // checking fraction, fraction length and if approved item change
            foreach ($voucherDetailsData as $v):
                if(array_key_exists($v['item_id'], $itemList)){
                    $itemInfo = $itemList[$v['item_id']];
                    // franction qty check
                    if($itemInfo->has_fraction==1){
                        if(self::isFloat($v['qty'])){
                            $explodeQty = explode('.', $v['qty']);
                            if(strlen($explodeQty[1])>$itemInfo->decimal_point_place){
                                $flag = false;
                                $msg[] = $itemInfo->product_name.' has allow '.$itemInfo->decimal_point_place.' decimal places'; 
                            }
                        }

                    }else{
                        if(self::isFloat($v['qty'])){
                            $flag = false;
                            $msg[] = $itemInfo->product_name.' has no decimal places'; 
                        }
                    }
                    // item approval check
                    $details_id = @$v['id'];
                    if($details_id>0){
                        $db_data = $voucherDetailsData_db_keyBy[$details_id];
                        if(($db_data->status==1||$db_data->status==2) && $db_data->qty!=$v['qty']){
                            $flag = false;
                            $msg[] = $itemInfo->product_name.' has already approved and can not change qty'; 
                        }
                        // check any of item has approval 
                        if($db_data->status==1||$db_data->status==2){
                            $item_approval=true;
                        }
                    }

                    if($v['qty']>0 && $v['row_style']=='valid'){
                        $has_qty = true;
                    }
                }else{
                   $flag = false;
                   $msg[] = 'Item not found'; 
                }
            endforeach;
            if($flag && $has_qty){
                DB::beginTransaction();
                try {
                    $data = [
                        "category"=>$request->category,
                        "voucher_no" => $request->voucher_no,
                        "voucher_int" => $request->voucher_int,
                        "representative_id" => $request->representative_id,
                        "date" => $date,
                        "store_id" => $request->store_id,
                        "comments" => $request->comments
                    ];
                    // note campus id with auto insert 

                    if(!empty($id)){
                        $stock_in_id = $id;
                        $stockInInfo = StockInModel::module()->valid()->findOrFail($id);
                        if($stockInInfo->status==0){ // check info status
                            // date change check 
                            if($item_approval && $stockInInfo->date!=$date){
                                $flag = false; 
                                $msg[] = 'Sorry Stock in details item already approved you can not change date';
                            }else{
                                // delete check 
                                $voucherDtlDbIds = $voucherDetailsData_db->pluck('id')->all();
                                $vouDtlIds  = collect($voucherDetailsData)->pluck('id')->all();
                                $vouDtlIds_diff = collect($voucherDtlDbIds)->diff($vouDtlIds)->all();
                                foreach($vouDtlIds_diff as $diffId) {
                                    $db_data = $voucherDetailsData_db_keyBy[$diffId];
                                    if($db_data->status==1||$db_data->status==2){
                                        $itemInfo = $itemList[$db_data->item_id];
                                        $flag = false;
                                        $msg[] = $itemInfo->product_name.' has already approved and can not delete it'; 
                                    }
                                }

                                if($flag){
                                   $stockInInfo->update([
                                        "category"=>$request->category,
                                        "representative_id" => $request->representative_id,
                                        "date" => $date,
                                        "store_id" => $request->store_id,
                                        "comments" => $request->comments
                                   ]); 
                                   // delete details data
                                   foreach($vouDtlIds_diff as $diffId) {
                                        StockInDetailsModel::find($diffId)->delete();
                                   }
                                   // delete stock in serial data
                                    $stockInSerialDetials  =  StockInSerialDetailsModel::module()->valid()->where('stock_in_id', $stock_in_id)->get();
                                    if(count($stockInSerialDetials)>0){
                                       // delete Stock in serial details
                                        foreach($stockInSerialDetials as $stockIn) {
                                            StockInSerialDetailsModel::find($stockIn->id)->delete();
                                        }
                                    }
                                    
                                }
                            }
                        }else{
                           $flag = false; 
                           $msg[] = 'Sorry Stock in item already approved';
                        }

                    }else{
                        $save = StockInModel::create($data);
                        $stock_in_id = $save->id; 

                    }
                    if($flag){
                        foreach ($voucherDetailsData as $v) {
                            $details_id = @$v['id'];
                            if($v['qty']>0 && $v['row_style']=='valid'){
                                $detailsData  = [
                                    'stock_in_id'=>$stock_in_id,
                                    'item_id'=>$v['item_id'],
                                    'qty'=>$v['qty'],
                                    'rate'=>$v['rate'],
                                    'amount'=>$v['amount'],
                                    'has_serial'=>$v['use_serial'],
                                ];
                                if($details_id>0){
                                    $stock_in_details_id = $details_id;
                                    StockInDetailsModel::module()->valid()->find($details_id)->update($detailsData);;
                                }else{
                                    $detailsSave = StockInDetailsModel::create($detailsData); 
                                    $stock_in_details_id = $detailsSave->id; 
                                }
                                // stock in serial details
                                foreach($v['serial_data'] as $serial){
                                    $serial_details_data = [
                                        'stock_in_id' => $stock_in_id,
                                        'stock_in_details_id' => $stock_in_details_id,
                                        'item_id' => $v['item_id'],
                                        'serial_details_id' => $serial['id'],
                                        'serial_code' => $serial['serial_code']
                                    ];
                                    StockInSerialDetailsModel::create($serial_details_data);
                                }

                            }else{
                                if($details_id>0){
                                    StockInDetailsModel::find($details_id)->delete();
                                }
                            }
                        }
                       $output = ['status'=>1,'message'=>'Stock in successfully saved'];
                       DB::commit();
                    }else{
                        $output = ['status'=>0,'message'=>$msg]; 
                    }
                } catch (Throwable $e) {
                    DB::rollback();
                    throw $e;
                }  
            }else{
                if(!$flag){
                    $output = ['status'=>0,'message'=>$msg]; 
                }else{
                    $output = ['status'=>0,'message'=>"Empty stock in qty"]; 
                }
            }
        }else{
            $output = ['status'=>0,'message'=>"Please add at least one item"];
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
        $item_list = self::getItemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $voucherInfo  = StockInModel::module()->valid()
            ->join('cadet_inventory_store', 'cadet_inventory_store.id','=', 'inventory_direct_stock_in.store_id')
            ->join('setting_campus', 'setting_campus.id','=', 'inventory_direct_stock_in.campus_id')
            ->leftJoin('users', 'inventory_direct_stock_in.representative_id','=', 'users.id')
            ->select('inventory_direct_stock_in.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS stock_in_date"), 'users.name', 'cadet_inventory_store.store_name', 'setting_campus.name as campus_name')
            ->where('inventory_direct_stock_in.id', $id)
            ->first();
        if(!empty($voucherInfo)){
            $voucherDetailsData = StockInDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_direct_stock_in_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->select('inventory_direct_stock_in_details.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
                ->where('stock_in_id', $id)->get(); 
            if(count($voucherDetailsData)>0){
                // stock in serial details data
                $stockInSerialDataGroupBy  = StockInSerialDetailsModel::module()->valid()
                    ->select('serial_details_id as id', 'serial_code','stock_in_details_id')
                    ->where('stock_in_id', $id)
                    ->get()->groupBy('stock_in_details_id')->all();

                $totalRate = 0; $totalAmount=0;
                foreach ($voucherDetailsData as $v) {
                   $totalRate+= $v->rate;
                   $totalAmount+= $v->amount;
                    if($v->has_serial==1){
                        if(array_key_exists($v->id, $stockInSerialDataGroupBy)){
                            $stockInSerialData = $stockInSerialDataGroupBy[$v->id];
                            $serial_html = '<ul style="list-style:none">';
                            foreach($stockInSerialData as $serialInfo){
                                $serial_html.='<li>'.$serialInfo->serial_code.'</li>';
                            }
                            $serial_html.= '</ul>';
                            $v->serial_html = $serial_html;
                        }else{
                            $v->serial_html = '';
                        }
                    }else{
                        $v->serial_html = '';
                    }

                }
                $voucherInfo->totalRate = $totalRate;
                $voucherInfo->totalAmount = $totalAmount;
                $voucherInfo->voucherDetailsData = $voucherDetailsData; 
            }else{
                $voucherInfo->totalRate = 0;
                $voucherInfo->totalAmount = 0;
                $voucherInfo->voucherDetailsData = []; 
            }
            
            $data['formData'] = $voucherInfo;
        }else{
            $data = ['status'=>0, 'message'=>"voucher not found"];
        }
        return response()->json($data);

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $item_list = self::getItemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $voucherInfo = StockInModel::module()->valid()->find($id);
        $voucherInfo->date_show = $voucherInfo->date;  
        if(!empty($voucherInfo)){
            $data['representative_user_list'] = User::select('id', 'name')->module()->get();
            $representative_id_model=User::select('id', 'name')->where('id', $voucherInfo->representative_id)->first();
            $voucherInfo->representative_id_model = $representative_id_model;
            $store_info = InventoryStore::select('id','store_name')->module()->find($voucherInfo->store_id);
            $voucherInfo->store_id_model = $store_info;
            $voucherInfo->store_name = $store_info->store_name;
            $voucherInfo->campus_id_model = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',$voucherInfo->campus_id)->first();
            $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
            $data['store_list'] = InventoryStore::select('id','store_name')->access($this)->get();
            $voucherInfo->itemAdded = 'yes';
            $data['store_item_list'] = self::storeWiseItemList($voucherInfo->store_id);

            $voucherDetailsData = StockInDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_direct_stock_in_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->select('inventory_direct_stock_in_details.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom','cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place, ifnull(cadet_stock_products.round_of, 0) as round_of'),'cadet_stock_products.has_fraction', 'cadet_stock_products.use_serial')
                ->where('stock_in_id', $id)->get(); 
            if(count($voucherDetailsData)>0){
                // stock in serial details data
                $stockInSerialDataGroupBy  = StockInSerialDetailsModel::module()->valid()
                    ->select('serial_details_id as id', 'serial_code','stock_in_details_id')
                    ->where('stock_in_id', $id)
                    ->get()->groupBy('stock_in_details_id')->all();

                $voucherInfo->numbering = true;
                $voucherInfo->voucherDetailsData = $voucherDetailsData;
                $totalRate = 0; $totalAmount=0;
                foreach ($voucherDetailsData as $v) {
                    $v->item_id_model = (object)['item_id'=>$v->item_id, 'product_name'=>$v->product_name];
                    $v->rate = round($v->rate, 2);
                    $v->amount = round($v->amount, 2);
                    $totalRate+= $v->rate;
                    $totalAmount+= $v->amount;
                    if($v->has_serial==1){
                        if(array_key_exists($v->id, $stockInSerialDataGroupBy)){
                            $stockInSerialData = $stockInSerialDataGroupBy[$v->id];
                            $v->serial_data = $stockInSerialData;
                            $serial_html = '<ul style="list-style:none">';
                            foreach($stockInSerialData as $serialInfo){
                                $serial_html.='<li>'.$serialInfo->serial_code.'</li>';
                            }
                            $serial_html.= '</ul>';
                            $v->serial_html = $serial_html;
                            $v->row_style = 'valid';
                        }else{
                            $v->serial_data = [];
                            $v->serial_html = '';
                            $v->row_style = 'invalid';
                        }
                    }else{
                        $v->serial_data = [];
                        $v->serial_html = '';
                        $v->row_style = 'valid';
                    }
                    $v->avail_qty = round($v->qty, $v->decimal_point_place); 
                }
                $voucherInfo->totalRate = $totalRate;
                $voucherInfo->totalAmount = $totalAmount;
            }else{
                $voucherInfo->voucherDetailsData = [];
                $voucherInfo->totalRate = 0;
                $voucherInfo->totalAmount = 0;
            }
            
            $data['formData'] = $voucherInfo; 
        }else{
            $data = ['status'=>0, 'message'=>"voucher not found"];
        }
        return response()->json($data);
    }

    public function voucherApproval(Request $request, $id=0)
    {
        DB::beginTransaction();
        try{
            $auth_user_id = Auth::user()->id;
            if($id>0){
                $approvalData = StockInDetailsModel::module()->valid()
                    ->join('inventory_direct_stock_in', 'inventory_direct_stock_in.id','=', 'inventory_direct_stock_in_details.stock_in_id')
                    ->select('inventory_direct_stock_in_details.*', 'inventory_direct_stock_in.store_id')
                    ->find($id);
                if(!empty($approvalData)){
                    if($approvalData->status==0){
                        $approval_info = self::getApprovalInfo('stock_in');
                        $step = $approval_info['step'];
                        $approval_access = $approval_info['approval_access'];
                        $last_step = $approval_info['last_step'];
                        if($approval_access && $approvalData->approval_level==$step){
                            $flag=true;
                            if($step==$last_step){
                                $itemInfo = CadetInventoryProduct::module()->find($approvalData->item_id);
                                if(!empty($itemInfo)){
                                    $stockCalInfo  = self::storeStockIncrementAndCostPrice($itemInfo,$approvalData);
                                    $flag = $stockCalInfo['flag'];
                                    if($flag){
                                        $serial_stock_check = true;
                                        if($approvalData->has_serial==1){
                                            $serial_msg = [];
                                            $stockInSerialDetialsData = StockInSerialDetailsModel::module()->valid()->where('stock_in_details_id', $approvalData->id)->get();
                                            $stockInSerialDetialsDataKeyBy = $stockInSerialDetialsData->keyBy('serial_details_id')->all();
                                            $stockInSerialDetialsIds = $stockInSerialDetialsData->pluck('serial_details_id')->all(); 
                                            foreach($stockInSerialDetialsIds as $stock_sl_dtl_id):
                                                $stock_item_serial_check = StockItemSerialDetailsModel::module()->valid()
                                                    ->where('item_id', $approvalData->item_id)
                                                    ->where('id', $stock_sl_dtl_id)
                                                    ->where(function($query){
                                                        $query->whereNull('stock_in_store_id')
                                                            ->orWhere('stock_in_store_id', 0);
                                                    })->first();
                                                if(empty($stock_item_serial_check)){
                                                    $serial_msg[] = @$stockInSerialDetialsDataKeyBy[$stock_sl_dtl_id]->serial_code.' has alredy received';
                                                    $serial_stock_check = false;
                                                }
                                            endforeach;
                                        }

                                        if($serial_stock_check){
                                            $current_stock = $stockCalInfo['current_stock'];
                                            $avg_price = $stockCalInfo['avg_price'];
                                            $approvalData->update([
                                                'status'=>1,
                                                'approval_level'=>$step+1
                                            ]);

                                            if($approvalData->has_serial==1){
                                                // update serial detials data
                                                StockItemSerialDetailsModel::module()->valid()->whereIn('id', $stockInSerialDetialsIds)->update([
                                                    'stock_in_store_id'=>$approvalData->store_id,
                                                    'stock_in_qty'=>1,
                                                    'current_stock'=>1,
                                                    'price'=>$approvalData->rate,
                                                    'stock_in_from'=>'stock_in',
                                                    'stock_in_date'=>date('Y-m-d')
                                                ]);
                                            }

                                            // update store wise stock
                                            StoreWiseItemModel::module()->where('item_id', $approvalData->item_id)->where('store_id', $approvalData->store_id)->update([
                                                'current_stock'=>$current_stock,
                                                'avg_cost_price'=>$avg_price
                                            ]);
                                            AllStockInModel::create([
                                                'date'=>date('Y-m-d'),
                                                'item_id'=>$approvalData->item_id,
                                                'stock_in_id'=>$approvalData->stock_in_id,
                                                'stock_in_details_id'=>$approvalData->id,
                                                'store_id'=>$approvalData->store_id,
                                                'qty'=>$approvalData->qty,
                                                'rate'=>$approvalData->rate,
                                                'hand_qty'=>$approvalData->qty,
                                                'stock_in_from'=>"Stock in item"
                                            ]);
                                        }else{
                                            $flag=false;
                                            $output = ['status'=>0, 'message'=>$serial_msg];
                                        }
                                    }else{
                                        $output = ['status'=>0, 'message'=>$stockCalInfo['msg']]; 
                                    }
                                }else{
                                   $flag=false;
                                   $output = ['status'=>0, 'message'=>'Stock Item not found']; 
                                }
                            }else{ // end if($step==$last_step){
                                $approvalData->update([
                                    'approval_level'=>$step+1
                                ]); 
                            }
                            if($flag){
                                VoucherApprovalLogModel::create([
                                    'date'=>date('Y-m-d H:i:s'),
                                    'voucher_id'=>$approvalData->stock_in_id,
                                    'voucher_details_id'=>$approvalData->id,
                                    'voucher_type'=>'stock_in',
                                    'approval_id'=>$auth_user_id,
                                    'approval_layer'=>$step,
                                    'action_status'=>1,
                                    'institute_id'=>self::getInstituteId(),
                                    'campus_id'=>self::getCampusId(),
                                ]);

                                // update master status base on all app
                                self::masterVoucherUpdate($approvalData->stock_in_id);
                                DB::commit();
                                $output = ['status'=>1, 'message'=>'Stock in item successfully approved'];
                            }
                        }else{ // end if($approval_access && $approvalData->approval_level==$step){
                            $output = ['status'=>0, 'message'=>'Sory you have no approval'];    
                        }
                    }else{ // end if($approvalData->status==0)
                        if($approvalData->status==3){
                            $output = ['status'=>0, 'message'=>'Stock Item reject'];
                        }else{
                            $output = ['status'=>0, 'message'=>'Stock Item already approved'];  
                        }
                    }
 
                }else{   // end if(!empty($approvalData))
                    $output = ['status'=>0, 'message'=>'Stock Item not found'];
                }
            }else{
                dd($request->appIds);
            }
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        } 

        return response()->json($output); 
    }

    public function masterVoucherUpdate($stock_in_id){
        $voucherDetailsData = StockInDetailsModel::module()->valid()->where('stock_in_id', $stock_in_id)->get();
        $all_approved = true; $status=1;
        foreach ($voucherDetailsData as $v) {
            if($all_approved){
                if($v->status==1 || $v->status==2){ // check all details row are app qty
                    if($v->status==2){
                        $status=2;
                    }
                }else{
                   $all_approved=false;  
                }
            }else{
                break;
            }
        }
        if($all_approved){
            StockInModel::module()->valid()->find($stock_in_id)->update([
                'status'=>$status
            ]);
        }

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
    public function destroy(Request $request, $id=0)
    {
        DB::beginTransaction();
        try{
            if($id>0){
                $deleteData = StockInDetailsModel::module()->valid()->find($id);
                if(!empty($deleteData)){
                    if($deleteData->status==1||$deleteData->status==2){  // check status
                        $output = ['status'=>0, 'message'=>'Sorry Stock In Item already approved'];
                    }else{
                        $stock_in_id = $deleteData->stock_in_id; 
                        $has_serial = $deleteData->has_serial;
                        StockInDetailsModel::module()->valid()->find($id)->delete();
                        // serial data delete
                        if($has_serial==1){
                            $stockInSerialDetails  = StockInSerialDetailsModel::module()->valid()->where('stock_in_details_id',$id)->get();
                            foreach($stockInSerialDetails as $stockInSerial){
                                StockInSerialDetailsModel::module()->valid()->find($stockInSerial->id)->delete();
                            }
                        }
                        $checkDetailsItem = StockInDetailsModel::module()->valid()->where('stock_in_id', $stock_in_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            StockInModel::module()->valid()->find($stock_in_id)->delete(); 
                        }
                        $output = ['status'=>1, 'message'=>'New Requisition item successfully deleted'];
                        DB::commit();
                    }
                }else{
                    $output = ['status'=>0, 'message'=>'Item not found'];
                }
            }else{
                $delIds = $request->delIds;
                // status check
                $stock_in_ids = []; $flag = true; $msg =[]; 
                foreach ($delIds as $del_id){
                    $deleteData = StockInDetailsModel::module()->valid()->find($del_id);
                    if($deleteData->status==1||$deleteData->status==2){
                        $itemInfo = CadetInventoryProduct::module()->find($deleteData->item_id);
                        $flag = false;
                        $msg[] = $itemInfo->product_name.' has stock in qty approval';
                    }
                    $stock_in_ids[] = $deleteData->stock_in_id;
                }
                if($flag){
                    foreach ($delIds as $del_id){
                        $deleteData = StockInDetailsModel::module()->valid()->find($del_id);
                        // serial data delete
                        if($deleteData->has_serial==1){
                            $stockInSerialDetails  = StockInSerialDetailsModel::module()->valid()->where('stock_in_details_id',$del_id)->get();
                            foreach($stockInSerialDetails as $stockInSerial){
                                StockInSerialDetailsModel::module()->valid()->find($stockInSerial->id)->delete();
                            }
                        }
                        $deleteData->delete();
                    }
                    $stock_in_unique_ids = collect($stock_in_ids)->unique()->values()->all();
                    foreach ($stock_in_unique_ids as $stock_in_id) {
                        $checkDetailsItem = StockInDetailsModel::module()->valid()->where('stock_in_id', $stock_in_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            StockInModel::module()->valid()->find($stock_in_id)->delete(); 
                        }
                    }

                    $output = ['status'=>1, 'message'=>'Stock In item successfully deleted'];
                    DB::commit();

                }else{
                    $output = ['status'=>0, 'message'=>$msg];
                }
            }
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }  
        return response()->json($output);
    }
}

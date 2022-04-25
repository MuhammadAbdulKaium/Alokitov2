<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\PurchaseOrderInfoModel;
use Modules\Inventory\Entities\PurchaseOrderDetailsModel;
use Modules\Inventory\Entities\PurchaseReceiveInfoModel;
use Modules\Inventory\Entities\PurchaseReceiveDetailsModel;
use Modules\Inventory\Entities\PurchaseReceiveSerialDetailsModel;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\UserVoucherApprovalLayerModel;
use Modules\Inventory\Entities\VoucherApprovalLogModel;
use Modules\Inventory\Entities\StoreWiseItemModel;
use Modules\Inventory\Entities\AllStockInModel;
use Modules\Inventory\Entities\VendorModel;
use Modules\Inventory\Entities\InventoryStore;
use Modules\Inventory\Entities\StockItemSerialDetailsModel;
use Modules\Setting\Entities\Campus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\InventoryHelper;
use App\User;
use Illuminate\Validation\Rule;
use DateTime;
use App;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Accounts\Entities\SignatoryConfig;
use Modules\Setting\Entities\Institute;

class PurchaseReceiveController extends Controller
{


    use InventoryHelper;
    private $academicHelper;
    public function __construct(Request $request ,AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
        $this->middleware(function ($request, $next) {
            $user_id = Auth::user()->id;
            $this->AccessStore = self::UserAccessStore($user_id);
            $this->campus_id = self::getCampusId();
            $this->institute_id = self::getInstituteId();
            return $next($request);
        });
        date_default_timezone_set('Asia/Dhaka');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
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
        if($sort=='id') $sort='inventory_purchase_receive_details.id';

        $item_list = self::itemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $data['item_list'] =  self::mergeEmtyArryObj($item_list, ['item_id'=>0, 'product_name'=>'Select item']);
        $voucher_list = PurchaseReceiveDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_purchase_receive_info', 'inventory_purchase_receive_info.id','=', 'inventory_purchase_receive_details.pur_receive_id')
            ->select('inventory_purchase_receive_details.pur_receive_id as id', 'inventory_purchase_receive_info.voucher_no')
            ->orderBy('voucher_int', 'desc')
            ->groupBy(['pur_receive_id','voucher_no'])
            ->get();   

        $data['voucher_list'] =  self::mergeEmtyArryObj($voucher_list, ['id'=>0, 'voucher_no'=>'Select voucher']);     

        $paginate_data_query = PurchaseReceiveDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_purchase_receive_info', 'inventory_purchase_receive_info.id','=', 'inventory_purchase_receive_details.pur_receive_id')
            ->join('inventory_vendor_info', 'inventory_vendor_info.id','=', 'inventory_purchase_receive_info.vendor_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_receive_details.item_id')
            ->select('inventory_purchase_receive_details.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS pur_rec_date"), 'inventory_purchase_receive_info.voucher_no', 'cadet_stock_products.product_name', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'inventory_vendor_info.name as vendor_name')
            ->when($item_id, function($query, $item_id){
                $query->where('inventory_purchase_receive_details.item_id',$item_id);
            })
            ->when($voucher_id, function($query, $voucher_id){
                $query->where('inventory_purchase_receive_details.pur_receive_id',$voucher_id);
            })
            ->when($from_date, function($query, $from_date){
                $query->where('inventory_purchase_receive_info.date','>=',$from_date);
            })
            ->when($to_date, function($query, $to_date){
                $query->where('inventory_purchase_receive_info.date','<=',$to_date);
            })
            ->when($status, function($query, $status){
                if($status=='p') $status=0;
                $query->where('inventory_purchase_receive_details.status',$status);
            })
            ->where(function($query)use($search_key){
                if(!empty($search_key)){
                    $query->where('inventory_purchase_receive_info.voucher_no','LIKE','%'.$search_key.'%')
                        ->orWhere('cadet_stock_products.product_name','LIKE','%'.$search_key.'%')
                        ->orWhere('inventory_vendor_info.name','LIKE','%'.$search_key.'%');
                }
            })            
            ->orderBy($sort,$order);     

        $paginate_data = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);
        if(count($paginate_data)>0){
            $auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', 'purchase_receive')->where('is_role', 0)->get();
            $step=1; $approval_access=true; $approval_log_group = []; $approval_step_log=[];
            if(count($UserVoucherApprovalLayer)>0){
                $UserVoucherApprovalKeyBy = $UserVoucherApprovalLayer->keyBy('approval_id')->all();
                if(array_key_exists($auth_user_id, $UserVoucherApprovalKeyBy)){
                    $step = $UserVoucherApprovalKeyBy[$auth_user_id]->step;
                }else{
                   $approval_access=false; 
                }
            }
            $voucher_details_ids = $paginate_data->pluck('id')->all(); 
            // for approval text
            $approval_log_group = VoucherApprovalLogModel::module()->valid()
                ->join('users', 'users.id', '=', 'inventory_voucher_approval_log.approval_id')
                ->select('inventory_voucher_approval_log.*', 'users.name')
                ->where('voucher_type', 'purchase_receive')
                ->where('is_role', 0)
                ->whereIn('voucher_details_id', $voucher_details_ids)
                ->orderBy('inventory_voucher_approval_log.approval_layer', 'asc')
                ->get()->groupBy('voucher_details_id')->all();
            // check if his step is approval or not
            $approval_step_log = VoucherApprovalLogModel::module()->valid()
                ->where('voucher_type', 'purchase_receive')
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


    public function page(){
        return view('inventory::purchase.purchaseReceive.purchase-receive');
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $voucherInfo = self::checkInvVoucher(7);
        if($voucherInfo['voucher_conf']){
            $data['representative_user_list'] = User::select('id', 'name')->module()->get();
            $representative_id_model=User::select('id', 'name')->where('id', Auth::user()->id)->first();
            $representative_name = $representative_id_model->name;
            $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
            $campus_id_model=Campus::select('id', 'name')->where('id', self::getCampusId())->first();
            $campus_name = $campus_id_model->name;
            $data['vendor_list'] = VendorModel::select('id','name')->get();
            $data['store_list'] = InventoryStore::select('id','store_name')->access($this)->get();
            $data['formData'] = ['voucher_no'=>$voucherInfo['voucher_no'],'voucher_config_id'=>$voucherInfo['voucher_config_id'],'auto_voucher'=>$voucherInfo['auto_voucher'], 'date'=>date('d/m/Y'),'due_date'=>date('d/m/Y'),'campus_id_model'=>$campus_id_model,'campus_id'=>self::getCampusId(),'campus_name'=>$campus_name,'vendor_id'=>0,'representative_id_model'=>$representative_id_model,'representative_id'=>Auth::user()->id,'representative_name'=>$representative_name,'store_id'=>0,'reference_type'=>'', 'voucherDetailsData'=>[], 'itemAdded'=>'no'];
        }else{
            $data = ['status'=>0, 'message'=>"Setup voucher configuration first"];
        }
        return response()->json($data);
    }


    public function purchaseReceiveReferenceList(Request $request){
        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $campus_id = $request->campus_id;
        $reference_type = $request->reference_type;
        $vendor_id = $request->vendor_id;
        $store_id = $request->store_id;
        $item_list = self::storeWiseItemList($store_id);
        $item_ids = $item_list->pluck('item_id')->all();
        $refItemList = PurchaseOrderDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_purchase_order_info', 'inventory_purchase_order_info.id','=', 'inventory_purchase_order_details.pur_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_order_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
            ->select('inventory_purchase_order_details.id as reference_details_id','inventory_purchase_order_details.pur_id as reference_id','inventory_purchase_order_details.item_id','inventory_purchase_order_details.pur_qty','inventory_purchase_order_details.app_qty','inventory_purchase_order_details.rate','inventory_purchase_order_details.total_amount','inventory_purchase_order_details.vat_per','inventory_purchase_order_details.vat_type','inventory_purchase_order_details.vat_amount','inventory_purchase_order_details.discount','inventory_purchase_order_details.net_total','inventory_purchase_order_details.remarks',DB::raw("DATE_FORMAT(inventory_purchase_order_info.date,'%d/%m/%Y') AS pur_date, DATE_FORMAT(inventory_purchase_order_info.due_date,'%d/%m/%Y') AS due_date"), 'inventory_purchase_order_info.voucher_no as ref_voucher_name', 'cadet_stock_products.product_name', 'cadet_stock_products.sku','cadet_stock_products.use_serial', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of')
            ->where('inventory_purchase_order_info.vendor_id',$vendor_id)
            ->where('inventory_purchase_order_info.due_date','<=',$date)
            ->whereIn('inventory_purchase_order_details.ref_use',[0,1])
            ->whereIn('inventory_purchase_order_details.status',[1,2])
            ->orderBy('inventory_purchase_order_info.due_date','asc')
            ->get(); 
            $ref_details_ids = $refItemList->pluck('reference_details_id')->all();
            $ref_item_ids =  $refItemList->pluck('item_id')->all(); 
        $purRecDataList  = PurchaseReceiveDetailsModel::module()->valid()->select('reference_details_id',DB::raw('SUM(app_rec_qty) as app_rec_qty'))->whereIn('reference_details_id', $ref_details_ids)->whereIn('status',[1,2])->where('reference_type', 'purchase-order')->groupBy('reference_details_id')->get()->keyBy('reference_details_id')->all();

        foreach ($refItemList as $v){
            if(array_key_exists($v->reference_details_id, $purRecDataList)){
                $purRecInfo = $purRecDataList[$v->reference_details_id];
                $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                $avail_qty = self::itemQtySubtraction($itemInfo, $v->app_qty,$purRecInfo->app_rec_qty);
            }else{
                $avail_qty = $v->app_qty;
            }
            $v->avail_qty = round($avail_qty, $v->decimal_point_place);
            $v->rec_qty = round($v->avail_qty, $v->decimal_point_place);
            $v->row_style = ($v->use_serial==0)? 'valid' : 'invalid';
            $v->app_qty = round($v->app_qty, $v->decimal_point_place);
            $v->ref_check=0;
            $v->serial_data = [];
            $v->serial_html = '';
        }

        return response()->json($refItemList);
    }

    public function purchaseReceiveSerialDetails(Request $request){
        $item_id = $request->item_id;
        $serial_data = $request->serial_data;
        $stock_type = $request->stock_type;
        $store_id = $request->store_id;
        $serial_ids   = collect($serial_data)->pluck('id')->all();
        if($stock_type=='stock-in'){  // stock in serial query
            $data['serial_details'] = StockItemSerialDetailsModel::module()->valid()
                ->select('id', 'serial_code', DB::raw('0 as checked_id'))
                ->where('item_id', $item_id)
                ->where(function($query)use($serial_ids){
                    if(count($serial_ids)>0){
                        $query->where(function($q)use($serial_ids){
                                $q->whereIn('id', $serial_ids);
                              })
                              ->orWhere(function($q){
                                  $q->whereNull('stock_in_store_id')
                                    ->orWhere('stock_in_store_id', 0);
                              });
                    }else{
                        $query->whereNull('stock_in_store_id')
                            ->orWhere('stock_in_store_id', 0);
                    }
                })
                ->orderBy('serial_int_no', 'asc')
                ->get();
        }else{    // stock out serial query
            $data['serial_details'] = StockItemSerialDetailsModel::module()->valid()
                ->select('id', 'serial_code', DB::raw('0 as checked_id'))
                ->where('item_id', $item_id)
                ->where('stock_in_store_id', $store_id)
                ->where(function($query)use($serial_ids){
                    if(count($serial_ids)>0){
                        $query->where(function($q)use($serial_ids){
                                $q->whereIn('id', $serial_ids);
                              })
                              ->orWhere(function($q){
                                  $q->where('current_stock','>',0);
                              });
                    }else{
                        $query->where('current_stock','>',0);
                    }
                })
                ->orderBy('serial_int_no', 'asc')
                ->get();
        }
        if(count($serial_ids)>0){  // for selecting existing data
            foreach($data['serial_details'] as $k => $v){
                if(in_array($v->id, $serial_ids)){
                    $v->checked_id = 1;
                }
            }
        }
        return response()->json($data); 
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $campus_id = $request->campus_id;
        $institute_id = self::getInstituteId();
        $validated = $request->validate([
            'voucher_no' => 'required|max:100',
            'vendor_id' => 'required',
            'representative_id' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'due_date' => 'required|date_format:d/m/Y|after_or_equal:date',
            'store_id' => 'required',
            'campus_id' => 'required',
            'reference_type' => 'required'
        ]);

        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');       
        $due_date = DateTime::createFromFormat('d/m/Y', $request->due_date)->format('Y-m-d');           
        $voucherDetailsData = $request->voucherDetailsData;
        $reference_type = $request->reference_type;
        if(count($voucherDetailsData)>0){
            if(!empty($id)){
                $voucherDetailsData_db = PurchaseReceiveDetailsModel::module()->valid()->where('pur_receive_id', $id)->get();
                $voucherDetailsData_db_keyBy = $voucherDetailsData_db->keyBy('id')->all(); 
                $db_item_ids = $voucherDetailsData_db->pluck('item_id')->all(); 
                $req_item_ids = collect($voucherDetailsData)->pluck('item_id')->all(); 
                $merge_item_ids = collect($db_item_ids)->merge($req_item_ids)->all();
                $item_ids = collect($merge_item_ids)->unique()->values()->all();
            }else{
                $item_ids = collect($voucherDetailsData)->pluck('item_id')->all();
            }
            
            $itemList = CadetInventoryProduct::whereIn('id', $item_ids)->get()->keyBy('id')->all();
            $flag = true; $msg = []; $item_approval = false; $has_qty=false;
            // checking fraction, fraction length and if approved item change
            foreach ($voucherDetailsData as $v):
                if(array_key_exists($v['item_id'], $itemList)){
                    if($v['avail_qty']>=$v['rec_qty']){           // check available qty
                        $itemInfo = $itemList[$v['item_id']];
                        // franction qty check
                        if($itemInfo->has_fraction==1){
                            if(self::isFloat($v['rec_qty'])){
                                $explodeQty = explode('.', $v['rec_qty']);
                                if(strlen($explodeQty[1])>$itemInfo->decimal_point_place){
                                    $flag = false;
                                    $msg[] = $itemInfo->product_name.' has allow '.$itemInfo->decimal_point_place.' decimal places'; 
                                }
                            }
                        }else{
                            if(self::isFloat($v['rec_qty'])){
                                $flag = false;
                                $msg[] = $itemInfo->product_name.' has no decimal places'; 
                            }
                        }
                        // item approval check
                        $details_id = @$v['id'];
                        if($details_id>0){
                            $db_data = $voucherDetailsData_db_keyBy[$details_id];
                            if(($db_data->status==1||$db_data->status==2) && $db_data->rec_qty!=$v['rec_qty']){
                                $flag = false;
                                $msg[] = $itemInfo->product_name.' has already approved and can not change qty'; 
                            }
                            // check any of item has approval 
                            if($db_data->status==1||$db_data->status==2){
                                $item_approval=true;
                            }
                        }
                        if($v['rec_qty']>0 && $v['row_style']=='valid'){
                            $has_qty = true;
                        }
                    }else{
                        $flag = false;
                        $msg[] = $itemInfo->product_name.' has receive more then available qty';  
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
                        "vendor_id" => $request->vendor_id,
                        "representative_id" => $request->representative_id,
                        "date" => $date,
                        "due_date" => $due_date,
                        "store_id" => $request->store_id,
                        "reference_type" => $request->reference_type,
                        "comments" => $request->comments
                    ];
                    $auto_voucher = $request->auto_voucher;  // voucher type 
                    if(!empty($id)){
                        $pur_receive_id = $id;
                        $purRecInfo = PurchaseReceiveInfoModel::module()->valid()->findOrFail($id);
                        if($purRecInfo->status==0){ // check info status
                            // date change check 
                            if($item_approval && ($purRecInfo->date!=$date || $purRecInfo->due_date!=$due_date || $purRecInfo->vendor_id!=$request->vendor_id || $purRecInfo->representative_id!=$request->representative_id || $purRecInfo->store_id != $request->store_id)){
                                $flag = false; 
                                if($purRecInfo->date!=$date || $purRecInfo->due_date!=$due_date){
                                    $msg[] = 'Sorry puchase receive  details item already approved you can not change date';
                                }else if($purRecInfo->store_id!=$request->store_id){
                                    $msg[] = 'Sorry puchase receive  details item already approved you can not change store';
                                }else if($purRecInfo->vendor_id!=$request->vendor_id){
                                    $msg[] = 'Sorry puchase receive  details item already approved you can not change vendor';
                                }else{
                                    $msg[] = 'Sorry puchase receive  details item already approved you can not change Representative';
                                }
                            }else{
                                // delete check 
                                $vouDtlDbIds = $voucherDetailsData_db->pluck('id')->all();
                                $vouDtlIds  = collect($voucherDetailsData)->pluck('id')->all();
                                $vouDtlIds_diff = collect($vouDtlDbIds)->diff($vouDtlIds)->all();
                                foreach($vouDtlIds_diff as $diffId) {
                                    $db_data = $voucherDetailsData_db_keyBy[$diffId];
                                    if($db_data->status==1||$db_data->status==2){
                                        $itemInfo = $itemList[$db_data->item_id];
                                        $flag = false;
                                        $msg[] = $itemInfo->product_name.' has already approved and can not delete it'; 
                                    }
                                }
                                if($flag){
                                    $purRecInfo->update($data); 
                                    // delete details data
                                    foreach($vouDtlIds_diff as $diffId) {
                                        PurchaseReceiveDetailsModel::find($diffId)->delete();
                                    }
                                    // delete purchase serial data
                                    $purchaseReciveSerialDetials  =  PurchaseReceiveSerialDetailsModel::module()->valid()->where('pur_receive_id', $pur_receive_id)->get();
                                    if(count($purchaseReciveSerialDetials)>0){
                                       // delete purchase receive serial details
                                        foreach($purchaseReciveSerialDetials as $purRec) {
                                            PurchaseReceiveSerialDetailsModel::find($purRec->id)->delete();
                                        }
                                    }
                                }
                            }
                        }else{
                           $flag = false; 
                           $msg[] = 'Sorry purchase receive already approved';
                        }

                    }else{
                        if($auto_voucher){  // auto voucher configuration
                            $voucherInfo = self::getVoucherNo('purReceive');
                            if($voucherInfo['voucher_no']){
                                $data['voucher_no'] = $voucherInfo['voucher_no'];
                                $data['voucher_int'] = $voucherInfo['voucher_int'];
                                $data['voucher_config_id'] = $voucherInfo['voucher_config_id'];
                            }else{
                                $flag=false;
                                $msg = $voucherInfo['msg'];  
                            }
                        }else{  // menual voucher 
                            $checkVoucher = PurchaseReceiveInfoModel::module()->valid()->where('voucher_no', $request->voucher_no)->first();
                            if(empty($checkVoucher)){
                                $data['voucher_no'] = $request->voucher_no;
                                $data['voucher_int'] = 0;
                                $data['voucher_config_id'] = $request->voucher_config_id;
                            }else{
                               $flag=false;
                               $msg = "Voucher no already exists";   
                            }
                        }
                        if($flag){
                            $save = PurchaseReceiveInfoModel::create($data);
                            $pur_receive_id = $save->id;
                        } 
                    }
                    if($flag){
                        foreach ($voucherDetailsData as $v) {
                            $details_id = @$v['id'];
                            if($v['rec_qty']>0 && $v['row_style']=='valid'){
                                $detailsData  = [
                                    'pur_receive_id'=>$pur_receive_id,
                                    'item_id'=>$v['item_id'],
                                    'rec_qty'=>$v['rec_qty'],
                                    'reference_type'=>$request->reference_type,
                                    'reference_id'=>$v['reference_id'],
                                    'reference_details_id'=>$v['reference_details_id'],
                                    'rate'=>(!empty($v['rate']))?$v['rate']:0,
                                    'total_amount'=>(!empty($v['total_amount']))?$v['total_amount']:0,
                                    'vat_per'=>(!empty($v['vat_per']))?$v['vat_per']:0,
                                    'vat_amount'=>(!empty($v['vat_amount']))?$v['vat_amount']:0,
                                    'vat_type'=>$v['vat_type'],
                                    'discount'=>(!empty($v['discount']))?$v['discount']:0,
                                    'net_total'=>(!empty($v['net_total']))?$v['net_total']:0,
                                    'has_serial'=>$v['use_serial'],
                                    'remarks'=>$v['remarks']
                                ];
                                if($details_id>0){
                                    $pur_receive_details_id = $details_id;
                                    PurchaseReceiveDetailsModel::module()->valid()->find($details_id)->update($detailsData);;
                                }else{
                                   $detailsSave  = PurchaseReceiveDetailsModel::create($detailsData);  
                                   $pur_receive_details_id =  $detailsSave->id;
                                }
                                // purchase receive serial details
                                foreach($v['serial_data'] as $serial){
                                    $serial_details_data = [
                                        'pur_receive_id' => $pur_receive_id,
                                        'pur_receive_details_id' => $pur_receive_details_id,
                                        'item_id' => $v['item_id'],
                                        'serial_details_id' => $serial['id'],
                                        'serial_code' => $serial['serial_code']
                                    ];
                                    PurchaseReceiveSerialDetailsModel::create($serial_details_data);
                                }
                            }else{
                                if($details_id>0){
                                    PurchaseReceiveDetailsModel::find($details_id)->delete();
                                }
                            }
                        }
                       $output = ['status'=>1,'message'=>'Purchase order successfully saved'];
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
                    $output = ['status'=>0,'message'=>"Empty pur qty"]; 
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
        $voucherInfo  = PurchaseReceiveInfoModel::module()->valid()
            ->join('setting_campus', 'setting_campus.id','=', 'inventory_purchase_receive_info.campus_id')
            ->join('inventory_vendor_info', 'inventory_vendor_info.id','=', 'inventory_purchase_receive_info.vendor_id')
            ->join('cadet_inventory_store', 'cadet_inventory_store.id','=', 'inventory_purchase_receive_info.store_id')
            ->leftJoin('users', 'inventory_purchase_receive_info.representative_id','=', 'users.id')
            ->select('inventory_purchase_receive_info.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS pur_rec_date, DATE_FORMAT(due_date,'%d/%m/%Y') AS due_date_formate"), 'users.name', 'setting_campus.name as campus_name', 'inventory_vendor_info.name as vendor_name','cadet_inventory_store.store_name')
            ->where('inventory_purchase_receive_info.id', $id)
            ->first();
        if(!empty($voucherInfo)){
            $voucherDetailsData = PurchaseReceiveDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_receive_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->join('inventory_purchase_order_info','inventory_purchase_order_info.id', '=', 'inventory_purchase_receive_details.reference_id')
                ->select('inventory_purchase_receive_details.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'),'cadet_stock_products.sku', 'inventory_purchase_order_info.voucher_no as ref_voucher_name')
                ->where('inventory_purchase_receive_details.pur_receive_id', $id)
                ->get();
            $purchaseRecieveSerialDataGroupBy  = PurchaseReceiveSerialDetailsModel::module()->valid()
                ->select('serial_details_id as id', 'serial_code','pur_receive_details_id')
                ->where('pur_receive_id', $id)
                ->get()->groupBy('pur_receive_details_id')->all();
            foreach($voucherDetailsData as $v){
                $v->rec_qty = round($v->rec_qty, $v->decimal_point_place);
                $v->rate = round($v->rate, 2);
                $v->total_amount = round($v->total_amount, 2);
                $v->vat_per = round($v->vat_per, 2);
                $v->vat_amount = round($v->vat_amount, 2);
                $v->discount = round($v->discount, 2);
                $v->net_total = round($v->net_total, 2);
                if($v->has_serial==1){
                    if(array_key_exists($v->id, $purchaseRecieveSerialDataGroupBy)){
                        $purchaseReceiveSerialData = $purchaseRecieveSerialDataGroupBy[$v->id];
                        $serial_html = '<ul style="list-style:none">';
                        foreach($purchaseReceiveSerialData as $serialInfo){
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
            $voucherInfo->voucherDetailsData = $voucherDetailsData;
            $data['formData'] = $voucherInfo;
            $data['refItemList'] = [];
        }else{
            $data = ['status'=>0, 'message'=>"Purchase Receive Voucher not found"];
        }
        return response()->json($data);

    }
    public function print($id){
        $item_list = self::getItemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $voucherInfo  = PurchaseReceiveInfoModel::module()->valid()
            ->join('setting_campus', 'setting_campus.id','=', 'inventory_purchase_receive_info.campus_id')
            ->join('inventory_vendor_info', 'inventory_vendor_info.id','=', 'inventory_purchase_receive_info.vendor_id')
            ->join('cadet_inventory_store', 'cadet_inventory_store.id','=', 'inventory_purchase_receive_info.store_id')
            ->leftJoin('users', 'inventory_purchase_receive_info.representative_id','=', 'users.id')
            ->select('inventory_purchase_receive_info.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS pur_rec_date, DATE_FORMAT(due_date,'%d/%m/%Y') AS due_date_formate"), 'users.name', 'setting_campus.name as campus_name', 'inventory_vendor_info.name as vendor_name','cadet_inventory_store.store_name')
            ->where('inventory_purchase_receive_info.id', $id)
            ->first();
        if(!empty($voucherInfo)){
            $voucherDetailsData = PurchaseReceiveDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_receive_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->join('inventory_purchase_order_info','inventory_purchase_order_info.id', '=', 'inventory_purchase_receive_details.reference_id')
                ->select('inventory_purchase_receive_details.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'),'cadet_stock_products.sku', 'inventory_purchase_order_info.voucher_no as ref_voucher_name')
                ->where('inventory_purchase_receive_details.pur_receive_id', $id)
                ->get();
        }
        $institute = Institute::findOrFail(self::getInstituteId());
        $pdf = App::make('dompdf.wrapper');
        $signatories = SignatoryConfig::with('employeeInfo.singleUser', 'employeeInfo.singleDesignation', 'employeeInfo.singleDepartment')->where([
            ['reportName','purchase-receive'],
            ['campus_id',$this->academicHelper->getCampus()],
            ['institute_id',$this->academicHelper->getInstitute()]
        ]);
        $totalSignatory = $signatories->count();
        $signatories = $signatories->get();
       
        $pdf->loadView('inventory::purchase.purchaseReceive.purchase-receive-print',compact('voucherDetailsData', 'voucherInfo','institute','totalSignatory', 'signatories'))->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $purchaseReceiveInfo  = PurchaseReceiveInfoModel::module()->valid()->find($id);            
        $purchase_receive_date = $purchaseReceiveInfo->date; 
        $purchase_receive_due_date = $purchaseReceiveInfo->due_date; 
        $date = DateTime::createFromFormat('Y-m-d', $purchaseReceiveInfo->date)->format('d/m/Y');
        $due_date = DateTime::createFromFormat('Y-m-d', $purchaseReceiveInfo->due_date)->format('d/m/Y');
        $purchaseReceiveInfo->date = $date;
        $purchaseReceiveInfo->auto_voucher = true;
        $purchaseReceiveInfo->due_date = $due_date;
        $data['representative_user_list'] = User::select('id', 'name')->module()->get();
        $representative_id_model=User::select('id', 'name')->where('id', Auth::user()->id)->first();
        $purchaseReceiveInfo->representative_name = $representative_id_model->name;
        $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
        $campus_id_model=Campus::select('id', 'name')->where('id', $purchaseReceiveInfo->campus_id)->first();
        $purchaseReceiveInfo->campus_name = $campus_id_model->name;
        $purchaseReceiveInfo->campus_id_model = $campus_id_model;

        $data['vendor_list'] = VendorModel::select('id','name')->get();
        $vendor_id_model = VendorModel::select('id', 'name')->find($purchaseReceiveInfo->vendor_id);
        $vendor_name = $vendor_id_model->name;
        $purchaseReceiveInfo->vendor_id_model = $vendor_id_model;
        $purchaseReceiveInfo->vendor_name = $vendor_name;
        $data['store_list'] = InventoryStore::select('id','store_name')->access($this)->get();
        $store_id_model = InventoryStore::select('id','store_name')->find($purchaseReceiveInfo->store_id);
        $purchaseReceiveInfo->store_name = $store_id_model->store_name;
        $purchaseReceiveInfo->store_id_model = $store_id_model;

        $item_list = self::storeWiseItemList($purchaseReceiveInfo->store_id);
        $item_ids = $item_list->pluck('item_id')->all();
        // voucher details data
        $voucherDetailsData = PurchaseReceiveDetailsModel::module()->valid()
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_receive_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
            ->join('inventory_purchase_order_info','inventory_purchase_order_info.id', '=', 'inventory_purchase_receive_details.reference_id')
            ->join('inventory_purchase_order_details','inventory_purchase_order_details.id', '=', 'inventory_purchase_receive_details.reference_details_id')
            ->select('inventory_purchase_receive_details.*', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of','cadet_stock_products.use_serial', 'inventory_purchase_order_details.app_qty', 'inventory_purchase_order_info.voucher_no as ref_voucher_name')
            ->where('inventory_purchase_receive_details.pur_receive_id', $id)
            ->whereIn('inventory_purchase_receive_details.item_id', $item_ids)
            ->orderBy('inventory_purchase_receive_details.id', 'asc')
            ->get();        
        $voucher_ref_details_ids = $voucherDetailsData->pluck('reference_details_id')->all();
        $voucher_details_item_ids = $voucherDetailsData->pluck('item_id')->all();
        
        // voucher reference data
       
        $refItemList = PurchaseOrderDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_purchase_order_info', 'inventory_purchase_order_info.id','=', 'inventory_purchase_order_details.pur_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_order_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
            ->select('inventory_purchase_order_details.id as reference_details_id','inventory_purchase_order_details.pur_id as reference_id','inventory_purchase_order_details.item_id','inventory_purchase_order_details.pur_qty','inventory_purchase_order_details.app_qty','inventory_purchase_order_details.rate','inventory_purchase_order_details.total_amount','inventory_purchase_order_details.vat_per','inventory_purchase_order_details.vat_type','inventory_purchase_order_details.vat_amount','inventory_purchase_order_details.discount','inventory_purchase_order_details.net_total','inventory_purchase_order_details.remarks',DB::raw("DATE_FORMAT(inventory_purchase_order_info.date,'%d/%m/%Y') AS pur_date, DATE_FORMAT(inventory_purchase_order_info.due_date,'%d/%m/%Y') AS due_date"), 'inventory_purchase_order_info.voucher_no as ref_voucher_name', 'cadet_stock_products.product_name', 'cadet_stock_products.sku','cadet_stock_products.use_serial', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of')
            ->where('inventory_purchase_order_info.vendor_id',$purchaseReceiveInfo->vendor_id)
            ->where('inventory_purchase_order_info.due_date','<=',$purchase_receive_date)
            ->whereIn('inventory_purchase_order_details.ref_use',[0,1])
            ->whereIn('inventory_purchase_order_details.status',[1,2])
            ->orderBy('inventory_purchase_order_info.due_date','asc')
            ->get(); 

        $ref_details_ids = $refItemList->pluck('reference_details_id')->all();
        $ref_item_ids =  $refItemList->pluck('item_id')->all(); 
        $merge_ref_details_ids  = collect($voucher_ref_details_ids)->merge($ref_details_ids)->all();
        $merge_ref_item_ids  = collect($voucher_details_item_ids)->merge($ref_item_ids)->all();
        $merge_un_ref_details_ids = collect($merge_ref_details_ids)->unique()->values()->all();
        $merge_un_ref_item_ids = collect($merge_ref_item_ids)->unique()->values()->all();

        $purRecDataList  = PurchaseReceiveDetailsModel::module()->valid()->select('reference_details_id',DB::raw('SUM(app_rec_qty) as app_rec_qty'))->whereIn('reference_details_id', $merge_un_ref_details_ids)->whereIn('status',[1,2])->where('reference_type', 'purchase-order')->groupBy('reference_details_id')->get()->keyBy('reference_details_id')->all();

        foreach ($refItemList as $v){
            if(array_key_exists($v->reference_details_id, $purRecDataList)){
                $purRecInfo = $purRecDataList[$v->reference_details_id];
                $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                $avail_qty = self::itemQtySubtraction($itemInfo, $v->app_qty,$purRecInfo->app_rec_qty);
            }else{
                $avail_qty = $v->app_qty;
            }
            $v->avail_qty = round($avail_qty, $v->decimal_point_place);
            $v->rec_qty = round($v->avail_qty, $v->decimal_point_place);
            $v->row_style = ($v->use_serial==0)? 'valid' : 'invalid';
            $v->app_qty = round($v->app_qty, $v->decimal_point_place);
            $v->ref_check=0;
            $v->serial_data = [];
            $v->serial_html = '';
        }
        // purchase receive serial details data
        $purchaseRecieveSerialDataGroupBy  = PurchaseReceiveSerialDetailsModel::module()->valid()
            ->select('serial_details_id as id', 'serial_code','pur_receive_details_id')
            ->where('pur_receive_id', $id)
            ->get()->groupBy('pur_receive_details_id')->all();

        foreach($voucherDetailsData as $v){
            if(array_key_exists($v->reference_details_id, $purRecDataList)){
                $purRecInfo = $purRecDataList[$v->reference_details_id];
                $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                $avail_qty = self::itemQtySubtraction($itemInfo, $v->app_qty,$purRecInfo->app_qty);
            }else{
                $avail_qty = round($v->app_qty, $v->decimal_point_place);
            }
            $v->avail_qty = round($avail_qty, $v->decimal_point_place);
            $v->rec_qty = round($v->rec_qty, $v->decimal_point_place);
            $v->rate = round($v->rate, 2);
            $v->total_amount = round($v->total_amount, 2);
            $v->vat_per = round($v->vat_per, 2);
            $v->vat_amount = round($v->vat_amount, 2);
            $v->discount = round($v->discount, 2);
            $v->net_total = round($v->net_total, 2);
            if($v->has_serial==1){
                if(array_key_exists($v->id, $purchaseRecieveSerialDataGroupBy)){
                    $purchaseReceiveSerialData = $purchaseRecieveSerialDataGroupBy[$v->id];
                    $v->serial_data = $purchaseReceiveSerialData;
                    $serial_html = '<ul style="list-style:none">';
                    foreach($purchaseReceiveSerialData as $serialInfo){
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
        }

        $purchaseReceiveInfo->voucherDetailsData = $voucherDetailsData;
        $purchaseReceiveInfo->itemAdded = (count($voucherDetailsData)>0)?'yes':'no';
        $data['refItemList'] = $refItemList;
        $data['formData'] = $purchaseReceiveInfo; 
        return response()->json($data);
    }

   

    public function voucherApproval(Request $request, $id=0)
    {
        DB::beginTransaction();
        try{
            $auth_user_id = Auth::user()->id;
            if($id>0){
                $approvalData = PurchaseReceiveDetailsModel::module()->valid()
                    ->join('inventory_purchase_receive_info', 'inventory_purchase_receive_info.id','=', 'inventory_purchase_receive_details.pur_receive_id')
                    ->select('inventory_purchase_receive_details.*','inventory_purchase_receive_details.rec_qty as qty','inventory_purchase_receive_details.net_total as amount', 'inventory_purchase_receive_info.store_id', 'inventory_purchase_receive_info.vendor_id')
                    ->find($id);
                if(!empty($approvalData)){
                    if($approvalData->status==0){
                        $approval_info = self::getApprovalInfo('purchase_receive');
                        $step = $approval_info['step'];
                        $approval_access = $approval_info['approval_access'];
                        $last_step = $approval_info['last_step'];
                        if($approval_access && $approvalData->approval_level==$step){
                            $flag=true;
                            if($step==$last_step){
                                $itemInfo = CadetInventoryProduct::find($approvalData->item_id);
                                if(!empty($itemInfo)){
                                    $purchase_receive_reference_data = PurchaseOrderDetailsModel::module()->valid()->where('id', $approvalData->reference_details_id)->whereIn('ref_use',[0,1])->whereIn('status',[1,2])->first(); 
                                    if(!empty($purchase_receive_reference_data)){
                                        $purRecReferenceSum  = PurchaseReceiveDetailsModel::module()->valid()->where('reference_details_id', $purchase_receive_reference_data->id)->whereIn('status',[1,2])->where('reference_type', 'purchase-order')->sum('app_rec_qty');
                                        if($purRecReferenceSum>0){
                                            $avail_qty = self::itemQtySubtraction($itemInfo, $purchase_receive_reference_data->app_qty,$purRecReferenceSum);
                                        }else{
                                            $avail_qty = $purchase_receive_reference_data->app_qty;
                                        }
                                        if($approvalData->rec_qty<=$avail_qty){
                                            $serial_stock_check = true; 
                                            if($approvalData->has_serial==1){
                                                $serial_msg = [];
                                                $purchaeReceiveSerialDetialsData = PurchaseReceiveSerialDetailsModel::module()->valid()->where('pur_receive_details_id', $approvalData->id)->get();
                                                $purchaeReceiveSerialDetialsDataKeyBy = $purchaeReceiveSerialDetialsData->keyBy('serial_details_id')->all();
                                                $purchaeReceiveSerialDetialsIds = $purchaeReceiveSerialDetialsData->pluck('serial_details_id')->all(); 
                                                foreach($purchaeReceiveSerialDetialsIds as $stock_sl_dtl_id):
                                                    $stock_item_serial_check = StockItemSerialDetailsModel::module()->valid()
                                                        ->where('item_id', $approvalData->item_id)
                                                        ->where('id', $stock_sl_dtl_id)
                                                        ->where(function($query){
                                                            $query->whereNull('stock_in_store_id')
                                                                ->orWhere('stock_in_store_id', 0);
                                                        })->first();
                                                    if(empty($stock_item_serial_check)){
                                                        $serial_msg[] = @$purchaeReceiveSerialDetialsDataKeyBy[$stock_sl_dtl_id]->serial_code.' has already received';
                                                        $serial_stock_check = false;
                                                    }
                                                endforeach;
                                            }

                                            if($serial_stock_check){
                                                $stockCalInfo  = self::storeStockIncrementAndCostPrice($itemInfo,$approvalData);
                                                $flag = $stockCalInfo['flag'];
                                                if($flag){
                                                    $current_stock = $stockCalInfo['current_stock'];
                                                    $avg_price = $stockCalInfo['avg_price'];
                                                    $approvalData->update([
                                                        'status'=>1,
                                                        'app_rec_qty'=>$approvalData->rec_qty,
                                                        'approval_level'=>$step+1
                                                    ]);
                                                    // update reference 
                                                    PurchaseOrderDetailsModel::module()->valid()->find($approvalData->reference_details_id)->update(['ref_use'=>($avail_qty>$approvalData->rec_qty)?1:3]);
                                                    if($approvalData->has_serial==1){
                                                        // update serial detials data
                                                        StockItemSerialDetailsModel::module()->valid()->whereIn('id', $purchaeReceiveSerialDetialsIds)->update([
                                                            'stock_in_store_id'=>$approvalData->store_id,
                                                            'stock_in_qty'=>1,
                                                            'current_stock'=>1,
                                                            'price'=>$approvalData->rate,
                                                            'stock_in_from'=>'purchase_receive',
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
                                                        'stock_in_id'=>$approvalData->pur_receive_id,
                                                        'stock_in_details_id'=>$approvalData->id,
                                                        'store_id'=>$approvalData->store_id,
                                                        'qty'=>$approvalData->qty,
                                                        'rate'=>$approvalData->rate,
                                                        'hand_qty'=>$approvalData->qty,
                                                        'stock_in_from'=>"purchase_receive"
                                                    ]);
                                                    // update master status base on all app
                                                    self::masterVoucherUpdate($approvalData);
                                                }else{
                                                    $output = ['status'=>0, 'message'=>$stockCalInfo['msg']]; 
                                                }
                                            }else{
                                               $flag=false;
                                               $output = ['status'=>0, 'message'=>$serial_msg];   
                                            }
                                        }else{
                                            $flag=false;
                                            $output = ['status'=>0, 'message'=>'Insufficient Purchase Receive Reference qty'];
                                        }
                                    }else{
                                        $flag=false;
                                        $output = ['status'=>0, 'message'=>'Purchase Receive Reference not found'];
                                    }
                                }else{
                                   $flag=false;
                                   $output = ['status'=>0, 'message'=>'Purchase Receive Item not found']; 
                                }
                            }else{ // end if($step==$last_step){
                                $approvalData->update([
                                    'approval_level'=>$step+1
                                ]); 
                            }
                            if($flag){
                                VoucherApprovalLogModel::create([
                                    'date'=>date('Y-m-d H:i:s'),
                                    'voucher_id'=>$approvalData->pur_receive_id,
                                    'voucher_details_id'=>$approvalData->id,
                                    'voucher_type'=>'purchase_receive',
                                    'approval_id'=>$auth_user_id,
                                    'approval_layer'=>$step,
                                    'action_status'=>1,
                                    'institute_id'=>self::getInstituteId(),
                                    'campus_id'=>self::getCampusId(),
                                ]);

                                
                                DB::commit();
                                $output = ['status'=>1, 'message'=>'Stock in item successfully approved'];
                            }
                        }else{ // end if($approval_access && $approvalData->approval_level==$step){
                            $output = ['status'=>0, 'message'=>'Sory you have no approval'];    
                        }
                    }else{ // end if($approvalData->status==0)
                        if($approvalData->status==3){
                            $output = ['status'=>0, 'message'=>'Purchase Receive Item reject'];
                        }else{
                            $output = ['status'=>0, 'message'=>'Purchase Receive Item already approved'];  
                        }
                    }
 
                }else{   // end if(!empty($approvalData))
                    $output = ['status'=>0, 'message'=>'Purchase Receive Item not found'];
                }
            }
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        } 

        return response()->json($output); 
    }

    public function masterVoucherUpdate($approvalData){
        $pur_receive_id = $approvalData->pur_receive_id;
        $reference_id = $approvalData->reference_id;
        $checkPendingStatus = PurchaseReceiveDetailsModel::module()->valid()
            ->where('pur_receive_id', $pur_receive_id)
            ->where(function($query){
                $query->where('status', 0)
                     ->orWhere('status',3);

            })->first();
        PurchaseReceiveInfoModel::module()->valid()->find($pur_receive_id)->update([
            'status'=>(!empty($checkPendingStatus))?2:1
        ]);
        // update reference table
        $checkPartialReqUse = PurchaseOrderDetailsModel::module()->valid()
            ->where('pur_id',$reference_id)
            ->where(function($query){
                $query->where('ref_use', 0)
                    ->orWhere('ref_use',1);

            })->first();
        PurchaseOrderInfoModel::module()->valid()->find($reference_id)->update(['ref_use'=> (!empty($checkPartialReqUse))?1:3]);
       
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
                $deleteData = PurchaseReceiveDetailsModel::module()->valid()->find($id);
                if(!empty($deleteData)){
                    if($deleteData->status==1||$deleteData->status==2){  // check status
                        $output = ['status'=>0, 'message'=>'Sorry! purchase receive item already approved'];
                    }else{
                        $pur_receive_id = $deleteData->pur_receive_id; 
                        $has_serial = $deleteData->has_serial;
                        PurchaseReceiveDetailsModel::module()->valid()->find($id)->delete();
                        if($has_serial==1){
                            $purchaseReceiveSerialDetails  = PurchaseReceiveSerialDetailsModel::module()->valid()->where('pur_receive_details_id',$id)->get();
                            foreach($purchaseReceiveSerialDetails as $purRecSerial){
                                PurchaseReceiveSerialDetailsModel::module()->valid()->find($purRecSerial->id)->delete();
                            }
                        }
                        $checkDetailsItem = PurchaseReceiveDetailsModel::module()->valid()->where('pur_receive_id', $pur_receive_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            PurchaseReceiveInfoModel::module()->valid()->find($pur_receive_id)->delete(); 
                        }
                        $output = ['status'=>1, 'message'=>'Purchase receive successfully deleted'];
                        DB::commit();
                    }
                }else{
                    $output = ['status'=>0, 'message'=>'Item not found'];
                }
            }else{
                $delIds = $request->delIds;
                // status check
                $pur_receive_ids = []; $flag = true; $msg =[]; 
                foreach ($delIds as $del_id){
                    $deleteData = PurchaseReceiveDetailsModel::module()->valid()->find($del_id);
                    if($deleteData->status==1||$deleteData->status==2){
                        $itemInfo = CadetInventoryProduct::find($deleteData->item_id);
                        $flag = false;
                        $msg[] = $itemInfo->product_name.' has purchase order qty approval';
                    }
                    $pur_receive_ids[] = $deleteData->pur_receive_id;
                }
                if($flag){
                    foreach ($delIds as $del_id){
                        $deleteData = PurchaseReceiveDetailsModel::module()->valid()->find($del_id);
                        if($deleteData->has_serial==1):
                            $purchaseReceiveSerialDetails  = PurchaseReceiveSerialDetailsModel::module()->valid()->where('pur_receive_details_id',$del_id)->get();
                            foreach($purchaseReceiveSerialDetails as $purRecSerial){
                                PurchaseReceiveSerialDetailsModel::module()->valid()->find($purRecSerial->id)->delete();
                            }
                        endif;
                        $deleteData->delete();
                    }
                    $pur_rec_unique_ids = collect($pur_receive_ids)->unique()->values()->all();
                    foreach ($pur_rec_unique_ids as $pur_receive_id) {
                        $checkDetailsItem = PurchaseReceiveDetailsModel::module()->valid()->where('pur_receive_id', $pur_receive_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            PurchaseReceiveInfoModel::module()->valid()->find($pur_receive_id)->delete(); 
                        }
                    }
                    $output = ['status'=>1, 'message'=>'Purchase receive successfully deleted'];
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

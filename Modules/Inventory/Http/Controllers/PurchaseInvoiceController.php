<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\PurchaseInvoiceModel;
use Modules\Inventory\Entities\PurchaseInvoiceDetailsModel;
use Modules\Inventory\Entities\PurchaseReceiveInfoModel;
use Modules\Inventory\Entities\PurchaseReceiveDetailsModel;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\UserVoucherApprovalLayerModel;
use Modules\Inventory\Entities\VoucherApprovalLogModel;
use Modules\Inventory\Entities\StoreWiseItemModel;
use Modules\Inventory\Entities\VendorModel;
use Modules\Inventory\Entities\InventoryStore;
use Modules\Accounts\Entities\ChartOfAccount;
use Modules\Accounts\Entities\AccountsConfigurationModel;
use Modules\Accounts\Entities\AccountsTransactionModel;
use Modules\Accounts\Entities\SubsidiaryCalculationModel;
use Modules\Accounts\Entities\AccountsVoucherApprovalLogModel;
use Modules\Accounts\Entities\ChartOfAccountsConfigModel;
use Modules\Setting\Entities\Campus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\InventoryHelper;
use App\User;
use Illuminate\Validation\Rule;
use DateTime;

class PurchaseInvoiceController extends Controller
{

    use InventoryHelper;
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

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $listPerPage = $request->input('listPerPage');
        $search_key = $request->input('search_key');
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
        if($sort=='id') $sort='inventory_purchase_invoice_info.id';

        $item_list = self::itemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $data['item_list'] =  self::mergeEmtyArryObj($item_list, ['item_id'=>0, 'product_name'=>'Select item']);
        $voucher_list = PurchaseInvoiceModel::module()
            ->select('inventory_purchase_invoice_info.id', 'inventory_purchase_invoice_info.voucher_no')
            ->orderBy('voucher_int', 'desc')
            ->get();   

        $data['voucher_list'] =  self::mergeEmtyArryObj($voucher_list, ['id'=>0, 'voucher_no'=>'Select voucher']);     

        $paginate_data_query = PurchaseInvoiceModel::module()
            ->join('inventory_vendor_info', 'inventory_vendor_info.id','=', 'inventory_purchase_invoice_info.vendor_id')
            ->select('inventory_purchase_invoice_info.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS pur_invoice_date"), 'inventory_vendor_info.name as vendor_name')
            ->when($voucher_id, function($query, $voucher_id){
                $query->where('inventory_purchase_invoice_info.pur_invoice_id',$voucher_id);
            })
            ->when($from_date, function($query, $from_date){
                $query->where('inventory_purchase_invoice_info.date','>=',$from_date);
            })
            ->when($to_date, function($query, $to_date){
                $query->where('inventory_purchase_invoice_info.date','<=',$to_date);
            })
            ->when($status, function($query, $status){
                if($status=='p') $status=0;
                $query->where('inventory_purchase_invoice_info.status',$status);
            })
            ->where(function($query)use($search_key){
                if(!empty($search_key)){
                    $query->where('inventory_purchase_invoice_info.voucher_no','LIKE','%'.$search_key.'%')
                        ->orWhere('inventory_vendor_info.name','LIKE','%'.$search_key.'%');
                }
            })            
            ->orderBy($sort,$order);     

        $paginate_data = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);
        if(count($paginate_data)>0){
            $auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', 'purchase_invoice')->where('is_role', 0)->get();
            $step=1; $approval_access=true; $approval_log_group = []; $approval_step_log=[];
            if(count($UserVoucherApprovalLayer)>0){
                $UserVoucherApprovalKeyBy = $UserVoucherApprovalLayer->keyBy('approval_id')->all();
                if(array_key_exists($auth_user_id, $UserVoucherApprovalKeyBy)){
                    $step = $UserVoucherApprovalKeyBy[$auth_user_id]->step;
                }else{
                   $approval_access=false; 
                }
            }
            $voucher_ids = $paginate_data->pluck('id')->all(); 
            // for approval text
            $approval_log_group = VoucherApprovalLogModel::module()->valid()
                ->join('users', 'users.id', '=', 'inventory_voucher_approval_log.approval_id')
                ->select('inventory_voucher_approval_log.*', 'users.name')
                ->where('voucher_type', 'purchase_invoice')
                ->where('is_role', 0)
                ->whereIn('voucher_id', $voucher_ids)
                ->orderBy('inventory_voucher_approval_log.approval_layer', 'asc')
                ->get()->groupBy('voucher_id')->all();
            // check if his step is approval or not
            $approval_step_log = VoucherApprovalLogModel::module()->valid()
                ->where('voucher_type', 'purchase_invoice')
                ->whereIn('voucher_id', $voucher_ids)
                ->where('approval_layer', $step)
                ->where('is_role', 0)
                ->where('approval_id', $auth_user_id)
                ->get()->keyBy('voucher_id')->all();

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
        return view('inventory::purchase.purchaseInvoice.purchase-invoice');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $voucherInfo = self::checkInvVoucher(17);
        if($voucherInfo['voucher_conf']){
            $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
            $campus_id_model=Campus::select('id', 'name')->where('id', self::getCampusId())->first();
            $campus_name = $campus_id_model->name;
            $data['vendor_list'] = VendorModel::select('id','name')->get();
            $data['formData'] = ['voucher_no'=>$voucherInfo['voucher_no'],'voucher_config_id'=>$voucherInfo['voucher_config_id'],'auto_voucher'=>$voucherInfo['auto_voucher'], 'date'=>date('d/m/Y'),'due_date'=>date('d/m/Y'),'campus_id_model'=>$campus_id_model,'campus_id'=>self::getCampusId(),'campus_name'=>$campus_name,'vendor_id'=>0,'reference_type'=>'', 'voucherDetailsData'=>[], 'itemAdded'=>'no'];
        }else{
            $data = ['status'=>0, 'message'=>"Setup voucher configuration first"];
        }
        return response()->json($data);
    }

    public function purchaseInvoiceReferenceList(Request $request){
        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $campus_id = $request->campus_id;
        $reference_type = $request->reference_type;
        $vendor_id = $request->vendor_id;
        $item_list = self::itemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $refItemList = PurchaseReceiveDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_purchase_receive_info', 'inventory_purchase_receive_info.id','=', 'inventory_purchase_receive_details.pur_receive_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_receive_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
            ->select('inventory_purchase_receive_details.id as reference_details_id','inventory_purchase_receive_details.pur_receive_id as reference_id','inventory_purchase_receive_details.item_id','inventory_purchase_receive_details.app_rec_qty','inventory_purchase_receive_details.rate','inventory_purchase_receive_details.total_amount','inventory_purchase_receive_details.vat_per','inventory_purchase_receive_details.vat_type','inventory_purchase_receive_details.vat_amount','inventory_purchase_receive_details.discount','inventory_purchase_receive_details.net_total','inventory_purchase_receive_details.remarks',DB::raw("DATE_FORMAT(inventory_purchase_receive_info.date,'%d/%m/%Y') AS pur_rec_date, DATE_FORMAT(inventory_purchase_receive_info.due_date,'%d/%m/%Y') AS due_date"), 'inventory_purchase_receive_info.voucher_no as ref_voucher_name', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of')
            ->where('inventory_purchase_receive_info.vendor_id',$vendor_id)
            ->where('inventory_purchase_receive_info.due_date','<=',$date)
            ->whereIn('inventory_purchase_receive_details.ref_use',[0,1])
            ->whereIn('inventory_purchase_receive_details.status',[1,2])
            ->orderBy('inventory_purchase_receive_info.due_date','asc')
            ->get(); 
            $ref_details_ids = $refItemList->pluck('reference_details_id')->all();
            $ref_item_ids =  $refItemList->pluck('item_id')->all(); 
        $purInvoiceDataList  = PurchaseInvoiceDetailsModel::module()->select('reference_details_id',DB::raw('SUM(qty) as app_total_qty'))->whereIn('reference_details_id', $ref_details_ids)->whereIn('status',[1,2])->where('reference_type', 'purchase-receive')->groupBy('reference_details_id')->get()->keyBy('reference_details_id')->all();

        foreach ($refItemList as $v){
            if(array_key_exists($v->reference_details_id, $purInvoiceDataList)){
                $purInvoiceInfo = $purInvoiceDataList[$v->reference_details_id];
                $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                $avail_qty = self::itemQtySubtraction($itemInfo, $v->app_rec_qty,$purInvoiceInfo->app_total_qty);
            }else{
                $avail_qty = $v->app_rec_qty;
            }
            $v->avail_qty = round($avail_qty, $v->decimal_point_place);
            $v->qty = round($v->avail_qty, $v->decimal_point_place);
            $v->rec_qty = round($v->rec_qty, $v->decimal_point_place);
            $v->app_rec_qty = round($v->app_rec_qty, $v->decimal_point_place);
            $v->ref_check=0;
        }

        return response()->json($refItemList);
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
            'date' => 'required|date_format:d/m/Y',
            'due_date' => 'required|date_format:d/m/Y|after_or_equal:date',
            'campus_id' => 'required',
            'reference_type' => 'required'
        ]);

        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');       
        $due_date = DateTime::createFromFormat('d/m/Y', $request->due_date)->format('Y-m-d');           
        $voucherDetailsData = $request->voucherDetailsData;
        $reference_type = $request->reference_type;
        if(count($voucherDetailsData)>0){
            if(!empty($id)){
                $voucherDetailsData_db = PurchaseInvoiceDetailsModel::module()->where('pur_invoice_id', $id)->get();
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
                    if($v['avail_qty']>=$v['qty']){           // check available qty
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
                        if($v['qty']>0){
                            $has_qty = true;
                        }
                    }else{
                        $flag = false;
                        $msg[] = $itemInfo->product_name.' has invoice more then available qty';  
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
                        "date" => $date,
                        "due_date" => $due_date,
                        "reference_type" => $request->reference_type,
                        "total_vat" => (!empty($request->total_vat))?$request->total_vat:0,
                        "total_discount" => (!empty($request->total_discount))?$request->total_discount:0,
                        "grand_total_amount" => (!empty($request->grand_total_amount))?$request->grand_total_amount:0,
                       "grand_net_amount" => (!empty($request->grand_net_amount))?$request->grand_net_amount:0,
                       "comments"=>$request->comments
                    ];
                    $auto_voucher = $request->auto_voucher;  // voucher type 
                    if(!empty($id)){
                        $pur_invoice_id = $id;
                        $purInvInfo = PurchaseInvoiceModel::module()->findOrFail($id);
                        if($purInvInfo->status==0){ // check info status
                            // date change check 
                            if($item_approval && ($purInvInfo->date!=$date || $purInvInfo->due_date!=$due_date || $purInvInfo->vendor_id!=$request->vendor_id)){
                                $flag = false; 
                                if($purInvInfo->date!=$date || $purInvInfo->due_date!=$due_date){
                                    $msg[] = 'Sorry puchase invoice  details item already approved you can not change date';
                                }else if($purInvInfo->vendor_id!=$request->vendor_id){
                                    $msg[] = 'Sorry puchase invoice  details item already approved you can not change vendor';
                                }else{
                                    $msg[] = 'Sorry puchase invoice  details item already approved you can not change Representative';
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
                                    $purInvInfo->update($data); 
                                    // delete details data
                                    foreach($vouDtlIds_diff as $diffId) {
                                        PurchaseInvoiceDetailsModel::find($diffId)->delete();
                                    }
                                }
                            }
                        }else{
                           $flag = false; 
                           $msg[] = 'Sorry purchase invoice already approved';
                        }

                    }else{
                        if($auto_voucher){ // auto voucher configuration
                            $voucherInfo = self::getVoucherNo('purInvoice');
                            if($voucherInfo['voucher_no']){
                                $data['voucher_no'] = $voucherInfo['voucher_no'];
                                $data['voucher_int'] = $voucherInfo['voucher_int'];
                                $data['voucher_config_id'] = $voucherInfo['voucher_config_id'];
                            }else{
                                $flag=false;
                                $msg = $voucherInfo['msg'];  
                            }
                        }else{  // menual voucher 
                            $checkVoucher = PurchaseInvoiceModel::module()->where('voucher_no', $request->voucher_no)->first();
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
                            $save = PurchaseInvoiceModel::create($data);
                            $pur_invoice_id = $save->id;
                        } 

                    }
                    if($flag){
                        foreach ($voucherDetailsData as $v) {
                            $details_id = @$v['id'];
                            if($v['qty']>0){
                                $detailsData  = [
                                    'pur_invoice_id'=>$pur_invoice_id,
                                    'item_id'=>$v['item_id'],
                                    'qty'=>$v['qty'],
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
                                    'remarks'=>$v['remarks']
                                ];
                                if($details_id>0){
                                    $pur_receive_details_id = $details_id;
                                    PurchaseInvoiceDetailsModel::module()->find($details_id)->update($detailsData);;
                                }else{
                                    $detailsSave = PurchaseInvoiceDetailsModel::create($detailsData);
                                    $pur_receive_details_id = $detailsSave->id;
                                }
                            }else{
                                if($details_id>0){
                                    PurchaseInvoiceDetailsModel::find($details_id)->delete();
                                }
                            }
                        }
                       $output = ['status'=>1,'message'=>'Purchase invoice successfully saved'];
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
                    $output = ['status'=>0,'message'=>"Empty invoice qty"]; 
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
        $voucherInfo  = PurchaseInvoiceModel::module()
            ->join('setting_campus', 'setting_campus.id','=', 'inventory_purchase_invoice_info.campus_id')
            ->join('inventory_vendor_info', 'inventory_vendor_info.id','=', 'inventory_purchase_invoice_info.vendor_id')
            ->select('inventory_purchase_invoice_info.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS pur_invoice_date, DATE_FORMAT(due_date,'%d/%m/%Y') AS due_date_formate"), 'setting_campus.name as campus_name', 'inventory_vendor_info.name as vendor_name')
            ->where('inventory_purchase_invoice_info.id', $id)
            ->first();
        if(!empty($voucherInfo)){
            $voucherDetailsData = PurchaseInvoiceDetailsModel::module()
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_invoice_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
                ->join('inventory_purchase_receive_info','inventory_purchase_receive_info.id', '=', 'inventory_purchase_invoice_details.reference_id')
                ->join('inventory_purchase_receive_details','inventory_purchase_receive_details.id', '=', 'inventory_purchase_invoice_details.reference_details_id')
                ->select('inventory_purchase_invoice_details.*', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of','cadet_stock_products.use_serial', 'inventory_purchase_receive_details.app_rec_qty', 'inventory_purchase_receive_info.voucher_no as ref_voucher_name')
                ->where('inventory_purchase_invoice_details.pur_invoice_id', $id)
                ->whereIn('inventory_purchase_invoice_details.item_id', $item_ids)
                ->orderBy('inventory_purchase_invoice_details.id', 'asc')
                ->get();  
            
            foreach($voucherDetailsData as $v){
                $v->qty = round($v->qty, $v->decimal_point_place);
                $v->rate = round($v->rate, 2);
                $v->total_amount = round($v->total_amount, 2);
                $v->vat_per = round($v->vat_per, 2);
                $v->vat_amount = round($v->vat_amount, 2);
                $v->discount = round($v->discount, 2);
                $v->net_total = round($v->net_total, 2);
            }
            $voucherInfo->voucherDetailsData = $voucherDetailsData;
            $data['formData'] = $voucherInfo;
            $data['refItemList'] = [];
        }else{
            $data = ['status'=>0, 'message'=>"Purchase Invoice Voucher not found"];
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
        $purchaseInvoiceInfo  = PurchaseInvoiceModel::module()->find($id);
        $purchase_invoice_date = $purchaseInvoiceInfo->date; 
        $purchase_invoice_due_date = $purchaseInvoiceInfo->due_date; 
        $date = DateTime::createFromFormat('Y-m-d', $purchaseInvoiceInfo->date)->format('d/m/Y');
        $due_date = DateTime::createFromFormat('Y-m-d', $purchaseInvoiceInfo->due_date)->format('d/m/Y');
        $purchaseInvoiceInfo->date = $date;
        $purchaseInvoiceInfo->auto_voucher = true;
        $purchaseInvoiceInfo->due_date = $due_date;
        $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
        $campus_id_model=Campus::select('id', 'name')->where('id', $purchaseInvoiceInfo->campus_id)->first();
        $purchaseInvoiceInfo->campus_name = $campus_id_model->name;
        $purchaseInvoiceInfo->campus_id_model = $campus_id_model;
        $data['vendor_list'] = VendorModel::select('id','name')->get();
        $vendor_id_model = VendorModel::select('id', 'name')->find($purchaseInvoiceInfo->vendor_id);
        $vendor_name = $vendor_id_model->name;
        $purchaseInvoiceInfo->vendor_id_model = $vendor_id_model;
        $purchaseInvoiceInfo->vendor_name = $vendor_name;

        $item_list = self::itemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        // voucher details data
        $voucherDetailsData = PurchaseInvoiceDetailsModel::module()
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_invoice_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
            ->join('inventory_purchase_receive_info','inventory_purchase_receive_info.id', '=', 'inventory_purchase_invoice_details.reference_id')
            ->join('inventory_purchase_receive_details','inventory_purchase_receive_details.id', '=', 'inventory_purchase_invoice_details.reference_details_id')
            ->select('inventory_purchase_invoice_details.*', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of','cadet_stock_products.use_serial', 'inventory_purchase_receive_details.app_rec_qty', 'inventory_purchase_receive_info.voucher_no as ref_voucher_name')
            ->where('inventory_purchase_invoice_details.pur_invoice_id', $id)
            ->whereIn('inventory_purchase_invoice_details.item_id', $item_ids)
            ->orderBy('inventory_purchase_invoice_details.id', 'asc')
            ->get();  

        $voucher_ref_details_ids = $voucherDetailsData->pluck('reference_details_id')->all();
        $voucher_details_item_ids = $voucherDetailsData->pluck('item_id')->all();
        
        // voucher reference data
        $refItemList = PurchaseReceiveDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_purchase_receive_info', 'inventory_purchase_receive_info.id','=', 'inventory_purchase_receive_details.pur_receive_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_receive_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
            ->select('inventory_purchase_receive_details.id as reference_details_id','inventory_purchase_receive_details.pur_receive_id as reference_id','inventory_purchase_receive_details.item_id','inventory_purchase_receive_details.app_rec_qty','inventory_purchase_receive_details.rate','inventory_purchase_receive_details.total_amount','inventory_purchase_receive_details.vat_per','inventory_purchase_receive_details.vat_type','inventory_purchase_receive_details.vat_amount','inventory_purchase_receive_details.discount','inventory_purchase_receive_details.net_total','inventory_purchase_receive_details.remarks',DB::raw("DATE_FORMAT(inventory_purchase_receive_info.date,'%d/%m/%Y') AS pur_rec_date, DATE_FORMAT(inventory_purchase_receive_info.due_date,'%d/%m/%Y') AS due_date"), 'inventory_purchase_receive_info.voucher_no as ref_voucher_name', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of')
            ->where('inventory_purchase_receive_info.vendor_id',$purchaseInvoiceInfo->vendor_id)
            ->where('inventory_purchase_receive_info.due_date','<=',$purchase_invoice_date)
            ->whereIn('inventory_purchase_receive_details.ref_use',[0,1])
            ->whereIn('inventory_purchase_receive_details.status',[1,2])
            ->orderBy('inventory_purchase_receive_info.due_date','asc')
            ->get(); 

        $ref_details_ids = $refItemList->pluck('reference_details_id')->all();
        $ref_item_ids =  $refItemList->pluck('item_id')->all(); 
        $merge_ref_details_ids  = collect($voucher_ref_details_ids)->merge($ref_details_ids)->all();
        $merge_ref_item_ids  = collect($voucher_details_item_ids)->merge($ref_item_ids)->all();
        $merge_un_ref_details_ids = collect($merge_ref_details_ids)->unique()->values()->all();
        $merge_un_ref_item_ids = collect($merge_ref_item_ids)->unique()->values()->all();

        $purInvoiceDataList  = PurchaseInvoiceDetailsModel::module()->select('reference_details_id',DB::raw('SUM(qty) as app_total_qty'))->whereIn('reference_details_id', $merge_un_ref_details_ids)->whereIn('status',[1,2])->where('reference_type', 'purchase-receive')->groupBy('reference_details_id')->get()->keyBy('reference_details_id')->all();

        foreach ($refItemList as $v){
            if(array_key_exists($v->reference_details_id, $purInvoiceDataList)){
                $purRecInfo = $purInvoiceDataList[$v->reference_details_id];
                $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                $avail_qty = self::itemQtySubtraction($itemInfo, $v->app_rec_qty,$purRecInfo->app_total_qty);
            }else{
                $avail_qty = $v->app_rec_qty;
            }
            $v->avail_qty = round($avail_qty, $v->decimal_point_place);
            $v->qty = round($v->avail_qty, $v->decimal_point_place);
            $v->ref_check=0;
        }
       

        foreach($voucherDetailsData as $v){
            if(array_key_exists($v->reference_details_id, $purInvoiceDataList)){
                $purRecInfo = $purInvoiceDataList[$v->reference_details_id];
                $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                $avail_qty = self::itemQtySubtraction($itemInfo, $v->app_rec_qty,$purRecInfo->app_total_qty);
            }else{
                $avail_qty = round($v->app_rec_qty, $v->decimal_point_place);
            }
            $v->avail_qty = round($avail_qty, $v->decimal_point_place);
            $v->qty = round($v->qty, $v->decimal_point_place);
            $v->rate = round($v->rate, 2);
            $v->total_amount = round($v->total_amount, 2);
            $v->vat_per = round($v->vat_per, 2);
            $v->vat_amount = round($v->vat_amount, 2);
            $v->discount = round($v->discount, 2);
            $v->net_total = round($v->net_total, 2);
        }

        $purchaseInvoiceInfo->voucherDetailsData = $voucherDetailsData;
        $purchaseInvoiceInfo->itemAdded = (count($voucherDetailsData)>0)?'yes':'no';
        $data['refItemList'] = $refItemList;
        $data['formData'] = $purchaseInvoiceInfo; 
        return response()->json($data);
    }

    public function voucherApproval(Request $request, $id=0)
    {
        DB::beginTransaction();
        try{
            $auth_user_id = Auth::user()->id;
            if($id>0){
                $approvalData = PurchaseInvoiceModel::module()->find($id);
                if(!empty($approvalData)){
                    $autoVoucherCheckInfo = self::invoiceAutoVoucherCheck($approvalData);
                    $auto_vou_status = $autoVoucherCheckInfo['status'];
                    if($approvalData->status==0 && $auto_vou_status){
                        $approval_info = self::getApprovalInfo('purchase_invoice');
                        $step = $approval_info['step'];
                        $approval_access = $approval_info['approval_access'];
                        $last_step = $approval_info['last_step'];
                        if($approval_access && $approvalData->approval_level==$step){
                            $flag=true; $msg = [];
                            if($step==$last_step){
                                $purchaseInvoiceDetails = PurchaseInvoiceDetailsModel::where('pur_invoice_id', $id)->get();
                                // Check reference 
                                foreach($purchaseInvoiceDetails as $v){
                                    $itemInfo = CadetInventoryProduct::find($v->item_id);
                                    $purchase_invoice_reference_data = PurchaseReceiveDetailsModel::module()->valid()->where('id', $v->reference_details_id)->whereIn('ref_use',[0,1])->whereIn('status',[1,2])->first();
                                    if(!empty($purchase_invoice_reference_data)){
                                        $purInvReferenceSum  = PurchaseInvoiceDetailsModel::module()->where('reference_details_id', $purchase_invoice_reference_data->id)->whereIn('status',[1,2])->where('reference_type', 'purchase-receive')->sum('qty');
                                        if($purInvReferenceSum>0){
                                            $avail_qty = self::itemQtySubtraction($itemInfo, $purchase_invoice_reference_data->app_rec_qty,$purInvReferenceSum);
                                        }else{
                                            $avail_qty = $purchase_invoice_reference_data->app_rec_qty;
                                        }
                                        $v->avail_qty = $avail_qty;
                                        if($v->qty>$avail_qty){
                                            $flag=false;
                                            $msg[] = $itemInfo->product_name.' has not sufficient reference qty';
                                        }
                                        
                                    }else{
                                        $flag=false;
                                        $msg[] = $itemInfo->product_name.' has not sufficient reference qty';
                                    }
                                }
                                if($flag){
                                    $approvalData->update([
                                        'status'=>1,
                                        'approval_level'=>$step+1
                                    ]);
                                    foreach($purchaseInvoiceDetails as $v){
                                        PurchaseInvoiceDetailsModel::find($v->id)->update([
                                                'status'=>1,
                                                'approval_level'=>$step+1
                                        ]);
                                        // update reference 
                                        PurchaseReceiveDetailsModel::module()->find($v->reference_details_id)->update(['ref_use'=>($v->avail_qty>$v->qty)?1:3]);
                                    }
                                    self::invoiceAutoVoucher($approvalData, $autoVoucherCheckInfo, $auth_user_id, $step);
                                    self::refMasterVoucherUpdate($purchaseInvoiceDetails); 
                                }
                            }else{ // end if($step==$last_step){
                                $approvalData->update([
                                    'approval_level'=>$step+1
                                ]);
                                PurchaseInvoiceDetailsModel::where('pur_invoice_id', $id)->update([
                                    'approval_level'=>$step+1
                                ]); 
                            }
                            if($flag){
                                VoucherApprovalLogModel::create([
                                    'date'=>date('Y-m-d H:i:s'),
                                    'voucher_id'=>$approvalData->id,
                                    'voucher_details_id'=>NULL,
                                    'voucher_type'=>'purchase_invoice',
                                    'approval_id'=>$auth_user_id,
                                    'approval_layer'=>$step,
                                    'action_status'=>1,
                                    'institute_id'=>self::getInstituteId(),
                                    'campus_id'=>self::getCampusId(),
                                ]);
                                DB::commit();
                                $output = ['status'=>1, 'message'=>'Stock in item successfully approved'];
                            }else{
                                $output = ['status'=>0, 'message'=>$msg]; 
                            }
                        }else{ // end if($approval_access && $approvalData->approval_level==$step){
                            $output = ['status'=>0, 'message'=>'Sory you have no approval'];    
                        }
                    }else{ // end if($approvalData->status==0)
                        if(!$auto_vou_status){
                            $output = ['status'=>0, 'message'=>$autoVoucherCheckInfo['msg']];
                        }else{
                            if($approvalData->status==3){
                                $output = ['status'=>0, 'message'=>'Purchase Invoice Item reject'];
                            }else{
                                $output = ['status'=>0, 'message'=>'Purchase Invoice Item already approved'];  
                            }
                        }
                    }
 
                }else{   // end if(!empty($approvalData))
                    $output = ['status'=>0, 'message'=>'Purchase Invoice Item not found'];
                }
            }
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        } 

        return response()->json($output); 
    }

    public function invoiceAutoVoucherCheck($approvalData){
        $status = true; $msg = []; $voucherDebitData=[]; $voucherCreditData=[];
        $vendor_id = $approvalData->vendor_id; 
        $grand_total_amount = $approvalData->grand_total_amount;
        $grand_net_amount = $approvalData->grand_net_amount;
        $total_discount = $approvalData->total_discount;
        $total_vat = $approvalData->total_vat;
        // chart of accounts code type config check start
        $coaConfig = ChartOfAccountsConfigModel::first();
        if(!empty($coaConfig) && $coaConfig->code_type=='Manual'){
           $code_type = $coaConfig->code_type;
        }else{
           $code_type = 'Auto'; 
        }
        $acc_code_col = ($code_type=='Manual')?'manual_account_code':'account_code';
        // chart of accounts code type config check end
        // check vendor ledger code
        $checkVendorGlCode = VendorModel::find($vendor_id);
        if(!empty($checkVendorGlCode->gl_code)){
            $chartOfGlCode = ChartOfAccount::where($acc_code_col, $checkVendorGlCode->gl_code)->first();

            $sundry_creditors_config = AccountsConfigurationModel::where('particular', 'sundry_creditors')->first();
            if(!empty($sundry_creditors_config)){
                $sundry_creditors_id = $sundry_creditors_config->particular_id;
            }else{
                $sundry_creditors_id = 0; 
            }  
            if(!empty($chartOfGlCode)){
                if($chartOfGlCode->account_type=='ledger' && $sundry_creditors_id==$chartOfGlCode->parent_id){
                    $vendor_gl_id = $chartOfGlCode->id;  
                    array_push($voucherCreditData,['sub_ledger'=>$vendor_gl_id, 'credit_amount'=>$grand_net_amount]); 
                }else{
                    $status = false;
                    if($chartOfGlCode->account_type=='group'){
                        $msg[] = "Vendor gl code should be ledger type of code";
                    }else{
                        $msg[] = "Vendor gl code should be ledger of Sundry Creditors";
                    }
                }
            }else{
                $status = false;
                $msg[] = "Invalid vendor gl code";
            }
        }else{
            $status = false;
            $msg[] = "Vendor GL code not setup";
        }

        $accountsConfig = AccountsConfigurationModel::where('label_name', "po_invoice")->get()->keyBy('particular')->all();
        // check purchase accounts 
        if(isset($accountsConfig['po_account']) && !empty($accountsConfig['po_account'])){
            $po_account = $accountsConfig['po_account']; 
            $chartOfCode = ChartOfAccount::where($acc_code_col, $po_account->particular_code)->first();
            if(!empty($chartOfCode)){
                if($chartOfCode->account_type=='ledger' && $po_account->account_type== 'ledger'){
                    $purchase_acc_id = $po_account->particular_id;
                    array_push($voucherDebitData,['sub_ledger'=>$purchase_acc_id, 'debit_amount'=>$grand_total_amount]); 
                }else{
                    $status = false;
                    $msg[] = "Purchase account should be ledger type code";
                } 
            }else{
                $status = false;
                $msg[] = "Invalid Purchase account ledger code";
            }
        }else{
            $status = false;
            $msg[] = "Purchase account ledger code did not configure";
        }

        // purchase discount
        if($total_discount>0){
            if(isset($accountsConfig['po_discount_account']) && !empty($accountsConfig['po_discount_account'])){
                $po_discount_account = $accountsConfig['po_discount_account']; 
                $chartOfCode = ChartOfAccount::where($acc_code_col, $po_discount_account->particular_code)->first();
                if(!empty($chartOfCode)){
                    if($chartOfCode->account_type=='ledger' && $po_discount_account->account_type == 'ledger'){
                        $purchase_dis_acc_id = $po_discount_account->particular_id;
                        array_push($voucherCreditData,['sub_ledger'=>$purchase_dis_acc_id, 'credit_amount'=>$total_discount]); 
                    }else{
                        $status = false;
                        $msg[] = "Purchase discount account should be ledger type code";
                    } 
                }else{
                    $status = false;
                    $msg[] = "Invalid Purchase discount account ledger code";
                }
            }else{
                $status = false;
                $msg[] = "Purchase discount account ledger code did not configure";
            }
        }

        // purchase vat account
        if($total_vat>0){
            if(isset($accountsConfig['vat_accounts']) && !empty($accountsConfig['vat_accounts'])){
                $vat_accounts = $accountsConfig['vat_accounts']; 
                $chartOfCode = ChartOfAccount::where($acc_code_col, $vat_accounts->particular_code)->first();
                if(!empty($chartOfCode)){
                    if($chartOfCode->account_type=='ledger' && $vat_accounts->account_type== 'ledger'){
                        $vat_acc_id = $vat_accounts->particular_id;
                        array_push($voucherDebitData,['sub_ledger'=>$vat_acc_id, 'debit_amount'=>$total_vat]); 
                    }else{
                        $status = false;
                        $msg[] = "VAT account should be ledger type code";
                    } 
                }else{
                    $status = false;
                    $msg[] = "Invalid VAT account ledger code";
                }
            }else{
                $status = false;
                $msg[] = "VAT account ledger code did not configure";
            }
        }

        return ([
            'status'=>$status,
            'msg'=>$msg,
            'voucherDebitData'=>$voucherDebitData,
            'voucherCreditData'=>$voucherCreditData,
        ]);        

    } 

    public function invoiceAutoVoucher($approvalData, $autoVoucherLedger, $auth_user_id, $step){
        $voucherDebitData = $autoVoucherLedger['voucherDebitData'];
        $voucherCreditData = $autoVoucherLedger['voucherCreditData'];
        $dr_ledger_ids = collect($voucherDebitData)->pluck('sub_ledger')->all();
        $cr_ledger_ids = collect($voucherCreditData)->pluck('sub_ledger')->all();
        $merge_ledger_ids = collect($dr_ledger_ids)->merge($cr_ledger_ids)->all();
        $ledger_ids = collect($merge_ledger_ids)->unique()->values()->all();
        $chartOfAccounts = ChartOfAccount::whereIn('id',$ledger_ids)->get()->keyBy('id')->all();

        $trans_date = $approvalData->date;
        $trans_date_time = $approvalData->created_at;
        $save = AccountsTransactionModel::create([
            'voucher_no'=>$approvalData->voucher_no,
            'voucher_int_no'=>$approvalData->voucher_int,
            'trans_date'=>$trans_date,
            'trans_date_time'=>$trans_date_time,
            'amount'=>$approvalData->grand_total_amount,
            'voucher_from'=>"Purchase Invoice",
            'voucher_type'=>3,
            'status'=>1,
            'remarks'=>$approvalData->comments
        ]);
        $acc_transaction_id = $save->id;

        // debit data entry
        foreach($voucherDebitData as $v){
            SubsidiaryCalculationModel::create([
                'particular_sub_ledger_id'=>NULL,
                'trans_date'=>$trans_date,
                'trans_date_time'=>$trans_date_time,
                'sub_ledger'=>$v['sub_ledger'],
                'increase_by'=>$chartOfAccounts[$v['sub_ledger']]->increase_by,
                'debit_amount'=>$v['debit_amount'],
                'credit_amount'=>0,
                'transaction_id'=>$acc_transaction_id,
                'tras_ledger_type'=>'debit',
                'status'=>1,
                'remarks'=>''
            ]);
        }
        // credit data entry
        foreach($voucherCreditData as $v){
            SubsidiaryCalculationModel::create([
                'particular_sub_ledger_id'=>NULL,
                'trans_date'=>$trans_date,
                'trans_date_time'=>$trans_date_time,
                'sub_ledger'=>$v['sub_ledger'],
                'increase_by'=>$chartOfAccounts[$v['sub_ledger']]->increase_by,
                'debit_amount'=>0,
                'credit_amount'=>$v['credit_amount'],
                'transaction_id'=>$acc_transaction_id,
                'tras_ledger_type'=>'credit',
                'status'=>1,
                'remarks'=>''
            ]);
        }

        // voucher approval log 
        AccountsVoucherApprovalLogModel::create([
            'date'=>date('Y-m-d H:i:s'),
            'voucher_id'=>$acc_transaction_id,
            'voucher_type'=>3,
            'approval_id'=>$auth_user_id,
            'approval_layer'=>$step,
            'action_status'=>1,
            'institute_id'=>self::getInstituteId(),
            'campus_id'=>self::getCampusId(),
        ]);

    }

    public function refMasterVoucherUpdate($purchaseInvoiceDetails){
        $reference_ids = $purchaseInvoiceDetails->pluck('reference_id')->all();
        $reference_unique_ids = collect($reference_ids)->unique()->values()->all();
        foreach($reference_unique_ids as $reference_id){
            // update reference table
            $checkPartialRefUse = PurchaseReceiveDetailsModel::module()->valid()
                ->where('pur_receive_id',$reference_id)
                ->where(function($query){
                    $query->where('ref_use', 0)
                        ->orWhere('ref_use',1);

                })->first();
            PurchaseReceiveInfoModel::module()->valid()->find($reference_id)->update(['ref_use'=> (!empty($checkPartialRefUse))?1:3]);
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
                $deleteData = PurchaseInvoiceModel::module()->find($id);
                if(!empty($deleteData)){
                    if($deleteData->status==1||$deleteData->status==2){  // check status
                        $output = ['status'=>0, 'message'=>'Sorry! purchase invoice item already approved'];
                    }else{
                        $purchaseInvoiceDetails  = PurchaseInvoiceDetailsModel::module()->where('pur_invoice_id', $id)->get();
                        foreach($purchaseInvoiceDetails as $v){
                            PurchaseInvoiceDetailsModel::find($v->id)->delete();
                        }
                        $deleteData->delete();
                        $output = ['status'=>1, 'message'=>'Purchase invoice successfully deleted'];
                        DB::commit();
                    }
                }else{
                    $output = ['status'=>0, 'message'=>'Invoice not found'];
                }
            }else{
                $delIds = $request->delIds;
                $allDeleteData = PurchaseInvoiceModel::module()->whereIn('id', $delIds)->get();
                // status check
                foreach ($allDeleteData as $deleteData){
                    if($deleteData->status==1||$deleteData->status==2){
                        $flag = false;
                        $msg[] = $deleteData->voucher_no.' voucher has  approval';
                    }
                }
                if($flag){
                    $detailsData = PurchaseInvoiceDetailsModel::module()->whereIn('pur_invoice_id', $delIds)->get();
                    foreach($delIds as $delId){
                        PurchaseInvoiceModel::find($delId)->delete();
                    }
                    // details data delete
                    foreach($detailsData as $v){
                        PurchaseInvoiceDetailsModel::find($v->id)->delete();
                    }
                    $output = ['status'=>1, 'message'=>'Purchase invoice successfully deleted'];
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

<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\PurchaseOrderInfoModel;
use Modules\Inventory\Entities\PurchaseOrderDetailsModel;
use Modules\Inventory\Entities\ComparativeStatementInfoModel;
use Modules\Inventory\Entities\ComparativeStatementDetailsModel;
use Modules\Inventory\Entities\PurchaseRequisitionInfoModel;
use Modules\Inventory\Entities\PurchaseRequisitionDetailsModel;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\UserVoucherApprovalLayerModel;
use Modules\Inventory\Entities\VoucherApprovalLogModel;
use Modules\Inventory\Entities\PriceCatelogueDetailsModel;
use Modules\Inventory\Entities\VendorModel;
use Modules\Setting\Entities\Campus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\InventoryHelper;
use App\User;
use Illuminate\Validation\Rule;
use DateTime;

class PurchaseOrderController extends Controller
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
        if($sort=='id') $sort='inventory_purchase_order_details.id';

        $item_list = self::itemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $data['item_list'] = self::mergeEmtyArryObj($item_list, ['item_id'=>0, 'product_name'=>'Select item']);
        $voucher_list = PurchaseOrderDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_purchase_order_info', 'inventory_purchase_order_info.id','=', 'inventory_purchase_order_details.pur_id')
            ->select('inventory_purchase_order_details.pur_id as id', 'inventory_purchase_order_info.voucher_no')
            ->orderBy('voucher_int', 'desc')
            ->groupBy(['pur_id','voucher_no'])
            ->get();

        $data['voucher_list'] =  self::mergeEmtyArryObj($voucher_list, ['id'=>0, 'voucher_no'=>'Select voucher']);

        $paginate_data_query = PurchaseOrderDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_purchase_order_info', 'inventory_purchase_order_info.id','=', 'inventory_purchase_order_details.pur_id')
            ->join('inventory_vendor_info', 'inventory_vendor_info.id','=', 'inventory_purchase_order_info.vendor_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_order_details.item_id')
            ->select('inventory_purchase_order_details.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS pur_date"), 'inventory_purchase_order_info.voucher_no', 'cadet_stock_products.product_name', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'inventory_vendor_info.name as vendor_name')
            ->when($item_id, function($query, $item_id){
                $query->where('inventory_purchase_order_details.item_id',$item_id);
            })
            ->when($voucher_id, function($query, $voucher_id){
                $query->where('inventory_purchase_order_details.pur_id',$voucher_id);
            })
            ->when($from_date, function($query, $from_date){
                $query->where('inventory_purchase_order_info.date','>=',$from_date);
            })
            ->when($to_date, function($query, $to_date){
                $query->where('inventory_purchase_order_info.date','<=',$to_date);
            })
            ->when($status, function($query, $status){
                if($status=='p') $status=0;
                $query->where('inventory_purchase_order_details.status',$status);
            })
            ->where(function($query)use($search_key){
                if(!empty($search_key)){
                    $query->where('inventory_purchase_order_info.voucher_no','LIKE','%'.$search_key.'%')
                        ->orWhere('cadet_stock_products.product_name','LIKE','%'.$search_key.'%');
                }
            })
            ->orderBy($sort,$order);     

        $paginate_data = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);
        if(count($paginate_data)>0){
            $auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', 'purchase_order')->where('is_role', 0)->get();
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
                ->where('voucher_type', 'purchase_order')
                ->where('is_role', 0)
                ->whereIn('voucher_details_id', $voucher_details_ids)
                ->orderBy('inventory_voucher_approval_log.approval_layer', 'asc')
                ->get()->groupBy('voucher_details_id')->all();
            // check if his step is approval or not
            $approval_step_log = VoucherApprovalLogModel::module()->valid()
                ->where('voucher_type', 'purchase_order')
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
        return view('inventory::purchase.purchaseOrder.purchase-order');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['instruction_user_list'] = User::select('id', 'name')->module()->get();
        $instruction_of_model=User::select('id', 'name')->where('id', Auth::user()->id)->first();
        $instruction_name = $instruction_of_model->name;
        $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
        $campus_id_model=Campus::select('id', 'name')->where('id', self::getCampusId())->first();
        $campus_name = $campus_id_model->name;
        $data['vendor_list'] = VendorModel::select('id','name')->module()->valid()->get();
        $data['formData'] = ['purchase_category'=>'','voucher_no'=>'','voucher_int'=>0,'numbering'=>true, 'date'=>date('Y-m-d'),'date_show'=>date('Y-m-d'),'due_date'=>date('Y-m-d'),'due_date_show'=>date('Y-m-d'),'campus_id_model'=>$campus_id_model,'campus_id'=>self::getCampusId(),'campus_name'=>$campus_name,'vendor_id'=>0,'instruction_of_model'=>$instruction_of_model,'instruction_of'=>Auth::user()->id,'instruction_name'=>$instruction_name,'reference_type'=>'', 'voucherDetailsData'=>[], 'itemAdded'=>'no'];
        return response()->json($data);
    }

    public function getPurchaseVoucherNo(Request $request){
        $purchase_category = $request->purchase_category;
        $voucherInfo = self::getVoucherNo($purchase_category);
        if($voucherInfo['voucher_no']){
            $data = ['voucher_no'=>$voucherInfo['voucher_no'],'voucher_int'=>$voucherInfo['voucher_int']];
        }else{
            $data = ['status'=>0, 'message'=>"Setup voucher configuration first"];
        }
        return response()->json($data);
    }


    public function purchaseReferenceList(Request $request){
        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $campus_id = $request->campus_id;
        $reference_type = $request->reference_type;
        $vendor_id = $request->vendor_id;
        $item_list = self::itemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $refItemList = [];
        if($reference_type=='purchase-requisition'){
            $refItemList = PurchaseRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('inventory_purchase_requisition_info', 'inventory_purchase_requisition_info.id','=', 'inventory_purchase_requisition_details.req_id')
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_requisition_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
                ->select('inventory_purchase_requisition_details.id as reference_details_id','inventory_purchase_requisition_details.req_id as reference_id','inventory_purchase_requisition_details.item_id','inventory_purchase_requisition_details.req_qty','inventory_purchase_requisition_details.app_qty as req_app_qty',DB::raw("DATE_FORMAT(inventory_purchase_requisition_info.date,'%d/%m/%Y') AS req_date, DATE_FORMAT(inventory_purchase_requisition_info.due_date,'%d/%m/%Y') AS due_date"), 'inventory_purchase_requisition_info.voucher_no as ref_voucher_name', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of')
                ->where('inventory_purchase_requisition_info.due_date','<=',$date)
                ->whereIn('inventory_purchase_requisition_details.ref_use',[0,1])
                ->whereIn('inventory_purchase_requisition_details.status',[1,2])
                ->where('inventory_purchase_requisition_details.need_cs',0)
                ->orderBy('inventory_purchase_requisition_info.due_date','asc')
                ->get(); 
            $ref_details_ids = $refItemList->pluck('reference_details_id')->all();
            $ref_item_ids =  $refItemList->pluck('item_id')->all(); 
            $purDataList  = PurchaseOrderDetailsModel::module()->select('reference_details_id',DB::raw('SUM(app_qty) as app_qty'))->whereIn('reference_details_id', $ref_details_ids)->whereIn('status',[1,2])->where('reference_type', 'purchase-requisition')->groupBy('reference_details_id')->get()->keyBy('reference_details_id')->all();
            foreach ($refItemList as $v){
                if(array_key_exists($v->reference_details_id, $purDataList)){
                    $purInfo = $purDataList[$v->reference_details_id];
                    $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                    $avail_qty = self::itemQtySubtraction($itemInfo, $v->req_app_qty,$purInfo->app_qty);

                }else{
                    $avail_qty = $v->req_app_qty;
                }
                $v->avail_qty = round($avail_qty, $v->decimal_point_place);
                $v->pur_qty = round($v->avail_qty, $v->decimal_point_place);
                $v->req_app_qty = round($v->req_app_qty, $v->decimal_point_place);
                $v->ref_check=0;
                $v->vat_type = 'fixed';
            }
        }else if($reference_type=='cs'){
            $refItemList = ComparativeStatementDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('inventory_comparative_statement_info', 'inventory_comparative_statement_info.id','=', 'inventory_comparative_statement_details_info.cs_id')
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_comparative_statement_details_info.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
                ->select('inventory_comparative_statement_details_info.id as reference_details_id','inventory_comparative_statement_details_info.cs_id as reference_id','inventory_comparative_statement_details_info.item_id','inventory_comparative_statement_details_info.rate','inventory_comparative_statement_details_info.amount as total_amount','inventory_comparative_statement_details_info.discount','inventory_comparative_statement_details_info.vat_per','inventory_comparative_statement_details_info.vat_type','inventory_comparative_statement_details_info.vat_amount','inventory_comparative_statement_details_info.net_amount as net_total','inventory_comparative_statement_details_info.qty as req_app_qty',DB::raw("DATE_FORMAT(inventory_comparative_statement_info.date,'%d/%m/%Y') AS cs_date, DATE_FORMAT(inventory_comparative_statement_info.due_date,'%d/%m/%Y') AS due_date"), 'inventory_comparative_statement_info.voucher_no as ref_voucher_name', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of')
                ->where('inventory_comparative_statement_info.due_date','<=',$date)
                ->where('inventory_comparative_statement_info.vendor_id',$vendor_id)
                ->whereIn('inventory_comparative_statement_details_info.ref_use',[0,1])
                ->whereIn('inventory_comparative_statement_details_info.status',[1,2])
                ->orderBy('inventory_comparative_statement_info.due_date','asc')
                ->get(); 
            $ref_details_ids = $refItemList->pluck('reference_details_id')->all();
            $ref_item_ids =  $refItemList->pluck('item_id')->all(); 
            $purDataList  = PurchaseOrderDetailsModel::module()->select('reference_details_id',DB::raw('SUM(app_qty) as app_qty'))->whereIn('reference_details_id', $ref_details_ids)->whereIn('status',[1,2])->where('reference_type', 'cs')->groupBy('reference_details_id')->get()->keyBy('reference_details_id')->all();

            foreach ($refItemList as $v) {
                if(array_key_exists($v->reference_details_id, $purDataList)){
                    $purInfo = $purDataList[$v->reference_details_id];
                    $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                    $avail_qty = self::itemQtySubtraction($itemInfo, $v->req_app_qty,$purInfo->app_qty);
                }else{
                    $avail_qty = $v->req_app_qty;
                }
                $v->avail_qty = round($avail_qty, $v->decimal_point_place);
                $v->pur_qty = round($v->avail_qty, $v->decimal_point_place);
                $v->req_app_qty = round($v->req_app_qty, $v->decimal_point_place);
                $v->rate = round($v->rate,2);
                $v->discount = round($v->discount,2);
                $v->vat_per = round($v->vat_per,2);
                $v->total_amount = round($v->total_amount,2);
                $v->net_total = round($v->net_total,2);
                $v->ref_check=0;
            }

        }
        return response()->json($refItemList);
    }

    public function purchaseOrderPriceCatalogCheck(Request $request){
        $campus_id = $request->campus_id;
        $vendor_id = $request->vendor_id;
        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $refDataList = $request->refDataList;
        $institute_id = self::getInstituteId(); 
        $vendorInfo = VendorModel::module()->valid()->find($vendor_id);
        $voucherDetailsData = [];
        if(!empty($vendorInfo->price_cate_id)){
            $price_cate_id = $vendorInfo->price_cate_id;
            foreach($refDataList as $key => $ref):
                $catalog_data = PriceCatelogueDetailsModel::module()->valid()
                    ->where('catalogue_uniq_id', $price_cate_id)
                    ->where('item_id', $ref['item_id'])
                    ->where('applicable_from', '<=',$date)
                    ->where('from_qty','<=', $ref['avail_qty'])
                    ->where('to_qty','>=', $ref['avail_qty'])
                    ->where('status',1)
                    ->orderBy('applicable_from', 'desc')->first();
                if(!empty($catalog_data)){
                    $ref['rate'] = round($catalog_data->rate, 2);
                    $ref['discount'] = round($catalog_data->discount, 2);
                    $ref['vat_per'] = round($catalog_data->vat_per, 2);
                    $ref['vat_type'] = $catalog_data->vat_type;

                    // price calculation
                    if($ref['has_fraction']==1 && self::isFloat($ref['avail_qty'])){
                        $calInfo = (object)['has_fraction'=>$ref['has_fraction'],'round_of'=>$ref['round_of'],'decimal_point_place'=>$ref['decimal_point_place'],'qty'=>$ref['avail_qty'], 'rate'=>$catalog_data->rate];
                        $amount = self::rateQtyMultiply($calInfo);
                    }else{
                        $amount = $ref['avail_qty'] * $catalog_data->rate;
                    }
                    $net_amount = $amount;
                    if(!empty($catalog_data->vat_per)){
                        $vat_per = $catalog_data->vat_per;
                        if($catalog_data->vat_type=='fixed'){
                            $vat_amount = $catalog_data->vat_per; 
                        }else{
                            $vat_amount_cal = ($amount/100)*$catalog_data->vat_per;
                            $vat_amount = round($vat_amount_cal,6);
                        }
                        $net_amount +=  $vat_amount;
                    }else{
                        $vat_amount = 0;
                    }
                    if(!empty($catalog_data->discount)){
                        $net_amount -=  $catalog_data->discount;
                    }

                    $ref['vat_amount'] = round($vat_amount, 2);
                    $ref['total_amount'] = round($amount, 2);
                    $ref['net_total'] = round($net_amount, 2);

                }else{
                    $ref['rate'] = 0;
                    $ref['discount'] = 0;
                    $ref['vat_per'] = 0;
                    $ref['total_amount'] = 0;
                    $ref['net_total'] = 0;
                    $ref['vat_type'] = '';
                    $ref['vat_amount'] = 0;
                }
                $voucherDetailsData[] = $ref;

            endforeach;
        }else{
            foreach($refDataList as $ref):
                $ref['rate'] = 0;
                $ref['discount'] = 0;
                $ref['vat_per'] = 0;
                $ref['total_amount'] = 0;
                $ref['net_total'] = 0;
                $ref['vat_amount'] = 0;
                $voucherDetailsData[] = $ref;
            endforeach;
        }

        $data['voucherDetailsData'] = $voucherDetailsData;         

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
        $campus_id = self::getCampusId();
        $institute_id = self::getInstituteId();
        $validated = $request->validate([
            'voucher_no' => [
                'required',
                'max:100',
                Rule::unique('inventory_purchase_order_info')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id)->where('valid',1);
                })->ignore($id, 'id')
            ],
            'date' => 'required|date_format:d/m/Y',
            'due_date' => 'required|date_format:d/m/Y|after_or_equal:date',
            'campus_id' => 'required',
            'vendor_id' => 'required',
            'purchase_category' => 'required',
            'reference_type'=>'required',
            'instruction_of'=>'required',
            'comments' => 'required|max:255',
        ]);

        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $due_date = DateTime::createFromFormat('d/m/Y', $request->due_date)->format('Y-m-d');
        $voucherDetailsData = $request->voucherDetailsData;
        if(count($voucherDetailsData)>0){
            if(!empty($id)){
                $voucherDetailsData_db = PurchaseOrderDetailsModel::module()->valid()->where('pur_id', $id)->get();
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
                        if(self::isFloat($v['pur_qty'])){
                            $explodeQty = explode('.', $v['pur_qty']);
                            if(strlen($explodeQty[1])>$itemInfo->decimal_point_place){
                                $flag = false;
                                $msg[] = $itemInfo->product_name.' has allow '.$itemInfo->decimal_point_place.' decimal places'; 
                            }
                        }
                    }else{
                        if(self::isFloat($v['pur_qty'])){
                            $flag = false;
                            $msg[] = $itemInfo->product_name.' has no decimal places'; 
                        }
                    }
                    // item approval check
                    $details_id = @$v['id'];
                    if($details_id>0){
                        $db_data = $voucherDetailsData_db_keyBy[$details_id];
                        if(($db_data->status==1||$db_data->status==2) && $db_data->pur_qty!=$v['pur_qty']){
                            $flag = false;
                            $msg[] = $itemInfo->product_name.' has already approved and can not change qty'; 
                        }
                        // check any of item has approval 
                        if($db_data->status==1||$db_data->status==2){
                            $item_approval=true;
                        }
                    }
                    if($v['pur_qty']>0){
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
                        "purchase_category"=>$request->purchase_category,
                        "voucher_no" => $request->voucher_no,
                        "voucher_int" => $request->voucher_int,
                        "vendor_id" => $request->vendor_id,
                        "instruction_of" => $request->instruction_of,
                        "date" => $date,
                        "due_date" => $due_date,
                        "reference_type" => $request->reference_type,
                        "comments" => $request->comments
                    ];
                    if(!empty($id)){
                        $pur_id = $id;
                        $purInfo = PurchaseOrderInfoModel::module()->valid()->findOrFail($id);
                        if($purInfo->status==0){ // check info status
                            // date change check 
                            if($item_approval && ($purInfo->date!=$date || $purInfo->due_date!=$due_date || $purInfo->purchase_category!=$request->purchase_category || $purInfo->vendor_id!=$request->vendor_id || $purInfo->instruction_of!=$request->instruction_of)){
                                $flag = false; 
                                if($purInfo->date!=$date || $purInfo->due_date!=$due_date){
                                    $msg[] = 'Sorry puchase  details item already approved you can not change date';
                                }else if($purInfo->purchase_category!=$request->purchase_category){
                                    $msg[] = 'Sorry puchase  details item already approved you can not change category';
                                }else if($purInfo->vendor_id!=$request->vendor_id){
                                    $msg[] = 'Sorry puchase  details item already approved you can not change vendor';
                                }else{
                                    $msg[] = 'Sorry puchase  details item already approved you can not change instruction of';
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
                                    $purInfo->update($data); 
                                   // delete details data
                                   foreach($vouDtlIds_diff as $diffId) {
                                        PurchaseOrderDetailsModel::find($diffId)->delete();
                                   }
                                }
                            }
                        }else{
                           $flag = false; 
                           $msg[] = 'Sorry purchase order already approved';
                        }

                    }else{
                        $save = PurchaseOrderInfoModel::create($data);
                        $pur_id = $save->id; 

                    }
                    if($flag){
                        foreach ($voucherDetailsData as $v) {
                            if($v['pur_qty']>0){
                                $details_id = @$v['id'];
                                $detailsData  = [
                                    'pur_id'=>$pur_id,
                                    'item_id'=>$v['item_id'],
                                    'pur_qty'=>$v['pur_qty'],
                                    'reference_type'=>$request->reference_type,
                                    'reference_id'=>$v['reference_id'],
                                    'reference_details_id'=>$v['reference_details_id'],
                                    'rate'=>$v['rate'],
                                    'total_amount'=>$v['total_amount'],
                                    'vat_per'=>$v['vat_per'],
                                    'vat_amount'=>$v['vat_amount'],
                                    'vat_type'=>(!empty($v['vat_type']))?$v['vat_type']:NULL,
                                    'discount'=>$v['discount'],
                                    'net_total'=>$v['net_total'],
                                ];
                                if($details_id>0){
                                    PurchaseOrderDetailsModel::module()->valid()->find($details_id)->update($detailsData);;
                                }else{
                                   PurchaseOrderDetailsModel::create($detailsData);  
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
        $voucherInfo  = PurchaseOrderInfoModel::module()->valid()
            ->join('setting_campus', 'setting_campus.id','=', 'inventory_purchase_order_info.campus_id')
            ->join('inventory_vendor_info', 'inventory_vendor_info.id','=', 'inventory_purchase_order_info.vendor_id')
            ->leftJoin('users', 'inventory_purchase_order_info.instruction_of','=', 'users.id')
            ->select('inventory_purchase_order_info.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS pur_date, DATE_FORMAT(due_date,'%d/%m/%Y') AS due_date_formate"), 'users.name', 'setting_campus.name as campus_name', 'inventory_vendor_info.name as vendor_name')
            ->where('inventory_purchase_order_info.id', $id)
            ->first();
        if(!empty($voucherInfo)){
            $voucherDetailQuery = PurchaseOrderDetailsModel::module()->itemAccess($item_ids)->valid();
            if($voucherInfo->reference_type=='cs'){
                $refSelect = DB::raw('inventory_comparative_statement_info.voucher_no as ref_voucher_name');
                $voucherDetailQuery->join('inventory_comparative_statement_info','inventory_comparative_statement_info.id', '=', 'inventory_purchase_order_details.reference_id');
            }else{
                $refSelect = DB::raw('inventory_purchase_requisition_info.voucher_no as ref_voucher_name');
                $voucherDetailQuery->join('inventory_purchase_requisition_info','inventory_purchase_requisition_info.id', '=', 'inventory_purchase_order_details.reference_id');
            }
            $voucherDetailQuery->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_order_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->select('inventory_purchase_order_details.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'),'cadet_stock_products.sku', $refSelect)
                ->where('inventory_purchase_order_details.pur_id', $id); 
            $voucherDetailsData = $voucherDetailQuery->get();
            $voucherInfo->voucherDetailsData = $voucherDetailsData;
            $data['formData'] = $voucherInfo;
            $data['refItemList'] = [];
        }else{
            $data = ['status'=>0, 'message'=>"Purchase order Voucher not found"];
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
        $purchaseOrderInfo  = PurchaseOrderInfoModel::module()->valid()->find($id);            
        $purchase_date = $purchaseOrderInfo->date; 
        $purchase_due_date = $purchaseOrderInfo->due_date; 
        $date = DateTime::createFromFormat('Y-m-d', $purchaseOrderInfo->date)->format('d/m/Y');
        $due_date = DateTime::createFromFormat('Y-m-d', $purchaseOrderInfo->due_date)->format('d/m/Y');
        $purchaseOrderInfo->date = $date;
        $purchaseOrderInfo->date_show = $purchase_date;
        $purchaseOrderInfo->numbering = true;
        $purchaseOrderInfo->due_date = $due_date;
        $purchaseOrderInfo->due_date_show = $purchase_due_date;
        $data['instruction_user_list'] = User::select('id', 'name')->module()->get();
        $instruction_of_model=User::select('id', 'name')->where('id', $purchaseOrderInfo->instruction_of)->first();
        $instruction_name = $instruction_of_model->name;
        $purchaseOrderInfo->instruction_of_model = $instruction_of_model;
        $purchaseOrderInfo->instruction_name = $instruction_name;

        $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
        $campus_id_model=Campus::select('id', 'name')->where('id', $purchaseOrderInfo->campus_id)->first();
        $purchaseOrderInfo->campus_name = $campus_id_model->name;
        $purchaseOrderInfo->campus_id_model = $campus_id_model;

        $data['vendor_list'] = VendorModel::select('id','name')->module()->valid()->get();
        $vendor_id_model = VendorModel::select('id', 'name')->module()->valid()->find($purchaseOrderInfo->vendor_id);
        $vendor_name = $vendor_id_model->name;
        $purchaseOrderInfo->vendor_id_model = $vendor_id_model;
        $purchaseOrderInfo->vendor_name = $vendor_name;
        $reference_type = $purchaseOrderInfo->reference_type;
        
        $item_list = self::itemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        // voucher details data
        $voucherDetailQuery = PurchaseOrderDetailsModel::module()->valid();
        if($reference_type=='cs'){
            $refSelect = DB::raw('inventory_comparative_statement_details_info.qty as req_app_qty, inventory_comparative_statement_info.voucher_no as ref_voucher_name');
            $voucherDetailQuery->join('inventory_comparative_statement_info','inventory_comparative_statement_info.id', '=', 'inventory_purchase_order_details.reference_id')
                ->join('inventory_comparative_statement_details_info','inventory_comparative_statement_details_info.id', '=', 'inventory_purchase_order_details.reference_details_id');
        }else{
            $refSelect = DB::raw('inventory_purchase_requisition_details.app_qty as req_app_qty, inventory_purchase_requisition_info.voucher_no as ref_voucher_name');
            $voucherDetailQuery->join('inventory_purchase_requisition_info','inventory_purchase_requisition_info.id', '=', 'inventory_purchase_order_details.reference_id')
                ->join('inventory_purchase_requisition_details','inventory_purchase_requisition_details.id', '=', 'inventory_purchase_order_details.reference_details_id')  ;
        }
        $voucherDetailQuery->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_order_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
            ->select('inventory_purchase_order_details.*', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of', $refSelect)
            ->where('inventory_purchase_order_details.pur_id', $id)
            ->whereIn('inventory_purchase_order_details.item_id', $item_ids);
        $voucherDetailsData = $voucherDetailQuery->get();
        $voucher_ref_details_ids = $voucherDetailsData->pluck('reference_details_id')->all();
        $voucher_details_item_ids = $voucherDetailsData->pluck('item_id')->all();
        
        // voucher reference data
        if($reference_type=='purchase-requisition'){
            $refItemList = PurchaseRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('inventory_purchase_requisition_info', 'inventory_purchase_requisition_info.id','=', 'inventory_purchase_requisition_details.req_id')
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_requisition_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
                ->select('inventory_purchase_requisition_details.id as reference_details_id','inventory_purchase_requisition_details.req_id as reference_id','inventory_purchase_requisition_details.item_id','inventory_purchase_requisition_details.req_qty','inventory_purchase_requisition_details.app_qty as req_app_qty',DB::raw("DATE_FORMAT(inventory_purchase_requisition_info.date,'%d/%m/%Y') AS req_date, DATE_FORMAT(inventory_purchase_requisition_info.due_date,'%d/%m/%Y') AS due_date"), 'inventory_purchase_requisition_info.voucher_no as ref_voucher_name', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of')
                ->where('inventory_purchase_requisition_info.due_date','<=',$purchase_date)
                ->whereIn('inventory_purchase_requisition_details.ref_use',[0,1])
                ->whereIn('inventory_purchase_requisition_details.status',[1,2])
                ->where('inventory_purchase_requisition_details.need_cs',0)
                ->orderBy('inventory_purchase_requisition_info.due_date','asc')
                ->get(); 

        }else{
            $refItemList = ComparativeStatementDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('inventory_comparative_statement_info', 'inventory_comparative_statement_info.id','=', 'inventory_comparative_statement_details_info.cs_id')
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_comparative_statement_details_info.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
                ->select('inventory_comparative_statement_details_info.id as reference_details_id','inventory_comparative_statement_details_info.cs_id as reference_id','inventory_comparative_statement_details_info.item_id','inventory_comparative_statement_details_info.rate','inventory_comparative_statement_details_info.amount as total_amount','inventory_comparative_statement_details_info.discount','inventory_comparative_statement_details_info.vat_per','inventory_comparative_statement_details_info.vat_type','inventory_comparative_statement_details_info.vat_amount','inventory_comparative_statement_details_info.net_amount as net_total','inventory_comparative_statement_details_info.qty as req_app_qty',DB::raw("DATE_FORMAT(inventory_comparative_statement_info.date,'%d/%m/%Y') AS cs_date, DATE_FORMAT(inventory_comparative_statement_info.due_date,'%d/%m/%Y') AS due_date"), 'inventory_comparative_statement_info.voucher_no as ref_voucher_name', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of')
                ->where('inventory_comparative_statement_info.due_date','<=',$purchase_date)
                ->where('inventory_comparative_statement_info.vendor_id',$purchaseOrderInfo->vendor_id)
                ->whereIn('inventory_comparative_statement_details_info.ref_use',[0,1])
                ->whereIn('inventory_comparative_statement_details_info.status',[1,2])
                ->orderBy('inventory_comparative_statement_info.due_date','asc')
                ->get();
        }
        
        $ref_details_ids = $refItemList->pluck('reference_details_id')->all();
        $ref_item_ids =  $refItemList->pluck('item_id')->all(); 
        $merge_ref_details_ids  = collect($voucher_ref_details_ids)->merge($ref_details_ids)->all();
        $merge_ref_item_ids  = collect($voucher_details_item_ids)->merge($ref_item_ids)->all();
        $merge_un_ref_details_ids = collect($merge_ref_details_ids)->unique()->values()->all();
        $merge_un_ref_item_ids = collect($merge_ref_item_ids)->unique()->values()->all();

        $purDataList  = PurchaseOrderDetailsModel::module()->select('reference_details_id',DB::raw('SUM(app_qty) as app_qty'))->whereIn('reference_details_id', $merge_un_ref_details_ids)->whereIn('status',[1,2])->where('reference_type', $reference_type)->groupBy('reference_details_id')->get()->keyBy('reference_details_id')->all();   

        if($reference_type=='cs'){
            foreach ($refItemList as $v) {
                if(array_key_exists($v->reference_details_id, $purDataList)){
                    $purInfo = $purDataList[$v->reference_details_id];
                    $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                    $avail_qty = self::itemQtySubtraction($itemInfo, $v->req_app_qty,$purInfo->app_qty);

                }else{
                    $avail_qty = $v->req_app_qty;
                }
                $v->avail_qty = round($avail_qty, $v->decimal_point_place);
                $v->pur_qty = round($v->avail_qty, $v->decimal_point_place);
                $v->req_app_qty = round($v->req_app_qty, $v->decimal_point_place);
                $v->rate = round($v->rate,2);
                $v->discount = round($v->discount,2);
                $v->vat_per = round($v->vat_per,2);
                $v->total_amount = round($v->total_amount,2);
                $v->net_total = round($v->net_total,2);
                $v->ref_check=0;
            }
        }else{
            foreach ($refItemList as $v) {
                if(array_key_exists($v->reference_details_id, $purDataList)){
                    $purInfo = $purDataList[$v->reference_details_id];
                    $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                    $avail_qty = self::itemQtySubtraction($itemInfo, $v->req_app_qty,$purInfo->app_qty);
                }else{
                    $avail_qty = $v->req_app_qty;
                }
                $v->avail_qty = round($avail_qty, $v->decimal_point_place);
                $v->pur_qty = round($v->avail_qty, $v->decimal_point_place);
                $v->req_app_qty = round($v->req_app_qty, $v->decimal_point_place);
                $v->ref_check=0;
            }
        } 
        foreach($voucherDetailsData as $v){
            if(array_key_exists($v->reference_details_id, $purDataList)){
                $purInfo = $purDataList[$v->reference_details_id];
                $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                $avail_qty = self::itemQtySubtraction($itemInfo, $v->req_app_qty,$purInfo->app_qty);
            }else{
                $v->avail_qty = round($v->req_app_qty, $v->decimal_point_place);
            }
            $v->pur_qty = round($v->pur_qty, $v->decimal_point_place);
            $v->rate = round($v->rate, 2);
            $v->total_amount = round($v->total_amount, 2);
            $v->vat_per = round($v->vat_per, 2);
            $v->vat_amount = round($v->vat_amount, 2);
            $v->discount = round($v->discount, 2);
            $v->net_total = round($v->net_total, 2);
        }

        $purchaseOrderInfo->voucherDetailsData = $voucherDetailsData;
        $purchaseOrderInfo->itemAdded = (count($voucherDetailsData)>0)?'yes':'no';
        $data['refItemList'] = $refItemList;
        $data['formData'] = $purchaseOrderInfo; 
        return response()->json($data);
    }

    public function voucherApproval(Request $request, $id=0)
    {
        DB::beginTransaction();
        try{
            $auth_user_id = Auth::user()->id;
            if($id>0){
                $approvalData = PurchaseOrderDetailsModel::module()->valid()->find($id);
                if(!empty($approvalData)){
                    if($approvalData->status==0){
                        $approval_info = self::getApprovalInfo('purchase_order');
                        $step = $approval_info['step'];
                        $approval_access = $approval_info['approval_access'];
                        $last_step = $approval_info['last_step'];
                        if($approval_access && $approvalData->approval_level==$step){
                            $flag=true;
                            if($step==$last_step){
                                $itemInfo = CadetInventoryProduct::module()->find($approvalData->item_id);
                                if(!empty($itemInfo)){
                                    if($approvalData->reference_type=='cs'){
                                        $comparativeStatementDetailsInfo = ComparativeStatementDetailsModel::module()->valid()->where('id', $approvalData->reference_details_id)->whereIn('ref_use',[0,1])->first();
                                        if(!empty($comparativeStatementDetailsInfo)){ // update ref use
                                            $purReferenceSum  = PurchaseOrderDetailsModel::module()->where('reference_details_id', $comparativeStatementDetailsInfo->id)->whereIn('status',[1,2])->where('reference_type', 'cs')->sum('app_qty');

                                            if($purReferenceSum>0){
                                                $avail_qty = self::itemQtySubtraction($itemInfo, $comparativeStatementDetailsInfo->qty,$purReferenceSum);
                                            }else{
                                                $avail_qty = $comparativeStatementDetailsInfo->qty;
                                            }
                                            if($approvalData->pur_qty<=$avail_qty){
                                                ComparativeStatementDetailsModel::module()->valid()->find($approvalData->reference_details_id)->update(['ref_use'=>($avail_qty>$approvalData->pur_qty)?1:3]);
                                            }else{
                                                $flag=false;
                                                $output = ['status'=>0, 'message'=>'Insufficient Comparative statement reference qty'];  
                                            }
                                        }else{
                                            $flag=false;
                                            $output = ['status'=>0, 'message'=>'Comparative statement reference already used']; 
                                        }
                                    }else{
                                        $purchaseRequisitionDetailsInfo = PurchaseRequisitionDetailsModel::module()->valid()->where('id', $approvalData->reference_details_id)->whereIn('ref_use',[0,1])->first();
                                        //dd($purchaseRequisitionDetailsInfo);
                                        if(!empty($purchaseRequisitionDetailsInfo)){ // update ref use

                                            $purReferenceSum  = PurchaseOrderDetailsModel::module()->where('reference_details_id', $purchaseRequisitionDetailsInfo->id)->whereIn('status',[1,2])->where('reference_type', 'purchase-requisition')->sum('app_qty');

                                            if($purReferenceSum>0){
                                                $avail_qty = self::itemQtySubtraction($itemInfo, $purchaseRequisitionDetailsInfo->app_qty,$purReferenceSum);
                                            }else{
                                                $avail_qty = $purchaseRequisitionDetailsInfo->app_qty;
                                            }
                                            if($approvalData->pur_qty<=$avail_qty){
                                                PurchaseRequisitionDetailsModel::module()->valid()->find($approvalData->reference_details_id)->update(['ref_use'=>($avail_qty>$approvalData->pur_qty)?1:3]);
                                            }else{
                                                $flag=false;
                                                $output = ['status'=>0, 'message'=>'Insufficient Purchase requisition qty'];  
                                            }
                                        }else{
                                            $flag=false;
                                            $output = ['status'=>0, 'message'=>'Purchase requisition reference already used']; 
                                        }

                                    }
                                    if($flag){
                                        $approvalData->update([
                                            'status'=>1,
                                            'app_qty'=>$approvalData->pur_qty,
                                            'approval_level'=>$step+1
                                        ]);
                                    }
                                }else{
                                   $flag=false;
                                   $output = ['status'=>0, 'message'=>'Purchase order  Item not found']; 
                                }

                            }else{ // end if($step==$last_step){
                                $approvalData->update([
                                    'approval_level'=>$step+1
                                ]); 
                            }
                            if($flag){
                                VoucherApprovalLogModel::create([
                                    'date'=>date('Y-m-d H:i:s'),
                                    'voucher_id'=>$approvalData->pur_id,
                                    'voucher_details_id'=>$approvalData->id,
                                    'voucher_type'=>'purchase_order',
                                    'approval_id'=>$auth_user_id,
                                    'approval_layer'=>$step,
                                    'action_status'=>1,
                                    'institute_id'=>self::getInstituteId(),
                                    'campus_id'=>self::getCampusId(),
                                ]);
                                // update master status base on all app
                                self::masterVoucherUpdate($approvalData);
                                DB::commit();
                                $output = ['status'=>1, 'message'=>'Stock in item successfully approved'];
                            }
                        }else{ // end if($approval_access && $approvalData->approval_level==$step){
                            $output = ['status'=>0, 'message'=>'Sory you have no approval'];    
                        }
                    }else{ // end if($approvalData->status==0)
                        if($approvalData->status==3){
                            $output = ['status'=>0, 'message'=>'Comparative Statement Item reject'];
                        }else{
                            $output = ['status'=>0, 'message'=>'Comparative Statement Item already approved'];  
                        }
                    }
 
                }else{   // end if(!empty($approvalData))
                    $output = ['status'=>0, 'message'=>'Comparative Statement Item not found'];
                }
            }
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        } 

        return response()->json($output); 
    }

    public function masterVoucherUpdate($approvalData){
        $pur_id = $approvalData->pur_id;
        $reference_id = $approvalData->reference_id;
        $checkPendingStatus = PurchaseOrderDetailsModel::module()->valid()
            ->where('pur_id', $pur_id)
            ->where(function($query){
                $query->where('status', 0)
                     ->orWhere('status',3);

            })->first();
        PurchaseOrderInfoModel::module()->valid()->find($pur_id)->update([
            'status'=>(!empty($checkPendingStatus))?2:1
        ]);
        // update reference table
        if($approvalData->reference_type=='cs'){
            $checkPartialCSUse = ComparativeStatementDetailsModel::module()->valid()
                ->where('cs_id',$reference_id)
                ->where(function($query){
                    $query->where('ref_use', 0)
                         ->orWhere('ref_use',1);

                })->first();
            
            ComparativeStatementInfoModel::module()->valid()->find($reference_id)->update(['ref_use'=> (!empty($checkPartialCSUse))?1:3]);
        }else{
            $checkPartialReqUse = PurchaseRequisitionDetailsModel::module()->valid()
                ->where('req_id',$reference_id)
                ->where('need_cs',0)
                ->where(function($query){
                    $query->where('ref_use', 0)
                        ->orWhere('ref_use',1);

                })->first();
            PurchaseRequisitionInfoModel::module()->valid()->find($reference_id)->update(['ref_use'=> (!empty($checkPartialReqUse))?1:3]);
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
                $deleteData = PurchaseOrderDetailsModel::module()->valid()->find($id);
                if(!empty($deleteData)){
                    if($deleteData->status==1||$deleteData->status==2){  // check status
                        $output = ['status'=>0, 'message'=>'Sorry! purchase item already approved'];
                    }else{
                        $pur_id = $deleteData->pur_id; 
                        PurchaseOrderDetailsModel::module()->valid()->find($id)->delete();
                        $checkDetailsItem = PurchaseOrderDetailsModel::module()->valid()->where('pur_id', $pur_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            PurchaseOrderInfoModel::module()->valid()->find($pur_id)->delete(); 
                        }
                        $output = ['status'=>1, 'message'=>'Purchase order successfully deleted'];
                        DB::commit();
                    }
                }else{
                    $output = ['status'=>0, 'message'=>'Item not found'];
                }
            }else{
                $delIds = $request->delIds;
                // status check
                $pur_ids = []; $flag = true; $msg =[]; 
                foreach ($delIds as $del_id){
                    $deleteData = PurchaseOrderDetailsModel::module()->valid()->find($del_id);
                    if($deleteData->status==1||$deleteData->status==2){
                        $itemInfo = CadetInventoryProduct::module()->find($deleteData->item_id);
                        $flag = false;
                        $msg[] = $itemInfo->product_name.' has purchase order qty approval';
                    }
                    $pur_ids[] = $deleteData->pur_id;
                }
                if($flag){
                    foreach ($delIds as $del_id){
                        PurchaseOrderDetailsModel::valid()->find($del_id)->delete();
                    }
                    $pur_unique_ids = collect($pur_ids)->unique()->values()->all();
                    foreach ($pur_unique_ids as $pur_id) {
                        $checkDetailsItem = PurchaseOrderDetailsModel::module()->valid()->where('pur_id', $pur_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            PurchaseOrderInfoModel::module()->valid()->find($pur_id)->delete(); 
                        }
                    }
                    $output = ['status'=>1, 'message'=>'Purchase order successfully deleted'];
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

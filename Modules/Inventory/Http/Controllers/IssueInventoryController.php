<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\IssueFromInventoryModel;
use Modules\Inventory\Entities\IssueFromInventoryDetailsModel;
use Modules\Inventory\Entities\IssueSerialDetailsModel;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\StoreWiseItemModel;
use Modules\Inventory\Entities\InventoryStore;
use Modules\Setting\Entities\Campus;
use Modules\Inventory\Entities\UserVoucherApprovalLayerModel;
use Modules\Inventory\Entities\VoucherApprovalLogModel;
use Modules\Inventory\Entities\AllStockOutModel;
use Modules\Inventory\Entities\NewRequisitionInfoModel;
use Modules\Inventory\Entities\NewRequisitionDetailsModel;
use Modules\Inventory\Entities\StockItemSerialDetailsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\InventoryHelper;
use App\User;
use App\UserInfo;
use Illuminate\Validation\Rule;
use DateTime;

class IssueInventoryController extends Controller
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
        if($sort=='id') $sort='inventory_issue_details.id';

        $item_list = self::itemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $data['item_list'] = self::mergeEmtyArryObj($item_list, ['item_id'=>0, 'product_name'=>'Select item']);
        $voucher_list = IssueFromInventoryDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_issue_from', 'inventory_issue_from.id','=', 'inventory_issue_details.issue_id')
            ->select('inventory_issue_details.issue_id as id', 'inventory_issue_from.voucher_no')
            ->orderBy('voucher_int', 'desc')
            ->groupBy(['issue_id','voucher_no'])
            ->get();

        $data['voucher_list'] =  self::mergeEmtyArryObj($voucher_list, ['id'=>0, 'voucher_no'=>'Select voucher']);

        $paginate_data_query = IssueFromInventoryDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_issue_from', 'inventory_issue_from.id','=', 'inventory_issue_details.issue_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_issue_details.item_id')
            ->join('cadet_inventory_store', 'cadet_inventory_store.id','=', 'inventory_issue_from.store_id')
            ->select('inventory_issue_details.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS issue_date"), 'inventory_issue_from.voucher_no', 'cadet_stock_products.product_name', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_store.store_name')
            ->when($item_id, function($query, $item_id){
                $query->where('inventory_issue_details.item_id',$item_id);
            })
            ->when($voucher_id, function($query, $voucher_id){
                $query->where('inventory_issue_details.issue_id',$voucher_id);
            })
            ->when($from_date, function($query, $from_date){
                $query->where('inventory_issue_from.date','>=',$from_date);
            })
            ->when($to_date, function($query, $to_date){
                $query->where('inventory_issue_from.date','<=',$to_date);
            })
            ->when($status, function($query, $status){
                if($status=='p') $status=0;
                $query->where('inventory_issue_details.status',$status);
            })
            ->where(function($query)use($search_key){
                if(!empty($search_key)){
                    $query->where('inventory_issue_from.voucher_no','LIKE','%'.$search_key.'%')
                        ->orWhere('cadet_stock_products.product_name','LIKE','%'.$search_key.'%');
                }
            })        
            ->orderBy($sort,$order);     

        $paginate_data = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);
        if(count($paginate_data)>0){
            $auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', 'issue_from')->where('is_role', 0)->get();
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
                ->where('voucher_type', 'issue_from')
                ->where('is_role', 0)
                ->whereIn('voucher_details_id', $voucher_details_ids)
                ->orderBy('inventory_voucher_approval_log.approval_layer', 'asc')
                ->get()->groupBy('voucher_details_id')->all();
            // check if his step is approval or not
            $approval_step_log = VoucherApprovalLogModel::module()->valid()
                ->where('voucher_type', 'issue_from')
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


    public function page()
    {
        return view('inventory::issueInventory.issue-inventory');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        $voucherInfo = self::checkInvVoucher(2);
        if($voucherInfo['voucher_conf']){
            $data['issue_user_list'] = self::getUserList();
            $issue_to_model='';
            $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
            $campus_id_model=Campus::select('id', 'name')->where('id', self::getCampusId())->first();
            $campus_name = $campus_id_model->name;
            $data['store_list'] = InventoryStore::select('id','store_name')->access($this)->get();
            $data['formData'] = ['voucher_no'=>$voucherInfo['voucher_no'],'voucher_config_id'=>$voucherInfo['voucher_config_id'],'auto_voucher'=>$voucherInfo['auto_voucher'], 'date'=>date('d/m/Y'),'campus_id_model'=>$campus_id_model,'campus_id'=>self::getCampusId(),'campus_name'=>$campus_name,'store_id'=>0,'issue_to_model'=>$issue_to_model,'issue_to'=>0,'reference_type'=>'', 'voucherDetailsData'=>[], 'itemAdded'=>'no'];
            $data['store_item_list'] = [];
        }else{
            $data = ['status'=>0, 'message'=>"Setup voucher configuration first"];
        }
        return response()->json($data);

       // return view('inventory::issueInventory.modal.add-new-issue-inventory');
    }

    public function issueReferenceList(Request $request){
        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $campus_id = $request->campus_id;
        $store_id = $request->store_id;
        $issue_to = $request->issue_to;
        $item_list = self::storeWiseItemList($store_id);
        $item_ids = $item_list->pluck('item_id')->all();
        $refItemList = NewRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_new_requisition_info', 'inventory_new_requisition_info.id','=', 'inventory_new_requisition_details.new_req_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_new_requisition_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
            ->select('inventory_new_requisition_details.id as reference_details_id','inventory_new_requisition_details.new_req_id as reference_id','inventory_new_requisition_details.item_id','inventory_new_requisition_details.req_qty','inventory_new_requisition_details.app_qty as req_app_qty','inventory_new_requisition_details.remarks',DB::raw("DATE_FORMAT(inventory_new_requisition_info.date,'%d/%m/%Y') AS req_date, DATE_FORMAT(inventory_new_requisition_info.due_date,'%d/%m/%Y') AS due_date"), 'inventory_new_requisition_info.voucher_no as ref_voucher_name', 'cadet_stock_products.product_name', 'cadet_stock_products.sku','cadet_stock_products.use_serial', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of')
            ->where('inventory_new_requisition_info.due_date','<=',$date)
            ->where('inventory_new_requisition_info.requisition_by',$issue_to)
            ->whereIn('inventory_new_requisition_details.status',[1,2,5])
            ->orderBy('inventory_new_requisition_info.due_date','asc')
            ->get(); 
        $ref_details_ids = $refItemList->pluck('reference_details_id')->all();
        $ref_item_ids =  $refItemList->pluck('item_id')->all(); 
        $storeWiseItemInfo  = StoreWiseItemModel::select('item_id', DB::raw('ifnull(inventory_store_wise_item.current_stock, 0) as current_stock'))->module()->valid()->where('store_id', $store_id)->whereIn('item_id', $ref_item_ids)->get()->keyBy('item_id')->all();
        $issueDataList  = IssueFromInventoryDetailsModel::module()->select('reference_details_id',DB::raw('SUM(app_qty) as app_qty'))->whereIn('reference_details_id', $ref_details_ids)->whereIn('status',[1,2])->where('reference_type', 'requisition')->groupBy('reference_details_id')->get()->keyBy('reference_details_id')->all();
        foreach ($refItemList as $v) {
            if(array_key_exists($v->item_id, $storeWiseItemInfo)){
                $v->current_stock = round($storeWiseItemInfo[$v->item_id]->current_stock, $v->decimal_point_place);
            }else{
                $v->current_stock = 0;
            }
            if(array_key_exists($v->reference_details_id, $issueDataList)){
                $issueInfo = $issueDataList[$v->reference_details_id];
                $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                $avail_qty = self::itemQtySubtraction($itemInfo, $v->req_app_qty,$issueInfo->app_qty);
                $v->avail_qty = round($avail_qty, $v->decimal_point_place);  

            }else{
                $v->avail_qty = round($v->req_app_qty, $v->decimal_point_place);
            }
            if($v->current_stock>=$v->avail_qty){
                $v->issue_qty = round($v->avail_qty, $v->decimal_point_place);
            }else{
                $v->issue_qty = round($v->current_stock, $v->decimal_point_place); 
            }
            $v->sl_avail_qty = $v->issue_qty; 
            $v->req_app_qty = round($v->req_app_qty, $v->decimal_point_place);
            $v->ref_check=0;
            $v->row_style = ($v->use_serial==0)? 'valid' : 'invalid';
            $v->serial_data = [];
            $v->serial_html = '';
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
        $campus_id = self::getCampusId();
        $institute_id = self::getInstituteId();
        $validated = $request->validate([
            'voucher_no' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'campus_id' => 'required',
            'store_id' => 'required',
            'issue_to' => 'required',
            'reference_type'=>'required'
        ]);

        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $voucherDetailsData = $request->voucherDetailsData;
        if(count($voucherDetailsData)>0){
            if(!empty($id)){
                $voucherDetailsData_db = IssueFromInventoryDetailsModel::module()->valid()->where('issue_id', $id)->get();
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
                    $itemInfo = $itemList[$v['item_id']];
                    // franction qty check
                    if($itemInfo->has_fraction==1){
                        if(self::isFloat($v['issue_qty'])){
                            $explodeQty = explode('.', $v['issue_qty']);
                            if(strlen($explodeQty[1])>$itemInfo->decimal_point_place){
                                $flag = false;
                                $msg[] = $itemInfo->product_name.' has allow '.$itemInfo->decimal_point_place.' decimal places'; 
                            }
                        }

                    }else{
                        if(self::isFloat($v['issue_qty'])){
                            $flag = false;
                            $msg[] = $itemInfo->product_name.' has no decimal places'; 
                        }
                    }
                    // item approval check
                    $details_id = @$v['id'];
                    if($details_id>0){
                        $db_data = $voucherDetailsData_db_keyBy[$details_id];
                        if(($db_data->status==1||$db_data->status==2) && $db_data->issue_qty!=$v['issue_qty']){
                            $flag = false;
                            $msg[] = $itemInfo->product_name.' has already approved and can not change qty'; 
                        }
                        // check any of item has approval 
                        if($db_data->status==1||$db_data->status==2){
                            $item_approval=true;
                        }
                    }
                    if($v['issue_qty']>0  && $v['row_style']=='valid'){
                        $has_qty = true;
                    }
                }else{
                   $flag = false;
                   $msg[] = 'Item not found'; 
                }
            endforeach;
            if($flag && $has_qty){
                $storeWiseItemInfo = StoreWiseItemModel::module()->select('item_id', 'avg_cost_price')->where('store_id', $request->store_id)->whereIn('item_id', $item_ids)->get()->keyBy('item_id')->all();
                DB::beginTransaction();
                try {
                    $data = [
                        "store_id" => $request->store_id,
                        "issue_to" => $request->issue_to,
                        "date" => $date,
                        "reference_type" => $request->reference_type,
                        "comments" => $request->comments
                    ];
                    $auto_voucher = $request->auto_voucher;  // voucher type
                    if(!empty($id)){
                        $issue_id = $id;
                        $issueInfo = IssueFromInventoryModel::module()->valid()->findOrFail($id);
                        if($issueInfo->status==0){ // check info status
                            // date change check 
                            if($item_approval && $issueInfo->date!=$date){
                                $flag = false; 
                                $msg[] = 'Sorry issue from inventory details item already approved you can not change date';
                            }else{
                                // delete check 
                                $reqDtlDbIds = $voucherDetailsData_db->pluck('id')->all();
                                $reqDtlIds  = collect($voucherDetailsData)->pluck('id')->all();
                                $reqDtlIds_diff = collect($reqDtlDbIds)->diff($reqDtlIds)->all();
                                foreach($reqDtlIds_diff as $diffId) {
                                    $db_data = $voucherDetailsData_db_keyBy[$diffId];
                                    if($db_data->status==1||$db_data->status==2){
                                        $itemInfo = $itemList[$db_data->item_id];
                                        $flag = false;
                                        $msg[] = $itemInfo->product_name.' has already approved and can not delete it'; 
                                    }
                                }
                                if($flag){
                                    $issueInfo->update([
                                        "campus_id" => $request->campus_id,
                                        "store_id" => $request->store_id,
                                        "issue_to" => $request->issue_to,
                                        "date" => $date,
                                        "comments" => $request->comments
                                    ]); 
                                   // delete details data
                                   foreach($reqDtlIds_diff as $diffId) {
                                        IssueFromInventoryDetailsModel::find($diffId)->delete();
                                   }
                                   // delete Issue serial data
                                    $issueSerialDetials  =  IssueSerialDetailsModel::module()->valid()->where('issue_id', $issue_id)->get();
                                    if(count($issueSerialDetials)>0){
                                       // delete issue serial details
                                        foreach($issueSerialDetials as $purRec) {
                                            IssueSerialDetailsModel::find($purRec->id)->delete();
                                        }
                                    }
                                }
                            }
                        }else{
                           $flag = false; 
                           $msg[] = 'Sorry Issue from inventory already approved';
                        }

                    }else{
                        if($auto_voucher){  // auto voucher configuration
                            $voucherInfo = self::getVoucherNo('issueFromInventory');
                            if($voucherInfo['voucher_no']){
                                $data['voucher_no'] = $voucherInfo['voucher_no'];
                                $data['voucher_int'] = $voucherInfo['voucher_int'];
                                $data['voucher_config_id'] = $voucherInfo['voucher_config_id'];
                            }else{
                                $flag=false;
                                $msg = $voucherInfo['msg'];  
                            }
                        }else{  // menual voucher 
                            $checkVoucher = IssueFromInventoryModel::module()->valid()->where('voucher_no', $request->voucher_no)->first();
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
                            $save = IssueFromInventoryModel::create($data);
                            $issue_id = $save->id;
                        } 

                    }
                    if($flag){
                        foreach ($voucherDetailsData as $v) {
                            $details_id = @$v['id'];
                            if($v['issue_qty']>0 && $v['row_style']=='valid'){
                                $detailsData  = [
                                    'issue_id'=>$issue_id,
                                    'item_id'=>$v['item_id'],
                                    'issue_qty'=>$v['issue_qty'],
                                    'reference_type'=>$request->reference_type,
                                    'reference_id'=>$v['reference_id'],
                                    'reference_details_id'=>$v['reference_details_id'],
                                    'has_serial'=>$v['use_serial'],
                                    'remarks'=>$v['remarks']
                                ];
                                if($details_id>0){
                                    $issue_details_id = $details_id;
                                    IssueFromInventoryDetailsModel::module()->valid()->find($details_id)->update($detailsData);;
                                }else{
                                    if(array_key_exists($v['item_id'], $storeWiseItemInfo)){
                                        $storItemInfo = $storeWiseItemInfo[$v['item_id']];
                                        $detailsData['rate'] = $storItemInfo->avg_cost_price;
                                    }
                                   $detailsSave  =  IssueFromInventoryDetailsModel::create($detailsData);  
                                   $issue_details_id =  $detailsSave->id;
                                }

                                // issue inventory serial details
                                foreach($v['serial_data'] as $serial){
                                    $serial_details_data = [
                                        'issue_id' => $issue_id,
                                        'issue_details_id' => $issue_details_id,
                                        'item_id' => $v['item_id'],
                                        'serial_details_id' => $serial['id'],
                                        'serial_code' => $serial['serial_code']
                                    ];
                                    IssueSerialDetailsModel::create($serial_details_data);
                                }
                            }else{
                                if($details_id>0){
                                    IssueFromInventoryDetailsModel::find($details_id)->delete();
                                }
                            }
                        }
                       $output = ['status'=>1,'message'=>'Issue from inventroy successfully saved'];
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
                    $output = ['status'=>0,'message'=>"Empty issue qty"]; 
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
        $issueFromInfo  = IssueFromInventoryModel::module()->valid()
            ->leftJoin('users', 'inventory_issue_from.issue_to','=', 'users.id')
            ->leftJoin('setting_campus', 'inventory_issue_from.campus_id','=', 'setting_campus.id')
            ->leftJoin('cadet_inventory_store', 'inventory_issue_from.store_id','=', 'cadet_inventory_store.id')
            ->select('inventory_issue_from.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS issue_date"), 'users.name','setting_campus.name as campus_name','cadet_inventory_store.store_name')
            ->where('inventory_issue_from.id', $id)
            ->first();
        if(!empty($issueFromInfo)){
            $issueSerialDataGroupBy  = IssueSerialDetailsModel::module()->valid()
                ->select('serial_details_id as id', 'serial_code','issue_details_id')
                ->where('issue_id', $id)
                ->get()->groupBy('issue_details_id')->all();
            $voucherDetailsData = IssueFromInventoryDetailsModel::module()->valid()
                ->join('inventory_new_requisition_info', 'inventory_new_requisition_info.id','=', 'inventory_issue_details.reference_id')
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_issue_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
                ->select('inventory_issue_details.*', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of', 'inventory_new_requisition_info.voucher_no as ref_voucher_name')
                ->where('inventory_issue_details.issue_id', $id)
                ->whereIn('inventory_issue_details.item_id', $item_ids)
                ->get();
            foreach($voucherDetailsData as $v){
                if($v->has_serial==1){
                    if(array_key_exists($v->id, $issueSerialDataGroupBy)){
                        $issueSerialData = $issueSerialDataGroupBy[$v->id];
                        $serial_html = '<ul style="list-style:none">';
                        foreach($issueSerialData as $serialInfo){
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
            $issueFromInfo->voucherDetailsData = $voucherDetailsData; 
            $data['formData'] = $issueFromInfo;
            $data['refItemList'] = [];
        }else{
            $data = ['status'=>0, 'message'=>"voucher not found"];
        }
        return response()->json($data);

        //return view('inventory::issueInventory.modal.issue-inventory-details');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $issueInfo  = IssueFromInventoryModel::module()
            ->select('id','voucher_no','voucher_int', 'date','store_id', 'issue_to', 'reference_type', 'comments', 'campus_id')
            ->valid()->find($id);
        $issue_date = $issueInfo->date; 
        $date = DateTime::createFromFormat('Y-m-d', $issueInfo->date)->format('d/m/Y');
        $issueInfo->date = $date;
        $issueInfo->numbering = true;
        $data['issue_user_list'] = self::getUserList();
        $issue_to_model=User::select('id', 'name')->where('id', $issueInfo->issue_to)->first();
        $issueInfo->issue_to_model = $issue_to_model;
        $issueInfo->issue_name = $issue_to_model->name; 
        $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
        $campus_id_model=Campus::select('id', 'name')->where('id', $issueInfo->campus_id)->first();
        $issueInfo->campus_name = $campus_id_model->name;
        $issueInfo->campus_id_model = $campus_id_model;
        $data['store_list'] = InventoryStore::select('id','store_name')->access($this)->get();
        $store_id_model =  InventoryStore::select('id','store_name')->where('id', $issueInfo->store_id)->first();
        $issueInfo->store_id_model = $store_id_model;
        $issueInfo->store_name = $store_id_model->store_name; 
        $item_list = self::storeWiseItemList($issueInfo->store_id);
        $item_ids = $item_list->pluck('item_id')->all();
        // voucher details data
        $voucherDetailsData = IssueFromInventoryDetailsModel::module()->valid()
            ->join('inventory_new_requisition_info', 'inventory_new_requisition_info.id','=', 'inventory_issue_details.reference_id')
            ->join('inventory_new_requisition_details', 'inventory_new_requisition_details.id','=', 'inventory_issue_details.reference_details_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_new_requisition_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
            ->select('inventory_issue_details.id','inventory_issue_details.item_id','inventory_issue_details.issue_qty','inventory_issue_details.reference_id','inventory_issue_details.reference_details_id','inventory_issue_details.has_serial','inventory_issue_details.remarks', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of','cadet_stock_products.use_serial', 'inventory_new_requisition_info.voucher_no as ref_voucher_name','inventory_new_requisition_details.app_qty as req_app_qty')
            ->where('inventory_issue_details.issue_id', $id)
            ->whereIn('inventory_issue_details.item_id', $item_ids)
            ->get();
        $voucher_ref_details_ids = $voucherDetailsData->pluck('reference_details_id')->all();
        $voucher_details_item_ids = $voucherDetailsData->pluck('item_id')->all();
        
        // voucher reference data
        $refItemList = NewRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_new_requisition_info', 'inventory_new_requisition_info.id','=', 'inventory_new_requisition_details.new_req_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_new_requisition_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
            ->select('inventory_new_requisition_details.id as reference_details_id','inventory_new_requisition_details.new_req_id as reference_id','inventory_new_requisition_details.item_id','inventory_new_requisition_details.req_qty','inventory_new_requisition_details.app_qty as req_app_qty','inventory_new_requisition_details.remarks',DB::raw("DATE_FORMAT(inventory_new_requisition_info.date,'%d/%m/%Y') AS req_date, DATE_FORMAT(inventory_new_requisition_info.due_date,'%d/%m/%Y') AS due_date"), 'inventory_new_requisition_info.voucher_no as ref_voucher_name', 'cadet_stock_products.product_name', 'cadet_stock_products.sku','cadet_stock_products.use_serial', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of')
            ->where('inventory_new_requisition_info.due_date','<=',$issue_date)
            ->where('inventory_new_requisition_info.requisition_by',$issueInfo->issue_to)
            ->whereIn('inventory_new_requisition_details.status',[1,2,5])
            ->orderBy('inventory_new_requisition_info.due_date','asc')
            ->get(); 

        $ref_details_ids = $refItemList->pluck('reference_details_id')->all();
        $ref_item_ids =  $refItemList->pluck('item_id')->all(); 
        $merge_ref_details_ids  = collect($voucher_ref_details_ids)->merge($ref_details_ids)->all();
        $merge_ref_item_ids  = collect($voucher_details_item_ids)->merge($ref_item_ids)->all();
        $merge_un_ref_details_ids = collect($merge_ref_details_ids)->unique()->values()->all();
        $merge_un_ref_item_ids = collect($merge_ref_item_ids)->unique()->values()->all();

        $storeWiseItemInfo  = StoreWiseItemModel::select('item_id', DB::raw('ifnull(inventory_store_wise_item.current_stock, 0) as current_stock'))->module()->valid()->where('store_id', $issueInfo->store_id)->whereIn('item_id', $merge_un_ref_item_ids)->get()->keyBy('item_id')->all();
        $issueDataList  = IssueFromInventoryDetailsModel::module()->select('reference_details_id',DB::raw('SUM(app_qty) as app_qty'))->whereIn('reference_details_id', $merge_un_ref_details_ids)->whereIn('status',[1,2])->where('reference_type', 'requisition')->groupBy('reference_details_id')->get()->keyBy('reference_details_id')->all();

        foreach ($refItemList as $v) {
            if(array_key_exists($v->item_id, $storeWiseItemInfo)){
                $v->current_stock = round($storeWiseItemInfo[$v->item_id]->current_stock, $v->decimal_point_place);
            }else{
                $v->current_stock = 0;
            }
            if(array_key_exists($v->reference_details_id, $issueDataList)){
                $issueDataInfo = $issueDataList[$v->reference_details_id];
                $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                $avail_qty = self::itemQtySubtraction($itemInfo, $v->req_app_qty,$issueDataInfo->app_qty);
                $v->avail_qty = round($avail_qty, $v->decimal_point_place);  

            }else{
                $v->avail_qty = round($v->req_app_qty, $v->decimal_point_place);
            }
            if($v->current_stock>=$v->avail_qty){
                $v->issue_qty = round($v->avail_qty, $v->decimal_point_place);
            }else{
                $v->issue_qty = round($v->current_stock, $v->decimal_point_place); 
            }
            $v->sl_avail_qty = $v->issue_qty; 
            $v->req_app_qty = round($v->req_app_qty, $v->decimal_point_place);
            $v->ref_check=0;
            $v->row_style = ($v->use_serial==0)? 'valid' : 'invalid';
            $v->serial_data = [];
            $v->serial_html = '';
        }

        // Issue serial details data
        $issueSerialDataGroupBy  = IssueSerialDetailsModel::module()->valid()
            ->select('serial_details_id as id', 'serial_code','issue_details_id')
            ->where('issue_id', $id)
            ->get()->groupBy('issue_details_id')->all();


        foreach($voucherDetailsData as $v){
            if(array_key_exists($v->item_id, $storeWiseItemInfo)){
                $v->current_stock = round($storeWiseItemInfo[$v->item_id]->current_stock, $v->decimal_point_place);
            }else{
                $v->current_stock = 0;
            }
            if(array_key_exists($v->reference_details_id, $issueDataList)){
                $issueDataInfo = $issueDataList[$v->reference_details_id];
                $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                $avail_qty = self::itemQtySubtraction($itemInfo, $v->req_app_qty,$issueDataInfo->app_qty);
                $v->avail_qty = round($avail_qty, $v->decimal_point_place);  
            }else{
                $v->avail_qty = round($v->req_app_qty, $v->decimal_point_place);
            }
            if($v->current_stock>=$v->avail_qty){
                $v->sl_avail_qty = $v->avail_qty;
            }else{
                $v->sl_avail_qty = $v->current_stock; 
            }
            $v->issue_qty = round($v->issue_qty, $v->decimal_point_place);
            if($v->has_serial==1){
                if(array_key_exists($v->id, $issueSerialDataGroupBy)){
                    $issueSerialData = $issueSerialDataGroupBy[$v->id];
                    $v->serial_data = $issueSerialData;
                    $serial_html = '<ul style="list-style:none">';
                    foreach($issueSerialData as $serialInfo){
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
        $issueInfo->voucherDetailsData = $voucherDetailsData;
        $issueInfo->itemAdded = (count($voucherDetailsData)>0)?'yes':'no';

        $data['refItemList'] = $refItemList;
        $data['formData'] = $issueInfo; 
        return response()->json($data);
    }

    public function voucherApproval(Request $request, $id=0)
    {
        DB::beginTransaction();
        try{
            $auth_user_id = Auth::user()->id;
            if($id>0){
                $approvalData = IssueFromInventoryDetailsModel::module()->valid()
                    ->join('inventory_issue_from', 'inventory_issue_from.id','=', 'inventory_issue_details.issue_id')
                    ->select('inventory_issue_details.*','inventory_issue_details.issue_qty as qty', 'inventory_issue_from.store_id')
                    ->find($id);
                if(!empty($approvalData)){
                    if($approvalData->status==0){
                        $approval_info = self::getApprovalInfo('issue_from');
                        $step = $approval_info['step'];
                        $approval_access = $approval_info['approval_access'];
                        $last_step = $approval_info['last_step'];
                        if($approval_access && $approvalData->approval_level==$step){
                            $flag=true; $msg = '';
                            if($step==$last_step){
                                $itemInfo = CadetInventoryProduct::find($approvalData->item_id);
                                if(!empty($itemInfo)){
                                    $requisitionDetailsInfo = NewRequisitionDetailsModel::module()->where('id', $approvalData->reference_details_id)->whereIn('status',[1,2,5])->first();
                                    if(!empty($requisitionDetailsInfo)){
                                        $issueReferenceSum  = IssueFromInventoryDetailsModel::module()->where('reference_details_id', $requisitionDetailsInfo->id)->whereIn('status',[1,2])->where('reference_type', 'requisition')->sum('app_qty');

                                        if($issueReferenceSum>0){
                                            $avail_qty = self::itemQtySubtraction($itemInfo, $requisitionDetailsInfo->app_qty,$issueReferenceSum);
                                        }else{
                                            $avail_qty = $requisitionDetailsInfo->app_qty;
                                        }

                                        if($approvalData->qty<=$avail_qty){
                                            $serial_stock_check = true; 
                                            if($approvalData->has_serial==1){
                                                $serial_msg = [];
                                                $issueSerialDetialsData = IssueSerialDetailsModel::module()->valid()->where('issue_details_id', $approvalData->id)->get();
                                                $issueSerialDetialsDataKeyBy = $issueSerialDetialsData->keyBy('serial_details_id')->all();
                                                $issueSerialDetialsIds = $issueSerialDetialsData->pluck('serial_details_id')->all(); 
                                                foreach($issueSerialDetialsIds as $stock_sl_dtl_id):
                                                    $stock_item_serial_check = StockItemSerialDetailsModel::module()->valid()
                                                        ->where('item_id', $approvalData->item_id)
                                                        ->where('id', $stock_sl_dtl_id)
                                                        ->where('stock_in_store_id', $approvalData->store_id)
                                                        ->where('current_stock','>',0)->first();

                                                    if(empty($stock_item_serial_check)){
                                                        $serial_msg[] = @$issueSerialDetialsDataKeyBy[$stock_sl_dtl_id]->serial_code.' has already stock out';
                                                        $serial_stock_check = false;
                                                    }
                                                endforeach;
                                            }
                                            if($serial_stock_check){
                                                $stockCalInfo  = self::storeStockDecrement($itemInfo,$approvalData);
                                                $flag = $stockCalInfo['flag'];
                                                if($flag){
                                                    $current_stock = $stockCalInfo['current_stock'];
                                                    if($requisitionDetailsInfo->app_qty>$approvalData->qty){
                                                        $issue_status = 2;
                                                    }else{
                                                        $issue_status = 1;
                                                    }
                                                    $approvalData->update([
                                                        'status'=>$issue_status,
                                                        'app_qty'=>$approvalData->qty,
                                                        'approval_level'=>$step+1
                                                    ]);

                                                    if($approvalData->has_serial==1){
                                                        // update serial detials data
                                                        StockItemSerialDetailsModel::module()->valid()->whereIn('id', $issueSerialDetialsIds)->where('stock_in_store_id', $approvalData->store_id)->update([
                                                            'current_stock'=>0,
                                                            'stock_out_from'=>'issue_from',
                                                            'stock_out_date'=>date('Y-m-d')
                                                        ]);
                                                    }
                                                    // update store wise stock
                                                    StoreWiseItemModel::module()->where('item_id', $approvalData->item_id)->where('store_id', $approvalData->store_id)->update([
                                                        'current_stock'=>$current_stock
                                                    ]);
                                                    // update new requision info
                                                    self::updateNewRequisionStatus($itemInfo,$requisitionDetailsInfo);
                                                    AllStockOutModel::create([
                                                        'date'=>date('Y-m-d'),
                                                        'item_id'=>$approvalData->item_id,
                                                        'stock_out_id'=>$approvalData->issue_id,
                                                        'stock_out_details_id'=>$approvalData->id,
                                                        'store_id'=>$approvalData->store_id,
                                                        'qty'=>$approvalData->qty,
                                                        'rate'=>$approvalData->rate,
                                                        'stock_out_reason'=>"Issue from inventory"
                                                    ]);
                                                    self::stockOutFifo($itemInfo, $approvalData, $approvalData->qty);
                                                }else{
                                                    $output = ['status'=>0, 'message'=>$stockCalInfo['msg']]; 
                                                }
                                            }else{
                                                $flag=false;
                                                $output = ['status'=>0, 'message'=>$serial_msg];
                                            }
                                        }else{
                                            $flag=false;
                                            $output = ['status'=>0, 'message'=>'Insufficient Issue inventory Reference qty'];
                                        }
                                    }else{
                                        $flag=false;
                                        $output = ['status'=>0, 'message'=>'Issue inventory Reference already used'];
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
                                    'voucher_id'=>$approvalData->issue_id,
                                    'voucher_details_id'=>$approvalData->id,
                                    'voucher_type'=>'issue_from',
                                    'approval_id'=>$auth_user_id,
                                    'approval_layer'=>$step,
                                    'action_status'=>1,
                                    'institute_id'=>self::getInstituteId(),
                                    'campus_id'=>self::getCampusId(),
                                ]);

                                // update master status base on all app
                                self::masterVoucherUpdate($approvalData->issue_id);
                                DB::commit();
                                $output = ['status'=>1, 'message'=>'Issue from item successfully approved'];
                            }

                        }else{ // end if($approval_access && $approvalData->approval_level==$step){
                            $output = ['status'=>0, 'message'=>'Sorry you have no approval'];    
                        }
                    }else{ // end if($approvalData->status==0)
                        if($approvalData->status==3){
                            $output = ['status'=>0, 'message'=>'Issue Item reject'];
                        }else{
                            $output = ['status'=>0, 'message'=>'Issue Item already approved'];  
                        }
                    }
 
                }else{   // end if(!empty($approvalData))
                    $output = ['status'=>0, 'message'=>'Issue from inventory data not found'];
                }
            }else{
            }
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        } 

        return response()->json($output); 
    }

    public function updateNewRequisionStatus($itemInfo,$requisitionDetailsInfo){
        $reference_details_id = $requisitionDetailsInfo->id;
        $issueReferenceSum  = IssueFromInventoryDetailsModel::module()->where('reference_details_id', $reference_details_id)->whereIn('status',[1,2])->where('reference_type', 'requisition')->sum('app_qty');
        $avail_qty = self::itemQtySubtraction($itemInfo, $requisitionDetailsInfo->app_qty,$issueReferenceSum);
        if($avail_qty>0){
            $status = 5;
        }else{
            $status = 4;
        }
        NewRequisitionDetailsModel::module()->find($requisitionDetailsInfo->id)->update([
            'status'=>$status
        ]);
        // master requisition info update
        $voucherDetailsData = NewRequisitionDetailsModel::module()->valid()->where('new_req_id', $requisitionDetailsInfo->new_req_id)->get();

        $all_issue = true; $status=4;
        foreach ($voucherDetailsData as $v) {
            if($all_issue){
                if($v->status==0||$v->status==1||$v->status==2||$v->status==3||$v->status==5){
                    $all_issue=false;
                    $status=5;
                }
            }else{
                break;
            }            
        }
        NewRequisitionInfoModel::module()->valid()->find($requisitionDetailsInfo->new_req_id)->update([
            'status'=>$status
        ]);        

    }


    public function masterVoucherUpdate($issue_id){
        $voucherDetailsData = IssueFromInventoryDetailsModel::module()->valid()->where('issue_id', $issue_id)->get();
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
            IssueFromInventoryModel::module()->valid()->find($issue_id)->update([
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
                $deleteData = IssueFromInventoryDetailsModel::module()->valid()->find($id);
                if(!empty($deleteData)){
                    if($deleteData->status==1||$deleteData->status==2){  // check status
                        $output = ['status'=>0, 'message'=>'Sorry Item issue already approved'];
                    }else{
                        $issue_id = $deleteData->issue_id; 
                        $has_serial = $deleteData->has_serial;
                        IssueFromInventoryDetailsModel::module()->valid()->find($id)->delete();
                        if($has_serial==1){
                            $issueSerialDetails  = IssueSerialDetailsModel::module()->valid()->where('issue_details_id',$id)->get();
                            foreach($issueSerialDetails as $issueSerial){
                                IssueSerialDetailsModel::module()->valid()->find($issueSerial->id)->delete();
                            }
                        }
                        $checkDetailsItem = IssueFromInventoryDetailsModel::module()->valid()->where('issue_id', $issue_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            IssueFromInventoryModel::module()->valid()->find($issue_id)->delete(); 
                        }
                        $output = ['status'=>1, 'message'=>'Issue from inventory successfully deleted'];
                        DB::commit();
                    }
                }else{
                    $output = ['status'=>0, 'message'=>'Item not found'];
                }
            }else{
                $delIds = $request->delIds;
                // status check
                $issue_ids = []; $flag = true; $msg =[]; 
                foreach ($delIds as $del_id){
                    $deleteData = IssueFromInventoryDetailsModel::module()->valid()->find($del_id);
                    if($deleteData->status==1||$deleteData->status==2){
                        $itemInfo = CadetInventoryProduct::find($deleteData->item_id);
                        $flag = false;
                        $msg[] = $itemInfo->product_name.' has issue from inventory qty approval';
                    }
                    $issue_ids[] = $deleteData->issue_id;
                }
                if($flag){
                    foreach ($delIds as $del_id){
                        $deleteData =  IssueFromInventoryDetailsModel::valid()->find($del_id);
                        if($deleteData->has_serial==1):
                            $issueSerialDetails  = IssueSerialDetailsModel::module()->valid()->where('issue_details_id',$del_id)->get();
                            foreach($issueSerialDetails as $issueSerial){
                                IssueSerialDetailsModel::module()->valid()->find($issueSerial->id)->delete();
                            }
                        endif;
                        $deleteData->delete();
                    }
                    $issue_unique_ids = collect($issue_ids)->unique()->values()->all();
                    foreach ($issue_unique_ids as $issue_id) {
                        $checkDetailsItem = IssueFromInventoryDetailsModel::module()->valid()->where('issue_id', $issue_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            IssueFromInventoryModel::module()->valid()->find($issue_id)->delete(); 
                        }
                    }
                    $output = ['status'=>1, 'message'=>'Issue from inventory successfully deleted'];
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

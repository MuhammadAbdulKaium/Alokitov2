<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\PurchaseRequisitionInfoModel;
use Modules\Inventory\Entities\PurchaseRequisitionDetailsModel;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\UserVoucherApprovalLayerModel;
use Modules\Inventory\Entities\VoucherApprovalLogModel;
use Modules\Setting\Entities\Campus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\InventoryHelper;
use App\User;
use Illuminate\Validation\Rule;
use DateTime;


class PurchaseRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

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
        if($sort=='id') $sort='inventory_purchase_requisition_details.id';

        $item_list = self::itemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $data['item_list'] = self::mergeEmtyArryObj($item_list, ['item_id'=>0, 'product_name'=>'Select item']);
        $voucher_list = PurchaseRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_purchase_requisition_info', 'inventory_purchase_requisition_info.id','=', 'inventory_purchase_requisition_details.req_id')
            ->select('inventory_purchase_requisition_details.req_id as id', 'inventory_purchase_requisition_info.voucher_no')
            ->orderBy('voucher_int', 'desc')
            ->groupBy(['req_id','voucher_no'])
            ->get();

        $data['voucher_list'] =  self::mergeEmtyArryObj($voucher_list, ['id'=>0, 'voucher_no'=>'Select voucher']);

        $paginate_data_query = PurchaseRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_purchase_requisition_info', 'inventory_purchase_requisition_info.id','=', 'inventory_purchase_requisition_details.req_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_requisition_details.item_id')
            ->select('inventory_purchase_requisition_details.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS stock_in_date"), 'inventory_purchase_requisition_info.voucher_no', 'cadet_stock_products.product_name', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
            ->when($item_id, function($query, $item_id){
                $query->where('inventory_purchase_requisition_details.item_id',$item_id);
            })
            ->when($voucher_id, function($query, $voucher_id){
                $query->where('inventory_purchase_requisition_details.req_id',$voucher_id);
            })
            ->when($from_date, function($query, $from_date){
                $query->where('inventory_purchase_requisition_info.date','>=',$from_date);
            })
            ->when($to_date, function($query, $to_date){
                $query->where('inventory_purchase_requisition_info.date','<=',$to_date);
            })
            ->when($status, function($query, $status){
                if($status=='p') $status=0;
                $query->where('inventory_purchase_requisition_details.status',$status);
            })
            ->where(function($query)use($search_key){
                if(!empty($search_key)){
                    $query->where('inventory_purchase_requisition_info.voucher_no','LIKE','%'.$search_key.'%')
                        ->orWhere('cadet_stock_products.product_name','LIKE','%'.$search_key.'%');
                }
            }) 
            ->orderBy($sort,$order);     

        $paginate_data = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);
        if(count($paginate_data)>0){
            $auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', 'purchase_requisition')->where('is_role', 0)->get();
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
                ->where('voucher_type', 'purchase_requisition')
                ->where('is_role', 0)
                ->whereIn('voucher_details_id', $voucher_details_ids)
                ->orderBy('inventory_voucher_approval_log.approval_layer', 'asc')
                ->get()->groupBy('voucher_details_id')->all();
            // check if his step is approval or not
            $approval_step_log = VoucherApprovalLogModel::module()->valid()
                ->where('voucher_type', 'purchase_requisition')
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
        return view('inventory::purchase.purchaseRequisition.purchase-requisition');
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        $voucherInfo = self::checkInvVoucher(5); 
        if($voucherInfo['voucher_conf']){
            $data['requisition_user_list'] = User::select('id', 'name')->module()->get();
            $requisition_by_model=User::select('id', 'name')->where('id', Auth::user()->id)->first();
            $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
            $campus_id_model=Campus::select('id', 'name')->where('id', self::getCampusId())->first();
            $data['formData'] = ['voucher_no'=>$voucherInfo['voucher_no'],'voucher_config_id'=>$voucherInfo['voucher_config_id'],'auto_voucher'=>$voucherInfo['auto_voucher'], 'date'=>date('d/m/Y'), 'due_date'=>date('d/m/Y'),'campus_id_model'=>$campus_id_model,'campus_id'=>self::getCampusId(),'requisition_by_model'=>$requisition_by_model,'requisition_by'=>Auth::user()->id, 'voucherDetailsData'=>[], 'itemAdded'=>'no', 'need_cs'=>0];
        }else{
            $data = ['status'=>0, 'message'=>"Setup voucher configuration first"];
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
        $campus_id = self::getCampusId();
        $institute_id = self::getInstituteId();
        $validated = $request->validate([
            'voucher_no' => 'required',
            'campus_id' => 'required',
            'requisition_by' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'due_date' => 'required|date_format:d/m/Y|after_or_equal:date',
            'need_cs'=>'required'
        ]);

        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $due_date = DateTime::createFromFormat('d/m/Y', $request->due_date)->format('Y-m-d');
        

        $voucherDetailsData = $request->voucherDetailsData;
        if(count($voucherDetailsData)>0){
            if(!empty($id)){
                $voucherDetailsData_db = PurchaseRequisitionDetailsModel::module()->valid()->where('req_id', $id)->get();
                $voucherDetailsData_db_keyBy = $voucherDetailsData_db->keyBy('id')->all(); 
                $db_item_ids = $voucherDetailsData_db->pluck('item_id')->all(); 
                $req_item_ids = collect($voucherDetailsData)->pluck('item_id')->all(); 
                $merge_item_ids = collect($db_item_ids)->merge($req_item_ids)->all();
                $item_ids = collect($merge_item_ids)->unique()->values()->all();
            }else{
               $item_ids = collect($voucherDetailsData)->pluck('item_id')->all(); 
            }
            $itemList = CadetInventoryProduct::whereIn('id', $item_ids)->get()->keyBy('id')->all();
            $flag = true; $msg = []; $item_approval = false;
            // checking fraction, fraction length and if approved item change
            foreach ($voucherDetailsData as $v):
                if(array_key_exists($v['item_id'], $itemList)){
                    $itemInfo = $itemList[$v['item_id']];
                    // franction qty check
                    if($itemInfo->has_fraction==1){
                        if(self::isFloat($v['req_qty'])){
                            $explodeQty = explode('.', $v['req_qty']);
                            if(strlen($explodeQty[1])>$itemInfo->decimal_point_place){
                                $flag = false;
                                $msg[] = $itemInfo->product_name.' has allow '.$itemInfo->decimal_point_place.' decimal places'; 
                            }
                        }

                    }else{
                        if(self::isFloat($v['req_qty'])){
                            $flag = false;
                            $msg[] = $itemInfo->product_name.' has no decimal places'; 
                        }
                    }
                    // item approval check
                    $details_id = @$v['id'];
                    if($details_id>0){
                        $db_data = $voucherDetailsData_db_keyBy[$details_id];
                        if(($db_data->status==1||$db_data->status==2) && $db_data->req_qty!=$v['req_qty']){
                            $flag = false;
                            $msg[] = $itemInfo->product_name.' has already approved and can not change qty'; 
                        }
                        // check any of item has approval 
                        if($db_data->status==1||$db_data->status==2){
                            $item_approval=true;
                        }
                    }
                }else{
                   $flag = false;
                   $msg[] = 'Item not found'; 
                }
            endforeach;
            if($flag){
                DB::beginTransaction();
                try {
                    $data = [
                        "requisition_by" => $request->requisition_by,
                        "date" => $date,
                        "due_date" => $due_date,
                        "comments" => $request->comments,
                        "need_cs" => $request->need_cs
                    ];

                    $auto_voucher = $request->auto_voucher;  // voucher type 

                    if(!empty($id)){
                        $req_id = $id;
                        $reqInfo = PurchaseRequisitionInfoModel::module()->valid()->findOrFail($id);
                        if($reqInfo->status==0){ // check info status
                            // date change check 
                            if($item_approval && ($reqInfo->date!=$date || $reqInfo->due_date!=$due_date || $reqInfo->need_cs!=$request->need_cs)){
                                $flag = false;
                                if($reqInfo->need_cs!=$request->need_cs){
                                   $msg[] = 'Sorry Purchase requisition details item already approved you can not change need cs'; 
                                }else{
                                   $msg[] = 'Sorry Purchase requisition details item already approved you can not change date'; 
                                }
                                
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
                                   $reqInfo->update([
                                        "requisition_by" => $request->requisition_by,
                                        "date" => $date,
                                        "due_date" => $due_date,
                                        "comments" => $request->comments,
                                        "need_cs" => $request->need_cs
                                   ]); 
                                   // delete details data
                                   foreach($reqDtlIds_diff as $diffId) {
                                        PurchaseRequisitionDetailsModel::find($diffId)->delete();
                                   }
                                }
                            }
                        }else{
                           $flag = false; 
                           $msg[] = 'Sorry Purchase requisition already approved';
                        }

                    }else{
                        if($auto_voucher){  // auto voucher configuration
                            $voucherInfo = self::getVoucherNo('purReq');
                            if($voucherInfo['voucher_no']){
                                $data['voucher_no'] = $voucherInfo['voucher_no'];
                                $data['voucher_int'] = $voucherInfo['voucher_int'];
                                $data['voucher_config_id'] = $voucherInfo['voucher_config_id'];
                            }else{
                                $flag=false;
                                $msg = $voucherInfo['msg'];  
                            }
                        }else{  // menual voucher 
                            $checkVoucher = PurchaseRequisitionInfoModel::module()->valid()->where('voucher_no', $request->voucher_no)->first();
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
                            $save = PurchaseRequisitionInfoModel::create($data);
                            $req_id = $save->id;
                        } 

                    }
                    if($flag){
                        foreach ($voucherDetailsData as $v) {
                            $details_id = @$v['id'];
                            $detailsData  = [
                                'req_id'=>$req_id,
                                'item_id'=>$v['item_id'],
                                'req_qty'=>$v['req_qty'],
                                'remarks'=>$v['remarks'],
                                'need_cs' => $request->need_cs
                            ];
                            if($details_id>0){
                                PurchaseRequisitionDetailsModel::module()->valid()->find($details_id)->update($detailsData);;
                            }else{
                               PurchaseRequisitionDetailsModel::create($detailsData);  
                            }
                        }
                       $output = ['status'=>1,'message'=>'Purchas  Requisition successfully saved'];
                       DB::commit();
                    }else{
                        $output = ['status'=>0,'message'=>$msg]; 
                    }
                } catch (Throwable $e) {
                    DB::rollback();
                    throw $e;
                }  
            }else{
               $output = ['status'=>0,'message'=>$msg]; 
            }
        }else{
            $output = ['status'=>0,'message'=>"Please add at least one purchase requisition item"];
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
        $voucherInfo  = PurchaseRequisitionInfoModel::module()->valid()
            ->join('setting_campus', 'setting_campus.id','=', 'inventory_purchase_requisition_info.campus_id')
            ->leftJoin('users', 'inventory_purchase_requisition_info.requisition_by','=', 'users.id')
            ->select('inventory_purchase_requisition_info.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS req_date, DATE_FORMAT(due_date,'%d/%m/%Y') AS due_date_formate"), 'users.name', 'setting_campus.name as campus_name')
            ->where('inventory_purchase_requisition_info.id', $id)
            ->first();
        if(!empty($voucherInfo)){
            $voucherDetailsData = PurchaseRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_requisition_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->select('inventory_purchase_requisition_details.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
                ->where('req_id', $id)->get(); 
            if(count($voucherDetailsData)>0){
                $totalReqQty = 0; $totalAppQty = 0;
                foreach ($voucherDetailsData as $v) {
                   $totalReqQty+= $v->req_qty;
                   $totalAppQty+= $v->req_qty;
                }
                $voucherInfo->totalReqQty = $totalReqQty;
                $voucherInfo->totalAppQty = $totalAppQty;
                $voucherInfo->voucherDetailsData = $voucherDetailsData; 
            }else{
                $voucherInfo->totalReqQty = 0;
                $voucherInfo->totalAppQty = 0;
                $voucherInfo->voucherDetailsData = []; 
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
                $approvalData = PurchaseRequisitionDetailsModel::module()->valid()->find($id);
                if(!empty($approvalData)){
                    if($approvalData->status==0){
                        $approval_info = self::getApprovalInfo('purchase_requisition');
                        $step = $approval_info['step'];
                        $approval_access = $approval_info['approval_access'];
                        $last_step = $approval_info['last_step'];
                        if($approval_access && $approvalData->approval_level==$step){
                            $flag=true;
                            if($step==$last_step){
                                $itemInfo = CadetInventoryProduct::find($approvalData->item_id);
                                if(!empty($itemInfo)){
                                    $approvalData->update([
                                        'status'=>1,
                                        'app_qty'=>$approvalData->req_qty,
                                        'approval_level'=>$step+1
                                    ]);
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
                                    'voucher_id'=>$approvalData->req_id,
                                    'voucher_details_id'=>$approvalData->id,
                                    'voucher_type'=>'purchase_requisition',
                                    'approval_id'=>$auth_user_id,
                                    'approval_layer'=>$step,
                                    'action_status'=>1,
                                    'institute_id'=>self::getInstituteId(),
                                    'campus_id'=>self::getCampusId(),
                                ]);

                                // update master status base on all app
                                self::masterVoucherUpdate($approvalData->req_id);
                                DB::commit();
                                $output = ['status'=>1, 'message'=>'Stock in item successfully approved'];
                            }

                        }else{ // end if($approval_access && $approvalData->approval_level==$step){
                            $output = ['status'=>0, 'message'=>'Sory you have no approval'];    
                        }
                    }else{ // end if($approvalData->status==0)
                        if($approvalData->status==3){
                            $output = ['status'=>0, 'message'=>'Purchase requisition Item reject'];
                        }else{
                            $output = ['status'=>0, 'message'=>'Purchase requistion Item already approved'];  
                        }
                    }
 
                }else{   // end if(!empty($approvalData))
                    $output = ['status'=>0, 'message'=>'Stock Item not found'];
                }
            }
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        } 

        return response()->json($output); 
    }

    public function masterVoucherUpdate($req_id){
        $voucherDetailsData = PurchaseRequisitionDetailsModel::module()->valid()->where('req_id', $req_id)->get();
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
            PurchaseRequisitionInfoModel::module()->valid()->find($req_id)->update([
                'status'=>$status
            ]);
        }

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
        $voucherInfo = PurchaseRequisitionInfoModel::module()->valid()->find($id);
        $date = DateTime::createFromFormat('Y-m-d', $voucherInfo->date)->format('d/m/Y');
        $due_date = DateTime::createFromFormat('Y-m-d', $voucherInfo->due_date)->format('d/m/Y');
        $voucherInfo->date = $date;  
        $voucherInfo->due_date = $due_date;  
        if(!empty($voucherInfo)){
            $data['requisition_user_list'] = User::select('id', 'name')->module()->get();
            $requisition_by_model=User::select('id', 'name')->where('id', $voucherInfo->requisition_by)->first();
            $voucherInfo->requisition_by_model = $requisition_by_model;
            $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
            $voucherInfo->campus_id_model = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',$voucherInfo->campus_id)->first();
            $voucherInfo->itemAdded = 'yes';

            $voucherDetailsData = PurchaseRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_requisition_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->select('inventory_purchase_requisition_details.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
                ->where('req_id', $id)->get(); 
            if(count($voucherDetailsData)>0){
                $voucherInfo->auto_voucher = true;
                $voucherInfo->voucherDetailsData = $voucherDetailsData;
                $total_qty = 0;
                foreach ($voucherDetailsData as $v) {
                    $v->item_id_model = (object)['item_id'=>$v->item_id, 'product_name'=>$v->product_name, 'uom'=>$v->uom, 'decimal_point_place'=>$v->decimal_point_place];
                   $total_qty+= $v->req_qty;
                }
                $voucherInfo->totalQty = $total_qty;
            }else{
                $voucherInfo->voucherDetailsData = [];
                $voucherInfo->totalQty = 0;
            }
            
            $data['formData'] = $voucherInfo; 
        }else{
            $data = ['status'=>0, 'message'=>"voucher not found"];
        }
        return response()->json($data);
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
                $deleteData = PurchaseRequisitionDetailsModel::module()->valid()->find($id);
                if(!empty($deleteData)){
                    if($deleteData->status==1||$deleteData->status==2){  // check status
                        $output = ['status'=>0, 'message'=>'Sorry Purchase requisition Item already approved'];
                    }else{
                        $req_id = $deleteData->req_id; 
                        PurchaseRequisitionDetailsModel::module()->valid()->find($id)->delete();
                        $checkDetailsItem = PurchaseRequisitionDetailsModel::module()->valid()->where('req_id', $req_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            PurchaseRequisitionInfoModel::module()->valid()->find($req_id)->delete(); 
                        }
                        $output = ['status'=>1, 'message'=>'Purchase requisition item successfully deleted'];
                        DB::commit();
                    }
                }else{
                    $output = ['status'=>0, 'message'=>'Purchase requisition not found'];
                }
            }else{
                $delIds = $request->delIds;
                // status check
                $req_ids = []; $flag = true; $msg =[]; 
                foreach ($delIds as $del_id){
                    $deleteData = PurchaseRequisitionDetailsModel::module()->valid()->find($del_id);
                    if($deleteData->status==1||$deleteData->status==2){
                        $itemInfo = CadetInventoryProduct::find($deleteData->item_id);
                        $flag = false;
                        $msg[] = $itemInfo->product_name.' has stock in qty approval';
                    }
                    $req_ids[] = $deleteData->req_id;
                }
                if($flag){
                    foreach ($delIds as $del_id){
                        PurchaseRequisitionDetailsModel::valid()->find($del_id)->delete();
                    }
                    $req_unique_ids = collect($req_ids)->unique()->values()->all();
                    foreach ($req_unique_ids as $req_id) {
                        $checkDetailsItem = PurchaseRequisitionDetailsModel::module()->valid()->where('req_id', $req_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            PurchaseRequisitionInfoModel::module()->valid()->find($req_id)->delete(); 
                        }
                    }
                    $output = ['status'=>1, 'message'=>'Purchase requisition item successfully deleted'];
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

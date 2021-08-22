<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\NewRequisitionInfoModel;
use Modules\Inventory\Entities\NewRequisitionDetailsModel;
use Modules\Inventory\Entities\IssueFromInventoryDetailsModel;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\StoreWiseItemModel;
use Modules\Inventory\Entities\UserVoucherApprovalLayerModel;
use Modules\Inventory\Entities\VoucherApprovalLogModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\InventoryHelper;
use App\User;
use Illuminate\Validation\Rule;
use DateTime;

class NewRequisitionController extends Controller
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
        if($sort=='id') $sort='inventory_new_requisition_details.id';

        $item_list = self::getItemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $data['item_list'] = self::mergeEmtyArryObj($item_list, ['item_id'=>0, 'product_name'=>'Select item']); 
        $voucher_list = NewRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_new_requisition_info', 'inventory_new_requisition_info.id','=', 'inventory_new_requisition_details.new_req_id')
            ->select('inventory_new_requisition_details.new_req_id as id', 'inventory_new_requisition_info.voucher_no')
            ->orderBy('voucher_int', 'desc')
            ->groupBy(['new_req_id','voucher_no'])
            ->get();

        $data['voucher_list'] =  self::mergeEmtyArryObj($voucher_list, ['id'=>0, 'voucher_no'=>'Select voucher']);

        $paginate_data_query = NewRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_new_requisition_info', 'inventory_new_requisition_info.id','=', 'inventory_new_requisition_details.new_req_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_new_requisition_details.item_id')
            ->select('inventory_new_requisition_details.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS req_date"), 'inventory_new_requisition_info.voucher_no', 'cadet_stock_products.product_name','cadet_stock_products.has_fraction','cadet_stock_products.round_of', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
            ->when($item_id, function($query, $item_id){
                $query->where('inventory_new_requisition_details.item_id',$item_id);
            })
            ->when($voucher_id, function($query, $voucher_id){
                $query->where('inventory_new_requisition_details.new_req_id',$voucher_id);
            })
            ->when($from_date, function($query, $from_date){
                $query->where('inventory_new_requisition_info.date','>=',$from_date);
            })
            ->when($to_date, function($query, $to_date){
                $query->where('inventory_new_requisition_info.date','<=',$to_date);
            })
            ->when($status, function($query, $status){
                if($status=='p') $status=0;
                $query->where('inventory_new_requisition_details.status',$status);
            })
            ->where(function($query)use($search_key){
                if(!empty($search_key)){
                    $query->where('inventory_new_requisition_info.voucher_no','LIKE','%'.$search_key.'%')
                        ->orWhere('cadet_stock_products.product_name','LIKE','%'.$search_key.'%');
                }
            }) 
            ->orderBy($sort,$order);     

        $paginate_data = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);
        if(count($paginate_data)>0){
            $auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', 'new_requisition')->where('is_role', 0)->get();
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
                ->where('voucher_type', 'new_requisition')
                ->where('is_role', 0)
                ->whereIn('voucher_details_id', $voucher_details_ids)
                ->orderBy('inventory_voucher_approval_log.approval_layer', 'asc')
                ->get()->groupBy('voucher_details_id')->all();
            // check if his step is approval or not
            $approval_step_log = VoucherApprovalLogModel::module()->valid()
                ->where('voucher_type', 'new_requisition')
                ->whereIn('voucher_details_id', $voucher_details_ids)
                ->where('approval_layer', $step)
                ->where('is_role', 0)
                ->where('approval_id', $auth_user_id)
                ->get()->keyBy('voucher_details_id')->all();

            // issue data 
            $issueDataList  = IssueFromInventoryDetailsModel::module()->select('reference_details_id','app_qty')->whereIn('reference_details_id', $voucher_details_ids)->whereIn('status',[1,2])->where('reference_type', 'requisition')->get()->keyBy('reference_details_id')->all();
            // stock info
            $stock_item_ids = $paginate_data->pluck('item_id')->all(); 
            $stock_item_un_ids = collect($stock_item_ids)->unique()->values()->all();
            $itemWiseStockInfo = self::itemWiseStock($this, $stock_item_un_ids);
            
            foreach ($paginate_data as $v){
                $v->app_qty = round($v->app_qty, $v->decimal_point_place);  
                $v->req_qty = round($v->req_qty, $v->decimal_point_place);  
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
                if(array_key_exists($v->id,$issueDataList)){
                    $issueInfo = $issueDataList[$v->id];
                    $v->issue_qty = round($issueInfo->app_qty, $v->decimal_point_place);
                    $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                    $avail_qty = self::itemQtySubtraction($itemInfo, $v->app_qty,$issueInfo->app_qty);
                    $v->avail_qty = round($avail_qty, $v->decimal_point_place);  
                }else{
                    $v->issue_qty = 0;
                    $v->avail_qty = round($v->app_qty, $v->decimal_point_place);
                }
                if(array_key_exists($v->item_id, $itemWiseStockInfo)){
                    $v->store_qty = $itemWiseStockInfo[$v->item_id]['current_stock'];
                }else{
                    $v->store_qty = 0;
                }
            }
        }
       
        $data['paginate_data'] = $paginate_data;
        return response()->json($data);
    }

    public function page(Request $request){
       return view('inventory::newRequisition.new-requisition'); 
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $voucherInfo = self::getVoucherNo('newreq');
        if($voucherInfo['voucher_no']){
            $data['requisition_user_list'] = User::select('id', 'name')->module()->get();
            $requisition_by_model=User::select('id', 'name')->where('id', Auth::user()->id)->first();
            $data['formData'] = ['voucher_no'=>$voucherInfo['voucher_no'],'voucher_int'=>$voucherInfo['voucher_int'],'numbering'=>$voucherInfo['numbering'], 'date'=>date('Y-m-d'),'date_show'=>date('Y-m-d'), 'due_date'=>date('Y-m-d'),'due_date_show'=>date('Y-m-d'),'requisition_by_model'=>$requisition_by_model,'requisition_by'=>Auth::user()->id, 'requisitionDetailsData'=>[], 'itemAdded'=>'no'];
        }else{
            $data = ['status'=>0, 'message'=>"Setup voucher configuration first"];
        }
        return response()->json($data);
        //return view('inventory::newRequisition.modal.add-new-requisition');
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
                Rule::unique('inventory_new_requisition_info')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id)->where('valid',1);
                })->ignore($id, 'id')
            ],
            'requisition_by' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'due_date' => 'required|date_format:d/m/Y|after_or_equal:date',
            'comments' => 'required|max:255',
        ]);

        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $due_date = DateTime::createFromFormat('d/m/Y', $request->due_date)->format('Y-m-d');
           

        $requisitionDetailsData = $request->requisitionDetailsData;
        if(count($requisitionDetailsData)>0){
            if(!empty($id)){
                $requisitionDetailsData_db = NewRequisitionDetailsModel::module()->valid()->where('new_req_id', $id)->get();
                $requisitionDetailsData_db_keyBy = $requisitionDetailsData_db->keyBy('id')->all(); 
                $db_item_ids = $requisitionDetailsData_db->pluck('item_id')->all(); 
                $req_item_ids = collect($requisitionDetailsData)->pluck('item_id')->all(); 
                $merge_item_ids = collect($db_item_ids)->merge($req_item_ids)->all();
                $item_ids = collect($merge_item_ids)->unique()->values()->all();
            }else{
               $item_ids = collect($requisitionDetailsData)->pluck('item_id')->all(); 
            }
            $itemList = CadetInventoryProduct::module()->whereIn('id', $item_ids)->get()->keyBy('id')->all();
            $flag = true; $msg = []; $item_approval = false;
            // checking fraction, fraction length and if approved item change
            foreach ($requisitionDetailsData as $v):
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
                        $db_data = $requisitionDetailsData_db_keyBy[$details_id];
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
                        "voucher_no" => $request->voucher_no,
                        "voucher_int" => $request->voucher_int,
                        "requisition_by" => $request->requisition_by,
                        "date" => $date,
                        "due_date" => $due_date,
                        "comments" => $request->comments
                    ];

                    if(!empty($id)){
                        $req_id = $id;
                        $newReqInfo = NewRequisitionInfoModel::module()->valid()->findOrFail($id);
                        if($newReqInfo->status==0){ // check info status
                            // date change check 
                            if($item_approval && ($newReqInfo->date!=$date || $newReqInfo->due_date!=$due_date)){
                                $flag = false; 
                                $msg[] = 'Sorry new requisition details item already approved you can not change date';
                            }else{
                                // delete check 
                                $reqDtlDbIds = $requisitionDetailsData_db->pluck('id')->all();
                                $reqDtlIds  = collect($requisitionDetailsData)->pluck('id')->all();
                                $reqDtlIds_diff = collect($reqDtlDbIds)->diff($reqDtlIds)->all();
                                foreach($reqDtlIds_diff as $diffId) {
                                    $db_data = $requisitionDetailsData_db_keyBy[$diffId];
                                    if($db_data->status==1||$db_data->status==2){
                                        $itemInfo = $itemList[$db_data->item_id];
                                        $flag = false;
                                        $msg[] = $itemInfo->product_name.' has already approved and can not delete it'; 
                                    }
                                }

                                if($flag){
                                   $newReqInfo->update([
                                        "requisition_by" => $request->requisition_by,
                                        "date" => $date,
                                        "due_date" => $due_date,
                                        "comments" => $request->comments
                                   ]); 
                                   // delete details data
                                   foreach($reqDtlIds_diff as $diffId) {
                                        NewRequisitionDetailsModel::find($diffId)->delete();
                                   }
                                }
                            }
                        }else{
                           $flag = false; 
                           $msg[] = 'Sorry new requisition already approved';
                        }

                    }else{
                        $save = NewRequisitionInfoModel::create($data);
                        $req_id = $save->id; 

                    }
                    if($flag){
                        foreach ($requisitionDetailsData as $v) {
                            $details_id = @$v['id'];
                            $detailsData  = [
                                'new_req_id'=>$req_id,
                                'item_id'=>$v['item_id'],
                                'req_qty'=>$v['req_qty']
                            ];
                            if($details_id>0){
                                NewRequisitionDetailsModel::module()->valid()->find($details_id)->update($detailsData);;
                            }else{
                               NewRequisitionDetailsModel::create($detailsData);  
                            }
                        }
                       $output = ['status'=>1,'message'=>'New Requisition successfully saved'];
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
        $requisitionInfo  = NewRequisitionInfoModel::module()->valid()
            ->leftJoin('users', 'inventory_new_requisition_info.requisition_by','=', 'users.id')
            ->select('inventory_new_requisition_info.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS req_date, DATE_FORMAT(due_date,'%d/%m/%Y') AS dueDate"), 'users.name')
            ->where('inventory_new_requisition_info.id', $id)
            ->first();
        if(!empty($requisitionInfo)){
            $requisitionDetailsData = NewRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_new_requisition_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->select('inventory_new_requisition_details.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
                ->where('new_req_id', $id)->get(); 
            $requisitionInfo->requisitionDetailsData = $requisitionDetailsData; 
            $data['formData'] = $requisitionInfo;
        }else{
            $data = ['status'=>0, 'message'=>"voucher not found"];
        }
        return response()->json($data);
        //return view('inventory::newRequisition.modal.new-requisition-details');
    }

    public function apporved($id)
    {
        $item_list = self::getItemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $requisitionInfo  = NewRequisitionInfoModel::module()->valid()
            ->leftJoin('users', 'inventory_new_requisition_info.requisition_by','=', 'users.id')
            ->select('inventory_new_requisition_info.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS req_date, DATE_FORMAT(due_date,'%d/%m/%Y') AS dueDate"), 'users.name')
            ->where('inventory_new_requisition_info.id', $id)
            ->first();
        if(!empty($requisitionInfo)){
            $requisitionDetailsData = NewRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_new_requisition_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->select('inventory_new_requisition_details.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
                ->where('new_req_id', $id)->get();

            $auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', 'new_requisition')->where('is_role', 0)->get();
            $step=1; $approval_access=true; $approval_step_log=[];  // find approval access
            if(count($UserVoucherApprovalLayer)>0){
                $UserVoucherApprovalKeyBy = $UserVoucherApprovalLayer->keyBy('approval_id')->all();
                if(array_key_exists($auth_user_id, $UserVoucherApprovalKeyBy)){
                    $step = $UserVoucherApprovalKeyBy[$auth_user_id]->step;
                }else{
                   $approval_access=false; 
                }
            }  

            $voucher_details_ids = $requisitionDetailsData->pluck('id')->all(); 
            $approval_step_log = VoucherApprovalLogModel::module()->valid()
                ->where('voucher_type', 'new_requisition')
                ->whereIn('voucher_details_id', $voucher_details_ids)
                ->where('approval_layer', $step)
                ->where('is_role', 0)
                ->where('approval_id', $auth_user_id)
                ->get()->keyBy('voucher_details_id')->all();          

            $requisitionDetailsData_app = [];
            foreach ($requisitionDetailsData as $v){
                if($approval_access && $v->approval_level==$step && !array_key_exists($v->id, $approval_step_log)){  // pick current step and not approval item 
                    if(empty($v->app_qty)){
                      $v->app_qty = $v->req_qty;
                    }
                    $v->app_qty = round($v->app_qty, $v->decimal_point_place);
                    $v->approve=1;
                    $requisitionDetailsData_app[] = $v;
                }
            }

            $requisitionInfo->requisitionDetailsData = $requisitionDetailsData_app; 
            $data['formData'] = $requisitionInfo;
        }else{
            $data = ['status'=>0, 'message'=>"voucher not found"];
        }
        return response()->json($data);
        //return view('inventory::newRequisition.modal.new-requisition-approved');
    }

    public function apporvedAction(Request $request){
        $id = $request->id;
        $requisitionDetailsData = $request->requisitionDetailsData;
        if(!empty($id)){
            $item_ids = collect($requisitionDetailsData)->pluck('item_id')->all();
            $itemList = CadetInventoryProduct::module()->whereIn('id', $item_ids)->get()->keyBy('id')->all();

            $auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', 'new_requisition')->get();
            $step=1; $approval_access=true; $approval_step_log=[];  // step and approval access
            if(count($UserVoucherApprovalLayer)>0){
                $UserVoucherApprovalKeyBy = $UserVoucherApprovalLayer->keyBy('approval_id')->all();
                if(array_key_exists($auth_user_id, $UserVoucherApprovalKeyBy)){
                    $step = $UserVoucherApprovalKeyBy[$auth_user_id]->step;
                }else{
                   $approval_access=false; 
                }

                $UserVoucherApprovalLastLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', 'new_requisition')->orderBy('step', 'desc')->first();
                $last_step = $UserVoucherApprovalLastLayer->step;
            }else{
                $last_step = 1;
            }

            $flag = true; $msg = []; $checkApprove = false;
            // checking fraction, fraction length and if approved item change
            foreach ($requisitionDetailsData as $v):
                $app_qty = (float)$v['app_qty'];
                if($v['approve']==1 && $app_qty>0){
                    $checkApprove=true;
                    if(array_key_exists($v['item_id'], $itemList)){
                        $itemInfo = $itemList[$v['item_id']];
                        // franction qty check
                        if($itemInfo->has_fraction==1){
                            if(self::isFloat($v['app_qty'])){
                                $explodeQty = explode('.', $v['app_qty']);
                                if(strlen($explodeQty[1])>$itemInfo->decimal_point_place){
                                    $flag = false;
                                    $msg[] = $itemInfo->product_name.' has allow '.$itemInfo->decimal_point_place.' decimal places'; 
                                }
                            }

                        }else{
                            if(self::isFloat($v['app_qty'])){
                                $flag = false;
                                $msg[] = $itemInfo->product_name.' has no decimal places'; 
                            }
                        }                    
                        // item approval check
                        if($v['status']==1||$v['status']==2){
                            $flag = false;
                            $msg[] = $itemInfo->product_name.' has already approved and can not change qty'; 
                        }
                        // qty check 
                        $app_qty = round($v['app_qty'], $v['decimal_point_place']);
                        $req_qty = round($v['req_qty'], $v['decimal_point_place']);
                        if($app_qty>$req_qty){
                            $flag = false;
                            $msg[] = $itemInfo->product_name.' can not approved more then request qty';
                        }
                    }else{
                       $flag = false;
                       $msg[] = 'Item not found'; 
                    }
                }
            endforeach;
            if($flag && $checkApprove){
                $app_log_comments = $request->app_log_comments;
                DB::beginTransaction();
                try{
                    foreach ($requisitionDetailsData as $v){
                        $app_qty = (float)$v['app_qty'];
                        $req_qty = (float)$v['req_qty'];
                        if($v['approve']==1 && $app_qty>0){
                            if($step==$last_step){   // last step status update
                                if($app_qty==$req_qty){
                                    $status=1;
                                }else{
                                    $status=2;
                                }
                            }else{
                               $status=$v['status']; 
                            }
                            NewRequisitionDetailsModel::valid()->find($v['id'])->update([
                                'status'=>$status,
                                'app_qty'=>$app_qty,
                                'approval_level'=>$step+1
                            ]);
                            if($app_qty==$req_qty){
                                $log_status=1;
                            }else{
                                $log_status=2;
                            }
                            VoucherApprovalLogModel::create([
                                'date'=>date('Y-m-d H:i:s'),
                                'voucher_id'=>$id,
                                'voucher_details_id'=>$v['id'],
                                'voucher_type'=>'new_requisition',
                                'approval_id'=>$auth_user_id,
                                'approval_layer'=>$step,
                                'action_status'=>$log_status,
                                'comments'=>$app_log_comments,
                                'institute_id'=>self::getInstituteId(),
                                'campus_id'=>self::getCampusId(),
                            ]);
                        }
                    }
                    // update master status base on all app
                    $requisitionDetailsData = NewRequisitionDetailsModel::module()->valid()->where('new_req_id', $id)->get();
                    $all_approved = true; $req_status=1;
                    foreach ($requisitionDetailsData as $v) {
                        if($all_approved){
                            if($v->app_qty>0 && $v->status==1 || $v->status==2){ // check all details row are app qty
                                if($v->status==2){
                                    $req_status=2;
                                }
                            }else{
                               $all_approved=false;  
                            }
                        }else{
                            break;
                        }
                    }
                    if($all_approved){
                        NewRequisitionInfoModel::module()->valid()->find($id)->update([
                            'status'=>$req_status
                        ]);
                    }
                    $data = ['status'=>1, 'message'=>'New Requisition item successfully approved'];
                    DB::commit();
                } catch (Throwable $e) {
                    DB::rollback();
                    throw $e;
                }  

            }else{
                if(!$checkApprove){
                    $data = ['status'=>0, 'message'=>'At least one item is checked for approval'];
                }else{
                    $data = ['status'=>0, 'message'=>$msg];
                }
            }
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
        $voucherInfo = NewRequisitionInfoModel::module()->valid()
            ->select('id','voucher_no','voucher_int','requisition_by','comments','status','date','due_date')->find($id);
        $voucherInfo->date_show = $voucherInfo->date;  
        $voucherInfo->due_date_show = $voucherInfo->due_date;  
        if(!empty($voucherInfo)){
            $data['requisition_user_list'] = User::select('id', 'name')->module()->get();
            $requisition_by_model=User::select('id', 'name')->where('id', $voucherInfo->requisition_by)->first();
            $voucherInfo->requisition_by_model = $requisition_by_model;
            $voucherInfo->itemAdded = 'yes';

            $requisitionDetailsData = NewRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_new_requisition_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->select('inventory_new_requisition_details.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
                ->where('new_req_id', $id)->get(); 
            if(count($requisitionDetailsData)>0){
                $voucherInfo->numbering = true;
                $voucherInfo->requisitionDetailsData = $requisitionDetailsData;
                $total_qty = 0;
                foreach ($requisitionDetailsData as $v) {
                    $v->item_id_model = (object)['item_id'=>$v->item_id, 'product_name'=>$v->product_name, 'uom'=>$v->uom, 'decimal_point_place'=>$v->decimal_point_place];
                   $total_qty+= $v->req_qty;
                }
                $voucherInfo->totalQty = $total_qty;
            }else{
                $voucherInfo->requisitionDetailsData = [];
                $voucherInfo->totalQty = 0;
            }
            
            $data['formData'] = $voucherInfo; 
        }else{
            $data = ['status'=>0, 'message'=>"voucher not found"];
        }
        return response()->json($data);
        //return view('inventory::newRequisition.modal.edit-new-requisition');
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
                $deleteData = NewRequisitionDetailsModel::module()->valid()->find($id);
                if(!empty($deleteData)){
                    if($deleteData->status==1||$deleteData->status==2){  // check status
                        $output = ['status'=>0, 'message'=>'Sorry Item requisiton already approved'];
                    }else{
                        $new_req_id = $deleteData->new_req_id; 
                        NewRequisitionDetailsModel::module()->valid()->find($id)->delete();
                        $checkDetailsItem = NewRequisitionDetailsModel::module()->valid()->where('new_req_id', $new_req_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            NewRequisitionInfoModel::module()->valid()->find($new_req_id)->delete(); 
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
                $new_req_ids = []; $flag = true; $msg =[]; 
                foreach ($delIds as $del_id){
                    $deleteData = NewRequisitionDetailsModel::module()->valid()->find($del_id);
                    if($deleteData->status==1||$deleteData->status==2){
                        $itemInfo = CadetInventoryProduct::module()->find($deleteData->item_id);
                        $flag = false;
                        $msg[] = $itemInfo->product_name.' has requisition qty approval';
                    }
                    $new_req_ids[] = $deleteData->new_req_id;
                }
                if($flag){
                    foreach ($delIds as $del_id){
                        NewRequisitionDetailsModel::valid()->find($del_id)->delete();
                    }
                    $new_req_unique_ids = collect($new_req_ids)->unique()->values()->all();
                    foreach ($new_req_unique_ids as $new_req_id) {
                        $checkDetailsItem = NewRequisitionDetailsModel::module()->valid()->where('new_req_id', $new_req_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            NewRequisitionInfoModel::module()->valid()->find($new_req_id)->delete(); 
                        }
                    }

                    $output = ['status'=>1, 'message'=>'New Requisition item successfully deleted'];
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

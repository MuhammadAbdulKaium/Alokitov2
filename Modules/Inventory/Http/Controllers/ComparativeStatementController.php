<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\ComparativeStatementInfoModel;
use Modules\Inventory\Entities\ComparativeStatementDetailsModel;
use Modules\Inventory\Entities\PurchaseRequisitionInfoModel;
use Modules\Inventory\Entities\PurchaseRequisitionDetailsModel;
use Modules\Inventory\Entities\PurchaseOrderDetailsModel;
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
use App;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Accounts\Entities\SignatoryConfig;
use Modules\Setting\Entities\Institute;

class ComparativeStatementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use InventoryHelper;
    private $academicHelper;
    public function __construct(Request $request , AcademicHelper $academicHelper)
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
    
    
    public function index(Request $request)
    {
        $listPerPage = $request->input('listPerPage');
        $search_key = $request->input('search_key');
        //dd($search_key);
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
        if($sort=='id') $sort='inventory_comparative_statement_details_info.id';

        $item_list = self::itemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $data['item_list'] = self::mergeEmtyArryObj($item_list, ['item_id'=>0, 'product_name'=>'Select item']);
        $voucher_list = ComparativeStatementDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_comparative_statement_info', 'inventory_comparative_statement_info.id','=', 'inventory_comparative_statement_details_info.cs_id')
            ->select('inventory_comparative_statement_details_info.cs_id as id', 'inventory_comparative_statement_info.voucher_no')
            ->orderBy('voucher_int', 'desc')
            ->groupBy(['cs_id','voucher_no'])
            ->get();

        $data['voucher_list'] =  self::mergeEmtyArryObj($voucher_list, ['id'=>0, 'voucher_no'=>'Select voucher']);

        $paginate_data_query = ComparativeStatementDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_comparative_statement_info', 'inventory_comparative_statement_info.id','=', 'inventory_comparative_statement_details_info.cs_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_comparative_statement_details_info.item_id')
            ->select('inventory_comparative_statement_details_info.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS stock_in_date"), 'inventory_comparative_statement_info.voucher_no', 'cadet_stock_products.product_name', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
            ->when($item_id, function($query, $item_id){
                $query->where('inventory_comparative_statement_details_info.item_id',$item_id);
            })
            ->when($voucher_id, function($query, $voucher_id){
                $query->where('inventory_comparative_statement_details_info.cs_id',$voucher_id);
            })
            ->when($from_date, function($query, $from_date){
                $query->where('inventory_comparative_statement_info.date','>=',$from_date);
            })
            ->when($to_date, function($query, $to_date){
                $query->where('inventory_comparative_statement_info.date','<=',$to_date);
            })
            ->when($status, function($query, $status){
                if($status=='p') $status=0;
                $query->where('inventory_comparative_statement_details_info.status',$status);
            })
            ->where(function($query)use($search_key){
                if(!empty($search_key)){
                    $query->where('inventory_comparative_statement_info.voucher_no','LIKE','%'.$search_key.'%')
                        ->orWhere('cadet_stock_products.product_name','LIKE','%'.$search_key.'%');
                }
            })
            ->orderBy($sort,$order);     

        $paginate_data = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);
        if(count($paginate_data)>0){
            $auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', 'cs')->where('is_role', 0)->get();
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
                ->where('voucher_type', 'cs')
                ->where('is_role', 0)
                ->whereIn('voucher_details_id', $voucher_details_ids)
                ->orderBy('inventory_voucher_approval_log.approval_layer', 'asc')
                ->get()->groupBy('voucher_details_id')->all();
            // check if his step is approval or not
            $approval_step_log = VoucherApprovalLogModel::module()->valid()
                ->where('voucher_type', 'cs')
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
        return view('inventory::purchase.comparativeStatement.comparative-statement');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('inventory::purchase.comparativeStatement.comparative-statement-add-form');
    }

    public function comparativeStatementCreateData(){
        $voucherInfo = self::checkInvVoucher(14);
        if($voucherInfo['voucher_conf']){
            $data['instruction_user_list'] = User::select('id', 'name')->module()->get();
            $instruction_of_model=User::select('id', 'name')->where('id', Auth::user()->id)->first();
            $instruction_name = $instruction_of_model->name;
            $data['campus_list'] = Campus::select('id', 'name')->where('institute_id', self::getInstituteId())->where('id',self::getCampusId())->get();
            $campus_id_model=Campus::select('id', 'name')->where('id', self::getCampusId())->first();
            $campus_name = $campus_id_model->name;
            $data['formData'] = ['voucher_no'=>$voucherInfo['voucher_no'],'voucher_config_id'=>$voucherInfo['voucher_config_id'],'auto_voucher'=>$voucherInfo['auto_voucher'], 'date'=>date('d/m/Y'), 'due_date'=>date('d/m/Y'),'campus_id_model'=>$campus_id_model,'campus_id'=>self::getCampusId(),'campus_name'=>$campus_name,'instruction_of_model'=>$instruction_of_model,'instruction_of'=>Auth::user()->id,'instruction_name'=>$instruction_name,'reference_type'=>'', 'voucherDetailsData'=>[], $vendorData=[], $price_catalog_component_data=[], 'itemAdded'=>'no','generateCS'=>'no','check_mandatory'=>0, 'vendor_id'=>0];
        }else{
            $data = ['status'=>0, 'message'=>"Setup voucher configuration first"];
        }
        return response()->json($data);
    }

    public function csReferenceList(Request $request){
        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $campus_id = $request->campus_id;
        $item_list = self::itemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $refItemList = PurchaseRequisitionDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('inventory_purchase_requisition_info', 'inventory_purchase_requisition_info.id','=', 'inventory_purchase_requisition_details.req_id')
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_purchase_requisition_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id','=', 'cadet_stock_products.unit')
            ->select('inventory_purchase_requisition_details.id as reference_details_id','inventory_purchase_requisition_details.req_id as reference_id','inventory_purchase_requisition_details.item_id','inventory_purchase_requisition_details.req_qty','inventory_purchase_requisition_details.app_qty as req_app_qty','inventory_purchase_requisition_details.remarks',DB::raw("DATE_FORMAT(inventory_purchase_requisition_info.date,'%d/%m/%Y') AS req_date, DATE_FORMAT(inventory_purchase_requisition_info.due_date,'%d/%m/%Y') AS due_date"), 'inventory_purchase_requisition_info.voucher_no as ref_voucher_name', 'cadet_stock_products.product_name', 'cadet_stock_products.sku', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'), 'cadet_inventory_uom.symbol_name as uom', 'cadet_stock_products.has_fraction','cadet_stock_products.round_of')
            ->where('inventory_purchase_requisition_info.due_date','<=',$date)
            ->whereIn('inventory_purchase_requisition_details.ref_use',[0,1])
            ->whereIn('inventory_purchase_requisition_details.status',[1,2])
            ->where('inventory_purchase_requisition_details.need_cs',1)
            ->orderBy('inventory_purchase_requisition_info.due_date','asc')
            ->get(); 
        $ref_details_ids = $refItemList->pluck('reference_details_id')->all();
        $ref_item_ids =  $refItemList->pluck('item_id')->all(); 
        $csDataList  = ComparativeStatementDetailsModel::module()->select('reference_details_id','qty')->whereIn('reference_details_id', $ref_details_ids)->whereIn('status',[1,2])->where('reference_type', 'purchase-requisition')->get()->keyBy('reference_details_id')->all();

        foreach ($refItemList as $v) {
            if(array_key_exists($v->reference_details_id, $csDataList)){
                $csInfo = $csDataList[$v->reference_details_id];
                $itemInfo = (object)['has_fraction'=>$v->has_fraction,'decimal_point_place'=>$v->decimal_point_place,'round_of'=>$v->round_of];
                $avail_qty = self::itemQtySubtraction($itemInfo, $v->req_app_qty,$csInfo->qty);

            }else{
                $avail_qty = $v->req_app_qty;
            }
            $v->avail_qty = round($avail_qty, $v->decimal_point_place);
            $v->qty = round($v->avail_qty, $v->decimal_point_place);
            $v->req_app_qty = round($v->req_app_qty, $v->decimal_point_place);
            $v->ref_check=0;
        }
        return response()->json($refItemList);
    }


    public function generateCS(Request $request){
        $campus_id = $request->campus_id;
        $check_mandatory = $request->check_mandatory;
        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $refDataList = $request->refDataList;
        $item_ids = collect($refDataList)->pluck('item_id')->all();
        $institute_id = self::getInstituteId(); 

        $price_catalog_data = []; $catalogue_uniq_id=[]; $vendor_found = 'yes';
        foreach($refDataList as $ref):   // check item qty wise price catalogue and collect catalog id
            $priceCatalogCheck  = DB::table('inventory_price_catalogue_details as pcTbl')
                ->select('pcTbl.*')
                ->where('pcTbl.item_id', $ref['item_id'])
                ->where('pcTbl.applicable_from', '<=',$date)
                ->where('pcTbl.from_qty', '<=',$ref['avail_qty'])
                ->where('pcTbl.to_qty', '>=',$ref['avail_qty'])
                ->whereRaw('pcTbl.applicable_from=(select MAX(pc.applicable_from) as max_applicable_date from inventory_price_catalogue_details as pc where pc.item_id=pcTbl.item_id and pc.catalogue_uniq_id=pcTbl.catalogue_uniq_id and pc.campus_id="'.$campus_id.'" and pc.institute_id="'.$institute_id.'" and pc.valid=1 and pc.status=1 and pc.from_qty<="'.$ref['avail_qty'].'" and  pc.to_qty>="'.$ref['avail_qty'].'")')
                ->where('pcTbl.status', 1)
                ->where('pcTbl.valid', 1)
                ->where('pcTbl.campus_id', self::getCampusId())
                ->where('pcTbl.institute_id', self::getInstituteId())
                ->orderBy('pcTbl.id', 'desc')
                ->get();

            if(count($priceCatalogCheck)>0):
                foreach($priceCatalogCheck as $pcat):
                    $catalogue_uniq_id[] = $pcat->catalogue_uniq_id; 
                    $price_catalog_data[$ref['reference_details_id'].'_'.$ref['item_id'].'_'.$pcat->catalogue_uniq_id] = $pcat;
                endforeach;
            endif;
        endforeach;
        $cat_unique_id  = collect($catalogue_uniq_id)->unique()->values()->all();
        $vendorInfo = VendorModel::select('id','name', 'gl_code', 'credit_limit', 'credit_priod', 'price_cate_id')
            ->whereIn('price_cate_id', $cat_unique_id)->get();
        $vendorData = []; $price_catalog_component_data = [];
        if(count($vendorInfo)>0){
            $vendor_ids = $vendorInfo->pluck('id')->all(); 
            $terms_conditions_grpBy  = collect(DB::table('inventory_vendor_terms_condition')->whereIn('vendor_id', $vendor_ids)->get())->groupBy('vendor_id')->all();
            $fields = ['rate', 'discount','vat_per','vat_type'];
            if($check_mandatory==1){   // need to match all item data 
                $has_vendor = false;
                foreach($vendorInfo as $v):   // vendor check and catalog data formating
                    $allDataCheck=true;
                    foreach($refDataList as $r):
                        if($allDataCheck){
                            $access_id = $r['reference_details_id'].'_'.$r['item_id'].'_'.$v->price_cate_id;
                            if(array_key_exists($access_id, $price_catalog_data)){
                                $catalog_data = $price_catalog_data[$access_id]; 
                                foreach($fields as $field){
                                    if($field=='vat_type'){
                                        $price_catalog_component_data[$field.'_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = $catalog_data->{$field};
                                    }else{
                                        $price_catalog_component_data[$field.'_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = $catalog_data->{$field}+0;
                                    }
                                }
                                // price calculation
                                if($r['has_fraction']==1 && self::isFloat($r['avail_qty'])){
                                    $calInfo = (object)['has_fraction'=>$r['has_fraction'],'round_of'=>$r['round_of'],'decimal_point_place'=>$r['decimal_point_place'],'qty'=>$r['avail_qty'], 'rate'=>$catalog_data->rate];
                                    $total_amount = self::rateQtyMultiply($calInfo);
                                }else{
                                   $total_amount = $r['avail_qty'] * $catalog_data->rate;
                                }
                                $price_catalog_component_data['amount_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = $total_amount;
                                $net_amount = $total_amount;
                                if(!empty($catalog_data->vat_per)){
                                    if($catalog_data->vat_type=='fixed'){
                                        $vat_amount = $catalog_data->vat_per; 
                                    }else{
                                        $vat_amount_cal = ($total_amount/100)*$catalog_data->vat_per;
                                        $vat_amount = round($vat_amount_cal,6);
                                    }
                                    $net_amount +=  $vat_amount;
                                }
                                $price_catalog_component_data['vat_amount_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = $vat_amount;

                                if(!empty($catalog_data->discount)){
                                    $net_amount -=  $catalog_data->discount;
                                }
                                $price_catalog_component_data['net_amount_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = $net_amount;

                            }else{
                                $allDataCheck = false;
                            }
                        }else{
                            break;
                        }
                    endforeach;
                    if($allDataCheck){
                        $has_vendor = true;
                        $terms_condition = (array_key_exists($v->id, $terms_conditions_grpBy))? $terms_conditions_grpBy[$v->id]:[];
                        $v->terms_condition = $terms_condition;
                        $v->credit_limit = $v->credit_limit+0;
                        $vendorData[] = $v;
                    }
                endforeach;
                if(!$has_vendor){  // check if any vendor is found
                    $vendor_found = 'no';
                }
            }else{
                foreach($vendorInfo as $v){
                    foreach($refDataList as $r):
                        $access_id = $r['reference_details_id'].'_'.$r['item_id'].'_'.$v->price_cate_id;
                        if(array_key_exists($access_id, $price_catalog_data)){
                            $catalog_data = $price_catalog_data[$access_id]; 
                            foreach($fields as $field){
                                if($field=='vat_type'){
                                    $price_catalog_component_data[$field.'_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = $catalog_data->{$field};
                                }else{
                                    $price_catalog_component_data[$field.'_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = $catalog_data->{$field}+0;
                                }
                            }
                            // price calculation
                            if($r['has_fraction']==1 && self::isFloat($r['avail_qty'])){
                                $calInfo = (object)['has_fraction'=>$r['has_fraction'],'round_of'=>$r['round_of'],'decimal_point_place'=>$r['decimal_point_place'],'qty'=>$r['avail_qty'], 'rate'=>$catalog_data->rate];
                                $total_amount = self::rateQtyMultiply($calInfo);
                            }else{
                               $total_amount = $r['avail_qty'] * $catalog_data->rate;
                            }
                            $price_catalog_component_data['amount_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = $total_amount;
                            $net_amount = $total_amount;
                            if(!empty($catalog_data->vat_per)){
                                if($catalog_data->vat_type=='fixed'){
                                    $vat_amount = $catalog_data->vat_per; 
                                }else{
                                    $vat_amount_cal = ($total_amount/100)*$catalog_data->vat_per;
                                    $vat_amount = round($vat_amount_cal,6);
                                }
                                $net_amount +=  $vat_amount;
                            }
                            $price_catalog_component_data['vat_amount_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = $vat_amount;
                            if(!empty($catalog_data->discount)){
                                $net_amount -=  $catalog_data->discount;
                            }
                            $price_catalog_component_data['net_amount_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = $net_amount;
                        }else{
                            foreach($fields as $field){
                                $price_catalog_component_data[$field.'_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = ($field=='vat_type')?'fixed':0;
                            }
                            $price_catalog_component_data['vat_amount_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = 0;
                            $price_catalog_component_data['amount_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = 0;
                            $price_catalog_component_data['net_amount_'.$r['reference_details_id'].'_'.$r['item_id'].'_'.$v->id] = 0;
                        }
                    endforeach;
                    $terms_condition = (array_key_exists($v->id, $terms_conditions_grpBy))? $terms_conditions_grpBy[$v->id]:[];
                    $v->terms_condition = $terms_condition;
                    $v->credit_limit = $v->credit_limit+0;
                    $vendorData[] = $v;
                } 
            }
        }else{
            $vendor_found = 'no';
        }

        $data['vendor_found'] = $vendor_found;
        $data['voucherDetailsData'] = $refDataList;
        $data['vendorData'] = $vendorData;
        $data['price_catalog_component_data'] = $price_catalog_component_data;
        return response()->json($data);

        
    }



    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $campus_id = self::getCampusId();
        $institute_id = self::getInstituteId();
        $validated = $request->validate([
            'voucher_no' => 'required|max:100',
            'campus_id' => 'required',
            'instruction_of' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'due_date' => 'required|date_format:d/m/Y|after_or_equal:date',
            'reference_type'=>'required'
        ]);

        $date = DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $due_date = DateTime::createFromFormat('d/m/Y', $request->due_date)->format('Y-m-d');
        $vendor_id = $request->vendor_id;
        $voucherDetailsData = $request->voucherDetailsData;
        $vendorData = $request->vendorData;
        $price_catalog_component_data = $request->price_catalog_component_data;
        if(!empty($vendor_id)){
            DB::beginTransaction();
            try {
                $data = [
                    "instruction_of" => $request->instruction_of,
                    "date" => $date,
                    "due_date" => $due_date,
                    "comments" => $request->comments,
                    "reference_type" => $request->reference_type,
                    "vendor_id" => $vendor_id,
                    "check_mandatory"=>$request->check_mandatory
                ];
                $auto_voucher = $request->auto_voucher;  // voucher type
                $flag=true;
                if($auto_voucher){  // auto voucher configuration
                    $voucherInfo = self::getVoucherNo('cs');
                    if($voucherInfo['voucher_no']){
                        $data['voucher_no'] = $voucherInfo['voucher_no'];
                        $data['voucher_int'] = $voucherInfo['voucher_int'];
                        $data['voucher_config_id'] = $voucherInfo['voucher_config_id'];
                    }else{
                        $flag=false;
                        $msg = $voucherInfo['msg'];  
                    }
                }else{  // menual voucher 
                    $checkVoucher = ComparativeStatementInfoModel::module()->valid()->where('voucher_no', $request->voucher_no)->first();
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
                    $save = ComparativeStatementInfoModel::create($data);
                    $cs_id = $save->id; 
                    $vendorInfo = VendorModel::find($vendor_id);
                    $vendor_cs_history_data =[]; $vendor_terms_con_history_data =[];
                    foreach($voucherDetailsData as $ref):   // check item qty wise price catalogue and collect catalog id
                        $catalog_data = PriceCatelogueDetailsModel::module()->valid()
                            ->where('catalogue_uniq_id', $vendorInfo->price_cate_id)
                            ->where('item_id', $ref['item_id'])
                            ->where('applicable_from', '<=',$date)
                            ->where('from_qty','<=', $ref['avail_qty'])
                            ->where('to_qty','>=', $ref['avail_qty'])
                            ->where('status',1)
                            ->orderBy('applicable_from', 'desc')->first();
                        if(!empty($catalog_data)){
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
                                $vat_per = 0;
                            }
                            if(!empty($catalog_data->discount)){
                                $net_amount -=  $catalog_data->discount;
                                $discount = $catalog_data->discount;
                            }else{
                                $discount = 0;
                            }
                            $cs_details_data = [
                                'cs_id'=>$cs_id,
                                'vendor_id'=>$vendor_id,
                                'item_id'=>$ref['item_id'],
                                'qty'=>$ref['avail_qty'],
                                'rate'=>$catalog_data->rate,
                                'amount'=>$amount,
                                'discount'=>$discount,
                                'vat_per'=>$vat_per,
                                'vat_type'=>$catalog_data->vat_type,
                                'vat_amount'=>$vat_amount,
                                'net_amount'=>$net_amount,
                                'reference_type'=>$request->reference_type,
                                'reference_id'=>$ref['reference_id'],
                                'reference_details_id'=>$ref['reference_details_id'],
                                'remarks'=>$ref['remarks']
                            ];
                            
                        }else{
                            $cs_details_data = [
                                'cs_id'=>$cs_id,
                                'vendor_id'=>$vendor_id,
                                'item_id'=>$ref['item_id'],
                                'qty'=>$ref['avail_qty'],
                                'rate'=>0,
                                'amount'=>0,
                                'discount'=>0,
                                'vat_per'=>0,
                                'vat_type'=>'fixed',
                                'vat_amount'=>0,
                                'net_amount'=>0,
                                'reference_type'=>$request->reference_type,
                                'reference_id'=>$ref['reference_id'],
                                'reference_details_id'=>$ref['reference_details_id'],
                                'remarks'=>$ref['remarks']
                            ];  
                        }
                        $saveDtl = ComparativeStatementDetailsModel::create($cs_details_data);
                        // vedor history data 
                        foreach($vendorData as $vd):
                            $vendor_cs_history_data[] = [
                                'cs_id'=>$cs_id,
                                'cs_details_id'=>$saveDtl->id,
                                'vendor_id'=>$vd['id'],
                                'vendor_name'=>$vd['name'],
                                'gl_code'=>$vd['gl_code'],
                                'credit_limit'=>$vd['credit_limit'],
                                'credit_priod'=>$vd['credit_priod'],
                                'item_id'=>$ref['item_id'],
                                'qty'=>$ref['avail_qty'],
                                'rate'=>$price_catalog_component_data['rate_'.$ref['reference_details_id'].'_'.$ref['item_id'].'_'.$vd['id']],
                                'amount'=>$price_catalog_component_data['amount_'.$ref['reference_details_id'].'_'.$ref['item_id'].'_'.$vd['id']],
                                'discount'=>$price_catalog_component_data['discount_'.$ref['reference_details_id'].'_'.$ref['item_id'].'_'.$vd['id']],
                                'vat_per'=>$price_catalog_component_data['vat_per_'.$ref['reference_details_id'].'_'.$ref['item_id'].'_'.$vd['id']],
                                'vat_type'=>$price_catalog_component_data['vat_type_'.$ref['reference_details_id'].'_'.$ref['item_id'].'_'.$vd['id']],
                                'vat_amount'=>$price_catalog_component_data['vat_amount_'.$ref['reference_details_id'].'_'.$ref['item_id'].'_'.$vd['id']],
                                'net_amount'=>$price_catalog_component_data['net_amount_'.$ref['reference_details_id'].'_'.$ref['item_id'].'_'.$vd['id']],
                                'reference_type'=>$request->reference_type,
                                'reference_id'=>$ref['reference_id'],
                                'reference_details_id'=>$ref['reference_details_id'],
                                'institute_id'=>self::getInstituteId(),
                                'campus_id'=>self::getCampusId(),
                            ];

                        endforeach;
                    endforeach;
                    // cs vendor history data save 
                    DB::table('inventory_comparative_statement_vendor_history')->insert($vendor_cs_history_data);
                    // vendor terms and condition
                    foreach($vendorData as $vd):
                        foreach ($vd['terms_condition'] as $vt) {
                            $vendor_terms_con_history_data[] = [
                                'cs_id'=>$cs_id,
                                'vendor_id'=>$vd['id'],
                                'term_condition'=>$vt['term_condition'],
                            ];
                        }
                    endforeach;
                    DB::table('inventory_comparative_statement_vendor_terms_condition_history')->insert($vendor_terms_con_history_data);
                    $output = ['status'=>1,'message'=>'Comparative Statement successfully saved'];
                    DB::commit();
                }else{
                  $output = ['status'=>0,'message'=>$msg];  
                }
            } catch (Throwable $e) {
                DB::rollback();
                throw $e;
            }  

        }else{
            $output = ['status'=>0,'message'=>"Please Select a vendor"]; 
        }

        return response()->json($output);
        
    }

    public function voucherApproval(Request $request, $id=0)
    {
        DB::beginTransaction();
        try{
            $auth_user_id = Auth::user()->id;
            if($id>0){
                $approvalData = ComparativeStatementDetailsModel::module()->valid()->find($id);
                if(!empty($approvalData)){
                    if($approvalData->status==0){
                        $approval_info = self::getApprovalInfo('cs');
                        $step = $approval_info['step'];
                        $approval_access = $approval_info['approval_access'];
                        $last_step = $approval_info['last_step'];
                        if($approval_access && $approvalData->approval_level==$step){
                            $flag=true;
                            if($step==$last_step){
                                $itemInfo = CadetInventoryProduct::find($approvalData->item_id);
                                if(!empty($itemInfo)){
                                    $purchaseRequisitionDetailsInfo = PurchaseRequisitionDetailsModel::module()->valid()->where('id', $approvalData->reference_details_id)->whereIn('ref_use',[0,1])->first();
                                    if(!empty($purchaseRequisitionDetailsInfo)){ // update ref use
                                        $purchaseRequisitionDetailsInfo->update(['ref_use'=>($purchaseRequisitionDetailsInfo->app_qty>$approvalData->qty)?1:3]);
                                    }else{
                                        $flag=false;
                                        $output = ['status'=>0, 'message'=>'Purchase requisition reference already used']; 
                                    }
                                    if($flag){
                                        $approvalData->update([
                                            'status'=>1,
                                            'approval_level'=>$step+1
                                        ]);
                                        // update master status base on all app
                                        self::masterVoucherUpdate($approvalData);
                                    }

                                }else{
                                   $flag=false;
                                   $output = ['status'=>0, 'message'=>'Comparative Statement Item not found']; 
                                }

                            }else{ // end if($step==$last_step){
                                $approvalData->update([
                                    'approval_level'=>$step+1
                                ]); 
                            }
                            if($flag){
                                VoucherApprovalLogModel::create([
                                    'date'=>date('Y-m-d H:i:s'),
                                    'voucher_id'=>$approvalData->cs_id,
                                    'voucher_details_id'=>$approvalData->id,
                                    'voucher_type'=>'cs',
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
        $cs_id = $approvalData->cs_id;
        $reference_id = $approvalData->reference_id;

        $checkPendingStatus = ComparativeStatementDetailsModel::module()->valid()
            ->where('cs_id', $cs_id)
            ->where(function($query){
                $query->where('status', 0)
                     ->orWhere('status',3);

            })->first();

        ComparativeStatementInfoModel::module()->valid()->find($cs_id)->update([
            'status'=>(!empty($checkPendingStatus))?2:1
        ]);

        // update reference data 
        $checkPurReqUse = PurchaseRequisitionDetailsModel::module()->valid()
            ->where('req_id',$reference_id)
            ->where('need_cs',1)
            ->where(function($query){
                $query->where('ref_use', 0)
                    ->orWhere('ref_use',1);

            })->first();
        PurchaseRequisitionInfoModel::module()->valid()->find($reference_id)->update(['ref_use'=> (!empty($checkPurReqUse))?1:3]);


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
        $voucherInfo  = ComparativeStatementInfoModel::module()->valid()
            ->join('setting_campus', 'setting_campus.id','=', 'inventory_comparative_statement_info.campus_id')
            ->join('inventory_vendor_info', 'inventory_vendor_info.id','=', 'inventory_comparative_statement_info.vendor_id')
            ->leftJoin('users', 'inventory_comparative_statement_info.instruction_of','=', 'users.id')
            ->select('inventory_comparative_statement_info.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS cs_date, DATE_FORMAT(due_date,'%d/%m/%Y') AS due_date_formate"), 'users.name', 'setting_campus.name as campus_name', 'inventory_vendor_info.name as vendor_name')
            ->where('inventory_comparative_statement_info.id', $id)
            ->first();
        if(!empty($voucherInfo)){
            $voucherDetailsData = ComparativeStatementDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_comparative_statement_details_info.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->select('inventory_comparative_statement_details_info.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'),'cadet_stock_products.sku')
                ->where('cs_id', $id)->get(); 
            $voucherInfo->voucherDetailsData = $voucherDetailsData;
            $data['formData'] = $voucherInfo;
        }else{
            $data = ['status'=>0, 'message'=>"Comparative Statement Voucher not found"];
        }
        return response()->json($data);
    }
    public function print($id){
        $item_list = self::getItemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $voucherInfo  = ComparativeStatementInfoModel::module()->valid()
            ->join('setting_campus', 'setting_campus.id','=', 'inventory_comparative_statement_info.campus_id')
            ->join('inventory_vendor_info', 'inventory_vendor_info.id','=', 'inventory_comparative_statement_info.vendor_id')
            ->leftJoin('users', 'inventory_comparative_statement_info.instruction_of','=', 'users.id')
            ->select('inventory_comparative_statement_info.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS cs_date, DATE_FORMAT(due_date,'%d/%m/%Y') AS due_date_formate"), 'users.name', 'setting_campus.name as campus_name', 'inventory_vendor_info.name as vendor_name')
            ->where('inventory_comparative_statement_info.id', $id)
            ->first();
        if(!empty($voucherInfo)){
            $voucherDetailsData = ComparativeStatementDetailsModel::module()->itemAccess($item_ids)->valid()
                ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_comparative_statement_details_info.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->select('inventory_comparative_statement_details_info.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'),'cadet_stock_products.sku')
                ->where('cs_id', $id)->get(); 
        }
        $institute = Institute::findOrFail(self::getInstituteId());
        $pdf = App::make('dompdf.wrapper');
        $signatories = SignatoryConfig::with('employeeInfo.singleUser', 'employeeInfo.singleDesignation', 'employeeInfo.singleDepartment')->where([
            ['reportName', 'comparative-statement'],
            ['campus_id', $this->academicHelper->getCampus()],
            ['institute_id', $this->academicHelper->getInstitute()]
        ]);
        $totalSignatory = $signatories->count();
        $signatories = $signatories->get();

        $pdf->loadView('inventory::purchase.comparativeStatement.comparative-statement-print', compact('voucherDetailsData', 'voucherInfo', 'institute', 'totalSignatory', 'signatories'))->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    public function csHistory($id){
        $item_list = self::getItemList($this);
        $item_ids = $item_list->pluck('item_id')->all();
        $voucherInfo  = ComparativeStatementInfoModel::module()->valid()
            ->join('setting_campus', 'setting_campus.id','=', 'inventory_comparative_statement_info.campus_id')
            ->leftJoin('users', 'inventory_comparative_statement_info.instruction_of','=', 'users.id')
            ->select('inventory_comparative_statement_info.*',DB::raw("DATE_FORMAT(date,'%d/%m/%Y') AS cs_date, DATE_FORMAT(due_date,'%d/%m/%Y') AS due_date_formate"), 'users.name', 'setting_campus.name as campus_name')
            ->where('inventory_comparative_statement_info.id', $id)
            ->first();
        
        $voucherDetailsData = ComparativeStatementDetailsModel::module()->itemAccess($item_ids)->valid()
            ->join('cadet_stock_products', 'cadet_stock_products.id','=', 'inventory_comparative_statement_details_info.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
            ->select('inventory_comparative_statement_details_info.*','cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'),'cadet_stock_products.sku')
            ->where('cs_id', $id)->get(); 
        $voucherInfo->voucherDetailsData = $voucherDetailsData;
        $vendor_history = DB::table('inventory_comparative_statement_vendor_history')->where('cs_id', $id)->get();
        $vendor_history_group_by = collect($vendor_history)->groupBy('vendor_id')->all();
        $terms_conditions_grpBy = collect(DB::table('inventory_comparative_statement_vendor_terms_condition_history')->where('cs_id', $id)->get())->groupBy('vendor_id')->all();
        $vendorData = []; $price_catalog_component_data = [];
        $fields = ['rate','amount', 'discount','vat_per','vat_type', 'net_amount'];
        foreach ($vendor_history as $v) {
            if(!array_key_exists($v->vendor_id, $vendorData)){
                $vendor_row_data = [
                    'name' => $v->vendor_name,
                    'gl_code' => $v->gl_code,
                    'credit_limit' => $v->credit_limit,
                    'credit_priod' => $v->credit_priod,
                ];
                foreach($vendor_history_group_by[$v->vendor_id] as $vs){
                    foreach($fields as $field){
                        $price_catalog_component_data[$field.'_'.$vs->reference_details_id.'_'.$vs->item_id.'_'.$v->vendor_id] = $vs->{$field};
                    }
                }
                $terms_condition = (array_key_exists($v->vendor_id, $terms_conditions_grpBy))? $terms_conditions_grpBy[$v->vendor_id]:[];
                $vendor_row_data['terms_condition'] = $terms_condition;
                $vendorData[$v->vendor_id] = (object)$vendor_row_data;
            }
        }
        $data['fields'] = $fields;
        $data['vendorData'] = $vendorData;
        $data['price_catalog_component_data'] = $price_catalog_component_data;
        $data['formData'] = $voucherInfo;

        return view('inventory::purchase.comparativeStatement.comparative-statement-history', $data);
        
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
    public function destroy(Request $request, $id=0)
    {
        DB::beginTransaction();
        try{
            if($id>0){
                $deleteData = ComparativeStatementDetailsModel::module()->valid()->find($id);
                if(!empty($deleteData)){
                    if($deleteData->status==1||$deleteData->status==2){  // check status
                        $output = ['status'=>0, 'message'=>'Sorry Comparative statement Item already approved'];
                    }else{
                        $cs_id = $deleteData->cs_id; 
                        ComparativeStatementDetailsModel::module()->valid()->find($id)->delete();
                        $checkDetailsItem = ComparativeStatementDetailsModel::module()->valid()->where('cs_id', $cs_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            ComparativeStatementInfoModel::module()->valid()->find($cs_id)->delete(); 
                        }
                        // cs vendor history delete
                        DB::table('inventory_comparative_statement_vendor_history')->where('cs_details_id', $id)->delete();
                        DB::table('inventory_comparative_statement_vendor_terms_condition_history')->where('cs_id', $cs_id)->delete();
                        $output = ['status'=>1, 'message'=>'Comparative statement item successfully deleted'];
                        DB::commit();
                    }
                }else{
                    $output = ['status'=>0, 'message'=>'Comparative statement not found'];
                }
            }else{
                $delIds = $request->delIds;
                // status check
                $cs_ids = []; $flag = true; $msg =[]; 
                foreach ($delIds as $del_id){
                    $deleteData = ComparativeStatementDetailsModel::module()->valid()->find($del_id);
                    if($deleteData->status==1||$deleteData->status==2){
                        $itemInfo = CadetInventoryProduct::find($deleteData->item_id);
                        $flag = false;
                        $msg[] = $itemInfo->product_name.' has stock in qty approval';
                    }
                    $cs_ids[] = $deleteData->cs_id;
                }
                if($flag){
                    foreach ($delIds as $del_id){
                        ComparativeStatementDetailsModel::valid()->find($del_id)->delete();
                    }
                    // cs vendor history data delete
                    DB::table('inventory_comparative_statement_vendor_history')->whereIn('cs_details_id', $delIds)->delete();
                    $cs_unique_ids = collect($cs_ids)->unique()->values()->all();
                    foreach ($cs_unique_ids as $cs_id) {
                        $checkDetailsItem = ComparativeStatementDetailsModel::module()->valid()->where('cs_id', $cs_id)->first(); 
                        // if all details data are deleted then master data also delete
                        if(empty($checkDetailsItem)){  
                            ComparativeStatementInfoModel::module()->valid()->find($cs_id)->delete(); 
                        }
                    }
                    DB::table('inventory_comparative_statement_vendor_terms_condition_history')->whereIn('cs_id', $cs_unique_ids)->delete();
                    $output = ['status'=>1, 'message'=>'Comparative statement item successfully deleted'];
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

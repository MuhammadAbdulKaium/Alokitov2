<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\PriceCatelogueInfoModel;
use Modules\Inventory\Entities\PriceCatelogueDetailsModel;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\UserVoucherApprovalLayerModel;
use Modules\Inventory\Entities\VoucherApprovalLogModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\InventoryHelper;
use DateTime;

class PriceCatalogueController extends Controller
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
        
    }

    public function index(Request $request)
    {
        $listPerPage = $request->input('listPerPage');
        $search_key = $request->input('search_key');
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if(!empty($from_date)){
            $from_date = DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
        }
        if(!empty($to_date)){
            $to_date = DateTime::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');
        }  
        $order = $request->input('order');
        $sort = $request->input('sort');

        $paginate_data_query = PriceCatelogueInfoModel::module()->valid()
            ->select('inventory_price_catalogue_info.*', DB::raw("DATE_FORMAT(applicable_from,'%d/%m/%Y') AS effective_date"))
            ->when($from_date, function($query, $from_date){
                $query->where('inventory_price_catalogue_info.applicable_from','>=',$from_date);
            })
            ->when($to_date, function($query, $to_date){
                $query->where('inventory_price_catalogue_info.applicable_from','<=',$to_date);
            })
            ->when($search_key, function($query, $search_key){
                $query->where('price_label','LIKE','%'.$search_key.'%');
            })
            ->orderBy($sort,$order);     

        $paginate_data = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);
        if(count($paginate_data)>0){
            $auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalLayerModel::module()->valid()->where('approval_name', 'price_catelogue')->where('is_role', 0)->get();
            $step=1; $approval_access=true; $approval_log_group = []; $approval_step_log=[];
            if(count($UserVoucherApprovalLayer)>0){
                $UserVoucherApprovalKeyBy = $UserVoucherApprovalLayer->keyBy('approval_id')->all();
                if(array_key_exists($auth_user_id, $UserVoucherApprovalKeyBy)){
                    $step = $UserVoucherApprovalKeyBy[$auth_user_id]->step;
                }else{
                   $approval_access=false; 
                }
            }
            foreach ($paginate_data as $v){
                if($v->status==0){
                    if($approval_access && $v->approval_level==$step){
                        $v->has_approval = 'yes';
                    }else{
                        $v->has_approval = 'no';
                    }
                }else{
                    $v->has_approval = 'no';
                }
                $approval_log_info = VoucherApprovalLogModel::module()->valid()
                    ->join('users', 'users.id', '=', 'inventory_voucher_approval_log.approval_id')
                    ->select('inventory_voucher_approval_log.*', 'users.name')
                    ->where('voucher_type', 'price_catelogue')
                    ->where('is_role', 0)
                    ->where('voucher_id', $v->id)->first();
                if(!empty($approval_log_info)){
                    $status = '';
                    if($approval_log_info->action_status==1){
                        $status = 'approved';
                    }else if($approval_log_info->action_status==2){
                        $status = 'partial approved';
                    }else if($approval_log_info->action_status==3){
                        $status = 'reject';
                    }
                    $v->approved_text = "Step ".$approval_log_info->approval_layer.' '.$status.' By '.$approval_log_info->name.' on '.$approval_log_info->date;
                }else{
                    $v->approved_text = '';
                }
            }
        }
       
        $data['paginate_data'] = $paginate_data;
        return response()->json($data);
    }

    public function page(Request $request){
        return view('inventory::priceCatalogue.price-catalogue');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        
        $data['price_catalogue_list'] = PriceCatelogueInfoModel::select('id', 'price_label')->module()->valid()->where('status',1)->get();
        $data['item_list'] = self::getItemList($this);
        $data['formData'] = ['catalogue_ref_id_model'=>'','catalogue_ref_id_'=>0,'price_label_readonly'=>"no", 'applicable_from'=>date('Y-m-d'),'applicable_from_show'=>date('Y-m-d'),'voucherDetailsData'=>[], 'itemAdded'=>'no'];
        
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
            'price_label' => 'required|max:50',
            'applicable_from' => 'required|date_format:d/m/Y',
            'comments' => 'required',
        ]);
        $applicable_from = DateTime::createFromFormat('d/m/Y', $request->applicable_from)->format('Y-m-d');
        $voucherDetailsData = $request->voucherDetailsData;
        if(count($voucherDetailsData)>0){
            if(!empty($id)){
                $voucherDetailsData_db = PriceCatelogueDetailsModel::module()->valid()->where('catelogue_id', $id)->get();
                $voucherDetailsData_db_keyBy = $voucherDetailsData_db->keyBy('id')->all(); 
                $db_item_ids = $voucherDetailsData_db->pluck('item_id')->all(); 
                $req_item_ids = collect($voucherDetailsData)->pluck('item_id')->all(); 
                $merge_item_ids = collect($db_item_ids)->merge($req_item_ids)->all();
                $item_ids = collect($merge_item_ids)->unique()->values()->all();
            }else{
               $item_ids = collect($voucherDetailsData)->pluck('item_id')->all(); 
            }


            $itemList = CadetInventoryProduct::module()->whereIn('id', $item_ids)->get()->keyBy('id')->all();
            $flag = true; $msg = []; $item_approval = false;
            // checking fraction, fraction length and if approved item change
            foreach ($voucherDetailsData as $v):
                if(array_key_exists($v['item_id'], $itemList)){
                    $itemInfo = $itemList[$v['item_id']];
                    // franction qty check
                    if($itemInfo->has_fraction==1){
                        if(self::isFloat($v['from_qty']) || self::isFloat($v['to_qty'])){
                            $explodeFromQty = explode('.', $v['from_qty']);
                            $explodeToQty = explode('.', $v['to_qty']);
                            if(strlen($explodeFromQty[1])>$itemInfo->decimal_point_place || strlen($explodeToQty[1])>$itemInfo->decimal_point_place){
                                $flag = false;
                                $msg[] = $itemInfo->product_name.' has allow '.$itemInfo->decimal_point_place.' decimal places'; 
                            }
                        }
                    }else{
                        if(self::isFloat($v['from_qty'])||self::isFloat($v['to_qty'])){
                            $flag = false;
                            $msg[] = $itemInfo->product_name.' has no decimal places'; 
                        }
                    }
                    // item approval check
                    $details_id = @$v['id'];
                    if($details_id>0){
                        $db_data = $voucherDetailsData_db_keyBy[$details_id];
                        if(($db_data->status==1||$db_data->status==2) && ($db_data->from_qty!=$v['from_qty'] || $db_data->to_qty!=$v['to_qty'])){
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
                        "catalogue_ref_id" => $request->catalogue_ref_id,
                        "price_label" => $request->price_label,
                        "applicable_from" => $applicable_from,
                        "comments" => $request->comments
                    ];

                    if(!empty($id)){
                        $catelogue_id = $id;
                        $catalogueInfo = PriceCatelogueInfoModel::module()->valid()->findOrFail($id);
                        if($catalogueInfo->status==0){ // check info status
                            // date change check 
                            $catalogue_ref_id = (!empty($request->catalogue_ref_id))?$request->catalogue_ref_id:0;
                            $catalogue_ref_id_db = (!empty($catalogueInfo->catalogue_ref_id))?$catalogueInfo->catalogue_ref_id:0;
                            if($item_approval && ($catalogueInfo->applicable_from!=$applicable_from || $catalogue_ref_id!=$catalogue_ref_id_db)){
                                $flag = false; 
                                if($catalogueInfo->applicable_from!=$applicable_from){
                                    $msg[] = 'Sorry price catalogue details item already approved you can not change date';
                                }else{
                                    $msg[] = 'Sorry price catalogue details item already approved you can not change catalogue reference'; 
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
                                    if($catalogue_ref_id != $catalogue_ref_id_db){ 
                                        if($catalogue_ref_id>0){
                                            $catalogue_uniq_id = $request->catalogue_ref_id;
                                        }else{
                                            $catalogue_uniq_id = $id;
                                        }
                                    }else{
                                        $catalogue_uniq_id = $catalogue_ref_id_db;
                                    }
                                    $data['catalogue_uniq_id']  = $catalogue_uniq_id;

                                   $catalogueInfo->update($data); 
                                   // delete details data
                                   foreach($reqDtlIds_diff as $diffId) {
                                        PriceCatelogueDetailsModel::find($diffId)->delete();
                                   }
                                }
                            }
                        }else{
                           $flag = false; 
                           $msg[] = 'Sorry Price Catalogue already approved';
                        }

                    }else{
                        $save = PriceCatelogueInfoModel::create($data);
                        $catelogue_id = $save->id; 
                        if($request->catalogue_ref_id>0){
                           $catalogue_uniq_id = $request->catalogue_ref_id;
                        }else{
                            $catalogue_uniq_id = $catelogue_id;
                        }
                        PriceCatelogueInfoModel::find($catelogue_id)->update([
                            'catalogue_uniq_id'=>$catalogue_uniq_id
                        ]);
                    }
                    if($flag){
                        foreach ($voucherDetailsData as $v) {
                            $details_id = @$v['id'];
                            $detailsData  = [
                                'catelogue_id'=>$catelogue_id,
                                'item_id'=>$v['item_id'],
                                'catalogue_uniq_id'=>$catalogue_uniq_id,
                                'from_qty'=>$v['from_qty'],
                                'to_qty'=>$v['to_qty'],
                                'applicable_from'=>$applicable_from,
                                'rate'=>$v['rate'],
                                'discount'=>(!empty($v['discount']))?$v['discount']:0,
                                'vat_type'=>(!empty($v['vat_type']))?$v['vat_type']:null,
                            ];

                            if(!empty($v['vat_per'])){
                                $detailsData['vat_per'] = $v['vat_per']; 
                                if($v['vat_type']=='percentage'){
                                    if($v['rate']>0){
                                        $detailsData['vat_amount'] = ($v['rate']/100)*$v['vat_per'];
                                    }else{
                                        $detailsData['vat_amount'] = 0;   
                                    }
                                }else{
                                    $detailsData['vat_amount'] = $v['vat_per']; 
                                }
                            }else{
                               $detailsData['vat_amount'] = 0; 
                               $detailsData['vat_per'] = 0; 
                            }

                            if($details_id>0){
                                PriceCatelogueDetailsModel::module()->valid()->find($details_id)->update($detailsData);;
                            }else{
                               PriceCatelogueDetailsModel::create($detailsData);  
                            }
                        }
                       $output = ['status'=>1,'message'=>'Price Catalogue successfully saved'];
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

    public function labelWiseDetails($catelogue_id){
        $item_list = self::getItemList($this);
        $item_ids  = $item_list->pluck('item_id')->all(); 
        $data['voucherDetailsData'] = PriceCatelogueDetailsModel::module()->valid()
            ->select('inventory_price_catalogue_details.item_id','inventory_price_catalogue_details.from_qty','inventory_price_catalogue_details.to_qty','inventory_price_catalogue_details.rate','inventory_price_catalogue_details.discount','inventory_price_catalogue_details.vat_per','inventory_price_catalogue_details.vat_amount','inventory_price_catalogue_details.vat_type', 'cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
            ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_price_catalogue_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
            ->whereIn('inventory_price_catalogue_details.item_id', $item_ids)
            ->where('inventory_price_catalogue_details.catelogue_id', $catelogue_id)->get();
        $totalRate=0;$totalFromQty=0;$totalToQty=0;$totalDiscount=0;$totalVat=0;
        foreach($data['voucherDetailsData'] as $v){
            $totalRate+= $v->rate;
            $totalFromQty+= $v->from_qty;
            $totalToQty+= $v->to_qty;
            if(!empty($v->discount)){
                $totalDiscount+= $v->discount;
            }
            if(!empty($v->vat_per)){
                $totalVat+= $v->vat_per;
            }
        }
        $data['totalRate'] = $totalRate;
        $data['totalFromQty'] = $totalFromQty;
        $data['totalToQty'] = $totalToQty;
        $data['totalDiscount'] = $totalDiscount;
        $data['totalVat'] = $totalVat;
        return response()->json($data);
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
        $catelogueInfo  = PriceCatelogueInfoModel::module()->valid()
            ->select('inventory_price_catalogue_info.*', DB::raw("DATE_FORMAT(applicable_from,'%d/%m/%Y') AS applicable_from_formate"))
            ->find($id);
        if(!empty($catelogueInfo)){
            $voucherDetailsData = PriceCatelogueDetailsModel::module()->valid()
                ->select('inventory_price_catalogue_details.item_id','inventory_price_catalogue_details.from_qty','inventory_price_catalogue_details.to_qty','inventory_price_catalogue_details.rate','inventory_price_catalogue_details.discount','inventory_price_catalogue_details.vat_per','inventory_price_catalogue_details.vat_amount','inventory_price_catalogue_details.vat_type', 'cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
                ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_price_catalogue_details.item_id')
                ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
                ->whereIn('inventory_price_catalogue_details.item_id', $item_ids)
                ->where('inventory_price_catalogue_details.catelogue_id', $id)->get();
            $totalRate=0;$totalFromQty=0;$totalToQty=0;$totalDiscount=0;$totalVat=0;
            foreach($voucherDetailsData as $v){
                $totalRate+= $v->rate;
                $totalFromQty+= $v->from_qty;
                $totalToQty+= $v->to_qty;
                if(!empty($v->discount)){
                    $totalDiscount+= $v->discount;
                }
                if(!empty($v->vat_per)){
                    $totalVat+= $v->vat_per;
                }
            }
            $catelogueInfo->totalRate = $totalRate;
            $catelogueInfo->totalFromQty = $totalFromQty;
            $catelogueInfo->totalToQty = $totalToQty;
            $catelogueInfo->totalDiscount = $totalDiscount;
            $catelogueInfo->totalVat = $totalVat;
            $catelogueInfo->voucherDetailsData = $voucherDetailsData;
            $data['formData'] = $catelogueInfo; 
            return response()->json($data);
        }else{
          $data = ['status'=>0, 'message'=>"Price catalogue not found"];  
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

        $data['price_catalogue_list'] = PriceCatelogueInfoModel::select('id', 'price_label')->module()->valid()->where('status',1)->get();
        $data['item_list'] = self::getItemList($this);
        $catelogueInfo  = PriceCatelogueInfoModel::module()
            ->valid()->find($id);
        if(!empty($catelogueInfo->catalogue_ref_id)){
            $catalogue_ref_id_model =  PriceCatelogueInfoModel::select('id', 'price_label')->valid()->find($catelogueInfo->catalogue_ref_id);
        }else{
            $catalogue_ref_id_model = '';
        }
        $catelogueInfo->catalogue_ref_id_model=$catalogue_ref_id_model;
        $catelogueInfo->applicable_from_show = $catelogueInfo->applicable_from;  
        $item_ids  = $data['item_list']->pluck('item_id')->all(); 
        $voucherDetailsData = PriceCatelogueDetailsModel::module()->valid()
            ->select('inventory_price_catalogue_details.item_id','inventory_price_catalogue_details.from_qty','inventory_price_catalogue_details.to_qty','inventory_price_catalogue_details.rate','inventory_price_catalogue_details.discount','inventory_price_catalogue_details.vat_per','inventory_price_catalogue_details.vat_amount','inventory_price_catalogue_details.vat_type', 'cadet_stock_products.product_name', 'cadet_inventory_uom.symbol_name as uom', DB::raw('ifnull(cadet_stock_products.decimal_point_place, 0) as decimal_point_place'))
            ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_price_catalogue_details.item_id')
            ->join('cadet_inventory_uom', 'cadet_inventory_uom.id', 'cadet_stock_products.unit')
            ->whereIn('inventory_price_catalogue_details.item_id', $item_ids)
            ->where('inventory_price_catalogue_details.catelogue_id', $id)->get();
        $totalRate=0;$totalFromQty=0;$totalToQty=0;$totalDiscount=0;$totalVat=0;
        foreach($voucherDetailsData as $v){
            $totalRate+= $v->rate;
            $totalFromQty+= $v->from_qty;
            $totalToQty+= $v->to_qty;
            if(!empty($v->discount)){
                $totalDiscount+= $v->discount;
            }
            if(!empty($v->vat_per)){
                $totalVat+= $v->vat_per;
            }
        }
        $catelogueInfo->totalRate = $totalRate;
        $catelogueInfo->totalFromQty = $totalFromQty;
        $catelogueInfo->totalToQty = $totalToQty;
        $catelogueInfo->totalDiscount = $totalDiscount;
        $catelogueInfo->totalVat = $totalVat;
        $catelogueInfo->voucherDetailsData = $voucherDetailsData;
        $catelogueInfo->itemAdded = 'yes';
        $data['formData'] = $catelogueInfo; 
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

    public function voucherApproval(Request $request, $id=0)
    {
        DB::beginTransaction();
        try{
            $auth_user_id = Auth::user()->id;
            if($id>0){
                $approvalData = PriceCatelogueInfoModel::module()->valid()->find($id);
                if(!empty($approvalData)){
                    if($approvalData->status==0){
                        $approvalData->update([
                            'status'=>1
                        ]);
                        $voucherDetailsData = PriceCatelogueDetailsModel::module()->valid()->where('catelogue_id', $id)->where('status',0)->get();
                        $data_time = date('Y-m-d H:i:s');
                        foreach($voucherDetailsData as $v){
                            PriceCatelogueDetailsModel::module()->valid()->find($v->id)->update(['status'=>1]);
                            VoucherApprovalLogModel::create([
                                'date'=>$data_time,
                                'voucher_id'=>$approvalData->id,
                                'voucher_details_id'=>$v->id,
                                'voucher_type'=>'price_catelogue',
                                'approval_id'=>$auth_user_id,
                                'approval_layer'=>1,
                                'action_status'=>1,
                                'institute_id'=>self::getInstituteId(),
                                'campus_id'=>self::getCampusId(),
                            ]);
                        } 
                        $output = ['status'=>1, 'message'=>'Price catalogue approved successfully'];
                        DB::commit();                      
                    }else{ // end if($approvalData->status==0)
                        if($approvalData->status==3){
                            $output = ['status'=>0, 'message'=>'Price Catalogue reject'];
                        }else{
                            $output = ['status'=>0, 'message'=>'Price Catalogue already approved'];  
                        }
                    }
 
                }else{   // end if(!empty($approvalData))
                    $output = ['status'=>0, 'message'=>'Price catalogue not found'];
                }
            }else{
            }
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        } 
        return response()->json($output); 
    }



    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $deleteData = PriceCatelogueInfoModel::module()->valid()->find($id);
            if($deleteData->status==1||$deleteData->status==2){  // check status
                $output = ['status'=>0, 'message'=>'Sorry Catalogue already approved'];
            }else{
                $detailsData = PriceCatelogueDetailsModel::module()->valid()->where('catelogue_id', $id)->get();
                // check details approval 
                $flag = true; $msg =[]; 
                foreach($detailsData as $v){
                    if($v->status==1||$v->status==2){
                        $itemInfo = CadetInventoryProduct::module()->find($v->item_id);
                        $flag = false;
                        $msg[] = $itemInfo->product_name.' has requisition qty approval';
                    }
                }
                if($flag){
                    $deleteData->delete();
                    // delete details data
                    foreach($detailsData as $v){
                        PriceCatelogueDetailsModel::valid()->find($v->id)->delete(); 
                    }
                    $output = ['status'=>1, 'message'=>'Price Catalogue successfully deleted'];
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

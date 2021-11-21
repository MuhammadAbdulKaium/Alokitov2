<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\StockCategory;
use Modules\Inventory\Entities\StockGroup;
use Modules\Inventory\Entities\StockUOM;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\CadetInventoryProductHistory;
use Modules\Inventory\Entities\InventoryStore;
use Modules\Setting\Entities\Campus;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Inventory\Entities\Imports\StockMasterImport;
use App\Helpers\InventoryHelper;
use Illuminate\Support\Str;

class StockMasterExcelImportController extends Controller
{
    use InventoryHelper;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        //return view('inventory::stock.stock-master-excel-import');
    }
    public function page()
    {
        return view('inventory::stock.stock-master-excel-import');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function uploadStockExcel(Request $request)
    {
        ini_set('memory_limit', '-1');
        $validated = $request->validate([
            'stock_master_excel' => 'required|mimes:xlsx',
            'import_type' => 'required'
        ]);
        try {
            $import = new StockMasterImport();
            Excel::import($import, request()->file('stock_master_excel'));
            $data['stock_item_list'] = $import->data;
            $data['import_type'] = $request->import_type;
            if($request->import_type=='centralized'){
                $data['stockCategory'] = StockCategory::selectRaw('id, LOWER(stock_category_name) as stock_category_name')->get();
                $data['stockGroup'] = StockGroup::selectRaw('id, LOWER(stock_group_name) as stock_group_name')->get();
                $data['uom'] = StockUOM::selectRaw('id,LOWER(symbol_name) as symbol_name')->get();
            }else{
                $institute_id = self::getInstituteId(); 
                $campus_id = self::getCampusId(); 
                $data['stockCategory'] = StockCategory::selectRaw('id, LOWER(stock_category_name) as stock_category_name')->where('institute_id', $institute_id)->where('campus_id', $campus_id)->get();
                $data['stockGroup'] = StockGroup::selectRaw('id, LOWER(stock_group_name) as stock_group_name')->where('institute_id', $institute_id)->where('campus_id', $campus_id)->get();
                $data['uom'] = StockUOM::selectRaw('id,LOWER(symbol_name) as symbol_name')->where('institute_id', $institute_id)->where('campus_id', $campus_id)->get();
            }
            $data['status'] = 1;
        } catch (Throwable $e) {
            throw $e;
        }  
        return response()->json($data);
    }
    public function create(Request $request)
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
        $stock_item_list = $request->stock_item_list;
        $need_validation = $request->need_validation;
        $import_type = $request->import_type;
        $output=[];
        $is_valid = true; $has_error=false; $required_error=false;
        if($need_validation){
            // dependencies data list
            $code_type_array = ['General', 'Finished','Pharmacy'];
            $code_type_val_array = ['General'=>1, 'Finished'=>2,'Pharmacy'=>3];
            $has_fraction_val = ['Yes','No'];
            $decimal_point_place_array=[1,2,3,4,5,6];
            $charAr = ['#','&','@','?','(',')',':',';','<','>','[',']'];
            if($import_type=='centralized'){
                $stockItemList = CadetInventoryProduct::selectRaw('id,LOWER(product_name) as product_name, LOWER(alias) as alias, LOWER(sku) as sku')->get();
                $stockCategory = StockCategory::selectRaw('id, LOWER(stock_category_name) as stock_category_name')->get()->keyBy('stock_category_name')->all();
                $stockGroup = StockGroup::selectRaw('id, LOWER(stock_group_name) as stock_group_name')->get()->keyBy('stock_group_name')->all();
                $uom = StockUOM::selectRaw('id, LOWER(symbol_name) as symbol_name')->get()->keyBy('symbol_name')->all();
                $storeList = InventoryStore::selectRaw('id, LOWER(store_name) as store_name')->get()->keyBy('store_name')->all();  
            }else{
                $institute_id = self::getInstituteId(); 
                $campus_id = self::getCampusId(); 
                $stockItemList = CadetInventoryProduct::selectRaw('id,LOWER(product_name) as product_name, LOWER(alias) as alias, LOWER(sku) as sku')->where('institute_id', $institute_id)->where('campus_id', $campus_id)->get();
                $stockCategory = StockCategory::selectRaw('id, LOWER(stock_category_name) as stock_category_name')->where('institute_id', $institute_id)->where('campus_id', $campus_id)->get()->keyBy('stock_category_name')->all();
                $stockGroup = StockGroup::selectRaw('id, LOWER(stock_group_name) as stock_group_name')->where('institute_id', $institute_id)->where('campus_id', $campus_id)->get()->keyBy('stock_group_name')->all();
                $uom = StockUOM::selectRaw('id, LOWER(symbol_name) as symbol_name')->where('institute_id', $institute_id)->where('campus_id', $campus_id)->get()->keyBy('symbol_name')->all();
                $storeList = InventoryStore::selectRaw('id, LOWER(store_name) as store_name')->where('institute_id', $institute_id)->where('campus_id', $campus_id)->get()->keyBy('store_name')->all(); 
            }

            $product_name_list = $stockItemList->keyBy('product_name')->all();
            $alias_list = $stockItemList->keyBy('alias')->all();
            $sku_list = $stockItemList->keyBy('sku')->all();

            // required, duplicate, data type validation checking
            $error_class=[]; $stock_duplicate_errors=[]; $alias_duplicate_errors=[]; 
            $sku_duplicate_errors=[]; $invalid_data_error=[];
            $stock_temp_data=[]; $sku_temp_data=[]; $alias_temp_data = [];
            $new_stock_data=[];
            foreach($stock_item_list as $k=> $v){
                if(!empty($v['product_name']) && !empty($v['alias']) && !empty($v['product_description']) && !empty($v['sku']) && !empty($v['unit']) && !empty($v['code_type_id']) && !empty($v['stock_group']) && !empty($v['category_id']) && !empty($v['store_tagging']) && !empty($v['has_fraction']) && (!empty($v['min_stock']) || $v['min_stock']==0)){
                     $rowNum = $k+1;
                    // dulicate check start
                    // stock item duplicate check
                    if(array_key_exists($v['product_name'], $stock_temp_data)){
                        $stock_duplicate_errors[] = $v['product_name'].' is duplicate at row no '.$rowNum;
                    }else{
                        if(array_key_exists(strtolower($v['product_name']), $product_name_list)){ // check db duplicate
                            $stock_duplicate_errors[] = $v['product_name'].' is duplicate in the database and at row no '.$rowNum;
                        }
                        $stock_temp_data[$v['product_name']]=$v['product_name'];
                    }

                    // alias duplicate check
                    if(array_key_exists($v['alias'], $alias_temp_data)){
                        $alias_duplicate_errors[] = $v['alias'].' is duplicate at row no '.$rowNum;
                    }else{
                        if(array_key_exists(strtolower($v['alias']), $alias_list)){ // check db duplicate
                            $alias_duplicate_errors[] = $v['alias'].' is duplicate in the database and at row no '.$rowNum;
                        }
                        $alias_temp_data[$v['alias']]=$v['alias'];
                    }

                    // sku duplicate check
                    if(array_key_exists($v['sku'], $sku_temp_data)){
                        $sku_duplicate_errors[] = $v['sku'].' is duplicate at row no '.$rowNum;
                    }else{
                        if(array_key_exists(strtolower($v['sku']), $sku_list)){ // check db duplicate
                            $sku_duplicate_errors[] = $v['sku'].' is duplicate in the database and at row no '.$rowNum;
                        }
                        $sku_temp_data[$v['sku']]=$v['sku'];
                    }
                    // dulicate check end
                    // sku special char check
                    if(Str::contains($v['sku'],$charAr)){
                        $invalid_data_error[] = "Special charecter (#,&,@,?,(,),:,;,<,>,[,]) are not allowed in sku name at row no ".$rowNum;
                    }

                    // dependency data check start 
                    if(!array_key_exists(strtolower($v['unit']), $uom)){
                        $invalid_data_error[] = "Invalid unit name at row no ".$rowNum;
                    }else{
                        $v['unit_id'] = $uom[strtolower($v['unit'])]->id;
                    }
                    if(!in_array($v['code_type_id'], $code_type_array)){
                        $invalid_data_error[] = "Invalid Type at row no ".$rowNum;
                    }else{
                        $v['code_type'] = $code_type_val_array[$v['code_type_id']];
                    }
                    if(!array_key_exists(strtolower($v['stock_group']), $stockGroup)){
                        $invalid_data_error[] = "Invalid Group at row no ".$rowNum;
                    }else{
                        $v['stock_group_id'] = $stockGroup[strtolower($v['stock_group'])]->id;  
                    }
                    if(!array_key_exists(strtolower($v['category_id']), $stockCategory)){
                        $invalid_data_error[] = "Invalid Catagory at row no ".$rowNum;
                    }else{
                        $v['category'] = $stockCategory[strtolower($v['category_id'])]->id;
                    }
                    if(Str::contains($v['store_tagging'],',')){
                        $explodStore = explode(',', $v['store_tagging']);
                        $store_id=[];
                        foreach($explodStore as $sk=>$sv){
                            if(!array_key_exists(strtolower(trim($sv)), $storeList)){
                                $invalid_data_error[] = "Invalid Store tagging at row no ".$rowNum;
                            }else{
                                $store_id[] = $storeList[strtolower(trim($sv))]->id;
                            }
                        }
                        $v['store_id'] = $store_id;
                    }else{
                        if(!array_key_exists(strtolower($v['store_tagging']), $storeList)){
                            $invalid_data_error[] = "Invalid Store tagging at row no ".$rowNum;
                        }else{
                            $v['store_id'] = [$storeList[strtolower($v['store_tagging'])]->id];
                        }
                    }

                    if(!in_array($v['has_fraction'], $has_fraction_val)){
                        $invalid_data_error[] = "Invalid Fraction at row no ".$rowNum;
                    }else{
                        $v['fraction'] = ($v['has_fraction']=='Yes')?1:0;
                    }
                    if(!empty($v['decimal_point_place']) && !in_array($v['decimal_point_place'], $decimal_point_place_array)){
                        $invalid_data_error[] = "Invalid Decimal at row no ".$rowNum;
                    }
                    // dependency data check end 
                    // additional data check start
                    if(!empty($v['warrenty_month']) && !is_numeric($v['warrenty_month'])){
                        $invalid_data_error[] = "Invalid Warranty Month at row no ".$rowNum;
                    }
                    if(!is_numeric($v['min_stock'])){
                        $invalid_data_error[] = "Invalid min stock at row no ".$rowNum;
                    }
                    if(!empty($v['reorder_qty']) && !is_numeric($v['reorder_qty'])){
                        $invalid_data_error[] = "Invalid reorder qty at row no ".$rowNum;
                    }
                    if(!empty($v['round_of']) && !is_numeric($v['round_of'])){
                        $invalid_data_error[] = "Invalid round of at row no ".$rowNum;
                    }
                    if($v['has_fraction']=='Yes' && (empty($v['decimal_point_place']) || empty($v['round_of']))){
                        if(empty($v['decimal_point_place'])){
                            $invalid_data_error[] = "Decimal must required at row no ".$rowNum;
                        }
                        if(empty($v['round_of'])){
                            $invalid_data_error[] = "Round of must required at row no ".$rowNum;  
                        }
                    }
                    // addintional data check end

                }else{
                    $is_valid = false; $required_error=true;
                    if(empty($v['product_name'])){
                        $error_class[$k.'_2'] = 'input-error';
                    }
                    if(empty($v['alias'])){
                        $error_class[$k.'_3'] = 'input-error';
                    }
                    if(empty($v['product_description'])){
                        $error_class[$k.'_4'] = 'input-error';
                    }
                    if(empty($v['sku'])){
                        $error_class[$k.'_5'] = 'input-error';
                    }
                    if(empty($v['unit'])){
                        $error_class[$k.'_6'] = 'input-error';
                    }
                    if(empty($v['code_type_id'])){
                        $error_class[$k.'_7'] = 'input-error';
                    }
                    if(empty($v['stock_group'])){
                        $error_class[$k.'_8'] = 'input-error';
                    }
                    if(empty($v['category_id'])){
                        $error_class[$k.'_9'] = 'input-error';
                    }
                    if(empty($v['min_stock']) && $v['min_stock']!=0){
                        $error_class[$k.'_11'] = 'input-error';
                    }
                    if(empty($v['store_tagging'])){
                        $error_class[$k.'_13'] = 'input-error';
                    }
                    if(empty($v['has_fraction'])){
                        $error_class[$k.'_15'] = 'input-error';
                    }
                    
                }
                $new_stock_data[] = $v;
            }
            //dd($new_stock_data);

            if($required_error){
                $invalid_data_error[] = "Please fill up all required fields";
            }

            if(count($stock_duplicate_errors)>0 || count($alias_duplicate_errors)>0 || count($sku_duplicate_errors)>0 || count($invalid_data_error)>0){
                $is_valid = false; $has_error=true;
            }
            if($is_valid){
                $user_id = Auth::user()->id;
                DB::beginTransaction();
                try {
                    // data insert
                    foreach($new_stock_data as $v){
                        $stockItem = new CadetInventoryProduct();
                        $stockItem->product_description = $v['product_description'];
                        $stockItem->product_name = $v['product_name'];
                        $stockItem->stock_group = $v['stock_group_id'];
                        $stockItem->unit = $v['unit_id'];
                        $stockItem->has_fraction = $v['fraction'];
                        $stockItem->round_of = (isset($v['round_of']) && !empty($v['round_of']))?$v['round_of']:NULL;
                        $stockItem->use_serial = 0;
                        $stockItem->min_stock = $v['min_stock'];
                        $stockItem->item_type = 1;
                        $stockItem->alias = $v['alias'];
                        $stockItem->sku = $v['sku'];
                        $stockItem->barcode = $v['sku'];
                        $stockItem->qrcode = $v['sku'];
                        $stockItem->code_type_id = $v['code_type'];
                        $stockItem->category_id = $v['category'];
                        $stockItem->warrenty_month = (isset($v['warrenty_month']) && !empty($v['warrenty_month']))?$v['warrenty_month']:NULL;
                        $stockItem->reorder_qty = (isset($v['reorder_qty']) && !empty($v['reorder_qty']))?$v['reorder_qty']:NULL;
                        $stockItem->additional_remarks = $v['additional_remarks'];
                        $stockItem->decimal_point_place = (isset($v['decimal_point_place']) && !empty($v['decimal_point_place']))?$v['decimal_point_place']:NULL;
                        $stockItem->store_tagging = $v['store_id'];
                        $stockItem->institute_id = $institute_id;
                        $stockItem->campus_id = $campus_id;
                        $stockItem->save();
                        $item_id = $stockItem->id;

                        
                        
                        // item history insert
                        CadetInventoryProductHistory::insert([
                            'product_id'=>$item_id,
                            'remarks'=>'Product Insert',
                            'created_by'=>$user_id
                        ]);
                        // store wise all campus item insert
                        $item_wise_store_data=[];
                        foreach ($v['store_id'] as $key => $value) {
                            $item_wise_store_data[] = [
                                'item_id'=>$item_id,
                                'store_id'=>$value,
                                'institute_id'=>$institute_id,
                                'campus_id'=>$campus_id,
                                'created_by'=>$user_id,
                                'created_at'=>date('Y-m-d H:i:s')
                            ];
                        }
                        DB::table('inventory_store_wise_item')->insert($item_wise_store_data);
                    }
                    DB::commit();
                    $output['status'] = 1;
                    $output['message'] = "Data successfully inserted";
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                }
            }else{
                $output['has_error'] = $has_error;
                $output['status'] = 0;
                $output['message'] = "Invalid data";
                $output['error_class'] = $error_class;
                $output['stock_duplicate_errors'] = $stock_duplicate_errors;
                $output['alias_duplicate_errors'] = $alias_duplicate_errors;
                $output['sku_duplicate_errors'] = $sku_duplicate_errors;
                $output['invalid_data_error'] = $invalid_data_error;
            }
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
        return view('inventory::show');
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

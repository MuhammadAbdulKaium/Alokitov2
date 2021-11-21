<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\CadetInventoryProductHistory;
use Modules\Inventory\Entities\CadetInventoryVoucher;
use Modules\Inventory\Entities\InventoryStore;
use Modules\Inventory\Entities\InventoryStoreCategory;
use Modules\Inventory\Entities\StockCategory;
use Modules\Inventory\Entities\StockGroup;
use Modules\Inventory\Entities\StockUOM;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\Campus;
use App\Helpers\InventoryHelper;
use File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use DNS2D;
use DNS1D;


class InventoryController extends Controller
{
    private $academicHelper;
    use InventoryHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    public function index()
    {
        return view('inventory::index');
    }


    public function create()
    {
        return view('inventory::create');
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        return view('inventory::show');
    }


    public function edit($id)
    {
        return view('inventory::edit');
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }


    // Batch Methods
    public function batchGrid()
    {
        return view('inventory::batch.batch-grid');
    }


    // Stock Methods
    public function stockGroupGrid()
    {
        $stockGroup = StockGroup::module()->get();
        return view('inventory::stock.stock-group', compact('stockGroup'));
    }

    public function addNewStockGroup()
    {
        $stockGroup = StockGroup::module()->where('has_child', 1)->get();
        return view('inventory::stock.modal.add-new-stock-group', compact('stockGroup'));
    }

    public function editStockGroup($id)
    {
        $stockGroupDeatils = StockGroup::where('id', '=', $id)->first();
        $stockGroup = StockGroup::module()->where('has_child', 1)->get();
        return view('inventory::stock.modal.edit-stock-group', compact('stockGroup', 'stockGroupDeatils'));
    }

    public function updateNewStockGroup(Request $request, $id)
    {
        $campus_id = $this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();
        $validated = $request->validate([
            'stock_group_name' => [
                'required',
                'max:255',
                Rule::unique('cadet_inventory_stock_group')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })->ignore($id, 'id')
            ],
            'has_child' => 'required'
        ]);

        $stockGroupDeatils = StockGroup::where('id', '=', $id)->first();
        $check_has_child = true; $msg = '';
        if($stockGroupDeatils->has_child != $request->has_child){
            if($stockGroupDeatils->has_child==0){

                $chekStockItem = CadetInventoryProduct::module()->where('stock_group', $id)->first();
                if(!empty($chekStockItem)){
                    $check_has_child = false;
                    $msg = 'Sorry! Item already manage you can not change has child';
                }
            }else{
                $checkChild  = StockGroup::where('parent_group_id', '=', $id)->first();
                if(!empty($checkChild)){
                    $check_has_child = false;
                    $msg = 'Sorry! Group already manage child group you can not change has child';
                }
            }
        }
        if($check_has_child){
            DB::beginTransaction();
            try {
                $groupUpdate = $stockGroupDeatils->update([
                    'stock_group_name' => $request->stock_group_name,
                    'parent_group_id' => $request->parent_group_id,
                    'has_child' => $request->has_child,
                    'updated_by' => Auth::user()->id,
                ]);
                if ($groupUpdate) {
                    DB::commit();
                    Session::flash('message', 'Success! Group updated successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Sorry! Error updating Group.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Sorry! Error updating Group.');
                return redirect()->back();
            }
        }else{
            Session::flash('errorMessage', $msg);
            return redirect()->back();
        }
    }

    public function storeNewStockGroup(Request $request)
    {
        $campus_id = $this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();
        $validated = $request->validate([
            'stock_group_name' => [
                'required',
                'max:255',
                Rule::unique('cadet_inventory_stock_group')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })
            ],
            'has_child' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $stockGroup = new StockGroup();
            $stockGroup->stock_group_name = $request->stock_group_name;
            $stockGroup->parent_group_id = $request->parent_group_id;
            $stockGroup->has_child = $request->has_child;
            $stockGroup->campus_id = $this->academicHelper->getCampus();
            $stockGroup->institute_id = $this->academicHelper->getInstitute();
            $stockGroup->create_by = Auth::user()->id;
            $stockGroupSave = $stockGroup->save();
            if ($stockGroupSave) {
                DB::commit();
                Session::flash('message', 'New Group created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating Group.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating Group.');
            return redirect()->back();
        }
    }

    public function deleteNewStockGroup(Request $request, $id)
    {
        $delete_check = true; $msg = "";
        $chekStockItem = CadetInventoryProduct::module()->where('stock_group', $id)->first();
        if(!empty($chekStockItem)){
            $delete_check = false; 
            $msg = 'Sorry! Item already manage  can not delete group';
        }else{
            $checkChild  = StockGroup::where('parent_group_id', '=', $id)->first();
            if(!empty($checkChild)){
                $delete_check = false;
                $msg = 'Sorry! Group  has child data you can not delete';
            }
        }
        if($delete_check){
            $stockGroupDeatils = StockGroup::where('id', '=', $id)->first();
            $delete = $stockGroupDeatils->delete();
            if ($delete) {
                Session::flash('message', 'Stock Deleted successfully.');
                return redirect()->back();
            }
        }else{
            Session::flash('errorMessage', $msg);
            return redirect()->back();
        }
    }

    public function stockCategory()
    {
        $stockCategory = StockCategory::module()->get();
        return view('inventory::stock.stock-category', compact('stockCategory'));
    }

    public function storeStockCategory(Request $request)
    {
        $campus_id = $this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();
        $validated = $request->validate([
            'stock_category_name' => [
                'required',
                'max:255',
                Rule::unique('cadet_inventory_stock_category')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })
            ],
            'has_child' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $stockCategory = new StockCategory();
            $stockCategory->stock_category_name = $request->stock_category_name;
            $stockCategory->stock_category_parent_id = $request->stock_category_parent_id;
            $stockCategory->has_child = $request->has_child;
            $stockCategory->campus_id = $this->academicHelper->getCampus();
            $stockCategory->institute_id = $this->academicHelper->getInstitute();
            $stockCategory->created_by = Auth::user()->id;
            $stockCategorySave = $stockCategory->save();
            if ($stockCategorySave) {
                DB::commit();
                Session::flash('message', 'New Category created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating Category.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating Category.');
            return redirect()->back();
        }
    }

    public function editStockCategory($id)
    {
        $stockCategoryDeatils = StockCategory::where('id', '=', $id)->first();
        $stockCategory = StockCategory::module()->where('has_child',1)->get();
        return view('inventory::stock.modal.edit-stock-category', compact('stockCategory', 'stockCategoryDeatils'));
    }

    public function addNewStockCategory()
    {
        $stockCategory = StockCategory::module()->where('has_child',1)->get();
        return view('inventory::stock.modal.add-new-stock-category', compact('stockCategory'));
    }

    public function updateStockCategory(Request $request, $id)
    {
        $campus_id = $this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();
        $validated = $request->validate([
            'stock_category_name' => [
                'required',
                'max:255',
                Rule::unique('cadet_inventory_stock_category')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })->ignore($id, 'id')
            ],
            'has_child' => 'required'
        ]);

        $stockCategoryDeatils = StockCategory::where('id', '=', $id)->first();

        $check_has_child = true; $msg = '';
        if($stockCategoryDeatils->has_child != $request->has_child){
            if($stockCategoryDeatils->has_child==0){
                $chekStockItem = CadetInventoryProduct::module()->where('category_id', $id)->first();
                if(!empty($chekStockItem)){
                    $check_has_child = false;
                    $msg = 'Sorry! Item already manage you can not change has child';
                }
            }else{
                $checkChild  = StockCategory::where('stock_category_parent_id', '=', $id)->first();
                if(!empty($checkChild)){
                    $check_has_child = false;
                    $msg = 'Sorry! Category already manage child category you can not change has child';
                }
            }
        }

        if($check_has_child){
            DB::beginTransaction();
            try {
                $categoryUpdate = $stockCategoryDeatils->update([
                    'stock_category_name' => $request->stock_category_name,
                    'stock_category_parent_id' => $request->stock_category_parent_id,
                    'has_child'=>$request->has_child,
                    'updated_by' => Auth::user()->id,
                ]);
                if ($categoryUpdate) {
                    DB::commit();
                    Session::flash('message', 'Success! Category updated successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Sorry! Error updating Category.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Sorry! Error updating Category.');
                return redirect()->back();
            }
        }else{
            Session::flash('errorMessage', $msg);
            return redirect()->back();
        }
    }

    public function deleteStockCategory(Request $request, $id)
    {
        $delete_check = true; $msg='';
        $chekStockItem = CadetInventoryProduct::module()->where('category_id', $id)->first();
        if(!empty($chekStockItem)){
            $delete_check = false; 
            $msg = 'Sorry! Item already manage  can not delete category';
        }else{
           $checkChild  = StockCategory::where('stock_category_parent_id', '=', $id)->first();
            if(!empty($checkChild)){
                $delete_check = false;
                $msg = 'Sorry! category  has child data you can not delete';
            }
        }

        if($delete_check){
            $stockCategoryDeatils = StockCategory::where('id', '=', $id)->first();
            $delete = $stockCategoryDeatils->delete();
            if ($delete) {
                Session::flash('message', 'Deleted Category successfully.');
                return redirect()->back();
            }
        }else{
            Session::flash('errorMessage', $msg);
            return redirect()->back();
        }
    }

    public function unitOfMeasurement()
    {
        $uom = StockUOM::module()->get();
        return view('inventory::stock.unit-of-measurement', compact('uom'));
    }

    public function addNewUnitOfMeasurement()
    {
        return view('inventory::stock.modal.add-new-unit-of-measurement');
    }

    public function storeUnitOfMeasurement(Request $request)
    {
        $campus_id = $this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();
        $validated = $request->validate([
            'symbol_name' => [
                'required',
                'max:255',
                Rule::unique('cadet_inventory_uom')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })
            ],
            'formal_name' => [
                'required',
                'max:255',
                Rule::unique('cadet_inventory_uom')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })
            ]
        ]);
        DB::beginTransaction();
        try {
            $stockUOM = new StockUOM();
            $stockUOM->symbol_name = $request->symbol_name;
            $stockUOM->formal_name = $request->formal_name;
            $stockUOM->campus_id = $this->academicHelper->getCampus();
            $stockUOM->institute_id = $this->academicHelper->getInstitute();
            $stockUOM->created_by = Auth::user()->id;
            $stockUOMSave = $stockUOM->save();
            if ($stockUOMSave) {
                DB::commit();
                Session::flash('message', 'New UOM created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating UOM.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating UOM.');
            return redirect()->back();
        }
    }

    public function editUnitOfMeasurement($id)
    {
        $stockUOMDeatils = StockUOM::where('id', '=', $id)->first();
        return view('inventory::stock.modal.edit-unit-of-measurement', compact('stockUOMDeatils'));
    }

    public function updateUnitOfMeasurement(Request $request, $id)
    {
        $campus_id = $this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();
        $validated = $request->validate([
            'symbol_name' => [
                'required',
                'max:255',
                Rule::unique('cadet_inventory_uom')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })->ignore($id, 'id')
            ],
            'formal_name' => [
                'required',
                'max:255',
                Rule::unique('cadet_inventory_uom')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })->ignore($id, 'id')
            ]
        ]);

        $stockUOMDeatils = StockUOM::where('id', '=', $id)->first();

        DB::beginTransaction();
        try {
            $UOMUpdate = $stockUOMDeatils->update([
                'symbol_name' => $request->symbol_name,
                'formal_name' => $request->formal_name,
                'updated_by' => Auth::user()->id,
            ]);
            if ($UOMUpdate) {
                DB::commit();
                Session::flash('message', 'Success! UOM updated successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Sorry! Error updating UOM.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Sorry! Error updating UOM.');
            return redirect()->back();
        }
    }

    public function deleteUnitOfMeasurement(Request $request, $id)
    {
        $chekStockItem = CadetInventoryProduct::module()
            ->where('unit', $id)->first();
        if(empty($chekStockItem)){
            $stockUOMDeatils = StockUOM::where('id', '=', $id)->first();
            $delete = $stockUOMDeatils->delete();
            if ($delete) {
                Session::flash('message', 'Deleted UOM successfully.');
                return redirect()->back();
            }
        }else{
            Session::flash('errorMessage', 'Sorry! UOM has stock item.');
            return redirect()->back();
        }
    }

    // Store Methods
    public function storeList()
    {
        $storeList = InventoryStore::module()->join('inventory_store_category', 'cadet_inventory_store.category_id', 'inventory_store_category.id')
            ->select('cadet_inventory_store.*', 'inventory_store_category.store_category_name')->get();
        return view('inventory::store.store-list', compact('storeList'));
    }

    public function addNewStore()
    {
        $storeCategory = InventoryStoreCategory::all();
        return view('inventory::store.modal.add-new-store', compact('storeCategory'));
    }

    public function storeNewStore(Request $request)
    {
        $campus_id = $this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();
        $validated = $request->validate([
            'store_name' => [
                'required',
                'max:255',
                Rule::unique('cadet_inventory_store')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })
            ],
            'store_address_1' => 'required',
            'store_address_2' => 'required',
            'store_phone' => 'required',
            'store_fax' => 'required',
            'store_city' => 'required',
            'category_id' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $store = new InventoryStore();
            $store->store_name = $request->store_name;
            $store->store_address_1 = $request->store_address_1;
            $store->store_address_2 = $request->store_address_2;
            $store->store_phone = $request->store_phone;
            $store->store_fax = $request->store_fax;
            $store->store_city = $request->store_city;
            $store->category_id = $request->category_id;
            $store->campus_id = $campus_id;
            $store->institute_id = $institute_id;
            $store->created_by = Auth::user()->id;
            $storeSave = $store->save();
            if ($storeSave) {
                DB::commit();
                Session::flash('message', 'New Store created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating Store.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating Store.');
            return redirect()->back();
        }
    }

    public function editStore($id)
    {
        $storeCategory = InventoryStoreCategory::all();
        $storeDetails = InventoryStore::where('id', '=', $id)->first();
        return view('inventory::store.modal.edit-store', compact('storeDetails', 'storeCategory'));
    }

    public function updateStore(Request $request, $id)
    {
        $campus_id = $this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();
        $validated = $request->validate([
            'store_name' => [
                'required',
                'max:255',
                Rule::unique('cadet_inventory_store')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })->ignore($id, 'id')
            ],
            'store_address_1' => 'required',
            'store_address_2' => 'required',
            'store_phone' => 'required',
            'store_fax' => 'required',
            'store_city' => 'required',
            'category_id' => 'required',
        ]);

        $storeDetails = InventoryStore::where('id', '=', $id)->first();
        DB::beginTransaction();
        try {
            $store_trans_check = true; 
            if($storeDetails->store_name != $request->store_name || $storeDetails->category_id != $request->category_id){
                $store_wise_item  = DB::table('inventory_store_wise_item')->where('campus_id', $campus_id)->where('institute_id', $institute_id)->where('store_id', $id)->first();
                if(!empty($store_wise_item)){
                    $store_trans_check=false;
                }
            }
            if($store_trans_check){
                $storeDetails->store_name = $request->store_name;
                $storeDetails->store_address_1 = $request->store_address_1;
                $storeDetails->store_address_2 = $request->store_address_2;
                $storeDetails->store_phone = $request->store_phone;
                $storeDetails->store_fax = $request->store_fax;
                $storeDetails->store_city = $request->store_city;
                $storeDetails->category_id = $request->category_id;
                $storeDetails->updated_by = Auth::user()->id;
                $storeUpdate = $storeDetails->save();

                if ($storeUpdate) {
                    DB::commit();
                    Session::flash('message', 'New Store Update successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Error Update Store.');
                    return redirect()->back();
                }
            }else{
                Session::flash('errorMessage', 'Sorry store has data dependency.');
                return redirect()->back();  
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error Update Store.');
            return redirect()->back();
        }

    }


    // Requisition Method
    public function newRequisition()
    {
        return view('inventory::requisition.new-requisition');
    }

    //    Stock List
    public function stockList()
    {
        return view('inventory::stock.stock-list');
    }

    public function stockListData(Request $request){
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = CadetInventoryProduct::module()->select('count(*) as allcount')->count();
        $totalRecordswithFilter = CadetInventoryProduct::module()->select('count(*) as allcount')
            ->join('cadet_inventory_stock_group', 'cadet_stock_products.stock_group', 'cadet_inventory_stock_group.id')
            ->join('cadet_inventory_stock_category', 'cadet_stock_products.category_id', 'cadet_inventory_stock_category.id')
            ->where(function($query)use($searchValue){
                $query->where('product_name', 'like', '%' .$searchValue . '%')
                      ->orWhere('stock_group_name', 'like', '%' .$searchValue . '%')
                      ->orWhere('sku', 'like', '%' .$searchValue . '%')
                      ->orWhere('alias', 'like', '%' .$searchValue . '%')
                      ->orWhere('stock_category_name', 'like', '%' .$searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = CadetInventoryProduct::module()->orderBy($columnName,$columnSortOrder)
            ->join('cadet_inventory_stock_group', 'cadet_stock_products.stock_group', 'cadet_inventory_stock_group.id')
            ->join('cadet_inventory_stock_category', 'cadet_stock_products.category_id', 'cadet_inventory_stock_category.id')
            ->select('cadet_stock_products.*','cadet_inventory_stock_group.stock_group_name','cadet_inventory_stock_category.stock_category_name')
            ->where(function($query)use($searchValue){
                $query->where('product_name', 'like', '%' .$searchValue . '%')
                      ->orWhere('stock_group_name', 'like', '%' .$searchValue . '%')
                      ->orWhere('sku', 'like', '%' .$searchValue . '%')
                      ->orWhere('alias', 'like', '%' .$searchValue . '%')
                      ->orWhere('stock_category_name', 'like', '%' .$searchValue . '%');
            })
            
            /*->whereRaw('(SELECT CASE item_type 
                    WHEN "1" THEN "General Goods"
                    ELSE "Finished Goods"
                END as custome_col from cadet_stock_products) like"%"'.$searchValue.'"%"')*/
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $stores = InventoryStore::module()->get()->keyBy('id')->all();

        $stock_item_ids = $records->pluck('id')->all(); 
        $stocks_items_keyBy = $records->keyBy('id')->all();
        $user_id = Auth::user()->id;
        $AccessStore = self::UserAccessStore($user_id);
        if(!empty(self::getInstituteId()) && !empty(self::getCampusId())){
           $params = (object)['AccessStore'=>$AccessStore, 'campus_id'=>self::getCampusId(), 'institute_id'=>self::getInstituteId()];
           $itemWiseStockInfo = self::itemWiseStock($params, $stock_item_ids);
        }else{
            $params = (object)['AccessStore'=>$AccessStore];
            $itemWiseCentralStockInfo = self::centralItemWiseStock($params, $stock_item_ids);
            $stocItemData = [];
            foreach($itemWiseCentralStockInfo as $v){
                if(array_key_exists($v->item_id, $stocks_items_keyBy)){
                    $itemInfo = $stocks_items_keyBy[$v->item_id];
                    $current_stock = round($v->totalCurrentStock, $itemInfo->decimal_point_place);
                    $avg_price = ($current_stock>0)? round($v->totalCostPrice/$current_stock,6):0;
                    $itemData = ['item_id'=>$v->item_id, 'current_stock'=>$current_stock,'avg_cost_price'=>$avg_price];
                    $stocItemData[$v->item_id] = $itemData;
                }
            }

           $itemWiseStockInfo =  $stocItemData; 
        }

   
        $data_arr = array();
        $sno = $start+1;
        foreach($records as $record){
            $store_sl=0; $store_html="";
            foreach($record->store_tagging as $tag){
                $store_html.='<b class="text-info">'.@$stores[$tag]->store_name;
                $store_html.= (++$store_sl==count($record->store_tagging))?'':',';
                $store_html.='</b>';
            }
            $decimal_point_place = (!empty($record->decimal_point_place))?$record->decimal_point_place:0;
            $sku = (!empty($record->sku))?$record->sku:'';
            if(array_key_exists($record->id,$itemWiseStockInfo)){
                $itemStock = $itemWiseStockInfo[$record->id];
                $current_stock = $itemStock['current_stock'];
                $avg_cost_price = $itemStock['avg_cost_price']+0;
                $current_value = round($current_stock*$avg_cost_price, $decimal_point_place);
            }else{
                $current_stock = 0;
                $avg_cost_price = 0;
                $current_value = 0;
            }
                                            
            $data_arr[] = array(
                "id" => $sno++,
                "product_name" => $record->product_name,
                "sku" => $sku,
                "barcode" => '<img src="data:image/png;base64,'.DNS1D::getBarcodePNG($record->barcode, 'C39',1,33,array(0,0,0), true).'" alt="barcode" width="90" height="30" />',
                "qrcode" => DNS2D::getBarcodeHTML($record->qrcode, 'QRCODE',2,2),
                "alias" => $record->alias,
                "stock_group_name" => $record->stock_group_name,
                "stock_category_name" => $record->stock_category_name,
                "current_stock" => $current_stock,
                "avg_cost_price" => $avg_cost_price,
                "current_value" => $current_value,
                "min_stock" => $record->min_stock,
                "reorder_qty" => $record->reorder_qty,
                "item_type" => ($record->item_type==1)?'General Goods':'Finished Goods',
                "decimal_point_place" => $decimal_point_place,
                "store_name" => $store_html,
                "history" => '<a class="btn btn-success btn-sm" href="/inventory/show/history/stock-product/'.$record->id.'" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square"></i> History </a>',
                "action" => '<a class="btn btn-success btn-sm" href="/inventory/edit/inventory/stock-product/'.$record->id.'" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square"></i> Edit </a>',
            );
        }
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $data_arr
        ); 

        echo json_encode($response);

    }

    public function addStockProduct()
    {
        $uoms = StockUOM::module()->get();
        $stockGroup = StockGroup::module()->where('has_child',0)->get();
        $category = StockCategory::module()->where('has_child',0)->get();
        $storeList = InventoryStore::module()->get();
        return view('inventory::stock.modal.add-new-stock-product', compact('stockGroup', 'category', 'storeList', 'uoms'));
    }

    public function storeStockProduct(Request $request)
    {
        $campus_id = $this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();
        $rules  = [
            'product_name' => [
                'required',
                'max:255',
                Rule::unique('cadet_stock_products')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })
            ],
            'product_description' => 'required|max:255',
            'unit' => 'required|numeric',
            'has_fraction' => 'required',
            'use_serial' => 'required',
            'item_type' => 'required',
            'alias' => 'required',
            'sku' => [
                'required',
                'max:255',
                Rule::unique('cadet_stock_products')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })
            ],
            'unit' => 'required',
            'code_type_id' => 'required',
            'store_tagging' => 'required',
            'stock_group' => 'required',
            'min_stock' => 'required',
            'category_id' => 'required',
            'store_tagging' => 'required',
        ];

        if($request->has_fraction==1){
            $rules['decimal_point_place'] = 'required'; 
            $rules['round_of'] = 'required|numeric'; 
        }else{
            if($request->use_serial==1){
                $rules['numeric_part'] = 'required';
                $rules['prefix'] = 'required';
                $rules['separator_symbol'] = 'required';
            }
        }

        $validated = $request->validate($rules);

        $charAr = ['#','&','@','?','(',')',':',';','<','>','[',']'];
        if(Str::contains($request->sku,$charAr)){
            Session::flash('errorMessage', 'Special charecter are not allowed in sku name');
            return redirect()->back();
        }else{
            DB::beginTransaction();
            try {
                $stockItem = new CadetInventoryProduct();
                $stockItem->product_name = $request->product_name;
                $stockItem->product_description = $request->product_description;
                $stockItem->unit = $request->unit;
                $stockItem->stock_group = $request->stock_group;
                $stockItem->use_serial = $request->use_serial;
                $stockItem->has_fraction = $request->has_fraction;
                if($request->has_fraction==1){
                    $stockItem->round_of = $request->round_of;
                    $stockItem->decimal_point_place = $request->decimal_point_place;
                }else{
                    if($request->use_serial==1){
                        $stockItem->numeric_part = $request->numeric_part;
                        $stockItem->prefix = $request->prefix;
                        $stockItem->suffix = $request->suffix;
                        $stockItem->separator_symbol = $request->separator_symbol;
                    }
                }

                $stockItem->min_stock = $request->min_stock;
                $stockItem->item_type = $request->item_type;
                $stockItem->alias = $request->alias;
                $stockItem->sku = $request->sku;
                $stockItem->barcode = $request->sku;
                $stockItem->qrcode = $request->sku;
                $stockItem->code_type_id = $request->code_type_id;
                $stockItem->category_id = $request->category_id;
                $stockItem->warrenty_month = $request->warrenty_month;
                $stockItem->reorder_qty = $request->reorder_qty;
                $stockItem->additional_remarks = $request->additional_remarks;
                $stockItem->store_tagging = $request->store_tagging;
                $stockItem->campus_id = $this->academicHelper->getCampus();
                $stockItem->institute_id = $this->academicHelper->getInstitute();
                $stockItem->created_by = Auth::user()->id;

                if ($request->image) {
                    $photoFile = $request->file('image');
                    $fileExtension = $photoFile->getClientOriginalExtension();
                    $contentName = "inv" . date("Ymdhis") . mt_rand(100000, 999999) . "." . $fileExtension;
                    $contentFileName = $contentName;
                    $uploaded = $photoFile->move('assets/inventory/products/', $contentFileName);
                    $stockItem->image = $contentFileName;
                }
                $stockSave = $stockItem->save();
                // store wise item save
                foreach ($request->store_tagging as $key => $value) {
                    $storeData = [
                        'item_id'=>$stockItem->id,
                        'store_id'=>$value,
                        'institute_id'=>$this->academicHelper->getInstitute(),
                        'campus_id'=>$this->academicHelper->getCampus(),
                        'created_by'=>Auth::user()->id,
                        'created_at'=>date('Y-m-d H:i:s')
                    ];
                    DB::table('inventory_store_wise_item')->insert($storeData);
                }
                if ($stockSave) {
                    $itemHistory = new CadetInventoryProductHistory();
                    $itemHistory->product_id = $stockItem->id;
                    $itemHistory->remarks = 'Product Insert';
                    $itemHistory->created_by = Auth::user()->id;
                    $historyDataSave = $itemHistory->save();
                }
                if ($historyDataSave) {
                    DB::commit();
                    Session::flash('message', 'New Stock item created successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Error item Store.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Error creating Store.');
                return redirect()->back();
            }
        }

    }

    public function editProduct($id)
    {
        $uoms = StockUOM::module()->get();
        $stockGroup = StockGroup::module()->where('has_child',0)->get();
        $category = StockCategory::module()->where('has_child',0)->get();
        $storeList = InventoryStore::module()->get();        
        $product = CadetInventoryProduct::module()->where('id','=',$id)->first();
        return view('inventory::stock.modal.edit-stock-product', compact('product','uoms','stockGroup','category','storeList'));
    }

    public function updateStockItem(Request $request, $id){
        $campus_id = $this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();
        $rules  = [
            'product_name' => [
                'required',
                'max:255',
                Rule::unique('cadet_stock_products')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })->ignore($id, 'id')
            ],
            'product_description' => 'required|max:255',
            'unit' => 'required|numeric',
            'has_fraction' => 'required',
            'use_serial' => 'required',
            'item_type' => 'required',
            'alias' => 'required',
            'sku' => [
                'required',
                'max:255',
                Rule::unique('cadet_stock_products')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id);
                })->ignore($id, 'id')
            ],
            'unit' => 'required',
            'code_type_id' => 'required',
            'store_tagging' => 'required',
            'stock_group' => 'required',
            'min_stock' => 'required',
            'category_id' => 'required',
            'store_tagging' => 'required',
        ];

        if($request->has_fraction==1){
            $rules['decimal_point_place'] = 'required'; 
            $rules['round_of'] = 'required|numeric'; 
        }else{
            if($request->use_serial==1){
                $rules['numeric_part'] = 'required';
                $rules['prefix'] = 'required';
                $rules['separator_symbol'] = 'required';
            }
        }
        $validated = $request->validate($rules);
        $charAr = ['#','&','@','?','(',')',':',';','<','>','[',']'];
        if(Str::contains($request->sku,$charAr)){
            Session::flash('errorMessage', 'Special charecter are not allowed in sku name');
            return redirect()->back();
        }else{
            DB::beginTransaction();
            try {
                $stockItem=CadetInventoryProduct::where('id','=',$id)->first();
                // stock history
                $update_history = self::stockItemHistory($request, $stockItem);
                $update_status = $update_history[0]; 
                $update_log = $update_history[1]; 
                $update_check_status = true;
                $store_tagging_db =  $stockItem->store_tagging;
                $newStore = array_diff($request->store_tagging, $store_tagging_db);
                $delStore = array_diff($store_tagging_db, $request->store_tagging);
                if(count($delStore)>0){
                    $update_status=true;
                }

                if($update_status){
                    $update_checkInfo = self::itemUpdateDependencyCheck($id,$delStore);
                    $update_check_status=$update_checkInfo['status'];
                    $msg=$update_checkInfo['msg'];

                }
                if($update_check_status){
                    $stockItem->product_name = $request->product_name;
                    $stockItem->product_description = $request->product_description;
                    $stockItem->unit = $request->unit;
                    $stockItem->stock_group = $request->stock_group;
                    $stockItem->use_serial = $request->use_serial;
                    $stockItem->has_fraction = $request->has_fraction;
                    if($request->has_fraction==1){
                        $stockItem->round_of = $request->round_of;
                        $stockItem->decimal_point_place = $request->decimal_point_place;
                    }else{
                        $stockItem->round_of = null;
                        $stockItem->decimal_point_place = null;
                        if($request->use_serial==1){
                            $stockItem->numeric_part = $request->numeric_part;
                            $stockItem->prefix = $request->prefix;
                            $stockItem->suffix = $request->suffix;
                            $stockItem->separator_symbol = $request->separator_symbol;
                        }else{
                            $stockItem->numeric_part = null;
                            $stockItem->prefix = null;
                            $stockItem->suffix = null;
                            $stockItem->separator_symbol = null; 
                        }
                    }

                    $stockItem->min_stock = $request->min_stock;
                    $stockItem->item_type = $request->item_type;
                    $stockItem->alias = $request->alias;
                    $stockItem->sku = $request->sku;
                    $stockItem->barcode = $request->sku;
                    $stockItem->qrcode = $request->sku;
                    $stockItem->code_type_id = $request->code_type_id;
                    $stockItem->category_id = $request->category_id;
                    $stockItem->warrenty_month = $request->warrenty_month;
                    $stockItem->reorder_qty = $request->reorder_qty;
                    $stockItem->additional_remarks = $request->additional_remarks;
                    
                    $stockItem->store_tagging = $request->store_tagging;
                    $stockItem->campus_id = $this->academicHelper->getCampus();
                    $stockItem->institute_id = $this->academicHelper->getInstitute();
                    $stockItem->created_by = Auth::user()->id;

                    if ($request->image) {
                        $photoFile = $request->file('image');
                        $fileExtension = $photoFile->getClientOriginalExtension();
                        $contentName = "inv" . date("Ymdhis") . mt_rand(100000, 999999) . "." . $fileExtension;
                        $contentFileName = $contentName;
                        $uploaded = $photoFile->move('assets/inventory/products/', $contentFileName);
                        if(!empty($stockItem->image)){
                            $file_path = public_path().'/assets/inventory/products/'.$stockItem->image;
                            if(file_exists($file_path)) unlink($file_path);
                        }
                        $stockItem->image = $contentFileName;
                    }
                    $stockSave = $stockItem->save();
                    // store wise item save
                    if(count($newStore)>0){
                        foreach ($newStore as $key => $value) {
                            $storeData = [
                                'item_id'=>$stockItem->id,
                                'store_id'=>$value,
                                'institute_id'=>$this->academicHelper->getInstitute(),
                                'campus_id'=>$this->academicHelper->getCampus(),
                                'created_by'=>Auth::user()->id,
                                'created_at'=>date('Y-m-d H:i:s')
                            ];
                            DB::table('inventory_store_wise_item')->insert($storeData);
                            $stockInfo  = InventoryStore::module()->find($value);
                            $update_log[]='New store "'.$stockInfo->store_name.'" tagged';
                        }
                        
                    }
                    // delete store item
                    if(count($delStore)>0){
                        DB::table('inventory_store_wise_item')->where('institute_id', $this->academicHelper->getInstitute())->where('campus_id', $this->academicHelper->getCampus())->where('item_id', $id)->whereIn('store_id', $delStore)->update([
                                'deleted_by'=>Auth::user()->id,
                                'deleted_at'=>date('Y-m-d H:i:s'),
                                'valid'=>0
                            ]);
                        foreach ($delStore as $del) {
                            $stockInfo  = InventoryStore::find($del);
                            $update_log[]='Remove store tagged "'.$stockInfo->store_name.'"';
                        }
                    }
                    
                    if ($stockSave) {
                        if(count($update_log)>0){
                            $update_history = '';
                            $i=0;
                            foreach ($update_log as $v) {
                                if(++$i!=0 && count($update_log)!=$i) $update_history.=', ';
                                $update_history.=$v;
                            }
                            $itemHistory = new CadetInventoryProductHistory();
                            $itemHistory->product_id = $stockItem->id;
                            $itemHistory->remarks = $update_history;
                            $itemHistory->created_by = Auth::user()->id;
                            $historyDataSave = $itemHistory->save();
                        }
                    }
                    DB::commit();
                    Session::flash('message', 'Stock Item  update successfully.');
                }else{
                    Session::flash('errorMessage', @$msg);
                }
                return redirect()->back();
                
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Error updating Stock item.');
                return redirect()->back();
            }
        }

    }

    public static function stockItemHistory($request, $stockItem){
        $update_log = []; $update_status=false;
        if($stockItem->product_name != $request->product_name){
            $update_log[]='Item name change form "'.$stockItem->product_name.'" to "'.$request->product_name.'"';
            $update_status=true;
    
        }
        if($stockItem->product_description != $request->product_description){
            $update_log[]='Product description change form "'.$stockItem->product_description.'" to "'.$request->product_description.'"';
        }
        if($stockItem->alias != $request->alias){
            $update_log[]='Alias change form "'.$stockItem->alias.'" to "'.$request->alias.'"';
            $update_status=true;
        }
        if($stockItem->sku != $request->sku){
            $update_log[]='Sku change form "'.$stockItem->sku.'" to "'.$request->sku.'"';
            $update_status=true;
        }
        if($stockItem->unit != $request->unit){
            $previousUnit = StockUOM::find($stockItem->unit);
            $curentUnit = StockUOM::find($request->unit);
            $update_log[]='Unit change form "'.$previousUnit->symbol_name.'" to "'.$curentUnit->symbol_name.'"';
            $update_status=true;
        }
        if($stockItem->stock_group != $request->stock_group){
            $previousStock = StockGroup::find($stockItem->unit);
            $curentStock = StockGroup::find($request->stock_group);
            $update_log[]='Group change form "'.$previousStock->stock_group_name.'" to "'.$curentStock->stock_group_name.'"';
            $update_status=true;
        }
        if($stockItem->min_stock != $request->min_stock){
            $update_log[]='Min Stock change form "'.$stockItem->min_stock.'" to "'.$request->min_stock.'"';
        }
        if($stockItem->item_type != $request->item_type){
            $item_type_array = ['Non-Inventory Item', 'Inventory Item'];
            $update_log[]='Item type change form "'.@$item_type_array[$stockItem->item_type].'" to "'.@$item_type_array[$request->item_type].'"';
            $update_status=true;
        }
        $yes_no = ['No', 'Yes'];
        if($stockItem->has_fraction != $request->has_fraction){
            $update_log[]='Has fraction change form "'.@$yes_no[$stockItem->has_fraction].'" to "'.@$yes_no[$request->has_fraction].'"';
            $update_status=true;
        }
        if($stockItem->use_serial != $request->use_serial){
            $update_log[]='Use serial change form "'.@$yes_no[$stockItem->use_serial].'" to "'.@$yes_no[$request->use_serial].'"';
            $update_status=true;
        }
        if($stockItem->code_type_id != $request->code_type_id){
            $code_types = [1=>'General Goods',2=>'Finished Goods'];
            $update_log[]='Type change form "'.@$code_types[$stockItem->code_type_id].'" to "'.@$code_types[$request->code_type_id].'"';
            $update_status=true;
        }

        if($stockItem->category_id != $request->category_id){
            $previousStock = StockCategory::find($stockItem->category_id);
            $curentStock = StockCategory::find($request->category_id);
            $update_log[]='Category change form "'.$previousStock->stock_category_name.'" to "'.$curentStock->stock_category_name.'"';
            $update_status=true;
        }


        if($stockItem->reorder_qty != $request->reorder_qty){
            if(!empty($stockItem->reorder_qty) && !empty($request->reorder_qty)){
                $update_log[]='Re order Qty change form "'.$stockItem->reorder_qty.'" to "'.$request->reorder_qty.'"';
            }else{
                if(!empty($request->reorder_qty)){
                    $update_log[]='"'.$request->reorder_qty.'" Re order Qty Added';
                }else{
                    $update_log[]='Re order Qty update'; 
                }
            }
        }

        return [$update_status,$update_log]; 
    }
    //    Voucher Config
    public function voucherConfigList()
    {
        $vouchers = CadetInventoryVoucher::module()->join('setting_institute', 'setting_institute.id', 'cadet_voucher_config.institute_id')
            ->join('setting_campus', 'setting_campus.id', 'cadet_voucher_config.campus_id')
            ->select('cadet_voucher_config.*','setting_institute.institute_name','setting_campus.name as campus_name')
            ->orderBy('cadet_voucher_config.id', 'desc')
            ->get();
        return view('inventory::voucher.voucher-config-list',compact('vouchers'));
    }
    public function addVoucherConfig()
    {
        $instititue_list = Institute::where('id', self::getInstituteId())->orderBy('institute_name', 'asc')->get();
        return view('inventory::voucher.modal.add-voucher-config', compact('instititue_list'));
    }
    public function storeVoucherConfig(Request $request){
        $campus_id = $request->campus_id;
        $campus_info = Campus::find($campus_id);
        $institute_id = $campus_info->institute_id;
        $rules = [
            'campus_id' => 'required',
            'voucher_name' => 'required',
            'type_of_voucher' => 'required',
            'numbering' => 'required',
            'status' => 'required'
        ];
        if($request->numbering=='auto'){
            $rules['numeric_part'] = 'required';
            $rules['starting_number'] = 'required|numeric';
            $rules['prefix'] = 'required';
        }
        $validated = $request->validate($rules);
       
        DB::beginTransaction();
        try {
            $voucher = new CadetInventoryVoucher();
            $voucher->type_of_voucher = $request->type_of_voucher;
            $voucher->numbering = $request->numbering;
            $voucher->voucher_name = $request->voucher_name;

            if($request->numbering=='auto'){
                $voucher->numeric_part = $request->numeric_part;
                $voucher->suffix = $request->suffix;
                $voucher->starting_number = $request->starting_number;
                $voucher->prefix = $request->prefix;
            }else{
                $voucher->numeric_part = null;
                $voucher->suffix = null;
                $voucher->starting_number = null;
                $voucher->prefix = null; 
            }
            $voucher->campus_id = $request->campus_id;
            $voucher->institute_id = $institute_id;
            $voucher->status = $request->status;
            $voucher->created_by = Auth::user()->id;
            if($request->status==1){
                CadetInventoryVoucher::where('institute_id',$institute_id)->where('campus_id',$request->campus_id)->where('type_of_voucher', $request->type_of_voucher)->update(['status'=>'0']);
            }

            $voucherStore=$voucher->save();
            if ($voucherStore) {
                DB::commit();
                Session::flash('message', 'New Voucher created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating Voucher.');
                return redirect()->back();
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating Voucher.');
            return redirect()->back();
        }
    }
    public function editVoucherConfig($id)
    {
        $voucher=CadetInventoryVoucher::where('id','=',$id)->first();
        $instititue_list = Institute::where('id', self::getInstituteId())->orderBy('institute_name', 'asc')->get();
        return view('inventory::voucher.modal.edit-voucher-config',compact('voucher','instititue_list'));

    }
    public function updateVoucherConfig(Request $request, $id)
    {
        $campus_id = $request->campus_id;
        $campus_info = Campus::find($campus_id);
        $institute_id = $campus_info->institute_id;

        $rules = [
            'campus_id' => 'required',
            'voucher_name' => 'required',
            'type_of_voucher' => 'required',
            'numbering' => 'required',
        ];
        if($request->numbering=='auto'){
            $rules['numeric_part'] = 'required';
            $rules['starting_number'] = 'required|numeric';
            $rules['prefix'] = 'required';
        }
        $validated = $request->validate($rules);

        $voucher=CadetInventoryVoucher::where('id','=',$id)->first();
        DB::beginTransaction();
        try {
            $checkUpdate = true;
            if($voucher->campus_id != $request->campus_id || $voucher->type_of_voucher != $request->type_of_voucher || $voucher->numbering != $request->numbering || $voucher->voucher_name != $request->voucher_name || (($voucher->numbering == $request->numbering && $voucher->numbering=='auto') &&  $voucher->numeric_part != $request->numeric_part || $voucher->suffix != $request->suffix || $voucher->starting_number != $request->starting_number || $voucher->prefix != $request->prefix)){
                $checkUpdate = self::checkVoucherTransaction($voucher->type_of_voucher,$voucher->campus_id, $voucher->institute_id);
            }
            if($checkUpdate){
                $voucher->type_of_voucher = $request->type_of_voucher;
                $voucher->numbering = $request->numbering;
                $voucher->voucher_name = $request->voucher_name;
                if($request->numbering=='auto'){
                    $voucher->numeric_part = $request->numeric_part;
                    $voucher->suffix = $request->suffix;
                    $voucher->starting_number = $request->starting_number;
                    $voucher->prefix = $request->prefix;
                }else{
                    $voucher->numeric_part = null;
                    $voucher->suffix = null;
                    $voucher->starting_number = null;
                    $voucher->prefix = null;
                }
                $voucher->campus_id = $request->campus_id;
                $voucher->institute_id = $institute_id;
                $voucher->status = $request->status;
                $voucher->updated_by = Auth::user()->id;
                $voucherUpdate = $voucher->save();
                if($request->status==1){
                    CadetInventoryVoucher::where('institute_id',$institute_id)->where('campus_id',$request->campus_id)->where('type_of_voucher', $request->type_of_voucher)->where('id', '!=', $id)->update(['status'=>'0']);
                }
                if ($voucherUpdate) {
                    DB::commit();
                    Session::flash('message', 'Voucher Update successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Error Update Voucher.');
                    return redirect()->back();
                }
            }else{
                Session::flash('errorMessage', 'Sorry voucher configuration has data dependancy.');
                return redirect()->back(); 
            }
        }catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error Update Voucher.');
            return redirect()->back();
        }

    }

    public static  function checkVoucherTransaction($type_of_voucher, $campus_id, $institute_id){
        $checkUpdate = true;
        if($type_of_voucher==1){
            $newRequisitionInfo = DB::table('inventory_new_requisition_info')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($newRequisitionInfo)){
                $checkUpdate = false;
            }
        }
        if($type_of_voucher==2){
            $inventoryIssue = DB::table('inventory_issue_from')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($inventoryIssue)){
                $checkUpdate = false;
            }
        }
        if($type_of_voucher==3){
            $storeTransferReq = DB::table('inventory_transfer_requisition')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($storeTransferReq)){
                $checkUpdate = false;
            }
        }
        if($type_of_voucher==4){
            $storeTransfer = DB::table('inventory_transfer_info')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($storeTransfer)){
                $checkUpdate = false;
            }
        }

        if($type_of_voucher==5){
            $purchaseRequisitionInfo = DB::table('inventory_purchase_requisition_info')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($purchaseRequisitionInfo)){
                $checkUpdate = false;
            }
        }
        if($type_of_voucher==14){
            $comparativeStatementInfo = DB::table('inventory_comparative_statement_info')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($comparativeStatementInfo)){
                $checkUpdate = false;
            }
        }
        if($type_of_voucher==15 || $type_of_voucher==16){
            $purchase_category = ($type_of_voucher==15)?'general':'lc';
            $purchaseOrderInfo = DB::table('inventory_purchase_order_info')->where('purchase_category', $purchase_category)->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($purchaseOrderInfo)){
                $checkUpdate = false;
            }
        }

        
        if($type_of_voucher==7){
            $purchaseReceiveInfo = DB::table('inventory_purchase_receive_info')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($purchaseReceiveInfo)){
                $checkUpdate = false;
            }
        }

        if($type_of_voucher==8){
            $purchaseReturnInfo = DB::table('inventory_purchase_return_info')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($purchaseReturnInfo)){
                $checkUpdate = false;
            }
        }
        if($type_of_voucher==9){
            $salesOrder = DB::table('inventory_sales_order_info')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($salesOrder)){
                $checkUpdate = false;
            }
        }
        if($type_of_voucher==10){
            $salesChallan = DB::table('inventory_sales_challan_delivery_info')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($salesChallan)){
                $checkUpdate = false;
            }
        }
        if($type_of_voucher==11){
            $salesReturn = DB::table('inventory_sales_return_info')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($salesReturn)){
                $checkUpdate = false;
            }
        }
        if($type_of_voucher==12){
            $directStockIn = DB::table('inventory_direct_stock_in')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($directStockIn)){
                $checkUpdate = false;
            }
        }

        if($type_of_voucher==13){
            $directStockOut = DB::table('inventory_direct_stock_out')->where('valid', 1)->where('campus_id',$campus_id)->where('institute_id',$institute_id)->first();
            if(!empty($directStockOut)){
                $checkUpdate = false;
            }
        }

        return $checkUpdate; 
    } 

    public function showHistoryProduct($id)
    {
        $histories= CadetInventoryProductHistory::where('product_id','=',$id)->get();
       return view('inventory::stock.modal.product-history',compact('histories'));
    }

}

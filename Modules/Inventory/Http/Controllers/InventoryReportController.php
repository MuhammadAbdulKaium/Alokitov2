<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\Campus;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use Modules\Inventory\Entities\StockGroup;
use Modules\Inventory\Entities\StockCategory;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Inventory\Entities\StockInDetailsModel;
use Modules\Inventory\Entities\StockOutDetailsModel;
use Modules\Inventory\Entities\PurchaseReceiveDetailsModel;
use Modules\Inventory\Entities\IssueFromInventoryDetailsModel;
use Modules\Inventory\Entities\InventoryStore;
use Modules\Inventory\Entities\StoreWiseItemModel;
use DateTime;
use Illuminate\Support\Carbon;

// use App;
use App\Helpers\ExamHelper;
use PDF;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use App\Helpers\UserAccessHelper;
use App\Subject;
use Mpdf\Tag\Em;

class InventoryReportController extends Controller
{
    use UserAccessHelper;
    use ExamHelper;
    private $academicHelper;
    private $academicsLevel;

    private $stockGroup;
    private $stockCategory;
    private $cadetInventoryProduct;
    private $inventoryStore;
    private $storeWiseItemModel;

    public function __construct(AcademicHelper $academicHelper, AcademicsLevel $academicsLevel, StockGroup $stockGroup, StockCategory $stockCategory, CadetInventoryProduct $cadetInventoryProduct, InventoryStore $inventoryStore)
    {
        $this->academicHelper = $academicHelper;
        $this->academicsLevel = $academicsLevel;

        $this->stockGroup = $stockGroup;
        $this->stockCategory = $stockCategory;
        $this->cadetInventoryProduct = $cadetInventoryProduct;
        $this->inventoryStore = $inventoryStore;
    }


    public function storeLedgerReport(Request $request)
    {
        // $pageAccessData = self::linkAccess($request);
        $campus = Campus::findOrFail($this->academicHelper->getCampus());
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());

        $stockGroups = $this->stockGroup->where(['institute_id' => $institute->id, 'campus_id' => $campus->id])->get();
        error_log('campus Id');
        error_log($campus->id);
        error_log('institute Id');
        error_log($institute->id);
        error_log('stockGroups');
        error_log($stockGroups);
        $productCatagories = $this->stockCategory->where(['institute_id' => $institute->id, 'campus_id' => $campus->id])->get();
        $products = $this->cadetInventoryProduct->where(['institute_id' => $institute->id, 'campus_id' => $campus->id])->get();
        $stores = $this->inventoryStore->where(['institute_id' => $institute->id, 'campus_id' => $campus->id])->get();

        return view('inventory::reports.store-ledger-reports', compact('stockGroups', 'productCatagories', 'products', 'stores'));
    }

    // public function storeSearchProduct(Request $request)
    // {
    //     if($request->data && $request->groupId && $request->categoryId) {
            
    //         return CadetInventoryProduct::where('category_id', $request->categoryId)->where('stock_group', $request->groupId)->get();
    //     }
    //     else {
    //         return [];
    //     }
    // }


    public function storeSearchCategory(Request $request)
    {
        $campus = Campus::findOrFail($this->academicHelper->getCampus());
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());

        if($request->data) {
            $productCategoryIds = CadetInventoryProduct::select('category_id')->where(['stock_group' => $request->data, 'institute_id' => $institute->id, 'campus_id' => $campus->id])->distinct()->get();
            error_log('$productCategoryIds');
            error_log($productCategoryIds);
            if($productCategoryIds) {
                return StockCategory::whereIn('id', $productCategoryIds)->get();
            }
            return [];
        }
        else {
            return [];
        }
    }

    // public function searchItemLedgerReport(Request $request)
    // {
    //     $campus = Campus::findOrFail($this->academicHelper->getCampus());
    //     $institute = Institute::findOrFail($this->academicHelper->getInstitute());
    //     $productGroupId = $request->productGroupId;
    //     $productCategoryId = $request->productCategoryId;
    //     $productId = $request->productId;
    //     $fromDate = $request->fromDate;
    //     $toDate = $request->toDate;
    //     if(!empty($fromDate)){
    //         $fromDate = DateTime::createFromFormat('Y-m-d', $fromDate)->format('d-m-Y');
    //     }
    //     if(!empty($toDate)){
    //         $toDate = DateTime::createFromFormat('Y-m-d', $toDate)->format('d-m-Y');
    //     }

    //     $group = StockGroup::findOrFail($request->productGroupId);
    //     $category = StockCategory::findOrFail($request->productCategoryId);
    //     $stores = $request->storeId;
    //     $all = 0;
    //     $stores1 = array();
        

    //     if($stores[0] == 'all'){
    //         $all = 'all';
    //         // unset($stores[0]);
    //         // error_log(count($stores));
            
    //         $stores1 = InventoryStore::pluck('id')->toArray();
    //         error_log($stores1[8]);
            
    //     }
    //     else{
    //         $all = InventoryStore::findOrFail($request->storeId);
    //         $stores1 = $stores;
    //     }


    //     $product = CadetInventoryProduct::where([
    //         ['id', $request->productId],
    //         ['category_id', $request->productCategoryId],
    //         ['stock_group', $request->productGroupId],
    //     ])->first();

    //     $products = CadetInventoryProduct::where('category_id', $request->productCategoryId)->where('stock_group', $request->productGroupId)->get();

    //     $directStockInItems = StockInDetailsModel::join('inventory_direct_stock_in', 'inventory_direct_stock_in.id', 'inventory_direct_stock_in_details.stock_in_id')
    //     ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_direct_stock_in_details.item_id')
    //     ->join('cadet_inventory_store', 'cadet_inventory_store.id', 'inventory_direct_stock_in.store_id')
    //     ->select('cadet_stock_products.id', 'cadet_stock_products.product_name',DB::raw("DATE_FORMAT(date,'%d-%m-%Y') AS tran_date"), 'cadet_inventory_store.id AS store_id', 'cadet_inventory_store.store_name', 'inventory_direct_stock_in.voucher_no', 'inventory_direct_stock_in.category', 'inventory_direct_stock_in_details.qty', 'inventory_direct_stock_in_details.rate', 'inventory_direct_stock_in_details.amount')
    //     ->selectRaw('"inward" AS type')
    //     ->where(['item_id' => $productId,
    //     'inventory_direct_stock_in.status' => 1,
    //     'inventory_direct_stock_in.campus_id' => $campus->id,
    //     'inventory_direct_stock_in.institute_id' => $institute->id,
    //     'inventory_direct_stock_in_details.status' => 1,
    //     'inventory_direct_stock_in_details.campus_id' => $campus->id,
    //     'inventory_direct_stock_in_details.institute_id' => $institute->id])       
    //     ->whereIn('inventory_direct_stock_in.store_id', $stores1)
    //     ->get();

    //     $directStockInItemsArray = json_decode($directStockInItems);
        
    //     $receivedPurchases = PurchaseReceiveDetailsModel::join('inventory_purchase_receive_info', 'inventory_purchase_receive_info.id', 'inventory_purchase_receive_details.pur_receive_id')
    //     ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_purchase_receive_details.item_id')
    //     ->join('cadet_inventory_store', 'cadet_inventory_store.id', 'inventory_purchase_receive_info.store_id')
    //     ->join('inventory_vendor_info', 'inventory_vendor_info.id', 'inventory_purchase_receive_info.vendor_id')
    //     ->select('cadet_stock_products.id', 'cadet_stock_products.product_name',DB::raw("DATE_FORMAT(date,'%d-%m-%Y') AS tran_date"), 'cadet_inventory_store.id AS store_id', 'cadet_inventory_store.store_name', 'inventory_purchase_receive_info.voucher_no', 'inventory_purchase_receive_details.reference_type AS category', 'inventory_purchase_receive_details.rec_qty AS qty', 'inventory_purchase_receive_details.rate', 'inventory_purchase_receive_details.total_amount AS amount', 'inventory_vendor_info.id AS vendor_id', 'inventory_vendor_info.name AS vendor_name')
    //     ->selectRaw('"inward" AS type')
    //     ->where(['item_id' => $productId,
    //     'inventory_purchase_receive_info.status' => 1,
    //     'inventory_purchase_receive_info.campus_id' => $campus->id,
    //     'inventory_purchase_receive_info.institute_id' => $institute->id,
    //     'inventory_purchase_receive_details.status' => 1,
    //     'inventory_purchase_receive_details.campus_id' => $campus->id,
    //     'inventory_purchase_receive_details.institute_id' => $institute->id])      
    //     ->whereIn('inventory_purchase_receive_info.store_id', $stores1)
    //     ->get();
       
    //     $receivedPurchasesArray = json_decode($receivedPurchases);

    //     error_log('directStockOutItems');
    //     $directStockOutItems = StockOutDetailsModel::join('inventory_direct_stock_out', 'inventory_direct_stock_out.id', 'inventory_direct_stock_out_details.stock_out_id')
    //     ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_direct_stock_out_details.item_id')
    //     ->join('cadet_inventory_store', 'cadet_inventory_store.id', 'inventory_direct_stock_out.store_id')
    //     ->select('cadet_stock_products.id', 'cadet_stock_products.product_name',DB::raw("DATE_FORMAT(date,'%d-%m-%Y') AS tran_date"), 'cadet_inventory_store.id AS store_id', 'cadet_inventory_store.store_name', 'inventory_direct_stock_out.store_id', 'inventory_direct_stock_out.voucher_no', 'inventory_direct_stock_out.category', 'inventory_direct_stock_out_details.qty', 'inventory_direct_stock_out_details.rate', 'inventory_direct_stock_out_details.amount')
    //     ->selectRaw('"outward" AS type')
    //     ->where(['item_id' => $productId,
    //     'inventory_direct_stock_out.status' => 1,
    //     'inventory_direct_stock_out.campus_id' => $campus->id,
    //     'inventory_direct_stock_out.institute_id' => $institute->id,
    //     'inventory_direct_stock_out_details.status' => 1,
    //     'inventory_direct_stock_out_details.campus_id' => $campus->id,
    //     'inventory_direct_stock_out_details.institute_id' => $institute->id])
    //     ->whereIn('inventory_direct_stock_out.store_id', $stores1)
    //     ->get();

    //     $directStockOutItemsArray = json_decode($directStockOutItems);


    //     error_log('FromInventory');
    //     $issueFromInventory = IssueFromInventoryDetailsModel::join('inventory_issue_from', 'inventory_issue_from.id', 'inventory_issue_details.issue_id')
    //     ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_issue_details.item_id')
    //     ->join('cadet_inventory_store', 'cadet_inventory_store.id', 'inventory_issue_from.store_id')
    //     ->select('cadet_stock_products.id', 'cadet_stock_products.product_name',DB::raw("DATE_FORMAT(date,'%d-%m-%Y') AS tran_date"), 'cadet_inventory_store.id AS store_id', 'cadet_inventory_store.store_name', 'inventory_issue_from.voucher_no', 'inventory_issue_from.reference_type AS category', 'inventory_issue_details.issue_qty AS qty', 'inventory_issue_details.rate')
    //     ->selectRaw('"outward" AS type')
    //     ->where(['item_id' => $productId,
    //     'inventory_issue_from.status' => 1,
    //     'inventory_issue_from.campus_id' => $campus->id,
    //     'inventory_issue_from.institute_id' => $institute->id,
    //     'inventory_issue_details.status' => 1,
    //     'inventory_issue_details.campus_id' => $campus->id,
    //     'inventory_issue_details.institute_id' => $institute->id])
    //     ->whereIn('inventory_issue_from.store_id', $stores1)
    //     ->get();

    //     $issueFromInventoryArray = json_decode($issueFromInventory);
    //     $directStockInItemsArray = array_merge($directStockInItemsArray, $receivedPurchasesArray, $directStockOutItemsArray, $issueFromInventoryArray);
        

    //     $result = json_decode(json_encode($directStockInItemsArray), true);
    //     $lowest = array();
        
    //     for($i=0; $i<count($result); $i++){
    //         for($j=$i+1; $j<count($result); $j++){
    //             if(strtotime($result[$i]['tran_date']) > strtotime($result[$j]['tran_date'])){
    //                 $lowest[$i] = $result[$i];
    //                 $result[$i] = $result[$j];
    //                 $result[$j] = $lowest[$i];
    //             }
    //         }
    //     }

        
    //     $openingQty = 0;
    //     $openingRate = 0;
    //     $openingValue = 0;

    //     $closingQty = 0;
    //     $closingRate = 0;
    //     $closingValue = 0;

    //     $inwardQtyGrandTotal = 0;
    //     $inwardRateGrandTotal = 0;
    //     $inwardValueGrandTotal = 0;

    //     $outwardQtyGrandTotal = 0;
    //     $outwardRateGrandTotal = 0;
    //     $outwardValueGrandTotal = 0;

    //     $closingQtys = array();
    //     $closingRates = array();
    //     $closingValues = array();

    //     for($i=0; $i<count($result); $i++){
    //         if(strtotime($result[$i]['tran_date']) < strtotime($fromDate)){
    //             if($result[$i]['type'] === 'inward'){
    //                 $closingQty = $openingQty += $result[$i]['qty'];
    //                 $closingValue = $openingValue += $result[$i]['amount'];
    //                 $closingRate = $openingRate = $openingValue/$openingQty;
    //             }
    //             if($result[$i]['type'] === 'outward'){
    //                 $closingQty = $openingQty -= $result[$i]['qty'];
    //                 $outwardValue = $closingRate*$result[$i]['qty'];
    //                 $closingValue = $openingValue -= $outwardValue;
    //                 $closingRate = $openingRate = $openingValue/$openingQty;
    //             }
    //         }
    //         elseif((strtotime($result[$i]['tran_date']) >= strtotime($fromDate)) && (strtotime($result[$i]['tran_date']) <= strtotime($toDate))){
    //             if($result[$i]['type'] == 'inward'){
    //                 error_log($closingRate);
    //                 $closingQty += $result[$i]['qty'];
    //                 $closingValue += $result[$i]['amount'];
    //                 $closingRate = $closingValue/$closingQty;

    //                 $inwardQtyGrandTotal += $result[$i]['qty'];
    //                 $inwardValueGrandTotal += $result[$i]['amount'];
    //                 $inwardRateGrandTotal = $inwardValueGrandTotal/$inwardQtyGrandTotal;

    //                 $closingQtys[$result[$i]['voucher_no']] = $closingQty;
    //                 $closingRates[$result[$i]['voucher_no']] = $closingRate;
    //                 $closingValues[$result[$i]['voucher_no']] = $closingValue;
    //             }
    //             if($result[$i]['type'] == 'outward'){
    //                 $result[$i]['rate'] = $closingRate;
                    
    //                 $closingQty -= $result[$i]['qty'];
    //                 $outwardValue = $closingRate*$result[$i]['qty'];
    //                 $closingValue -= $outwardValue;
    //                 $closingRate = $closingValue/$closingQty;

    //                 $outwardQtyGrandTotal += $result[$i]['qty'];
    //                 $outwardValueGrandTotal += $outwardValue;
    //                 $outwardRateGrandTotal = $outwardValueGrandTotal/$outwardQtyGrandTotal;

    //                 $closingQtys[$result[$i]['voucher_no']] = $closingQty;
    //                 $closingRates[$result[$i]['voucher_no']] = $closingRate;
    //                 $closingValues[$result[$i]['voucher_no']] = $closingValue;
    //             }
    //         }           
    //     }

    //     if ($request->type == "print") {
    //         $pdf = App::make('dompdf.wrapper');
    //         $pdf->getDomPDF()->set_option("enable_php", true);
    //         $user = Auth::user();
    //         $pdf->loadView('inventory::reports.store-ledger-reports-pdf', compact('user', 'institute', 'products', 'product', 'group', 'category', 'fromDate', 'toDate', 'result', 'openingQty', 'openingRate', 'openingValue', 'closingQtys', 'closingRates', 'closingValues', 'openingQty', 'openingRate', 'openingValue', 'inwardQtyGrandTotal', 'inwardRateGrandTotal', 'inwardValueGrandTotal', 'outwardQtyGrandTotal', 'outwardRateGrandTotal', 'outwardValueGrandTotal', 'closingQty', 'closingRate', 'closingValue', 'all'))->setPaper('a2', 'landscape');
    //         return $pdf->stream();
    //     }
    //     else{
    //         return view('inventory::reports.store-ledger-reports-table', compact('products', 'product', 'group', 'category', 'fromDate', 'toDate', 'result', 'openingQty', 'openingRate', 'openingValue', 'closingQtys', 'closingRates', 'closingValues', 'openingQty', 'openingRate', 'openingValue', 'inwardQtyGrandTotal', 'inwardRateGrandTotal', 'inwardValueGrandTotal', 'outwardQtyGrandTotal', 'outwardRateGrandTotal', 'outwardValueGrandTotal', 'closingQty', 'closingRate', 'closingValue', 'all'))->render();
    //     }
    // }






    // public function stockSummaryReport(Request $request)
    // {
    //     $stockGroups = $this->stockGroup->all();
    //     $productCatagories = $this->stockCategory->all();
    //     $products = $this->cadetInventoryProduct->all();
    //     $stores = $this->inventoryStore->all();

    //     return view('inventory::reports.stock-summary-reports', compact('stockGroups', 'productCatagories', 'products', 'stores'));
    // }

    // public function stockSearchCategory(Request $request){
    //     $groups = array();
    //     if($request->group[0] == 'all'){
    //         $groups = StockGroup::pluck('id')->toArray();
    //         error_log($groups[0]);
    //     }
    //     else {
    //         $groups = $request->group;
    //     }

    //     if($groups){
    //         $productCatagoryIds = CadetInventoryProduct::select('category_id')->whereIn('stock_group', $groups)->distinct()->get();
    //         if($productCatagoryIds){
    //             $categories = StockCategory::whereIn('id', $productCatagoryIds)->get();
    //             echo json_encode(array('categories'=>$categories,'groups'=>$groups));
    //         }
    //         else{
    //             return [];
    //         }
    //     }
    //     else{
    //         return [];
    //     }
    // }

    // public function stockSearchStore(Request $request){
    //     error_log('HI HI HI HI HI');
    //     error_log($request->groups[0]);

    //     $categories = array();

    //     error_log($request->category[0]);

    //     if($request->category[0] == 'all'){
    //         $categories = StockCategory::pluck('id')->toArray();
    //     }
    //     else {
    //         $categories = $request->category;
    //     }
    //     if($categories){
    //         $productStoreIds = StockInDetailsModel::join('inventory_direct_stock_in', 'inventory_direct_stock_in.id', 'inventory_direct_stock_in_details.stock_in_id')
    //         ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_direct_stock_in_details.item_id')
    //         ->select('inventory_direct_stock_in.store_id')
    //         ->whereIn('cadet_stock_products.stock_group', $request->groups)
    //         ->whereIn('inventory_direct_stock_in.store_id', $categories)
    //         ->distinct()
    //         ->get();
            
            
    //         error_log($productStoreIds);
    //         $productStoreIds1 = StockInDetailsModel::with('detailStockInWise', 'detailProductWise')
    //         ->selct('store_id')
    //         ->whereIn('stock_group', $request->groups)
    //         ->whereIn('category_id', $categories)
    //         ->get();


    //         if($productStoreIds){
    //             $stores = StockCategory::whereIn('id', $productStoreIds)->get();
    //             echo json_encode(array('categories'=>$categories,'groups'=>$categories));
    //         }
    //         else{
    //             return [];
    //         }
    //     }
    //     else{
    //         return [];
    //     }
    // }

    // public function searchStockSummaryReport(Request $request)
    // {
    //     $campus = Campus::findOrFail($this->academicHelper->getCampus());
    //     $institute = Institute::findOrFail($this->academicHelper->getInstitute());

    //     $groupIds = array();
    //     $categoryIds = array();
    //     $storeIds = array();
    //     $productIds = array();
    //     $chunks = array();
    //     $fromDate = $request->fromDate;
    //     $toDate = $request->toDate;
    //     if(!empty($fromDate)){
    //         $fromDate = DateTime::createFromFormat('Y-m-d', $fromDate)->format('d-m-Y');
    //     }
    //     if(!empty($toDate)){
    //         $toDate = DateTime::createFromFormat('Y-m-d', $toDate)->format('d-m-Y');
    //     }
        

    //     if($request->groupId[0] == 'all'){
    //         $groupIds = StockGroup::pluck('id')->toArray();
    //     }
    //     else {
    //         $groupIds = $request->groupId;
    //     }

    //     if($request->categoryId[0] == 'all'){
    //         $categoryIds = StockCategory::pluck('id')->toArray();
    //     }
    //     else {
    //         $categoryIds = $request->categoryId;
    //     }

    //     if($request->storeId[0] == 'all'){
    //         $storeIds = InventoryStore::pluck('id')->toArray();
    //     }
    //     else {
    //         $storeIds = $request->storeId;
    //     }

    //     if($request->productId[0] == 'all'){

    //         $productIds = StockInDetailsModel::select('item_id')->distinct()->get();
    //     }
    //     else {
    //         $productIds = $request->productId;
    //     }

    //     // chunk(300, function($query, $productIds) {
    //     //     foreach ($productIds as $productId) {
    //     //         return $query->whereIn('item_id', $productId);
    //     //     }
    //     // })

    //     // when(count($chunks)>1, function($q,$chunks){
    //     //     foreach ($chunks as $chunk){
    //     //         return $q->whereIn('cadet_stock_products.id', $chunk);
    //     //     }
    //     // })



    //     $directStockInItems = StockInDetailsModel::join('inventory_direct_stock_in', 'inventory_direct_stock_in.id', 'inventory_direct_stock_in_details.stock_in_id')
    //     ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_direct_stock_in_details.item_id')
    //     ->join('cadet_inventory_store', 'cadet_inventory_store.id', 'inventory_direct_stock_in.store_id')
    //     ->join('cadet_inventory_stock_group', 'cadet_inventory_stock_group.id', 'cadet_stock_products.stock_group')
    //     ->select('cadet_stock_products.id', 'cadet_stock_products.product_name', 'cadet_stock_products.category_id', 'cadet_stock_products.stock_group', 'cadet_inventory_stock_group.stock_group_name',DB::raw("DATE_FORMAT(date,'%d-%m-%Y') AS tran_date"), 'cadet_inventory_store.id AS store_id', 'cadet_inventory_store.store_name', 'inventory_direct_stock_in.voucher_no', 'inventory_direct_stock_in.category', 'inventory_direct_stock_in_details.qty', 'inventory_direct_stock_in_details.rate', 'inventory_direct_stock_in_details.amount')
    //     ->selectRaw('"inward" AS type')
    //     ->whereIn('item_id', $productIds)
    //     ->whereIn('inventory_direct_stock_in.store_id', $storeIds)
    //     ->whereIn('cadet_stock_products.category_id', $categoryIds)
    //     ->whereIn('cadet_stock_products.stock_group', $groupIds)
    //     ->where(['inventory_direct_stock_in.status' => 1,
    //     'inventory_direct_stock_in.campus_id' => $campus->id,
    //     'inventory_direct_stock_in.institute_id' => $institute->id,
    //     'inventory_direct_stock_in_details.status' => 1,
    //     'inventory_direct_stock_in_details.campus_id' => $campus->id,
    //     'inventory_direct_stock_in_details.institute_id' => $institute->id]) 
    //     ->get();
        
    //     $directStockInItemsArray = json_decode($directStockInItems);
        
        
    //     if($request->productId[0] == 'all'){
            
    //         $productIds = PurchaseReceiveDetailsModel::select('item_id')->distinct()->get();
    //     }
    //     else {
    //         $productIds = $request->productId;
    //     }

    //     error_log('Purchase Receive');
    //     $receivedPurchases = PurchaseReceiveDetailsModel::join('inventory_purchase_receive_info', 'inventory_purchase_receive_info.id', 'inventory_purchase_receive_details.pur_receive_id')
    //     ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_purchase_receive_details.item_id')
    //     ->join('cadet_inventory_store', 'cadet_inventory_store.id', 'inventory_purchase_receive_info.store_id')
    //     ->join('inventory_vendor_info', 'inventory_vendor_info.id', 'inventory_purchase_receive_info.vendor_id')
    //     ->join('cadet_inventory_stock_group', 'cadet_inventory_stock_group.id', 'cadet_stock_products.stock_group')
    //     ->select('cadet_stock_products.id', 'cadet_stock_products.product_name', 'cadet_stock_products.category_id', 'cadet_stock_products.stock_group', 'cadet_inventory_stock_group.stock_group_name',DB::raw("DATE_FORMAT(date,'%d-%m-%Y') AS tran_date"), 'cadet_inventory_store.id AS store_id', 'cadet_inventory_store.store_name', 'inventory_purchase_receive_info.voucher_no', 'inventory_purchase_receive_details.reference_type AS category', 'inventory_purchase_receive_details.rec_qty AS qty', 'inventory_purchase_receive_details.rate', 'inventory_purchase_receive_details.total_amount AS amount', 'inventory_vendor_info.id AS vendor_id', 'inventory_vendor_info.name AS vendor_name')
    //     ->selectRaw('"inward" AS type')
    //     ->whereIn('item_id', $productIds)
    //     ->whereIn('inventory_purchase_receive_info.store_id', $storeIds)
    //     ->whereIn('cadet_stock_products.category_id', $categoryIds)
    //     ->whereIn('cadet_stock_products.stock_group', $groupIds)
    //     ->where(['inventory_purchase_receive_info.status' => 1,
    //     'inventory_purchase_receive_info.campus_id' => $campus->id,
    //     'inventory_purchase_receive_info.institute_id' => $institute->id,
    //     'inventory_purchase_receive_details.status' => 1,
    //     'inventory_purchase_receive_details.campus_id' => $campus->id,
    //     'inventory_purchase_receive_details.institute_id' => $institute->id])
    //     ->get();

    //     $receivedPurchasesArray = json_decode($receivedPurchases);


    //     if($request->productId[0] == 'all'){           
    //         $productIds = StockOutDetailsModel::select('item_id')->distinct()->get();
    //     }
    //     else {
    //         $productIds = $request->productId;
    //     }
        
    //     $directStockOutItems = StockOutDetailsModel::join('inventory_direct_stock_out', 'inventory_direct_stock_out.id', 'inventory_direct_stock_out_details.stock_out_id')
    //     ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_direct_stock_out_details.item_id')
    //     ->join('cadet_inventory_store', 'cadet_inventory_store.id', 'inventory_direct_stock_out.store_id')
    //     ->join('cadet_inventory_stock_group', 'cadet_inventory_stock_group.id', 'cadet_stock_products.stock_group')
    //     ->select('cadet_stock_products.id', 'cadet_stock_products.product_name', 'cadet_stock_products.category_id', 'cadet_stock_products.stock_group', 'cadet_inventory_stock_group.stock_group_name',DB::raw("DATE_FORMAT(date,'%d-%m-%Y') AS tran_date"), 'cadet_inventory_store.id AS store_id', 'cadet_inventory_store.store_name', 'inventory_direct_stock_out.store_id', 'inventory_direct_stock_out.voucher_no', 'inventory_direct_stock_out.category', 'inventory_direct_stock_out_details.qty', 'inventory_direct_stock_out_details.rate', 'inventory_direct_stock_out_details.amount')
    //     ->selectRaw('"outward" AS type')
    //     ->whereIn('item_id', $productIds)
    //     ->whereIn('inventory_direct_stock_out.store_id', $storeIds)
    //     ->whereIn('cadet_stock_products.category_id', $categoryIds)
    //     ->whereIn('cadet_stock_products.stock_group', $groupIds)
    //     ->where(['inventory_direct_stock_out.status' => 1,
    //     'inventory_direct_stock_out.campus_id' => $campus->id,
    //     'inventory_direct_stock_out.institute_id' => $institute->id,
    //     'inventory_direct_stock_out_details.status' => 1,
    //     'inventory_direct_stock_out_details.campus_id' => $campus->id,
    //     'inventory_direct_stock_out_details.institute_id' => $institute->id])
    //     ->get();

    //     $directStockOutItemsArray = json_decode($directStockOutItems);


    //     if($request->productId[0] == 'all'){           
    //         $productIds = IssueFromInventoryDetailsModel::select('item_id')->distinct()->get();
    //     }
    //     else {
    //         $productIds = $request->productId;
    //     }
    
    //     $issueFromInventory = IssueFromInventoryDetailsModel::join('inventory_issue_from', 'inventory_issue_from.id', 'inventory_issue_details.issue_id')
    //     ->join('cadet_stock_products', 'cadet_stock_products.id', 'inventory_issue_details.item_id')
    //     ->join('cadet_inventory_store', 'cadet_inventory_store.id', 'inventory_issue_from.store_id')
    //     ->join('cadet_inventory_stock_group', 'cadet_inventory_stock_group.id', 'cadet_stock_products.stock_group')
    //     ->select('cadet_stock_products.id', 'cadet_stock_products.product_name', 'cadet_stock_products.category_id', 'cadet_stock_products.stock_group', 'cadet_inventory_stock_group.stock_group_name',DB::raw("DATE_FORMAT(date,'%d-%m-%Y') AS tran_date"), 'cadet_inventory_store.id AS store_id', 'cadet_inventory_store.store_name', 'inventory_issue_from.voucher_no', 'inventory_issue_from.reference_type AS category', 'inventory_issue_details.issue_qty AS qty', 'inventory_issue_details.rate')
    //     ->selectRaw('"outward" AS type')
    //     ->whereIn('item_id', $productIds)
    //     ->whereIn('inventory_issue_from.store_id', $storeIds)
    //     ->whereIn('cadet_stock_products.category_id', $categoryIds)
    //     ->whereIn('cadet_stock_products.stock_group', $groupIds)
    //     ->where(['inventory_issue_from.status' => 1,
    //     'inventory_issue_from.campus_id' => $campus->id,
    //     'inventory_issue_from.institute_id' => $institute->id,
    //     'inventory_issue_details.status' => 1,
    //     'inventory_issue_details.campus_id' => $campus->id,
    //     'inventory_issue_details.institute_id' => $institute->id])
    //     ->get();

    //     $issueFromInventoryArray = json_decode($issueFromInventory);
    //     $directStockInItemsArray = array_merge($directStockInItemsArray, $receivedPurchasesArray, $directStockOutItemsArray, $issueFromInventoryArray);
        

    //     $result = json_decode(json_encode($directStockInItemsArray), true);
    //     $lowest = array();
        
    //     for($i=0; $i<count($result); $i++){
    //         for($j=$i+1; $j<count($result); $j++){
    //             if(strtotime($result[$i]['tran_date']) > strtotime($result[$j]['tran_date'])){
    //                 $lowest[$i] = $result[$i];
    //                 $result[$i] = $result[$j];
    //                 $result[$j] = $lowest[$i];
    //             }
    //         }
    //     }

    //     error_log('COUNT');
    //     $productsInArray = array_column($result, 'id');
    //     $productsInArray = array_unique($productsInArray);
    //     $productsInArray = array_values($productsInArray);
        
    //     $totalOpeningQty = 0;
    //     $totalOpeningValue = 0;
    //     $totalInwardQty = 0;
    //     $totalInwardValue = 0;
    //     $totalOutwardQty = 0;
    //     $totalOutwardValue = 0;
    //     $totalClosingQty = 0;
    //     $totalClosingValue = 0;

    //     $closingQtys = array();
    //     $closingRates = array();
    //     $closingValues = array();

    //     $stocks = array(0 => array(0 => array()));
    //     $allGroups = array();
    //     $allProducts = array();
    //     $k=0;
    //     $p=0;

    //     for($j=0; $j<count($groupIds); $j++){
    //         for($l=0; $l<count($productsInArray); $l++){  
    //             $openingQty = 0;
    //             $openingRate = 0;
    //             $openingValue = 0;

    //             $closingQty = 0;
    //             $closingRate = 0;
    //             $closingValue = 0;

    //             $inwardQtyGrandTotal = 0;
    //             $inwardRateGrandTotal = 0;
    //             $inwardValueGrandTotal = 0;

    //             $outwardQtyGrandTotal = 0;
    //             $outwardRateGrandTotal = 0;
    //             $outwardValueGrandTotal = 0;

    //             $groupName = 0;
    //             $productName = 0;

    //             $closingQtys = array();
    //             $closingRates = array();
    //             $closingValues = array();
        
    //             for($i=0; $i<count($result); $i++){
    //                 if(($productsInArray[$l] == $result[$i]['id']) && ($groupIds[$j] == $result[$i]['stock_group'])){
    //                     $groupName = $result[$i]['stock_group_name'];
    //                     $productName = $result[$i]['product_name'];
    //                     if(strtotime($result[$i]['tran_date']) < strtotime($fromDate)){
    //                         if($result[$i]['type'] === 'inward'){
    //                             $closingQty = $openingQty += $result[$i]['qty'];
    //                             $closingValue = $openingValue += $result[$i]['amount'];
    //                             $closingRate = $openingRate = $openingValue/$openingQty;
    //                         }
    //                         if($result[$i]['type'] === 'outward'){
    //                             $closingQty = $openingQty -= $result[$i]['qty'];
    //                             $outwardValue = $closingRate*$result[$i]['qty'];
    //                             $closingValue = $openingValue -= $outwardValue;
    //                             $closingRate = $openingRate = $openingValue/$openingQty;
    //                         }
    //                     }
    //                     elseif((strtotime($result[$i]['tran_date']) >= strtotime($fromDate)) && (strtotime($result[$i]['tran_date']) <= strtotime($toDate))){
    //                         if($result[$i]['type'] == 'inward'){
    //                             $closingQty += $result[$i]['qty'];
    //                             $closingValue += $result[$i]['amount'];
    //                             $closingRate = $closingValue/$closingQty;

    //                             $inwardQtyGrandTotal += $result[$i]['qty'];
    //                             $inwardValueGrandTotal += $result[$i]['amount'];
    //                             $inwardRateGrandTotal = $inwardValueGrandTotal/$inwardQtyGrandTotal;

    //                             $closingQtys[$result[$i]['voucher_no']] = $closingQty;
    //                             $closingRates[$result[$i]['voucher_no']] = $closingRate;
    //                             $closingValues[$result[$i]['voucher_no']] = $closingValue;

                                
    //                         }
    //                         if($result[$i]['type'] == 'outward'){
    //                             $result[$i]['rate'] = $closingRate;
                                
    //                             $closingQty -= $result[$i]['qty'];
    //                             $outwardValue = $closingRate*$result[$i]['qty'];
    //                             $closingValue -= $outwardValue;
    //                             $closingRate = $closingValue/$closingQty;

    //                             $outwardQtyGrandTotal += $result[$i]['qty'];
    //                             $outwardValueGrandTotal += $outwardValue;
    //                             $outwardRateGrandTotal = $outwardValueGrandTotal/$outwardQtyGrandTotal;

    //                             $closingQtys[$result[$i]['voucher_no']] = $closingQty;
    //                             $closingRates[$result[$i]['voucher_no']] = $closingRate;
    //                             $closingValues[$result[$i]['voucher_no']] = $closingValue;
    //                         }
    //                     }
    //                 }   
                         
    //             }

    //             $stocks[$j][$l]['group_name'] = $groupName;
    //             $stocks[$j][$l]['product_name'] = $productName;

    //             $totalOpeningQty += $stocks[$j][$l]['opening_qty'] = $openingQty;
    //             $stocks[$j][$l]['opening_rate'] = $openingRate;
    //             $totalOpeningValue += $stocks[$j][$l]['opening_value'] = $openingValue;

    //             $totalClosingQty += $stocks[$j][$l]['closing_qty'] = $closingQty;
    //             $stocks[$j][$l]['closing_rate'] = $closingRate;
    //             $totalClosingValue += $stocks[$j][$l]['closing_value'] = $closingValue;

    //             $totalInwardQty += $stocks[$j][$l]['inward_qty'] = $inwardQtyGrandTotal;
    //             $stocks[$j][$l]['inward_rate'] = $inwardRateGrandTotal;
    //             $totalInwardValue += $stocks[$j][$l]['inward_value'] = $inwardValueGrandTotal;

    //             $totalOutwardQty += $stocks[$j][$l]['outward_qty'] = $outwardQtyGrandTotal;
    //             $stocks[$j][$l]['outward_rate'] = $outwardRateGrandTotal;
    //             $totalOutwardValue += $stocks[$j][$l]['outward_value'] = $outwardValueGrandTotal; 
    //         }
    //     }
    //     $value = array_sum(array_column($stocks,'opening_qty'));
        
    //     if ($request->type == "print") {
    //         $pdf = App::make('dompdf.wrapper');
    //         $pdf->getDomPDF()->set_option("enable_php", true);
    //         $user = Auth::user();
    //         $pdf->loadView('inventory::reports.stock-summary-reports-pdf', compact('user', 'institute', 'fromDate', 'toDate', 'stocks', 'value', 'totalOpeningQty', 'totalOpeningValue', 'totalInwardQty', 'totalInwardValue', 'totalOutwardQty', 'totalOutwardValue', 'totalClosingQty', 'totalClosingValue'))->setPaper('a2', 'landscape');
    //         return $pdf->stream();
    //     }
    //     else{
    //         return view('inventory::reports.stock-summary-reports-table', compact('fromDate', 'toDate', 'stocks', 'value', 'totalOpeningQty', 'totalOpeningValue', 'totalInwardQty', 'totalInwardValue', 'totalOutwardQty', 'totalOutwardValue', 'totalClosingQty', 'totalClosingValue'))->render();
    //     }
    // }
    
}

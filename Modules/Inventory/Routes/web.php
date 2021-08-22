<?php

//Route::prefix('inventory')->group(function() {
Route::group(['middleware' => ['auth'], 'prefix' => 'inventory'], function () {

    Route::get('/', 'InventoryController@index');

    // Batch
    Route::get('/batch-grid', 'InventoryController@batchGrid');

    // Stock
    Route::get('/stock-group-grid', 'InventoryController@stockGroupGrid');  
    Route::get('/add/stock-group', 'InventoryController@addNewStockGroup');  
    Route::get('/edit/stock-group/{id}', 'InventoryController@editStockGroup');
    Route::post('/store/stock-group', 'InventoryController@storeNewStockGroup');
    Route::post('/update/stock-group/{id}', 'InventoryController@updateNewStockGroup');
    Route::get('/delete/stock-group/{id}', 'InventoryController@deleteNewStockGroup');
    Route::get('/stock-category', 'InventoryController@stockCategory');
    Route::get('/add/stock-category', 'InventoryController@addNewStockCategory');  
    Route::post('/store/stock-category', 'InventoryController@storeStockCategory');
    Route::get('/edit/stock-category/{id}', 'InventoryController@editStockCategory');
    Route::post('/update/stock-category/{id}', 'InventoryController@updateStockCategory');
    Route::get('/delete/stock-category/{id}', 'InventoryController@deleteStockCategory');
    Route::get('/unit-of-measurement', 'InventoryController@unitOfMeasurement');
    Route::post('/store/unit-of-measurement', 'InventoryController@storeUnitOfMeasurement');
    Route::post('/update/unit-of-measurement/{id}', 'InventoryController@updateUnitOfMeasurement');
    Route::get('/edit/unit-of-measurement/{id}', 'InventoryController@editUnitOfMeasurement');
    Route::get('/delete/unit-of-measurement/{id}', 'InventoryController@deleteUnitOfMeasurement');
    Route::get('/add/unit-of-measurement', 'InventoryController@addNewUnitOfMeasurement');
    // Stock List
    Route::get('/stock-list', 'InventoryController@stockList');
    Route::get('/add/stock-product', 'InventoryController@addStockProduct');
    Route::get('/show/history/stock-product/{id}', 'InventoryController@showHistoryProduct');
    Route::get('/edit/inventory/stock-product/{id}', 'InventoryController@editProduct');
    Route::post('/update/stock-product/{id}', 'InventoryController@updateStockItem');
    Route::post('/store/stock-product', 'InventoryController@storeStockProduct');
    // stock item serial
    Route::get('stock-item-serial', 'StockItemSerialNumberController@page');
    Route::get('stock-item-serial-generate', 'StockItemSerialNumberController@stockItemSerialGenerate');
    Route::resource('stock-item-serial-data', 'StockItemSerialNumberController');
   

    // Voucher Config
    Route::get('/voucher-config-list', 'InventoryController@voucherConfigList');
    Route::get('/add/voucher-config', 'InventoryController@addVoucherConfig');
    Route::get('/edit/voucher/{id}', 'InventoryController@editVoucherConfig');
    Route::post('/update/voucher/{id}', 'InventoryController@updateVoucherConfig');
    Route::post('/store/voucher-config', 'InventoryController@storeVoucherConfig');

    // Store
    Route::get('/store', 'InventoryController@storeList');  
    Route::get('/add-new-store', 'InventoryController@addNewStore');  
    Route::post('/store-new-store', 'InventoryController@storeNewStore');
    Route::get('/edit-store/{id}', 'InventoryController@editStore');
    Route::post('/update-store/{id}', 'InventoryController@updateStore');

    // New Requisition
    Route::get('new-requisition/apporved/{id}', 'NewRequisitionController@apporved');
    Route::post('new-requisition/apporved-action', 'NewRequisitionController@apporvedAction');
    Route::get('new-requisition', 'NewRequisitionController@page');
    Route::resource('new-requisition-data', 'NewRequisitionController');

    // Issue Inventory 
    Route::get('issue-inventory', 'IssueInventoryController@page');
    Route::get('issue-reference-list', 'IssueInventoryController@issueReferenceList');
    Route::post('issue-inventory-approval/{id}', 'IssueInventoryController@voucherApproval');
    Route::resource('issue-inventory-data', 'IssueInventoryController');

   // Route::resource('issue-inventory', 'IssueInventoryController');

    // Store Transfer Requisition
    Route::resource('store-transfer-requisition', 'StoreTransferRequisitionController');

    // Store Transfer 
    Route::resource('store-transfer', 'StoreTransferController');

    // Purchase  part start here
    // vendor
    Route::get('vendor', 'VendorController@page');
    Route::get('vendor-create-form-data', 'VendorController@vendorCreateData');
    Route::get('vendor-edit-form-data/{id}', 'VendorController@vendorEditData');
    Route::resource('vendor-data', 'VendorController');
    // Purchase Requisition
    Route::get('purchase-requisition', 'PurchaseRequisitionController@page');
    Route::post('purchase-requisition-approval/{id}', 'PurchaseRequisitionController@voucherApproval');
    Route::resource('purchase-requisition-data', 'PurchaseRequisitionController');

    // Comparative Statement
    Route::get('comparative-statement', 'ComparativeStatementController@page');
    Route::get('comparative-statement-create-form-data', 'ComparativeStatementController@comparativeStatementCreateData');
    Route::get('cs-reference-list', 'ComparativeStatementController@csReferenceList');
    Route::post('comparative-statement-approval/{id}', 'ComparativeStatementController@voucherApproval');
    Route::post('generate-cs', 'ComparativeStatementController@generateCS');
    Route::resource('comparative-statement-data', 'ComparativeStatementController');

    
    // Purchase Order
    Route::get('purchase-order', 'PurchaseOrderController@page');
    Route::get('purchase-order-voucher-no', 'PurchaseOrderController@getPurchaseVoucherNo');
    Route::get('purchase-order-reference-list', 'PurchaseOrderController@purchaseReferenceList');
    Route::post('purchase-order-approval/{id}', 'PurchaseOrderController@voucherApproval');
    Route::post('purchase-order-price-catalog-check', 'PurchaseOrderController@purchaseOrderPriceCatalogCheck');
    Route::resource('purchase-order-data', 'PurchaseOrderController');
    // Purchase order receive
    Route::get('purchase-receive', 'PurchaseReceiveController@page');
    Route::get('purchase-receive-reference-list', 'PurchaseReceiveController@purchaseReceiveReferenceList');
    Route::post('purchase-receive-serial-data', 'PurchaseReceiveController@purchaseReceiveSerialDetails');
    Route::post('purchase-receive-approval/{id}', 'PurchaseReceiveController@voucherApproval');
    Route::resource('purchase-receive-data', 'PurchaseReceiveController');
    // Purchase return
    Route::resource('purchase-return', 'PurchaseReturnController');

    // Sales part start here
    // customer
    Route::get('customer', 'CustomerController@page');
    Route::get('customer-create-form-data', 'CustomerController@customerCreateData');
    Route::get('customer-edit-form-data/{id}', 'CustomerController@customerEditData');
    Route::resource('customer-data', 'CustomerController');

    // sales 
    Route::resource('sales-order', 'SalesOrderController');
    // sales challan
    Route::resource('sales-challan', 'SalesChallanController');

    // Stock in
    Route::get('stock-in', 'SotckInController@page');
    Route::get('store-wise-item/{store_id}', 'SotckInController@storeWiseItem');
    Route::post('stock-in-approval/{id}', 'SotckInController@voucherApproval');
    Route::resource('stock-in-data', 'SotckInController');

    // Stock out
    Route::get('stock-out', 'SotckOutController@page');
    Route::post('stock-out-approval/{id}', 'SotckOutController@voucherApproval');
    Route::resource('stock-out-data', 'SotckOutController');

    // Price catalogue
    Route::get('price-catalogue', 'PriceCatalogueController@page');
    Route::get('price-catalogue/label-wise-details/{catelogue_id}', 'PriceCatalogueController@labelWiseDetails');
    Route::post('price-catalogue-approval/{id}', 'PriceCatalogueController@voucherApproval');
    Route::resource('price-catalogue-data', 'PriceCatalogueController');




});

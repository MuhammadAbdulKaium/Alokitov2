<?php

//Route::prefix('inventory')->group(function() {
Route::group(['middleware' => ['auth', 'cadet-user-permission'], 'prefix' => 'inventory'], function () {

    Route::get('/', 'InventoryController@index');

    // Batch
    Route::get('/batch-grid', 'InventoryController@batchGrid');

    // Stock
    Route::get('/stock-group-grid', 'InventoryController@stockGroupGrid'); 
    Route::get('/add/stock-group', 'InventoryController@addNewStockGroup');  
    Route::get('/edit/stock-group/{id}', ['access'=>['inventory/stock-group.edit'], 'uses'=>'InventoryController@editStockGroup']);
    Route::post('/store/stock-group', ['access'=>['inventory/add/stock-group'], 'uses'=>'InventoryController@storeNewStockGroup']);
    Route::post('/update/stock-group/{id}', ['access'=>['inventory/stock-group.edit'], 'uses'=>'InventoryController@updateNewStockGroup']);
    Route::get('/delete/stock-group/{id}', ['access'=>['inventory/stock-group.delete'], 'uses'=>'InventoryController@deleteNewStockGroup']);
    Route::get('/stock-category', 'InventoryController@stockCategory');
    Route::get('/add/stock-category', 'InventoryController@addNewStockCategory');  
    Route::post('/store/stock-category', ['access'=>['inventory/add/stock-category'], 'uses'=>'InventoryController@storeStockCategory']);
    Route::get('/edit/stock-category/{id}', ['access'=>['inventory/stock-category.edit'], 'uses'=>'InventoryController@editStockCategory']);
    Route::post('/update/stock-category/{id}', ['access'=>['inventory/stock-category.edit'], 'uses'=>'InventoryController@updateStockCategory']);
    Route::get('/delete/stock-category/{id}', ['access'=>['inventory/stock-category.delete'], 'uses'=>'InventoryController@deleteStockCategory']);
    Route::get('/unit-of-measurement', 'InventoryController@unitOfMeasurement');
    Route::post('/store/unit-of-measurement', ['access'=>['inventory/add/unit-of-measurement'], 'uses'=>'InventoryController@storeUnitOfMeasurement']);
    Route::post('/update/unit-of-measurement/{id}',['access'=>['inventory/unit-of-measurement.edit'], 'uses'=>'InventoryController@updateUnitOfMeasurement']);
    Route::get('/edit/unit-of-measurement/{id}', ['access'=>['inventory/unit-of-measurement.edit'], 'uses'=>'InventoryController@editUnitOfMeasurement']);
    Route::get('/delete/unit-of-measurement/{id}', ['access'=>['inventory/unit-of-measurement.delete'], 'uses'=>'InventoryController@deleteUnitOfMeasurement']);
    Route::get('/add/unit-of-measurement', 'InventoryController@addNewUnitOfMeasurement');
    // Stock List
    Route::get('/stock-list', 'InventoryController@stockList');
    Route::get('/stock-list-data', ['access'=>['inventory/stock-list'], 'uses'=>'InventoryController@stockListData']);
    Route::get('/add/stock-product', 'InventoryController@addStockProduct');
    Route::get('/show/history/stock-product/{id}',['access'=>['inventory/stock-product.show-history'], 'uses'=>'InventoryController@showHistoryProduct']);
    Route::get('/edit/inventory/stock-product/{id}', ['access'=>['inventory/stock-product.edit'], 'uses'=>'InventoryController@editProduct']);
    Route::post('/update/stock-product/{id}', ['access'=>['inventory/stock-product.edit'], 'uses'=>'InventoryController@updateStockItem']);
    Route::post('/store/stock-product', ['access'=>['inventory/add/stock-product'], 'uses'=>'InventoryController@storeStockProduct']);
    // stock item serial
    Route::get('stock-item-serial', 'StockItemSerialNumberController@page');
    Route::get('stock-item-serial-generate', ['access'=>'all', 'uses'=>'StockItemSerialNumberController@stockItemSerialGenerate']);
    Route::get('stock-item-serial-data', ['access'=>['inventory/stock-item-serial'], 'uses'=>'StockItemSerialNumberController@index']);
    Route::post('stock-item-serial-data', 'StockItemSerialNumberController@store');
    Route::get('stock-item-serial-data/{id}', ['access'=>['inventory/stock-item-serial.show'], 'uses'=>'StockItemSerialNumberController@show']);

    // stock master excel import
    Route::get('stock-master-excel-import', 'StockMasterExcelImportController@page');
    Route::get('stock-master-excel-import-data', 'StockMasterExcelImportController@index');
    Route::post('stock-master-excel-import-data/upload-stock-excel', 'StockMasterExcelImportController@uploadStockExcel');
    Route::post('stock-master-excel-import-data', 'StockMasterExcelImportController@store');
   

    // Voucher Config
    Route::get('/voucher-config-list', 'InventoryController@voucherConfigList');
    Route::get('/add/voucher-config', 'InventoryController@addVoucherConfig');
    Route::get('/edit/voucher/{id}', ['access'=>['inventory/voucher.edit'], 'uses'=>'InventoryController@editVoucherConfig']);
    Route::post('/update/voucher/{id}', ['access'=>['inventory/voucher.edit'], 'uses'=>'InventoryController@updateVoucherConfig']);
    Route::post('/store/voucher-config', ['access'=>['inventory/add/voucher-config'], 'uses'=>'InventoryController@storeVoucherConfig']);

    // Store
    Route::get('/store', 'InventoryController@storeList');  
    Route::get('/add-new-store', 'InventoryController@addNewStore');  
    Route::post('/store-new-store', ['access'=>['inventory/add-new-store'], 'uses'=>'InventoryController@storeNewStore']);
    Route::get('/edit-store/{id}', ['access'=>['inventory/store.edit'], 'uses'=>'InventoryController@editStore']);
    Route::post('/update-store/{id}', ['access'=>['inventory/store.edit'], 'uses'=>'InventoryController@updateStore']);

    // New Requisition
    Route::get('new-requisition/apporved/{id}',['access'=>['inventory/new-requisition.approved'], 'uses'=>'NewRequisitionController@apporved']);
    Route::post('new-requisition/apporved-action', ['access'=>['inventory/new-requisition.approved'], 'uses'=>'NewRequisitionController@apporvedAction']);
    Route::get('new-requisition', 'NewRequisitionController@page');
    Route::get('new-requisition-data', ['access'=>['inventory/new-requisition'], 'uses'=>'NewRequisitionController@index']);
    Route::get('new-requisition-data/create', 'NewRequisitionController@create');
    Route::get('new-requisition-data/{id}/edit', ['access'=>['inventory/new-requisition.edit'], 'uses'=>'NewRequisitionController@edit']);
    Route::post('new-requisition-data', ['access'=>['inventory/new-requisition-data/create','inventory/new-requisition.edit'], 'uses'=>'NewRequisitionController@store']);
    Route::get('new-requisition-data/{id}', ['access'=>['inventory/new-requisition.show'], 'uses'=>'NewRequisitionController@show']);
    Route::delete('new-requisition-data/{id}', ['access'=>['inventory/new-requisition.delete'], 'uses'=>'NewRequisitionController@destroy']);

    // Issue Inventory 
    Route::get('issue-inventory', 'IssueInventoryController@page');
    Route::get('issue-reference-list', ['access'=>['inventory/issue-inventory-data/create','inventory/issue-inventory.edit'], 'uses'=>'IssueInventoryController@issueReferenceList']);
    Route::post('issue-inventory-approval/{id}', ['access'=>['inventory/issue-inventory.approval'], 'uses'=>'IssueInventoryController@voucherApproval']);
    Route::get('issue-inventory-data', ['access'=>['inventory/issue-inventory'], 'uses'=>'IssueInventoryController@index']);
    Route::get('issue-inventory-data/create', 'IssueInventoryController@create');
    Route::get('issue-inventory-data/{id}/edit', ['access'=>['inventory/issue-inventory.edit'], 'uses'=>'IssueInventoryController@edit']);
    Route::post('issue-inventory-data', ['access'=>['inventory/issue-inventory-data/create','inventory/issue-inventory.edit'], 'uses'=>'IssueInventoryController@store']);
    Route::get('issue-inventory-data/{id}', ['access'=>['inventory/issue-inventory.show'], 'uses'=>'IssueInventoryController@show']);
    Route::delete('issue-inventory-data/{id}', ['access'=>['inventory/issue-inventory.delete'], 'uses'=>'IssueInventoryController@destroy']);


    // Store Transfer Requisition
    Route::resource('store-transfer-requisition', 'StoreTransferRequisitionController');

    // Store Transfer 
    Route::resource('store-transfer', 'StoreTransferController');

    // Purchase  part start here
    // vendor
    Route::get('vendor', 'VendorController@page');
    Route::get('vendor-create-form-data', ['access'=>['inventory/vendor-data/create'], 'uses'=>'VendorController@vendorCreateData']);
    Route::get('vendor-edit-form-data/{id}', ['access'=>['inventory/vendor.edit'], 'uses'=>'VendorController@vendorEditData']);
    Route::get('vendor-data', ['access'=>['inventory/vendor'], 'uses'=>'VendorController@index']);
    Route::get('vendor-data/create', 'VendorController@create');
    Route::get('vendor-data/{id}/edit', ['access'=>['inventory/vendor.edit'], 'uses'=>'VendorController@edit']);
    Route::post('vendor-data', ['access'=>['inventory/vendor-data/create', 'inventory/vendor.edit'], 'uses'=>'VendorController@store']);
    Route::get('vendor-data/{id}', ['access'=>['inventory/vendor.show'], 'uses'=>'VendorController@show']);
    Route::delete('vendor-data/{id}', ['access'=>['inventory/vendor.delete'], 'uses'=>'VendorController@destroy']);
    
    Route::get('purchase-requisition', 'PurchaseRequisitionController@page');
    Route::post('purchase-requisition-approval/{id}', ['access'=>['inventory/purchase-requisition.approval'], 'uses'=>'PurchaseRequisitionController@voucherApproval']);

    Route::get('purchase-requisition-data', ['access'=>['inventory/purchase-requisition'], 'uses'=>'PurchaseRequisitionController@index']);
    Route::get('purchase-requisition-data/create', 'PurchaseRequisitionController@create');
    Route::get('purchase-requisition-data/{id}/edit', ['access'=>['inventory/purchase-requisition.edit'], 'uses'=>'PurchaseRequisitionController@edit']);
    Route::post('purchase-requisition-data', ['access'=>['inventory/purchase-requisition-data/create','inventory/purchase-requisition.edit'], 'uses'=>'PurchaseRequisitionController@store']);
    Route::get('purchase-requisition-data/{id}', ['access'=>['inventory/purchase-requisition.show'], 'uses'=>'PurchaseRequisitionController@show']);
    Route::delete('purchase-requisition-data/{id}', ['access'=>['inventory/purchase-requisition.delete'], 'uses'=>'PurchaseRequisitionController@destroy']);
    Route::get('purchase/requisition/print/{id}','PurchaseRequisitionController@print');
    // Comparative Statement
    Route::get('comparative-statement', 'ComparativeStatementController@page');
    Route::get('comparative-statement-create-form-data', ['access'=>['inventory/comparative-statement-data/create'], 'uses'=>'ComparativeStatementController@comparativeStatementCreateData']);
    Route::get('cs-reference-list', ['access'=>['inventory/comparative-statement-data/create'], 'uses'=>'ComparativeStatementController@csReferenceList']);
    Route::post('comparative-statement-approval/{id}', ['access'=>['inventory/comparative-statement.approval'], 'uses'=>'ComparativeStatementController@voucherApproval']);
    Route::post('generate-cs', ['access'=>['inventory/comparative-statement-data/create'], 'uses'=>'ComparativeStatementController@generateCS']);


    Route::get('comparative-statement-data', ['access'=>['inventory/comparative-statement'], 'uses'=>'ComparativeStatementController@index']);
    Route::get('comparative-statement-data/create', 'ComparativeStatementController@create');
    
    Route::post('comparative-statement-data', ['access'=>['inventory/comparative-statement-data/create'], 'uses'=>'ComparativeStatementController@store']);
    Route::get('comparative-statement-data/{id}', ['access'=>['inventory/comparative-statement.show'], 'uses'=>'ComparativeStatementController@show']);
    Route::get('comparative-statement-history/{id}', ['access'=>['inventory/comparative-statement.show'], 'uses'=>'ComparativeStatementController@csHistory']);
    Route::delete('comparative-statement-data/{id}', ['access'=>['inventory/comparative-statement.delete'], 'uses'=>'ComparativeStatementController@destroy']);
    Route::get('purchase/comparative-statement/print/{id}','ComparativeStatementController@print');

    
    // Purchase Order
    Route::get('purchase-order', 'PurchaseOrderController@page');
    Route::get('purchase-order-voucher-no', ['access'=>['inventory/purchase-order-data/create','inventory/purchase-order.edit'], 'uses'=>'PurchaseOrderController@getPurchaseVoucherNo']);
    Route::get('purchase-order-reference-list', ['access'=>['inventory/purchase-order-data/create','inventory/purchase-order.edit'], 'uses'=>'PurchaseOrderController@purchaseReferenceList']);
    Route::post('purchase-order-approval/{id}', ['access'=>['inventory/purchase-order.approval'], 'uses'=>'PurchaseOrderController@voucherApproval']);
    Route::post('purchase-order-price-catalog-check', ['access'=>'all', 'uses'=>'PurchaseOrderController@purchaseOrderPriceCatalogCheck']);

    Route::get('purchase-order-data', ['access'=>['inventory/purchase-order'], 'uses'=>'PurchaseOrderController@index']);
    Route::get('purchase-order-data/create', 'PurchaseOrderController@create');
    Route::get('purchase-order-data/{id}/edit', ['access'=>['inventory/purchase-order.edit'], 'uses'=>'PurchaseOrderController@edit']);
    Route::post('purchase-order-data', ['access'=>['inventory/purchase-order-data/create','inventory/purchase-order.edit'], 'uses'=>'PurchaseOrderController@store']);
    Route::get('purchase-order-data/{id}', ['access'=>['inventory/purchase-order.show'], 'uses'=>'PurchaseOrderController@show']);
    Route::delete('purchase-order-data/{id}', ['access'=>['inventory/purchase-order.delete'], 'uses'=>'PurchaseOrderController@destroy']);
    // Purchase Order Print
    Route::get('purchase/order/print/{id}','PurchaseOrderController@print');
    // Purchase order receive
    Route::get('purchase-receive', 'PurchaseReceiveController@page');
    Route::get('purchase-receive-reference-list', ['access'=>['inventory/purchase-receive-data/create','inventory/purchase-receive.edit'], 'uses'=>'PurchaseReceiveController@purchaseReceiveReferenceList']);
    Route::post('purchase-receive-serial-data', ['access'=>'all', 'uses'=>'PurchaseReceiveController@purchaseReceiveSerialDetails']);
    Route::post('purchase-receive-approval/{id}', ['access'=>['inventory/purchase-receive.approval'], 'uses'=>'PurchaseReceiveController@voucherApproval']);

    Route::get('purchase-receive-data', ['access'=>['inventory/purchase-receive'], 'uses'=>'PurchaseReceiveController@index']);
    Route::get('purchase-receive-data/create', 'PurchaseReceiveController@create');
    Route::get('purchase-receive-data/{id}/edit', ['access'=>['inventory/purchase-receive.edit'], 'uses'=>'PurchaseReceiveController@edit']);
    Route::post('purchase-receive-data', ['access'=>['inventory/purchase-receive-data/create','inventory/purchase-receive.edit'], 'uses'=>'PurchaseReceiveController@store']);
    Route::get('purchase-receive-data/{id}', ['access'=>['inventory/purchase-receive.show'], 'uses'=>'PurchaseReceiveController@show']);
    Route::delete('purchase-receive-data/{id}', ['access'=>['inventory/purchase-receive.delete'], 'uses'=>'PurchaseReceiveController@destroy']);
    Route::get('purchase/receive/print/{id}','PurchaseReceiveController@print');
    
    // Purchase return
    Route::resource('purchase-return', 'PurchaseReturnController');

    // Purchase Invoice start 

    Route::get('purchase-invoice', 'PurchaseInvoiceController@page');
    Route::get('purchase-invoice-reference-list', ['access'=>['inventory/purchase-invoice-data/create','inventory/purchase-invoice.edit'], 'uses'=>'PurchaseInvoiceController@purchaseReceiveReferenceList']);
    Route::get('purchase-invoice-data', ['access'=>['inventory/purchase-invoice'], 'uses'=>'PurchaseInvoiceController@index']);
    Route::get('purchase-invoice-data/create', 'PurchaseInvoiceController@create');
    Route::get('purchase-invoice-reference-list', ['access'=>['inventory/purchase-invoice-data/create','inventory/purchase-invoice.edit'], 'uses'=>'PurchaseInvoiceController@purchaseInvoiceReferenceList']);
    Route::post('purchase-invoice-data', ['access'=>['inventory/purchase-invoice-data/create','inventory/purchase-invoice.edit'], 'uses'=>'PurchaseInvoiceController@store']);
    Route::get('purchase-invoice-data/{id}/edit', ['access'=>['inventory/purchase-invoice.edit'], 'uses'=>'PurchaseInvoiceController@edit']);
    Route::delete('purchase-invoice-data/{id}', ['access'=>['inventory/purchase-invoice.delete'], 'uses'=>'PurchaseInvoiceController@destroy']);
     Route::get('purchase-invoice-data/{id}', ['access'=>['inventory/purchase-invoice.show'], 'uses'=>'PurchaseInvoiceController@show']);
     Route::post('purchase-invoice-approval/{id}', ['access'=>['inventory/purchase-invoice.approval'], 'uses'=>'PurchaseInvoiceController@voucherApproval']);
     Route::get('purchase/invoice/print/{id}','PurchaseInvoiceController@print');

    // Purchase Invoice end


    // Sales part start here
    // customer
    Route::get('customer', 'CustomerController@page');
    Route::get('customer-create-form-data', ['access'=>['inventory/customer-data/create'], 'uses'=>'CustomerController@customerCreateData']);
    Route::get('customer-edit-form-data/{id}', ['access'=>['inventory/customer.edit'], 'uses'=>'CustomerController@customerEditData']);
    Route::get('customer-data', ['access'=>['inventory/customer'], 'uses'=>'CustomerController@index']);
    Route::get('customer-data/create', 'CustomerController@create');
    Route::get('customer-data/{id}/edit', ['access'=>['inventory/customer.edit'], 'uses'=>'CustomerController@edit']);
    Route::post('customer-data', ['access'=>['inventory/customer-data/create'], 'uses'=>'CustomerController@store']);
    Route::get('customer-data/{id}', ['access'=>['inventory/customer.show'], 'uses'=>'CustomerController@show']);
    Route::delete('customer-data/{id}', ['access'=>['inventory/customer.delete'], 'uses'=>'CustomerController@destroy']);
  
    // sales 
    Route::resource('sales-order', 'SalesOrderController');
    // sales challan
    Route::resource('sales-challan', 'SalesChallanController');

    // Stock in
    Route::get('stock-in', 'SotckInController@page');
    Route::get('store-wise-item/{store_id}', ['access'=>'all', 'uses'=>'SotckInController@storeWiseItem']);
    Route::post('stock-in-approval/{id}', ['access'=>['inventory/stock-in.approval'], 'uses'=>'SotckInController@voucherApproval']);
    Route::get('stock-in-data', ['access'=>['inventory/stock-in'], 'uses'=>'SotckInController@index']);
    Route::get('stock-in-data/create', 'SotckInController@create');
    Route::get('stock-in-data/{id}/edit', ['access'=>['inventory/stock-in.edit'], 'uses'=>'SotckInController@edit']);
    Route::post('stock-in-data', ['access'=>['inventory/stock-in-data/create','inventory/stock-in.edit'], 'uses'=>'SotckInController@store']);
    Route::get('stock-in-data/{id}', ['access'=>['inventory/stock-in.show'], 'uses'=>'SotckInController@show']);
    Route::delete('stock-in-data/{id}', ['access'=>['inventory/stock-in.delete'], 'uses'=>'SotckInController@destroy']);

    //Route::resource('stock-in-data', 'SotckInController');

    // Stock out
    Route::get('stock-out', 'SotckOutController@page');
    Route::post('stock-out-approval/{id}', ['access'=>['inventory/stock-in.approval'], 'uses'=>'SotckOutController@voucherApproval']);

    Route::get('stock-out-data', ['access'=>['inventory/stock-out'], 'uses'=>'SotckOutController@index']);
    Route::get('stock-out-data/create', 'SotckOutController@create');
    Route::get('stock-out-data/{id}/edit', ['access'=>['inventory/stock-out.edit'], 'uses'=>'SotckOutController@edit']);
    Route::post('stock-out-data', ['access'=>['inventory/stock-out-data/create','inventory/stock-out.edit'], 'uses'=>'SotckOutController@store']);
    Route::get('stock-out-data/{id}', ['access'=>['inventory/stock-out.show'], 'uses'=>'SotckOutController@show']);
    Route::delete('stock-out-data/{id}', ['access'=>['inventory/stock-out.delete'], 'uses'=>'SotckOutController@destroy']);


    // Price catalogue
    Route::get('price-catalogue', 'PriceCatalogueController@page');
    Route::get('price-catalogue/label-wise-details/{catelogue_id}', ['access'=>'all', 'uses'=>'PriceCatalogueController@labelWiseDetails']);
    Route::post('price-catalogue-approval/{id}', ['access'=>['inventory/price-catalogue.voucher-approval'], 'uses'=>'PriceCatalogueController@voucherApproval']);

    Route::get('price-catalogue-data', ['access'=>['inventory/price-catalogue'], 'uses'=>'PriceCatalogueController@index']);
    Route::get('price-catalogue-data/create', 'PriceCatalogueController@create');
    Route::post('price-catalogue-data', ['access'=>['inventory/price-catalogue-data/create','inventory/price-catalogue.edit'], 'uses'=>'PriceCatalogueController@store']);
    Route::get('price-catalogue-data/{id}/edit', ['access'=>['inventory/price-catalogue.edit'], 'uses'=>'PriceCatalogueController@edit']);
    Route::get('price-catalogue-data/{id}', ['access'=>['inventory/price-catalogue.show'], 'uses'=>'PriceCatalogueController@show']);
    Route::delete('price-catalogue-data/{id}', ['access'=>['inventory/price-catalogue.delete'], 'uses'=>'PriceCatalogueController@destroy']);

    
    // Store Ledger
    Route::get('/store-ledger/reports', 'InventoryReportController@storeLedgerReport');
    Route::get('/store-ledger-report/search-product', 'InventoryReportController@storeSearchProduct');
    Route::get('/store-ledger-report/search-category', 'InventoryReportController@storeSearchCategory'); 
    Route::post('/store-ledger-report/item-report', 'InventoryReportController@searchItemLedgerReport');
   
        
    // Stock Summary
    Route::get('/stock-summary/reports', 'InventoryReportController@stockSummaryReport');
    Route::post('/stock-summary-report/stock-report', 'InventoryReportController@searchStockSummaryReport');

});

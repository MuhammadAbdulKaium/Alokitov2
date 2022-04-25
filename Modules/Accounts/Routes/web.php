<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'accounts'], function () {
    Route::get('/', 'AccountsController@index');

    // Chart of Account routes Start
    Route::get('/chart-of-accounts', 'ChartOfAccountController@index');
    Route::get('/chart-of-accounts/create-group', 'ChartOfAccountController@create');
    Route::get('/chart-of-accounts/create-ledger', 'ChartOfAccountController@createLedger');
    Route::post('/chart-of-accounts/store', 'ChartOfAccountController@store');
    Route::get('/chart-of-accounts/edit/{id}', 'ChartOfAccountController@edit');
    Route::post('/chart-of-accounts/update/{id}', 'ChartOfAccountController@update');
    Route::get('/chart-of-accounts/delete/{id}', 'ChartOfAccountController@destroy');
    Route::get('/chart-of-accounts-config', 'ChartOfAccountController@chartOfAccountsConfig');
    Route::post('/chart-of-accounts-config-update', 'ChartOfAccountController@chartOfAccountsConfigUpdate');
    // Chart of Account routes End


    // Fiscal year routes start
    Route::get('/fiscal-year', 'FiscalYearController@index');
    // Fiscal year routes start

    // Accounts voucher config start
    Route::get('/voucher-config-list', 'AccountsVoucherConfigController@index');
    Route::get('/voucher-config-list/create', 'AccountsVoucherConfigController@create');
    Route::get('/voucher-config-list/{id}/edit', 'AccountsVoucherConfigController@edit');
    Route::post('/voucher-config-list', 'AccountsVoucherConfigController@store');
    Route::post('/voucher-config-list/update/{id}', 'AccountsVoucherConfigController@update');
    // Accounts voucher config end

    // Accounts Configuration start
    Route::get('/accounts-configuration', 'AccountsConfigurationController@index');
    Route::get('/accounts-configuration/{label_name}/edit', 'AccountsConfigurationController@edit');
    Route::post('/accounts-configuration/update/{label_name}', 'AccountsConfigurationController@update');

    // Accounts Configuration end

    // Budget Allocation routes Start
    Route::get('/budget-allocation', 'BudgetAllocationController@index');
    Route::get('/budget-allocation/add-budget', 'BudgetAllocationController@create');
    Route::post('/budget-allocation/store-budget', 'BudgetAllocationController@store');
    Route::get('/budget-allocation/edit-budget/{id}', 'BudgetAllocationController@edit');
    Route::post('/budget-allocation/update-budget/{id}', 'BudgetAllocationController@update');
    Route::get('/budget-allocation/delete-budget/{id}', 'BudgetAllocationController@destroy');

    // Budget Allocation routes End


    // Payment Voucher Start

    Route::get('/payment-voucher', 'PaymentVoucherController@page');
    Route::get('/payment-voucher-data', 'PaymentVoucherController@index');
    Route::get('/payment-voucher-data/create', 'PaymentVoucherController@create');
    Route::post('/payment-voucher-data', 'PaymentVoucherController@store');
    Route::get('/payment-voucher-data/{id}/edit', 'PaymentVoucherController@edit');
    Route::post('/payment-voucher-approval/{id}', 'PaymentVoucherController@voucherApproval');
    Route::get('/payment-voucher-data/{id}', 'PaymentVoucherController@show');
    Route::delete('/payment-voucher-data/{id}', 'PaymentVoucherController@destroy');

    Route::get('/check-acc-voucher-no', 'PaymentVoucherController@checkVoucher');
    Route::get('/print/payment-voucher/{id}', 'PaymentVoucherController@print');
    // Payment Voucher End

    // Receive Voucher Start

    Route::get('/receive-voucher', 'ReceiveVoucherController@page');
    Route::get('/receive-voucher-data', 'ReceiveVoucherController@index');
    Route::get('/receive-voucher-data/create', 'ReceiveVoucherController@create');
    Route::post('/receive-voucher-data', 'ReceiveVoucherController@store');
    Route::get('/receive-voucher-data/{id}/edit', 'ReceiveVoucherController@edit');
    Route::post('/receive-voucher-approval/{id}', 'ReceiveVoucherController@voucherApproval');
    Route::get('/receive-voucher-data/{id}', 'ReceiveVoucherController@show');
    Route::delete('/receive-voucher-data/{id}', 'ReceiveVoucherController@destroy');
    Route::get('/print/receive-voucher/{id}', 'ReceiveVoucherController@print');
    // Receive Voucher End



    // Journal Voucher Start
    Route::get('/journal-voucher', 'JournalVoucherController@page');
    Route::get('/journal-voucher-data', 'JournalVoucherController@index');
    Route::get('/journal-voucher-data/create', 'JournalVoucherController@create');
    Route::post('/journal-voucher-data', 'JournalVoucherController@store');
    Route::get('/journal-voucher-data/{id}/edit', 'JournalVoucherController@edit');
    Route::get('/journal-voucher-data/{id}', 'JournalVoucherController@show');
    Route::post('/journal-voucher-approval/{id}', 'JournalVoucherController@voucherApproval');
    Route::delete('/journal-voucher-data/{id}', 'JournalVoucherController@destroy');
    Route::get('/print/journal-voucher/{id}', 'JournalVoucherController@print');

    // Journal Voucher End


    // Contra Voucher Start

    Route::get('/contra-voucher', 'ContraVoucherController@page');
    Route::get('/contra-voucher-data', 'ContraVoucherController@index');
    Route::get('/contra-voucher-data/create', 'ContraVoucherController@create');
    Route::post('/contra-voucher-data', 'ContraVoucherController@store');
    Route::get('/contra-voucher-data/{id}/edit', 'ContraVoucherController@edit');
    Route::post('/contra-voucher-approval/{id}', 'ContraVoucherController@voucherApproval');
    Route::get('/contra-voucher-data/{id}', 'ContraVoucherController@show');
    Route::delete('/contra-voucher-data/{id}', 'ContraVoucherController@destroy');
    Route::get('/print/contra-voucher/{id}', 'ContraVoucherController@print');

    // Contra Voucher End
    
    // SignatoryConfig Start
    Route::get('/signatory-config-data/{report_name}', 'SignatoryConfigController@page');
    Route::get('/signatory-confin-form', 'SignatoryConfigController@createForm');
    Route::get('/signatory-confin-getdesignation', 'SignatoryConfigController@getdesignation');
    Route::post('/signatory-confin-data/post', "SignatoryConfigController@insertSignatory");
    Route::get('/signatory-confin-data/delete/{id}', "SignatoryConfigController@deleteSignatory");
    // SignatoryConfig End

});

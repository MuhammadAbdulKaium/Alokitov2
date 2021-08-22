<?php

Route::group(['middleware' => 'web', 'prefix' => 'finance', 'namespace' => 'Modules\Finance\Http\Controllers'], function()
{

    Route::get('accounts', 'FinancialAccountController@index');
    Route::get('dashboard', 'FinancialAccountController@dashboard');
    Route::get('accounts/index', 'FinancialAccountController@chartOfAccount');

    Route::get('admin/create/account', 'FinancialAccountController@createAccount');
    Route::post('account/store', 'FinancialAccountController@store');
    Route::post('account/active', 'FinancialAccountController@accountActive');
    Route::get('account/show', 'ReportController@accountShowReport');

    Route::get('accounts/ledger/add', 'LedgerController@ledgerCreate');
    Route::post('accounts/ledger/store', 'LedgerController@ledgerStore');
    Route::get('ledger/edit/{id}', 'LedgerController@ledgerEdit');


    Route::get('/reports/trialbalance','ReportController@trialbalance');
    Route::get('/reports/balancesheet','ReportController@balanceSheet');
    Route::get('/reports/profitloss','ReportController@profitLoss');
    Route::get('/reports/test','ReportController@testTrialBlanceByDate');

    // entries add row all
    Route::get('/entries/addrow/all','EntriesController@entriesAddRowAll');
//    Route::get('/entries/addrow/all', function (){
//       return "welocme";
//    });






    // dashbaord here
    Route::get('/accounts/dashboard', 'FinancialAccountController@dashboard');

    /// account manage and create accoutn here
    Route::get('accounts/manage', 'FinancialAccountController@accountList');
    Route::get('accounts/create', 'FinancialAccountController@createAccount');
    Route::post('accounts/store', 'FinancialAccountController@store');

    //
    Route::get('/accounts/groups/add','GroupController@createGroup');
    Route::post('/accounts/groups/store','GroupController@storeGroup');
    Route::get('/group/edit/{id}','GroupController@editGorup');

    // accounts Setting

    Route::get('/accounts/account_settings/entrytypes','EntriesTypeController@entrytypeList');
    Route::get('/accounts/account_settings/addentrytype','EntriesTypeController@addEntryType');
    Route::post('/accounts/account_settings/storeentrytype','EntriesTypeController@storeEntryType');


// entries system
    Route::get('accounts/entries/index', 'EntriesController@entriesList');
    Route::get('accounts/entries/add/{entriesType}', 'EntriesController@addEntries');
    Route::get('accounts/ledgers/cl/{ledgerid}', 'EntriesController@cl');
    Route::get('accounts/entries/addrow/{rowid}', 'EntriesController@addrow');
    Route::post('accounts/entries/add', 'EntriesController@entriesStore');
    /// view a single entries
    Route::get('accounts/entries/view/{id}', 'EntriesController@viewEntries');
    Route::get('accounts/entries/print/{id}', 'EntriesController@viewEntriesPrint');
    Route::get('accounts/entries/edit/{label}/{id}', 'EntriesController@editntries');
    Route::post('accounts/entries/update/store', 'EntriesController@entriestUpdateStore');

    // report route
    Route::get('accounts/reports/balancesheet/{download?}/{format?}', 'ReportController@balancesheet');
    Route::get('accounts/reports/profitloss/{download?}/{format?}', 'ReportController@profitloss');
    Route::get('accounts/reports/trailbalance/{download?}/{format?}', 'ReportController@trialbalance');
    // ledger statement here
    Route::get('accounts/reports/ledgerstatement/{show?}/{ledger_id?}', 'ReportController@ledgerStatement');
    Route::post('accounts/reports/ledgerstatement/', 'ReportController@ledgerStatement');
    Route::get('accounts/reports/ledgerstatement/ledgerid/{id}', 'ReportController@ledgerStatement');

    /// ledger entriest
    Route::get('accounts/reports/ledgerentries/{show?}/{ledger_id?}', 'ReportController@ledgerEntries');
    Route::post('accounts/reports/ledgerentries/', 'ReportController@ledgerEntries');

    // Report downlaod pdf and excel
    Route::get('accounts/reports/export_ledgerstatement', 'ReportController@export_ledgerstatement');

    // ledger entries
    Route::get('accounts/reports/export_ledgerentries', 'ReportController@export_ledgerEntries');


















    Route::get('/', 'FinanceController@index');

    Route::get('/account/create', function (){
        return view('finance::pages.create-account');
    });



    Route::get('/reports/ledgerstatement', function (){
        return view('finance::pages.reports-ledgerstatement');
    });

    Route::get('/entrytypes', function (){
        return view('finance::pages.entrytypes');
    });

    Route::get('/entrytypes/add', function (){
        return view('finance::pages.entrytypes-add');
    });







});

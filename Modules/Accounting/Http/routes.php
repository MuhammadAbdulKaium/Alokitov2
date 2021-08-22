<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'accounting', 'namespace' => 'Modules\Accounting\Http\Controllers'], function()
{
    //home
    Route::get('/', 'AccHeadController@home');
    Route::get('/accdashboard', 'AccHeadController@dashboard');

    //acchead
    Route::get('/acchead', 'AccHeadController@index');
    Route::get('/acchead/add', 'AccHeadController@create');
    Route::post('/acchead', 'AccHeadController@store');
    Route::post('/acchead/edit', 'AccHeadController@edit');
    Route::post('/acchead/update', 'AccHeadController@update');
    Route::get('/acchead/delete/{id}', 'AccHeadController@delete');

    //accsubhead
    Route::get('/accsubhead', 'AccSubHeadController@index');
    Route::get('/accsubhead/add', 'AccSubHeadController@create');
    Route::post('/accsubhead', 'AccSubHeadController@store');
    Route::post('/accsubhead/edit', 'AccSubHeadController@edit');
    Route::post('/accsubhead/update', 'AccSubHeadController@update');
    Route::get('/accsubhead/delete/{id}', 'AccSubHeadController@delete');

    //accbank
    Route::get('/accbank', 'AccBankController@index');
    Route::get('/accbank/add', 'AccBankController@create');
    Route::post('/accbank', 'AccBankController@store');
    Route::post('/accbank/edit', 'AccBankController@edit');
    Route::post('/accbank/update', 'AccBankController@update');
    Route::get('/accbank/delete/{id}', 'AccBankController@delete');

    //accvouchertype
    Route::get('/accvouchertype', 'AccVoucherTypeController@index');
    Route::get('/accvouchertype/add', 'AccVoucherTypeController@create');
    Route::post('/accvouchertype', 'AccVoucherTypeController@store');
    Route::post('/accvouchertype/edit', 'AccVoucherTypeController@edit');
    Route::post('/accvouchertype/update', 'AccVoucherTypeController@update');
    Route::get('/accvouchertype/delete/{id}', 'AccVoucherTypeController@delete');

    //accvoucherentry
    Route::get('/accvoucherentry', 'AccVoucherEntryController@index');
    Route::get('/accvoucherentry/add', 'AccVoucherEntryController@create');
    Route::post('/accvoucherentry/vnextno', 'AccVoucherEntryController@voucherNextSerial');
    Route::post('/accvoucherentry', 'AccVoucherEntryController@store');
    Route::post('/accvoucherentry/approve','AccVoucherEntryController@approve');
    Route::post('/accvoucherentry/approved','AccVoucherEntryController@approveStore');

    //accreport
    Route::get('/accreport','AccReportController@index');
    Route::get('/accreport/accdailybook','AccReportController@dailyBook');
    Route::post('/accreport/accdailybook','AccReportController@dailyBookAjax');
    Route::get('/accreport/accledgerbook','AccReportController@ledgerBook');
    Route::post('/accreport/accledgerbook','AccReportController@ledgerBookAjax');
    Route::get('/accreport/accreceivepayment','AccReportController@receivePayment');
    Route::get('/accreport/acctrialbalance','AccReportController@trialBalance');
    Route::get('/accreport/acctrialbalance-test','AccReportController@trialBalanceTest');

    Route::get('/accreport/accbalancesheet','AccReportController@balanceSheet');
    Route::get('/accreport/accprofitloss','AccReportController@profitLoss');
    Route::get('/accreport/accprofitloss-test','AccReportController@profitLossTest');
    // excel export
    Route::get('/accreport/accdailybookexcel/{voucherType}/{fromDate}/{toDate}','AccReportController@dailybookexcel');
    Route::get('/accreport/accledgerbook/{ledgersList}/{fromDate}/{toDate}','AccReportController@ledgerBookexcel');
    Route::get('/accreport/accreceivepaymentexcel','AccReportController@receivePaymentexcel');
    Route::get('/accreport/acctrialbalanceexcel','AccReportController@trialBalanceexcel');
    Route::get('/accreport/accbalancesheetexcel','AccReportController@balanceSheetexcel');
    Route::get('/accreport/accprofitlossexcel','AccReportController@profitLossexcel');


    //accfyear
    Route::get('/accfyear','AccFYearController@index');
    Route::get('/accfyear/add', 'AccFYearController@create');
    Route::post('/accfyear', 'AccFYearController@store');
    Route::get('/accfyear/yearclosing', 'AccFYearController@yearClosing');
    Route::get('/accfyear/closeYear', 'AccFYearController@closeYear');

    //accFees collection
    Route::get('/accfeescollection','AccFeesController@index');
    Route::post('/accfeescollection', 'AccFeesController@store');

    Route::get('/accfeesitemcollection','AccFeesController@feesItems');
    Route::post('/accfeesitemcollection','AccFeesController@storeFeesCollection');

    Route::get('/accfeeslist','AccHeadController@accfeeslist');
});
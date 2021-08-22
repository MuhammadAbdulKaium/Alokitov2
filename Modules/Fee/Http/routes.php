<?php

Route::group(['middleware' => 'web', 'prefix' => 'fee', 'namespace' => 'Modules\Fee\Http\Controllers'], function()
{
    Route::get('/setting/{tab}', 'FeeController@feeSetting');

    // fee create route
    Route::get('/create/{tab}', 'FeeController@feeCreate');

    // fee assing tab
    Route::get('/feeassign/{tab}', 'FeeController@feeAssign');

    // fee collection
    Route::get('/collection/{tab}', 'FeeController@feeCollection');
    Route::get('/fine-collection/{tab}', 'FeeController@feeFineCollection');

    // fee report
    Route::get('/report/collection-amount/{tab}', 'FeeController@feeReportCollectionAmount');
    Route::get('/report/money-receipt/{tab}', 'FeeController@feeReportMoneyReceipt');
    Route::get('/report/due-amount/{tab}', 'FeeController@feeReportDueAmount');


    // fee head
    Route::post('/feehead/store', 'FeeHeadController@store');
    Route::get('/feehead/edit/{id}', 'FeeHeadController@feeHeadEdit');


    // fee sub head
    Route::post('/feesubhead/store', 'FeeSubheadController@store');
    Route::get('/feesubhead/edit/{id}', 'FeeSubheadController@feesubHeadEdit');
    Route::get('/feesubhead/list/', 'FeeSubheadController@getSubHeadByClassHead');
    Route::get('/head/class/subheadlist/', 'FeeSubheadController@getSubHeadByClassHead');



    Route::get('/subhead/class/amount/', 'FeeSubheadController@getSubheadClassAmount');

    // fee waiver
    Route::post('/waivertype/store', 'FeeWaiverTypeController@store');
    Route::get('/waivertype/edit/{id}', 'FeeWaiverTypeController@feewaiverTypeEdit');


    // fee fund here
    Route::post('/fund/store', 'FeeFundController@store');
    Route::get('/fund/edit/{id}', 'FeeFundController@feefundEdit');

    // fee create
    Route::post('/feeheadfund/store', 'FeeHeadFundSettingController@store');

    // get fund list by feehead_id
    Route::post('/feehead/fundlist', 'FeeHeadFundSettingController@headFundList');

    // fee assign sotre
    Route::post('/assign/store', 'FeeAssignController@feeAssignStore');
    Route::get('/fees_assign/delete/{id}', 'FeeAssignController@feeAssignDeleteWithAllInvoices');
    Route::get('/class/wise/student/list', 'FeeAssignController@classSectionWiseStudent');


    // fee subhead fine store
    Route::post('/subhead/fine/store', 'SubHeadFineController@subheadFineStore');



    // fee invoice search
    Route::post('/invoice/single/student', 'FeeInvoiceController@getSingleStudentInvoice');
    Route::get('/invoice/payment/single/{invocieID}', 'FeeInvoiceController@SingleStudentPaymentModal');
    Route::post('/class/student/invoice/search', 'FeeInvoiceController@multipleStudentInvoiceSearch');
// invoice delete single student


    Route::get('/student/invoice/delete/{invocieID}', 'FeeInvoiceController@deleteInvoice');


    // waiver student Search

    Route::post('/class/section/student/search', 'WaiverAssignController@searchClassSectionStudent');
    Route::get('/waiver/assign/student', 'WaiverAssignController@waiverAssignModal');
    Route::post('/waiver/assign/store', 'WaiverAssignController@waiverAssignStore');
    Route::get('/waiver_assign/delete/{id}', 'WaiverAssignController@waiverAssignDelete');

    //dwonlaod waiver assign report pdf and excel
    Route::get('/waiver-assign/download/pdf', 'WaiverAssignController@downlaodWaiverAssignPdf');
    Route::get('/waiver-assign/download/excel', 'WaiverAssignController@downlaodWaiverAssignExcel');



    // student payment
    Route::post('/single-student/payment/store', 'TransactionController@singleStudentPayment');
    Route::get('/single-student/invoice/payment/{id}', 'TransactionController@printStudentInvoice');
    Route::post('/single-student/multiple/payment/store', 'TransactionController@singleStudentMultipleInvoicePayment');
    Route::get('/multiple-student/multiple/payment/{arrayId}', 'TransactionController@printMultipleReceipt');
    Route::post('/collection/multiple/student/store', 'TransactionController@multiplePaymentStore');


    Route::get('/invoice/print', function (){
        return view('fee::pages.report.receipt-single-view');
    });

    Route::get('/test/report','FeeReportController@feeHeadYearlyReport');
    Route::post('/student/fee/details','FeeReportController@studentFeeDetails');
    Route::post('/student/fee/details/download','FeeReportController@studentFeeDetailsDownload');
    Route::post('/class/section/transaction','FeeReportController@getClassSectionTransaction');


    // fine collection route
    Route::post('/fine-collection/absent-fine','FineCollectionController@searchAbsentStudentList');
    Route::post('/fine-collection/late-fine','FineCollectionController@searchLateFine');


    // absent fine collection single
    Route::get('/collection/absent/single/{studentid}/{fineRate}','FineCollectionController@absentFineModalSingle');
    Route::post('/absent-fine/single-student/payment/store','FineCollectionController@absentFineCollectionStore');

    // late fine colleciton single
    Route::get('/collection/late-fee/single/{invoice_id}/{totalfeefinePaid}','FineCollectionController@latefeeFineModalSingle');
    Route::post('/late-fine/single-student/payment/store','FineCollectionController@lateFineCollectionStore');


    // absent fine setting her
    Route::post('/setting/absent-fine/','AbsetFineSettingController@store');

    // due amount report route
    Route::post('/due-absent-fine/studentlist','DueReportController@searchDueAbsentStudentList');
    Route::post('/report/due-amount/absent-fine/download','DueReportController@studentDueAbsentReportDownload');

    Route::post('/due-fee-wise/studentlist','DueReportController@studentInvoiceSearch');
    Route::post('/report/due-amount/fee-wise/download','DueReportController@studentDueReportDownload');


    Route::post('/search/class/section/studentlist','DueReportController@searchClassSectionStudent');
    Route::get('/report-due-amount/student/{std_id}','DueReportController@dueAmountReportByStudentID');

    Route::post('/search/class/section/student-due-report','DueReportController@searchClassSectionDueReport');

// money receipt l
    Route::post('/money-receipt/late-fine/list','MoneyReceiptController@lateFineReceipt');
    Route::post('/money-receipt/late-fine/download','MoneyReceiptController@lateFineReceiptDownload');

    // single student money receipt
    Route::get('/single-student/money-receipt/{id}','MoneyReceiptController@studentMoneyReceipt');


    Route::post('/money-receipt/absent-fine/list','MoneyReceiptController@absentFineReceipt');
    Route::post('/money-receipt/absent-fine/download','MoneyReceiptController@absentFineReceiptDownload');


    Route::post('/money-receipt/fee/list','MoneyReceiptController@feeReceipt');
    Route::post('/money-receipt/download','MoneyReceiptController@feeReceiptDownload');

    //Online Payment
    Route::post('/student/onlinepayment/request', 'OnlinePaymentController@onlinePaymentRequest');
    Route::post('/student/onlinepayment/callback', 'OnlinePaymentController@onlinePaymentCallback');
    Route::get('/student/onlinepayment/status', 'OnlinePaymentController@onlinePaymentStatus')->name('paymentStatus');
});

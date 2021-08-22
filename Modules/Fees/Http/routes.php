<?php

//->middleware('access-permission');

Route::group(['middleware' => ['web', 'auth','access-permission'], 'prefix' => 'fees', 'namespace' => 'Modules\Fees\Http\Controllers'], function()
{
    //fees
    Route::get('/', 'FeesController@index');
    Route::get('/{tabName}', 'FeesController@allFees');
    Route::post('/manage', 'FeesController@manageFees');
    Route::post('/update/partial_payment', 'FeesController@updatePartialPaymentById');
    Route::get('/edit/{id}', 'FeesController@getFeesById');
    Route::get('/delete/{id}', 'FeesController@deleteFees');
    Route::get('/find/all_fees', 'FeesController@getAllFees');
    Route::get('/find/all_fees_template', 'FeesController@getAllFeesTemplate');

    // fees due date update

    Route::get('/due_date/update/{id}', 'FeesController@feesDueDateUpdateModal');
    Route::post('/due_date/update/', 'FeesController@feesDueDateUpdate');


    Route::get('/all/search/', 'FeesController@feesSearch');



    // Fees Invoice
    Route::post('/payer/feesStudent', 'FeesInvoiceController@storeStudentPayer');
    Route::get('/batch/section/student', 'FeesInvoiceController@getBatchSection');
    Route::post('/batch/section/store', 'FeesInvoiceController@storeStudentPrayer');
    Route::get('/invoice', 'FeesInvoiceController@index');
    Route::get('/invoice/show/{id}/{backUrl}', 'FeesInvoiceController@getInvoiceById');
    Route::get('/invoice/add/{id}', 'FeesInvoiceController@addInvoice');
    Route::get('/payers/list/{id}', 'FeesInvoiceController@getStudentbyFees');
    Route::get('/invoice/pdf/{id}', 'FeesInvoiceController@getFeesInvoiceReport');
    Route::get('/invoice/pdf/report/{id}', 'FeesInvoiceController@getDemoFeesInvoiceReport');
    Route::get('/invoice/update_status/{id}', 'FeesInvoiceController@updateInvoiceStatus');
    Route::get('/invoice/delete/{id}', 'FeesInvoiceController@deleteInvoice');

    //invoice search

    Route::get('/invoice/search', 'FeesInvoiceController@invoiceSearch');
    Route::post('/invoice/', 'FeesInvoiceController@invoiceSearch');
    Route::post('/invoice/advance_search', 'FeesInvoiceController@invoiceAdvanceSearch');

    Route::get('/invoice-all/pdf/{invoiceArray}', 'FeesInvoiceController@getFeesInvoiceAllReport');

    //Invoice Payment
    Route::get('/invoice/payment/{invoice_id}', 'InvoicePaymentController@invoicePaymentModal');
    Route::get('/invoice/payment/update/{payment_id}', 'InvoicePaymentController@invoicePaymentUpdateModal');
    Route::get('/invoice/payment/view/{payment_id}', 'InvoicePaymentController@invoicePaymentViewModal');
    Route::post('/invoice/payment/update/', 'InvoicePaymentController@invoicePaymentUpdate');
    Route::post('/invoice/payment/student/store', 'InvoicePaymentController@store');
    Route::post('/invoice/attendance/payment/student/store', 'InvoicePaymentController@storeStudentAttendanceFine');


    //Payment Method Route
    Route::get('/payment/method', 'PaymentMethodController@index');
    Route::post('/payment/method/store', 'PaymentMethodController@store');

    //payment search
    Route::get('/paymenttransaction/search', 'InvoicePaymentController@searchPaymentTransaction');

    Route::post('/paymenttransaction', 'InvoicePaymentController@searchPaymentTransaction');



    //fees Report
    Route::get('/report/index','FeesReportController@index');
    Route::get('/report/search','FeesReportController@getBatchListReport');
    Route::get('/report/{reporttype}/{academicYear}/{academicLevel}','FeesReportController@getFeesCollectionReportPdfExcel');


    // fees all student fees details Report Card

    Route::post('/student/details/report-card','FeesReportController@feesDetailsReportPdfExcel');

    //due date fees report

    Route::get('/report/due-date-fees-report','FeesReportController@dueDateFeesReportView');
    Route::post('/report/due-date-fees-report','FeesReportController@dueDateFeesReport');
    Route::get('/report/due-date-fees-report-pdf-excel/','FeesReportController@dueDateFeesReportPdfExcel');

    //date wise fees report
//    Route::get('/report/date-wise-fees','FeesReportController@dateWiseFees');
    Route::get('/report/date-wise-fees/','FeesReportController@searchDateWiseFees');
    Route::post('/report/date-wise-fees/','FeesReportController@searchDateWiseFees');
    Route::get('/report/daily-fees-report/','FeesReportController@getFeesPaymentTransactionPdfExcel');

    // dailay Payment Sum
    Route::get('/get_daily_payment/{date}/{company_id}','FeesReportController@getDailayPayment');
    Route::get('/data/pdf','FeesReportController@getpdf');
    //get Banglaa Pdf
    Route::get('/lang/pdf','FeesReportController@getLang');

    Route::get('/daily_payment/test',function(){
        echo date('l jS \of F Y h:i:s A');
    });


    // daily fees collection Api call
//        Route::get('/fees_collection/{fees_name}/{start_date}/{end_date}','FeesReportController@dailyFeesCollectionApi');
    Route::get('/fees_collection/{start_date}/{end_date}','FeesReportController@dailyFeesCollectionApi');


    //fees template update

    Route::get('/template/copy/{id}','FeesController@feesTemplateUpdatebyId');
    Route::post('/fees-template-manage/search','FeesController@feesTemplateSearch');
    Route::get('/template/add/class-section/{feesId}','FeesController@feesTemplateAddClassSectionModal');
    Route::post('/feestemplate/add/class/section','FeesController@feesTemplateClassSectionStore');
    Route::get('/template/class/section/delete/{id}','FeesController@feesTemplateClassSectionDelete');
    Route::get('/template/class/section/find','FeesController@feesTemplateBatchSection');
    Route::post('/template/manage','FeesController@feesTemplateManage');




    Route::get('/see/discount','FeesController@getDiscount');


    // report Card
    Route::post('/all/student/invoice/report-card/','FeesReportController@getInvoiceAllStudentReportCard');
    Route::post('/single/student/invoice/report-card/','FeesReportController@getInvoiceSingleStudentReportCard');


    //fee type route
    Route::post('/feetype/','FeeTypeController@store');
    Route::get('/feetype/delete/{id}','FeeTypeController@delete');
    Route::get('/feetype/edit/{id}','FeeTypeController@edit');


    //payment extra amount



    Route::get('/advance/payment/modal','PaymentExtraController@advancePaymentModal');
    Route::get('/advance_payment/search','PaymentExtraController@searchPaymentExtra');
    Route::post('/advance/payment/store','PaymentExtraController@advancePaymentStore');


    // waiver route

    Route::post('/invoice/add-waiver/{id}','InvoicePaymentController@addStudentWaiver');
    Route::get('/invoice/add-waiver-modal/{id}','FeesInvoiceController@addStudentWaiverModal');

    // fees waiver find batch section
    Route::get('/find/batch','FeesBatchSectionController@getBatchByFees');
    Route::get('/find/section','FeesBatchSectionController@getSectionByFees');
    Route::post('/student/batch-section/waiver/list','FeesBatchSectionController@getBatchSectionWaiver');

    // fees monthly report here


    Route::get('/monthly/report','FeesReportController@feesMonthlyReport');


    // fees setting rotue
    Route::get('/setting/view/{tab}','FeesController@feesSettingView');

    // fees fine report here

    Route::post('/student/fine/report','InvoiceFineController@getDateWiseFineReport');

    // fees attendance fine
    Route::post('/attendance/fine/list','AttendanceFineController@fineStudentList');
    Route::post('/attendance/fine/generate','AttendanceFineController@fineGenerate');

    // fees manage invoice search

    Route::get('/feesmanage/search','FeesInvoiceController@getInvoiceListByFeesIdName');
    // fees invoice payer add modal
    Route::get('/feesmanage/add/payer/{fees_id}','FeesInvoiceController@getAddPayerModal');
    Route::post('/feesmanage/add/payer/','FeesInvoiceController@addPayerFeesInvoice');

    // add payer class section
    Route::get('/feesmanage/add/payer/class/section/{fees_id}','FeesInvoiceController@getAddPayerClassSectionModal');
    Route::get('/feesmanage/add/payer/class/section/','FeesInvoiceController@addPayerClassSectionFeesInvoice');
    Route::post('/feesmanage/add/payer/class/section/store','FeesInvoiceController@addPayerClassSectionFeesInvoiceStore');

    // fine reduction

    Route::get('/student/fine-reduction/','FineReductionController@index');
    Route::post('/student/fine_reduction/search/','FineReductionController@searchInvoice');
    Route::get('/student/fine-reduction/modal/{id}/{due_fine}/{attendance_fine}','FineReductionController@showFineReductionModal');
    Route::post('/student/fine-reduction/store','FineReductionController@fineReductionStore');


    // other invoice genearte and payment route here
    Route::post('/attendance/fine/student/date_range','AttendanceInvoiceController@fineStudentList');
    Route::post('/attendance/fine/generate/invoice','AttendanceInvoiceController@fineGenerateInvoice');

    Route::get('/student/invoice/search','AttendanceInvoiceController@attendanceAndInvoiceSearchView');
    Route::get('/student/invoice/search/result','AttendanceInvoiceController@attendanceAndInvoiceSearchResult');
    Route::get('/student/invoice/process','AttendanceInvoiceController@attendanceInvoiceProcess');

    //invoice/payment/student/process/store
    // attendance store
    Route::post('/invoice/payment/student/process/store','AttendanceInvoiceController@attendanceInvoiceProcessStore');
    // Attendance Fine Invoice Payment View
    Route::get('/invoice/student/payment/process/{id}/{totalAmount}/', 'AttendanceInvoiceController@invoicePaymentProcessModal');

    // new Item Route

    Route::post('/items/store/','ItemsController@store');
    Route::get('/items/delete/{id}','ItemsController@delete');
    Route::get('/items/edit/{id}','ItemsController@edit');
    Route::get('/items/find','ItemsController@findItems');


    // fees items collection
    Route::get('/fees_item_collection/{start_date}/{end_date}','FeesReportController@getFeesItemsCollectionApi');

    // feesDashboard
    Route::get('dashboard/panel','FeesReportController@dashboard');
    Route::get('monthly/tuition-fees/{id}','FeesReportController@getMonthlyTuitionFeesGenerate');


});



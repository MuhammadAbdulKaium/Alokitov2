<?php

Route::group(['middleware' => ['web','auth'], 'prefix' => 'library', 'namespace' => 'Modules\Library\Http\Controllers'], function()
{
    Route::get('/', 'LibraryController@index');


    //library book category
    Route::get('/library-book-category/index','BookCategoryController@index');
    Route::post('/library-book-category/store','BookCategoryController@store');
    Route::get('/library-book-category/delete/{id}','BookCategoryController@delete');
    Route::get('/library-book-category/edit/{id}','BookCategoryController@edit');

    //library book shelf
    Route::get('/library-book-shelf/index','BookShelfController@index');
    Route::post('/library-book-shelf/store','BookShelfController@store');
    Route::get('/library-book-shelf/edit/{id}','BookShelfController@edit');
    Route::get('/library-book-shelf/delete/{id}','BookShelfController@delete');

    //library cupboard Shelf
    Route::get('/library-cupboard-shelf/index','CupBoardShelfController@index');
    Route::get('/find/library-cupboard-shelf/{id}','CupBoardShelfController@getCupBoradShelfByBookShelfId');
    Route::post('/library-cupboard-shelf/store','CupBoardShelfController@store');
    Route::get('/library-cupboard-shelf/delete/{id}','CupBoardShelfController@delete');
    Route::get('/library-cupboard-shelf/edit/{id}','CupBoardShelfController@edit');


    //library vendor master
    Route::get('/library-book-vendor/index','BookVendorController@index');
    Route::post('/library-book-vendor/store','BookVendorController@store');
    Route::get('/library-book-vendor/delete/{id}','BookVendorController@delete');
    Route::get('/library-book-vendor/edit/{id}','BookVendorController@edit');

    //book create
    Route::get('/library-book/index','BookController@showAllBooks')->name('bookList');;
    Route::get('/library-book/create','BookController@index');
    Route::post('/library-book/store','BookController@store');
    Route::get('/library-book/view/{id}','BookController@viewBookDetails');
    Route::get('/library-book/update-book/{id}','BookController@editBook');

    // add more copy modal
    Route::get('/library-book-master/add-more-copy/{id}','BookController@addMoreCopyModal');
    // add more copy store
    Route::post('/library-book-master/add-more-copy/store','BookController@addMoreCopyStore');
    ///book search
    Route::get('/library-borrow-transaction/search','BookController@bookSearch');



    // book list show
    Route::get('/library-borrow-transaction/index','BookController@showBorrowBookTransaction');

    //issue Book Modal
    Route::get('/library-borrow-transaction/borrow-book/{id}','IssueBookController@showIssueBookModal');
    Route::post('/library-borrow-transaction/issue-book/store','IssueBookController@storeIssueBook');
    Route::get('/library-borrow-transaction/borrower','IssueBookController@showReturnRenewBook');

    Route::get('/library-borrow-transaction/update/{id}','IssueBookController@bookBorrowTransactionUpdate');
    // show Modal
    Route::get('/library-borrow-transaction/return-book/{id}','IssueBookController@returnRenewBook');
    Route::post('/library-borrow-transaction/return-book/update/{id}','IssueBookController@returnRenewBookUpdate');

//    Fine
    Route::post('/library-borrow-transaction/return-book-with-fine/{id}','IssueBookController@returnBookWithFine');
    Route::post('/library-borrow-transaction/return-book-with-fine-manual/{id}','IssueBookController@returnBookWithFineManual');
    Route::get('/library-borrow-transaction/return-book-with-fine-show/{id}','IssueBookController@returnBookManually');
    Route::get('/library-fine-master/fine-list','IssueBookController@fineList')->name('fineList');


    //library book  status
        Route::get('/library-book-status/index','BookStatusController@index');
        Route::post('/library-book-status/store','BookStatusController@store');
        Route::get('/library-book-status/edit/{id}','BookStatusController@edit');
        Route::get('/library-book-status/delete/{id}','BookStatusController@delete');




//    //library book  Master
//    Route::get('/library-book-master/index', function (){
//        return view('library::library-book-master.index');
//    });



        // library-borrow- transaction book search
//     Route::get('/library-borrow-transaction/index', function (){
//         return view('library::library-borrow-transaction.index');
//     });


    // library book issue search
//    Route::get('/library-borrow-transaction/index', function (){
//        return view('library::issue-books.index');
//    });

    // library return and renuew book


        // library return and renuew book
//    Route::get('/library-fine-master/fine-list', function (){
//        return view('library::library-fine-master.fine');
//    });



});



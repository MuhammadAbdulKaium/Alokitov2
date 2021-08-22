<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('mess')->group(function () {
    Route::get('/', 'MessController@index');

    // Mess Table Routes
    Route::get('/table', 'MessTableController@index');
    Route::get('/create/table', 'MessTableController@create');
    Route::post('/store/table', 'MessTableController@store');
    Route::get('/edit/table/{id}', 'MessTableController@edit');
    Route::post('/update/table/{id}', 'MessTableController@update');
    Route::get('/table/history/{id}', 'MessTableController@history');
    Route::get('/delete/table/{id}', 'MessTableController@destroy');

    // Mess Table Ajax Routes
    Route::get('/get/persons/from/personType', 'MessTableController@getPersonsFromPersonType');
    Route::get('/get/all/persons/from/personType', 'MessTableController@getAllPersonsFromPersonType');
    Route::get('/assign/person/to/seat', 'MessTableController@assignPersonToSeat');
    Route::get('/get/mess/table/view', 'MessTableController@getMessTableView');
    Route::get('/get/person/details', 'MessTableController@getPersonDetails');
    Route::get('/get/mess/table/seats', 'MessTableController@getMessTableSeats');
    Route::get('/remove/person/from/seat', 'MessTableController@removePersonFromSeat');
    Route::get('/search/table/by/person', 'MessTableController@searchTableByPerson');


    // Mess Food Menu Routes
    Route::get('/food-menu', 'FoodMenuController@index');
    Route::post('/food-menu/store/category', 'FoodMenuController@store');
    Route::post('/food-menu/store/menu', 'FoodMenuController@storeMenu');
    Route::post('/food-menu/store/menu/item', 'FoodMenuController@storeMenuItem');
    Route::get('/food-menu/category/delete/{id}', 'FoodMenuController@destroy');

    // Mess Food Menu Modal Routes
    Route::get('/food-menu/category/edit/{id}', 'FoodMenuController@edit');
    Route::get('/food-menu/edit/{id}', 'FoodMenuController@menuEdit');
    Route::get('/food-menu/item/edit/{id}', 'FoodMenuController@menuItemEdit');
    Route::get('/food-menu/assign-item/view/{id}', 'FoodMenuController@assignItemView');
    Route::post('/food-menu/category/update/{id}', 'FoodMenuController@update');
    Route::post('/food-menu/update/{id}', 'FoodMenuController@updateMenu');
    Route::post('/food-menu/item/update/{id}', 'FoodMenuController@updateMenuItem');
    Route::post('/food-menu/assign-item/to/menu/{id}', 'FoodMenuController@assignItemToMenu');


    // Mess Food Menu Schedule Routes
    Route::get('/food-menu-schedule', 'FoodMenuScheduleController@index');
    Route::get('/print/food-menu-schedule', 'FoodMenuScheduleController@printSchedule');

    // Mess Food Menu Schedule Ajax Routes
    Route::get('/food-menu-schedule/table', 'FoodMenuScheduleController@foodMenuScheduleTable');
    Route::get('/get/menu/from/category', 'FoodMenuScheduleController@getMenuFromCategory');
    Route::get('/get/form-designation/from/class-department', 'FoodMenuScheduleController@getFormDesignationFromClassDepartment');
    Route::get('/get/strength/from/form-designation', 'FoodMenuScheduleController@getStrengthFormDesignation');
    Route::get('/save/food-menu/schedule', 'FoodMenuScheduleController@saveFoodMenuSchedule');
});

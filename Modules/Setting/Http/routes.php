<?php

//Route::group(['middleware' => ['web', 'auth','setting-permission'], 'prefix' => 'setting', 'namespace' => 'Modules\Setting\Http\Controllers'], function () {
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'setting', 'namespace' => 'Modules\Setting\Http\Controllers'], function () {
    /*Start Institute*/
    Route::get('/', 'InstituteController@index');
    Route::get('institute-update-view', 'InstituteController@show');
    Route::any('store-institute-update/{id}', 'InstituteController@store_update');
    Route::any('add-institute', 'InstituteController@add_institute');

    Route::any('store-add-institute', 'InstituteController@save_add_institute');

    Route::any('edit-institute-view/{id}', 'InstituteController@edit_institute_view');
    Route::any('institute-view/{id}', 'InstituteController@institute_show');
    Route::get('delete-institute/{id}', 'InstituteController@delete');
    /*End Institute*/



//    Route::post('/admin/institute/create', 'InstituteController@index');
    Route::post('/admin/institute/store', 'InstituteController@storeInstitute');
    Route::post('/admin/campus/store', 'CampusController@storeCampusBySuperAdmin');

    /*Setting Country Start*/

    Route::any('country', 'CountryController@index');

    Route::any('store-country', 'CountryController@store');

    Route::any('view-country/{id}', 'CountryController@show');

    Route::any('edit-country/{id}', 'CountryController@edit');

    Route::any('edit-country-perform/{id}', 'CountryController@update');

    Route::any('delete-country/{id}', 'CountryController@delete');
    /*Setting Country End*/

    /*Setting City Start*/

    Route::any('city', 'CityController@index');

    Route::any('store-city', 'CityController@store');

    Route::any('view-city/{id}', 'CityController@show');

    Route::any('edit-city/{id}', 'CityController@edit');

    Route::any('edit-city-perform/{id}', 'CityController@update');

    Route::any('delete-city/{id}', 'CityController@delete');
    /*Setting City End*/

    /*Setting State Start*/

    Route::any('state', 'StateController@index');
    Route::any('store-state', 'StateController@store');
    Route::any('view-state/{id}', 'StateController@show');
    Route::any('edit-state/{id}', 'StateController@edit');
    Route::any('state-edit-perform/{id}', 'StateController@update');
    Route::any('delete-state/{id}', 'StateController@delete');
    /*Setting State End*/

    /*Start Campus*/
    Route::any('campus', 'CampusController@index');
    Route::any('add-campus/{institute_id}', 'CampusController@add_campus');
    Route::any('store-campus-update/{id}', 'CampusController@store_update');
    Route::any('store-add-campus/{institute_id}', 'CampusController@save_add_campus');
    Route::any('edit-campus-view/{id}', 'CampusController@edit_campus_view');
    Route::any('campus-edit-perform/{id}', 'CampusController@campus_edit_save');
    Route::any('campus-view/{id}', 'CampusController@campus_show');
    Route::any('delete-campus/{id}', 'CampusController@delete');


    ////////////////////  manage users /////////////////////////
    Route::get('/manage/users', 'UserInstitutionController@index');
    Route::get('/manage/users/create', 'UserInstitutionController@createAdminUser');
    Route::post('/manage/users/store', 'UserInstitutionController@storeAdminUser');
    Route::get('/manage/users/show/{userId}/campus', 'UserInstitutionController@showAdminUserCampusList');



    // Language setting here are ...
    Route::get('/language/index/', 'LanguageController@index');
    Route::post('/language/store/', 'LanguageController@store');
    Route::get('/language/edit/{id}', 'LanguageController@languageEdit');
    Route::get('/language/delete/{id}', 'LanguageController@deleteLanguage');

//language Choosen
    Route::get('/language/choose/{locale}','LanguageController@selectLanguage');


    // set institute wise language

    Route::get('/language/institute','InstituteLanguageController@index');
    Route::post('/language/institute/store','InstituteLanguageController@store');


    //sms setting route
    Route::get('/sms/setting/{tabName}','SmsSettingController@allSmsSetting');


    //sms setting route
//    Route::get('/sms/setting/getway','SmsSettingController@index');

    // sms getway setting route
    Route::post('/sms/getway/store/','SmsInstitutionGetwayController@smsGetwayStore');
    Route::get('/sms/getway/list','SmsInstitutionGetwayController@show');
    Route::get('/sms/getway/edit/{id}','SmsInstitutionGetwayController@smsGetwayEdit');
    Route::post('/sms/getway/update','SmsInstitutionGetwayController@smsGetwayUpdate');
    Route::get('/sms/getway/delete/{id}','SmsInstitutionGetwayController@deleteSmsGetWay');


    // auto sms module setting

    Route::post('/sms/autosmsmodule/store/','AutoSmsController@store');
    Route::get('/sms_modules/update/{id}/','AutoSmsController@updateModal');
    Route::post('/sms/autosmsmodule/update/','AutoSmsController@updateSmsModules');


    // aut sms setting


    Route::post('/sms/autosmssetting/store','AutoSmsSettingController@store');


    ////////// User rights management  //////////

    // user institute
    Route::get('/institute/campus/assign','UserInstitutionController@assignCampus');
    Route::get('/institute/campus/assign/user','UserInstitutionController@getUserCampus');
    Route::post('/institute/campus/assign','UserInstitutionController@assignUserCampus');
    Route::get('/find/user','UserInstitutionController@findInstituteUser');


    // assign user (uno) by super admin
    Route::get('/uno/institute/assign','UserInstitutionController@getUNOInstitute');
    // Route::post('/uno/institute/assign','UserInstitutionController@assignUNOInstitute');
    Route::get('/uno/institute/pie','UserInstitutionController@uno');
    Route::get('/uno/institute/pie/compare','UserInstitutionController@unoCompare');


    // change user campus
    Route::get('/institute/campus/{id}','UserInstitutionController@changeCampus');


    // user rights
    Route::get('/rights/{tabId}', 'UserRights\UserRightController@index');
    // role
    Route::get('/rights/role/create', 'UserRights\RoleController@create');
    Route::post('/rights/role/store', 'UserRights\RoleController@store');
    Route::get('/rights/role/edit/{id}', 'UserRights\RoleController@edit');
    Route::get('/rights/role/delete/{id}', 'UserRights\RoleController@destroy');
    // permission
    //  Route::get('/rights/permission','PermissionController@index');
    Route::get('/rights/permission/create', 'UserRights\PermissionController@create');
    Route::post('/rights/permission/store', 'UserRights\PermissionController@store');
    Route::get('/rights/permission/status/{id}', 'UserRights\PermissionController@status');
    Route::get('/rights/permission/edit/{id}', 'UserRights\PermissionController@edit');
    Route::get('/rights/permission/delete/{id}', 'UserRights\PermissionController@destroy');
    // user permission
    Route::get('/user-permission', 'UserRights\PermissionController@getUserPermission');
    Route::post('/user-permission', 'UserRights\PermissionController@storeUserPermission');

    // module
    Route::get('/rights/module/create', 'UserRights\ModuleController@create');
    Route::post('/rights/module/store', 'UserRights\ModuleController@store');
    Route::get('/rights/module/status/{id}', 'UserRights\ModuleController@status');
    Route::get('/rights/module/edit/{id}', 'UserRights\ModuleController@edit');
    Route::get('/rights/module/delete/{id}', 'UserRights\ModuleController@destroy');
    // menu
    Route::get('/rights/menu/create', 'UserRights\MenuController@create');
    Route::get('/rights/menu/store', 'UserRights\MenuController@store');
    Route::get('/rights/menu/status/{id}', 'UserRights\MenuController@status');
    Route::get('/rights/menu/edit/{id}', 'UserRights\MenuController@edit');
    Route::get('/rights/menu/delete/{id}', 'UserRights\MenuController@destroy');
    // find sub modules
    Route::get('/rights/menu/find', 'UserRights\MenuController@findMenu');
    Route::get('/rights/find/module', 'UserRights\ModuleController@findSubModule');
    Route::get('/rights/permission/find', 'UserRights\PermissionController@findPermission');


    //setting institute module assign
    Route::any('/rights/setting/institute-module/', 'UserRights\UserRightController@manageInstituteModule');
    Route::any('/rights/setting/role-permission', 'UserRights\UserRightController@manageRolePermission');
    Route::any('/rights/setting/menu-permission', 'UserRights\UserRightController@manageMenuPermission');

    // setting audit
    Route::get('/audit/history', 'AuditController@getAuditList');
    Route::get('/audit/userList', 'AuditController@getAuditUserList');
    Route::get('/audit/search', 'AuditController@auditSearch');


    // setting institute Property
     Route::get('/institute/property', 'SettingInstPropController@index');
     Route::get('/find/campus', 'CampusController@getCampusListByInstitueId');
     Route::post('/institute/property/store', 'SettingInstPropController@store');
     Route::get('/institute/property/edit/{id}', 'SettingInstPropController@edit');
     Route::post('/institute/property/', 'SettingInstPropController@update');
     Route::get('/institute/property/delete/{id}', 'SettingInstPropController@delete');


    // setting font family Setting
    Route::get('/font-family/', 'FontFamilyController@index');
    Route::post('/font-family/store', 'FontFamilyController@store');
    Route::get('/font-family/edit/{id}', 'FontFamilyController@edit');
    Route::get('/font-family/delete/{id}', 'FontFamilyController@delete');


    // for performance category
    Route::get('/performance/category', 'CadetPerformanceCategoryController@index');

    Route::post('/performance/category/create', 'CadetPerformanceCategoryController@store');
    Route::get('/performance/category/edit/{id}', 'CadetPerformanceCategoryController@CategoryEdit');
    Route::post('/performance/category/update/{id}', 'CadetPerformanceCategoryController@CategoryUpdate');
    Route::get('/performance/category/delete/{id}', 'CadetPerformanceCategoryController@CategoryDelete');


    Route::post('/performance/activity/create', 'CadetPerformanceCategoryController@activityStore');
    Route::get('/performance/activity/edit/{id}', 'CadetPerformanceCategoryController@ActivityEdit');
    Route::post('/performance/activity/update/{id}', 'CadetPerformanceCategoryController@ActivityUpdate');
    Route::get('/performance/activity/delete/{id}', 'CadetPerformanceCategoryController@ActivityDelete');

    Route::get('/performance/activity/point/{id}', 'CadetPerformanceCategoryController@ActivityPoint');
    Route::post('/performance/activity/point/add', 'CadetPerformanceCategoryController@ActivityPointAdd');
    Route::get('/performance/activity/point/edit/{id}', 'CadetPerformanceCategoryController@ActivityPointEdit');
    Route::get('/performance/activity/point/delete/{id}', 'CadetPerformanceCategoryController@ActivityPointDelete');


    // for performance type
    Route::post('/performance/type/create', 'CadetPerformanceCategoryController@TypeStore');
    Route::get('/performance/type/edit/{id}', 'CadetPerformanceCategoryController@TypeEdit');
    Route::post('/performance/type/update/{id}', 'CadetPerformanceCategoryController@TypeUpdate');
    Route::get('/performance/type/delete/{id}', 'CadetPerformanceCategoryController@TypeDelete');

    // Academic Point
    Route::post('/academics/subject/entry', 'CadetPerformanceCategoryController@SubjectPointStore');


    // fees Setting Here
    Route::get('/fees/setting/list','FeesSettingController@index');
    Route::get('/fees/setting/create','FeesSettingController@create');
    Route::post('/fees/setting/store','FeesSettingController@store');
    Route::get('/fees/setting/delete/{id}','FeesSettingController@delete');
    Route::get('/fees/setting/edit/{id}','FeesSettingController@edit');

    // attendance fine setting
    Route::get('/attendance/','AttendanceFineController@index');
    Route::get('/attendance/create','AttendanceFineController@create');
    Route::post('/attendance/store','AttendanceFineController@store');
    Route::get('/attendance/delete/{id}','AttendanceFineController@delete');
    Route::get('/attendance/edit/{id}','AttendanceFineController@edit');

    Route::get('/change/password','ChangePasswordController@index');
    Route::get('/password/change/user','ChangePasswordController@findUser');
    Route::post('/change/password/store','ChangePasswordController@store');

    Route::get('/login/screen/','LoginScreenController@index');
    Route::post('/login/screen/store','LoginScreenController@store');
    Route::get('/login/screen/edit/{id}','LoginScreenController@edit');
    Route::get('/login/screen/delete/{id}','LoginScreenController@delete');

    // institution sms price setting route
    Route::get('/institute/sms-price/','InstitutionSmsPriceController@index');
    Route::post('/institute/sms-price/store','InstitutionSmsPriceController@store');
    Route::get('/institute/sms-price/edit/{id}','InstitutionSmsPriceController@edit');
    Route::get('/institute/sms-price/delete/{id}','InstitutionSmsPriceController@delete');


});

Route::group(['middleware' => ['web','auth'], 'prefix' => 'setting', 'namespace' => 'Modules\Setting\Http\Controllers'], function () {
    ////////////// ajax request from student module address page //////////////
    Route::get('/find/state/', 'StateController@findStateList');
    Route::get('/find/city/', 'CityController@findCityList');
    // find institute list by search term
    Route::get('/find/institute/','InstituteController@findInstituteBySearchTerm');


});




Route::group(['middleware' => ['web'], 'namespace' => 'Modules\Setting\Http\Controllers'], function () {

});
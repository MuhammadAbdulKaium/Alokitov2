<?php
//
//Route::group(['middleware' => 'web', 'prefix' => 'academics', 'namespace' => 'Modules\Academics\Http\Controllers'], function()
//{
//    Route::get('/', 'AcademicsController@index');
//
//	//
//	Route::get('event', 'AcademicsController@event');
//	Route::get('assignment', 'AcademicsController@assignment');
//	Route::get('ad_assignment', 'AcademicsController@addAssignment');
//
//	// acadamic/online test
//	Route::get('online_test/question_category', 'AcademicsController@questionCategory');
//	Route::get('online_test/question_master', 'AcademicsController@questionMaster');
//	Route::get('online_test/grading_system', 'AcademicsController@gradingSystem');
//	Route::get('online_test/create_grading_system', 'AcademicsController@createGadingSystem');
//	Route::get('online_test/test_create', 'AcademicsController@createTest');
//	Route::get('online_test/online_test_master', 'AcademicsController@testMaster');
//
//    //test commit
//
//
//	/*Subject*/
//
//	Route::get('subject', [
//		'as' => 'subject',
//		'uses' => 'SubjectController@index'
//	]);
//
//	Route::any("store-subject", [
//		"as"   => "store-subject",
//		"uses" => "SubjectController@store"
//	]);
//
//	Route::get('view-subject/{id}', [
//		"as"   => "view-subject",
//		"uses" => "SubjectController@show"
//	]);
//	Route::get('edit-subject/{id}', [
//		"as"   => "edit-subject",
//		"uses" => "SubjectController@edit"
//	]);
//
////	Route::post('edit-subject-perform/{id}', [
////		"as"   => "edit-subject-perform",
////		"uses" => "SubjectController@update"
////	]);
//
//	Route::any("delete-subject/{id}", [
//		"as"   => "delete-subject",
//		"uses" => "SubjectController@delete"
//	]);
//
//
//
//	Route::post('edit-subject-perform/{id}', 'SubjectController@update');
///*Subject End*/
//
//
//	/*Start Academic year*/
//
//	Route::any('academic-year', 'AcademicYearController@index');
//
//	Route::any('store-academic-year', 'AcademicYearController@store');
//
//	Route::any('view-academic-year/{id}', 'AcademicYearController@show');
//
//
//
//	Route::any('edit-academic-year/{id}', 'AcademicYearController@edit');
//
//	Route::any('edit-academic-year-perform/{id}', 'AcademicYearController@update');
//
//	Route::any('delete-academic-year/{id}', 'AcademicYearController@delete');
//
//	/*End Academic year*/
//
//	/*Course Start*/
//
//	Route::any('academic-year', 'AcademicYearController@index');
//
//	Route::any('store-academic-year', 'AcademicYearController@store');
//
//	Route::any('view-academic-year/{id}', 'AcademicYearController@show');
//
//
//
//	Route::any('edit-academic-year/{id}', 'AcademicYearController@edit');
//
//	Route::any('edit-academic-year-perform/{id}', 'AcademicYearController@update');
//
//	Route::any('delete-academic-year/{id}', 'AcademicYearController@delete');
//	/*Course End*/
//
//
//	/*Academic Level Start*/
//
//	Route::any('academic-level', 'AcademicLevelController@index');
//
//	Route::any('store-academic-level', 'AcademicLevelController@store');
//
//	Route::any('view-academic-level/{id}', 'AcademicLevelController@show');
//
//
//
//	Route::any('edit-academic-level/{id}', 'AcademicLevelController@edit');
//
//	Route::any('edit-academic-level-perform/{id}', 'AcademicLevelController@update');
//
//	Route::any('delete-academic-level/{id}', 'AcademicLevelController@delete');
//	/*Acdemic Level End*/
//
//
//
//	/*Batch Start*/
//
//	Route::post('batch', 'SubjectController@update');
//	/*Batch End*/
//
//});
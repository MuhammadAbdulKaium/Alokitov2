<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'payroll', 'namespace' => 'Modules\Payroll\Http\Controllers'], function()
{
    //salary component
    Route::get('salary-component', 'SalaryComponentController@index');
    Route::get('salary-component/add', 'SalaryComponentController@create');
    Route::post('salary-component/store', 'SalaryComponentController@store');
    Route::post('salary-component/show', 'SalaryComponentController@show');
    Route::post('salary-component/edit', 'SalaryComponentController@edit');
    Route::post('salary-component/update', 'SalaryComponentController@update');
    Route::post('salary-component/delete', 'SalaryComponentController@destroy');

    //salary structure
    Route::get('salary-structure', 'SalaryStructureController@index');
    Route::get('salary-structure/add', 'SalaryStructureController@create');
    Route::post('salary-structure/store', 'SalaryStructureController@store');
    Route::post('salary-structure/show', 'SalaryStructureController@show');
    Route::post('salary-structure/edit', 'SalaryStructureController@edit');
    Route::post('salary-structure/update', 'SalaryStructureController@update');
    Route::post('salary-structure/delete', 'SalaryStructureController@destroy');

    //salary allocation
    Route::get('emp-salary-assign','SalaryAssignController@index');
    Route::get('emp-salary-assign/add','SalaryAssignController@create');
    Route::post('emp-salary-assign/store','SalaryAssignController@store');
    Route::post('emp-salary-assign/show','SalaryAssignController@show');
    Route::post('emp-salary-assign/edit','SalaryAssignController@edit');
    Route::post('emp-salary-assign/salary-segregation','SalaryAssignController@salarySegregation');
    Route::post('emp-salary-assign/delete','SalaryAssignController@destroy');

    //salary monthly deduction and allowance
    Route::get('emp-salary-dedallo','SalaryMonthlyController@index');
    Route::get('emp-salary-dedallo/add','SalaryMonthlyController@create');
    Route::post('emp-salary-dedallo/store','SalaryMonthlyController@store');
    Route::post('emp-salary-dedallo/show','SalaryMonthlyController@show');
    Route::post('emp-salary-dedallo/delete','SalaryMonthlyController@destroy');

    //salary employee calculation monthly
    Route::get('emp-salary-monthly', 'SalaryMonthlyController@monthlySalaryCalc');
    Route::post('emp-salary-monthly/calc', 'SalaryMonthlyController@monthlySalaryCalcStore');
    Route::get('emp-salary', 'SalaryMonthlyController@empSalaryAll');
    Route::post('emp-salary/emp_monthly_sal', 'SalaryMonthlyController@empSalaryMonthly');
    Route::post('emp-salary/single_emp_monthly_sal', 'SalaryMonthlyController@empSalaryDetails');

    //pf-rules
    Route::get('pf-rules','SalaryPfRuleController@index');
    Route::get('pf-rules/add','SalaryPfRuleController@create');
    Route::post('pf-rules/store','SalaryPfRuleController@store');
    Route::post('pf-rules/show', 'SalaryPfRuleController@show');
    Route::post('pf-rules/delete', 'SalaryPfRuleController@destroy');

    //ot-rate
    Route::get('ot-rates','SalaryOtRuleController@index');
    Route::get('ot-rates/add','SalaryOtRuleController@create');
    Route::post('ot-rates/store','SalaryOtRuleController@store');
    Route::post('ot-rates/show', 'SalaryOtRuleController@show');
    Route::post('ot-rates/delete', 'SalaryOtRuleController@destroy');

    //emp-lone
    Route::get('emp-lones','SalaryEmpLoneController@index');
    Route::get('emp-lones/add','SalaryEmpLoneController@create');
    Route::post('emp-lones/store','SalaryEmpLoneController@store');
    Route::post('emp-lones/show','SalaryEmpLoneController@show');
    Route::post('emp-lones/delete','SalaryEmpLoneController@destroy');
});
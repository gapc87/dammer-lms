<?php

Route::post('/login', 'AuthController@login');

Route::middleware('auth:api')->group(function () {

    Route::post('/logout', 'AuthController@logout');

    Route::get('/modules', 'HomeController@modules');
    Route::get('/modules/{module}', 'ModuleController@index');
    //Route::get('/modules/{module}/tasks/{task}', 'ModuleController@task');
    Route::get('/modules/{module}/tasks/{task}', 'TaskController@task');

    Route::post('/modules/{module}/upload-resource', 'ModuleController@storeResource');
    Route::post('/modules/{module}/upload-task', 'ModuleController@storeTask');

    Route::post('/modules/{module}/tasks/{task}/upload', 'TaskController@storeCompleteTask');

    Route::post('modules/{module}/{uuid}/download', 'ModuleController@downloadResource');
    Route::post('modules/{user}/{module}/{task}/{uuid}/download', 'TaskController@downloadCompleteTask');

    Route::post('modules/{user}/{module}/{task}/evaluate', 'ModuleController@evaluate');

    Route::get('admin/users', 'Admin\UserController@index');
    Route::post('admin/users', 'Admin\UserController@store');
    Route::post('admin/users/{user}', 'Admin\UserController@update');
    Route::delete('admin/users/{user}', 'Admin\UserController@destroy');

    Route::get('admin/modules', 'Admin\ModuleController@index');
    Route::get('admin/modules/{module}', 'Admin\ModuleController@show');
    Route::post('admin/modules', 'Admin\ModuleController@store');
    Route::post('admin/modules/{module}', 'Admin\ModuleController@update');
    Route::delete('admin/modules/{module}', 'Admin\ModuleController@destroy');
    Route::get('admin/teachers', 'Admin\ModuleController@teachers');
    Route::get('admin/students', 'Admin\ModuleController@students');

});

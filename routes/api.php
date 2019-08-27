<?php

Route::post('/login', 'AuthController@login');

Route::middleware('auth:api')->group(function () {

    Route::post('/logout', 'AuthController@logout');

});

<?php

// user things (for testing)
Route::post('auth/register', 'UserController@register');
Route::post('auth/login', 'UserController@login');
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', 'UserController@getAuthUser');
    // another private resources
    Route::get('/user/{user_id}/{related_resource_type}/', 'JsonApi2Controller@getCollection');
});

Route::get('/{resource_type}', 'JsonApi2Controller@getCollection');
Route::get('/{resource_type}/{resource_id}', 'JsonApi2Controller@get');
Route::get('/{related_resource_type}/{resource_id}/{resource_type}', 'JsonApi2Controller@getRelatedCollection');
Route::put('/{resource_type}/{resource_id}', 'JsonApi2Controller@update');

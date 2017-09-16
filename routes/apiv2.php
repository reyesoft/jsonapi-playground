<?php

Route::post('auth/register', 'UserController@register');
Route::post('auth/login', 'UserController@login');


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', 'UserController@getAuthUser');
});

Route::get('/{resource_type}', 'JsonApi2Controller@getCollection');
Route::get('/{resource_type}/{resource_id}', 'JsonApi2Controller@get');
Route::get('/{related_resource_type}/{resource_id}/{resource_type}', 'JsonApi2Controller@getRelatedCollection');

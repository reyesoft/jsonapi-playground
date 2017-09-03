<?php

Route::get('/{resource}', 'JsonApi1Controller@getAll');
Route::get('/{resource}/{resource_id}', 'JsonApi1Controller@get');
Route::delete('/{resource}/{resource_id}', 'JsonApi1Controller@delete');
Route::patch('/{resource}/{resource_id}', 'JsonApi1Controller@update');
Route::put('/{resource}/{resource_id}', 'JsonApi1Controller@update');
Route::post('/{resource}', 'JsonApi1Controller@store');

<?php


//});

Route::get('/{resource_type}', 'JsonApiController@getCollection');
Route::get('/{resource_type}/{resource_id}', 'JsonApiController@get');
Route::get('/{parent_type}/{parent_id}/{resource_type}', 'JsonApiController@getRelatedCollection');
Route::post('/{resource_type}', 'JsonApiController@create');
Route::patch('/{resource_type}/{resource_id}', 'JsonApiController@update');
Route::delete('/{resource_type}/{resource_id}', 'JsonApiController@delete');

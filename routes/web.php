<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{resource}','ApicultorWay@getAll');
Route::get('/{resource}/{resource_id}','ApicultorWay@get');
Route::get('/{resource}/{resource_id}','ApicultorWay@delete');
Route::patch('/{resource}/{resource_id}','ApicultorWay@update');
Route::put('/{resource}/{resource_id}','ApicultorWay@update');

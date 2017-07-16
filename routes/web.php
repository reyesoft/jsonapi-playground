<?php

Route::get('/', function () {
    return view('welcome');
});

## NEOMERX RESOLUTION
Route::get('/neomerx/{resource}','NeomerxMethod@getAll');
Route::get('/neomerx/{resource}/{resource_id}','NeomerxMethod@get');
Route::delete('/neomerx/{resource}/{resource_id}','NeomerxMethod@delete');
Route::patch('/neomerx/{resource}/{resource_id}','NeomerxMethod@update');
Route::put('/neomerx/{resource}/{resource_id}','NeomerxMethod@update');
Route::post('/neomerx/{resource}','NeomerxMethod@store');

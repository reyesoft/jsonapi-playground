<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

Route::get('/{resource_type}', 'JsonApiController@getCollection');
Route::get('/{resource_type}/{resource_id}', 'JsonApiController@get');
Route::get('/{parent_type}/{parent_id}/{resource_type}', 'JsonApiController@getRelatedCollection');
Route::post('/{resource_type}', 'JsonApiController@create');
Route::patch('/{resource_type}/{resource_id}', 'JsonApiController@update');
Route::delete('/{resource_type}/{resource_id}', 'JsonApiController@delete');

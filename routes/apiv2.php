<?php

Route::get('/{resource_type}', 'JsonApi2Controller@getCollection');
Route::get('/{resource_type}/{resource_id}', 'JsonApi2Controller@get');
Route::get('/{related_resource_type}/{resource_id}/{resource_type}', 'JsonApi2Controller@getRelatedCollection');

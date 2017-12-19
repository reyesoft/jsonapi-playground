<?php

$router->get('/{resource_type}', 'JsonApiController@getCollection');
$router->get('/{resource_type}/{resource_id}', 'JsonApiController@get');
$router->get('/{parent_type}/{parent_id}/{resource_type}', 'JsonApiController@getRelatedCollection');
$router->post('/{resource_type}', 'JsonApiController@create');
$router->patch('/{resource_type}/{resource_id}', 'JsonApiController@update');
$router->delete('/{resource_type}/{resource_id}', 'JsonApiController@delete');

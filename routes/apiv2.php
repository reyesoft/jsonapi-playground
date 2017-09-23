<?php

$router->get('/{resource_type}', 'JsonApiController@getCollection');
$router->get('/{resource_type}/{resource_id}', 'JsonApiController@get');
$router->get('/{related_resource_type}/{id}/{resource_type}', 'JsonApiController@getRelatedCollection');

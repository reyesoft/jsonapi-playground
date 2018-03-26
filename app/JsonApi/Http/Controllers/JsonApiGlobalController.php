<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Http\Controllers;

use App\JsonApi\Exceptions\ResourceTypeNotFoundException;
use App\JsonApi\Http\JsonApiRequestHelper;
// use Laravel\Lumen\Routing\Controller;
use Illuminate\Routing\Controller;
use Psr\Http\Message\ServerRequestInterface;

abstract class JsonApiGlobalController extends Controller
{
    /*
     * Avaiable resources on this route controller
     * related with respective Schema, like:
     *
     * const AVAIBLE_RESOURCES = [
     *    'books' => BookSchema::class,
     *    'photos' => BookSchema::class,
     * ];
     */
    public const AVAIBLE_RESOURCES = [];

    /*
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'delete']]);
        // Alternativly
        // $this->middleware('auth', ['except' => ['index', 'show']]);
    }
     */

    public function getCollection(ServerRequestInterface $request, ...$params)
    {
        $resource_type = end($params);
        $jsonapirequesthelper = new JsonApiRequestHelper($request, $this->getSchema($resource_type));

        $service = $jsonapirequesthelper->getObjectService();
        $objects = $service->all();

        return $jsonapirequesthelper->getResponse($objects);
    }

    public function getRelatedCollection(ServerRequestInterface $request, ...$params)
    {
        list($parent_type, $parent_id, $resource_alias) = array_slice($params, -3);

        // find parent resource
        $parent_schema_class = static::AVAIBLE_RESOURCES[$parent_type];
        $parent_schema = new $parent_schema_class();
        $relation = $parent_schema::getRelationshipsSchema()[$resource_alias];

        // set related child schema via reation alias
        $schema = new $relation['schema']();
        $resource_type = $schema->getResourceType();

        // set child model
        $parent_model_class = $parent_schema->getModelName();
        $parent_model = $parent_model_class::findOrFail($parent_id);
        $builder = $parent_model->{$resource_alias}();

        $jsonapirequesthelper = new JsonApiRequestHelper(
                $request,
                $this->getSchema($resource_type),
                $parent_id,
                $resource_alias
            );

        $service = $jsonapirequesthelper->getObjectService();
        $objects = $service->allRelated($builder);

        return $jsonapirequesthelper->getResponse($objects);
    }

    public function get(ServerRequestInterface $request, ...$params)
    {
        list($resource_type, $resource_id) = array_slice($params, -2);

        $jsonapirequesthelper = new JsonApiRequestHelper($request, $this->getSchema($resource_type), $resource_id);

        $service = $jsonapirequesthelper->getObjectService();
        $object = $service->get($resource_id);

        return $jsonapirequesthelper->getResponse($object);
    }

    public function create(ServerRequestInterface $request, ...$route_params)
    {
        $resource_type = end($route_params);
        $jsonapirequesthelper = new JsonApiRequestHelper($request, $this->getSchema($resource_type));

        $service = $jsonapirequesthelper->getObjectService();
        $object = $service->create($request->getParsedBody());

        return $jsonapirequesthelper->getResponse($object);
    }

    public function update(ServerRequestInterface $request, ...$params)
    {
        list($resource_type, $resource_id) = array_slice($params, -2);

        $jsonapirequesthelper = new JsonApiRequestHelper($request, $this->getSchema($resource_type));

        $service = $jsonapirequesthelper->getObjectService();
        $object = $service->update($resource_id, $request->getParsedBody());

        return $jsonapirequesthelper->getResponse($object);
    }

    public function delete(ServerRequestInterface $request, ...$params)
    {
        list($resource_type, $resource_id) = array_slice($params, -2);

        $jsonapirequesthelper = new JsonApiRequestHelper($request, $this->getSchema($resource_type));

        $service = $jsonapirequesthelper->getObjectService();
        $object = $service->delete($resource_id);

        return $jsonapirequesthelper->getResponse('');
        // return response(json_encode(['status' => 'success']), 200);
    }

    protected function getSchema(string $resource_type): string
    {
        if (!isset(static::AVAIBLE_RESOURCES[$resource_type])) {
            throw new ResourceTypeNotFoundException($resource_type);
        }

        return static::AVAIBLE_RESOURCES[$resource_type];
    }
}

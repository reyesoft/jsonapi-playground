<?php

namespace App\JsonApi\Http\Controllers;

use App\JsonApi\Exceptions\ResourceTypeNotFoundException;
use App\JsonApi\Helpers\ObjectsBuilder;
use App\JsonApi\Http\JsonApiRequestHelper;
use Laravel\Lumen\Routing\Controller;
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
    const AVAIBLE_RESOURCES = [];

    /*
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'delete']]);
        // Alternativly
        // $this->middleware('auth', ['except' => ['index', 'show']]);
    }
     */

    public function getCollection(ServerRequestInterface $request, string $resource_type)
    {
        $jsonapirequesthelper = new JsonApiRequestHelper($request, $this->getSchema($resource_type));

        $service = $jsonapirequesthelper->getObjectService();
        $objects = $service->all();

        return $jsonapirequesthelper->getResponse($objects);
    }

    public function getRelatedCollection(ServerRequestInterface $request, string $parent_type, int $parent_id, string $resource_alias)
    {
        // find parent resource
        $parent_schema_class = static::AVAIBLE_RESOURCES[$parent_type];
        $parent_schema = new $parent_schema_class();
        $relation = $parent_schema->relationshipsSchema[$resource_alias];

        // set related child schema via reation alias
        $schema = new $relation['schema']();
        $resource_type = $schema->getResourceType();

        // set child model
        $parent_model_class = $parent_schema->getModelName();
        $parent_model = $parent_model_class::findOrFail($parent_id);
        $builder = $parent_model->$resource_alias();

        $jsonapirequesthelper = new JsonApiRequestHelper($request, $this->getSchema($resource_type), $parent_id, $resource_alias);
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($jsonapirequesthelper);
        $objectbuilder->buildEloquentBuilder($builder);
        $objects = $objectbuilder->getObjects();

        return $jsonapirequesthelper->getResponse($objects);
    }

    public function get(ServerRequestInterface $request, string $resource_type, int $resource_id)
    {
        $jsonapirequesthelper = new JsonApiRequestHelper($request, $this->getSchema($resource_type), $resource_id);

        $service = $jsonapirequesthelper->getObjectService();
        $object = $service->get();

        return $jsonapirequesthelper->getResponse($object);
    }

    public function create(ServerRequestInterface $request, string $resource) {
        $resourceArray = $request->all();
        $class = $this->resource2class($resource);
        $object = new $class();
        $object->fill($resourceArray);
        $object->save();
        $result = $this->jsonApiTransform->transform($class, $object, '');

        return $result;
    }

    public function update(ServerRequestInterface $request, string $resource_type, int $resource_id)
    {
        dd($request->getParsedBody());
        $jsonapirequest = new JsonApiRequestHelper($resource_type, $request, $resource_id);
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($jsonapirequest);
        $responses = $jsonapirequest->getResponses();

        // save object
        $object = $objectbuilder->getObject($resource_id);
        $object->fill($request->getParsedBody()['data']['attributes']);
        var_dump($object->save());

        return $responses->getContentResponse($object);

        $resourceArray = $request->all();
        $class = $this->resource2class($resource);
        $object = new $class();
        $object = $object->findOrFail($resource_id);
        $object->fill($resourceArray);
        $object->save();

        $result = $this->jsonApiTransform->transform($class, $object, '');

        return $result;
    }

    public function delete(string $resource, int $resource_id)
    {
        $class = $this->resource2class($resource);
        $object = new $class();
        $object = $object->findOrFail($resource_id);
        $object->delete();

        return response(json_encode(['status' => 'success']), 200);
    }

    protected function getSchema(string $resource_type): string
    {
        if (!isset(static::AVAIBLE_RESOURCES[$resource_type]))
            throw new ResourceTypeNotFoundException($resource_type);
        return static::AVAIBLE_RESOURCES[$resource_type];
    }
}

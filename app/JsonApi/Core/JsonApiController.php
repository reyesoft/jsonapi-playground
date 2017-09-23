<?php

namespace App\JsonApi\Core;

use App\Http\JsonApiRequest;
use App\JsonApi\Helpers\ObjectsBuilder;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Psr\Http\Message\ServerRequestInterface;

abstract class JsonApiController extends BaseController
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

    public function getCollection(ServerRequestInterface $request, string $resource_type)
    {
        $jsonapirequest = new JsonApiRequest(static::AVAIBLE_RESOURCES, $resource_type, $request);
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($jsonapirequest);
        $responses = $jsonapirequest->getResponses();

        return $responses->getContentResponse($objectbuilder->getObjects());
    }

    public function getRelatedCollection(ServerRequestInterface $request, string $related_resource_type, $id, string $resource_type)
    {
        $jsonapirequest = new JsonApiRequest(static::AVAIBLE_RESOURCES, $resource_type, $request);
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($jsonapirequest);

        $objectbuilder->getEloquentBuilder()->where('author_id', '=', $id);

        $responses = $jsonapirequest->getResponses();

        return $responses->getContentResponse($objectbuilder->getObjects());
    }

    public function get(ServerRequestInterface $request, string $resource_type, int $resource_id)
    {
        $jsonapirequest = new JsonApiRequest(static::AVAIBLE_RESOURCES, $resource_type, $request);
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($jsonapirequest);
        $responses = $jsonapirequest->getResponses();

        return $responses->getContentResponse($objectbuilder->getObject($resource_id));
    }

    public function create(Request $request, string $resource) {
        $resourceArray = $request->all();
        $class = $this->resource2class($resource);
        $object = new $class();
        $object->fill($resourceArray);
        $object->save();
        $result = $this->jsonApiTransform->transform($class, $object, '');

        return $result;
    }

    public function update(Request $request, string $resource, int $resource_id)
    {
        $jsonapirequest = new JsonApiRequest($resource_type, $request, $resource_id);
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($jsonapirequest);
        $responses = $jsonapirequest->getResponses();

        // save object
        $object = $objectbuilder->getObject($resource_id);
        $object->fill($request->getParsedBody()['data']['attributes']);
        var_dump($object->save());

        return $responses->getContentResponse($object);
    }

    public function delete(string $resource, int $resource_id)
    {
        $class = $this->resource2class($resource);
        $object = new $class();
        $object = $object->findOrFail($resource_id);
        $object->delete();

        return response(json_encode(['status' => 'success']), 200);
    }
}

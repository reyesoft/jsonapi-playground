<?php

namespace App\JsonApi\Core;

use App\Http\JsonApiRequest;
use App\JsonApi\ObjectsBuilder;
use App\Neomerx\Models\Store;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Psr\Http\Message\ServerRequestInterface;

abstract class JsonApiController extends BaseController
{
    public function getCollection(ServerRequestInterface $request, string $resource_type)
    {
        $jsonapirequest = new JsonApiRequest($resource_type, $request);
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($jsonapirequest);
        $responses = $jsonapirequest->getResponses();

        return $responses->getContentResponse($objectbuilder->getObjects());
    }

    public function getRelatedCollection(ServerRequestInterface $request, string $related_resource_type, $id, string $resource_type)
    {
        $jsonapirequest = new JsonApiRequest($resource_type, $request);
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($jsonapirequest);

        $objectbuilder->getEloquentBuilder()->where('author_id', '=', $id);

        $responses = $jsonapirequest->getResponses();

        return $responses->getContentResponse($objectbuilder->getObjects());
    }

    public function get(ServerRequestInterface $request, string $resource_type, int $resource_id)
    {
        $jsonapirequest = new JsonApiRequest($resource_type, $request, $resource_id);
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($jsonapirequest);
        $responses = $jsonapirequest->getResponses();

        return $responses->getContentResponse($objectbuilder->getObject($resource_id));
    }

    public function store(Request $request, string $resource) {
        $resourceArray = $request->all();
        $class = $this->resource2class($resource);
        $object = new $class();
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

    public function update(Request $request, string $resource, int $resource_id)
    {
        $resourceArray = $request->all();
        $class = $this->resource2class($resource);
        $object = new $class();
        $object = $object->findOrFail($resource_id);
        $object->fill($resourceArray);
        $object->save();

        $result = $this->jsonApiTransform->transform($class, $object, '');

        return $result;
    }

    private function resource2ModelInstance($resource_name): \Illuminate\Database\Eloquent\Model {
        $model_class_name = '\\App\\' . studly_case(str_singular($resource_name));
        if (!class_exists($model_class_name))
            throw new \Exception('No se encontró el recurso `' . $resource_name . '`.');
        return new $model_class_name();
    }

    private function resource2SchemaInstance($resource_name): \App\JsonApi\SchemaProvider {
        $model_class_name = '\\App\\JsonApi\\Schemas\\' . studly_case(str_singular($resource_name)) . 'Schema';
        if (!class_exists($model_class_name))
            throw new \Exception('No se encontró el recurso `' . $resource_name . '`.');
        return new $model_class_name();
    }
}

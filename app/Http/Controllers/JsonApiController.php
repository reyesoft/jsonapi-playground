<?php

namespace App\Http\Controllers;

use App\JsonApi\Core\JsonApiController as BaseJsonApiController;
use App\JsonApi\Schemas\AuthorSchema;
use App\JsonApi\Schemas\BookSchema;
use App\JsonApi\Schemas\ChapterSchema;
use App\JsonApi\Schemas\PhotoSchema;
use App\JsonApi\Schemas\SerieSchema;
use App\JsonApi\Schemas\StoreSchema;
use Illuminate\Http\Request;

class JsonApiController extends BaseJsonApiController
{
    const AVAIBLE_RESOURCES = [
        'authors' => AuthorSchema::class,
        'photos' => PhotoSchema::class,
        'books' => BookSchema::class,
        'chapters' => ChapterSchema::class,
        'series' => SerieSchema::class,
        'stores' => StoreSchema::class,
    ];

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

    /**
     * @deprecated use AVAIBLES_RESOURCES now
     */
    private function resource2class(string $resource): string
    {
        throw new \Exception('resource2class deprectad');
        /*$arrayKeyValue = [
            'books' => Book::class,
            'authors' => Author::class,
            'chapters' => Chapter::class,
            'series' => Serie::class,
            'stores' => Store::class,
            'photos' => Photo::class,
        ];

        return $arrayKeyValue[$resource];*/
    }
}

<?php

namespace App\Http\Controllers;

use App\AuthorSchema;
use App\BookSchema;
use App\ChapterSchema;
use App\JsonApi\Http\Controllers\JsonApiGlobalController;
use App\PhotoSchema;
use App\SerieSchema;
use App\StoreSchema;
use Illuminate\Http\Request;

class JsonApiController extends JsonApiGlobalController
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

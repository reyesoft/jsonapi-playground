<?php

namespace App\Http\Controllers;

use App\Neomerx\Models\Author;
use App\Neomerx\Models\Book;
use App\Neomerx\Models\Chapter;
use App\Neomerx\Models\JsonApiTransform;
use App\Neomerx\Models\Photo;
use App\Neomerx\Models\Serie;
use App\Neomerx\Models\Store;
use Illuminate\Http\Request;

class NeomerxMethod extends Controller
{
    protected $jsonApiTransform;

    public function __construct()
    {
        $this->jsonApiTransform = new JsonApiTransform();
    }

    public function getAll(Request $request, string $resource)
    {
        $include = $this->getInclude($request);
        $objects = $this->mapResult($resource);
        $object = $this->mapResource($resource);
        $result = $this->jsonApiTransform->transform($object, $objects, '', $include);

        return $result;
    }

    public function get(Request $request, string $resource, int $resource_id)
    {
        $include = $this->getInclude($request);
        $objects = $this->mapResult($resource, $resource_id);
        $object = $this->mapResource($resource);
        $result = $this->jsonApiTransform->transform($object, $objects, '', $include);

        return $result;
    }

    public function store(Request $request,string $resource){

        $resourceArray = $request->all();
        $class = $this->mapResource($resource);
        $object = new $class;
        $object->fill($resourceArray);
        $object->save();
        $result = $this->jsonApiTransform->transform($class, $object, '');

        return $result;
    }

    public function delete(string $resource, int $resource_id)
    {
        $class = $this->mapResource($resource);
        $object = new $class;
        $object = $object->findOrFail($resource_id);
        $object->delete();

        return response(json_encode(['status' => 'success']), 200);
    }

    public function update(Request $request, string $resource,int $resource_id)
    {
        $resourceArray = $request->all();
        $class = $this->mapResource($resource);
        $object = new $class;
        $object = $object->findOrFail($resource_id);
        $object->fill($resourceArray);
        $object->save();

        $result = $this->jsonApiTransform->transform($class, $object, '');

        return $result;
    }

    /**
     * Refactorizar en otro lado
     *
     */

    private function mapResult(string $resource, int $resouce_id = 0)
    {
        $resource = $this->mapResource($resource);

        if ($resouce_id === 0) {
            $objects = (new $resource)->paginate();
        } else {
            $objects = (new $resource)->findOrFail($resouce_id);
        }

        return $objects;
    }

    private function mapResource(string $resource): string
    {
        $arrayKeyValue = [
            'books' => Book::class,
            'authors' => Author::class,
            'chapters' => Chapter::class,
            'series' => Serie::class,
            'stores' => Store::class,
            'photos' => Photo::class,
        ];

        $value = $arrayKeyValue[$resource];

        return $value;
    }

    private function getInclude(Request $request): string
    {
        $include = '';
        if (isset($request['include'])) {
            $include = $request['include'];
        }

        return $include;
    }
}

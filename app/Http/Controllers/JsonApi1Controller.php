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
use Neomerx\JsonApi\Factories\Factory;
use Psr\Http\Message\ServerRequestInterface;

class JsonApi1Controller extends Controller
{
    protected $jsonApiTransform;

    public function __construct()
    {
        $this->jsonApiTransform = new JsonApiTransform();
    }

    private function getModel($class_name): \Illuminate\Database\Eloquent\Model {
        return new $class_name();
    }

    public function getAll(ServerRequestInterface $request, string $resource_name)
    {
        $include = $this->getInclude($request);
        $class_name = $this->resource2class($resource_name);
        $model = $this->getModel($class_name);
        $objects = $model->paginate();

        return $this->jsonApiTransform->transform($class_name, $objects, '', $include, $request);
    }

    public function get(Request $request, string $resource, int $resource_id)
    {
        //$include = $this->getInclude($request);
        $include = '';
        $class_name = $this->resource2class($resource);
        $objects = (new $class_name())->findOrFail($resource_id);
        $result = $this->jsonApiTransform->transform($class_name, $objects, '', $include);

        return $result;
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

    private function resource2class(string $resource): string
    {
        $arrayKeyValue = [
            'books' => Book::class,
            'authors' => Author::class,
            'chapters' => Chapter::class,
            'series' => Serie::class,
            'stores' => Store::class,
            'photos' => Photo::class,
        ];

        return $arrayKeyValue[$resource];
    }

    private function getInclude(ServerRequestInterface $request): string
    {
        $factory = new Factory();
        $parameters = $factory->createQueryParametersParser()->parse($request);

        if ($parameters->getIncludePaths())
            return implode(',', $parameters->getIncludePaths());
        else
            return '';
    }
}

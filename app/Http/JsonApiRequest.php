<?php

namespace App\Http;

use App\JsonApi\Helpers\ParametersChecker;
use App\JsonApi\SchemaProvider;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Neomerx\JsonApi\Factories\Factory;
use Psr\Http\Message\ServerRequestInterface;

class JsonApiRequest
{
    /**
     * @var SchemaProvider
     */
    protected $schema;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var ServerRequestInterface
     */
    protected $factory = null;

    public function __construct(string $resource_type, ServerRequestInterface $request, int $id = 0) {
        $this->schema = $this->resource2SchemaInstance($resource_type);
        $this->request = $request;
        $this->id = $id;

        // check parameters
        $parameters = $this->getRequestParameters();
        ParametersChecker::checkOrFail($this->schema, $parameters, $id ? false : true);

        $this->parsedparameters = new JsonApiParameters($parameters);
    }

    public function getSchema(): SchemaProvider {
        return $this->schema;
    }

    public function getResponses(): AppResponses {
        return AppResponses::instance($this->request);
    }

    public function getRequest(): ServerRequestInterface {
        return $this->request;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getModel(): \Illuminate\Database\Eloquent\Model {
        return $this->resource2ModelInstance($this->getSchema()->getResourceType());
    }

    public function getFactory(): Factory {
        if ($this->factory === null) {
            $this->factory = new Factory();
        }

        return $this->factory;
    }

    public function getParsedParameters(): JsonApiParameters {
        return $this->parsedparameters;
    }

    public function getRequestParameters(): EncodingParameters {
        $factory = new Factory();

        return $factory->createQueryParametersParser()->parse($this->request);
    }

    private function resource2SchemaInstance($resource_name): SchemaProvider {
        $model_class_name = '\\App\\JsonApi\\Schemas\\' . studly_case(str_singular($resource_name)) . 'Schema';
        if (!class_exists($model_class_name))
            throw new \Exception('No se encontró el recurso `' . $resource_name . '`.');
        return new $model_class_name();
    }

    private function resource2ModelInstance($resource_name): \Illuminate\Database\Eloquent\Model {
        $model_class_name = '\\App\\' . studly_case(str_singular($resource_name));
        if (!class_exists($model_class_name))
            throw new \Exception('No se encontró el recurso `' . $resource_name . '`.');
        return new $model_class_name();
    }
}

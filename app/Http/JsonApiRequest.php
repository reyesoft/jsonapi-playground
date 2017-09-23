<?php

namespace App\Http;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Helpers\ParametersChecker;
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
     * @var array
     */
    protected $encoder = [];

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

    public function __construct(array $avaiable_resources, string $resource_type, ServerRequestInterface $request, int $id = 0) {
        $this->schema = $this->resource2SchemaInstance($resource_type, $avaiable_resources);
        $this->buildEncoder();
        $this->request = $request;
        $this->id = $id;

        // check parameters
        $parameters = $this->getRequestParameters();
        ParametersChecker::checkOrFail($this->schema, $parameters, $id ? false : true);

        $this->parsedparameters = new JsonApiParameters($parameters);
    }

    private function buildEncoder() {
        // add this schema to encoder
        $this->encoder = [
            $this->schema->getModelName() => get_class($this->schema),
        ];
        // add related schemas to encoder
        foreach ($this->schema->relationshipsSchema as $relation_alias => $relation_schema) {
            $schema_class = $relation_schema['schema'];
            $model_class = $schema_class::$model;
            $this->encoder[$model_class] = $schema_class;
        }
    }

    public function getResponses(): AppResponses {
        return AppResponses::instance($this->request, $this->encoder);
    }

    public function getRequest(): ServerRequestInterface {
        return $this->request;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getSchema(): SchemaProvider {
        return $this->schema;
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

    private function resource2SchemaInstance($resource_name, array $avaiable_resources): SchemaProvider {
        if (!isset($avaiable_resources[$resource_name]))
            throw new \Exception('No se encontró el recurso `' . $resource_name . '`.');
        $ret = new $avaiable_resources[$resource_name]();

        return $ret;
    }
}

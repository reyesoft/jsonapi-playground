<?php

namespace App\JsonApi\Http;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Helpers\ParametersChecker;
use App\JsonApi\Services\EloquentObjectService;
use App\JsonApi\Services\ObjectService;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Neomerx\JsonApi\Factories\Factory;
use Psr\Http\Message\ServerRequestInterface;

class JsonApiRequestHelper
{
    protected $type = '';
    protected $related_alias = '';

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

    protected $appresponses = null;

    public function __construct($request, string $schema, int $id = 0, string $related_alias = '') {
        $this->request = $request;
        $isACollection = (!$id || $related_alias);
        $this->id = $id;
        $this->setSchemaAndEval($schema, $isACollection);
    }

    private function setSchema(SchemaProvider $schema) {
        $this->schema = $schema;
    }

    private function setSchemaAndEval(string $schema_class_name, bool $isACollection) {
        $this->setSchema(new $schema_class_name());
        $this->buildEncoder();
        $parameters = $this->getRequestParameters();
        ParametersChecker::checkOrFail($this->schema, $parameters, $isACollection);
        $this->parsedparameters = new JsonApiParameters($parameters);
    }

    public function getType() {
        return $this->type;
    }

    protected function setType($type) {
        $this->type = $type;
    }

    public function getRelatedAlias() {
        return $this->related_alias;
    }

    protected function setRelatedAlias($related_alias) {
        $this->related_alias = $related_alias;
    }

    private function buildEncoder() {
        // add this schema to encoder
        $this->encoder = [
            $this->schema->getModelName() => get_class($this->schema),
        ];

        // add related schemas to encoder
        foreach ($this->schema::getRelationshipsSchema() as $relation_alias => $relation_schema) {
            $schema_class = $relation_schema['schema'];
            $model_class = $schema_class::$model;
            $this->encoder[$model_class] = $schema_class;
        }
    }

    public function getResponse($object_or_objects): JsonApiResponse {
        $responses = $this->getAppResponses();
        switch($this->request->getMethod()) {
            case 'POST':    // create
                return $responses->getCreatedResponse($object_or_objects);
            case 'DELETE':
                return $responses->getCodeResponse(200);
            default:
                return $responses->getContentResponse($object_or_objects);
        }
    }

    public function getAppResponses(): AppResponses {
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

    public function getObjectService(): ObjectService {
        $service = $this->getSchema()->getObjectService();
        if ($service) {
            return new $service($this);
        } else {
            return new EloquentObjectService($this);
        }
    }
}

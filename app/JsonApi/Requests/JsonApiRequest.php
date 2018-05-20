<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Requests;

use App\JsonApi\Core\Action;
use App\JsonApi\Core\QueryParser;
use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Exceptions\ResourceTypeNotFoundException;
use App\JsonApi\Http\JsonApiParameters;
use Psr\Http\Message\ServerRequestInterface;

abstract class JsonApiRequest
{
    private $encoder;
    protected $data;
    protected $action = '';
    protected $schema;
    protected $schema_class = '';
    protected $available_schemas = [];
    protected $resource_type = '';
    /**
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * @var JsonApiParameters
     */
    protected $parameters;

    public function __construct(ServerRequestInterface $request, array $params, array $available_s)
    {
        $this->request = $request;
        $this->available_schemas = $available_s;

        $this->readParams($params);
        $this->setSchemas($available_s);
    }

    protected function setSchemas(array $available_s): void
    {
        if (!isset($available_s[$this->resource_type])) {
            throw new ResourceTypeNotFoundException($this->resource_type);
        }

        $this->schema_class = $available_s[$this->resource_type];
        $this->schema = new $available_s[$this->resource_type]();
    }

    public function getAvailableSchemas(): array
    {
        return $this->available_schemas;
    }

    /**
     * @throws \Exception
     */
    public function getAction(): Action
    {
        if (!preg_match('/([A-Z][a-z]+)Request$/', static::class, $matches)) {
            throw new \Exception('Action cant be determined on ' . static::class . '.');
        }

        return new Action(
            $matches[1],
            $this->getSchema(),
            '',
            $this->getData(),
            $this->getParameters()
        );
    }

    protected function readParams(array $params): void
    {
        throw new \Exception('readParams not defined.');
    }

    public function getSchema(): SchemaProvider
    {
        return $this->schema;
    }

    public function getParameters(): JsonApiParameters
    {
        if ($this->parameters === null) {
            $parameters = new QueryParser($this->request->getQueryParams());
            $parameters->checkQuery();
            $this->parameters = new JsonApiParameters($parameters);
        }

        return $this->parameters;
    }

    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @todo this is part of answer? we should have a class for that
     */
    public function getEncoder()
    {
        if (!$this->encoder) {
            $this->buildEncoder();
        }

        return $this->encoder;
    }

    private function buildEncoder(): void
    {
        // add this schema to encoder
        $this->encoder = [
            $this->getSchema()->getModelName() => get_class($this->getSchema()),
        ];

        // add related schemas to encoder
        foreach ($this->getSchema()::getRelationshipsSchema() as $relation_alias => $relation_schema) {
            $schema_class = $relation_schema['schema'];
            $model_class = $schema_class::$model;
            $this->encoder[$model_class] = $schema_class;
        }
    }

    public function getData()
    {
        if ($this->data === null) {
            $this->data = $this->request->getParsedBody();
        }

        return $this->data;
    }

    public function getDataIncluded(): array
    {
        return $this->getData()['included'];
    }

    public function hasIncludedData(): bool
    {
        return isset($this->data['included']);
    }

    public function replaceIdOnRelationships(array $new_ids): void
    {
        foreach ($this->data['data']['relationships'] as &$relation) {
            if (!$relation['data'] || count($relation['data']) === 0) {
                continue;
            }

            if (isset($relation['data']['type'])) {
                // hasOne
                $this->replaceIdOnRelation($relation['data'], $new_ids);
                continue;
            }

            foreach ($relation['data'] as &$relation2) {
                $this->replaceIdOnRelation($relation2, $new_ids);
            }
        }
    }

    private function replaceIdOnRelation(array &$relation, array $new_ids): void
    {
        if (!isset($relation['type'])) {
            return;
        }

        if (isset($new_ids[$relation['type']][$relation['id']])) {
            $relation['id'] = $new_ids[$relation['type']][$relation['id']];
        }
    }
}

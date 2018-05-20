<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Requests;

use App\JsonApi\Exceptions\ResourceTypeNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class RelatedRequest extends JsonApiRequest
{
    protected $parent_schema;
    protected $parent_type = '';
    protected $parent_id = '';
    protected $resource_alias = '';

    public function __construct(ServerRequestInterface $request, array $params, array $available_s)
    {
        parent::__construct($request, $params, $available_s);

        $this->parent_schema = $available_s[$this->parent_type];
    }

    protected function readParams(array $params): void
    {
        list($this->parent_type, $this->parent_id, $this->resource_alias) = array_slice($params, -3);
    }

    protected function setSchemas(array $available_s): void
    {
        if (!isset($available_s[$this->parent_type])) {
            throw new ResourceTypeNotFoundException($this->parent_type);
        }

        $parent_schema_class = $available_s[$this->getParentType()];
        $parent_schema = new $parent_schema_class();
        $relation = $parent_schema::getRelationshipsSchema()[$this->getResourceAlias()];

        $schema = new $relation['schema']();
        $this->resource_type = $schema->getResourceType();

        $this->schema_class = $available_s[$this->resource_type];
        $this->schema = new $available_s[$this->resource_type]();
    }

    public function getParentSchemaClass(): string
    {
        return $this->parent_schema;
    }

    public function getParentSchema()
    {
        return new $this->parent_schema();
    }

    public function getParentType(): string
    {
        return $this->parent_type;
    }

    public function getParentId(): string
    {
        return $this->parent_id;
    }

    public function getResourceAlias(): string
    {
        return $this->resource_alias;
    }
}

<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Helpers;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Http\JsonApiParameters;
use App\JsonApi\Http\JsonApiRequestHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ObjectsBuilder
{
    /**
     * @var SchemaProvider
     */
    protected $schema;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var JsonApiParameters
     */
    protected $params;

    /**
     * @var Builder
     */
    protected $builder = null;

    public function __construct(SchemaProvider $schema, Model $model, JsonApiParameters $params)
    {
        $this->schema = $schema;
        $this->model = $model;
        $this->params = $params;
    }

    public static function createViaJsonApiRequest(JsonApiRequestHelper $jsonapirequest)
    {
        return new static (
            $jsonapirequest->getSchema(),
            $jsonapirequest->getSchema()->getModelInstance(),
            $jsonapirequest->getParsedParameters()
        );
    }

    public function getObjects()
    {
        $builder = $this->getEloquentBuilder();

        // paginate (check we use `simplePaginate` over `paginate` preventing extra a SQL request)
        $columns = ['*'];

        // mover a eoquent object service?
        $this->schema->modelBeforeGet($builder);

        return $builder
            ->simplePaginate($this->params->getPageSize(), $columns, null, $this->params->getPageNumber())
            ->items();
    }

    public function getObject(string $resource_id)
    {
        $builder = $this->getEloquentBuilder();

        // mover a eoquent object service?
        $this->schema->modelBeforeGet($builder);

        return $builder->findOrFail($resource_id);
    }

    /**
     * @return Builder|Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function buildEloquentBuilder($builder = null)
    {
        if ($builder === null) {
            $this->builder = $this->schema->getModelInstance()->newQueryWithoutScopes();
        } else {
            $this->builder = $builder;
        }

        $this->builder->with($this->schema->getWithForEloquent($this->params->getIncludePaths()));

        // filters
        $filteringParameters = $this->params->getFilteringParameters();
        foreach ($filteringParameters as $field => $value) {
            if (empty($value)) {
                continue;
            }

            $filtertype = $this->schema->getFilterType($field);
            switch ($filtertype) {
                case 'like':
                    $this->builder->where($field, $filtertype, '%' . $value . '%');
                    break;
                case 'date':
                    $this->builder
                        ->where($field, '>=', $value['since'])
                        ->where($field, '<=', $value['until']);
                    break;
                default:
                    $this->builder->where($field, $filtertype, $value);
            }
        }
    }

    /**
     * @return Builder|Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    protected function getEloquentBuilder()
    {
        if ($this->builder === null) {
            $this->buildEloquentBuilder();
        }

        return $this->builder;
    }
}

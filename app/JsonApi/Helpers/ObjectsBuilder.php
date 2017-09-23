<?php

namespace App\JsonApi\Helpers;

use App\Http\JsonApiParameters;
use App\Http\JsonApiRequest;
use App\JsonApi\Core\SchemaProvider;
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

    public function __construct(SchemaProvider $schema, Model $model, JsonApiParameters $params) {
        $this->schema = $schema;
        $this->model = $model;
        $this->params = $params;
    }

    public static function createViaJsonApiRequest(JsonApiRequest $jsonapirequest) {
        $schema = $jsonapirequest->getSchema();
        $model = $jsonapirequest->getSchema()->getModelInstance();
        $params = $jsonapirequest->getParsedParameters();

        return new static ($schema, $model, $params);
    }

    public function getObject(int $resource_id) {
        $builder = $this->getEloquentBuilder();

        return $builder->findOrFail($resource_id);
    }

    public function getObjects() {
        $builder = $this->getEloquentBuilder();

        // paginate (check we use `simplePaginate` over `paginate` preventing extra a SQL request)
        $columns = ['*'];

        return  $builder
                    ->simplePaginate($this->params->getPageSize(), $columns, null, $this->params->getPageNumber())
                    ->items();
    }

    public function getEloquentBuilder(): Builder {
        if ($this->builder === null) {
            $this->builder = $this->model->newQueryWithoutScopes();

            // with
            $this->builder->with($this->schema->getWithForEloquent($this->params->getIncludePaths()));

            // filters
            $filteringParameters = $this->params->getFilteringParameters();
            foreach($filteringParameters as $field => $value) {
                if (empty($value))
                    continue;

                $filtertype = $this->schema->getFilterType($field);
                switch($filtertype) {
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

        return $this->builder;
    }
}

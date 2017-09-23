<?php

namespace App\JsonApi\Core;

use Neomerx\JsonApi\Contracts\Schema\SchemaFactoryInterface;
use Neomerx\JsonApi\Factories\Factory;
use Neomerx\JsonApi\Schema\SchemaProvider as NeomerxSchemaProvider;

abstract class SchemaProvider extends NeomerxSchemaProvider
{
    /**
     * Like books.
     *
     * @var string
     */
    protected $resourceType;

    /**
     * Like Book::class;.
     *
     * @var string
     */
    public static $model = 'undefined';

    /**
     * Like `/authors`.
     *
     * @var string
     */
    protected $selfSubUrl;

    // used by factory (get includes, for example)
    protected $isPaginable = true;
    protected $filterBySchema = [];
    protected $relationshipsSchema = [];

    public function __construct(SchemaFactoryInterface $factory = null) {
        $this->selfSubUrl = '/' . $this->resourceType;

        // include params permited
        foreach ($this->relationshipsSchema as $type => $relationshipSchema) {
            $this->includePaths[] = $type;
        }

        if ($factory == null) {
            $factory = new Factory();
        }

        return parent::__construct($factory);
    }

    public function getWithForEloquent(array $include_request = []): array {
        $ret = [];
        foreach ($this->relationshipsSchema as $type => $relationshipSchema) {
            if ($relationshipSchema['hasMany']) {
                // hasMany
                $ret[] = $type;
            }
            elseif (in_array($type, $include_request)) {
                // without s (belongTo relationship)
                // $ret[] = substr($type, 0, -1);
                $ret[] = $type;
            }
        }

        return $ret;
    }

    public function getModelName() {
        return static::$model;
    }

    public function getModelInstance() {
        $model = static::$model;

        return new $model();
    }

    public function isPaginable(): bool {
        return $this->isPaginable;
    }

    public function getFilterType(string $field): string {
        return $this->filterBySchema[$field]['type'];
    }

    public function getFilterBySchemaArray(): array
    {
        return array_keys($this->filterBySchema);
    }

    protected function buildRelationship($object, array $includeList, $modelClass, $singularType)
    {
        if (isset($includeList[$singularType])) {
            $relation = $object->$singularType;
        } else {
            $modelFieldId = $singularType . '_id';
            if ($object->$modelFieldId != 0)  {
                $relation = new $modelClass();
                $relation->id = $object->$modelFieldId;
            } else {
                // no element on this hasOne relationship
                // http://jsonapi.org/format/#fetching-resources-responses
                $relation = null;
            }
        }

        return [self::DATA => $relation];
    }
}

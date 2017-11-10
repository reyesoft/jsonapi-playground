<?php

namespace App\JsonApi\Core;

use App\JsonApi\Services\ObjectService;
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

    /**
     * Like `App\MyObjectService`.
     *
     * @var ObjectService
     */
    protected $objectservice = '';

    // used by factory (get includes, for example)
    protected $isPaginable = true;
    protected static $attributes = [];
    protected static $relationships = [];

    public function __construct(SchemaFactoryInterface $factory = null) {
        $this->selfSubUrl = '/' . $this->resourceType;

        // include params permited
        foreach (static::$relationships as $type => $relationshipSchema) {
            $this->includePaths[] = $type;
        }

        if ($factory == null) {
            $factory = new Factory();
        }

        return parent::__construct($factory);
    }

    public function getWithForEloquent(array $include_request = []): array {
        $ret = [];
        foreach (static::$relationships as $type => $relationshipSchema) {
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

    public function getModelName(): string {
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
        return static::attributes[$field]['type'];
    }

    public function getFiltersArray(): array
    {
        $ret = array_filter(static::$attributes, function ($value) {
            return isset($value['filter']);
        });

        return array_keys($ret);
    }

    public function getSortArray(): array
    {
        $ret = array_filter(static::$attributes, function ($value) {
            return isset($value['sort']);
        });

        return array_keys($ret);
    }

    public function getObjectService(): string
    {
        return $this->objectservice;
    }

    public function getAttributesSchema()
    {
        return static::$attributes;
    }

    public static function getRelationshipsSchema(): array
    {
        return static::$relationships;
    }

    public function getId($object)
    {
        return $object->id;
    }

    public function getAttributes($object)
    {
        $ret = [];
        foreach (static::$attributes as $key => $value) {
            $ret[$key] = $object->{$key};
        }

        return $ret;
    }
}

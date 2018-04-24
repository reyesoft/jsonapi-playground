<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Core;

use App\JsonApi\Policy;
use App\JsonApi\Services\DataService;
use Neomerx\JsonApi\Contracts\Schema\SchemaFactoryInterface;
use Neomerx\JsonApi\Factories\Factory;
use Neomerx\JsonApi\Schema\BaseSchema;

abstract class SchemaProvider extends BaseSchema
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
     * @var string
     */
    public static $policy = Policy::class;

    /**
     * Like `/authors`.
     *
     * @var string
     */
    protected $selfSubUrl;

    /**
     * Like `App\MyObjectService`.
     *
     * @var DataService
     */
    protected $object_service;

    // used by factory (get includes, for example)
    protected $isPaginable = true;
    protected static $attributes = [];
    protected static $relationships = [];

    public function __construct(SchemaFactoryInterface $factory = null)
    {
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

    public function getWithForEloquent(array $include_request = []): array
    {
        $ret = [];
        foreach (static::$relationships as $type => $relationshipSchema) {
            if ($relationshipSchema['hasMany']) {
                // hasMany
                $ret[] = $type;
            } elseif (in_array($type, $include_request)) {
                // without s (belongTo relationship)
                $ret[] = $type;
            }
        }

        return $ret;
    }

    public function getModelName(): string
    {
        return static::$model;
    }

    public function getModelInstance()
    {
        $model = static::$model;

        return new $model();
    }

    public function isPaginable(): bool
    {
        return $this->isPaginable;
    }

    public function getFilterType(string $field): string
    {
        return static::$attributes[$field]['type'];
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

    public function getObjectService(): DataService
    {
        return $this->object_service;
    }

    public function getAttributesSchema()
    {
        return static::$attributes;
    }

    public static function getRelationshipsSchema(): array
    {
        return static::$relationships;
    }

    public function getId($object): ?string
    {
        return (string) $object->id;
    }

    public function getAttributes($object, ?array $fieldKeysFilter = null): ?array
    {
        $ret = [];
        foreach (static::$attributes as $key => $value) {
            $ret[$key] = $object->{$key};
        }

        return $ret;
    }

    public function getPolicy(): Policy
    {
        $policy_class = static::$policy;

        return new $policy_class();
    }

    public static function filterAttributesWithCru(array $data, string $cru): array
    {
        $ret = [];
        $data_attributes = $data['data']['attributes'];
        foreach ($data_attributes as $key => $value) {
            // is on attributes array?
            if (!isset(static::$attributes[$key])) {
                // don't have permission to $cru
                continue;
            }

            // its on attributes array but, has correct crud?
            if (isset(static::$attributes[$key]['cru']) && strpos(static::$attributes[$key]['cru'], $cru) === false) {
                // don't have permission to $cru
                continue;
            }

            $ret[$key] = $value;
        }

        $data['data']['attributes'] = $ret;

        return $data;
    }

    public function modelBeforeSave($builder)
    {
        return $builder;
    }

    public function modelBeforeGet($builder)
    {
        return $builder;
    }
}

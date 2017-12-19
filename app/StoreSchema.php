<?php

namespace App;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Core\SchemaRelationsTrait;

class StoreSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'stores';
    public static $model = Store::class;

    protected static $relationships = [
        'photos' => [
            'schema' => PhotoSchema::class,
            'hasMany' => true,
        ],
        'books' => [
            'schema' => BookSchema::class,
            'hasMany' => true,
        ],
    ];

    public function getAttributes($object)
    {
        return [
            'name' => $object->name,
        ];
    }
}

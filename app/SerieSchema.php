<?php

namespace App;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Core\SchemaRelationsTrait;

class SerieSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'series';
    public static $model = Serie::class;

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

    public function getAttributes($object, ?array $fieldKeysFilter = null): ?array
    {
        return [
            'title' => $object->title,
        ];
    }
}

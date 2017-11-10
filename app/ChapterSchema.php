<?php

namespace App;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Core\SchemaRelationsTrait;

class ChapterSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'chapters';
    public static $model = Chapter::class;

    protected static $relationships = [
        'photos' => [
            'schema' => PhotoSchema::class,
            'hasMany' => true,
        ],
        'books' => [
            'type' => 'books',
            'schema' => BookSchema::class,
            'hasMany' => false,
        ],
    ];

    public function getAttributes($object)
    {
        return [
            'title' => $object->title,
            'ordering' => $object->ordering,
        ];
    }
}

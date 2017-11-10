<?php

namespace App;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Core\SchemaRelationsTrait;

class AuthorSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'authors';
    public static $model = Author::class;

    protected static $attributes = [
        'name' => [
            'filter' => 'like',
        ],
        'date_of_birth' => [],
        'date_of_death' => [],
    ];

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
}

<?php

namespace App;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Core\SchemaRelationsTrait;

class BookSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'books';
    public static $model = Book::class;

    protected static $attributes = [
        'title' => [
            'type' => 'like',
        ],
        'date_published' => [
            'type' => 'date',
        ],
        'isbn' => [],
    ];

    protected static $relationships = [
        'author' => [
            'schema' => AuthorSchema::class,
            'hasMany' => false,
        ],
        'chapters' => [
            'schema' => ChapterSchema::class,
            'hasMany' => true,
        ],
        'photos' => [
            'schema' => PhotoSchema::class,
            'hasMany' => true,
        ],
        'serie' => [
            'schema' => SerieSchema::class,
            'hasMany' => false,
        ],
        'stores' => [
            'schema' => StoreSchema::class,
            'hasMany' => true,
        ],
    ];
}

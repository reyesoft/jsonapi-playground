<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

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
        'isbn' => [
            'type' => 'number',
        ],
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
        'series' => [
            'schema' => SeriesSchema::class,
            'hasMany' => false,
        ],
        'stores' => [
            'schema' => StoreSchema::class,
            'hasMany' => true,
        ],
    ];
}

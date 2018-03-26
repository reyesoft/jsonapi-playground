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

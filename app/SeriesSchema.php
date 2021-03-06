<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App;

use Reyesoft\JsonApi\Core\SchemaProvider;

class SeriesSchema extends SchemaProvider
{
    protected $resourceType = 'series';
    public static $model = Series::class;

    protected static $attributes = [
        'title' => [
            'type' => 'like',
        ],
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

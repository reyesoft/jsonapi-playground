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

class ChapterSchema extends SchemaProvider
{
    protected $resourceType = 'chapters';
    public static $model = Chapter::class;
    public static $policy = ChapterPolicy::class;

    protected static $attributes = [
        'title' => [
            'type' => 'string',
        ],
        'ordering' => [
            'type' => 'number',
        ],
    ];

    protected static $relationships = [
        'photos' => [
            'schema' => PhotoSchema::class,
            'hasMany' => true,
        ],
        'book' => [
            'schema' => BookSchema::class,
            'hasMany' => false,
        ],
    ];
}

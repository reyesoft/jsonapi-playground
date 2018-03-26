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
        'book' => [
            'schema' => BookSchema::class,
            'hasMany' => false,
        ],
    ];

    public function getAttributes($object, ?array $fieldKeysFilter = null): ?array
    {
        return [
            'title' => $object->title,
            'ordering' => $object->ordering,
        ];
    }
}

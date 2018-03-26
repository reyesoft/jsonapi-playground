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

class PhotoSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'photos';
    public static $model = Photo::class;

    protected static $relationships = [
    ];

    public function getAttributes($object, ?array $fieldKeysFilter = null): ?array
    {
        return [
            'title' => $object->title,
            'uri' => $object->uri,
        ];
    }
}

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

class StoreSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'stores';
    public static $model = Store::class;

    protected static $attributes = [
        'name' => [
            'type' => 'like',
        ],
        'address' => [
            'cru' => 'cr',
        ],
        'created_by' => [
            'type' => 'enum',
            'cru' => 'r',
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

    public function modelBeforeSave($builder)
    {
        $builder->created_by = 2;

        return $builder;
    }
}

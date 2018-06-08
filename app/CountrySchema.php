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

class CountrySchema extends SchemaProvider
{
    protected $resourceType = 'countries';
    public static $service = CountryService::class;
    public static $model = Country::class;

    protected static $attributes = [
        'name' => [],
    ];

    public function getModelName(): string
    {
        return Country::class;
    }
}

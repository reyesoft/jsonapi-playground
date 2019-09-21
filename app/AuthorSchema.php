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
use Reyesoft\JsonApi\Eloquent\Filter\StringFilter;

class AuthorSchema extends SchemaProvider
{
    protected $resourceType = 'authors';
    public static $policy = AuthorPolicy::class;
    public static $model = Author::class;
    protected static $attributes = [];
    protected static $relationships = [];

    public static function boot(): void
    {
        self::addAttribute('name')
            ->setFilter(StringFilter::class)
            ->sortable(true);
        self::addAttribute('birthplace');
        self::addAttribute('date_of_birth');
        self::addAttribute('date_of_death');

        self::addRelationship(PhotoSchema::class, 'photos')
            ->setHasMany();
        self::addRelationship(BookSchema::class, 'books')
            ->setHasMany();
    }
}

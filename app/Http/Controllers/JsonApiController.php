<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use App\AuthorSchema;
use App\BookSchema;
use App\ChapterSchema;
use App\JsonApi\Http\Controllers\JsonApiGlobalController;
use App\PhotoSchema;
use App\SerieSchema;
use App\StoreSchema;

class JsonApiController extends JsonApiGlobalController
{
    public const AVAILABLE_RESOURCES = [
        'authors' => AuthorSchema::class,
        'photos' => PhotoSchema::class,
        'books' => BookSchema::class,
        'chapters' => ChapterSchema::class,
        'series' => SerieSchema::class,
        'stores' => StoreSchema::class,
    ];
}

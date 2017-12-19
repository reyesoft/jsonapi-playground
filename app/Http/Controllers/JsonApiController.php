<?php

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
    const AVAIBLE_RESOURCES = [
        'authors' => AuthorSchema::class,
        'photos' => PhotoSchema::class,
        'books' => BookSchema::class,
        'chapters' => ChapterSchema::class,
        'series' => SerieSchema::class,
        'stores' => StoreSchema::class,
    ];
}

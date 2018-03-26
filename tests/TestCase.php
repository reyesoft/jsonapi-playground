<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests;

use App\Author;
use App\Book;
use App\Chapter;
use App\Photo;
use App\Serie;
use App\Store;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $relations = [
        'authors' => [
            'books',
            'photos',
        ],
        'books' => [
            'author',
            'serie',
            'chapters',
            'stores',
            'photos',
        ],
        'chapters' => [
            'book',
            'photos',
        ],
        'series' => [
            'photos',
            'books',
        ],
        'stores' => [
            'photos',
            'books',
        ],
    ];

    protected $models = [
        'authors' => Author::class,
        'photos' => Photo::class,
        'books' => Book::class,
        'chapters' => Chapter::class,
        'series' => Serie::class,
        'stores' => Store::class,
    ];

    protected $alias = [
        'book' => 'books',
        'serie' => 'series',
        'author' => 'authors',
    ];
}

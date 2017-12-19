<?php

namespace Tests;

use App\Author;
use App\Book;
use App\Chapter;
use App\Photo;
use App\Serie;
use App\Store;
use Laravel\Lumen\Testing\TestCase as LumenTestCase;

abstract class TestCase extends LumenTestCase
{
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

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }
}

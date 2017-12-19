<?php

namespace Tests\Entrypoints;

use App\Author;
use App\Book;

class BooksTest extends BaseTestCase
{
    protected $layout = [
        'model' => Book::class,
        'type' => 'books',
        'attributes' => [
            'title',
            'date_published',
        ],
        'relationships' => [
            'author' => 'authors',
            'chapters' => 'chapters',
            'photos' => 'photos',
            'serie' => 'series',
            'stores' => 'stores',
        ],
    ];

    public function testBookIndex()
    {
        $this->callGet('/v2/books/');
        $this->assertResponseOk();
    }

    public function testBookCreate()
    {
        $resource = $this->newResource();

        // with author, ok
        $author = Author::first();
        $resource['data']['relationships']['author']['data'] = ['id' => $author->id, 'type' => 'authors'];
        unset($resource['data']['relationships']['serie']);
        $this->callPost('/v2/books/', $resource);
        $this->assertResponseStatus(201);

        $result = json_decode($this->response->getContent(), true);
        $this->assertEquals($resource['data']['attributes']['title'], $result['data']['attributes']['title']);

        return $result['data']['id'];
    }

    public function testBookCreateWithoutRelatedAuthor()
    {
        $resource = $this->newResource();

        unset($resource['data']['relationships']['author']);
        $this->callPost('/v2/books/', $resource);
        $this->assertResponseJsonApiError(403);
    }
}

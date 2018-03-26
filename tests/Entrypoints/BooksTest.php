<?php

namespace Tests\Entrypoints;

use App\Author;
use App\Book;
use App\Store;

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

    public function testBookIndex(): void
    {
        $this->callGet('/v2/books');
        $this->assertResponseStatus();
    }

    public function testBookCreate()
    {
        $resource = $this->newResource();

        // with author, ok
        $author = Author::first();
        $resource['data']['relationships']['author']['data'] = ['id' => $author->id, 'type' => 'authors'];
        unset($resource['data']['relationships']['serie']);
        $this->callPost('/v2/books', $resource);
        $this->assertResponseStatus(201);

        $result = json_decode($this->response->getContent(), true);
        $this->assertEquals($resource['data']['attributes']['title'], $result['data']['attributes']['title']);
        $this->assertEquals($resource['data']['relationships']['author']['data']['id'], $author->id);

        return $result['data']['id'];
    }

    public function testBookCreateWithoutRelatedAuthor(): void
    {
        $resource = $this->newResource();

        unset($resource['data']['relationships']['author']);
        $this->callPost('/v2/books', $resource);
        $this->assertResponseJsonApiError(null, 403);
    }

    /**
     * @depends testBookCreate
     */
    public function testBookUpdateWithoutRelatedAuthor($book_id): void
    {
        $resource = $this->newResource($book_id);

        $resource['data']['relationships']['author']['data'] = null;
        $this->callPatch('/v2/books/' . $book_id, $resource);
        $this->assertResponseJsonApiError(null, 403);
    }

    /**
     * @depends testBookCreate
     */
    public function testBookUpdateAddTwoRelatedStores($book_id): void
    {
        $resource = $this->newResource($book_id);

        // adding stores
        $resource['data']['relationships']['stores']['data'] = null;
        $stores = Store::take(2)->get();
        foreach ($stores as $store) {
            $resource['data']['relationships']['stores']['data'][$store->id] = ['id' => $store->id, 'type' => 'stores'];
        }

        // saving
        $this->callPatch('/v2/books/' . $book_id, $resource);
        $this->assertResponseStatus();

        // cheking saved data
        $result = json_decode($this->response->getContent(), true);
        $this->assertEquals(2, count($result['data']['relationships']['stores']['data']));
        $this->assertContains($result['data']['relationships']['stores']['data'][0]['id'], $stores->pluck('id'));
    }

    /**
     * @depends testBookCreate
     */
    public function testBookUpdateRemoveOneRelatedStore($book_id): void
    {
        $resource = $this->newResource($book_id);

        // adding stores
        $resource['data']['relationships']['stores']['data'] = null;
        $stores = Store::take(1)->get();
        foreach ($stores as $store) {
            $resource['data']['relationships']['stores']['data'][$store->id] = ['id' => $store->id, 'type' => 'stores'];
        }

        // saving
        $this->callPatch('/v2/books/' . $book_id, $resource);
        $this->assertResponseStatus();

        // cheking saved data
        $result = json_decode($this->response->getContent(), true);
        $this->assertEquals(1, count($result['data']['relationships']['stores']['data']));
        $this->assertContains($result['data']['relationships']['stores']['data'][0]['id'], $stores->pluck('id'));
    }

    /**
     * @depends testBookCreate
     */
    public function testBookUpdateRemoveAllRelatedStores($book_id): void
    {
        $resource = $this->newResource($book_id);

        // adding stores
        $resource['data']['relationships']['stores']['data'] = null;

        // saving
        $this->callPatch('/v2/books/' . $book_id, $resource);
        $this->assertResponseStatus();

        // cheking saved data
        $result = json_decode($this->response->getContent(), true);
        $this->assertEquals(0, count($result['data']['relationships']['stores']['data']));
    }
}

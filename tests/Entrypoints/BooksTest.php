<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests\Entrypoints;

use App\Author;
use App\Book;
use App\Chapter;
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
            'stores' => 'stores',
        ],
    ];

    public function testBookIndex(): void
    {
        $this->callGet('/v2/books');
        $this->assertResponseJsonApiCollection();
    }

    public function testBookCreate()
    {
        $resource = $this->newResource();

        // with author, ok
        $author = Author::firstOrFail();
        $resource['data']['relationships']['author']['data'] = ['id' => $author->id, 'type' => 'authors'];
        unset($resource['data']['relationships']['series']);
        $this->callPost('/v2/books', $resource);
        $this->assertResponseJsonApiCreated();

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($resource['data']['attributes']['title'], $result['data']['attributes']['title']);
        $this->assertSame($resource['data']['relationships']['author']['data']['id'], $author->id);

        return $result['data']['id'];
    }

    public function testBookCreateWithRelationshipHasMany()
    {
        $resource = $this->newResource();

        // with author, ok
        $author = Author::firstOrFail();
        $resource['data']['relationships']['author']['data'] = ['id' => $author->id, 'type' => 'authors'];

        // with chapters
        $chapters_id = Chapter::inRandomOrder()->take(2)->pluck('id')->toArray();
        foreach ($chapters_id as $chapter_id) {
            $resource['data']['relationships']['chapters']['data'][] = ['type' => 'chapters', 'id' => $chapter_id];
        }

        unset($resource['data']['relationships']['series']);

        $this->callPost('/v2/books', $resource);
        $this->assertResponseJsonApiCreated();

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($resource['data']['attributes']['title'], $result['data']['attributes']['title']);
        $this->assertSame($resource['data']['relationships']['author']['data']['id'], $author->id);

        return $result['data']['id'];
    }

    public function testBookCreateWithoutRelatedAuthor(): void
    {
        $resource = $this->newResource();

        unset($resource['data']['relationships']['author']);
        $this->callPost('/v2/books', $resource);
        $this->assertResponseJsonApiError('author id field is required', 403);
    }

    /**
     * @depends testBookCreate
     */
    public function testBookUpdateWithoutRelatedAuthor($book_id): void
    {
        $resource = $this->newResource($book_id);

        $resource['data']['relationships']['author']['data'] = null;
        $this->callPatch('/v2/books/' . $book_id, $resource);
        $this->assertResponseJsonApiError('author id field is required', 403);
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
        $this->assertResponseJsonApiResource();

        // checking saved data
        $result = json_decode($this->response->getContent(), true);
        $this->assertCount(2, $result['data']['relationships']['stores']['data']);
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
        $this->assertResponseJsonApiResource();

        // cheking saved data
        $result = json_decode($this->response->getContent(), true);
        $this->assertCount(1, $result['data']['relationships']['stores']['data']);
        $this->assertContains($result['data']['relationships']['stores']['data'][0]['id'], $stores->pluck('id'));
    }

    /**
     * @depends testBookCreate
     */
    public function testBookGetIncludedStoreData(): void
    {
        $book_id = 1;
        $this->callGet('/v2/books/' . $book_id . '?include=author');
        $this->assertResponseJsonApiResource();

        $result = json_decode($this->response->getContent(), true);

        // related author
        $author_id = $result['data']['relationships']['author']['data']['id'];
        $this->assertGreaterThan(0, $author_id);

        // checking included data
        $this->assertSame($result['included'][0]['type'], 'authors');
        $this->assertSame($result['included'][0]['id'], $author_id);
        $this->assertNotEmpty($result['included'][0]['attributes']['name']);
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
        $this->assertResponseJsonApiResource();

        // checking saved data
        $result = json_decode($this->response->getContent(), true);
        $this->assertCount(0, $result['data']['relationships']['stores']['data']);
    }
}

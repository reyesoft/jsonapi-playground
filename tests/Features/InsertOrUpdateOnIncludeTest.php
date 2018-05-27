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

class InsertOrUpdateOnIncludeTest extends BaseTestCase
{
    protected function getData(string $book_id = null): array
    {
        $this->callGet('/v2/books/' . ($book_id ?? 1) . '?include=author');

        return json_decode($this->response->getContent(), true);
    }

    public function testBookCreateAndIncludedCreateAuthor(): string
    {
        $result = $this->getData();
        $book_data = $result['data'];
        $author_data = $result['included'][0];

        $author_data['id'] = 'new_1111';
        $author_data['attributes']['name'] = md5($author_data['attributes']['name']);

        $book_data['id'] = '';
        $book_data['relationships']['author']['data']['id'] = $author_data['id'];

        $resource = [
            'data' => $book_data,
            'included' => [$author_data],
        ];

        $this->callPost('/v2/books' . '?include=author', $resource);
        $this->assertResponseStatus(201);

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($author_data['attributes']['name'], $result['included'][0]['attributes']['name']);
        $this->assertSame($result['data']['relationships']['author']['data']['id'], $result['included'][0]['id']);

        return $result['data']['id'];
    }

    /**
     * @depends testBookCreateAndIncludedCreateAuthor
     */
    public function testBookCreateAndIncludedUpdateAuthor($book_id): void
    {
        $result = $this->getData($book_id);
        $book_data = $result['data'];
        $author_data = $result['included'][0];

        $author_data['attributes']['name'] = md5($author_data['attributes']['name']);

        $book_data['id'] = '';

        $resource = [
            'data' => $book_data,
            'included' => [$author_data],
        ];

        $this->callPost('/v2/books' . '?include=author', $resource);
        $this->assertResponseStatus(201);

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($author_data['attributes']['name'], $result['included'][0]['attributes']['name']);
        $this->assertSame($result['data']['relationships']['author']['data']['id'], $result['included'][0]['id']);
    }

    /**
     * @depends testBookCreateAndIncludedCreateAuthor
     */
    public function testBookUpdateAndIncludedCreateAuthor($book_id): void
    {
        $result = $this->getData($book_id);
        $book_data = $result['data'];
        $author_data = $result['included'][0];

        $author_data['id'] = 'new_1111';
        $author_data['attributes']['name'] = md5($author_data['attributes']['name']);

        $book_data['relationships']['author']['data']['id'] = $author_data['id'];

        $resource = [
            'data' => $book_data,
            'included' => [$author_data],
        ];

        $this->callPatch('/v2/books/' . $book_id . '?include=author', $resource);
        $this->assertResponseStatus(200);

        $result = json_decode($this->response->getContent(), true);
        $this->assertNotSame($author_data['id'], $result['included'][0]['id']);
        $this->assertSame($author_data['attributes']['name'], $result['included'][0]['attributes']['name']);
        $this->assertSame($result['data']['relationships']['author']['data']['id'], $result['included'][0]['id']);
    }

    /**
     * @depends testBookCreateAndIncludedCreateAuthor
     */
    public function testBookUpdateAndIncludedUpdateAuthor($book_id): void
    {
        $result = $this->getData($book_id);
        $book_data = $result['data'];
        $author_data = $result['included'][0];

        $author_data['attributes']['name'] = md5($author_data['attributes']['name']);

        $resource = [
            'data' => $book_data,
            'included' => [$author_data],
        ];

        $this->callPatch('/v2/books/' . $book_id . '?include=author', $resource);
        $this->assertResponseStatus(200);

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($author_data['id'], $result['included'][0]['id']);
        $this->assertSame($author_data['attributes']['name'], $result['included'][0]['attributes']['name']);
        $this->assertSame($result['data']['relationships']['author']['data']['id'], $result['included'][0]['id']);
    }

    public function testCreateBookWithInvalidIncluded(): void
    {
        /*
         * @todo
         * Update a book with wrong data on include.
         * and check if book was not updated (rollback)
         */
        $this->assertTrue(true);
    }
}

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

class AuthorsTest extends BaseTestCase
{
    protected $layout = [
        'model' => Author::class,
        'type' => 'authors',
        'attributes' => [
            'name',
            'date_of_birth',
            'date_of_death',
        ],
        'relationships' => [
            'photos' => 'photos',
            'books' => 'books',
        ],
    ];

    public function testAuthorIndex(): void
    {
        $this->callGet('/v2/authors/');
        $this->assertResponseStatus();
    }

    public function testAuthorCreate()
    {
        $resource = $this->newResource();
        $this->callPost('/v2/authors/', $resource);
        $this->assertResponseStatus(201);

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($resource['data']['attributes']['name'], $result['data']['attributes']['name']);

        return $result['data']['id'];
    }

    /**
     * @depends testAuthorCreate
     */
    public function testAuthorGet($author_id): void
    {
        $this->callGet('/v2/authors/' . $author_id);
        $this->assertResponseStatus(200);

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($result['data']['id'], $author_id);
    }

    /**
     * @depends testAuthorCreate
     */
    public function testAuthorUpdate($author_id): void
    {
        $resource = $this->newResource($author_id);
        $this->callPatch('/v2/authors/' . $author_id, $resource);
        $this->assertResponseStatus(200);

        $result = json_decode($this->response->getContent(), true);
        $this->assertSame($resource['data']['attributes']['name'], $result['data']['attributes']['name']);
    }

    /**
     * @depends testAuthorCreate
     */
    public function testAuthorDelete($author_id): void
    {
        Author::findOrFail($author_id);

        $this->callDelete('/v2/authors/' . $author_id);
        $this->assertResponseStatus(200);

        $this->expectException(\Exception::class);
        Author::findOrFail($author_id);
    }
}

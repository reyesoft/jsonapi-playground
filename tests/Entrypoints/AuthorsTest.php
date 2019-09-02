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
            'birthplace',
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
        $this->assertResponseJsonApiCollection();
    }

    public function testAuthorCreate()
    {
        $resource = $this->newResource();
        $this->callPost('/v2/authors/', $resource);
        $this->assertResponseJsonApiCreated();

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
        $this->assertResponseJsonApiResource();

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
        $this->assertResponseJsonApiResource();

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
        $this->assertResponseJsonApiDeleted();

        $this->expectException(\Exception::class);
        Author::findOrFail($author_id);
    }

    /**
     * Business rule: chapter 1 cant be removed.
     */
    public function testChapter1CantBeRemoved(): void
    {
        $this->callDelete('/v2/authors/1');
        $this->assertResponseJsonApiError('Policy exception');
    }
}

<?php

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

    public function testAuthorIndex()
    {
        $this->callGet('/v2/authors/');
        $this->assertResponseOk();
    }

    public function testAuthorCreate()
    {
        $resource = $this->newResource();
        $this->callPost('/v2/authors/', $resource);
        $this->assertResponseStatus(201);

        $result = json_decode($this->response->getContent(), true);
        $this->assertEquals($resource['data']['attributes']['name'], $result['data']['attributes']['name']);

        return $result['data']['id'];
    }

    /**
     * @depends testAuthorCreate
     */
    public function testAuthorGet($author_id)
    {
        $this->callGet('/v2/authors/' . $author_id);
        $this->assertResponseStatus(200);

        $result = json_decode($this->response->getContent(), true);
        $this->assertEquals($result['data']['id'], $author_id);
    }

    /**
     * @depends testAuthorCreate
     */
    public function testAuthorUpdate($author_id)
    {
        $resource = $this->newResource($author_id);
        $this->callPatch('/v2/authors/' . $author_id, $resource);
        $this->assertResponseStatus(200);

        $result = json_decode($this->response->getContent(), true);
        $this->assertEquals($resource['data']['attributes']['name'], $result['data']['attributes']['name']);
    }

    /**
     * @depends testAuthorCreate
     */
    public function testAuthorDelete($author_id)
    {
        Author::findOrFail($author_id);

        $this->callDelete('/v2/authors/' . $author_id);
        $this->assertResponseStatus(200);

        $this->expectException(\Exception::class);
        Author::findOrFail($author_id);
    }
}

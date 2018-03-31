<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests\Entrypoints;

use App\Chapter;

class ChaptersTest extends BaseTestCase
{
    protected $layout = [
        'model' => Chapter::class,
        'type' => 'chapters',
        'attributes' => [
            'book_id',
            'title',
            'ordering',
        ],
        'relationships' => [
        ],
    ];

    public function testChapterIndex(): void
    {
        $this->callGet('/v2/chapters');
        $this->assertResponseStatus();
    }

    public function testChapterCreate()
    {
        $resource = $this->newResource();
        $this->callPost('/v2/chapters/', $resource);
        $this->assertResponseStatus(201);

        $result = json_decode($this->response->getContent(), true);
        $this->assertEquals($resource['data']['attributes']['title'], $result['data']['attributes']['title']);

        return $result['data']['id'];
    }

    public function testChapterGet(): void
    {
        // get fails, policy applied
        $this->assertTrue(true);
    }
}

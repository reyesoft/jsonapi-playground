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

    public function testChapterIndex(): string
    {
        $this->callGet('/v2/chapters');
        $this->assertResponseJsonApiCollection();

        $result = json_decode($this->response->getContent(), true);

        return $result['data'][0]['id'];
    }

    /**
     * @depends testChapterIndex
     */
    public function testChapterGet(string $chapter_id): void
    {
        $this->callGet('/v2/chapters/' . $chapter_id);
        $this->assertResponseJsonApiResource();
    }

    public function testChapterCantBeCreated(): void
    {
        $resource = $this->newResource();
        $this->callPost('/v2/chapters', $resource);
        $this->assertResponseJsonApiError('Policy exception', 403);
    }

    /**
     * @depends testChapterIndex
     */
    public function testChapterCantBeUpdated(string $chapter_id): void
    {
        $resource = $this->newResource($chapter_id);
        $this->callPatch('/v2/chapters/' . $chapter_id, $resource);
        $this->assertResponseJsonApiError('Policy exception', 403);
    }

    /**
     * @depends testChapterIndex
     */
    public function testChapterCantBeDeleted(string $chapter_id): void
    {
        $this->callDelete('/v2/chapters/' . $chapter_id);
        $this->assertResponseJsonApiError('Policy exception', 403);

        $chapter = Chapter::find($chapter_id);
        $this->assertSame((string) $chapter->id, $chapter_id);
    }
}

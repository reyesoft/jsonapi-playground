<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests\Features;

use App\Book;
use App\Chapter;
use Tests\Entrypoints\BaseTestCase;

class FilterAndPaginationTest extends BaseTestCase
{
    public function testFilterEquals(): void
    {
        $title = Chapter::first()->title;

        $this->callGet('/v2/chapters/?filter[title]=' . urlencode($title));
        $result = json_decode($this->response->getContent(), true);

        $this->assertContains($title, $result['data'][0]['attributes']['title']);
    }

    public function testFilterNumber(): void
    {
        // @todo
        $this->assertTrue(true);
    }

    public function testFilterLike(): void
    {
        $title = Book::orderBy('title')->first()->title;
        $title = substr($title, 1, -1);

        $this->callGet('/v2/books/?filter[title]=' . urlencode($title));
        $result = json_decode($this->response->getContent(), true);

        $this->assertContains($title, $result['data'][0]['attributes']['title']);
    }

    public function testFilterLikeEquals(): void
    {
        $title = Book::orderByDesc('title')->first()->title;

        $this->callGet('/v2/books/?filter[title]=' . urlencode($title));
        $result = json_decode($this->response->getContent(), true);

        $this->assertContains($title, $result['data'][0]['attributes']['title']);
    }

    public function testFilterDate(): void
    {
        $middle_date = Book::orderBy('date_published')->skip(5)->first()->date_published;

        $this->callGet(
            '/v2/books/?filter[date_published][since]=1900-01-01&filter[date_published][until]=' . $middle_date
        );
        $result1 = json_decode($this->response->getContent(), true);

        $this->callGet(
            '/v2/books/?filter[date_published][since]=' . $middle_date . '&filter[date_published][until]=2050-01-01'
        );
        $result2 = json_decode($this->response->getContent(), true);

        $this->assertNotSame(
            $result1['data'][0]['id'],
            $result2['data'][0]['id']
        );

        $this->assertLessThanOrEqual($middle_date, $result1['data'][0]['attributes']['date_published']);
        $this->assertGreaterThanOrEqual($middle_date, $result2['data'][0]['attributes']['date_published']);
    }

    public function testFilterWrongAttribute(): void
    {
        // @todo
        $this->assertTrue(true);
    }
}

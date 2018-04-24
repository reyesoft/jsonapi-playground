<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests\Features;

use Tests\Entrypoints\BaseTestCase;

class PoliciesOnIncludeTest extends BaseTestCase
{
    protected function getData(string $book_id = null): array
    {
        $this->callGet('/v2/books/' . ($book_id ?? 1) . '?include=author');

        return json_decode($this->response->getContent(), true);
    }

    public function testBookUpdateAndCreateNewAuthorIncludedBlockedByPolicy(): void
    {
        // @todo
        $this->assertTrue(true);
    }

    public function testBookUpdateAndEditExistentAuthorIncludedBlockedByPolicy(): void
    {
        // @todo
        $this->assertTrue(true);
    }
}

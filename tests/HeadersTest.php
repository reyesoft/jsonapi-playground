<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace tests;

class HeadersTest extends TestCase
{
    public function testIfResponseWithCorrectHeader(): void
    {
        foreach ($this->models as $resource => $value) {
            $response = $this->call('GET', 'v2/' . $resource);
            $this->assertEquals('application/vnd.api+json', $response->headers->get('content-type'));
        }
    }
}

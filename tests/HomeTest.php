<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests;

class HomeTest extends TestCase
{
    public function testHomeShowApplicationName(): void
    {
        $response = $this->get('/');
        $this->assertContains(config('app.name'), $response->getContent());
    }
}

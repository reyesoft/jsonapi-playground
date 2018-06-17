<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests\Entrypoints;

class CountriesTest extends BaseTestCase
{
    public function testCountryIndex(): void
    {
        $this->callGet('/v2/countries/');
        $this->assertResponseJsonApiCollection();
    }
}

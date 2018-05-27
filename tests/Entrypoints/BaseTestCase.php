<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests\Entrypoints;

use Reyesoft\JsonApi\Tests\TestJsonApiAssertionsTrait;
use Reyesoft\JsonApi\Tests\TestJsonApiLayoutTrait;
use Tests\TestCallsTrait;
use Tests\TestCase;

abstract class BaseTestCase extends TestCase
{
    use TestCallsTrait;
    use TestJsonApiLayoutTrait;
    use TestJsonApiAssertionsTrait;
}

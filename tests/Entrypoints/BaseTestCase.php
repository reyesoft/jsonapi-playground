<?php

namespace Tests\Entrypoints;

use App\JsonApi\Tests\TestJsonApiAssertionsTrait;
use App\JsonApi\Tests\TestJsonApiLayoutTrait;
use Tests\TestCallsTrait;
use Tests\TestCase;

abstract class BaseTestCase extends TestCase
{
    use TestCallsTrait;
    use TestJsonApiLayoutTrait;
    use TestJsonApiAssertionsTrait;
}

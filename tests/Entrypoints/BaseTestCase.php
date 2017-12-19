<?php

namespace Tests\Entrypoints;

use App\JsonApi\Tests\TestJsonApiLayoutTrait;
use App\JsonApi\Tests\TestJsonApiTrait;
use Tests\TestCallsTrait;
use Tests\TestCase;

abstract class BaseTestCase extends TestCase
{
    use TestCallsTrait;
    use TestJsonApiLayoutTrait;
    use TestJsonApiTrait;
}

<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi;

class Policy
{
    public function beforeAll(): bool
    {
        return true;
    }

    public function afterAll(): bool
    {
        return true;
    }

    public function beforeGet(): bool
    {
        return true;
    }

    public function afterGet(): bool
    {
        return true;
    }
}

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

    public function beforeGet(): bool
    {
        return true;
    }

    public function beforeRelated(): bool
    {
        return true;
    }

    public function beforeCreate(): bool
    {
        return true;
    }

    public function beforeUpdate(): bool
    {
        return true;
    }

    public function beforeDelete(): bool
    {
        return true;
    }
}

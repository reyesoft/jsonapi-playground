<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App;

use Reyesoft\JsonApi\Policy;

class ChapterPolicy extends Policy
{
    public function beforeCreate(): bool
    {
        return false;
    }

    public function beforeUpdate(): bool
    {
        return false;
    }

    public function beforeDelete(): bool
    {
        return false;
    }
}

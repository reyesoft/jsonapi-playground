<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Requests;

abstract class NoResourceRequest extends JsonApiRequest
{
    protected function readParams(array $params): void
    {
        $this->resource_type = end($params);
    }
}

<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Requests;

abstract class ResourceRequest extends JsonApiRequest
{
    protected $resource_id = '';

    protected function readParams(array $params): void
    {
        list($this->resource_type, $this->resource_id) = array_slice($params, -2);
    }

    public function getId(): string
    {
        return $this->resource_id;
    }
}

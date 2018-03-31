<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Services;

use App\JsonApi\Requests\JsonApiRequest;

abstract class DataService
{
    /**
     * @var JsonApiRequest
     */
    protected $jsonapirequest;

    public function __construct(JsonApiRequest $jsonapirequest)
    {
        $this->jsonapirequest = $jsonapirequest;
    }
}

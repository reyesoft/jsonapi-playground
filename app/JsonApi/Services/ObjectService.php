<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Services;

use App\JsonApi\Http\JsonApiRequestHelper;
use ArrayAccess;

abstract class ObjectService
{
    /**
     * @var JsonApiRequestHelper
     */
    protected $jsonapirequesthelper;

    public function __construct(JsonApiRequestHelper $jsonapirequesthelper)
    {
        $this->jsonapirequesthelper = $jsonapirequesthelper;
    }

    public function all(): array
    {
        return null;
    }

    public function get(string $id): ArrayAccess
    {
        return null;
    }
}

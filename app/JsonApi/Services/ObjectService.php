<?php

namespace App\JsonApi\Services;

use App\JsonApi\Http\JsonApiRequestHelper;
use ArrayAccess;

abstract class ObjectService
{
    /**
     * @var JsonApiRequestHelper
     */
    protected $jsonapirequesthelper;

    public function __construct(JsonApiRequestHelper $jsonapirequesthelper) {
        $this->jsonapirequesthelper = $jsonapirequesthelper;
    }

    public function all(): array {
        return null;
    }

    public function get(): ArrayAccess {
        return null;
    }
}

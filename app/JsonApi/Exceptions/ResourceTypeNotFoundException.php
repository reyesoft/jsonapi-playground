<?php

namespace App\JsonApi\Exceptions;

class ResourceTypeNotFoundException extends BaseException
{
    public function __construct(string $resource_type)
    {
        return $this->make(
                null,
                null,
                null,
                self::HTTP_CODE_TYPE_NOT_FOUND,
                "`${resource_type}` resource don't exist.",
                null,
                null
            );
    }
}

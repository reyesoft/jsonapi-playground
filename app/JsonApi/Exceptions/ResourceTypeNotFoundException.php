<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Exceptions;

use Neomerx\JsonApi\Document\Error;

class ResourceTypeNotFoundException extends BaseException
{
    public function __construct(string $resource_type)
    {
        // parent::__construct(new \Exception("`${resource_type}` resource don't exist."),
        // self::HTTP_CODE_TYPE_NOT_FOUND);
        parent::__construct(
                $this->createQueryError('AAAAAAAAAA', 'BBBBBBBBBB'),
                self::HTTP_CODE_TYPE_NOT_FOUND
        );
        /*
        return $this->make(
                null,
                null,
                null,
                self::HTTP_CODE_TYPE_NOT_FOUND,
                "`${resource_type}` resource don't exist.",
                null,
                null
            );
         */
    }

    private function createQueryError(string $name, string $title): Error
    {
        $source = [Error::SOURCE_PARAMETER => $name];
        $error = new Error(null, null, null, null, $title, null, $source);

        return $error;
    }
}

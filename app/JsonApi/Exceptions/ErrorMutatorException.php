<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class ErrorMutatorException extends BaseException
{
    public function __construct($exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $this->make(
                null, null, null,
                (string) self::HTTP_CODE_TYPE_NOT_FOUND,
                'Resource `' . implode(', ', $exception->getIds()) . '` not found.'
            );
        }

        throw $exception;
    }
}

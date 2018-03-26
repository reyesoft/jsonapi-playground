<?php

namespace App\JsonApi\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class ErrorMutatorException extends BaseException
{
    public function __construct($exception, $convert_all_to_jsonapi = true)
    {
        if ($exception instanceof ModelNotFoundException) {
            return $this->make(
                    null, null, null,
                    self::HTTP_CODE_TYPE_NOT_FOUND,
                    'Resource `' . implode(', ', $exception->getIds()) . '` not found.'
                );
        } elseif ($convert_all_to_jsonapi) {
            return $this->make(
                    null, null, null,
                    self::HTTP_CODE_BAD_REQUEST,
                    $exception->getMessage()
                );
        }

        throw $exception;
    }
}

<?php

namespace App\JsonApi\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorMutatorException extends BaseException
{
    /**
     * @param Exception|HttpException $exception
     *
     * @return type
     */
    public function __construct($exception, $also_convert = true)
    {
        if ($exception instanceof ModelNotFoundException)
        {
            return $this->make(
                    null, null, null,
                    self::HTTP_CODE_TYPE_NOT_FOUND,
                    'Resource `' . implode(', ', $exception->getIds()) . '` not found.'
                );
        }
        elseif ($also_convert)
        {
            return $this->make(
                    null, null, null,
                    self::HTTP_CODE_BAD_REQUEST,
                    $exception->getMessage()
                );
        }

        throw $exception;
    }
}

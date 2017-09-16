<?php

namespace App\JsonApi\Core;

trait JsonApiExceptionHandlerTrait
{
    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function renderException(\Exception $e)
    {
        // echo 'woooow! error JASONA';
        return false;
    }
}

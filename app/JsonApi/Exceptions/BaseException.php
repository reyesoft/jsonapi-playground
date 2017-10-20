<?php

namespace App\JsonApi\Exceptions;

use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;

abstract class BaseException extends JsonApiException
{
    const HTTP_CODE_TYPE_NOT_FOUND = 404;
    const HTTP_CODE_RESOURCE_NOT_FOUND = 404;
    const HTTP_CODE_UNAUTHORIZED = 404;

    public function make(
        $idx = null,
        LinkInterface $aboutLink = null,
        $status = null,
        $code = null,
        $title = null,
        $detail = null,
        array $source = null,
        $meta = null)
    {
        $error = new Error($idx, $aboutLink, $status, $code, $title, $detail, $source, $meta);

        return self::__construct($error);
    }
}

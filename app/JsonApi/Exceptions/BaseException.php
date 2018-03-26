<?php

namespace App\JsonApi\Exceptions;

use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;

class BaseException extends JsonApiException
{
    public const HTTP_CODE_TYPE_NOT_FOUND = 404;
    public const HTTP_CODE_RESOURCE_NOT_FOUND = 404;
    public const HTTP_CODE_UNAUTHORIZED = 404;

    public function __construct($errors, $defaultHttpCode = self::DEFAULT_HTTP_CODE, Exception $previous = null)
    {
        // $jsonapierrors = $this->mutateErrors($errors);
        // parent::__construct($jsonapierrors, $jsonapierrors->getHttpStatus($defaultHttpCode), $previous);

        parent::__construct($errors, $defaultHttpCode, $previous);
    }

    protected function mutateErrors($errors)
    {
        if ($errors instanceof self) {
            return $errors;
        } elseif ($errors instanceof ErrorInterface) {
            $errors = [$errors];
        } elseif ($errors instanceof ErrorCollection) {
            $errors = $errors->getArrayCopy();
        } elseif (!is_array($errors)) {
            throw new \Exception(
                'Errors is a ' . get_class($errors) . ' and can\'t be mutated, itn\'t a collection or array of errors.'
            );
        }

        return new self($errors);
    }

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

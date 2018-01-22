<?php

namespace App\JsonApi\Exceptions;

use Neomerx\JsonApi\Document\Error;

class ResourceValidationException extends BaseException
{
    public function __construct(array $errors)
    {
        $jsonapierrors = [];
        foreach ($errors as $attribute => $error) {
            $jsonapierrors[] = new Error(
                    null,
                    null,
                    self::HTTP_CODE_FORBIDDEN,
                    null,   // code
                    null,   // title
                    $error[0],
                    [
                       'pointer' => '/data/attributes/' . $attribute,
                       'attribute' => $attribute,
                    ]
                );
        }

        return parent::__construct($jsonapierrors, self::HTTP_CODE_FORBIDDEN);
    }
}

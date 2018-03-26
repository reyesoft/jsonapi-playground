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

class ResourceValidationException extends BaseException
{
    public function __construct(array $errors)
    {
        $jsonapierrors = [];
        foreach ($errors as $attribute => $error) {
            $jsonapierrors[] = new Error(
                    null,
                    null,
                    (string) self::HTTP_CODE_FORBIDDEN,
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

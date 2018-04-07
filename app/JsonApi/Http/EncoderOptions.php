<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Http;

use Neomerx\JsonApi\Encoder\EncoderOptions as NeomerxEncoderOptions;

class EncoderOptions extends NeomerxEncoderOptions
{
    public function __construct()
    {
        parent::__construct(
                JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE,
                '/v2');
    }
}

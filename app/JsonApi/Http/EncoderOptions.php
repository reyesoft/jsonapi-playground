<?php

namespace App\JsonApi\Http;

use Neomerx\JsonApi\Encoder\EncoderOptions as NeomerxEncoderOptions;

class EncoderOptions extends NeomerxEncoderOptions
{
    public function __construct() {
        parent::__construct(
                JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE,
                '/v2');
    }
}

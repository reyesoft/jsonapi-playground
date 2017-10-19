<?php

namespace App\JsonApi\Http;

use Psr\Http\Message\ServerRequestInterface;

class JsonApiRequest
{
    // public $request;

    public function __construct(ServerRequestInterface $request) {
        // $this->request = $request;
    }
}

<?php

namespace App\JsonApi\Core;

use Closure;

// use Psr\Http\Message\ServerRequestInterface;

class JsonApiMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}

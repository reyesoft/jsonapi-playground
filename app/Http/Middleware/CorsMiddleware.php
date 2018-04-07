<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Intercepts OPTIONS requests
        if ($request->isMethod('OPTIONS')) {
            $response = response('', 200);
        } else {
            $response = $next($request);
        }

        $this->addCorsHeaders($request, $response);

        return $response;
    }

    private function addCorsHeaders($request, &$response): void
    {
        $headers = [
            'Access-Control-Allow-Methods' => 'GET,POST,OPTIONS,DELETE,PATCH',
            'Access-Control-Allow-Headers' => $request->header('Access-Control-Request-Headers') === null ?
                    'Content-Type, X-Auth-Token, Origin, Authorization' :
                    $request->header('Access-Control-Request-Headers'),
            'Access-Control-Allow-Origin' => '*',
        ];

        if ($response instanceof \App\JsonApi\Http\JsonApiResponse) {
            foreach ($headers as $key => $value) {
                $response->withHeader($key, $value);
            }
        } else {
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
        }
    }
}

<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Http\Middleware;

use App\JsonApi\Exceptions\Handler as JsonApiExceptionHandler;
use App\JsonApi\Http\AppResponses;
use Closure;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Neomerx\JsonApi\Factories\Factory;
use Psr\Http\Message\ServerRequestInterface;

class JsonApiMiddleware
{
    public function __construct(ServerRequestInterface $request)
    {
        app()->singleton(
            AppResponses::class, function () use ($request) {
                return AppResponses::instance($request, []);
            }
        );

        app()->singleton(
            Factory::class, function () {
                return new Factory();
            }
        );
    }

    /**
     * Handle an incoming request.
     *
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->registerJsonApiResponses();
        $this->registerJsonApiExceptionHandler();

        $response = $next($request);

        return $response;
    }

    private function registerJsonApiResponses(): void
    {
        /* app()->singleton(ResponsesInterface, function () use ($previousHandler) {
            return new JsonApiExceptionHandler(app(), $previousHandler);
        });*/
    }

    private function registerJsonApiExceptionHandler(): void
    {
        $previousHandler = null;
        if (app()->bound(ExceptionHandler::class) === true) {
            $previousHandler = app()->make(ExceptionHandler::class);
        }
        app()->singleton(
            ExceptionHandler::class, function () use ($previousHandler) {
                return new JsonApiExceptionHandler(app(), $previousHandler);
            }
        );
    }
}

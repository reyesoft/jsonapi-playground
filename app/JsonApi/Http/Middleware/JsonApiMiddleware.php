<?php

namespace App\JsonApi\Http\Middleware;

use App\JsonApi\Exceptions\Handler as JsonApiExceptionHandler;
use App\JsonApi\Http\AppResponses;
use App\JsonApi\Http\JsonApiRequest;
use Closure;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Neomerx\JsonApi\Contracts\Http\ResponsesInterface;
use Neomerx\JsonApi\Factories\Factory;
use Psr\Http\Message\ServerRequestInterface;

class JsonApiMiddleware
{
    public function __construct(ServerRequestInterface $request)
    {
        app()->singleton(JsonApiRequest::class, function () use ($request) {
            return new JsonApiRequest($request);
        });

        app()->singleton(AppResponses::class, function () use ($request) {
            return AppResponses::instance($request, []);
        });

        app()->singleton(Factory::class, function () {
            return new Factory();
        });

        // create an instance of JsonApiRequest
        app()[JsonApiRequest::class];
        // app()[AppResponses::class];
        //app()[AppResponses::class];
    }

    /**
     * Handle an incoming request.
     *
     * @param  $request
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
        app()->singleton(ExceptionHandler::class, function () use ($previousHandler) {
            return new JsonApiExceptionHandler(app(), $previousHandler);
        });
    }
}

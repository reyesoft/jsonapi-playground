<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Http\Controllers;

use App\JsonApi\Core\RequestHandler;
use App\JsonApi\Requests\AllRequest;
use App\JsonApi\Requests\CreateRequest;
use App\JsonApi\Requests\DeleteRequest;
use App\JsonApi\Requests\GetRequest;
use App\JsonApi\Requests\RelatedRequest;
use App\JsonApi\Requests\UpdateRequest;
use Illuminate\Routing\Controller;
use Psr\Http\Message\ServerRequestInterface;

abstract class JsonApiGlobalController extends Controller
{
    /*
     * Available resources on this route controller
     * related with respective Schemas, like:
     *
     * const AVAILABLE_RESOURCES = [
     *    'books' => BookSchema::class,
     *    'photos' => BookSchema::class,
     * ];
     */
    public const AVAILABLE_RESOURCES = [];

    public function getCollection(ServerRequestInterface $request, ...$params)
    {
        $jsonapirequest = new AllRequest($request, $params, static::AVAILABLE_RESOURCES);
        $handler = new RequestHandler($jsonapirequest);

        return $handler->handle()->getResponse();
    }

    public function getRelatedCollection(ServerRequestInterface $request, ...$params)
    {
        $jsonapirequest = new RelatedRequest($request, $params, static::AVAILABLE_RESOURCES);
        $handler = new RequestHandler($jsonapirequest);

        return $handler->handle()->getResponse();
    }

    public function get(ServerRequestInterface $request, ...$params)
    {
        $jsonapirequest = new GetRequest($request, $params, static::AVAILABLE_RESOURCES);
        $handler = new RequestHandler($jsonapirequest);

        return $handler->handle()->getResponse();
    }

    public function create(ServerRequestInterface $request, ...$params)
    {
        $jsonapirequest = new CreateRequest($request, $params, static::AVAILABLE_RESOURCES);
        $handler = new RequestHandler($jsonapirequest);

        return $handler->handle()->getResponse();
    }

    public function update(ServerRequestInterface $request, ...$params)
    {
        $jsonapirequest = new UpdateRequest($request, $params, static::AVAILABLE_RESOURCES);
        $handler = new RequestHandler($jsonapirequest);

        return $handler->handle()->getResponse();
    }

    public function delete(ServerRequestInterface $request, ...$params)
    {
        $jsonapirequest = new DeleteRequest($request, $params, static::AVAILABLE_RESOURCES);
        $handler = new RequestHandler($jsonapirequest);

        return $handler->handle()->getResponse();
    }
}

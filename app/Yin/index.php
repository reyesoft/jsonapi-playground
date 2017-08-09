<?php
declare(strict_types=1);

require_once "../vendor/autoload.php";

use App\Yin\Book\Action\CreateBookAction;
use App\Yin\Book\Action\GetAuthorsOfBookAction;
use App\Yin\Book\Action\GetBookAction;
use App\Yin\Book\Action\GetBooksAction;
use App\Yin\Book\Action\GetBookRelationshipsAction;
use App\Yin\Book\Action\UpdateBookAction;
use App\Yin\Book\Action\UpdateBookRelationshipAction;
use App\Yin\User\Action\GetUserAction;
use App\Yin\User\Action\GetUserRelationshipsAction;
use App\Yin\User\Action\GetUsersAction;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\JsonApi;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

error_reporting(-1);
ini_set('display_errors', 'On');

// Defining routes
$routes = [
    "GET /books" => function (Request $request): Request {
        return $request
            ->withAttribute("action", GetBooksAction::class);
    },
    "GET /books/{id}" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", GetBookAction::class)
            ->withAttribute("id", $matches[1]);
    },
    "GET /books/{id}/relationships/{rel}" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", GetBookRelationshipsAction::class)
            ->withAttribute("id", $matches[1])
            ->withAttribute("rel", $matches[2]);
    },
    "GET /books/{id}/authors" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", GetAuthorsOfBookAction::class)
            ->withAttribute("id", $matches[1]);
    },
    "POST /books" => function (Request $request) {
        return $request
            ->withAttribute("action", CreateBookAction::class);
    },
    "PATCH /books/{id}" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", UpdateBookAction::class)
            ->withAttribute("id", $matches[1]);
    },
    "PATCH /books/{id}/relationships/{rel}" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", UpdateBookRelationshipAction::class)
            ->withAttribute("id", $matches[1])
            ->withAttribute("rel", $matches[2]);
    },

    "GET /users" => function (Request $request): Request {
        return $request
            ->withAttribute("action", GetUsersAction::class);
    },
    "GET /users/{id}" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", GetUserAction::class)
            ->withAttribute("id", $matches[1]);
    },
    "GET /users/{id}/relationships/{rel}" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", GetUserRelationshipsAction::class)
            ->withAttribute("id", $matches[1])
            ->withAttribute("rel", $matches[2]);
    },
];

// Find the current route
$exceptionFactory = new DefaultExceptionFactory();
$deserializer = new JsonDeserializer();
$request = new Request(ServerRequestFactory::fromGlobals(), $exceptionFactory, $deserializer);
$request = findRoute($request, $routes);

// Invoking the current action
$jsonApi = new JsonApi($request, new Response(), $exceptionFactory);
$action = $request->getAttribute("action");
$response = call_user_func(new $action(), $jsonApi);
$response = $response->withHeader("Access-Control-Allow-Origin", "*");

// Emitting the response
$emitter = new SapiEmitter();
$emitter->emit($response);

function findRoute(Request $request, array $routes): Request
{
    $queryParams = $request->getQueryParams();
    if (isset($queryParams["path"]) === false) {
        die("You must provide the 'path' query parameter!");
    }

    $method = $request->getMethod();
    $path = $queryParams["path"];
    $requestLine = $method . " " . $path;

    foreach ($routes as $pattern => $route) {
        $matches = [];
        $pattern = str_replace(
            ["{id}", "{rel}"],
            ["([A-Za-z0-9-]+)", "([A-Za-z0-9-]+)"],
            $pattern
        );
        if (preg_match("#^$pattern/{0,1}$#", $requestLine, $matches) === 1) {
            return $route($request, $matches);
        }
    }

    die("Resource not found!");
}

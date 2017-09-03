<?php

namespace App\Exceptions;

use App\Http\AppResponses;
use App\Http\EncoderOptions;
use App\JsonApi\Error;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Exceptions\JsonApiException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($request->is('v2/*')) {
            return $this->renderApiV2($request, $exception);
        }

        $json = [
            'success' => false,
            'error' => [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ],
        ];

        return response()->json($json, 400);
    }

    private function renderApiV2($request, Exception $exception) {
        $encodeOptions = new EncoderOptions();

        if ($exception instanceof JsonApiException) {
            $encoded_error = Encoder::instance([], $encodeOptions)->encodeErrors($exception->getErrors());
        } else {
            $error = new Error(
                $exception->getCode(),
                $exception->getMessage()
            );

            if (env('APP_DEBUG')) {
                $error->setMeta([
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]);
            }

            /* $responses = AppResponses::instance(
                [],
                $requestg
            );
            return $responses->getContentResponse($error); */
            $encoded_error = Encoder::instance([], $encodeOptions)->encodeError($error);
        }

        return response($encoded_error, $exception->getCode() + 400)
                ->header('Content-Type', 'application/vnd.api+json; charset=utf8');
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}

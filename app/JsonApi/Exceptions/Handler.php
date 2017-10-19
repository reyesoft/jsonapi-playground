<?php

namespace App\JsonApi\Exceptions;

use App\JsonApi\Http\AppResponses;
use App\JsonApi\Http\EncoderOptions;
use Exception;
use Illuminate\Contracts\Container\Container as ContainerInterface;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerInterface;
use Illuminate\Http\Response;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler implements ExceptionHandlerInterface
{
    /**
     * @var ExceptionHandlerInterface|null
     */
    private $previous;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface        $container
     * @param ExceptionHandlerInterface $previous
     */
    public function __construct(ContainerInterface $container, ExceptionHandlerInterface $previous = null)
    {
        $this->previous = $previous;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function report(Exception $exception)
    {
        $this->previous === null ?: $this->previous->report($exception);
    }

    /**
     * {@inheritdoc}
     */
    public function render($request, Exception $exception)
    {
        /*if ($exception instanceof NotFoundHttpException)
        {
        $responses = app()[AppResponses::class];
                            $response = $responses->getErrorResponse('x', 404);
                            return $response;

        }*/

        if ($exception instanceof JsonApiException) {
            $response = $this->createResponse($exception);
        } else {
            $response = $this->previous === null ? null : $this->previous->render($request, $exception);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function renderForConsole($output, Exception $exception)
    {
        /* @var OutputInterface $output */
        $this->previous === null ?: $this->previous->renderForConsole($output, $exception);
    }

    /**
     * @param JsonApiException $exception
     *
     * @return Response
     */
    protected function createResponse(JsonApiException $exception)
    {
        /* @var $responses AppResponses */
        $responses = app()[AppResponses::class];
        $errors = $exception->getErrors();
        $code = $exception->getHttpCode();
        switch (count($errors)) {
            case 0:
                $response = $responses->getCodeResponse($code);
                break;
            case 1:
                $response = $responses->getErrorResponse($errors[0], $code);
                break;
            default:
                $response = $responses->getErrorResponse($errors, $code);
                break;
        }

        return $response;
    }

    private static function processJsonApiException(JsonApiException $exception): Response
    {
        $encodeOptions = new EncoderOptions();
        $encoded_error = Encoder::instance([], $encodeOptions)->encodeErrors($exception->getErrors());

        return response($encoded_error, $exception->getHttpCode())
                ->header('Content-Type', 'application/vnd.api+json; charset=utf8');
    }

    private static function processUnknownException(\Exception $exception)
    {
        $encodeOptions = new EncoderOptions();

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

        $encoded_error = Encoder::instance([], $encodeOptions)->encodeError($error);

        return response($encoded_error, $exception->getCode() + 400)
                ->header('Content-Type', 'application/vnd.api+json; charset=utf8');
    }
}

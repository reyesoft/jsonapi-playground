<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Exceptions;

use App\JsonApi\Http\AppResponses;
use Exception;
use Illuminate\Contracts\Container\Container as ContainerInterface;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerInterface;
use Illuminate\Http\Response;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Symfony\Component\Console\Output\OutputInterface;

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
    public function report(Exception $exception): void
    {
        $this->previous === null ?: $this->previous->report($exception);
    }

    /**
     * {@inheritdoc}
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof JsonApiException) {
            $response = $this->createResponse($exception);
        } elseif ($exception instanceof BaseException) {
            $jsonapiexception = new ErrorMutatorException($exception);
            $response = $this->createResponse($jsonapiexception);
        } else {
            try {
                $jsonapiexception = new ErrorMutatorException($exception);
                $response = $this->createResponse($jsonapiexception);
            } catch (Exception $e) {
                $response = $this->previous === null ? null : $this->previous->render($request, $exception);
            }
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function renderForConsole($output, Exception $exception): void
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
}

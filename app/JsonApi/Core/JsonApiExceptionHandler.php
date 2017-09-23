<?php

namespace App\JsonApi\Core;

use App\Http\EncoderOptions;
use Illuminate\Http\Response;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Exceptions\JsonApiException;

class JsonApiExceptionHandler
{
    public static function render(\Exception $exception): Response
    {
        if ($exception instanceof JsonApiException)
        {
            return self::processJsonApiException($exception);
        } else {
            // return self::processUnknownException($exception);
            throw $exception;  // throw the original exception
        }
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

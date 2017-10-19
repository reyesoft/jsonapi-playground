<?php

namespace App\JsonApi\Http;

use Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\InjectContentTypeTrait;
use Zend\Diactoros\Stream;

class JsonApiResponse extends Response
{
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    use InjectContentTypeTrait;

    public function __construct(string $content = null, int $status = 200, array $headers = [])
    {
        $headers2 = $this->injectContentType(MediaTypeInterface::JSON_API_MEDIA_TYPE, $headers);

        parent::__construct($this->createBody($content), $status, $headers2);
    }

    protected function createBody(string $content = null): StreamInterface
    {
        $body = new Stream('php://temp', 'wb+');

        if ($content !== null) {
            $body->write($content);
            $body->rewind();
        }

        return $body;
    }
}

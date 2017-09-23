<?php

namespace App\Http;

use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;
use Neomerx\JsonApi\Contracts\Http\Headers\SupportedExtensionsInterface;
use Neomerx\JsonApi\Contracts\Schema\ContainerInterface;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Neomerx\JsonApi\Factories\Factory;
use Neomerx\JsonApi\Http\Headers\MediaType;
use Neomerx\JsonApi\Http\Headers\SupportedExtensions;
use Neomerx\JsonApi\Http\Responses;
use Psr\Http\Message\ServerRequestInterface;

class AppResponses extends Responses
{
    private $parameters;
    private $encoder;
    private $outputMediaType;
    private $extensions;
    private $schemes;
    private $urlPrefix;
    private $factory;

    public static function instance(
        ServerRequestInterface $request,
        array $encoderArray,
        string $urlPrefix = null
    ): self {
        $encodeOptions = new EncoderOptions();

        $factory = new Factory();
        $parameters = $factory->createQueryParametersParser()->parse($request);
        $schemasContainer = $factory->createContainer($encoderArray);
        $encoder = $factory->createEncoder($schemasContainer, $encodeOptions);

        $responses = new static(
            new MediaType(MediaTypeInterface::JSON_API_TYPE, MediaTypeInterface::JSON_API_SUB_TYPE),
            new SupportedExtensions(),
            $encoder,
            $schemasContainer,
            $parameters,
            $urlPrefix,
            $factory
        );

        return $responses;
    }

    public function __construct(
        MediaTypeInterface $outputMediaType,
        SupportedExtensionsInterface $extensions,
        EncoderInterface $encoder,
        ContainerInterface $schemes,
        EncodingParametersInterface $parameters = null,
        string $urlPrefix = null,
        Factory $factory = null
    ) {
        $this->extensions = $extensions;
        $this->encoder = $encoder;
        $this->outputMediaType = $outputMediaType;
        $this->schemes = $schemes;
        $this->urlPrefix = $urlPrefix;
        $this->parameters = $parameters;
        $this->factory = $factory;
    }

    public function getParameters(): EncodingParameters {
        return $this->parameters;
    }

    protected function createResponse($content, $statusCode, array $headers)
    {
        return new JsonApiResponse($content, $statusCode, $headers);
    }

    protected function getEncoder()
    {
        return $this->encoder;
    }

    protected function getUrlPrefix()
    {
        return $this->urlPrefix;
    }

    protected function getEncodingParameters()
    {
        return $this->parameters;
    }

    public function getSchemaContainer()
    {
        return $this->schemes;
    }

    protected function getSupportedExtensions()
    {
        return $this->extensions;
    }

    protected function getMediaType()
    {
        return $this->outputMediaType;
    }

    public function getFactory(): Factory {
        return $this->factory;
    }
}

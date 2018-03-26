<?php

namespace App\JsonApi\Http;

use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;
use Neomerx\JsonApi\Contracts\Schema\ContainerInterface;
use Neomerx\JsonApi\Decoders\ArrayDecoder;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Neomerx\JsonApi\Factories\Factory;
use Neomerx\JsonApi\Http\BaseResponses;
use Neomerx\JsonApi\Http\Headers\MediaType;
use Psr\Http\Message\ServerRequestInterface;

// use Neomerx\JsonApi\Http\Request as RequestWrapper;

class AppResponses extends BaseResponses
{
    private $parameters;
    private $encoder;
    private $outputMediaType;
    private $extensions;
    private $schemes;
    private $urlPrefix;
    private $factory;

    private $codecMatcher = null;
    private $requestWrapper = null;
    private $request = null;

    public static function instance(
        ServerRequestInterface $request,
        array $encoderArray,
        string $urlPrefix = null
    ): self {
        $encodeOptions = self::getEncoderOptions();

        $factory = new Factory();
        $schemasContainer = $factory->createContainer($encoderArray);
        $encoder = $factory->createEncoder($schemasContainer, $encodeOptions);
        $parameters = $factory->createParametersAnalyzer(new EncodingParameters(), $schemasContainer);

        $responses = new static(
            new MediaType(MediaTypeInterface::JSON_API_TYPE, MediaTypeInterface::JSON_API_SUB_TYPE),
            null,
            $encoder,
            $schemasContainer,
            $parameters->getParameters(),
            $urlPrefix,
            $factory
        );

        return $responses;
    }

    public function __construct(
        MediaTypeInterface $outputMediaType,
        $extensions,
        EncoderInterface $encoder,
        ContainerInterface $schemes,
        EncodingParameters $parameters = null,
        string $urlPrefix = null,
        Factory $factory = null
    ) {
        $this->extensions = null;
        $this->outputMediaType = $outputMediaType;
        $this->urlPrefix = $urlPrefix;
        $this->parameters = $parameters;
        $this->factory = $factory;
        $this->setSchemesContainer($schemes);

        // $container = $factory->createContainer($schemes);
        $this->encoder = $factory->createEncoder($schemes);
    }

    public function setSchemesContainer(ContainerInterface $schemes): void
    {
        $this->schemes = $schemes;
    }

    public function getParameters(): EncodingParameters
    {
        return $this->parameters;
    }

    protected function createResponse(?string $content, int $statusCode, array $headers)
    {
        return new JsonApiResponse($content, $statusCode, $headers);
    }

    protected function getEncoder(): EncoderInterface
    {
        return $this->encoder;
    }

    protected function getUrlPrefix(): ?string
    {
        return $this->urlPrefix;
    }

    protected function getEncodingParameters(): ?EncodingParametersInterface
    {
        return $this->parameters;
    }

    public function getSchemaContainer(): ?ContainerInterface
    {
        return $this->schemes;
    }

    protected function getSupportedExtensions()
    {
        return $this->extensions;
    }

    protected function getMediaType(): MediaTypeInterface
    {
        return $this->outputMediaType;
    }

    public function getFactory(): Factory
    {
        return $this->factory;
    }

    /**
     * @return EncoderOptions
     */
    protected static function getEncoderOptions()
    {
        /*
        $config        = $this->getConfig();
        $schemaAndHost = $this->getRequest()->getSchemeAndHttpHost();
        $options       = $this->getValue($config, S::JSON, S::JSON_OPTIONS, S::JSON_OPTIONS_DEFAULT);
        $depth         = $this->getValue($config, S::JSON, S::JSON_DEPTH, S::JSON_DEPTH_DEFAULT);
        $urlPrefix     = $schemaAndHost . '/' . $this->getValue($config, S::JSON, S::JSON_URL_PREFIX, null);
        $this->encoderOptions = new EncoderOptions($options, $urlPrefix, $depth);
         */
        return new EncoderOptions(
            JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE,
            '/v2'
        );
    }

    /**
     * @return Closure
     */
    protected function getDecoderClosure()
    {
        return function () {
            return new ArrayDecoder();
        };
    }

    /**
     * @return ServerRequestInterface
     */
    protected function getRequest()
    {
        if ($this->request === null) {
            $this->request = app()->make(ServerRequestInterface::class);
        }

        return $this->request;
    }

    /**
     * @return RequestWrapper
     */
    protected function getRequestWrapper()
    {
        if ($this->requestWrapper === null) {
            $getMethod = function () {
                $method = $this->getRequest()->getMethod();

                return $method;
            };
            $getHeader = function ($name) {
                $header = $this->getRequest()->headers->get($name, null, false);

                return $header;
            };
            $getQueryParams = function () {
                $queryParams = $this->getRequest()->query->all();

                return $queryParams;
            };
            $this->requestWrapper = new RequestWrapper($getMethod, $getHeader, $getQueryParams);
        }

        return $this->requestWrapper;
    }
}

<?php

namespace App\JsonApi\Http;

use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;
use Neomerx\JsonApi\Contracts\Http\Headers\SupportedExtensionsInterface;
use Neomerx\JsonApi\Contracts\Schema\ContainerInterface;
use Neomerx\JsonApi\Decoders\ArrayDecoder;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Neomerx\JsonApi\Factories\Factory;
use Neomerx\JsonApi\Http\Headers\MediaType;
use Neomerx\JsonApi\Http\Headers\SupportedExtensions;
use Neomerx\JsonApi\Http\Responses;
use Psr\Http\Message\ServerRequestInterface;

// use Neomerx\JsonApi\Http\Request as RequestWrapper;

class AppResponses extends Responses
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
        $this->outputMediaType = $outputMediaType;
        $this->urlPrefix = $urlPrefix;
        $this->parameters = $parameters;
        $this->factory = $factory;

        $this->schemes = $schemes;
        $matcher = $this->getCodecMatcher();
        $headerParameters = $this->factory->createHeaderParametersParser()->parse($this->getRequest());
        $matcher->matchEncoder($headerParameters->getAcceptHeader());
        $this->encoder = $matcher->getEncoder();
    }

    public function setSchemesContainer(ContainerInterface $schemes) {
        $this->schemes = $schemes;
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

    /**
     * @return CodecMatcherInterface
     */
    protected function getCodecMatcher()
    {
        if ($this->codecMatcher === null) {
            $config = []; // $this->getConfig();
            $container = $this->getSchemaContainer();
            $factory = $this->getFactory();
            $matcher = $factory->createCodecMatcher();
            $decoderClosure = $this->getDecoderClosure();
            $encoderClosure = function () use ($factory, $container, $config) {
                $encoderOptions = $this->getEncoderOptions();
                $encoder = $factory->createEncoder($container, $encoderOptions);
                // $encoder->withJsonApiVersion('1.1.1.1');

                return $encoder;
            };
            $jsonApiType = $factory->createMediaType(
                MediaTypeInterface::JSON_API_TYPE,
                MediaTypeInterface::JSON_API_SUB_TYPE
            );
            $jsonApiTypeUtf8 = $factory->createMediaType(
                MediaTypeInterface::JSON_API_TYPE,
                MediaTypeInterface::JSON_API_SUB_TYPE,
                ['charset' => 'UTF-8']
            );
            $matcher->registerEncoder($jsonApiType, $encoderClosure);
            $matcher->registerDecoder($jsonApiType, $decoderClosure);
            $matcher->registerEncoder($jsonApiTypeUtf8, $encoderClosure);
            $matcher->registerDecoder($jsonApiTypeUtf8, $decoderClosure);
            $this->codecMatcher = $matcher;
        }

        return $this->codecMatcher;
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

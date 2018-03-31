<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi;

use App\JsonApi\Http\AppResponses;
use App\JsonApi\Requests\JsonApiRequest;
use App\JsonApi\Services\DataService;
use App\JsonApi\Services\EloquentDataService;
use Zend\Diactoros\Response;

class RequestHandler
{
    private $jsonapirequest;
    private $data;

    public function __construct(JsonApiRequest $jsonapirequest)
    {
        $this->jsonapirequest = $jsonapirequest;
    }

    public function handle(): self
    {
        //$this->handlePolicy();

        /*
        switch($method) {
            case 'create':
                $this->data = $service->create();
                break;
            default:
                $this->data =$service->{$method}();
        }
        */
        $this->data = $this->getService()
            ->{$this->jsonapirequest->getAction()}();

        //$this->handlePolicy();
        //$service = $schema->service ?? new EloquentDataService();

        //$this->handlePolicy();

        return $this;
    }

    /**
     * @return array|ArrayAccess
     */
    public function getData()
    {
        return $this->data;
    }

    protected function getService(): DataService
    {
        return new EloquentDataService($this->jsonapirequest);
    }

    public function getResponse(): Response
    {
        $responses = AppResponses::instance($this->jsonapirequest->getRequest(), $this->jsonapirequest->getEncoder());
        switch ($this->jsonapirequest->getRequest()->getMethod()) {
            case 'POST':    // create
                return $responses->getCreatedResponse($this->data);
            case 'DELETE':
                return $responses->getCodeResponse(200);
            default:
                return $responses->getContentResponse($this->data);
        }
    }
}

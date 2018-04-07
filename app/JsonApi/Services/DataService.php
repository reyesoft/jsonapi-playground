<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Services;

use App\JsonApi\Core\Action;
use App\JsonApi\Helpers\ObjectsBuilder;
use App\JsonApi\Requests\JsonApiRequest;

abstract class DataService
{
    /**
     * @var Action
     */
    protected $action;

    /**
     * @var JsonApiRequest
     */
    protected $jsonapirequest;

    public function __construct(Action $action, JsonApiRequest $jsonapirequest)
    {
        $this->action = $action;
        $this->jsonapirequest = $jsonapirequest;
    }

    protected function getObjectBuilder(): ObjectsBuilder
    {
        return new ObjectsBuilder(
            $this->action->getSchema(),
            $this->action->getSchema()->getModelInstance(),
            $this->action->getParameters()
        );
    }
}

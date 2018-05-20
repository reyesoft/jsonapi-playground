<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Core;

use App\JsonApi\Exceptions\ResourcePolicyException;
use App\JsonApi\Exceptions\WrongDataException;
use App\JsonApi\Http\AppResponses;
use App\JsonApi\Requests\JsonApiRequest;
use App\JsonApi\Services\DataService;
use App\JsonApi\Services\EloquentDataService;
use Zend\Diactoros\Response;

class RequestHandler
{
    /**
     * @var JsonApiRequest
     */
    private $jsonapirequest;
    private $data;

    public function __construct(JsonApiRequest $jsonapirequest)
    {
        $this->jsonapirequest = $jsonapirequest;
    }

    public function handle($action = null): self
    {
        $action = $action ?? $this->jsonapirequest->getAction();

        // filter attributes of received data
        if ($action->isSaving()) {
            $action->filterReceivedAttributesWithSchema();

            if ($this->jsonapirequest->hasIncludedData()) {
                $this->getService($action)->openTransaction();
                $this->handleSaveIncluded();
                $action->setData($this->jsonapirequest->getData());
            }
        }

        // check policy
        $policy = $action->getSchema()->getPolicy();
        if (!$policy->before() || !$policy->{'before' . ucfirst($action->getActionName()) }()) {
            throw new ResourcePolicyException($action->getActionName());
        }

        // send data to service
        $this->data = $this->getService($action)
            ->{ $action->getActionName() }();

        if ($action->isSaving() && $this->jsonapirequest->hasIncludedData()) {
            $this->getService($action)->closeTransaction();
        }

        return $this;
    }

    private function handleSaveIncluded(): void
    {
        $included = $this->jsonapirequest->getDataIncluded();
        // $new_ids = [type][old_value] = new_value
        $new_ids = [];

        foreach ($included as $resource) {
            $schema = $this->jsonapirequest->getAvailableSchemas()[$resource['type']];
            if (!$resource['id']) {
                throw new WrongDataException(
                    'Included resource type ' . $resource['type'] . ' with id `' . $resource['id'] . '`'
                );
            }
            $action_name = preg_match('/^new_.+$/', $resource['id']) ? 'create' : 'update';
            $action = new Action(
                $action_name,
                new $schema(),
                $resource['id'],
                ['data' => $resource],
                $this->jsonapirequest->getParameters() // is not necessary here
            );

            // @todo check policy
            //            $policy = $action->getSchema()->getPolicy();
            //            if (!$policy->{'before' . ucfirst($action->getActionName()) }()) {
            //                throw new ResourcePolicyException($action->getActionName());
            //            }

            // send data to service
            $data = $this->getService($action)
                ->{ $action->getActionName() }();
            //            dd($resource['id'], $data->id);

            $new_ids[$action->getSchema()->getResourceType()][$resource['id']] = $data->id;
        }
        $this->jsonapirequest->replaceIdOnRelationships($new_ids);
    }

    /**
     * @return array|\ArrayAccess
     */
    public function getData()
    {
        return $this->data;
    }

    protected function getService(Action $action): DataService
    {
        return new EloquentDataService($action, $this->jsonapirequest);
    }

    public function getResponse(): Response
    {
        $responses = AppResponses::instance(
            $this->jsonapirequest->getRequest(),
            $this->jsonapirequest->getEncoder()
        );
        switch ($this->jsonapirequest->getRequest()->getMethod()) {
            case 'POST': // create
                return $responses->getCreatedResponse($this->data);
            case 'DELETE':
                return $responses->getCodeResponse(200);
            default:
                return $responses->getContentResponse($this->data);
        }
    }
}

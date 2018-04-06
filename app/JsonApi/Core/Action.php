<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Core;

use App\JsonApi\Http\JsonApiParameters;

class Action
{
    private $action_name = '';
    private $schema;
    private $id;
    private $data;
    private $parameters;

    public function __construct(
        string $action_name,
        SchemaProvider $schema,
        string $id,
        array $data,
        JsonApiParameters $parameters
    ) {
        $this->action_name = strtolower($action_name);
        $this->schema = $schema;
        $this->id = $id;
        $this->data = $data;
        $this->parameters = $parameters;
    }

    public function getActionName(): string
    {
        return $this->action_name;
    }

    public function getSchema(): SchemaProvider
    {
        return $this->schema;
    }

    public function getParameters(): JsonApiParameters
    {
        return $this->parameters;
    }

    public function getCrudLetter(): string
    {
        switch ($this->action_name) {
            case 'create':
                return 'c';
            case 'all':
            case 'get':
                return 'r';
            case 'update':
                return 'u';
            case 'delete':
                return 'd';
        }

        throw new \Exception('Bad crud letter');
    }

    public function isSaving(): bool
    {
        // in_array($action->getActionName(), ['get', 'all', 'related', 'delete']);
        return in_array($this->getActionName(), ['create', 'update']);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function filterReceivedAttributesWithSchema(): void
    {
        $cru = $this->getActionName()[0];

        $this->data = $this->getSchema()::filterAttributesWithCru($this->getData(), $cru);
    }
}

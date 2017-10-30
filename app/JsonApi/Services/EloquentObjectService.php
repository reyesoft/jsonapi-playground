<?php

namespace App\JsonApi\Services;

use App\JsonApi\Helpers\ObjectsBuilder;
use ArrayAccess;

class EloquentObjectService extends ObjectService
{
    public function all(): array {
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($this->jsonapirequesthelper);

        return $objectbuilder->getObjects();
    }

    public function get(): ArrayAccess {
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($this->jsonapirequesthelper);

        return $objectbuilder->getObject($this->jsonapirequesthelper->getId());
    }
}

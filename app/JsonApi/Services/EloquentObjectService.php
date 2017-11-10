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

    public function allRelated($builder): array {
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($this->jsonapirequesthelper);
        $objectbuilder->buildEloquentBuilder($builder);

        return $objectbuilder->getObjects();
    }

    public function get($id): ArrayAccess {
        $objectbuilder = ObjectsBuilder::createViaJsonApiRequest($this->jsonapirequesthelper);

        return $objectbuilder->getObject($id);
    }
}

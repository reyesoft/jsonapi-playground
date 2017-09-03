<?php

namespace App\JsonApi\Schemas;

use App\JsonApi\SchemaProvider;

class StoreSchema extends SchemaProvider
{
    protected $resourceType = 'stores';

    public function getId($obj)
    {
        return $obj->id;
    }

    public function getAttributes($obj)
    {
        return [
            'name' => $obj->name,
        ];
    }

    public function getRelationships($obj, $isPrimary, array $includeList)
    {
        if ($isPrimary) {
            return [
                'books' => [self::DATA => $obj->books->toArrayObjects()],
                'photos' => [self::DATA => $obj->photos->toArrayObjects()],
            ];
        } else {
            return [];
        }
    }
}

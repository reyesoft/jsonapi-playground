<?php

namespace App\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class StoreSchema extends SchemaProvider
{
    protected $resourceType = 'stores';
    protected $selfSubUrl = '/stores';

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

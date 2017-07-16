<?php

namespace App\Neomerx\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class AuthorSchema extends SchemaProvider
{
    protected $resourceType = 'authors';
    protected $selfSubUrl = '/authors';

    public function getId($obj)
    {
        return $obj->id;
    }

    public function getAttributes($obj)
    {
        return [
            'name' => $obj->name,
            'date_of_birth' => $obj->date_of_birth,
            'date_of_death' => $obj->date_of_death,
        ];
    }

    public function getRelationships($obj, $isPrimary, array $includeList)
    {
        if ($isPrimary) {
            return [
                'photos' => [self::DATA => $obj->photos->toArrayObjects()],
            ];
        } else {
            return [];
        }

    }
}

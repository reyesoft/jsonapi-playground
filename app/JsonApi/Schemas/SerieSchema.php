<?php

namespace App\JsonApi\Schemas;

use App\JsonApi\SchemaProvider;

class SerieSchema extends SchemaProvider
{
    protected $resourceType = 'series';

    public function getId($obj)
    {
        return $obj->id;
    }

    public function getAttributes($obj)
    {
        return [
            'title' => $obj->title,
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

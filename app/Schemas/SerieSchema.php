<?php

namespace App\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class SerieSchema extends SchemaProvider
{
    protected $resourceType = 'series';
    protected $selfSubUrl = '/series';

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

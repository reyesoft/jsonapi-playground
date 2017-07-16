<?php

namespace App\Neomerx\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class ChapterSchema extends SchemaProvider
{
    protected $resourceType = 'chapters';
    protected $selfSubUrl = '/chapters';

    public function getId($obj)
    {
        return $obj->id;
    }

    public function getAttributes($obj)
    {
        return [
            'title' => $obj->title,
            'ordering' => $obj->ordering,
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

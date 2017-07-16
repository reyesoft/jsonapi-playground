<?php

namespace App\Neomerx\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class BookSchema extends SchemaProvider
{
    protected $resourceType = 'books';
    protected $selfSubUrl = '/books';

    public function getId($obj)
    {
        return $obj->id;
    }

    public function getAttributes($obj)
    {
        return [
            'date_published' => $obj->date_published,
            'title' => $obj->title,
        ];
    }

    public function getRelationships($obj, $isPrimary, array $includeList)
    {
        if ($isPrimary) {
            return [
                'author' => [self::DATA => $obj->author],
                'serie' => [self::DATA => $obj->serie],
                'chapters' => [self::DATA => $obj->chapters->toArrayObjects()],
                'photos' => [self::DATA => $obj->photos->toArrayObjects()],
                'stores' => [self::DATA => $obj->stores->toArrayObjects()],
            ];
        } else {
            return [];
        }
    }
}

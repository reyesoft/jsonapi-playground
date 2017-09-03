<?php

namespace App\JsonApi\Schemas;

use App\JsonApi\SchemaProvider;

class BookSchema extends SchemaProvider
{
    protected $resourceType = 'books';

    protected $filterBySchema = [
        'date_published' => [
            'type' => 'date',
        ],
    ];

    protected $relationshipsSchema = [
        'photos' => [
            'hasMany' => true,
        ],
        'author' => [
            'type' => 'authors',
            'hasMany' => false,
        ],
    ];

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

    public function getRelationships($object, $isPrimary, array $includeList)
    {
        if ($isPrimary) {
            return [
                'author' => $this->buildRelationship($object, $includeList, '\App\Author', 'author'),
                // 'author' => [self::DATA => $object->author],
                // 'serie' => [self::DATA => $object->serie],
                // 'chapters' => [self::DATA => $object->chapters->toArrayObjects()],
                'photos' => [self::DATA => $object->photos->toArrayObjects()],
                // 'stores' => [self::DATA => $obj->stores->toArrayObjects()],
            ];
        } else {
            return [];
        }
    }
}

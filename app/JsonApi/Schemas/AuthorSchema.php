<?php

namespace App\JsonApi\Schemas;

use App\JsonApi\SchemaProvider;

class AuthorSchema extends SchemaProvider
{
    protected $resourceType = 'authors';

    protected $filterBySchema = [
        'name' => [
            'type' => 'like',
        ],
    ];

    protected $relationshipsSchema = [
        'photos' => [
            'hasMany' => true,
        ],
        'books' => [
            'hasMany' => true,
        ],
    ];

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
                'books' => [self::DATA => $obj->books->toArrayObjects()],
            ];
        } else {
            return [];
        }
    }
}

<?php

namespace App\JsonApi\Schemas;

use App\Author;
use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Core\SchemaRelationsTrait;

class AuthorSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'authors';
    public static $model = Author::class;

    protected $filterBySchema = [
        'name' => [
            'type' => 'like',
        ],
    ];

    public $relationshipsSchema = [
        'photos' => [
            'schema' => PhotoSchema::class,
            'hasMany' => true,
        ],
        'books' => [
            'schema' => BookSchema::class,
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
}

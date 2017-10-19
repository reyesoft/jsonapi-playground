<?php

namespace App;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Core\SchemaRelationsTrait;

class ChapterSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'chapters';
    public static $model = Chapter::class;

    public $relationshipsSchema = [
        'photos' => [
            'schema' => PhotoSchema::class,
            'hasMany' => true,
        ],
        'books' => [
            'type' => 'books',
            'schema' => BookSchema::class,
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
            'title' => $obj->title,
            'ordering' => $obj->ordering,
        ];
    }
}

<?php

namespace App;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Core\SchemaRelationsTrait;

class SerieSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'series';
    public static $model = Serie::class;

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
            'title' => $obj->title,
        ];
    }
}

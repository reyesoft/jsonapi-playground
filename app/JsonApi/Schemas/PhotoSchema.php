<?php

namespace App\JsonApi\Schemas;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Core\SchemaRelationsTrait;
use App\Photo;

class PhotoSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'photos';
    public static $model = Photo::class;

    public $relationshipsSchema = [
    ];

    public function getId($obj)
    {
        return $obj->id;
    }

    public function getAttributes($obj)
    {
        return [
            'title' => $obj->title,
            'uri' => $obj->uri,
        ];
    }
}

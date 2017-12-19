<?php

namespace App;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Core\SchemaRelationsTrait;

class PhotoSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'photos';
    public static $model = Photo::class;

    protected static $relationships = [
    ];

    public function getAttributes($object)
    {
        return [
            'title' => $object->title,
            'uri' => $object->uri,
        ];
    }
}

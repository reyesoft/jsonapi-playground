<?php

namespace App\JsonApi\Schemas;

use App\JsonApi\SchemaProvider;

class PhotoSchema extends SchemaProvider
{
    protected $resourceType = 'photos';

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

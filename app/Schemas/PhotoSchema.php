<?php

namespace App\Schemas;

use Neomerx\JsonApi\Schema\SchemaProvider;

class PhotoSchema extends SchemaProvider
{
    protected $resourceType = 'photos';
    protected $selfSubUrl = '/photos';

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

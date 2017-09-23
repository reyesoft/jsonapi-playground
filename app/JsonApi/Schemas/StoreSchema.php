<?php

namespace App\JsonApi\Schemas;

use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Core\SchemaRelationsTrait;
use App\Store;

class StoreSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'stores';
    public static $model = Store::class;

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
        ];
    }
}

<?php

namespace App\JsonApi\Schemas;

use App\Book;
use App\JsonApi\Core\SchemaProvider;
use App\JsonApi\Core\SchemaRelationsTrait;

class BookSchema extends SchemaProvider
{
    use SchemaRelationsTrait;

    protected $resourceType = 'books';
    public static $model = Book::class;

    protected $filterBySchema = [
        'date_published' => [
            'type' => 'date',
        ],
    ];

    public $relationshipsSchema = [
        'photos' => [
            'schema' => PhotoSchema::class,
            'hasMany' => true,
        ],
        'author' => [
            'schema' => AuthorSchema::class,
            'type' => 'authors',
            'hasMany' => false,
        ],
        'stores' => [
            'schema' => StoreSchema::class,
            'hasMany' => true,
        ],
        'chapters' => [
            'schema' => ChapterSchema::class,
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
            'date_published' => $obj->date_published,
            'title' => $obj->title,
        ];
    }

    /*
    public function getRelationships($object, $isPrimary, array $includeList)
    {
        if ($isPrimary) {
            return [
                'author' => $this->buildRelationship($object, $includeList, '\App\Author', 'author'),
                'photos' => [self::DATA => $object->photos],
            ];
        } else {
            return [];
        }
    }
     */
}

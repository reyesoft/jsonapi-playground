<?php
declare(strict_types=1);

namespace App\Yin\Book\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class AuthorResourceTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $author
     * @return string
     */
    public function getType($author) : string
    {
        return "authors";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $author
     * @return string
     */
    public function getId($author) : string
    {
        return $author["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $author
     * @return array
     */
    public function getMeta($author) : array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $author
     * @return Links|null
     */
    public function getLinks($author)
    {
        return null;
    }

    /**
     * Provides information about the "attributes" member of the current resource.
     *
     * The method returns an array where the keys signify the attribute names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return the value of the corresponding attribute.
     *
     * @param array $author
     * @return array
     */
    public function getAttributes($author) : array
    {
        return [
            "name" => function (array $author) {
                return $author["name"];
            },
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $author
     * @return array
     */
    public function getDefaultIncludedRelationships($author) : array
    {
        return [];
    }

    /**
     * Provides information about the "relationships" member of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param array $author
     * @return array
     */
    public function getRelationships($author) : array
    {
        return [];
    }
}

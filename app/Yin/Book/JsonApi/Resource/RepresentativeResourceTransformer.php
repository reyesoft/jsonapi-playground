<?php
declare(strict_types=1);

namespace App\Yin\Book\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class RepresentativeResourceTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $representative
     */
    public function getType($representative): string
    {
        return "representatives";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $representative
     */
    public function getId($representative): string
    {
        return $representative["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $representative
     * @return array
     */
    public function getMeta($representative): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $representative
     * @return Links|null
     */
    public function getLinks($representative)
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
     * @param array $representative
     * @return callable[]
     */
    public function getAttributes($representative): array
    {
        return [
            "name" => function (array $representative) {
                return $representative["name"];
            },
            "email" => function (array $representative) {
                return $representative["email"];
            },
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $representative
     */
    public function getDefaultIncludedRelationships($representative): array
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
     * @param array $representative
     * @return callable[]
     */
    public function getRelationships($representative): array
    {
        return [];
    }
}

<?php
declare(strict_types=1);

namespace App\Yin\User\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class ContactResourceTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $contact
     */
    public function getType($contact): string
    {
        return "contacts";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $contact
     */
    public function getId($contact): string
    {
        return $contact["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $contact
     */
    public function getMeta($contact): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $contact
     * @return Links|null
     */
    public function getLinks($contact)
    {
        return Links::createWithoutBaseUri(
            [
                "self" => new Link("/?path=/contacts/" . $this->getId($contact))
            ]
        );
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $contact
     * @return array
     */
    public function getDefaultIncludedRelationships($contact): array
    {
        return [];
    }

    /**
     * Provides information about the "attributes" member of the current resource.
     *
     * The method returns an array where the keys signify the attribute names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return the value of the corresponding attribute.
     *
     * @param array $contact
     * @return callable[]
     */
    public function getAttributes($contact): array
    {
        return [
            $contact["type"] => function (array $contact) {
                return $contact["value"];
            },
        ];
    }

    /**
     * Provides information about the "relationships" member of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param array $contact
     * @return callable[]
     */
    public function getRelationships($contact): array
    {
        return [];
    }
}

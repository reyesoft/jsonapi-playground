<?php
declare(strict_types=1);

namespace App\Yin\User\Action;

use Psr\Http\Message\ResponseInterface;
use App\Yin\User\JsonApi\Document\UserDocument;
use App\Yin\User\JsonApi\Resource\ContactResourceTransformer;
use App\Yin\User\JsonApi\Resource\UserResourceTransformer;
use App\Yin\User\Repository\UserRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetUserRelationshipsAction
{
    public function __invoke(JsonApi $jsonApi): ResponseInterface
    {
        // Checking the "id" of the currently requested user
        $id = $jsonApi->getRequest()->getAttribute("id");

        // Checking the name of the currently requested relationship
        $relationshipName = $jsonApi->getRequest()->getAttribute("rel");

        // Retrieving a user domain object with an ID of $id
        $user = UserRepository::getUser($id);

        // Instantiating a book document
        $document = new UserDocument(new UserResourceTransformer(new ContactResourceTransformer()));

        // Responding with "200 Ok" status code along with the requested relationship document
        return $jsonApi->respond()->okWithRelationship($relationshipName, $document, $user);
    }
}

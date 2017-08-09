<?php
declare(strict_types=1);

namespace App\Yin\User\Action;

use Psr\Http\Message\ResponseInterface;
use App\Yin\User\JsonApi\Document\UserDocument;
use App\Yin\User\JsonApi\Resource\ContactResourceTransformer;
use App\Yin\User\JsonApi\Resource\UserResourceTransformer;
use App\Yin\User\Repository\UserRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetUserAction
{
    public function __invoke(JsonApi $jsonApi): ResponseInterface
    {
        // Checking the "id" of the currently requested user
        $id = $jsonApi->getRequest()->getAttribute("id");

        // Retrieving a user domain object with an ID of $id
        $user = UserRepository::getUser($id);

        // Instantiating a user document
        $document = new UserDocument(new UserResourceTransformer(new ContactResourceTransformer()));

        // Responding with "200 Ok" status code along with the user document
        return $jsonApi->respond()->ok($document, $user);
    }
}

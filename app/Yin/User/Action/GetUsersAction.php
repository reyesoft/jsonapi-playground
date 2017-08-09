<?php
declare(strict_types=1);

namespace App\Yin\User\Action;

use Psr\Http\Message\ResponseInterface;
use App\Yin\User\JsonApi\Document\UsersDocument;
use App\Yin\User\JsonApi\Resource\ContactResourceTransformer;
use App\Yin\User\JsonApi\Resource\UserResourceTransformer;
use App\Yin\User\Repository\UserRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetUsersAction
{
    public function __invoke(JsonApi $jsonApi): ResponseInterface
    {
        // Extracting pagination information from the request, page = 1, size = 10 if it is missing
        $pagination = $jsonApi->getRequest()->getPageBasedPagination(1, 10);

        // Retrieving a paginated collection of user domain objects
        $users = UserRepository::getUsers($pagination->getPage(), $pagination->getSize());

        // Instantiating a users document
        $document = new UsersDocument(new UserResourceTransformer(new ContactResourceTransformer()));

        // Responding with "200 Ok" status code along with the users document
        return $jsonApi->respond()->ok($document, $users);
    }
}

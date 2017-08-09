<?php
declare(strict_types=1);

namespace App\Yin\Book\Action;

use Psr\Http\Message\ResponseInterface;
use App\Yin\Book\JsonApi\Document\BookDocument;
use App\Yin\Book\JsonApi\Hydrator\BookHydator;
use App\Yin\Book\JsonApi\Resource\AuthorResourceTransformer;
use App\Yin\Book\JsonApi\Resource\BookResourceTransformer;
use App\Yin\Book\JsonApi\Resource\PublisherResourceTransformer;
use App\Yin\Book\JsonApi\Resource\RepresentativeResourceTransformer;
use App\Yin\Book\Repository\BookRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class UpdateBookRelationshipAction
{
    public function __invoke(JsonApi $jsonApi): ResponseInterface
    {
        // Checking the name of the currently requested relationship
        $relationshipName = $jsonApi->getRequest()->getAttribute("rel");

        // Retrieving a book domain object with an ID of $id
        $id = $jsonApi->getRequest()->getAttribute("id");
        $book = BookRepository::getBook($id);
        if ($book === null) {
            die("A book with an ID of '$id' can't be found!");
        }

        // Hydrating the retrieved book domain object from the request
        $book = $jsonApi->hydrateRelationship($relationshipName, new BookHydator(), $book);

        // Instantiating a book document
        $document = new BookDocument(
            new BookResourceTransformer(
                new AuthorResourceTransformer(),
                new PublisherResourceTransformer(
                    new RepresentativeResourceTransformer()
                )
            )
        );

        // Responding with "200 Ok" status code along with the book document
        return $jsonApi->respond()->ok($document, $book);
    }
}

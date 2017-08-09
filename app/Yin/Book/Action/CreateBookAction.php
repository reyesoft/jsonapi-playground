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
use WoohooLabs\Yin\JsonApi\JsonApi;

class CreateBookAction
{
    public function __invoke(JsonApi $jsonApi): ResponseInterface
    {
        // Hydrating a new book domain object from the request
        $book = $jsonApi->hydrate(new BookHydator(), []);

        // Instantiating a book document
        $document = new BookDocument(
            new BookResourceTransformer(
                new AuthorResourceTransformer(),
                new PublisherResourceTransformer(
                    new RepresentativeResourceTransformer()
                )
            )
        );

        // Responding with "201 Created" status code along with the book document
        return $jsonApi->respond()->created($document, $book);
    }
}

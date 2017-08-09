<?php
declare(strict_types=1);

namespace App\Yin\Book\Action;

use Psr\Http\Message\ResponseInterface;
use App\Yin\Book\JsonApi\Document\BooksDocument;
use App\Yin\Book\JsonApi\Resource\AuthorResourceTransformer;
use App\Yin\Book\JsonApi\Resource\BookResourceTransformer;
use App\Yin\Book\JsonApi\Resource\PublisherResourceTransformer;
use App\Yin\Book\JsonApi\Resource\RepresentativeResourceTransformer;
use App\Yin\Book\Repository\BookRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetBooksAction
{
    public function __invoke(JsonApi $jsonApi): ResponseInterface
    {
        // Extracting pagination information from the request, page = 1, size = 10 if it is missing
        $pagination = $jsonApi->getRequest()->getPageBasedPagination(1, 10);

        // Retrieving a paginated collection of Book domain objects
        $books = BookRepository::getBooks($pagination->getPage(), $pagination->getSize());

        // Instantiating a Books document
        $document = new BooksDocument(
            new BookResourceTransformer(
                new AuthorResourceTransformer(),
                new PublisherResourceTransformer(
                    new RepresentativeResourceTransformer()
                )
            )
        );

        // Responding with "200 Ok" status code along with the Books document
        return $jsonApi->respond()->ok($document, $books);
    }
}

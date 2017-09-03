<?php

namespace App\JsonApi;

use App\Author;
use App\Book;
use App\JsonApi\Schemas\AuthorSchema;
use App\JsonApi\Schemas\BookSchema;
use App\JsonApi\Schemas\PhotoSchema;
use App\Photo;

class EncoderArray
{
    const ENCODER_ARRAY = [
        Author::class => AuthorSchema::class,
        Photo::class => PhotoSchema::class,
        Book::class => BookSchema::class,
    ];
}

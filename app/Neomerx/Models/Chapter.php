<?php

namespace App\Neomerx\Models;

use App\Neomerx\Schemas\BookSchema;
use App\Neomerx\Schemas\ChapterSchema;
use App\Neomerx\Schemas\PhotoSchema;

class Chapter extends BaseModel
{

    /**
     * Posibles relaciones para el include de neomerx, no van todas, sólo las que me puede pedir frontend
     */
    const RELATIONS = [
        'books' => 'books',
        'photos' => 'photos',
    ];

    /**
     * Define el total de relaciones posibles, que luego se usara en la librería.
     */
    const ENCODER = [
        self::class => ChapterSchema::class,
        Photo::class => PhotoSchema::class,
        Book::class => BookSchema::class
    ];

    protected $fillable = [
        'book_id',
        'title',
        'ordering'
    ];

    /* BelongTo */

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /* MorphBy */

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }
}

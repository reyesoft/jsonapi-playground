<?php

namespace App;

use App\Schemas\BookSchema;
use App\Schemas\PhotoSchema;
use App\Schemas\SerieSchema;

class Serie extends BaseModel
{

    /**
     * Posibles relaciones para el include de neomerx, no van todas, sólo las que me puede pedir frontend
     */
    const RELATIONS = [
        'photos' => 'photos',
        'books' => 'books',
    ];

    /**
     * Define el total de relaciones posibles, que luego se usara en la librería.
     */
    const ENCODER = [
        self::class => SerieSchema::class,
        Photo::class => PhotoSchema::class,
        Book::class => BookSchema::class
    ];

    protected $fillable =[
        'title',
    ];

    /* MorphBy */

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    /* HasMany */

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}

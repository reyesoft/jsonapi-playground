<?php

namespace App\Neomerx\Models;

use App\Neomerx\Schemas\AuthorSchema;
use App\Neomerx\Schemas\BookSchema;
use App\Neomerx\Schemas\ChapterSchema;
use App\Neomerx\Schemas\PhotoSchema;
use App\Neomerx\Schemas\SerieSchema;
use App\Neomerx\Schemas\StoreSchema;

class Book extends BaseModel
{
    /**
     * Posibles relaciones para el include de neomerx, no van todas, sólo las que me puede pedir frontend.
     */
    const RELATIONS = [
        'author' => 'author',
        'serie' => 'serie',
        'chapters' => 'chapters',
        'stores' => 'stores',
        'photos' => 'photos',
    ];

    /**
     * Define el total de relaciones posibles, que luego se usara en la librería.
     */
    const ENCODER = [
        self::class => BookSchema::class,
        Author::class => AuthorSchema::class,
        Serie::class => SerieSchema::class,
        Chapter::class => ChapterSchema::class,
        Store::class => StoreSchema::class,
        Photo::class => PhotoSchema::class,
    ];

    protected $fillable = [
        'author_id',
        'serie_id',
        'date_published',
        'title',
    ];

    /* BelongTo */

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }

    /* HasMany */

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    /* BelongToMany */

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'book_store')
            ->withTimestamps();
    }

    /* MorphBy */

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }
}

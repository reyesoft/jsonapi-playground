<?php

namespace App\Neomerx\Models;

use App\Neomerx\Schemas\AuthorSchema;
use App\Neomerx\Schemas\PhotoSchema;

class Author extends BaseModel
{

    /**
     * Posibles relaciones para el include de neomerx, no van todas, sólo las que me puede pedir frontend
     */
    const RELATIONS = [
        'photos' => 'photos'
    ];

    /**
     * Define el total de relaciones posibles, que luego se usara en la librería.
     */
    const ENCODER = [
        self::class => AuthorSchema::class,
        Photo::class => PhotoSchema::class,
    ];

    protected $fillable = [
        'name',
        'date_of_birth',
        'date_of_death'
    ];

    /* MorphBy */

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

}

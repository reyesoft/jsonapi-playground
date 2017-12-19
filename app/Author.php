<?php

namespace App;

use App\Base\ElegantModel;

class Author extends ElegantModel
{
    protected $fillable = [
        'name',
        'date_of_birth',
        'date_of_death',
    ];

    protected $rules = [
        'name' => 'required',
        'date_of_birth' => '',
        'date_of_death' => '',
    ];

    /* HasMany */

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /* MorphBy */

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }
}

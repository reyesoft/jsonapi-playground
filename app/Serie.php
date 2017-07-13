<?php

namespace App;

class Serie extends BaseModel
{
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

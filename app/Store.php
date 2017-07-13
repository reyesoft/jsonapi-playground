<?php

namespace App;

class Store extends BaseModel
{
    /* MorphBy */

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    /* BelongToMany */

    public function books()
    {
        return $this->belongsToMany(Book::class,'book_store')
            ->withTimestamps();
    }
}

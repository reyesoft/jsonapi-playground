<?php

namespace App;

class Book extends BaseModel
{

    /* BelongTo */

    public function autor()
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

    /* MorphBy */

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    /* BelongToMany */

    public function stores()
    {
        return $this->belongsToMany(Store::class,'book_store')
            ->withTimestamps();
    }
}

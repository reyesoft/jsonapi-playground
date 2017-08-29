<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{

    protected $fillable = [
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

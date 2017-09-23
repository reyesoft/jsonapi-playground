<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'name',
        'date_of_birth',
        'date_of_death',
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

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{

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

<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Chapter.
 *
 * @property int $id
 * @property int $book_id
 * @property string|null $title
 * @property int|null $ordering
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \App\Book $book
 * @property \Illuminate\Database\Eloquent\Collection|\App\Photo[] $photos
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chapter whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chapter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chapter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chapter whereOrdering($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chapter whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chapter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Chapter extends Model
{
    protected $fillable = [
        'book_id',
        'title',
        'ordering',
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

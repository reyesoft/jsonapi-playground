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
 * App\Series.
 *
 * @property int $id
 * @property string|null $title
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Book[] $books
 * @property \Illuminate\Database\Eloquent\Collection|\App\Photo[] $photos
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Series whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Series whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Series whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Series whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Series extends Model
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

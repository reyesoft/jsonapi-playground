<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App;

use App\Base\EvaluatesRulesTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Book.
 *
 * @property int $id
 * @property int $author_id
 * @property int|null $series_id
 * @property int $isbn
 * @property string $date_published
 * @property string|null $title
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \App\Author $author
 * @property \Illuminate\Database\Eloquent\Collection|\App\Chapter[] $chapters
 * @property \Illuminate\Database\Eloquent\Collection|\App\Photo[] $photos
 * @property \App\Series|null $series
 * @property \Illuminate\Database\Eloquent\Collection|\App\Store[] $stores
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereDatePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Book extends Model
{
    use EvaluatesRulesTrait;

    protected $fillable = [
        'title',
        'date_published',
    ];

    protected static $rules = [
        'author_id' => 'required',
    ];

    /* BelongTo */

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    /* HasMany */

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    /* BelongToMany */

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'book_store')
            ->withTimestamps();
    }

    /* MorphBy */

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }
}

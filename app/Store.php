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
 * App\Store.
 *
 * @property int $id
 * @property string|null $name
 * @property string $address
 * @property string $private_data
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $created_by
 * @property \Illuminate\Database\Eloquent\Collection|\App\Book[] $books
 * @property \Illuminate\Database\Eloquent\Collection|\App\Photo[] $photos
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store wherePrivateData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Store extends Model
{
    protected $fillable = [
        'name',
        'address',
        'private_data',
        'created_by',
    ];

    /* MorphBy */

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    /* BelongToMany */

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_store')
            ->withTimestamps();
    }
}

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
 * App\Photo.
 *
 * @property int $id
 * @property int $photoable_id
 * @property string $photoable_type
 * @property string|null $title
 * @property string|null $uri
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Illuminate\Database\Eloquent\Model|\Eloquent $photoable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo wherePhotoableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo wherePhotoableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereUri($value)
 * @mixin \Eloquent
 */
class Photo extends Model
{
    protected $fillable = [
        'title',
        'uri',
    ];

    /**
     * Get all of the owning commentable models.
     */
    public function photoable()
    {
        return $this->morphTo();
    }
}

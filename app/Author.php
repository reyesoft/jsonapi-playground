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
 * App\Author.
 *
 * @property int $id
 * @property string $name
 * @property string $birthplace
 * @property string|null $date_of_birth
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereDateOfDeath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property string|null $date_of_death
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Book[] $books
 * @property \Illuminate\Database\Eloquent\Collection|\App\Photo[] $photos
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereBirthplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereCreatedAt($value)
 */
class Author extends Model
{
    use EvaluatesRulesTrait;

    protected $fillable = [
        'name',
        'birthplace',
        'date_of_birth',
        'date_of_death',
    ];

    protected static $rules = [
        'name' => 'required',
        'birthplace' => '',
        'date_of_birth' => '',
        'date_of_death' => '',
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

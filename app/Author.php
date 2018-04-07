<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App;

use App\Base\ElegantModel;

class Author extends ElegantModel
{
    protected $fillable = [
        'name',
        'date_of_birth',
        'date_of_death',
    ];

    protected $rules = [
        'name' => 'required',
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

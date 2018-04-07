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

class Book extends ElegantModel
{
    protected $fillable = [
        // 'author_id',
        // 'serie_id',
        'title',
        'date_published',
    ];

    protected $rules = [
        'author_id' => 'required',
    ];

    /* BelongTo */

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function serie()
    {
        return $this->belongsTo(Serie::class);
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

<?php

namespace App;

class Author extends BaseModel
{

    /* MorphBy */

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

}

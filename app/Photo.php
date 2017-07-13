<?php

namespace App;

class Photo extends BaseModel
{
    /**
     * Get all of the owning commentable models.
     */
    public function photoable()
    {
        return $this->morphTo();
    }
}

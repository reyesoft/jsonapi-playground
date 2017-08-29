<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

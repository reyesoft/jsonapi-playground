<?php

namespace App\Neomerx\Models;

class Photo extends BaseModel
{
    protected $fillable =[
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
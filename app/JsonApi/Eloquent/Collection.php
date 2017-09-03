<?php

namespace App\JsonApi\Eloquent;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class Collection extends EloquentCollection
{
    /**
     * Get the collection of items as a plain array of only first level.
     *
     * @return array
     */
    public function toArrayObjects()
    {
        return array_map(function ($value) {
            return $value;
        }, $this->items);
    }
}

<?php

namespace App\Neomerx\Models;

class Collection extends \Illuminate\Database\Eloquent\Collection
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

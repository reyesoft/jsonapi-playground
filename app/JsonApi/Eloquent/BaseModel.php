<?php

namespace App\JsonApi\Eloquent;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function newCollection(array $models = [])
    {
        return new Collection($models);
    }
}

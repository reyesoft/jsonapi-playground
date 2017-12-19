<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ElegantModel extends Model
{
    protected $fillable = [];

    protected $rules = [];

    protected $errors;

    public function validate($data)
    {
        $validator = Validator::make($data, $this->rules);
        if ($validator->fails())
        {
            $this->errors = $validator->errors();

            throw new ValidationException($validator);
        }

        return true;
    }

    public function save(array $options = [])
    {
        if($this->validate($this->attributes))
            return parent::save($options);

        return false;
    }

    public function errors()
    {
        return $this->errors;
    }
}

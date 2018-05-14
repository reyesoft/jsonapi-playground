<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\Base;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait EvaluatesRulesTrait
{
    /**
     * @deprecated
     */
    public $errors = [];

    public static function bootEvaluatesRulesTrait(): void
    {
        $rules = self::$rules;

        static::saving(function ($model) use ($rules) {
            $validator = Validator::make($model->attributes, $rules);
            if ($validator->fails()) {
                $model->errors = $validator->errors();

                throw new ValidationException($validator);
            }

            return true;
        });
    }

    /**
     * @deprecated
     */
    public function errors()
    {
        return $this->errors;
    }
}

<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Tests;

trait TestJsonApiLayoutTrait
{
    /* example for test
    protected $layout = [
        'model' => Physicalpos::class,
        'type' => 'physicalpos',
        'attributes' => [
            'number',
            'enabled',
        ],
        'relationships' => [
            'company' => 'company_id',
        ],
    ];
    */

    protected function newResource($model_instance = null): array
    {
        return $this->newResourceFromLayout($this->layout, $model_instance);
    }

    protected function newResourceFromLayout($layout, $model_instance = null): array
    {
        $builder = new JsonApiResourceBuilder($layout);

        return $builder->newResource($model_instance);
    }
}

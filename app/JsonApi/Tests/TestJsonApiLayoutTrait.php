<?php

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
        $builder = new JsonApiResourceBuilder($this->layout);

        return $builder->newResource($model_instance);
    }
}
